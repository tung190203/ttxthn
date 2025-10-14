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
        $permissions = $user->getAllPermissionsFromGroup();
        $permissions = array_values(array_filter($permissions, fn($p) => $p !== 'backend_access'));
        if (empty($permissions)) {
            return redirect()->route('backend_dashboard');
        }
        $firstPermission = $permissions[0];
        $routeName = 'backend_' . str_replace('/', '_', $firstPermission);
        if (app('router')->has($routeName)) {
            return redirect()->route($routeName);
        }

        return redirect()->route('backend_dashboard');
    }
}
