<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class HeartController extends Controller
{
    public PostService $postService;

    public function __construct()
    {
        $this->postService = new PostService();
    }

    public function heartForPostId(Request $request, string $id)
    {
        try {
            $post = Post::find($id);
            if ($this->postService->isUserHavePermissionToViewPost(auth()->user(), $post)) {
                $heart = $post->hearts->where('id', auth()->user()->id)->first();
                if ($heart && $heart->pivot->active) {
                    $this->postService->cancelledHeart($post, auth()->user());
                    return response()->json(reshelper()->withFormat(null, 'Cancelled Heart Success'));
                } else {
                    $this->postService->createHeart($post, auth()->user());
                    return response()->json(reshelper()->withFormat(null, 'Created Heart Success'));
                }
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }
}
