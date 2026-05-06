<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    /**
     * Handle the event.
     * Đã tắt log đăng xuất theo yêu cầu.
     */
    public function handle(Logout $event): void
    {
        // Không log hành động đăng xuất
    }
}
