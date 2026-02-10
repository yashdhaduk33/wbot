@extends('admin.layout')

@section('title', 'Departments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Departments</h1>
    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">Create Department</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                <tr>
                    <td>{{ $department->id }}</td>
                    <td>{{ $department->name }}</td>
                    <td>{{ $department->code }}</td>
                    <td>
                        <span class="badge bg-{{ $department->is_active ? 'success' : 'danger' }}">
                            {{ $department->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $department->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('admin.departments.show', $department) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $departments->links() }}
    </div>
</div>
@endsection