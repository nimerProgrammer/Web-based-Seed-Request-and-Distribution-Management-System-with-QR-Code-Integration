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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<?php else : ?>
    <script src="<?= base_url( 'templates/js/jquery@3.6.0.min.js?v=2.2.2' ) ?>"></script>
    <script src="<?= base_url( 'templates/js/bootstrap@5.3.3.bundle.min.js?v=2.2.2' ) ?>"></script>
    <script src="<?= base_url( 'templates/js/admin-lte@3.2.min.js?v=2.2.2' ) ?>"></script>
<?php endif ?>

<script src="<?= base_url( 'templates/js/publicScript.js?v=6.6.6' ) ?>"></script>

<?php if ( session()->has( 'swal' ) ) : ?>
    <script>
        Swal.fire({
            title: '<?= esc( session( 'swal' )[ 'title' ] ) ?>',
            text: '<?= esc( session( 'swal' )[ 'text' ] ) ?>',
            icon: '<?= esc( session( 'swal' )[ 'icon' ] ) ?>',
            confirmButtonText: '<?= esc( session( 'swal' )[ 'confirmButtonText' ] ?? 'OK' ) ?>',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });
    </script>
<?php endif; ?>
</body>

</html>