<?php
  $application_setting  = $this->application_settings_model->get_application_records();
  $company_setting      = $this->company_settings_model->get_company_records();
  $user                 = $this->ion_auth_model->user($this->session->userdata('user_id'))->row();
  $sidebar_theme = '';
  $navbar_theme = '';

  if($application_setting->sidebar_theme == '')
  {
    $sidebar_theme = 'sidebar_dark_primary';
  }
  else
  {
    $sidebar_theme = $application_setting->sidebar_theme;
  }

  if($application_setting->navbar_theme == '')
  {
    $navbar_theme = 'sidebar_dark_primary';
  }
  else
  {
    $navbar_theme = $application_setting->navbar_theme;
  }

  $promotion = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'promotion', 'active',$row = true,$check_delete_status = false);

  $pos = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'pos', 'active',$row = true,$check_delete_status = false);
?>
<?php
$user_id    = $this->session->userdata('user_id');
$company_id = $this->session->userdata('company_id');
$level_id   = $this->session->userdata('level_id');

$user = $this->db->select('first_name, last_name, level_id')
                 ->from('users')
                 ->where('id', $user_id)
                 ->get()
                 ->row();

$next_level = null;
if ($level_id > 0) {
    $next_level = $level_id - 1; 
}

$next_level_users = [];
if ($next_level !== null) {
    $approvers = $this->db->select('first_name, last_name')
                          ->from('users')
                          ->where('company_id', $company_id)
                          ->where('level_id', $next_level)
                          ->get()
                          ->result();

    foreach ($approvers as $a) {
        $next_level_users[] = ucwords($a->first_name . ' ' . $a->last_name);
    }
 }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> <?=$company_setting->company_name?> | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ionicons.min.css">
   <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/jqvmap/jqvmap.min.css"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- SweetAlert2 -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"> -->
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font_family.css">
  <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
 <!--  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/> -->
  <link href="<?php echo base_url();?>assets/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/all.css">
   <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/square/blue.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/autocomplete/') ?>/jquery_auto_complete.css">

  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/toastr/toastr.min.css">


  <style type="text/css">
*{
    font-size: 14px;
}
.custom-list {
  list-style: none; /* Remove default bullets */
  padding-left: 0; /* Remove default padding */
}

.custom-list li {
  position: relative; /* Position relative for positioning the icon */
  padding-left: 30px; /* Space for the icon */
}

.custom-list li::before {
  content: "\f054"; /* Unicode for Font Awesome icon (fa-chevron-right) */
  font-family: "Font Awesome 5 Free"; /* Ensure correct font-family */
  font-weight: 900; /* Required for Font Awesome icons */
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #000; /* Icon color */
}

.custom-list a {
  text-decoration: none; /* Remove underline from links */
  color: inherit; /* Inherit color from parent or set custom color */
}

