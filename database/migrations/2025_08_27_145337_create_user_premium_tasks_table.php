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
    Schema::create('user_premium_tasks', function (Blueprint $table) {
    $table->id();

    // Fix: point to your actual user table
    $table->foreignId('user_id')
          ->constrained('users_record') // 👈 must match your UserRecord model
          ->onDelete('cascade');

    $table->foreignId('premium_task_id')
          ->constrained('premium_tasks')
          ->onDelete('cascade');

    $table->boolean('completed')->default(false);
    $table->timestamps();
});

}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_premium_tasks');
    }
};
