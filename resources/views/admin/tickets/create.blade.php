{{-- resources/views/admin/tickets/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Create New Ticket')

@section('content')
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1">Create New Ticket</h1>
        <p class="text-muted mb-0">Fill in the details to create a support ticket</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="order_id" class="form-label fw-semibold">Search Mobile Number</label>
                            <select name="order_id" id="order_id" class="form-select" style="width: 100%;">
                                @if(isset($ticket) && $ticket->order)
                                    <option value="{{ $ticket->order->id }}" selected>{{ $ticket->order->customerNumber }} (ID:
                                        {{ $ticket->order->id }})
                                    </option>
                                @endif
                            </select>
                            <small class="text-muted">Search by order ID or mobile number</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" required placeholder="Brief summary of the issue">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="5" required
                                placeholder="Detailed description of the issue...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
                                <select name="priority" class="form-select @error('priority') is-invalid @enderror"
                                    required>
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror"
                                    required>
                                    <option value="">Select Category</option>
                                    <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical
                                    </option>
                                    <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Billing
                                    </option>
                                    <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General
                                    </option>
                                    <option value="feature_request" {{ old('category') == 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                                    <option value="bug" {{ old('category') == 'bug' ? 'selected' : '' }}>Bug Report</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Department</label>
                                <select name="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror">
                                    <option value="">Select Department</option>
                                    @foreach($departments ?? [] as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
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
                                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
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
                            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                                value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Attachments</label>
                            <input type="file" name="attachments[]"
                                class="form-control @error('attachments.*') is-invalid @enderror" multiple
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.txt">
                            <small class="text-muted">Max file size: 10MB. Allowed: JPG, PNG, PDF, DOC, DOCX, XLS, XLSX,
                                TXT</small>
                            @error('attachments.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" class="me-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Create Ticket
                            </button>
                            <a href="{{ route('admin.tickets.index') }}" class="btn btn-light border">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">Tips for creating tickets</h5>
                    <ul class="list-unstyled">
                        <li class="d-flex mb-3">
                            <span class="text-primary me-2">•</span>
                            <span>Be specific and detailed in your description</span>
                        </li>
                        <li class="d-flex mb-3">
                            <span class="text-primary me-2">•</span>
                            <span>Include steps to reproduce if reporting a bug</span>
                        </li>
                        <li class="d-flex mb-3">
                            <span class="text-primary me-2">•</span>
                            <span>Attach relevant screenshots or files</span>
                        </li>
                        <li class="d-flex mb-3">
                            <span class="text-primary me-2">•</span>
                            <span>Select the correct priority and category</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery and Select2 in your layout's <head> or before closing body -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#order_id').select2({
                placeholder: 'Search for an order...',
                ajax: {
                    url: '{{ route("admin.tickets.search-orders") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                let text = item.customerNumber + ' (ID: ' + item.id + ')';
                                if (item.date) {
                                    // Format the date as needed (e.g., YYYY-MM-DD)
                                    let dateStr = new Date(item.date).toLocaleDateString(); // or item.date directly
                                    text += ' - Date: ' + dateStr;
                                }
                                if (item.mobile) text += ' - Mobile: ' + item.mobile;
                                if (item.phone) text += ' - Phone: ' + item.phone;
                                return { id: item.id, text: text };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });
        });
    </script>
@endsection