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
        Schema::table('payment_applications', function (Blueprint $table) {
            $table->decimal('amount_to_receive', 15, 2)->nullable()->after('amount_requested');
            $table->decimal('platform_fee', 15, 2)->nullable()->after('amount_to_receive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_applications', function (Blueprint $table) {
            $table->dropColumn(['amount_to_receive', 'platform_fee']);
        });
    }
};