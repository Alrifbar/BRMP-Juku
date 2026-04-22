<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Jurnal Seluruh Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        @media print {
            body { font-size: 12px; }
            .no-print { display: none; }
            .page-break { page-break-after: always; }
        }
        .stat-gradient { background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%); }
    </style>
</head>
<body class="bg-white text-gray-900">
    <div class="max-w-5xl mx-auto p-6">
        <div class="flex items-start justify-between gap-6 mb-6 border-b border-blue-100 pb-4">
            <div class="flex items-center gap-4">
                @php
                    $photo = optional($admin)->profile_photo;
                @endphp
                @if(!empty($photo))
                    <img src="{{ str_starts_with($photo, 'http') ? $photo : (str_starts_with($photo, 'uploads/') ? asset($photo) : asset('storage/'.$photo)) }}" alt="Admin" class="w-14 h-14 rounded-full object-cover border border-blue-200" />
                @else
                    <div class="w-14 h-14 stat-gradient rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(optional($admin)->name ?? 'A', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-[#4A72FF]">Laporan Jurnal Seluruh Pegawai</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Admin: <span class="font-medium text-gray-900">{{ optional($admin)->name ?? 'Admin' }}</span> ({{ optional($admin)->email ?? 'admin@example.com' }})
                    </p>
                    <p class="text-sm text-gray-600">Divisi: Admin</p>
                </div>
            </div>
            <div class="text-right no-print">
                <button onclick="window.print()" class="px-4 py-2 bg-[#4A72FF] text-white rounded-lg text-sm hover:bg-blue-600 transition-colors">
                    <i class="fas fa-print mr-2"></i>Print / Save PDF
                </button>
            </div>
        </div>

        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <p class="text-sm font-semibold text-[#4A72FF]">Periode:</p>
                    <p class="text-sm text-gray-600">
                        @switch($period)
                            @case('daily') Harian (7 hari terakhir) @break
                            @case('weekly') Mingguan (4 minggu terakhir) @break
                            @case('monthly') Bulanan ({{ now()->format('F Y') }}) @break
                            @case('yearly') Tahunan ({{ now()->format('Y') }}) @break
                            @default Semua Jurnal
                        @endswitch
                    </p>
                    @if($rangeStart && $rangeEnd)
                        <p class="text-xs text-gray-500 mt-1">
                            Jarak jangka jurnal: {{ $rangeStart->format('d M Y, H:i') }} s/d {{ $rangeEnd->format('d M Y, H:i') }}
                        </p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-700">Total Jurnal:</p>
                    <p class="text-sm text-gray-600">{{ $journals->count() }} entries</p>
                    <p class="text-xs text-gray-500 mt-1">Generated: {{ now()->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-green-50 p-3 rounded-lg text-center">
                <p class="text-2xl font-bold text-green-700">{{ $journals->where('received_by_admin', true)->count() }}</p>
                <p class="text-xs text-green-600">Diterima Admin</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-lg text-center">
                <p class="text-2xl font-bold text-yellow-700">{{ $journals->where('received_by_admin', false)->count() }}</p>
                <p class="text-xs text-yellow-600">Belum diterima</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg text-center">
                <p class="text-2xl font-bold text-blue-700">
                    {{ $journals->count() > 0 ? round(($journals->where('received_by_admin', true)->count() / $journals->count()) * 100, 1) : 0 }}%
                </p>
                <p class="text-xs text-blue-600">Completion Rate</p>
            </div>
        </div>

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
                    @foreach($journals as $j)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $j->no ?? $j->id }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="leading-tight">
                                    <div class="font-medium">{{ $j->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $j->created_at->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ optional($j->user)->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="max-w-xs">
                                    @php
                                        $text = $j->uraian_pekerjaan ?? $j->content ?? 'No Content';
                                        $text = is_string($text) ? preg_replace('/\s+/', ' ', $text) : '';
                                        if (strlen($text) > 100) { $text = substr($text, 0, 100) . '...'; }
                                    @endphp
                                    {{ $text }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($j->dokumen_pekerjaan)
                                    @php $doc = $j->dokumen_pekerjaan; @endphp
                                    <div class="w-20 h-14 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        <img src="{{ str_starts_with($doc, 'http') ? $doc : (str_starts_with($doc, 'uploads/') ? asset($doc) : asset('storage/'.$doc)) }}" alt="Dokumen" class="w-full h-full object-cover"
                                             onerror="this.style.display='none'; this.closest('td').innerHTML = '<span class=\'text-gray-400\'>-</span>';">
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($j->received_by_admin)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Diterima
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-hourglass-start mr-1"></i>Belum diterima
                                    </span>
                                @endif
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

        <div class="mt-8 pt-4 border-t text-xs text-gray-500 text-center">
            <p>Laporan jurnal seluruh pegawai - Journal Management System</p>
            <p class="mt-1">Halaman ini dicetak pada {{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>
</body>
</html>
