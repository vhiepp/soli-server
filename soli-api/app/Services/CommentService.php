<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;

class CommentService
{
    public function isUserAuthorForComment(string|User|null $user, string|Comment|null $comment): bool
    {
        try {
            if (gettype($user) == 'string') {
                $user = User::find($user);
            }
            if (gettype($comment) == 'string') {
                $comment = Comment::find($comment);
            }
            return $comment->author->id == $user->id;
        } catch (\Exception $exception) {}
        return false;
    }

    public function deleteComment(string|Comment|null $comment): bool
    {
        try {
            if (gettype($comment) == 'string') {
                $comment = Comment::find($comment);
            }
            $comment->update(['status' => 'deleted']);
            return true;
        } catch (\Exception $exception) {}
        return false;
    }
}
