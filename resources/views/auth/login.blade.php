<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Journal Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/presentation.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .login-illustration {
            background-image: url('https://img.freepik.com/free-vector/office-concept-illustration_114360-1415.jpg?w=900&t=st=1709798777~exp=1709799377~hmac=6c8c4d2d4f5c5d4f5c5d4f5c5d4f5c5d');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .blob-shape {
            position: absolute;
            filter: blur(40px);
            z-index: 0;
            opacity: 0.5;
        }
        
        /* Custom Animations */
        @keyframes slideInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .slide-in-up {
            animation: slideInUp 0.6s ease-out forwards;
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
    </style>
</head>
<body class="bg-[#EEF2FF] min-h-screen flex items-center justify-center p-4 font-sans">

    <!-- Main Container -->
    <div class="bg-white rounded-[2rem] shadow-xl w-full max-w-6xl overflow-hidden flex min-h-[600px] relative slide-in-up">
        
        <!-- Left Side - Illustration -->
        <div class="hidden lg:flex w-1/2 bg-[#EEF2FF] relative items-center justify-center p-12 overflow-hidden">
            <!-- Background Decorations -->
            <div class="blob-shape bg-[#4A72FF] w-64 h-64 rounded-full -top-10 -left-10 opacity-20"></div>
            <div class="blob-shape bg-blue-300 w-80 h-80 rounded-full -bottom-20 -right-20 opacity-20"></div>
            
            <!-- Content -->
            <div class="relative z-10 w-full h-full flex flex-col items-center justify-center fade-in" style="animation-delay: 0.2s">
                <!-- Using a composition of icons to represent the office scene if image fails or to enhance it -->
                <div class="relative w-full max-w-md aspect-square bg-white/40 backdrop-blur-md rounded-full flex items-center justify-center shadow-lg border border-white/60">
                    <div class="text-center transform hover:scale-105 transition-transform duration-500">
                        <i class="fas fa-book-open text-9xl text-[#4A72FF] drop-shadow-lg mb-4 block"></i>
                        <div class="flex justify-center space-x-8 mt-8">
                            <div class="text-center">
                                <i class="fas fa-pen-fancy text-4xl text-[#4A72FF] mb-2 block opacity-80"></i>
                                <span class="text-[#4A72FF] font-semibold text-sm">Menulis</span>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-calendar-alt text-4xl text-[#4A72FF] mb-2 block opacity-80"></i>
                                <span class="text-[#4A72FF] font-semibold text-sm">Kalender</span>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-lock text-4xl text-[#4A72FF] mb-2 block opacity-80"></i>
                                <span class="text-[#4A72FF] font-semibold text-sm">Aman</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute top-10 right-10 animate-bounce delay-100">
                        <i class="fas fa-sticky-note text-yellow-400 text-3xl drop-shadow-md"></i>
                    </div>
                    <div class="absolute bottom-20 left-10 animate-bounce delay-700">
                        <i class="fas fa-file-alt text-blue-400 text-3xl drop-shadow-md"></i>
                    </div>
                    <div class="absolute top-20 left-20 animate-bounce delay-300">
                        <i class="fas fa-bookmark text-green-400 text-3xl drop-shadow-md"></i>
                    </div>
                </div>
                
                <div class="mt-12 text-center">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Kelola Jurnal Anda</h2>
                    <p class="text-gray-600">Tulis dan atur catatan harian dengan mudah</p>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 p-8 md:p-16 flex flex-col justify-center relative bg-white">
            
            <!-- Mobile Header (Visible only on small screens) -->
            <div class="lg:hidden text-center mb-8">
                 <i class="fas fa-book-open text-5xl text-[#4A72FF] mb-2"></i>
                 <h2 class="text-2xl font-bold text-gray-800">Journal App</h2>
            </div>

            <div class="max-w-md mx-auto w-full fade-in" style="animation-delay: 0.4s">
                <!-- Header -->
                <div class="flex items-center text-[#4A72FF] mb-10">
                    <div class="bg-blue-50 p-2 rounded-lg mr-3">
                        <i class="fas fa-user-shield text-xl"></i>
                    </div>
                    <span class="text-lg font-bold tracking-wide">Dashboard Login</span>
                </div>

                <!-- Welcome Text -->
                <div class="mb-10">
                    @if(session('error'))
                        <div class="mb-4 text-red-500 font-bold animate-pulse flex items-center bg-red-50 p-3 rounded-lg">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="mb-4 text-green-500 font-bold flex items-center bg-green-50 p-3 rounded-lg">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">SELAMAT DATANG!</h1>
                    <p class="text-gray-500 text-lg">Kelola sistem jurnal dengan aman dan efisien</p>
                </div>

                <!-- Form -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Email Input -->
                    <div class="group">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2 ml-1">Username / Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400 group-focus-within:text-[#4A72FF] transition-colors"></i>
                            </div>
                            <input type="email" name="email" id="email" 
                                   class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#4A72FF] focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 outline-none text-gray-800 placeholder-gray-400 font-medium" 
                                   placeholder="Enter your email" required value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-500 pl-1 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="group">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2 ml-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400 group-focus-within:text-[#4A72FF] transition-colors"></i>
                            </div>
                            <input type="password" name="password" id="password" 
                                   class="block w-full pl-11 pr-12 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#4A72FF] focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 outline-none text-gray-800 placeholder-gray-400 font-medium" 
                                   placeholder="Enter your password" required>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <button type="button" id="togglePassword" class="text-gray-400 hover:text-[#4A72FF] focus:outline-none transition-colors">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-500 pl-1 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center text-gray-600 cursor-pointer hover:text-gray-800">
                            <input name="remember" type="checkbox" value="1" checked class="w-4 h-4 rounded border-gray-300 text-[#4A72FF] focus:ring-[#4A72FF] transition duration-150 ease-in-out">
                            <span class="ml-2">Remember me</span>
                        </label>
                        <a href="#" class="font-medium text-[#4A72FF] hover:text-blue-700 transition-colors">Forgot Password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-[#4A72FF] hover:bg-blue-600 text-white font-bold py-4 px-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group">
                        <span class="mr-2">Login to My Account</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>

</body>
</html>
