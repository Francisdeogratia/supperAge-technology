<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users_record', function (Blueprint $table) {
            $table->timestamp('disabled_until')->nullable()->after('updated_at');
            $table->integer('disabled_days')->nullable()->after('disabled_until');
        });
    }

    public function down(): void
    {
        Schema::table('users_record', function (Blueprint $table) {
            $table->dropColumn(['disabled_until', 'disabled_days']);
        });
    }
};
