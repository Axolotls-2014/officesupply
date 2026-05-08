

<script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url();?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo base_url();?>assets/plugins/chart.js/Chart.min.js"></script>

<!-- JQVMap -->
<!-- <script src="<?php echo base_url();?>assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script> -->
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url();?>assets/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url();?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo base_url();?>assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?php echo base_url();?>assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url();?>assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- // <script src="<?php echo base_url();?>assets/js/pages/dashboard.js"></script> -->
<!-- AdminLTE for demo purposes -->
<!-- <script src="<?php echo base_url();?>assets/js/demo.js"></script> -->
<script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- <script src="<?php echo base_url();?>assets/plugins/select2/js/select2.min.js"></script> -->
<script src="<?php echo base_url();?>assets/plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/filterizr/jquery.filterizr.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/autocomplete/jquery_auto_complete.js"></script>
<script src="<?php echo base_url();?>assets/plugins/printThis/printThis.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url();?>assets/js/modernizr.js"></script>

<!-- <script src="<?=base_url('assets/plugins/datepicker')?>js/bootstrap-datepicker.js"></script> -->
<script src="<?php echo base_url();?>assets/plugins/jquery-base64/jquery.base64.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/js-calc.js" defer></script>

<script src="<?php echo base_url();?>" data-app-script="myApp"></script>


 <footer class="main-footer no-print">
    <strong><?=$this->lang->line('copyright')?> &copy; <?=date('Y')?> <a href="https://www.Axolotlsindia">Axolotls India</a>.</strong>
    <?=$this->lang->line('all_rights_reserved')?>
    <div class="float-right d-none d-sm-inline-block">
      <b><?=$this->lang->line('version')?></b> 1.0
    </div>
 </footer>
 
<script type="text/javascript">
  var startDate = new Date($("#from_date").val());
  /*alert(startDate);*/
  var FromEndDate = new Date();
  var ToEndDate = new Date();
  ToEndDate.setDate(ToEndDate.getDate() + 365);
  

  // $('#from_date').datepicker({
  //     autoclose: true,
  //     todayHighlight: true,
  //     format: 'dd-mm-yyyy',
  //     startDate: startDate
  // }).on('changeDate', function () {
  //     var temp = $(this).datepicker('getDate');
  //     var startDate = new Date(temp);
  //     startDate.setDate(startDate.getDate(new Date(temp))+1);
  //     $('#to_date').datepicker('setStartDate', startDate);
  // });

  // $('#to_date').datepicker({
  //     weekStart: 1,
  //     startDate: startDate,
  //     endDate: ToEndDate,
  //     format: 'dd-mm-yyyy',
  //     autoclose: true
  // }).on('changeDate', function (selected) {
  //     var temp = $(this).datepicker('getDate');
  //     var FromEndDate = new Date(temp);
  //     FromEndDate.setDate(FromEndDate.getDate(new Date(temp)));
  //     $('#from_date').datepicker('setEndDate', FromEndDate);
  // });
</script>

<div class="message-modal"> 
  <div class="modal fade" id="message" data-backdrop="static" data-keyboard="false"  style="z-index:99999999 !important">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header message-header text-left">
          <h4 class="modal-title message_title"><?php echo $this->lang->line('message');?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4>
            <?php echo $this->lang->line('lbl_cust_delete_modal');?>
          </h4>
        </div>
        <div class="modal-body message-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><?php echo "Close";?></button>
        </div>
      </div>
      
    </div>
  </div>
</div>


<div class="modal fade" id="clear_dummy_data_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header failure-header text-left">
        <h4 class="modal-title message_title"><?php echo $this->lang->line('message');?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4>
          <?php echo $this->lang->line('lbl_cust_delete_modal');?>
        </h4>
      </div>
      <div class="modal-body message-body">
        Are you sure want to clear dummy data ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="window.location.href='<?=base_url('application_settings/clean_dummy_data')?>'"><?php echo "Yes";?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo "No";?></button>
      </div>
    </div>
    
  </div>
</div>

<div class="modal fade" id="create_backup_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header warning-header text-left">
        <h4 class="modal-title message_title"><?php echo $this->lang->line('message');?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4>
          <?php echo $this->lang->line('lbl_cust_delete_modal');?>
        </h4>
      </div>
      <div class="modal-body message-body">
        Do you want to create Backup ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="window.location.href='<?=base_url('application_settings/update_application')?>'"><?php echo "Yes";?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo "No";?></button>
      </div>
    </div>
    
  </div>
</div>


<div class="modal fade" id="restore_dummy_data_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header warning-header text-left">
        <h4 class="modal-title message_title"><?php echo $this->lang->line('message');?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4>
          <?php echo $this->lang->line('lbl_cust_delete_modal');?>
        </h4>
      </div>
      <div class="modal-body message-body">
        Are you sure want to restore dummy data ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="window.location.href='<?=base_url('application_settings/restore_dummy_data')?>'"><?php echo "Yes";?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo "No";?></button>
      </div>
    </div>
    
  </div>
</div>

