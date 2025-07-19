<!-- Edit for Fullname -->
<div class="modal fade" id="editNameModal" tabindex="-1" aria-labelledby="editNameModalLabel" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Fullname</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editFullnameForm" method="post" action="<?= base_url( 'admin/updateFullname' ) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="lastNameInput">Last Name</label>
                        <input type="text" id="editLastName" name="editLastName" class="form-control"
                            placeholder="e.g. Juan" required>
                    </div>
                    <div class="mb-3">
                        <label for="middleNameInput">Middle Name</label>
                        <input type="text" id="editMiddleName" name="editMiddleName" class="form-control"
                            placeholder="e.g. Santos (optional)">
                    </div>
                    <div class="mb-3">
                        <label for="editFirstName">First Name</label>
                        <input type="text" id="editFirstName" name="editFirstName" class="form-control"
                            placeholder="e.g. Juan" required>
                    </div>
                    <div class="mb-3">
                        <label for="editExtName">Suffix / Extension</label>
                        <select id="editExtName" name="editExtName" class="form-select">
                            <option value="">-- Select Suffix (optional) --</option>
                            <option value="Jr.">Jr.</option>
                            <option value="Sr.">Sr.</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                            <option value="V">V</option>
                            <option value="VI">VI</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit for Gender -->
<div class="modal fade" id="editGenderModal" tabindex="-1" aria-labelledby="editGenderModalLabel"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Gender</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editGenderForm" method="post" action="<?= base_url( 'admin/updateGender' ) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editGender">Gender</label>
                        <select id="editGender" name="editGender" class="form-select">
                            <option value="" selected disabled>-- Select Gender --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit for Birthdate -->
<div class="modal fade" id="editBirthdateModal" tabindex="-1" aria-labelledby="editBirthdateModalLabel"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Birthdate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBirthdateForm" method="post" action="<?= base_url( 'admin/updateBirthdate' ) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editBirthdate" class="form-label">Birthdate</label>
                        <input type="date" class="form-control" id="editBirthdate" name="editBirthdate" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit for Contact Number -->
<div class="modal fade" id="editContactNoModal" tabindex="-1" aria-labelledby="editContactNoModalLabel"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Contact No.</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editContactNoForm" method="post" action="<?= base_url( 'admin/updateContactNo' ) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editContactNo" class="form-label">Contact Number</label>
                        <input type="hidden" id="originalContactNo" name="originalContactNo">
                        <input type="text" class="form-control" id="editContactNo" name="editContactNo"
                            placeholder="e.g. 09123456789 or +639123456789" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit for Email -->
<div class="modal fade" id="editEmailModal" tabindex="-1" aria-labelledby="editEmailModalLabel"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editEmailForm" method="post" action="<?= base_url( 'admin/updateEmail' ) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="hidden" id="originalEmail" name="originalEmail">
                        <input type="text" class="form-control" id="editEmail" name="editEmail"
                            placeholder="e.g. example@mail.com" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit for Username -->
<div class="modal fade" id="editUsernameModal" tabindex="-1" aria-labelledby="editUsernameModalLabel"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Username</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUsernameForm" method="post" action="<?= base_url( 'admin/updateUsername' ) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editUsername" class="form-label">Username</label>
                        <input type="hidden" id="originalUsername" name="originalUsername">
                        <input type="text" class="form-control" id="editUsername" name="editUsername"
                            placeholder="New username" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit for Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePassordModalLabel"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changePasswordForm" method="post" action="<?= base_url( 'admin/changePassword' ) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <div class="input-group input-group-md">
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword"
                                placeholder="Enter current password" required>
                            <span class="input-group-text" id="toggleCurrentPassword" style="cursor: pointer;">
                                <i class="fa-solid fa-eye-slash"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="input-group input-group-md">
                            <input type="password" class="form-control" id="newPassword" name="newPassword"
                                placeholder="Enter new password" required>
                            <span class="input-group-text" id="toggleNewPassword" style="cursor: pointer;">
                                <i class="fa-solid fa-eye-slash"></i>
                            </span>
                        </div>

                        <!-- 🔽 Description output area -->
                        <div class="description mt-2 text-secondary small"></div>
                    </div>


                    <!-- ✅ Confirm Password Field -->
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <div class="input-group input-group-md">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                placeholder="Confirm new password" required>
                            <span class="input-group-text" id="toggleConfirmPassword" style="cursor: pointer;">
                                <i class="fa-solid fa-eye-slash"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback" id="confirmPasswordFeedback"></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>