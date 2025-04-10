<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>

.sidebar-brand-text {
    color: white;
    font-size: 2rem;
    font-weight: bold;
}

.nav-item {
    padding: 0.8rem 1rem; 
}

.nav-item .text-lg {
    font-size: 2rem;
}


.nav-link:hover {
    color: white;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}

.text-lg {
    margin-right: 10px;
    font-size: 15rem; 
}

hr.sidebar-divider {
    margin-top: 20px;
    margin-bottom: 20px;
    border: 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

#sidebarToggle {
    background-color: #4e73df;
    color: white;
    border: none;
    padding: 10px; 
    border-radius: 5px;
}

    </style>
</head>

<body id="page-top">

    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a href="{{ route('dashboard') }}">
    <img src="admin_assets/img/LOGO.png" alt="Votre Logo" style="width: 100px; height: auto; margin-left: 50px;">
</a>



        <hr class="sidebar-divider my-0">

        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt fa-lg" style="font-size: 2em;"></i>
                    <span class="text-lg" >Dashboard </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('ouvriers') }}">
                <i class="fas fa-fw fa-hard-hat fa-lg"  style="font-size: 2em;"></i>
                <span class="text-lg">Ouvriers</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('clients') }}">
                <i class="fas fa-fw fa-users fa-lg"  style="font-size: 2em;"></i>
                <span class="text-lg">Clients</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('demandes') }}">
                <i class="fas fa-fw fa-clipboard-list fa-lg"  style="font-size: 2em;"></i>
                <span class="text-lg">Demandes</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/profile">
                <i class="fas fa-fw fa-user-circle fa-lg"  style="font-size: 2em;"></i>
                <span class="text-lg">Profil</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('contacts') }}">
        <i class="fas fa-fw fa-envelope fa-lg" style="font-size: 2em;"></i> <!-- Changer la classe ici -->
                <span class="text-lg">contact</span>
            </a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>

    <script>
    </script>

</body>

</html>
