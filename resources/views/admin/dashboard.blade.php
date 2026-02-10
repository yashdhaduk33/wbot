@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">Dashboard</h1>
    <p class="text-muted mb-0">Welcome back, {{ Auth::user()->email }}</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#22c55e"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold fs-4">{{ $stats['active_bots'] ?? 0 }}</div>
                    <div class="text-muted small">Active Bots</div>
                    <div class="text-success small mt-1">+{{ $stats['bots_added_week'] ?? 0 }} added this week</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#3b82f6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold fs-4">{{ $stats['messages_sent'] ?? '0' }}</div>
                    <div class="text-muted small">Messages Sent</div>
                    <div class="text-primary small mt-1">Last 30 days</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-info bg-opacity-10 p-3 me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#0ea5e9"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold fs-4">{{ $stats['connected_users'] ?? 0 }}</div>
                    <div class="text-muted small">Connected Users</div>
                    <div class="text-info small mt-1">Unique conversations</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-warning bg-opacity-10 p-3 me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#eab308"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold fs-4">{{ $stats['uptime'] ?? '99.9%' }}</div>
                    <div class="text-muted small">Uptime</div>
                    <div class="text-warning small mt-1">Last 90 days</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-semibold">Recent Activity</h5>
    </div>
    <div class="card-body pt-0">
        <ul class="list-unstyled mb-0">
            @forelse($recentActivity ?? [] as $activity)
                <li class="d-flex align-items-start py-3 border-bottom border-light">
                    <span class="rounded-circle flex-shrink-0 mt-1 me-3" style="width:10px;height:10px;background:{{ $activity['color'] ?? '#22c55e' }}"></span>
                    <span class="flex-grow-1">{{ $activity['message'] }}</span>
                    <span class="text-muted small flex-shrink-0">{{ $activity['time'] }}</span>
                </li>
            @empty
                <li class="d-flex align-items-start py-3 border-bottom border-light">
                    <span class="rounded-circle flex-shrink-0 mt-1 me-3" style="width:10px;height:10px;background:#22c55e"></span>
                    <span class="flex-grow-1">No recent activity yet.</span>
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
