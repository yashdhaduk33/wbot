@extends('layouts.admin')

@section('title', 'WhatsApp Bots')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">WhatsApp Bot Manager</h1>
    <a href="{{ route('admin.bots.create') }}" class="btn btn-primary">Add Bot</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($bots->isEmpty())
            <div class="text-center py-5 text-muted">
                <p class="mb-2">No WhatsApp bots yet.</p>
                <a href="{{ route('admin.bots.create') }}" class="btn btn-primary">Create your first bot</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bots as $bot)
                            <tr>
                                <td>
                                    <strong>{{ $bot->name }}</strong>
                                    @if($bot->description)
                                        <br><small class="text-muted">{{ Str::limit($bot->description, 40) }}</small>
                                    @endif
                                </td>
                                <td>{{ $bot->phone_number ?? '—' }}</td>
                                <td>
                                    @if($bot->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td><small>{{ $bot->updated_at->format('M j, Y') }}</small></td>
                                <td>
                                    <a href="{{ route('admin.bots.show', $bot) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    <a href="{{ route('admin.bots.edit', $bot) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.bots.destroy', $bot) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this bot?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-0">
                {{ $bots->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
