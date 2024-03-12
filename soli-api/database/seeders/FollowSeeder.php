<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FollowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userService = new UserService();
        $users = User::all();
        $sum = 0;
        foreach ($users as $user) {
            $userFollows = User::where('id', '<>', $user->id)->inRandomOrder()->limit(rand(1, 40))->get();
            foreach ($userFollows as $userFollow) {
                $user->following()->syncWithoutDetaching($userFollow->id);
                if ($sum != 0) echo "\033[F\033[K";
                echo "Tạo follow: " . (++$sum) . "\n";
            }
        }
        echo "Đã tạo " . $sum . " follows. \n";
    }
}
