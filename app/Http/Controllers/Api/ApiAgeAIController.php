<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ApiAgeAIController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $userId  = $request->user()->id;
        $message = $request->message;

        // Load recent history for context
        $history = DB::table('age_ai_messages')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->reverse()
            ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
            ->values()
            ->toArray();

        // Save user message
        DB::table('age_ai_messages')->insert([
            'user_id'    => $userId,
            'role'       => 'user',
            'content'    => $message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Build prompt
        $systemPrompt = 'You are AgeAI, a helpful AI assistant for SupperAge, a social-financial platform. Be friendly, concise, and helpful. Help users with social features, wallet, marketplace, events, live streaming, and general questions about the platform.';

        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $history,
            [['role' => 'user', 'content' => $message]]
        );

        // Try OpenAI-compatible API if configured
        $apiKey = config('services.openai.key');
        if ($apiKey) {
            try {
                $response = Http::withToken($apiKey)
                    ->timeout(30)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model'       => 'gpt-3.5-turbo',
                        'messages'    => $messages,
                        'max_tokens'  => 500,
                        'temperature' => 0.7,
                    ]);

                if ($response->successful()) {
                    $reply = $response->json('choices.0.message.content');
                    DB::table('age_ai_messages')->insert([
                        'user_id'    => $userId,
                        'role'       => 'assistant',
                        'content'    => $reply,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    return response()->json(['reply' => $reply]);
                }
            } catch (\Exception $e) {
                // Fall through to simple response
            }
        }

        // Simple rule-based fallback
        $reply = $this->simpleReply($message);

        DB::table('age_ai_messages')->insert([
            'user_id'    => $userId,
            'role'       => 'assistant',
            'content'    => $reply,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['reply' => $reply]);
    }

    private function simpleReply(string $message): string
    {
        $msg = strtolower($message);

        if (str_contains($msg, 'wallet') || str_contains($msg, 'money') || str_contains($msg, 'balance')) {
            return 'You can check your wallet balance, fund it, transfer to other users, or withdraw to your bank account from the Wallet tab.';
        }
        if (str_contains($msg, 'story') || str_contains($msg, 'tale')) {
            return 'You can create stories by tapping "Your Story" in the story bar at the top of your feed. Stories expire after 24 hours.';
        }
        if (str_contains($msg, 'post') || str_contains($msg, 'feed')) {
            return 'Create posts using the + Post button at the top of your feed, or the floating + button. You can add text, images, and colors.';
        }
        if (str_contains($msg, 'follow')) {
            return 'Find and follow people using the Search tab. You can also view followers and following from any profile page.';
        }
        if (str_contains($msg, 'market') || str_contains($msg, 'shop') || str_contains($msg, 'store')) {
            return 'The Marketplace lets you browse products from stores on SupperAge. Access it from the + FAB button on the feed.';
        }
        if (str_contains($msg, 'live') || str_contains($msg, 'stream')) {
            return 'Go Live from the Live tab. Viewers can comment and like your stream in real time.';
        }
        if (str_contains($msg, 'event')) {
            return 'Browse and RSVP to events from the Events section. Access it from the + FAB button on the feed.';
        }
        if (str_contains($msg, 'hello') || str_contains($msg, 'hi') || str_contains($msg, 'hey')) {
            return 'Hello! I\'m AgeAI, your SupperAge assistant. Ask me anything about the platform — posts, wallet, events, marketplace, and more!';
        }

        return 'I\'m here to help with SupperAge! You can ask me about posts, stories, wallet, marketplace, events, live streaming, messages, and more.';
    }
}
