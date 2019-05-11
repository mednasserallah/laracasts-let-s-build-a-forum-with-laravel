<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function destroy(User $user, $notificationId)
    {
        \Auth::user()->notifications()->findOrFail($notificationId)->markAsRead();
    }

    public function index()
    {
        return \Auth::user()->unreadNotifications;
    }
}
