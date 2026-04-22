<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Management - Admin Panel</title>
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
        
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes pulse-soft {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .slide-in {
            animation: slideIn 0.6s ease-out;
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
        
        .executive-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(74, 114, 255, 0.1), 0 10px 10px -5px rgba(74, 114, 255, 0.04);
            border-color: #4A72FF;
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
        
        .hover-scale {
            transition: all 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(74, 114, 255, 0.1);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-[#EEF2FF] min-h-screen">
    @include('layouts.admin_navbar')

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-8 text-sm md:text-base">
        <div class="px-4 py-6 sm:px-0">
            <div class="executive-card rounded-2xl p-8 mb-8 fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Profil Admin</h2>
                        <p class="text-gray-600">Kelola informasi dan pengaturan akun Anda</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-blue-50 p-4 rounded-2xl">
                            <i class="fas fa-user-shield text-[#4A72FF] text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 fade-in">
                    <div class="executive-card border-l-4 border-l-green-500 p-4 bg-green-50">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                            <span class="font-medium text-gray-800">{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="executive-card rounded-2xl overflow-hidden fade-in">
                <div class="stat-gradient p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                        <div class="relative group">
                            @if(!empty($admin->profile_photo))
                                <img src="{{ str_starts_with($admin->profile_photo, 'http') ? $admin->profile_photo : (str_starts_with($admin->profile_photo, 'uploads/') ? asset($admin->profile_photo) : asset('storage/'.$admin->profile_photo)) }}" 
                                     alt="Profile Photo" 
                                     class="w-32 h-32 rounded-3xl object-cover border-4 border-white shadow-2xl">
                            @else
                                <div class="w-32 h-32 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center text-4xl font-bold border-2 border-white/30 shadow-2xl">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="text-center md:text-left">
                            <h3 class="text-3xl font-bold mb-2">{{ $admin->name }}</h3>
                            <p class="text-blue-100 text-lg mb-4">{{ $admin->email }}</p>
                            <div class="flex flex-wrap justify-center md:justify-start gap-3">
                                <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold border border-white/30">
                                    <i class="fas fa-id-badge mr-2"></i>NIP: {{ $admin->nip ?? '-' }}
                                </span>
                                <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold border border-white/30">
                                    <i class="fas fa-shield-alt mr-2"></i>ADMINISTRATOR
                                </span>
                                <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold border border-white/30">
                                    <i class="fas fa-calendar-alt mr-2"></i>Bergabung {{ $admin->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="md:ml-auto flex flex-col sm:flex-row gap-3">
                            <a href="?tab=pengaturan" class="bg-white text-[#4A72FF] px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 text-center">
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

                <div class="p-8 bg-white">
                    @if(request('tab') !== 'pengaturan')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="space-y-6">
                            <h4 class="text-lg font-bold text-gray-900 border-b pb-2">Informasi Kontak</h4>
                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-[#4A72FF] mr-4">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Nomor Telepon</p>
                                        <p class="font-bold text-gray-900">{{ $admin->phone ?? 'Belum diatur' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 mr-4">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Email Address</p>
                                        <p class="font-bold text-gray-900">{{ $admin->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-lg font-bold text-gray-900 border-b pb-2">Informasi Pekerjaan</h4>
                            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                                <div class="flex items-start">
                                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mr-4 flex-shrink-0">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Unit Kerja</p>
                                        <p class="font-bold text-gray-900 leading-relaxed">{{ $admin->division ?? 'Belum diatur' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-lg font-bold text-gray-900 border-b pb-2">Informasi Alamat</h4>
                            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 h-full min-h-[120px]">
                                <div class="flex items-start">
                                    <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 mr-4 flex-shrink-0">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Alamat Lengkap</p>
                                        <p class="font-bold text-gray-900 leading-relaxed">{{ $admin->address ?? 'Alamat belum dilengkapi' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(request('tab') === 'pengaturan')
                    <!-- Settings Section copied from current but styled better -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-cog mr-3 text-[#4A72FF]"></i>Pengaturan Akun
                            </h4>
                            <a href="{{ route('admin.profile') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700">
                                <i class="fas fa-arrow-left mr-2"></i>Batal
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Info -->
                            <div class="executive-card rounded-2xl p-6 bg-gray-50/50">
                                <h5 class="font-bold text-gray-800 mb-4 flex items-center uppercase tracking-wider text-xs">
                                    <i class="fas fa-id-card mr-2 text-blue-500"></i>Informasi Dasar
                                </h5>
                                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required 
                                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#4A72FF] transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">NIP</label>
                                        <input type="text" name="nip" value="{{ old('nip', $admin->nip) }}" required 
                                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#4A72FF] transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Unit Kerja</label>
                                        <select name="division" required 
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#4A72FF] transition-all appearance-none">
                                            <option value="Penata Layanan Operasional" {{ old('division', $admin->division) === 'Penata Layanan Operasional' ? 'selected' : '' }}>Penata Layanan Operasional</option>
                                            <option value="Operator Layanan Operasional" {{ old('division', $admin->division) === 'Operator Layanan Operasional' ? 'selected' : '' }}>Operator Layanan Operasional</option>
                                            <option value="Pengelola Umum Oprasional" {{ old('division', $admin->division) === 'Pengelola Umum Oprasional' ? 'selected' : '' }}>Pengelola Umum Oprasional</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Nomor Telepon</label>
                                        <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" 
                                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#4A72FF] transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Alamat</label>
                                        <textarea name="address" rows="3" 
                                                  class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#4A72FF] transition-all">{{ old('address', $admin->address) }}</textarea>
                                    </div>
                                    <button type="submit" class="w-full stat-gradient text-white py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">
                                        Simpan Perubahan
                                    </button>
                                </form>
                            </div>

                            <!-- Photo & Password -->
                            <div class="space-y-6">
                                <div class="executive-card rounded-2xl p-6 bg-gray-50/50">
                                    <h5 class="font-bold text-gray-800 mb-4 flex items-center uppercase tracking-wider text-xs">
                                        <i class="fas fa-camera mr-2 text-purple-500"></i>Foto Profil
                                    </h5>
                                    <form action="{{ route('admin.profile.photo') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <input type="file" name="profile_photo" required class="text-sm block w-full text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <button type="submit" class="w-full bg-gray-800 text-white py-3 rounded-xl font-bold hover:bg-black transition-all">
                                            Update Foto
                                        </button>
                                    </form>
                                </div>

                                <div class="executive-card rounded-2xl p-6 bg-gray-50/50">
                                    <h5 class="font-bold text-gray-800 mb-4 flex items-center uppercase tracking-wider text-xs">
                                        <i class="fas fa-key mr-2 text-orange-500"></i>Ganti Password
                                    </h5>
                                    <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <input type="password" name="current_password" placeholder="Password Saat Ini" required 
                                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#4A72FF] transition-all">
                                        <input type="password" name="password" placeholder="Password Baru" required 
                                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#4A72FF] transition-all">
                                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all">
                                            Update Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <h4 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-info-circle mr-3 text-gray-400"></i>Detail Akun
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Account ID</p>
                                <p class="text-sm font-bold text-gray-700">#{{ str_pad($admin->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Status Akun</p>
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    <p class="text-sm font-bold text-gray-700 uppercase">Aktif</p>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Login Terakhir</p>
                                <p class="text-sm font-bold text-gray-700">{{ $admin->last_login_at ? \Carbon\Carbon::parse($admin->last_login_at)->format('d M, H:i') : 'Baru saja' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Custom Footer padding for bottom nav -->
    <div class="h-20 md:hidden"></div>
</body>
</html>
