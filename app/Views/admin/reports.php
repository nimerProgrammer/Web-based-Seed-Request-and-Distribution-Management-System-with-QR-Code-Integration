<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header border-bottom">
        <div class="container-fluid">
            <div class="row">
                <!-- Breadcrumb -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-start">
                        <li class="breadcrumb-item"><a href="<?= base_url( 'admin' ) ?>">Home</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle float-end" type="button"
                            id="listDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            View Lists
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="listDropdown">
                            <li><a class="dropdown-item dropdownListReports"
                                    href="<?= base_url( '/admin/reports/setListView/seedrequests' ) ?>">Seed Request
                                    List</a>
                            </li>
                            <li><a class="dropdown-item dropdownListReports"
                                    href="<?= base_url( '/admin/reports/setListView/beneficiaries' ) ?>">Beneficiaries
                                    List</a>
                            </li>
                        </ul>

                    </div>
                </div>

                <?php $selectedList = session( 'selected_list' ); ?>

                <div class="col-lg-12">
                    <?php if ( $selectedList === 'seedrequests' ) : ?>

                        <div class="card mt-3">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Seed Request List Reports</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="btn-group float-end mb-3" role="group" aria-label="Export Buttons">
                                            <!-- Hidden Export Form -->
                                            <form id="excelExportForm"
                                                action="<?= base_url( '/admin/reports/exportToExcel' ) ?>" method="post"
                                                target="_blank" style="display: none;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="inventory_id" id="excelInventoryId">
                                            </form>

                                            <!-- Excel Button -->
                                            <a href="#" class="btn btn-outline-success" id="exportExcelBtn">
                                                <i class="bi bi-file-earmark-excel"></i> Excel
                                            </a>

                                            <a href="<?= base_url( '/admin/reports/exportPDF' ) ?>"
                                                class="btn btn-outline-danger">
                                                <i class="bi bi-file-earmark-pdf"></i> PDF
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="card mt-0 shadow border border-secondary">
                                            <div class="card-header">
                                                <ul class="nav nav-tabs card-header-tabs" id="seedRequestsReportsTabs"
                                                    role="tablist">
                                                    <?php $isFirst = true; ?>
                                                    <?php foreach ( $inventory as $item ) : ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link <?= $isFirst ? 'active' : '' ?>"
                                                                id="tab-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>"
                                                                data-bs-toggle="tab"
                                                                data-bs-target="#tab-content-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>"
                                                                role="tab"
                                                                aria-controls="tab-content-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>"
                                                                aria-selected="<?= $isFirst ? 'true' : 'false' ?>">
                                                                <?= esc( $item[ 'seed_name' ] ) ?>
                                                            </a>
                                                        </li>
                                                        <?php $isFirst = false; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>

                                            <div class="card-body">
                                                <div class="tab-content">
                                                    <?php $isFirst = true; ?>
                                                    <?php foreach ( $inventory as $item ) : ?>
                                                        <div class=" tab-pane fade <?= $isFirst ? 'show active' : '' ?>"
                                                            id="tab-content-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>"
                                                            role="tabpanel"
                                                            aria-labelledby="tab-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>">
                                                            <div class="table-responsive">
                                                                <table class="set-Table table table-bordered table-hover">
                                                                    <thead class="table-secondary">
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th>Last Name</th>
                                                                            <th>First Name</th>
                                                                            <th>Middle Name</th>
                                                                            <th>Suffix & Ext.</th>
                                                                            <th>RSBSA Reference No.</th>
                                                                            <th>Name of Land Owner</th>
                                                                            <th>Verified Farm Area (Hectares)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $i = 1; ?>
                                                                        <?php foreach ( $seed_requests as $request ) : ?>
                                                                            <?php if ( $request[ 'inventory_tbl_id' ] != $item[ 'inventory_tbl_id' ] )
                                                                                continue; ?>

                                                                            <!-- render row -->
                                                                            <tr class="text-center">
                                                                                <td class="align-middle"><?= $i++ ?></td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $request[ 'last_name' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $request[ 'first_name' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $request[ 'middle_name' ] ?? 'N/A' ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $request[ 'suffix_and_ext' ] ?? 'N/A' ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $request[ 'rsbsa_ref_no' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $request[ 'name_land_owner' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $request[ 'farm_area' ] ) ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <?php $isFirst = false; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ( $selectedList === 'beneficiaries' ) : ?>
                            <div class="card mt-3">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Beneficiaries List Reports</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Put your Beneficiaries List table or content here -->
                                    <p>This is the beneficiaries list content.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabLinks = document.querySelectorAll('#seedRequestsReportsTabs .nav-link');

        // Restore active tab from localStorage
        const savedTabId = localStorage.getItem('activeSeedRequestsReportsTabs');
        if (savedTabId) {
            const savedTab = document.querySelector(`#seedRequestsReportsTabs .nav-link#${savedTabId}`);
            if (savedTab) {
                new bootstrap.Tab(savedTab).show();
            }
        }

        // Save active tab on click
        tabLinks.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (event) {
                localStorage.setItem('activeSeedRequestsReportsTabs', event.target.id);
            });
        });
    });
</script>