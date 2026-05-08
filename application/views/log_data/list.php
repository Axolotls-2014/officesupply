<?php $this->load->view('layout/header');?>
<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_history_logs')?></a></li>
          
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('history_list')?></h3>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <button type="button" class="btn btn-info btn-sm" title="<?=$this->lang->line('export_history_log')?>" data-toggle="modal" data-target="#export_history_log">
                      <i class="fa fa-history" aria-hidden="true"></i>
                      <?=$this->lang->line('export_history_log')?>
                    </button>
                    <!-- <a class="nav-link active" href="<?=base_url('currency/add')?>" data-tt="tooltip" title="Click here to Export History Log">
                      <i class="fa fa-history mr-2"></i><?=$this->lang->line('export_history_log')?>
                    </a> -->
                  </li>
                </ul>
              </div>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="15%">User Name</th>
                      <th width="15%">Page(s)</th>
                      <th width="5%">Action</th>
                      <th width="30%">Description</th>
                      <th width="35%">Data</th>
                      <th width="15%">Date/Time</th>
                      <th width="10%">IP Address</th>
                      <th width="10%">Reference</th>
                      <th width="10%">Browser</th>
                  </tr>
                </thead>
                
               <tbody>
                          <?php
                            foreach ($log as $value) {
                          ?>
                          <tr>
                            <td><?php echo $value->user_name;?></td>
                            <td><?php echo ucfirst(str_replace("_"," ",$value->module));?></td>
                            <td>
                              <?php 
                                if($value->user_action == 0)
                                  echo "View";
                                else if($value->user_action == 1)
                                  echo "Created";
                                else if($value->user_action == 2)
                                  echo "Edited";
                                else if($value->user_action == 3)
                                  echo "Deleted";
                                else if($value->user_action == 4)
                                  echo "Import";
                                else if($value->user_action == 5)
                                  echo "Export";
                                else if($value->user_action == 6)
                                  echo "Login";
                                else if($value->user_action == 7)
                                  echo "Logout";
                                else if($value->user_action == 8)
                                  echo "Change Password";
                                else if($value->user_action == 9)
                                  echo "Forget Password";
                                else if($value->user_action == 10)
                                  echo "Activation";
                                else if($value->user_action == 11)
                                  echo "Deactivation";
                                else if($value->user_action == 12)
                                  echo "Download";
                                else if($value->user_action == 13)
                                  echo "Upload";
                                else if($value->user_action == 14)
                                  echo "Change Invoice Status";
                                else if($value->user_action == 15)
                                  echo "Move Attachment";
                              ?>
                            </td>
                            <td><?php echo $value->description;?></td>
                            <td style="text-align: left">
                              
                              <?php

                                $before = '';
                                $after  = '';

                               
                                if($value->module == "service" || $value->module == "expense" || $value->module == "currency" || $value->module == "tax" || $value->module == "discount" || $value->module == "customer" || $value->module == "email_setup" || $value->module == "expense_category" || $value->module == "company_setting" || $value->module == "sms_setup" || $value->module == "supplier" || $value->module == "application_setting")
                                {
                                  if($value->data != ""  || $value->data != null)
                                  {
                                    $after = json_decode($value->data,true);
                                  }


                                  if($value->b_data != ""  || $value->b_data != null)
                                  {
                                    $before = json_decode($value->b_data,true);
                                  } 
                                }

                              ?>

                              <?php 
                                if($after != '' || $after != null || $before != '' || $before != null)
                                {
                              ?>
                              <table width="100%" >
                                <tr>
                                  <th width="50%" style="text-align: center;border-bottom: 1px solid #ccc;">
                                    Before 
                                  </th>
                                  <th width="50%" style="text-align: center;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                                    After
                                  </th>
                                </tr>
                                
                                <tr>
                                  <td>
                                    <table width="100%" style="padding-left: 5px;">
                                    <?php
                                      if($before != '' || $before != null){
 
                                        if (array_key_exists('id',$before))
                                        {
                                          unset($before['id']);
                                        }

                                        if (array_key_exists('delete_status',$before))
                                        {
                                          unset($before['delete_status']);
                                        }    

                                        if (array_key_exists('delete_date',$before))
                                        {
                                          unset($before['delete_date']);
                                        }   

                                        if (array_key_exists('user_id',$before))
                                        {
                                          unset($before['user_id']);
                                        }  

                                        if (array_key_exists('tax_id',$before))
                                        {
                                          unset($before['tax_id']);
                                        }  

                                        if (array_key_exists('account_id',$before))
                                        {
                                          unset($before['account_id']);
                                        }  


                                        foreach ($before as $key1 => $value1) {
                                    ?>
                                        <tr style="border-bottom: 1px solid #DCDCDC">
                                          <td style="padding-left: 5px">
                                            <?=ucfirst(str_replace("_", " ", $key1))?>
                                          </td>
                                          <td>
                                            <?php 
                                              if($key1 == 'tax')
                                              {
                                                if($value1 != null && $value1 != ""){
                                                  echo $this->log_data_model->get_any_record('tax','tax_id',$value1)->tax_name;  
                                                }
                                              }
                                              else if($key1 == 'country_id')
                                              {
                                                if($value1 != null && $value1 != ""){
                                                  echo $this->log_data_model->get_any_record('countries','id',$value1)->name;
                                                }
                                              }
                                              else if($key1 == 'state_id')
                                              {
                                                if($value1 != null && $value1 != ""){
                                                  echo $this->log_data_model->get_any_record('states','id',$value1)->name;
                                                }
                                              }
                                              else if($key1 == 'status')
                                              {
                                                if($value1 != null && $value1 != ""){
                                                  if($value1 == 1)
                                                    echo 'Active';
                                                  else 
                                                    echo 'Inactive';
                                                }
                                              }
                                              else if($key1 == 'can_edit')
                                              {
                                                if($value1 != null && $value1 != ""){
                                                  if($value1 == 1)
                                                    echo 'Yes';
                                                  else 
                                                    echo 'No';
                                                }
                                              }
                                              else if($key1 == 'expense_category_id')
                                              {
                                                if($value1 != null && $value1 != ""){
                                                  echo $this->log_data_model->get_any_record('expense_category','id',$value1)->name; 
                                                }
                                              }
                                              else if($key1 == 'ledger_id')
                                              {
                                                if($value1 != null && $value1 != ""){
                                                  echo $this->log_data_model->get_any_record('ledger','id',$value1)->title; 
                                                }
                                              }
                                              else if($key1 == 'users_can_approve')
                                              {

                                                  $users_can_approve_name_arr = array();
                                                  if($value1 != '')
                                                  {
                                                    $users_can_approve_arr = explode(",", $value1);

                                                    for ($i=0; $i <sizeof($users_can_approve_arr) ; $i++) 
                                                    { 
                                                      if($users_can_approve_arr[$i] != '' || $users_can_approve_arr[$i] != null)
                                                      {
                                                        $users_can_approve_name_arr[] = $this->log_data_model->get_any_record('users','id',$users_can_approve_arr[$i])->first_name." ".$this->log_data_model->get_any_record('users','id',$users_can_approve_arr[$i])->last_name;   
                                                      }
                                                    }

                                                    echo implode(", ", $users_can_approve_name_arr);
                                                  }

                                                  // echo $this->log_data_model->get_any_record('users','id',$value1)->first_name; 
                                                
                                              }
                                            
                                              else if($key1 == 'attached_files')
                                              {

                                                $attached_files_name_arr = array();
                                                if($value1 != '')
                                                {
                                                  $attached_files_arr = explode(",", $value1);

                                                  for ($i=0; $i <sizeof($attached_files_arr) ; $i++) 
                                                  { 
                                                    if($attached_files_arr[$i] != '' || $attached_files_arr[$i] != null)
                                                    {
                                                      $attached_files_name_arr[] = $this->log_data_model->get_any_record('attachment','id',$attached_files_arr[$i])->filename;   
                                                    }
                                                  }

                                                  echo implode(", ", $attached_files_name_arr);
                                                }
                                              }
                                              else if($key1 == 'service_id')
                                              {

                                                $service_id_name_arr = array();
                                                if($value1 != '')
                                                {
                                                  $service_id_arr = explode(",", $value1);

                                                  for ($i=0; $i <sizeof($service_id_arr) ; $i++) 
                                                  { 
                                                    if($service_id_arr[$i] != '' || $service_id_arr[$i] != null)
                                                    {
                                                      $service_id_name_arr[] = $this->log_data_model->get_any_record('service','id',$service_id_arr[$i])->title;   
                                                    }
                                                  }

                                                  echo implode(", ", $service_id_name_arr);
                                                }
                                              }
                                              else if($key1 == 'expense_category')
                                              {

                                                $expense_category_name_arr = array();
                                                if($value1 != '')
                                                {
                                                  $expense_category_arr = explode(",", $value1);

                                                  for ($i=0; $i <sizeof($expense_category_arr) ; $i++) 
                                                  { 
                                                    if($expense_category_arr[$i] != '' || $expense_category_arr[$i] != null)
                                                    {
                                                      $expense_category_name_arr[] = $this->log_data_model->get_any_record('expense_category','id',$expense_category_arr[$i])->name;   
                                                    }
                                                  }

                                                  echo implode(", ", $expense_category_name_arr);
                                                }
                                              }
                                              else if($key1 == 'users_access')
                                              {
                                                $users_access_name_arr = array();
                                                if($value1 != '')
                                                {
                                                  $users_access_arr = explode(",", $value1);

                                                  for ($i=0; $i <sizeof($users_access_arr) ; $i++) 
                                                  { 
                                                    if($users_access_arr[$i] != '' || $users_access_arr[$i] != null)
                                                    {
                                                      $users_access_name_arr[] = $this->log_data_model->get_any_record('users','id',$users_access_arr[$i])->first_name." ".$this->log_data_model->get_any_record('users','id',$users_access_arr[$i])->last_name;   
                                                    }
                                                  }

                                                  echo implode(", ", $users_access_name_arr);
                                                }
                                              }
                                              else
                                              {
                                                if((date('Y-m-d H:i:s', strtotime($value1)) == $value1))
                                                {
                                                    echo date("d-m-Y H:i:s", strtotime($value1));
                                                }
                                                else if((date('Y-m-d', strtotime($value1)) == $value1))
                                                {
                                                    echo date("d-m-Y", strtotime($value1));
                                                }
                                                else
                                                {
                                                  echo $value1;  
                                                }
                                                

                                              }
                                            ?>
                                          </td>
                                        </tr>
                                      
                                    <?php
                                        }
                                      }
                                    ?>
                                    </table>
                                  </td>
                                  <td style="border-left: 1px solid #ccc">
                                    <table width="100%" style="padding-left: 5px;">
                                    <?php
                                      if($after != '' || $after != null){
                                        
                                        if (array_key_exists('id',$after))
                                        {
                                          unset($after['id']);
                                        }

                                        if (array_key_exists('delete_status',$after))
                                        {
                                          unset($after['delete_status']);
                                        }    

                                        if (array_key_exists('delete_date',$after))
                                        {
                                          unset($after['delete_date']);
                                        }   

                                        if (array_key_exists('user_id',$after))
                                        {
                                          unset($after['user_id']);
                                        }  

                                        if (array_key_exists('tax_id',$after))
                                        {
                                          unset($after['tax_id']);
                                        } 

                                        if (array_key_exists('account_id',$after))
                                        {
                                          unset($after['account_id']);
                                        }   
                                      
                                        foreach ($after as $key2 => $value2) {
                                    ?>
                                        
                                        <tr style="border-bottom: 1px solid #DCDCDC">
                                          <td style="padding-left: 5px;">
                                            <?=ucfirst(str_replace("_", " ", $key2))?>
                                          </td>
                                          <td style="
                                              <?php 
                                                if($before != null && $before != '')
                                                {
                                                  if($before[$key2] != $after[$key2])
                                                  {
                                                    echo ' color:red';
                                                  }
                                                }
                                              ?>   
                                          ">
                                            <?php 
                                              if($key2 == 'tax')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('tax','tax_id',$value2)->tax_name;
                                                }
                                              }
                                              else if($key2 == 'country_id')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('countries','id',$value2)->name;
                                                }
                                              }
                                              else if($key2 == 'state_id')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('states','id',$value2)->name;
                                                }
                                              }
                                              else if($key2 == 'status')
                                              {
                                                if($value2 == 1)
                                                  echo 'Active';
                                                else 
                                                  echo 'Inactive';
                                              }
                                              else if($key2 == 'can_edit')
                                              {
                                                if($value2 == 1)
                                                  echo 'Yes';
                                                else 
                                                  echo 'No';
                                              }
                                              else if($key2 == 'expense_category_id')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('expense_category','id',$value2)->name; 
                                                }
                                              }
                                              else if($key2 == 'payment_method_id')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('payment_method','id',$value2)->name; 
                                                }
                                              }
                                              else if($key2 == 'from_account_id')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('account','id',$value2)->account_name; 
                                                }
                                              }
                                              else if($key2 == 'to_account_id')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('account','id',$value2)->account_name; 
                                                }
                                              }
                                              else if($key2 == 'supplier_id')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('supplier','id',$value2)->company_name; 
                                                }
                                              }
                                              else if($key2 == 'ledger_id')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('ledger','id',$value2)->title; 
                                                }
                                              }
                                              else if($key2 == 'users_can_approve')
                                              {
                                                $users_can_approve_name_arr = array();
                                                if($value2 != '')
                                                {
                                                  $users_can_approve_arr = explode(",", $value2);

                                                  for ($i=0; $i <sizeof($users_can_approve_arr) ; $i++) 
                                                  { 
                                                    if($users_can_approve_arr[$i] != '' || $users_can_approve_arr[$i] != null)
                                                    {
                                                      $users_can_approve_name_arr[] = $this->log_data_model->get_any_record('users','id',$users_can_approve_arr[$i])->first_name." ".$this->log_data_model->get_any_record('users','id',$users_can_approve_arr[$i])->last_name;   
                                                    }
                                                  }

                                                  echo implode(", ", $users_can_approve_name_arr);
                                                }
                                              }
                                              else if($key2 == 'purchase_project_manager')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('users','id',$value2)->first_name; 
                                                }
                                              }
                                              else if($key2 == 'purchase_project_coordinator')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('users','id',$value2)->first_name; 
                                                }
                                              }
                                              else if($key2 == 'sender_id')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('users','id',$value2)->first_name; 
                                                }
                                              }
                                              else if($key2 == 'recipient_id')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('users','id',$value2)->first_name; 
                                                }
                                              }
                                              else if($key2 == 'project')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('project','id',$value2)->code; 
                                                }
                                              }
                                              else if($key2 == 'purchase_order_status')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('status_code','id',$value2)->name; 
                                                }
                                              }
                                              else if($key2 == 'client')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('customer','id',$value2)->name; 
                                                }
                                              }
                                              else if($key2 == 'site_id')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('site','id',$value2)->name; 
                                                }
                                              }
                                              else if($key2 == 'purchase_bank_account')
                                              {
                                                if($value2 != null && $value2 != ""){
                                                  echo $this->log_data_model->get_any_record('account','id',$value2)->account_name; 
                                                }
                                              }
                                              else if($key2 == 'attached_files')
                                              {
                                                $attached_files_name_arr = array();
                                                if($value2 != '')
                                                {
                                                  $attached_files_arr = explode(",", $value2);

                                                  for ($i=0; $i <sizeof($attached_files_arr) ; $i++) 
                                                  { 
                                                    if($attached_files_arr[$i] != '' || $attached_files_arr[$i] != null)
                                                    {
                                                      $attached_files_name_arr[] = $this->log_data_model->get_any_record('attachment','id',$attached_files_arr[$i])->filename;   
                                                    }
                                                  }

                                                  echo implode(", ", $attached_files_name_arr);
                                                }
                                               
                                              }
                                              else if($key2 == 'service_id')
                                              {

                                                $service_id_name_arr = array();
                                                if($value2 != '')
                                                {
                                                  $service_id_arr = explode(",", $value2);

                                                  for ($i=0; $i <sizeof($service_id_arr) ; $i++) 
                                                  { 
                                                    if($service_id_arr[$i] != '' || $service_id_arr[$i] != null)
                                                    {
                                                      $service_id_name_arr[] = $this->log_data_model->get_any_record('service','id',$service_id_arr[$i])->title;   
                                                    }
                                                  }

                                                  echo implode(", ", $service_id_name_arr);
                                                }
                                              }
                                              else if($key2 == 'expense_category')
                                              {

                                                $expense_category_name_arr = array();
                                                if($value2 != '')
                                                {
                                                  $expense_category_arr = explode(",", $value2);

                                                  for ($i=0; $i <sizeof($expense_category_arr) ; $i++) 
                                                  { 
                                                    if($expense_category_arr[$i] != '' || $expense_category_arr[$i] != null)
                                                    {
                                                      $expense_category_name_arr[] = $this->log_data_model->get_any_record('expense_category','id',$expense_category_arr[$i])->name;   
                                                    }
                                                  }

                                                  echo implode(", ", $expense_category_name_arr);
                                                }
                                              }
                                              else if($key2 == 'users_access')
                                              {
                                                $users_access_name_arr = array();
                                                if($value2 != '')
                                                {
                                                  $users_access_arr = explode(",", $value2);

                                                  for ($i=0; $i <sizeof($users_access_arr) ; $i++) 
                                                  { 
                                                    if($users_access_arr[$i] != '' || $users_access_arr[$i] != null)
                                                    {
                                                      $users_access_name_arr[] = $this->log_data_model->get_any_record('users','id',$users_access_arr[$i])->first_name." ".$this->log_data_model->get_any_record('users','id',$users_access_arr[$i])->last_name;   
                                                    }
                                                  }

                                                  echo implode(", ", $users_access_name_arr);
                                                }
                                              }
                                              else
                                              {
                                                if((date('Y-m-d H:i:s', strtotime($value2)) == $value2))
                                                {
                                                    echo date("d-m-Y H:i:s", strtotime($value2));
                                                }
                                                else if((date('Y-m-d', strtotime($value2)) == $value2))
                                                {
                                                    echo date("d-m-Y", strtotime($value2));
                                                }
                                                else
                                                {
                                                  echo $value2;  
                                                }
                                                
                                              }
                                            ?>
                                          </td>
                                        </tr>
                                      
                                    <?php
                                        }
                                      }
                                    ?>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                              <?php 
                                }
                                else
                                {
                              ?>
                              null    
                              <?php   
                                }
                              ?>
                              
                            </td>
                            <td><?php echo date("d-m-Y H:i:s", strtotime($value->date_created));?></td>
                            
                            <td><?php echo $value->ip_address;?></td>
                            <td>

                              <?php 
                                if($value->referer_url != '' && $value->referer_url != null)
                                {
                              ?>
                                <a href="<?=$value->referer_url;?>" data-tt="tooltip" title="<?=$value->referer_url;?>">View</a>
                              <?php
                                }
                              ?>
                            </td>
                            <td><?php echo $value->browser;?></td>
                          </tr>
                          <?php
                            }
                          ?>
                          
                        </tbody>
                <tfoot>
                  <tr>
                    <th width="15%">User Name</th>
                      <th width="15%">Page(s)</th>
                      <th width="5%">Action</th>
                      <th width="30%">Description</th>
                      <th width="35%">Data</th>
                      <th width="15%">Date/Time</th>
                      <th width="10%">IP Address</th>
                      <th width="10%">Reference</th>
                      <th width="10%">Browser</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php $this->load->view('layout/footer');?>

