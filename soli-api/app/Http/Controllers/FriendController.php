<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function requestFriend(Request $request) {
        try {
            if ($this->userService->sendFriendRequest(
                auth()->user()->id,
                $request->user_id
            )) {
                return response()->json(reshelper()->withFormat(null, 'Send friend request successfully'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function friendRequests(Request $request)
    {
        try {
            $pagination = $request->paginate ? $request->paginate : 8;
            $friendRequest = auth()->user()->friendRequests()->orderBy('updated_at', 'desc')->paginate($pagination);
            return response()->json(reshelper()->withFormat($friendRequest));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error', 'error', false, true));
    }

    public function cancelledFriendRequests(Request $request)
    {
        try {
            if ($this->userService->cancelledFriendRequests($request->user_request_id, auth()->user())) {
                return response()->json(reshelper()->withFormat(null, 'Delete friend request successfully'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function cancelledSendRequestFriend(Request $request)
    {
        try {
            if ($this->userService->cancelledRequestFriend(auth()->user(), $request->user_is_requested_id)) {
                return response()->json(reshelper()->withFormat(null, 'Cancelled request friend successfully'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function agreedFriendRequests(Request $request)
    {
        try {
            if ($this->userService->addFriend(auth()->user(), $request->user_request_id)) {
                return response()->json(reshelper()->withFormat(null, 'Agreed friend request successfully'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function friends(Request $request)
    {
        try {
            $pagination = $request->paginate ? $request->paginate : 8;
            $friendRequest = auth()->user()->friends()->orderBy('updated_at', 'asc')->paginate($pagination);
            return response()->json(reshelper()->withFormat($friendRequest));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error', 'error', false, true));
    }

    public function cancelledFriend(Request $request)
    {
        try {
            if ($this->userService->cancelledFriend($request->user_id, auth()->user())) {
                return response()->json(reshelper()->withFormat(null, 'Cancelled friend successfully'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }
}
