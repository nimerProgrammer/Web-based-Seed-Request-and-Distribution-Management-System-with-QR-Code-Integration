<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header border-bottom">
        <div class="container-fluid">
            <div class="row">
                <!-- Breadcrumb -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-start">
                        <li class="breadcrumb-item"><a href="<?= base_url( 'admin' ) ?>">Home</a></li>
                        <li class="breadcrumb-item active">Logs</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Logs Section -->
            <div class="row mt-3">
                <div class="col-lg-12">
                    <form id="clearLogsForm" method="post" action="<?= base_url( '/admin/logs/clearLogs' ) ?>">
                        <?= csrf_field() ?>
                        <button type="button" class="btn btn-sm btn-outline-danger float-end" id="clearLogsBtn">
                            <i class="bi bi-trash"></i>
                            Clear Logs
                        </button>
                    </form>
                </div>
                <div class="col-lg-12">
                    <div class="card mt-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Activity Logs</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="set-Table table table-bordered table-hover text-center">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>#</th>
                                            <th>Timestamp</th>
                                            <th>User</th>
                                            <th>Action</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ( $logs as $log ) : ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td>
                                                    <?php
                                                    $dateObj = DateTime::createFromFormat( 'm-d-Y h:i:s A', $log[ 'timestamp' ] );
                                                    if ( $dateObj ) :
                                                        ?>
                                                        <?= $dateObj->format( 'F j, Y' ) ?><br>
                                                        <small><?= $dateObj->format( 'h:i:s A' ) ?></small>
                                                    <?php else : ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?= isset( $log[ 'user_type' ] )
                                                        ? esc( ucfirst( $log[ 'user_type' ] ) )
                                                        : '<span class="text-danger">Anonymous</span>' ?>
                                                </td>

                                                </td>
                                                <td><?= esc( $log[ 'action' ] ) ?></td>
                                                <td><?= esc( $log[ 'details' ] ) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>