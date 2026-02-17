<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // In the migration file for the `messages` table (if that's where you track the thread)
Schema::table('messages', function (Blueprint $table) {
    // We assume this is the table that tracks the conversation, and the ID points to another message ID.
    $table->foreignId('pinned_message_id')->nullable()->after('updated_at')->constrained('messages')->onDelete('set null');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
           // Drop the foreign key constraint and then the column
            $table->dropConstrainedForeignId('pinned_message_id');
        });
    }
};
