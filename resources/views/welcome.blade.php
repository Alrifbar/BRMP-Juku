<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Poppins', 'sans-serif'],
                        },
                        colors: {
                            primary: '#4A72FF',
                            secondary: '#2563EB',
                        },
                        animation: {
                            'fade-in': 'fadeIn 0.8s ease-out forwards',
                            'slide-up': 'slideUp 0.8s ease-out forwards',
                            'pulse-soft': 'pulseSoft 3s infinite',
                            'float': 'float 6s ease-in-out infinite',
                        },
                        keyframes: {
                            fadeIn: {
                                '0%': { opacity: '0' },
                                '100%': { opacity: '1' },
                            },
                            slideUp: {
                                '0%': { opacity: '0', transform: 'translateY(20px)' },
                                '100%': { opacity: '1', transform: 'translateY(0)' },
                            },
                            pulseSoft: {
                                '0%, 100%': { opacity: '1' },
                                '50%': { opacity: '0.8' },
                            },
                            float: {
                                '0%, 100%': { transform: 'translateY(0)' },
                                '50%': { transform: 'translateY(-20px)' },
                            }
                        }
                    }
                }
            }
        </script>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: #F3F4F6;
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.5);
                box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
            }
            .hero-gradient {
                background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .bg-gradient-primary {
                background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
            }
            .blob {
                position: absolute;
                filter: blur(40px);
                z-index: -1;
                opacity: 0.5;
                animation: pulseSoft 10s infinite alternate;
            }
        </style>
    </head>
    <body class="antialiased text-gray-800 min-h-screen flex flex-col relative overflow-hidden">
        
        <!-- Background Blobs -->
        <div class="blob bg-blue-300 w-96 h-96 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2 mix-blend-multiply"></div>
        <div class="blob bg-indigo-300 w-96 h-96 rounded-full bottom-0 right-0 translate-x-1/2 translate-y-1/2 mix-blend-multiply animation-delay-2000"></div>
        
        <!-- Navigation -->
        @if (Route::has('login'))
            <nav class="w-full p-6 flex justify-between items-center z-10 animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-primary rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 text-white font-bold text-xl">
                        J
                    </div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">JurnalKu</span>
                </div>
                <div class="flex gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-white text-gray-700 font-medium rounded-full hover:bg-gray-50 transition-all shadow-sm border border-gray-100">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 text-gray-600 font-medium hover:text-[#4A72FF] transition-colors">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gradient-primary text-white font-medium rounded-full shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all">Register</a>
                        @endif
                    @endauth
                </div>
            </nav>
        @endif

        <!-- Hero Section -->
        <main class="flex-grow flex items-center justify-center px-6 relative z-10">
            <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
                
                <!-- Text Content -->
                <div class="space-y-8 text-center lg:text-left animate-slide-up">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-[#4A72FF] text-sm font-medium mb-4">
                        <span class="flex h-2 w-2 relative mr-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4A72FF]"></span>
                        </span>
                        v1.1 Now Available
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-bold leading-tight tracking-tight">
                        Catat Setiap <br>
                        <span class="hero-gradient">Momen Berharga</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Platform jurnal digital modern untuk mencatat aktivitas harian, memantau produktivitas, dan menyimpan kenangan Anda dengan aman dan estetik.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-gradient-primary text-white rounded-2xl font-semibold shadow-xl shadow-blue-500/30 hover:shadow-blue-500/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                                <span>Buka Dashboard</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-8 py-4 bg-gradient-primary text-white rounded-2xl font-semibold shadow-xl shadow-blue-500/30 hover:shadow-blue-500/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                                <span>Mulai Menulis</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-gray-700 border border-gray-200 rounded-2xl font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
                
                <!-- Visual Content -->
                <div class="relative lg:h-[600px] flex items-center justify-center animate-float hidden lg:flex">
                    <!-- Main Card -->
                    <div class="glass-card w-full max-w-md rounded-3xl p-6 relative z-10 transform rotate-[-2deg] transition-transform hover:rotate-0 duration-500">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-[#4A72FF]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">My Journal</div>
                                    <div class="text-xs text-gray-500">Just now</div>
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="h-4 bg-gray-100 rounded-full w-3/4"></div>
                            <div class="h-4 bg-gray-100 rounded-full w-full"></div>
                            <div class="h-4 bg-gray-100 rounded-full w-5/6"></div>
                            <div class="h-4 bg-gray-100 rounded-full w-4/5"></div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 rounded-full bg-blue-400 border-2 border-white"></div>
                                <div class="w-8 h-8 rounded-full bg-indigo-400 border-2 border-white"></div>
                                <div class="w-8 h-8 rounded-full bg-purple-400 border-2 border-white"></div>
                            </div>
                            <div class="text-sm text-[#4A72FF] font-medium">Read more →</div>
                        </div>
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="absolute top-1/4 -right-4 w-24 h-24 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl rotate-12 opacity-20 blur-xl"></div>
                    <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-gradient-to-tr from-purple-400 to-pink-500 rounded-full opacity-20 blur-xl"></div>
                </div>
            </div>
        </main>
        
        <footer class="py-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} JurnalKu. All rights reserved.
        </footer>
    </body>
</html>