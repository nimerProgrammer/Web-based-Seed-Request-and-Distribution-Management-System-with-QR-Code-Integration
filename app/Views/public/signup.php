<script>
    const BASE_URL = "<?= base_url() ?>";
</script>



<!-- Main Content -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="border-bottom">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-start">
                            <li class="breadcrumb-item"><a href="<?= base_url( '/' ) ?>">Home</a></li>
                            <li class="breadcrumb-item active">Sign Up</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form id="signUpForm" action="<?= base_url( 'public/signUp/submitSignUp' ) ?>" method="post">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Personal Information </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    placeholder="e.g. Dela Cruz" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    placeholder="e.g. Juan" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name"
                                    placeholder="e.g. Santos">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="suffix" class="form-label">Suffix / Extension</label>
                                <input type="text" class="form-control" id="suffix" name="suffix"
                                    placeholder="e.g. Jr., Sr., III">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="birthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender" required>
                                    <option value="">-- Select Gender --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-primary text-white rounded-0">
                        <h5 class="mb-0">Address</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="barangay" class="form-label">Barangay</label>
                                <select class="form-select" id="barangay" name="barangay" required>
                                    <option value="">-- Select Barangay --</option>
                                    <?php foreach ( getBarangayList() as $b ) : ?>
                                        <option value="<?= esc( $b[ 'barangay_name' ] ) ?>">
                                            <?= esc( $b[ 'barangay_name' ] ) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="municipality" class="form-label">Municipality</label>
                                <input type="text" class="form-control" id="municipality" name="municipality"
                                    value="Oras" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" class="form-control" id="province" name="province"
                                    value="Eastern Samar" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-primary text-white rounded-0">
                        <h5 class="mb-0">Farm Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="rsbsa_no" class="form-label">RSBSA No.</label>
                                <input type="text" class="form-control" id="rsbsa_no" name="rsbsa_no"
                                    placeholder="e.g. 1234-5678-9012" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="farm_area" class="form-label">Farm Area (Hectares)</label>
                                <input type="number" class="form-control" id="farm_area" name="farm_area" step="0.01"
                                    min="0" placeholder="e.g. 1.25" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="land_owner" class="form-label">Name of Land Owner</label>
                                <input type="text" class="form-control" id="land_owner" name="land_owner"
                                    placeholder="e.g. Juan Dela Cruz" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-primary text-white rounded-0">
                        <h5 class="mb-0">Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_no" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contact_no" name="contact_no"
                                    placeholder="e.g. 09123456789 or +639123456789" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="e.g. example@mail.com" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-primary text-white rounded-0">
                        <h5 class="mb-0">Account</h5>
                    </div>
                    <div class="card-body">
                        <!-- Add username/password if needed -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Enter your username" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter your password" required>
                                <div class="description" style="font-size: 0.875rem;"></div>
                            </div>

                            <div class="col-md-12 mt-1">
                                <button type="submit" id="submitBtn" class="btn btn-sm btn-primary w-100" disabled>
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>