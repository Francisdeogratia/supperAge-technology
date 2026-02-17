<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('calls', function (Blueprint $table) {
            $table->string('status', 20)->change();
        });
    }

    public function down()
    {
        Schema::table('calls', function (Blueprint $table) {
            $table->enum('status', ['ringing', 'active', 'ended', 'declined'])->change();
        });
    }
};