<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header border-bottom">
        <div class="container-fluid">
            <div class="row">
                <!-- Breadcrumb -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-start">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Logs</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

   <!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <h1 class="text-center text-secondary mb-4">Under Development</h1>

        <!-- Logs Section -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i>Activity Logs</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2025-06-20 18:45:00</td>
                                <td>admin</td>
                                <td>Login</td>
                                <td>User logged in successfully</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>2025-06-20 19:00:15</td>
                                <td>johndoe</td>
                                <td>Update</td>
                                <td>Edited inventory record ID #15</td>
                            </tr>
                            <!-- Add more rows dynamically via PHP/JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

</div>
