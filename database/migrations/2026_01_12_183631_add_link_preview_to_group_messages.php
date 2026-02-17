<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('group_messages', function (Blueprint $table) {
            $table->text('link_preview')->nullable()->after('message');
        });
    }

    public function down()
    {
        Schema::table('group_messages', function (Blueprint $table) {
            $table->dropColumn('link_preview');
        });
    }
};