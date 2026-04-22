<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Jurnal - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        @media print {
            body { font-size: 12px; }
            .no-print { display: none; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body class="bg-white text-gray-900">
    <div class="max-w-5xl mx-auto p-6">
        <!-- Header -->
        <div class="flex items-start justify-between gap-6 mb-6 border-b border-blue-100 pb-4">
            <div class="flex items-center gap-4">
                @php
                    $uphoto = $user->profile_photo ?? null;
                @endphp
                @if(!empty($uphoto))
                    <img src="{{ str_starts_with($uphoto, 'http') ? $uphoto : (str_starts_with($uphoto, 'uploads/') ? asset($uphoto) : asset('storage/'.$uphoto)) }}" alt="User" class="w-14 h-14 rounded-full object-cover border border-blue-200" />
                @else
                    <div class="w-14 h-14 stat-gradient rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-[#4A72FF]">Laporan Jurnal Pribadi</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Nama: <span class="font-medium text-gray-900">{{ $user->name }}</span> ({{ $user->email }})
                    </p>
                    <p class="text-sm text-gray-600">
                        Divisi: {{ $user->division ?? 'Tidak ada' }}
                    </p>
                </div>
            </div>
            <div class="text-right no-print">
                <button onclick="window.print()" class="px-4 py-2 bg-[#4A72FF] text-white rounded-lg text-sm hover:bg-blue-600 transition-colors">
                    <i class="fas fa-print mr-2"></i>Print / Save PDF
                </button>
            </div>
        </div>

        <!-- Period Info -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-[#4A72FF]">Periode:</p>
                    <p class="text-sm text-gray-600">
                        @switch($period)
                            @case('daily')
                                Harian (7 hari terakhir)
                                @break
                            @case('weekly')
                                Mingguan (4 minggu terakhir)
                                @break
                            @case('monthly')
                                Bulanan ({{ now()->format('F Y') }})
                                @break
                            @case('yearly')
                                Tahunan (Semua jurnal)
                                @break
                            @default
                                Semua Jurnal
                                @break
                        @endswitch
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-700">Total Jurnal:</p>
                    <p class="text-sm text-gray-600">{{ $journals->count() }} entries</p>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500">
                Generated: {{ now()->format('d M Y, H:i') }}
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-green-50 p-3 rounded-lg text-center">
                <p class="text-2xl font-bold text-green-700">{{ $journals->where('received_by_admin', true)->count() }}</p>
                <p class="text-xs text-green-600">Diterima Admin</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-lg text-center">
                <p class="text-2xl font-bold text-yellow-700">{{ $journals->where('received_by_admin', false)->count() }}</p>
                <p class="text-xs text-yellow-600">Pending</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg text-center">
                <p class="text-2xl font-bold text-blue-700">{{ $journals->count() > 0 ? round(($journals->where('received_by_admin', true)->count() / $journals->count()) * 100, 1) : 0 }}%</p>
                <p class="text-xs text-blue-600">Completion Rate</p>
            </div>
        </div>

        <!-- Journals Table -->
        <div class="border rounded-lg overflow-hidden border-blue-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-50">
                    <tr>
                        @foreach($header as $h)
                            <th class="px-4 py-3 text-left text-xs font-semibold text-[#4A72FF] uppercase">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($journals as $journal)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $journal->no ?? $journal->id }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="leading-tight">
                                    <div class="font-medium">{{ $journal->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $journal->created_at->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @php
                                    $atasanText = null;
                                    if(isset($journal->admins) && $journal->admins->count() > 0){
                                        $atasanText = $journal->admins->map(function($a){
                                            $st = $a->pivot->status ?? 'waiting';
                                            $label = match($st){
                                                'approved' => 'Approved',
                                                'revised' => 'Revisi',
                                                'rejected' => 'Ditolak',
                                                default => 'Menunggu',
                                            };
                                            return $a->name.' ('.$label.')';
                                        })->join(', ');
                                    } else {
                                        $atasanText = $journal->nama_atasan ?? 'No Atasan';
                                    }
                                @endphp
                                {{ $atasanText }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="max-w-xs">
                                    {{ Str::limit($journal->uraian_pekerjaan ?? $journal->content ?? 'No Content', 100) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($journal->dokumen_pekerjaan)
                                    @php $doc = trim($journal->dokumen_pekerjaan); @endphp
                                    <div class="w-20 h-14 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        @php
                                            $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|jfif)$/i', $doc);
                                            $docUrl = str_starts_with($doc, 'http') ? $doc : (str_starts_with($doc, 'uploads/') ? asset($doc) : asset('storage/'.$doc));
                                        @endphp
                                        @if($isImage)
                                            <img src="{{ $docUrl }}" alt="Dokumen" class="w-full h-full object-cover"
                                                 onerror="this.style.display='none'; this.parentElement.innerHTML = '<div class=\'w-full h-full flex items-center justify-center\'><i class=\'fas fa-file-alt text-gray-400 text-xl\'></i></div>';">
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
                            <td class="px-4 py-3 text-sm">
                                @php
                                    $target = isset($journal->admins) ? ($journal->admins->count() ?: 1) : 1;
                                    $approved = isset($journal->admins) ? $journal->admins->where('pivot.status','approved')->count() : ((int) ($journal->admin_checks ?? 0));
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $approved >= $target ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas {{ $approved >= $target ? 'fa-check-circle' : 'fa-hourglass-start' }} mr-1"></i>{{ $approved }}/{{ $target }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($journals->count() === 0)
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 font-medium">Tidak ada jurnal</p>
                <p class="text-sm text-gray-400 mt-1">Belum ada jurnal untuk periode ini</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="mt-8 pt-4 border-t text-xs text-gray-500 text-center">
            <p>Laporan jurnal pribadi - Journal Management System</p>
            <p class="mt-1">Halaman ini dicetak pada {{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>
</body>
</html>
