<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionToMarketplaceStoresTable extends Migration
{
    public function up()
    {
        Schema::table('marketplace_stores', function (Blueprint $table) {
            $table->timestamp('subscription_started_at')->nullable()->after('status');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_started_at');
            $table->enum('subscription_status', ['active', 'expired', 'cancelled'])->default('active')->after('subscription_expires_at');
        });
    }

    public function down()
    {
        Schema::table('marketplace_stores', function (Blueprint $table) {
            $table->dropColumn(['subscription_started_at', 'subscription_expires_at', 'subscription_status']);
        });
    }
}