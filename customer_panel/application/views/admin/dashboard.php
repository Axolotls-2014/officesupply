<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Home</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-xxl-8 d-flex align-items-stretch">
                <div class="card w-100 overflow-hidden rounded-4">
                    <div class="card-body position-relative p-4">
                        <div class="row">
                            <div class="col-12 col-sm-7">
                                <div class="d-flex align-items-center gap-3 mb-5">
                                    <img src="<?= base_url('assets/admin/') ?>assets/images/avatars/06.png" class="rounded-circle bg-grd-info p-1" width="60" height="60" alt="user">
                                    <div class="">
                                        <p class="mb-0 fw-semibold">Welcome back</p>
                                        <h4 class="fw-semibold mb-0 fs-4 mb-0"><?= $this->db_model->get_name() ?>!</h4>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-5">
                                    <div class="">
                                        <h4 class="mb-1 fw-semibold d-flex align-content-center">2<i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i></h4>
                                        <p class="mb-3">Credentials</p>
                                        <div class="progress mb-0" style="height:5px;">
                                            <div class="progress-bar bg-grd-success" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="">
                                        <h4 class="mb-1 fw-semibold d-flex align-content-center">2<i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i></h4>
                                        <p class="mb-3">Demos</p>
                                        <div class="progress mb-0" style="height:5px;">
                                            <div class="progress-bar bg-grd-danger" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-5">
                                <div class="welcome-back-img pt-4">
                                    <img src="<?= base_url('assets/admin/') ?>assets/images/gallery/welcome-back-3.png" height="180" alt="">
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-xxl-2 d-flex align-items-stretch">
                <div class="card w-100 rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-1">
                            <div class="">
                                <h5 class="mb-0">2</h5>
                                <p class="mb-0">Total Orders</p>
                            </div>
                        </div>
                        <div class="chart-container2">
                            <div id="chart1"></div>
                        </div>
                        <div class="text-center">
                            <p class="mb-0 font-12">24K users increased from last month</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-xxl-2 d-flex align-items-stretch">
                <div class="card w-100 rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="">
                                <h5 class="mb-0">2</h5>
                                <p class="mb-0">Total Pending</p>
                            </div>
                        </div>
                        <div class="chart-container2">
                            <div id="chart2"></div>
                        </div>
                        <div class="text-center">
                            <p class="mb-0 font-12"><span class="text-success me-1">12.5%</span> from last month</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-xxl-4 d-flex align-items-stretch">
                <div class="card w-100 rounded-4">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-0">Monthly View</h6>
                        </div>
                        <div class="mt-4" id="chart5"></div>
                        <p>Avrage monthly view for demos</p>
                        <div class="d-flex align-items-center gap-3 mt-4">
                            <div class="">
                                <h1 class="mb-0 text-primary">68.9%</h1>
                            </div>
                            <div class="d-flex align-items-center align-self-end">
                                <p class="mb-0 text-success">34.5%</p>
                                <span class="material-icons-outlined text-success">expand_less</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-xxl-4 d-flex align-items-stretch">
                <div class="card w-100 rounded-4">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="">
                                    <h5 class="mb-0">Device Type</h5>
                                </div>
                            </div>
                            <div class="position-relative">
                                <div class="piechart-legend">
                                    <h2 class="mb-1">68%</h2>
                                    <h6 class="mb-0">Total Views</h6>
                                </div>
                                <div id="chart6"></div>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-0 d-flex align-items-center gap-2 w-25"><span class="material-icons-outlined fs-6 text-primary">desktop_windows</span>Desktop</p>
                                    <div class="">
                                        <p class="mb-0">35%</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-0 d-flex align-items-center gap-2 w-25"><span class="material-icons-outlined fs-6 text-danger">tablet_mac</span>Tablet</p>
                                    <div class="">
                                        <p class="mb-0">48%</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-0 d-flex align-items-center gap-2 w-25"><span class="material-icons-outlined fs-6 text-success">phone_android</span>Mobile</p>
                                    <div class="">
                                        <p class="mb-0">27%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-stretch">
                        <div class="card w-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-1">
                                    <div class="">
                                        <h5 class="mb-0">82.7K</h5>
                                        <p class="mb-0">Total Approve</p>
                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle" data-bs-toggle="dropdown">
                                            <span class="material-icons-outlined fs-5">more_vert</span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="chart-container2">
                                    <div id="chart3"></div>
                                </div>
                                <div class="text-center">
                                    <p class="mb-0 font-12"><span class="text-success me-1">12.5%</span> from last month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 d-flex align-items-stretch">
                        <div class="card w-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-1">
                                    <div class="">
                                        <h5 class="mb-0">68.4K</h5>
                                        <p class="mb-0">Total Rejected</p>
                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle" data-bs-toggle="dropdown">
                                            <span class="material-icons-outlined fs-5">more_vert</span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="chart-container2">
                                    <div id="chart4"></div>
                                </div>
                                <div class="text-center">
                                    <p class="mb-0 font-12">35K users increased from last month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="">
                                <h3 class="mb-0"><?= $this->db_model->count_all('customer') ?></h3>
                            </div>
                        </div>
                        <p class="mb-0">Total Team</p>
                        <div id="chart7"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>