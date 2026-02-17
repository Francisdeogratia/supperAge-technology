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
    Schema::table('wallet_transactions', function (Blueprint $table) {
        $table->string('currency', 10)->default('NGN')->after('amount');
    });
}

public function down()
{
    Schema::table('wallet_transactions', function (Blueprint $table) {
        $table->dropColumn('currency');
    });
}

};
