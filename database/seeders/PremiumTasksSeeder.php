<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PremiumTask;


class PremiumTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
public function run()
{
    $tasks = [
        ['name' => 'Blue Badge Verification', 'icon' => '✅'],
        ['name' => 'Priority Profile Boost', 'icon' => '🚀'],
        ['name' => 'Enhanced Security & Privacy', 'icon' => '🛡'],
        ['name' => 'Advanced Analytics', 'icon' => '📈'],
        ['name' => 'Premium Support 24/7', 'icon' => '💬'],
    ];

    foreach ($tasks as $task) {
        PremiumTask::create($task);
    }
}

}
