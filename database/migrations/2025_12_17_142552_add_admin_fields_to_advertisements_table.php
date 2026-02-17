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
    Schema::table('advertisements', function (Blueprint $table) {
        $table->timestamp('approved_at')->nullable();
        $table->unsignedBigInteger('approved_by')->nullable();
        $table->timestamp('rejected_at')->nullable();
        $table->unsignedBigInteger('rejected_by')->nullable();
        $table->text('rejection_reason')->nullable();
    });

    Schema::table('users_record', function (Blueprint $table) {
        $table->boolean('is_admin')->default(false);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            //
        });
    }
};
