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
       Schema::create('post_engagements', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id');
    $table->foreignId('task_id');
    $table->boolean('liked')->default(false);
    $table->boolean('commented')->default(false);
    $table->boolean('shared')->default(false);
    $table->boolean('is_completed')->default(false);
    $table->unsignedBigInteger('shared_to')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_engagements');
    }
};
