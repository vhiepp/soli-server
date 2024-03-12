<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Vhiepp\VNDataFaker\VNFaker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sum = 0;
        $postService = new PostService();
        $users = User::all();
        $timeS = strtotime('01/01/2020');
        $timeE = time();
        foreach ($users as $user) {
            for ($i = 0; $i < rand(5, 25); $i++) {
                try {
                    $description = VNFaker::statusText();
                    $post = $postService->createNew(
                        $user,
                        $description,
                        VNFaker::image(600, 800, $description),
                        VNFaker::array_rand(['all', 'friends', 'all', 'only_me', 'all'])
                    );

                    $time = rand($timeS, $timeE);
                    $post->update([
                        'created_at' => $time,
                        'updated_at' => $time,
                    ]);
                    if ($sum != 0) echo "\033[F\033[K";
                    echo "Tạo posts: " . (++$sum) . "\n";
                } catch (\Exception $exception) {}
            }
        }
        echo "Đã tạo " . $sum . " posts. \n";
    }
}
