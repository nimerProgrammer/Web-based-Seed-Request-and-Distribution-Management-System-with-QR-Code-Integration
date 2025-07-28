<!-- New Season Modal -->
<div class="modal fade" id="newSeasonModal" tabindex="-1" aria-labelledby="newSeasonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form id="newSeasonForm" action="<?= base_url( 'admin/dashboard/newSeason' ) ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="newSeasonModalLabel">New Cropping Season</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="season_name" class="form-label">Season Name</label>
                        <select class="form-select" id="season_name" name="season_name" required
                            data-url="<?= base_url( 'admin/dashboard/checkSeasonExists' ) ?>">
                            <option value="" selected disabled>Select season</option>
                            <option value="1st CROPPING">1st CROPPING</option>
                            <option value="2nd CROPPING">2nd CROPPING</option>
                        </select>
                        <div id="seasonNameFeedback" class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="season_year" class="form-label">Season Year</label>
                        <select class="form-select" id="season_year" name="season_year" required>
                            <option value="" selected disabled>Select year</option>
                            <?php for ( $year = 2025; $year <= 2050; $year++ ) : ?>
                                <option value="<?= $year ?>"><?= $year ?></option>
                            <?php endfor; ?>
                        </select>
                        <div id="seasonYearFeedback" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="season_start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="season_start_date" name="season_start_date"
                            data-url="<?= base_url( 'admin/dashboard/checkStartDateConflict' ) ?>" required>
                        <div id="startDateFeedback" class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="season_end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="season_end_date" name="season_end_date" required>
                        <div id="endDateFeedback" class="invalid-feedback"></div>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Add</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Season Modal -->
<div class="modal fade" id="editSeasonModal" tabindex="-1" aria-labelledby="editSeasonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form id="editSeasonForm" action="<?= base_url( 'admin/dashboard/updateSeason' ) ?>" method="post">
                <input type="hidden" name="cropping_season_tbl_id" id="edit_cropping_season_tbl_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="editSeasonModalLabel">Edit Cropping Season</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="edit_season_name" class="form-label">Season Name</label>
                        <select class="form-select" id="edit_season_name" name="season_name" required
                            data-url="<?= base_url( 'admin/dashboard/editCheckSeasonExists' ) ?>">
                            <option value="" selected disabled>Select season</option>
                            <option value="1st CROPPING">1st CROPPING</option>
                            <option value="2nd CROPPING">2nd CROPPING</option>
                        </select>
                        <div id="editSeasonNameFeedback" class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_season_year" class="form-label">Season Year</label>
                        <select class="form-select" id="edit_season_year" name="season_year" required>
                            <option value="" selected disabled>Select year</option>
                            <?php for ( $year = 2025; $year <= 2050; $year++ ) : ?>
                                <option value="<?= $year ?>"><?= $year ?></option>
                            <?php endfor; ?>
                        </select>
                        <div id="editSeasonYearFeedback" class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_season_start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="edit_season_start_date" name="season_start_date"
                            data-url="<?= base_url( 'admin/dashboard/editCheckStartDateConflict' ) ?>" required>
                        <div id="editStartDateFeedback" class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_season_end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="edit_season_end_date" name="season_end_date"
                            required>
                        <div id="editEndDateFeedback" class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>