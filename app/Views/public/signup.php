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
            <div class="card mt-3">
                <form action="<?= base_url( 'public/register/submit' ) ?>" method="post">

                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Personal Information </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="rsbsa_no" class="form-label">RSBSA Reference No.</label>
                                <input type="text" class="form-control" id="rsbsa_no" name="rsbsa_no" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="suffix" class="form-label">Suffix / Extension</label>
                                <input type="text" class="form-control" id="suffix" name="suffix">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="barangay" class="form-label">Barangay</label>
                                <input type="text" class="form-control" id="barangay" name="barangay" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="municipality" class="form-label">Municipality</label>
                                <input type="text" class="form-control" id="municipality" name="municipality" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" class="form-control" id="province" name="province" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="birthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender" required>
                                    <option value="">-- Select Gender --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="contact_no" class="form-label">Contact No. (Mobile)</label>
                                <input type="text" class="form-control" id="contact_no" name="contact_no" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="farm_area" class="form-label">Farm Area (Hectares)</label>
                                <input type="number" step="0.01" class="form-control" id="farm_area" name="farm_area"
                                    required>
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
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-success">Register</button>
                </form>
            </div>
        </div>
    </section>
</div>