<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In the migration file:
public function up()
{
    Schema::create('call_signals', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('call_id');
        $table->unsignedBigInteger('user_id');
        $table->text('signal_data');
        $table->timestamp('created_at');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_signals');
    }
};
