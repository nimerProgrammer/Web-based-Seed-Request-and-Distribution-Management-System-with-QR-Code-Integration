<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DA Login</title>
    <?php if ( is_internet_available() ) : ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
            integrity="sha384-/o6I2CkkWC//PSjvWC/eYN7l3xM3tJm8ZzVkCOfp//W05QcE3mlGskpoHB6XqI+B" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
            integrity="sha384-Ay26V7L8bsJTsX9Sxclnvsn+hkdiwRnrjZJXqKmkIDobPgIIWBOVguEcQQLDuhfN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css"
            integrity="sha384-qrt37eUXKQgF1p6OlpdB29OTyKryxbxdJHkvfVN4suujWnn6PibIvbnygcK4uJfA" crossorigin="anonymous">
    <?php else : ?>
        <link rel="stylesheet" href="<?= base_url( 'templates/css/bootstrap@5.3.3.min.css?v=1.1.1' ) ?>">
        <link rel="stylesheet" href="<?= base_url( 'templates/css/bootstrap-icons@1.10.5.css?v=1.1.1' ) ?>">
        <link rel="stylesheet" href="<?= base_url( 'templates/css/adminlte@3.2.min.css?v=1.1.1' ) ?>">
    <?php endif ?>

    <!-- Default Icon in the Head Section -->
    <link rel="shortcut icon" href="<?= base_url( 'templates/img/icon.png' ) ?>" type="image/x-icon">

    <link rel="stylesheet" href="<?= base_url( 'templates/css/admin-style.css?v=2.2.2' ) ?>">

</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4 mt-3" style="max-width: 400px; width: 100%;">
            <div class="border-bottom">
                <div class="text-center mb-4">
                    <img src="<?= base_url( 'templates/img/icon.png' ) ?>" alt="Logo" class="img-fluid"
                        style="max-width: 200px;" />
                </div>
                <p class="text-center text-secondary">Seed Request & Distribution</p>
            </div>
            <form action="javascript:void(0)" method="post" id="login_form">
                <div class="mb-3 mt-3">
                    <label for="login_email" class="form-label">Email address</label>
                    <div class="input-group input-group-md">
                        <input type="email" name="email" class="form-control" id="login_email" placeholder="Enter email"
                            required />
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
                <button id="login_submit" type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="text-center mt-3 text-secondary small">&copy; 2025 DA | All rights reserved</p>
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

    <script src="<?= base_url( 'templates/js/season-watcher.js?v=4.4.4' ) ?>"></script>

    <script src="<?= base_url( 'templates/js/adminLoginScript.js?v=2.2.2' ) ?>"></script>

</body>

</html>