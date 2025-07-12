<!-- Add Post Modal -->
<div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="addPostForm" action="<?= base_url( 'admin/uploadPost' ) ?>" method="post"
            enctype="multipart/form-data" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title" id="addPostModalLabel">Add Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="addImagePreview" class="d-flex flex-wrap gap-2 mb-3 align-items-center"></div>
                <input type="file" name="images[]" id="addFinalImageInput" multiple hidden>

                <div class="mb-3">
                    <label for="addDescription" class="form-label">Post Description</label>
                    <textarea name="addDescription" id="addDescription" class="form-control" rows="5"
                        placeholder="Write your thoughts or story here..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Add Post Button -->
                <button type="submit" id="submitPostBtn" class="btn btn-primary" disabled>
                    <i class="bi bi-upload"></i> Publish Post
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editPostForm" action="<?= base_url( 'public/uploadImages' ) ?>" method="post"
            enctype="multipart/form-data" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="editImagePreview" class="d-flex flex-wrap gap-2 mb-3 align-items-center"></div>
                <input type="file" name="images[]" id="editFinalImageInput" multiple hidden>

                <div class="mb-3">
                    <label for="editDescription" class="form-label">Description</label>
                    <textarea name="description" id="editDescription" class="form-control" rows="5"
                        placeholder="Enter your post description here..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Edit Post Button -->
                <button type="submit" id="editPostBtn" class="btn btn-primary" disabled>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>