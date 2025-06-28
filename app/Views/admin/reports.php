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
            <div class="row">
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
                                        <div class="btn-group mb-3" role="group" aria-label="Export Buttons">
                                            <div class="mr-2 mt-1">
                                                <?= esc( session( 'selected_cropping_season_name' ) ) ?>
                                            </div>

                                            <!-- Cropping Season Dropdown Form -->
                                            <form id="seasonForm" action="<?= base_url( '/admin/reports/setSeasonView' ) ?>"
                                                method="post" style="display: none;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="season_data" id="seasonDataInput">
                                            </form>

                                            <!-- Visible Dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle float-end"
                                                    type="button" id="seasonDropdown" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Select Cropping Season
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="seasonDropdown">
                                                    <?php foreach ( $cropping_seasons as $s ) : ?>
                                                        <li>
                                                            <a href="#" class="dropdown-item select-season"
                                                                data-value="<?= $s[ 'cropping_season_tbl_id' ] . '|' . esc( $s[ 'season' ] ) . '|' . esc( $s[ 'year' ] ) ?>">
                                                                <?= esc( $s[ 'season' ] ) . ' ' . esc( $s[ 'year' ] ) ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>

                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle float-end"
                                                    type="button" id="listDropdown" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    View Lists
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="listDropdown">
                                                    <li><a class="dropdown-item dropdownListReports"
                                                            href="<?= base_url( '/admin/reports/setListView/seedrequests' ) ?>">Seed
                                                            Request
                                                            List</a>
                                                    </li>
                                                    <li><a class="dropdown-item dropdownListReports"
                                                            href="<?= base_url( '/admin/reports/setListView/beneficiaries' ) ?>">Beneficiaries
                                                            List</a>
                                                    </li>
                                                </ul>

                                            </div>
                                            <!-- Hidden Export Form -->
                                            <form id="excelExportForm"
                                                action="<?= base_url( '/admin/reports/exportToExcel' ) ?>" method="post"
                                                target="_blank" style="display: none;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="inventory_id" id="excelInventoryId">
                                            </form>

                                            <!-- Excel Button -->
                                            <a href="#" class="btn btn-sm btn-outline-success" id="exportExcelBtn">
                                                <i class="bi bi-file-earmark-excel"></i> Excel
                                            </a>

                                            <!-- Hidden PDF Export Form -->
                                            <form id="seedRequestPDFExportForm"
                                                action="<?= base_url( '/admin/reports/seedRequestExportToPDF' ) ?>"
                                                method="post" target="_blank" style="display: none;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="inventory_id" id="pdfInventoryId">
                                                <input type="hidden" name="seed_name" id="pdfSeedName">
                                            </form>

                                            <!-- PDF Button -->
                                            <a href="#" class="btn btn-sm btn-outline-danger" id="seedRequestExportPdfBtn">
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
                                                                data-seed-name="<?= esc( $item[ 'seed_name' ] ) ?>" role="tab"
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
                        </div>
                        <!-- BENEFICIARIES -->
                    <?php elseif ( $selectedList === 'beneficiaries' ) : ?>
                        <div class="card mt-3">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Beneficiaries List Reports</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="btn-group mb-3" role="group" aria-label="Export Buttons">
                                            <div class="mr-2 mt-1">
                                                <?= esc( session( 'selected_cropping_season_name' ) ) ?>
                                            </div>

                                            <!-- Cropping Season Dropdown Form -->
                                            <form id="seasonForm" action="<?= base_url( '/admin/reports/setSeasonView' ) ?>"
                                                method="post" style="display: none;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="season_data" id="seasonDataInput">
                                            </form>

                                            <!-- Visible Dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle float-end"
                                                    type="button" id="seasonDropdown" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Select Cropping Season
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="seasonDropdown">
                                                    <?php foreach ( $cropping_seasons as $s ) : ?>
                                                        <li>
                                                            <a href="#" class="dropdown-item select-season"
                                                                data-value="<?= $s[ 'cropping_season_tbl_id' ] . '|' . esc( $s[ 'season' ] ) . '|' . esc( $s[ 'year' ] ) ?>">
                                                                <?= esc( $s[ 'season' ] ) . ' ' . esc( $s[ 'year' ] ) ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>

                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle float-end"
                                                    type="button" id="listDropdown" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    View Lists
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="listDropdown">
                                                    <li><a class="dropdown-item dropdownListReports"
                                                            href="<?= base_url( '/admin/reports/setListView/seedrequests' ) ?>">Seed
                                                            Request
                                                            List</a>
                                                    </li>
                                                    <li><a class="dropdown-item dropdownListReports"
                                                            href="<?= base_url( '/admin/reports/setListView/beneficiaries' ) ?>">Beneficiaries
                                                            List</a>
                                                    </li>
                                                </ul>

                                            </div>
                                            <!-- Hidden Export Form -->
                                            <form id="excelExportForm"
                                                action="<?= base_url( '/admin/reports/exportToExcel' ) ?>" method="post"
                                                target="_blank" style="display: none;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="inventory_id" id="excelInventoryId">
                                            </form>

                                            <!-- Excel Button -->
                                            <a href="#" class="btn btn-sm btn-outline-success" id="exportExcelBtn">
                                                <i class="bi bi-file-earmark-excel"></i> Excel
                                            </a>

                                            <!-- Hidden PDF Export Form -->
                                            <form id="beneficiariesPDFExportForm"
                                                action="<?= base_url( '/admin/reports/beneficiariesExportToPDF' ) ?>"
                                                method="post" target="_blank" style="display: none;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="inventory_id" id="pdfInventoryId">
                                                <input type="hidden" name="seed_name" id="pdfSeedName">
                                            </form>

                                            <!-- PDF Button -->
                                            <a href="#" class="btn btn-sm btn-outline-danger"
                                                id="beneficiariesExportPdfBtn">
                                                <i class="bi bi-file-earmark-pdf"></i> PDF
                                            </a>
                                        </div>

                                    </div>

                                    <div class="col-lg-12">
                                        <div class="card mt-0 shadow border border-secondary">

                                            <div class="card-header">
                                                <ul class="nav nav-tabs card-header-tabs" id="beneficiariesReportsTabs"
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
                                                                            <th rowspan="2">No.</th>
                                                                            <th rowspan="2">RSBSA Reference No.</th>
                                                                            <th rowspan="2">Last Name</th>
                                                                            <th rowspan="2">First Name</th>
                                                                            <th rowspan="2">Middle Name</th>
                                                                            <th rowspan="2">Suffix & Ext.</th>
                                                                            <th rowspan="2">Barangay</th>
                                                                            <th rowspan="2">Municipality</th>
                                                                            <th rowspan="2">Province</th>
                                                                            <th rowspan="2">Birthdate<br>(mm/dd/yyyy)</th>
                                                                            <th rowspan="2">Gender<br>Male/Female</th>
                                                                            <th rowspan="2">Contact No.<br>(Mobile No.)</th>
                                                                            <th rowspan="2">Farm Area<br>(Hectares)</th>
                                                                            <th class="text-center" colspan="2">Voucher</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Reference No</th>
                                                                            <th>Date Received</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $i = 1; ?>
                                                                        <?php foreach ( $beneficiaries as $beneficiary ) : ?>
                                                                            <?php if ( $beneficiary[ 'inventory_tbl_id' ] != $item[ 'inventory_tbl_id' ] )
                                                                                continue; ?>

                                                                            <tr class="text-center">
                                                                                <td class="align-middle"><?= $i++ ?></td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'rsbsa_ref_no' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'last_name' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'first_name' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'middle_name' ] ?? '—' ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'suffix_and_ext' ] ?? '—' ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'brgy' ] ?? '—' ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'mun' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'prov' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?php
                                                                                    $rawBDate = $beneficiary[ 'b_date' ];
                                                                                    $dateObj  = DateTime::createFromFormat( 'm-d-Y', $rawBDate );
                                                                                    ?>

                                                                                    <?php if ( $dateObj ) : ?>
                                                                                        <?= $dateObj->format( 'F j, Y' ) ?>
                                                                                    <?php else : ?>
                                                                                        <span class="text-muted">—</span>
                                                                                    <?php endif; ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'gender' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'contact_no' ] ?? '—' ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'farm_area' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?= esc( $beneficiary[ 'ref_no' ] ) ?>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <?php
                                                                                    $rawDate = $beneficiary[ 'date_time_received' ];

                                                                                    // Create DateTime from the exact format used in DB
                                                                                    $dateObj = DateTime::createFromFormat( 'm-d-Y h:i:s A', $rawDate );

                                                                                    if ( $dateObj ) : ?>
                                                                                        <?= $dateObj->format( 'F j, Y' ) ?><br>
                                                                                        <small><?= $dateObj->format( 'h:i:s A' ) ?></small>
                                                                                    <?php else : ?>
                                                                                        <span class="text-muted">—</span>
                                                                                    <?php endif; ?>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabLinks = document.querySelectorAll('#beneficiariesReportsTabs .nav-link');

        // Restore active tab from localStorage
        const savedTabId = localStorage.getItem('activeBeneficiariesReportsTabs');
        if (savedTabId) {
            const savedTab = document.querySelector(`#beneficiariesReportsTabs .nav-link#${savedTabId}`);
            if (savedTab) {
                new bootstrap.Tab(savedTab).show();
            }
        }

        // Save active tab on click
        tabLinks.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (event) {
                localStorage.setItem('activeBeneficiariesReportsTabs', event.target.id);
            });
        });
    });
</script>