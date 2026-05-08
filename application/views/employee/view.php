<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_hrmodule')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_employee')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('employee_view')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <?php $this->load->view('layout/common/hr_menu');?>
        </div>
        <div class="col-md-9">
          <div class="card">
            <div class="card-header secondary-header">
              <h3 class="card-title"><?=$this->lang->line('employee_view')?></h3>
              <div class="card-tools">
                    
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item ml-2">
                    <a class="nav-link active text-white btn btn-secondary" href="<?=base_url('employee')?>" data-tt="tooltip" title="Click here to show employee list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                  </li>
                </ul>
              
              </div>
             
            </div>
            <div class="card-body p-0">

               <table class="table table-striped">
                  
                  <tbody>
                    <tr>
                      <td>
                        <label><?=$this->lang->line('employee_first_name')?></label> 
                        : 
                        <?=$employee->first_name ?>
                      </td>
                      <td>
                        <label><?=$this->lang->line('employee_last_name')?></label> 
                        : 
                        <?=$employee->last_name ?>
                      </td>

                      <td>
                        <label><?=$this->lang->line('employee_code')?></label> 
                        : 
                        <?=$employee->employee_code ?>
                      </td>
                      
                      
                    </tr>
                    <tr>

                     

                      <td>
                        <label><?=$this->lang->line('employee_email')?></label> 
                        : 
                        <?=$employee->email ?>
                      </td>

                      <td>
                        <label><?=$this->lang->line('employee_date_of_birth')?></label> 
                        : 
                        <?=($employee->date_of_birth != '' && $employee->date_of_birth != '0000-00-00') ? date('d-m-Y',strtotime($employee->date_of_birth)) : ''?>
                      </td>

                      <td>
                        <label><?=$this->lang->line('employee_department_id')?></label> 
                        : 
                        <?=$employee->department_name?>
                      </td>
                     
                     
                    </tr>
                    <tr>

                     
                      <td>
                        <label><?=$this->lang->line('employee_position_id')?></label> 
                        : 
                        <?=$employee->position_title?>
                      </td>

                      <td>
                        <label><?=$this->lang->line('employee_hire_date')?></label> 
                        : 
                        <?=($employee->hire_date != '' && $employee->hire_date != '0000-00-00') ? date('d-m-Y',strtotime($employee->hire_date)) : ''?>
                      </td>

                      <td>
                        <label><?=$this->lang->line('employee_termination_date')?></label> 
                        : 
                        <?=($employee->termination_date != '' && $employee->termination_date != '0000-00-00') ? date('d-m-Y',strtotime($employee->termination_date)) : ''?>
                      </td>
                     
                      
                    </tr>
                    <tr>

                      
                      <td>
                        <label><?=$this->lang->line('employee_salary')?></label> 
                        : 
                        <?=$employee->salary ?>
                      </td>
                      
                      <td>
                        <label><?=$this->lang->line('employee_bank_name')?></label> 
                        : 
                        <?=$employee->bank_name ?>
                      </td>

                      <td>
                        <label><?=$this->lang->line('employee_account_no')?></label> 
                        : 
                        <?=$employee->account_number ?>
                      </td>
                     
                      
                    </tr>
                    <tr>

                      
                      <td>
                        <label><?=$this->lang->line('employee_branch')?></label> 
                        : 
                        <?=$employee->branch ?>
                      </td>
                      
                      <td colspan="2">
                        <label><?=$this->lang->line('employee_ifsc_code')?></label> 
                        : 
                        <?=$employee->ifsc_code ?>
                      </td>
                     
                    </tr>
                   
                  </tbody>
                </table>
            </div>
            <!-- /.card-body -->
          </div>

          <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="pt-2 px-3"><h3 class="card-title">Employee Details</h3></li>
                <li class="nav-item">
                  <a class="nav-link active" id="custom-tabs-one-attendance-tab" data-toggle="pill" href="#custom-tabs-one-attendance" role="tab" aria-controls="custom-tabs-one-attendance" aria-selected="true"><?=$this->lang->line('attendance_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-leave-tab" data-toggle="pill" href="#custom-tabs-one-leave" role="tab" aria-controls="custom-tabs-one-leave" aria-selected="true"><?=$this->lang->line('leave_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-advance-salary-tab" data-toggle="pill" href="#custom-tabs-one-advance-salary" role="tab" aria-controls="custom-tabs-one-advance-salary" aria-selected="true"><?=$this->lang->line('advance_salary_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-bonus-tab" data-toggle="pill" href="#custom-tabs-one-bonus" role="tab" aria-controls="custom-tabs-one-bonus" aria-selected="true"><?=$this->lang->line('bonus_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-deduction-tab" data-toggle="pill" href="#custom-tabs-one-deduction" role="tab" aria-controls="custom-tabs-one-deduction" aria-selected="true"><?=$this->lang->line('deduction_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-tax-deduction-tab" data-toggle="pill" href="#custom-tabs-one-tax-deduction" role="tab" aria-controls="custom-tabs-one-tax-deduction" aria-selected="false"><?=$this->lang->line('tax_deduction_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-payroll-tab" data-toggle="pill" href="#custom-tabs-one-payroll" role="tab" aria-controls="custom-tabs-one-payroll" aria-selected="true"><?=$this->lang->line('payroll_list')?></a>
                </li>
              </ul>
            </div>
            <div class="card-body  m-0 p-0">
              <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-attendance" role="tabpanel" aria-labelledby="custom-tabs-one-attendance-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('attendance_employee_id')?></th>
                        <th><?=$this->lang->line('attendance_date')?></th>
                        <th><?=$this->lang->line('attendance_status')?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      
                        if(sizeof($attendances) > 0)
                        {
                          foreach ($attendances as $value) 
                          {
                      ?>
                      <tr>                        
                        <td><?=$value->employee_name;?></td>
                        <td>
                          <?=($value->attendance_date != '' && $value->attendance_date != '0000-00-00') ? date('d-m-Y',strtotime($value->attendance_date)) : ''?>
                        </td>
                      
                        
                      
                        <td>
                          <?php 
                            // Convert the attendance status to uppercase
                            $attendance_status = strtoupper($value->attendance_status);

                            // Initialize the attendance status color variable
                            $attendanceStatusColor = '';

                            // Determine the attendance status color
                            if ($attendance_status == ATTENDANCE_STATUS_PRESENT) 
                            {
                              $attendanceStatusColor = '<span style="color: green;">PRESENT</span>';
                            } 
                            elseif ($attendance_status == ATTENDANCE_STATUS_ABSENT) 
                            {
                              $attendanceStatusColor = '<span style="color: red;">ABSENT</span>';
                            } 
                            elseif ($attendance_status == ATTENDANCE_STATUS_HALF_LEAVE) 
                            {
                              $attendanceStatusColor = '<span style="color: blue;">HALF LEAVE</span>';
                            } 
                           
                            // Output the attendance status color
                            echo $attendanceStatusColor;
                          ?>
                        </td>

                      </tr>
                      <?php  
                          }
                        }
                        else
                        {
                      ?>
                      <tr>
                        <td colspan="3"><?=$this->lang->line('no_records_available')?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    
                  </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-leave" role="tabpanel" aria-labelledby="custom-tabs-one-leave-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('leave_employee_id')?></th>
                        <th><?=$this->lang->line('leave_date')?></th>
                        <th><?=$this->lang->line('leave_type')?></th>
                        <th><?=$this->lang->line('leave_status')?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      
                        if(sizeof($leaves) > 0)
                        {
                          foreach ($leaves as $value) 
                          {
                      ?>
                      <tr>                        
                        <td><?=$value->employee_name;?></td>
                        <td>
                          <?=($value->leave_date != '' && $value->leave_date != '0000-00-00') ? date('d-m-Y',strtotime($value->leave_date)) : ''?>
                        </td>
                      
                        <td>
                          <?php 

                            $leaveType = strtoupper($value->leave_type);

                            if ($leaveType == 'FULL') 
                            {
                              echo  '<span style="color: green;">' . $leaveType . '</span>';
                            } 
                            elseif ($leaveType == 'HALF') 
                            {
                              echo  '<span style="color: blue;">' . $leaveType . '</span>';
                            } 
                            elseif ($leaveType == 'QUARTER') 
                            { // Corrected spelling from 'qurter' to 'quarter'
                              echo  '<span style="color: orange;">' . $leaveType . '</span>';
                            }
                          ?>
                        </td>
                        
                        <td>
                          <?php

                            if ($value->status == 'pending') 
                            {
                                echo  '<span class="badge badge-warning">Pending</span>';
                            } 
                            elseif ($value->status == 'approved') 
                            {
                                echo  '<span class="badge badge-success">Approved</span>';
                            } 
                            elseif ($value->status == 'rejected') 
                            {
                                echo  '<span class="badge badge-danger">Rejected</span>';
                            }
                          ?>
                        </td>

                      </tr>
                      <?php  
                          }
                        }
                        else
                        {
                      ?>
                      <tr>
                        <td colspan="4"><?=$this->lang->line('no_records_available')?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    
                  </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-advance-salary" role="tabpanel" aria-labelledby="custom-tabs-one-advance-salary-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('advance_salary_employee_id')?></th>
                        <th><?=$this->lang->line('advance_salary_request_date')?></th>
                        <th><?=$this->lang->line('advance_salary_amount')?></th>
                        <th><?=$this->lang->line('advance_salary_status')?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      
                        if(sizeof($advance_salaries) > 0)
                        {
                          foreach ($advance_salaries as $value) 
                          {
                      ?>
                      <tr>                        
                        <td><?=$value->employee_name;?></td>
                        <td>
                          <?=($value->request_date != '' && $value->request_date != '0000-00-00') ? date('d-m-Y',strtotime($value->request_date)) : ''?>
                        </td>
                        <td><?=$value->amount;?></td>
                      
                        <td>
                          <?php

                            if ($value->status == 'pending') 
                            {
                                echo  '<span class="badge badge-warning">Pending</span>';
                            } 
                            elseif ($value->status == 'approved') 
                            {
                                echo  '<span class="badge badge-success">Approved</span>';
                            } 
                            elseif ($value->status == 'rejected') 
                            {
                                echo  '<span class="badge badge-danger">Rejected</span>';
                            }
                          ?>
                        </td>

                      </tr>
                      <?php  
                          }
                        }
                        else
                        {
                      ?>
                      <tr>
                        <td colspan="4"><?=$this->lang->line('no_records_available')?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    
                  </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-bonus" role="tabpanel" aria-labelledby="custom-tabs-one-bonus-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('bonus_employee_id')?></th>
                        <th><?=$this->lang->line('bonus_date')?></th>
                        <th><?=$this->lang->line('bonus_type')?></th>
                        <th><?=$this->lang->line('bonus_amount')?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      
                        if(sizeof($bonuses) > 0)
                        {
                          foreach ($bonuses as $value) 
                          {
                      ?>
                      <tr>                        
                        <td><?=$value->employee_name;?></td>
                        <td>
                          <?=($value->bonus_date != '' && $value->bonus_date != '0000-00-00') ? date('d-m-Y',strtotime($value->bonus_date)) : ''?>
                        </td>
                        <td><?=$value->bonus_type;?></td>
                        <td><?=$value->amount;?></td>
                     
                      </tr>
                      <?php  
                          }
                        }
                        else
                        {
                      ?>
                      <tr>
                        <td colspan="4"><?=$this->lang->line('no_records_available')?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    
                  </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-deduction" role="tabpanel" aria-labelledby="custom-tabs-one-deduction-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('deduction_employee_id')?></th>
                        <th><?=$this->lang->line('deduction_date')?></th>
                        <th><?=$this->lang->line('deduction_type')?></th>
                        <th><?=$this->lang->line('deduction_amount')?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      
                        if(sizeof($deductions) > 0)
                        {
                          foreach ($deductions as $value) 
                          {
                      ?>
                      <tr>                        
                        <td><?=$value->employee_name;?></td>
                        <td>
                          <?=($value->deduction_date != '' && $value->deduction_date != '0000-00-00') ? date('d-m-Y',strtotime($value->deduction_date)) : ''?>
                        </td>
                        <td><?=$value->deduction_type;?></td>
                        <td><?=$value->amount;?></td>
                     
                      </tr>
                      <?php  
                          }
                        }
                        else
                        {
                      ?>
                      <tr>
                        <td colspan="4"><?=$this->lang->line('no_records_available')?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    
                  </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-tax-deduction" role="tabpanel" aria-labelledby="custom-tabs-one-tax-deduction-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('tax_deduction_employee_id')?></th>
                        <th><?=$this->lang->line('tax_deduction_date')?></th>
                        <th><?=$this->lang->line('tax_deduction_tax_type')?></th>
                        <th><?=$this->lang->line('tax_deduction_amount')?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      
                        if(sizeof($tax_deductions) > 0)
                        {
                          foreach ($tax_deductions as $value) 
                          {
                      ?>
                      <tr>                        
                        <td><?=$value->employee_name;?></td>
                        <td>
                          <?=($value->tax_deduction_date != '' && $value->tax_deduction_date != '0000-00-00') ? date('d-m-Y',strtotime($value->tax_deduction_date)) : ''?>
                        </td>
                        <td><?=$value->tax_type;?></td>
                        <td><?=$value->amount;?></td>
                     
                      </tr>
                      <?php  
                          }
                        }
                        else
                        {
                      ?>
                      <tr>
                        <td colspan="4"><?=$this->lang->line('no_records_available')?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    
                  </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-payroll" role="tabpanel" aria-labelledby="custom-tabs-one-payroll-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('payroll_employee_id')?></th>
                        <th><?=$this->lang->line('payroll_base_salary')?></th>
                        <th><?=$this->lang->line('payroll_total_bonuses')?></th>
                        <th><?=$this->lang->line('payroll_total_deductions')?></th>
                        <th><?=$this->lang->line('payroll_total_tax')?></th>
                        <th><?=$this->lang->line('payroll_total_advance')?></th>
                        <th><?=$this->lang->line('payroll_leave_deduction_amount')?></th>
                        <th><?=$this->lang->line('payroll_total_leaves')?></th>
                        <th><?=$this->lang->line('payroll_net_amount')?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      
                        if(sizeof($payrolls) > 0)
                        {
                          foreach ($payrolls as $value) 
                          {
                      ?>
                      <tr>                        
                        <td><?=$value->employee_name;?></td>
                        <td><?=$value->base_salary;?></td>
                        <td><?=$value->total_bonuses;?></td>
                        <td><?=$value->total_deductions;?></td>
                        <td><?=$value->total_tax;?></td>
                        <td><?=$value->total_advance;?></td>
                        <td><?=number_format($value->leave_deduction_amount,2);?></td>
                        <td><?=$value->total_leaves;?></td>
                        <td><?=number_format($value->net_salary,2);?></td>
                     
                      </tr>
                      <?php  
                          }
                        }
                        else
                        {
                      ?>
                      <tr>
                        <td colspan="9"><?=$this->lang->line('no_records_available')?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    
                  </table>
                </div>


              </div>
            </div>
            <!-- /.card -->
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

<div class="modal fade" id="add_employee_modal" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      
    </div>
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="delete_employee" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>



<script type="text/javascript">
  $(document).ready(function(e){

    /*************************** Start Dynamic Employee List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    { 
      $('#example').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [], //Initial no order.
        "pageLength": 50,
 
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('employee/ajax_list')?>",
            "type": "POST",
            "data":  {
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 3 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
      });
    }

    function reinitialize(){
      $(".select2bs4").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }

    $(document).on('click', ".add_employee_modal" ,function(event){
      event.preventDefault();
      
      var employee_id = $(this).data('employee_id');
      employee_id = (employee_id === undefined) ? "" : "/"+employee_id;

      $.ajax({
        url: "<?=base_url('employee/add')?>"+employee_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_employee_modal').find('.modal-content').html(data.add_employee_modal_body);
          $('#add_employee_modal').modal('show');
          reinitialize();
          $('.datepicker').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
          });
        },
        error: function (xhr, ajaxOptions, thrownError) {
          Swal.fire({
            title: 'FAILURE !!',
            // text: xhr.status + thrownError + ajaxOptions,
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
          });
        }
      });
    });

    $(document).on('submit','#addEmployeeForm',function(e){
      
      e.preventDefault();

      $('#addEmployeeSubmit').text('Please wait...').attr('disabled','disabled');
      

      var isError = false;

      $('form#addEmployeeForm .field_validation').each(function() {
          
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#addEmployeeForm  #err_"+id).text(field+ " field is required.");
          $('form#addEmployeeForm  #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addEmployeeForm #err_"+id).text("");
          $('form#addEmployeeForm #'+id).removeClass('is-invalid');
          $('form#addEmployeeForm #'+id).addClass('is-valid');
        }
      });
      
      if(isError == true)
      {
        $('#addEmployeeSubmit').text('Save').removeAttr('disabled');
        return false;
      }
      else
      {
        var formData = $('#addEmployeeForm').serialize();
        
        var employee_id = $('#add_employee_modal').find('input[name="employee_id"]').val();
        employee_id = (employee_id == '') ? "" : "/"+employee_id;

        $.ajax({
          url: "<?php echo base_url('employee/add')?>"+employee_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_employee_modal').modal('hide');
              $('form#addEmployeeForm #addEmployeeSubmit').text('Save').removeAttr('disabled');

              Swal.fire({
                title: 'SUCCESS !!',
                text: response.message,
                icon: "success",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              initialize_datatable();
            }
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                $("form#addEmployeeForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#addEmployeeForm #addEmployeeSubmit').text('Save').removeAttr('disabled');
            }
            else
            {
              Swal.fire({
                title: 'FAILURE !!',
                text: response.message,
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              $('#addEmployeeSubmit').text('Save').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addEmployeeForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addEmployeeForm #err_"+id).text(field+ " field is required.");
          $('form#addEmployeeForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addEmployeeForm #err_"+id).text("");
          $('form#addEmployeeForm #'+id).removeClass('is-invalid');
          $('form#addEmployeeForm #'+id).addClass('is-valid');
        }
    });


    $(document).on('show.bs.modal','#delete_employee', function (e) {
      var employee_id = $(e.relatedTarget).data('employee_id');
      $('#delete_employee').find('#id').val(employee_id);

      // alert(employee_id);

      $.ajax({
        url: "<?php echo base_url('employee/employee_delete_confirmation')?>",
        type: "POST",
        data:{
          'employee_id': employee_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_employee').find('.modal-content').html(data.employee_delete_modal_body);
        }
      });


    });

    $(document).on('submit', '#deleteEmployeeForm' ,function (e) {
      e.preventDefault();

      $('#deleteEmployeeSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteEmployeeForm').serialize();

      $.ajax({
      url: "<?php echo base_url('employee/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#delete_employee').modal('hide');
            $('form#deleteEmployeeForm #deleteEmployeeSubmit').text('Delete').removeAttr('disabled');
            initialize_datatable();

            Swal.fire({
              title: 'SUCCESS !!',
                text: response.message,
                icon: "success",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              initialize_datatable();
            
          }
          else
          {
            
            Swal.fire({
              title: 'FAILURE !!',
                text: response.message,
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              initialize_datatable();        
          }

          $('#deleteEmployeeSubmit').text('Delete').removeAttr('disabled');
        }
      });
    });

    /*************************** End Dynamic Employyee List with Datatables ****************************/

    
  });
</script>