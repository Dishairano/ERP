<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('supplier_evaluations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->integer('delivery_time_rating')->nullable();
      $table->integer('quality_rating')->nullable();
      $table->integer('communication_rating')->nullable();
      $table->integer('price_rating')->nullable();
      $table->decimal('overall_rating', 3, 2);
      $table->text('comments')->nullable();
      $table->string('order_reference')->nullable();
      $table->date('evaluation_date');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('supplier_evaluations');
  }
};
