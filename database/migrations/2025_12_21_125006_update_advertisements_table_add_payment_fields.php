<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            // Add daily budget column
            $table->decimal('daily_budget', 10, 2)->nullable()->after('budget');
            
            // Add payment fields
            $table->string('payment_status')->default('pending')->after('status');
            $table->string('payment_reference')->nullable()->after('payment_status');
            $table->string('flutterwave_tx_ref')->nullable()->after('payment_reference');
            $table->timestamp('paid_at')->nullable()->after('flutterwave_tx_ref');
            
            // Add new pricing columns
            $table->decimal('cost_per_action', 10, 2)->default(400.00)->after('paid_at');
            $table->decimal('cost_per_mille', 10, 2)->default(2500.00)->after('cost_per_action');
        });
        
        // Update default values for existing columns
        DB::statement('ALTER TABLE advertisements MODIFY cost_per_click DECIMAL(10,2) DEFAULT 50.00');
        DB::statement('ALTER TABLE advertisements MODIFY cost_per_impression DECIMAL(10,4) DEFAULT 2.50');
    }

    public function down()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn([
                'daily_budget',
                'payment_status',
                'payment_reference',
                'flutterwave_tx_ref',
                'paid_at',
                'cost_per_action',
                'cost_per_mille'
            ]);
        });
        
        // Restore old defaults
        DB::statement('ALTER TABLE advertisements MODIFY cost_per_click DECIMAL(10,2) DEFAULT 40.00');
        DB::statement('ALTER TABLE advertisements MODIFY cost_per_impression DECIMAL(10,4) DEFAULT 1.00');
    }
};