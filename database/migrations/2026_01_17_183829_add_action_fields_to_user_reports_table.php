<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->enum('action_taken', ['none', 'warned', 'suspended', 'deleted'])->nullable()->after('status');
            $table->text('admin_note')->nullable()->after('action_taken');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('admin_note');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            
            // Update status enum to include 'dismissed'
            DB::statement("ALTER TABLE user_reports MODIFY COLUMN status ENUM('pending', 'reviewed', 'action_taken', 'dismissed') DEFAULT 'pending'");
            
            // Add foreign key for reviewer
            $table->foreign('reviewed_by')->references('id')->on('users_record')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['action_taken', 'admin_note', 'reviewed_by', 'reviewed_at']);
            
            // Revert status enum
            DB::statement("ALTER TABLE user_reports MODIFY COLUMN status ENUM('pending', 'reviewed', 'action_taken') DEFAULT 'pending'");
        });
    }
};