<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $user = auth('web')->user();
        if($user->status_approve != 'approved') {
            auth('web')->logout();
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đang chờ phê duyệt hoặc bị từ chối. Vui lòng liên hệ quản trị viên.');
        }
        $permissions = $user->getAllPermissionsFromGroup();
        $permissions = array_values(array_filter($permissions, fn($p) => $p !== 'backend_access'));
        if (empty($permissions)) {
            return redirect()->route('backend_home');
        }
        $firstPermission = $permissions[0];
        $routeName = 'backend_' . str_replace('/', '_', $firstPermission);
        if (app('router')->has($routeName)) {
            return redirect()->route($routeName);
        }

        return redirect()->route('backend_home');
    }
}
