<aside class="sidebar">
  <div class="brand d-flex align-items-center justify-content-between">
    <span><i class="bi bi-kanban fs-5"></i> <span>Project Manager</span></span>
    @auth
    <div class="dropdown">
      <button class="btn btn-link text-white position-relative p-0" data-bs-toggle="dropdown" aria-label="Notifications">
        <i class="bi bi-bell fs-5"></i>
        <span id="notification-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ auth()->user()->unreadNotifications()->count() ? '' : 'd-none' }}">{{ auth()->user()->unreadNotifications()->count() }}</span>
      </button>
      <div class="dropdown-menu dropdown-menu-end p-0 shadow" style="min-width: 320px;">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
          <strong>Recent notifications</strong>
          <small class="text-muted">Live updates</small>
        </div>
        <div id="notification-list" class="notification-list">
          @forelse(auth()->user()->notifications()->latest()->limit(10)->get() as $notification)
            <a href="{{ $notification->data['url'] ?? '#' }}" class="dropdown-item text-wrap {{ $notification->read_at ? '' : 'fw-semibold' }}" data-notification-id="{{ $notification->id }}">
              {{ $notification->data['message'] ?? 'New activity' }}<small class="d-block text-muted">{{ $notification->created_at->diffForHumans() }}</small>
            </a>
          @empty
            <span class="dropdown-item text-muted">No notifications yet.</span>
          @endforelse
        </div>
      </div>
    </div>
    @endauth
  </div>
  <nav class="nav flex-column pt-2">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    @php $role = auth()->user()->role; @endphp

    @if(in_array($role, ['admin', 'manager']))
    <a class="nav-link {{ request()->routeIs('project.*') ? 'active' : '' }}" href="{{ route('project.index') }}"><i class="bi bi-folder2-open"></i> Projects</a>
    @endif
    <a class="nav-link {{ request()->routeIs('task.*') ? 'active' : '' }}" href="{{ route('task.index') }}"><i class="bi bi-list-task"></i> Tasks</a>
    @if($role === 'admin')
    <a class="nav-link {{ request()->routeIs('report.*') ? 'active' : '' }}" href="{{ route('report.index') }}"><i class="bi bi-bar-chart"></i> Reports</a>
    @endif

    @if($role === 'admin')
    <div class="nav-section-title">Administration</div>
    <a class="nav-link {{ request()->routeIs('user.index') ? 'active' : '' }}" href="{{ route('user.index') }}"><i class="bi bi-people"></i> Users</a>
    <a class="nav-link {{ request()->routeIs('category.*') ? 'active' : '' }}" href="{{ route('category.index') }}"><i class="bi bi-tags"></i> Categories</a>
    <a class="nav-link {{ request()->routeIs('department.*') ? 'active' : '' }}" href="{{ route('department.index') }}"><i class="bi bi-diagram-3"></i> Departments</a>
    <a class="nav-link {{ request()->routeIs('client.*') ? 'active' : '' }}" href="{{ route('client.index') }}"><i class="bi bi-person-vcard"></i> Clients</a>
    <a class="nav-link {{ request()->routeIs('expence.*') ? 'active' : '' }}" href="{{ route('expence.index') }}"><i class="bi bi-receipt"></i> Expenses</a>
    @endif

    <div class="nav-section-title">Account</div>
    <a class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}"><i class="bi bi-person-circle"></i> Profile</a>
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
      @csrf
      <button type="submit" class="nav-link border-0 bg-transparent text-start w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
    </form>
  </nav>
</aside>