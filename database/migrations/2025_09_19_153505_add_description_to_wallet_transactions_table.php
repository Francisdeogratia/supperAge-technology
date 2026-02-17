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
        if (!Schema::hasColumn('wallet_transactions', 'description')) {
            $table->string('description')->nullable()->after('status');
        }
    });
}

public function down()
{
    Schema::table('wallet_transactions', function (Blueprint $table) {
        if (Schema::hasColumn('wallet_transactions', 'description')) {
            $table->dropColumn('description');
        }
    });
}

};
