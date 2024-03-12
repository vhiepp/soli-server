<?php

namespace App\Services\Notifications;

class NotificationMessage
{
    const LIKE_POST = 'thích bài viết của bạn';
    const COMMENT_POST = 'vừa bình luận về bài viết';
    const LIKE_STORY = 'thích tin của bạn';
    const COMMENT_STORY = 'vừa bình luận tin của bạn';
    const CREATE_NEW_STORY = 'vừa đăng khoảnh khắc';
    const FOLLOW = 'vừa theo dõi bạn';
    const SYSTEM = 'thông báo hệ thống';
    const NEW_FRIEND_REQUEST = 'vừa gửi lời mời kết bạn';
    const AGREED_FRIEND_REQUEST = 'vừa chấp nhận lời mời kết bạn';
}
