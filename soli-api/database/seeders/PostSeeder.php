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
        $arrImage = [
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036723/images/image_74_u1kpgj.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036719/images/image_72_zmbc9s.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036719/images/image_73_ddievo.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036718/images/image_71_esstb0.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036718/images/image_70_gqvmem.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036715/images/image_69_uixohs.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036714/images/image_68_ylcpn9.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036713/images/image_67_mfwuup.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036711/images/image_66_jnzkyb.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036710/images/image_65_fcpo3c.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036709/images/image_64_hlse1h.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036707/images/image_63_p8x3nq.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036706/images/image_62_fiedst.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036705/images/image_61_zhehg6.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036703/images/image_60_hfp5q1.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036702/images/image_59_rx7uh7.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036702/images/image_58_ov1zax.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036700/images/image_57_yjf0hf.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036698/images/image_55_jss7jf.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036684/images/image_45_pwcgub.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036683/images/image_44_ozvueh.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036683/images/image_42_sm6hml.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036682/images/image_43_wonlvq.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036680/images/image_41_lex26m.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036679/images/image_40_jiei3b.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036678/images/image_39_ofmrbt.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036675/images/image_38_t85rei.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036675/images/image_37_cxmjam.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036674/images/image_36_krfsek.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036673/images/image_35_qsgvlm.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036672/images/image_34_rj2ob3.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036672/images/image_33_nwyhrg.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036669/images/image_31_szmokm.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036668/images/image_30_hrvp9z.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036665/images/image_29_bvhz9j.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036665/images/image_28_xziowi.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036665/images/image_27_t0uvcc.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036660/images/image_22_qlcytb.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036660/images/image_25_mweuhr.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036623/images/image_21_z6t0db.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036623/images/image_20_jwnmw2.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036621/images/image_19_yciwyc.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036620/images/image_17_ns1xfi.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036615/images/image_16_imwftl.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036614/images/image_15_usbdja.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036611/images/image_13_wnp6pk.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036599/images/image_4_dsajci.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036596/images/image_3_tbzr8p.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036595/images/image_1_wmdjga.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036595/images/image_2_vxf8ub.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036595/images/image_105_mp2im5.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036550/images/image_103_mzrseh.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036525/images/image_101_rpvben.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036523/images/image_99_n2jgwi.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036521/images/image_97_f0jwsn.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036519/images/image_95_cfpyo6.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036517/images/image_92_gfc03q.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036515/images/image_90_eskftl.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036512/images/image_88_vyvfn7.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036513/images/image_89_f07rem.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036512/images/image_87_vgwujr.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036511/images/image_86_vnqagx.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036509/images/image_84_mgfxjy.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036509/images/image_82_ouxasf.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036508/images/image_78_dlk65l.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036509/images/image_82_ouxasf.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036509/images/image_84_mgfxjy.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036508/images/image_80_v26xzr.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036508/images/image_75_hpyl5o.jpg',
            'https://res.cloudinary.com/daz6jb1w7/image/upload/v1711036507/images/image_77_uzby0a.jpg'
        ];
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
//                        VNFaker::image(600, 800, $description),
                        $arrImage[rand(0, count($arrImage))],
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
