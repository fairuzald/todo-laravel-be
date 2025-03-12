<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Get all tags for the user
            $tags = $user->tags;

            // Create tasks for each user
            Task::factory(10)->create([
                'user_id' => $user->id,
            ])->each(function ($task) use ($tags) {
                // Attach 1-3 random tags to each task
                $task->tags()->attach(
                    $tags->random(rand(1, min(3, $tags->count())))->pluck('id')->toArray()
                );
            });
        }
    }
}
