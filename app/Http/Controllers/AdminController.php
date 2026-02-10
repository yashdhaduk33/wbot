<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WhatsAppBot;
use App\Models\Role;
use App\Models\Department;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_bots' => WhatsAppBot::count(),
            'total_roles' => Role::count(),
            'total_departments' => Department::count(),
            'active_bots' => WhatsAppBot::where('is_active', true)->count(),
        ];

        $recentUsers = User::with(['role', 'department'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }

    public function settings()
    {
        return view('admin.settings');
    }
}