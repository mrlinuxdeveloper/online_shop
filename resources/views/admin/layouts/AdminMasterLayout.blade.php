<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Panel')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f5f6fa;
        }

        .admin-wrapper {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #212529;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
        }

        .sidebar-brand {
            padding: 20px;
            font-size: 22px;
            font-weight: bold;
            border-bottom: 1px solid #343a40;
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: block;
            padding: 12px 20px;
            color: #adb5bd;
            text-decoration: none;
            transition: 0.2s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: #343a40;
            color: white;
        }

        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            min-height: 100vh;
        }

        .admin-navbar {
            height: 65px;
            background: white;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }

        .content {
            padding: 25px;
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="admin-wrapper">

    {{-- Sidebar --}}
    <aside class="sidebar">

        <div class="sidebar-brand">
            Admin Panel
        </div>

        <ul class="sidebar-menu">

            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('admin.categories.index') }}"
                   class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    Categories
                </a>
            </li>

            <li>
                <a href="{{ route('admin.brands.index') }}" class="nav-link" >
                    Brands
                </a>
            </li>

            <li>
                <a
                    href="{{ route('admin.products.index') }}"
                    class="nav-link"
                >
                    Products
                </a>
                <a href="{{ route('admin.sizes.index') }}"
                   class="nav-link">
                    Sizes
                </a>
                <a href="{{ route('admin.colors.index') }}"
                   class="nav-link">
                    Colors
                </a>
            </li>

            <li>
                <a href="{{ route('admin.orders.index') }}"
                   class="nav-link">
                    Orders
                </a>
            </li>

            <li>
                <a href="#">
                    Users
                </a>
            </li>

        </ul>

    </aside>


    {{-- Main --}}
    <main class="main-content">

        {{-- Navbar --}}
        <nav class="admin-navbar">

            <div>
                <strong>
                    @yield('page-title', 'Dashboard')
                </strong>
            </div>

            <div class="d-flex align-items-center gap-3">

                <span>
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-sm btn-outline-danger">
                        Logout
                    </button>
                </form>

            </div>

        </nav>


        {{-- Page Content --}}
        <div class="content">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </div>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
