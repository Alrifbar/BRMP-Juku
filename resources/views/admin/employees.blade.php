<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Pegawai - Admin Panel</title>
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
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .slide-in {
            animation: slideIn 0.6s ease-out;
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
        
        .hover-scale {
            transition: all 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
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

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 pb-24 md:pb-8 text-sm md:text-base">
        <!-- Header -->
        <div class="mb-8 fade-in">
            <div class="executive-card rounded-2xl p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Pegawai Management</h2>
                        <p class="text-gray-600">Daftar pegawai (user non-admin)</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-blue-50 p-4 rounded-2xl">
                            <i class="fas fa-id-badge text-[#4A72FF] text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 fade-in">
                <div class="executive-card border-l-4 border-l-[#4A72FF] p-4 bg-blue-50">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-[#4A72FF] text-xl mr-3"></i>
                        <span class="font-medium text-gray-800">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Employees Table -->
        <div class="executive-card rounded-2xl overflow-hidden fade-in"
             x-data="{ 
                selectedUsers: [],
                bulkDelete() {
                    if(confirm(`Hapus ${this.selectedUsers.length} akun pegawai yang dipilih? Tindakan ini tidak dapat dibatalkan.`)) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('admin.users.bulk-delete') }}';
                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        form.appendChild(csrf);
                        const ids = document.createElement('input');
                        ids.type = 'hidden';
                        ids.name = 'user_ids';
                        ids.value = JSON.stringify(this.selectedUsers);
                        form.appendChild(ids);
                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            }">
            <div class="p-6">
                <div class="flex flex-row justify-between items-center mb-6 gap-2">
                    <div class="flex flex-col">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-users mr-2 text-[#4A72FF]"></i>
                            Regular Users
                        </h3>
                        <span class="text-sm font-normal text-gray-500 mt-1">({{ $employees->total() }} total)</span>
                    </div>
                    <a href="{{ route('admin.users.create') }}?type=user" class="stat-gradient text-white px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl hover:shadow-lg shadow-blue-500/30 transition-all duration-300 flex items-center transform hover:-translate-y-0.5 whitespace-nowrap text-xs sm:text-sm">
                        <i class="fas fa-user-plus mr-2"></i>Add User
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="mb-6">
                    <form action="{{ route('admin.employees') }}" method="GET" class="relative group flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input type="text" 
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search employees by name or email..." 
                                   class="w-full px-4 py-3 pl-10 border-2 border-gray-100 rounded-xl focus:border-[#4A72FF] focus:ring-2 focus:ring-blue-100 bg-gray-50 focus:bg-white transition-all duration-300 outline-none text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400 group-focus-within:text-[#4A72FF] transition-colors"></i>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 sm:flex-none stat-gradient text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5 text-sm">Search</button>
                            @if(request('search'))
                                <a href="{{ route('admin.employees') }}" class="flex-1 sm:flex-none bg-gray-100 text-gray-600 px-6 py-3 rounded-xl font-bold flex items-center justify-center hover:bg-gray-200 transition-colors text-sm">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Multi-select Action Bar -->
                <div x-show="selectedUsers.length > 0" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mb-6 p-3 bg-white border border-blue-100 rounded-2xl flex flex-col sm:flex-row items-center justify-between shadow-xl ring-1 ring-blue-50 gap-4">
                    <div class="flex items-center">
                        <button @click="selectedUsers = []" class="mr-4 hover:bg-gray-100 p-2.5 rounded-xl transition-all text-gray-400 hover:text-gray-600" title="Batalkan Pilihan">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                        <div class="h-8 w-px bg-gray-200 mr-4"></div>
                        <span class="font-bold text-gray-800">
                            <span x-text="selectedUsers.length" class="text-xl mr-1 text-[#4A72FF]"></span> item dipilih
                        </span>
                    </div>
                    <div class="flex items-center">
                        <button @click="bulkDelete()" 
                                class="flex items-center justify-center h-11 px-6 rounded-xl bg-red-500 text-white hover:bg-red-600 transition-all duration-300 shadow-lg shadow-red-500/20 font-bold text-sm" 
                                title="Hapus Terpilih">
                            <i class="fas fa-trash-alt mr-2"></i>Hapus Akun
                        </button>
                    </div>
                </div>

                <!-- Table (Desktop) -->
                <div class="hidden md:block overflow-x-auto whitespace-nowrap">
                    <table class="min-w-full divide-y divide-gray-100 text-xs md:text-sm">
                        <thead class="bg-blue-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left w-10">
                                    <input type="checkbox" @change="selectedUsers = $el.checked ? {{ json_encode($employees->pluck('id')) }} : []" class="w-4 h-4 text-[#4A72FF] rounded cursor-pointer border-gray-300 focus:ring-[#4A72FF]">
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">Pegawai</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">Kontak & Alamat</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">Jurnal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">Joined</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#4A72FF] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($employees as $employee)
                                <tr class="hover:bg-blue-50/30 transition-colors duration-200" :class="selectedUsers.includes({{ $employee->id }}) ? 'bg-blue-50/50' : ''">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" :value="{{ $employee->id }}" x-model="selectedUsers" class="w-4 h-4 text-[#4A72FF] rounded cursor-pointer border-gray-300 focus:ring-[#4A72FF]">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($employee->profile_photo)
                                                    <img src="{{ str_starts_with($employee->profile_photo, 'http') ? $employee->profile_photo : (str_starts_with($employee->profile_photo, 'uploads/') ? asset($employee->profile_photo) : asset('storage/'.$employee->profile_photo)) }}" class="h-10 w-10 rounded-full object-cover" />
                                                @else
                                                    <div class="h-10 w-10 rounded-full stat-gradient flex items-center justify-center text-white font-bold">
                                                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $employee->name }}</div>
                                                <div class="text-xs text-[#4A72FF] font-medium">NIP: {{ $employee->nip ?? '-' }}</div>
                                                <div class="text-xs text-gray-500">{{ $employee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-gray-900 font-medium">
                                            <i class="fas fa-phone mr-1 text-[#4A72FF]"></i> {{ $employee->phone ?? '-' }}
                                        </div>
                                        <div class="text-[10px] text-gray-500 mt-1 max-w-[200px] truncate" title="{{ $employee->address }}">
                                            <i class="fas fa-map-marker-alt mr-1 text-red-400"></i> {{ $employee->address ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col items-center">
                                            <span class="px-3 py-1 text-xs font-bold bg-blue-50 text-[#4A72FF] rounded-full">
                                                {{ $employee->journals_count }} Posts
                                            </span>
                                            <span class="text-[10px] text-green-600 mt-1 font-semibold">
                                                {{ $employee->received_journals_count }} Diterima
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $employee->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.employees.show', $employee->id) }}" class="text-gray-400 hover:text-[#4A72FF] transition-colors p-1" title="View Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.export.user-journals') }}?user_id={{ $employee->id }}&period=all&format=pdf" class="text-gray-400 hover:text-red-600 transition-colors p-1" title="Export PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $employee->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 p-1 transition-colors" onclick="return confirm('Hapus akun pegawai ini? Tindakan ini tidak dapat dibatalkan.')">
                                                    <i class="fas fa-trash"></i>
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
                    @foreach($employees as $employee)
                        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-all"
                             :class="selectedUsers.includes({{ $employee->id }}) ? 'ring-2 ring-blue-100 bg-blue-50/30' : ''">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" :value="{{ $employee->id }}" x-model="selectedUsers" class="w-5 h-5 text-[#4A72FF] rounded cursor-pointer border-gray-300 focus:ring-[#4A72FF] mr-3">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if($employee->profile_photo)
                                                <img src="{{ str_starts_with($employee->profile_photo, 'http') ? $employee->profile_photo : (str_starts_with($employee->profile_photo, 'uploads/') ? asset($employee->profile_photo) : asset('storage/'.$employee->profile_photo)) }}" class="h-12 w-12 rounded-full object-cover shadow-sm border border-gray-100" />
                                            @else
                                                <div class="h-12 w-12 rounded-full stat-gradient flex items-center justify-center text-white font-bold shadow-md shadow-blue-500/20">
                                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-bold text-gray-900 leading-tight">{{ $employee->name }}</h4>
                                            <p class="text-[10px] text-[#4A72FF] font-bold">NIP: {{ $employee->nip ?? '-' }}</p>
                                            <p class="text-[10px] text-gray-500">{{ $employee->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                <div class="flex items-center space-x-6">
                                    <div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Jurnal</div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs font-bold text-[#4A72FF]">{{ $employee->journals_count }} Posts</span>
                                            <span class="text-[10px] text-green-600 font-semibold">{{ $employee->received_journals_count }} OK</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Joined</div>
                                        <div class="text-xs text-gray-700 font-medium">{{ $employee->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.employees.show', $employee->id) }}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.export.user-journals') }}?user_id={{ $employee->id }}&period=all&format=pdf" class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $employee->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center shadow-sm" onclick="return confirm('Hapus akun pegawai ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </main>

    <!-- Custom Footer padding for bottom nav -->
    <div class="h-20 md:hidden"></div>

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

    <script>
        // Server-side search is active. This script can be removed or used for simple highlighting.
    </script>
</body>
</html>
