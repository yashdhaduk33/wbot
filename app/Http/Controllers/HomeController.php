<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WhatsAppBot;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $activeBots = WhatsAppBot::where('is_active', true)->count();
        $totalBots = WhatsAppBot::count();
        $weekAgo = now()->subWeek();

        $stats = [
            'bots' => $totalBots,
            'active_bots' => $activeBots,
            'bots_added_week' => WhatsAppBot::where('created_at', '>=', $weekAgo)->count(),
            'messages_sent' => '0',
            'connected_users' => User::count(),
            'uptime' => '99.9%',
            'trend_bots' => $totalBots > 0 ? '+12%' : '0%',
            'trend_messages' => '+8%',
            'trend_users' => '+23%',
            'trend_uptime' => '+0.1%',
        ];

        $recentActivity = $this->getRecentActivity();

        return view('home', compact('stats', 'recentActivity'));
    }

    protected function getRecentActivity(): array
    {
        $bots = WhatsAppBot::where('is_active', true)->latest()->take(4)->get();
        $activity = [];

        foreach ($bots as $i => $bot) {
            $activity[] = [
                'message' => "Bot '{$bot->name}' is active",
                'time' => $bot->updated_at->diffForHumans(),
                'color' => ['#22c55e', '#3b82f6', '#f59e0b', '#0ea5e9'][$i % 4],
            ];
        }

        if (empty($activity)) {
            $activity[] = [
                'message' => 'Welcome to WA Bot Manager.',
                'time' => 'Just now',
                'color' => '#22c55e',
            ];
        }

        return $activity;
    }
}
