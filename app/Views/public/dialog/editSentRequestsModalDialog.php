<?php if ( session()->get( "public_logged_in" ) === true ) : ?>
    <div class="modal fade" id="editSentRequestsModal" tabindex="-1" aria-labelledby="editSentRequestsModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="submitEditSentRequestForm"
                        action="<?= base_url( 'public/sentRequest/edit' ) ?>">
                        <?= csrf_field() ?>

                        <input type="hidden" name="seed_requests_tbl_id" id="editSeedRequestId">

                        <div class="mb-3">
                            <label for="seed_name" class="form-label">Select Seed Type</label>
                            <?php
                            $inventory      = getInventoryList();
                            $hasEnabledSeed = false;
                            ?>
                            <select name="edit_seed_name" id="edit_seed_name" class="form-select" required>
                                <option value="" disabled selected>-- Choose a seed --</option>
                                <?php if ( empty( $inventory ) ) : ?>
                                    <option disabled>No seed inventory available</option>
                                <?php else : ?>
                                    <?php
                                    $userClientId = session( 'public_user_client_id' );
                                    foreach ( $inventory as $seed ) :
                                        $available        = (int) $seed[ 'stock' ] - (int) $seed[ 'distributed' ];
                                        $alreadyRequested = isSeedRequestedByUser( $seed[ 'inventory_tbl_id' ], $userClientId );
                                        $isDisabled       = ( $available <= 0 || $alreadyRequested ) ? 'disabled' : '';

                                        if ( $isDisabled === '' ) {
                                            $hasEnabledSeed = true;
                                        }

                                        $label = esc( $seed[ 'seed_name' ] ) . " - " . esc( $seed[ 'seed_class' ] ) . " ({$available} available)";
                                        if ( $alreadyRequested ) {
                                            $label .= " • requested";
                                        }
                                        ?>
                                        <option value="<?= esc( $seed[ 'inventory_tbl_id' ] ) ?>" <?= $isDisabled ?>
                                            data-original-label="<?= $label ?>">
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3" id="submitEditSentRequestBtn">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>