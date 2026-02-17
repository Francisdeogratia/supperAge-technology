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
        Schema::table('badge_verifications', function (Blueprint $table) {
            // ✅ Only add if it doesn't already exist
            if (!Schema::hasColumn('badge_verifications', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');

                // Optional: Add foreign key only if `users` table exists
                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('badge_verifications', function (Blueprint $table) {
            // ✅ Only drop if it exists
            if (Schema::hasColumn('badge_verifications', 'user_id')) {
                // Drop FK first if it exists
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Foreign key might already be gone — skip
                }

                $table->dropColumn('user_id');
            }
        });
    }
};
