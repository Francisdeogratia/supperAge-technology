<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    private const EXPO_API = 'https://exp.host/--/api/v2/push/send';

    /**
     * Send a push notification to a single user by their user ID.
     */
    public static function sendToUser(
        int    $userId,
        string $title,
        string $body,
        array  $data   = [],
        string $channel = 'default'
    ): void {
        $token = DB::table('users_record')->where('id', $userId)->value('expo_push_token');
        if (!$token || !str_starts_with((string) $token, 'ExponentPushToken[')) return;

        self::dispatch([
            [
                'to'           => $token,
                'title'        => $title,
                'body'         => $body,
                'data'         => $data,
                'sound'        => 'default',
                'priority'     => 'high',
                'channelId'    => $channel,
                'badge'        => 1,
            ],
        ]);
    }

    /**
     * Send a push notification to multiple users by their user IDs.
     */
    public static function sendToUsers(
        array  $userIds,
        string $title,
        string $body,
        array  $data   = [],
        string $channel = 'default'
    ): void {
        if (empty($userIds)) return;

        $tokens = DB::table('users_record')
            ->whereIn('id', $userIds)
            ->whereNotNull('expo_push_token')
            ->pluck('expo_push_token')
            ->filter(fn($t) => str_starts_with((string) $t, 'ExponentPushToken['))
            ->values()
            ->toArray();

        if (empty($tokens)) return;

        $messages = array_map(fn($token) => [
            'to'        => $token,
            'title'     => $title,
            'body'      => $body,
            'data'      => $data,
            'sound'     => 'default',
            'priority'  => 'high',
            'channelId' => $channel,
            'badge'     => 1,
        ], $tokens);

        // Expo Push API accepts up to 100 messages per request
        foreach (array_chunk($messages, 100) as $batch) {
            self::dispatch($batch);
        }
    }

    // ── Internal HTTP sender ──────────────────────────────────────────────────
    private static function dispatch(array $messages): void
    {
        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => self::EXPO_API,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => json_encode($messages),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'Accept-Encoding: gzip, deflate',
                ],
            ]);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $e) {
            Log::warning('Push notification failed: ' . $e->getMessage());
        }
    }
}
