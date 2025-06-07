<!-- Modal -->
<div class="modal fade" id="addSeedModal" tabindex="-1" aria-labelledby="addSeedModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="POST">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addSeedModalLabel">Add Seed</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label for="seed_name" class="form-label">Seed Name:</label>
					<input type="text" class="form-control" name="seed_name" id="seed_name" required>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Save</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</div>
		</form>
	</div>
</div>