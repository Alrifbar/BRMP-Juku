<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Journal Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/presentation.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pulse-soft {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        
        .fade-in-up {
            animation: slideInUp 0.6s ease-out forwards;
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .glass-morphism {
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .executive-card {
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        
        .executive-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #4A72FF;
        }
        
        .stat-gradient {
            background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #4A72FF;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover {
            color: #4A72FF;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }

        /* Responsive max-width adjustments */
        .max-container {
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 640px) {
            .mobile-compact h1 { font-size: 1.1rem !important; }
            .mobile-compact h2 { font-size: 1rem !important; }
            .mobile-compact h3 { font-size: 0.9rem !important; }
            .mobile-compact p, .mobile-compact span, .mobile-compact div, .mobile-compact a, .mobile-compact button { font-size: 0.75rem !important; }
            .mobile-compact .text-xs { font-size: 0.65rem !important; }
            .mobile-compact .text-lg { font-size: 0.85rem !important; }
            .mobile-compact .text-xl { font-size: 0.9rem !important; }
            .mobile-compact .text-2xl { font-size: 1rem !important; }
            .mobile-compact .text-3xl { font-size: 1.1rem !important; }
            .mobile-compact .p-6 { padding: 1rem !important; }
            .mobile-compact .p-8 { padding: 1.25rem !important; }
            .mobile-compact .px-4 { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
            .mobile-compact .py-8 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
            .mobile-compact .gap-6 { gap: 0.75rem !important; }
            
            /* Specific fix for metric values */
            .mobile-compact .metric-value { font-size: 1.25rem !important; }
        }
    </style>
</head>
<body class="min-h-screen mobile-compact">
    @include('layouts.user_navbar')

    <!-- Main Content -->
    <main class="max-container py-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-8 text-sm md:text-base">
        <!-- Header -->
        <div class="mb-8 fade-in">
            <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Dashboard Overview</h2>
                        <p class="text-gray-600 dark:text-gray-400">Your journal activity and statistics</p>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-xl">
                        <i class="fas fa-chart-line text-gray-600 dark:text-gray-300 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="grid grid-cols-1 gap-6 mb-8">
            <!-- Notifications Section -->
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-bell mr-2 text-gray-600 dark:text-gray-400"></i>
                        Notifikasi
                        @if($unreadCount > 0)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                                {{ $unreadCount }} baru
                            </span>
                        @endif
                    </h3>
                    @if($unreadCount > 0)
                        <button onclick="markAllNotificationsAsRead()" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                            <i class="fas fa-check-double mr-1"></i>Tandai semua dibaca
                        </button>
                    @endif
                </div>
                
                @if($unreadNotifications->count() > 0)
                    <div class="space-y-3">
                        @foreach($unreadNotifications as $notification)
                            <div class="notification-item flex items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                                 onclick="markNotificationAsRead({{ $notification->id }}, this)">
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
                                    @endswitch
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bell text-gray-400 dark:text-gray-500 text-xl"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada notifikasi</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Anda akan mendapat notifikasi saat admin memproses jurnal Anda</p>
                    </div>
                @endif
            </div>


        </div>
        <!-- Key Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-gradient p-3 rounded-xl">
                        <i class="fas fa-file-alt text-white text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total</span>
                </div>
                <div class="metric-value text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $totalJournals ?? 0 }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Journals</p>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-600 dark:text-gray-300 font-medium">{{ $journalsThisMonth ?? 0 }}</span>
                    <span class="text-gray-500 dark:text-gray-400 ml-1">this month</span>
                </div>
            </div>

            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-gradient p-3 rounded-xl">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Monthly</span>
                </div>
                <div class="metric-value text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $journalsThisMonth ?? 0 }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">This Month</p>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-600 dark:text-gray-300 font-medium">{{ date('F') }}</span>
                    <span class="text-gray-500 dark:text-gray-400 ml-1">activity</span>
                </div>
            </div>

            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-gradient p-3 rounded-xl">
                        <i class="fas fa-calendar-week text-white text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Weekly</span>
                </div>
                <div class="metric-value text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $journalsThisWeek ?? 0 }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">This Week</p>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-600 dark:text-gray-300 font-medium">7 days</span>
                    <span class="text-gray-500 dark:text-gray-400 ml-1">period</span>
                </div>
            </div>

            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-gradient p-3 rounded-xl">
                        <i class="fas fa-user-check text-white text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Status</span>
                </div>
                <div class="metric-value text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $receivedJournals ?? 0 }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Admin Received</p>
                <div class="mt-4 flex items-center text-sm">
                    @if(($pendingJournals ?? 0) > 0)
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 px-2 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-clock mr-1"></i>{{ $pendingJournals }} pending
                        </div>
                    @else
                        <span class="text-green-600 dark:text-green-400 font-medium">All received</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Statistik Harian (7 Hari Terakhir)</h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Last 7 days</div>
                </div>
                <div class="relative" style="height:300px">
                    <canvas id="userDailyChart"></canvas>
                </div>
            </div>
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Statistik Mingguan (4 Minggu Terakhir)</h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Last 4 weeks</div>
                </div>
                <div class="relative" style="height:300px">
                    <canvas id="userWeeklyChart"></canvas>
                </div>
            </div>
        </div>
        <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 mb-8 fade-in">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Statistik Tahunan</h3>
                <div class="text-sm text-gray-500 dark:text-gray-400">Yearly overview</div>
            </div>
            <div class="relative" style="height:360px">
                <canvas id="userYearlyChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity & Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Journals -->
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.5s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Journals</h3>
                    <a href="{{ route('user.journals.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">
                        View all <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="space-y-4">
                    @if(isset($recentJournals) && $recentJournals->count() > 0)
                        @foreach($recentJournals as $journal)
                            <a href="{{ route('user.journals.show', $journal) }}" class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-file-alt text-gray-600 dark:text-gray-300"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">Journal #{{ $journal->no ?? $journal->id }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $journal->created_at?->setTimezone('Asia/Jakarta')->format('d M Y') ?? 'No date' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($journal->received_by_admin ?? false)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                            <i class="fas fa-check mr-1"></i>Received
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-file-alt text-gray-400 dark:text-gray-500 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">No journals yet</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Create your first journal to get started</p>
                            <a href="{{ route('user.journals.create') }}" class="inline-flex items-center mt-4 stat-gradient text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition-all duration-300">
                                <i class="fas fa-plus mr-2"></i>Create Journal
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6 fade-in" style="animation-delay: 0.6s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Quick Actions</h3>
                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg">
                        <i class="fas fa-bolt text-gray-600 dark:text-gray-300"></i>
                    </div>
                </div>
                <div class="space-y-4">
                    <a href="{{ route('user.journals.create') }}" 
                       class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="stat-gradient p-2 rounded-lg">
                                <i class="fas fa-plus text-white"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">Create New Journal</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Add your daily activity</p>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300"></i>
                    </a>
                    
                    <a href="{{ route('user.journals.index') }}" 
                       class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="bg-gray-200 dark:bg-gray-600 p-2 rounded-lg">
                                <i class="fas fa-history text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">View All Journals</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Browse your history</p>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Notification functions
        function markNotificationAsRead(notificationId, element) {
            fetch(`/user/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the notification element with fade out
                    element.style.transition = 'opacity 0.3s ease-out';
                    element.style.opacity = '0';
                    setTimeout(() => {
                        element.remove();
                        
                        // Update unread count
                        const countElement = document.querySelector('.bg-red-100');
                        if (countElement) {
                            const currentCount = parseInt(countElement.textContent);
                            if (currentCount > 1) {
                                countElement.textContent = (currentCount - 1) + ' baru';
                            } else {
                                countElement.remove();
                            }
                        }
                        
                        // Check if no more notifications
                        const notificationsContainer = document.querySelector('.space-y-3');
                        if (notificationsContainer && notificationsContainer.children.length === 0) {
                            location.reload(); // Reload to show empty state
                        }
                    }, 300);
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }

        function markAllNotificationsAsRead() {
            fetch('/user/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear notifications UI
                    const dropdown = document.querySelector('.notification-dropdown-item')?.parentElement;
                    if (dropdown) dropdown.innerHTML = '<div class="text-center py-6 text-sm text-gray-500">Tidak ada notifikasi</div>';
                    
                    const list = document.querySelector('.space-y-3');
                    if (list) list.innerHTML = '<div class="text-center py-8"><div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-bell text-gray-400 text-xl"></i></div><p class="text-gray-500 font-medium">Tidak ada notifikasi</p></div>';
                    
                    // Reset count
                    const counts = document.querySelectorAll('.bg-red-500, .bg-red-100');
                    counts.forEach(c => c.remove());
                    
                    // Hide mark all read button
                    const markAllBtn = document.querySelector('button[onclick="markAllNotificationsAsRead()"]')?.parentElement;
                    if (markAllBtn) markAllBtn.remove();
                }
            })
            .catch(error => {
                console.error('Error marking all notifications as read:', error);
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dCtx = document.getElementById('userDailyChart');
            if (dCtx) {
                new Chart(dCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json(isset($dailyStats) ? $dailyStats->map(fn($s)=> \Carbon\Carbon::parse($s->date)->format('d M')) : ['No Data']),
                        datasets: [
                            {
                                label: 'Jurnal',
                                data: @json(isset($dailyStats) ? $dailyStats->pluck('count') : [0]),
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.12)',
                                tension: 0.35,
                                fill: true
                            },
                            {
                                label: 'Disetujui',
                                data: @json(isset($dailyStats) ? $dailyStats->pluck('approved_count') : [0]),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.12)',
                                tension: 0.35,
                                fill: true
                            },
                            {
                                label: 'Revisi',
                                data: @json(isset($dailyStats) ? $dailyStats->pluck('revised_count') : [0]),
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
                        plugins: { legend: { display: true, position: 'top' } },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } }
                        }
                    }
                });
            }

            const wCtx = document.getElementById('userWeeklyChart');
            if (wCtx) {
                new Chart(wCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json(isset($weeklyStats) ? $weeklyStats->map(function($s){ 
                            return 'Minggu ' . \Carbon\Carbon::parse($s->week_start)->format('d M') . ' - ' . \Carbon\Carbon::parse($s->week_end)->format('d M'); 
                        }) : ['No Data']),
                        datasets: [
                            {
                                label: 'Jurnal',
                                data: @json(isset($weeklyStats) ? $weeklyStats->pluck('count') : [0]),
                                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                borderColor: 'rgb(16, 185, 129)',
                                borderWidth: 2,
                                borderRadius: 8
                            },
                            {
                                label: 'Disetujui',
                                data: @json(isset($weeklyStats) ? $weeklyStats->pluck('approved_count') : [0]),
                                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 2,
                                borderRadius: 8
                            },
                            {
                                label: 'Revisi',
                                data: @json(isset($weeklyStats) ? $weeklyStats->pluck('revised_count') : [0]),
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
                        plugins: { legend: { display: true, position: 'top' } },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                    }
                });
            }

            const yCtx = document.getElementById('userYearlyChart');
            if (yCtx) {
                new Chart(yCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json(isset($yearlyStats) ? $yearlyStats->pluck('year') : ['No Data']),
                        datasets: [
                            {
                                label: 'Jurnal',
                                data: @json(isset($yearlyStats) ? $yearlyStats->pluck('count') : [0]),
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.12)',
                                tension: 0.35,
                                fill: true
                            },
                            {
                                label: 'Disetujui',
                                data: @json(isset($yearlyStats) ? $yearlyStats->pluck('approved_count') : [0]),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.12)',
                                tension: 0.35,
                                fill: true
                            },
                            {
                                label: 'Revisi',
                                data: @json(isset($yearlyStats) ? $yearlyStats->pluck('revised_count') : [0]),
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
                        plugins: { legend: { display: true, position: 'top' } },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                    }
                });
            }
        });
    </script>

</body>
</html>
