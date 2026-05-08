<?php $this->load->view('layout/header'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<style>
    .code{
        background-color: #3ab9ac;
    }
    .stat-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 12px 16px;
        background-color: #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        transition: 0.3s ease;
    }
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .info-box.bg-success, .info-box.bg-warning, .info-box.bg-danger, .info-box.bg-info, .info-box.bg-secondary {
        background-color: #3ab9ac !important;
        color: white !important;
    }
    .info-box.bg {
        background-color: #3ab9ac !important;
        color: white !important;
    }
</style>

<div class="wrapper">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><?=$this->lang->line('dashboard')?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <section class="content">
            <div class="container-fluid">
                <style type="text/css">
                    .info-box-icon{
                        padding-left: 5px !important;
                        margin-left : 5px !important;
                        margin-right: 5px !important;
                    }
                    .bg{
                        background-color: #3ab9ac !important;
                    }
                </style>
            </div>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row text-light">
                    <?php
                    $session_user_id = $this->session->userdata('user_id');
                    $head_office_count = $this->db_model->count_all('sale_requests', array('added_by' => $session_user_id));
                    $branch_users = $this->db->select('id')->where('added_by', $session_user_id)->get('users')->result_array();
                    $branch_user_ids = array_column($branch_users, 'id');
                    
                    $branch_count = 0;
                    if (!empty($branch_user_ids)) {
                        $this->db->where_in('added_by', $branch_user_ids);
                        $branch_count = $this->db->count_all_results('sale_requests');
                    }
                    $total_count = $head_office_count + $branch_count;
                    ?>
                    
                    <!-- Total Purchases Card -->
                    <div class="col-md-4 col-12">
                        <a href="<?= base_url('Purchase_request') ?>" class="info-box bg" style="color: white !important;">
                            <div class="info-box-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Purchases</span>
                                <span class="info-box-number"><?php echo $total_count; ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <div class="row mt-2" style="font-size: 16px;">
                                    <div class="col-6">
                                        <strong>From Head Office:</strong> <?php echo $head_office_count; ?>
                                    </div>
                                    <div class="col-6 text-right">
                                        <strong>From Branches:</strong> <?php echo $branch_count; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <?php  
                    $user_id = $this->session->userdata('user_id');
                    $user_info = $this->db->select('role')->where('id', $user_id)->get('users')->row();
                    
                    if (!empty($user_info) && $user_info->role == 'client') { 
                    ?>
                    <!-- Total Branch Managers Card -->
                    <div class="col-md-4 col-6">
                        <a href="<?= base_url('purchase_request/add_branch_manager') ?>" class="info-box bg" style="color: white;">
                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" style="color: white;">Total Branch Managers</span>
                                <span class="info-box-number" style="color: white;">
                                    <?php 
                                        echo $this->db_model->count_all('users', array(
                                            'role' => 'clients_branch_manager',
                                            'added_by' => $session_user_id
                                        ));
                                    ?>
                                </span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description" style="font-size:14px; color: white;">
                                    Total Branch Managers Count.
                                </span>
                            </div>
                        </a>
                    </div>

                    <!-- Total Branches Card -->
                    <div class="col-md-4 col-6">
                        <a href="<?= base_url('purchase_request/add_branch') ?>" class="info-box bg" style="color: white;">
                            <span class="info-box-icon"><i class="fas fa-building"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" style="color: white;">Total Branches</span>
                                <span class="info-box-number" style="color: white;">
                                    <?php 
                                        echo $this->db_model->count_all('clients_branch', array('added_by' => $session_user_id));
                                    ?>
                                </span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description" style="font-size:14px; color: white;">
                                    Total active branches in the system.
                                </span>
                            </div>
                        </a>
                    </div>
                    <?php 
                    }
                    ?>
                </div>

                <!-- Status Counts Section - 5 Cards with Uniform Color -->
                <?php if (isset($status_counts)): ?>
                <div class="row mt-4">
                    <!-- Approved Purchases Card -->
                    <div class="col-md-2-4 col-sm-6 col-12">
                        <a href="<?= base_url('Purchase_request?status=approved') ?>" class="info-box bg" style="background-color: #3ab9ac !important; color: white;">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Approved Purchases</span>
                                <span class="info-box-number"><?= $status_counts->approved ?? 0 ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description">
                                    Total Approved Purchase Requests
                                </span>
                            </div>
                        </a>
                    </div>

                    <!-- Unapproved Purchases Card (Pending) -->
                    <div class="col-md-2-4 col-sm-6 col-12">
                        <a href="<?= base_url('Purchase_request?status=pending') ?>" class="info-box bg" style="background-color: #3ab9ac !important; color: white;">
                            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Unapproved Purchases</span>
                                <span class="info-box-number"><?= $status_counts->unapproved ?? 0 ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description">
                                    Pending Approval Requests
                                </span>
                            </div>
                        </a>
                    </div>

                    <!-- Rejected/Cancelled Purchases Card -->
                    <div class="col-md-2-4 col-sm-6 col-12">
                        <a href="<?= base_url('Purchase_request?status=rejected') ?>" class="info-box bg" style="background-color: #3ab9ac !important; color: white;">
                            <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Rejected/Cancelled</span>
                                <span class="info-box-number"><?= $status_counts->rejected ?? 0 ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description">
                                    Rejected Purchase Requests
                                </span>
                            </div>
                        </a>
                    </div>

                    <!-- Delivered Purchases Card -->
                    <div class="col-md-2-4 col-sm-6 col-12">
                        <a href="<?= base_url('Purchase_request?status=delivered') ?>" class="info-box bg" style="background-color: #3ab9ac !important; color: white;">
                            <span class="info-box-icon"><i class="fas fa-box-open"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">In Transit</span>
                                <span class="info-box-number"><?= $status_counts->in_transit ?? 0 ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description">
                                    Items in Transit
                                </span>
                            </div>
                        </a>
                    </div>


                    <!-- Delivery Confirmed Purchases Card -->
                    <div class="col-md-2-4 col-sm-6 col-12">
                        <a href="<?= base_url('Purchase_request?status=delivery_confirmed') ?>" class="info-box bg" style="background-color: #3ab9ac !important; color: white;">
                            <span class="info-box-icon"><i class="fas fa-check-double"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Delivery Confirmed</span>
                                <span class="info-box-number"><?= $status_counts->delivery_confirmed ?? 0 ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description">
                                    Delivery Confirmed by Customer
                                </span>
                            </div>
                        </a>
                    </div>
                </div>

                <style>
                    /* Custom column class for 5 equal columns */
                    .col-md-2-4 {
                        flex: 0 0 20%;
                        max-width: 20%;
                    }
                    @media (max-width: 768px) {
                        .col-md-2-4 {
                            flex: 0 0 50%;
                            max-width: 50%;
                        }
                    }
                    @media (max-width: 576px) {
                        .col-md-2-4 {
                            flex: 0 0 100%;
                            max-width: 100%;
                        }
                    }
                </style>
                <?php endif; ?>
                
                <?php if (!empty($user_info) && $user_info->role == 'client'): ?>
                <!-- Graph Section -->
                <?php
                // Get branch user IDs for graph
                $branch_users_graph = $this->db->select('id')->where('added_by', $session_user_id)->get('users')->result_array();
                $branch_user_ids_graph = array_column($branch_users_graph, 'id');
                $current_year = date('Y');

                // Get head office monthly purchase count
                $head_office_data = $this->db->query("
                    SELECT MONTH(created_date) as month, COUNT(id) as total
                    FROM sale_requests
                    WHERE added_by = '$session_user_id' AND YEAR(created_date) = $current_year
                    GROUP BY MONTH(created_date)
                ")->result();

                // Get branch monthly purchase count
                $branch_data_graph = [];
                if (!empty($branch_user_ids_graph)) {
                    $ids_str = implode(',', array_map('intval', $branch_user_ids_graph));
                    $branch_data_graph = $this->db->query("
                        SELECT MONTH(created_date) as month, COUNT(id) as total
                        FROM sale_requests
                        WHERE added_by IN ($ids_str) AND YEAR(created_date) = $current_year
                        GROUP BY MONTH(created_date)
                    ")->result();
                }

                // Fill data for all 12 months
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $head_office_counts = array_fill(1, 12, 0);
                $branch_counts_graph = array_fill(1, 12, 0);

                foreach ($head_office_data as $row) {
                    $head_office_counts[(int)$row->month] = (int)$row->total;
                }
                foreach ($branch_data_graph as $row) {
                    $branch_counts_graph[(int)$row->month] = (int)$row->total;
                }

                // Prepare for chart.js
                $labels = $months;
                $head_data = [];
                $branch_data_arr = [];
                for ($i = 1; $i <= 12; $i++) {
                    $head_data[] = $head_office_counts[$i];
                    $branch_data_arr[] = $branch_counts_graph[$i];
                }

                // Initialize variables for safety
                $top_branch_by_amount = null;
                $top_branch_by_orders = null;
                $top_branches_chart = [];

                if (!empty($branch_user_ids_graph)) {
                    $ids_str = implode(',', array_map('intval', $branch_user_ids_graph));

                    // Top branch by amount
                    $top_branch_by_amount = $this->db->query("
                        SELECT cb.branch_name, SUM(sr.total) as total_amount
                        FROM sale_requests sr
                        JOIN clients_branch cb ON cb.id = sr.branch_id
                        WHERE sr.added_by IN ($ids_str) AND YEAR(sr.invoice_date) = $current_year
                        GROUP BY sr.branch_id
                        ORDER BY total_amount DESC
                        LIMIT 1
                    ")->row();

                    // Top branch by orders
                    $top_branch_by_orders = $this->db->query("
                        SELECT cb.branch_name, COUNT(sr.id) as total_orders
                        FROM sale_requests sr
                        JOIN clients_branch cb ON cb.id = sr.branch_id
                        WHERE sr.added_by IN ($ids_str) AND YEAR(sr.invoice_date) = $current_year
                        GROUP BY sr.branch_id
                        ORDER BY total_orders DESC
                        LIMIT 1
                    ")->row();

                    // Top 5 branches for chart
                    $top_branches_chart = $this->db->query("
                        SELECT cb.branch_name, SUM(sr.total) as total_amount
                        FROM sale_requests sr
                        JOIN clients_branch cb ON cb.id = sr.branch_id
                        WHERE sr.added_by IN ($ids_str) AND YEAR(sr.invoice_date) = $current_year
                        GROUP BY sr.branch_id
                        ORDER BY total_amount DESC
                        LIMIT 5
                    ")->result();
                }

                // Prepare chart data arrays
                $chart_labels = [];
                $chart_values = [];
                foreach ($top_branches_chart as $b) {
                    $chart_labels[] = $b->branch_name;
                    $chart_values[] = round($b->total_amount, 2);
                }
                ?>

                <!-- Graph Section -->
                <div class="card-body d-flex flex-wrap flex-md-nowrap mt-4">
                    <!-- Left: Bar Chart -->
                    <div class="flex-grow-1">
                        <canvas id="purchaseGraph" height="100"></canvas>
                    </div>
                
                    <!-- Right: Pie Chart + Info -->
                    <div class="ms-md-4 mt-4 mt-md-0" style="min-width: 300px;">
                        <?php if ($top_branch_by_amount): ?>
                        <div class="stat-card shadow-sm mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <strong class="d-block mb-1">Top by Amount</strong>
                                    <span class="text-muted"><?= $top_branch_by_amount->branch_name ?></span>
                                </div>
                                <div class="text-success fs-4">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-secondary"><?= $this->session->userdata('currency_symbol') ?><?= number_format($top_branch_by_amount->total_amount, 2) ?></small>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($top_branch_by_orders): ?>
                        <div class="stat-card shadow-sm mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <strong class="d-block mb-1">Top by Orders</strong>
                                    <span class="text-muted"><?= $top_branch_by_orders->branch_name ?></span>
                                </div>
                                <div class="text-primary fs-4">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-secondary"><?= $top_branch_by_orders->total_orders ?> orders</small>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($chart_labels)): ?>
                        <div class="mt-3">
                            <h6 class="text-center mb-2"><i class="fas fa-coins text-warning"></i> Top Branches by Amount</h6>
                            <canvas id="pieChart" height="200"></canvas>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php $this->load->view('layout/footer'); ?>
<?php $this->load->view('customer/add_customer_modal'); ?>
<?php $this->load->view('service/add_service_modal'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxBar = document.getElementById('purchaseGraph').getContext('2d');
new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            {
                label: 'Head Office',
                data: <?= json_encode($head_data) ?>,
                backgroundColor: '#007bff'
            },
            {
                label: 'Branches',
                data: <?= json_encode($branch_data_arr) ?>,
                backgroundColor: '#28a745'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        }
    }
});

<?php if (!empty($chart_labels)): ?>
const ctxPie = document.getElementById('pieChart').getContext('2d');
new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: <?= json_encode($chart_labels) ?>,
        datasets: [{
            data: <?= json_encode($chart_values) ?>,
            backgroundColor: ['#f39c12', '#00c0ef', '#00a65a', '#f56954', '#3c8dbc']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
<?php endif; ?>
</script>