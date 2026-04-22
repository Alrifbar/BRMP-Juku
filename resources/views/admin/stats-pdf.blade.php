<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Statistik (PDF)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        @media print {
            body { font-size: 12px; }
            .no-print { display: none; }
            .print-grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 8px; }
            canvas { max-height: 160px !important; }
        }
        .stat-gradient { background: linear-gradient(135deg, #4A72FF 0%, #2563EB 100%); }
        .chart-card { height: 180px; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-white text-gray-900">
    <div class="max-w-5xl mx-auto p-6">
        <div class="flex items-start justify-between gap-6 mb-6">
            <div class="flex items-center gap-4">
                @php
                    $photo = isset($adminPhoto) ? $adminPhoto : (Session::get('admin_profile_photo') ?? null);
                @endphp
                @if(!empty($photo))
                    <img src="{{ str_starts_with($photo, 'http') ? $photo : (str_starts_with($photo, 'uploads/') ? asset($photo) : asset('storage/'.$photo)) }}" alt="Admin" class="w-14 h-14 rounded-full object-cover border border-blue-200" />
                @else
                    <div class="w-14 h-14 stat-gradient rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(optional($admin)->name ?? 'A', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-[#4A72FF]">Statistik ({{ strtoupper($period) }})</h1>
                    <p class="text-sm text-gray-600">Generated: {{ now()->format('d M Y, H:i') }}</p>
                    @if(!empty($admin))
                        <p class="text-xs text-gray-500">Admin: {{ $admin->name }} ({{ $admin->email }})</p>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <button onclick="window.print()" class="px-4 py-2 bg-[#4A72FF] text-white rounded-lg text-sm hover:bg-blue-600 transition-colors">Print / Save PDF</button>
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
                    @foreach($rows as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 print-grid-2">
            <div class="p-4 border rounded-lg chart-card">
                <h3 class="text-lg font-semibold mb-4">Aktivitas Harian (7 hari)</h3>
                <canvas id="dailyChart" height="120"></canvas>
            </div>
            <div class="p-4 border rounded-lg chart-card">
                <h3 class="text-lg font-semibold mb-4">Aktivitas Mingguan (12 minggu)</h3>
                <canvas id="weeklyChart" height="120"></canvas>
            </div>
            <div class="p-4 border rounded-lg chart-card">
                <h3 class="text-lg font-semibold mb-4">Statistik Tahunan</h3>
                <canvas id="yearlyChart" height="120"></canvas>
            </div>
            <div class="p-4 border rounded-lg chart-card">
                <h3 class="text-lg font-semibold mb-4">Top Performers</h3>
                <canvas id="topUsersChart" height="120"></canvas>
            </div>
            <div class="p-4 border rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Active Users</h3>
                <div class="text-sm text-gray-600">Aktif 24 jam terakhir: {{ $activeUsers }} / {{ $totalEmployees }}</div>
                <div class="mt-2 w-full bg-gray-100 rounded-full h-3">
                    @php
                        $activePct = $totalEmployees > 0 ? round(($activeUsers / $totalEmployees) * 100) : 0;
                    @endphp
                    <div class="h-3 rounded-full stat-gradient" style="width: {{ $activePct }}%"></div>
                </div>
            </div>
        </div>

        <script>
            (function(){
                const dailyData = @json(($dailyChart ?? collect())->pluck('count'));
                const dailyLabels = @json(($dailyChart ?? collect())->pluck('date'));
                const weeklyLabels = @json(($weeklyChart ?? collect())->map(function($w){ return ($w->week_start ?? '') . ' - ' . ($w->week_end ?? ''); }));
                const weeklyData = @json(($weeklyChart ?? collect())->pluck('count'));
                const yearlyLabels = @json(($yearlyChart ?? collect())->pluck('year'));
                const yearlyData = @json(($yearlyChart ?? collect())->pluck('count'));
                const topLabels = @json(($topUsersExport ?? collect())->pluck('name'));
                const topData = @json(($topUsersExport ?? collect())->pluck('journals_count'));

                function simpleChart(ctxId, type, labels, data, label) {
                    const el = document.getElementById(ctxId);
                    if (!el) return;
                    if (el.dataset.rendered) return;
                    el.dataset.rendered = '1';
                    new Chart(el.getContext('2d'), {
                        type,
                        data: {
                            labels,
                            datasets: [{
                                label,
                                data,
                                borderColor: '#4A72FF',
                                backgroundColor: 'rgba(74,114,255,0.25)',
                                fill: true,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            animation: false,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                        }
                    });
                }

                simpleChart('dailyChart', 'bar', dailyLabels, dailyData, 'Jurnal');
                simpleChart('weeklyChart', 'bar', weeklyLabels, weeklyData, 'Jurnal');
                simpleChart('yearlyChart', 'line', yearlyLabels, yearlyData, 'Jurnal');
                simpleChart('topUsersChart', 'bar', topLabels, topData, 'Entries');
            })();
        </script>
    </div>
</body>
</html>
