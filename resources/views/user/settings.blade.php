<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - JurnalBRMP</title>
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

        .max-container {
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
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
<body x-data="{ theme: '{{ Session::get('theme', 'light') }}' }" x-init="if(theme==='dark'){ document.documentElement.classList.add('dark'); }" class="bg-[#EEF2FF] dark:bg-gray-900 min-h-screen mobile-compact">
    @include('layouts.user_navbar')

    <main class="max-container py-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-8 text-sm md:text-base">
        <div class="mb-8 fade-in">
            <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Settings</h2>
                        <p class="text-gray-600 dark:text-gray-300">Manage your account preferences</p>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-2xl">
                        <i class="fas fa-cog text-gray-600 dark:text-gray-300 text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Notification Preferences -->
            <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-6 fade-in">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Notification Preferences</h3>
                <form action="{{ route('user.settings.preferences') }}" method="POST" class="space-y-3">
                    @csrf
                    <label class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3">
                        <span class="text-gray-800 dark:text-gray-200">Jurnal disetujui</span>
                        <input type="checkbox" name="approved" value="1" {{ ($prefs->approved ?? true) ? 'checked' : '' }} class="w-5 h-5">
                    </label>
                    <label class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3">
                        <span class="text-gray-800 dark:text-gray-200">Jurnal direvisi</span>
                        <input type="checkbox" name="revised" value="1" {{ ($prefs->revised ?? true) ? 'checked' : '' }} class="w-5 h-5">
                    </label>
                    <label class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3">
                        <span class="text-gray-800 dark:text-gray-200">Jurnal ditolak</span>
                        <input type="checkbox" name="rejected" value="1" {{ ($prefs->rejected ?? true) ? 'checked' : '' }} class="w-5 h-5">
                    </label>
                    <label class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3">
                        <span class="text-gray-800 dark:text-gray-200">Feedback / balasan jurnal</span>
                        <input type="checkbox" name="feedback" value="1" {{ ($prefs->feedback ?? true) ? 'checked' : '' }} class="w-5 h-5">
                    </label>
                    <label class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3">
                        <span class="text-gray-800 dark:text-gray-200">Jurnal baru (untuk admin)</span>
                        <input type="checkbox" name="new_journal" value="1" {{ ($prefs->new_journal ?? true) ? 'checked' : '' }} class="w-5 h-5">
                    </label>
                    <button type="submit" class="w-full mt-2 stat-gradient text-white px-4 py-2 rounded-lg hover:shadow-lg">Simpan Preferensi</button>
                </form>
            </div>

            <!-- Theme & Default Page -->
            <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-6 fade-in">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Theme & Default Page</h3>
                <form action="{{ route('user.settings.theme') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Theme</label>
                        <div class="flex items-center space-x-3">
                            <label class="inline-flex items-center space-x-2">
                                <input type="radio" name="theme" value="light" x-model="theme" @change="document.documentElement.classList.remove('dark')" {{ (Session::get('theme', 'light')) === 'light' ? 'checked' : '' }}>
                                <span class="text-gray-800 dark:text-gray-200">Light</span>
                            </label>
                            <label class="inline-flex items-center space-x-2">
                                <input type="radio" name="theme" value="dark" x-model="theme" @change="document.documentElement.classList.add('dark')" {{ (Session::get('theme', 'light')) === 'dark' ? 'checked' : '' }}>
                                <span class="text-gray-800 dark:text-gray-200">Dark</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Default Page After Login</label>
                        <select name="default_page" class="border border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 w-full">
                            <option value="">Default (Dashboard)</option>
                            <option value="dashboard" {{ ($user->default_page ?? '')==='dashboard' ? 'selected' : '' }}>Dashboard</option>
                            <option value="journals" {{ ($user->default_page ?? '')==='journals' ? 'selected' : '' }}>Jurnal</option>
                            <option value="notifications" {{ ($user->default_page ?? '')==='notifications' ? 'selected' : '' }}>Notifikasi</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full stat-gradient text-white px-4 py-2 rounded-lg hover:shadow-lg">Simpan Pengaturan</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