<div class="modal fade" id="update_application_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header info-header text-left">
        <h4 class="modal-title message_title"><?php echo $this->lang->line('update_application');?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4>
          <?php echo $this->lang->line('lbl_cust_delete_modal');?>
        </h4>
      </div>
      <div class="modal-body message-body">
        
        <p>Update is available to install.</p>
        <h6 class="text-red">Important Notice</h6>
        <p>Before updating application, please take backup of application manually. Updating application is irreversible.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" onclick="window.location.href='<?=base_url('application_settings/update_application')?>'"><?php echo "Install Update";?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo "Close";?></button>
      </div>
    </div>
    
  </div>
</div>


<div class="guide-modal">
  <div class="modal fade" id="guide_modal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header text-left teal-header">
          <h4 class="modal-title"><?php echo $this->lang->line('help_question_answers');?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-5 col-sm-3">
              <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="vert-tabs-accounting-tab" data-toggle="pill" href="#vert-tabs-accounting" role="tab" aria-controls="vert-tabs-accounting" aria-selected="true">Accounting</a>
                <a class="nav-link" id="vert-tabs-company-setting-tab" data-toggle="pill" href="#vert-tabs-company-setting" role="tab" aria-controls="vert-tabs-company-setting" aria-selected="false">Company Setting</a>
                <a class="nav-link" id="vert-tabs-application-setting-tab" data-toggle="pill" href="#vert-tabs-application-setting" role="tab" aria-controls="vert-tabs-application-setting" aria-selected="false">Application Setting</a>
                <a class="nav-link" id="vert-tabs-sales-tab" data-toggle="pill" href="#vert-tabs-sales" role="tab" aria-controls="vert-tabs-sales" aria-selected="false">Sales</a>

              </div>
            </div>
            <div class="col-7 col-sm-9">
              <div class="tab-content" id="vert-tabs-tabContent">
                <div class="tab-pane text-left fade show active" id="vert-tabs-accounting" role="tabpanel" aria-labelledby="vert-tabs-accounting-tab">
                  <dl>
                    <dt>What is Capital Account ?</dt>
                    <dd>In accounting, a capital account is a general ledger account that is used to record the owner's contributed capital and retained earnings whether it is in any form i.e., Cash Balance, Bank Balance, Fixed Assests etc.</dd>

                    <dt>What is Cash on Hand ?</dt>
                    <dd>Cash on Hand is an Asset account. It is the total amount of any accessible cash. It is either brought by the capital or is received by any Sundry Debtor after sale or bank withdraw.</dd>

                    <dt>How does cash on hand ledger account works ?</dt>
                    <dd>Cash on Hand ledger works as debits(DR) increase its balance and credits(CR) decrease that total. This account, therefore, is said to carry a debit(DR) balance. In Simple form we can say that if Cash is Received it will be a debit Balance and if paid it will be a credit balance.</dd>
                  </dl>
                </div>
                <div class="tab-pane fade" id="vert-tabs-company-setting" role="tabpanel" aria-labelledby="vert-tabs-company-setting-tab">
                  <dl>
                    <dt>What is the use of Bank detail & Terms and Condition in Company setting ?</dt>
                    <dd>Mentioned Bank details and Terms & condition will be printed on sales invoice while printing.</dd>
                  </dl>
                </div>
                <div class="tab-pane fade" id="vert-tabs-application-setting" role="tabpanel" aria-labelledby="vert-tabs-application-setting-tab">
                  <dl>
                    <dt>How can I change the sidebar menu text size ?</dt>
                    <dd></dd>
                  </dl>
                </div>
                <div class="tab-pane fade" id="vert-tabs-sales" role="tabpanel" aria-labelledby="vert-tabs-sales-tab">
                   Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis. 
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
</div>

<div id="calculator_modal" class="modal">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header" style="background-color: white;">
              <h5 class="modal-title">Calculator</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
             <div id="calc-wrapper">
              <div id="calculator">
                <div id="screenLeft"></div>
                <div id="screen"><span id="displayLeft"></span><span id="display">0</span></div>
                <div class="leftPad">
                  <button type="button" onclick="clearAll()" class="btncal btn-3">C</button>
                  <button type="button" onclick="negate()" class="btncal">±</button>
                  <button type="button" onclick="prc()" class="btncal">%</button>
                  <button type="button" onclick="concatDigit(7)" class="btncal">7</button>
                  <button type="button" onclick="concatDigit(8)" class="btncal">8</button>
                  <button type="button" onclick="concatDigit(9)" class="btncal">9</button>
                  <button type="button" onclick="concatDigit(4)" class="btncal">4</button>
                  <button type="button" onclick="concatDigit(5)" class="btncal">5</button>
                  <button type="button" onclick="concatDigit(6)" class="btncal">6</button>
                  <button type="button" onclick="concatDigit(1)" class="btncal">1</button>
                  <button type="button" onclick="concatDigit(2)" class="btncal">2</button>
                  <button type="button" onclick="concatDigit(3)" class="btncal">3</button>
                  <button type="button" onclick="concatDigit(0)" class="btncal">0</button>
                  <button type="button" onclick="point()" class="btncal">.</button>
                  <button type="button" onclick="equals(operation)" class="btncal btn-4">=</button>
                </div>
                <div class="rightPad">
                  <button type="button" onclick="vals()" style="font-size:24px; border-radius: 5px" class="btncal btn-3">console<br/>log</button>
                  <button type="button" onclick="div()" class="btncal btn-2">÷</button>
                  <button type="button" onclick="mul()" class="btncal btn-2">×</button>
                  <button type="button" onclick="dif()" class="btncal btn-2">–</button>
                  <button type="button" onclick="sum()" class="btncal btn-2">+</button>
                </div>
              </div>
            </div>
          </div>
         
      </div>
  </div>
