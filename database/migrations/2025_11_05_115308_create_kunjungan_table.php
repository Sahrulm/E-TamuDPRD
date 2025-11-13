<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('kunjungan', function (Blueprint $table) {
      $table->id();

      // relasi
      $table->foreignId('tamu_id')->constrained('tamu')->cascadeOnDelete();
      $table->foreignId('kategori_pihak_id')->constrained('kategori_pihak'); // subkategori yang dipilih
      $table->foreignId('host_id')->nullable()->constrained('users')->nullOnDelete();

      // data kunjungan
      $table->unsignedInteger('jumlah_peserta');
      $table->text('keperluan');
      $table->date('tanggal_kunjungan');
      $table->time('waktu_kunjungan');

      // Dokumen
      $table->string('dokumen');

      // status
      $table->enum('status_sekarang', ['menunggu','diterima','ditolak','selesai'])->default('menunggu');

      $table->timestamps();

      $table->index(['tanggal_kunjungan','waktu_kunjungan']);
      $table->index('status_sekarang');
      $table->index('host_id');
      $table->index('kategori_pihak_id');
    });
  }
  public function down(): void { Schema::dropIfExists('kunjungan'); }
};