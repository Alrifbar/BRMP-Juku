<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class JournalController extends Controller
{
    private function generateJournalNoForDate(\DateTimeInterface $date) : string
    {
        $seq = Journal::whereDate('created_at', $date->format('Y-m-d'))->count() + 1;
        return 'JRN-' . $date->format('Ymd') . '-' . str_pad((string)$seq, 3, '0', STR_PAD_LEFT);
    }

    public function index(Request $request)
    {
        $userId = Session::get('user_id');
        $user = \App\Models\User::find($userId);
        $search = $request->input('search');

        $query = Journal::with(['user', 'admins'])
            ->where('user_id', $userId);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('uraian_pekerjaan', 'like', "%{$search}%")
                  ->orWhere('no', 'like', "%{$search}%");
            });
        }

        $journals = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
            
        // Get unread notifications for navbar
        $unreadNotifications = \App\Models\Notification::forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = \App\Models\Notification::forUser($userId)->unread()->count();

        return view('user.journals.index', compact('journals', 'user', 'unreadNotifications', 'unreadCount'));
    }

    public function create()
    {
        $userId = Session::get('user_id');
        $user = \App\Models\User::find($userId);
        $formattedNumber = $this->generateJournalNoForDate(now());
        
        // Get all admin users for selection
        $admins = \App\Models\User::where('is_admin', true)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Get unread notifications for navbar
        $unreadNotifications = \App\Models\Notification::forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = \App\Models\Notification::forUser($userId)->unread()->count();
            
        return view('user.journals.create', compact('formattedNumber', 'admins', 'user', 'unreadNotifications', 'unreadCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'uraian_pekerjaan' => 'required|string',
            'dokumen_pekerjaan_url' => 'nullable|url|max:2048',
            'dokumen_pekerjaan_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'admin_ids' => 'required|array',
            'admin_ids.*' => 'exists:users,id',
        ], [
            'dokumen_pekerjaan_url.max' => 'URL dokumen terlalu panjang. Maksimal 2048 karakter.',
            'dokumen_pekerjaan_url.url' => 'Format URL tidak valid. Gunakan format http:// atau https://',
            'dokumen_pekerjaan_file.max' => 'Ukuran file terlalu besar. Maksimal 10MB.',
            'admin_ids.required' => 'Pilih setidaknya satu admin.',
        ]);

        $userId = Session::get('user_id');
        $user = \App\Models\User::find($userId);

        $journalData = [
            'user_id' => $userId,
            'title' => $validated['judul'],
            'uraian_pekerjaan' => $validated['uraian_pekerjaan'],
            'tanggal' => now(),
        ];
        $journalData['no'] = $this->generateJournalNoForDate(now());

        if ($request->hasFile('dokumen_pekerjaan_file')) {
            $file = $request->file('dokumen_pekerjaan_file');
            $extension = $file->getClientOriginalExtension();
            $filename = 'journal_' . time() . '.' . $extension;
            $tempPath = $file->getRealPath();
            
            // Limit to ~1MB (1024KB) for compression target
            $targetSize = 1024 * 1024;
            
            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']) && function_exists('imagecreatefromjpeg')) {
                $image = null;
                if (in_array(strtolower($extension), ['png']) && function_exists('imagecreatefrompng')) {
                    $image = imagecreatefrompng($tempPath);
                    if ($image) {
                        imagepalettetotruecolor($image);
                        imagealphablending($image, true);
                        imagesavealpha($image, true);
                    }
                } elseif (in_array(strtolower($extension), ['webp']) && function_exists('imagecreatefromwebp')) {
                    $image = imagecreatefromwebp($tempPath);
                } else {
                    $image = imagecreatefromjpeg($tempPath);
                }

                if ($image) {
                    $quality = 85;
                    $dirPath = storage_path('app/public/journal-documents');
                    if (!file_exists($dirPath)) {
                        mkdir($dirPath, 0755, true);
                    }
                    
                    $compressedPath = $dirPath . '/' . $filename;
                    
                    // Progressive compression if file is larger than target
                    do {
                        if (strtolower($extension) == 'png') {
                            // PNG compression is 0-9 (9 is max compression)
                            $pngQuality = round((100 - $quality) / 10);
                            imagepng($image, $compressedPath, $pngQuality);
                        } elseif (strtolower($extension) == 'webp') {
                            imagewebp($image, $compressedPath, $quality);
                        } else {
                            imagejpeg($image, $compressedPath, $quality);
                        }
                        
                        $fileSize = filesize($compressedPath);
                        $quality -= 10;
                    } while ($fileSize > $targetSize && $quality > 10);
                    
                    imagedestroy($image);
                    $journalData['dokumen_pekerjaan'] = 'journal-documents/' . $filename;
                } else {
                    $path = $file->storeAs('journal-documents', $filename, 'public');
                    $journalData['dokumen_pekerjaan'] = $path;
                }
            } else {
                $path = $file->storeAs('journal-documents', $filename, 'public');
                $journalData['dokumen_pekerjaan'] = $path;
            }
        } elseif (!empty($validated['dokumen_pekerjaan_url'])) {
            $journalData['dokumen_pekerjaan'] = $validated['dokumen_pekerjaan_url'];
        }

        $journal = Journal::create($journalData);
        
        // Fix: Sync only selected admins from checkbox
        $journal->admins()->sync($validated['admin_ids']);

        // Kirim notifikasi agregasi ke semua admin yang dipilih
        foreach ($validated['admin_ids'] as $adminId) {
            // Agregasi per jam untuk admin terkait
            $hourStart = now()->startOfHour();
            $hourEnd = now()->endOfHour();

            $uniqueCount = Journal::whereHas('admins', function($q) use ($adminId) {
                        $q->where('admin_id', $adminId);
                    })
                    ->whereBetween('created_at', [$hourStart, $hourEnd])
                    ->distinct('user_id')
                    ->count('user_id');

            // Pesan: jika hanya satu orang di jam ini, tampilkan nama pegawai dan judul
            $message = $uniqueCount === 1 
                ? ('Pegawai ' . $user->name . " mengirimkan jurnal baru: '" . ($journal->title ?? 'Jurnal') . "'")
                : ($uniqueCount . ' pegawai telah mengirim jurnal');

            // Cari notifikasi batch di jam yang sama yang masih unread
            $existing = \App\Models\Notification::where('user_id', $adminId)
                ->where('type', 'new_journal_batch')
                ->where('read', false)
                ->whereBetween('created_at', [$hourStart, $hourEnd])
                ->first();
            if ($existing) {
                $existing->message = $message;
                $existing->journal_id = $journal->id;
                $existing->save();
            } else {
                \App\Models\Notification::create([
                    'user_id' => $adminId,
                    'type' => 'new_journal_batch',
                    'message' => $message,
                    'journal_id' => $journal->id,
                ]);
            }
        }

        return redirect()->route('user.journals.index')->with('success', 'Jurnal berhasil ditambahkan.');
    }

    public function bulkDelete(Request $request)
    {
        $userId = Session::get('user_id');
        $journalIds = json_decode($request->input('journal_ids', '[]'), true);

        if (empty($journalIds)) {
            return back()->with('error', 'Tidak ada jurnal yang dipilih.');
        }

        $journals = Journal::where('user_id', $userId)
            ->whereIn('id', $journalIds)
            ->get();

        foreach ($journals as $journal) {
            if ($journal->dokumen_pekerjaan && !str_starts_with($journal->dokumen_pekerjaan, 'http')) {
                if (str_starts_with($journal->dokumen_pekerjaan, 'uploads/')) {
                    $oldPath = public_path($journal->dokumen_pekerjaan);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                } else {
                    Storage::disk('public')->delete($journal->dokumen_pekerjaan);
                }
            }
            $journal->delete();
        }

        return redirect()->route('user.journals.index')->with('success', count($journals) . ' jurnal berhasil dihapus.');
    }

    public function show($id)
    {
        $userId = Session::get('user_id');
        $user = \App\Models\User::find($userId);
        
        $journal = Journal::with(['user', 'admins'])->findOrFail($id);

        if ($journal->user_id != $userId && !$journal->is_private) {
            // Allow viewing public journals
        } elseif ($journal->user_id != $userId) {
            abort(403);
        }

        // Get unread notifications for navbar
        $unreadNotifications = \App\Models\Notification::forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = \App\Models\Notification::forUser($userId)->unread()->count();

        return view('user.journals.show', compact('journal', 'user', 'unreadNotifications', 'unreadCount'));
    }

    public function edit(Journal $journal)
    {
        $userId = Session::get('user_id');
        if ((int) $journal->user_id !== (int) $userId) {
            abort(403);
        }
        return view('user.journals.edit', compact('journal'));
    }

    public function update(Request $request, Journal $journal)
    {
        $userId = Session::get('user_id');
        if ((int) $journal->user_id !== (int) $userId) {
            abort(403);
        }
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'uraian_pekerjaan' => 'required|string',
            'dokumen_pekerjaan_url' => 'nullable|url|max:2048',
            'dokumen_pekerjaan_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $journalData = [
            'title' => $validated['judul'],
            'uraian_pekerjaan' => $validated['uraian_pekerjaan'],
        ];

        if ($request->hasFile('dokumen_pekerjaan_file')) {
            $file = $request->file('dokumen_pekerjaan_file');
            $filename = 'journal_' . time() . '.' . $file->getClientOriginalExtension();

            if ($journal->dokumen_pekerjaan && !str_starts_with($journal->dokumen_pekerjaan, 'http')) {
                if (str_starts_with($journal->dokumen_pekerjaan, 'uploads/')) {
                    $oldPath = public_path($journal->dokumen_pekerjaan);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                } else {
                    Storage::disk('public')->delete($journal->dokumen_pekerjaan);
                }
            }

            $path = $file->storeAs('journal-documents', $filename, 'public');
            $journalData['dokumen_pekerjaan'] = $path;
        } elseif (!empty($validated['dokumen_pekerjaan_url'])) {
            $journalData['dokumen_pekerjaan'] = $validated['dokumen_pekerjaan_url'];
        } else {
            $journalData['dokumen_pekerjaan'] = $journal->dokumen_pekerjaan;
        }

        $journal->update($journalData);

        return redirect()->route('user.journals.index')->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function destroy(Journal $journal)
    {
        $userId = Session::get('user_id');
        if ((int) $journal->user_id !== (int) $userId) {
            abort(403);
        }

        if ($journal->dokumen_pekerjaan && !str_starts_with($journal->dokumen_pekerjaan, 'http')) {
            if (str_starts_with($journal->dokumen_pekerjaan, 'uploads/')) {
                $oldPath = public_path($journal->dokumen_pekerjaan);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            } else {
                Storage::disk('public')->delete($journal->dokumen_pekerjaan);
            }
        }

        $journal->delete();

        return redirect()->route('user.journals.index')
            ->with('success', 'Jurnal berhasil dihapus!');
    }
}
