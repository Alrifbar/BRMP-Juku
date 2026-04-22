<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'theme')) {
                $table->string('theme', 10)->default('light')->after('provider');
            }
            if (!Schema::hasColumn('users', 'default_page')) {
                $table->string('default_page', 20)->nullable()->after('theme');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'default_page')) {
                $table->dropColumn('default_page');
            }
            if (Schema::hasColumn('users', 'theme')) {
                $table->dropColumn('theme');
            }
        });
    }
};
