<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTaskIdForeignFromTaskUserTable extends Migration
{
    public function up()
    {
        Schema::table('task_user', function (Blueprint $table) {
            $table->dropForeign(['task_id']); // 👈 This removes the foreign key
        });
    }

    public function down()
    {
        Schema::table('task_user', function (Blueprint $table) {
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }
}
