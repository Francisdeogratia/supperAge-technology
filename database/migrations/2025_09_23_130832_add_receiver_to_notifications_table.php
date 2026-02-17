<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('notification_reciever_id')->after('id');
            $table->enum('read_notification', ['yes', 'no'])->default('no')->after('notification_reciever_id');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['notification_reciever_id', 'read_notification']);
        });
    }
};
