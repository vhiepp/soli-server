<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;
    protected PostService $postService;
    public function __construct()
    {
        $this->userService = new UserService();
        $this->postService = new PostService();
    }

    public function profile(Request $request) {
        try {
            $user = null;
            if (gettype($request->uid) == 'array') {
                $users = User::whereIn('uid', $request->uid)->get();
                if ($request->group) {
                    $users = $users->groupBy('uid')->toArray();
                    foreach ($users as $key => $user) {
                        $users[$key] = $user[0];
                    }
                }
                return response()->json(reshelper()->withFormat($users));
            } else {
                if ($request->id) {
                    $user = User::where('id', $request->id)->first();
                }
                elseif ($request->uid) {
                    $user = User::where('uid', $request->uid)->first();
                }
                elseif ($request->email) {
                    $user = User::where('email', $request->email)->first();
                }
                if ($user) {
                    return response()->json(reshelper()->withFormat($this->resProfile($user)));
                }
            }
        } catch (\Exception $exception) {}

        return response()->json(reshelper()->withFormat(null, 'Error or not found user', 'not_found', false, true));
    }

    public function profileWithId(Request $request, string $id) {
        try {
            if (str($id)->isUuid()) {
                $user = User::find($id);
            } else {
                $user = User::where('uid', $id)->first();
            }
            if ($user) {
                return response()->json(reshelper()->withFormat($this->resProfile($user)));
            }
        } catch (\Exception $exception) {}

        return response()->json(reshelper()->withFormat(null, 'Error or not found user', 'not_found', false, true));
    }

    public function resProfile(User $user) {
        if ($user) {
            $posts_total = $this->postService->getPostForUser($user)->total();
            $followers_total = $user->followers()->count();
            $following_total = $user->following()->count();
            return [
                'profile' => $user,
                'posts' => [
                    'total' => $posts_total,
                    'total_short' => numberhelper()->abbreviateNumber($posts_total),
                ],
                'followers' => [
                    'total' => $followers_total,
                    'total_short' => numberhelper()->abbreviateNumber($followers_total),
                ],
                'following' => [
                    'total' => $following_total,
                    'total_short' => numberhelper()->abbreviateNumber($following_total),
                ],
                'is_following' => $this->userService->isFollowingUser(auth()->user(), $user),
                'is_friend' => $this->userService->isFriend(auth()->user(), $user),
                'is_send_invitation' => !!auth()->user()->sendFriendRequests->where('id', $user->id)->first(),
                'is_request_friend' => !!$user->sendFriendRequests()->where('id', auth()->user()->id)->first()
            ];
        }
        return null;
    }

    public function searchUser(Request $request) {
        try {
            $searchKey = $request->search_key ? $request->search_key : null;
            if ($searchKey) {
                $users = $this->userService->searchUserForKey($searchKey);
                foreach ($users as $user) {
                    $user->is_following = $this->userService->isFollowingUser(auth()->user(), $user);
                    $user->followers = [
                        'total' => $user->followers_count,
                        'total_short' => numberhelper()->abbreviateNumber($user->followers_count),
                    ];
                }
                return response()->json(reshelper()->withFormat($users));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }
}