<div class="example-modal">
  <div class="modal fade" id="export_history_log">
    <div class="modal-dialog">
      <form name="export_history_log_form" id="export_history_log_form" action="<?php echo base_url();?>log_data/create_full_csv" method="POST" target="_blank">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
             <h4 class="modal-title"><?=$this->lang->line('export_history_log')?></h4>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                    <label><?=$this->lang->line('history_user')?></label>
                    <select class="form-control select2bs4 prerror-select" name="user_id" id="user_id" style="width:100%">
                      <option value=""><?php echo "All";?></option>
                      <?php
                        foreach ($users as $key) {
                      ?>
                        <option value='<?php echo $key->id ?>'>
                        <?php echo $key->first_name." ".$key->last_name; ?>
                        </option>
                      <?php
                        }
                      ?>
                    </select>
                    <span class="validation-color" id="err_user_id"><?php echo form_error('user_id');?></span>
                </div>
              </div>
              <div class="col-md-6">
              

                <div class="form-group">
                    <label><?=$this->lang->line('history_modules')?></label>
                    <select class="form-control select2bs4 prerror-select" name="module" id="module" style="width:100%">
                      <option value=""><?php echo "All";?></option>
                      <?php
                        foreach ($modules as $key) {
                      ?>
                        <option value='<?php echo $key->module ?>'>
                        <?php echo ucwords(str_replace('_', ' ', $key->module)); ?>
                        </option>
                      <?php
                        }
                      ?>
                    </select>
                    <span class="validation-color" id="err_module"><?php echo form_error('module');?></span>
                </div>
               
              </div>
            </div>
            <div class="row">
            
              <div class="col-md-6">
                <div class="form-group">
                    <label><?=$this->lang->line('history_from_date')?></label>
                    <input class="form-control datepicker" id="from_date" type="text" name="from_date" value="" autocomplete="off">
                    <span class="validation-color"><?php echo form_error('from_date');?></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label><?=$this->lang->line('history_to_date')?></label>
                    <input class="form-control datepicker" id="to_date" type="text" name="to_date" value="" autocomplete="off">
                    <span class="validation-color"><?php echo form_error('to_date');?></span>
                </div>
              </div>
             
            </div>
            <div class="row">
              <div class="col-md-12">
               
                <div class="form-group">
                  <label for="delete">
                    <input type="checkbox" name="delete" id="delete" value="1">  <?=$this->lang->line('history_log_delete')?>
                  </label>
                </div>
               
              </div>
            </div> 
          </div>
          <div class="modal-footer">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success change_status_btn">Export</button>
            
          </div>
        </div>
      </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('#export_history_log_form').submit(function(e){
      // e.preventDefault();

    })
  });

</script>