<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - WA Bot Manager</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <!-- Additional CSS for admin -->
    <style>
        .admin-wrap {
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 240px;
            background: #1a202c;
            color: #cbd5e0;
        }

        .admin-main {
            background: #f7fafc;
        }

        .sidebar-section {
            font-size: 0.75rem;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .nav-link {
            color: #cbd5e0;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0 0.5rem;
        }

        .nav-link:hover {
            background: #2d3748;
            color: #fff;
        }

        .nav-link.active {
            background: #3182ce;
            color: white;
        }

        .sidebar-user-avatar {
            width: 36px;
            height: 36px;
            background: #4a5568;
            color: white;
            font-weight: bold;
        }

        .table-actions {
            min-width: 150px;
        }

        /* Add these styles to your existing admin styles */
        .bg-orange {
            background-color: #f97316 !important;
        }

        .text-orange {
            color: #f97316 !important;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 0.25rem;
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        .list-group-item {
            transition: background-color 0.2s;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .dropdown-item svg {
            width: 16px;
            height: 16px;
        }

        .modal-content {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-size: 0.875rem;
            color: #4a5568;
        }

        .table th {
            font-weight: 600;
            font-size: 0.875rem;
            color: #4a5568;
            border-bottom-width: 1px;
        }

        .table td {
            font-size: 0.875rem;
            vertical-align: middle;
        }

        /* Notifications dropdown */
        .notifications-dropdown {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            animation: dropdownFade 0.2s ease;
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-item {
            transition: background-color 0.2s;
            color: #1e293b;
        }

        .notification-item:hover {
            background-color: #f8fafc;
        }

        .notification-item.unread {
            background-color: #f0f9ff;
        }

        .notification-item.unread:hover {
            background-color: #e0f2fe;
        }

        .notification-icon {
            transition: background-color 0.2s;
        }

        /* Ensure dropdown stays above other content */
        .dropdown-menu {
            z-index: 1050;
        }
    </style>
</head>

<body class="bg-light">
    <div class="d-flex min-vh-100 admin-wrap">
        <aside class="admin-sidebar d-flex flex-column">
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-brand d-flex align-items-center gap-2 p-3 text-decoration-none">
                <div class="sidebar-brand-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div class="sidebar-brand-text lh-sm">
                    <div class="brand-wa">WA Bot</div>
                    <div class="brand-manager">Manager</div>
                </div>
            </a>
            <nav class="sidebar-nav flex-grow-1 overflow-auto py-3">
                <div class="sidebar-section px-3 pb-2">Main</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.bots.*') ? 'active' : '' }}"
                            href="{{ route('admin.bots.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Bot Manager
                        </a>
                    </li>
                </ul>
                <div class="sidebar-section px-3 pt-3 pb-2">User Management</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                            href="{{ route('admin.roles.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Roles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}"
                            href="{{ route('admin.departments.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Departments
                        </a>
                    </li>
                </ul>
                {{-- Add this after User Management section and before System section --}}
                <div class="sidebar-section px-3 pt-3 pb-2">Ticket Management</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.tickets.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.tickets.dashboard') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Ticket Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.tickets.index') ? 'active' : '' }}"
                            href="{{ route('admin.tickets.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            All Tickets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.tickets.create') ? 'active' : '' }}"
                            href="{{ route('admin.tickets.create') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Create Ticket
                        </a>
                    </li>
                </ul>
                <div class="sidebar-section px-3 pt-3 pb-2">System</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.settings') ? 'active' : '' }}"
                            href="{{ route('admin.settings') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                    </li>
                </ul>
                <div class="sidebar-section px-3 pt-3 pb-2">Quick action</div>
                <a class="sidebar-add-btn d-flex align-items-center justify-content-center gap-2 mx-3 mb-3 py-2 text-decoration-none btn btn-primary"
                    href="{{ route('admin.bots.create') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Bot
                </a>
            </nav>
            <div class="sidebar-user d-flex align-items-center gap-2 p-3 mt-auto">
                <div class="sidebar-user-avatar d-flex align-items-center justify-content-center rounded">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="sidebar-user-info flex-grow-1 min-w-0">
                    <div class="sidebar-user-email text-truncate">{{ Auth::user()->email }}</div>
                    <div class="sidebar-user-role">
                        @if(Auth::user()->role)
                            {{ Auth::user()->role->name }}
                        @else
                            No Role
                        @endif
                    </div>
                </div>
                <a href="{{ route('logout') }}" class="sidebar-user-logout text-decoration-none p-1" title="Logout"
                    onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </a>
                <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </aside>
        <div class="admin-main d-flex flex-column flex-grow-1">
            <header class="admin-navbar navbar navbar-expand bg-white align-items-center justify-content-end px-3">
                <div class="d-flex gap-2 align-items-center">
                    <!-- View site button (unchanged) -->
                    <a href="{{ route('home') }}" class="admin-navbar-icon btn btn-light border" title="View site">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>

                    <!-- Notifications dropdown -->
                    <div class="dropdown" id="notificationsDropdown">
                        <button class="admin-navbar-icon btn btn-light border position-relative" type="button"
                            id="notificationBell" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                            aria-expanded="false" title="Notifications">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge"
                                style="font-size: 0.65rem; padding: 0.25rem 0.4rem; display: none;">
                                0
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end notifications-dropdown p-0"
                            style="width: 360px; max-width: 90vw;" aria-labelledby="notificationBell">
                            <!-- Header will be populated by JavaScript -->
                            <!-- Loading indicator -->
                            <div class="text-center py-4" id="notifications-loading">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 mb-0 small text-muted">Loading notifications...</p>
                            </div>
                            <!-- Notifications list container -->
                            <div id="notifications-list-container" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </header>
            <main class="admin-content p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('status'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Notifications dropdown logic
            const notificationDropdown = document.getElementById('notificationsDropdown');
            const bellButton = document.getElementById('notificationBell');
            const badge = document.querySelector('.notification-badge');
            const loadingEl = document.getElementById('notifications-loading');
            const listContainer = document.getElementById('notifications-list-container');

            // Function to fetch notifications and update UI
            function loadNotifications() {
                fetch('{{ route("admin.notifications.recent") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        // Update badge
                        const unreadCount = data.unread_count;
                        if (unreadCount > 0) {
                            badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                            badge.style.display = 'inline';
                        } else {
                            badge.style.display = 'none';
                        }

                        // Hide loading, show list container
                        loadingEl.style.display = 'none';
                        listContainer.style.display = 'block';

                        // Render notifications
                        renderNotifications(data.notifications);
                    })
                    .catch(error => {
                        console.error('Error loading notifications:', error);
                        loadingEl.innerHTML = '<p class="text-danger small">Failed to load notifications.</p>';
                    });
            }

            // Render notifications list
            function renderNotifications(notifications) {
                if (!notifications || notifications.length === 0) {
                    listContainer.innerHTML = `
                    <div class="dropdown-item text-center py-4 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="mb-2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="mb-0">No notifications</p>
                    </div>
                `;
                    return;
                }

                let html = `
                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                    <h6 class="mb-0 fw-semibold">Notifications</h6>
                    <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}" class="d-inline mark-all-read-form">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 text-decoration-none small" style="font-size: 0.8rem;">Mark all as read</button>
                    </form>
                </div>
                <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
            `;

                notifications.forEach(notif => {
                    const isUnread = !notif.read_at;
                    const iconColor = isUnread ? '#2563eb' : '#64748b';
                    const bgColor = isUnread ? '#e6f0ff' : '#f1f5f9';
                    const title = notif.data.title || 'Notification';
                    const message = notif.data.message || '';
                    const actionUrl = notif.data.action_url || '#';
                    const time = notif.created_at;

                    html += `
                    <a href="${actionUrl}" class="dropdown-item notification-item ${isUnread ? 'unread' : 'read'} px-3 py-2 border-bottom d-flex align-items-start gap-2 text-decoration-none" data-notification-id="${notif.id}">
                        <div class="flex-shrink-0">
                            <div class="notification-icon rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: ${bgColor};">
                                ${notif.data.icon ? `<i class="${notif.data.icon}" style="color: ${iconColor};"></i>` : `
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: ${iconColor};">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                `}
                            </div>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <p class="mb-0 small fw-semibold ${!isUnread ? 'text-muted' : ''}">${title}</p>
                                <small class="text-muted ms-2" style="font-size: 0.7rem;">${time}</small>
                            </div>
                            <p class="mb-0 small text-muted text-truncate">${message}</p>
                        </div>
                        ${isUnread ? '<span class="badge bg-primary rounded-circle p-1 ms-1" style="width: 8px; height: 8px;"></span>' : ''}
                    </a>
                `;
                });

                html += `
                </div>
                <div class="border-top text-center p-2">
                    <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none small">View all notifications</a>
                </div>
            `;

                listContainer.innerHTML = html;

                // Attach click handlers to mark single notification as read via AJAX
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.addEventListener('click', function (e) {
                        e.preventDefault();
                        const notifId = this.dataset.notificationId;
                        const href = this.getAttribute('href');

                        fetch(`/admin/notifications/${notifId}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                            .then(() => {
                                window.location.href = href;
                            })
                            .catch(() => {
                                window.location.href = href;
                            });
                    });
                });

                // Handle "Mark all as read" via AJAX
                const markAllForm = listContainer.querySelector('.mark-all-read-form');
                if (markAllForm) {
                    markAllForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => response.json())
                            .then(() => {
                                // Reload notifications to reflect changes
                                loadNotifications();
                                badge.style.display = 'none';
                            })
                            .catch(error => console.error('Error marking all as read:', error));
                    });
                }
            }

            // Load notifications when dropdown is shown
            bellButton.addEventListener('show.bs.dropdown', function () {
                loadNotifications();
            });

            // Periodically update the unread count
            function updateUnreadCount() {
                fetch('{{ route("admin.notifications.unread-count") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > 0) {
                            badge.textContent = data.count > 9 ? '9+' : data.count;
                            badge.style.display = 'inline';
                        } else {
                            badge.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error updating unread count:', error));
            }

            // Update count every 30 seconds
            setInterval(updateUnreadCount, 30000);

            // Initial count on page load
            updateUnreadCount();
        });
    </script>
</body>

</html>