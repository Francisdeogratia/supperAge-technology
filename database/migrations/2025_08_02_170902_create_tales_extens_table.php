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
    Schema::create('tales_extens', function (Blueprint $table) {
        $table->id('tales_id'); // auto-increment primary key
        $table->string('specialcode', 19);
        $table->string('tales_content', 250);
        $table->timestamp('tales_datetime')->useCurrent()->useCurrentOnUpdate();
        $table->string('tales_types', 50);
        $table->string('username', 50);
        $table->string('files_talesexten', 500)->nullable();
        $table->string('text_color', 13);
        $table->string('bgnd_color', 13);
        $table->string('type', 11);
        $table->timestamps(6); // created_at and updated_at with microseconds
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tales_extens');
    }
};
