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
    Schema::create('task_center', function (Blueprint $table) {
        $table->id();
        $table->string('specialcode');
        $table->string('task_type'); // e.g. follow, promote, visit
        $table->text('task_content'); // post, url, story, etc
        $table->decimal('price', 8, 2);
        $table->string('currency')->default('NGN');
        $table->string('target')->nullable(); // countries, gender, age
        $table->integer('duration'); // in days
        $table->integer('max_participants')->nullable(); // optional
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_center');
    }
};
