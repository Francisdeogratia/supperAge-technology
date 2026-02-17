<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\TalesExten;

class DeleteExpiredTales extends Command
{
   protected $signature = 'tales:delete-expired';
    protected $description = 'Delete tales older than 1 day and remove associated media';

    public function handle()
    {
        $expiredTales = TalesExten::where('created_at', '<', now()->subDay())->get();

foreach ($expiredTales as $tale) {
    if ($tale->files_talesexten) {
        Storage::delete('public/talesphotos/' . $tale->files_talesexten);
    }
    $tale->delete();
}

        $this->info('Expired tales deleted successfully.');
    }
}
