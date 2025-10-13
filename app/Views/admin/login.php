<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DA Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha384-/o6I2CkkWC//PSjvWC/eYN7l3xM3tJm8ZzVkCOfp//W05QcE3mlGskpoHB6XqI+B" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
        integrity="sha384-Ay26V7L8bsJTsX9Sxclnvsn+hkdiwRnrjZJXqKmkIDobPgIIWBOVguEcQQLDuhfN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css"
        integrity="sha384-qrt37eUXKQgF1p6OlpdB29OTyKryxbxdJHkvfVN4suujWnn6PibIvbnygcK4uJfA" crossorigin="anonymous">


    <!-- Default Icon in the Head Section -->
    <link rel="shortcut icon" href="<?= base_url( 'templates/img/icon.png' ) ?>" type="image/x-icon">

    <link rel="stylesheet" href="<?= base_url( 'templates/css/admin-style.css?v=2.2.2' ) ?>">

</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg pt-4 pl-4 pr-4 mt-3" style="max-width: 400px; width: 100%;">
            <div class="border-bottom">
                <div class="d-flex align-items-center mb-4">
                    <img src="<?= base_url( 'templates/img/icon.png' ) ?>" alt="Logo" class="img-fluid"
                        style="max-width: 70px; margin-right: 10px;" />
                    <p class="text-secondary m-0">
                        <span class="fw-bold">Seed Request & Distribution</span><br>
                        <small>Login your account</small>
                    </p>
                </div>
            </div>
            <form action="javascript:void(0)" method="post" id="login_form">
                <div class="mb-3 mt-3">
                    <label for="login_email" class="form-label">Email address</label>
                    <div class="input-group input-group-md">
                        <input type="email" name="email" class="form-control" id="login_email"
                            placeholder="e.g. juan.delacruz@example.com" required />
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    </div>
                    <div id="email_error" class="text-danger small mt-1"></div>
                </div>
                <div class="mb-3">
                    <label for="login_password" class="form-label">Password</label>
                    <div class="input-group input-group-md">
                        <input type="password" name="password" class="form-control" id="login_password"
                            placeholder="********" required />
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                    </div>
                    <div id="password_error" class="text-danger small mt-1"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check ml-1">
                        <input class="form-check-input" type="checkbox" id="show_password_checkbox">
                        <label class="form-check-label small" for="show_password_checkbox">
                            Show Password
                        </label>
                    </div>
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                        data-bs-target="#forgotPasswordModal">
                        Forgot Password?
                    </a>

                </div>

                <button id="login_submit" type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="text-center mt-3 text-secondary small">&copy; 2025 Seed Request and Distribution System with QR
                Code Integration | All rights reserved.</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"
        integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"
        integrity="sha384-GzAyPc+9MeNdsDGfpe/gNkeDXXSbdZdY0yKEFBGFxqmq/97NJ92k5oyF1YPOOhm5"
        crossorigin="anonymous"></script>


    <script src="<?= base_url( 'templates/js/adminLoginScript.js?v=3' ) ?>"></script>

</body>

</html>


<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted">Enter your registered email address. We will send you a link to reset your
                    password.</p>

                <!-- ✅ Message will appear here -->
                <div id="forgot_message" class="mb-2"></div>

                <form id="forgotPasswordForm">
                    <div class="mb-3">
                        <label for="forgot_email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="forgot_email" name="email"
                            placeholder="e.g. user@example.com" required>
                        <div id="forgot_email_error" class="text-danger small mt-1"></div>
                    </div>
                    <button type="submit" id="forgotPasswordBtn" class="btn btn-primary w-100">
                        <span class="spinner-border spinner-border-sm me-2 d-none" id="forgotSpinner" role="status"
                            aria-hidden="true"></span>
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .alert-outline-success {
        border: 1px solid #198754;
        background: transparent;
        color: #198754;
        padding: 6px 10px;
        border-radius: 5px;
        font-size: 14px;
    }

    .alert-outline-danger {
        border: 1px solid #dc3545;
        background: transparent;
        color: #dc3545;
        padding: 6px 10px;
        border-radius: 5px;
        font-size: 14px;
    }
</style>