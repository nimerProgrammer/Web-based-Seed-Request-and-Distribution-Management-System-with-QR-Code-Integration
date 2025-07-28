<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header border-bottom">
        <div class="container-fluid">
            <div class="row">
                <!-- Breadcrumb -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-start">
                        <li class="breadcrumb-item"><a href="<?= base_url( 'admin' ) ?>">Home</a></li>
                        <li class="breadcrumb-item active">Beneficiaries</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">List of Beneficiaries</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <?php $barangays = getBarangayList(); ?>

                            <div class="btn-group mb-3" role="group" aria-label="Export Buttons">
                                <div class="mr-3 mt-1 text-bold">
                                    Barangay:
                                    <?= esc( session( 'selected_beneficiaries_barangay_name' ) ?? 'Select a Barangay' ) ?>
                                </div>

                                <!-- Hidden form -->
                                <form id="barangayForm"
                                    action="<?= base_url( '/admin/beneficiaries/setBarangayView' ) ?>" method="post"
                                    style="display: none;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="barangay_data" id="barangayDataInput">
                                </form>

                                <!-- Visible Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle float-end"
                                        type="button" id="barangayDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Select Barangay
                                    </button>
                                    <ul style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc;"
                                        class="dropdown-menu" aria-labelledby="barangayDropdown">
                                        <?php foreach ( $barangays as $b ) : ?>
                                            <?php
                                            $isActive = session( 'selected_beneficiaries_barangay_name' ) === $b[ 'barangay_name' ];
                                            ?>
                                            <li>
                                                <a href="#"
                                                    class="dropdown-item select-barangay <?= $isActive ? 'active' : '' ?>"
                                                    data-value="<?= esc( $b[ 'barangay_name' ] ) ?>">
                                                    <?= esc( $b[ 'barangay_name' ] ) ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-12">

                            <div class="card mt-0 shadow border border-secondary">
                                <div class="card-header">
                                    <ul class="nav nav-tabs card-header-tabs" id="beneficiariesInventoryTabs"
                                        role="tablist">
                                        <?php $isFirst = true; ?>
                                        <?php foreach ( $inventory as $item ) : ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?= $isFirst ? 'active' : '' ?>"
                                                    id="tab-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>" data-bs-toggle="tab"
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
                                                id="tab-content-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>" role="tabpanel"
                                                aria-labelledby="tab-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>">
                                                <div class="table-responsive">
                                                    <table class="set-Table table table-bordered table-hover">
                                                        <thead class="table-secondary">
                                                            <tr>
                                                                <th rowspan="2">No.</th>
                                                                <th rowspan="2">RSBSA Reference No.</th>
                                                                <th colspan="4" class="text-center">Name of Farmer</th>

                                                                <th rowspan="2">Barangay</th>
                                                                <th rowspan="2">Municipality</th>
                                                                <th rowspan="2">Province</th>
                                                                <th rowspan="2">Birthdate<br>(mm/dd/yyyy)</th>
                                                                <th rowspan="2">Gender<br>Male/Female</th>
                                                                <th rowspan="2">Contact No.<br>(Mobile No.)</th>
                                                                <th rowspan="2">Farm Area<br>(Hectares)</th>
                                                                <th rowspan="2">Date & Time Received</th>
                                                                <th rowspan="2" class="text-center">Status</th>
                                                                <th rowspan="2" class="text-center">Action</th>
                                                            </tr>
                                                            <tr>
                                                                <th>Last Name</th>
                                                                <th>First Name</th>
                                                                <th>Middle Name</th>
                                                                <th>Suffix & Ext.</th>
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
                                                                    <td class="align-middle"><?= esc( $beneficiary[ 'mun' ] ) ?>
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <?= esc( $beneficiary[ 'prov' ] ) ?>
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <?php
                                                                        $rawBDate = $beneficiary[ 'b_date' ];
                                                                        $dateObj  = DateTime::createFromFormat( 'Y-m-d', $rawBDate );
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
                                                                    <td class="align-middle">
                                                                        <?php if ( $beneficiary[ 'status' ] === 'Received' ) : ?>
                                                                            <span class="badge bg-success">Received</span>
                                                                        <?php else : ?>
                                                                            <span
                                                                                class="badge bg-primary"><?= esc( $beneficiary[ 'status' ] ) ?></span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <div class="btn-group" role="group">
                                                                            <?php
                                                                            $isReceived    = $beneficiary[ 'status' ] === 'Received';
                                                                            $beneficiaryId = $beneficiary[ 'beneficiaries_tbl_id' ];
                                                                            ?>

                                                                            <?php if ( $isReceived ) : ?><!-- Undo Receive Button -->
                                                                                <form method="post"
                                                                                    action="<?= base_url( '/admin/beneficiaries/undoReceive/' . $beneficiaryId ) ?>"
                                                                                    class="undo-receive-form"
                                                                                    style="display:inline;">
                                                                                    <?= csrf_field() ?>
                                                                                    <input type="hidden" name="beneficiaries_tbl_id"
                                                                                        value="<?= esc( $beneficiaryId ) ?>">
                                                                                    <input type="hidden" name="rsbsa_ref_no"
                                                                                        value="<?= esc( $beneficiary[ 'rsbsa_ref_no' ] ) ?>">
                                                                                    <input type="hidden" name="first_name"
                                                                                        value="<?= esc( $beneficiary[ 'first_name' ] ) ?>">
                                                                                    <input type="hidden" name="middle_name"
                                                                                        value="<?= esc( $beneficiary[ 'middle_name' ] ?? '' ) ?>">
                                                                                    <input type="hidden" name="last_name"
                                                                                        value="<?= esc( $beneficiary[ 'last_name' ] ) ?>">
                                                                                    <input type="hidden" name="suffix_and_ext"
                                                                                        value="<?= esc( $beneficiary[ 'suffix_and_ext' ] ?? '' ) ?>">

                                                                                    <button type="submit"
                                                                                        class="btn btn-sm btn-outline-secondary"
                                                                                        data-bs-toggle="tooltip"
                                                                                        title="Undo Receive">
                                                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                                                    </button>
                                                                                </form>
                                                                            <?php else : ?>
                                                                                <form method="post"
                                                                                    action="<?= base_url( '/admin/beneficiaries/markReceived/' . $beneficiaryId ) ?>"
                                                                                    class="mark-receive-form"
                                                                                    style="display:inline;">

                                                                                    <?= csrf_field() ?>

                                                                                    <input type="hidden" name="rsbsa_ref_no"
                                                                                        value="<?= esc( $beneficiary[ 'rsbsa_ref_no' ] ) ?>">
                                                                                    <input type="hidden" name="first_name"
                                                                                        value="<?= esc( $beneficiary[ 'first_name' ] ) ?>">
                                                                                    <input type="hidden" name="middle_name"
                                                                                        value="<?= esc( $beneficiary[ 'middle_name' ] ?? '' ) ?>">
                                                                                    <input type="hidden" name="last_name"
                                                                                        value="<?= esc( $beneficiary[ 'last_name' ] ) ?>">
                                                                                    <input type="hidden" name="suffix_and_ext"
                                                                                        value="<?= esc( $beneficiary[ 'suffix_and_ext' ] ?? '' ) ?>">

                                                                                    <button type="submit"
                                                                                        class="btn btn-sm btn-outline-primary"
                                                                                        data-bs-toggle="tooltip"
                                                                                        title="Mark as Received">
                                                                                        <i class="bi bi-check-circle"></i>
                                                                                    </button>
                                                                                </form>

                                                                            <?php endif; ?>

                                                                        </div>
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
        </div>

    </section>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabLinks = document.querySelectorAll('#beneficiariesInventoryTabs .nav-link');

        // Restore active tab from localStorage
        const savedTabId = localStorage.getItem('activeBeneficiariesInventoryTab');
        if (savedTabId) {
            const savedTab = document.querySelector(`#beneficiariesInventoryTabs .nav-link#${savedTabId}`);
            if (savedTab) {
                const tabInstance = new bootstrap.Tab(savedTab);
                tabInstance.show();
            }
        }

        // Save active tab on tab change
        tabLinks.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (event) {
                localStorage.setItem('activeBeneficiariesInventoryTab', event.target.id);
            });
        });
    });
</script>