@extends('layouts.admin')

@section('title', 'Edit WhatsApp Bot')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.bots.index') }}" class="btn btn-outline-secondary btn-sm">← Back to Bots</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Edit: {{ $bot->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.bots.update', $bot) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $bot->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $bot->phone_number) }}">
                @error('phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="api_token" class="form-label">API Token</label>
                <input type="text" class="form-control @error('api_token') is-invalid @enderror" id="api_token" name="api_token" value="{{ old('api_token', $bot->api_token) }}">
                @error('api_token')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="webhook_verify_token" class="form-label">Webhook Verify Token</label>
                <input type="text" class="form-control @error('webhook_verify_token') is-invalid @enderror" id="webhook_verify_token" name="webhook_verify_token" value="{{ old('webhook_verify_token', $bot->webhook_verify_token) }}">
                @error('webhook_verify_token')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description', $bot->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $bot->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Update Bot</button>
            <a href="{{ route('admin.bots.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
