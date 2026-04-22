<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            // $table->string('no')->nullable(); // sudah ada
            // $table->date('tanggal')->nullable(); // sudah ada
            // $table->text('uraian_pekerjaan')->nullable(); // sudah ada
            // $table->string('dokumen_pekerjaan')->nullable(); // sudah ada
            $table->string('penilai_kasubang')->nullable();
            $table->string('penilai_tu')->nullable();
            $table->string('penilai_katimker')->nullable();
            $table->enum('jenis_katimker', ['program', 'evaluasi', 'pemanfaatan'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn([
                // 'no', // sudah ada
                // 'tanggal', // sudah ada
                // 'uraian_pekerjaan', // sudah ada
                // 'dokumen_pekerjaan', // sudah ada
                'penilai_kasubang',
                'penilai_tu',
                'penilai_katimker',
                'jenis_katimker'
            ]);
        });
    }
};
