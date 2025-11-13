<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('full_name', 150);
      $table->string('email', 150)->unique();
      $table->string('password');
      $table->enum('role', ['admin','host','resepsionis']);
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->index('role');
    });

    Schema::create('sessions', function (Blueprint $table) {
      $table->string('id')->primary();
      $table->foreignId('user_id')->nullable()->index();
      $table->string('ip_address', 45)->nullable();
      $table->text('user_agent')->nullable();
      $table->longText('payload');
      $table->integer('last_activity')->index();
      });
  }
  public function down(): void { Schema::dropIfExists('users'); }
};
