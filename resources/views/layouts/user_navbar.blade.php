<!-- Desktop Navigation -->
<script>
tailwind.config = {
  darkMode: 'class'
}
</script>
<script>
// Apply persisted theme globally on every page
(function(){
  try {
    var theme = "{{ Session::get('theme', 'light') }}";
    document.documentElement.classList.remove('dark');
    if(theme === 'dark'){ document.documentElement.classList.add('dark'); }
  } catch(e){}
})();
</script>
<style>
/* Global dark mode overrides for common utility classes */
.dark body { background-color: #111827 !important; color: #e5e7eb !important; }

.dark .executive-card { background: #1f2937 !important; border-color: #374151 !important; }
.dark .bg-white { background-color: #1f2937 !important; }
.dark .bg-gray-50 { background-color: #111827 !important; }
.dark .bg-gray-100 { background-color: #1f2937 !important; }
.dark .text-gray-900 { color: #f3f4f6 !important; }
.dark .text-gray-800 { color: #f3f4f6 !important; }
.dark .text-gray-700 { color: #e5e7eb !important; }
.dark .text-gray-600 { color: #d1d5db !important; }
.dark .text-gray-500 { color: #9ca3af !important; }
.dark .border-gray-200 { border-color: #374151 !important; }
.dark .border-gray-100 { border-color: #4b5563 !important; }
.dark .hover\:bg-gray-100:hover { background-color: #374151 !important; }

/* Light mode overrides to ensure high contrast */
html:not(.dark) body { background-color: #f8fafc !important; color: #0f172a !important; }
html:not(.dark) .executive-card { background-color: #ffffff !important; border-color: #e2e8f0 !important; color: #0f172a !important; }
html:not(.dark) .bg-white { background-color: #ffffff !important; }
html:not(.dark) .bg-gray-50 { background-color: #f9fafb !important; }
html:not(.dark) .bg-gray-100 { background-color: #f3f4f6 !important; }
html:not(.dark) .bg-blue-50 { background-color: #eff6ff !important; }
html:not(.dark) .bg-green-50 { background-color: #f0fdf4 !important; }
html:not(.dark) .bg-green-100 { background-color: #dcfce7 !important; }
html:not(.dark) .bg-red-100 { background-color: #fee2e2 !important; }
html:not(.dark) .bg-yellow-100 { background-color: #fef9c3 !important; }
html:not(.dark) .bg-yellow-400 { background-color: #facc15 !important; }
html:not(.dark) .bg-green-400 { background-color: #4ade80 !important; }
html:not(.dark) .text-green-600 { color: #16a34a !important; }
html:not(.dark) .text-green-800 { color: #166534 !important; }
html:not(.dark) .text-green-900 { color: #14532d !important; }
html:not(.dark) .text-red-800 { color: #991b1b !important; }
html:not(.dark) .text-red-900 { color: #7f1d1d !important; }
html:not(.dark) .text-yellow-800 { color: #854d0e !important; }
html:not(.dark) .text-yellow-900 { color: #713f12 !important; }
html:not(.dark) .text-gray-500 { color: #64748b !important; }
html:not(.dark) .text-gray-900 { color: #0f172a !important; }
html:not(.dark) .text-gray-800 { color: #1e293b !important; }
html:not(.dark) .text-gray-700 { color: #334155 !important; }
html:not(.dark) .text-gray-600 { color: #475569 !important; }
html:not(.dark) .border-gray-200 { border-color: #e2e8f0 !important; }
html:not(.dark) .border-gray-100 { border-color: #f1f5f9 !important; }
html:not(.dark) .hover\:bg-gray-50:hover { background-color: #f1f5f9 !important; }
html:not(.dark) .hover\:bg-blue-50\/50:hover { background-color: #eff6ff !important; }

/* Mobile compactness and consistency */
@media (max-width: 768px) {
    .mobile-compact .executive-card { padding: 1.25rem !important; }
    .mobile-compact h2 { font-size: 1.5rem !important; }
    .mobile-compact p { font-size: 0.875rem !important; }
    .mobile-compact i.text-3xl { font-size: 1.5rem !important; }
    .mobile-compact .p-8 { padding: 1.25rem !important; }
    .mobile-compact .p-4 { padding: 0.75rem !important; }
}
</style>
<nav class="hidden md:block sticky top-0 z-50 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <!-- Logo Section (Kiri) -->
            <div class="flex items-center">
                <a href="{{ route('user.home') }}" class="flex items-center space-x-4">
                    <img src="{{ asset('images/logobrmp.png') }}" alt="BRMP Logo" class="w-10 h-10 rounded-xl shadow-lg shadow-blue-500/30 object-contain" />
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">Jurnal<span class="text-[#4A72FF]">BRMP</span></h1>
                        <p class="text-[10px] text-blue-500 font-medium tracking-wider">BRMP JOURNAL MANAGEMENT SYSTEM</p>
                    </div>
                </a>
            </div>

            <!-- Menu & Profile Section (Kanan) -->
            <div class="flex items-center space-x-8">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('user.home') }}" class="nav-link {{ request()->routeIs('user.home') ? 'text-[#4A72FF] font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium' }} px-1 py-2 text-sm">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('user.journals.index') }}" class="nav-link {{ request()->routeIs('user.journals.index') ? 'text-[#4A72FF] font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium' }} px-1 py-2 text-sm">
                        <i class="fas fa-layer-group mr-2"></i>Riwayat
                    </a>
                    <a href="{{ route('user.journals.create') }}" class="bg-[#4A72FF] hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-0.5 font-medium text-sm">
                        <i class="fas fa-plus mr-2"></i>Buat Jurnal
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Notification Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative">
                            <i class="fas fa-bell text-gray-600 dark:text-gray-400"></i>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5">{{ $unreadCount }}</span>
                            @endif
                        </button>
                        <!-- ... (dropdown content unchanged) ... -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 max-h-96 overflow-y-auto z-50">
                            <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                <span>Notifikasi</span>
                                <a href="{{ route('user.notifications.index') }}" class="text-[#4A72FF] hover:underline normal-case font-bold">Lihat Semua</a>
                            </div>
                            @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                <div class="py-1">
                                    @foreach($unreadNotifications as $notification)
                                        <div class="notification-dropdown-item flex items-start px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
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
                                <div class="text-center py-6 text-sm text-gray-500 dark:text-gray-400">Tidak ada notifikasi</div>
                            @endif
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <div class="px-3 py-2 border-t border-gray-100 dark:border-gray-700">
                                    <button onclick="markAllNotificationsAsRead()" class="w-full text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">Tandai semua dibaca</button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Settings Icon (Desktop) -->
                    <a href="{{ route('user.settings') }}" class="p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition-colors">
                        <i class="fas fa-cog"></i>
                    </a>

                    <!-- Profile Section (Desktop) -->
                    <a href="{{ route('user.profile.index') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity pl-4 border-l border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ $user->name }}</span>
                        @if($user->profile_photo)
                            <img src="{{ str_starts_with($user->profile_photo, 'http') ? $user->profile_photo : (str_starts_with($user->profile_photo, 'uploads/') ? asset($user->profile_photo) : asset('storage/'.$user->profile_photo)) }}" 
                                 alt="Profile Photo" 
                                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700 shadow-sm">
                        @else
                            <div class="w-10 h-10 stat-gradient rounded-full flex items-center justify-center text-white font-semibold shadow-sm text-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </a>
                </div>
            </div>
</div>
<script>
// Web Push registration for users
(function(){
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;
    const vapidPublicKey = "{{ env('VAPID_PUBLIC_KEY', '') }}";
    if (!vapidPublicKey) return;
    const csrf = "{{ csrf_token() }}";
    const urlBase64ToUint8Array = (base64String) => {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
    };
    navigator.serviceWorker.register('/sw.js').then(async reg => {
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return;
        const sub = await reg.pushManager.getSubscription();
        if (!sub) {
            const newSub = await reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
            });
            await fetch("{{ route('user.push-subscriptions.store') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json','X-CSRF-TOKEN': csrf },
                body: JSON.stringify({
                    endpoint: newSub.endpoint,
                    publicKey: btoa(String.fromCharCode.apply(null, new Uint8Array(newSub.getKey('p256dh')))),
                    authToken: btoa(String.fromCharCode.apply(null, new Uint8Array(newSub.getKey('auth')))),
                    contentEncoding: (newSub.options && newSub.options.applicationServerKey) ? 'aesgcm' : 'aes128gcm'
                })
            });
        }
    }).catch(()=>{});
})();
</script>
    </div>
</nav>

<!-- Mobile Top Navigation -->
<nav class="md:hidden sticky top-0 z-50 px-4 py-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <!-- Notification Icon -->
            <a href="{{ route('user.notifications.index') }}" class="p-2 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 relative">
                <i class="fas fa-bell text-sm"></i>
                @if(isset($unreadCount) && $unreadCount > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-gray-800"></span>
                @endif
            </a>
            <!-- Settings Icon -->
            <a href="{{ route('user.settings') }}" class="p-2 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <i class="fas fa-cog text-sm"></i>
            </a>
        </div>
        
        <div class="flex items-center space-x-2">
            <div class="text-right">
                <h2 class="text-xs font-bold text-gray-800 dark:text-gray-100 leading-tight">Jurnal<span class="text-[#4A72FF]">BRMP</span></h2>
                <p class="text-[8px] text-gray-500 dark:text-gray-400 font-medium">MANAGEMENT SYSTEM</p>
            </div>
            <img src="{{ asset('images/logobrmp.png') }}" alt="Logo" class="w-7 h-7 object-contain">
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 z-50 px-4 py-3">
    <div class="flex items-center justify-around">
        <a href="{{ route('user.home') }}" class="flex flex-col items-center {{ request()->routeIs('user.home') ? 'text-[#4A72FF]' : 'text-gray-400 dark:text-gray-500' }}">
            <i class="fas fa-home text-4xl"></i>
        </a>
        <a href="{{ route('user.journals.index') }}" class="flex flex-col items-center {{ request()->routeIs('user.journals.index*') ? 'text-[#4A72FF]' : 'text-gray-400 dark:text-gray-500' }}">
            <i class="fas fa-layer-group text-4xl"></i>
        </a>
        <a href="{{ route('user.journals.create') }}" class="flex flex-col items-center {{ request()->routeIs('user.journals.create') ? 'text-[#4A72FF]' : 'text-gray-400 dark:text-gray-500' }}">
            <i class="fas fa-plus-circle text-4xl"></i>
        </a>
        <a href="{{ route('user.profile.index') }}" class="flex flex-col items-center">
            @if($user->profile_photo)
                <img src="{{ str_starts_with($user->profile_photo, 'http') ? $user->profile_photo : (str_starts_with($user->profile_photo, 'uploads/') ? asset($user->profile_photo) : asset('storage/'.$user->profile_photo)) }}" 
                     class="w-12 h-12 rounded-full object-cover border-2 {{ request()->routeIs('user.profile.index*') ? 'border-[#4A72FF]' : 'border-gray-200 dark:border-gray-600' }}">
            @else
                <div class="w-12 h-12 stat-gradient rounded-full flex items-center justify-center text-white text-xs font-bold border-2 {{ request()->routeIs('user.profile.index*') ? 'border-[#4A72FF]' : 'border-white dark:border-gray-700' }}">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </a>
    </div>
</div>
