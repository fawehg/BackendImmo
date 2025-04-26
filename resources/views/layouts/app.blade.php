<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tableau de Bord - B2C')</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #00ddeb;
            --secondary: #ff00e1;
            --dark: #0a0e1a;
            --light: #e0e7ff;
            --accent: #3b82f6;
            --glow: 0 0 8px rgba(0, 221, 235, 0.5), 0 0 16px rgba(0, 221, 235, 0.3);
            --shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            --transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: linear-gradient(-45deg, #0a0e1a, #1e2a44, #2a3b6b, #0a0e1a);
            background-size: 400%;
            animation: gradientShift 15s ease infinite;
            color: var(--light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
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
            background: linear-gradient(180deg, rgba(10, 14, 26, 0.9), rgba(30, 41, 59, 0.9));
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--light);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            transition: var(--transition);
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-brand {
            padding: 1.5rem 1rem;
            text-align: center;
        }

        .sidebar-logo {
            width: 180px;
            height: auto;
            transition: var(--transition);
        }

        .sidebar.collapsed .sidebar-logo {
            width: 50px;
        }

        .sidebar-divider {
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin: 1rem 0;
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
            padding: 0.75rem 1.5rem;
            color: var(--light);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 12px;
            margin: 0 1rem;
            transition: var(--transition);
        }

        .nav-link:hover {
            background: rgba(0, 221, 235, 0.2);
            color: var(--primary);
            box-shadow: var(--glow);
            transform: translateX(4px);
        }

        .nav-item.active .nav-link {
            background: linear-gradient(90deg, var(--primary), var(--accent));
            color: #fff;
            box-shadow: var(--glow);
        }

        .nav-link i {
            font-size: 1.5rem;
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
            background: rgba(10, 14, 26, 0.9);
            color: var(--light);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
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
            padding: 1rem;
        }

        #sidebarToggle {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
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
            background: var(--primary);
            color: var(--dark);
            box-shadow: var(--glow);
            transform: rotate(180deg);
        }

        .sidebar.collapsed #sidebarToggle i {
            transform: rotate(180deg);
        }

        /* Top Navigation Bar */
        .top-navbar {
            background: rgba(10, 14, 26, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
        }

        .sidebar.collapsed ~ .main-content .top-navbar {
            left: var(--sidebar-collapsed-width);
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2rem;
            padding: 0.5rem 1rem;
            transition: var(--transition);
        }

        .search-bar:focus-within {
            box-shadow: var(--glow);
        }

        .search-input {
            background: transparent;
            border: none;
            color: var(--light);
            padding: 0.5rem;
            width: 250px;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .search-button {
            background: transparent;
            border: none;
            color: var(--primary);
            cursor: pointer;
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .notification-badge {
            position: relative;
            color: var(--light);
            cursor: pointer;
        }

        .badge-counter {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary);
            color: white;
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
        }

        /* Dropdown Menu */
        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background: rgba(10, 14, 26, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
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
            color: var(--light);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: rgba(0, 221, 235, 0.2);
            color: var(--primary);
        }

        .dropdown-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 0.5rem 0;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 6rem 2.5rem 12rem; /* Increased padding-bottom for fixed footer */
            transition: var(--transition);
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Dashboard Styles */
        .dashboard-container {
            max-width: 1500px;
            margin: 0 auto;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            text-shadow: 0 0 10px rgba(0, 221, 235, 0.5);
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            font-size: 1rem;
            color: #a5b4fc;
            font-weight: 400;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3.5rem;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            text-decoration: none;
            color: var(--light);
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: var(--shadow), var(--glow);
            border-color: var(--primary);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 221, 235, 0.2) 0%, transparent 70%);
            opacity: 0;
            transition: var(--transition);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card i {
            font-size: 2rem;
            color: var(--primary);
            background: rgba(0, 221, 235, 0.1);
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: var(--glow);
            transition: var(--transition);
        }

        .stat-card:hover i {
            transform: scale(1.15);
            box-shadow: 0 0 15px rgba(0, 221, 235, 0.7);
        }

        .stat-card h3 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #a5b4fc;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.75rem;
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--light);
            margin-bottom: 1rem;
            text-shadow: 0 0 5px rgba(0, 221, 235, 0.3);
        }

        .stat-card .view-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }

        .stat-card .view-link:hover {
            color: var(--secondary);
            transform: translateX(6px);
            text-shadow: 0 0 5px rgba(255, 0, 225, 0.5);
        }

        .stat-card .view-link i {
            font-size: 0.875rem;
            margin-left: 0.5rem;
            background: transparent;
        }

        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .chart-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .chart-card:hover {
            box-shadow: var(--shadow), var(--glow);
            border-color: var(--primary);
        }

        .chart-header {
            margin-bottom: 1.5rem;
        }

        .chart-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
            text-shadow: 0 0 5px rgba(0, 221, 235, 0.3);
        }

        .chart-wrapper {
            position: relative;
            height: 400px;
            width: 100%;
        }

        /* Animations */
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .stat-card, .chart-card {
            animation: fadeInScale 0.8s ease-out forwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        .stat-card:nth-child(5) { animation-delay: 0.5s; }
        .stat-card:nth-child(6) { animation-delay: 0.6s; }
        .chart-card:nth-child(1) { animation-delay: 0.7s; }
        .chart-card:nth-child(2) { animation-delay: 0.8s; }

        /* Notification Dropdown */
        .notification-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            background: rgba(10, 14, 26, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            width: 350px;
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
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
            background: rgba(0, 221, 235, 0.1);
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
            background: rgba(0, 221, 235, 0.2);
            color: var(--primary);
        }

        .icon-circle.bg-success {
            background: rgba(0, 255, 163, 0.2);
            color: #00ffa3;
        }

        .notification-content {
            flex: 1;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #a5b4fc;
        }

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
                padding: 5rem 1.5rem 10rem; /* Adjusted padding-bottom for mobile */
            }

            .top-navbar {
                left: var(--sidebar-collapsed-width);
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-header h1 {
                font-size: 1.75rem;
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
                right: -50%;
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

        <!-- Footer -->
    </div>

    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', () => {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            
            // Rotate icon
            const icon = document.querySelector('#sidebarToggle i');
            icon.style.transform = sidebar.classList.contains('collapsed') ? 'rotate(180deg)' : 'rotate(0)';
        });

        // User Dropdown
        const userDropdown = document.getElementById('userDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');

        userDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
            
            // Close notification dropdown if open
            notificationDropdown.classList.remove('show');
        });

        // Notification Dropdown
        const notificationToggle = document.getElementById('notificationToggle');
        const notificationDropdown = document.getElementById('notificationDropdown');

        notificationToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.classList.toggle('show');
            
            // Close user dropdown if open
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

        // Initialize Charts if they exist on page
      
    </script>
    
    @yield('scripts')
</body>
</html>