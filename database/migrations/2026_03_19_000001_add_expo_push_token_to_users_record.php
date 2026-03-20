<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddExpoPushTokenToUsersRecord extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE `users_record`
            ADD COLUMN IF NOT EXISTS `expo_push_token` VARCHAR(255) NULL AFTER `specialcode`
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE `users_record`
            DROP COLUMN IF EXISTS `expo_push_token`
        ");
    }
}
