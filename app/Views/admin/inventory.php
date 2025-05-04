

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header border-bottom">
        <div class="container-fluid">
            <div class="row">
                <!-- Breadcrumb -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-start">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Inventory</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h3 class="card-title">Seed Inventory</h3>
                        </div>
                        <div class="col-lg-6">
                            <button class="btn btn-sm btn-outline-primary float-right">
                                <i class="fas fa-plus"></i>
                                Add Seed
                            </button>
                        </div>
                    </div>
                    
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="seedTable" class="table table-bordered table-hover">
                            <thead class="table-success">
                                <tr>
                                    <th>#</th>
                                    <th>Seed Name</th>
                                    <th>Category</th>
                                    <th>Quantity (kg)</th>
                                    <th>Availability</th>
                                    <th>Cropping Season</th>
                                    <th>Date Stored</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Rice (IR64)</td>
                                    <td>Grain</td>
                                    <td>100</td>
                                    <td><span class="badge bg-success">Available</span></td>
                                    <td>1st CROPPING 2025</td>
                                    <td>2025-04-15</td>
                                    <td class="text-center">
                                        <div class="btn-group text-center" role="group">
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

   

</div>