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

                <div class="col-md-4">
                    <div class="card shadow-md text-white"
                        style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                        <div class="card-body d-flex align-items-center justify-content-between">

                            <!-- Text Content on the Left -->
                            <div>
                                <h6 class="card-title mb-1">Current Season</h6>

                                <h5 class="card-text mb-1" id="season-title">Loading...</h5>
                                <p class="card-text small" id="season-dates">Fetching season dates...</p>
                            </div>

                            <!-- Icon Circle on the Right -->
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center ms-auto"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-calendar3 text-primary fa-2x"></i>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-top">
                            <a href="#cropping-seasons-table" class="btn btn-outline-light btn-sm float-end">
                                <i class="fas fa-search me-1"></i> View More Details
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-md text-white"
                        style="background: linear-gradient(135deg, #fd7e14, #e8590c);">
                        <!-- Bootstrap orange gradient -->
                        <div class="card-body d-flex align-items-center justify-content-between">

                            <!-- Text Content on the Left -->
                            <div>
                                <h4 class="card-text">Total: <?= countFarmersWithAccountsOnly() ?>
                                </h4>
                                <h6 class="card-title">List of Farmers <br>(<i>account holders
                                        only</i>)</h6>
                            </div>

                            <!-- Icon Circle on the Right -->
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center ms-auto"
                                style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-person-digging text-orange fa-2x" style="color: #fd7e14;"></i>
                                <i class="fa-solid fa-person-digging text-orange"></i>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-top">
                            <a href="#farmers-table" class="btn btn-outline-light btn-sm float-end">
                                <i class="fas fa-search"></i> View More Details
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="card shadow-md text-white"
                        style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <div class="card-body d-flex align-items-center justify-content-between">

                            <!-- Text Content on the Left -->
                            <div>
                                <h4 class="card-text">Total: <?= countCurrentSeasonSeeds() ?></h4>
                                <h6 class="card-title mb-1">Available Seeds</h6>
                            </div>

                            <!-- Icon Circle on the Right -->
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center ms-auto"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-wheat-awn text-success fa-2x"></i>
                            </div>

                        </div>
                        <div class="card-footer bg-transparent border-top">

                            <a href="inventory" class="dash-btn btn btn-outline-light btn-sm float-end"><i
                                    class="fas fa-search"></i>
                                View More Details</a>
                        </div>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-md text-white"
                        style="background: linear-gradient(135deg, #f5b907ff, #cf9c02ff);">
                        <!-- Bootstrap warning gradient -->
                        <div class="card-body d-flex align-items-center justify-content-between">

                            <!-- Text Content on the Left -->
                            <div>
                                <h4 class="card-text">Total: <?= countCurrentSeasonSeedRequests() ?></h4>
                                <h6 class="card-title mb-1">Seed Requests</h6>
                            </div>

                            <!-- Icon Circle on the Right -->
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center ms-auto"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-envelope-open-text text-warning fa-2x"></i>
                            </div>

                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <a href="seedsRequests" class="dash-btn btn btn-outline-light btn-sm float-end">
                                <i class="fas fa-search"></i> View More Details
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-md text-white"
                        style="background: linear-gradient(135deg, #14bfdaff, #1593a7ff);">
                        <!-- Bootstrap info gradient -->
                        <div class="card-body d-flex align-items-center justify-content-between">

                            <!-- Text Content on the Left -->
                            <div>
                                <h4 class="card-text">Total: <?= countCurrentSeasonBeneficiaries() ?>
                                </h4>
                                <h6 class=" card-title mb-1">Beneficiaries</h6>
                            </div>

                            <!-- Icon Circle on the Right -->
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center ms-auto"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-users text-info fa-2x"></i>
                            </div>

                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <a href="beneficiaries" class="dash-btn btn btn-outline-light btn-sm float-end">
                                <i class="fas fa-search"></i> View More Details
                            </a>
                        </div>
                    </div>
                </div>




                <div class="col-lg-12" id="cropping-seasons-table">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">Cropping Season</h3>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-sm btn-outline-primary float-end mb-3" data-bs-toggle="modal"
                                data-bs-target="#newSeasonModal">
                                <i class="fas fa-plus"></i>
                                New Season
                            </button>
                            <div class="table-responsive">
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
                                    <tbody id="season-tbody">
                                        <?php $i = 1; ?>
                                        <?php foreach ( $seasons as $season ) : ?>
                                            <tr>
                                                <td class="align-middle"><?= $i++ ?></td>
                                                <td class="align-middle"><?= esc( $season[ 'season' ] ) ?></td>
                                                <td class="align-middle text-center"><?= esc( $season[ 'year' ] ) ?></td>
                                                <td class="align-middle">
                                                    <?php
                                                    $rawStored = $season[ 'date_start' ];
                                                    $storedObj = DateTime::createFromFormat( 'm-d-Y', $rawStored );

                                                    if ( $storedObj ) :
                                                        ?>
                                                        <?= $storedObj->format( 'F j, Y' ) ?><br>
                                                    <?php else : ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="align-middle">
                                                    <?php
                                                    $rawStored = $season[ 'date_end' ];
                                                    $storedObj = DateTime::createFromFormat( 'm-d-Y', $rawStored );

                                                    if ( $storedObj ) :
                                                        ?>
                                                        <?= $storedObj->format( 'F j, Y' ) ?><br>
                                                    <?php else : ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
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
                                                        <?php
                                                        // Fix: convert from m-d-Y to Y-m-d
                                                        $startDate = DateTime::createFromFormat( 'm-d-Y', $season[ 'date_start' ] );
                                                        $endDate   = DateTime::createFromFormat( 'm-d-Y', $season[ 'date_end' ] );
                                                        ?>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary edit-season-btn"
                                                            data-bs-toggle="modal" data-bs-target="#editSeasonModal"
                                                            data-id="<?= esc( $season[ 'cropping_season_tbl_id' ] ) ?>"
                                                            data-season="<?= $season[ 'season' ] ?>"
                                                            data-year="<?= $season[ 'year' ] ?>"
                                                            data-start="<?= $startDate ? $startDate->format( 'Y-m-d' ) : '' ?>"
                                                            data-end="<?= $endDate ? $endDate->format( 'Y-m-d' ) : '' ?>"
                                                            data-bs-toggles="tooltip" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>

                                                        <a href="javascript:void(0)"
                                                            class="btn btn-sm btn-outline-danger delete-season-btn <?= $season[ 'status' ] === 'Current' ? 'disabled' : '' ?>"
                                                            data-id="<?= esc( $season[ 'cropping_season_tbl_id' ] ) ?>"
                                                            data-url="<?= base_url( 'admin/dashboard/deleteSeason/' . $season[ 'cropping_season_tbl_id' ] ) ?>"
                                                            data-csrf-name="<?= csrf_token() ?>"
                                                            data-csrf-hash="<?= csrf_hash() ?>"
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

                <div class="col-lg-12" id="farmers-table">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">List of Farmers (<i>account holders only</i>)</h3>
                        </div>
                        <div class="card-body">
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
                                        </tr>
                                        <tr>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <th>Suffix & Ext.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ( !empty( $clients ) ) : ?>
                                            <?php $no = 1; ?>
                                            <?php foreach ( $clients as $client ) : ?>
                                                <tr>
                                                    <td class="align-middle"><?= $no++ ?></td>
                                                    <td class="align-middle"><?= esc( $client[ 'rsbsa_ref_no' ] ) ?></td>
                                                    <td class="align-middle"><?= esc( $client[ 'last_name' ] ) ?></td>
                                                    <td class="align-middle"><?= esc( $client[ 'first_name' ] ) ?></td>
                                                    <td class="align-middle"><?= esc( $client[ 'middle_name' ] ?? '—' ) ?></td>
                                                    <td class="align-middle"><?= esc( $client[ 'suffix_and_ext' ] ?? '—' ) ?>
                                                    </td>
                                                    <td class="align-middle"><?= esc( $client[ 'brgy' ] ?? '—' ) ?></td>
                                                    <td class="align-middle"><?= esc( $client[ 'mun' ] ) ?></td>
                                                    <td class="align-middle"><?= esc( $client[ 'prov' ] ) ?></td>
                                                    <td class="align-middle">
                                                        <?php
                                                        $rawBDate = $client[ 'b_date' ] ?? null;
                                                        $dateObj  = $rawBDate ? DateTime::createFromFormat( 'Y-m-d', $rawBDate ) : null;
                                                        ?>
                                                        <?php if ( $dateObj ) : ?>
                                                            <?= $dateObj->format( 'F j, Y' ) ?>
                                                        <?php else : ?>
                                                            <span class="text-muted">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="align-middle"><?= esc( $client[ 'gender' ] ) ?></td>
                                                    <td class="align-middle"><?= esc( $client[ 'contact_no' ] ) ?></td>
                                                    <td class="align-middle"><?= esc( $client[ 'farm_area' ] ) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td class="text-center" colspan="13">No farmers found.</td>
                                            </tr>
                                        <?php endif; ?>
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