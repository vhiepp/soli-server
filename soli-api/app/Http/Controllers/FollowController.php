<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public UserService $userService;
    public function __construct()
    {
        $this->userService = new UserService();
    }
    public function followers(Request $request)
    {
        try {
            $pagination = $request->paginate ? $request->paginate : 8;
            $friendRequest = auth()->user()->followers()->orderBy('updated_at', 'asc')->paginate($pagination);
            foreach ($friendRequest as $fR) {
                $fR->is_following = $this->userService->isFollowingUser(auth()->user(), $fR);
            }
            return response()->json(reshelper()->withFormat($friendRequest));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error', 'error', false, true));
    }

    public function followUser(Request $request)
    {
        try {
            $userFollow = User::find($request->user_id);
            auth()->user()->following()->syncWithoutDetaching($userFollow->id);
            return response()->json(reshelper()->withFormat(null, 'Follow user successfully'));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function cancelledFollowUser(Request $request)
    {
        try {
            $userFollow = User::find($request->user_id);
            auth()->user()->following()->syncWithoutDetaching([$userFollow->id => ['status' => 'unfollowed']]);
            return response()->json(reshelper()->withFormat(null, 'Delete follow user successfully'));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function following(Request $request)
    {
        try {
            $pagination = $request->paginate ? $request->paginate : 8;
            $following = auth()->user()->following()->orderBy('updated_at', 'asc')->paginate($pagination);
            return response()->json(reshelper()->withFormat($following));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error', 'error', false, true));
    }
}
