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
         Schema::table('users', function (Blueprint $table) {
            $table->string('role')->after('password')->default('karyawan');

            // Default 1 GB (1024 * 1024 * 1024 = 1073741824 bytes)
            $table->bigInteger('storage_quota')->after('role')->default(1073741824);
            $table->bigInteger('storage_used')->after('storage_quota')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'storage_quota', 'storage_used']);
        });
    }
};
