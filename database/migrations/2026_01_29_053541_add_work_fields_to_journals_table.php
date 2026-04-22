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
            $table->date('tanggal_pekerjaan')->after('content');
            $table->text('uraian_pekerjaan')->after('tanggal_pekerjaan');
            $table->string('dokumen_pekerjaan')->nullable()->after('uraian_pekerjaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn(['tanggal_pekerjaan', 'uraian_pekerjaan', 'dokumen_pekerjaan']);
        });
    }
};
