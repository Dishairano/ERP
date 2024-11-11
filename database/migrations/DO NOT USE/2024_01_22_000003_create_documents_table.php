<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('documents', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
      $table->string('title');
      $table->text('description')->nullable();
      $table->string('file_path');
      $table->string('file_type');
      $table->bigInteger('file_size');
      $table->float('version')->default(1.0);
      $table->enum('status', ['draft', 'review', 'approved', 'archived']);
      $table->foreignId('uploaded_by')->constrained('users');
      $table->foreignId('last_modified_by')->constrained('users');
      $table->string('category');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('documents');
  }
};
