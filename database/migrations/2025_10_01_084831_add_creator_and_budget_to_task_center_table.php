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
    Schema::table('task_center', function (Blueprint $table) {
        $table->unsignedBigInteger('creator_id')->nullable()->after('specialcode');
        $table->decimal('total_budget', 12, 2)->default(0)->after('price');
    });
}

public function down()
{
    Schema::table('task_center', function (Blueprint $table) {
        $table->dropColumn('creator_id');
        $table->dropColumn('total_budget');
    });
}

};
