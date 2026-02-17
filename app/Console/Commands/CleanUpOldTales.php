<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TalesExten;


class CleanUpOldTales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-up-old-tales';

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
        TalesExten::where('type', 'tales')
    ->where('created_at', '<', now()->subDay())
    ->delete();

    }
}