</div>
<div id="activate_modal" class="modal fade" style="z-index: 99999999;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form class="form-horizontal" name="activationForm" id="activationForm" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fas fa-key"></i>
            Activate System
          </h5>
          <?php 

          ?>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-warning alert-dismissible">
            <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
            <h5>Important Notice:</h5>
            <ul>
              <li>
                Using same activation key in multiple setup of software ( whether in <b>localhost</b> or <b>on server</b> ), all setup will stop working and you might end up lossing the data.
              </li>
            </ul>
          </div>
          <div class="form-group row">
            <label for="activation_key" class="col-sm-4 col-form-label"><?=$this->lang->line('application_settings_activation_key')?></label>
            <div class="col-sm-6">
              <textarea class="form-control form-control-sm" name="activation_key" id="activation_key" required="required"></textarea>
              <span id="err_activation_key" class="error invalid-feedback"></span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <button type="submit" name="submit" id="activateSubmit" class="btn btn-info" data-tt="tooltip" title="<?=$this->lang->line('activate')?>">
            <?=$this->lang->line('application_settings_activate')?>
          </button>
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('btn_modal_close');?></button>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="update_available_modal" class="modal fade">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      
    </div>
  </div>
</div>



<div class="modal fade" id="show_menu_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header info-header text-left">
        <h4 class="modal-title message_title">Menu</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4>
          <?php echo $this->lang->line('lbl_cust_delete_modal');?>
        </h4>
      </div>
      <div class="modal-body message-body">
        
        <div class="row">
          <div class="col-md-4">
            <a href="<?=base_url('auth/dashboard')?>" class="btn btn-block btn-outline-primary btn-sm mb-3"> <i class="nav-icon fas fa-tachometer-alt mr-2"></i> DASHBOARD</a>
          </div>
          
          <div class="col-md-4">
            <a href="<?=base_url('employee')?>" class="btn btn-block btn-outline-primary btn-sm mb-3"> <i class="nav-icon fas fa-university mr-2"></i> HR MODULE</a>
          </div>

          <div class="col-md-4">
            <a href="<?=base_url('bank_account')?>" class="btn btn-block btn-outline-primary btn-sm mb-3"> <i class="nav-icon fas fa-piggy-bank mr-2"></i> BANK ACCOUNT</a>
          </div>
        </div>

        <div class="row">
          <table class="table table-bordered">
            <thead>
              <tr style="background-color:#F5F5F5;">
                <th>PEOPLE</th>
                <th>ACCOUNT</th>
                <th>INVENTORY</th>
                <th>TRANSACTION</th>
                <th>REPORTS</th>
                <th>SETTINGS</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <ul class="custom-list">
                    <?php 
                      if($this->permission_model->has_module_permission('user_role'))
                      {
                    ?>
                        <li><a href="<?=base_url('auth/user_roles')?>">User Roles</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('user'))
                      {
                    ?>
                        <li><a href="<?=base_url('auth/users')?>">Users</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('customer'))
                      {
                    ?>
                        <li><a href="<?=base_url('customer')?>">Customers</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('supplier'))
                      {
                    ?>
                        <li><a href="<?=base_url('supplier')?>">Suppliers</a></li>
                    <?php
                      }
                    ?>
                  </ul>
                </td>

                <td>
                  <ul class="custom-list">
                    <?php 
                      if($this->permission_model->has_module_permission('cash_bank_entry'))
                      {
                    ?>
                        <li><a href="<?=base_url('cash_bank_entry')?>">Cash Bank Entry</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('bank_statement'))
                      {
                    ?>
                        <li><a href="<?=base_url('bank_statement')?>">Bank Statement</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('pdc_receive'))
                      {
                    ?>
                        <li><a href="<?=base_url('pdc_receive')?>">PDC Receive</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('pdc_payment'))
                      {
                    ?>
                        <li><a href="<?=base_url('pdc_payment')?>">PDC Payment</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('scrap_entry'))
                      {
                    ?>
                        <li><a href="<?=base_url('scrap_entry')?>">Scrap Entry</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('scrap_issue'))
                      {
                    ?>
                        <li><a href="<?=base_url('scrap_issue')?>">Scrap Issue</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('scrap_receive'))
                      {
                    ?>
                        <li><a href="<?=base_url('scrap_receive')?>">Scrap Receive</a></li>
                    <?php
                      }
                    ?>
                  </ul>
                </td>

                <td>
                  <ul class="custom-list">
                    <?php 
                      if($this->permission_model->has_module_permission('promotion'))
                      {
                    ?>
                        <?php
                          $promotion = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'promotion', 'active',$row = true,$check_delete_status = false);
                        ?>
                        <li class="<?= (empty($promotion) || $promotion->field_status !== 'active') ? 'd-none' : '' ?>"><a href="<?=base_url('promotion')?>">Promotion</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('warehouse'))
                      {
                    ?>
                        <li><a href="<?=base_url('warehouse')?>">Warehouse</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('product_category'))
                      {
                    ?>
                        <li><a href="<?=base_url('product_category')?>">Product Category</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('product'))
                      {
                    ?>
                        <li><a href="<?=base_url('product_core')?>">Add Product</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('product'))
                      {
                    ?>
                        <li><a href="<?=base_url('product')?>">All Products</a></li>
                    <?php
                      }
                    ?>

                   

                    <?php 
                      if($this->permission_model->has_module_permission('scrap_product'))
                      {
                    ?>
                        <li><a href="<?=base_url('scrap_product')?>">All Scrap Products</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('stock'))
                      {
                    ?>
                        <li><a href="<?=base_url('stock_entry')?>">Stock Entry</a></li>
                    <?php
                      }
                    ?>
                  </ul>
                </td>

                <td>
                  <ul class="custom-list">
                    <?php 
                      if($this->permission_model->has_module_permission('purchase_order'))
                      {
                    ?>
                        <li><a href="<?=base_url('purchase_order')?>">Purchase Order</a></li>
                    <?php
                      }
                    ?>
                    <?php 
                      if($this->permission_model->has_module_permission('purchase'))
                      {
                    ?>
                        <li><a href="<?=base_url('purchase')?>">Purchase</a></li>
                    <?php
                      }
                    ?>
                    <?php 
                      if($this->permission_model->has_module_permission('purchase_return'))
                      {
                    ?>
                        <li><a href="<?=base_url('purchase_return')?>">Purchase Return</a></li>
                    <?php
                      }
                    ?>
                    <?php 
                      if($this->permission_model->has_module_permission('transfer'))
                      {
                    ?>
                      <li><a href="<?=base_url('transfer')?>">Transfer</a></li>
                    <?php
                      }
                    ?>
                    <?php 
                      if($this->permission_model->has_module_permission('quotation'))
                      {
                    ?>
                      <li><a href="<?=base_url('quotation')?>">Quotation</a></li>
                    <?php
                      }
                    ?>
                    <?php 
                      if($this->permission_model->has_module_permission('proforma_invoice'))
                      {
                    ?>

                        <li><a href="<?=base_url('proforma_invoice')?>">Proforma Invoice</a></li>
                    <?php
                      }
                    ?>
                    <?php 
                      if($this->permission_model->has_module_permission('sale'))
                      {
                    ?>
                        <li><a href="<?=base_url('sale')?>">Sale</a></li>
                    <?php
                      }
                    ?>
                    <?php 
                      if($this->permission_model->has_module_permission('sales_return'))
                      {
                    ?>
                        <li><a href="<?=base_url('sales_return')?>">Sales Return</a></li>
                    <?php
                      }
                    ?>
                    <?php 
                      if($this->permission_model->has_module_permission('credit_debit_note'))
                      {
                    ?>
                        <li><a href="<?=base_url('credit_debit_note')?>">Credit Debit Note</a></li>
                    <?php
                      }
                    ?>
                    <?php 
                      if($this->permission_model->has_module_permission('expense'))
                      {
                    ?>
                        <li><a href="<?=base_url('expense')?>">Expense</a></li>
                    <?php
                      }
                    ?>
                  </ul>
                </td>

                <td>
                  <ul class="custom-list">
                   
                      <?php 
                        if($this->permission_model->has_permission('customer_payment_due_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/customer_payment_due')?>">Customer Payment Due</a></li>
                      <?php
                        }
                      ?>
                    
                      <?php 
                        if($this->permission_model->has_permission('supplier_payment_due_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/supplier_payment_due')?>">Supplier Payment Due</a></li>
                      <?php
                        }
                      ?>
                    
                   
                      <?php 
                        if($this->permission_model->has_permission('stock_value_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/stock_value')?>">Stock Value</a></li>
                      <?php
                        }
                      ?>
                      <li class="nav-item">
    <a href="<?=base_url('report/stock_movement_summary')?>" class="nav-link">
        <i class="fas fa-exchange-alt nav-icon"></i>
        <p>Stock Movement Summary</p>
    </a>
