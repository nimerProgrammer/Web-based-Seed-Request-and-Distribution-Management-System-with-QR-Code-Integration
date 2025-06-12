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
            <div class="card mt-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h3 class="card-title">Seed Inventory</h3>
                        </div>
                        <div class="col-lg-6">
                            <button class="btn btn-sm btn-outline-primary float-end" data-bs-toggle="modal"
                                data-bs-target="#addSeedModal">
                                <i class="fas fa-plus"></i>
                                Add Seed
                            </button>
                        </div>
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="seedTable" class="table table-bordered table-hover">
                            <thead class="table-success">
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
                                        <td>
                                            <?php if ( $item[ 'stock' ] > 0 ) : ?>
                                                <span class="badge bg-success">Available</span>
                                            <?php else : ?>
                                                <span class="badge bg-danger">Out of Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= esc( $item[ 'season' ] ) ?> (<?= esc( $item[ 'year' ] ) ?>)
                                        </td>
                                        <td>
                                            <?= date( 'F j, Y', strtotime( $item[ 'date_stored' ] ) ) ?><br>
                                            <small><?= date( 'h:i A', strtotime( $item[ 'date_stored' ] ) ) ?></small>
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
    </section>
</div>

<script>

    // document.querySelectorAll('.edit-inventory-button').forEach(button => {
    //     button.addEventListener('click', function () {
    //         document.getElementById('edit_inventory_id').value = this.dataset.id;
    //         document.getElementById('edit_seed_name').value = this.dataset.name;
    //         document.getElementById('edit_seed_class').value = this.dataset.class;
    //         document.getElementById('edit_stock').value = this.dataset.stock;

    //         // Set form action dynamically
    //         document.getElementById('editSeedForm').action = `/admin/inventory/update/${this.dataset.id}`;
    //     });
    // });

    // // Wait for DOM to load
    // document.addEventListener('DOMContentLoaded', function () {
    //     const form = document.getElementById('editSeedForm');
    //     const updateBtn = document.getElementById('edit_update_button');
    //     const cancelBtn = document.getElementById('edit_cancel_button');

    //     form.addEventListener('submit', function () {
    //         updateBtn.innerHTML = 'Updating...';
    //         updateBtn.disabled = true;
    //         cancelBtn.disabled = true;
    //     });
    // });
</script>

<script>
    // document.querySelectorAll('.delete-button').forEach(button => {
    //     button.addEventListener('click', function (event) {
    //         event.preventDefault();
    //         const href = this.getAttribute('href');

    //         Swal.fire({
    //             title: 'Are you sure?',
    //             text: "You won't be able to revert this!",
    //             icon: 'warning',
    //             showCancelButton: true,
    //             confirmButtonText: 'Yes, delete it!',
    //             cancelButtonText: 'Cancel',
    //             customClass: {
    //                 confirmButton: 'btn btn-sm btn-primary mr-1',
    //                 cancelButton: 'btn btn-sm btn-danger'
    //             },
    //             buttonsStyling: false
    //         }).then((result) => {
    //             if (result.isConfirmed) {
    //                 window.location.href = href;
    //             }
    //         });
    //     });
    // });
</script>