<?php

namespace Database\Seeders;

use App\Services\UserService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\User;
use Mockery\Exception;
use Vhiepp\VNDataFaker\VNFaker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Đang tạo user...\n";
        $count = config('seeder.user_total');
        $jump = 1;
        $sum = 0;
        $userService = new UserService();
        for ($i = 1; $i <= $count/$jump; $i++) {
            try {
               $users = User::factory($jump)->create();
               foreach ($users as $user) {
                   $user->accounts()->create([
                       'username' => $user->email,
                       'password' => '123',
                       'provider' => 'email/password',
                       'provider_id' => $user->email,
                   ]);
                   $userService->changeAvatar($user, VNFaker::avatar());
               }
                if ($i > 1) echo "\033[F\033[K";
                echo "User đã tạo: " . ($sum += $jump) . "\n";
            } catch (Exception $err) {}
        }
        echo "Đã tạo " . $sum . " user. \n";
        echo "Tạo thất bại " . ($count - $sum) . " user. \n";
    }
}
