<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestNotificationController extends Controller
{
    public function read($id)
    {
        $guest = auth('guest')->user();
        if ($guest) {
            $notification = $guest->notifications()->where('id', $id)->first();
            if ($notification) {
                $notification->markAsRead();
                $url = $notification->data['url'] ?? route('guest.dashboard');
                return redirect($url);
            }
        }
        return redirect()->back();
    }
    
    public function markAllAsRead()
    {
        $guest = auth('guest')->user();
        if ($guest) {
            $guest->unreadNotifications->markAsRead();
        }
        return redirect()->back();
    }
}
