<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    protected PostService $postService;
    protected UserService $userService;
    public function __construct()
    {
        $this->postService = new PostService();
        $this->userService = new UserService();
    }
    public function createNewPost(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'media' => 'mimes:jpeg,png,jpg,gif,svg',
            ]);
            if ($validator->fails()) {
                return response(reshelper()->withFormat(null, 'Error, media must be an image', 'error', false, true));
            }
            $mediaUrl = filehelper()->saveMedia($request->file('media'), auth()->user()->uid);
//            if (env('SERVER_IMAGE_URL', false) && env('APP_ENV', '') == 'production') {
//                $mediaUrl = env('SERVER_IMAGE_URL') . "?w=600&h=800&image=" . env("APP_URL", '') . $mediaUrl;
//            }
            $post = $this->postService->createNew(
                auth()->user(),
                $request->caption,
                $mediaUrl,
                $request->limit
            );
            $post = Post::find($post->id);
            if ($post) {
                return response()->json(reshelper()->withFormat($post, 'Create new post successfully'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getPostForUserId(Request $request, string $id)
    {
        try {
            if (str($id)->isUuid()) {
                $user = User::find($id);
            } else {
                $user = User::where('uid', $id)->first();
            }
            if ($user) {
                $posts = $this->postService->getPostForUser($user);
                return response()->json(reshelper()->withFormat($posts));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getPosts(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user) {
                $notInPostIds = [];
                $newExceptPosts = [];
                $nowTime = time();
                $exceptPosts = $request->except_posts ? $request->except_posts : [];
                if ($exceptPosts && str($exceptPosts)->isJson()) {
                    $exceptPosts = json_decode($exceptPosts);
                    foreach ($exceptPosts as $exceptPost) {
                        if ($exceptPost->exp > $nowTime) {
                            array_push($notInPostIds, $exceptPost->id);
                            array_push($newExceptPosts, $exceptPost);
                        }
                    }
                }
                $posts = $this->postService->getPosts($user, $notInPostIds);
                $postRes = [];
                $count = 0;
                foreach ($posts as $post) {
                    if ($count < 8 && ($count == 0 || rand(0, 1) > 0)) {
                        array_push($newExceptPosts, [
                            'id' => $post->id,
                            'exp' => $nowTime + 300
                        ]);
                        if ($this->postService->isUserHavePermissionToViewPost(auth()->user(), $post))
                        {
                            $count++;
                            array_push($postRes, $post);
                        }
                    }
                    if ($count >= 8) break;
                }
                shuffle($postRes);
                return response()->json(reshelper()->withFormat([
                    'posts' => $postRes,
                    'except_posts' => json_encode($newExceptPosts)
                ]));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getPostWithId(Request $request, string $id)
    {
        try {
            $post = $this->postService->getPostDetailWithId($id);
            if ($post && $this->postService->isUserHavePermissionToViewPost(auth()->user(), $post)) {
                return response()->json(reshelper()->withFormat($post));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function deletePost(Request $request) {
        try {
            if ($request->post_id && $this->postService->deletePostWithId($request->post_id)) {
                return response()->json(reshelper()->withFormat(null, 'Deleted Post Success'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }
}
