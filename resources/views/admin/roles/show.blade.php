@extends('layouts.admin')

@section('title', 'Role Details: ' . $role->name)

@section('content')
  <div class="container-fluid">
    <div class="row mb-4">
      <div class="col-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active">Details</li>
          </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
          <h1 class="h3 mb-0">Role Details: {{ $role->name }}</h1>
          <div class="btn-group" role="group">
            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">
              <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Back
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="card shadow mb-4">
          <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-info-circle"></i> Basic Information</h6>
          </div>
          <div class="card-body">
            <table class="table table-borderless">
              <tr>
                <th width="150">ID:</th>
                <td>{{ $role->id }}</td>
              </tr>
              <tr>
                <th>Name:</th>
                <td>
                  <span class="badge bg-primary">{{ $role->name }}</span>
                  @if($role->is_system)
                    <span class="badge bg-warning ms-2">System Role</span>
                  @endif
                </td>
              </tr>
              <tr>
                <th>Created:</th>
                <td>{{ $role->created_at->format('M d, Y H:i') }}</td>
              </tr>
              <tr>
                <th>Updated:</th>
                <td>{{ $role->updated_at->format('M d, Y H:i') }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow mb-4">
          <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-users"></i> Users with this Role ({{ $role->users->count() }})</h6>
          </div>
          <div class="card-body">
            @if($role->users->count() > 0)
              <div class="list-group">
                @foreach($role->users as $user)
                  <a href="{{ route('admin.users.show', $user) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                      <h6 class="mb-1">{{ $user->name }}</h6>
                      <small>{{ $user->email }}</small>
                    </div>
                  </a>
                @endforeach
              </div>
            @else
              <p class="text-muted mb-0">No users assigned to this role.</p>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card shadow">
          <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="fas fa-key"></i> Permissions ({{ $role->permissions->count() }})</h6>
          </div>
          <div class="card-body">
            @if($role->permissions->count() > 0)
              <div class="row">
                @foreach($role->permissions->chunk(4) as $chunk)
                  @foreach($chunk as $permission)
                    <div class="col-md-3 mb-2">
                      <span class="badge bg-success p-2 d-block">
                        {{ $permission->name }}
                      </span>
                    </div>
                  @endforeach
                @endforeach
              </div>
            @else
              <p class="text-muted mb-0">No permissions assigned to this role.</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection