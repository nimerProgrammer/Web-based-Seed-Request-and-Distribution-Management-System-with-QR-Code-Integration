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