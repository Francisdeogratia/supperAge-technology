<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users_record', function (Blueprint $table) {
        $table->string('token')->nullable()->after('unsetacct');
        $table->timestamp('tokenExpire')->nullable()->after('token');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_record', function (Blueprint $table) {
            //
        });
    }
};
