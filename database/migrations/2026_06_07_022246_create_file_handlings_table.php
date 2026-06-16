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
    Schema::create('file_handlings', function (Blueprint $table) {
      $table->id();
      $table->string('file');
      $table->foreignUuid('user_id')->nullable()->constrained()->cascadeOnDelete();
      $table->string('private_url');
      $table->string('public_url');
      $table->text('description')->nullable();
      $table->string('password')->nullable();
      $table->dateTime('expiration')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('file_handlings');
  }
};