</li>
                  
                    
                      <?php 
                        if($this->permission_model->has_permission('closing_stock_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/closing_stock')?>">Closing Stock</a></li>
                      <?php
                        }
                      ?>
                   
                      <?php 
                        if($this->permission_model->has_permission('inventory_product_added_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/inventory_product_added')?>">Inventory Product Added</a></li>
                      <?php
                        }
                      ?>
                    
                   
                      <?php 
                        if($this->permission_model->has_permission('receivable_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/receivable')?>">Receivable</a></li>
                      <?php
                        }
                      ?>
                    
                      <?php 
                        if($this->permission_model->has_permission('payable_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/payable')?>">Payable</a></li>
                      <?php
                        }
                      ?>
                    
                    
                      <?php 
                        if($this->permission_model->has_permission('gstr1_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/gstr1')?>">GSTR1</a></li>
                      <?php
                        }
                      ?>
                    
                    
                      <?php 
                        if($this->permission_model->has_permission('gstr2_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/gstr2')?>">GSTR2</a></li>
                      <?php
                        }
                      ?>
                   
                      <?php 
                        if($this->permission_model->has_permission('hsn_sale'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/hsn_sale')?>">HSN Sale</a></li>
                      <?php
                        }
                      ?>
                    
                      <?php 
                        if($this->permission_model->has_permission('hsn_purchase'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/hsn_purchase')?>">HSN Purchase</a></li>
                      <?php
                        }
                      ?>
                   
                   
                      <?php 
                        if($this->permission_model->has_permission('product_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/stock')?>">Stock</a></li>
                      <?php
                        }
                      ?>
                    
                      <?php 
                        if($this->permission_model->has_permission('product_detail_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/product')?>">Product Detail</a></li>
                      <?php
                        }
                      ?>
                    
                    
                      <?php 
                        if($this->permission_model->has_permission('product_sale'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/product_sale')?>">Product Sale</a></li>
                      <?php
                        }
                      ?>
                   
                    
                      <?php 
                        if($this->permission_model->has_permission('product_purchase'))
                        {
                      ?>
                        <li><a  href="<?=base_url('report/product_purchase')?>">Product Purchase</a></li>
                      <?php
                        }
                      ?>
                    
                   
                      <?php 
                        if($this->permission_model->has_permission('sale_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/sale')?>">Sales</a></li>
                      <?php
                        }
                      ?>
                    
                   
                      <?php 
                        if($this->permission_model->has_permission('sales_return_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/sales_return')?>">Sales Return</a></li>
                      <?php
                        }
                      ?>
                    
                   
                      <?php 
                        if($this->permission_model->has_permission('purchase_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/purchase')?>">Purchase</a></li>
                      <?php
                        }
                      ?>
                    
                    
                      <?php 
                        if($this->permission_model->has_permission('purchase_return_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/purchase_return')?>">Purchase Return</a></li>
                      <?php
                        }
                      ?>
                   
                    
                      <?php 
                        if($this->permission_model->has_permission('expense_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/expense')?>">Expense</a></li>
                      <?php
                        }
                      ?>
                   
                    
                      <?php 
                        if($this->permission_model->has_permission('ledger_report'))
                        {
                      ?>
                        <li><a href="<?=base_url('report/ledger')?>">Ledger</a></li>
                      <?php
                        }
                      ?>
                    
                      <?php 
                        if($this->permission_model->has_permission('daily_report'))
                        {
                      ?>
                      <li><a href="<?=base_url('report/daily')?>">Daily</a></li>
                      <?php
                        }
                      ?>
                    
                    
                  </ul>
                </td>

                <td>
                  <ul class="custom-list">
                  
                    <?php 
                      if($this->permission_model->has_module_permission('company_setting'))
                      {
                    ?>
                        <li><a href="<?=base_url('settings')?>">Company settings</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('custom_field'))
                      {
                    ?>
                        <li><a href="<?=base_url('custom_field')?>">Custom settings</a></li>
                    <?php
                      }
                    ?>


                    <?php 
                      if($this->permission_model->has_module_permission('email_template'))
                      {
                    ?>
                        <li><a href="<?=base_url('email_template')?>">Email Template</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('whatsapp_template'))
                      {
                    ?>
                        <li><a href="<?=base_url('whatsapp_template')?>">Whatsapp Template</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('expense_category'))
                      {
                    ?>
                        <li><a href="<?=base_url('expense_category')?>">Expense Category</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('item'))
                      {
                    ?>
                        <li><a href="<?=base_url('item')?>">Item</a></li>
                    <?php
                      }
                    ?>
                    <?php 
                      if($this->permission_model->has_module_permission('tax'))
                      {
                    ?>
                        <li><a href="<?=base_url('tax')?>">Tax</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('discount'))
                      {
                    ?>
                        <li><a href="<?=base_url('discount')?>">Discount</a></li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_module_permission('currency'))
                      {
                    ?>
                        <li><a href="<?=base_url('currency')?>">Currency</a></li>
                    <?php
                      }
                    ?>
                  </ul>
                </td>


              </tr>
            </tbody>
          </table>
        </div>

      </div>
      
    </div>
    
  </div>
</div>




<script type="text/javascript">
  $(document).ready(function(e){
    const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 10000
                          });



   

    $('#activate_modal').on('shown.bs.modal',function(event){
      $.get('<?=base_url('application_settings/index')?>', function(data, status){
        $('#activation_key').text(JSON.parse(data).application_settings.activation_key);    
      });
    });

    $(document).on('submit','form#activationForm',function(e){
      e.preventDefault();

      var activationFormData = $('form#activationForm').serialize();
      $('#activateSubmit').text('<?=$this->lang->line("application_settings_activating")?>').attr('disabled','disabled');

      $.ajax({
        url: "<?=base_url('application_settings/activate')?>",
        type: "POST",
        data: activationFormData,
        dataType: "JSON",
        success: function(data){

          // alert(data);
          var toast_type = '';
          var toast_message = '';

          if(data.code == 1)
          {
            toast_type    = 'success';
            toast_message = data.message; 
            $('#err_activation_key').text('');
            $('#activateSubmit').text('<?=$this->lang->line("application_settings_activated")?>')
            $('#activate_modal').modal('hide');

            Toast.fire({
              type: toast_type,
              title: toast_message
            });

            location.reload();
          }
          else if(data.code == 0)
          {
            $('#activateSubmit').removeAttr('disabled').text('<?=$this->lang->line("application_settings_activate")?>');
          }
          else if(data.code == 2)
          {
            alert(data.message);
            $('#activateSubmit').removeAttr('disabled').text('<?=$this->lang->line("application_settings_activate")?>'); 
          }
        }
      });
    });
  });
</script>
<div class="short-cut-modal">
  <div class="modal fade" id="short_cut_modal" data-keyboard="true" tabindex="-1" style="z-index: 99999999999 !important">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header text-left primary-header">
          <h4 class="modal-title"><?php echo $this->lang->line('shortcut_modal_title');?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-bordered" width="100%">
                <tr>
                  <td class="td_font_awesome"><i class="fas fa-keyboard text-primary"></i> </td>
                  <td>Go Shortcuts</td>
                  <td class="short_cut">CTRL + ALT + 1</td> 
                  <td class="td_font_awesome"><i class="fas fa-tachometer-alt text-red"></i></td>
                  <td>Dashboard</td>
                  <td class="short_cut" colspan="4">CTRL + ALT + H</td> 
                  <!-- <td class="td_font_awesome"><i class="fas fa-tree text-yellow"></i> </td>
                  <td>GST Return</td>
                  <td class="short_cut">CTRL + ALT + G</td>  -->
                </tr>
                <tr>
                  <td class="td_font_awesome"><i class="fas fa-user-cog text-purple"></i></td>
                  <td>User Roles</td>
                  <td class="short_cut">CTRL + ALT + R</td> 
                  <td class="td_font_awesome"><i class="fas fa-users text-pink"></i></td>
                  <td>Users</td>
                  <td class="short_cut">CTRL + ALT + I</td> 
                  <td class="td_font_awesome"><i class="fas fa-user-friends text-yellow"></i></td>
                  <td>Customers</td>
                  <td class="short_cut">CTRL + ALT + C</td> 
                </tr>
                <tr>
                  <td class="td_font_awesome"><i class="fas fa-warehouse text-red"></i></td>
                  <td>Warehouse</td>
                  <td class="short_cut">CTRL + ALT + W</td> 
                  <td class="td_font_awesome"><i class="fas fas fa-shapes text-blue"></i></td>
                  <td>Product Category</td>
                  <td class="short_cut">CTRL + ALT + J</td>
                  <td class="td_font_awesome"><i class="fas fa-dice-d20 text-green"></i></td>
                  <td>Product</td>
                  <td class="short_cut">CTRL + ALT + K</td> 
                  
                </tr>
                <tr>
                  <td class="td_font_awesome"><i class="fas fa-piggy-bank text-blue"></i></td>
                  <td>Bank Account</td>
                  <td class="short_cut">CTRL + ALT + B</td> 
                  <td class="td_font_awesome"><i class="fas fa-balance-scale text-pink"></i></td>
                  <td>Balance Sheet Report</td>
                  <td class="short_cut">CTRL + ALT + F</td>
                  <td class="td_font_awesome"><i class="fas fa-store text-red"></i></td>
                  <td>Sale</td>
                  <td class="short_cut">CTRL + ALT + S</td> 
                  
                </tr>
                <tr>
                  <td class="td_font_awesome"><i class="fas fa-reply text-red"></i> </td>
                  <td>Sale Return</td>
                  <td class="short_cut">CTRL + ALT + A</td> 
                  <td class="td_font_awesome"><i class="fas fa-file-invoice-dollar text-green"></i></td>
                  <td>Quotation</td>
                  <td class="short_cut">CTRL + ALT + Q</td> 
                  <td class="td_font_awesome"><i class="fab fa-codepen text-green"></i></td>
                  <td>Expense</td>
                  <td class="short_cut">CTRL + ALT + E</td> 
                </tr>
                <tr>
                  <td class="td_font_awesome"><i class="fas fa-male text-yellow"></i> </td>
                  <td>Suppliers</td>
                  <td class="short_cut">CTRL + ALT + O</td> 
                  <td class="td_font_awesome"><i class="fas fa-book-open text-yellow"></i> </td>
                  <td>Sales Report</td>
                  <td class="short_cut">CTRL + ALT + 2</td> 
                  <td class="td_font_awesome"><i class="fab fa-readme text-red"></i> </td>
                  <td>Expense Report</td>
                  <td class="short_cut">CTRL + ALT + 3</td> 
                </tr>

                <tr>
                  <td class="td_font_awesome"><i class="fas fa-hand-holding-usd text-teal"></i></td>
                  <td>Profit & Loss Report</td>
                  <td class="short_cut">CTRL + ALT + 4</td>
                  <td class="td_font_awesome"><i class="fas fa-search-dollar text-orange"></i></td> 
                  <td>Ledger Report</td>
                  <td class="short_cut">CTRL + ALT + 5</td> 
                  <td class="td_font_awesome"><i class="fas fa-cogs text-pink"></i></td> 
                  <td>Company Settings</td>
                  <td class="short_cut">CTRL + ALT + Z</td> 
                </tr>

                <tr>
                  <td class="td_font_awesome"><i class="fas fa-hand-holding-usd text-yellow"></i></td>
                  <td>Application Settings</td>
                  <td class="short_cut">CTRL + ALT + X</td>
                  <td class="td_font_awesome"><i class="fas fa-receipt text-yellow"></i></td> 
                  <td>Expense Category</td>
                  <td class="short_cut">CTRL + ALT + V</td> 
                  <td class="td_font_awesome"><i class="fas fa-file-invoice text-green"></i></td> 
                  <td>Tax</td>
                  <td class="short_cut">CTRL + ALT + T</td> 
                </tr>

                <tr>
                  <td class="td_font_awesome"><i class="fas fa-percent text-red"></i></td>
                  <td>Discount</td>
                  <td class="short_cut">CTRL + ALT + D</td>
                  <td class="td_font_awesome"><i class="fab fa-ethereum  text-green"></i></td> 
                  <td>Currency</td>
                  <td class="short_cut">CTRL + ALT + Y</td> 
                  <td class="td_font_awesome"><i class="fas fa-life-ring text-purple"></i></td> 
                  <td>Email Configuration</td>
                  <td class="short_cut">CTRL + ALT + M</td> 
                </tr>

                <tr>
                  <td class="td_font_awesome"><i class="fas fa-history text-pink"></i></td>
                  <td>History Logs</td>
                  <td class="short_cut">CTRL + ALT + L</td>
                  <td class="td_font_awesome"><i class="fas fa-shopping-bag text-yellow"></i></td>
                  <td>Purchase</td>
                  <td class="short_cut">CTRL + ALT + N</td>
                  <td class="td_font_awesome"><i class="fas fa-reply text-yellow"></i></td>
                  <td>Purchase Return</td>
                  <td class="short_cut">CTRL + ALT + U</td>
                 
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" data-tt="tooltip" title="<?=$this->lang->line('shortcut_modal_close')?>">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
</div>

    <script type="text/javascript">
      $(document).ready(function(e){
        document.addEventListener ("keydown", function (zEvent) {
          
          if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "h") {  
            window.location.href="<?=base_url('auth/dashboard')?>";
          }
          // else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "g") {  
          //   window.location.href="<?=base_url('gst_return')?>";
          // }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "w") {  
            window.location.href="<?=base_url('warehouse')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "n") {  
            window.location.href="<?=base_url('purchase')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "u") {  
            window.location.href="<?=base_url('purchase_return')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "j") {  
            window.location.href="<?=base_url('product_category')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "k") {  
            window.location.href="<?=base_url('product')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "r") {  
            window.location.href="<?=base_url('auth/user_roles')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "i") {  
            window.location.href="<?=base_url('auth/users')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "c") {  
            window.location.href="<?=base_url('customer')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "b") {  
            window.location.href="<?=base_url('bank_account')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "f") {  
            window.location.href="<?=base_url('report/balance_sheet')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "s") {  
            window.location.href="<?=base_url('sale')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "a") {  
            window.location.href="<?=base_url('sales_return')?>";
          }        
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "q") {  
            window.location.href="<?=base_url('quotation')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "e") {  
            window.location.href="<?=base_url('expense')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "o") {  
            window.location.href="<?=base_url('supplier')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "2") {  
            window.location.href="<?=base_url('report/sale')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "3") {  
            window.location.href="<?=base_url('report/expense')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "4") {  
            window.location.href="<?=base_url('report/profit_and_loss')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "5") {  
            window.location.href="<?=base_url('report/ledger')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "z") {  
            window.location.href="<?=base_url('settings')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "x") {  
            window.location.href="<?=base_url('application_settings')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "v") {  
            window.location.href="<?=base_url('expense_category')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "t") {  
            window.location.href="<?=base_url('tax')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "d") {  
            window.location.href="<?=base_url('discount')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "y") {  
            window.location.href="<?=base_url('currency')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "m") {  
            window.location.href="<?=base_url('email_setup')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "l") {  
            window.location.href="<?=base_url('log_data')?>";
          }
          else if (zEvent.ctrlKey  &&  zEvent.altKey  &&  zEvent.key === "1") {  
            $('#short_cut_modal').modal('show');
          }
        });
      })
    </script>

    <script type="text/javascript">
      $.AdminLTESidebarTweak = {};

      $.AdminLTESidebarTweak.options = {
              EnableRemember: true,
              NoTransitionAfterReload: true
              //Removes the transition after page reload.
      };

      $(function () {
          "use strict";

          $("body").on("collapsed.pushMenu", function() {
              if($.AdminLTESidebarTweak.options.EnableRemember) {
                  localStorage.setItem("toggleState", "closed");
              }
          });
              
          $("body").on("expanded.pushMenu", function() {
                  if($.AdminLTESidebarTweak.options.EnableRemember) {
                      localStorage.setItem("toggleState", "opened");
                  } 
          });

          if ($.AdminLTESidebarTweak.options.EnableRemember) {
              var toggleState = localStorage.getItem("toggleState");
              if (toggleState == 'closed'){
                  if ($.AdminLTESidebarTweak.options.NoTransitionAfterReload) {
                      $("body").addClass('sidebar-collapse hold-transition').delay(100).queue(function() {
                          $(this).removeClass('hold-transition');
                      });
                  } else {
                      $("body").addClass('sidebar-collapse');
                  }
              }
          }
      });
    </script>

    <script type="text/javascript">
      $(document).ready(function(e){

        const CapitalToast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 10000
        });

        $('#capitalLedgerForm').submit(function(e){
          e.preventDefault();

          var capitalLedgerForm        = $(this).closest('form');        
          var capitalLedgerFormData    = capitalLedgerForm.serialize();

          $('#capitalLedgerSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

          $.ajax({
            url: "<?php echo base_url('transaction/add')?>",
            type: "POST",
            data: capitalLedgerFormData,
            dataType: "JSON",
            success: function(data){

              $('#capital_modal').modal('hide');
              
              capitalLedgerForm.find('input[name="transaction_amount"]').val('');

              if(data.code == 1)
              {
                CapitalToast.fire({
                  type: 'success',
                  title: data.message
                });

                location.reload();
              }
              else
              {
                CapitalToast.fire({
                  type: 'error',
                  title: data.message
                }); 
                location.reload();
              } 
              $('#capitalLedgerSubmit').text('<?=$this->lang->line("save")?>').removeAttr('disabled');         
            }
          });   
        });

        $('#cashCapitalLedgerForm').submit(function(e){
          e.preventDefault();
          var isError = false;

          $('form#cashCapitalLedgerForm .field_validation').each(function() {
              
              var id    = $(this).attr('id');
              var value = $(this).val();
              var field = $(this).attr('placeholder');

              if(value==null || value==""){
                $("form#cashCapitalLedgerForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
                $('form#cashCapitalLedgerForm #'+id).addClass('is-invalid');
                isError = true;
              }
              else
              {
                $("form#cashCapitalLedgerForm #err_"+id).text("").fadeOut('slow');
                $('form#cashCapitalLedgerForm #'+id).removeClass('is-invalid');
                $('form#cashCapitalLedgerForm #'+id).addClass('is-valid');
              }
          });

          if(isError == true)
          {
            return false;
          }
          else
          {
            var cashCapitalLedgerFormData = $(this).closest('form').serialize();
            $('#cashCapitalLedgerSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

            $.ajax({
              url: "<?php echo base_url('transaction/add')?>",
              type: "POST",
              data: cashCapitalLedgerFormData,
              dataType: "JSON",
              success: function(data){

                $('#cash-to-capital_modal').modal('hide');
                if(data.code == 1)
                {
                  CapitalToast.fire({
                    type: 'success',
                    title: data.message
                  });
                  location.reload();
                }
                else
                {
                  CapitalToast.fire({
                    type: 'error',
                    title: data.message
                  }); 
                  location.reload();
                } 

                $('#cashCapitalLedgerSubmit').text('<?=$this->lang->line("save")?>').removeAttr('disabled');
              }
            });  
          }
        });
      });
    </script>

    <script>
      //paste this code under head tag or in a seperate js file.
      // Wait for window load
      $(document).ready(function() {
        // Animate loader off screen
        setTimeout(function(){ 
          $(".se-pre-con").fadeOut(); 
        }, 10);
      });
    </script>

    <script>
      $(function () {
        $("input[data-bootstrap-switch]").each(function(){
          $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
        $("#example1").DataTable({
          "ordering": false,
          "iDisplayLength": 50,
          "aLengthMenu": [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]]
        });
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "iDisplayLength": 50,
          "aLengthMenu": [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]]
        });
      });

      function show_message(message_header,message_body = null)
      {
        var message_headers = ['success-header','info-header','failure-header','secondary-header']

        for (var i = message_headers.length - 1; i >= 0; i--) 
        {
          if($('.message_header').hasClass(message_headers[i]))
          {
            $('.message_header').removeClass(message_headers[i]);  
          }
        }

        $('.message-header').addClass(message_header);

        if(message_body != null)
        {
          $('.message-body').html(message_body);
        }

        $('#message').modal('show');
      }
    </script>

    <script>
      $(document).ready(function(e){
        
        // Initialize tooltip
        $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
        
        // Mask the date
        $('[data-mask]').inputmask("99-99-9999");

        //Initialize Select2 Elements
        $('.select2bs4').select2({
          theme: 'bootstrap4'
        });

        $('.datepicker').datepicker({
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });

        // $('.datepicker').datepicker("setDate", new Date());
      });
    </script>

    <script type="text/javascript">

      $(function() {
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 10000
        });

      <?php if($this->session->flashdata('success')) { ?>
          Swal.fire({
            title: 'SUCCESS !',
            text: '<?=$this->session->flashdata('success')?>',
            icon: "success",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            timer: 10000,

            customClass: {
                confirmButton: "btn btn-primary"
            }
          });
      <?php } ?>

      <?php if($this->session->flashdata('failure')) { ?>
          Swal.fire({
            title: 'FAILURE !',
            text: '<?=$this->session->flashdata('failure')?>',
            icon: "danger",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            timer: 30000,
            customClass: {
                confirmButton: "btn btn-primary"
            }
          });
      <?php } ?>

      <?php if($this->session->flashdata('warning')) { ?>
          Swal.fire({
            title: 'WARNING !',
            text: '<?=$this->session->flashdata('warning')?>',
            icon: "warning",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            timer: 30000,
            customClass: {
                confirmButton: "btn btn-primary"
            }
          });
      <?php } ?>

      <?php if($this->session->flashdata('message')) { ?>
          Swal.fire({
            title: 'Message !',
            text: '<?=strip_tags($this->session->flashdata('message'))?>',
            icon: "warning",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            timer: 3000,
            customClass: {
                confirmButton: "btn btn-primary"
            }
          });
      <?php } ?>
    });
  </script>
  </body>
</html>