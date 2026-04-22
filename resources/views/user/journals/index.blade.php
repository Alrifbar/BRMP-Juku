<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal History - Journal Management System</title>
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
            .mobile-compact p, .mobile-compact span, .mobile-compact div, .mobile-compact a, .mobile-compact button { font-size: 0.75rem !important; }
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

            /* Bottom mobile navbar improvements */
            .bottom-navbar a { padding: 10px 6px; }
            .bottom-navbar i { font-size: 1.35rem; }
            .bottom-navbar .text-xs { font-size: 0.70rem; }
        }
    </style>
</head>
<body class="min-h-screen mobile-compact">
    @include('layouts.user_navbar')
    
    <!-- Export Overlay (Mobile) -->
    <div x-data="{ exportOpen:false }" @open-export.window="exportOpen = true">
        <div x-show="exportOpen" x-transition.opacity class="fixed inset-0 z-[60] md:hidden">
            <div @click="exportOpen=false" class="absolute inset-0 bg-black/40"></div>
            <div class="absolute bottom-20 left-0 right-0 mx-4 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <div class="bg-blue-50 dark:bg-blue-900/30 text-[#4A72FF] dark:text-blue-400 w-8 h-8 rounded-lg flex items-center justify-center">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="font-semibold text-gray-800 dark:text-gray-100">Export Jurnal</div>
                    </div>
                    <button @click="exportOpen=false" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('user.export.journals') }}" method="GET" class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Periode</label>
                        <select name="period" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-[#4A72FF]">
                            <option value="daily">Harian (7 hari)</option>
                            <option value="weekly">Mingguan (4 minggu)</option>
                            <option value="monthly">Bulanan</option>
                            <option value="yearly">Tahunan</option>
                            <option value="all">Semua</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Format</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="border dark:border-gray-600 rounded-lg px-3 py-2 flex items-center justify-center space-x-2 cursor-pointer hover:border-[#4A72FF] dark:hover:border-blue-500">
                                <input type="radio" name="format" value="excel" class="mr-2" checked>
                                <span class="text-sm text-gray-700 dark:text-gray-200">Excel</span>
                            </label>
                            <label class="border dark:border-gray-600 rounded-lg px-3 py-2 flex items-center justify-center space-x-2 cursor-pointer hover:border-[#4A72FF] dark:hover:border-blue-500">
                                <input type="radio" name="format" value="pdf" class="mr-2">
                                <span class="text-sm text-gray-700 dark:text-gray-200">PDF</span>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="w-full stat-gradient text-white font-bold rounded-lg py-2.5">Export</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-container py-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-8 text-sm md:text-base">
        <!-- Floating Action Button (Mobile) -->
        <a href="{{ route('user.journals.create') }}" 
           class="md:hidden fixed bottom-24 right-6 w-14 h-14 stat-gradient text-white rounded-full flex items-center justify-center shadow-2xl z-[60] hover:scale-110 active:scale-95 transition-all duration-300">
            <i class="fas fa-plus text-2xl"></i>
        </a>

        <!-- Header -->
        <div class="mb-8 fade-in relative z-[40]">
            <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Journal History</h2>
                        <p class="text-gray-600 dark:text-gray-400">Complete archive of your journal entries</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Export Button (Desktop & Mobile Trigger) -->
                        <div class="relative" x-data="{ open: false }">
                            <!-- Desktop Button -->
                            <button @click="open = !open" class="hidden md:flex stat-gradient text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all duration-300 items-center">
                                <i class="fas fa-download mr-2"></i>
                                Export Jurnal
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            </button>

                            <!-- Mobile Button Trigger -->
                            <button @click="$dispatch('open-export')" class="md:hidden stat-gradient text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-download"></i>
                            </button>
                            
                            <!-- Desktop Dropdown -->
                            <div x-show="open" 
                                 @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden hidden md:block"
                                 style="z-index: 9999;">
                                
                                <div class="py-2">
                                    <div class="px-4 py-2 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-50 dark:border-gray-700/50 mb-2">Export Format</div>
                                    
                                    <!-- Options same as dashboard -->
                                    <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <div class="text-xs font-bold text-gray-700 dark:text-gray-200 mb-2 flex items-center">
                                            <i class="far fa-calendar-alt mr-2 text-[#4A72FF]"></i>
                                            Harian (7 hari)
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('user.export.journals') }}?period=daily&format=excel" class="flex-1 flex items-center justify-center gap-1.5 text-[10px] font-bold bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 px-3 py-2 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/40 transition-all border border-green-100 dark:border-green-800/30">
                                                <i class="fas fa-file-excel"></i> Excel
                                            </a>
                                            <a href="{{ route('user.export.journals') }}?period=daily&format=pdf" class="flex-1 flex items-center justify-center gap-1.5 text-[10px] font-bold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-3 py-2 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40 transition-all border border-red-100 dark:border-red-800/30">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </a>
                                        </div>
                                    </div>

                                    <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors border-t border-gray-50 dark:border-gray-700/50">
                                        <div class="text-xs font-bold text-gray-700 dark:text-gray-200 mb-2 flex items-center">
                                            <i class="fas fa-calendar-week mr-2 text-[#4A72FF]"></i>
                                            Mingguan (4 minggu)
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('user.export.journals') }}?period=weekly&format=excel" class="flex-1 flex items-center justify-center gap-1.5 text-[10px] font-bold bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 px-3 py-2 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/40 transition-all border border-green-100 dark:border-green-800/30">
                                                <i class="fas fa-file-excel"></i> Excel
                                            </a>
                                            <a href="{{ route('user.export.journals') }}?period=weekly&format=pdf" class="flex-1 flex items-center justify-center gap-1.5 text-[10px] font-bold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-3 py-2 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40 transition-all border border-red-100 dark:border-red-800/30">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </a>
                                        </div>
                                    </div>

                                    <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors border-t border-gray-50 dark:border-gray-700/50">
                                        <div class="text-xs font-bold text-gray-700 dark:text-gray-200 mb-2 flex items-center">
                                            <i class="fas fa-calendar-check mr-2 text-[#4A72FF]"></i>
                                            Bulanan (6 bulan)
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('user.export.journals') }}?period=monthly&format=excel" class="flex-1 flex items-center justify-center gap-1.5 text-[10px] font-bold bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 px-3 py-2 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/40 transition-all border border-green-100 dark:border-green-800/30">
                                                <i class="fas fa-file-excel"></i> Excel
                                            </a>
                                            <a href="{{ route('user.export.journals') }}?period=monthly&format=pdf" class="flex-1 flex items-center justify-center gap-1.5 text-[10px] font-bold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-3 py-2 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40 transition-all border border-red-100 dark:border-red-800/30">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hidden md:block">
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-2xl">
                                <i class="fas fa-history text-gray-600 dark:text-gray-300 text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 fade-in">
                <div class="executive-card bg-white dark:bg-gray-800 border-l-4 border-l-[#4A72FF] p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-[#4A72FF] text-xl mr-3"></i>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Journals Table -->
        @if($journals->count() > 0 || request('search'))
            <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl overflow-hidden fade-in"
             x-data="{ 
                selectedJournals: [],
                bulkAction(type) {
                    let actionUrl = '';
                    let confirmMsg = '';
                    
                    switch(type) {
                        case 'delete':
                            actionUrl = '{{ route('user.journals.bulk-delete') }}';
                            confirmMsg = `Hapus ${this.selectedJournals.length} jurnal yang dipilih? Tindakan ini tidak dapat dibatalkan.`;
                            break;
                    }

                    if(confirm(confirmMsg)) {
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
            }">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-table mr-2 text-[#4A72FF]"></i>
                        Riwayat Jurnal ({{ $journals->total() }} total)
                    </h3>
                    <a href="{{ route('user.journals.create') }}" class="stat-gradient text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">Buat Jurnal</a>
                </div>

                <!-- Search Bar -->
                <div class="mb-6">
                    <form action="{{ route('user.journals.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1 relative">
                            <input type="text" 
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari berdasarkan nomor, judul, atau uraian..." 
                                   class="w-full px-4 py-3 pl-10 border border-gray-200 dark:border-gray-700 rounded-xl focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 bg-white dark:bg-gray-800 dark:text-gray-100 outline-none transition-all text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 sm:flex-none stat-gradient text-white px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-300 text-sm font-bold">
                                <i class="fas fa-search mr-2"></i>Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('user.journals.index') }}" class="flex-1 sm:flex-none bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-6 py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-bold flex items-center justify-center">
                                    <i class="fas fa-times mr-2"></i>Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Multi-select Action Bar -->
                <div x-show="selectedJournals.length > 0" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mb-6 p-3 bg-white dark:bg-gray-800 border border-blue-100 dark:border-blue-900/30 rounded-2xl flex flex-col sm:flex-row items-center justify-between shadow-xl ring-1 ring-blue-50 dark:ring-blue-900/10 gap-4">
                    <div class="flex items-center w-full sm:w-auto">
                        <button @click="selectedJournals = []" class="mr-4 hover:bg-gray-100 dark:hover:bg-gray-700 p-2.5 rounded-xl transition-all text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" title="Batalkan Pilihan">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                        <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 mr-4"></div>
                        <span class="font-bold text-gray-800 dark:text-gray-200">
                            <span x-text="selectedJournals.length" class="text-xl mr-1 text-[#4A72FF]"></span> item dipilih
                        </span>
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button @click="selectedJournals = {{ json_encode($journals->pluck('id')) }}" 
                                x-show="selectedJournals.length < {{ $journals->count() }}"
                                class="flex-1 sm:flex-none flex items-center justify-center h-11 px-6 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-[#4A72FF] dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-all font-bold text-sm">
                            <i class="fas fa-check-double mr-2"></i>Pilih Semua
                        </button>
                        <button @click="bulkAction('delete')" 
                                class="flex-1 sm:flex-none flex items-center justify-center h-11 px-6 rounded-xl bg-red-600 text-white hover:bg-red-700 transition-all duration-300 shadow-lg shadow-red-600/20 font-bold text-sm" 
                                title="Hapus Terpilih">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </div>
                </div>

                <!-- Table (Desktop) -->
                <div class="hidden md:block overflow-x-auto whitespace-nowrap">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs md:text-sm">
                        <thead class="bg-blue-50 dark:bg-blue-900/20">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" @change="selectedJournals = $el.checked ? {{ json_encode($journals->pluck('id')) }} : []" class="w-4 h-4 text-blue-600 rounded cursor-pointer">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] dark:text-blue-400 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] dark:text-blue-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] dark:text-blue-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] dark:text-blue-400 uppercase tracking-wider">Dokumen</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] dark:text-blue-400 uppercase tracking-wider">Dibuat</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] dark:text-blue-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($journals as $journal)
                                <tr class="hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition-colors" :class="selectedJournals.includes({{ $journal->id }}) ? 'bg-blue-50/50 dark:bg-blue-900/20' : ''">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" :value="{{ $journal->id }}" x-model="selectedJournals" class="w-4 h-4 text-blue-600 rounded cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $journal->title ?? 'No Title' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-gray-300">
                                            {{ $journal->tanggal ? \Carbon\Carbon::parse($journal->tanggal)->format('d M Y') : $journal->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php 
                                            $approvedCount = $journal->admin_checks ?? 0;
                                            $totalAdmins = $journal->admins->count();
                                        @endphp
                                        @if($approvedCount >= $totalAdmins && $totalAdmins > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-800 shadow-sm">
                                                <i class="fas fa-check-circle mr-1"></i>{{ $approvedCount }}/{{ $totalAdmins }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 border border-gray-100 dark:border-gray-600 shadow-sm">
                                                <i class="fas fa-hourglass-start mr-1"></i>{{ $approvedCount }}/{{ $totalAdmins }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($journal->dokumen_pekerjaan)
                                            <div class="w-20 h-14 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600">
                                                @php
                                                    $doc = trim($journal->dokumen_pekerjaan);
                                                    $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|jfif)$/i', $doc);
                                                    $docUrl = str_starts_with($doc, 'http') ? $doc : (str_starts_with($doc, 'uploads/') ? asset($doc) : asset('storage/'.$doc));
                                                @endphp
                                                @if($isImage)
                                                    <img src="{{ $docUrl }}" 
                                                         alt="Dokumen" 
                                                         @click="$dispatch('open-image-modal', { src: $el.src, title: 'Dokumen: {{ $journal->title ?? 'Journal' }}' })"
                                                         class="w-full h-full object-cover cursor-zoom-in hover:brightness-90 transition-all"
                                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><i class=\'fas fa-file-alt text-gray-400 text-xl\'></i></div>';">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $journal->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('user.journals.show', $journal) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('user.journals.edit', $journal) }}" class="text-[#4A72FF] dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('user.journals.destroy', $journal) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jurnal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-search text-gray-300 dark:text-gray-600 text-4xl mb-4"></i>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada hasil pencarian untuk "{{ request('search') }}"</p>
                                            <a href="{{ route('user.journals.index') }}" class="mt-4 text-[#4A72FF] hover:underline">Lihat semua jurnal</a>
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
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-all" :class="selectedJournals.includes({{ $journal->id }}) ? 'ring-2 ring-blue-500 bg-blue-50/30 dark:bg-blue-900/10' : ''">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-start flex-1 min-w-0 pr-2">
                                    <input type="checkbox" :value="{{ $journal->id }}" x-model="selectedJournals" class="w-5 h-5 text-blue-600 rounded cursor-pointer mt-1 mr-3 flex-shrink-0">
                                    <div class="min-w-0">
                                        <div class="text-[10px] text-[#4A72FF] dark:text-blue-400 font-bold uppercase tracking-wider mb-0.5">Judul Jurnal</div>
                                        <h4 class="font-bold text-gray-900 dark:text-gray-100 leading-tight truncate">{{ $journal->title ?? 'No Title' }}</h4>
                                        <div class="text-[10px] text-gray-400 mt-1 uppercase">
                                            <i class="far fa-calendar-alt mr-1"></i>{{ $journal->tanggal ? \Carbon\Carbon::parse($journal->tanggal)->format('d/m/Y') : $journal->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    @php 
                                        $approvedCount = $journal->admin_checks ?? 0;
                                        $totalAdmins = $journal->admins->count();
                                    @endphp
                                    @if($approvedCount >= $totalAdmins && $totalAdmins > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-800 uppercase">
                                            <i class="fas fa-check-circle mr-1"></i>{{ $approvedCount }}/{{ $totalAdmins }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 border border-gray-100 dark:border-gray-600 uppercase">
                                            <i class="fas fa-hourglass-start mr-1"></i>{{ $approvedCount }}/{{ $totalAdmins }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-50 dark:border-gray-700">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('user.journals.show', $journal) }}" class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('user.journals.edit', $journal) }}" class="w-9 h-9 rounded-xl bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('user.journals.destroy', $journal) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="flex items-center">
                                      @if($journal->dokumen_pekerjaan)
                                          <div class="w-32 h-20 bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm">
                                              @php
                                                   $doc = trim($journal->dokumen_pekerjaan);
                                                   $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|jfif)$/i', $doc);
                                                   $docUrl = str_starts_with($doc, 'http') ? $doc : (str_starts_with($doc, 'uploads/') ? asset($doc) : asset('storage/'.$doc));
                                               @endphp
                                              @if($isImage)
                                                  <img src="{{ $docUrl }}" 
                                                       alt="Dokumen" 
                                                       @click="$dispatch('open-image-modal', { src: $el.src, title: 'Dokumen: {{ $journal->title ?? 'Journal' }}' })"
                                                       class="w-full h-full object-cover cursor-zoom-in hover:brightness-95 transition-all"
                                                       onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><i class=\'fas fa-file-alt text-gray-400 text-xl\'></i></div>';">
                                              @else
                                                  <div class="w-full h-full flex items-center justify-center">
                                                      <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                                  </div>
                                              @endif
                                          </div>
                                      @else
                                          <div class="w-32 h-20 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-300 dark:text-gray-600">
                                              <i class="fas fa-image text-2xl"></i>
                                          </div>
                                      @endif
                                  </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-12 text-center border border-gray-100 dark:border-gray-700 shadow-sm">
                            <i class="fas fa-search text-gray-300 dark:text-gray-600 text-4xl mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada hasil pencarian untuk "{{ request('search') }}"</p>
                            <a href="{{ route('user.journals.index') }}" class="mt-4 inline-block text-[#4A72FF] hover:underline">Lihat semua jurnal</a>
                        </div>
                    @endforelse
                </div>
                <div class="mt-6">
                    {{ $journals->links() }}
                </div>
            </div>
        </div>
        @else
            <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-12 text-center fade-in">
                <div class="bg-gray-100 dark:bg-gray-700 w-32 h-32 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-folder-open text-5xl text-gray-600 dark:text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">No Journal History</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 text-lg">Start writing your first journal to record your activities</p>
                <a href="{{ route('user.journals.create') }}" 
                   class="inline-flex items-center px-8 py-4 stat-gradient text-white rounded-xl hover:shadow-xl transition-all duration-300 text-lg font-semibold">
                    <i class="fas fa-plus-circle mr-3 text-xl"></i>
                    Create New Journal
                </a>
            </div>
        @endif
    </main>

    <script>
        function markAllNotificationsAsRead() {
            fetch('/user/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const dropdown = document.querySelector('.notification-dropdown-item')?.parentElement;
                    if (dropdown) dropdown.innerHTML = '<div class="text-center py-6 text-sm text-gray-500">Tidak ada notifikasi</div>';
                    const counts = document.querySelectorAll('.bg-red-500');
                    counts.forEach(c => c.remove());
                    const markAllBtn = document.querySelector('button[onclick="markAllNotificationsAsRead()"]')?.parentElement;
                    if (markAllBtn) markAllBtn.remove();
                }
            })
            .catch(error => console.error('Error:', error));
        }

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
    </script>

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
        <div @click.away="open = false" class="relative max-w-4xl w-full bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900">
                <h4 class="font-bold text-gray-900 dark:text-gray-100" x-text="title"></h4>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4 flex justify-center bg-gray-100 dark:bg-gray-700">
                <img :src="src" class="max-w-full max-h-[70vh] rounded-lg shadow-lg object-contain">
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-900 flex justify-end">
                <a :href="src" target="_blank" class="px-6 py-2 stat-gradient text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-blue-500/30 transition-all">
                    <i class="fas fa-external-link-alt mr-2"></i>Buka di Tab Baru
                </a>
            </div>
        </div>
    </div>
</body>
</html>
