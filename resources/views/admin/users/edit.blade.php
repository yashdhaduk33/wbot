@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->name)

@section('content')
  <div class="container-fluid">
    <div class="row mb-4">
      <div class="col-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </nav>
        <h1 class="h3 mb-0">Edit User: {{ $user->name }}</h1>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <div class="card shadow">
          <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                      value="{{ old('name', $user->name) }}" required>
                    @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                      value="{{ old('email', $user->email) }}" required>
                    @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                      name="password">
                    @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Leave blank to keep current password</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                      value="{{ old('phone', $user->phone) }}">
                    @error('phone')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="position" class="form-label">Position</label>
                    <input type="text" class="form-control @error('position') is-invalid @enderror" id="position"
                      name="position" value="{{ old('position', $user->position) }}">
                    @error('position')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="department_id" class="form-label">Department</label>
                    <select class="form-select @error('department_id') is-invalid @enderror" id="department_id"
                      name="department_id">
                      <option value="">Select Department</option>
                      @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ (old('department_id') ?? $user->department_id) == $department->id ? 'selected' : '' }}>
                          {{ $department->name }}
                        </option>
                      @endforeach
                    </select>
                    @error('department_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="avatar" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar"
                      name="avatar" accept="image/*">
                    @error('avatar')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Current:
                      @if($user->avatar)
                        <a href="{{ asset('storage/' . $user->avatar) }}" target="_blank">View</a>
                      @else
                        None
                      @endif
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="role_id" class="form-label">Role *</label>
                <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                  <option value="">Select Role</option>
                  @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ (old('role_id') ?? $user->role_id) == $role->id ? 'selected' : '' }}>
                      {{ $role->name }}
                      @if(!$role->is_active)
                        (Inactive)
                      @endif
                    </option>
                  @endforeach
                </select>
                @error('role_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                    {{ (old('is_active') ?? $user->is_active) ? 'checked' : '' }}>
                  <label class="form-check-label" for="is_active">Active User</label>
                </div>
              </div>

              <!-- <div class="mb-3">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="email_verified" name="email_verified"
                    value="1" {{ (old('email_verified') ?? $user->email_verified_at) ? 'checked' : '' }}>
                  <label class="form-check-label" for="email_verified">Email Verified</label>
                </div>
              </div> -->

              <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Update User
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-info-circle"></i> User Information</h6>
          </div>
          <div class="card-body">
            <div class="text-center mb-3">
              @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle"
                  width="100" height="100" style="object-fit: cover;">
              @else
                <div
                  class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                  style="width: 100px; height: 100px; font-size: 30px;">
                  {{ substr($user->name, 0, 2) }}
                </div>
              @endif
            </div>

            <ul class="list-unstyled">
              <li class="mb-2">
                <strong>ID:</strong> {{ $user->id }}
              </li>
              <li class="mb-2">
                <strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}
              </li>
              <li class="mb-2">
                <strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y') }}
              </li>
              <li class="mb-2">
                <strong>Last Login:</strong>
                @if($user->last_login_at)
                  {{ $user->last_login_at->diffForHumans() }}
                @else
                  <span class="text-muted">Never</span>
                @endif
              </li>
              <li class="mb-2">
                <strong>Status:</strong>
                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                  {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
              </li>
            </ul>
          </div>
        </div>

        <div class="card shadow mt-4">
          <div class="card-header bg-warning">
            <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h6>
          </div>
          <div class="card-body">
            @if($user->id !== auth()->id())
              <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-100"
                  onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                  <i class="fas fa-trash"></i> Delete User
                </button>
              </form>
            @else
              <button class="btn btn-danger w-100" disabled>
                <i class="fas fa-exclamation-circle"></i> Cannot delete yourself
              </button>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection