<?php

namespace App\Services\Notifications;

class NotificationType
{
    const LIKE_POST = 'like_post';
    const COMMENT_POST = 'comment_post';
    const LIKE_STORY = 'like_story';
    const COMMENT_STORY = 'comment_story';
    const CREATE_NEW_STORY = 'create_story';
    const FOLLOW = 'follow';
    const SYSTEM = 'system';
    const NEW_FRIEND_REQUEST = 'new_friend_request';
    const AGREED_FRIEND_REQUEST = 'agreed_friend_request';
}
