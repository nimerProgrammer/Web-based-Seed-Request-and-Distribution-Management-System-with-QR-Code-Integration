<div class="modal fade" id="requestSeedModal" tabindex="-1" aria-labelledby="requestSeedModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestSeedModalLabel">Request Seed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="submitRequestSeedForm" action="<?= base_url( 'public/request_seed/submit' ) ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="seed_name" class="form-label">Select Seed Type</label>
                        <?php
                        $inventory      = getInventoryList();
                        $hasEnabledSeed = false;
                        ?>
                        <select name="seed_name" id="seed_name" class="form-select" required>
                            <option value="" disabled selected>-- Choose a seed --</option>
                            <?php if ( empty( $inventory ) ) : ?>
                                <option disabled>No seed inventory available</option>
                            <?php else : ?>
                                <?php foreach ( $inventory as $seed ) : ?>
                                    <?php
                                    $available        = (int) $seed[ 'stock' ] - (int) $seed[ 'distributed' ];
                                    $userId           = session( 'public_user_id' );
                                    $alreadyRequested = isSeedRequestedByUser( $seed[ 'inventory_tbl_id' ], $userId );
                                    $isDisabled       = ( $available <= 0 || $alreadyRequested ) ? 'disabled' : '';

                                    // Set flag if this option is enabled
                                    if ( $isDisabled === '' ) {
                                        $hasEnabledSeed = true;
                                    }

                                    $label = esc( $seed[ 'seed_name' ] ) . " ({$available} available)";
                                    if ( $alreadyRequested ) {
                                        $label .= " • requested";
                                    }
                                    ?>
                                    <option value="<?= esc( $seed[ 'inventory_tbl_id' ] ) ?>" <?= $isDisabled ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>


                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3" id="submitRequestSeedBtn" <?= ( empty( $inventory ) || !$hasEnabledSeed ) ? 'disabled' : '' ?>>
                        <?= ( empty( $inventory ) || !$hasEnabledSeed ) ? 'No seeds available' : 'Submit Request' ?>
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>