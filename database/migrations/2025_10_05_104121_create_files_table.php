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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nama asli file, misal: "laporan.pdf"
            $table->string('path')->unique(); // Path lengkap di storage, misal: "user_files/1/laporan.pdf"
            $table->unsignedBigInteger('size'); // Ukuran dalam bytes
            $table->string('mime_type'); // Tipe file, misal: "application/pdf"
            $table->boolean('is_starred')->default(false); // Untuk fitur "Berbintang"
            $table->timestamps();
            $table->softDeletes(); // Kolom untuk fitur "Sampah" (Recycle Bin)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
