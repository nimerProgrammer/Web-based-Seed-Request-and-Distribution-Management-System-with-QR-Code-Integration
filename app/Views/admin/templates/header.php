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
    
    <link rel="stylesheet" href="<?= base_url('templates/css/admin-style.css?v=1.1.1') ?>">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light bg-white border-bottom">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-1">
                <li class="nav-item">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                            <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </li>
                <li class="nav-item">
                    <a href="logout" class="btn btn-outline-dark btn-sm ml-3">Logout</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-md-inline">sdfsdf</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <p>
                                    <small>Administrator</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <a href="javascript:void(0)" class="btn btn-default btn-flat" id="profile">Profile</a>
                            </li>
                        </ul>
                    </li>
                </ul>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link text-center">
                <span class="brand-text font-weight-light">Seed System</span>
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
                                <p>
                                    Public Page
                                    <i class="bi bi-box-arrow-up-right ml-1"></i>
                                </p>
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

                    </ul>
                </nav>
                
            </div>
            
        </aside>