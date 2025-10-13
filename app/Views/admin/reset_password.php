<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="shortcut icon" href="<?= base_url( 'templates/img/icon.png' ) ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url( 'templates/css/admin-style.css?v=2.2.2' ) ?>">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4 mt-3" style="max-width: 400px; width: 100%;">
            <div class="border-bottom">
                <div class="text-center mb-4">
                    <img src="<?= base_url( 'templates/img/icon.png' ) ?>" alt="Logo" class="img-fluid"
                        style="max-width: 150px;" />
                </div>
                <p class="text-center text-secondary">Reset Your Password</p>
            </div>

            <!-- ✅ Message area -->
            <div id="reset_message" class="mt-2"></div>

            <form action="javascript:void(0)" method="post" id="resetPasswordForm">
                <input type="hidden" id="token" name="token" value="<?= $token ?>">

                <div class="mb-3 mt-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <div class="input-group input-group-md">
                        <input type="password" name="new_password" class="form-control" id="new_password"
                            placeholder="********" required />
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                    </div>
                    <div id="new_password_error" class="text-danger small mt-1"></div>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <div class="input-group input-group-md">
                        <input type="password" name="confirm_password" class="form-control" id="confirm_password"
                            placeholder="********" required />
                        <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                    </div>
                    <div id="confirm_password_error" class="text-danger small mt-1"></div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="show_reset_password">
                    <label class="form-check-label small" for="show_reset_password">
                        Show Passwords
                    </label>
                </div>

                <button id="resetPasswordBtn" type="submit" class="btn btn-primary w-100">
                    <span class="spinner-border spinner-border-sm me-2 d-none" id="resetSpinner" role="status"
                        aria-hidden="true"></span>
                    Update Password
                </button>
            </form>

            <p class="text-center mt-3 text-secondary small">&copy; 2025 Seed Request and Distribution System with QR
                Code Integration | All rights reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <script>
        // Show Password Toggle
        $("#show_reset_password").on("change", function () {
            const type = this.checked ? "text" : "password";
            $("#new_password, #confirm_password").attr("type", type);
        });

        // Submit Handler (Adjust API URL as needed)
        $("#resetPasswordForm").on("submit", function () {
            $("#resetSpinner").removeClass("d-none");
            $("#resetPasswordBtn").prop("disabled", true).text("Updating...");

            // Simulate request (replace with your AJAX)
            setTimeout(() => {
                $("#reset_message").html('<div class="alert alert-outline-success">Password updated successfully!</div>');
                $("#resetSpinner").addClass("d-none");
                $("#resetPasswordBtn").prop("disabled", false).text("Update Password");
            }, 2000);
        });
    </script>

    <style>
        .alert-outline-success {
            border: 1px solid #198754;
            background: transparent;
            color: #198754;
            padding: 6px 10px;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>

</body>

</html>