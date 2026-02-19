@extends('layouts.admin')

@section('title', 'User Details: ' . $user->name)

@section('content')
  <div class="container-fluid">
    <div class="row mb-4">
      <div class="col-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">Details</li>
          </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
          <h1 class="h3 mb-0">User Details: {{ $user->name }}</h1>
          <div class="btn-group" role="group">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
              <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Back
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="card shadow mb-4">
          <div class="card-body text-center">
            @if($user->avatar)
              <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle mb-3"
                width="150" height="150" style="object-fit: cover;">
            @else
              <div
                class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                style="width: 150px; height: 150px; font-size: 50px;">
                {{ substr($user->name, 0, 2) }}
              </div>
            @endif

            <h4 class="mb-1">{{ $user->name }}</h4>
            <p class="text-muted mb-2">{{ $user->email }}</p>

            <div class="mb-3">
              <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
              </span>
              @if($user->email_verified_at)
                <span class="badge bg-success">Verified</span>
              @else
                <span class="badge bg-warning">Unverified</span>
              @endif
            </div>

            @if($user->department)
              <div class="mb-3">
                <span class="badge bg-info">{{ $user->department->name }}</span>
                @if($user->department->head_id == $user->id)
                  <span class="badge bg-primary">Head of Department</span>
                @endif
              </div>
            @endif
          </div>
        </div>

        <div class="card shadow mb-4">
          <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-id-card"></i> Contact Information</h6>
          </div>
          <div class="card-body">
            <ul class="list-unstyled">
              @if($user->phone)
                <li class="mb-2">
                  <i class="fas fa-phone me-2"></i>
                  {{ $user->phone }}
                </li>
              @endif
              <li class="mb-2">
                <i class="fas fa-envelope me-2"></i>
                {{ $user->email }}
              </li>
              @if($user->position)
                <li class="mb-2">
                  <i class="fas fa-briefcase me-2"></i>
                  {{ $user->position }}
                </li>
              @endif
              <li class="mb-2">
                <i class="fas fa-calendar me-2"></i>
                Joined: {{ $user->created_at->format('M d, Y') }}
              </li>
              @if($user->last_login_at)
                <li class="mb-2">
                  <i class="fas fa-sign-in-alt me-2"></i>
                  Last Login: {{ $user->last_login_at->diffForHumans() }}
                </li>
              @endif
            </ul>
          </div>
        </div>

        <div class="card shadow">
          <div class="card-header bg-warning">
            <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Account Statistics</h6>
          </div>
          <div class="card-body">
            <div class="row text-center">
              <div class="col-6 mb-3">
                <div class="border rounded p-2">
                  <h5 class="mb-0">{{ $user->login_count ?? 0 }}</h5>
                  <small class="text-muted">Logins</small>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div class="border rounded p-2">
                  <h5 class="mb-0">{{ $user->role ? 1 : 0 }}</h5>
                  <small class="text-muted">Role</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card shadow mb-4">
          <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-key"></i> Role & Permissions</h6>
          </div>
          <div class="card-body">
            <h6 class="mb-3">Assigned Role</h6>
            <div class="mb-4">
              @if($user->role)
                <span class="badge bg-primary me-2 mb-2" style="font-size: 0.9rem; padding: 0.5rem 0.8rem;">
                  {{ $user->role->name }}
                </span>
                @if(!$user->role->is_active)
                  <span class="badge bg-warning">Inactive Role</span>
                @endif
              @else
                <p class="text-muted mb-0">No role assigned</p>
              @endif
            </div>

            <h6 class="mb-3">Permissions</h6>
            @if($user->role && !empty($user->role->permissions))
              @php
                $permissions = is_array($user->role->permissions) ? $user->role->permissions : json_decode($user->role->permissions, true);
              @endphp
              @if(count($permissions) > 0)
                <div class="row">
                  @foreach(array_chunk($permissions, 3) as $chunk)
                    @foreach($chunk as $permission)
                      <div class="col-md-4 mb-2">
                        <span class="badge bg-success p-2 d-block">
                          {{ $permission }}
                        </span>
                      </div>
                    @endforeach
                  @endforeach
                </div>
              @else
                <p class="text-muted mb-0">No permissions assigned to this role</p>
              @endif
            @else
              <p class="text-muted mb-0">No permissions assigned</p>
            @endif
          </div>
        </div>

        <div class="card shadow mb-4">
          <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="fas fa-building"></i> Department Information</h6>
          </div>
          <div class="card-body">
            @if($user->department)
              <div class="row">
                <div class="col-md-6">
                  <h6>Department Details</h6>
                  <table class="table table-sm">
                    <tr>
                      <th>Department:</th>
                      <td>{{ $user->department->name }}</td>
                    </tr>
                    @if($user->department->head)
                      <tr>
                        <th>Head:</th>
                        <td>{{ $user->department->head->name }}</td>
                      </tr>
                    @endif
                    <tr>
                      <th>Status:</th>
                      <td>
                        <span class="badge bg-{{ $user->department->is_active ? 'success' : 'danger' }}">
                          {{ $user->department->is_active ? 'Active' : 'Inactive' }}
                        </span>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-6">
                  <h6>Contact Information</h6>
                  <table class="table table-sm">
                    @if($user->department->email)
                      <tr>
                        <th>Email:</th>
                        <td>{{ $user->department->email }}</td>
                      </tr>
                    @endif
                    @if($user->department->phone)
                      <tr>
                        <th>Phone:</th>
                        <td>{{ $user->department->phone }}</td>
                      </tr>
                    @endif
                  </table>
                </div>
              </div>
            @else
              <p class="text-muted mb-0">User is not assigned to any department.</p>
            @endif
          </div>
        </div>

        <div class="card shadow">
          <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-history"></i> Recent Activity</h6>
          </div>
          <div class="card-body">
            @if($user->activities && $user->activities->count() > 0)
              <div class="list-group">
                @foreach($user->activities->take(5) as $activity)
                  <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                      <h6 class="mb-1">{{ $activity->description }}</h6>
                      <small>{{ $activity->created_at->diffForHumans() }}</small>
                    </div>
                    @if($activity->properties)
                      <p class="mb-1 small text-muted">
                        {{ json_encode($activity->properties) }}
                      </p>
                    @endif
                  </div>
                @endforeach
              </div>
              @if($user->activities->count() > 5)
                <div class="text-center mt-3">
                  <a href="#" class="btn btn-sm btn-outline-primary">
                    View All Activities
                  </a>
                </div>
              @endif
            @else
              <p class="text-muted mb-0">No recent activity found.</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .avatar-placeholder {
      background: linear-gradient(45deg, #6c5ce7, #a29bfe);
    }
  </style>
@endpush