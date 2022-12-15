<?php

namespace App\Http\Controllers;

use App\Events\NotifyNotificationsRead;
use App\Events\NotifyUser;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UserIdRequest;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function store(StoreNotificationRequest $request): JsonResponse
	{
		$validated = $request->validated();
		if ($validated['sender_id'] != $validated['reciever_id'])
		{
			$notification = Notification::create($validated);
			NotifyUser::dispatch($notification);
		}
		return response()->json($notification, 201);
	}

	public function showUserNotifications(UserIdRequest $request): JsonResponse
	{
		$notifications = Notification::where('reciever_id', $request->user_id)->get()->reverse()->values();
		$notifications->load('sender');
		return response()->json($notifications);
	}

	public function markAllAsRead(UserIdRequest $request)
	{
		$notifications = Notification::where('reciever_id', '=', $request->user_id)->update(['read_status' => 0]);
		NotifyNotificationsRead::dispatch($request->user_id);

		return response()->json($notifications);
	}
}
