<script>
    const LOGIN_URL = "<?= base_url( 'public/login/check_credentials' ) ?>";
</script>

<!-- Locked Login Modal -->
<div class="modal fade" id="loginModalDialog" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form id="loginForm" action="javascript:void(0)" method="post">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <img src="<?= base_url( 'templates/img/icon.png' ) ?>" alt="Logo" class="img-fluid"
                            style="max-width: 60px; margin-right: 10px;" />
                        <p class="text-secondary m-0">
                            <span class="fw-bold text-md">Seed Request & Distribution</span><br>
                            <small>Login your account</small>
                        </p>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="login_username" class="form-label">Username</label>
                        <div class="input-group input-group-md">
                            <input type="text" class="form-control" id="login_username" name="username"
                                placeholder="Enter username" required>
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        </div>
                        <div class="invalid-feedback" id="username_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="login_password" class="form-label">Password</label>

                        <div class="input-group input-group-md">
                            <input type="password" class="form-control" id="login_password" name="password"
                                placeholder="********" required>
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                        </div>
                        <div class="invalid-feedback" id="password_error"></div>

                        <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                            <div class="form-check ml-1">
                                <input class="form-check-input" type="checkbox" id="togglePassword">
                                <label class="form-check-label small" for="togglePassword">
                                    Show Password
                                </label>
                            </div>
                            <a href="#" class="text-decoration-none text-sm" data-bs-toggle="modal"
                                data-bs-target="#forgotPasswordModal">
                                Forgot Password?
                            </a>

                        </div>
                    </div>

                    <div class="mb-0">
                        <button id="login-btn" type="submit" class="btn btn-md btn-primary rounded-5 w-100">Log
                            in</button>
                    </div>
                </div>


            </form>
        </div>
    </div>
</div>