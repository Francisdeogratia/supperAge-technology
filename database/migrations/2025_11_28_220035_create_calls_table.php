<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In database/migrations/..._create_calls_table.php

public function up()
{
    Schema::create('calls', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('caller_id');
        $table->unsignedBigInteger('receiver_id');
        $table->enum('call_type', ['audio', 'video']);
        // In your calls table migration (if you need to add it)
        $table->enum('status', ['ringing', 'active', 'ended', 'declined', 'no_answer'])->default('ringing');
        $table->timestamp('started_at')->nullable();
        $table->timestamp('ended_at')->nullable();
        $table->timestamps();

        $table->foreign('caller_id')->references('id')->on('users_record')->onDelete('cascade');
        $table->foreign('receiver_id')->references('id')->on('users_record')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
