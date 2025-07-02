<!-- Locked Login Modal -->
<div class="modal fade" id="loginModalDialog" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url( 'public/login/submit' ) ?>" method="post">
                <div class="modal-header">
                    <div>
                        <img src="<?= base_url( 'templates/img/icon.png' ) ?>" alt="Logo" class="img-fluid"
                            style="max-width: 100px;">
                    </div>
                    <div class="ms-2">
                        <span class="fw-bold text-center" style="font-size: 22px;">Department of Agriculture</span><br>
                        <small class="text-muted">Oras, Eastern Samar</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <small style="font-size: medium;">Log in your account.</small>
                    </div>
                    <div class="mb-3">
                        <label for="login_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="login_username" name="username"
                            placeholder="Enter username" required>
                    </div>

                    <div class="mb-3">
                        <label for="login_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="login_password" name="password"
                            placeholder="Enter password" required>
                        <div class="form-check mt-2 ml-1">
                            <input class="form-check-input" type="checkbox" id="togglePassword">
                            <label class="form-check-label" for="togglePassword">show password</label>
                            <a href="<?= base_url( 'public/forgot-password' ) ?>" class="text-decoration-none float-end"
                                style="font-size: 0.875rem;">
                                Forgot Password?
                            </a>
                        </div>
                    </div>

                    <div class="mb-0">
                        <button type="submit" class="btn btn-md btn-primary rounded-5 w-100">Log in</button>
                    </div>
                </div>


            </form>
        </div>
    </div>
</div>