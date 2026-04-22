<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Dashboard - Journal Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .executive-card {
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .stat-gradient {
            background: linear-gradient(135deg, #4A72FF 0%, #00C6FF 100%);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .executive-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(74, 114, 255, 0.1), 0 10px 10px -5px rgba(74, 114, 255, 0.04);
            border-color: #4A72FF;
        }
        
        .metric-value {
            font-variant-numeric: tabular-nums;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .chart-container canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>
<script>
(function(){
  try {
    var theme = "{{ Session::get('theme', 'light') }}";
    document.documentElement.classList.remove('dark');
    if(theme === 'dark'){ document.documentElement.classList.add('dark'); }
  } catch(e){}
})();
</script>
<body class="min-h-screen">
    @include('layouts.admin_navbar')

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 pb-24 md:pb-8 text-sm md:text-base">
        <!-- Header -->
        <div class="mb-8 fade-in">
            <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Analytics Overview</h2>
                        <p class="text-gray-600 dark:text-gray-400">Comprehensive insights into journal management system performance</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.export.options') }}" class="stat-gradient text-white px-6 py-2.5 rounded-xl hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-300 flex items-center whitespace-nowrap font-medium">
                            <i class="fas fa-download mr-2"></i>
                            Export Data
                        </a>
                        
                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-xl hidden sm:block">
                            <i class="fas fa-chart-line text-gray-600 dark:text-gray-300 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 gap-6 mb-8">
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-bell mr-2 text-gray-600 dark:text-gray-400"></i>
                        Notifikasi
                        @if(($unreadCount ?? 0) > 0)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                                {{ $unreadCount }} baru
                            </span>
                        @endif
                    </h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.notifications.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Lihat semua <i class="fas fa-arrow-right ml-1"></i></a>
                        @if(($unreadCount ?? 0) > 0)
                            <button onclick="markAllAdminNotificationsAsRead()" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                <i class="fas fa-check-double mr-1"></i>Tandai semua dibaca
                            </button>
                        @endif
                    </div>
                </div>
                @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                    <div class="space-y-3">
                        @foreach($unreadNotifications as $notification)
                            <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex-shrink-0 mt-1">
                                    @switch($notification->type)
                                        @case('received')
                                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                                            </div>
                                            @break
                                        @case('rejected')
                                            <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                                                <i class="fas fa-times text-orange-600 dark:text-orange-400 text-sm"></i>
                                            </div>
                                            @break
                                        @case('revised')
                                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                <i class="fas fa-edit text-blue-600 dark:text-blue-400 text-sm"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                <i class="fas fa-bell text-gray-600 dark:text-gray-300 text-sm"></i>
                                            </div>
                                    @endswitch
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                @if($notification->journal_id)
                                    <a href="{{ route('admin.posts.show', $notification->journal_id) }}" class="text-xs text-[#4A72FF] hover:underline ml-3 whitespace-nowrap">Lihat</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bell text-gray-400 dark:text-gray-500 text-xl"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada notifikasi</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Anda akan mendapat notifikasi terkait proses jurnal</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-gradient p-3 rounded-xl">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Active</span>
                </div>
                <div class="metric-value text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $totalUsers ?? 0 }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Regular Users</p>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-600 dark:text-gray-300 font-medium">{{ $totalAdmins ?? 0 }}</span>
                    <span class="text-gray-500 dark:text-gray-400 ml-1">administrators</span>
                </div>
            </div>

            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-gradient p-3 rounded-xl">
                        <i class="fas fa-file-alt text-white text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Archive</span>
                </div>
                <div class="metric-value text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $totalJournals ?? 0 }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Journals</p>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-600 dark:text-gray-300 font-medium">{{ $journalsThisMonth ?? 0 }}</span>
                    <span class="text-gray-500 dark:text-gray-400 ml-1">this month</span>
                </div>
            </div>

            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-gradient p-3 rounded-xl">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Growth</span>
                </div>
                <div class="metric-value text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">+{{ $journalsThisMonth ?? 0 }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Monthly Activity</p>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-600 dark:text-gray-300 font-medium">{{ number_format(($journalsThisMonth ?? 0) / max($totalJournals ?? 1, 1) * 100, 1) }}%</span>
                    <span class="text-gray-500 dark:text-gray-400 ml-1">engagement</span>
                </div>
            </div>

            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-gradient p-3 rounded-xl">
                        <i class="fas fa-trophy text-white text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Performance</span>
                </div>
                <div class="metric-value text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $topUsers->count() ?? 0 }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Top Performers</p>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-600 dark:text-gray-300 font-medium">{{ $topUsers->first()?->journals_count ?? 0 }}</span>
                    <span class="text-gray-500 dark:text-gray-400 ml-1">max entries</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Daily Activity Chart -->
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.5s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Statistik Harian (7 Hari Terakhir)</h3>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Last 7 days
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <!-- Weekly Activity Chart -->
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.6s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Statistik Mingguan (4 Minggu Terakhir)</h3>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Last 4 weeks
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Yearly Stats Section -->
        <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 mb-8 fade-in" style="animation-delay: 0.7s">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Statistik Tahunan</h3>
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-chart-pie mr-2"></i>
                    Yearly Overview
                </div>
            </div>
            <div class="chart-container" style="height: 400px;">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity & Top Users -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Journals -->
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.7s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Journal Entries</h3>
                    <a href="{{ route('admin.posts') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">
                        View all <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach($recentJournals->take(5) as $journal)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-file-alt text-gray-600 dark:text-gray-300"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">Journal #{{ $journal->no }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $journal->user->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $journal->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $journal->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Performers -->
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.8s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Top Performers</h3>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-trophy mr-2 text-gray-400"></i>
                        This month
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($topUsers->take(5) as $index => $user)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors overflow-hidden">
                            <div class="flex items-center space-x-4 min-w-0">
                                <span class="text-sm font-bold text-gray-400 w-4">{{ $index + 1 }}</span>
                                <div class="relative flex-shrink-0">
                                    @if($user->profile_photo)
                                        <img src="{{ str_starts_with($user->profile_photo, 'http') ? $user->profile_photo : (str_starts_with($user->profile_photo, 'uploads/') ? asset($user->profile_photo) : asset('storage/'.$user->profile_photo)) }}" 
                                             class="w-10 h-10 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow-sm" />
                                    @else
                                        <div class="w-10 h-10 stat-gradient rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    @if($index === 0)
                                        <div class="absolute -top-1 -right-1 bg-yellow-400 text-white w-4 h-4 rounded-full flex items-center justify-center text-[8px] border border-white dark:border-gray-800">
                                            <i class="fas fa-crown"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->journals_count }} entries this month</p>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="bg-blue-50 dark:bg-blue-900/30 text-[#4A72FF] dark:text-blue-400 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ number_format(($user->journals_count / max($totalJournals ?? 1, 1)) * 100, 1) }}%
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
                        <script>
        // Chart.js Configuration
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.color = '#6b7280';
        
        document.addEventListener('DOMContentLoaded', function() {
            // Daily Activity Chart
            const dailyCtx = document.getElementById('dailyChart');
            if (dailyCtx) {
                new Chart(dailyCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json(isset($dailyStats) && $dailyStats->count() > 0 ? $dailyStats->map(function($stat) { 
                            return \Carbon\Carbon::parse($stat->date)->format('d M'); 
                        }) : ['No Data']),
                        datasets: [
                            {
                                label: 'Jurnal',
                                data: @json(isset($dailyStats) && $dailyStats->count() > 0 ? $dailyStats->pluck('count') : [0]),
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.35,
                                fill: true
                            },
                            {
                                label: 'Disetujui',
                                data: @json(isset($dailyStats) && $dailyStats->count() > 0 ? $dailyStats->pluck('approved_count') : [0]),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.12)',
                                tension: 0.35,
                                fill: true
                            },
                            {
                                label: 'Revisi',
                                data: @json(isset($dailyStats) && $dailyStats->count() > 0 ? $dailyStats->pluck('revised_count') : [0]),
                                borderColor: 'rgb(249, 115, 22)',
                                backgroundColor: 'rgba(249, 115, 22, 0.12)',
                                tension: 0.35,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // Weekly Activity Chart
            const weeklyCtx = document.getElementById('weeklyChart');
            if (weeklyCtx) {
                new Chart(weeklyCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json(isset($weeklyStats) && $weeklyStats->count() > 0 ? $weeklyStats->map(function($stat) { 
                            return 'Minggu ' . \Carbon\Carbon::parse($stat->week_start)->format('d M') . ' - ' . \Carbon\Carbon::parse($stat->week_end)->format('d M'); 
                        }) : ['No Data']),
                        datasets: [
                            {
                                label: 'Jurnal',
                                data: @json(isset($weeklyStats) && $weeklyStats->count() > 0 ? $weeklyStats->pluck('count') : [0]),
                                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                borderColor: 'rgb(16, 185, 129)',
                                borderWidth: 2,
                                borderRadius: 8
                            },
                            {
                                label: 'Disetujui',
                                data: @json(isset($weeklyStats) && $weeklyStats->count() > 0 ? $weeklyStats->pluck('approved_count') : [0]),
                                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 2,
                                borderRadius: 8
                            },
                            {
                                label: 'Revisi',
                                data: @json(isset($weeklyStats) && $weeklyStats->count() > 0 ? $weeklyStats->pluck('revised_count') : [0]),
                                backgroundColor: 'rgba(249, 115, 22, 0.8)',
                                borderColor: 'rgb(249, 115, 22)',
                                borderWidth: 2,
                                borderRadius: 8
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // Yearly Stats Chart
            const yearlyCtx = document.getElementById('yearlyChart');
            if (yearlyCtx) {
                new Chart(yearlyCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json(isset($yearlyStats) && $yearlyStats->count() > 0 ? $yearlyStats->pluck('year') : ['No Data']),
                        datasets: [
                            {
                                label: 'Jurnal',
                                data: @json(isset($yearlyStats) && $yearlyStats->count() > 0 ? $yearlyStats->pluck('count') : [0]),
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.35,
                                fill: true
                            },
                            {
                                label: 'Disetujui',
                                data: @json(isset($yearlyStats) && $yearlyStats->count() > 0 ? $yearlyStats->pluck('approved_count') : [0]),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.12)',
                                tension: 0.35,
                                fill: true
                            },
                            {
                                label: 'Revisi',
                                data: @json(isset($yearlyStats) && $yearlyStats->count() > 0 ? $yearlyStats->pluck('revised_count') : [0]),
                                borderColor: 'rgb(249, 115, 22)',
                                backgroundColor: 'rgba(249, 115, 22, 0.12)',
                                tension: 0.35,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah'
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    <script>
        function markAllAdminNotificationsAsRead() {
            fetch("{{ route('admin.notifications.read-all') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(r => r.ok ? location.reload() : null)
            .catch(()=>{});
        }
    </script>
</body>
</html>
