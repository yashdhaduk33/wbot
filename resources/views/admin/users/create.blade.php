@extends('layouts.admin')

@section('title', 'Create New User')

@section('content')
  <div class="container-fluid">
    <div class="row mb-4">
      <div class="col-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">Create</li>
          </ol>
        </nav>
        <h1 class="h3 mb-0">Create New User</h1>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <div class="card shadow">
          <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                      value="{{ old('name') }}" required>
                    @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                      value="{{ old('email') }}" required>
                    @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="password" class="form-label">Password *</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                      name="password" required>
                    @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                      required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                      value="{{ old('phone') }}">
                    @error('phone')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="position" class="form-label">Position</label>
                    <input type="text" class="form-control @error('position') is-invalid @enderror" id="position"
                      name="position" value="{{ old('position') }}">
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
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
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
                    <div class="form-text">Max file size: 2MB. Allowed: jpg, png, gif</div>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="role_id" class="form-label">Role *</label>
                <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                  <option value="">Select Role</option>
                  @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
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
                    checked>
                  <label class="form-check-label" for="is_active">Active User</label>
                </div>
              </div>

              <div class="mb-3">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="send_welcome_email"
                    name="send_welcome_email" value="1">
                  <label class="form-check-label" for="send_welcome_email">Send welcome email</label>
                </div>
              </div>

              <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Create User
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-info-circle"></i> Information</h6>
          </div>
          <div class="card-body">
            <p class="small text-muted">
              <strong>Password:</strong> Must be at least 8 characters long and include uppercase, lowercase, numbers, and
              symbols.
            </p>
            <p class="small text-muted">
              <strong>Roles:</strong> Select one or more roles for the user. Roles determine permissions.
            </p>
            <p class="small text-muted">
              <strong>Welcome Email:</strong> If enabled, the user will receive account creation notification.
            </p>
          </div>
        </div>

        <div class="card shadow mt-4">
          <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-image"></i> Avatar Preview</h6>
          </div>
          <div class="card-body text-center">
            <div id="avatarPreview" class="mb-3">
              <div
                class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                style="width: 120px; height: 120px; font-size: 40px;">
                <span id="avatarInitials">JD</span>
              </div>
            </div>
            <p class="small text-muted mb-0">Selected image will appear here</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Update avatar initials based on name input
      const nameInput = document.getElementById('name');
      const avatarInitials = document.getElementById('avatarInitials');

      nameInput.addEventListener('input', function () {
        const name = this.value.trim();
        if (name.length >= 2) {
          const words = name.split(' ');
          let initials = '';
          if (words.length >= 2) {
            initials = words[0].charAt(0) + words[words.length - 1].charAt(0);
          } else {
            initials = name.substring(0, 2);
          }
          avatarInitials.textContent = initials.toUpperCase();
        } else {
          avatarInitials.textContent = 'JD';
        }
      });

      // Preview avatar image
      const avatarInput = document.getElementById('avatar');
      const avatarPreview = document.getElementById('avatarPreview');

      avatarInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
            avatarPreview.innerHTML = `<img src="${e.target.result}" class="rounded-circle" width="120" height="120" style="object-fit: cover;">`;
          };
          reader.readAsDataURL(file);
        }
      });
    });
  </script>
@endpush