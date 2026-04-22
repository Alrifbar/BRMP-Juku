<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Export Admin' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } .stat-gradient{background:linear-gradient(135deg,#4A72FF 0%,#2563EB 100%);} </style>
</head>
<body class="bg-white text-gray-900">
    <div class="max-w-5xl mx-auto p-6">
        <div class="flex items-start justify-between gap-6 mb-6">
            <div class="flex items-center gap-4">
                @php $photo = isset($adminPhoto) ? $adminPhoto : (Session::get('admin_profile_photo') ?? null); @endphp
                @if(!empty($photo))
                    <img src="{{ str_starts_with($photo, 'http') ? $photo : (str_starts_with($photo, 'uploads/') ? asset($photo) : asset('storage/'.$photo)) }}" alt="Admin" class="w-14 h-14 rounded-full object-cover border border-blue-200" />
                @else
                    <div class="w-14 h-14 stat-gradient rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(optional($admin)->name ?? 'A', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-[#4A72FF]">{{ $title ?? 'Export Admin' }}</h1>
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
                        <tr class="hover:bg-gray-50">
                            @foreach($row as $cell)
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-xs text-gray-500">
            Catatan: Gunakan tombol Print untuk menyimpan sebagai PDF.
        </div>
    </div>
</body>
</html>
