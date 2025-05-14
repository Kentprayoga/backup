<style>
    #accordionSidebar {
        background-color: #1f1770 !important;
        /* Menambahkan !important untuk memastikan gaya diterapkan */
    }
    </style>
    
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon bg-w" style="width: 40px; height: 40px; overflow: hidden; border-radius: 50%;">
            <img src="{{ asset('log.jpg') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div class="sidebar-brand-text mx-3">AVENTREX</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('pages.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('template.index') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Documents</span></a>
    </li>
    <hr class="sidebar-divider">
    <!-- Nav Item - User -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('user.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <!-- Nav Item - Approvals -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('approvals.index')}}">
            <i class="fas fa-fw fa-check-circle"></i>
            <span>Approvals</span></a>
    </li>
    <hr class="sidebar-divider">
    <!-- Nav Item - Laporan -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('history.index')}}">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Laporan</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.chat') }}">
            <i class="fas fa-fw fa-comments"></i>
            <span>Chat Admin</span>
        </a>
    </li>
    <!-- Heading -->



    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button id="logoutButton" type="submit" class="nav-link" style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                <span>Logout</span>
            </button>
        </form>
    </li>
    

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>


