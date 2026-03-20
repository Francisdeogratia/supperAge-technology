<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_file_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedSmallInteger('file_index');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['post_id', 'file_index', 'user_id']);
            $table->index('post_id');
        });

        Schema::create('post_file_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedSmallInteger('file_index');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['post_id', 'file_index', 'user_id']);
            $table->index('post_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_file_views');
        Schema::dropIfExists('post_file_likes');
    }
};
