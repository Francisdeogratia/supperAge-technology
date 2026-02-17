<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration for admin_messages table
// Create file: database/migrations/2026_01_14_create_admin_messages_table.php

class CreateAdminMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('admin_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('admin_id');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users_record')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_messages');
    }
}