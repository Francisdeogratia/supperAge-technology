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
        Schema::create('task_user', function (Blueprint $table) {
    $table->id();

    // Fix: must match your real table name exactly
    $table->foreignId('user_id')
          ->constrained('users_record') // 👈 not "user_record"
          ->onDelete('cascade');

    $table->foreignId('task_id')
          ->constrained('tasks')
          ->onDelete('cascade');

    $table->boolean('is_completed')->default(false);
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_user');
    }
};
