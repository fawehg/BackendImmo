
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tableau de Bord - B2C')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #2B6CB0; /* Deep blue for primary elements */
            --secondary: #9F7AEA; /* Soft purple for accents */
            --background: #F7FAFC; /* Light gray background */
            --card-bg: rgba(255, 255, 255, 0.95); /* Semi-transparent white for cards */
            --text-dark: #1A202C; /* Dark gray for text */
            --text-light: #4A5568; /* Lighter gray for secondary text */
            --border: #E2E8F0; /* Light border color */
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.05); /* Subtle shadow */
            --glow: 0 0 10px rgba(43, 108, 176, 0.2); /* Soft glow effect */
            --transition: all 0.3s ease-in-out;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --border-radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* App Wrapper */
        .app-wrapper {
            display: flex;
            flex: 1;
            position: relative;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, #4C78A8 100%);
            color: #FFFFFF;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            transition: var(--transition);
            overflow-y: auto;
            z-index: 1000;
            box-shadow: var(--shadow);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .sidebar-logo {
            width: 200px;
            height: auto;
            transition: var(--transition);
        }

        .sidebar.collapsed .sidebar-logo {
            width: 50px;
        }

        .sidebar-divider {
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin: 1.5rem 0;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin: 0.5rem 0;
            position: relative;
            animation: slideIn 0.5s ease-out forwards;
        }

        .nav-item:nth-child(1) { animation-delay: 0.1s; }
        .nav-item:nth-child(2) { animation-delay: 0.2s; }
        .nav-item:nth-child(3) { animation-delay: 0.3s; }
        .nav-item:nth-child(4) { animation-delay: 0.4s; }
        .nav-item:nth-child(5) { animation-delay: 0.5s; }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: #FFFFFF;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: var(--border-radius);
            margin: 0 1rem;
            transition: var(--transition);
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
            box-shadow: var(--glow);
        }

        .nav-item.active .nav-link {
            background: var(--secondary);
            box-shadow: var(--glow);
        }

        .nav-link i {
            font-size: 1.25rem;
            margin-right: 1rem;
            transition: var(--transition);
        }

        .nav-link span {
            flex: 1;
            transition: var(--transition);
        }

        .sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
            width: 0;
        }

        .tooltip {
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: var(--card-bg);
            color: var(--text-dark);
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            font-size: 0.85rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 10;
            box-shadow: var(--shadow);
        }

        .sidebar.collapsed .nav-link:hover .tooltip {
            opacity: 1;
            visibility: visible;
            left: calc(100% + 0.5rem);
        }

        .sidebar-toggle {
            text-align: center;
            padding: 1.5rem;
        }

        #sidebarToggle {
            background: transparent;
            border: 2px solid #FFFFFF;
            color: #FFFFFF;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        #sidebarToggle:hover {
            background: #FFFFFF;
            color: var(--primary);
            box-shadow: var(--glow);
        }

        /* Top Navigation Bar */
        .top-navbar {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 900;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .sidebar.collapsed ~ .main-content .top-navbar {
            left: var(--sidebar-collapsed-width);
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: #EDF2F7;
            border-radius: var(--border-radius);
            padding: 0.5rem 1rem;
            transition: var(--transition);
        }

        .search-bar:focus-within {
            box-shadow: var(--glow);
            border-color: var(--primary);
        }

        .search-input {
            background: transparent;
            border: none;
            color: var(--text-dark);
            padding: 0.5rem;
            width: 250px;
            font-size: 0.9rem;
        }

        .search-input::placeholder {
            color: var(--text-light);
        }

        .search-button {
            background: transparent;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 1.1rem;
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .notification-badge {
            position: relative;
            color: var(--text-dark);
            cursor: pointer;
            transition: var(--transition);
        }

        .notification-badge:hover {
            color: var(--primary);
        }

        .badge-counter {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary);
            color: #FFFFFF;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .user-profile:hover {
            color: var(--primary);
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--border-radius);
            padding: 0.5rem 0;
            min-width: 200px;
            box-shadow: var(--shadow);
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 1000;
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            color: var(--text-dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: var(--primary);
            color: #FFFFFF;
        }

        .dropdown-divider {
            border-top: 1px solid var(--border);
            margin: 0.5rem 0;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 5rem 2rem 2rem;
            transition: var(--transition);
            min-height: calc(100vh - 5rem);
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Dashboard Styles */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-header {
            text-align: left;
            margin-bottom: 2rem;
        }

        .dashboard-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            font-size: 1rem;
            color: var(--text-light);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            text-decoration: none;
            color: var(--text-dark);
            backdrop-filter: blur(10px);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow), var(--glow);
            border-color: var(--primary);
        }

        .stat-card i {
            font-size: 1.5rem;
            color: var(--primary);
            background: rgba(43, 108, 176, 0.1);
            width: 3rem;
            height: 3rem;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            transition: var(--transition);
        }

        .stat-card:hover i {
            background: var(--primary);
            color: #FFFFFF;
        }

        .stat-card h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .stat-card .value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .stat-card .view-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            margin-top: 1rem;
            transition: var(--transition);
        }

        .stat-card .view-link:hover {
            color: var(--secondary);
            transform: translateX(4px);
        }

        .stat-card .view-link i {
            font-size: 0.85rem;
            margin-left: 0.5rem;
            background: transparent;
        }

        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .chart-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            backdrop-filter: blur(10px);
        }

        .chart-card:hover {
            box-shadow: var(--shadow), var(--glow);
            border-color: var(--primary);
        }

        .chart-header {
            margin-bottom: 1rem;
        }

        .chart-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* Notification Dropdown */
        .notification-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--border-radius);
            padding: 0.5rem 0;
            width: 320px;
            max-height: 400px;
            overflow-y: auto;
            box-shadow: var(--shadow);
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 1000;
        }

        .notification-dropdown.show {
            opacity: 1;
            visibility: visible;
        }

        .notification-header {
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-item {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: var(--transition);
        }

        .notification-item:hover {
            background: var(--primary);
            color: #FFFFFF;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-circle.bg-primary {
            background: rgba(43, 108, 176, 0.2);
            color: var(--primary);
        }

        .icon-circle.bg-success {
            background: rgba(72, 187, 120, 0.2);
            color: #48BB78;
        }

        .notification-content {
            flex: 1;
        }

        .notification-time {
            font-size: 0.75rem;
            color: var(--text-light);
        }

        .notification-item:hover .notification-time {
            color: #FFFFFF;
        }

        /* Animations */
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .stat-card, .chart-card {
            animation: fadeInScale 0.6s ease-out forwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        .chart-card:nth-child(1) { animation-delay: 0.5s; }
        .chart-card:nth-child(2) { animation-delay: 0.6s; }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .charts-container {
                grid-template-columns: 1fr;
            }

            .notification-dropdown {
                width: 300px;
            }
        }

        @media (max-width: 992px) {
            .search-input {
                width: 200px;
            }

            .dashboard-header h1 {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed-width);
            }

            .sidebar-logo {
                width: 50px;
            }

            .nav-link span {
                opacity: 0;
                visibility: hidden;
                width: 0;
            }

            .tooltip {
                display: none;
            }

            .sidebar-toggle {
                display: none;
            }

            .main-content {
                margin-left: var(--sidebar-collapsed-width);
                padding: 4.5rem 1.5rem 1.5rem;
            }

            .top-navbar {
                left: var(--sidebar-collapsed-width);
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card .value {
                font-size: 1.5rem;
            }

            .search-input {
                width: 150px;
            }

            .profile-name {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .top-navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 0.75rem;
            }

            .search-bar {
                width: 100%;
            }

            .search-input {
                width: 100%;
            }

            .nav-icons {
                width: 100%;
                justify-content: space-between;
            }

            .notification-dropdown {
                width: 280px;
                right: -10px;
            }

            .dashboard-header h1 {
                font-size: 1.5rem;
            }
        }

        /* Focus States */
        .nav-link:focus,
        #sidebarToggle:focus,
        .stat-card:focus,
        .view-link:focus,
        .search-input:focus,
        .search-button:focus,
        .notification-badge:focus,
        .user-profile:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
            box-shadow: var(--glow);
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Top Navigation Bar -->
        @include('layouts.navbar')

        <!-- Main Content -->
        <main class="main-content">
            @yield('contents')
        </main>
    </div>

    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', () => {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        });

        // User Dropdown
        const userDropdown = document.getElementById('userDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');

        userDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
            notificationDropdown.classList.remove('show');
        });

        // Notification Dropdown
        const notificationToggle = document.getElementById('notificationToggle');
        const notificationDropdown = document.getElementById('notificationDropdown');

        notificationToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.classList.toggle('show');
            dropdownMenu.classList.remove('show');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            dropdownMenu.classList.remove('show');
            notificationDropdown.classList.remove('show');
        });

        // Prevent dropdown close when clicking inside
        dropdownMenu.addEventListener('click', (e) => e.stopPropagation());
        notificationDropdown.addEventListener('click', (e) => e.stopPropagation());
    </script>

    @yield('scripts')
</body>
</html>
