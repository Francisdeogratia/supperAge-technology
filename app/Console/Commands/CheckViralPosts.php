<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SamplePost;
use App\Models\PostPerformanceTracking;
use App\Models\Notification;
use Carbon\Carbon;

class CheckViralPosts extends Command
{
    protected $signature = 'posts:check-viral';
    protected $description = 'Check for viral posts and send notifications';

    public function handle()
    {
        $this->info('Checking for viral posts...');
        
        // Get posts from last 3 days
        $recentPosts = SamplePost::where('created_at', '>=', now()->subDays(3))
            ->withCount(['likesRelation', 'comments', 'repostsRelation', 'sharesRelation', 'viewsRelation'])
            ->get();
        
        $notifiedCount = 0;
        
        foreach ($recentPosts as $post) {
            // Check if already tracked
            $tracking = PostPerformanceTracking::firstOrCreate(
                ['post_id' => $post->id],
                [
                    'user_id' => $post->user_id,
                    'likes_count' => $post->likes_relation_count,
                    'comments_count' => $post->comments_count,
                    'reposts_count' => $post->reposts_relation_count,
                    'shares_count' => $post->shares_relation_count,
                    'views_count' => $post->views_relation_count
                ]
            );
            
            // Update counts
            $tracking->update([
                'likes_count' => $post->likes_relation_count,
                'comments_count' => $post->comments_count,
                'reposts_count' => $post->reposts_relation_count,
                'shares_count' => $post->shares_relation_count,
                'views_count' => $post->views_relation_count
            ]);
            
            // Check if viral (meets all criteria)
            if ($this->isViral($tracking) && !$tracking->fire_notification_sent_at) {
                $this->sendFireNotification($post, $tracking);
                $notifiedCount++;
            }
        }
        
        $this->info("Checked {$recentPosts->count()} posts. Sent {$notifiedCount} 'On Fire' notifications.");
        
        return 0;
    }
    
    private function isViral($tracking)
    {
        return $tracking->likes_count >= 1000 &&
               $tracking->comments_count >= 500 &&
               $tracking->reposts_count >= 100 &&
               $tracking->shares_count >= 100 &&
               $tracking->views_count >= 2000;
    }
    
    private function sendFireNotification($post, $tracking)
    {
        // Create notification
        Notification::create([
            'user_id' => 0, // System notification
            'message' => "🔥 Your post is on FIRE! Keep it up - you're close to monetization!",
            'link' => route('posts.show', $post->id),
            'notification_reciever_id' => $post->user_id,
            'read_notification' => 'no',
            'type' => 'post_viral',
            'notifiable_type' => SamplePost::class,
            'notifiable_id' => $post->id,
            'data' => json_encode([
                'post_id' => $post->id,
                'likes' => $tracking->likes_count,
                'comments' => $tracking->comments_count,
                'reposts' => $tracking->reposts_count,
                'shares' => $tracking->shares_count,
                'views' => $tracking->views_count,
                'message' => 'Your post has reached viral status! This level of engagement is bringing you closer to monetization eligibility.'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Update tracking
        $tracking->update([
            'fire_notification_sent_at' => now(),
            'is_viral' => true,
            'viral_achieved_at' => now()
        ]);
        
        $this->info("✅ Sent notification to user {$post->user_id} for post {$post->id}");
    }
}