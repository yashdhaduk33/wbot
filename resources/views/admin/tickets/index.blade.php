{{-- resources/views/admin/tickets/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Ticket Management')

@section('content')
  <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between">
    <div>
      <h1 class="h4 fw-bold mb-1">Ticket Management</h1>
      <p class="text-muted mb-0">Manage and track all support tickets</p>
    </div>
    <div>
      <a href="{{ route('admin.tickets.dashboard') }}" class="btn btn-outline-primary me-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" class="me-1">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Dashboard
      </a>
      <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" class="me-1">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Ticket
      </a>
    </div>
  </div>

  {{-- Filter Card --}}
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
      <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-3">
        <div class="col-md-3">
          <label class="form-label small fw-semibold">Search</label>
          <input type="text" name="search" class="form-control" placeholder="Ticket #, Title, Description"
            value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
          <label class="form-label small fw-semibold">Status</label>
          <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            <option value="reopened" {{ request('status') == 'reopened' ? 'selected' : '' }}>Reopened</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small fw-semibold">Priority</label>
          <select name="priority" class="form-select">
            <option value="">All Priority</option>
            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small fw-semibold">Assigned To</label>
          <select name="assigned_to" class="form-select">
            <option value="">All Users</option>
            @foreach($users ?? [] as $user)
              <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small fw-semibold">Department</label>
          <select name="department_id" class="form-select">
            <option value="">All Departments</option>
            @foreach($departments ?? [] as $dept)
              <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                {{ $dept->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Tickets Table --}}
  <div class="card border-0 shadow-sm">
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
              <th class="py-3">Created By</th>
              <th class="py-3">Created</th>
              <th class="py-3 text-end pe-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tickets ?? [] as $ticket)
              <tr>
                <td class="px-4">
                  <a href="{{ route('admin.tickets.show', $ticket) }}"
                    class="text-primary fw-semibold text-decoration-none">
                    {{ $ticket->ticket_number }}
                  </a>
                </td>
                <td>
                  <div class="fw-semibold">{{ Str::limit($ticket->title, 40) }}</div>
                  <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $ticket->category)) }}</small>
                </td>
                <td>{!! $ticket->priority_badge !!}</td>
                <td>{!! $ticket->status_badge !!}</td>
                <td>
                  @if($ticket->assignedTo)
                    <div class="d-flex align-items-center">
                      <span
                        class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-2"
                        style="width: 28px; height: 28px; font-size: 12px;">
                        {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                      </span>
                      {{ $ticket->assignedTo->name }}
                    </div>
                  @else
                    <span class="text-muted">Unassigned</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <span
                      class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-2"
                      style="width: 28px; height: 28px; font-size: 12px;">
                      {{ strtoupper(substr($ticket->creator->name ?? 'U', 0, 1)) }}
                    </span>
                    {{ $ticket->creator->name ?? 'Unknown' }}
                  </div>
                </td>
                <td>
                  <div>{{ $ticket->created_at->format('M d, Y') }}</div>
                  <small class="text-muted">{{ $ticket->created_at->format('h:i A') }}</small>
                </td>
                <td class="text-end pe-4">
                  <div class="dropdown">
                    <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                      Actions <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="{{ route('admin.tickets.show', $ticket) }}">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" class="me-2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                          View Details
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="{{ route('admin.tickets.edit', $ticket) }}">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" class="me-2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                          Edit Ticket
                        </a>
                      </li>
                      @if(!in_array($ticket->status, ['resolved', 'closed']))
                        <li>
                          <a class="dropdown-item" href="#" data-bs-toggle="modal"
                            data-bs-target="#resolveModal{{ $ticket->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                              stroke="currentColor" class="me-2">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Mark Resolved
                          </a>
                        </li>
                      @endif
                      <li>
                        <hr class="dropdown-divider">
                      </li>
                      <li>
                        <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="dropdown-item text-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                              stroke="currentColor" class="me-2">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                          </button>
                        </form>
                      </li>
                    </ul>
                  </div>

                  {{-- Resolve Modal --}}
                  @if(!in_array($ticket->status, ['resolved', 'closed']))
                    <div class="modal fade" id="resolveModal{{ $ticket->id }}" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="resolved">
                            <div class="modal-header">
                              <h5 class="modal-title">Resolve Ticket #{{ $ticket->ticket_number }}</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-start">
                              <div class="mb-3">
                                <label class="form-label fw-semibold">Resolution Notes</label>
                                <textarea name="resolution_notes" class="form-control" rows="3" required
                                  placeholder="Describe how this ticket was resolved..."></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-success">Resolve Ticket</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center py-5">
                  <div class="text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor" class="mb-3">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h5>No tickets found</h5>
                    <p class="mb-3">Get started by creating your first ticket</p>
                    <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary">
                      Create New Ticket
                    </a>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if(isset($tickets) && $tickets->hasPages())
      <div class="card-footer bg-white border-0 py-3">
        {{ $tickets->withQueryString()->links() }}
      </div>
    @endif
  </div>
@endsection