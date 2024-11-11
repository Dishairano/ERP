<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevenueStreamsTable extends Migration
{
  public function up()
  {
    Schema::create('revenue_streams', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->decimal('expected_amount', 15, 2);
      $table->string('currency', 3)->default('EUR');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('revenue_streams');
  }
}
