<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppBot;
use Illuminate\Http\Request;

class WhatsAppBotController extends Controller
{
    public function index()
    {
        $bots = WhatsAppBot::latest()->paginate(10);
        return view('admin.bots.index', compact('bots'));
    }

    public function create()
    {
        return view('admin.bots.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'api_token' => 'nullable|string|max:500',
            'webhook_verify_token' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        WhatsAppBot::create($validated);

        return redirect()->route('admin.bots.index')->with('status', 'WhatsApp bot created successfully.');
    }

    public function show(WhatsAppBot $bot)
    {
        return view('admin.bots.show', compact('bot'));
    }

    public function edit(WhatsAppBot $bot)
    {
        return view('admin.bots.edit', compact('bot'));
    }

    public function update(Request $request, WhatsAppBot $bot)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'api_token' => 'nullable|string|max:500',
            'webhook_verify_token' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $bot->update($validated);

        return redirect()->route('admin.bots.index')->with('status', 'WhatsApp bot updated successfully.');
    }

    public function destroy(WhatsAppBot $bot)
    {
        $bot->delete();
        return redirect()->route('admin.bots.index')->with('status', 'WhatsApp bot deleted.');
    }
}
