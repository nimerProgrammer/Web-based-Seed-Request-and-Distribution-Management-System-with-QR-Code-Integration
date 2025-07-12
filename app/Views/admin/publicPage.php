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
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const navbar = document.querySelector('.main-header.navbar');
        if (navbar) {
            const navbarHeight = navbar.offsetHeight;
            document.body.style.paddingTop = `${navbarHeight}px`;
        }
    });

    // Optional: Recalculate on window resize
    window.addEventListener('resize', () => {
        const navbar = document.querySelector('.main-header.navbar');
        if (navbar) {
            const navbarHeight = navbar.offsetHeight;
            document.body.style.paddingTop = `${navbarHeight}px`;
        }
    });
</script>

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

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid text-center">

                    <!-- Full-width image carousel -->
                    <div id="devCarousel" class="carousel slide mb-2" data-bs-ride="carousel">
                        <!-- Indicators -->
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#devCarousel" data-bs-slide-to="0" class="active"
                                aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#devCarousel" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#devCarousel" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                        </div>

                        <!-- Slides -->
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?= base_url( 'assets/images/image1.jpg' ) ?>" class="d-block w-100"
                                    alt="Slide 1" style="max-height: 500px; object-fit: cover;">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url( 'assets/images/image2.jpg' ) ?>" class="d-block w-100"
                                    alt="Slide 2" style="max-height: 500px; object-fit: cover;">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url( 'assets/images/image3.jpg' ) ?>" class="d-block w-100"
                                    alt="Slide 3" style="max-height: 500px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#devCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#devCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <!-- Caption below carousel -->
                    <div class="text-center mb-4">
                        <p id="devCaption" class="text-secondary">
                            This feature is currently under development.
                            <i id="editCaptionBtn" class="bi bi-pencil-square text-primary" role="button"
                                data-bs-toggle="modal" data-bs-target="#editModal">&nbsp;edit</i>
                        </p>
                    </div>

                </div>
            </section>

            <!-- Styles for Indicators -->
            <style>
                .carousel-indicators [data-bs-target] {
                    width: 10px;
                    height: 10px;
                    border-radius: 50%;
                    background-color: rgba(255, 255, 255, 0.1);
                    /* default faded */
                    opacity: 1;
                    border: none;
                    transition: background-color 0.3s ease, transform 0.3s ease;
                }

                .carousel-indicators .active-dot {
                    background-color: #ffffff;
                    /* fully white */
                    transform: scale(1.2);
                }

                .carousel-indicators .near-active-dot {
                    background-color: rgba(255, 255, 255, 0.6);
                    /* medium opacity */
                }
            </style>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="editForm" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Image & Caption</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="mb-3">
                                <label for="imageUrl" class="form-label">Image URL</label>
                                <input type="url" class="form-control" id="imageUrl"
                                    placeholder="https://example.com/image.jpg">
                            </div>
                            <div class="mb-3">
                                <label for="captionText" class="form-label">Caption</label>
                                <input type="text" class="form-control" id="captionText"
                                    placeholder="Enter caption here...">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Script -->
            <script>
                const carousel = document.querySelector('#devCarousel');
                const indicators = document.querySelectorAll('.carousel-indicators [data-bs-target]');

                function updateIndicators() {
                    const activeIndex = [...indicators].findIndex(dot => dot.classList.contains('active'));
                    indicators.forEach((dot, i) => {
                        dot.classList.remove('active-dot', 'near-active-dot');
                        if (i === activeIndex) {
                            dot.classList.add('active-dot');
                        } else if (Math.abs(i - activeIndex) <= 2) {
                            dot.classList.add('near-active-dot');
                        }
                    });
                }

                document.addEventListener('DOMContentLoaded', updateIndicators);
                carousel.addEventListener('slid.bs.carousel', updateIndicators);

                // Optional: Update caption/image on edit
                document.getElementById('editForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const imageUrl = document.getElementById('imageUrl').value;
                    const captionText = document.getElementById('captionText').value;

                    if (imageUrl) {
                        document.querySelector('.carousel-item.active img').src = imageUrl;
                    }
                    if (captionText) {
                        document.getElementById('devCaption').textContent = captionText;
                    }

                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                });
            </script>



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

    <script src="<?= base_url( 'templates/js/adminHomeScript.js?v=5.5.5' ) ?>"></script>




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