<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header border-bottom">
        <div class="container-fluid">
            <div class="row">
                <!-- Breadcrumb -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-start">
                        <li class="breadcrumb-item"><a href="<?= base_url( 'admin' ) ?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-3">


                <!-- Card 1: Home -->
                <div class="col-lg-4">
                    <div class="card text-white"
                        style="background: linear-gradient(135deg, #007bff, #0056b3); border: none;">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-user-friends fa-3x me-4"></i>
                            <div>
                                <h5 class="card-title mb-1">Benificiaries</h5>
                                <!-- <h1 class="text-center">5</h1> -->
                                <p class="card-text small">This feature is currently under development.</p>
                                <a href="#" class="btn btn-outline-light btn-sm mt-2">View More</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Available Seed -->
                <div class="col-lg-4">
                    <div class="card text-white"
                        style="background: linear-gradient(135deg, #28a745, #1e7e34); border: none;">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-seedling fa-3x me-4"></i>
                            <div>
                                <h5 class="card-title mb-1">Available Seed</h5>
                                <!-- <h1 class="text-center">5</h1> -->
                                <p class="card-text small">This feature is currently under development.</p>
                                <a href="#" class="btn btn-outline-light btn-sm mt-2">View Seeds</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Analytics -->
                <div class="col-lg-4">
                    <div class="card text-white"
                        style="background: linear-gradient(135deg, #28a745, #218838); border: none;">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-calendar3 fa-3x me-4"></i> <!-- Bootstrap Icon -->
                            <div>
                                <h5 class="card-title mb-1">Current Season</h5>

                                <?php if ( !empty( $currentSeason ) ) : ?>
                                    <p class="card-text mb-0">
                                        <?= esc( $currentSeason[ 'season' ] ) ?> - <?= esc( $currentSeason[ 'year' ] ) ?>
                                    </p>
                                    <p class="card-text small">
                                        <?= date( 'F j, Y', strtotime( $currentSeason[ 'date_start' ] ) ) ?>
                                        to
                                        <?= date( 'F j, Y', strtotime( $currentSeason[ 'date_end' ] ) ) ?>
                                    </p>
                                <?php else : ?>
                                    <p class="card-text small">No current season available.</p>
                                <?php endif; ?>

                                <a href="#" class="btn btn-outline-light btn-sm mt-2">Manage Season</a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">Cropping Season</h3>
                        </div>
                        <div class="card-body">
                            <table id="seasonsTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Season</th>
                                        <th class="text-center">Year</th>
                                        <th>Date Start</th>
                                        <th>Date End</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ( $seasons as $season ) : ?>
                                        <tr>
                                            <td class="align-middle"><?= $i++ ?></td>
                                            <td class="align-middle"><?= esc( $season[ 'season' ] ) ?></td>
                                            <td class="align-middle text-center"><?= esc( $season[ 'year' ] ) ?></td>
                                            <td class="align-middle">
                                                <?php
                                                $rawStored = $season[ 'date_start' ];
                                                $storedObj = DateTime::createFromFormat( 'Y-m-d', $rawStored );

                                                if ( $storedObj ) :
                                                    ?>
                                                    <?= $storedObj->format( 'F j, Y' ) ?><br>
                                                <?php else : ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle"><?= esc( $season[ 'date_end' ] ) ?></td>
                                            <td class="align-middle">
                                                <?php if ( $season[ 'status' ] === 'Current' ) : ?>
                                                    <?= esc( $season[ 'status' ] ) ?>&nbsp; <i
                                                        class="bi bi-circle-fill text-success fs-7 align-middle"
                                                        style="font-size: 0.5rem;"></i>
                                                <?php else : ?>
                                                    <?= esc( $season[ 'status' ] ) ?>
                                                <?php endif; ?>
                                            </td>

                                            <td class="align-middle text-center">
                                                <div class="btn-group text-center" role="group">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary edit-season-button"
                                                        data-bs-toggle="modal" data-bs-target="#editSeasonModal"
                                                        data-id="<?= $season[ 'cropping_season_tbl_id' ] ?>"
                                                        data-season="<?= esc( $season[ 'season' ] ) ?>"
                                                        data-year="<?= esc( $season[ 'year' ] ) ?>"
                                                        data-date_start="<?= esc( $season[ 'date_start' ] ) ?>"
                                                        data-date_end="<?= esc( $season[ 'date_end' ] ) ?>"
                                                        data-bs-toggles="tooltip" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <a href="#"
                                                        class="btn btn-sm btn-outline-danger <?= $season[ 'status' ] === 'Current' ? 'disabled' : '' ?>"
                                                        data-bs-toggle="tooltip"
                                                        title="<?= $season[ 'status' ] === 'Current' ? 'Cannot delete current season' : 'Delete' ?>"
                                                        <?= $season[ 'status' ] === 'Current' ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
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
    </section>


    <section class="content">
        <div class="container-fluid">
            <h1 class="text-secondary text-center">
                Under Development
            </h1>
        </div>
    </section>


</div>