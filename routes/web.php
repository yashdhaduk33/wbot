<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WhatsAppBotController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    }
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

// Admin login page
Route::get('/admin/login', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.admin-login');
})->name('admin.login')->middleware('guest');

// Admin panel (auth + admin role required)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Notifications routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/recent', [App\Http\Controllers\NotificationController::class, 'getRecent'])->name('recent');
        Route::post('/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/clear/all', [App\Http\Controllers\NotificationController::class, 'clearAll'])->name('clear-all');
    });

    // ==================== TICKET MANAGEMENT ====================
    // IMPORTANT: All custom routes MUST come BEFORE the resource route

    // Custom dashboard route
    Route::get('tickets/dashboard', [TicketController::class, 'dashboard'])
        ->name('tickets.dashboard');

    // Custom search orders route (moved before resource to avoid conflict)
    Route::get('tickets/search-orders', [TicketController::class, 'searchOrders'])
        ->name('tickets.search-orders');  // ✅ Correct name – will become admin.tickets.search-orders

    // Download attachment route
    Route::get('attachments/{attachment}/download', [TicketController::class, 'downloadAttachment'])
        ->name('tickets.attachments.download');

    // Resource route for tickets (creates index, create, store, show, edit, update, destroy)
    Route::resource('tickets', TicketController::class);

    // Additional POST routes (these come after resource but are fine because they use specific paths)
    Route::post('tickets/{ticket}/comments', [TicketController::class, 'addComment'])
        ->name('tickets.comments');

    Route::post('tickets/{ticket}/status', [TicketController::class, 'changeStatus'])
        ->name('tickets.status');

    Route::post('tickets/{ticket}/assign', [TicketController::class, 'assign'])
        ->name('tickets.assign');

    // ==================== BOT MANAGEMENT ====================
    Route::resource('bots', WhatsAppBotController::class);

    // ==================== USER MANAGEMENT ====================
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // ==================== ROLE MANAGEMENT ====================
    Route::resource('roles', RoleController::class);

    // ==================== DEPARTMENT MANAGEMENT ====================
    Route::resource('departments', DepartmentController::class);

    // ==================== SETTINGS ====================
    Route::get('settings', [AdminController::class, 'settings'])->name('settings');
});