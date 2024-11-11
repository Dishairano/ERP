<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('supplier_notifications', function (Blueprint $table) {
      $table->id();
      $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
      $table->string('type');
      $table->string('title');
      $table->text('message');
      $table->json('data')->nullable();
      $table->timestamp('read_at')->nullable();
      $table->string('priority');
      $table->timestamps();

      $table->index(['supplier_id', 'type']);
      $table->index(['supplier_id', 'read_at']);
      $table->index(['type', 'priority']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('supplier_notifications');
  }
};
