<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/presentation.css') }}">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
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

        @keyframes pulse-soft {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        
        .fade-in-up {
            animation: slideInUp 0.6s ease-out forwards;
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
        }
        
        .stat-gradient {
            background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: #4A72FF;
        }

        /* Responsive max-width adjustments */
        .max-container {
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 640px) {
            .mobile-compact h1 { font-size: 1.1rem !important; }
            .mobile-compact h2 { font-size: 1rem !important; }
            .mobile-compact h3 { font-size: 0.9rem !important; }
            .mobile-compact p, .mobile-compact span, .mobile-compact div, .mobile-compact a, .mobile-compact button, .mobile-compact label, .mobile-compact input, .mobile-compact select { font-size: 0.75rem !important; }
            .mobile-compact .text-xs { font-size: 0.65rem !important; }
            .mobile-compact .text-lg { font-size: 0.85rem !important; }
            .mobile-compact .text-xl { font-size: 0.9rem !important; }
            .mobile-compact .text-2xl { font-size: 1rem !important; }
            .mobile-compact .text-3xl { font-size: 1.1rem !important; }
            .mobile-compact .p-6 { padding: 1rem !important; }
            .mobile-compact .p-8 { padding: 1.25rem !important; }
            .mobile-compact .px-4 { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
            .mobile-compact .py-8 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
            .mobile-compact .gap-6 { gap: 0.75rem !important; }
            
            .mobile-compact input, .mobile-compact select { padding: 0.5rem 0.75rem !important; }
        }
    </style>
</head>
<body class="min-h-screen mobile-compact">
    @include('layouts.user_navbar')

    <!-- Main Content -->
    <main class="max-container py-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-8 text-sm md:text-base">
        <div class="px-4 py-6 sm:px-0">
            <!-- Back Button -->
            <div class="mb-6 fade-in">
                <a href="{{ route('user.profile.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#4A72FF] font-medium group transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2 text-lg group-hover:-translate-x-2 transition-transform"></i>
                    Back to Profile
                </a>
            </div>

            <!-- Edit Profile Form -->
            <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden fade-in border border-gray-100 dark:border-gray-700">
                <div class="stat-gradient p-8 text-white">
                    <h1 class="text-3xl font-bold">Edit Profile</h1>
                    <p class="text-blue-100 mt-2">Update your personal information</p>
                </div>

                <div class="p-8">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded-r-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                <span class="font-medium text-green-800 dark:text-green-200">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                                <span class="font-medium text-red-800 dark:text-red-200">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Profile Information Form -->
                    <form action="{{ route('user.profile.update') }}" method="POST" class="mb-8">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-user mr-2 text-gray-400 dark:text-gray-500"></i>Full Name
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ $user->name }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4A72FF] focus:border-[#4A72FF] transition-all outline-none">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-envelope mr-2 text-gray-400 dark:text-gray-500"></i>Email Address
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ $user->email }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4A72FF] focus:border-[#4A72FF] transition-all outline-none">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="nip" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-id-badge mr-2 text-gray-400 dark:text-gray-500"></i>NIP
                                </label>
                                <input type="text" 
                                       name="nip" 
                                       id="nip" 
                                       value="{{ old('nip', $user->nip) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4A72FF] focus:border-[#4A72FF] transition-all outline-none">
                                @error('nip')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="division" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-building mr-2 text-gray-400 dark:text-gray-500"></i>Unit Kerja
                                </label>
                                <select name="division" id="division" required class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4A72FF] focus:border-[#4A72FF] transition-all outline-none">
                                    <option value="Penata Layanan Operasional" {{ old('division', $user->division) === 'Penata Layanan Operasional' ? 'selected' : '' }}>Penata Layanan Operasional</option>
                                    <option value="Operator Layanan Operasional" {{ old('division', $user->division) === 'Operator Layanan Operasional' ? 'selected' : '' }}>Operator Layanan Operasional</option>
                                    <option value="Pengelola Umum Oprasional" {{ old('division', $user->division) === 'Pengelola Umum Oprasional' ? 'selected' : '' }}>Pengelola Umum Oprasional</option>
                                </select>
                                @error('division')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-phone mr-2 text-gray-400 dark:text-gray-500"></i>Nomor Telepon
                                </label>
                                <input type="text" 
                                       name="phone" 
                                       id="phone" 
                                       value="{{ old('phone', $user->phone) }}"
                                       class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4A72FF] focus:border-[#4A72FF] transition-all outline-none">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-venus-mars mr-2 text-gray-400 dark:text-gray-500"></i>Jenis Kelamin
                                </label>
                                <select name="gender" id="gender" class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4A72FF] focus:border-[#4A72FF] transition-all outline-none">
                                    <option value="" {{ old('gender', $user->gender) === null ? 'selected' : '' }}>Pilih</option>
                                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400 dark:text-gray-500"></i>Alamat
                                </label>
                                <input type="text" 
                                       name="address" 
                                       id="address" 
                                       value="{{ old('address', $user->address) }}"
                                       class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4A72FF] focus:border-[#4A72FF] transition-all outline-none">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-calendar-day mr-2 text-gray-400 dark:text-gray-500"></i>Tanggal Lahir
                                </label>
                                <input type="date" 
                                       name="birth_date" 
                                       id="birth_date" 
                                       value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : null) }}"
                                       class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4A72FF] focus:border-[#4A72FF] transition-all outline-none">
                                @error('birth_date')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('user.profile.index') }}" class="mr-4 px-6 py-3 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-800 dark:hover:text-gray-200 transition-all font-medium">
                                Cancel
                            </a>
                            <button type="submit" class="bg-[#4A72FF] hover:bg-blue-600 text-white px-8 py-3 rounded-xl font-medium shadow-lg hover:shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </form>

                    <!-- Profile Photo Upload -->
                    <div class="border-t dark:border-gray-700 pt-8" x-data="{ 
                        showCropper: false,
                        imageSrc: '',
                        cropper: null,
                        initCropper() {
                            const image = document.getElementById('cropperImage');
                            if (this.cropper) this.cropper.destroy();
                            this.cropper = new Cropper(image, {
                                aspectRatio: 1,
                                viewMode: 1,
                                dragMode: 'move',
                                guides: true,
                                center: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                            });
                        },
                        handleFile(e) {
                            const file = e.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = (event) => {
                                    this.imageSrc = event.target.result;
                                    this.showCropper = true;
                                    this.$nextTick(() => this.initCropper());
                                };
                                reader.readAsDataURL(file);
                            }
                        },
                        saveCrop() {
                            const canvas = this.cropper.getCroppedCanvas({
                                width: 400,
                                height: 400
                            });
                            canvas.toBlob((blob) => {
                                const formData = new FormData();
                                formData.append('profile_photo', blob, 'profile.jpg');
                                formData.append('_token', '{{ csrf_token() }}');
                                
                                fetch('{{ route('user.profile.photo') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        window.location.reload();
                                    } else {
                                        alert('Gagal mengupload foto');
                                    }
                                });
                            }, 'image/jpeg');
                        }
                    }">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Profile Photo</h3>
                        <div class="flex items-center space-x-6">
                            @if($user->profile_photo)
                                <img src="{{ str_starts_with($user->profile_photo, 'http') ? $user->profile_photo : (str_starts_with($user->profile_photo, 'uploads/') ? asset($user->profile_photo) : asset('storage/'.$user->profile_photo)) }}" 
                                     alt="Current Profile Photo" 
                                     class="w-20 h-20 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700 shadow-md">
                            @else
                                <div class="w-20 h-20 stat-gradient rounded-full flex items-center justify-center text-2xl font-bold text-white shadow-md">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-image mr-2 text-gray-500 dark:text-gray-400"></i>Pilih Foto Baru
                                </label>
                                <input type="file" 
                                       @change="handleFile"
                                       id="profile_photo" 
                                       accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#4A72FF] focus:border-transparent text-sm">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">Format yang didukung: JPG, PNG. Maksimal 2MB.</p>
                            </div>
                        </div>

                        <!-- Cropper Modal -->
                        <div x-show="showCropper" 
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                             x-transition.opacity>
                            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl">
                                <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                                    <h4 class="font-bold text-gray-900 dark:text-gray-100">Crop Profile Photo</h4>
                                    <button @click="showCropper = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="p-6">
                                    <div class="max-h-[60vh] overflow-hidden bg-gray-100 dark:bg-gray-900 rounded-lg">
                                        <img :src="imageSrc" id="cropperImage" class="max-w-full">
                                    </div>
                                    <div class="mt-6 flex justify-end gap-3">
                                        <button @click="showCropper = false" class="px-6 py-2 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-600 dark:text-gray-400 font-medium">Batal</button>
                                        <button @click="saveCrop" class="px-6 py-2 stat-gradient text-white rounded-xl font-medium shadow-lg">Terapkan & Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="border-t dark:border-gray-700 pt-8 mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Change Password</h3>
                        <form action="{{ route('user.profile.password') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-lock mr-2 text-gray-500 dark:text-gray-400"></i>Current Password
                                    </label>
                                    <input type="password" 
                                           name="current_password" 
                                           id="current_password" 
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4A72FF] focus:border-transparent">
                                    @error('current_password')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-key mr-2 text-gray-500 dark:text-gray-400"></i>New Password
                                    </label>
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           required
                                           minlength="6"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4A72FF] focus:border-transparent">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-check mr-2 text-gray-500 dark:text-gray-400"></i>Confirm New Password
                                    </label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation" 
                                           required
                                           minlength="6"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4A72FF] focus:border-transparent">
                                    @error('password_confirmation')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="stat-gradient text-white px-4 py-2 rounded-lg font-medium hover:shadow-lg transition-all duration-300">
                                    <i class="fas fa-lock mr-2"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // No file preview needed; image will render from URL once saved
    </script>
</body>
</html>
