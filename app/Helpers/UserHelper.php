<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class UserHelper
{
    // public static function countUnseenMessages(int $userId, string $type): int
    // {
    //     $status = 1;

    //     if ($type === 'message') {
    //         return DB::table('chat_messages')
    //                  ->where('to_user_id', $userId)
    //                  ->where('chat_status', $status)
    //                  ->count();
    //     }

    //     if ($type === 'file') {
    //         return DB::table('chat_media_dir')
    //                  ->where('to_user_id', $userId)
    //                  ->where('chat_file_status', $status)
    //                  ->count();
    //     }

    //     return 0; // fallback if type is unknown

    // }


    public static function countNotifications(string $receiverId): int
{
    return DB::table('notifications') // ✅ match your migration
        ->where('notification_reciever_id', $receiverId)
        ->where('read_notification', 'no')
        ->count();
}

}
