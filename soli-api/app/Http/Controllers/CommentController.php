<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\CommentService;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public UserService $userService;
    public PostService $postService;
    public CommentService $commentService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->postService = new PostService();
        $this->commentService = new CommentService();
    }

    public function getCommentForPostId(Request $request, string $id)
    {
        try {
            $post = Post::find($id);
            if ($this->postService->isUserHavePermissionToViewPost(auth()->user(), $post)) {
                $exIds = json_decode($request->exIds??"[]");
                $comments = $post->comments()->whereNotIn('id', $exIds)->selectRaw('*, CAST(updated_at AS UNSIGNED) AS updated_at_number')->orderByDesc('updated_at_number')->paginate(8);
                return response()->json(reshelper()->withFormat($comments));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function createCommentForPostId(Request $request, string $id)
    {
        try {
            $content = $request->input('content') ? $request->input('content') : null;
            if ($content) {
                $post = Post::find($id);
                if ($this->postService->isUserHavePermissionToViewPost(auth()->user(), $post)) {
                    $comment = $this->postService->createComment($post, auth()->user(), $content);
                    $comment = Comment::find($comment->id);
                    return response()->json(reshelper()->withFormat($comment, 'Create comment success'));
                }
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function deleteComment(Request $request)
    {
        try {
            $comment = Comment::find($request->comment_id);
            if ($comment && $this->commentService->isUserAuthorForComment(auth()->user(), $comment)) {
                $this->commentService->deleteComment($comment);
                return response()->json(reshelper()->withFormat(null, 'Deleted comment success'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }
}
