<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     * Đã tắt log đăng nhập theo yêu cầu.
     */
    public function handle(Login $event): void
    {
        // Không log hành động đăng nhập
    }
}
