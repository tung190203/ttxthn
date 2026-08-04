<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function read($id)
    {
        $notification = auth('web')->user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            
            $url = $notification->data['url'] ?? route('backend_dashboard');
            return redirect($url);
        }
        
        return redirect()->back();
    }

    public function readAll()
    {
        auth('web')->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Đã đánh dấu đọc tất cả thông báo.');
    }
}
