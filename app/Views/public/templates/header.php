<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seed Request & Distribution</title>
    <?php if ( is_internet_available() ) : ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <?php else : ?>
        <link rel="stylesheet" href="<?= base_url( 'templates/css/bootstrap@5.3.3.min.css?v=1.1.1' ) ?>">
        <link rel="stylesheet" href="<?= base_url( 'templates/css/bootstrap-icons@1.10.5.css?v=1.1.1' ) ?>">
        <link rel="stylesheet" href="<?= base_url( 'templates/css/adminlte@3.2.min.css?v=1.1.1' ) ?>">
    <?php endif ?>

    <!-- Default Icon in the Head Section -->
    <link rel="shortcut icon" href="<?= base_url( 'templates/img/icon.png' ) ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url( 'templates/css/admin-style.css?v=1.1.1' ) ?>">

    <style>
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.2);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner-with-logo {
            position: relative;
            width: 100px;
            height: 100px;
        }

        .gradient-spinner {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: conic-gradient(rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.2) 20%,
                    rgba(255, 255, 255, 0.4) 40%,
                    rgba(255, 255, 255, 0.8) 70%,
                    rgba(255, 255, 255, 1) 100%);

            /* Make the ring thin (just a small line on the edge) */
            mask: radial-gradient(farthest-side, transparent 89%, black 90%);
            -webkit-mask: radial-gradient(farthest-side, transparent 89%, black 90%);

            animation: spin 1s linear infinite;
            transform-origin: center;
        }

        .spinner-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 80px;
            height: 80px;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            object-fit: contain;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

</head>
<div class="spinner-overlay" id="loading-spinner" style="display: none;">
    <div class="spinner-with-logo">
        <div class="gradient-spinner"></div>
        <img src="<?= base_url( 'templates/img/icon.png' ) ?>" alt="Logo" class="spinner-logo">
    </div>
</div>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <!-- Logo and DA text -->
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="<?= base_url( 'templates/img/icon.png' ) ?>" alt="Logo" class="img-fluid"
                        style="max-width: 60px;">
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
                        <a class="nav-link" href="#" role="button" data-bs-toggle="collapse"
                            data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
                            aria-label="Toggle navigation">
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
                        <li class="nav-item ms-1"><a class="nav-link active" href="<?= base_url( '/' ) ?>">Home</a></li>
                        <li class="nav-item ms-1">
                            <a href="#" class="nav-link" id="publicRequestSeedLink"
                                data-is-logged-in="<?= session()->get( 'public_user_id' ) ? '1' : '0' ?>"
                                data-request-url="<?= base_url( 'public/request_seed' ) ?>"
                                data-login-url="<?= base_url( 'public/login' ) ?>"
                                data-signup-url="<?= base_url( 'public/signUp' ) ?>">
                                Request Seeds
                            </a>
                        </li>

                        <li class="nav-item ms-1">
                            <a class="nav-link" href="#" id="testSweetAlert">Sent Request</a>
                        </li>
                        <li class="nav-item ms-1"><a class="nav-link" href="#">About</a></li>
                        <li class="nav-item ms-1"><a class="nav-link" href="#">Profile</a></li>
                    </ul>

                    <!-- Right content -->
                    <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">

                        <li class="nav-item mr-2 mb-2">
                            <a href="login" class="btn btn-outline-primary
                             btn-sm">Log in</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url( 'public/signUp' ) ?>" class="btn btn-outline-primary
                             btn-sm">Sign up</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>