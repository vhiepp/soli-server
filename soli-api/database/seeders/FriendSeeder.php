<?php

namespace Database\Seeders;

use App\Models\FriendRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sum = 0;
        $userService = new UserService();
        $users = User::all();
        foreach ($users as $user) {
            $userFriends = User::where('id', '<>', $user->id)->inRandomOrder()->limit(rand(1, 30))->get();
            foreach ($userFriends as $userFriend) {
                $userService->sendFriendRequest($user, $userFriend);
                if ($sum != 0) echo "\033[F\033[K";
                echo "Tạo lời mời kết bạn: " . (++$sum) . "\n";
            }
        }
        echo "Đã tạo " . $sum . " lời mời kết bạn. \n";
        $friendRequests = FriendRequest::inRandomOrder()->limit(rand($sum/4, $sum/2))->get();
        $sum = 0;
        foreach ($friendRequests as $friendRequest) {
            $userService->addFriend($friendRequest->user_request_id, $friendRequest->user_is_requested_id);
            if ($sum != 0) echo "\033[F\033[K";
            echo "Lời mời đã chấp nhận: " . (++$sum) . "\n";
        }
        echo "Đã chấp nhận " . $sum . " lời mời kết bạn. \n";
    }
}
