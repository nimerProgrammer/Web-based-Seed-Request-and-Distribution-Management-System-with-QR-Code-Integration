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
<!-- Right Sidebar (Control Sidebar) -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Content inside -->
    <div class="p-3 control-sidebar-content">
        <h5>Notifications</h5>
        <hr class="mb-2">
        <ul class="list-unstyled">
            <li>
                <i class="bi bi-bell text-warning"></i> New seed request submitted
                <br><small class="text-muted">2 mins ago</small>
            </li>
            <li class="mt-2">
                <i class="bi bi-check-circle text-success"></i> Request #102 approved
                <br><small class="text-muted">10 mins ago</small>
            </li>
            <li>
                <i class="bi bi-bell text-warning"></i> New seed request submitted
                <br><small class="text-muted">2 mins ago</small>
            </li>
            <li class="mt-2">
                <i class="bi bi-check-circle text-success"></i> Request #102 approved
                <br><small class="text-muted">10 mins ago</small>
            </li>
            <li>
                <i class="bi bi-bell text-warning"></i> New seed request submitted
                <br><small class="text-muted">2 mins ago</small>
            </li>
            <li class="mt-2">
                <i class="bi bi-check-circle text-success"></i> Request #102 approved
                <br><small class="text-muted">10 mins ago</small>
            </li>
            <li>
                <i class="bi bi-bell text-warning"></i> New seed request submitted
                <br><small class="text-muted">2 mins ago</small>
            </li>
            <li class="mt-2">
                <i class="bi bi-check-circle text-success"></i> Request #102 approved
                <br><small class="text-muted">10 mins ago</small>
            </li>
            <li>
                <i class="bi bi-bell text-warning"></i> New seed request submitted
                <br><small class="text-muted">2 mins ago</small>
            </li>
            <li class="mt-2">
                <i class="bi bi-check-circle text-success"></i> Request #102 approved
                <br><small class="text-muted">10 mins ago</small>
            </li>
            <li>
                <i class="bi bi-bell text-warning"></i> New seed request submitted
                <br><small class="text-muted">2 mins ago</small>
            </li>
            <li class="mt-2">
                <i class="bi bi-check-circle text-success"></i> Request #102 approved
                <br><small class="text-muted">10 mins ago</small>
            </li>
            <li>
                <i class="bi bi-bell text-warning"></i> New seed request submitted
                <br><small class="text-muted">2 mins ago</small>
            </li>
            <li class="mt-2">
                <i class="bi bi-check-circle text-success"></i> Request #102 approved
                <br><small class="text-muted">10 mins ago</small>
            </li>
            <li>
                <i class="bi bi-bell text-warning"></i> New seed request submitted
                <br><small class="text-muted">2 mins ago</small>
            </li>
            <li class="mt-2">
                <i class="bi bi-check-circle text-success"></i> Request #102 approved
                <br><small class="text-muted">10 mins ago</small>
            </li>
        </ul>
    </div>
</aside>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27"
    integrity="sha256-S/HO+Ru8zrLDmcjzwxjl18BQYDCvFDD7mPrwJclX6U8=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha256-CDOy6cOibCWEdsRiZuaHf8dSGGJRYuBGC+mjoJimHGw=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"
    integrity="sha256-u2yoem2HtOCQCnsp3fO9sj5kUrL+7hOAfm8es18AFjw=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"
    integrity="sha256-JDYsFFqB4eL9lRhcQwDSWVr7LK3Z8VgMLdzpW8GbIIQ=" crossorigin="anonymous"></script>



<script src="<?= base_url( 'templates/js/season-watcher.js?v=6.6.6' ) ?>"></script>
<script src="<?= base_url( 'templates/js/adminscript.js?v=7.1.3' ) ?>"></script>

<?php if ( session()->get( 'title' ) === 'Profile' && session()->get( 'current_tab' ) === 'profile' ) : ?>
    <script src="<?= base_url( 'templates/js/adminProfileScript.js?v=6.6.6' ) ?>"></script>
<?php endif; ?>

<?php if ( session()->get( 'title' ) === 'Dashboard' && session()->get( 'current_tab' ) === 'dashboard' ) : ?>
    <script src="<?= base_url( 'templates/js/adminDashboardScript.js?v=7.7.7' ) ?>"></script>
    <script src="<?= base_url( 'templates/js/adminDashboardSeasonUpdate.js?v=7.7.7' ) ?>"></script>
    <script src="<?= base_url( 'templates/js/adminDashboardTableUpdate.js?v=7.7.7' ) ?>"></script>

<?php endif; ?>

<?php if ( session()->has( 'swal' ) ) : ?>
    <script>
        Swal.fire({
            title: '<?= esc( session( 'swal' )[ 'title' ] ) ?>',
            text: '<?= esc( session( 'swal' )[ 'text' ] ) ?>',
            icon: '<?= esc( session( 'swal' )[ 'icon' ] ) ?>',
            timer: 4000, // 4 seconds
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