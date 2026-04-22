<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/presentation.css') }}">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    </style>
</head>
<body class="bg-[#EEF2FF] dark:bg-gray-900 min-h-screen text-gray-900 dark:text-gray-100">
    @include('layouts.user_navbar')

    <main class="max-container py-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-8 text-sm md:text-base">
        <div class="mb-8 fade-in-up">
                <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Profil Saya</h2>
                            <p class="text-gray-600 dark:text-gray-400">Informasi lengkap akun Anda</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-2xl">
                                <i class="fas fa-user-circle text-[#4A72FF] dark:text-blue-400 text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 fade-in">
                    <div class="executive-card border-l-4 border-l-green-500 p-4 bg-green-50 dark:bg-green-900/20">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-xl mr-3"></i>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl overflow-hidden fade-in-up" style="animation-delay: 0.1s">
                <div class="stat-gradient p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                        <div class="relative group">
                            @if($user->profile_photo)
                                <img src="{{ str_starts_with($user->profile_photo, 'http') ? $user->profile_photo : (str_starts_with($user->profile_photo, 'uploads/') ? asset($user->profile_photo) : asset('storage/'.$user->profile_photo)) }}" 
                                     alt="Profile Photo" 
                                     class="w-32 h-32 rounded-3xl object-cover border-4 border-white dark:border-gray-700 shadow-2xl">
                            @else
                                <div class="w-32 h-32 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center text-4xl font-bold border-2 border-white/30 shadow-2xl">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="text-center md:text-left">
                            <h3 class="text-3xl font-bold mb-2">{{ $user->name }}</h3>
                            <p class="text-blue-100 text-lg mb-4">{{ $user->email }}</p>
                            <div class="flex flex-wrap justify-center md:justify-start gap-3">
                                <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold border border-white/30">
                                    <i class="fas fa-id-badge mr-2"></i>NIP: {{ $user->nip ?? '-' }}
                                </span>
                                <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold border border-white/30">
                                    <i class="fas fa-user-circle mr-2"></i>{{ strtoupper($user->role) }}
                                </span>
                                <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold border border-white/30">
                                    <i class="fas fa-calendar-alt mr-2"></i>Bergabung {{ $user->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="md:ml-auto flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('user.profile.edit') }}" class="bg-white text-[#4A72FF] px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 text-center">
                                <i class="fas fa-user-edit mr-2"></i>Edit Profil
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="w-full bg-red-500 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl hover:bg-red-600 transition-all duration-300 transform hover:-translate-y-1">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-white dark:bg-gray-800">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 border-b dark:border-gray-700 pb-2">Informasi Kontak</h4>
                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-2xl border border-gray-100 dark:border-gray-600">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-[#4A72FF] dark:text-blue-400 mr-4">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Nomor Telepon</p>
                                        <p class="font-bold text-gray-900 dark:text-gray-100">{{ $user->phone ?? 'Belum diatur' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-2xl border border-gray-100 dark:border-gray-600">
                                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mr-4">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Email Alternatif</p>
                                        <p class="font-bold text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 border-b dark:border-gray-700 pb-2">Informasi Pekerjaan</h4>
                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-2xl border border-gray-100 dark:border-gray-600">
                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center text-purple-600 dark:text-purple-400 mr-4">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Unit Kerja</p>
                                        <p class="font-bold text-gray-900 dark:text-gray-100">{{ $user->division ?? 'Belum diatur' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-2xl border border-gray-100 dark:border-gray-600">
                                    <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center text-orange-600 dark:text-orange-400 mr-4">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Alamat Kantor</p>
                                        <p class="font-bold text-gray-900 dark:text-gray-100 truncate" title="{{ $user->address }}">{{ $user->address ?? 'Belum diatur' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
