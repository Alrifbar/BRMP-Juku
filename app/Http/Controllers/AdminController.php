<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Journal;
use App\Models\notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AdminController extends Controller
{
    private function downloadStyledExcel($filename, $title, $header, $rows, $subtitle = '')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Title and Subtitle
        $sheet->mergeCells('A1:' . chr(64 + count($header)) . '1');
        $sheet->getCell('A1')->setValue($title);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        if ($subtitle) {
            $sheet->mergeCells('A2:' . chr(64 + count($header)) . '2');
            $sheet->getCell('A2')->setValue($subtitle);
            $sheet->getStyle('A2')->getFont()->setItalic(true);
            $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        }

        // Set Header
        $sheet->fromArray($header, null, 'A4');
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4CAF50']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A4:' . chr(64 + count($header)) . '4')->applyFromArray($headerStyle);

        // Rows
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $rowIndex = 5;
        foreach ($rows as $row) {
            $colIndex = 1;
            foreach ($row as $cellValue) {
                $columnLetter = chr(64 + $colIndex);
                $isDocCol = strpos($header[$colIndex - 1], 'Dokumen') !== false;
                if ($isDocCol && !empty($cellValue)) {
                    $imgPath = $this->resolveImageLocalPath((string)$cellValue);
                    $isImg = $imgPath ? in_array(strtolower(pathinfo($imgPath, PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif','webp'], true) : false;
                    if ($imgPath && $isImg && @is_file($imgPath) && @filesize($imgPath) > 0 && @is_readable($imgPath)) {
                        try {
                            $drawing = new Drawing();
                            $drawing->setName('Image');
                            $drawing->setDescription('Image');
                            $drawing->setPath($imgPath);
                            $drawing->setCoordinates($columnLetter . $rowIndex);
                            $drawing->setHeight(60);
                            $drawing->setOffsetX(5);
                            $drawing->setOffsetY(5);
                            $drawing->setWorksheet($sheet);
                            $sheet->getRowDimension($rowIndex)->setRowHeight(70);
                        } catch (\Throwable $e) {
                            $sheet->getCell($columnLetter . $rowIndex)->setValueExplicit((string)$cellValue, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        }
                    } else {
                        $sheet->getCell($columnLetter . $rowIndex)->setValueExplicit((string)$cellValue, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    }
                } else {
                    $sheet->getCell($columnLetter . $rowIndex)->setValue($cellValue);
                }
                $colIndex++;
            }
            $rowIndex++;
        }

        // Column widths and wrap
        $lastColumn = chr(64 + count($header));
        $wrapCandidates = [];
        foreach ($header as $i => $label) {
            $col = chr(65 + $i);
            $lower = strtolower($label);
            if (str_contains($lower, 'uraian') || str_contains($lower, 'deskripsi')) {
                $sheet->getColumnDimension($col)->setWidth(60);
                $wrapCandidates[] = $col;
            } elseif (str_contains($lower, 'nama')) {
                $sheet->getColumnDimension($col)->setWidth(25);
                $wrapCandidates[] = $col;
            } elseif (str_contains($lower, 'dokumen')) {
                $sheet->getColumnDimension($col)->setWidth(20);
            } else {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }
        foreach ($wrapCandidates as $col) {
            $sheet->getStyle($col . '5:' . $col . ($rowIndex - 1))
                ->getAlignment()
                ->setWrapText(true)
                ->setVertical(Alignment::VERTICAL_TOP);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function dashboard()
    {
        $driver = DB::connection()->getDriverName();
        $adminId = Session::get('user_id');

        // Statistik Harian (7 hari terakhir) per Admin
        $dailyRaw = Journal::leftJoin('journal_admin as ja', function($join) use ($adminId){
                $join->on('journals.id', '=', 'ja.journal_id')
                     ->where('ja.admin_id', $adminId);
            })
            ->select(
                DB::raw("date(journals.created_at) as d"),
                DB::raw('COUNT(DISTINCT journals.id) as total_count'),
                DB::raw("SUM(CASE WHEN ja.status = 'approved' THEN 1 ELSE 0 END) as approved_count"),
                DB::raw("SUM(CASE WHEN ja.status = 'revised' THEN 1 ELSE 0 END) as revised_count")
            )
            ->whereHas('admins', function($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })
            ->where('journals.created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw("date(journals.created_at)"))
            ->orderBy('d', 'asc')
            ->get();

        // Isi tanggal yang kosong agar chart tidak kosong di sisi kiri
        $dates = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));
        $dailyStats = $dates->map(function($day) use ($dailyRaw) {
            $row = $dailyRaw->firstWhere('d', $day);
            return (object)[
                'date' => $day,
                'count' => (int)($row->total_count ?? 0),
                'approved_count' => (int)($row->approved_count ?? 0),
                'revised_count' => (int)($row->revised_count ?? 0),
            ];
        });

        if ($driver === 'sqlite') {
            // SQLite: gunakan strftime - Filter by Admin
            $weeklyStats = Journal::leftJoin('journal_admin as ja', function($join) use ($adminId){
                    $join->on('journals.id', '=', 'ja.journal_id')
                         ->where('ja.admin_id', $adminId);
                })
                ->select(
                    DB::raw("strftime('%Y-%W', journals.created_at) as week"),
                    DB::raw("strftime('%Y', journals.created_at) as year"),
                    DB::raw("MIN(date(journals.created_at)) as week_start"),
                    DB::raw("MAX(date(journals.created_at)) as week_end"),
                    DB::raw('COUNT(DISTINCT journals.id) as count'),
                    DB::raw("SUM(CASE WHEN ja.status = 'approved' THEN 1 ELSE 0 END) as approved_count"),
                    DB::raw("SUM(CASE WHEN ja.status = 'revised' THEN 1 ELSE 0 END) as revised_count")
                )
                ->whereHas('admins', function($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })
                ->where('journals.created_at', '>=', now()->subWeeks(4))
                ->groupBy(DB::raw("strftime('%Y-%W', journals.created_at)"))
                ->orderBy('week', 'desc')
                ->get();

            $yearlyRaw = Journal::leftJoin('journal_admin as ja', function($join) use ($adminId){
                    $join->on('journals.id', '=', 'ja.journal_id')
                         ->where('ja.admin_id', $adminId);
                })
                ->select(
                    DB::raw("strftime('%Y', journals.created_at) as year"),
                    DB::raw('COUNT(DISTINCT journals.id) as count'),
                    DB::raw("SUM(CASE WHEN ja.status = 'approved' THEN 1 ELSE 0 END) as approved_count"),
                    DB::raw("SUM(CASE WHEN ja.status = 'revised' THEN 1 ELSE 0 END) as revised_count")
                )
                ->whereHas('admins', function($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })
                ->groupBy(DB::raw("strftime('%Y', journals.created_at)"))
                ->orderBy('year', 'asc')
                ->get();

            // Pastikan setidaknya 2 tahun terakhir muncul
            $currentYear = (int) now()->format('Y');
            $years = collect(range($currentYear - 4, $currentYear));
            $yearlyStats = $years->map(function($y) use ($yearlyRaw){
                $row = $yearlyRaw->firstWhere('year', (string)$y);
                return (object)[
                    'year' => (string)$y,
                    'count' => (int)($row->count ?? 0),
                    'approved_count' => (int)($row->approved_count ?? 0),
                    'revised_count' => (int)($row->revised_count ?? 0),
                ];
            });
        } else {
            // MySQL/MariaDB - Filter by Admin
            $weeklyStats = Journal::leftJoin('journal_admin as ja', function($join) use ($adminId){
                    $join->on('journals.id', '=', 'ja.journal_id')
                         ->where('ja.admin_id', $adminId);
                })
                ->select(
                    DB::raw('YEARWEEK(journals.created_at) as week'),
                    DB::raw('YEAR(journals.created_at) as year'),
                    DB::raw('MIN(DATE(journals.created_at)) as week_start'),
                    DB::raw('MAX(DATE(journals.created_at)) as week_end'),
                    DB::raw('COUNT(DISTINCT journals.id) as count'),
                    DB::raw("SUM(CASE WHEN ja.status = 'approved' THEN 1 ELSE 0 END) as approved_count"),
                    DB::raw("SUM(CASE WHEN ja.status = 'revised' THEN 1 ELSE 0 END) as revised_count")
                )
                ->whereHas('admins', function($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })
                ->where('journals.created_at', '>=', now()->subWeeks(4))
                ->groupBy(DB::raw('YEARWEEK(journals.created_at)'), DB::raw('YEAR(journals.created_at)'))
                ->orderBy('week', 'desc')
                ->get();

            $yearlyRaw = Journal::leftJoin('journal_admin as ja', function($join) use ($adminId){
                    $join->on('journals.id', '=', 'ja.journal_id')
                         ->where('ja.admin_id', $adminId);
                })
                ->select(
                    DB::raw('YEAR(journals.created_at) as year'),
                    DB::raw('COUNT(DISTINCT journals.id) as count'),
                    DB::raw("SUM(CASE WHEN ja.status = 'approved' THEN 1 ELSE 0 END) as approved_count"),
                    DB::raw("SUM(CASE WHEN ja.status = 'revised' THEN 1 ELSE 0 END) as revised_count")
                )
                ->whereHas('admins', function($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })
                ->groupBy(DB::raw('YEAR(journals.created_at)'))
                ->orderBy('year', 'asc')
                ->get();

            $currentYear = (int) now()->format('Y');
            $years = collect(range($currentYear - 4, $currentYear));
            $yearlyStats = $years->map(function($y) use ($yearlyRaw){
                $row = $yearlyRaw->firstWhere('year', (string)$y);
                return (object)[
                    'year' => (string)$y,
                    'count' => (int)($row->count ?? 0),
                    'approved_count' => (int)($row->approved_count ?? 0),
                    'revised_count' => (int)($row->revised_count ?? 0),
                ];
            });
        }

        // Statistik Umum - Filter by Admin
        $totalUsers = User::where('is_admin', false)->count();
        $totalAdmins = User::where('is_admin', true)->count();
        $totalJournals = Journal::whereHas('admins', function($q) use ($adminId) {
            $q->where('admin_id', $adminId);
        })->count();
        $journalsThisMonth = Journal::whereHas('admins', function($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Recent Journals - Filter by Admin
        $recentJournals = Journal::with('user')
            ->whereHas('admins', function($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Top Users (exclude admin) - Filter by journals sent to THIS Admin
        $topUsers = User::where('is_admin', false)
            ->withCount(['journals' => function($q) use ($adminId) {
                $q->whereHas('admins', function($sq) use ($adminId) {
                    $sq->where('admin_id', $adminId);
                });
            }])
            ->orderBy('journals_count', 'desc')
            ->take(10)
            ->get();

        // Least Active Users (exclude admin)
        $leastUsers = User::where('is_admin', false)
            ->withCount(['journals' => function($q) use ($adminId) {
                $q->whereHas('admins', function($sq) use ($adminId) {
                    $sq->where('admin_id', $adminId);
                });
            }])
            ->orderBy('journals_count', 'asc')
            ->take(5)
            ->get();

        // Admin unread notifications
        $unreadNotifications = \App\Models\Notification::forUser($adminId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = \App\Models\Notification::forUser($adminId)->unread()->count();

        // Get current admin user data (session-based auth)
        $userId = Session::get('user_id');
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                Session::put('user_name', $user->name);
                Session::put('user_email', $user->email);
                Session::put('user_photo', $user->profile_photo);
                Session::put('admin_profile_photo', $user->is_admin ? $user->profile_photo : null);
                Session::put('role', $user->role ?? ($user->is_admin ? 'Admin' : 'User'));
            }
        } else {
            // Fallback values if user is not authenticated
            Session::put('user_name', 'Admin');
            Session::put('user_email', 'admin@example.com');
            Session::put('user_photo', null);
            Session::put('admin_profile_photo', null);
            Session::put('role', 'Admin');
        }

        return view('admin.dashboard', compact(
            'dailyStats',
            'weeklyStats', 
            'yearlyStats',
            'totalUsers',
            'totalAdmins',
            'totalJournals',
            'journalsThisMonth',
            'recentJournals',
            'topUsers',
            'leastUsers'
        , 'unreadNotifications', 'unreadCount'));
    }

    public function exportStats(Request $request)
    {
        $period = $request->query('period', 'daily');
        if (!in_array($period, ['daily', 'weekly', 'monthly', 'yearly'], true)) {
            $period = 'daily';
        }

        $format = $request->query('format', 'excel');
        if (!in_array($format, ['excel', 'pdf', 'xlsx'], true)) {
            $format = 'excel';
        }

        $driver = DB::connection()->getDriverName();

        $rows = [];
        if ($period === 'daily') {
            $stats = Journal::leftJoin('journal_admin as ja', function($join){
                    $join->on('journals.id', '=', 'ja.journal_id')
                         ->where('ja.status', 'revised');
                })
                ->select(
                    DB::raw("date(journals.created_at) as period"),
                    DB::raw('COUNT(DISTINCT journals.id) as entries_count'),
                    DB::raw('COUNT(DISTINCT journals.user_id) as unique_users'),
                    DB::raw('SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count'),
                    DB::raw('COUNT(DISTINCT ja.journal_id) as revised_count')
                )
                ->where('journals.created_at', '>=', now()->subDays(7))
                ->groupBy(DB::raw("date(journals.created_at)"))
                ->orderBy('period', 'asc')
                ->get();

            foreach ($stats as $s) {
                $rows[] = [$s->period, (int)$s->entries_count, (int)$s->unique_users, (int)($s->approved_count ?? 0), (int)($s->revised_count ?? 0)];
            }

            $header = ['Tanggal', 'Jumlah Jurnal', 'Jumlah Pengirim', 'Disetujui', 'Revisi'];
        } elseif ($period === 'weekly') {
            if ($driver === 'sqlite') {
                $stats = Journal::leftJoin('journal_admin as ja', function($join){
                        $join->on('journals.id', '=', 'ja.journal_id')
                             ->where('ja.status', 'revised');
                    })
                    ->select(
                        DB::raw("strftime('%Y-%W', journals.created_at) as period"),
                        DB::raw("MIN(date(journals.created_at)) as week_start"),
                        DB::raw("MAX(date(journals.created_at)) as week_end"),
                        DB::raw('COUNT(DISTINCT journals.id) as entries_count'),
                        DB::raw('COUNT(DISTINCT journals.user_id) as unique_users'),
                        DB::raw('SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count'),
                        DB::raw('COUNT(DISTINCT ja.journal_id) as revised_count')
                    )
                    ->where('journals.created_at', '>=', now()->subWeeks(12))
                    ->groupBy(DB::raw("strftime('%Y-%W', journals.created_at)"))
                    ->orderBy('period', 'asc')
                    ->get();
            } else {
                $stats = Journal::leftJoin('journal_admin as ja', function($join){
                        $join->on('journals.id', '=', 'ja.journal_id')
                             ->where('ja.status', 'revised');
                    })
                    ->select(
                        DB::raw('YEARWEEK(journals.created_at) as period'),
                        DB::raw('MIN(DATE(journals.created_at)) as week_start'),
                        DB::raw('MAX(DATE(journals.created_at)) as week_end'),
                        DB::raw('COUNT(DISTINCT journals.id) as entries_count'),
                        DB::raw('COUNT(DISTINCT journals.user_id) as unique_users'),
                        DB::raw('SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count'),
                        DB::raw('COUNT(DISTINCT ja.journal_id) as revised_count')
                    )
                    ->where('journals.created_at', '>=', now()->subWeeks(12))
                    ->groupBy(DB::raw('YEARWEEK(journals.created_at)'))
                    ->orderBy('period', 'asc')
                    ->get();
            }

            foreach ($stats as $s) {
                $rows[] = [$s->period, $s->week_start, $s->week_end, (int)$s->entries_count, (int)$s->unique_users, (int)($s->approved_count ?? 0), (int)($s->revised_count ?? 0)];
            }

            $header = ['Minggu', 'Mulai', 'Sampai', 'Jumlah Jurnal', 'Jumlah Pengirim', 'Disetujui', 'Revisi'];
        } elseif ($period === 'monthly') {
            if ($driver === 'sqlite') {
                $stats = Journal::leftJoin('journal_admin as ja', function($join){
                        $join->on('journals.id', '=', 'ja.journal_id')
                             ->where('ja.status', 'revised');
                    })
                    ->select(
                        DB::raw("strftime('%Y-%m', journals.created_at) as period"),
                        DB::raw('COUNT(DISTINCT journals.id) as entries_count'),
                        DB::raw('COUNT(DISTINCT journals.user_id) as unique_users'),
                        DB::raw('SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count'),
                        DB::raw('COUNT(DISTINCT ja.journal_id) as revised_count')
                    )
                    ->where('journals.created_at', '>=', now()->subMonths(24))
                    ->groupBy(DB::raw("strftime('%Y-%m', journals.created_at)"))
                    ->orderBy('period', 'asc')
                    ->get();
            } else {
                $stats = Journal::leftJoin('journal_admin as ja', function($join){
                        $join->on('journals.id', '=', 'ja.journal_id')
                             ->where('ja.status', 'revised');
                    })
                    ->select(
                        DB::raw("DATE_FORMAT(journals.created_at, '%Y-%m') as period"),
                        DB::raw('COUNT(DISTINCT journals.id) as entries_count'),
                        DB::raw('COUNT(DISTINCT journals.user_id) as unique_users'),
                        DB::raw('SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count'),
                        DB::raw('COUNT(DISTINCT ja.journal_id) as revised_count')
                    )
                    ->where('journals.created_at', '>=', now()->subMonths(24))
                    ->groupBy(DB::raw("DATE_FORMAT(journals.created_at, '%Y-%m')"))
                    ->orderBy('period', 'asc')
                    ->get();
            }

            foreach ($stats as $s) {
                $rows[] = [$s->period, (int)$s->entries_count, (int)$s->unique_users, (int)($s->approved_count ?? 0), (int)($s->revised_count ?? 0)];
            }

            $header = ['Bulan', 'Jumlah Jurnal', 'Jumlah Pengirim', 'Disetujui', 'Revisi'];
        } else {
            if ($driver === 'sqlite') {
                $stats = Journal::leftJoin('journal_admin as ja', function($join){
                        $join->on('journals.id', '=', 'ja.journal_id')
                             ->where('ja.status', 'revised');
                    })
                    ->select(
                        DB::raw("strftime('%Y', journals.created_at) as period"),
                        DB::raw('COUNT(DISTINCT journals.id) as entries_count'),
                        DB::raw('COUNT(DISTINCT journals.user_id) as unique_users'),
                        DB::raw('SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count'),
                        DB::raw('COUNT(DISTINCT ja.journal_id) as revised_count')
                    )
                    ->groupBy(DB::raw("strftime('%Y', journals.created_at)"))
                    ->orderBy('period', 'asc')
                    ->get();
            } else {
                $stats = Journal::leftJoin('journal_admin as ja', function($join){
                        $join->on('journals.id', '=', 'ja.journal_id')
                             ->where('ja.status', 'revised');
                    })
                    ->select(
                        DB::raw('YEAR(journals.created_at) as period'),
                        DB::raw('COUNT(DISTINCT journals.id) as entries_count'),
                        DB::raw('COUNT(DISTINCT journals.user_id) as unique_users'),
                        DB::raw('SUM(CASE WHEN journals.received_by_admin = 1 THEN 1 ELSE 0 END) as approved_count'),
                        DB::raw('COUNT(DISTINCT ja.journal_id) as revised_count')
                    )
                    ->groupBy(DB::raw('YEAR(journals.created_at)'))
                    ->orderBy('period', 'asc')
                    ->get();
            }

            foreach ($stats as $s) {
                $rows[] = [$s->period, (int)$s->entries_count, (int)$s->unique_users, (int)($s->approved_count ?? 0), (int)($s->revised_count ?? 0)];
            }

            $header = ['Tahun', 'Jumlah Jurnal', 'Jumlah Pengirim', 'Disetujui', 'Revisi'];
        }

        $adminId = Session::get('user_id');
        $admin = $adminId ? User::find($adminId) : null;
        $adminPhoto = $admin && $admin->profile_photo ? $admin->profile_photo : null;

        // Additional datasets for charts (unfiltered, global)
        // Daily (last 7 days)
        $dailyChart = Journal::select(
                DB::raw("date(created_at) as date"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw("date(created_at)"))
            ->orderBy('date', 'asc')
            ->get();

        // Weekly (last 12 weeks)
        if ($driver === 'sqlite') {
            $weeklyChart = Journal::select(
                    DB::raw("strftime('%Y-%W', created_at) as week"),
                    DB::raw("MIN(date(created_at)) as week_start"),
                    DB::raw("MAX(date(created_at)) as week_end"),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', now()->subWeeks(12))
                ->groupBy(DB::raw("strftime('%Y-%W', created_at)"))
                ->orderBy('week', 'asc')
                ->get();
        } else {
            $weeklyChart = Journal::select(
                    DB::raw('YEARWEEK(created_at) as week'),
                    DB::raw('MIN(DATE(created_at)) as week_start'),
                    DB::raw('MAX(DATE(created_at)) as week_end'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', now()->subWeeks(12))
                ->groupBy(DB::raw('YEARWEEK(created_at)'))
                ->orderBy('week', 'asc')
                ->get();
        }

        // Yearly (all years)
        if ($driver === 'sqlite') {
            $yearlyChart = Journal::select(
                    DB::raw("strftime('%Y', created_at) as year"),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy(DB::raw("strftime('%Y', created_at)"))
                ->orderBy('year', 'asc')
                ->get();
        } else {
            $yearlyChart = Journal::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->orderBy('year', 'asc')
                ->get();
        }

        // Top performers (top 10 employees by journals count)
        $topUsersExport = User::where('is_admin', false)
            ->withCount('journals')
            ->orderBy('journals_count', 'desc')
            ->take(10)
            ->get();

        // Active users (last 24 hours)
        $activeUsers = User::where('is_admin', false)
            ->whereHas('journals', function($q){
                $q->where('created_at', '>=', now()->subDay());
            })->count();
        $totalEmployees = User::where('is_admin', false)->count();

        if ($format === 'pdf') {
            return view('admin.stats-pdf', [
                'period' => $period,
                'header' => $header,
                'rows' => $rows,
                'admin' => $admin,
                'adminPhoto' => $adminPhoto,
                'dailyChart' => $dailyChart,
                'weeklyChart' => $weeklyChart,
                'yearlyChart' => $yearlyChart,
                'topUsersExport' => $topUsersExport,
                'activeUsers' => $activeUsers,
                'totalEmployees' => $totalEmployees,
            ]);
        }

        if ($format === 'xlsx' || $format === 'excel') {
            $filename = 'statistik_' . $period . '_' . now()->format('Ymd_His') . '.xlsx';
            $title = 'Statistik ' . strtoupper($period);
            $subtitle = 'Diekspor: ' . now()->format('Y-m-d H:i');
            return $this->downloadStyledExcel($filename, $title, $header, $rows, $subtitle);
        }

        $filename = 'statistik_' . $period . '_' . now()->format('Ymd_His') . '.csv';
        return response()->streamDownload(function () use ($header, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fwrite($out, "sep=,\n");
            fputcsv($out, $header);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportOptions()
    {
        $adminId = Session::get('user_id');
        $admin = $adminId ? User::find($adminId) : null;
        $employees = User::where('is_admin', false)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'division']);
        return view('admin.export.options', [
            'admin' => $admin,
            'employees' => $employees,
        ]);
    }

    public function exportDetailedJournals(Request $request)
    {
        $period = $request->query('period', 'all');
        $format = $request->query('format', 'pdf');
        $adminId = Session::get('user_id');
        $admin = $adminId ? User::find($adminId) : null;

        $query = Journal::with('user')->orderBy('created_at', 'desc');
        $start = null;
        $end = now();
        switch ($period) {
            case 'day':
                $start = now()->subDay();
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'week':
                $start = now()->subWeek();
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'month':
                $start = now()->subMonth();
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'year':
                $start = now()->subYear();
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'all':
            default:
                // no filter
                break;
        }

        $journals = $query->get();
        $rows = [];
        foreach ($journals as $j) {
            $content = $j->uraian_pekerjaan ?? $j->content ?? 'Tidak ada uraian';
            $content = preg_replace('/\s+/', ' ', $content);
            $dok = $j->dokumen_pekerjaan ?: '';
            $statusText = $j->received_by_admin ? 'Diterima' : 'Belum diterima';
            $rows[] = [
                $j->no ?? $j->id,
                $j->created_at ? $j->created_at->format('Y-m-d H:i') : '',
                optional($j->user)->name ?: '-',
                $content,
                $dok,
                $statusText,
            ];
        }

        $header = ['No. Jurnal', 'Waktu Dibuat', 'Nama Pegawai', 'Uraian Pekerjaan', 'Dokumen', 'Status'];

        $rangeStart = $start ?: $journals->min('created_at');
        $rangeEnd = $end ?: $journals->max('created_at');

        if ($format === 'pdf') {
            return view('admin.export.journals-detailed-pdf', [
                'admin' => $admin,
                'period' => $period,
                'header' => $header,
                'rows' => $rows,
                'journals' => $journals,
                'rangeStart' => $rangeStart,
                'rangeEnd' => $rangeEnd,
            ]);
        }
        if ($format === 'xlsx' || $format === 'excel') {
            $filename = 'laporan_jurnal_seluruh_pegawai_' . $period . '_' . now()->format('Ymd_His') . '.xlsx';
            $title = 'Laporan Jurnal Seluruh Pegawai';
            $subtitle = 'Periode: ' . ucfirst($period) . ' | Diekspor: ' . now()->format('Y-m-d H:i');
            return $this->downloadStyledExcel($filename, $title, $header, $rows, $subtitle);
        }
        if ($format === 'sql') {
            $sql = $this->generateSqlDump($rangeStart ?: now()->subYears(10), $rangeEnd ?: now());
            $filename = 'laporan_jurnal_seluruh_pegawai_' . $period . '_' . now()->format('Ymd_His') . '.sql';
            return response()->streamDownload(function() use ($sql){ echo $sql; }, $filename, [
                'Content-Type' => 'application/sql; charset=UTF-8',
            ]);
        }

        $filename = 'laporan_jurnal_seluruh_pegawai_' . $period . '_' . now()->format('Ymd_His') . '.csv';
        return response()->streamDownload(function () use ($header, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fwrite($out, "sep=,\n");
            fputcsv($out, $header);
            foreach ($rows as $row) { fputcsv($out, $row); }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportUserJournals(Request $request)
    {
        $adminId = Session::get('user_id');
        $admin = $adminId ? User::find($adminId) : null;
        if (!$admin || !$admin->is_admin) {
            return redirect()->route('login')->with('error', 'Unauthorized');
        }

        $userId = (int) $request->query('user_id');
        $period = $request->query('period', 'all');
        $format = $request->query('format', 'pdf');

        $user = User::findOrFail($userId);

        $query = Journal::with(['admins'])->where('user_id', $userId)->orderBy('created_at', 'desc');
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
                break;
            case 'all':
            default:
                break;
        }

        $journals = $query->get();

        $rows = [];
        foreach ($journals as $journal) {
            $content = $journal->uraian_pekerjaan ?? $journal->content ?? 'Tidak ada uraian';
            $content = preg_replace('/\s+/', ' ', $content);
            $dok = $journal->dokumen_pekerjaan ?: '';
            $status = $journal->received_by_admin ? 'Diterima' : 'Pending';
            $rows[] = [
                $journal->no ?? $journal->id,
                $journal->created_at ? $journal->created_at->format('Y-m-d H:i') : '',
                $journal->nama_atasan ?: '',
                $content,
                $dok,
                $status,
            ];
        }

        $header = ['No. Jurnal', 'Waktu Dibuat', 'Nama Atasan', 'Uraian Pekerjaan', 'Dokumen', 'Status'];
        if ($format === 'pdf') {
            $header = ['No. Jurnal', 'Waktu Dibuat', 'Nama Atasan', 'Uraian Pekerjaan', 'Dokumen', 'Status'];
            return view('user.journals-pdf', [
                'user' => $user,
                'period' => $period,
                'header' => $header,
                'rows' => $rows,
                'journals' => $journals
            ]);
        }
        if ($format === 'xlsx' || $format === 'excel') {
            $filename = 'jurnal_' . $user->name . '_' . $period . '_' . now()->format('Ymd_His') . '.xlsx';
            $title = 'Data Jurnal ' . $user->name;
            $subtitle = 'Periode: ' . ucfirst($period) . ' | Diekspor: ' . now()->format('Y-m-d H:i');
            return $this->downloadStyledExcel($filename, $title, $header, $rows, $subtitle);
        }
        $filename = 'jurnal_' . $user->name . '_' . $period . '_' . now()->format('Ymd_His') . '.csv';
        return response()->streamDownload(function () use ($header, $rows) {
            $out = fopen('php://output', 'w');
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

    public function exportPosts(Request $request)
    {
        $format = $request->query('format', 'excel');
        $journals = Journal::with('user')->orderBy('created_at', 'desc')->get();
        $rows = [];
        foreach ($journals as $j) {
            $rows[] = [
                $j->no ?? $j->id,
                $j->nama_atasan ?? 'No Atasan',
                $j->user ? $j->user->name : '',
                $j->received_by_admin ? 'Received' : 'Pending',
                (int) ($j->admin_checks ?? 0)
            ];
        }
        $header = ['No. Jurnal', 'Nama Atasan', 'Pengirim', 'Status', 'Cek Admin'];
        if ($format === 'pdf') {
            $adminId = Session::get('user_id');
            $admin = $adminId ? User::find($adminId) : null;
            $adminPhoto = $admin && $admin->profile_photo ? $admin->profile_photo : null;
            return view('admin.export.posts-pdf', [
                'title' => 'Daftar Posts',
                'header' => $header,
                'rows' => $rows,
                'admin' => $admin,
                'adminPhoto' => $adminPhoto,
            ]);
        }
        if ($format === 'xlsx' || $format === 'excel') {
            $filename = 'posts_' . now()->format('Ymd_His') . '.xlsx';
            $title = 'Daftar Posts';
            $subtitle = 'Diekspor: ' . now()->format('Y-m-d H:i');
            return $this->downloadStyledExcel($filename, $title, $header, $rows, $subtitle);
        }
        $filename = 'posts_' . now()->format('Ymd_His') . '.csv';
        return response()->streamDownload(function () use ($header, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fwrite($out, "sep=,\n");
            fputcsv($out, $header);
            foreach ($rows as $row) { fputcsv($out, $row); }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportAdmins(Request $request)
    {
        $format = $request->query('format', 'excel');
        $admins = User::where('is_admin', true)->withCount('journals')->orderBy('created_at', 'desc')->get();
        $rows = [];
        foreach ($admins as $a) {
            $rows[] = [
                $a->id,
                $a->name,
                $a->email,
                $a->role ?? 'admin',
                (int) ($a->journals_count ?? 0),
                $a->created_at->format('Y-m-d H:i')
            ];
        }
        $header = ['ID', 'Nama', 'Email', 'Role', 'Jumlah Jurnal', 'Dibuat Pada'];
        if ($format === 'pdf') {
            $adminId = Session::get('user_id');
            $admin = $adminId ? User::find($adminId) : null;
            $adminPhoto = $admin && $admin->profile_photo ? $admin->profile_photo : null;
            return view('admin.export.admins-pdf', [
                'title' => 'Daftar Admin',
                'header' => $header,
                'rows' => $rows,
                'admin' => $admin,
                'adminPhoto' => $adminPhoto,
            ]);
        }
        if ($format === 'xlsx' || $format === 'excel') {
            $filename = 'admins_' . now()->format('Ymd_His') . '.xlsx';
            $title = 'Daftar Admin';
            $subtitle = 'Diekspor: ' . now()->format('Y-m-d H:i');
            return $this->downloadStyledExcel($filename, $title, $header, $rows, $subtitle);
        }
        $filename = 'admins_' . now()->format('Ymd_His') . '.csv';
        return response()->streamDownload(function () use ($header, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fwrite($out, "sep=,\n");
            fputcsv($out, $header);
            foreach ($rows as $row) { fputcsv($out, $row); }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportEmployees(Request $request)
    {
        $format = $request->query('format', 'excel');
        $employees = User::where('is_admin', false)->withCount('journals')->orderBy('created_at', 'desc')->get();
        $rows = [];
        foreach ($employees as $e) {
            $rows[] = [
                $e->id,
                $e->name,
                $e->email,
                $e->division ?? '',
                (int) ($e->journals_count ?? 0),
                $e->created_at->format('Y-m-d H:i')
            ];
        }
        $header = ['ID', 'Nama', 'Email', 'Divisi', 'Jumlah Jurnal', 'Dibuat Pada'];
        if ($format === 'pdf') {
            $adminId = Session::get('user_id');
            $admin = $adminId ? User::find($adminId) : null;
            $adminPhoto = $admin && $admin->profile_photo ? $admin->profile_photo : null;
            return view('admin.export.employees-pdf', [
                'title' => 'Daftar Pegawai',
                'header' => $header,
                'rows' => $rows,
                'admin' => $admin,
                'adminPhoto' => $adminPhoto,
            ]);
        }
        if ($format === 'xlsx' || $format === 'excel') {
            $filename = 'employees_' . now()->format('Ymd_His') . '.xlsx';
            $title = 'Daftar Pegawai';
            $subtitle = 'Diekspor: ' . now()->format('Y-m-d H:i');
            return $this->downloadStyledExcel($filename, $title, $header, $rows, $subtitle);
        }
        $filename = 'employees_' . now()->format('Ymd_His') . '.csv';
        return response()->streamDownload(function () use ($header, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fwrite($out, "sep=,\n");
            fputcsv($out, $header);
            foreach ($rows as $row) { fputcsv($out, $row); }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function posts()
    {
        $adminId = Session::get('user_id');
        $query = Journal::with(['user', 'admins'])
            ->whereHas('admins', function($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })
            ->orderBy('created_at', 'desc');
        
        // Search functionality
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('no', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('uraian_pekerjaan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $journals = $query->paginate(20);

        return view('admin.posts', compact('journals'));
    }

    public function showPost($id)
    {
        $journal = Journal::with(['user', 'admins'])->findOrFail($id);
        $adminId = Session::get('user_id');
        $hasApproved = $journal->admins()->where('admin_id', $adminId)->where('status', 'approved')->exists();

        // Mark notifications as read for this journal and admin
        \App\Models\Notification::where('user_id', $adminId)
            ->where('journal_id', $journal->id)
            ->where('read', false)
            ->update(['read' => true]);

        return view('admin.show-post', compact('journal', 'hasApproved'));
    }

    public function toggleJournalReceived(Journal $journal)
    {
        $adminId = Session::get('user_id');
        $admin = User::find($adminId);
        if (!$admin || !$admin->is_admin) {
            return redirect()->route('admin.posts.show', $journal)
                ->with('error', 'Hanya admin yang dapat menerima laporan.');
        }

        // Update status di pivot table journal_admin
        $journal->admins()->updateExistingPivot($adminId, [
            'status' => 'approved',
            'updated_at' => now(),
        ]);

        // Hitung berapa admin yang sudah approve
        $approvedCount = $journal->admins()->where('status', 'approved')->count();
        $totalTargetAdmins = $journal->admins()->count();

        $journal->admin_checks = $approvedCount;
        if ($approvedCount >= $totalTargetAdmins) {
            $journal->received_by_admin = true;
            $journal->received_at = now();
        }
        $journal->save();

        Notification::create([
            'user_id' => $journal->user_id,
            'type' => 'received',
            'message' => "Jurnal '{$journal->title}' telah disetujui oleh {$admin->name}. Progress: {$approvedCount}/{$totalTargetAdmins}",
            'journal_id' => $journal->id,
        ]);

        return redirect()->route('admin.posts.show', $journal)
            ->with('success', "Berhasil menyetujui jurnal. ({$approvedCount}/{$totalTargetAdmins})");
    }

    public function bulkApprove(Request $request)
    {
        $adminId = Session::get('user_id');
        $admin = User::find($adminId);
        if (!$admin || !$admin->is_admin) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $journalIds = json_decode($request->input('journal_ids', '[]'));
        if (empty($journalIds)) {
            return redirect()->back()->with('error', 'Tidak ada jurnal yang dipilih');
        }

        $journals = Journal::whereIn('id', $journalIds)->get();
        $count = 0;

        foreach ($journals as $journal) {
            $journal->admins()->updateExistingPivot($adminId, [
                'status' => 'approved',
                'updated_at' => now(),
            ]);

            $approvedCount = $journal->admins()->where('status', 'approved')->count();
            $totalTargetAdmins = $journal->admins()->count();

            $journal->admin_checks = $approvedCount;
            if ($approvedCount >= $totalTargetAdmins) {
                $journal->received_by_admin = true;
                $journal->received_at = now();
            }
            $journal->save();

            Notification::create([
                'user_id' => $journal->user_id,
                'type' => 'received',
                'message' => "Jurnal '{$journal->title}' telah disetujui oleh {$admin->name}. Progress: {$approvedCount}/{$totalTargetAdmins}",
                'journal_id' => $journal->id,
            ]);
            $count++;
        }

        return redirect()->route('admin.posts')->with('success', "Berhasil menyetujui {$count} jurnal.");
    }

    public function bulkRevise(Request $request)
    {
        $adminId = Session::get('user_id');
        $admin = User::find($adminId);
        if (!$admin || !$admin->is_admin) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $journalIds = json_decode($request->input('journal_ids', '[]'));
        if (empty($journalIds)) {
            return redirect()->back()->with('error', 'Tidak ada jurnal yang dipilih');
        }

        $journals = Journal::whereIn('id', $journalIds)->get();
        $count = 0;

        foreach ($journals as $journal) {
            $journal->admins()->updateExistingPivot($adminId, [
                'status' => 'revised',
                'updated_at' => now(),
            ]);

            $journal->received_by_admin = false;
            $journal->save();

            Notification::create([
                'user_id' => $journal->user_id,
                'type' => 'revised',
                'message' => "Jurnal '{$journal->title}' perlu direvisi sesuai arahan dari {$admin->name}.",
                'journal_id' => $journal->id,
            ]);
            $count++;
        }

        return redirect()->route('admin.posts')->with('success', "Berhasil mengubah status {$count} jurnal menjadi Revisi.");
    }

    public function cancelJournalReceived(Journal $journal)
    {
        $adminId = Session::get('user_id');
        $admin = User::find($adminId);

        // Reset status di pivot table
        $journal->admins()->updateExistingPivot($adminId, [
            'status' => 'waiting',
            'updated_at' => now(),
        ]);

        $approvedCount = $journal->admins()->where('status', 'approved')->count();
        $totalTargetAdmins = $journal->admins()->count();

        $journal->admin_checks = $approvedCount;
        $journal->received_by_admin = false;
        $journal->received_at = null;
        $journal->save();

        Notification::create([
            'user_id' => $journal->user_id,
            'type' => 'rejected',
            'message' => "Persetujuan jurnal '{$journal->title}' dibatalkan oleh {$admin->name}.",
            'journal_id' => $journal->id,
        ]);

        return redirect()->route('admin.posts.show', $journal)
            ->with('success', "Persetujuan dibatalkan. ({$approvedCount}/{$totalTargetAdmins})");
    }

    public function rejectJournal($id)
    {
        $journal = Journal::findOrFail($id);
        $adminId = Session::get('user_id');
        $admin = User::find($adminId);

        $journal->admins()->updateExistingPivot($adminId, [
            'status' => 'rejected',
            'updated_at' => now(),
        ]);

        $journal->received_by_admin = false;
        $journal->save();

        Notification::create([
            'user_id' => $journal->user_id,
            'type' => 'rejected',
            'message' => "Jurnal '{$journal->title}' ditolak oleh {$admin->name}. Silakan periksa detail jurnal.",
            'journal_id' => $journal->id,
        ]);

        return redirect()->route('admin.posts.show', $journal)
            ->with('success', 'Jurnal berhasil ditolak');
    }

    public function destroyPost($id)
    {
        $journal = Journal::findOrFail($id);
        $adminId = Session::get('user_id');
        $admin = User::find($adminId);

        $journal->admins()->updateExistingPivot($adminId, [
            'status' => 'revised',
            'updated_at' => now(),
        ]);

        $journal->received_by_admin = false;
        $journal->save();

        Notification::create([
            'user_id' => $journal->user_id,
            'type' => 'revised',
            'message' => "Jurnal '{$journal->title}' perlu direvisi sesuai arahan dari {$admin->name}.",
            'journal_id' => $journal->id,
        ]);

        return redirect()->route('admin.posts.show', $journal)
            ->with('success', 'Status jurnal diubah menjadi Revisi');
    }

    public function users(Request $request)
    {
        $query = User::where('is_admin', true)
            ->withCount('journals')
            ->with(['journals' => function($query) {
                $query->join('journal_admin', 'journals.id', '=', 'journal_admin.journal_id')
                      ->select('journals.*', 'journal_admin.updated_at as interaction_at');
            }]);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->get()
            ->map(function($user) {
                // Get last interaction from pivot table
                $lastInteraction = DB::table('journal_admin')
                    ->where('admin_id', $user->id)
                    ->orderBy('updated_at', 'desc')
                    ->first();
                
                $user->last_interaction_at = $lastInteraction ? $lastInteraction->updated_at : null;
                return $user;
            });

        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users-create');
    }

    public function showUser($id)
    {
        $user = User::withCount('journals')->findOrFail($id);
        $photo = $user->profile_photo;
        if ($photo) {
            if (str_starts_with($photo, 'http')) {
                $photoUrl = $photo;
            } elseif (str_starts_with($photo, 'uploads/')) {
                $photoUrl = asset($photo);
            } else {
                $photoUrl = asset('storage/'.$photo);
            }
        } else {
            $photoUrl = null;
        }
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nip' => $user->nip ?? '-',
            'role' => $user->is_admin ? 'Administrator' : 'User',
            'role_key' => $user->role ?? ($user->is_admin ? 'admin' : 'user'),
            'journals_count' => $user->journals_count ?? 0,
            'phone' => $user->phone,
            'address' => $user->address,
            'created_at' => $user->created_at ? $user->created_at->format('M d, Y') : null,
            'created_at_full' => $user->created_at ? $user->created_at->format('d M Y, H:i') : null,
            'photo' => $photoUrl,
        ]);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'nip' => 'required|string|max:50|unique:users,nip',
            'provider' => 'required|in:local,google',
            'password' => 'required_if:provider,local|nullable|string|min:6|confirmed',
            'user_type' => 'required|in:user,admin',
            'division' => 'required|string|max:100',
        ]);

        $isAdmin = $validated['user_type'] === 'admin';

        // Double Security Check: Pastikan email belum terdaftar sebagai tipe lain
        $existingUser = User::where('email', $validated['email'])->first();
        if ($existingUser) {
            return back()->with('error', 'Email sudah terdaftar di sistem.')->withInput();
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nip' => $validated['nip'],
            'provider' => $validated['provider'],
            'password' => $validated['provider'] === 'local' ? Hash::make($validated['password']) : null,
            'role' => $isAdmin ? 'admin' : 'pegawai',
            'is_admin' => $isAdmin,
            'division' => $validated['division'],
        ]);

        $message = $isAdmin ? 'Admin berhasil ditambahkan.' : 'Pegawai berhasil ditambahkan.';
        
        return redirect()->route('admin.users')->with('success', $message);
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        $currentAdminId = Session::get('user_id');
        if ($user->is_admin) {
            if ((int) $user->id === (int) $currentAdminId) {
                return redirect()->route('admin.users')->with('error', 'Tidak dapat menghapus akun admin yang sedang login.');
            }
            $adminCount = User::where('is_admin', true)->count();
            if ($adminCount <= 1) {
                return redirect()->route('admin.users')->with('error', 'Minimal satu admin harus tetap ada.');
            }
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Akun berhasil dihapus.');
    }

    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->is_admin = !$user->is_admin;
        $user->role = $user->is_admin ? 'admin' : 'user';
        $user->save();

        return redirect()->route('admin.users')
            ->with('success', 'Status admin berhasil diubah');
    }

    public function employees(Request $request)
    {
        $query = User::where('is_admin', false)
            ->withCount('journals')
            ->withMax('journals as last_journal_at', 'created_at')
            ->withCount([
                'journals as received_journals_count' => function ($query) {
                    $query->where('received_by_admin', true);
                }
            ]);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('journals_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.employees', compact('employees'));
    }

    public function bulkDeleteUsers(Request $request)
    {
        $userIds = json_decode($request->input('user_ids', '[]'));
        if (empty($userIds)) {
            return redirect()->back()->with('error', 'Tidak ada akun yang dipilih');
        }

        // Jangan hapus diri sendiri (admin yang sedang login)
        $currentUserId = Session::get('user_id');
        $userIds = array_diff($userIds, [$currentUserId]);

        if (empty($userIds)) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus diri sendiri.');
        }

        $count = User::whereIn('id', $userIds)->delete();

        return redirect()->back()->with('success', "Berhasil menghapus {$count} akun.");
    }

    public function employeeDetail(Request $request, $id)
    {
        $employee = User::where('is_admin', false)->findOrFail($id);
        $adminId = Session::get('user_id');

        $query = Journal::with(['user', 'admins'])
            ->where('user_id', $employee->id)
            ->whereHas('admins', function($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            });

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('no', 'like', "%{$search}%")
                  ->orWhere('uraian_pekerjaan', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        
        // Allowed sort columns
        $allowedSorts = ['no', 'tanggal', 'created_at', 'received_by_admin'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $journals = $query->paginate(20)->withQueryString();

        return view('admin.employee-detail', compact('employee', 'journals'));
    }

    public function profile()
    {
        $userId = Session::get('user_id');
        $admin = User::withCount('journals')->findOrFail($userId);

        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $userId = Session::get('user_id');
        $admin = User::findOrFail($userId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:users,nip,'.$admin->id,
            'division' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
        ]);

        $admin->name = $validated['name'];
        $admin->nip = $validated['nip'];
        $admin->division = $validated['division'];
        $admin->phone = $validated['phone'];
        $admin->address = $validated['address'];
        $admin->save();

        Session::put('user_name', $admin->name);

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateEmail(Request $request)
    {
        $userId = Session::get('user_id');
        $admin = User::findOrFail($userId);

        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:users,email,'.$admin->id,
        ]);

        $admin->email = $validated['email'];
        $admin->save();

        return redirect()->route('admin.profile')->with('success', 'Email berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $userId = Session::get('user_id');
        $admin = User::findOrFail($userId);

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $admin->password)) {
            return redirect()->route('admin.profile')->with('error', 'Password lama salah.');
        }

        $admin->password = Hash::make($validated['password']);
        $admin->save();

        return redirect()->route('admin.profile')->with('success', 'Password berhasil diubah.');
    }

    public function updatePhoto(Request $request)
    {
        $userId = Session::get('user_id');
        $admin = User::findOrFail($userId);

        $validated = $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');

            if ($admin->profile_photo && !str_starts_with($admin->profile_photo, 'http')) {
                if (str_starts_with($admin->profile_photo, 'uploads/')) {
                    $oldPhotoPath = public_path($admin->profile_photo);
                    if (file_exists($oldPhotoPath)) {
                        @unlink($oldPhotoPath);
                    }
                } else {
                    Storage::disk('public')->delete($admin->profile_photo);
                }
            }

            $fileName = 'profile_admin_' . $admin->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile-photos', $fileName, 'public');

            $admin->profile_photo = $path;
            $admin->save();

            Session::put('admin_profile_photo', $admin->profile_photo);
            Session::put('user_photo', $admin->profile_photo);
        }

        return redirect()->route('admin.profile')->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function settings()
    {
        $userId = Session::get('user_id');
        $prefs = \App\Models\NotificationPreference::firstOrCreate(['user_id' => $userId], []);
        return view('admin.settings', compact('prefs'));
    }
    
    public function updateThemeSettings(Request $request)
    {
        $userId = Session::get('user_id');
        $admin = User::findOrFail($userId);
        $validated = $request->validate([
            'theme' => 'required|in:light,dark',
            'default_page' => 'nullable|in:dashboard,notifications',
        ]);
        $admin->theme = $validated['theme'];
        $admin->default_page = $validated['default_page'] ?? null;
        $admin->save();
        Session::put('theme', $admin->theme);
        return redirect()->route('admin.settings')->with('success', 'Pengaturan tema admin diperbarui.');
    }

    public function updateNotificationPreferences(Request $request)
    {
        $userId = Session::get('user_id');
        $prefs = \App\Models\NotificationPreference::firstOrCreate(['user_id' => $userId], []);
        $prefs->new_journal = $request->boolean('new_journal');
        // Admin bisa gunakan preferensi lain bila dibutuhkan nanti
        $prefs->save();
        return redirect()->route('admin.settings')->with('success', 'Preferensi notifikasi admin diperbarui.');
    }

    public function notifications()
    {
        $userId = Session::get('user_id');
        $notifications = \App\Models\Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = \App\Models\Notification::where('user_id', $userId)->where('read', false)->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAllNotificationsAsRead()
    {
        $userId = Session::get('user_id');
        \App\Models\Notification::where('user_id', $userId)
            ->where('read', false)
            ->update(['read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function cleanupOldDocuments(Request $request)
    {
        $threshold = now()->subMonths(3);
        $oldJournals = Journal::whereNotNull('dokumen_pekerjaan')
            ->where('created_at', '<', $threshold)
            ->get();

        $deleted = 0;
        foreach ($oldJournals as $j) {
            $path = $j->dokumen_pekerjaan;
            if ($path && !str_starts_with($path, 'http')) {
                $normalized = $this->normalizeJournalDocPath($path);
                if ($normalized && $this->isImageExtension($normalized)) {
                    try {
                        if (Storage::disk('public')->exists($normalized)) {
                            Storage::disk('public')->delete($normalized);
                            $deleted++;
                        } else {
                            $full = public_path($normalized);
                            if (file_exists($full)) { @unlink($full); $deleted++; }
                        }
                    } catch (\Throwable $e) {
                        // ignore errors
                    }
                    $j->dokumen_pekerjaan = null;
                    $j->save();
                }
            }
        }

        return redirect()->back()->with('success', "Cleanup selesai. Dokumen dihapus: {$deleted}");
    }

    public function exportBackup(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|in:hour,day,week,month',
            'format' => 'required|in:pdf,excel,sql',
            'path' => 'nullable|string',
        ]);

        $period = $validated['period'];
        $format = $validated['format'];
        $path = $validated['path'] ?: storage_path('app/exports');

        if (!file_exists($path)) {
            @mkdir($path, 0755, true);
        }

        $start = match ($period) {
            'hour' => now()->subHour(),
            'day' => now()->subDay(),
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
        };
        $end = now();

        $journals = Journal::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($format === 'excel') {
            $rows = [];
            foreach ($journals as $j) {
                $rows[] = [
                    $j->no ?? $j->id,
                    $j->created_at ? $j->created_at->format('Y-m-d H:i') : '',
                    optional($j->user)->name ?: '-',
                    $j->uraian_pekerjaan ?? $j->content ?? '',
                    $j->dokumen_pekerjaan ?: '',
                    $j->received_by_admin ? 'Diterima' : 'Belum diterima',
                ];
            }
            $header = ['No. Jurnal', 'Waktu Dibuat', 'Nama Pegawai', 'Uraian Pekerjaan', 'Dokumen', 'Status'];
            $filename = 'backup_jurnal_' . $period . '_' . now()->format('Ymd_His') . '.xlsx';
            $subtitle = 'Periode: ' . $start->format('d M Y H:i') . ' s/d ' . $end->format('d M Y H:i');

            $content = $this->renderExcelContent($header, $rows, 'Backup Jurnal', $subtitle);
            file_put_contents(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename, $content);
            return redirect()->back()->with('success', "Backup Excel dibuat: {$filename}");
        }

        if ($format === 'pdf') {
            // Simpan HTML sebagai .html untuk di-print menjadi PDF secara manual
            $html = view('admin.export.journals-detailed-pdf', [
                'admin' => User::find(Session::get('user_id')),
                'period' => $period,
                'header' => ['No. Jurnal', 'Waktu Dibuat', 'Nama Pegawai', 'Uraian Pekerjaan', 'Dokumen', 'Status'],
                'rows' => [], // tabel di view mengambil dari $journals
                'journals' => $journals,
                'rangeStart' => $start,
                'rangeEnd' => $end,
            ])->render();
            $filename = 'backup_jurnal_' . $period . '_' . now()->format('Ymd_His') . '.html';
            file_put_contents(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename, $html);
            return redirect()->back()->with('success', "Backup HTML (untuk PDF) dibuat: {$filename}");
        }

        // SQL dump (basic)
        $filename = 'backup_full_' . $period . '_' . now()->format('Ymd_His') . '.sql';
        $sql = $this->generateSqlDump($start, $end);
        file_put_contents(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename, $sql);
        return redirect()->back()->with('success', "Backup SQL dibuat: {$filename}");
    }

    public function restoreFromSql(Request $request)
    {
        $validated = $request->validate([
            'sql_file' => 'required|file|mimes:sql,txt',
        ]);

        $file = $request->file('sql_file')->getRealPath();
        $sql = file_get_contents($file);
        try {
            DB::unprepared($sql);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Restore gagal: ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Restore berhasil. Database telah ditimpa.');
    }

    private function renderExcelContent(array $header, array $rows, string $title, string $subtitle): string
    {
        $out = "<table border='1' style='border-collapse:collapse;width:100%'><thead><tr>";
        foreach ($header as $h) { $out .= "<th style='background:#4CAF50;color:#fff;padding:8px;border:1px solid #333'>".htmlspecialchars((string)$h)."</th>"; }
        $out .= "</tr></thead><tbody>";
        foreach ($rows as $r) {
            $out .= "<tr>";
            foreach ($r as $i => $c) {
                $label = $header[$i] ?? '';
                if (stripos($label, 'Dokumen') !== false) {
                    $dataUri = $this->getDataUriForImage((string)$c);
                    if ($dataUri) {
                        $out .= "<td style='text-align:center;border:1px solid #333'><img src='{$dataUri}' style='max-width:60px;max-height:60px;border:1px solid #ccc;border-radius:4px' /></td>";
                    } else {
                        $safe = htmlspecialchars((string)$c);
                        $out .= "<td style='text-align:center;border:1px solid #333'>".($safe ? "<a href='{$safe}'>Lihat</a>" : "<span style='color:#777'>-</span>")."</td>";
                    }
                } else {
                    $out .= "<td style='border:1px solid #333;padding:6px 8px;white-space:normal;word-wrap:break-word'>".htmlspecialchars((string)$c)."</td>";
                }
            }
            $out .= "</tr>";
        }
        $out .= "</tbody></table>";
        return $out;
    }

    private function generateSqlDump($start, $end): string
    {
        $tables = ['users', 'journals', 'journal_admin', 'notifications'];
        $dump = "-- BRMP JurnalKU SQL backup\n-- Generated at " . now()->format('Y-m-d H:i:s') . "\n\nSET FOREIGN_KEY_CHECKS=0;\n";
        foreach ($tables as $table) {
            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                $columns = array_keys((array)$row);
                $values = array_map(function($v){
                    if (is_null($v)) return 'NULL';
                    return "'" . str_replace(["\\", "'"], ["\\\\", "\\'"], (string)$v) . "'";
                }, array_values((array)$row));
                $dump .= "INSERT INTO `{$table}` (`" . implode("`,`", $columns) . "`) VALUES (" . implode(",", $values) . ");\n";
            }
            $dump .= "\n";
        }
        $dump .= "SET FOREIGN_KEY_CHECKS=1;\n";
        return $dump;
    }

    private function resolveImageLocalPath(?string $value): ?string
    {
        if (!$value) return null;
        $v = ltrim($value, '/');
        if (preg_match('/^https?:\\/\\//i', $v)) {
            $pathPart = parse_url($v, PHP_URL_PATH) ?: '';
            $ext = strtolower(pathinfo($pathPart, PATHINFO_EXTENSION));
            if (!$ext || !in_array($ext, ['jpg','jpeg','png','gif','webp'], true)) {
                $ext = 'jpg';
            }
            $cacheDir = storage_path('app/tmp-excel-images');
            if (!is_dir($cacheDir)) {
                @mkdir($cacheDir, 0775, true);
            }
            $tmp = $cacheDir . DIRECTORY_SEPARATOR . 'img_' . sha1($v) . '.' . $ext;
            if (!is_file($tmp)) {
                try {
                    $data = @file_get_contents($v, false, stream_context_create([
                        'http' => ['timeout' => 10],
                        'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
                    ]));
                    if (!$data && function_exists('curl_init')) {
                        $ch = curl_init($v);
                        curl_setopt_array($ch, [
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_TIMEOUT => 10,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                        ]);
                        $data = curl_exec($ch);
                        curl_close($ch);
                    }
                    if ($data) {
                        @file_put_contents($tmp, $data);
                    }
                } catch (\Throwable $e) {
                }
            }
            return is_file($tmp) ? $tmp : null;
        }
        if (str_starts_with($v, 'storage/')) {
            $rest = substr($v, 8);
            $cand1 = storage_path('app/public/' . $rest);
            if (file_exists($cand1)) return $cand1;
            $cand2 = public_path($v);
            if (file_exists($cand2)) return $cand2;
        }
        if (str_starts_with($v, 'public/')) {
            $cand = public_path(substr($v, 7));
            if (file_exists($cand)) return $cand;
        }
        if (str_starts_with($v, 'uploads/')) {
            $cand = public_path($v);
            if (file_exists($cand)) return $cand;
        }
        $normalized = $this->normalizeJournalDocPath($v);
        if ($normalized) {
            try {
                $cand = \Illuminate\Support\Facades\Storage::disk('public')->path($normalized);
                if (file_exists($cand)) return $cand;
            } catch (\Throwable $e) {
            }
            $cand2 = public_path('storage/' . $normalized);
            if (file_exists($cand2)) return $cand2;
            $cand3 = public_path($normalized);
            if (file_exists($cand3)) return $cand3;
        }
        $cand4 = public_path($v);
        if (file_exists($cand4)) return $cand4;
        return null;
    }

    private function normalizeJournalDocPath(string $path): ?string
    {
        $p = ltrim($path, '/');
        if (str_starts_with($p, 'storage/')) {
            $p = substr($p, strlen('storage/'));
        }
        if (str_starts_with($p, 'public/')) {
            $p = substr($p, strlen('public/'));
        }
        if (!str_contains($p, 'journal-documents')) {
            return null;
        }
        return $p;
    }

    private function isImageExtension(string $path): bool
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg','jpeg','png','gif','webp'], true);
    }

    private function getDataUriForImage(?string $path): ?string
    {
        if (!$path) return null;
        $normalized = $this->normalizeJournalDocPath($path);
        if (!$normalized || !$this->isImageExtension($normalized)) return null;

        try {
            if (Storage::disk('public')->exists($normalized)) {
                $contents = Storage::disk('public')->get($normalized);
            } else {
                $full = public_path($normalized);
                if (!file_exists($full)) return null;
                $contents = @file_get_contents($full);
            }
            if (!$contents) return null;
            $ext = strtolower(pathinfo($normalized, PATHINFO_EXTENSION));
            $mime = match ($ext) {
                'jpg','jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                default => 'application/octet-stream',
            };
            $base64 = base64_encode($contents);
            return "data:{$mime};base64,{$base64}";
        } catch (\Throwable $e) {
            return null;
        }
    }
}
