<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="sidebar-brand-link">
            <img src="{{ asset('admin_assets/img/hh.png') }}" alt="Logo" class="sidebar-logo">
        </a>
    </div>

    <hr class="sidebar-divider">

    <ul class="sidebar-nav">
        <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Tableau de bord</span>
                <span class="tooltip">Tableau de bord</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('vendeurs*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('vendeurs') }}">
                <i class="fas fa-store"></i>
                <span>Vendeurs</span>
                <span class="tooltip">Vendeurs</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('clients*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('clients') }}">
                <i class="fas fa-users"></i>
                <span>Clients</span>
                <span class="tooltip">Clients</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('profile*') ? 'active' : '' }}">
            <a class="nav-link" href="/profile">
                <i class="fas fa-user-circle"></i>
                <span>Profil</span>
                <span class="tooltip">Profil</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('contacts*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('contacts') }}">
                <i class="fas fa-envelope"></i>
                <span>Contact</span>
                <span class="tooltip">Contact</span>
            </a>
        </li>
    </ul>

    <hr class="sidebar-divider">

    <div class="sidebar-toggle">
        <button id="sidebarToggle" aria-label="Basculer la barre latérale">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
</aside>