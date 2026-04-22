<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $journal->title }} - Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .stat-gradient {
            background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%);
        }
        
        .content-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        
        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(74, 114, 255, 0.1), 0 10px 10px -5px rgba(74, 114, 255, 0.04);
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
    </style>
</head>
<body class="bg-[#EEF2FF] min-h-screen">
    @include('layouts.admin_navbar')

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-8 sm:px-6 lg:px-8 pb-24 md:pb-8 text-sm md:text-base">
        <div class="px-4 py-6 sm:px-0">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 fade-in">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl shadow-lg flex items-center">
                        <i class="fas fa-check-circle text-2xl mr-3"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Back Button -->
            <div class="mb-6 fade-in">
                <a href="{{ url()->previous() == url()->current() ? route('admin.posts') : url()->previous() }}" class="inline-flex items-center text-[#4A72FF] hover:text-blue-700 font-medium group transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2 text-lg group-hover:-translate-x-2 transition-transform"></i>
                    Kembali
                </a>
            </div>

            <!-- Journal Detail -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden fade-in border border-gray-100">
                <!-- Header -->
                <div class="stat-gradient p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black opacity-10 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10 flex items-start justify-between">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold mb-2">{{ $journal->title ?? ('Jurnal #' . ($journal->no ?? $journal->id)) }}</h1>
                            <div class="text-sm text-blue-100 mb-4">
                                <span class="inline-flex items-center bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                                    <i class="fas fa-hashtag mr-2"></i>Jurnal #{{ $journal->no ?? $journal->id }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-4 text-blue-100">
                                <div class="flex items-center bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                                    <i class="fas fa-user-circle mr-2"></i>
                                    <span>{{ $journal->user->name }}</span>
                                </div>
                                <div class="flex items-center bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span>{{ $journal->created_at->format('d F Y, H:i') }}</span>
                                </div>
                                @if($journal->received_by_admin)
                                    <span class="bg-green-400 text-green-900 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm">
                                        <i class="fas fa-check mr-1"></i>Diterima Admin
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="hidden md:block transform rotate-12 opacity-20">
                            <i class="fas fa-file-alt text-8xl"></i>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-6">
                    <!-- Date Information -->
                    @if($journal->tanggal)
                        <div class="content-card bg-blue-50/50 rounded-xl p-6 border border-blue-100">
                            <div class="flex items-center mb-3">
                                <div class="stat-gradient p-3 rounded-xl shadow-lg shadow-blue-500/20 mr-4">
                                    <i class="fas fa-calendar-alt text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Tanggal Pekerjaan</h3>
                                    <p class="text-xl font-bold text-gray-800">{{ $journal->tanggal->format('l, d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Work Description -->
                    @if($journal->uraian_pekerjaan)
                        <div class="content-card rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="stat-gradient p-3 rounded-xl shadow-lg shadow-blue-500/20 mr-4">
                                    <i class="fas fa-briefcase text-white text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Uraian Pekerjaan</h3>
                            </div>
                            <div class="prose max-w-none">
                                <div class="bg-gray-50 rounded-xl p-6 leading-relaxed text-gray-700 border border-gray-100">
                                    {{ nl2br(e($journal->uraian_pekerjaan)) }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Document Information -->
                    @if($journal->dokumen_pekerjaan)
                        <div class="content-card bg-gray-50/50 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="bg-green-500 p-3 rounded-xl shadow-lg shadow-green-500/20 mr-4">
                                    <i class="fas fa-file-image text-white text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Dokumen Pekerjaan</h3>
                            </div>
                            <div class="ml-16">
                                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                                    <div class="mb-4">
                                        @php $doc = trim($journal->dokumen_pekerjaan); @endphp
                                        <a href="{{ str_starts_with($doc, 'http') ? $doc : (str_starts_with($doc, 'uploads/') ? asset($doc) : asset('storage/'.$doc)) }}" target="_blank" 
                                           class="inline-flex items-center text-green-600 hover:text-green-800 font-medium px-4 py-2 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                            <i class="fas fa-external-link-alt mr-2"></i>
                                            Buka di Tab Baru
                                        </a>
                                    </div>
                                    <div class="rounded-lg overflow-hidden border border-gray-100">
                                        @php
                                            $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|jfif)$/i', $doc);
                                        @endphp
                                        @if($isImage)
                                            <img src="{{ str_starts_with($doc, 'http') ? $doc : (str_starts_with($doc, 'uploads/') ? asset($doc) : asset('storage/'.$doc)) }}" 
                                                 alt="Dokumen Pekerjaan" 
                                                 class="w-full h-auto max-h-96 object-contain bg-gray-50"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        @else
                                            <div class="p-8 text-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl">
                                                <i class="fas fa-file-pdf text-red-500 text-5xl mb-3"></i>
                                                <p class="text-gray-600 font-medium">Dokumen ini adalah file PDF</p>
                                                <p class="text-xs text-gray-400 mt-1">Klik tombol di atas untuk melihat dokumen</p>
                                            </div>
                                        @endif
                                        <div class="p-8 text-center text-gray-500 bg-gray-50" style="display: none;">
                                            <i class="fas fa-exclamation-triangle text-3xl mb-2 text-yellow-500"></i>
                                            <p>Gagal memuat gambar. Klik link di atas untuk membuka dokumen.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Evaluators Information -->
                    <div class="content-card rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="stat-gradient p-3 rounded-xl shadow-lg shadow-blue-500/20 mr-4">
                                <i class="fas fa-users text-white text-lg"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Daftar Admin Penilai</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-16">
                            @foreach($journal->admins as $admin)
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 stat-gradient rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                        <span class="text-gray-900 font-medium">{{ $admin->name }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        @switch($admin->pivot->status)
                                            @case('approved')
                                                <span class="text-green-600 text-xs font-bold uppercase flex items-center bg-green-50 px-2 py-1 rounded">
                                                    <i class="fas fa-check-circle mr-1"></i> Disetujui
                                                </span>
                                                @break
                                            @case('revised')
                                                <span class="text-orange-600 text-xs font-bold uppercase flex items-center bg-orange-50 px-2 py-1 rounded">
                                                    <i class="fas fa-edit mr-1"></i> Revisi
                                                </span>
                                                @break
                                            @default
                                                <span class="text-gray-400 text-xs font-bold uppercase flex items-center bg-gray-100 px-2 py-1 rounded">
                                                    <i class="fas fa-hourglass-half mr-1"></i> Menunggu
                                                </span>
                                        @endswitch
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tags -->
                    @if($journal->tags && count($journal->tags) > 0)
                        <div class="content-card rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="stat-gradient p-3 rounded-xl shadow-lg shadow-blue-500/20 mr-4">
                                    <i class="fas fa-hashtag text-white text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Tags</h3>
                            </div>
                            <div class="flex flex-wrap gap-3 ml-16">
                                @foreach($journal->tags as $tag)
                                    <span class="bg-blue-50 text-[#4A72FF] border border-blue-100 px-4 py-1.5 rounded-full text-sm font-medium">
                                        #{{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center mb-4">
                            <div class="bg-gray-400 p-3 rounded-xl shadow-lg shadow-gray-400/20 mr-4">
                                <i class="fas fa-info-circle text-white text-lg"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Informasi Waktu</h3>
                        </div>
                        <div class="ml-16 space-y-3">
                            <div class="flex items-center text-sm text-gray-600 bg-white px-4 py-2 rounded-lg border border-gray-100">
                                <i class="fas fa-plus-circle mr-3 text-green-500"></i>
                                <span>Dibuat: <span class="font-medium text-gray-900">{{ $journal->created_at->format('d F Y, H:i:s') }}</span></span>
                            </div>
                            @if($journal->updated_at != $journal->created_at)
                                <div class="flex items-center text-sm text-gray-600 bg-white px-4 py-2 rounded-lg border border-gray-100">
                                    <i class="fas fa-edit mr-3 text-blue-500"></i>
                                    <span>Diperbarui: <span class="font-medium text-gray-900">{{ $journal->updated_at->format('d F Y, H:i:s') }}</span></span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-100">
                        @if($hasApproved)
                            <form action="{{ route('admin.posts.cancel-received', $journal) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-gray-600 text-white px-6 py-4 rounded-xl font-semibold hover:bg-gray-700 hover:shadow-lg hover:shadow-gray-500/30 transition-all duration-300 transform hover:-translate-y-1">
                                    <i class="fas fa-undo mr-2"></i>
                                    Batalkan Persetujuan Saya
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.posts.toggle-received', $journal) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full stat-gradient text-white px-6 py-4 rounded-xl font-semibold hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-1">
                                    <i class="fas fa-check mr-2"></i>
                                    Terima Laporan (klik sekali)
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.posts.destroy', $journal) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin merevisi jurnal ini?')" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-orange-500 text-white px-6 py-4 rounded-xl font-semibold hover:bg-orange-600 hover:shadow-lg hover:shadow-orange-500/30 transition-all duration-300 transform hover:-translate-y-1">
                                <i class="fas fa-edit mr-2"></i>
                                Revisi Jurnal
                            </button>
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
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
