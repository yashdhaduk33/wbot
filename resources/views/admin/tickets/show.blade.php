{{-- resources/views/admin/tickets/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Ticket #' . ($ticket->ticket_number ?? ''))

@section('content')
  <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between">
    <div>
      <h1 class="h4 fw-bold mb-1">Ticket #{{ $ticket->ticket_number }}</h1>
      <p class="text-muted mb-0">Created {{ $ticket->created_at->diffForHumans() }}</p>
    </div>
    <div>
      <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary me-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" class="me-1">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to List
      </a>
      <a href="{{ route('admin.tickets.edit', $ticket) }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" class="me-1">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        Edit Ticket
      </a>
    </div>
  </div>

  <div class="row">
    {{-- Main Content --}}
    <div class="col-lg-8">
      {{-- Ticket Details Card --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
              <h5 class="card-title fw-semibold mb-1">{{ $ticket->title }}</h5>
              <div class="d-flex gap-2 mt-2">
                {!! $ticket->priority_badge !!}
                {!! $ticket->status_badge !!}
                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                  {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                </span>
              </div>
            </div>
            @if($ticket->due_date)
              <div class="text-end">
                <small class="text-muted">Due Date</small>
                <div
                  class="fw-semibold @if($ticket->due_date->isPast() && !in_array($ticket->status, ['resolved', 'closed'])) text-danger @endif">
                  {{ $ticket->due_date->format('M d, Y') }}
                </div>
              </div>
            @endif
          </div>

          <div class="mb-4">
            <h6 class="fw-semibold mb-2">Description</h6>
            <div class="p-3 bg-light rounded">
              {{ nl2br(e($ticket->description)) }}
            </div>
          </div>

          @if($ticket->resolution_notes)
            <div class="mb-4">
              <h6 class="fw-semibold mb-2">Resolution Notes</h6>
              <div class="p-3 bg-success bg-opacity-10 rounded text-success">
                {{ nl2br(e($ticket->resolution_notes)) }}
              </div>
            </div>
          @endif

          @if($ticket->attachments->count() > 0)
            <div>
              <h6 class="fw-semibold mb-2">Attachments ({{ $ticket->attachments->count() }})</h6>
              <div class="row g-2">
                @foreach($ticket->attachments as $attachment)
                  <div class="col-md-6">
                    <div class="d-flex align-items-center p-2 border rounded">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" class="me-2 text-muted">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                      </svg>
                      <div class="flex-grow-1 text-truncate">
                        <a href="{{ route('admin.tickets.attachments.download', $attachment) }}" class="text-decoration-none">
                          {{ $attachment->original_filename }}
                        </a>
                        <br>
                        <small class="text-muted">{{ number_format($attachment->file_size / 1024, 1) }} KB</small>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </div>

      {{-- Comments Section --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="mb-0 fw-semibold">Comments ({{ $ticket->comments->count() }})</h5>
        </div>
        <div class="card-body">
          @forelse($ticket->comments as $comment)
            <div class="d-flex mb-4" id="comment-{{ $comment->id }}">
              <div class="flex-shrink-0 me-3">
                <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center"
                  style="width: 40px; height: 40px;">
                  {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                </div>
              </div>
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <div>
                    <span class="fw-semibold">{{ $comment->user->name ?? 'Unknown' }}</span>
                    @if($comment->is_internal)
                      <span class="badge bg-warning bg-opacity-10 text-warning ms-2">Internal Note</span>
                    @endif
                  </div>
                  <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
                <div class="mb-2">
                  {{ nl2br(e($comment->comment)) }}
                </div>
                @if($comment->attachments->count() > 0)
                  <div class="d-flex flex-wrap gap-2 mt-2">
                    @foreach($comment->attachments as $attachment)
                      <a href="{{ route('admin.tickets.attachments.download', $attachment) }}" class="text-decoration-none">
                        <span class="badge bg-light text-dark p-2 border">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" class="me-1">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                          </svg>
                          {{ $attachment->original_filename }}
                        </span>
                      </a>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>
          @empty
            <p class="text-muted text-center py-3">No comments yet. Be the first to comment!</p>
          @endforelse

          {{-- Add Comment Form --}}
          <form action="{{ route('admin.tickets.comments', $ticket) }}" method="POST" enctype="multipart/form-data"
            class="mt-4">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold">Add a comment</label>
              <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="3" required
                placeholder="Type your comment here..."></textarea>
              @error('comment')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <div class="form-check">
                <input type="checkbox" name="is_internal" class="form-check-input" id="isInternal" value="1">
                <label class="form-check-label" for="isInternal">
                  Internal note (only visible to staff)
                </label>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Attachments</label>
              <input type="file" name="attachments[]" class="form-control" multiple>
            </div>
            <button type="submit" class="btn btn-primary">
              Post Comment
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
      {{-- Assignment Card --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <h6 class="fw-semibold mb-3">Assignment</h6>

          <div class="mb-3">
            <label class="small text-muted mb-1">Created By</label>
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-2"
                style="width: 32px; height: 32px;">
                {{ strtoupper(substr($ticket->creator->name ?? 'U', 0, 1)) }}
              </div>
              <div>
                <div class="fw-semibold">{{ $ticket->creator->name ?? 'Unknown' }}</div>
                <small class="text-muted">{{ $ticket->creator->email ?? '' }}</small>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="small text-muted mb-1">Assigned To</label>
            @if($ticket->assignedTo)
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-2"
                    style="width: 32px; height: 32px;">
                    {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                  </div>
                  <div>
                    <div class="fw-semibold">{{ $ticket->assignedTo->name }}</div>
                    <small class="text-muted">{{ $ticket->assignedTo->email }}</small>
                  </div>
                </div>
              </div>
            @else
              <div class="text-muted mb-2">Unassigned</div>
            @endif

            {{-- Reassign Form --}}
            <form action="{{ route('admin.tickets.assign', $ticket) }}" method="POST" class="mt-2">
              @csrf
              <div class="input-group">
                <select name="assigned_to" class="form-select form-select-sm">
                  <option value="">Reassign to...</option>
                  @foreach($users ?? [] as $user)
                    <option value="{{ $user->id }}" {{ $ticket->assigned_to == $user->id ? 'selected' : '' }}>
                      {{ $user->name }}
                    </option>
                  @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
              </div>
            </form>
          </div>

          @if($ticket->department)
            <div>
              <label class="small text-muted mb-1">Department</label>
              <div>{{ $ticket->department->name }}</div>
            </div>
          @endif
        </div>
      </div>

      {{-- Status Actions Card --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <h6 class="fw-semibold mb-3">Update Status</h6>

          @if($ticket->status == 'open')
            <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST" class="mb-2">
              @csrf
              <input type="hidden" name="status" value="in_progress">
              <button type="submit" class="btn btn-info w-100">
                Start Progress
              </button>
            </form>
          @endif

          @if(!in_array($ticket->status, ['resolved', 'closed']))
            <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#resolveModal">
              Mark as Resolved
            </button>
          @endif

          @if($ticket->status == 'resolved')
            <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST" class="mb-2">
              @csrf
              <input type="hidden" name="status" value="closed">
              <button type="submit" class="btn btn-secondary w-100">
                Close Ticket
              </button>
            </form>
            <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST">
              @csrf
              <input type="hidden" name="status" value="reopened">
              <button type="submit" class="btn btn-warning w-100">
                Reopen Ticket
              </button>
            </form>
          @endif
        </div>
      </div>

      {{-- Activity Log Card --}}
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
          <h6 class="mb-0 fw-semibold">Activity Log</h6>
        </div>
        <div class="card-body p-0">
          <div class="list-group list-group-flush">
            @forelse($ticket->activities as $activity)
              <div class="list-group-item border-0 py-3">
                <div class="d-flex">
                  <span class="rounded-circle flex-shrink-0 mt-1 me-3"
                    style="width:8px;height:8px;background:#22c55e"></span>
                  <div class="flex-grow-1">
                    <div class="small">{{ $activity->details }}</div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                      <small class="text-muted">by {{ $activity->user->name ?? 'System' }}</small>
                      <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <div class="list-group-item border-0 py-3 text-center text-muted">
                No activity recorded
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Resolve Modal --}}
  <div class="modal fade" id="resolveModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST">
          @csrf
          <input type="hidden" name="status" value="resolved">
          <div class="modal-header">
            <h5 class="modal-title">Resolve Ticket #{{ $ticket->ticket_number }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
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
@endsection