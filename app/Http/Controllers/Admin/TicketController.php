<?php
// app/Http/Controllers/Admin/TicketController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Department;
use App\Models\TicketComment;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
  /**
   * Display a listing of tickets.
   */
  public function index(Request $request)
  {
    $query = Ticket::with(['creator', 'assignedTo', 'department']);

    // Apply filters
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }
    if ($request->filled('priority')) {
      $query->where('priority', $request->priority);
    }
    if ($request->filled('assigned_to')) {
      $query->where('assigned_to', $request->assigned_to);
    }
    if ($request->filled('department_id')) {
      $query->where('department_id', $request->department_id);
    }
    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('ticket_number', 'like', "%{$search}%")
          ->orWhere('title', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%");
      });
    }

    // Restrict non-admin users
    if (!auth()->user()->isAdmin()) {
      $query->where(function ($q) {
        $q->where('created_by', auth()->id())
          ->orWhere('assigned_to', auth()->id());
      });
    }

    $tickets = $query->latest()->paginate(15);
    $users = User::all();
    $departments = Department::where('is_active', true)->get();

    return view('admin.tickets.index', compact('tickets', 'users', 'departments'));
  }

  /**
   * Show the form for creating a new ticket.
   */
  public function create()
  {
    $users = User::all();
    $departments = Department::where('is_active', true)->get();

    return view('admin.tickets.create', compact('users', 'departments'));
  }

  /**
   * Store a newly created ticket.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'priority' => 'required|in:low,medium,high,urgent',
      'category' => 'required|in:technical,billing,general,feature_request,bug',
      'department_id' => 'nullable|exists:departments,id',
      'assigned_to' => 'nullable|exists:users,id',
      'due_date' => 'nullable|date',
      'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt'
    ]);

    DB::transaction(function () use ($validated, $request) {
      $ticket = Ticket::create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'priority' => $validated['priority'],
        'category' => $validated['category'],
        'department_id' => $validated['department_id'],
        'assigned_to' => $validated['assigned_to'],
        'due_date' => $validated['due_date'],
        'created_by' => auth()->id(),
        'status' => 'open'
      ]);

      // Log activity
      $ticket->activities()->create([
        'user_id' => auth()->id(),
        'action' => 'created',
        'details' => 'Ticket created',
        'ip_address' => $request->ip()
      ]);

      // Handle attachments
      if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
          $path = $file->store('tickets/' . $ticket->id, 'public');

          $ticket->attachments()->create([
            'user_id' => auth()->id(),
            'filename' => basename($path),
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
          ]);
        }
      }
    });

    return redirect()->route('admin.tickets.index')
      ->with('success', 'Ticket created successfully.');
  }

  /**
   * Display the specified ticket.
   */
  public function show(Ticket $ticket)
  {
    $ticket->load(['creator', 'assignedTo', 'department', 'comments.user', 'attachments', 'activities.user']);
    $users = User::all();

    return view('admin.tickets.show', compact('ticket', 'users'));
  }

  /**
   * Show the form for editing the specified ticket.
   */
  public function edit(Ticket $ticket)
  {
    $users = User::all();
    $departments = Department::where('is_active', true)->get();

    return view('admin.tickets.edit', compact('ticket', 'users', 'departments'));
  }

  /**
   * Update the specified ticket.
   */
  public function update(Request $request, Ticket $ticket)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'priority' => 'required|in:low,medium,high,urgent',
      'category' => 'required|in:technical,billing,general,feature_request,bug',
      'department_id' => 'nullable|exists:departments,id',
      'assigned_to' => 'nullable|exists:users,id',
      'due_date' => 'nullable|date'
    ]);

    DB::transaction(function () use ($validated, $ticket, $request) {
      $oldAssignedTo = $ticket->assigned_to;

      $ticket->update($validated);

      $ticket->activities()->create([
        'user_id' => auth()->id(),
        'action' => 'updated',
        'details' => 'Ticket details updated',
        'ip_address' => $request->ip()
      ]);
    });

    return redirect()->route('admin.tickets.show', $ticket)
      ->with('success', 'Ticket updated successfully.');
  }

  /**
   * Remove the specified ticket.
   */
  public function destroy(Ticket $ticket)
  {
    DB::transaction(function () use ($ticket) {
      foreach ($ticket->attachments as $attachment) {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
      }
      $ticket->delete();
    });

    return redirect()->route('admin.tickets.index')
      ->with('success', 'Ticket deleted successfully.');
  }

  /**
   * Add a comment to the ticket.
   */
  public function addComment(Request $request, Ticket $ticket)
  {
    $validated = $request->validate([
      'comment' => 'required|string',
      'is_internal' => 'boolean',
      'attachments.*' => 'nullable|file|max:10240'
    ]);

    DB::transaction(function () use ($validated, $ticket, $request) {
      $comment = $ticket->comments()->create([
        'user_id' => auth()->id(),
        'comment' => $validated['comment'],
        'is_internal' => $request->has('is_internal')
      ]);

      if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
          $path = $file->store('tickets/' . $ticket->id . '/comments', 'public');

          $ticket->attachments()->create([
            'user_id' => auth()->id(),
            'comment_id' => $comment->id,
            'filename' => basename($path),
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
          ]);
        }
      }

      $ticket->activities()->create([
        'user_id' => auth()->id(),
        'action' => 'commented',
        'details' => 'Added a comment' . ($comment->is_internal ? ' (internal)' : ''),
        'ip_address' => $request->ip()
      ]);
    });

    return redirect()->route('admin.tickets.show', $ticket . '#comments')
      ->with('success', 'Comment added successfully.');
  }

  /**
   * Change ticket status.
   */
  public function changeStatus(Request $request, Ticket $ticket)
  {
    $validated = $request->validate([
      'status' => 'required|in:open,in_progress,resolved,closed,reopened',
      'resolution_notes' => 'nullable|string|required_if:status,resolved,closed'
    ]);

    DB::transaction(function () use ($validated, $ticket, $request) {
      $oldStatus = $ticket->status;

      switch ($validated['status']) {
        case 'resolved':
          $ticket->resolve($validated['resolution_notes'] ?? null);
          break;
        case 'closed':
          $ticket->close($validated['resolution_notes'] ?? null);
          break;
        case 'reopened':
          $ticket->reopen();
          break;
        default:
          $ticket->status = $validated['status'];
          $ticket->save();

          $ticket->activities()->create([
            'user_id' => auth()->id(),
            'action' => 'status_changed',
            'details' => "Status changed from {$oldStatus} to {$validated['status']}",
            'ip_address' => $request->ip()
          ]);
      }
    });

    return redirect()->route('admin.tickets.show', $ticket)
      ->with('success', 'Ticket status updated successfully.');
  }

  /**
   * Assign ticket to a user.
   */
  public function assign(Request $request, Ticket $ticket)
  {
    $validated = $request->validate([
      'assigned_to' => 'required|exists:users,id'
    ]);

    DB::transaction(function () use ($validated, $ticket, $request) {
      $newAssignee = User::find($validated['assigned_to']);
      $ticket->assignTo($newAssignee);
    });

    return redirect()->route('admin.tickets.show', $ticket)
      ->with('success', 'Ticket assigned successfully.');
  }

  /**
   * Download attachment.
   */
  public function downloadAttachment(TicketAttachment $attachment)
  {
    if (!Storage::disk('public')->exists($attachment->file_path)) {
      abort(404);
    }

    return Storage::disk('public')->download(
      $attachment->file_path,
      $attachment->original_filename
    );
  }

  /**
   * Display ticket dashboard.
   */
  public function dashboard()
  {
    $stats = [
      'total' => Ticket::count(),
      'open' => Ticket::whereIn('status', ['open', 'reopened'])->count(),
      'in_progress' => Ticket::where('status', 'in_progress')->count(),
      'resolved' => Ticket::where('status', 'resolved')->count(),
      'closed' => Ticket::where('status', 'closed')->count(),
      'urgent' => Ticket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress', 'reopened'])->count(),
      'my_tickets' => Ticket::where('assigned_to', auth()->id())->whereIn('status', ['open', 'in_progress', 'reopened'])->count()
    ];

    $recentTickets = Ticket::with(['creator', 'assignedTo'])
      ->latest()
      ->limit(10)
      ->get();

    $priorityDistribution = Ticket::select('priority', DB::raw('count(*) as total'))
      ->groupBy('priority')
      ->get();

    $statusDistribution = Ticket::select('status', DB::raw('count(*) as total'))
      ->groupBy('status')
      ->get();

    return view('admin.tickets.dashboard', compact('stats', 'recentTickets', 'priorityDistribution', 'statusDistribution'));
  }
}