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
        Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tale_id');
    $table->string('username');
    $table->text('comment');
    $table->timestamps();

    $table->foreign('tale_id')->references('tales_id')->on('tales_extens')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
