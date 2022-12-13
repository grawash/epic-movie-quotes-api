<?php

namespace App\Http\Controllers;

use App\Events\NotifyUser;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UserIdRequest;
use App\Models\notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function store(StoreNotificationRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$notification = notification::create($validated);
		NotifyUser::dispatch($notification);
		return response()->json($notification, 201);
	}

	public function showUserNotifications(UserIdRequest $request): JsonResponse
	{
		$notifications = notification::where('reciever_id', $request->user_id)->get();
		$notifications->load('sender');

		return response()->json($notifications);
	}
}
