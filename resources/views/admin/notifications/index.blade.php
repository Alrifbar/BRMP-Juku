<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Admin - JurnalBRMP</title>
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
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .executive-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        
        .stat-gradient {
            background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
        }
        
        .max-container {
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body class="bg-[#EEF2FF] min-h-screen">
    @include('layouts.admin_navbar')

    <main class="max-container py-12 px-4 sm:px-6 lg:px-8 pb-24 md:pb-8 text-sm md:text-base">
        <div class="flex justify-between items-center mb-8 fade-in">
            <h1 class="text-3xl font-bold text-gray-900">Pemberitahuan Jurnal</h1>
            @if($unreadCount > 0)
                <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-[#4A72FF] hover:text-blue-700 transition-colors">
                        <i class="fas fa-check-double mr-2"></i>Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="executive-card rounded-2xl overflow-hidden shadow-xl fade-in" style="animation-delay: 0.1s">
            @if($notifications->count() > 0)
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @php $adminId = Session::get('user_id'); $lastDay = null; @endphp
                    @foreach($notifications as $notification)
                        @php 
                            $dayKey = $notification->created_at->format('Y-m-d'); 
                            $label = $notification->created_at->isToday() ? 'Hari ini' : ($notification->created_at->isYesterday() ? 'Kemarin' : $notification->created_at->format('d M Y'));
                        @endphp
                        @if($lastDay !== $dayKey)
                            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                {{ $label }}
                            </div>
                            @php $lastDay = $dayKey; @endphp
                        @endif
                        <div class="p-6 flex items-start bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ !$notification->read ? 'bg-blue-50/50 dark:bg-blue-900/20 border-l-4 border-l-[#4A72FF] dark:border-l-blue-400' : '' }}" x-data="{ open: false }">
                            <div class="flex-shrink-0 mt-1">
                                @php 
                                    $wrap = ['bg' => 'bg-blue-100', 'icon' => 'fa-file-import', 'txt' => 'text-[#4A72FF]'];
                                    switch($notification->type) {
                                        case 'received': $wrap = ['bg'=>'bg-green-100','icon'=>'fa-check','txt'=>'text-green-600']; break;
                                        case 'revised': $wrap = ['bg'=>'bg-orange-100','icon'=>'fa-edit','txt'=>'text-orange-600']; break;
                                        case 'rejected': $wrap = ['bg'=>'bg-red-100','icon'=>'fa-times','txt'=>'text-red-600']; break;
                                        case 'new_journal_batch': $wrap = ['bg'=>'bg-blue-100','icon'=>'fa-users','txt'=>'text-blue-600']; break;
                                    }
                                @endphp
                                <div class="w-12 h-12 {{ $wrap['bg'] }} rounded-xl flex items-center justify-center {{ $wrap['txt'] }}">
                                    <i class="fas {{ $wrap['icon'] }} text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-gray-900 dark:text-gray-100 font-bold leading-tight mb-1">{{ $notification->message }}</p>
                                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 space-x-3">
                                            <span class="flex items-center"><i class="far fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                            <span class="text-gray-300">•</span>
                                            <span class="flex items-center"><i class="far fa-calendar-alt mr-1"></i> {{ $notification->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        
                                        @if($notification->type === 'new_journal_batch')
                                            @php
                                                $hs = \Carbon\Carbon::parse($notification->created_at)->startOfHour();
                                                $he = (clone $hs)->endOfHour();
                                                $batch = \App\Models\Journal::with('user')
                                                    ->whereBetween('created_at', [$hs, $he])
                                                    ->whereHas('admins', function($q) use ($adminId){ $q->where('admin_id', $adminId); })
                                                    ->orderBy('created_at','desc')->get();
                                                $batchCount = $batch->count();
                                            @endphp
                                            <button type="button" class="mt-3 text-xs font-semibold text-[#4A72FF] hover:underline" @click="open = !open">
                                                <span x-text="open ? 'Sembunyikan detail' : 'Lihat detail'"></span>
                                            </button>
                                            <div x-show="open" x-transition.opacity class="mt-3 space-y-2">
                                                @forelse($batch as $bj)
                                                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                        <div class="text-sm text-gray-700 dark:text-gray-200">
                                                            <span class="font-semibold">{{ $bj->user?->name ?? 'Pegawai' }}</span>
                                                            <span class="mx-1 text-gray-400">•</span>
                                                            <span>{{ $bj->title ?? 'Jurnal' }}</span>
                                                            <span class="mx-1 text-gray-400">•</span>
                                                            <span class="text-xs">{{ $bj->created_at?->format('H:i') }}</span>
                                                        </div>
                                                        @if($batchCount === 1)
                                                            <button type="button" @click="open = false" class="text-xs font-bold text-[#4A72FF] hover:underline">Sembunyikan detail</button>
                                                        @else
                                                            <a href="{{ route('admin.posts.show', $bj->id) }}" class="text-xs font-bold text-[#4A72FF] hover:underline">Lihat Jurnal</a>
                                                        @endif
                                                    </div>
                                                @empty
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Tidak ada rincian jurnal pada jam ini.</div>
                                                @endforelse
                                            </div>
                                        @endif
                                    </div>
                                    @if($notification->journal_id)
                                        <a href="{{ route('admin.posts.show', $notification->journal_id) }}" class="text-xs font-bold text-[#4A72FF] hover:underline bg-blue-50 px-3 py-1.5 rounded-lg transition-all">
                                            Lihat Jurnal
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @if(!$notification->read)
                                <div class="ml-4 flex-shrink-0 self-center">
                                    <div class="w-2.5 h-2.5 bg-[#4A72FF] rounded-full shadow-lg shadow-blue-500/50 animate-pulse"></div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                @if($notifications->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-24">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bell-slash text-gray-300 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">Belum ada notifikasi</h3>
                    <p class="text-gray-500 dark:text-gray-400">Anda akan menerima notifikasi di sini saat ada pegawai yang mengunggah jurnal baru.</p>
                </div>
            @endif
        </div>
    </main>
</body>
</html>
