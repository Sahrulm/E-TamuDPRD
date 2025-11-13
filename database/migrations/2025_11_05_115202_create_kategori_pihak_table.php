<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('kategori_pihak', function (Blueprint $table) {
      $table->id();
      $table->enum('kategori', ['pimpinan','akd','sekretariat']); // indu
      $table->string('sub_kategori', 150);  // ex: Ketua DPRD, Wakil Ketua 1, Komisi 1, Bagian Keuangan, ...
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('kategori_pihak'); }
};