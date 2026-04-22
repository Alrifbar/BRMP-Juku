<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Journal #{{ $journal->title }} - Journal Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/presentation.css') }}">
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
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(74, 114, 255, 0.2);
            border-color: #4A72FF;
            outline: none;
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
            
            .mobile-compact input, .mobile-compact select, .mobile-compact textarea { padding: 0.5rem 0.75rem !important; }

            /* Bottom mobile navbar improvements */
            .bottom-navbar a { padding: 10px 6px; }
            .bottom-navbar i { font-size: 1.35rem; }
            .bottom-navbar .text-xs { font-size: 0.70rem; }
        }
    </style>
</head>
<body class="min-h-screen mobile-compact">
    @include('layouts.user_navbar')

    <!-- Main Content -->
    <main class="max-container py-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-8 text-sm md:text-base">
        <!-- Back Button -->
        <div class="mb-6 fade-in text-gray-600 dark:text-gray-400">
            <a href="{{ route('user.journals.index') }}" class="inline-flex items-center hover:text-gray-800 dark:hover:text-gray-200 font-medium group transition-all duration-300">
                <i class="fas fa-arrow-left mr-2 text-lg group-hover:-translate-x-2 transition-transform"></i>
                Back to Journal History
            </a>
        </div>

        <!-- Header -->
        <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl overflow-hidden fade-in mb-8">
            <div class="stat-gradient p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Edit Journal #{{ $journal->title }}</h2>
                        <p class="text-blue-100">Update journal information</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white bg-opacity-20 p-4 rounded-2xl">
                            <i class="fas fa-edit text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-8 fade-in">
            <form action="{{ route('user.journals.update', $journal->id) }}" method="POST" id="journalForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-8">
                    <!-- Main Information -->
                    <div>
                        <div class="flex items-center mb-6">
                            <div class="stat-gradient p-3 rounded-lg mr-3">
                                <i class="fas fa-info text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Main Information</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-heading mr-2 text-gray-500 dark:text-gray-400"></i>Judul
                                </label>
                                <input type="text" name="judul" id="judul" value="{{ old('judul', $journal->title) }}" required
                                       class="input-focus w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#4A72FF] focus:border-transparent"
                                       placeholder="Masukkan judul">
                                @error('judul')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Work Description -->
                    <div>
                        <div class="flex items-center mb-6">
                            <div class="stat-gradient p-3 rounded-lg mr-3">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Work Description</h3>
                        </div>
                        
                        <div>
                            <label for="uraian_pekerjaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-align-left mr-2 text-gray-500 dark:text-gray-400"></i>Description
                            </label>
                            <textarea name="uraian_pekerjaan" id="uraian_pekerjaan" rows="4" required
                                      class="input-focus w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#4A72FF] focus:border-transparent"
                                      placeholder="Describe the work performed...">{{ old('uraian_pekerjaan', $journal->uraian_pekerjaan) }}</textarea>
                            @error('uraian_pekerjaan')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Document -->
                    <div>
                        <div class="flex items-center mb-6">
                            <div class="stat-gradient p-3 rounded-lg mr-3">
                                <i class="fas fa-image text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Document</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="dokumen_pekerjaan_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-upload mr-2 text-gray-500 dark:text-gray-400"></i>Upload Foto/Dokumen
                                </label>
                                <input type="file" name="dokumen_pekerjaan_file" id="dokumen_pekerjaan_file" accept="image/*"
                                       class="input-focus w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#4A72FF] focus:border-transparent" />
                                @error('dokumen_pekerjaan_file')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                                <div id="dokumen_preview" class="mt-3 hidden">
                                    <img id="dokumen_preview_img" src="" alt="Preview" class="w-full max-h-56 object-contain rounded-xl border dark:border-gray-600" />
                                </div>
                                @if(!empty($journal->dokumen_pekerjaan))
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-image mr-1"></i>Current file: {{ $journal->dokumen_pekerjaan }}
                                    </p>
                                @endif
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Jika upload dan link diisi, sistem memakai file upload.</p>
                            </div>
                            <div>
                                <label for="dokumen_pekerjaan_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-link mr-2 text-gray-500 dark:text-gray-400"></i>Work Document (URL)
                                </label>
                                <div class="relative">
                                    <input type="url" name="dokumen_pekerjaan_url" id="dokumen_pekerjaan_url" placeholder="https://contoh.com/dokumen.jpg"
                                           class="input-focus w-full px-4 py-3 pr-10 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#4A72FF] focus:border-transparent" />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i class="fas fa-link text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-info-circle mr-1"></i>Optional: Isi link gambar atau dokumen
                                </p>
                                @error('dokumen_pekerjaan_url')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-6 border-t dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="submit"
                                    class="btn-gradient flex-1 text-white px-8 py-3 rounded-xl font-semibold text-lg hover:shadow-xl transition-all duration-300">
                                <i class="fas fa-save mr-2"></i>
                                Update Journal
                            </button>
                            <a href="{{ route('user.journals.index') }}"
                               class="flex-1 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 px-8 py-3 rounded-xl font-semibold text-lg hover:border-[#4A72FF] dark:hover:border-blue-400 hover:text-[#4A72FF] dark:hover:text-blue-400 transition-all duration-300 text-center">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Setup CSRF token for AJAX requests
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
        
        // Set default headers for AJAX requests
        if (typeof $ !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.fade-in').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'all 0.8s ease-out';
                observer.observe(el);
            });
        });
        // Preview uploaded document image
        const fileInput = document.getElementById('dokumen_pekerjaan_file');
        const previewContainer = document.getElementById('dokumen_preview');
        const previewImg = document.getElementById('dokumen_preview_img');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.classList.add('hidden');
                    previewImg.src = '';
                }
            });
        }
    </script>
</body>
</html>
