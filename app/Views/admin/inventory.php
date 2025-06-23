<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header border-bottom">
        <div class="container-fluid">
            <div class="row">
                <!-- Breadcrumb -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-start">
                        <li class="breadcrumb-item"><a href="<?= base_url( 'admin' ) ?>">Home</a></li>
                        <li class="breadcrumb-item active">Inventory</li>
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
                    <button class="btn btn-sm btn-outline-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#addSeedModal">
                        <i class="fas fa-plus"></i>
                        Add Seed
                    </button>
                </div>
                <div class="col-lg-12">

                    <div class="card mt-3">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Seed Inventory</h3>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="seedTable" class="table table-bordered table-hover">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>#</th>
                                            <th>Seed Name</th>
                                            <th>Seed Class</th>
                                            <th>Stock (kg)</th>
                                            <th>Distributed</th>
                                            <th>Total</th>
                                            <th>Availability</th>
                                            <th>Cropping Season</th>
                                            <th>Date Stored</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ( $inventory as $item ) : ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= esc( $item[ 'seed_name' ] ) ?></td>
                                                <td><?= esc( $item[ 'seed_class' ] ?? '-' ) ?></td>
                                                <td><?= esc( $item[ 'stock' ] - $item[ 'distributed' ] ) ?></td>
                                                <td><?= esc( $item[ 'distributed' ] ?? 0 ) ?></td>
                                                <td><?= esc( $item[ 'stock' ] + $item[ 'distributed' ] ) ?></td>
                                                <td class="text-center">
                                                    <?php if ( $item[ 'stock' ] > 0 ) : ?>
                                                        <?= esc( $item[ 'stock' ] - $item[ 'distributed' ] ) ?> <br>
                                                        <span class="badge bg-success">Available</span>
                                                    <?php else : ?>
                                                        <span class="badge bg-danger">Out of Stock</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?= esc( $item[ 'season' ] ) ?> (<?= esc( $item[ 'year' ] ) ?>)
                                                </td>
                                                <td class="align-middle">
                                                    <?php
                                                    $rawStored = $item[ 'date_stored' ];
                                                    $storedObj = DateTime::createFromFormat( 'm-d-Y h:i A', $rawStored );

                                                    if ( $storedObj ) :
                                                        ?>
                                                        <?= $storedObj->format( 'F j, Y' ) ?><br>
                                                        <small><?= $storedObj->format( 'h:i A' ) ?></small>
                                                    <?php else : ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>


                                                <!-- Add edit/delete actions here -->
                                                <td class="text-center">
                                                    <div class="btn-group text-center" role="group">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary edit-inventory-button"
                                                            data-bs-toggle="modal" data-bs-target="#editSeedModal"
                                                            data-id="<?= $item[ 'inventory_tbl_id' ] ?>"
                                                            data-name="<?= esc( $item[ 'seed_name' ] ) ?>"
                                                            data-class="<?= esc( $item[ 'seed_class' ] ) ?>"
                                                            data-stock="<?= esc( $item[ 'stock' ] ) ?>">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>

                                                        <a href="<?= base_url( '/admin/inventory/delete/' . $item[ 'inventory_tbl_id' ] ) ?>"
                                                            class="btn btn-sm btn-outline-danger delete-inventory-button"
                                                            title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>

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