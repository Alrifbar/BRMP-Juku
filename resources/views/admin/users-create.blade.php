<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ request('type') === 'admin' ? 'Add Admin' : 'Add User' }} - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse-soft {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .slide-in-up {
            animation: slideInUp 0.6s ease-out;
        }
        
        .executive-card {
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        
        .executive-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(74, 114, 255, 0.1), 0 10px 10px -5px rgba(74, 114, 255, 0.04);
            border-color: #4A72FF;
        }
        
        .stat-gradient {
            background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(74, 114, 255, 0.1);
            border-color: #4A72FF;
        }

        .select-custom {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%234A72FF' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
    </style>
</head>
<body class="bg-[#EEF2FF] min-h-screen">
    @include('layouts.admin_navbar')

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 pb-24 md:pb-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="executive-card rounded-2xl p-8 mb-8 slide-in-up">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">
                            {{ request('type') === 'admin' ? 'Create New Admin' : 'Create New User' }}
                        </h2>
                        <p class="text-gray-600">
                            {{ request('type') === 'admin' ? 'Add a new administrator to the system' : 'Add a new user to the system' }}
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-blue-50 p-4 rounded-2xl">
                            <i class="fas {{ request('type') === 'admin' ? 'fa-user-shield' : 'fa-user-plus' }} text-[#4A72FF] text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 fade-in">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl shadow-lg flex items-center">
                        <i class="fas fa-check-circle text-2xl mr-3"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 fade-in">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-lg flex items-center">
                        <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 fade-in">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-2xl mr-3 mt-1"></i>
                            <div>
                                @foreach($errors->all() as $error)
                                    <div class="font-medium">{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="executive-card rounded-2xl p-8 fade-in">
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="user_type" value="{{ request('type', 'user') }}">
                    <input type="hidden" name="provider" value="local">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="name">
                            <i class="fas fa-user mr-2 text-gray-600"></i>Full Name
                        </label>
                        <div class="relative">
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                   class="input-focus w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-xl focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-100">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="nip">
                            <i class="fas fa-id-badge mr-2 text-gray-600"></i>NIP
                        </label>
                        <div class="relative">
                            <input id="nip" name="nip" type="text" value="{{ old('nip') }}" required
                                   class="input-focus w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-xl focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-100"
                                   placeholder="Masukkan NIP">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-badge text-gray-400"></i>
                            </div>
                        </div>
                        @error('nip')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="email">
                            <i class="fas fa-envelope mr-2 text-gray-600"></i>Email Address
                        </label>
                        <div class="relative">
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                   class="input-focus w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-xl focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-100">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="password">
                                <i class="fas fa-lock mr-2 text-gray-600"></i>Password
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required
                                       class="input-focus w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-xl focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-100">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="password_confirmation">
                                <i class="fas fa-check mr-2 text-gray-600"></i>Confirm Password
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                       class="input-focus w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-xl focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-100">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-check text-gray-400"></i>
                                </div>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    @if(request('type') === 'admin')
                    <!-- Admin Creation - Add Division Field with restricted options -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="division">
                            <i class="fas fa-building mr-2 text-gray-600"></i>Unit Kerja
                        </label>
                        <div class="relative">
                            <select id="division" name="division" required
                                    class="input-focus w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-xl focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-100 appearance-none">
                                <option value="">Pilih Unit Kerja</option>
                                <option value="Penata Layanan Operasional" {{ old('division') === 'Penata Layanan Operasional' ? 'selected' : '' }}>Penata Layanan Operasional</option>
                                <option value="Operator Layanan Operasional" {{ old('division') === 'Operator Layanan Operasional' ? 'selected' : '' }}>Operator Layanan Operasional</option>
                                <option value="Pengelola Umum Oprasional" {{ old('division') === 'Pengelola Umum Oprasional' ? 'selected' : '' }}>Pengelola Umum Oprasional</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('division')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    @else
                    <!-- Pegawai Creation - Add Division Field with restricted options -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="division">
                            <i class="fas fa-building mr-2 text-gray-600"></i>Unit Kerja
                        </label>
                        <div class="relative">
                            <select id="division" name="division" required
                                    class="input-focus w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-xl focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-100 appearance-none">
                                <option value="">Pilih Unit Kerja</option>
                                <option value="Penata Layanan Operasional" {{ old('division') === 'Penata Layanan Operasional' ? 'selected' : '' }}>Penata Layanan Operasional</option>
                                <option value="Operator Layanan Operasional" {{ old('division') === 'Operator Layanan Operasional' ? 'selected' : '' }}>Operator Layanan Operasional</option>
                                <option value="Pengelola Umum Oprasional" {{ old('division') === 'Pengelola Umum Oprasional' ? 'selected' : '' }}>Pengelola Umum Oprasional</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('division')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    @endif

                    <div class="flex items-center justify-between pt-6">
                        <a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-[#4A72FF] font-medium flex items-center transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Users
                        </a>
                        <button type="submit" class="stat-gradient text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300 shadow-blue-500/30">
                            <i class="fas fa-save mr-2"></i>{{ request('type') === 'admin' ? 'Create Admin' : 'Create User' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <!-- Custom Footer padding for bottom nav -->
    <div class="h-20 md:hidden"></div>
</body>
</html>
