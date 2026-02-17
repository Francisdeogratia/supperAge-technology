<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('calls', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('calls', 'answered_at')) {
                $table->timestamp('answered_at')->nullable()->after('started_at');
            }
            if (!Schema::hasColumn('calls', 'ended_at')) {
                $table->timestamp('ended_at')->nullable()->after('answered_at');
            }
            if (!Schema::hasColumn('calls', 'duration')) {
                $table->integer('duration')->nullable()->after('ended_at')->comment('Duration in seconds');
            }
        });
    }

    public function down()
    {
        Schema::table('calls', function (Blueprint $table) {
            $table->dropColumn(['answered_at', 'ended_at', 'duration']);
        });
    }
};