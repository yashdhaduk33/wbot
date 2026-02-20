@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">Users Management</h1>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New User
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">All Roles</option>
                            @if(isset($roles) && $roles)
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="department" class="form-select">
                            <option value="">All Departments</option>
                            @if(isset($departments) && $departments)
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                                    class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                            @else
                                                <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                    style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ substr($user->name, 0, 2) }}
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                <div class="small text-muted">{{ $user->position ?? 'No position' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role)
                                            <span class="badge bg-primary">{{ $user->role->name }}</span>
                                            @if(!$user->role->is_active)
                                                <span class="badge bg-warning">Inactive Role</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">No role</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->department)
                                            <span class="badge bg-info">{{ $user->department->name }}</span>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning">Unverified</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->last_login_at)
                                            <span class="small" title="{{ $user->last_login_at->format('Y-m-d H:i:s') }}">
                                                {{ $user->last_login_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info"
                                                title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                    <path
                                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning"
                                                title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                    <path
                                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                                </svg>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete({{ $user->id }})" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                        <path fill-rule="evenodd"
                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                    </svg>
                                                </button>
                                                <form id="delete-form-{{ $user->id }}"
                                                    action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <p>No users found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($users, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar-placeholder {
            background: linear-gradient(45deg, #6c5ce7, #a29bfe);
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                document.getElementById('delete-form-' + userId).submit();
            }
        }
    </script>
@endpush