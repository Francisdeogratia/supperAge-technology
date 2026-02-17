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
    Schema::table('post_shares', function (Blueprint $table) {
        $table->unsignedBigInteger('recipient_id')->nullable()->after('user_id');
        $table->text('message')->nullable()->after('platform');
    });
}

public function down()
{
    Schema::table('post_shares', function (Blueprint $table) {
        $table->dropColumn(['recipient_id', 'message']);
    });
}
};
