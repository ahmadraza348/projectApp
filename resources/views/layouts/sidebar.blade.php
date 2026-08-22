<aside class="sidebar">
  <div class="brand"><i class="bi bi-kanban fs-5"></i> <span>Project Manager</span></div>
  <nav class="nav flex-column pt-2">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <a class="nav-link {{ request()->routeIs('project.*') ? 'active' : '' }}" href="{{ route('project.index') }}"><i class="bi bi-folder2-open"></i> Projects</a>
    <a class="nav-link {{ request()->routeIs('task.*') ? 'active' : '' }}" href="{{ route('task.index') }}"><i class="bi bi-list-task"></i> Tasks</a>
    <a class="nav-link {{ request()->routeIs('report.*') ? 'active' : '' }}" href="{{ route('report.index') }}"><i class="bi bi-bar-chart"></i> Reports</a>

    <div class="nav-section-title">Administration</div>
    <a class="nav-link {{ request()->routeIs('user.index') ? 'active' : '' }}" href="{{ route('user.index') }}"><i class="bi bi-people"></i> Users</a>
    <a class="nav-link {{ request()->routeIs('category.*') ? 'active' : '' }}" href="{{ route('category.index') }}"><i class="bi bi-tags"></i> Categories</a>

    <div class="nav-section-title">Account</div>
    <a class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}"><i class="bi bi-person-circle"></i> Profile</a>
    <a class="nav-link" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </nav>
</aside>