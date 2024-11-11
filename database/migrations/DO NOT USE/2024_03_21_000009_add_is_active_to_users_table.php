<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->boolean('is_active')->default(true)->after('remember_token');
    });

    // Set all existing users to active
    DB::table('users')->update(['is_active' => true]);
  }

  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('is_active');
    });
  }
};
