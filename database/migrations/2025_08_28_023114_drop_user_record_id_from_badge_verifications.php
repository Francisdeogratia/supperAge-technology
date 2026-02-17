<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('badge_verifications', function (Illuminate\Database\Schema\Blueprint $table) {
        if (Schema::hasColumn('badge_verifications', 'user_record_id')) {
            $table->dropForeign(['user_record_id']);
            $table->dropColumn('user_record_id');
        }
    });
}



// In public function down()
// In public function down()
public function down(): void
{
    Schema::table('badge_verifications', function (Blueprint $table) {
        
        // CRITICAL CHECK: Only add the column if it doesn't already exist
        if (!Schema::hasColumn('badge_verifications', 'user_record_id')) {
             // Use foreignId() as it correctly handles the BIGINT and index
            $table->foreignId('user_record_id')
                  ->constrained('users_record') // Assumes the primary key is 'id'
                  ->onDelete('cascade');
        }
    });
}

};
