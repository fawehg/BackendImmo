<nav class="top-navbar">
    <div class="search-bar">
        <input type="text" class="search-input" placeholder="Recherche...">
        <button class="search-button">
            <i class="fas fa-search"></i>
        </button>
    </div>

    <div class="nav-icons">
        <div class="notification-badge" id="notificationToggle">
            <i class="fas fa-bell"></i>
            <span class="badge-counter">2+</span>
            
            <!-- Notification Dropdown -->
            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-header">
                    <h4>Notifications</h4>
                    <small>2 nouvelles</small>
                </div>
                
                <a class="notification-item" href="#">
                    <div class="notification-icon icon-circle bg-primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="notification-content">
                        <div class="small text-gray-500">11 mai 2024</div>
                        <p>Un nouveau utilisateur a rempli le formulaire de contact</p>
                    </div>
                </a>
                
                <a class="notification-item" href="#">
                    <div class="notification-icon icon-circle bg-success">
                        <i class="fas fa-donate"></i>
                    </div>
                    <div class="notification-content">
                        <div class="small text-gray-500">15 mai 2024</div>
                        <p>Un nouveau utilisateur a rempli le formulaire de contact</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="user-profile" id="userDropdown">
            <span class="profile-name">{{ auth()->user()->name }}</span>
            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('default-avatar.png') }}" 
            alt="Profile" class="profile-image">
        </div>
    </div>

    <!-- User Dropdown Menu -->
    <div class="dropdown-menu" id="dropdownMenu">
        <a class="dropdown-item" href="/profile">
            <i class="fas fa-user fa-fw"></i> Profil
        </a>
        <a class="dropdown-item" href="#">
            <i class="fas fa-cogs fa-fw"></i> Paramètres
        </a>
        <div class="dropdown-divider"></div>
        <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
            @csrf
            <button type="submit" class="btn btn-link w-100 text-left text-light">
                <i class="fas fa-sign-out-alt fa-fw"></i> Déconnexion
            </button>
        </form>
    </div>
</nav>