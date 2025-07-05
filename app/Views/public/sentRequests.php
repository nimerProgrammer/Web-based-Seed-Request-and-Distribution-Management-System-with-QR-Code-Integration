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
                            <li class="breadcrumb-item active">Sent Requests</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <?php if ( !empty( $seed_requests ) ) : ?>
                <?php foreach ( $seed_requests as $request ) : ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <?= esc( $request[ 'seed_name' ] ) ?> (<?= esc( $request[ 'seed_class' ] ) ?>) —
                                <?= esc( $request[ 'season' ] ) ?>         <?= esc( $request[ 'year' ] ) ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="set-Table table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <td class="font-weight-bold">Date Requested:</td>
                                            <td>
                                                <?php
                                                $rawRequested = $request[ 'date_time_requested' ];
                                                $requestedObj = DateTime::createFromFormat( 'm-d-Y H:i:s A', $rawRequested );
                                                if ( $requestedObj ) :
                                                    ?>
                                                    <?= $requestedObj->format( 'F j, Y' ) ?><br>
                                                    <small><?= $requestedObj->format( 'h:i:s A' ) ?></small>
                                                <?php else : ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <?php
                                        $status      = $request[ 'status' ];
                                        $approvedRaw = $request[ 'date_time_approved' ] ?? null;
                                        $rejectedRaw = $request[ 'date_time_rejected' ] ?? null;

                                        if ( ( $status === 'Approved' && $approvedRaw ) || ( $status === 'Rejected' && $rejectedRaw ) ) :
                                            $rawDate = $status === 'Approved' ? $approvedRaw : $rejectedRaw;
                                            $dateObj = DateTime::createFromFormat( 'm-d-Y H:i:s A', $rawDate );
                                            ?>
                                            <tr>
                                                <td class="font-weight-bold">
                                                    <?= $status === 'Approved' ? 'Date Approved:' : 'Date Rejected:' ?>
                                                </td>
                                                <td>
                                                    <?php if ( $dateObj ) : ?>
                                                        <?= $dateObj->format( 'F j, Y' ) ?><br>
                                                        <small><?= $dateObj->format( 'h:i:s A' ) ?></small>
                                                    <?php else : ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                        <tr>
                                            <td class="font-weight-bold">Status:</td>
                                            <td class="text-capitalize"><?= esc( $request[ 'status' ] ) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="btn-group mt-3" role="group" aria-label="Voucher Actions">
                                <button type="button" class="btn btn-outline-primary btn-md download-voucher-btn"
                                    data-id="<?= esc( $request[ 'seed_requests_tbl_id' ] ) ?>"
                                    data-download-url="<?= base_url( 'public/downloadVoucher' ) ?>">
                                    <i class="bi bi-download me-1"></i> Download Voucher
                                </button>
                                <button class="btn btn-outline-secondary btn-md">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </button>
                                <button class="btn btn-outline-danger btn-md">
                                    <i class="bi bi-x-circle me-1"></i> Cancel Request
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-secondary text-center" role="alert">
                    No seed requests found for the selected cropping season.
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>