<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandPlanningTables extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('demand_forecasts', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('product_id');
      $table->date('forecast_date');
      $table->integer('forecast_quantity');
      $table->decimal('forecast_price', 10, 2);
      $table->timestamps();

      // Remove the foreign key constraint for now
      // $table->foreign('product_id')->references('id')->on('products');
    });

    Schema::create('demand_budgets', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('product_id');
      $table->date('budget_date');
      $table->integer('budget_quantity');
      $table->decimal('budget_price', 10, 2);
      $table->timestamps();

      // Remove the foreign key constraint for now
      // $table->foreign('product_id')->references('id')->on('products');
    });

    Schema::create('demand_notifications', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('product_id');
      $table->date('notification_date');
      $table->text('notification_message');
      $table->timestamps();

      // Remove the foreign key constraint for now
      // $table->foreign('product_id')->references('id')->on('products');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('demand_notifications');
    Schema::dropIfExists('demand_budgets');
    Schema::dropIfExists('demand_forecasts');
  }
}
