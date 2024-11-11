<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('compliance_documents', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('document_type');
      $table->string('file_path');
      $table->date('expiry_date')->nullable();
      $table->string('status');
      $table->text('description')->nullable();
      $table->string('department');
      $table->string('owner');
      $table->text('tags')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('compliance_documents');
  }
};
