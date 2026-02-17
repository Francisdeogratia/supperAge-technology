<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sample_posts', function (Blueprint $table) {
            $table->json('link_preview')->nullable()->after('bgnd_color');
        });
    }

    public function down()
    {
        Schema::table('sample_posts', function (Blueprint $table) {
            $table->dropColumn('link_preview');
        });
    }
};