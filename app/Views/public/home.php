<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seed Request & Distribution</title>
    <?php if (is_internet_available()): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <?php else: ?>
        <link rel="stylesheet" href="<?= base_url('templates/css/bootstrap@5.3.3.min.css?v=1.1.1') ?>">
        <link rel="stylesheet" href="<?= base_url('templates/css/bootstrap-icons@1.10.5.css?v=1.1.1') ?>">
        <link rel="stylesheet" href="<?= base_url('templates/css/adminlte@3.2.min.css?v=1.1.1') ?>">
    <?php endif ?>

    <!-- Default Icon in the Head Section -->
    <link rel="shortcut icon" href="<?= base_url('templates/img/icon.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('templates/css/admin-style.css?v=1.1.1') ?>">
</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <!-- Logo and DA text -->
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="<?= base_url('templates/img/icon.png') ?>" alt="Logo" class="img-fluid" style="max-width: 60px;">
                    <div class="ms-2">
                        <span class="fw-bold" style="font-size: 22px;">Department of Agriculture</span><br>
                        <small class="text-muted">Oras, Eastern Samar</small>
                    </div>
                </a>

                <!-- Toggler -->
                <!-- <button class="navbar-toggler ms-1 mb-2 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button> -->

                <ul class="navbar-nav ms-1 d-lg-none"> <!-- d-lg-none hides this on large screens -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="bi bi-list fs-3"></i> <!-- User icon, same as sidebar toggle -->
                        </a>
                    </li>
                </ul>

                <!-- Collapsible content -->
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <!-- Left links -->
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item ms-1 mt-1 mr-1">
                            <form class="d-flex" role="search">
                                <div class="input-group input-group-sm">
                                    <input type="search" class="form-control" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </li>
                        <li class="nav-item ms-1"><a class="nav-link active" href="#">Home</a></li>
                        <li class="nav-item ms-1"><a class="nav-link" href="#">Request Seeds</a></li>
                        <li class="nav-item ms-1"><a class="nav-link" href="#">Distributions</a></li>
                        <li class="nav-item ms-1"><a class="nav-link" href="#">About</a></li>
                    </ul>

                    <!-- Right content -->
                    <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                        <li class="nav-item ms-1">
                            <a href="logout" class="btn btn-outline-dark btn-sm">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>



        <!-- Main Content -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="border-bottom">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-start">
                                    <li class="breadcrumb-item"><a href="<?= base_url("admin") ?>">Home</a></li>
                                    <li class="breadcrumb-item active">Reports</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <h1 class="text-center text-secondary"> Under Development</h1>
                </div>
            </section>
        </div>


        <!-- Footer -->
        <footer class="main-footer bg-dark text-white py-4">
            <div class="container p-4">
                <div class="row">
                    <div class="col-lg-6 col-md-12 mb-4 mb-md-0 text-start">
                        <h5 class="text-uppercase">About Us</h5>
                        <p>
                            Our Web-based Seed Request and Distribution Management System helps streamline the seed request and delivery process for farmers and local agencies. It integrates QR Code technology for secure and efficient tracking.
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mb-md-0 text-start">
                        <h5 class="text-uppercase">Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-light">Home</a></li>
                            <li><a href="#" class="text-light">Seed Requests</a></li>
                            <li><a href="#" class="text-light">Distribution</a></li>
                            <li><a href="#" class="text-light">QR Code</a></li>
                            <li><a href="#" class="text-light">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center p-3 bg-secondary text-white">
                © 2025 Seed Distribution System | All rights reserved.
            </div>
        </footer>

    </div>

    <?php if (is_internet_available()): ?>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <?php else: ?>
        <script src="<?= base_url('templates/js/jquery@3.6.0.min.js?v=2.2.2') ?>"></script>
        <script src="<?= base_url('templates/js/bootstrap@5.3.3.bundle.min.js?v=2.2.2') ?>"></script>
        <script src="<?= base_url('templates/js/admin-lte@3.2.min.js?v=2.2.2') ?>"></script>
    <?php endif ?>
</body>
</html>
