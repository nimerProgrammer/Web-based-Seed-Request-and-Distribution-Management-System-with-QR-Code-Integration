<!-- Main Content -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="border-bottom">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-start">
                            <li class="breadcrumb-item"><a href="<?= base_url( '/' ) ?>">Home</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="border-bottom">
                <h6 class="text-bold">Personal Information</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-transparent  table-hover">
                    <tbody>
                        <tr>
                            <td class="align-middle">
                                <?= session()->get( 'public_user_fullname' ) ?>
                            </td>
                            <td>
                                <i class="bi bi-pencil-square text-primary fs-3 float-end" data-bs-toggle="tooltip"
                                    title="Edit"></i>

                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle">Gerald M Nimer</td>
                            <td>
                                <i class="bi bi-pencil-square text-primary fs-3 float-end" data-bs-toggle="tooltip"
                                    title="Edit"></i>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </section>
</div>
<style>
    .table-transparent,
    .table-transparent th,
    .table-transparent td,
    .table-transparent thead,
    .table-transparent tbody,
    .table-transparent tr {
        background-color: transparent !important;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        $("a[data-mobile-tooltip]").on("click", function (e) {
            if (window.innerWidth < 768) { // mobile width
                e.preventDefault(); // optional
                Swal.fire({
                    text: $(this).data("mobile-tooltip"),
                    icon: "info",
                    timer: 2000,
                    showConfirmButton: false,
                });
            }
        });

        $("a[data-mobile-tooltip]").on("click", function (e) {
            if (window.innerWidth < 768) { // mobile width
                e.preventDefault(); // optional
                Swal.fire({
                    text: $(this).data("mobile-tooltip"),
                    icon: "info",
                    timer: 2000,
                    showConfirmButton: false,
                });
            }
        });

    });

</script>