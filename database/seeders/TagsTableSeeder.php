<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $defaultTags = [
            ['name' => 'Work', 'color' => '#3498db'],
            ['name' => 'Personal', 'color' => '#2ecc71'],
            ['name' => 'Urgent', 'color' => '#e74c3c'],
            ['name' => 'Study', 'color' => '#9b59b6'],
            ['name' => 'Shopping', 'color' => '#f39c12'],
        ];

        foreach ($users as $user) {
            foreach ($defaultTags as $tag) {
                Tag::create([
                    'name' => $tag['name'],
                    'color' => $tag['color'],
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
