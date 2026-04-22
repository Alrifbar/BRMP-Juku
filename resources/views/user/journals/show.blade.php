<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal #{{ $journal->title }} - Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/presentation.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .action-button {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-button:hover::before {
            left: 100%;
        }
        
        .content-card {
            transition: all 0.3s ease;
        }
        
        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .badge-glow {
            box-shadow: 0 0 15px rgba(147, 51, 234, 0.4);
        }
        
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .max-container {
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 640px) {
            .mobile-compact h1 { font-size: 1.1rem !important; }
            .mobile-compact h2 { font-size: 1rem !important; }
            .mobile-compact h3 { font-size: 0.9rem !important; }
            .mobile-compact p, .mobile-compact span, .mobile-compact div, .mobile-compact a, .mobile-compact button, .mobile-compact label { font-size: 0.75rem !important; }
            .mobile-compact .text-xs { font-size: 0.65rem !important; }
            .mobile-compact .text-lg { font-size: 0.85rem !important; }
            .mobile-compact .text-xl { font-size: 0.9rem !important; }
            .mobile-compact .text-2xl { font-size: 1rem !important; }
            .mobile-compact .text-3xl { font-size: 1.1rem !important; }
            .mobile-compact .p-6 { padding: 1rem !important; }
            .mobile-compact .p-8 { padding: 1.25rem !important; }
            .mobile-compact .px-4 { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
            .mobile-compact .py-8 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
            .mobile-compact .rounded-2xl { border-radius: 0.75rem !important; }
            .mobile-compact .rounded-xl { border-radius: 0.75rem !important; }
            .mobile-compact .gap-6 { gap: 0.75rem !important; }
        }
    </style>
</head>
<body class="min-h-screen mobile-compact">
    @include('layouts.user_navbar')

    <!-- Main Content -->
    <main class="max-container py-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-8 text-sm md:text-base">
        <!-- Back Button -->
        <div class="mb-6 fade-in text-gray-600 dark:text-gray-400">
            <a href="{{ route('user.journals.index') }}" class="inline-flex items-center hover:text-gray-800 dark:hover:text-gray-200 font-medium group transition-all duration-300">
                <i class="fas fa-arrow-left mr-2 text-lg group-hover:-translate-x-2 transition-transform"></i>
                Back to Journal History
            </a>
        </div>

        <!-- Journal Detail -->
        <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl overflow-hidden fade-in">
            <!-- Header -->
            <div class="stat-gradient p-8 text-white">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <h1 class="text-3xl font-bold">Journal #{{ $journal->title }}</h1>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-gray-100">
                            <div class="flex flex-col">
                                <div class="flex items-center mb-1">
                                    <i class="fas fa-user-circle mr-2 text-xl"></i>
                                    <span class="text-lg font-bold">{{ $journal->user->name }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($journal->received_by_admin)
                                        <span class="bg-green-400 text-green-900 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm">
                                            <i class="fas fa-check-circle mr-1.5"></i>Received by Admin
                                        </span>
                                    @else
                                        <span class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm">
                                            <i class="fas fa-clock mr-1.5"></i>Waiting for Approval
                                        </span>
                                    @endif
                                    @if($journal->is_private)
                                        <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm border border-white/20">
                                            <i class="fas fa-lock mr-1.5"></i>Private
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="w-full md:w-auto mt-4 md:mt-0 flex flex-wrap gap-4 pt-2 border-t border-white/10 md:border-0 md:pt-0">
                                <div class="flex items-center bg-white/10 px-3 py-1.5 rounded-xl backdrop-blur">
                                    <i class="fas fa-calendar-alt mr-2 text-blue-200"></i>
                                    <span class="text-sm font-medium">{{ $journal->created_at->setTimezone('Asia/Jakarta')->format('d F Y, H:i') }}</span>
                                </div>
                                @if($journal->received_by_admin && $journal->received_at)
                                    <div class="flex items-center bg-green-400/20 px-3 py-1.5 rounded-xl backdrop-blur border border-green-400/30 text-green-100">
                                        <i class="fas fa-check-double mr-2"></i>
                                        <span class="text-sm font-medium">Received: {{ $journal->received_at->setTimezone('Asia/Jakarta')->format('d F Y, H:i') }}</span>
                                    </div>
                                @endif
                                @if($journal->category)
                                    <span class="bg-white/10 px-3 py-1.5 rounded-xl text-sm font-medium backdrop-blur">
                                        <i class="fas fa-folder mr-1.5 text-blue-200"></i>{{ $journal->category }}
                                    </span>
                                @endif
                                @if($journal->mood)
                                    <span class="bg-white/10 px-3 py-1.5 rounded-xl text-sm font-medium backdrop-blur">
                                        <i class="fas fa-smile mr-1.5 text-blue-200"></i>{{ $journal->mood }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white bg-opacity-20 p-4 rounded-2xl">
                            <i class="fas fa-file-alt text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-8">
                <!-- Evaluators & Status -->
                <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6">
                    <div class="flex items-center mb-6">
                        <div class="stat-gradient p-3 rounded-lg mr-3">
                            <i class="fas fa-user-shield text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Penilaian & Status Admin</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($journal->admins as $admin)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-blue-200 dark:hover:border-blue-500 transition-all">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 stat-gradient rounded-full flex items-center justify-center text-white font-bold mr-3 shadow-sm">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $admin->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Admin Penilai</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    @switch($admin->pivot->status)
                                        @case('approved')
                                            <div class="flex items-center text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-3 py-1.5 rounded-lg border border-green-100 dark:border-green-900/30">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                <span class="text-xs font-bold uppercase">Disetujui</span>
                                            </div>
                                            @break
                                        @case('revised')
                                            <div class="flex items-center text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/20 px-3 py-1.5 rounded-lg border border-orange-100 dark:border-orange-900/30">
                                                <i class="fas fa-edit mr-2"></i>
                                                <span class="text-xs font-bold uppercase">Revisi</span>
                                            </div>
                                            @break
                                        @default
                                            <div class="flex items-center text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600">
                                                <i class="fas fa-hourglass-half mr-2 animate-pulse"></i>
                                                <span class="text-xs font-bold uppercase">Menunggu</span>
                                            </div>
                                    @endswitch
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Work Description -->
                @if($journal->uraian_pekerjaan)
                    <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="stat-gradient p-3 rounded-lg mr-3">
                                <i class="fas fa-briefcase text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Work Description</h3>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                            <div class="prose max-w-none">
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $journal->uraian_pekerjaan }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Document -->
                @if($journal->dokumen_pekerjaan)
                    <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6" x-data="{ showModal: false }">
                        <div class="flex items-center mb-4">
                            <div class="stat-gradient p-3 rounded-lg mr-3">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Document</h3>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                            <div class="flex flex-col space-y-4">
                                @php 
                                    $doc = trim($journal->dokumen_pekerjaan);
                                    $docPath = str_starts_with($doc, 'http') ? $doc : (str_starts_with($doc, 'uploads/') ? asset($doc) : asset('storage/'.$doc));
                                    $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|jfif)$/i', $doc);
                                    $isPdf = preg_match('/\.pdf$/i', $doc);
                                @endphp

                                @if($isImage)
                                    <div class="relative group cursor-pointer" @click="showModal = true">
                                        <img src="{{ $docPath }}" alt="Document Preview" class="max-h-96 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm transition-transform duration-300 group-hover:scale-[1.01]">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors rounded-xl flex items-center justify-center">
                                            <div class="bg-white/90 dark:bg-gray-800/90 p-3 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                                <i class="fas fa-search-plus text-[#4A72FF] dark:text-blue-400 text-xl"></i>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($isPdf)
                                    <div class="aspect-[4/3] w-full border border-gray-200 dark:border-gray-600 rounded-xl overflow-hidden shadow-sm">
                                        <iframe src="{{ $docPath }}" class="w-full h-full"></iframe>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                        <div class="flex items-center">
                                            <i class="fas fa-file text-3xl text-gray-400 dark:text-gray-500 mr-4"></i>
                                            <p class="text-gray-700 dark:text-gray-300 font-medium truncate max-w-xs">{{ $doc }}</p>
                                        </div>
                                        <a href="{{ $docPath }}" target="_blank" class="stat-gradient text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all duration-300">
                                            <i class="fas fa-download mr-2"></i>Download
                                        </a>
                                    </div>
                                @endif

                                @if($isImage || $isPdf)
                                    <div class="flex justify-end">
                                        <a href="{{ $docPath }}" target="_blank" class="text-sm text-[#4A72FF] dark:text-blue-400 font-semibold hover:underline flex items-center">
                                            <i class="fas fa-external-link-alt mr-2"></i>Buka di Tab Baru
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Floating Large View Modal (Image Only) -->
                        @if($isImage)
                            <template x-teleport="body">
                                <div x-show="showModal" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                                     @keydown.escape.window="showModal = false"
                                     style="display: none;">
                                    <div class="absolute inset-0" @click="showModal = false"></div>
                                    <div class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center justify-center scale-in"
                                         @click.stop>
                                        <button @click="showModal = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-3xl transition-colors">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <img src="{{ $docPath }}" alt="Large Document View" class="max-w-full max-h-full rounded-lg shadow-2xl">
                                        <div class="mt-4 flex space-x-4">
                                            <a href="{{ $docPath }}" download class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-6 py-2 rounded-full font-semibold transition-all flex items-center">
                                                <i class="fas fa-download mr-2"></i>Unduh
                                            </a>
                                            <button @click="showModal = false" class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-6 py-2 rounded-full font-semibold transition-all">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        @endif
                    </div>
                @endif

                <!-- Tags -->
                @if($journal->tags && count($journal->tags) > 0)
                    <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="stat-gradient p-3 rounded-lg mr-3">
                                <i class="fas fa-tags text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tags</h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($journal->tags as $tag)
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm font-medium border border-gray-200 dark:border-gray-600">
                                    <i class="fas fa-hashtag mr-1"></i>{{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Metadata -->
                <div class="executive-card bg-white dark:bg-gray-800 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="stat-gradient p-3 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Created</p>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $journal->created_at->format('d F Y, H:i:s') }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Last Updated</p>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $journal->updated_at->format('d F Y, H:i:s') }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Status</p>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">
                                @if($journal->received_by_admin)
                                    <span class="text-green-600 dark:text-green-400">Received by Admin</span>
                                @else
                                    <span class="text-yellow-600 dark:text-yellow-400">Waiting for Approval</span>
                                @endif
                            </p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Visibility</p>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">
                                @if($journal->is_private)
                                    <span class="text-yellow-600 dark:text-yellow-400">Private</span>
                                @else
                                    <span class="text-gray-600 dark:text-gray-400">Public</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t dark:border-gray-700">
                    <a href="{{ route('user.journals.edit', $journal) }}" 
                       class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition-all duration-300 text-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Journal
                    </a>
                    <form action="{{ route('user.journals.destroy', $journal) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this journal?')" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-700 transition-all duration-300">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Journal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        function markAllNotificationsAsRead() {
            fetch('/user/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value,
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
                    
                    // Reset count
                    const counts = document.querySelectorAll('.bg-red-500');
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

        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.fade-in').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'all 0.8s ease-out';
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