.custom-list a:hover {
  color: #007bff; /* Change color on hover */
}


    label.required::after {
      content: ' *';
      color: red;
    }

    .text-left {
        text-align: left !important;
    }

    #calculator {
      background: #333;
      width: 374px;
      height: auto;
      margin: 100px auto;
      padding: 5px 5px 5px;
      overflow: hidden;
      background: white;
      background: radial-gradient(#ccc);
      background-size: cover;
      margin-left: -16px;
      margin-top: -17px;
      margin-bottom: -16px;
    }

    #screen {
      color: black;
      font-family: "Dosis";
      font-size: 40px;
      text-align: right;
      padding: 20px 10px 0px 0px;
      background: #B0BEC5;
      width: auto;
      height: 80px;
      overflow: hidden;
      margin: 10px 10px 10px 10px;
      border-radius: 3px;
      box-shadow: inset 0px 4px rgba(0, 0, 0, 0.2);
      user-select: none;
      overflow: visible;
      word-wrap: break-word;
      word-break: break-all;
    }

    .leftPad {
      /*background: rgba(250, 34, 89, .5);*/
      height: auto;
      width: 264px;
      float: left;
      margin: 0px 0px 0px 7px;
    }

    .rightPad {
      /*background: rgba(250, 89, 250, .9);*/
      height: 460px;
      width: auto;
      margin: 0 0 0 0;
      overflow: auto;
    }

    #display {
      float:right;
      padding: 0 0 0 10px;
      overflow:visible;
    }

    #displayLeft {
      float:left;
      padding: 0 0 0 10px;
      overflow:visible;
    }
    /*BUTTONS*/

    .btncal {
        outline: none;
        position: relative;
        background-color: #607D8B;
        border-radius: 5px;
        font-size: 40px;
        font-family: "Dosis";
        color: #fff;
        padding: 0px;
        margin: 5px 2px 5px 3px;
        width: 80px;
        height: 80px;
        -webkit-transition: 0.05s;
        -moz-transition: 0.05s;
        transition: 0.05s;
        overflow: hidden;
        cursor: pointer;
        box-shadow: 0 4px #455A64;
      
    }

    .btncal:hover {
      background-color: #47A;
      color: #fff;
      box-shadow: 0 4px #455A64;
      top: 2px;
    }

    .btncal:active {
      box-shadow: 0 0 #455A64;
      top: 6px;
    }

    .btn-3 {
      border-radius: 100px;
      box-shadow: 0 4px #ab3c3c;
      background: #cb4e4e;
      color: #fff;
    }

    .btn-3:hover {
      background-color: #F57F17;
      box-shadow: 0 4px #ab3c3c;
      top: 2px;
    }

    .btn-3:active {
      box-shadow: 0 0 #ab3c3c;
      top: 6px;
    }

    .btn-2 {
      box-shadow: 0 4px #A1887F;
      background-color: #FFA726;
    }

    .btn-2:hover {
      background-color: #F57F17;
      box-shadow: 0 4px #ab3c3c;
      top: 2px;
    }

    .btn-2:active {
      box-shadow: 0 0 #ab3c3c;
      top: 6px;
    }

    .btn-4 {
      box-shadow: 0 4px #455A64;
      background-color: #369;
    }

    .btn-4:hover {
      background-color: #47A;
      box-shadow: 0 4px #455A64;
      top: 2px;
    }

    .btn-4:active {
      box-shadow: 0 0 #455A64;
      top: 6px;
    }
    .modal-dialog-centered {
      max-width: 378px; /* New width for default modal */
    }

  </style>

  <style type="text/css">

    html {
      scroll-behavior: smooth;
    }
    .validation{
      color:red;
    }
    .breadcrumb-custom{
      background-color: #fff !important; 
      width: 100%; 
      padding-left: 10px !important;
    }
    .success-header{
      background-color: #28a745 !important;
      color:#fff;
    }
    .failure-header{
      background-color: #dc3545 !important;
      color:#fff;
    }
    .info-header{
      background-color: #17a2b8 !important;
      color:#fff;
    }
    .secondary-header{
      background-color: #6c757d !important;
      color:#fff;
    }

    .teal-header{
      background-color: #39cccc !important;
      color:#fff;
    }

    .purple-header{
      background-color: #6f42c1 !important;
      color:#fff;
    }

    .lime-header{
      background-color: #01ff70 !important;
    }

    .light-purple-header{
      background-color: #a09dcb !important;
      color:#fff;
    }

    .light-secondary-header{
      background-color: #b6babe !important;
      color:#fff !important;
    }

    .light-failure-header{
      background-color: #e7727d !important;
      color:#fff;
    }

    .light-success-header{
      background-color: #7eca8f !important;
      color:#fff;
    }

    .light-warning-header{
      background-color: #ffc107 !important;
      color:#000;
    }

    .warning-header{
      background-color: #ffc107 !important;
      /*color:#ccc;*/
    }

    .content  .card{
      overflow-x: auto !important;
    }
    .card-title{
      font-weight: bolder !important;
    }
    .footer_data{
      font-size: 20px;
    }

    .datepicker{
      z-index: 1000000 !important;
    }

    /* scollbar css */

    ::-webkit-scrollbar {
      width: 10px;
    }
     
    ::-webkit-scrollbar-track {
      background-color: white;
      border-radius: 5px;
    }
     
    ::-webkit-scrollbar-thumb {
      background-color: #20c997;
      border-radius: 5px;
    }

    .btn:focus{
      border-color: 1px solid #fff !important;
    }

    .financial-year-dropdown-menu{
      left: -126px !important;
    }

    /*change the select2 results background color*/
    .select2-results { background-color: #E0FFFA; }

    .daterangepicker{
      z-index: 9999999 !important;
    }
    .cursor-pointer{
      cursor: pointer;
    }
    textarea{
      white-space: pre-wrap;
    }
  </style>

  <style type="text/css">
    tbody td{
      padding-top:0.25rem !important;
      padding-bottom:0.25rem !important;
    }
    .thumbnail {
      width: 30px;
      height: 30px;
      background-color: blue;
      color: white;
      font-size: 18px;
      text-align: center;
      line-height: 30px;
    }
    .warningg
    {
           background-color: #EAF205;
    }

    /* Option 1: Pure Black */
.main-sidebar .nav-sidebar .nav-treeview .nav-link.active {
    background-color: #000000 !important;
}

/* Option 2: Dark Gray */
.main-sidebar .nav-sidebar .nav-treeview .nav-link.active {
    background-color: #343a40 !important;
}

/* Option 3: Charcoal */
.main-sidebar .nav-sidebar .nav-treeview .nav-link.active {
    background-color: #212529 !important;
}

/* Option 4: Dark Slate */
.main-sidebar .nav-sidebar .nav-treeview .nav-link.active {
    background-color: #2c3e50 !important;
}
  </style>
  
</head>

<body class="hold-transition sidebar-mini layout-fixed <?php if($application_setting->application_text_size == 0){echo ' text-sm ';} ?> <?=(($this->uri->segment(1)=='pos') ? 'sidebar-collapse':'' )?>">
  <!-- Paste this code after body tag -->
  <div class="se-pre-con"></div>
  <!-- Ends -->  
  <nav class="main-header navbar navbar-expand <?=$navbar_theme?>">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
         <div class="col-sm-6">
                          <h1 class="m-0 text-dark">
    <span style="font-size: 1.5rem;"><?= $this->lang->line('dashboard') ?></span>

    <?php
    $user_id = $this->session->userdata('user_id');
    $warehouse_name = "";

    if ($user_id) {
        $query_user = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get();
        if ($query_user->num_rows() > 0) {
            $branch_id = $query_user->row()->branch_id;

            if ($branch_id) {
                $query_warehouse = $this->db->select('name')->from('warehouse')->where('id', $branch_id)->get();
                if ($query_warehouse->num_rows() > 0) {
                    $warehouse_name = $query_warehouse->row()->name;
                }
            }
        }
    }
        if (!empty($warehouse_name)) {
            echo '<span class="ml-2 badge bg-info text-white" style="font-size: 1.5rem;">' . $warehouse_name . '</span>';
        }
        $user_info = $this->db->select('role, clients_branch_id, company_name,first_name,last_name')->where('id', $user_id)->get('users')->row();
        if ($user_info) {
            if ($user_info->role == 'client') {
             echo '<span class="ml-4 badge bg-info text-white" style="font-size: 1.5rem;">Head Office Manager (' . $user_info->first_name . ' ' . $user_info->last_name . ')</span>';
             } elseif ($user_info->role == 'clients_branch_manager') {
                $branch_name = $this->db->select('branch_name')->where('id', $user_info->clients_branch_id)->get('clients_branch')->row('branch_name');
                echo '<span class="ml-4 badge bg-info text-white" style="font-size: 1.5rem;">Branch Manager (' . $branch_name . ')</span>';
             }
          }
        ?>
      <span style="color:#444;font-size: 1.5rem;">
              <?php 
                $dept_id = $this->session->userdata('dept_id');
                $dept = $this->db->get_where('departments', ['id' => $dept_id])->row();
                $dept_name = $dept ? htmlspecialchars($dept->name) : 'Not Assigned';
              ?>
              <span class="badge bg-info" style="padding:6px 10px; margin-left:5px;">
               Dept:  <?php echo $dept_name; ?>
              </span>
            </span>
       </h1>
  
      </div>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->

    <!--   <li class="nav-item pr-1">
        <div class="btn-group">
 
      <?php
        if($this->permission_model->has_permission('show_ledger_balance'))
        {
      ?>
      <li class="nav-item dropdown">
        <a class="nav-link bg-lime" data-toggle="dropdown" href="#" style="border-radius: 5px;">
          <?php 
            $cash_ledger = $this->ledger_model->get_single_record(CASH_GROUP_LEDGER);
          ?>
          <?=$this->session->userdata('currency_symbol')?> <?=$cash_ledger->closing_balance?>
          <i class="fas fa-arrow-down mr-2"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header text-left"><?=$this->lang->line('ledger_account_balance')?></span>
          <div class="dropdown-divider"></div>
          <?php 
            $bank_accounts = $this->bank_account_model->get_records();

            if(sizeof($bank_accounts) > 0)
            {
              foreach ($bank_accounts as $value) {
          ?>
          <a href="#" class="dropdown-item">
            <?=$value->account_name?>
            <span class="float-right text-muted text-sm"><?=$this->session->userdata('currency_symbol')?> <?=$value->closing_balance?></span>
            <div class="progress progress-xxs">
              <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
              </div>
            </div>
            <span style="font-size: 12px"><?=$value->bank_name?></span>
          </a>

          <div class="dropdown-divider"></div>
          <?php 
              }
            }
            else
            {
          ?>
          <a href="#" class="dropdown-item">
            <?=$this->lang->line('no_records_available')?>
          </a>
          <?php
            }
          ?>
        </div>
      </li>
      <?php 
        }
      ?>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-globe"></i>
        </a>
        <?php 
          $languages = $this->utility_model->get_languages();
        ?>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="overflow-y: scroll;">
          <span class="dropdown-item dropdown-header"><?=sizeof($languages)?> languages are available.</span>
          <div class="dropdown-divider"></div>
          <?php 
            foreach ($languages as $value) {
          ?>
          <a href="<?=base_url('languageSwitcher/switchLang/'.$value->language)?>" class="dropdown-item">
            <?=$value->description?>
            <?php 
              $session_lang = $this->session->userdata('site_lang');
              if($session_lang == $value->language)
              {
            ?>
                <span class="float-right text-muted text-sm"><i class="fas fa-check-circle"></i></span>
            <?php 
              }
            ?>
          </a>
          <?php
            }
          ?>
          <div class="dropdown-divider"></div>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-user-tie"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          
          <!-- <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item" data-toggle="modal" data-target="#activate_modal">
            <i class="fas fa-key mr-2"></i> <?php echo $this->lang->line('application_settings_enter_your_activation_key');?>
          </a> -->
          
          <div class="dropdown-divider"></div>
          <a href="<?=base_url('auth/profile')?>" class="dropdown-item">
            <i class="fas fa-id-card-alt mr-2"></i> <?php echo $this->lang->line('user_edit_profile');?>
            
          </a>
          <div class="dropdown-divider"></div>
          <a href="<?=base_url('auth/logout')?>" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> <?php echo $this->lang->line('user_log_out');?>
            
          </a>
          <div class="dropdown-divider"></div>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-3 <?=$sidebar_theme?>">
    <!-- Brand Logo -->
    
    <?php
    $user_id = $this->session->userdata('user_id');
    $warehouse_name = "";
    if ($user_id) {
        $query_user = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get();
        if ($query_user->num_rows() > 0) {
            $branch_id = $query_user->row()->branch_id;

            if ($branch_id) {
                $query_warehouse = $this->db->select('name')->from('warehouse')->where('id', $branch_id)->get();
                if ($query_warehouse->num_rows() > 0) {
                    $warehouse_name = $query_warehouse->row()->name;
                }
            }
        }
    }
?>

<?php if (!empty($warehouse_name)): ?>
    <a class="brand-link bg-light">
<?php else: ?>
   <a   class="brand-link bg-light">
<?php endif; ?>

    <!--<a href="<?=base_url('auth/dashboard')?>" class="brand-link bg-danger">-->
      <center>
           <img src="<?= base_url('/assets/images/0/' . $company_setting->logo); ?>"  style="width: 90%; height: auto; vertical-align: left;">
          <!--<span class="brand-text font-weight"><?=strtoupper($company_setting->company_name)?></span>-->
          
          </center>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        <i class="fa fa-user" style="color: white !important; font-size: 28px;"></i>
    </div>

    <div class="info">
        <a href="#" class="d-block"><?= ucwords($user->first_name . ' ' . $user->last_name) ?></a>

        <?php 
        $session_role = $this->session->userdata('role'); 
        $allowed_roles = ['client', 'clients_branch_manager', 'approval_2', 'approval_3', 'approval_4'];
        if (in_array($session_role, $allowed_roles) && !empty($next_level_users)):

            $max_visible = 1; // Show only the first user
            $total_users = count($next_level_users);

            // Show first user(s)
            // $visible_users = array_slice($next_level_users, 0, $max_visible);
            // echo '<small class="text-muted" style="font-size: 12px;"><i class="fas fa-user-shield"></i> Approver- ' 
            //     . implode(', ', $visible_users) . '</small>';








            // If more users, show "+N more" button
            if ($total_users > $max_visible):
                $more_count = $total_users - $max_visible;
                $all_users_js = json_encode($next_level_users); 
                ?>
                <!--<a href="javascript:void(0);" class="text-primary" -->
                <!--   onclick='showMoreUsers(<?= $all_users_js ?>)'>-->
                <!--    +<?= $more_count ?> more-->
                <!--</a>-->
        <?php
            endif;
        endif; 
        ?>
        
        
  <!--new code-->
  
        <?php
        // Get the logged-in user's ID
$user_id = $this->session->userdata('user_id');

// Fetch the 'added_by' field (i.e., the user ID of the person who added the record)
$approvers = $this->db->select('added_by')
                      ->from('users')
                      ->where('id', $user_id)
                      ->get()
                      ->row(); // Use row() instead of result() to get a single result

// If the 'added_by' field is not null, fetch the first_name and last_name of the person who added the record
if ($approvers && $approvers->added_by) {
    $added_by_user = $this->db->select('first_name, last_name')
                              ->from('users')
                              ->where('id', $approvers->added_by)
                              ->get()
                              ->row(); // Fetch the added_by user's details

    // If the added_by user is found, display their full name
    if ($added_by_user) {
        $approver_name = ucwords($added_by_user->first_name . ' ' . $added_by_user->last_name);
        echo '<small class="text-muted" style="font-size: 12px;"><i class="fas fa-user-shield"></i> Approver- ' 
             . $approver_name . '</small>';
    }
}
?>
    </div>
</div>


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column  <?php if($application_setting->sidebar_nav_legacy_style == 1) echo ' nav-legacy '; ?> <?php if($application_setting->sidebar_menu_flat_style == 1) echo ' nav-flat '; ?> text-sm" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <?php 
            if($this->permission_model->has_module_permission('dashboard'))
            {
          ?>
          <li class="nav-item">
            <a href="<?=base_url('auth/dashboard')?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt text-red"></i>
              <p>
                <?=$this->lang->line('header_dashboard')?>
              </p>
            </a>
          </li>
          
          <?php 
            }
          ?>
          
          <?php
            if(
               // $this->permission_model->has_module_permission('sales') ||
               
                $this->permission_model->has_module_permission('sales_return') ||
               
                $this->permission_model->has_module_permission('quotation') ||                
                
                $this->permission_model->has_module_permission('proforma_invoice') ||
                $this->permission_model->has_module_permission('delivery_challan') ||
                $this->permission_model->has_module_permission('payment_in') ||
                $this->permission_model->has_module_permission('credit_debit_note') 
              )
            {
          ?>
          <li class="nav-item has-treeview
            <?php 
              if( $this->uri->segment(1)=='sales_return'    || $this->uri->segment(1)=='quotation'  || $this->uri->segment(1)=='proforma_invoice' || $this->uri->segment(1)=='credit_debit_note' || $this->uri->segment(1)=='delivery_challan' ||  $this->uri->segment(1)=='payment_in'  )
                echo ' menu-open';
            ?>
            ">
            <a href="#" class="nav-link
              <?php 
                if($this->uri->segment(1)=='sales_return' || $this->uri->segment(1)=='quotation' || $this->uri->segment(1)=='proforma_invoice' || $this->uri->segment(1)=='credit_debit_note' || $this->uri->segment(1)=='delivery_challan' || $this->uri->segment(1)=='payment_in')
                  echo ' active';
              ?>
            ">
              
              <i class="nav-icon fas fa-store text-dark"></i>
              <p>
                Sale
                <i class="fas fa-angle-left right"></i>
              </p>
            </a> 
            
            
            <ul class="nav nav-treeview">

              <?php 
                if($this->permission_model->has_module_permission('quotation'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('quotation');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='quotation')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                     <?=$this->lang->line('header_quotation')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              
              <?php 
                if($this->permission_model->has_module_permission('proforma_invoice'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('proforma_invoice');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='proforma_invoice')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                     <?=$this->lang->line('header_proforma_invoice')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
             
             
              <?php 
                if($this->permission_model->has_module_permission('delivery_challan'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('delivery_challan');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='delivery_challan')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                     <!--<?=$this->lang->line('header_proforma_invoice')?>-->
                     Delivery Challan
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>              
             
             <!-- <?php 
                if($this->permission_model->has_module_permission('sales'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('sale');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='sale')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                    <p class="text-danger ml-4">
                      <?=$this->lang->line('header_sales')?>
                    </p>
                </a>
              </li>
              <?php 
                }
              ?>-->

               <?php 
                if($this->permission_model->has_module_permission('sales_return'))
                {
              ?>
              <li class="nav-item">
                <a href="<?=base_url('sales_return')?>" class="nav-link
                    <?php 
                      if($this->uri->segment(1)=='sales_return')
                        echo ' active';
                    ?>"
                >
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('header_sales_return')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?> 

            <!--<?php 
                if($this->permission_model->has_module_permission('credit_debit_note'))
                {
              ?>
              <li class="nav-item">
                <a href="<?=base_url('credit_debit_note')?>" class="nav-link
                    <?php 
                      if($this->uri->segment(1)=='credit_debit_note')
                        echo ' active';
                    ?>"
                >
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('header_credit_debit_note')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>-->
            <?php 
                if($this->permission_model->has_module_permission('payment_in'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('payment_in');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='payment_in')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                     Payment In
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

            </ul>
          </li>
          <?php
            } 
          ?>
          
<?php
if($this->permission_model->has_module_permission('Sales_request'))
{
?>
<li class="nav-item has-treeview
    <?php 
      if($this->uri->segment(1)=='Sales_request' || $this->uri->segment(1)=='sale')
        echo ' menu-open';
    ?>
">

<a href="#" class="nav-link
    <?php 
      if($this->uri->segment(1)=='Sales_request' || $this->uri->segment(1)=='sale')
        echo ' active';
    ?>
">

    <i class="nav-icon fas fa-shopping-bag text-pink"></i>
    <p>
        Sales Request
        <i class="fas fa-angle-left right"></i>
    </p>
</a>            

<ul class="nav nav-treeview">

    <!-- Sales Request -->
    <li class="nav-item">
        <a href="<?=base_url('Sales_request')?>" class="nav-link
            <?php 
              if($this->uri->segment(1)=='Sales_request')
                echo ' active';
            ?>">
            <i class="nav-icon text-danger"></i>
            <p class="text-danger ml-4">
                <?=$this->lang->line('header_sales_request')?>
            </p>
        </a>
    </li>

    <!-- Sales -->
    <li class="nav-item">
        <a href="<?=base_url('sale')?>" class="nav-link
            <?php 
              if($this->uri->segment(1)=='sale')
                echo ' active';
            ?>">
            <i class="nav-icon"></i>
            <p class="text-danger ml-4">
              Create Sale
               <!-- <?=$this->lang->line('header_sales')?> -->
            </p>
        </a>
    </li>

</ul>
</li>
<?php } ?>
<!---- sales Request end---------->
          
          <!--sales Request Start-->
              <!-- <?php
            if(
                
                $this->permission_model->has_module_permission('Sales_request')
                              
              
              )
            {
          ?>
          <li class="nav-item has-treeview
            <?php 
              if($this->uri->segment(1)=='Sales_request')
                echo ' menu-open';
            ?>
            ">
            <a href="#" class="nav-link
              <?php 
                if( $this->uri->segment(1)=='Sales_request')
                  echo ' active';
              ?>
            ">
              
              <i class="nav-icon fas fa-shopping-bag  text-pink"></i>
              <p>
               Sales Request
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>            
            <ul class="nav nav-treeview">

            <?php 
                if($this->permission_model->has_module_permission('Sales_request'))
                {
              ?>
              <li class="nav-item">
                <a href="<?=base_url('Sales_request')?>" class="nav-link
                    <?php 
                      if($this->uri->segment(1)=='Sales_request')
                        echo ' active';
                    ?>"
                >
                  <i class="nav-icon text-danger"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('header_sales_request')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
              
              <?php 
                if($this->permission_model->has_module_permission('sales'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('sale');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='sale')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                    <p class="text-danger ml-4">
                      <?=$this->lang->line('header_sales')?>
                    </p>
                </a>
              </li>
              <?php 
                }
              ?>
            </ul>
          </li>
          <?php
            } 
          ?>  -->
<!---- sales Request end---------->
   
   
       <!-- Purchase Request Section Start -->
<?php
// Check if the current user has permission to access the 'Purchase_request' module
if ($this->permission_model->has_module_permission('Purchase_request')) {
?>

    
    <li class="nav-item">
        <a href="<?= base_url('auth/dashboard2') ?>" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt text-red"></i>
            <p> Dashboard </p>
        </a>
    </li>
    
     <!--Sidebar menu item for Purchase Request with conditional class for open state -->
    <li class="nav-item has-treeview
        <?php 
          if ($this->uri->segment(1) == 'Purchase_request') echo ' menu-open';
        ?>
    ">
    <a href="#" class="nav-link
        <?php 
          if ($this->uri->segment(1) == 'Purchase_request') echo ' active';
        ?>
    ">
        <i class="nav-icon fas fa-shopping-bag text-pink"></i>
        <p>
            Purchase Request
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= base_url('Purchase_request') ?>" class="nav-link
                <?php 
                  if ($this->uri->segment(1) == 'Purchase_request') echo ' active';
                ?>
            ">
                <i class="nav-icon text-danger"></i>
                <p class="text-danger ml-4">Add Purchase Request</p>
            </a>
        </li>
    </ul>
    </li>
<?php 
} 
?>
<!-- Purchase Request Section End -->


<!--Sub User Start-->
<?php
    if ($this->permission_model->has_module_permission('Sub_users')) {
?>
<li class="nav-item has-treeview
    <?php 
      if ($this->uri->segment(1) == 'Sub_users') echo ' menu-open';
    ?>
">
  <a href="#" class="nav-link
    <?php 
      if ($this->uri->segment(1) == 'Sub_users') echo ' active';
    ?>
  ">
    <i class="nav-icon fas fa-users text-pink"></i>
    <p>
      Sub Users
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>            
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?= base_url('purchase_request/add_sub_user') ?>" class="nav-link
        <?php 
          if ($this->uri->segment(1) == 'Sub_users') echo ' active';
        ?>"
      >
        <i class="nav-icon text-danger"></i>
        <p class="text-danger ml-4">Add Sub User</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= base_url('purchase_request/view_sub_user') ?>" class="nav-link
        <?php 
          if ($this->uri->segment(2) == 'add_sub_user') echo ' active';
        ?>"
      >
        <i class="nav-icon text-danger"></i>
        <p class="text-danger ml-4">View Sub User</p>
      </a>
    </li>
  </ul>
</li>
<?php } ?>
<!--Sub User End-->

        <!--add branch option-->
        <?php
        $level_id = $this->session->userdata('level_id');
        
        if ($level_id != 3 && $this->permission_model->has_module_permission('Branche')) {
        ?>
        <li class="nav-item has-treeview
            <?php if ($this->uri->segment(1) == 'Branch') echo ' menu-open'; ?>
        ">
          <a href="#" class="nav-link
            <?php if ($this->uri->segment(1) == 'Branch') echo ' active'; ?>
          ">
            <i class="nav-icon fas fa-users text-pink"></i>
            <p>
             Branches
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>            
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('purchase_request/add_branch') ?>" class="nav-link
                <?php if ($this->uri->segment(1) == 'Branche') echo ' active'; ?>
              ">
                <i class="nav-icon text-danger"></i>
                <p class="text-danger ml-4">Add Branch</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('purchase_request/add_branch_manager') ?>" class="nav-link
                <?php if ($this->uri->segment(2) == 'Branche') echo ' active'; ?>
              ">
                <i class="nav-icon text-danger"></i>
                <p class="text-danger ml-4">Add Branch Manager</p>
              </a>
            </li>
          </ul>
        </li>
        <?php } ?>


         <?php 
            if ($this->permission_model->has_module_permission('change_password')) {
            ?>
            <li class="nav-item has-treeview">
              <a href="<?= base_url('change_password') ?>" class="nav-link">
                <i class="nav-icon fas fa-lock text-red"></i>
                <p>Change Password</p>
              </a>
            </li>
            <?php 
            }
        ?>

<?php
    if ($this->permission_model->has_module_permission('creports')) {
?>
<li class="nav-item has-treeview
    <?php 
      if ($this->uri->segment(1) == 'Client_report') echo ' menu-open';
    ?>
">
  <a href="#" class="nav-link
    <?php 
      if ($this->uri->segment(1) == 'Client_report') echo ' active';
    ?>
  ">
    <i class="nav-icon fas fa-book text-blue"></i>
    <p>
     Reports
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>            
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?= base_url('Client_report/purchase') ?>" class="nav-link <?php   if ($this->uri->segment(1) == 'Client_report') echo ' active'; ?>"  >
        <i class="nav-icon text-danger"></i>
        <p class="text-danger ml-4">Purchase Report</p>
      </a>
    </li>
    
     <li class="nav-item">
      <a href="<?= base_url('Client_report/tax_purchase') ?>" class="nav-link <?php   if ($this->uri->segment(1) == 'Client_report') echo ' active'; ?>"  >
        <i class="nav-icon text-danger"></i>
        <p class="text-danger ml-4">Tax Invoice Report</p>
      </a>
    </li>
    
     <li class="nav-item">
        <a href="<?= base_url('Client_report/detailed_invoice_report') ?>"
           class="nav-link <?= ($this->uri->segment(2) == 'detailed_invoice_report') ? 'active' : '' ?>">
            <i class="nav-icon text-danger"></i>
            <p class="text-danger ml-4">Detailed Invoice Report</p>
        </a>
    </li>

  </ul>
</li>
<li class="nav-item">
    <a href="<?= base_url('support_client') ?>" class="nav-link">
        <i class="nav-icon fas fa-tachometer-alt text-green"></i>
        <p> Support </p>
    </a>
</li>
<li class="nav-item">
    <a href="<?= base_url('auth/logout') ?>" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt text-red"></i>
        <p> Logout </p>
    </a>
</li>
<?php } ?>
<!--Sub User End-->
<!--add branch option end -->
          <?php
            if(
                $this->permission_model->has_module_permission('purchase') ||
                $this->permission_model->has_module_permission('purchase_order') ||
                
                $this->permission_model->has_module_permission('purchase_return') || 
                 $this->permission_model->has_module_permission('payment_out') 
                              
              
              )
            {
          ?>
          <li class="nav-item has-treeview
            <?php 
              if($this->uri->segment(1)=='purchase' || $this->uri->segment(1)=='purchase_order' || $this->uri->segment(1)=='purchase_return' || $this->uri->segment(1)=='payment_out')
                echo ' menu-open';
            ?>
            ">
            <a href="#" class="nav-link
              <?php 
                if( $this->uri->segment(1)=='purchase' || $this->uri->segment(1)=='purchase_order' || $this->uri->segment(1)=='purchase_return' || $this->uri->segment(1)=='payment_out')
                  echo ' active';
              ?>
            ">
              
              <i class="nav-icon fas fa-shopping-bag  text-pink"></i>
              <p>
               Purchase
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>            
            <ul class="nav nav-treeview">

            <?php 
                if($this->permission_model->has_module_permission('purchase_order'))
                {
              ?>
              <li class="nav-item">
                <a href="<?=base_url('purchase_order')?>" class="nav-link
                    <?php 
                      if($this->uri->segment(1)=='purchase_order')
                        echo ' active';
                    ?>"
                >
                  <i class="nav-icon text-danger"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('header_purchase_order')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
              
              <?php 
                if($this->permission_model->has_module_permission('purchase'))
                {
              ?>
              <li class="nav-item">
                <a href="<?=base_url('purchase')?>" class="nav-link
                    <?php 
                      if($this->uri->segment(1)=='purchase')
                        echo ' active';
                    ?>"
                >
                  <i class="nav-icon text-danger"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('header_purchase')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_module_permission('purchase_return'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('purchase_return');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='purchase_return')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon text-danger"></i>
                  <p class="text-danger ml-4">
                    Purchase Return/Debit Note
                    <!-- <?=$this->lang->line('header_purchase_return')?> -->
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
             <?php 
                if($this->permission_model->has_module_permission('payment_out'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('payment_out');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='payment_out')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                     Payment Out
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
             
            </ul>
          </li>
          <?php
            } 
          ?>
          
      <!--clients role-->
            <?php 
            if( 
                  $this->permission_model->has_module_permission('clients')
              )
            {
          ?>
          <li class="nav-item has-treeview
          ">
            <a href="#" class="nav-link
            ">
              <i class="nav-icon fas fa-users text-green"></i>
              <p>
              Clients
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            
            <ul class="nav nav-treeview">
              
              <?php 
                if($this->permission_model->has_module_permission('clients'))
                {
              ?>
              <li class="nav-item">
                <a href="<?=base_url('clients')?>" class="nav-link
                  ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                  Companies
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
              <?php 
                if($this->permission_model->has_module_permission('clients'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('clients/clients_list');?>" class="nav-link
                 
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                Head Office Managers
                  </p>
                </a>
              </li>
              
               <li class="nav-item">
                <a href="<?=base_url('clients/all_users_list')?>" class="nav-link
                  ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                  All Users
                  </p>
                </a>
              </li>
              
              
              
              <?php 
                }
              ?>

              
            </ul>
          </li>
          <?php 
            }
          ?>
            <!--clients role-->

   
          <?php 
            if($this->permission_model->has_module_permission('gst_return'))
            {
          ?>
          <!-- <li class="nav-item has-treeview">
            <a href="<?=base_url('gst_return')?>" class="nav-link
                <?php 
                  if($this->uri->segment(1)=='gst_return')
                    echo ' active';
                ?>"
            >
              <i class="nav-icon fas fa-tree text-yellow"></i>
              <p>
                <?=$this->lang->line('header_gst_return')?>
              </p>
            </a>
          </li> -->
          <?php 
            }
          ?>
          
          <?php 
            if(     
                  $this->permission_model->has_module_permission('user') || 
                  $this->permission_model->has_module_permission('customer') || 
                  $this->permission_model->has_module_permission('supplier') || 
                  $this->permission_model->has_module_permission('user_role')
              )
            {
          ?>
          <li class="nav-item has-treeview
            <?php 
              if($this->uri->segment(1)=='auth' || $this->uri->segment(1)=='customer' || $this->uri->segment(1)=='supplier')
                echo ' menu-open';
            ?>
          ">
            <a href="#" class="nav-link
              <?php 
                if(
                    ($this->uri->segment(1)=='auth' &&  $this->uri->segment(2)=='users') || 
                    ($this->uri->segment(1)=='auth' && $this->uri->segment(2)=='user_roles') || 
                    $this->uri->segment(1)=='customer' ||
                    $this->uri->segment(1)=='supplier' 
                  )
                echo ' active';
              ?>
            ">
              <i class="nav-icon fas fa-users text-green"></i>
              <p>
                <?php echo ucfirst($this->lang->line('header_people'));?>
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <?php 
                if($this->permission_model->has_module_permission('user'))
                {
              ?>
              <li class="nav-item">
                <a href="<?=base_url('auth/users')?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='auth' && $this->uri->segment(2)=='users')
                      echo ' active';
                  ?>
                  ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                  Users
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
              <?php 
                if($this->permission_model->has_module_permission('customer'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('customer');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='customer')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                Customers
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
              
              
                            <?php 
                if($this->permission_model->has_module_permission('customer'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('lead');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='lead')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('header_lead')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>


              <?php 
                if($this->permission_model->has_module_permission('supplier'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('supplier');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='supplier')
                      echo ' active';
                  ?>"
                >
                  <!--  <i class="nav-icon fas fa-book"></i> -->
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('supplier_header')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
              
            </ul>
          </li>
          <?php 
            }
          ?>
         <?php
            if(
                $this->permission_model->has_module_permission('product_category') ||
                $this->permission_model->has_module_permission('product') ||
                $this->permission_model->has_module_permission('scrap_product') ||
                $this->permission_model->has_module_permission('stock') ||
                $this->permission_model->has_module_permission('warehouse') ||
                $this->permission_model->has_module_permission('promotion')
              )
            {
          ?>
          <li class="nav-item has-treeview
            <?php 
              if($this->uri->segment(1)=='product_category' || $this->uri->segment(1)=='product' || $this->uri->segment(1)=='product_core' || $this->uri->segment(1)=='stock' || $this->uri->segment(1)=='product_variant' || $this->uri->segment(1)=='barcode' || $this->uri->segment(1)=='warehouse' || $this->uri->segment(1)=='scrap_product' || $this->uri->segment(1)=='promotion')
                echo ' menu-open';
            ?>
            ">
            <a href="#" class="nav-link
              <?php 
                if($this->uri->segment(1)=='product_category' || $this->uri->segment(1)=='product' || $this->uri->segment(1)=='product_core' || $this->uri->segment(1)=='stock' || $this->uri->segment(1)=='product_variant' || $this->uri->segment(1)=='barcode' || $this->uri->segment(1)=='warehouse' || $this->uri->segment(1)=='scrap_product' || $this->uri->segment(1)=='promotion')
                  echo ' active';
              ?>
            ">
              <i class="nav-icon fas fa-boxes text-purple"></i>
              <p>
                <?php echo $this->lang->line('header_inventory');?>

                <i class="fas fa-angle-left right"></i>
              </p>
            </a>            
            <ul class="nav nav-treeview">

              <?php 
                  if($this->permission_model->has_module_permission('promotion'))
                  {
                ?>
                <li class="nav-item <?= (empty($promotion) || $promotion->field_status !== 'active') ? 'd-none' : '' ?>">
                  <a href="<?php echo base_url('promotion');?>" class="nav-link
                    <?php 
                      if($this->uri->segment(1)=='promotion')
                        echo ' active';
                    ?>
                  ">
                    <i class="nav-icon"></i>
                    <p class="text-danger ml-4">
                      <?=$this->lang->line('header_promotion')?>
                    </p>
                  </a>
                </li>
                <?php 
                  }
                ?>

              <?php 
                if($this->permission_model->has_module_permission('warehouse'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('warehouse');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='warehouse')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    Branch 
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
             
              <?php 
                if($this->permission_model->has_module_permission('product_category'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('product_category');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='product_category')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                     <?=$this->lang->line('header_product_category')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_module_permission('product'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('product_core');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='product_core')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                     <?=$this->lang->line('header_add_product')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_module_permission('product'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('product');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='product')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                     <?=$this->lang->line('header_all_product')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

             <?php 
// Check if user has permission for scrap_product module
if ($this->permission_model->has_module_permission('scrap_product')) {
    /*
    <li class="nav-item">
        <a href="<?php echo base_url('scrap_product');?>" 
           class="nav-link <?php if($this->uri->segment(1) == 'scrap_product') echo ' active'; ?>">
            <i class="nav-icon"></i>
            <p class="text-danger ml-4">
                <?=$this->lang->line('header_scrap_product')?>
            </p>
        </a>
    </li>
    */
}
?>


              <?php 
                if($this->permission_model->has_module_permission('stock'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('stock');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='stock')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                     <?=$this->lang->line('header_stock')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

            </ul>
          </li>
          <?php
            } 
          ?>

<!--for masters-->

<!--end masters-->


        <?php 
            if($this->permission_model->has_module_permission('reports'))
            {
          ?>
          <li class="nav-item has-treeview
            <?php 
              if($this->uri->segment(1)=='report')
                echo ' menu-open';
            ?>
          ">
            <a href="#" class="nav-link
              <?php 
              if($this->uri->segment(1)=='report')
                echo ' active';
              ?>
             ">
            
              <i class="nav-icon fas fa-chart-area text-pink"></i>
              <p>
                <?php echo ucfirst($this->lang->line('header_reports'));?>

                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

            <?php 
                if($this->permission_model->has_permission('customer_payment_due_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/customer_payment_due');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='customer_payment_due' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_customer_payment_due')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('supplier_payment_due_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/supplier_payment_due');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='supplier_payment_due' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_supplier_payment_due')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php /*
                if($this->permission_model->has_permission('stock_value_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/stock_value');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='stock_value' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_stock_value')?>
                  </p>
                </a>
              </li>
              <?php 
                } */
              ?>

              <?php 
                if($this->permission_model->has_permission('closing_stock_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/closing_stock');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='closing_stock' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <!--<?=$this->lang->line('header_closing_stock')?>-->
                    Stock Management Summary
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('inventory_product_added_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/inventory_product_added');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='inventory_product_added' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_inventory_product_added')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php /*
                if($this->permission_model->has_permission('receivable_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/receivable');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='receivable' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_receivable')?>
                  </p>
                </a>
              </li>
              <?php 
                } */
              ?>

              <?php /*
                if($this->permission_model->has_permission('payable_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/payable');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='payable' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">

                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_payable')?>
                  </p>
                </a>
              </li>
              <?php 
                }*/
              ?>

              <?php 
                if($this->permission_model->has_permission('gstr1_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/gstr1');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='gstr1' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_gstr1')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('gstr2_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/gstr2');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='gstr2' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_gstr2')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('hsn_sale'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/hsn_sale');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='hsn_sale' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_hsn_sale')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('hsn_purchase'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/hsn_purchase');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='hsn_purchase' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_hsn_purchase')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php /*
                if($this->permission_model->has_permission('product_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/stock');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='stock' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_stock_report')?>
                  </p>
                </a>
              </li>
              <?php 
                }*/
              ?>

              <?php 
                if($this->permission_model->has_permission('product_detail_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/product');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='product' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_product_report')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('product_sale'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/product_sale');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='product_sale' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                    <?=$this->lang->line('header_product_sale_report')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('product_purchase'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/product_purchase');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='product_purchase' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_product_purchase_report')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('sale_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/sale');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='sale' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                 <!--  <i class="nav-icon fas fa-book"></i> -->
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <!--<?=$this->lang->line('header_sales')?>-->
                       Sale Register Report
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('sales_return_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/sales_return');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='sales_return' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                 <!--  <i class="nav-icon fas fa-book"></i> -->
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_sales_return')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('purchase_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/purchase');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='purchase' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                 <!--  <i class="nav-icon fas fa-book"></i> -->
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <!--<p>-->
                  <!-- <?=$this->lang->line('header_purchase')?>-->
                  <!--</p>-->
                  <p>Purchase Register Report</p>
                </a>
              </li>
              <?php 
                }
              ?>  

               <?php 
                if($this->permission_model->has_permission('purchase_return_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/purchase_return');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='purchase_return' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                 <!--  <i class="nav-icon fas fa-book"></i> -->
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <!--<?=$this->lang->line('header_purchase_return')?>-->
                   Purchase Return Report
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>  

              <?php 
                if($this->permission_model->has_permission('expense_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/expense');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='expense' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                 <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_expense')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('ledger_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/ledger');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='ledger' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_ledger')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                // if($this->permission_model->has_permission('transaction_report'))
                // {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/transaction');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='transaction' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_transaction')?>
                  </p>
                </a>
              </li>
              <?php 
                // }
              ?>

              <?php 
                if($this->permission_model->has_permission('profit_and_loss_report'))
                {
              ?>
              <li class="nav-item d-none">
                <a href="<?php echo base_url('report/profit_and_loss');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='profit_and_loss' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <!-- <i class="fas fa-hand-holding-usd"></i> -->
                  <i class="nav-icon far fa-dot-circle text-danger"></i>
                  <p>
                   <?=$this->lang->line('header_profit_and_loss')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>


              <!-- <li class="nav-item">
                <a href="<?php echo base_url('report/balance_sheet');?>" class="nav-link
                <?php 
                    if($this->uri->segment(2)=='balance_sheet' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon fas fa-balance-scale text-pink"></i>
                  <p>
                   <?=$this->lang->line('header_balance_sheet')?>
                  </p>
                </a>
              </li> -->

              <?php 
                if($this->permission_model->has_permission('balance_sheet_report'))
                {
              ?>
              <!-- <li class="nav-item">
                <a href="<?php echo base_url('report/balance_sheet');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='balance_sheet' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon fas fa-balance-scale text-pink"></i>
                  <p>
                   <?=$this->lang->line('header_balance_sheet')?>
                  </p>
                </a>
              </li> -->
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('daily_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/daily');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='daily' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon far fa-dot-circle text-yellow"></i>
                  <p>
                   <?=$this->lang->line('header_daily')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
              
            </ul>
          </li>
          <?php 
            }
          ?>

         

          <?php 
            if($this->permission_model->has_module_permission('hr_module'))
            {
          ?>
          <li class="nav-item has-treeview">
            <a href="<?=base_url('employee')?>" class="nav-link
                <?php 
                  if(
                      $this->uri->segment(1)=='employee' ||
                      $this->uri->segment(1)=='department' ||
                      $this->uri->segment(1)=='position' ||
                      $this->uri->segment(1)=='weekend' ||
                      $this->uri->segment(1)=='holiday' ||
                      $this->uri->segment(1)=='attendance'||
                      $this->uri->segment(1)=='leave'||
                      $this->uri->segment(1)=='advance_salary'||
                      $this->uri->segment(1)=='bonus'||
                      $this->uri->segment(1)=='deduction'||
                      $this->uri->segment(1)=='tax_deduction'||
                      $this->uri->segment(1)=='payroll'
                    )
                    echo ' active';
                ?>"
            >
              <i class="nav-icon fas fa-university text-red"></i>
              <p>
                <?=$this->lang->line('header_hrmodule')?>
              </p>
            </a>
          </li>
          <?php 
            }
          ?> 

 <?php
            if(
                $this->permission_model->has_module_permission('cash_bank_entry') ||
                $this->permission_model->has_module_permission('pdc_receive') ||
                $this->permission_model->has_module_permission('pdc_payment') ||
                $this->permission_model->has_module_permission('scrap_issue') ||
                $this->permission_model->has_module_permission('scrap_receive') ||
                $this->permission_model->has_module_permission('scrap_entry') ||
                $this->permission_model->has_module_permission('bank_statement')  ||
                $this->permission_model->has_module_permission('expenses') 
              )
            {
          ?>
         <?php 
/*
<li class="nav-item has-treeview
    <?php 
      if($this->uri->segment(1)=='cash_bank_entry' || $this->uri->segment(1)=='bank_statement' || $this->uri->segment(1)=='pdc_receive' || $this->uri->segment(1)=='pdc_payment' || $this->uri->segment(1)=='scrap_issue' || $this->uri->segment(1)=='scrap_receive' || $this->uri->segment(1)=='scrap_entry' || $this->uri->segment(1)=='expense' )
        echo ' menu-open';
    ?>
    ">
    <a href="#" class="nav-link
      <?php 
        if($this->uri->segment(1)=='cash_bank_entry' || $this->uri->segment(1)=='bank_statement' || $this->uri->segment(1)=='pdc_receive' || $this->uri->segment(1)=='pdc_payment' || $this->uri->segment(1)=='scrap_issue' || $this->uri->segment(1)=='scrap_receive' || $this->uri->segment(1)=='scrap_entry' || $this->uri->segment(1)=='expense' )
          echo ' active';
      ?>
    ">
      <i class="nav-icon fas fa-file-invoice-dollar text-pink"></i>
      <p>
        <?php echo $this->lang->line('header_account');?>
        <i class="fas fa-angle-left right"></i>
      </p>
    </a>            
    <ul class="nav nav-treeview">

      <?php 
        if($this->permission_model->has_module_permission('cash_bank_entry'))
        {
      ?>
      <li class="nav-item">
        <a href="<?php echo base_url('cash_bank_entry');?>" class="nav-link
          <?php 
            if($this->uri->segment(1)=='cash_bank_entry')
              echo ' active';
          ?>
        ">
          <i class="nav-icon"></i>
          <p class="text-danger ml-4">
             <?=$this->lang->line('header_cash_bank_entry')?>
          </p>
        </a>
      </li>
      <?php 
        }
      ?>

      <?php 
        if($this->permission_model->has_module_permission('bank_statement'))
        {
      ?>
      <li class="nav-item">
        <a href="<?php echo base_url('bank_statement');?>" class="nav-link
          <?php 
            if($this->uri->segment(1)=='bank_statement')
              echo ' active';
          ?>
        ">
          <i class="nav-icon"></i>
          <p class="text-danger ml-4">
             <?=$this->lang->line('header_bank_statement')?>
          </p>
        </a>
      </li>
      <?php 
        }
      ?>

      <?php 
        if($this->permission_model->has_module_permission('pdc_receive'))
        {
      ?>
      <li class="nav-item">
        <a href="<?php echo base_url('pdc_receive');?>" class="nav-link
          <?php 
            if($this->uri->segment(1)=='pdc_receive')
              echo ' active';
          ?>
        ">
          <i class="nav-icon"></i>
          <p class="text-danger ml-4">
             <?=$this->lang->line('header_pdc_receive')?>
          </p>
        </a>
      </li>
      <?php 
        }
      ?>

      <?php 
        if($this->permission_model->has_module_permission('pdc_payment'))
        {
      ?>
      <li class="nav-item">
        <a href="<?php echo base_url('pdc_payment');?>" class="nav-link
          <?php 
            if($this->uri->segment(1)=='pdc_payment')
              echo ' active';
          ?>
        ">
          <i class="nav-icon"></i>
          <p class="text-danger ml-4">
             <?=$this->lang->line('header_pdc_payment')?>
          </p>
        </a>
      </li>
      <?php 
        }
      ?>

      <?php 
        if($this->permission_model->has_module_permission('scrap_entry'))
        {
      ?>
      <li class="nav-item">
        <a href="<?php echo base_url('scrap_entry');?>" class="nav-link
          <?php 
            if($this->uri->segment(1)=='scrap_entry')
              echo ' active';
          ?>
        ">
          <i class="nav-icon"></i>
          <p class="text-danger ml-4">
             <?=$this->lang->line('header_scrap_entry')?>
          </p>
        </a>
      </li>
      <?php 
        }
      ?>

      <?php 
        if($this->permission_model->has_module_permission('scrap_issue'))
        {
      ?>
      <li class="nav-item">
        <a href="<?php echo base_url('scrap_issue');?>" class="nav-link
          <?php 
            if($this->uri->segment(1)=='scrap_issue')
              echo ' active';
          ?>
        ">
          <i class="nav-icon"></i>
          <p class="text-danger ml-4">
             <?=$this->lang->line('header_scrap_issue')?>
          </p>
        </a>
      </li>
      <?php 
        }
      ?>

      <?php 
        if($this->permission_model->has_module_permission('scrap_receive'))
        {
      ?>
      <li class="nav-item">
        <a href="<?php echo base_url('scrap_receive');?>" class="nav-link
          <?php 
            if($this->uri->segment(1)=='scrap_receive')
              echo ' active';
          ?>
        ">
          <i class="nav-icon"></i>
          <p class="text-danger ml-4">
             <?=$this->lang->line('header_scrap_receive')?>
          </p>
        </a>
      </li>
      <?php 
        }
      ?>

      <?php 
        if($this->permission_model->has_module_permission('expense'))
        {
      ?>
      <li class="nav-item has-treeview
        <?php 
          if($this->uri->segment(1)=='expense')
            echo ' menu-open';
        ?>
      ">
        <a href="<?php echo base_url('expense');?>" class="nav-link
          <?php 
            if($this->uri->segment(1)=='expense')
              echo ' active';
          ?>
        ">
          <i class="nav-icon"></i>
          <p class="text-danger ml-4">
            <?=$this->lang->line('header_expenses')?>
          </p>
        </a>
      </li>
      <?php 
        }
      ?>

    </ul>
</li>
*/
?>

          <?php
            } 
          ?>



          <?php
            if(
                               
                $this->permission_model->has_module_permission('transfer')
               
              )
            {
          ?>
          <li class="nav-item has-treeview
            <?php 
              if($this->uri->segment(1)=='transfer')
                echo ' menu-open';
            ?>
            ">
            <a href="#" class="nav-link
              <?php 
                if($this->uri->segment(1)=='transfer')
                  echo ' active';
              ?>
            ">
              
              <i class="nav-icon fas fa-random text-dark"></i>
              <p>
              Stock Transfer
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>            
            <ul class="nav nav-treeview">
              <?php 
                if($this->permission_model->has_module_permission('transfer'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('transfer');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='transfer')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                     <?=$this->lang->line('header_transfer')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

             
            </ul>
          </li>
          <?php
            } 
          ?>
         
        <?php 
                if($this->permission_model->has_module_permission('user_role'))
                {
              ?>
              <li class="nav-item">
                <a href="<?=base_url('auth/user_roles')?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='auth' && $this->uri->segment(2)=='user_roles') 
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon fas fa-user-cog text-purple"></i>
                  <p>
                    <?=$this->lang->line('header_user_roles')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
       
          
          <?php 
            if($this->permission_model->has_module_permission('log'))
            {
          ?>
          <li class="nav-item">
              <a href="<?=base_url('log')?>" class="nav-link">
                <i class="nav-icon fas fa-history text-info"></i>
                <p>Log History</p>
              </a>
            </li>
          <?php 
            }
          ?>
          
          <!--expenses module-->
          
            <?php 
            if( 
                  $this->permission_model->has_module_permission('expenses')
              )
            {
          ?>
          <li class="nav-item has-treeview
          ">
            <a href="#" class="nav-link
            ">
             <i class="nav-icon fas fa-file-invoice-dollar text-green"></i>
             <p>Expenses</p>

                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            
            <ul class="nav nav-treeview">
              
              <?php 
                if($this->permission_model->has_module_permission('expenses'))
                {
              ?>
              <li class="nav-item">
                <a href="<?=base_url('expense_category')?>" class="nav-link
                  ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                  Expenses Category
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
              <?php 
                if($this->permission_model->has_module_permission('expenses'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('expense/add');?>" class="nav-link
                 
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
               Add Expenses
                  </p>
                </a>
              </li>
              
               <li class="nav-item">
                <a href="<?=base_url('expense')?>" class="nav-link
                  ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
               View Expenses
                  </p>
                </a>
              </li>
              
              <?php 
                }
              ?>

              
            </ul>
          </li>
          <?php 
            }
          ?>
            <!--expense module-->
          
              
          <?php 
            if($this->permission_model->has_module_permission('company_setting') || $this->permission_model->has_module_permission('custom_field') || $this->permission_model->has_module_permission('email_template') || $this->permission_model->has_module_permission('whatsapp_template') ||$this->permission_model->has_module_permission('whatsapp_message') || $this->permission_model->has_module_permission('application_setting') || $this->permission_model->has_module_permission('discount') || $this->permission_model->has_module_permission('email_setup') || $this->permission_model->has_module_permission('sms_setup') || $this->permission_model->has_module_permission('currency') || $this->permission_model->has_module_permission('expense_category') || $this->permission_model->has_module_permission('tax') || $this->permission_model->has_module_permission('log') || $this->permission_model->has_module_permission('uom') || $this->permission_model->has_module_permission('item') || $this->permission_model->has_module_permission('due_days'))
            {
          ?>
          <li class="nav-item has-treeview
            <?php 
              if($this->uri->segment(1)=='discount' || $this->uri->segment(1)=='email_template' || $this->uri->segment(1)=='whatsapp_template' || $this->uri->segment(1)=='whatsapp_message' || $this->uri->segment(1)=='application_settings' || $this->uri->segment(1)=='custom_field' || $this->uri->segment(1)=='settings' || $this->uri->segment(1)=='tax' || $this->uri->segment(1)=='email_setup' || $this->uri->segment(1)=='sms_setup' || $this->uri->segment(1)=='expense_category' || $this->uri->segment(1)=='currency' || $this->uri->segment(1)=='uom' || $this->uri->segment(1)=='log_data' || $this->uri->segment(1)=='item')
                echo ' menu-open';
            ?>
          ">
            <a href="#" class="nav-link 
              <?php 
                if($this->uri->segment(1)=='discount' || $this->uri->segment(1)=='email_template' || $this->uri->segment(1)=='whatsapp_template'|| $this->uri->segment(1)=='whatsapp_message' || $this->uri->segment(1)=='application_settings' || $this->uri->segment(1)=='custom_field' || $this->uri->segment(1)=='settings' || $this->uri->segment(1)=='tax' || $this->uri->segment(1)=='email_setup' || $this->uri->segment(1)=='sms_setup' || $this->uri->segment(1)=='expense_category' || $this->uri->segment(1)=='currency' || $this->uri->segment(1)=='uom' || $this->uri->segment(1)=='log_data' || $this->uri->segment(1)=='item')
                  echo ' active';
              ?>
            ">
              <!-- <i class="nav-icon fas fa-store"></i> -->
              <i class="nav-icon fas fa-user-cog text-danger"></i>
              <p>
                <?php echo ucfirst($this->lang->line('header_setting'));?>
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                
                
                
        <?php 
            if($this->permission_model->has_module_permission('due_days'))
            {
          ?>
          <li class="nav-item">
            <a href="<?=base_url('due_days')?>" class="nav-link
                <?php 
                  if($this->uri->segment(1)=='due_days')
                    echo ' active';
                ?>"
            >
              <i class="nav-icon"></i>
              <p class="text-danger ml-4">
              Due days
              </p>
            </a>
          </li>
          <?php 
            }
          ?>
          

                
                
                
                
                 
              <?php 
                if($this->permission_model->has_module_permission('company_setting'))
                {
              ?>
              
              <li class="nav-item">
                <a href="<?php echo base_url('settings');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='settings')
                      echo ' active';
                  ?>
                ">
                  <!-- <i class="nav-icon fas fa-book"></i> -->
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <!-- Company Settings -->
                    <?=$this->lang->line('header_company_setting')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_module_permission('custom_field'))
                {
              ?>
              
              <li class="nav-item">
                <a href="<?php echo base_url('custom_field');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='custom_field')
                      echo ' active';
                  ?>
                ">
                  <!-- <i class="nav-icon fas fa-book"></i> -->
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <!-- Company Settings -->
                    <?=$this->lang->line('header_custom_field')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_module_permission('application_setting'))
                {
              ?>
             <!--  <li class="nav-item">
                <a href="<?php echo base_url('application_settings');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='application_settings')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon fas fa-sun text-yellow"></i>
                  <p>
                    <?=$this->lang->line('header_application_settings')?>
                  </p>
                </a>
              </li> -->
              <?php 
                }
              ?>

              
              <?php 
                if($this->permission_model->has_module_permission('email_template'))
                {
              ?>
              
              <li class="nav-item">
                <a href="<?php echo base_url('email_template');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='email_template')
                      echo ' active';
                  ?>
                ">
                  <!-- <i class="nav-icon fas fa-book"></i> -->
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <!-- Company Settings -->
                    <?=$this->lang->line('header_email_template')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_module_permission('whatsapp_template') || $this->permission_model->has_module_permission('whatsapp_message'))
                {
              ?>
              
              <li class="nav-item">
                <a href="<?php echo base_url('whatsapp_template');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='whatsapp_template' || $this->uri->segment(1)=='whatsapp_message')
                      echo ' active';
                  ?>
                ">
                  <!-- <i class="nav-icon fas fa-book"></i> -->
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <!-- Company Settings -->
                    <?=$this->lang->line('header_whatsapp_template')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
              

              <?php 
                if($this->permission_model->has_module_permission('item'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('item');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='item')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('header_item')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_module_permission('tax'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('tax');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='tax')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('header_tax')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_module_permission('discount'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('discount');?>" class="nav-link 
                  <?php 
                    if($this->uri->segment(1)=='discount')
                      echo ' active';
                  ?>
                ">
                  <!-- <i class="nav-icon fas fa-book"></i> -->
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('header_discount')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_module_permission('currency'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('currency');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='currency')
                      echo ' active';
                  ?>
                ">
                  <!-- <i class="nav-icon fas fa-money-check-alt"></i> -->
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('header_currency')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_module_permission('uom'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('uom');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='uom')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                     <?=$this->lang->line('header_uom')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>


              <?php 
                if($this->permission_model->has_module_permission('email_configuration'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('email_setup');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='email_setup')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon"></i>
                  <p class="text-danger ml-4">
                    <?=$this->lang->line('header_email_configuration')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
              <!-- <li class="nav-item">
                <a href="<?php echo base_url('sms_setup');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='sms_setup')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon fas fa-comment-alt text-red"></i>
                  <p>
                   <?=$this->lang->line('header_sms_configuration')?>
                  </p>
                </a>
              </li> -->
              
             
              
           

              
            </ul>
            
          </li>
          <?php 
            }
          ?>
          <!-- Bank Account - Separate Menu Item -->
<?php 
if($this->permission_model->has_module_permission('bank_account'))
{
?>
<li class="nav-item">
    <a href="<?=base_url('bank_account')?>" class="nav-link
        <?php 
          if($this->uri->segment(1)=='bank_account')
            echo ' active';
        ?>
    ">
        <i class="nav-icon fas fa-university text-primary"></i>
        <p>
            <?=$this->lang->line('header_bank_account')?>
        </p>
    </a>
</li>
<?php 
}
?>
  <?php 
$user_id = $this->session->userdata('user_id');
$user_info = $this->db->select('role, clients_branch_id, company_name')
                      ->where('id', $user_id)
                      ->get('users')
                      ->row();

if ($user_info) {
    if ($user_info->role == 'super_admin') {
?>
    <li class="nav-item">
        <a href="<?php echo base_url('support_client'); ?>" class="nav-link <?php echo ($this->uri->segment(1) == 'log_data') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-history text-pink"></i>
            <p>Support Messages</p>
        </a>
    </li>
<?php 
    } // end role check
} // end user_info check
?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function showMoreUsers(users) {
    // Wrap all badges in a flex container
    let html = '<div style="display:flex; flex-wrap:wrap; gap:5px;">';
    users.forEach(function(user) {
        html += `<div style="padding:5px 10px; background:#17a2b8; color:white; border-radius:4px; font-size:0.87rem; white-space:nowrap;">${user}</div>`;
    });
    html += '</div>';

    Swal.fire({
        title: 'Next Level Users',
        html: html,
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: 'Close',
        width: '800px'  // adjust width as needed
    });
}
</script>