<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public function up()
  {
    // First check if there are any foreign key constraints referencing performed_by
    $constraints = DB::select("
      SELECT CONSTRAINT_NAME
      FROM information_schema.KEY_COLUMN_USAGE
      WHERE REFERENCED_TABLE_NAME = 'activities'
      AND REFERENCED_COLUMN_NAME = 'performed_by'
      AND TABLE_SCHEMA = DATABASE()
    ");

    // Drop any foreign key constraints that reference performed_by
    foreach ($constraints as $constraint) {
      DB::statement("ALTER TABLE activities DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
    }

    // Now we can safely drop the old columns
    Schema::table('activities', function (Blueprint $table) {
      $table->dropColumn([
        'activitable_type',
        'activitable_id',
        'performed_by',
        'performed_at',
        'data'
      ]);
    });
  }

  public function down()
  {
    Schema::table('activities', function (Blueprint $table) {
      $table->string('activitable_type');
      $table->unsignedBigInteger('activitable_id');
      $table->foreignId('performed_by')->constrained('users');
      $table->timestamp('performed_at');
      $table->json('data')->nullable();
      $table->index(['activitable_type', 'activitable_id']);
    });

    // Copy data back from new columns to old ones
    DB::table('activities')->update([
      'performed_by' => DB::raw('user_id'),
      'data' => DB::raw('metadata'),
      'activitable_type' => DB::raw('subject_type'),
      'activitable_id' => DB::raw('subject_id')
    ]);
  }
};
