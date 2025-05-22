<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header border-bottom">
        <div class="container-fluid">
            <div class="row">
                <!-- Breadcrumb -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-start">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Seeds Requests</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active">1st CROPPING 2025</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card mt-3 text-center">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="inventoryTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="seeds-tab" data-bs-toggle="tab" href="#seeds" role="tab" aria-controls="seeds" aria-selected="true">Rice</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="inventoryTabsContent">

                        <!-- Seed Inventory Tab -->
                        <div class="tab-pane fade show active" id="seeds" role="tabpanel" aria-labelledby="seeds-tab">
                           
                            <div class="table-responsive">
                                <table id="seedTable" class="table table-bordered table-hover">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>No.</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <th>Ext. Name</th>
                                            <th>RSBSA Reference No.</th>
                                            <th>Name of Land Owner</th>
                                            <th>Verified Farm Area (Hectares)</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Nimer</td>
                                            <td>Gerald</td>
                                            <td>Montallana</td>
                                            <td>Jr.</td>
                                            <td>0967856</td>
                                            <td>Clarck</td>
                                            <td>2</td>
                                            <td><span class="badge bg-success">Approved</span></td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" title="Approve">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" title="Reject">
                                                        <i class="bi bi-x-circle"></i>
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
            </div>
        </div>
    </section>

</div>
