<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDueDateToProjectTasksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('project_tasks', function (Blueprint $table) {
      $table->dateTime('due_date')->nullable()->after('start_date');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('project_tasks', function (Blueprint $table) {
      $table->dropColumn('due_date');
    });
  }
}
