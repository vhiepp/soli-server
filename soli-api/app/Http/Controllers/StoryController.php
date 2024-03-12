<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Story;
use App\Models\User;
use App\Services\StoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoryController extends Controller
{
    public StoryService $storyService;

    public function __construct()
    {
        $this->storyService = new StoryService();
    }

    public function createNewStory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'media' => 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/x-flv',
            ]);
            if ($validator->fails()) {
                return response(reshelper()->withFormat(null, 'Error, media must be an video', 'error', false, true));
            }
            $mediaUrl = filehelper()->saveMedia($request->file('media'), auth()->user()->uid, 'videos');
            $story = $this->storyService->createNew(auth()->user(), $mediaUrl, $request->limit);
            $story = Story::find($story->id);
            if ($story) {
                return response()->json(reshelper()->withFormat($story, 'Create new story successfully'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getStoryForUserId(Request $request, string $id)
    {
        try {
            if (str($id)->isUuid()) {
                $user = User::find($id);
            } else {
                $user = User::where('uid', $id)->first();
            }
            if ($user) {
                $stories = $this->storyService->getStoryForUser($user);
                return response()->json(reshelper()->withFormat($stories));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getUserStoryListForYou(Request $request)
    {
            $users = $this->storyService->getUserStoryList();
            $users = $users->toArray();
            $users_temp = [];
            foreach ($users['data'] as $user) {
                if ($user['stories_count'] > 0) {
                    $users_temp[] = $user;
                }
            }
            $users['data'] = $users_temp;
            shuffle($users['data']);
            return response()->json(reshelper()->withFormat($users));
        try {
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getStoryWithId(Request $request, string $id)
    {
        try {
            $story = $this->storyService->getStoryDetailWithId($id);
            if ($story && $this->storyService->isUserHavePermissionToViewStory(auth()->user(), $story)) {
                return response()->json(reshelper()->withFormat($story));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getStoryListForMe(Request $request)
    {
        try {
            $stories = $this->storyService->getStoryForUser(auth()->user());
            return response()->json(reshelper()->withFormat($stories));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function deleteStory(Request $request)
    {
        try {
            if ($request->story_id && $this->storyService->deleteStoryWithId($request->story_id)) {
                return response()->json(reshelper()->withFormat(null, 'Deleted Story Success'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }
}
