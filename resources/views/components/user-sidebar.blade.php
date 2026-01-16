<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2 class="sidebar-title">User Menu</h2>
        <div class="menu-toggle-icon" id="toggle-menu">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </div>
    </div>

    <div class="sidebar-body">
        <nav class="nav-container">
            <p class="nav-label">Management</p>
            
            <a href="{{ route('users.show', auth()->id()) }}" 
               class="nav-item {{ request()->routeIs('users.show') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span class="nav-text">User Details</span>
            </a>

            <a href="{{ route('users.edit', auth()->id()) }}" 
               class="nav-item {{ request()->routeIs('users.edit') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                <span class="nav-text">Edit Details</span>
            </a>

            <a href="#" class="nav-item">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <span class="nav-text">My Products</span>
            </a>
        </nav>
        <div class= "sidebar-footer">
            <a href="{{ route('home') }}" class="nav-item">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"></path>
                </svg>
                <span class="nav-text">Back</span>
            </a>
        </div>
    </div>
</aside>