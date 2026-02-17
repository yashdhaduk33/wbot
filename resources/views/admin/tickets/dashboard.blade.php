{{-- resources/views/admin/tickets/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Ticket Dashboard')

@section('content')
  <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between">
    <div>
      <h1 class="h4 fw-bold mb-1">Ticket Dashboard</h1>
      <p class="text-muted mb-0">Overview of your ticket system</p>
    </div>
    <div>
      <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" class="me-1">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Ticket
      </a>
    </div>
  </div>

  {{-- Stats Cards --}}
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-2">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" class="text-primary">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <div>
              <div class="fw-bold fs-4">{{ $stats['total'] ?? 0 }}</div>
              <div class="text-muted small">Total Tickets</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-2">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" class="text-success">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <div class="fw-bold fs-4">{{ $stats['open'] ?? 0 }}</div>
              <div class="text-muted small">Open</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-2">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="rounded-3 bg-info bg-opacity-10 p-3 me-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" class="text-info">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <div class="fw-bold fs-4">{{ $stats['in_progress'] ?? 0 }}</div>
              <div class="text-muted small">In Progress</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-2">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="rounded-3 bg-warning bg-opacity-10 p-3 me-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" class="text-warning">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <div class="fw-bold fs-4">{{ $stats['resolved'] ?? 0 }}</div>
              <div class="text-muted small">Resolved</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-2">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="rounded-3 bg-danger bg-opacity-10 p-3 me-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" class="text-danger">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div>
              <div class="fw-bold fs-4">{{ $stats['urgent'] ?? 0 }}</div>
              <div class="text-muted small">Urgent</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-2">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="rounded-3 bg-secondary bg-opacity-10 p-3 me-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" class="text-secondary">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <div class="fw-bold fs-4">{{ $stats['my_tickets'] ?? 0 }}</div>
              <div class="text-muted small">My Tickets</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    {{-- Priority Distribution --}}
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="mb-0 fw-semibold">Tickets by Priority</h5>
        </div>
        <div class="card-body">
          @php
            $priorityColors = [
              'low' => 'bg-info',
              'medium' => 'bg-warning',
              'high' => 'bg-orange',
              'urgent' => 'bg-danger'
            ];
            $totalTickets = $stats['total'] ?? 1;
          @endphp
          @forelse($priorityDistribution ?? [] as $priority)
            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-capitalize">{{ $priority->priority }}</span>
                <span class="fw-semibold">{{ $priority->total }}</span>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar {{ $priorityColors[$priority->priority] ?? 'bg-secondary' }}" role="progressbar"
                  style="width: {{ ($priority->total / $totalTickets) * 100 }}%">
                </div>
              </div>
            </div>
          @empty
            <p class="text-muted text-center py-3">No ticket data available</p>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Status Distribution --}}
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="mb-0 fw-semibold">Tickets by Status</h5>
        </div>
        <div class="card-body">
          @php
            $statusColors = [
              'open' => 'bg-success',
              'in_progress' => 'bg-info',
              'resolved' => 'bg-warning',
              'closed' => 'bg-secondary',
              'reopened' => 'bg-primary'
            ];
            $totalTickets = $stats['total'] ?? 1;
          @endphp
          @forelse($statusDistribution ?? [] as $status)
            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-capitalize">{{ str_replace('_', ' ', $status->status) }}</span>
                <span class="fw-semibold">{{ $status->total }}</span>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar {{ $statusColors[$status->status] ?? 'bg-secondary' }}" role="progressbar"
                  style="width: {{ ($status->total / $totalTickets) * 100 }}%">
                </div>
              </div>
            </div>
          @empty
            <p class="text-muted text-center py-3">No ticket data available</p>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Recent Tickets --}}
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-semibold">Recent Tickets</h5>
          <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-light">View All</a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="px-4 py-3">Ticket #</th>
                  <th class="py-3">Title</th>
                  <th class="py-3">Priority</th>
                  <th class="py-3">Status</th>
                  <th class="py-3">Assigned To</th>
                  <th class="py-3">Created</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentTickets ?? [] as $ticket)
                  <tr>
                    <td class="px-4">
                      <a href="{{ route('admin.tickets.show', $ticket) }}"
                        class="text-primary fw-semibold text-decoration-none">
                        {{ $ticket->ticket_number }}
                      </a>
                    </td>
                    <td>{{ Str::limit($ticket->title, 50) }}</td>
                    <td>{!! $ticket->priority_badge !!}</td>
                    <td>{!! $ticket->status_badge !!}</td>
                    <td>
                      @if($ticket->assignedTo)
                        <div class="d-flex align-items-center">
                          <span
                            class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-2"
                            style="width: 24px; height: 24px; font-size: 12px;">
                            {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                          </span>
                          {{ $ticket->assignedTo->name }}
                        </div>
                      @else
                        <span class="text-muted">Unassigned</span>
                      @endif
                    </td>
                    <td>{{ $ticket->created_at->diffForHumans() }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                      No tickets found
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection