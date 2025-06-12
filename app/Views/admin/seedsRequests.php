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
                    <ul class="nav nav-tabs card-header-tabs" id="inventoryTabs" role="tablist">
                        <?php $isFirst = true; ?>
                        <?php foreach ( $inventory as $item ) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $isFirst ? 'active' : '' ?>"
                                    id="tab-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>" data-bs-toggle="tab"
                                    href="#tab-content-<?= esc( $item[ 'inventory_tbl_id' ] ) ?>" role="tab"
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
                                                <th>Ext. Name</th>
                                                <th>RSBSA Reference No.</th>
                                                <th>Name of Land Owner</th>
                                                <th>Verified Farm Area (Hectares)</th>
                                                <th>Date & Time Requested</th>
                                                <th>Date & Time Approved</th>
                                                <th>Date & Time Rejected</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach ( $seed_requests as $request ) : ?>
                                                <?php if ( $request[ 'inventory_tbl_id' ] != $item[ 'inventory_tbl_id' ] )
                                                    continue; ?>

                                                <!-- render row -->
                                                <tr>
                                                    <td><?= $i++ ?></td>
                                                    <td><?= esc( $request[ 'last_name' ] ) ?></td>
                                                    <td><?= esc( $request[ 'first_name' ] ) ?></td>
                                                    <td><?= esc( $request[ 'middle_name' ] ?? '—' ) ?></td>
                                                    <td><?= esc( $request[ 'suffix_and_ext' ] ?? '—' ) ?></td>
                                                    <td><?= esc( $request[ 'rsbsa_ref_no' ] ) ?></td>
                                                    <td><?= esc( $request[ 'name_land_owner' ] ) ?></td>
                                                    <td><?= esc( $request[ 'farm_area' ] ) ?></td>
                                                    <td>
                                                        <?= !empty( $request[ 'date_time_requested' ] ) ? date( 'F j, Y', strtotime( $request[ 'date_time_requested' ] ) ) : '—' ?><br>
                                                        <small><?= !empty( $request[ 'date_time_requested' ] ) ? date( 'h:i A', strtotime( $request[ 'date_time_requested' ] ) ) : '' ?></small>
                                                    </td>
                                                    <td>
                                                        <?= !empty( $request[ 'date_time_approved' ] ) ? date( 'F j, Y', strtotime( $request[ 'date_time_approved' ] ) ) : '—' ?><br>
                                                        <small><?= !empty( $request[ 'date_time_approved' ] ) ? date( 'h:i A', strtotime( $request[ 'date_time_approved' ] ) ) : '' ?></small>
                                                    </td>
                                                    <td>
                                                        <?= !empty( $request[ 'date_time_rejected' ] ) ? date( 'F j, Y', strtotime( $request[ 'date_time_rejected' ] ) ) : '—' ?><br>
                                                        <small><?= !empty( $request[ 'date_time_rejected' ] ) ? date( 'h:i A', strtotime( $request[ 'date_time_rejected' ] ) ) : '' ?></small>
                                                    </td>

                                                    <td>
                                                        <?php if ( $request[ 'status' ] === 'Approved' ) : ?>
                                                            <span class="badge bg-success">Approved</span>
                                                        <?php elseif ( $request[ 'status' ] === 'Rejected' ) : ?>
                                                            <span class="badge bg-danger">Rejected</span>
                                                        <?php else : ?>
                                                            <span class="badge bg-primary">Pending</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-outline-primary" title="Approve">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger" title="Reject">
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
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