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
    Schema::table('users_record', function (Blueprint $table) {
        $table->text('bio')->nullable()->after('bgimg');
        $table->unsignedBigInteger('number_followers')->default(0)->after('bio');
    });
}

public function down()
{
    Schema::table('users_record', function (Blueprint $table) {
        $table->dropColumn(['bio', 'number_followers']);
    });
}

};
