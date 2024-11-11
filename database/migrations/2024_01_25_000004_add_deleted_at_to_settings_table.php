<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::table('settings', function (Blueprint $table) {
      if (!Schema::hasColumn('settings', 'deleted_at')) {
        $table->softDeletes();
      }
    });
  }

  public function down()
  {
    Schema::table('settings', function (Blueprint $table) {
      $table->dropSoftDeletes();
    });
  }
};
