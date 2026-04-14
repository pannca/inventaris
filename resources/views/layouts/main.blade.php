<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Wikrama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f4f7fe;
            color: #334155;
        }

        :root {
            --primary-color: #2b43cc;
            --primary-hover: #1e32a1;
            --primary-light: #e8edff;
        }

        .btn-theme {
            background-color: var(--primary-color);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-theme:hover {
            background-color: var(--primary-hover);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(43, 67, 204, 0.2);
        }

        .text-theme {
            color: var(--primary-color) !important;
        }

        .bg-theme-light {
            background-color: var(--primary-light) !important;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #2b43cc;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .sidebar .nav-link i {
            width: 30px;
        }

        .sidebar .nav-link.dropdown-toggle::after {
            margin-left: auto;
        }

        .sidebar .collapse-link {
            padding-left: 48px;
            font-size: 0.95rem;
        }

        .offcanvas-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .offcanvas-sidebar .nav-link:hover,
        .offcanvas-sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .offcanvas-sidebar .nav-link i {
            width: 30px;
        }

        .offcanvas-sidebar .collapse-link {
            padding-left: 48px;
            font-size: 0.95rem;
        }

        .main-wrapper {
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        .topbar {
            background: white;
            padding: 15px 30px;
        }

        @media (max-width: 991.98px) {
            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }

            .topbar {
                padding: 12px 16px;
            }
        }
    </style>
</head>

<body>

    {{-- Offcanvas Sidebar untuk Mobile --}}
    <div class="offcanvas offcanvas-start offcanvas-sidebar text-white p-3" tabindex="-1" id="mobileSidebar"
        style="background-color:#2b43cc; width:250px;">
        <div class="offcanvas-header pb-2">
            <h5 class="fw-bold mb-0">SMK WIKRAMA</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body px-2 pt-0">
            <div class="nav flex-column">
                <small class="text-white-50 mb-2">Menu</small>
                <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('staff.dashboard') }}"
                    class="nav-link {{ request()->is('*/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <small class="text-white-50 mt-3 mb-2">Items Data</small>
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-link {{ request()->is('*/categories*') ? 'active' : '' }}">
                        <i class="fas fa-list"></i> Categories
                    </a>
                    <a href="{{ route('admin.items.index') }}"
                        class="nav-link {{ request()->is('*/items*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> Items
                    </a>
                @elseif (Auth::user()->role == 'staff')
                    <a href="{{ route('staff.items.index') }}"
                        class="nav-link {{ request()->is('*/items*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> Items
                    </a>
                    <a href="{{ route('staff.lendings.index') }}"
                        class="nav-link {{ request()->is('*/lendings*') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding"></i> Lending
                    </a>
                @endif
                <small class="text-white-50 mt-3 mb-2">Accounts</small>
                @if (Auth::user()->role == 'admin')
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="collapse"
                            data-bs-target="#usersCollapseMobile" aria-expanded="false">
                            <i class="fas fa-users"></i> Users
                        </a>
                        <div class="collapse" id="usersCollapseMobile">
                            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
                                class="nav-link collapse-link {{ request()->is('*/users*') && request('role') == 'admin' ? 'active' : '' }}">
                                <i class="fas fa-user-tie"></i> Admin
                            </a>
                            <a href="{{ route('admin.users.index', ['role' => 'staff']) }}"
                                class="nav-link collapse-link {{ request()->is('*/users*') && request('role') == 'staff' ? 'active' : '' }}">
                                <i class="fas fa-user"></i> Operator
                            </a>
                        </div>
                    </div>
                @elseif (Auth::user()->role == 'staff')
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="collapse"
                            data-bs-target="#usersCollapseMobileStaff" aria-expanded="false">
                            <i class="fas fa-users"></i> Users
                        </a>
                        <div class="collapse" id="usersCollapseMobileStaff">
                            <a href="{{ route('staff.users.index') }}"
                                class="nav-link collapse-link {{ request()->is('*/users*') ? 'active' : '' }}">
                                <i class="fas fa-user"></i> Edit
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="d-flex">
        {{-- Sidebar Desktop --}}
        <div class="sidebar p-3 shadow d-none d-lg-block">
            <div class="text-center mb-4">
                <h5 class="fw-bold">SMK WIKRAMA</h5>
            </div>
            <div class="nav flex-column">
                <small class="text-white-50 mb-2">Menu</small>
                <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('staff.dashboard') }}"
                    class="nav-link {{ request()->is('*/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <small class="text-white-50 mt-3 mb-2">Items Data</small>
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-link {{ request()->is('*/categories*') ? 'active' : '' }}">
                        <i class="fas fa-list"></i> Categories
                    </a>
                    <a href="{{ route('admin.items.index') }}"
                        class="nav-link {{ request()->is('*/items*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> Items
                    </a>
                @elseif (Auth::user()->role == 'staff')
                    <a href="{{ route('staff.items.index') }}"
                        class="nav-link {{ request()->is('*/items*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> Items
                    </a>
                    <a href="{{ route('staff.lendings.index') }}"
                        class="nav-link {{ request()->is('*/lendings*') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding"></i> Lending
                    </a>
                @endif
                <small class="text-white-50 mt-3 mb-2">Accounts</small>
                @if (Auth::user()->role == 'admin')
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="collapse"
                            data-bs-target="#usersCollapse" aria-expanded="false">
                            <i class="fas fa-users"></i> Users
                        </a>
                        <div class="collapse" id="usersCollapse">
                            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
                                class="nav-link collapse-link {{ request()->is('*/users*') && request('role') == 'admin' ? 'active' : '' }}">
                                <i class="fas fa-user-tie"></i> Admin
                            </a>
                            <a href="{{ route('admin.users.index', ['role' => 'staff']) }}"
                                class="nav-link collapse-link {{ request()->is('*/users*') && request('role') == 'staff' ? 'active' : '' }}">
                                <i class="fas fa-user"></i> Operator
                            </a>
                        </div>
                    </div>
                @elseif (Auth::user()->role == 'staff')
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="collapse"
                            data-bs-target="#usersCollapseStaff" aria-expanded="false">
                            <i class="fas fa-users"></i> Users
                        </a>
                        <div class="collapse" id="usersCollapseStaff">
                            <a href="{{ route('staff.users.index') }}"
                                class="nav-link collapse-link {{ request()->is('*/users*') ? 'active' : '' }}">
                                <i class="fas fa-user"></i> Edit
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="main-wrapper">
            <div class="topbar d-flex justify-content-between align-items-center shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    {{-- Tombol hamburger hanya muncul di mobile --}}
                    <button class="btn d-lg-none p-0" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                        <i class="fas fa-bars fs-5"></i>
                    </button>
                    <h6 class="mb-0 fw-bold">Welcome Back, {{ Auth::user()->name }}</h6>
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle border-0 bg-transparent" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="p-3 p-md-4">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
