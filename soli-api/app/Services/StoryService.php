<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Story;
use App\Models\User;
use App\Services\Notifications\NotificationMessage;
use App\Services\Notifications\NotificationService;
use App\Services\Notifications\NotificationType;

class StoryService
{
    public function isUserHavePermissionToViewStory(string|User|null $user, string|Story|null $story): bool
    {
        try {
            if (gettype($user) == 'string') {
                $user = User::find($user);
            }
            if (gettype($story) == 'string') {
                $story = Story::find($story);
            }
            $userService = new UserService();
            return  ($story && $user &&
                ($story->limit == 'all' ||
                    ($story->limit == 'friends' && ($userService->isFriend($user, $story->author) || $user->id == $story->author->id)) ||
                    ($story->limit == 'only_me' && $story->author->id == $user->id))
            );
        } catch (\Exception $exception) {}
        return false;
    }
    public function createNew(string|User|null $user, array|string|null $mediaUrl, string|null $limit = 'all')
    {
        if (gettype($user) == 'string') {
            $user = User::find($user);
        }
        if ($limit == null) {
            $limit = 'only_me';
        }
        $media = Media::create([
            'file_url' => $mediaUrl,
            'type' => 'video'
        ]);
        $story = $user->stories()->create([
            'status' => 'showing',
            'limit' => $limit,
            'media_id' => $media->id
        ]);
        $notificationService = new NotificationService();
        foreach ($user->followers as $follower) {
            $notificationService->createNew(
                NotificationType::CREATE_NEW_STORY,
                $follower,
                $user,
                NotificationMessage::CREATE_NEW_STORY,
                [
                    'story_id' => $story->id
                ]
            );
        }
        return $story;
    }

    public function getStoryForUser(string|User|null $user)
    {
        if (gettype($user) == 'string') {
            $user = User::find($user);
        }
        $limit = ['all'];
        if (auth()->check()) {
            $userService = new UserService();
            if ($user->id == auth()->user()->id) {
                $limit = ['all', 'friends', 'only_me'];
            } elseif ($userService->isFriend($user, auth()->user())) {
                $limit = ['all', 'friends'];
            }
        }
        return $user->stories()->whereIn('limit', $limit)->orderBy('created_at', 'desc')->get();
    }

    public function getUserStoryList()
    {
        $users = User::where('id', '<>', auth()->user()->id)
            ->withCount('stories')
            ->having('stories_count', '>', 0)
            ->orderByDesc('stories_count')
            ->paginate(40);
        foreach ($users as $user) {
            $limit = ['all'];
            if (auth()->check()) {
                $userService = new UserService();
                if ($user->id == auth()->user()->id) {
                    $limit = ['all', 'friends', 'only_me'];
                } elseif ($userService->isFriend($user, auth()->user())) {
                    $limit = ['all', 'friends'];
                }
            }
            $stories_count = $user->stories()->whereIn('limit', $limit)->count();
            $user->stories_count = $stories_count;
        }
        return $users;
    }

    public function getStoryDetailWithId(string|null $id)
    {
        if ($id) {
            $timeOfExistenceForStory = env('TIME_OF_EXISTENCE_FOR_STORY', 24 * 60 * 60);
            $story = Story::where('id', $id)->where('status', 'showing')->where('created_at', '>=', time() - $timeOfExistenceForStory)->first();
            if ($story) {
                return $story;
            }
        }
        return null;
    }

    public function deleteStoryWithId(string|null $id): bool
    {
        if ($id) {
            $timeOfExistenceForStory = env('TIME_OF_EXISTENCE_FOR_STORY', 24 * 60 * 60);
            $story = Story::where('id', $id)->where('status', 'showing')->where('created_at', '>=', time() - $timeOfExistenceForStory);
            if ($story->first() && $story->first()->author->id == auth()->user()->id) {
                $story->update(['status' => 'deleted']);
                return true;
            }
        }
        return false;
    }
}
