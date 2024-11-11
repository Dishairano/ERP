<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('suppliers', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('name');
      $table->string('contact_person')->nullable();
      $table->string('email')->nullable();
      $table->string('phone')->nullable();
      $table->text('address')->nullable();
      $table->string('tax_number')->nullable();
      $table->string('payment_terms')->nullable();
      $table->boolean('status')->default(true);
      $table->text('notes')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('suppliers');
  }
};
