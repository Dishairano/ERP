<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public function up()
  {
    if (Schema::hasColumn('settings', 'category')) {
      // Copy data from category to group
      DB::statement('UPDATE settings SET `group` = category WHERE category IS NOT NULL');

      // Drop the category column
      Schema::table('settings', function (Blueprint $table) {
        $table->dropColumn('category');
      });
    }
  }

  public function down()
  {
    if (!Schema::hasColumn('settings', 'category')) {
      Schema::table('settings', function (Blueprint $table) {
        $table->string('category')->nullable()->after('value');
      });

      // Copy data back from group to category
      DB::statement('UPDATE settings SET category = `group` WHERE `group` IS NOT NULL');
    }
  }
};
