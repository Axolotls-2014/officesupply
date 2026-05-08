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
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/all.css">
   <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/square/blue.css">
   <link rel="stylesheet" href="<?php echo base_url('assets/plugins/autocomplete/') ?>/jquery_auto_complete.css">


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

    /* Paste this css to your style sheet file or under head tag */
    /* This only works with JavaScript, 
    if it's not present, don't show loader */
    .no-js #loader { display: none;  }
    .js #loader { display: block; position: absolute; left: 100px; top: 0; }
    .se-pre-con {
      position: fixed;
      left: 0px;
      top: 0px;
      width: 100%;
      height: 100%;
      z-index: 9999999 !important;
      background: url(<?=base_url('assets/plugins/simple-pre-loader')?>/Preloader_7.gif) center no-repeat #fff;
    }

    .datepicker{
      z-index: 1000000 !important;
    }

    /* scollbar css */

    ::-webkit-scrollbar {
      width: 3px;
    }
     
    ::-webkit-scrollbar-track {
      background-color: white;
      border-radius: 2px;
    }
     
    ::-webkit-scrollbar-thumb {
      background-color: #20c997;
      border-radius: 2px;
    }

    .btn:focus{
      border-color: 1px solid #fff !important;
    }

    .financial-year-dropdown-menu{
      left: -126px !important;
    }

    /*change the select2 results background color*/
    .select2-results { background-color: #E0FFFA; }


  </style>
  
</head>

<body class="hold-transition sidebar-mini layout-fixed <?php if($application_setting->application_text_size == 0){echo ' text-sm ';} ?>">
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

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->

      <li class="nav-item pr-1">
        <div class="btn-group">
          <button type="button" class="btn btn-warning">Current Year : <?php

            if (date('m') > 4) {
                $year = date('Y')."-".(date('Y') +1);
            }
            else {
                $year = (date('Y')-1)."-".date('Y');
            }
            echo $year; // 2015-2016

          ?></button>

          
          <!-- <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown">
            <span class="sr-only">Toggle Dropdown</span>
            <div class="dropdown-menu financial-year-dropdown-menu" role="menu">
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <a class="dropdown-item" href="#">Something else here</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Separated link</a>
            </div>
          </button> -->
        </div>
      </li>
      <?php
        if($this->permission_model->has_permission('add_purchase'))
        {
      ?>
      <li class="nav-item pr-1">
        <a class="nav-link bg-white btn" data-toggle="dropdown" href="#" data-tt="tooltip" title="<?=$this->lang->line('purchase_header').' ('.$this->lang->line('purchase_shortkey'). ')'?>">
          <i class="fas fa-shopping-bag text-yellow"></i>
        </a>
      </li>
      <?php
        }
      ?>
      <?php
        if($this->permission_model->has_permission('add_quotation'))
        {
      ?>
      <li class="nav-item pr-1">
        <a class="nav-link bg-white btn" data-toggle="dropdown" href="#" data-tt="tooltip" title="<?=$this->lang->line('quotation_header').' ('.$this->lang->line('quotation_shortkey'). ')'?>">
          <i class="fas fa-file-invoice-dollar text-green"></i>
        </a>
      </li>
      <?php
        }
      ?>
      <?php
        if($this->permission_model->has_permission('add_sale'))
        {
      ?>
      <li class="nav-item pr-1">
        <a class="nav-link bg-white btn" data-toggle="dropdown" href="#" data-tt="tooltip" title="<?=$this->lang->line('sale_header').' ('.$this->lang->line('sale_shortkey'). ')'?>">
          <i class="fas fa-store text-red"></i>
        </a>
      </li>
      <?php
        } 
      ?>
      <?php
        if($this->permission_model->has_permission('show_ledger_balance'))
        {
      ?>
      <li class="nav-item dropdown">
        <a class="nav-link bg-lime" data-toggle="dropdown" href="#" style="border-radius: 5px;">
          <?php 
            $cash_ledger = $this->ledger_model->get_single_record(CASH_GROUP_LEDGER);
          ?>
          Cash on Hand : <?=$this->session->userdata('currency_symbol')?> <?=$cash_ledger->closing_balance?>
          <i class="fas fa-arrow-down mr-2"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header text-left"><?=$this->lang->line('ledger_account_balance')?></span>
          <!-- <div class="dropdown-divider"></div>
          <?php 
            $captial_ledger = $this->ledger_model->get_single_record(CAPITAL_LEDGER);
          ?>
          <a href="#" class="dropdown-item">
            <strong><?=ucwords(strtolower($captial_ledger->title))?></strong>
            <span class="float-right text-muted text-sm">
              <?=$this->session->userdata('currency_symbol')?> <?=$captial_ledger->closing_balance?>
            </span>
          </a> -->

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
          <!-- <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <?php 
                if($this->permission_model->has_permission('add_amount_to_capital_account') && $this->permission_model->has_permission('transfer_amount_from_capital_to_cash'))
                {
              ?>
              <td width="50%">
                <a href="#" data-toggle="modal" data-target="#capital_modal" class="dropdown-item dropdown-footer bg-purple" data-tt="tooltip" title="<?=$this->lang->line('ledger_capital_add_amount')?>">
                 
                  <i class="fa fa-plus"></i>
                </a>
              </td>
              <td>
                <a href="#" data-toggle="modal" data-target="#cash-to-capital_modal" class="dropdown-item dropdown-footer bg-warning" data-tt="tooltip" title="<?=$this->lang->line('ledger_transfer_amount_from_capital_to_cash')?>">
                  <i class="fa fa-exchange-alt"></i>
                  
                </a>
              </td>  
              <?php 
                }
                else if(!$this->permission_model->has_permission('add_amount_to_capital_account') || $this->permission_model->has_permission('transfer_amount_from_capital_to_cash'))
                {
              ?>
              <td>
                <a href="#" data-toggle="modal" data-target="#cash-to-capital_modal" class="dropdown-item dropdown-footer bg-warning" data-tt="tooltip" title="<?=$this->lang->line('ledger_transfer_amount_from_capital_to_cash')?>">
                  <i class="fa fa-exchange-alt"></i>
                  
                </a>
              </td>  
              <?php
                }
                else if($this->permission_model->has_permission('add_amount_to_capital_account') || !$this->permission_model->has_permission('transfer_amount_from_capital_to_cash'))
                {
              ?>
              <td>
                <a href="#" data-toggle="modal" data-target="#capital_modal" class="dropdown-item dropdown-footer bg-purple" data-tt="tooltip" title="<?=$this->lang->line('ledger_capital_add_amount')?>">
               
                  <i class="fa fa-plus"></i>
                </a>
              </td>
              <?php
                }
              ?>
            </tr>
          </table> -->
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
    <a href="<?=base_url('auth/dashboard')?>" class="brand-link">
      <img src="<?php echo base_url();?>assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><?=strtoupper($application_setting->app_name)?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url();?>assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?=ucwords($user->first_name.' '.$user->last_name)?></a>
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
            if($this->permission_model->has_module_permission('gst_return'))
            {
          ?>
          <li class="nav-item has-treeview">
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
          </li>
          <?php 
            }
          ?>

          <?php 
            if($this->permission_model->has_module_permission('bank_account'))
            {
          ?>
          <li class="nav-item has-treeview">
            <a href="<?=base_url('bank_account')?>" class="nav-link
                <?php 
                  if($this->uri->segment(1)=='bank_account')
                    echo ' active';
                ?>"
            >
              <i class="nav-icon fas fa-piggy-bank text-blue"></i>
              <p>
                <?=$this->lang->line('header_bank_account')?>
              </p>
            </a>
          </li>
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
                  <i class="nav-icon fas fa-users text-pink"></i>
                  <p>
                    <?=$this->lang->line('header_users')?>
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
                  <i class="nav-icon fas fa-user-friends text-yellow"></i>
                  <p>
                    <?=$this->lang->line('header_customers')?>
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
                  <i class="nav-icon fas fa-male text-yellow"></i>
                  <p>
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
                $this->permission_model->has_module_permission('product') ||
                $this->permission_model->has_module_permission('purchase') || 
                $this->permission_model->has_module_permission('purchase_return') || 
                $this->permission_model->has_module_permission('quotation') ||
                $this->permission_model->has_module_permission('sales') ||
                $this->permission_model->has_module_permission('sales_return') ||
                $this->permission_model->has_module_permission('product_category') ||
                $this->permission_model->has_module_permission('warehouse')
              )
            {
          ?>
          <li class="nav-item has-treeview
            <?php 
              if($this->uri->segment(1)=='warehouse' || $this->uri->segment(1)=='product_category' || $this->uri->segment(1)=='product' || $this->uri->segment(1)=='sale' ||  $this->uri->segment(1)=='sale_return' || $this->uri->segment(1)=='purchase' || $this->uri->segment(1)=='purchase_return' || $this->uri->segment(1)=='quotation')
                echo ' menu-open';
            ?>
            ">
            <a href="#" class="nav-link
              <?php 
                if($this->uri->segment(1)=='warehouse' || $this->uri->segment(1)=='product_category' || $this->uri->segment(1)=='product' || $this->uri->segment(1)=='sale' || $this->uri->segment(1)=='purchase' || $this->uri->segment(1)=='quotation')
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
                  <i class="nav-icon fas fa-warehouse text-red"></i>
                  <p>
                     <?=$this->lang->line('header_warehouse')?>
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
                  <i class="nav-icon fas fa-shapes text-blue"></i>
                  <p>
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
                <a href="<?php echo base_url('product');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='product')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon fas fa-dice-d20 text-green"></i>
                  <p>
                     <?=$this->lang->line('header_product')?>
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
                  <i class="nav-icon fas fa-shopping-bag text-yellow"></i>
                  <p>
                    <?=$this->lang->line('header_purchase')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>
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
                  <i class="nav-icon fas fa-shopping-bag text-yellow"></i>
                  <p>
                    <?=$this->lang->line('header_sales_return')?>
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
                  <i class="nav-icon fas fa-store text-red"></i>
                  <p>
                     <?=$this->lang->line('header_sales')?>
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
                  <i class="nav-icon fas fa-store text-red"></i>
                  <p>
                     <?=$this->lang->line('header_purchase_return')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

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
                  <i class="nav-icon fas fa-file-invoice-dollar text-green"></i>
                  <p>
                     <?=$this->lang->line('header_quotation')?>
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
             <!--  <i class="nav-icon fas fa-book"></i> -->
              <i class="nav-icon fab fa-codepen text-green"></i>
              <p>
               <?=$this->lang->line('header_expenses')?>
              </p>
            </a>
          </li>
          <?php 
            }
          ?>

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
                  <i class="nav-icon fas fa-book-open text-yellow"></i>
                  <p>
                   <?=$this->lang->line('header_sales')?>
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
                  <i class="nav-icon fas fa-shopping-bag text-gray"></i>
                  <p>
                   <?=$this->lang->line('header_purchase')?>
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
                 <i class="nav-icon fab fa-readme text-red"></i>
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
                  <i class="nav-icon fas fa-search-dollar text-orange"></i>
                  <p>
                   <?=$this->lang->line('header_ledger')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('profit_and_loss_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/profit_and_loss');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='profit_and_loss' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <!-- <i class="fas fa-hand-holding-usd"></i> -->
                  <i class="nav-icon fas fa-hand-holding-usd text-teal"></i>
                  <p>
                   <?=$this->lang->line('header_profit_and_loss')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('balance_sheet_report'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('report/balance_sheet');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(2)=='balance_sheet' && $this->uri->segment(1)=='report')
                      echo ' active';
                  ?>
                ">
                  <!-- <i class="fas fa-hand-holding-usd"></i> -->
                  <i class="nav-icon fas fa-balance-scale text-pink"></i>
                  <p>
                   <?=$this->lang->line('header_balance_sheet')?>
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
            if($this->permission_model->has_module_permission('company_setting') || $this->permission_model->has_module_permission('application_setting') || $this->permission_model->has_module_permission('discount') || $this->permission_model->has_module_permission('email_setup') || $this->permission_model->has_module_permission('sms_setup') || $this->permission_model->has_module_permission('currency') || $this->permission_model->has_module_permission('expense_category') || $this->permission_model->has_module_permission('tax'))
            {
          ?>
          <li class="nav-item has-treeview
            <?php 
              if($this->uri->segment(1)=='discount' || $this->uri->segment(1)=='application_settings' || $this->uri->segment(1)=='settings' || $this->uri->segment(1)=='tax' || $this->uri->segment(1)=='email_setup' || $this->uri->segment(1)=='sms_setup' || $this->uri->segment(1)=='expense_category' || $this->uri->segment(1)=='currency')
                echo ' menu-open';
            ?>
          ">
            <a href="#" class="nav-link 
              <?php 
                if($this->uri->segment(1)=='discount' || $this->uri->segment(1)=='application_settings' || $this->uri->segment(1)=='settings' || $this->uri->segment(1)=='tax' || $this->uri->segment(1)=='email_setup' || $this->uri->segment(1)=='sms_setup' || $this->uri->segment(1)=='expense_category' || $this->uri->segment(1)=='currency')
                  echo ' active';
              ?>
            ">
              <!-- <i class="nav-icon fas fa-store"></i> -->
              <i class="nav-icon fas fa-user-cog text-blue"></i>
              <p>
                <?php echo ucfirst($this->lang->line('header_setting'));?>
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

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
                  <i class="nav-icon fas fa-cogs text-pink"></i>
                  <p>
                    <!-- Company Settings -->
                    <?=$this->lang->line('header_company_setting')?>
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
              <li class="nav-item">
                <a href="<?php echo base_url('application_settings');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='application_settings')
                      echo ' active';
                  ?>
                ">
                  <!-- <i class="nav-icon fas fa-book"></i> -->
                  <i class="nav-icon fas fa-sun text-yellow"></i>
                  <p>
                    <?=$this->lang->line('header_application_settings')?>
                  </p>
                </a>
              </li>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_module_permission('expense_category'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('expense_category');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='expense_category')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon fas fa-receipt text-yellow"></i>
                  <p>
                    <?=$this->lang->line('header_expense_category')?>
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
                  <i class="nav-icon fas fa-file-invoice text-green"></i>
                  <p>
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
                  <i class="nav-icon fas fa-percent text-red"></i>
                  <p>
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
                  <i class="nav-icon fab fa-ethereum text-green"></i>
                  <p>
                    <?=$this->lang->line('header_currency')?>
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
                  <i class="nav-icon fas fa-life-ring text-purple"></i>
                  <p>
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
              
             
              <?php 
                if($this->permission_model->has_module_permission('log'))
                {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('log_data');?>" class="nav-link
                  <?php 
                    if($this->uri->segment(1)=='log_data')
                      echo ' active';
                  ?>
                ">
                  <i class="nav-icon fas fa-history text-pink"></i>
                  <p>
                    <?=$this->lang->line('header_history_logs')?>
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

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>