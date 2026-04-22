<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Baru</title>
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
        
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        .slide-in {
            animation: slideIn 0.8s ease-out;
        }
        
        .hover-scale {
            transition: all 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.02);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(74, 114, 255, 0.2);
            border-color: #4A72FF;
            outline: none;
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-gradient:hover::before {
            left: 100%;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(74, 114, 255, 0.3);
        }
        
        .form-section {
            transition: all 0.3s ease;
        }
        
        .form-section:hover {
            transform: translateX(5px);
        }
        
        .select-custom {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%234A72FF' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        .checkbox-custom {
            transition: all 0.3s ease;
        }
        
        .checkbox-custom:checked {
            animation: pulse 0.3s ease;
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
            .mobile-compact p, .mobile-compact span, .mobile-compact div, .mobile-compact a, .mobile-compact button, .mobile-compact label, .mobile-compact input, .mobile-compact textarea { font-size: 0.75rem !important; }
            .mobile-compact .text-xs { font-size: 0.65rem !important; }
            .mobile-compact .text-lg { font-size: 0.85rem !important; }
            .mobile-compact .text-xl { font-size: 0.9rem !important; }
            .mobile-compact .text-2xl { font-size: 1rem !important; }
            .mobile-compact .text-3xl { font-size: 1.1rem !important; }
            .mobile-compact .p-6 { padding: 1rem !important; }
            .mobile-compact .p-8 { padding: 1.25rem !important; }
            .mobile-compact .px-4 { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
            .mobile-compact .py-8 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
            .mobile-compact .gap-8 { gap: 0.75rem !important; }
            
            .mobile-compact input, .mobile-compact select, .mobile-compact textarea { padding: 0.5rem 0.75rem !important; }
        }
    </style>
</head>
<body class="min-h-screen mobile-compact">
    @include('layouts.user_navbar')

    <!-- Main Content -->
    <main class="max-container py-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-8 text-sm md:text-base">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="glass-effect bg-white/95 dark:bg-gray-800/95 rounded-2xl shadow-2xl mb-8 overflow-hidden fade-in">
                <div class="gradient-bg p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-bold mb-2 flex items-center">
                                <i class="fas fa-plus-circle mr-3 floating"></i>
                                Jurnal Baru
                            </h2>
                            <p class="text-blue-100 text-lg">Catat kegiatan dan pekerjaan Anda dengan detail</p>
                        </div>
                        <div class="hidden md:block">
                            <i class="fas fa-edit text-6xl opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="glass-effect bg-white/95 dark:bg-gray-800/95 rounded-2xl shadow-xl p-8 fade-in" style="animation-delay: 0.2s">
                <form action="{{ route('user.journals.store') }}" method="POST" id="journalForm" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-8">
                        <!-- Informasi Utama -->
                        <div class="form-section">
                            <div class="flex items-center mb-4">
                                <div class="gradient-bg p-3 rounded-lg mr-3">
                                    <i class="fas fa-info text-white"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Informasi Utama</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="journal_no" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-hashtag mr-2 text-[#4A72FF]"></i>No. Jurnal (otomatis)
                                    </label>
                                    <input type="text" name="no" id="journal_no" readonly
                                           value="{{ $formattedNumber ?? '1/1' }}"
                                           class="input-focus w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-200 sm:text-sm">
                                </div>
                                <div>
                                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-calendar-day mr-2 text-[#4A72FF]"></i>Tanggal
                                    </label>
                                    <input type="date" name="tanggal" id="tanggal" readonly
                                           value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                           class="input-focus w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-200 sm:text-sm">
                                </div>
                            </div>
                            
                            <div>
                                <label for="judul" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-heading mr-2 text-[#4A72FF]"></i>Judul
                                </label>
                                <input type="text" name="judul" id="judul" required
                                       class="input-focus w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-200 sm:text-sm"
                                       placeholder="Masukkan judul">
                                @error('judul')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                                        <i class="fas fa-user-shield mr-2 text-[#4A72FF]"></i>Pilih Admin (Target Kirim) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($admins as $admin)
                                            <label class="relative flex items-center p-4 border-2 border-gray-100 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-blue-200 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all group">
                                                <input type="checkbox" name="admin_ids[]" value="{{ $admin->id }}" class="checkbox-custom w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-[#4A72FF] focus:ring-[#4A72FF] dark:bg-gray-900">
                                                <div class="ml-3">
                                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200 group-hover:text-[#4A72FF]">{{ $admin->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $admin->email }}</p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('admin_ids')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Uraian Pekerjaan -->
                        <div class="form-section">
                            <div class="flex items-center mb-4">
                                <div class="gradient-bg p-3 rounded-lg mr-3">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Uraian Pekerjaan</h3>
                            </div>
                            
                            <div>
                                <label for="uraian_pekerjaan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-align-left mr-2 text-[#4A72FF]"></i>Uraian Pekerjaan
                                </label>
                                <textarea name="uraian_pekerjaan" id="uraian_pekerjaan" rows="4" required
                                          class="input-focus w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-200 sm:text-sm"
                                          placeholder="Jelaskan uraian pekerjaan yang dilakukan..."></textarea>
                                @error('uraian_pekerjaan')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        <!-- Dokumen Pekerjaan -->
                        <div class="form-section">
                            <div class="flex items-center mb-4">
                                <div class="gradient-bg p-3 rounded-lg mr-3">
                                    <i class="fas fa-image text-white"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Dokumen Pekerjaan</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="dokumen_pekerjaan_file" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-upload mr-2 text-[#4A72FF]"></i>Upload Foto/Dokumen
                                    </label>
                                    <input type="file" name="dokumen_pekerjaan_file" id="dokumen_pekerjaan_file" accept="image/*"
                                           class="input-focus w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-200 sm:text-sm" />
                                    @error('dokumen_pekerjaan_file')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                    <div id="dokumen_preview" class="mt-3 hidden relative group">
                                        <img id="dokumen_preview_img" src="" alt="Preview" class="w-full max-h-56 object-contain rounded-xl border dark:border-gray-700" />
                                        <div id="compression_info" class="absolute bottom-2 right-2 bg-black/60 text-white text-[10px] px-2 py-1 rounded-lg backdrop-blur-sm hidden">
                                            Auto-compressed
                                        </div>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between">
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 italic">Maksimal 10MB. Sistem akan otomatis mengompres foto.</p>
                                        <span id="file_size_badge" class="hidden px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800 uppercase tracking-tighter"></span>
                                    </div>
                                </div>
                                <div>
                                    <label for="dokumen_pekerjaan_url" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-link mr-2 text-[#4A72FF]"></i>Link Dokumen Pekerjaan
                                    </label>
                                    <div class="relative">
                                        <input type="url" name="dokumen_pekerjaan_url" id="dokumen_pekerjaan_url" placeholder="https://contoh.com/dokumen.jpg"
                                               class="input-focus w-full px-4 py-3 pr-10 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-200 sm:text-sm" 
                                               maxlength="2048" />
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <i class="fas fa-link text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-info-circle mr-1"></i>Opsional: Isi link gambar atau dokumen (maks. 2048 karakter)
                                    </p>
                                    @error('dokumen_pekerjaan_url')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Penilai Atasan Removed -->

                        <!-- Actions -->
                        <div class="mt-8 flex flex-col sm:flex-row gap-4">
                            <button type="submit"
                                    class="btn-gradient flex-1 bg-gradient-to-r from-[#4A72FF] to-blue-600 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-xl transition-all duration-300">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Jurnal
                            </button>
                            <a href="{{ route('user.journals.index') }}"
                               class="flex-1 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 px-8 py-4 rounded-xl font-semibold text-lg hover:border-[#4A72FF] dark:hover:border-blue-400 hover:text-[#4A72FF] dark:hover:text-blue-400 transition-all duration-300 text-center">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                        </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Nama atasan validation
        const namaAtasanInput = document.getElementById('nama_atasan');
        if (namaAtasanInput) {
            namaAtasanInput.addEventListener('input', function() {
                const value = this.value.trim();
                const minLength = 2;
                
                if (value.length > 0 && value.length < minLength) {
                    this.setCustomValidity(`Nama atasan minimal ${minLength} karakter.`);
                    this.classList.add('border-red-500');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('border-red-500');
                }
            });
        }

        // URL validation for document link
        const urlInput = document.getElementById('dokumen_pekerjaan_url');
        if (urlInput) {
            urlInput.addEventListener('input', function() {
                const url = this.value;
                const maxLength = 2048;
                
                if (url.length > maxLength) {
                    this.setCustomValidity(`URL terlalu panjang. Maksimal ${maxLength} karakter.`);
                    this.classList.add('border-red-500');
                } else if (url && !isValidUrl(url)) {
                    this.setCustomValidity('Format URL tidak valid. Gunakan format http:// atau https://');
                    this.classList.add('border-red-500');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('border-red-500');
                }
            });
        }
        
        function isValidUrl(string) {
            try {
                new URL(string);
                return string.startsWith('http://') || string.startsWith('https://');
            } catch (_) {
                return false;
            }
        }

        // Convert tags input to array
        document.getElementById('journalForm').addEventListener('submit', function(e) {
            const tagsInput = document.getElementById('tags');
            if (tagsInput.value.trim()) {
                const tags = tagsInput.value.split(',').map(tag => tag.trim()).filter(tag => tag);
                // Create hidden inputs for each tag
                tags.forEach(tag => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'tags[]';
                    input.value = tag;
                    this.appendChild(input);
                });
                tagsInput.name = 'tags_original'; // Rename original input
            }
        });

        // Add interactive effects
        document.querySelectorAll('input, textarea, select').forEach(element => {
            element.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-105');
            });
            
            element.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-105');
            });
        });

        // Animate form sections on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.form-section').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });

        // Add character counter for textareas
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            const counter = document.createElement('div');
            counter.className = 'text-xs text-gray-500 mt-1 text-right';
            counter.textContent = '0 karakter';
            textarea.parentNode.appendChild(counter);
            
            textarea.addEventListener('input', function() {
                counter.textContent = `${this.value.length} karakter`;
            });
        });

        // Preview and Handle Image Upload with Compression info
        const fileInput = document.getElementById('dokumen_pekerjaan_file');
        const previewContainer = document.getElementById('dokumen_preview');
        const previewImg = document.getElementById('dokumen_preview_img');
        const sizeBadge = document.getElementById('file_size_badge');
        const compressionInfo = document.getElementById('compression_info');

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (file) {
                    // Check size limit (10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar! Maksimal 10MB.');
                        this.value = '';
                        previewContainer.classList.add('hidden');
                        sizeBadge.classList.add('hidden');
                        return;
                    }

                    // Format size for display
                    const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
                    sizeBadge.textContent = `${sizeInMb} MB`;
                    sizeBadge.classList.remove('hidden');

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        
                        // Show compression info if size is > 1MB
                        if (file.size > 1024 * 1024) {
                            compressionInfo.classList.remove('hidden');
                            compressionInfo.innerHTML = `<i class="fas fa-compress-alt mr-1"></i> Auto-compress to ~1MB`;
                        } else {
                            compressionInfo.classList.add('hidden');
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.classList.add('hidden');
                    sizeBadge.classList.add('hidden');
                    compressionInfo.classList.add('hidden');
                    previewImg.src = '';
                }
            });
        }
    </script>
</body>
</html>
