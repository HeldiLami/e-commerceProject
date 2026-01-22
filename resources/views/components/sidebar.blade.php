<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <h2 class="sidebar-title">Menu</h2>

    <div class="menu-toggle-icon" id="toggle-menu" style="cursor: pointer;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </div>
  </div>

  <div class="sidebar-body">

    <nav class="nav-container">
      <p class="nav-label">Dashboard</p>

      <a href="{{ route('admin.users') }}" class="nav-item {{ request()->is('admin/users') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
        </svg>
        <span class="nav-text">Users</span>
      </a>

      <a href="{{ route('admin.statistics') }}" class="nav-item {{ request()->is('admin/statistics') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="12" y1="20" x2="12" y2="10"></line>
          <line x1="18" y1="20" x2="18" y2="4"></line>
          <line x1="6" y1="20" x2="6" y2="16"></line>
        </svg>
        <span class="nav-text">Sales</span>
      </a>

      <a href="{{ route('admin.products.create') }}" class="nav-item {{ request()->is('admin/products/create') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 8l-9-5-9 5 9 5 9-5z"></path>
          <path d="M3 10v10l9 5 9-5V10"></path>
          <path d="M12 15v9"></path>
        </svg>
        <span class="nav-text">Products</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <form action="{{ route('logout') }}" method="POST" class="logout-form">
        @csrf
        <button
          type="submit"
          class="nav-item logout-button"
          style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;"
        >
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          <span class="nav-text">Sign out</span>
        </button>
      </form>
    </div>
  </div>
</aside>