<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seed Request & Distribution <?= session()->get("title") ?></title>
    <?php if (is_internet_available()): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" integrity="sha384-Ay26V7L8bsJTsX9Sxclnvsn+hkdiwRnrjZJXqKmkIDobPgIIWBOVguEcQQLDuhfN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" integrity="sha384-qrt37eUXKQgF1p6OlpdB29OTyKryxbxdJHkvfVN4suujWnn6PibIvbnygcK4uJfA" crossorigin="anonymous">
    <?php else: ?>
        <link rel="stylesheet" href="<?= base_url('templates/css/bootstrap@5.3.3.min.css?v=1.1.1') ?>">
        <link rel="stylesheet" href="<?= base_url('templates/css/bootstrap-icons@1.10.5.css?v=1.1.1') ?>">
        <link rel="stylesheet" href="<?= base_url('templates/css/adminlte@3.2.min.css?v=1.1.1') ?>">
        
    <?php endif ?>
    
    <!-- Default Icon in the Head Section -->
    <link rel="shortcut icon" href="<?= base_url('templates/img/icon.png') ?>" type="image/x-icon">

    <link rel="stylesheet" href="<?= base_url('templates/css/admin-style.css?v=2.2.2') ?>">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <!-- Sidebar toggle -->
                <ul class="navbar-nav mt-n1">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                            <i class="bi bi-list fs-5"></i>
                        </a>
                    </li>
                </ul>
            
                <!-- Search -->
                <ul class="navbar-nav ml-1">
                    <li class="nav-item">
                        <form class="form-inline" id="searchForm">
                            <div class="input-group input-group-sm ml-1 position-relative">
                                <input id="searchInput" class="form-control" type="search" placeholder="Search" aria-label="Search" autocomplete="off">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar rounded-end" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <ul id="suggestionsList" class="list-group position-absolute w-100" style="top: 100%; z-index: 1050; display: none;"></ul>
                            </div>
                        </form>

                        <input type="hidden" id="base_url" value="<?= base_url('admin') ?>/" />

                    </li>
                </ul>
            
                

                <!-- Mobile toggle button for user dropdown (only visible on mobile) -->
                <ul class="navbar-nav ms-auto d-lg-none mt-n1"> <!-- d-lg-none hides this on large screens -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="collapse" data-bs-target="#navbarUserDropdown" aria-controls="navbarUserDropdown" aria-expanded="false" aria-label="Toggle user dropdown">
                            <i class="bi bi-list fs-5"></i> <!-- User icon, same as sidebar toggle -->
                        </a>
                    </li>
                </ul>
            
                <!-- User dropdown (collapsible on mobile) -->
                <div class="collapse navbar-collapse justify-content-end" id="navbarUserDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown user-menu">
                            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                                <img src="<?= base_url('templates/img/icon.png') ?>" alt="User Avatar" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                <span>sdfsdf</span>
                            </a>
            
                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                                <li class="user-header text-bg-primary text-center">
                                    <p><small>Administrator</small></p>
                                </li>
                                <li class="user-footer text-center">
                                    <a href="javascript:void(0)" class="btn btn-default btn-flat" id="profile">Profile</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Logo and DA text -->
            <a class="navbar-brand brand-link active d-flex align-items-center p-2" href="#">
                <img src="<?= base_url('templates/img/icon.png') ?>" alt="Logo" class="img-fluid ml-1" style="max-width: 50px; height: auto;">
                <div class="ms-2 text-truncate">
                    <span class=" text-light d-block" style="font-size: 0.92rem;">Department of Agriculture</span>
                    <span class="d-block" style="font-size: 0.8rem;">Oras, Eastern Samar</span>
                </div>
            </a>
            
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="dashboard" class="nav-link <?= session()->get("current_tab") == "dashboard" ? "active" : null ?>">
                                <i class="nav-icon bi bi-speedometer2 ml-1"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
            
                        <!-- Public Page -->
                        <li class="nav-item">
                            <a href="<?= base_url("/") ?>" class="nav-link">
                                <i class="nav-icon bi bi-globe ml-1"></i>
                                <p>Public Page <i class="bi bi-box-arrow-up-right ml-1"></i></p>
                            </a>
                        </li>
            
                        <!-- Inventory -->
                        <li class="nav-item">
                            <a href="inventory" class="nav-link <?= session()->get("current_tab") == "inventory" ? "active" : null ?>">
                                <i class="nav-icon bi bi-box-seam ml-1"></i>
                                <p>Inventory</p>
                            </a>
                        </li>
            
                        <!-- Seed Requests -->
                        <li class="nav-item">
                            <a href="seedsRequests" class="nav-link <?= session()->get("current_tab") == "seeds_requests" ? "active" : null ?>">
                                <i class="nav-icon bi bi-journal-text ml-1"></i>
                                <p>Seeds Requests</p>
                            </a>
                        </li>
            
                        <!-- Beneficiaries -->
                        <li class="nav-item">
                            <a href="beneficiaries" class="nav-link <?= session()->get("current_tab") == "beneficiaries" ? "active" : null ?>">
                                <i class="nav-icon bi bi-people ml-1"></i>
                                <p>Beneficiaries</p>
                            </a>
                        </li>
            
                        <!-- Reports -->
                        <li class="nav-item">
                            <a href="reports" class="nav-link <?= session()->get("current_tab") == "reports" ? "active" : null ?>">
                                <i class="nav-icon bi bi-graph-up ml-1"></i>
                                <p>Reports</p>
                            </a>
                        </li>
            
                        <!-- Logs -->
                        <li class="nav-item">
                            <a href="logs" class="nav-link <?= session()->get("current_tab") == "logs" ? "active" : null ?>">
                                <i class="nav-icon bi bi-clock-history ml-1"></i>
                                <p>Logs</p>
                            </a>
                        </li>
                        
                        <!-- Logout -->
                        <li class="nav-item mt-2">
                            <a href="logout" class="nav-link">
                                <i class="nav-icon bi bi-box-arrow-right ml-1"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        

