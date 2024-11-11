<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('project_team', function (Blueprint $table) {
      $table->id();
      $table->foreignId('core_project_modal_id')
        ->constrained('projects', 'id')
        ->onDelete('cascade');
      $table->foreignId('user_id')
        ->constrained('users')
        ->onDelete('cascade');
      $table->string('role')->nullable();
      $table->timestamps();

      $table->unique(['core_project_modal_id', 'user_id']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('project_team');
  }
};
