<?php

namespace App\Services;

use App\Models\UserRecord;
use App\Models\Follow;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Notify all followers of a new post or story.
     *
     * @param UserRecord $author      The user who created the content
     * @param string     $type        'post' | 'story'
     * @param int        $contentId   ID of the post or story
     */
    public static function notifyFollowers(UserRecord $author, string $type, int $contentId): void
    {
        $recipientIds = Follow::where('receiver_id', $author->id)
            ->pluck('sender_id')
            ->unique()
            ->toArray();

        if (empty($recipientIds)) return;

        $authorName = $author->name ?? $author->username ?? 'Someone';
        $message    = $type === 'post'
            ? "{$authorName} shared a new post"
            : "{$authorName} shared a new story";

        $pushData = [
            'type'      => $type,
            'post_id'   => $contentId,
            'author_id' => $author->id,
            'actor_id'  => $author->id,
        ];

        $now  = now();
        $rows = [];
        foreach ($recipientIds as $receiverId) {
            $rows[] = [
                'user_id'                  => $author->id,
                'notification_reciever_id' => (string) $receiverId,
                'type'                     => $type,
                'message'                  => $message,
                'notifiable_type'          => 'App\\Models\\UserRecord',
                'notifiable_id'            => $receiverId,
                'data'                     => json_encode($pushData),
                'read_notification'        => 'no',
                'read_at'                  => null,
                'created_at'               => $now,
                'updated_at'               => $now,
            ];
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('notifications')->insert($chunk);
        }

        // Push — uses the centralised PushNotificationService
        PushNotificationService::sendToUsers(
            $recipientIds,
            $authorName,
            $message,
            $pushData,
            'default'
        );
    }
}
