<!-- Desktop Navigation -->
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
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideIn {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .fade-in {
        animation: fadeIn 0.8s ease-out;
    }
    
    .slide-in {
        animation: slideIn 0.6s ease-out;
    }

    .glass-morphism {
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
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

    .stat-gradient {
        background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
    }
    /* Global dark overrides */
    .dark body { background-color: #111827 !important; color: #e5e7eb !important; }
    .dark .glass-morphism { background: rgba(31,41,55,0.85) !important; border-color: rgba(55,65,81,.7) !important; }
    .dark .executive-card, .dark .content-card { background: #1f2937 !important; border-color: #374151 !important; }
    .dark .bg-white { background-color: #1f2937 !important; }
    .dark .bg-gray-50 { background-color: #111827 !important; }
    .dark .bg-gray-100 { background-color: #1f2937 !important; }
    .dark .text-gray-900 { color: #f3f4f6 !important; }
    .dark .text-gray-800 { color: #f3f4f6 !important; }
    .dark .text-gray-700 { color: #e5e7eb !important; }
    .dark .text-gray-600 { color: #d1d5db !important; }
    .dark .text-gray-500 { color: #9ca3af !important; }
    .dark .text-gray-400 { color: #9ca3af !important; }
    .dark .border-gray-200 { border-color: #374151 !important; }
    .dark .border-gray-100 { border-color: #4b5563 !important; }
    .dark .hover\:bg-gray-100:hover { background-color: #374151 !important; }
    .dark .bg-blue-50, .dark .bg-blue-50\/50, .dark .bg-blue-50\/30 { background-color: #374151 !important; }
    .dark .text-\[\#4A72FF\], .dark .text-blue-500 { color: #93c5fd !important; }
    .dark thead th { color: #f3f4f6 !important; background-color: #374151 !important; }
    .dark tbody tr:hover { background-color: rgba(55, 65, 81, 0.3) !important; }

    /* Light mode overrides to ensure high contrast */
    html:not(.dark) body { background-color: #f8fafc !important; color: #0f172a !important; }
    html:not(.dark) .glass-morphism { background: rgba(255, 255, 255, 0.9) !important; }
    html:not(.dark) .executive-card { background-color: #ffffff !important; border-color: #e2e8f0 !important; color: #0f172a !important; }
    html:not(.dark) .bg-white, html:not(.dark) .bg-white\/95 { background-color: #ffffff !important; }
    html:not(.dark) .bg-gray-50, html:not(.dark) .bg-gray-50\/50 { background-color: #f9fafb !important; }
    html:not(.dark) .bg-gray-100 { background-color: #f3f4f6 !important; }
    html:not(.dark) .text-gray-900 { color: #0f172a !important; }
    html:not(.dark) .text-gray-800 { color: #1e293b !important; }
    html:not(.dark) .text-gray-700 { color: #334155 !important; }
    html:not(.dark) .text-gray-600 { color: #475569 !important; }
    html:not(.dark) .text-gray-500 { color: #64748b !important; }
    html:not(.dark) .border-gray-200 { border-color: #e2e8f0 !important; }
    html:not(.dark) .border-gray-100 { border-color: #f1f5f9 !important; }
    html:not(.dark) .hover\:bg-gray-50:hover { background-color: #f1f5f9 !important; }
    html:not(.dark) .hover\:bg-gray-100:hover { background-color: #f1f5f9 !important; }
</style>
<nav class="hidden md:block glass-morphism sticky top-0 z-50 dark:bg-gray-800/80">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 text-gray-800 dark:text-gray-100">
        <div class="flex justify-between h-20">
            <!-- Logo Section -->
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-4">
                    <img src="{{ asset('images/logobrmp.png') }}" alt="BRMP Logo" class="w-10 h-10 rounded-xl shadow-lg shadow-blue-500/30 object-contain" />
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-800 dark:text-gray-100">Jurnal<span class="text-[#4A72FF]">BRMP</span></h1>
                        <p class="text-[10px] text-blue-500 dark:text-blue-400 font-medium tracking-wider uppercase">Admin Management System</p>
                    </div>
                </a>
            </div>

            <!-- Menu Section -->
            <div class="flex items-center space-x-8">
                <div class="flex items-center space-x-6">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'text-[#4A72FF] font-semibold' : 'text-gray-500 font-medium' }} px-1 py-2 text-sm transition-all">
                        <i class="fas fa-chart-line mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.posts') }}" class="nav-link {{ request()->routeIs('admin.posts*') ? 'text-[#4A72FF] font-semibold' : 'text-gray-500 font-medium' }} px-1 py-2 text-sm transition-all">
                        <i class="fas fa-file-alt mr-2"></i>Post
                    </a>
                    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'text-[#4A72FF] font-semibold' : 'text-gray-500 font-medium' }} px-1 py-2 text-sm transition-all">
                        <i class="fas fa-user-shield mr-2"></i>Admin
                    </a>
                    <a href="{{ route('admin.employees') }}" class="nav-link {{ request()->routeIs('admin.employees*') ? 'text-[#4A72FF] font-semibold' : 'text-gray-500 font-medium' }} px-1 py-2 text-sm transition-all">
                        <i class="fas fa-users mr-2"></i>Pegawai
                    </a>
                </div>

                <!-- Separator -->
                <div class="h-8 w-px bg-gray-200"></div>

                <!-- Action Icons & Profile -->
                <div class="flex items-center space-x-4">
                    <!-- Settings Icon -->
                    <a href="{{ route('admin.settings') }}" class="p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors {{ request()->routeIs('admin.settings') ? 'text-[#4A72FF] bg-blue-50 dark:bg-blue-900/30' : '' }}" title="Web Settings">
                        <i class="fas fa-cog"></i>
                    </a>

                    <!-- Notification Icon -->
                    <a href="{{ route('admin.notifications.index') }}" class="p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors relative {{ request()->routeIs('admin.notifications.index') ? 'text-[#4A72FF] bg-blue-50 dark:bg-blue-900/30' : '' }}" title="Notifications">
                        <i class="fas fa-bell"></i>
                        @php $unreadAdminCount = \App\Models\Notification::where('user_id', Session::get('user_id'))->where('read', false)->count(); @endphp
                        @if($unreadAdminCount > 0)
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                        @endif
                    </a>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 hover:opacity-80 transition-all pl-4 border-l border-gray-200 dark:border-gray-700 py-1">
                            <div class="text-right hidden lg:block">
                                <p class="text-sm font-bold text-gray-800 leading-none">{{ Session::get('user_name', 'Admin') }}</p>
                                <p class="text-[10px] text-gray-500 font-medium mt-1 uppercase tracking-tighter">Administrator</p>
                            </div>
                            @php $aph = Session::get('admin_profile_photo'); @endphp
                            @if(!empty($aph))
                                <img src="{{ str_starts_with($aph, 'http') ? $aph : (str_starts_with($aph, 'uploads/') ? asset($aph) : asset('storage/'.$aph)) }}" alt="Profile Photo" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm" />
                            @else
                                <div class="w-10 h-10 stat-gradient rounded-full flex items-center justify-center text-white font-semibold shadow-sm text-sm">
                                    {{ strtoupper(substr(Session::get('user_name', 'A'), 0, 1)) }}
                                </div>
                            @endif
                            <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Dropdown Content -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                            
                            <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-[#4A72FF] dark:text-blue-400 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <span class="font-medium">Profil Saya</span>
                            </a>

                            <div class="h-px bg-gray-100 dark:bg-gray-700 mx-4 my-1"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </div>
                                    <span class="font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Top Navigation -->
<nav class="md:hidden glass-morphism sticky top-0 z-50 px-4 py-3 dark:bg-gray-800/90">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.notifications.index') }}" class="p-2 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 relative">
                <i class="fas fa-bell text-sm"></i>
                @if($unreadAdminCount > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-gray-800"></span>
                @endif
            </a>
            <a href="{{ route('admin.settings') }}" class="p-2 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <i class="fas fa-cog text-sm"></i>
            </a>
        </div>
        
        <div class="flex items-center space-x-2">
            <div class="text-right">
                <h2 class="text-xs font-bold text-gray-800 dark:text-gray-100 leading-tight">Jurnal<span class="text-[#4A72FF]">BRMP</span></h2>
                <p class="text-[8px] text-gray-500 dark:text-gray-400 font-medium uppercase tracking-tighter">Admin System</p>
            </div>
            <img src="{{ asset('images/logobrmp.png') }}" alt="Logo" class="w-7 h-7 object-contain">
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 z-50 px-4 py-3">
    <div class="flex items-center justify-around">
        <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center {{ request()->routeIs('admin.dashboard') ? 'text-[#4A72FF]' : 'text-gray-400 dark:text-gray-500' }}">
            <i class="fas fa-home text-4xl"></i>
        </a>
        <a href="{{ route('admin.posts') }}" class="flex flex-col items-center {{ request()->routeIs('admin.posts*') ? 'text-[#4A72FF]' : 'text-gray-400 dark:text-gray-500' }}">
            <i class="fas fa-file-alt text-4xl"></i>
        </a>
        <a href="{{ route('admin.users') }}" class="flex flex-col items-center {{ request()->routeIs('admin.users*') ? 'text-[#4A72FF]' : 'text-gray-400 dark:text-gray-500' }}">
            <i class="fas fa-user-shield text-4xl"></i>
        </a>
        <a href="{{ route('admin.employees') }}" class="flex flex-col items-center {{ request()->routeIs('admin.employees*') ? 'text-[#4A72FF]' : 'text-gray-400 dark:text-gray-500' }}">
            <i class="fas fa-users text-4xl"></i>
        </a>
        <a href="{{ route('admin.profile') }}" class="flex flex-col items-center">
            @if(!empty($aph))
                <img src="{{ str_starts_with($aph, 'http') ? $aph : (str_starts_with($aph, 'uploads/') ? asset($aph) : asset('storage/'.$aph)) }}" 
                     class="w-12 h-12 rounded-full object-cover border-2 {{ request()->routeIs('admin.profile') ? 'border-[#4A72FF]' : 'border-gray-200 dark:border-gray-600' }}">
            @else
                <div class="w-12 h-12 stat-gradient rounded-full flex items-center justify-center text-white text-xs font-bold border-2 {{ request()->routeIs('admin.profile') ? 'border-[#4A72FF]' : 'border-white dark:border-gray-700' }}">
                    {{ strtoupper(substr(Session::get('user_name', 'A'), 0, 1)) }}
                </div>
            @endif
        </a>
    </div>
</div>
<script>
// Web Push registration for admins (same as user)
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
            await fetch("/user/push-subscriptions", {
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
