<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('journals', 'admin_checks')) {
            Schema::table('journals', function (Blueprint $table) {
                $table->unsignedTinyInteger('admin_checks')->default(0);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('journals', 'admin_checks')) {
            Schema::table('journals', function (Blueprint $table) {
                $table->dropColumn('admin_checks');
            });
        }
    }
};
