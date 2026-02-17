<?php

namespace App\Console\Commands;

use App\Models\Comment;
use Illuminate\Console\Command;

class CleanUpOldComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-up-old-comments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         Comment::where('created_at', '<', now()->subDay())->delete();
    \Log::info('Old comments deleted.');
    }
}
