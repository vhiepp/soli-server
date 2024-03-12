<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            FriendSeeder::class,
            FollowSeeder::class,
            PostSeeder::class,
            PostCommentSeeder::class,
            PostHeartSeeder::class,
        ]);
        echo "Ok!\n";
    }
}
