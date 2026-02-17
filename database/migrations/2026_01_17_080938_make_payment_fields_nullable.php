<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payment_applications', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->change();
            $table->string('account_number')->nullable()->change();
            $table->string('account_name')->nullable()->change();
            $table->string('paypal_email')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('payment_applications', function (Blueprint $table) {
            $table->string('bank_name')->nullable(false)->change();
            $table->string('account_number')->nullable(false)->change();
            $table->string('account_name')->nullable(false)->change();
            $table->string('paypal_email')->nullable(false)->change();
        });
    }
};