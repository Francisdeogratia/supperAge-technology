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
    Schema::table('tales_extens', function (Blueprint $table) {
        if (!Schema::hasColumn('tales_extens', 'views')) {
            $table->unsignedInteger('views')->default(0)->after('type');
        }
        if (!Schema::hasColumn('tales_extens', 'likes')) {
            $table->unsignedInteger('likes')->default(0)->after('views');
        }
        if (!Schema::hasColumn('tales_extens', 'shares')) {
            $table->unsignedInteger('shares')->default(0)->after('likes');
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tales_extens', function (Blueprint $table) {
        $table->dropColumn(['views', 'likes', 'shares']);
    });
    }
};
