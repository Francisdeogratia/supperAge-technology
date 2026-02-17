<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'file_path')) {
                $table->text('file_path')->nullable();
            }
            if (!Schema::hasColumn('messages', 'reply_to_id')) {
                $table->unsignedBigInteger('reply_to_id')->nullable();
            }
            if (!Schema::hasColumn('messages', 'reactions')) {
                $table->text('reactions')->nullable();
            }
            if (!Schema::hasColumn('messages', 'is_edited')) {
                $table->boolean('is_edited')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'reply_to_id', 'reactions', 'is_edited']);
        });
    }
};