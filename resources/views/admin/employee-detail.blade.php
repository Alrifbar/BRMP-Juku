<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pegawai - Admin Panel</title>
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
        @media print {
            .no-print { display: none !important; }
            th:nth-last-child(1), td:nth-last-child(1) { display: none !important; }
            body { background: #ffffff !important; }
        }
    </style>
</head>
<body class="bg-[#EEF2FF] min-h-screen">
    @include('layouts.admin_navbar')

    <!-- Main Content -->
    <main x-data="{
            showAccountDetails: false,
            selectedJournals: [],
            bulkAction(type) {
                let actionUrl = '';
                let confirmMsg = '';
                switch(type) {
                    case 'approve':
                        actionUrl = '{{ route('admin.posts.bulk-approve') }}';
                        confirmMsg = `Terima ${this.selectedJournals.length} jurnal yang dipilih?`;
                        break;
                    case 'revise':
                        actionUrl = '{{ route('admin.posts.bulk-revise') }}';
                        confirmMsg = `Ubah status ${this.selectedJournals.length} jurnal menjadi Revisi?`;
                        break;
                }
                if(confirmMsg && confirm(confirmMsg)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = actionUrl;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    const ids = document.createElement('input');
                    ids.type = 'hidden';
                    ids.name = 'journal_ids';
                    ids.value = JSON.stringify(this.selectedJournals);
                    form.appendChild(ids);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }" class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 pb-24 md:pb-8 text-sm md:text-base">
        <div class="px-4 py-6 sm:px-0">
            <!-- Back Button -->
            <div class="mb-6 fade-in flex items-center justify-between">
                <a href="{{ route('admin.employees') }}" class="inline-flex items-center text-[#4A72FF] hover:text-blue-700 font-medium group transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2 text-lg group-hover:-translate-x-2 transition-transform"></i>
                    Kembali ke Daftar Pegawai
                </a>
                
                <button @click="showAccountDetails = !showAccountDetails" 
                        class="md:hidden flex items-center space-x-2 px-4 py-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 text-xs font-bold text-gray-600 dark:text-gray-300 shadow-sm transition-all active:scale-95">
                    <i class="fas" :class="showAccountDetails ? 'fa-user-minus' : 'fa-user-plus'"></i>
                    <span x-text="showAccountDetails ? 'Sembunyikan Info' : 'Lihat Info Pegawai'"></span>
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden fade-in border border-gray-100 mb-8">
                <div class="stat-gradient p-6 md:p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black opacity-10 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex flex-col sm:flex-row items-center text-center sm:text-left">
                            @if(!empty($employee->profile_photo))
                                <img src="{{ str_starts_with($employee->profile_photo, 'http') ? $employee->profile_photo : (str_starts_with($employee->profile_photo, 'uploads/') ? asset($employee->profile_photo) : asset('storage/'.$employee->profile_photo)) }}" 
                                     alt="Profile Photo" 
                                     @click="$dispatch('open-image-modal', { src: $el.src, title: '{{ $employee->name }}' })"
                                     class="w-20 h-20 md:w-24 md:h-24 rounded-2xl object-cover border-4 border-white shadow-xl mb-4 sm:mb-0 sm:mr-6 cursor-zoom-in hover:scale-105 transition-transform" />
                            @else
                                <div class="w-20 h-20 md:w-24 md:h-24 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center text-3xl font-bold mb-4 sm:mb-0 sm:mr-6 border-2 border-white/30">
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold">{{ $employee->name }}</h2>
                                <p class="text-blue-100 text-sm md:text-base">{{ $employee->email }}</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-medium bg-white/20 backdrop-blur text-white border border-white/30">
                                        <i class="fas fa-id-badge mr-1.5"></i>NIP: {{ $employee->nip ?? '-' }}
                                    </div>
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-medium bg-white/20 backdrop-blur text-white border border-white/30">
                                        <i class="fas fa-user-circle mr-1.5"></i>Pegawai #{{ $employee->id }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 md:space-x-6">
                            <div class="text-center sm:text-right">
                                <div class="text-[10px] md:text-sm text-blue-100 mb-1 uppercase tracking-wider">Total Jurnal</div>
                                <div class="text-2xl md:text-3xl font-bold">{{ $journals->total() }}</div>
                            </div>
                            <div class="no-print flex items-center space-x-2">
                                <a href="{{ route('admin.export.user-journals') }}?user_id={{ $employee->id }}&period=all&format=pdf"
                                   class="w-11 h-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shadow-sm hover:bg-red-100 transition-colors"
                                   title="Export PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Account Details Section -->
                <div x-show="showAccountDetails || window.innerWidth >= 768" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="p-6 md:p-8 border-t border-gray-100 bg-gray-50/50">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                        <div class="flex items-start p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-4 text-[#4A72FF] flex-shrink-0">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Telepon</div>
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $employee->phone ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="flex items-start p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center mr-4 text-emerald-600 flex-shrink-0">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Unit Kerja</div>
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $employee->division ?? 'Pegawai' }}</div>
                            </div>
                        </div>
                        <div class="flex items-start p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-4 text-purple-600 flex-shrink-0">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Joined</div>
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $employee->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="sm:col-span-2 md:col-span-3 flex items-start p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mr-4 text-orange-600 flex-shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Alamat</div>
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $employee->address ?? 'Belum melengkapi alamat' }}</div>
                            </div>
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

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden fade-in border border-gray-100">
                <div class="p-6 md:p-8">
                    <!-- Search & Filter Area -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 no-print">
                        <h3 class="text-xl font-bold text-gray-900">
                            <i class="fas fa-file-invoice mr-2 text-[#4A72FF]"></i>
                            Riwayat Jurnal
                        </h3>
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <form action="{{ url()->current() }}" method="GET" class="flex flex-1 gap-2">
                                <div class="relative flex-1">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No Jurnal / Uraian..." 
                                           class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-100 rounded-xl focus:border-[#4A72FF] outline-none transition-all text-sm">
                                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <button type="submit" class="stat-gradient text-white w-11 h-11 flex items-center justify-center rounded-xl font-bold text-sm shadow-lg shadow-blue-500/20 active:scale-95 transition-transform">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                            
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="bg-gray-100 text-gray-700 w-11 h-11 flex items-center justify-center rounded-xl font-bold text-sm hover:bg-gray-200 transition-colors shadow-sm">
                                    <i class="fas fa-sort-amount-down"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-gray-100 z-20 overflow-hidden">
                                    <div class="px-4 py-2 border-b border-gray-50 text-[10px] font-bold text-gray-400 uppercase">Urutkan Berdasarkan</div>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['sort' => 'created_at', 'direction' => 'desc'])) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#4A72FF]">Terbaru</a>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['sort' => 'tanggal', 'direction' => 'desc'])) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#4A72FF]">Tanggal Kerja</a>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['sort' => 'no', 'direction' => 'asc'])) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#4A72FF]">No Jurnal (A-Z)</a>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['sort' => 'received_by_admin', 'direction' => 'desc'])) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#4A72FF]">Status (Approved)</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Action Bar -->
                    <div x-show="selectedJournals.length > 0"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mb-6 p-3 bg-white border border-blue-100 rounded-2xl flex flex-col sm:flex-row items-center justify-between shadow-xl ring-1 ring-blue-50 gap-4">
                        <div class="flex items-center w-full sm:w-auto">
                            <button @click="selectedJournals = []" class="mr-4 hover:bg-gray-100 p-2.5 rounded-xl transition-all text-gray-400 hover:text-gray-600" title="Batalkan Pilihan">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                            <div class="h-8 w-px bg-gray-200 mr-4"></div>
                            <span class="font-bold text-gray-800">
                                <span x-text="selectedJournals.length" class="text-xl mr-1 text-[#4A72FF]"></span> item dipilih
                            </span>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button @click="bulkAction('approve')"
                                    class="flex-1 sm:flex-none flex items-center justify-center h-11 px-6 rounded-xl bg-green-500 text-white hover:bg-green-600 transition-all duration-300 shadow-lg shadow-green-500/20 font-bold text-sm">
                                <i class="fas fa-check-circle mr-2"></i>Terima
                            </button>
                            <button @click="bulkAction('revise')"
                                    class="flex-1 sm:flex-none flex items-center justify-center h-11 px-6 rounded-xl bg-[#4A72FF] text-white hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-blue-500/20 font-bold text-sm">
                                <i class="fas fa-undo-alt mr-2"></i>Revisi
                            </button>
                        </div>
                    </div>

                    <!-- Table (Desktop) -->
                    <div class="hidden md:block overflow-x-auto whitespace-nowrap rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-xs md:text-sm">
                                    <thead class="bg-blue-50/50">
                                        <tr>
                                            <th class="px-6 py-4 text-left">
                                                <input type="checkbox"
                                                       @change="selectedJournals = $el.checked ? {{ json_encode($journals->pluck('id')) }} : []"
                                                       class="w-4 h-4 text-blue-600 rounded cursor-pointer">
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">No. Jurnal</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">Tanggal Kerja</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">Uraian Pekerjaan</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">Dokumen</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider no-print">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @forelse($journals as $journal)
                                            <tr class="hover:bg-blue-50/30 transition-colors duration-200" :class="selectedJournals.includes({{ $journal->id }}) ? 'bg-blue-50/50' : ''">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" :value="{{ $journal->id }}" x-model="selectedJournals" class="w-4 h-4 text-blue-600 rounded cursor-pointer">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-900">
                                                        <a href="{{ route('admin.posts.show', $journal->id) }}" class="hover:text-[#4A72FF]">
                                                            {{ $journal->no }}
                                                        </a>
                                                    </div>
                                                    <div class="text-[10px] text-gray-400 mt-1 uppercase tracking-tighter">
                                                        <i class="far fa-clock mr-1"></i>Dibuat: {{ $journal->created_at->format('d/m/Y H:i') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-700 font-medium">
                                                        {{ $journal->tanggal?->format('d M Y') ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-600 max-w-xs line-clamp-2" title="{{ $journal->uraian_pekerjaan }}">
                                                        {{ $journal->uraian_pekerjaan }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $doc = trim($journal->dokumen_pekerjaan);
                                                    @endphp
                                                    @if($doc)
                                                        @php
                                                            $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|jfif)$/i', $doc);
                                                            $docUrl = str_starts_with($doc, 'http') ? $doc : (str_starts_with($doc, 'uploads/') ? asset($doc) : asset('storage/'.$doc));
                                                        @endphp
                                                        <div class="flex flex-col gap-2">
                                                            @if($isImage)
                                                                <img src="{{ $docUrl }}" 
                                                                     alt="Doc Thumbnail" 
                                                                     @click="$dispatch('open-image-modal', { src: $el.src, title: 'Dokumen: {{ $journal->no }}' })"
                                                                     class="w-20 h-14 rounded-lg object-cover border border-gray-200 shadow-sm cursor-zoom-in hover:brightness-90 transition-all">
                                                            @else
                                                                <div class="w-20 h-14 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                                                </div>
                                                            @endif
                                                            <a href="{{ $docUrl }}" target="_blank" 
                                                               class="inline-flex items-center justify-center px-2 py-1 rounded-md text-[9px] font-bold bg-gray-50 text-gray-500 hover:bg-gray-100 transition-all border border-gray-200 uppercase">
                                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                                Tab Baru
                                                            </a>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-300 text-xs italic">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($journal->received_by_admin)
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-600 border border-green-200 uppercase">
                                                            <i class="fas fa-check-circle mr-1"></i>Approved
                                                        </span>
                                                        <div class="text-[10px] text-gray-400 mt-1 ml-1">{{ optional($journal->received_at)->format('d/m/y H:i') }}</div>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-200 uppercase">
                                                            <i class="fas fa-clock mr-1"></i>Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap no-print">
                                                    <div class="flex items-center space-x-1">
                                                        <a href="{{ route('admin.posts.show', $journal->id) }}" class="p-2 text-gray-400 hover:text-[#4A72FF] transition-colors" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                        @php
                                                            $adminId = Session::get('user_id');
                                                            $hasApproved = $journal->admins()->where('admin_id', $adminId)->where('status', 'approved')->exists();
                                                        @endphp
                                                        
                                                        @if($hasApproved)
                                                            <form action="{{ route('admin.posts.cancel-received', $journal->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="p-2 text-gray-400 hover:text-orange-500 transition-colors" title="Batal Setujui">
                                                                    <i class="fas fa-undo"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('admin.posts.toggle-received', $journal->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="p-2 text-gray-400 hover:text-green-600 transition-colors" title="Setujui Cepat">
                                                                    <i class="fas fa-check-circle"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                            <i class="fas fa-inbox text-2xl text-gray-300"></i>
                                                        </div>
                                                        <p class="text-base font-bold text-gray-800">Tidak ada jurnal ditemukan</p>
                                                        <p class="text-sm text-gray-500 mt-1">Belum ada jurnal yang sesuai dengan pencarian Anda.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                        </table>
                    </div>

                    <!-- Cards (Mobile) -->
                    <div class="md:hidden space-y-4">
                        @forelse($journals as $journal)
                            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-all">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="text-[10px] text-[#4A72FF] font-bold uppercase tracking-wider mb-0.5">No. Jurnal</div>
                                        <h4 class="font-bold text-gray-900 leading-tight">{{ $journal->no }}</h4>
                                        <div class="text-[10px] text-gray-400 mt-1 uppercase">
                                            <i class="far fa-calendar-alt mr-1"></i>{{ $journal->tanggal?->format('d/m/Y') ?? '-' }}
                                        </div>
                                    </div>
                                    <div>
                                        @if($journal->received_by_admin)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-600 border border-green-100 uppercase">
                                                <i class="fas fa-check-circle mr-1"></i>Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-100 uppercase">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Uraian Pekerjaan</div>
                                    <p class="text-xs text-gray-700 line-clamp-3 leading-relaxed" title="{{ $journal->uraian_pekerjaan }}">
                                        {{ $journal->uraian_pekerjaan }}
                                    </p>
                                </div>

                                <div class="flex items-start justify-between pt-3 border-t border-gray-50">
                                    <div class="flex flex-col items-start space-y-2">
                                        <div class="flex items-center space-x-2 no-print">
                                            <input type="checkbox" :value="{{ $journal->id }}" x-model="selectedJournals" class="w-5 h-5 text-blue-600 rounded cursor-pointer">
                                            <a href="{{ route('admin.posts.show', $journal->id) }}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @php
                                                $hasApproved = $journal->admins()->where('admin_id', $adminId)->where('status', 'approved')->exists();
                                            @endphp
                                            
                                            @if($hasApproved)
                                                <form action="{{ route('admin.posts.cancel-received', $journal->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.posts.toggle-received', $journal->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-9 h-9 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.posts.destroy', $journal->id) }}" method="POST" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin merevisi jurnal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="text-[10px] text-gray-400 leading-tight">
                                            <div class="font-bold uppercase">Dikirim</div>
                                            <div>{{ $journal->created_at->format('d/m/y H:i') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        @php
                                            $doc = trim($journal->dokumen_pekerjaan);
                                        @endphp
                                        @if($doc)
                                            @php
                                                $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|jfif)$/i', $doc);
                                                $docUrl = str_starts_with($doc, 'http') ? $doc : (str_starts_with($doc, 'uploads/') ? asset($doc) : asset('storage/'.$doc));
                                            @endphp
                                            <div class="w-32 h-24 bg-gray-100 rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                                                @if($isImage)
                                                    <img src="{{ $docUrl }}" 
                                                         alt="Dokumen" 
                                                         @click="$dispatch('open-image-modal', { src: $el.src, title: 'Dokumen: {{ $journal->no }}' })"
                                                         class="w-full h-full object-cover cursor-zoom-in hover:brightness-95 transition-all">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="w-32 h-24 bg-gray-50 rounded-xl border border-dashed border-gray-200 flex items-center justify-center text-gray-300">
                                                <i class="fas fa-image text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-3 opacity-20"></i>
                                <p class="text-sm font-bold">Tidak ada jurnal</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $journals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Image Preview Modal -->
    <div x-data="{ 
            open: false, 
            src: '', 
            title: '' 
         }" 
         @open-image-modal.window="open = true; src = $event.detail.src; title = $event.detail.title"
         x-show="open" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         x-transition.opacity
         style="display: none;">
        <div @click.away="open = false" class="relative max-w-4xl w-full bg-white rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                <h4 class="font-bold text-gray-900" x-text="title"></h4>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600 p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4 flex justify-center bg-gray-100">
                <img :src="src" class="max-w-full max-h-[70vh] rounded-lg shadow-lg object-contain">
            </div>
            <div class="p-4 bg-gray-50 flex justify-end">
                <a :href="src" target="_blank" class="px-6 py-2 stat-gradient text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-blue-500/30 transition-all">
                    <i class="fas fa-external-link-alt mr-2"></i>Buka di Tab Baru
                </a>
            </div>
        </div>
    </div>
</body>
</html>
