<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - JurnalBRMP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        
        .fade-in-up {
            animation: slideInUp 0.6s ease-out forwards;
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .stat-gradient {
            background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
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
            .mobile-compact .py-12 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
        }
    </style>
</head>
<body class="min-h-screen mobile-compact">
    @include('layouts.user_navbar')

    <main class="max-container px-4 sm:px-6 lg:px-8 py-12 pb-32 text-sm md:text-base">
        <div class="flex justify-between items-center mb-8 fade-in-up">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Semua Notifikasi</h1>
            @if($unreadCount > 0)
                <button onclick="markAllNotificationsAsRead()" class="text-sm font-semibold text-[#4A72FF] dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                    <i class="fas fa-check-double mr-2"></i>Tandai semua dibaca
                </button>
            @endif
        </div>

        <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-xl fade-in-up" style="animation-delay: 0.1s">
            @if($notifications->count() > 0)
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($notifications as $notification)
                        <div class="p-6 flex items-start hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ !$notification->read ? 'bg-blue-50/50 dark:bg-blue-900/20' : '' }}">
                            <div class="flex-shrink-0 mt-1">
                                @switch($notification->type)
                                    @case('received')
                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg"></i>
                                        </div>
                                        @break
                                    @case('rejected')
                                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-times-circle text-orange-600 dark:text-orange-400 text-lg"></i>
                                        </div>
                                        @break
                                    @case('revised')
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-edit text-blue-600 dark:text-blue-400 text-lg"></i>
                                        </div>
                                        @break
                                    @default
                                        <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-bell text-gray-600 dark:text-gray-400 text-lg"></i>
                                        </div>
                                @endswitch
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-gray-900 dark:text-gray-100 font-medium leading-relaxed">{{ $notification->message }}</p>
                                <div class="flex items-center mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    <i class="far fa-clock mr-1.5"></i>
                                    {{ $notification->created_at->format('d M Y, H:i') }}
                                    <span class="mx-2 text-gray-300 dark:text-gray-600">•</span>
                                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @if(!$notification->read)
                                <div class="ml-4 flex-shrink-0">
                                    <div class="w-3 h-3 bg-[#4A72FF] dark:bg-blue-500 rounded-full shadow-lg shadow-blue-500/50"></div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                @if($notifications->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-20">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-gray-400 dark:text-gray-500 text-3xl"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada notifikasi untuk Anda.</p>
                </div>
            @endif
        </div>
    </main>

    <script>
        function markAllNotificationsAsRead() {
            fetch('{{ route('user.notifications.read-all') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
