@extends('layouts.admin')

@section('title', $bot->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.bots.index') }}" class="btn btn-outline-secondary btn-sm">← Back to Bots</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">{{ $bot->name }}</h5>
        <div>
            @if($bot->is_active)
                <span class="badge bg-success">Active</span>
            @else
                <span class="badge bg-secondary">Inactive</span>
            @endif
            <a href="{{ route('admin.bots.edit', $bot) }}" class="btn btn-sm btn-primary ms-2">Edit</a>
        </div>
    </div>
    <div class="card-body">
        @if($bot->description)
            <p class="text-muted">{{ $bot->description }}</p>
        @endif
        <dl class="row mb-0">
            <dt class="col-sm-3">Phone Number</dt>
            <dd class="col-sm-9">{{ $bot->phone_number ?? '—' }}</dd>

            <dt class="col-sm-3">API Token</dt>
            <dd class="col-sm-9">{{ $bot->api_token ? '••••••••' : '—' }}</dd>

            <dt class="col-sm-3">Webhook Verify Token</dt>
            <dd class="col-sm-9">{{ $bot->webhook_verify_token ? '••••••••' : '—' }}</dd>

            <dt class="col-sm-3">Created</dt>
            <dd class="col-sm-9">{{ $bot->created_at->format('M j, Y H:i') }}</dd>

            <dt class="col-sm-3">Updated</dt>
            <dd class="col-sm-9">{{ $bot->updated_at->format('M j, Y H:i') }}</dd>
        </dl>
    </div>
</div>
@endsection
