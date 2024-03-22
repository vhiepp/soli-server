<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HeartController;
use App\Http\Controllers\NotifiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('sign-in', [AuthController::class, 'signInWithEmailPassword']);
        Route::post('sign-in-with-firebase', [AuthController::class, 'signInWithFirebase']);
        Route::post('sign-in-with-oauth', [AuthController::class, 'signInWithOAuth']);
    });

    Route::middleware('authen')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::get('sign-out', [AuthController::class, 'signOut']);
            Route::get('profile', [AuthController::class, 'profile']);
        });

        Route::prefix('user')->group(function () {

            Route::prefix('profile')->group(function () {
                Route::post('', [UserController::class, 'profile']);
                Route::prefix('{id}')->group(function () {
                    Route::get('', [UserController::class, 'profileWithId']);
                });
            });

            Route::prefix('{id}')->group(function () {
                Route::get('', [UserController::class, 'profileWithId']);
                Route::get('profile', [UserController::class, 'profileWithId']);
                Route::get('posts', [PostController::class, 'getPostForUserId']);
                Route::get('stories', [StoryController::class, 'getStoryForUserId']);
            });

            Route::prefix('search')->group(function () {
                Route::post('', [UserController::class, 'searchUser']);
            });

        });

        Route::prefix('friend')->group(function () {
            Route::get('', [FriendController::class, 'friends']);

            Route::post('', [FriendController::class, 'requestFriend']);
            Route::post('send-request-friend', [FriendController::class, 'requestFriend']);
            Route::delete('send-request-friend', [FriendController::class, 'cancelledSendRequestFriend']);

            Route::delete('', [FriendController::class, 'cancelledFriend']);

            Route::prefix('request')->group(function () {
                Route::get('', [FriendController::class, 'friendRequests']);
                Route::delete('', [FriendController::class, 'cancelledFriendRequests']);
                Route::post('', [FriendController::class, 'agreedFriendRequests']);
            });
        });

        Route::prefix('follower')->group(function () {
            Route::get('', [FollowController::class, 'followers']);
        });

        Route::prefix('following')->group(function () {
            Route::get('', [FollowController::class, 'following']);
            Route::post('', [FollowController::class, 'followUser']);
            Route::delete('', [FollowController::class, 'cancelledFollowUser']);
        });

        Route::prefix('posts')->group(function () {
            Route::post('for-you', [PostController::class, 'getPosts']);
            Route::post('', [PostController::class, 'createNewPost']);
            Route::delete('', [PostController::class, 'deletePost']);

            Route::prefix('{id}')->group(function () {
                Route::get('', [PostController::class, 'getPostWithId']);

                Route::prefix('comments')->group(function () {
                    Route::get('', [CommentController::class, 'getCommentForPostId']);
                    Route::post('', [CommentController::class, 'createCommentForPostId']);
                });

                Route::prefix('hearts')->group(function () {
                    Route::post('', [HeartController::class, 'heartForPostId']);
                });
            });
        });

        Route::prefix('stories')->group(function () {
            Route::post('', [StoryController::class, 'createNewStory']);
            Route::get('users', [StoryController::class, 'getUserStoryListForYou']);
            Route::get('', [StoryController::class, 'getStoryListForMe']);
            Route::delete('', [StoryController::class, 'deleteStory']);

            Route::prefix('{id}')->group(function () {
                Route::get('', [StoryController::class, 'getStoryWithId']);
            });
        });

        Route::prefix('comments')->group(function () {
            Route::delete('', [CommentController::class, 'deleteComment']);
        });

        Route::prefix('search')->group(function () {
            Route::post('user', [UserController::class, 'searchUser']);
        });

        Route::prefix('notifications')->group(function () {
            Route::get('', [NotifiController::class, 'getAllNotification']);
            Route::get('new', [NotifiController::class, 'getNewNotification']);
            Route::get('old', [NotifiController::class, 'getOldNotification']);
            Route::delete('', [NotifiController::class, 'deleteNotificationWithId']);

            Route::post('seen', [NotifiController::class, 'seenNotificationWithId']);
        });
    });
});
