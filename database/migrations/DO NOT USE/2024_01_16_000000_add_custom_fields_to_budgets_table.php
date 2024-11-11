<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::table('budgets', function (Blueprint $table) {
      // Add custom input fields that are nullable
      $table->string('custom_department')->nullable()->after('department_id');
      $table->string('custom_cost_category')->nullable()->after('cost_category_id');
      $table->string('custom_project')->nullable()->after('project_id');
    });
  }

  public function down()
  {
    Schema::table('budgets', function (Blueprint $table) {
      // Remove custom fields
      $table->dropColumn('custom_department');
      $table->dropColumn('custom_cost_category');
      $table->dropColumn('custom_project');
    });
  }
};
