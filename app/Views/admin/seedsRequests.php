<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header border-bottom">
        <div class="container-fluid">
            <div class="row">
                <!-- Breadcrumb -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-start">
                        <li class="breadcrumb-item"><a href="<?= base_url( 'admin' ) ?>">Home</a></li>
                        <li class="breadcrumb-item active">Seed Requests</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active">1st CROPPING 2025</li>
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
                    <h5 class="mb-0">List of Seed Requests</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <?php $barangays = getBarangayList(); ?>

                            <div class="btn-group mb-3" role="group" aria-label="Export Buttons">
                                <div class="mr-3 mt-1 text-bold">
                                    Barangay:
                                    <?= esc( session( 'selected_seedrequests_barangay_name' ) ?? 'Select a Barangay' ) ?>
                                </div>

                                <!-- Hidden form -->
                                <form id="barangayForm"
                                    action="<?= base_url( '/admin/seedrequests/setBarangayView' ) ?>" method="post"
                                    style="display: none;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="barangay_data" id="barangayDataInput">
                                </form>

                                <!-- Visible Dropdown -->
                                <div class="dropdown">
                                    <!-- <style>
                                        .dropdown-item:hover {
                                            background-color: var(--bs-secondary);
                                            color: #fff;
                                        }
                                    </style> -->
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle float-end"
                                        type="button" id="barangayDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Select Barangay
                                    </button>
                                    <ul style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc;"
                                        class="dropdown-menu" aria-labelledby="barangayDropdown">
                                        <?php foreach ( $barangays as $b ) : ?>
                                            <?php
                                            $isActive = session( 'selected_seedrequests_barangay_name' ) === $b[ 'barangay_name' ];
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
                                    <ul class="nav nav-tabs card-header-tabs" id="seedRequestsInventoryTabs"
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
                                                                <th colspan="4" class="text-center">Name of Farmer</th>

                                                                <th rowspan="2">RSBSA Reference No.</th>
                                                                <th rowspan="2">Name of Land Owner</th>
                                                                <th rowspan="2">Verified Farm Area (Hectares)</th>
                                                                <th rowspan="2">Date & Time Requested</th>
                                                                <th rowspan="2">Date & Time Approved</th>
                                                                <th rowspan="2">Date & Time Rejected</th>
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
                                                                        <?= esc( $request[ 'middle_name' ] ?? '—' ) ?>
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <?= esc( $request[ 'suffix_and_ext' ] ?? '—' ) ?>
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
                                                                    <td class="align-middle">
                                                                        <?php
                                                                        $rawRequested = $request[ 'date_time_requested' ];
                                                                        $requestedObj = DateTime::createFromFormat( 'm-d-Y h:i:s A', $rawRequested );
                                                                        if ( $requestedObj ) : ?>
                                                                            <?= $requestedObj->format( 'F j, Y' ) ?><br>
                                                                            <small>
                                                                                <?= $requestedObj->format( 'h:i:s A' ) ?>
                                                                            </small>
                                                                        <?php else : ?>
                                                                            <span class="text-muted">—</span>
                                                                        <?php endif; ?>
                                                                    </td>

                                                                    <td class="align-middle">
                                                                        <?php
                                                                        $rawApproved = $request[ 'date_time_approved' ];
                                                                        $approvedObj = DateTime::createFromFormat( 'm-d-Y h:i:s A', $rawApproved );
                                                                        if ( $approvedObj ) : ?>
                                                                            <?= $approvedObj->format( 'F j, Y' ) ?><br>
                                                                            <small>
                                                                                <?= $approvedObj->format( 'h:i:s A' ) ?>
                                                                            </small> <?php else : ?>
                                                                            <span class="text-muted">—</span>
                                                                        <?php endif; ?>
                                                                    </td>

                                                                    <td class="align-middle">
                                                                        <?php
                                                                        $rawRejected = $request[ 'date_time_rejected' ];
                                                                        $rejectedObj = DateTime::createFromFormat( 'm-d-Y h:i:s A', $rawRejected );
                                                                        if ( $rejectedObj ) : ?>
                                                                            <?= $rejectedObj->format( 'F j, Y' ) ?><br>
                                                                            <small><?= $rejectedObj->format( 'h:i:s A' ) ?></small>
                                                                        <?php else : ?>
                                                                            <span class="text-muted">—</span>
                                                                        <?php endif; ?>
                                                                    </td>

                                                                    <td class="align-middle">
                                                                        <?php if ( $request[ 'status' ] === 'Approved' ) : ?>
                                                                            <span class="badge bg-success">Approved</span>
                                                                        <?php elseif ( $request[ 'status' ] === 'Rejected' ) : ?>
                                                                            <span class="badge bg-danger">Rejected</span>
                                                                        <?php else : ?>
                                                                            <span class="badge bg-primary">Pending</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <div class="btn-group" role="group">
                                                                            <?php
                                                                            $isApproved = $request[ 'status' ] === 'Approved';
                                                                            $isRejected = $request[ 'status' ] === 'Rejected';
                                                                            $isPending  = $request[ 'status' ] === 'Pending';
                                                                            $isReceived = isset( $request[ 'beneficiary_status' ] ) && $request[ 'beneficiary_status' ] === 'Received';

                                                                            $season    = $request[ 'season' ];
                                                                            $year      = $request[ 'year' ];
                                                                            $seedName  = $request[ 'seed_name' ];
                                                                            $seedClass = $request[ 'seed_class' ];
                                                                            $rsbsa     = $request[ 'rsbsa_ref_no' ];

                                                                            $qrCode = "{$season}-{$year}-{$seedName}-{$seedClass}-{$rsbsa}";

                                                                            $formData = [ 
                                                                                'rsbsa'          => $rsbsa,
                                                                                'season'         => $season,
                                                                                'year'           => $year,
                                                                                'seed_name'      => $seedName,
                                                                                'seed_class'     => $seedClass,
                                                                                'first_name'     => $request[ 'first_name' ],
                                                                                'middle_name'    => $request[ 'middle_name' ] ?? '',
                                                                                'last_name'      => $request[ 'last_name' ],
                                                                                'suffix_and_ext' => $request[ 'suffix_and_ext' ] ?? ''
                                                                            ];

                                                                            ?>


                                                                            <?php if ( $isReceived ) : ?>
                                                                                <!-- If status is Received, show only dash -->
                                                                                —
                                                                            <?php else : ?>
                                                                                <!-- Approve -->
                                                                                <?php if ( !$isApproved && !$isRejected ) : ?>
                                                                                    <form method="post"
                                                                                        action="<?= base_url( '/admin/seedrequests/approve/' . $request[ 'seed_requests_tbl_id' ] ) ?>"
                                                                                        class="approve-form d-inline">
                                                                                        <?= csrf_field() ?>
                                                                                        <?php foreach ( $formData as $name => $value ) : ?>
                                                                                            <input type="hidden" name="<?= esc( $name ) ?>"
                                                                                                value="<?= esc( $value ) ?>">
                                                                                        <?php endforeach; ?>
                                                                                        <button type="submit"
                                                                                            class="btn btn-sm btn-outline-primary"
                                                                                            data-bs-toggle="tooltip" title="Approve">
                                                                                            <i class="bi bi-check-circle"></i>
                                                                                        </button>
                                                                                    </form>
                                                                                <?php endif; ?>

                                                                                <!-- Undo Approve -->
                                                                                <?php if ( $isApproved ) : ?>
                                                                                    <form method="post"
                                                                                        action="<?= base_url( '/admin/seedrequests/undoApproved/' . $request[ 'seed_requests_tbl_id' ] ) ?>"
                                                                                        class="undo-approve-form d-inline">
                                                                                        <?= csrf_field() ?>
                                                                                        <?php foreach ( $formData as $name => $value ) : ?>
                                                                                            <input type="hidden" name="<?= esc( $name ) ?>"
                                                                                                value="<?= esc( $value ) ?>">
                                                                                        <?php endforeach; ?>
                                                                                        <button type="submit"
                                                                                            class="btn btn-sm btn-outline-secondary"
                                                                                            data-bs-toggle="tooltip"
                                                                                            title="Undo Approve">
                                                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                                                        </button>
                                                                                    </form>
                                                                                <?php endif; ?>

                                                                                <!-- Reject -->
                                                                                <?php if ( !$isApproved && !$isRejected ) : ?>
                                                                                    <form method="post"
                                                                                        action="<?= base_url( '/admin/seedrequests/reject/' . $request[ 'seed_requests_tbl_id' ] ) ?>"
                                                                                        class="reject-form d-inline">
                                                                                        <?= csrf_field() ?>
                                                                                        <?php foreach ( $formData as $name => $value ) : ?>
                                                                                            <input type="hidden" name="<?= esc( $name ) ?>"
                                                                                                value="<?= esc( $value ) ?>">
                                                                                        <?php endforeach; ?>
                                                                                        <button type="submit"
                                                                                            class="btn btn-sm btn-outline-danger"
                                                                                            data-bs-toggle="tooltip" title="Reject">
                                                                                            <i class="bi bi-x-circle"></i>
                                                                                        </button>
                                                                                    </form>
                                                                                <?php endif; ?>

                                                                                <!-- Undo Reject -->
                                                                                <?php if ( $isRejected ) : ?>
                                                                                    <form method="post"
                                                                                        action="<?= base_url( '/admin/seedrequests/undoRejected/' . $request[ 'seed_requests_tbl_id' ] ) ?>"
                                                                                        class="undo-reject-form d-inline">
                                                                                        <?= csrf_field() ?>
                                                                                        <?php foreach ( $formData as $name => $value ) : ?>
                                                                                            <input type="hidden" name="<?= esc( $name ) ?>"
                                                                                                value="<?= esc( $value ) ?>">
                                                                                        <?php endforeach; ?>
                                                                                        <button type="submit"
                                                                                            class="btn btn-sm btn-outline-secondary"
                                                                                            data-bs-toggle="tooltip"
                                                                                            title="Undo Reject">
                                                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                                                        </button>
                                                                                    </form>
                                                                                <?php endif; ?>
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
        const tabLinks = document.querySelectorAll('#seedRequestsInventoryTabs .nav-link');

        // Restore active tab from localStorage
        const savedTabId = localStorage.getItem('activeInventoryTab');
        if (savedTabId) {
            const savedTab = document.querySelector(`#seedRequestsInventoryTabs .nav-link#${savedTabId}`);
            if (savedTab) {
                new bootstrap.Tab(savedTab).show();
            }
        }

        // Save active tab on click
        tabLinks.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (event) {
                localStorage.setItem('activeInventoryTab', event.target.id);
            });
        });
    });
</script>