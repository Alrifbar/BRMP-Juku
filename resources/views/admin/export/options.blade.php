<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Options - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Poppins', sans-serif; }
        .executive-card { 
            border: 1px solid rgba(226,232,240,.6); 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.05), 0 2px 4px -1px rgba(0,0,0,.03);
            transition: all 0.3s ease;
        }
        .executive-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(74, 114, 255, 0.1), 0 10px 10px -5px rgba(74, 114, 255, 0.04);
            border-color: #4A72FF;
        }
    </style>
</head>
<body class="bg-[#EEF2FF] dark:bg-gray-900 min-h-screen">
    @include('layouts.admin_navbar')

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 pb-24 md:pb-8 text-sm md:text-base fade-in">
        <div class="mb-8">
            <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Export Data</h2>
                        <p class="text-gray-600 dark:text-gray-300">Pilih jenis data dan format yang ingin diexport</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="stat-gradient text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <!-- Export Statistik -->
                <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="stat-gradient p-3 rounded-lg mr-3">
                            <i class="fas fa-chart-bar text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Export Statistik</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">Harian (7 hari)</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan aktivitas harian</p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.stats.export') }}?period=daily&format=excel" class="flex-1 sm:flex-none !bg-green-500 text-white px-3 py-2 rounded-lg hover:!bg-green-600 text-xs font-bold flex items-center justify-center transition-all shadow-md">
                                    <i class="fas fa-file-excel mr-1"></i>Excel (.xlsx)
                                </a>
                                <a href="{{ route('admin.stats.export') }}?period=daily&format=pdf" class="flex-1 sm:flex-none !bg-red-500 text-white px-3 py-2 rounded-lg hover:!bg-red-600 text-xs font-bold flex items-center justify-center transition-all shadow-md">
                                    <i class="fas fa-file-pdf mr-1"></i>PDF
                                </a>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-4 gap-3">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">Mingguan (12 minggu)</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Performa mingguan</p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.stats.export') }}?period=weekly&format=excel" class="flex-1 sm:flex-none !bg-green-500 text-white px-3 py-2 rounded-lg hover:!bg-green-600 text-xs font-bold flex items-center justify-center transition-all shadow-md">
                                    <i class="fas fa-file-excel mr-1"></i>Excel (.xlsx)
                                </a>
                                <a href="{{ route('admin.stats.export') }}?period=weekly&format=pdf" class="flex-1 sm:flex-none !bg-red-500 text-white px-3 py-2 rounded-lg hover:!bg-red-600 text-xs font-bold flex items-center justify-center transition-all shadow-md">
                                    <i class="fas fa-file-pdf mr-1"></i>PDF
                                </a>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-4 gap-3">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">Bulanan (24 bulan)</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan per bulan</p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.stats.export') }}?period=monthly&format=excel" class="flex-1 sm:flex-none !bg-green-500 text-white px-3 py-2 rounded-lg hover:!bg-green-600 text-xs font-bold flex items-center justify-center transition-all shadow-md">
                                    <i class="fas fa-file-excel mr-1"></i>Excel (.xlsx)
                                </a>
                                <a href="{{ route('admin.stats.export') }}?period=monthly&format=pdf" class="flex-1 sm:flex-none !bg-red-500 text-white px-3 py-2 rounded-lg hover:!bg-red-600 text-xs font-bold flex items-center justify-center transition-all shadow-md">
                                    <i class="fas fa-file-pdf mr-1"></i>PDF
                                </a>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-4 gap-3">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">Tahunan (Semua)</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Performa per tahun</p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.stats.export') }}?period=yearly&format=excel" class="flex-1 sm:flex-none !bg-green-500 text-white px-3 py-2 rounded-lg hover:!bg-green-600 text-xs font-bold flex items-center justify-center transition-all shadow-md">
                                    <i class="fas fa-file-excel mr-1"></i>Excel (.xlsx)
                                </a>
                                <a href="{{ route('admin.stats.export') }}?period=yearly&format=pdf" class="flex-1 sm:flex-none !bg-red-500 text-white px-3 py-2 rounded-lg hover:!bg-red-600 text-xs font-bold flex items-center justify-center transition-all shadow-md">
                                    <i class="fas fa-file-pdf mr-1"></i>PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Export Dokumen Rinci -->
                <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="stat-gradient p-3 rounded-lg mr-3">
                            <i class="fas fa-file-pdf text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Export Dokumen Rinci</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Laporan jurnal seluruh pegawai (PDF/Excel .xlsx/SQL) dengan kolom: No Jurnal, Waktu Dibuat, Nama Pegawai, Uraian Pekerjaan, Dokumen, Status.</p>
                    <form action="{{ route('admin.export.journals-detailed') }}" method="GET" class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400 mb-1 block">Periode</label>
                            <select name="period" class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 w-full">
                                <option value="day">1 Hari</option>
                                <option value="week">1 Minggu</option>
                                <option value="month">1 Bulan</option>
                                <option value="year">1 Tahun</option>
                                <option value="all">Semua Periode</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400 mb-1 block">Format</label>
                            <select name="format" class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 w-full">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="sql">SQL</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <button type="submit" class="w-full rounded-xl py-3 flex items-center justify-center space-x-3 !bg-blue-600 hover:!bg-blue-700 text-white transition-all group shadow-md">
                                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="fas fa-file-export"></i>
                                </div>
                                <span class="font-bold">Export Dokumen Rinci</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <!-- Backup & Restore -->
                <div class="executive-card bg-white dark:bg-gray-800 rounded-2xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="stat-gradient p-3 rounded-lg mr-3">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Backup & Retention</h3>
                    </div>
                    <div class="space-y-6">
                        <form action="{{ route('admin.export.backup') }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-sm text-gray-600 dark:text-gray-400 mb-1 block">Jangka Waktu</label>
                                    <select name="period" class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 w-full">
                                        <option value="hour">1 Jam</option>
                                        <option value="day">1 Hari</option>
                                        <option value="week">1 Minggu</option>
                                        <option value="month">1 Bulan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600 dark:text-gray-400 mb-1 block">Format</label>
                                    <select name="format" class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 w-full">
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel (.xlsx)</option>
                                        <option value="sql">SQL (Lengkap)</option>
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-sm text-gray-600 dark:text-gray-400 mb-1 block font-semibold">Path Penyimpanan</label>
                                    <input type="text" name="path" placeholder="Contoh: C:\Backup\JurnalBRMP" class="border-2 border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-xl px-4 py-3 w-full focus:border-green-400 outline-none transition-all">
                                </div>
                            </div>
                            <button type="submit" class="w-full rounded-xl py-3 flex items-center justify-center space-x-3 !bg-green-600 hover:!bg-green-700 text-white transition-all group shadow-md">
                                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="fas fa-download"></i>
                                </div>
                                <span class="font-bold">Buat Backup</span>
                            </button>
                        </form>

                        <form action="{{ route('admin.storage.cleanup') }}" method="POST" class="!bg-yellow-100 border-2 border-yellow-400 rounded-2xl p-6 transition-all hover:shadow-lg">
                            @csrf
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                                <div>
                                    <p class="font-bold !text-yellow-900 text-base mb-1">Retention: Maksimal simpan dokumen 3 bulan.</p>
                                    <p class="text-xs !text-yellow-800 font-medium">Klik untuk menghapus dokumen gambar jurnal (public/storage/journal-documents) yang lebih lama dari 3 bulan.</p>
                                </div>
                                <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-yellow-600 text-white font-bold hover:bg-yellow-700 shadow-lg shadow-yellow-600/20 transition-all active:scale-95 whitespace-nowrap">Jalankan Cleanup</button>
                            </div>
                        </form>

                        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100 flex items-center">
                                <i class="fas fa-history mr-2 text-red-500"></i>Restore Data (SQL Only)
                            </h4>
                            <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 p-4 rounded-xl mb-4">
                                <p class="text-xs text-red-600 dark:text-red-400 font-bold uppercase tracking-wider mb-1">Peringatan Bahaya</p>
                                <p class="text-sm text-red-500 dark:text-red-400">Data saat ini akan sepenuhnya ditimpa oleh file backup!</p>
                            </div>
                            <form action="{{ route('admin.export.restore') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <input type="file" name="sql_file" accept=".sql,.txt" class="border-2 border-dashed border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-xl px-4 py-3 w-full cursor-pointer hover:border-red-400 transition-all">
                                <button type="submit" class="w-full bg-red-600 text-white font-bold rounded-xl py-4 hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all active:scale-95">Restore dari SQL</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const periodSel = document.querySelector('form[action*="export/journals-detailed"] select[name="period"]');
            const formatSel = document.querySelector('form[action*="export/journals-detailed"] select[name="format"]');
            if (periodSel && formatSel) {
                const key = 'exportDetailed';
                try {
                    const p = localStorage.getItem(key + '.period');
                    const f = localStorage.getItem(key + '.format');
                    if (p) periodSel.value = p;
                    if (f) formatSel.value = f;
                } catch (e) {}
                periodSel.addEventListener('change', () => {
                    try { localStorage.setItem(key + '.period', periodSel.value); } catch(e){}
                });
                formatSel.addEventListener('change', () => {
                    try { localStorage.setItem(key + '.format', formatSel.value); } catch(e){}
                });
            }
        });
    </script>
</body>
</html>
