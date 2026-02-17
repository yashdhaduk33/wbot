{{-- resources/views/admin/tickets/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Ticket #' . ($ticket->ticket_number ?? ''))

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">Edit Ticket #{{ $ticket->ticket_number }}</h1>
    <p class="text-muted mb-0">Update ticket details</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $ticket->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $ticket->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
                    <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                        <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="technical" {{ old('category', $ticket->category) == 'technical' ? 'selected' : '' }}>Technical</option>
                        <option value="billing" {{ old('category', $ticket->category) == 'billing' ? 'selected' : '' }}>Billing</option>
                        <option value="general" {{ old('category', $ticket->category) == 'general' ? 'selected' : '' }}>General</option>
                        <option value="feature_request" {{ old('category', $ticket->category) == 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                        <option value="bug" {{ old('category', $ticket->category) == 'bug' ? 'selected' : '' }}>Bug Report</option>
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Department</label>
                    <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                        <option value="">Select Department</option>
                        @foreach($departments ?? [] as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $ticket->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Assign To</label>
                    <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                        <option value="">Select User</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $ticket->assigned_to) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->role_name ?? 'No Role' }})
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Due Date</label>
                <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $ticket->due_date?->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}">
                @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Ticket</button>
                <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-light border">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection