<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token_name" content="<?= csrf_token() ?>">
    <meta name="csrf_token" content="<?= csrf_hash() ?>">

    <title>Seed Request & Distribution</title>
    <?php if ( is_internet_available() ) : ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
            integrity="sha384-/o6I2CkkWC//PSjvWC/eYN7l3xM3tJm8ZzVkCOfp//W05QcE3mlGskpoHB6XqI+B" crossorigin="anonymous">
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
    <link rel="stylesheet" href="<?= base_url( 'templates/css/publicPage.css?v=1.1.1' ) ?>">



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
        <nav class="main-header navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top">
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

                        <li class="nav-item ms-1 top-loader">
                            <a class="nav-link" href="javascript:void(0)">
                                <i class="bi bi-house-door me-1"></i>Home
                            </a>
                        </li>

                        <li class="nav-item ms-1">
                            <a href="javascript:void(0)" class="nav-link" id="publicRequestSeedLink">
                                <i class="bi bi-box-arrow-in-down me-1"></i>Request Seed
                            </a>
                        </li>

                        <li class="nav-item ms-1">
                            <a class="nav-link" id="publicSentRequestLink" href="javascript:void(0)">
                                <i class="bi bi-send-check me-1"></i>Sent Requests
                            </a>
                        </li>

                        <li class="nav-item ms-1">
                            <a class="nav-link" href="#">
                                <i class="bi bi-info-circle me-1"></i>About
                            </a>
                        </li>
                        <li class="nav-item ms-1 top-loader">
                            <a class="nav-link" href="javascript:void(0)">
                                <i class="bi bi-person-circle me-1"></i>Profile
                            </a>
                        </li>
                    </ul>


                    <!-- Right content -->
                    <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                        <li class="nav-item ml-1 mr-1">
                            <a href="javascript:void(0)" class="btn btn-outline-primary w-100 btn-sm" id="logoutBtn">
                                <i class="bi bi-box-arrow-right me-1"></i> Log out
                            </a>
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
                                    <li class="breadcrumb-item top-loader"><a href="<?= base_url( '/' ) ?>">Home</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Body -->
            <section class="content">
                <div class="container-fluid">
                    <button type="button" class="add-post-box mb-3" style="font-size: 20px" data-bs-toggle="modal"
                        data-bs-target="#addPostModal">
                        <i class="fas fa-plus"></i>&nbsp;<span>Add Post</span>
                    </button>

                    <?php foreach ( $posts as $index => $post ) : ?>
                        <div class="card">
                            <!-- Date at the top -->
                            <div class="px-3 pt-3">
                                <?php
                                $rawCreated = $post[ 'created_at' ];
                                $createdObj = DateTime::createFromFormat( 'm-d-Y h:i:s A', $rawCreated );
                                if ( $createdObj ) :
                                    ?>
                                    <?= $createdObj->format( 'F j, Y' ) ?><br>
                                    <small class="text-muted"><?= $createdObj->format( 'h:i:s A' ) ?></small>
                                <?php endif; ?>
                            </div>

                            <!-- Image Carousel -->
                            <div id="carouselPost<?= $index ?>" class="carousel slide" data-bs-ride="carousel">
                                <?php if ( count( $post[ 'images' ] ) > 1 ) : ?>
                                    <div class="carousel-indicators">
                                        <?php foreach ( $post[ 'images' ] as $imgIndex => $img ) : ?>
                                            <button type="button" data-bs-target="#carouselPost<?= $index ?>"
                                                data-bs-slide-to="<?= $imgIndex ?>" class="<?= $imgIndex === 0 ? 'active' : '' ?>"
                                                aria-current="<?= $imgIndex === 0 ? 'true' : 'false' ?>"
                                                aria-label="Slide <?= $imgIndex + 1 ?>"></button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="carousel-inner">
                                    <?php foreach ( $post[ 'images' ] as $imgIndex => $img ) : ?>
                                        <div class="carousel-item <?= $imgIndex === 0 ? 'active' : '' ?>">
                                            <img src="<?= base_url( $img[ 'image_path' ] ) ?>" class="d-block w-100"
                                                style="max-height: 500px; object-fit: cover;" alt="Post Image">
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <?php if ( count( $post[ 'images' ] ) > 1 ) : ?>
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselPost<?= $index ?>" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselPost<?= $index ?>" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <!-- Description -->
                            <div class="card-body">
                                <p>
                                    <?= esc( $post[ 'description' ] ) ?>. <i id="editCaptionBtn"
                                        class="bi bi-pencil-square text-primary" role="button" data-bs-toggle="modal"
                                        data-bs-target="#editPostModal">&nbsp;edit</i>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>


        </div>

        <!-- Footer -->
        <footer class="main-footer bg-dark text-white py-4">
            <div class="text-center p-3 bg-secondary text-white">
                © 2025 Seed Request and Distribution System with QR Code Integration | All rights reserved.
            </div>
        </footer>

    </div>

    <?php if ( is_internet_available() ) : ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27"
            integrity="sha384-mdoL/5UxiiM5ctOnxLuxKDJy3T8r0cDATSr/QEK/m5xMEgwzfimGt2OK0hjqJp9S"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"
            integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <?php else : ?>
        <script src="<?= base_url( 'templates/js/jquery@3.6.0.min.js?v=2.2.2' ) ?>"></script>
        <script src="<?= base_url( 'templates/js/bootstrap@5.3.3.bundle.min.js?v=2.2.2' ) ?>"></script>
        <script src="<?= base_url( 'templates/js/admin-lte@3.2.min.js?v=2.2.2' ) ?>"></script>
        <script src="<?= base_url( 'templates/js/sweetalert2.js?v=3.3.3' ) ?>"></script>
    <?php endif ?>

    <script src="<?= base_url( 'templates/js/publicPageScript.js?v=5.5.5' ) ?>"></script>




    <?php if ( session()->has( 'swal' ) ) : ?>
        <script>
            Swal.fire({
                title: '<?= esc( session( 'swal' )[ 'title' ] ) ?>',
                text: '<?= esc( session( 'swal' )[ 'text' ] ) ?>',
                icon: '<?= esc( session( 'swal' )[ 'icon' ] ) ?>',
                timer: 3000, // 3 seconds
                showConfirmButton: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    <?php endif; ?>
</body>

</html>

<script>
    // Optional: expose CSRF vars globally (if using CodeIgniter 4 CSRF protection)
    const csrfTokenName =
        $('meta[name="csrf_token_name"]').attr("content") || "<?= csrf_token() ?>";
    const csrfHash =
        $('meta[name="csrf_token"]').attr("content") || "<?= csrf_hash() ?>";

</script>