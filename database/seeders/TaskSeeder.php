<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // First batch of tasks
        $tasks = [
            
            
            [
                'title' => 'Invite a friend',
                'description' => 'Bring a new friend to SupperAge',
                'category' => 'community',
                'reward_points' => 200,
                'status' => 'available', // ✅ fixed
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Download SupperAge App',
                'description' => 'Once you download our App come here and claim your reward',
                'category' => 'special',
                'reward_points' => 10,
                'status' => 'available', // ✅ fixed
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Second batch of tasks
        $extraTasks = [
            [
                'title' => 'Share our app for download',
                'description' => 'Invite 5 friends to download SupperAge App on PlaY store or App store ',
                'category' => 'referral',
                'reward_points' => 1000,
                'status' => 'available', // ✅ fixed
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Daily Login/Signup',
                'description' => 'Once you signup on SupperAge come here and claim your reward,and remember any times you login you earn',
                'category' => 'daily',
                'reward_points' => 10,
                'status' => 'available', // ✅ fixed
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ];

        // Merge and insert all tasks
        \App\Models\Task::insert(array_merge($tasks, $extraTasks));
    }
}
