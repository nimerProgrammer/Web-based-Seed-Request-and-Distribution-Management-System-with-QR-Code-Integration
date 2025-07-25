<!-- Footer -->
<footer class="main-footer bg-dark text-white py-4">
    <!-- <div class="container p-4">
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
            </div> -->
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

<script src="<?= base_url( 'templates/js/publicScript.js?v=5.5.5' ) ?>"></script>
<script src="<?= base_url( 'templates/js/publicLoginScript.js?v=4.4.4' ) ?>"></script>
<link rel="stylesheet" href="<?= base_url( 'templates/css/publicPage.css?v=1.1.1' ) ?>">


<?php if ( session()->get( 'public_logged_in' ) === true ) : ?>

    <script src="<?= base_url( 'templates/js/publicRequestSeedScript.js?v=4.4.4' ) ?>"></script>

<?php endif; ?>

<?php if ( session()->get( 'public_title' ) === 'profile' && session()->get( 'public_current_tab' ) === 'profile' ) : ?>

    <script src="<?= base_url( 'templates/js/publicProfileScript.js?v=5.5.5' ) ?>"></script>

<?php endif; ?>

<?php if ( session()->get( 'public_title' ) === 'Sign Up' && session()->get( 'public_current_tab' ) === 'Sign Up' ) : ?>

    <script src="<?= base_url( 'templates/js/publicSignUpScript.js?v=5.5.5' ) ?>"></script>

<?php endif; ?>

<?php if ( session()->get( 'public_title' ) === 'sentRequests' && session()->get( 'public_current_tab' ) === 'sentRequests' ) : ?>

    <script src="<?= base_url( 'templates/js/publicSentRequestsScript.js?v=5.5.5' ) ?>"></script>

<?php endif; ?>

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