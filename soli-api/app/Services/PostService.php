<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\Services\Notifications\NotificationMessage;
use App\Services\Notifications\NotificationService;
use App\Services\Notifications\NotificationType;

class PostService
{
    public function isUserHavePermissionToViewPost(string|User|null $user, string|Post|null $post): bool
    {
        try {
            if (gettype($user) == 'string') {
                $user = User::find($user);
            }
            if (gettype($post) == 'string') {
                $post = Post::find($post);
            }
            $userService = new UserService();
            return  ($post && $user &&
                    ($post->limit == 'all' ||
                    ($post->limit == 'friends' && ($userService->isFriend($user, $post->author) || $user->id == $post->author->id)) ||
                    ($post->limit == 'only_me' && $post->author->id == $user->id))
            );
        } catch (\Exception $exception) {}
        return false;
    }
    public function createNew(string|User $user, string $description, array|string $mediaUrl, string|null $limit = 'all')
    {
        if (gettype($user) == 'string') {
            $user = User::find($user);
        }
        if (gettype($mediaUrl) == 'string') {
            $arr = []; array_push($arr, $mediaUrl);
            $mediaUrl = $arr;
        }
        if ($limit == null) {
            $limit = 'only_me';
        }
        $post = $user->posts()->create([
            'description' => $description,
            'status' => 'showing',
            'limit' => $limit,
        ]);
        foreach ($mediaUrl as $url) {
            $post->media()->create([
                'file_url' => $url,
                'type' => 'image'
            ]);
        }
        return $post;
    }

    public function createComment(string|Post $post, string|User $user, string $content)
    {
        if (gettype($user) == 'string') {
            $user = User::find($user);
        }
        if (gettype($post) == 'string') {
            $post = Post::find($post);
        }
        $comment = $post->comments()->create([
            'content' => $content,
            'user_author_id' => $user->id
        ]);
        $notificationService = new NotificationService();
        $notificationService->createNew(
            NotificationType::COMMENT_POST,
            $post->authors()->first(),
            $user,
            NotificationMessage::COMMENT_POST,
            [
                'post_id' => $post->id,
                'comment_id' => $comment->id
            ]
        );
        return $comment;
    }

    public function createHeart(string|Post $post, string|User $user): bool
    {
        try {
            if (gettype($user) == 'string') {
                $user = User::find($user);
            }
            if (gettype($post) == 'string') {
                $post = Post::find($post);
            }
            $post->hearts()->syncWithoutDetaching([$user->id => ['active' => true]]);
            $notificationService = new NotificationService();
            $notificationService->createNew(
                NotificationType::LIKE_POST,
                $post->authors()->first(),
                $user,
                NotificationMessage::LIKE_POST,
                [
                    'post_id' => $post->id
                ]
            );
            return true;
        } catch (\Exception $exception) {}
        return false;
    }

    public function cancelledHeart(string|Post $post, string|User $user): bool
    {
        try {
            if (gettype($user) == 'string') {
                $user = User::find($user);
            }
            if (gettype($post) == 'string') {
                $post = Post::find($post);
            }
            $post->hearts()->updateExistingPivot($user->id, ['active' => false]);
            return true;
        } catch (\Exception $exception) {}
        return false;
    }

    public function getPostForUser(string|User $user, int $paginate = 8)
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
        return $user->posts()->whereIn('limit', $limit)->selectRaw('*, CAST(created_at AS UNSIGNED) AS created_at_number')->orderByDesc('created_at_number')->orderBy('created_at', 'desc')->paginate(6);
    }

    public function getPosts(string|User $user, array $notInPostIds = [])
    {
        if (gettype($user) == 'string') {
            $user = User::find($user);
        }
        $posts = Post::whereNotIn('id', $notInPostIds)->where('status', 'showing')->selectRaw('*, CAST(updated_at AS UNSIGNED) AS updated_at_number')->orderByDesc('updated_at_number')->limit(30)->get();
        return $posts;
    }

    public function getPostDetailWithId(string|null $id)
    {
        if ($id) {
            $post = Post::where('id', $id)->where('status', 'showing')->first();
            if ($post) {
                return $post;
            }
        }
        return null;
    }

    public function deletePostWithId(string|null $id): bool
    {
        if ($id) {
            $post = Post::where('id', $id)->where('status', 'showing')->first();
            if ($post && $post->author->id == auth()->user()->id) {
                Post::where('id', $id)->where('status', 'showing')->update(['status' => 'deleted']);
                return true;
            }
        }
        return false;
    }
}
