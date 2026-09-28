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
            
            $url = $notification->data['url'] ?? route('backend_dashboard', [], false);
            
            // Chuyển thành relative path để luôn ở lại domain hiện tại mà user đang truy cập (dautuhanoi...)
            $parsed = parse_url($url);
            $target = ($parsed['path'] ?? '/backend') . (isset($parsed['query']) ? '?' . $parsed['query'] : '');
            
            return redirect($target);
        }
        
        return redirect()->back();
    }

    public function readAll()
    {
        auth('web')->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Đã đánh dấu đọc tất cả thông báo.');
    }
}
