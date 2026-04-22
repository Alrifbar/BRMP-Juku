<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class UserController extends Controller
{
    public function home()
    {
        $userId = Session::get('user_id');
        
        // Check if user is logged in
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }
        
        try {
            // Get user data
            $user = User::find($userId);
            
            // Get user's journals only
            $totalJournals = Journal::where('user_id', $userId)->count();
            $journalsThisMonth = Journal::where('user_id', $userId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            $journalsThisWeek = Journal::where('user_id', $userId)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count();
                
            // Journals received by admin
            $receivedJournals = Journal::where('user_id', $userId)
                ->where('received_by_admin', true)
                ->count();
                
            $pendingJournals = Journal::where('user_id', $userId)
                ->where('received_by_admin', false)
                ->count();
                
            $recentJournals = Journal::with('user')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
                
            $categories = Journal::where('user_id', $userId)
                ->select('category')
                ->whereNotNull('category')
                ->groupBy('category')
                ->pluck('category');
                
            // Daily stats (7 days) for current user
            $dailyRaw = Journal::leftJoin('journal_admin as ja', function($join){
                    $join->on('journals.id', '=', 'ja.journal_id')
                         ->where('ja.status', 'revised');
                })
                ->where('journals.user_id', $userId)
                ->where('journals.created_at', '>=', now()->subDays(6)->startOfDay())
                ->selectRaw("date(journals.created_at) as d")
                ->selectRaw("COUNT(DISTINCT journals.id) as total_count")
                ->selectRaw("SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count")
                ->selectRaw("COUNT(DISTINCT ja.journal_id) as revised_count")
                ->groupByRaw("date(journals.created_at)")
                ->orderBy('d','asc')
                ->get();
            $dates = collect(range(6,0))->map(fn($i)=> now()->subDays($i)->format('Y-m-d'));
            $dailyStats = $dates->map(function($day) use ($dailyRaw){
                $row = $dailyRaw->firstWhere('d', $day);
                return (object)[
                    'date' => $day,
                    'count' => (int)($row->total_count ?? 0),
                    'approved_count' => (int)($row->approved_count ?? 0),
                    'revised_count' => (int)($row->revised_count ?? 0),
                ];
            });

            // Weekly stats (4 weeks) for current user
            if (DB::connection()->getDriverName() === 'sqlite') {
                $weeklyStats = Journal::leftJoin('journal_admin as ja', function($join){
                        $join->on('journals.id', '=', 'ja.journal_id')
                             ->where('ja.status', 'revised');
                    })
                    ->where('journals.user_id', $userId)
                    ->where('journals.created_at', '>=', now()->subWeeks(4))
                    ->selectRaw("strftime('%Y-%W', journals.created_at) as week")
                    ->selectRaw("MIN(date(journals.created_at)) as week_start")
                    ->selectRaw("MAX(date(journals.created_at)) as week_end")
                    ->selectRaw("COUNT(DISTINCT journals.id) as count")
                    ->selectRaw("SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count")
                    ->selectRaw("COUNT(DISTINCT ja.journal_id) as revised_count")
                    ->groupByRaw("strftime('%Y-%W', journals.created_at)")
                    ->orderBy('week','desc')
                    ->get();
            } else {
                $weeklyStats = Journal::leftJoin('journal_admin as ja', function($join){
                        $join->on('journals.id', '=', 'ja.journal_id')
                             ->where('ja.status', 'revised');
                    })
                    ->where('journals.user_id', $userId)
                    ->where('journals.created_at', '>=', now()->subWeeks(4))
                    ->selectRaw("YEARWEEK(journals.created_at) as week")
                    ->selectRaw("MIN(DATE(journals.created_at)) as week_start")
                    ->selectRaw("MAX(DATE(journals.created_at)) as week_end")
                    ->selectRaw("COUNT(DISTINCT journals.id) as count")
                    ->selectRaw("SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count")
                    ->selectRaw("COUNT(DISTINCT ja.journal_id) as revised_count")
                    ->groupByRaw("YEARWEEK(journals.created_at)")
                    ->orderBy('week','desc')
                    ->get();
            }

            // Yearly stats (5 years window) for current user
            if (DB::connection()->getDriverName() === 'sqlite') {
                $yearlyRaw = Journal::leftJoin('journal_admin as ja', function($join){
                        $join->on('journals.id', '=', 'ja.journal_id')
                             ->where('ja.status', 'revised');
                    })
                    ->where('journals.user_id', $userId)
                    ->selectRaw("strftime('%Y', journals.created_at) as year")
                    ->selectRaw("COUNT(DISTINCT journals.id) as count")
                    ->selectRaw("SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count")
                    ->selectRaw("COUNT(DISTINCT ja.journal_id) as revised_count")
                    ->groupByRaw("strftime('%Y', journals.created_at)")
                    ->orderBy('year','asc')
                    ->get();
            } else {
                $yearlyRaw = Journal::leftJoin('journal_admin as ja', function($join){
                        $join->on('journals.id', '=', 'ja.journal_id')
                             ->where('ja.status', 'revised');
                    })
                    ->where('journals.user_id', $userId)
                    ->selectRaw("YEAR(journals.created_at) as year")
                    ->selectRaw("COUNT(DISTINCT journals.id) as count")
                    ->selectRaw("SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count")
                    ->selectRaw("COUNT(DISTINCT ja.journal_id) as revised_count")
                    ->groupByRaw("YEAR(journals.created_at)")
                    ->orderBy('year','asc')
                    ->get();
            }
            $currentYear = (int) now()->format('Y');
            $years = collect(range($currentYear - 4, $currentYear));
            $yearlyStats = $years->map(function($y) use ($yearlyRaw){
                $row = $yearlyRaw->firstWhere('year', (string)$y) ?? $yearlyRaw->firstWhere('year', $y);
                return (object)[
                    'year' => (string)$y,
                    'count' => (int)($row->count ?? 0),
                    'approved_count' => (int)($row->approved_count ?? 0),
                    'revised_count' => (int)($row->revised_count ?? 0),
                ];
            });

            // Get unread notifications
            $unreadNotifications = Notification::forUser($userId)
                ->unread()
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
                
            $unreadCount = Notification::forUser($userId)->unread()->count();
                
            return view('user.home', compact(
                'user',
                'totalJournals', 
                'journalsThisMonth', 
                'journalsThisWeek', 
                'receivedJournals',
                'pendingJournals',
                'recentJournals',
                'categories',
                'unreadNotifications',
                'unreadCount',
                'dailyStats',
                'weeklyStats',
                'yearlyStats'
            ));
        } catch (\Exception $e) {
            // Handle any database or query errors
            return redirect()->route('login')->with('error', 'An error occurred. Please login again.');
        }
    }

    public function notifications()
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        $notifications = Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $unreadNotifications = Notification::forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = Notification::forUser($userId)->unread()->count();

        return view('user.notifications.index', compact('user', 'notifications', 'unreadNotifications', 'unreadCount'));
    }

    public function updateProfilePhoto(Request $request)
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $request->validate([
            'profile_photo_url' => 'required|url|max:2048'
        ]);

        try {
            $user = User::find($userId);
            
            $user->profile_photo = $request->input('profile_photo_url');
            $user->save();
            
            return redirect()->route('user.home')->with('success', 'Profile photo updated successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile photo. Please try again.');
        }
    }

    public function exportJournals(Request $request)
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $period = $request->query('period', 'all');
        $format = $request->query('format', 'excel');

        // Get user data
        $user = User::find($userId);

        // Build query based on period
        $query = Journal::with('admins')->where('user_id', $userId)->orderBy('created_at', 'desc');

        switch ($period) {
            case 'daily':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case 'weekly':
                $query->where('created_at', '>=', now()->subWeeks(4));
                break;
            case 'monthly':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'yearly':
                // Get all journals for yearly
                break;
            case 'all':
            default:
                // Get all journals
                break;
        }

        $journals = $query->get();

        // Prepare data for export (kolom nyata jurnal)
        $rows = [];
        foreach ($journals as $journal) {
            $content = $journal->uraian_pekerjaan ?? $journal->content ?? 'Tidak ada uraian';
            $content = preg_replace('/\s+/', ' ', $content);
            $dok = trim($journal->dokumen_pekerjaan) ?: '';
            
            // Format Atasan Status
            $atasanStatus = '-';
            if ($journal->admins->count() > 0) {
                $atasanStatus = $journal->admins->map(function($a) {
                    $st = $a->pivot->status ?? 'waiting';
                    $label = match($st) {
                        'approved' => '(Approved)',
                        'revised' => '(Revisi)',
                        'rejected' => '(Ditolak)',
                        default => '(Menunggu)',
                    };
                    return $a->name . ' ' . $label;
                })->join(', ');
            } elseif ($journal->nama_atasan) {
                $atasanStatus = $journal->nama_atasan;
            }

            // Format Status (X/Y)
            $approvedCount = $journal->admins->where('pivot.status', 'approved')->count();
            $totalAdmins = $journal->admins->count() ?: 1;
            $statusText = $approvedCount . '/' . $totalAdmins;

            $rows[] = [
                $journal->no ?? $journal->id,
                $journal->created_at ? $journal->created_at->format('d M Y, H:i') : '',
                $atasanStatus,
                $content,
                $dok,
                $statusText,
            ];
        }
        
        // Headers jurnal
        $header = ['No. Jurnal', 'Waktu Dibuat', 'Nama Atasan', 'Uraian Pekerjaan', 'Dokumen', 'Status'];
        if ($format === 'pdf') {
            $header = ['No. Jurnal', 'Waktu Dibuat', 'Nama Atasan', 'Uraian Pekerjaan', 'Dokumen', 'Status'];
        }
        if ($format === 'xlsx') {
            // sama seperti excel
            $header = ['No. Jurnal', 'Waktu Dibuat', 'Nama Atasan', 'Uraian Pekerjaan', 'Dokumen', 'Status'];
        }

        if ($format === 'pdf') {
            return view('user.journals-pdf', [
                'user' => $user,
                'period' => $period,
                'header' => $header,
                'rows' => $rows,
                'journals' => $journals
            ]);
        }

        // Excel bergaya rapi menggunakan PhpSpreadsheet (Teknik Admin)
        if ($format === 'xlsx' || $format === 'excel') {
            $filename = 'jurnal_' . $user->name . '_' . $period . '_' . now()->format('Ymd_His') . '.xlsx';
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Judul
            $title = 'Data Jurnal ' . ucwords($user->name);
            $sheet->mergeCells('A1:F1');
            $sheet->getCell('A1')->setValue($title);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Subjudul
            $subtitle = 'Periode: ' . ucfirst($period) . ' | Diekspor: ' . now()->format('d M Y, H:i');
            $sheet->mergeCells('A2:F2');
            $sheet->getCell('A2')->setValue($subtitle);
            $sheet->getStyle('A2')->getFont()->setItalic(true);
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Header Row
            $sheet->fromArray($header, null, 'A4');
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4CAF50']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ];
            $sheet->getStyle('A4:F4')->applyFromArray($headerStyle);

            // Rows
            $rowIndex = 5;
            foreach ($rows as $row) {
                $sheet->getRowDimension($rowIndex)->setRowHeight(60);
                
                // No. Jurnal (A)
                $sheet->getCell('A' . $rowIndex)->setValueExplicit($row[0], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                // Waktu Dibuat (B)
                $sheet->getCell('B' . $rowIndex)->setValue($row[1]);
                // Nama Atasan (C)
                $sheet->getCell('C' . $rowIndex)->setValue($row[2]);
                // Uraian (D)
                $sheet->getCell('D' . $rowIndex)->setValue($row[3]);
                
                // Dokumen (E) - Image Logic
                if (!empty($row[4])) {
                    $imgPath = $this->ucResolveImageLocalPath((string)$row[4]);
                    if ($imgPath && @is_file($imgPath)) {
                        try {
                            $drawing = new Drawing();
                            $drawing->setName('Journal Image');
                            $drawing->setPath($imgPath);
                            $drawing->setCoordinates('E' . $rowIndex);
                            $drawing->setHeight(50);
                            $drawing->setOffsetX(10);
                            $drawing->setOffsetY(5);
                            $drawing->setWorksheet($sheet);
                        } catch (\Throwable $e) {
                            $sheet->getCell('E' . $rowIndex)->setValue('Gagal memuat gambar');
                        }
                    } else {
                        $sheet->getCell('E' . $rowIndex)->setValue('Lihat online');
                        $sheet->getCell('E' . $rowIndex)->getHyperlink()->setUrl(str_starts_with($row[4], 'http') ? $row[4] : asset('storage/'.$row[4]));
                    }
                } else {
                    $sheet->getCell('E' . $rowIndex)->setValue('-');
                }

                // Status (F)
                $sheet->getCell('F' . $rowIndex)->setValue($row[5]);

                // Apply borders and alignment
                $sheet->getStyle('A' . $rowIndex . ':F' . $rowIndex)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('A' . $rowIndex . ':F' . $rowIndex)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('D' . $rowIndex)->getAlignment()->setWrapText(true);
                $sheet->getStyle('C' . $rowIndex)->getAlignment()->setWrapText(true);
                
                $rowIndex++;
            }

            // Column Widths
            $sheet->getColumnDimension('A')->setWidth(15);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(25);
            $sheet->getColumnDimension('D')->setWidth(50);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(10);

            $writer = new Xlsx($spreadsheet);
            
            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        // Fallback: CSV (tidak berformat)
        $filename = 'jurnal_' . $user->name . '_' . $period . '_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($header, $rows) {
            $out = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($out, "\xEF\xBB\xBF");
            fwrite($out, "sep=,\n");
            
            fputcsv($out, $header);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    public function markNotificationAsRead($id)
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = Notification::where('user_id', $userId)->findOrFail($id);
        $notification->read = true;
        $notification->save();

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Notification::where('user_id', $userId)->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    public function settings()
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        $prefs = \App\Models\NotificationPreference::firstOrCreate(['user_id' => $userId], []);
        
        // Get unread notifications for navbar
        $unreadNotifications = Notification::forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = Notification::forUser($userId)->unread()->count();

        return view('user.settings', compact('user', 'prefs', 'unreadNotifications', 'unreadCount'));
    }

    public function updateNotificationPreferences(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }
        $prefs = \App\Models\NotificationPreference::firstOrCreate(['user_id' => $userId], []);
        $prefs->approved = $request->boolean('approved');
        $prefs->revised = $request->boolean('revised');
        $prefs->rejected = $request->boolean('rejected');
        $prefs->feedback = $request->boolean('feedback');
        $prefs->new_journal = $request->boolean('new_journal');
        $prefs->save();
        return redirect()->route('user.settings')->with('success', 'Preferensi notifikasi diperbarui.');
    }

    public function updateThemeAndDefaultPage(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }
        $request->validate([
            'theme' => 'required|in:light,dark',
            'default_page' => 'nullable|in:dashboard,journals,notifications',
        ]);
        $user = User::findOrFail($userId);
        $user->theme = $request->input('theme', 'light');
        $user->default_page = $request->input('default_page');
        $user->save();
        Session::put('theme', $user->theme);
        return redirect()->route('user.settings')->with('success', 'Pengaturan tema dan halaman awal diperbarui.');
    }

    public function storePushSubscription(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $request->validate([
            'endpoint' => 'required|url',
            'publicKey' => 'required|string',
            'authToken' => 'required|string',
            'contentEncoding' => 'nullable|string',
        ]);
        $user = User::findOrFail($userId);
        $user->updatePushSubscription(
            $request->input('endpoint'),
            $request->input('publicKey'),
            $request->input('authToken'),
            $request->input('contentEncoding', 'aesgcm')
        );
        return response()->json(['success' => true]);
    }

    public function deletePushSubscription(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $request->validate([
            'endpoint' => 'required|url',
        ]);
        $user = User::findOrFail($userId);
        $user->deletePushSubscription($request->input('endpoint'));
        return response()->json(['success' => true]);
    }

    private function ucResolveImageLocalPath(string $path): ?string
    {
        $normalized = trim($path);
        if (!$normalized) return null;

        // Try different paths based on how the file is stored
        $pathsToTry = [
            // 1. Full path from root (if $path is absolute)
            $normalized,
            // 2. Storage public path
            storage_path('app/public/' . ltrim($normalized, '/')),
            // 3. Public path (if symlinked)
            public_path(ltrim($normalized, '/')),
            // 4. Handle "storage/" prefix in path
            public_path(str_starts_with($normalized, 'storage/') ? $normalized : 'storage/' . ltrim($normalized, '/')),
        ];

        foreach (array_unique($pathsToTry) as $p) {
            if (is_file($p) && file_exists($p)) {
                return $p;
            }
        }

        return null;
    }

    private function ucIsImageExt(string $path): bool
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg','jpeg','png','gif','webp','jfif'], true);
    }

    private function ucGetDataUriForImage(?string $path): ?string
    {
        if (!$path) return null;
        $normalized = trim($path);
        if (!$normalized || !$this->ucIsImageExt($normalized)) return null;
        
        try {
            $imgPath = $this->ucResolveImageLocalPath($normalized);
            if (!$imgPath) return null;

            $contents = @file_get_contents($imgPath);
            if (!$contents) return null;
            
            $ext = strtolower(pathinfo($normalized, PATHINFO_EXTENSION));
            $mime = match ($ext) {
                'jpg','jpeg','jfif' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                default => 'application/octet-stream',
            };
            
            // Limit base64 size for Excel compatibility (if it's too big, it might fail)
            if (strlen($contents) > 2 * 1024 * 1024) { // 2MB limit for Excel
                return null;
            }

            return "data:{$mime};base64," . base64_encode($contents);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
