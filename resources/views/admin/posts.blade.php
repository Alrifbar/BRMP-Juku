<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Management - Admin Panel</title>
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
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .glass-morphism {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
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

        /* Custom Scrollbar */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
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
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 pb-24 md:pb-8 text-sm md:text-base">
        <!-- Header -->
        <div class="mb-8 fade-in">
            <div class="executive-card rounded-2xl p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Journal Entries</h2>
                        <p class="text-gray-600">Manage and review all submitted journal entries</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-gray-100 p-4 rounded-2xl">
                            <i class="fas fa-file-alt text-gray-600 text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 fade-in">
                <div class="executive-card border-l-4 border-l-[#4A72FF] p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-[#4A72FF] text-xl mr-3"></i>
                        <span class="font-medium text-gray-800">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Journals Table -->
        <div class="executive-card rounded-2xl overflow-hidden fade-in" 
             x-data="{ 
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
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-list mr-2 text-[#4A72FF]"></i>
                        All Journals ({{ $journals->total() }} total)
                    </h3>
                </div>

                <!-- Search Bar -->
                <div class="mb-6">
                    <form action="{{ route('admin.posts') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1 relative">
                            <input type="text" 
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search by journal number, user name, or title..." 
                                   class="w-full px-4 py-3 pl-10 border border-gray-200 rounded-lg focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-100 bg-white outline-none transition-all text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 sm:flex-none stat-gradient text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-bold">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.posts') }}" class="flex-1 sm:flex-none bg-blue-50 text-[#4A72FF] px-6 py-3 rounded-lg hover:bg-blue-100 transition-colors text-sm font-bold flex items-center justify-center">
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
                                class="flex-1 sm:flex-none flex items-center justify-center h-11 px-6 rounded-xl bg-green-500 text-white hover:bg-green-600 transition-all duration-300 shadow-lg shadow-green-500/20 font-bold text-sm" 
                                title="Setujui Terpilih">
                            <i class="fas fa-check-circle mr-2"></i>Terima
                        </button>
                        <button @click="bulkAction('revise')" 
                                class="flex-1 sm:flex-none flex items-center justify-center h-11 px-6 rounded-xl bg-[#4A72FF] text-white hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-blue-500/20 font-bold text-sm" 
                                title="Revisi Terpilih">
                            <i class="fas fa-undo-alt mr-2"></i>Revisi
                        </button>
                    </div>
                </div>

                <!-- Table (Desktop) -->
                <div class="hidden md:block overflow-x-auto whitespace-nowrap">
                    <table class="min-w-full divide-y divide-gray-200 text-xs md:text-sm">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" @change="selectedJournals = $el.checked ? {{ json_encode($journals->pluck('id')) }} : []" class="w-4 h-4 text-blue-600 rounded cursor-pointer">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">
                                    No Jurnal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">
                                    User
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">
                                    Judul
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">
                                    Document
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">
                                    Submitted
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($journals as $journal)
                                <tr class="hover:bg-gray-50 transition-colors" :class="selectedJournals.includes({{ $journal->id }}) ? 'bg-blue-50/50' : ''">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" :value="{{ $journal->id }}" x-model="selectedJournals" class="w-4 h-4 text-blue-600 rounded cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.posts.show', $journal->id) }}" 
                                               class="text-gray-700 hover:text-gray-900">
                                                {{ ($journals->firstItem() ?? 1) + $loop->index }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $journal->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $journal->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $currentAdminId = Session::get('user_id');
                                            $adminEntry = $journal->admins->firstWhere('id', $currentAdminId);
                                            $pivotStatus = $adminEntry ? $adminEntry->pivot->status : 'waiting';
                                        @endphp
                                        @if($pivotStatus === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Approved
                                            </span>
                                        @elseif($pivotStatus === 'revised')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-edit mr-1"></i>Revised
                                            </span>
                                        @elseif($pivotStatus === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-hourglass-start mr-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $journal->title ?? $journal->uraian_pekerjaan }}">
                                            {{ $journal->title 
                                                ? (strlen($journal->title) > 60 ? substr($journal->title, 0, 60) . '...' : $journal->title)
                                                : (strlen($journal->uraian_pekerjaan) > 60 ? substr($journal->uraian_pekerjaan, 0, 60) . '...' : $journal->uraian_pekerjaan) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($journal->dokumen_pekerjaan)
                                            <div class="w-20 h-14 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                                @php
                                                    $doc = trim($journal->dokumen_pekerjaan);
                                                    $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|jfif)$/i', $doc);
                                                    $docUrl = str_starts_with($doc, 'http') ? $doc : (str_starts_with($doc, 'uploads/') ? asset($doc) : asset('storage/'.$doc));
                                                @endphp
                                                @if($isImage)
                                                    <img src="{{ $docUrl }}" 
                                                         alt="Dokumen" 
                                                         @click="$dispatch('open-image-modal', { src: $el.src, title: 'Dokumen: Journal #{{ ($journals->firstItem() ?? 1) + $loop->index }}' })"
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
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $journal->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            @php
                                                $adminId = Session::get('user_id');
                                                $hasApproved = $journal->admins->where('id', $adminId)->where('pivot.status', 'approved')->isNotEmpty();
                                            @endphp
                                            
                                            @if(!$hasApproved)
                                                <form action="{{ route('admin.posts.toggle-received', $journal->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-800" title="Terima Laporan">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.posts.cancel-received', $journal->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-gray-400 hover:text-gray-600" title="Batalkan Persetujuan">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('admin.posts.show', $journal->id) }}" 
                                               class="text-gray-600 hover:text-gray-800" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.posts.destroy', $journal->id) }}" method="POST" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin merevisi jurnal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-blue-600 hover:text-blue-800" title="Revisi Jurnal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Cards (Mobile) -->
                <div class="md:hidden space-y-4">
                    @foreach($journals as $journal)
                        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-all"
                             :class="selectedJournals.includes({{ $journal->id }}) ? 'ring-2 ring-blue-100 bg-blue-50/30' : ''">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" :value="{{ $journal->id }}" x-model="selectedJournals" class="w-5 h-5 text-blue-600 rounded cursor-pointer">
                                    <div>
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">#{{ ($journals->firstItem() ?? 1) + $loop->index }}</span>
                                        <h4 class="font-bold text-gray-900 leading-tight">{{ $journal->user->name }}</h4>
                                        <p class="text-[10px] text-gray-500">{{ $journal->user->email }}</p>
                                    </div>
                                </div>
                                @php
                                    $currentAdminId = Session::get('user_id');
                                    $adminEntry = $journal->admins->firstWhere('id', $currentAdminId);
                                    $pivotStatus = $adminEntry ? $adminEntry->pivot->status : 'waiting';
                                @endphp
                                <div>
                                    @if($pivotStatus === 'approved')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Approved
                                        </span>
                                    @elseif($pivotStatus === 'revised')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                            <i class="fas fa-edit mr-1"></i>Revised
                                        </span>
                                    @elseif($pivotStatus === 'rejected')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-800">
                                            <i class="fas fa-hourglass-start mr-1"></i>Pending
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <p class="text-xs text-gray-700 line-clamp-2" title="{{ $journal->title ?? $journal->uraian_pekerjaan }}">
                                    {{ $journal->title ?? $journal->uraian_pekerjaan }}
                                </p>
                            </div>

                            <div class="flex items-start justify-between pt-3 border-t border-gray-50">
                                <div class="flex flex-col items-start space-y-2">
                                    <div class="flex items-center space-x-2">
                                    @php
                                        $adminId = Session::get('user_id');
                                        $hasApproved = $journal->admins->where('id', $adminId)->where('pivot.status', 'approved')->isNotEmpty();
                                    @endphp
                                    
                                    @if(!$hasApproved)
                                        <form action="{{ route('admin.posts.toggle-received', $journal->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-9 h-9 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.posts.cancel-received', $journal->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.posts.show', $journal->id) }}" 
                                       class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
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
                                    @if($journal->dokumen_pekerjaan)
                                        <div class="w-32 h-24 bg-gray-100 rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                                            @php
                                                $doc = trim($journal->dokumen_pekerjaan);
                                                $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|jfif)$/i', $doc);
                                                $docUrl = str_starts_with($doc, 'http') ? $doc : (str_starts_with($doc, 'uploads/') ? asset($doc) : asset('storage/'.$doc));
                                            @endphp
                                            @if($isImage)
                                                <img src="{{ $docUrl }}" 
                                                     alt="Dokumen" 
                                                     @click="$dispatch('open-image-modal', { src: $el.src, title: 'Dokumen: Journal #{{ ($journals->firstItem() ?? 1) + $loop->index }}' })"
                                                     class="w-full h-full object-cover cursor-zoom-in hover:brightness-95 transition-all"
                                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><i class=\'fas fa-file-alt text-gray-400 text-xl\'></i></div>';">
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
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $journals->links() }}
                </div>
            </div>
        </div>
    </main>

    <!-- Custom Footer padding for bottom nav -->
    <div class="h-20 md:hidden"></div>

    <script>
        // Search functionality
        const searchInput = document.querySelector('input[type="text"]');
        const tableRows = document.querySelectorAll('tbody tr');

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
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
