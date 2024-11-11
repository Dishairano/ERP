<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('resource_costs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('resource_id')->constrained()->onDelete('cascade');
      $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
      $table->string('cost_type'); // operational, maintenance, rental, personnel
      $table->decimal('amount', 10, 2);
      $table->string('currency', 3)->default('EUR');
      $table->date('date');
      $table->text('description')->nullable();
      $table->string('status'); // planned, actual, projected
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('resource_costs');
  }
};
