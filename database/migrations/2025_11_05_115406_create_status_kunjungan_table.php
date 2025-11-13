<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('status_kunjungan', function (Blueprint $table) {
      $table->id();
      $table->foreignId('kunjungan_id')->constrained('kunjungan')->cascadeOnDelete();
      $table->enum('old_status', ['menunggu','diterima','ditolak','dibatalkan']);
      $table->enum('new_status', ['menunggu','diterima','ditolak','dibatalkan']);
      $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
      $table->text('note')->nullable();
      $table->timestamp('changed_at')->useCurrent();

      $table->index(['kunjungan_id','changed_at']);
    });
  }
  public function down(): void { Schema::dropIfExists('status_kunjungan'); }
};