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
            <div class="card mt-3 text-center">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="seedRequestsInventoryTabs" role="tablist">
                        <?php $isFirst = true; ?>
                        <?php foreach ( $inventory as $item ) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $isFirst ? 'active' : '' ?>"
                                    id="tab-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>" data-bs-toggle="tab"
                                    data-bs-target="#tab-content-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>" role="tab"
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
                                                <th>No.</th>
                                                <th>Last Name</th>
                                                <th>First Name</th>
                                                <th>Middle Name</th>
                                                <th>Suffix & Ext.</th>
                                                <th>RSBSA Reference No.</th>
                                                <th>Name of Land Owner</th>
                                                <th>Verified Farm Area (Hectares)</th>
                                                <th>Date & Time Requested</th>
                                                <th>Date & Time Approved</th>
                                                <th>Date & Time Rejected</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
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
                                                    <td class="align-middle"><?= esc( $request[ 'last_name' ] ) ?></td>
                                                    <td class="align-middle"><?= esc( $request[ 'first_name' ] ) ?></td>
                                                    <td class="align-middle"><?= esc( $request[ 'middle_name' ] ?? '—' ) ?></td>
                                                    <td class="align-middle"><?= esc( $request[ 'suffix_and_ext' ] ?? '—' ) ?>
                                                    </td>
                                                    <td class="align-middle"><?= esc( $request[ 'rsbsa_ref_no' ] ) ?></td>
                                                    <td class="align-middle"><?= esc( $request[ 'name_land_owner' ] ) ?></td>
                                                    <td class="align-middle"><?= esc( $request[ 'farm_area' ] ) ?></td>
                                                    <td class="align-middle">
                                                        <?php
                                                        $rawRequested = $request[ 'date_time_requested' ];
                                                        $requestedObj = DateTime::createFromFormat( 'm-d-Y h:i A', $rawRequested );
                                                        if ( $requestedObj ) : ?>
                                                            <?= $requestedObj->format( 'F j, Y' ) ?><br>
                                                            <small>
                                                                <?= $requestedObj->format( 'h:i A' ) ?>
                                                            </small>
                                                        <?php else : ?>
                                                            <span class="text-muted">—</span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td class="align-middle">
                                                        <?php
                                                        $rawApproved = $request[ 'date_time_approved' ];
                                                        $approvedObj = DateTime::createFromFormat( 'm-d-Y h:i A', $rawApproved );
                                                        if ( $approvedObj ) : ?>
                                                            <?= $approvedObj->format( 'F j, Y' ) ?><br>
                                                            <small>
                                                                <?= $approvedObj->format( 'h:i A' ) ?>
                                                            </small> <?php else : ?>
                                                            <span class="text-muted">—</span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td class="align-middle">
                                                        <?php
                                                        $rawRejected = $request[ 'date_time_rejected' ];
                                                        $rejectedObj = DateTime::createFromFormat( 'm-d-Y h:i A', $rawRejected );
                                                        if ( $rejectedObj ) : ?>
                                                            <?= $rejectedObj->format( 'F j, Y' ) ?><br>
                                                            <small><?= $rejectedObj->format( 'h:i A' ) ?></small>
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
                                                                'rsbsa'      => $rsbsa,
                                                                'season'     => $season,
                                                                'year'       => $year,
                                                                'seed_name'  => $seedName,
                                                                'seed_class' => $seedClass
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
                                                                        <button type="submit" class="btn btn-sm btn-outline-primary"
                                                                            title="Approve">
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
                                                                        <button type="submit" class="btn btn-sm btn-outline-secondary"
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
                                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                            title="Reject">
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
                                                                        <button type="submit" class="btn btn-sm btn-outline-secondary"
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const swalFormHandler = (selector, title, text) => {
            document.querySelectorAll(selector).forEach(function (form) {
                form.addEventListener("submit", function (event) {
                    event.preventDefault();
                    Swal.fire({
                        title: title,
                        text: text,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, continue",
                        cancelButtonText: "Cancel",
                        customClass: {
                            confirmButton: "btn btn-sm btn-primary me-2",
                            cancelButton: "btn btn-sm btn-secondary"
                        },
                        buttonsStyling: false
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            showLoader(); // Optional
                            form.submit();
                        }
                    });
                });
            });
        };

        // Bind each action with appropriate confirmation
        swalFormHandler(".approve-form", "Approve request?", "Are you sure you want to approve this request?");
        swalFormHandler(".undo-approve-form", "Undo approval?", "Are you sure you want to undo this approval?");
        swalFormHandler(".reject-form", "Reject request?", "Are you sure you want to reject this request?");
        swalFormHandler(".undo-reject-form", "Undo rejection?", "Are you sure you want to undo this rejection?");
    });
</script>