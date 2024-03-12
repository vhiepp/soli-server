<?php

namespace App\Http\Controllers;

use App\Services\Notifications\NotificationService;
use Illuminate\Http\Request;

class NotifiController extends Controller
{
    protected NotificationService $notifiService;

    public function __construct()
    {
        $this->notifiService = new NotificationService();
    }

    public function getAllNotification(Request $request)
    {
        try {
            $notifications = $this->notifiService->getAllNotifiListForUser(auth()->user());
            return response()->json(reshelper()->withFormat($notifications));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getNewNotification(Request $request)
    {
        try {
            $notifications = $this->notifiService->getNewNotifiListForUser(auth()->user());
            return response()->json(reshelper()->withFormat($notifications));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getOldNotification(Request $request)
    {
        try {
            $notifications = $this->notifiService->getOldNotifiListForUser(auth()->user());
            return response()->json(reshelper()->withFormat($notifications));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function seenNotificationWithId(Request $request)
    {
        try {
            $notifications = $this->notifiService->seenNotifiWithId($request->notification_id);
            if ($notifications) {
                return response()->json(reshelper()->withFormat(null, 'Seen notification success'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function deleteNotificationWithId(Request $request)
    {
        try {
            $notifications = $this->notifiService->deleteNotifiWithId($request->notification_id);
            if ($notifications) {
                return response()->json(reshelper()->withFormat(null, 'Delete notification success'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }
}
