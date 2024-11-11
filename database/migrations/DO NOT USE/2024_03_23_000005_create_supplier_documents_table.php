<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('supplier_documents', function (Blueprint $table) {
      $table->id();
      $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
      $table->string('title');
      $table->string('file_path');
      $table->string('document_type');
      $table->text('description')->nullable();
      $table->date('valid_until')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('supplier_documents');
  }
};
