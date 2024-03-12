<?php

namespace App\Services\Notifications;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{

    protected int $limitNewNotification = 4;
    protected int $expTimeNewNotification = 86400 * 4; // 4 day

    public function isNotificationForUser(string|User|null $user, string|Notification|null $notification): bool
    {
        try {
            if (gettype($user) == 'string') {
                $user = User::find($user);
            }
            if (gettype($notification) == 'string') {
                $notification = Notification::find($notification);
            }
            return $notification->forUser->id == $user->id;
        } catch (\Exception $exception) {}
        return false;
    }
    public function createNew(
        string $notificationType = NotificationType::LIKE_POST,
        string|User $userIsNotified = null,
        string|User $userCreatedNotification = null,
        string $message = NotificationMessage::LIKE_POST,
        $detailData = null
    ): bool
    {
        if (gettype($userIsNotified) == 'string') {
            $userIsNotified = User::find($userIsNotified);
        }
        if (gettype($userCreatedNotification) == 'string') {
            $userCreatedNotification = User::find($userCreatedNotification);
        }
        if (
            $userIsNotified &&
            $userCreatedNotification &&
            ($userIsNotified->id != $userCreatedNotification->id) &&
            $notificationType
        ) {
            if ($notificationType == NotificationType::CREATE_NEW_STORY) {
                $userIsNotified->notifications()->updateOrCreate([
                    'type' => $notificationType,
                    'done_by_user_id' => $userCreatedNotification->id
                ], [
                    'type' => $notificationType,
                    'done_by_user_id' => $userCreatedNotification->id,
                    'message' => $message,
                    'detail_data' => json_encode($detailData),
                    'status' => 'not_seen'
                ]);
            }
            else {
                $userIsNotified->notifications()->create([
                    'done_by_user_id' => $userCreatedNotification->id,
                    'type' => $notificationType,
                    'message' => $message,
                    'detail_data' => json_encode($detailData),
                ]);
            }
            return true;
        }
        return false;
    }

    public function getAllNotifiListForUser(string|User|null $user)
    {
        if ($user) {
            if (gettype($user) == 'string') {
                $user = User::find($user);
            }
            $notifications = $user->notifications()->orderByDesc('updated_at')->paginate(8);
            return $notifications;
        }
        return null;
    }

    public function getOldNotifiListForUser(string|User|null $user)
    {
        if ($user) {
            if (gettype($user) == 'string') {
                $user = User::find($user);
            }
            $newNotifications = $this->getNewNotifiListForUser($user);
            $eIds = [];
            foreach ($newNotifications as $notifi) {
                array_push($eIds, $notifi->id);
            }
            $notifications = $user->notifications()->selectRaw('*, CAST(created_at AS UNSIGNED) AS created_at_number')->orderByDesc('created_at_number')->whereNotIn('id', $eIds)->paginate(8);
            return $notifications;
        }
        return null;
    }

    public function getNewNotifiListForUser(string|User|null $user)
    {
        if ($user) {
            if (gettype($user) == 'string') {
                $user = User::find($user);
            }
            $notifications = $user->notifications()->where('created_at', '>=', time() - $this->expTimeNewNotification)->selectRaw('*, CAST(created_at AS UNSIGNED) AS created_at_number')->orderByDesc('created_at_number')->limit($this->limitNewNotification)->get();
            return $notifications;
        }
        return null;
    }

    public function seenNotifiWithId(string|Notification|null $notification)
    {
        try {
            if (gettype($notification) == 'string') {
                $notification = Notification::find($notification);
            }
            if ($this->isNotificationForUser(auth()->user(), $notification)) {
                if (Notification::where('id', $notification->id)->update(['status' => 'seen']))
                    return true;
            }
        } catch (\Exception $exception) {}
        return false;
    }

    public function deleteNotifiWithId(string|Notification|null $notification)
    {
        try {
            if (gettype($notification) == 'string') {
                $notification = Notification::find($notification);
            }
            if ($this->isNotificationForUser(auth()->user(), $notification)) {
                if (Notification::where('id', $notification->id)->update(['status' => 'deleted']))
                    return true;
            }
        } catch (\Exception $exception) {}
        return false;
    }
}
