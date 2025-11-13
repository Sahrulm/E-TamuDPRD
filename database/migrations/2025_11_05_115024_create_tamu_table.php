<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('tamu', function (Blueprint $table) {
      $table->id();
      $table->string('nama', 150);
      $table->string('email', 150);
      $table->string('no_hp', 30);
      $table->enum('instansi_kategori', ['opd','lembaga','perseorangan','ormas']);
      $table->string('instansi_nama', 200)->nullable();
      $table->timestamps();

      $table->index('email');
      $table->index('no_hp');
      $table->index('instansi_kategori');
    });
  }
  public function down(): void { Schema::dropIfExists('tamu'); }
};
