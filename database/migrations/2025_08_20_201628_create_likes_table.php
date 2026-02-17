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
    Schema::create('likes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('tale_id');
        $table->string('username');
        $table->timestamps();

        // Optional: add foreign key if tales_extens uses tales_id
        $table->foreign('tale_id')->references('tales_id')->on('tales_extens')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
