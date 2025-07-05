<!-- Modal -->
<div class="modal fade" id="addSeedModal" tabindex="-1" aria-labelledby="addSeedModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<form action="<?= base_url( 'admin/inventory/save' ) ?>" method="post">
				<div class="modal-header">
					<h5 class="modal-title" id="addSeedModalLabel">Add Seed</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">
					<div class="mb-3">
						<label for="add_seed_name" class="form-label">Seed Name</label>
						<input type="text" class="form-control" id="add_seed_name" name="add_seed_name"
							placeholder="e.g. RC18 (Rice)" required>
					</div>

					<div class="mb-3">
						<label for="add_seed_class" class="form-label">Seed Class</label>
						<select class="form-select" id="add_seed_class" name="add_seed_class" required>
							<option value="" selected disabled>Select a seed class</option>
							<option value="Hybrid">Hybrid</option>
							<option value="Inbred">Inbred</option>
							<option value="Foundation">Foundation</option>
						</select>
					</div>

					<div class="mb-3">
						<label for="add_stock" class="form-label">Stock (kg)</label>
						<input type="number" class="form-control" id="add_stock" name="add_stock" placeholder="e.g. 100"
							required min="1">
					</div>

					<!-- Read-only input to show the season name and year -->
					<input type="hidden" class="form-control" id="add_cropping_season_tbl_id"
						name="cropping_season_tbl_id"
						value="<?= esc( $cropping_season[ 'cropping_season_tbl_id' ] ) ?>">
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-sm btn-primary" id="add_seed_to_inventory">Save</button>
					<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Edit Seed Modal -->
<div class="modal fade" id="editSeedModal" tabindex="-1" aria-labelledby="editSeedModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<form id="editSeedForm" method="post">
				<div class="modal-header">
					<h5 class="modal-title" id="editSeedModalLabel">Edit Seed</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">
					<input type="hidden" id="edit_inventory_id" name="edit_inventory_id">

					<div class="mb-3">
						<label for="edit_seed_name" class="form-label">Seed Name</label>
						<input type="text" class="form-control" id="edit_seed_name" name="edit_seed_name"
							placeholder="e.g. RC18 (Rice)" required>
					</div>

					<div class="mb-3">
						<label for="edit_seed_class" class="form-label">Seed Class</label>
						<select class="form-select" id="edit_seed_class" name="edit_seed_class" required>
							<option value="" selected disabled>Select a seed class</option>
							<option value="Hybrid">Hybrid</option>
							<option value="Improved">Improved</option>
							<option value="Foundation">Foundation</option>
						</select>
					</div>

					<div class="mb-3">
						<label for="edit_stock" class="form-label">Stock (kg)</label>
						<input type="number" class="form-control" id="edit_stock" name="edit_stock"
							placeholder="e.g. 100" required min="0">
					</div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-sm btn-primary" id="edit_update_button">Update</button>
					<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"
						id="edit_cancel_button">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>