<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('compliance_notifications', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('message');
      $table->string('type');
      $table->string('priority');
      $table->string('status');
      $table->foreignId('user_id')->constrained();
      $table->dateTime('read_at')->nullable();
      $table->text('action_required')->nullable();
      $table->date('due_date')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('compliance_notifications');
  }
};
