<?php $this->load->view('layout/header');?>
<style>

  .custom-modal-lg {
      max-width: 80%; /* Adjust the percentage to your desired width */
  }

  .download-btn {
    float: left;
    margin-right: 5px;
  }

  .payslip-box {
      border: 1px solid #ddd;
      padding: 20px;
      margin: 20px;
      border-radius: 10px;
  }
  .payslip-header {
      text-align: center;
      margin-bottom: 20px;
  }
  .payslip-header h1 {
      font-size: 24px;
      margin: 0;
  }
  .payslip-header h2 {
      font-size: 18px;
      margin: 0;
  }
  .payslip-table {
      width: 100%;
      margin-bottom: 20px;
  }
  .payslip-table th, .payslip-table td {
      padding: 10px;
      text-align: left;
      border: 1px solid #ddd;
  }
  .payslip-footer {
      text-align: right;
      margin-top: 20px;
  }
  .payslip-footer h3 {
      margin: 0;
  }

</style>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('employee')?>"><?=$this->lang->line('header_hrmodule')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_payrolls')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('payroll_list')?></li>
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
            
            <div class="card-body">
              <div class="row">
                 
                  
                  <div class="col-md-3">
                    <label>Employee</label>
                    <select class="form-control form-control-sm select2bs4" id="employee_id" name="employee_id">
                        <option value="">All</option>
                        <?php 
                          foreach ($employees as $value) {
                        ?>
                            <option value="<?=$value->employee_id?>"><?=$value->first_name.' '.$value->last_name?></option>
                        <?php
                          }
                        ?>
                      </select>
                  </div>

                  <div class="col-md-3">
                    <label>Month</label>
                    <select class="form-control form-control-sm select2bs4" id="month">
                      <option value="">All</option>
                      <option value="1">January</option>
                      <option value="2">February</option>
                      <option value="3">March</option>
                      <option value="4">April</option>
                      <option value="5">May</option>
                      <option value="6">June</option>
                      <option value="7">July</option>
                      <option value="8">August</option>
                      <option value="9">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                   
                    </select>
                  </div>

                  <div class="col-md-3">
                    <label>Year</label>
                    <select class="form-control form-control-sm select2bs4" id="year">
                      
                    </select>
                  </div>

                
                

                </div>
            </div>
            <!-- /.card-body -->
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('payroll_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item ml-2">
                    <a class="nav-link bulk_payroll active" href="" data-tt="tooltip" title="Click here create payroll in bulk."><i class="fab fa-ravelry mr-2"></i>Bulk Payroll</a>
                  </li>

                  <li class="nav-item  ml-2">
                    <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Payroll">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>
                </ul>
              </div>
             
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="2%"><input type="checkbox" class="all_payroll"></th>
                    <th><?=$this->lang->line('payroll_employee_name')?></th>
                    <th><?=$this->lang->line('payroll_base_salary')?></th>
                    <th><?=$this->lang->line('payroll_total_bonuses')?></th>
                    <th><?=$this->lang->line('payroll_total_deductions')?></th>
                    <th><?=$this->lang->line('payroll_total_tax')?></th>
                    <th><?=$this->lang->line('payroll_total_advance')?></th>
                    <th><?=$this->lang->line('payroll_leave_deduction_amount')?></th>
                    <th><?=$this->lang->line('payroll_total_leaves')?></th>
                    <th><?=$this->lang->line('payroll_net_amount')?></th>
                    <th width="15%"><?=$this->lang->line('payroll_action')?></th>
                    
                    
                  </tr>
                </thead>
                
                <tbody id="payroll_list">
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('payroll_employee_name')?></th>
                    <th><?=$this->lang->line('payroll_base_salary')?></th>
                    <th><?=$this->lang->line('payroll_total_bonuses')?></th>
                    <th><?=$this->lang->line('payroll_total_deductions')?></th>
                    <th><?=$this->lang->line('payroll_total_tax')?></th>
                    <th><?=$this->lang->line('payroll_total_advance')?></th>
                    <th><?=$this->lang->line('payroll_leave_deduction_amount')?></th>
                    <th><?=$this->lang->line('payroll_total_leaves')?></th>
                    <th><?=$this->lang->line('payroll_net_amount')?></th>
                    <th><?=$this->lang->line('payroll_action')?></th>
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

<!-- <div class="modal fade" id="add_payroll_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      
    </div>
  </div>
</div> -->


<div class="example-modal"> 
  <div class="modal fade" id="view_payroll_modal" data-backdrop="static">
    <div class="modal-dialog custom-modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="create_payroll">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="delete_payroll" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="bulk_payroll_modal">
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

    initialize_datatable();

    function initialize_datatable()
    { 

      var employee_id   = $('#employee_id').val();
      var month               = $('#month').val();
      var year                = $('#year').val();

      $('#example').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [], //Initial no order.
        "pageLength": 50,
 
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('payroll/ajax_list')?>",
            "type": "POST",
            "data":  {
              'employee_id' : employee_id,
              'month': month,
              'year': year,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 0,10 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
      });
    }

    function reinitialize(){
      $(".select2bs4").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }


    var currentYear = new Date().getFullYear();
    var currentMonth = new Date().getMonth() + 1; // Months are zero-based in JavaScript
    var startYear = 1991;

    // Populate year dropdown
    for (var year = currentYear; year >= startYear; year--) {
        $('#year').append(new Option(year, year));
    }

    // Set current year and month as selected
    $('#year').val(currentYear).trigger('change');
    $('#month').val(currentMonth).trigger('change');

    /*************************** Start Dynamic payroll List with Datatables **************************/

   

    $(document).on('change','#employee_id, #month, #year',function(e){
      initialize_datatable();
    })

    $(document).on('shown.bs.modal','#view_payroll_modal', function (e) {
      var employee_id = $(e.relatedTarget).data('employee_id');

      $.ajax({
        url: "<?php echo base_url('payroll/view')?>/"+employee_id,
        type: "GET",
        // data:{
        
        //   '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        // },
        dataType: "JSON",
        success: function(data){
          $('#view_payroll_modal').find('.modal-content').html(data.view_payroll_modal_body);
        }
      });

    });

    // $(document).on('show.bs.modal','#create_payroll', function (e) {
    //   var employee_id = $(e.relatedTarget).data('employee_id');
    //   $('#create_payroll').find('#employee_id').val(employee_id);

    //   $.ajax({
    //     url: "<?php echo base_url('payroll/create_payroll_confirmation')?>",
    //     type: "POST",
    //     data:{
    //       'employee_id': employee_id,
    //       '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
    //     },
    //     dataType: "JSON",
    //     success: function(data){
    //       $('#create_payroll').find('.modal-content').html(data.create_payroll_modal_body);
    //       $('#create_payroll').modal('show');
    //       reinitialize();
    //     }
    //   });
    // });

    $(document).on('click', ".create_payroll" ,function(event){
      event.preventDefault();
      
      var employee_id = $(this).data('employee_id');
      employee_id = (employee_id === undefined) ? "" : "/"+employee_id;

      $.ajax({
        url: "<?=base_url('payroll/create_payroll_confirmation')?>"+employee_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#create_payroll').find('.modal-content').html(data.create_payroll_modal_body);
          $('#create_payroll').modal('show');
          reinitialize();
        
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


    $(document).on('submit', '#createPayrollForm' ,function (e) {
      e.preventDefault();

      $('#createPayrollSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#createPayrollForm').serialize();

      var employee_id = $('#create_payroll').find('input[name="employee_id"]').val();
      employee_id = (employee_id == '') ? "" : "/"+employee_id;

      $.ajax({
      url: "<?php echo base_url('payroll/create_payroll')?>"+employee_id,
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#create_payroll').modal('hide');
            $('form#createPayrollForm #createPayrollSubmit').text('Submit').removeAttr('disabled');

            // Remove modal backdrop
            $('.modal-backdrop').remove();
            
            Swal.fire({
              text: response.message,
              title: 'SUCCESS !!',
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
                text: response.message,
                title: 'FAILURE !!',
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              initialize_datatable();        
          }

          $('#createPayrollSubmit').text('Submit').removeAttr('disabled');
        }
      });
    });

    $(document).on('click', ".bulk_payroll", function(event){
        event.preventDefault();

        var checkboxes = $('.single_payroll');
        var checkedNames = [];

        checkboxes.each(function() {
            if ($(this).is(':checked')) {
                checkedNames.push($(this).data('employee_id'));
            }
        });

        if (checkedNames.length) {
            $.ajax({
                url: '<?= base_url('payroll/bulk_payroll_form'); ?>',
                type: "GET",
                dataType: "JSON",
                data: {
                    employee_ids: checkedNames.join("-"),
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                },
                success: function(data){
                    // $('#bulk_payroll_modal').find('.modal-body').html(data.bulk_payroll_modal_body);
                    // $('#bulk_payroll_modal').modal('show');

                    $('#bulk_payroll_modal').find('.modal-content').html(data.bulk_payroll_modal_body);
                    $('#bulk_payroll_modal').modal('show');
                    $('#bulkPayrollForm').find('input[name="employee_ids"]').val(checkedNames.join("-"));

                    // Append selected employees to the modal
                    // var selectedEmployeesList = $('#selectedEmployeesList');
                    // selectedEmployeesList.empty();
                    // checkboxes.each(function() {
                    //     if ($(this).is(':checked')) {
                    //         selectedEmployeesList.append('<li>' + $(this).data('employee_name') + '</li>');
                    //     }
                    // });

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    Swal.fire({
                        title: 'FAILURE !!',
                        icon: "error",
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        } else {
            Swal.fire({
              title: 'FAILURE !!',
                text: "Please select at least one employee",
                icon: "warning",
                confirmButtonText: "Ok, got it!",
                timer: 3000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        }
    });

    $(document).on('submit', '#bulkPayrollForm', function(e) {
      e.preventDefault();

      $('#bulkCreatePayrollSubmit').text('Please Wait...').attr('disabled', 'disabled');
      var formData = $(this).serialize();

      $.ajax({
          url: "<?= base_url('payroll/create_payroll') ?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response) {
              if (response.code == 1) {
                  Swal.fire({
                      text: response.message,
                      title: 'SUCCESS !!',
                      buttonsStyling: false,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                          confirmButton: "btn btn-primary"
                      },
                      timer: 1000
                  });
                  $('#bulk_payroll_modal').modal('hide');
                  initialize_datatable();
              } else {
                  Swal.fire({
                      text: response.message,
                      title: 'FAILURE !!',
                      buttonsStyling: false,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                          confirmButton: "btn btn-primary"
                      },
                      timer: 1000
                  });
              }
              $('#bulkCreatePayrollSubmit').text('Submit').removeAttr('disabled');
          },
          error: function(xhr, ajaxOptions, thrownError) {
              Swal.fire({
                title: 'FAILURE !!',
                  text: xhr.status + " " + thrownError,
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                      confirmButton: "btn btn-primary"
                  }
              });
              $('#bulkCreatePayrollSubmit').text('Create Bulk Payroll').removeAttr('disabled');
          }
      });
    });


    $(document).on('show.bs.modal','#delete_payroll', function (e) {
      var payroll_history_id = $(e.relatedTarget).data('payroll_history_id');
      $('#delete_payroll').find('#id').val(payroll_history_id);

      // alert(payroll_history_id);

      $.ajax({
        url: "<?php echo base_url('payroll/payroll_delete_confirmation')?>",
        type: "POST",
        data:{
          'payroll_history_id': payroll_history_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_payroll').find('.modal-content').html(data.payroll_delete_modal_body);
        }
      });


    });

    $(document).on('submit', '#deletePayrollForm' ,function (e) {
      e.preventDefault();

      $('#deletePayrollSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deletePayrollForm').serialize();

      $.ajax({
      url: "<?php echo base_url('payroll/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#delete_payroll').modal('hide');
            $('form#deletePayrollForm #deletePayrollSubmit').text('Delete').removeAttr('disabled');
             // Remove modal backdrop
             $('.modal-backdrop').remove();
            

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

          $('#deletePayrollSubmit').text('Delete').removeAttr('disabled');
        }
      });
    });



    // $(document).on('change', 'form#addPayrollForm #employee_id',function() {
    //   // alert();
    //   var selectedSalary = $(this).find('option:selected').data('salary');
    //   $('#gross_amount').val(selectedSalary ? selectedSalary : '');
    //   $('#net_amount').val(selectedSalary ? selectedSalary : '');
    // });

    // $(document).on('click', ".add_payroll_modal" ,function(event){
    //   event.preventDefault();
      
    //   var payroll_id = $(this).data('payroll_id');
    //   payroll_id = (payroll_id === undefined) ? "" : "/"+payroll_id;

    //   $.ajax({
    //     url: "<?=base_url('payroll/add')?>"+payroll_id,
    //     type: "GET",
    //     dataType: "JSON",
    //     success: function(data){
    //       $('#add_payroll_modal').find('.modal-content').html(data.add_payroll_modal_body);
    //       $('#add_payroll_modal').modal('show');
    //       reinitialize();
    //       $('.datepicker').datepicker({
    //           weekStart: 1,
    //           daysOfWeekHighlighted: "6,0",
    //           autoclose: true,
    //           todayHighlight: true,
    //           format: 'dd-mm-yyyy'
    //       });
    //     },
    //     error: function (xhr, ajaxOptions, thrownError) {
    //       Swal.fire({
    //         // text: xhr.status + thrownError + ajaxOptions,
    //         icon: "error",
    //         buttonsStyling: !1,
    //         confirmButtonText: "Ok, got it!",
    //         customClass: {
    //             confirmButton: "btn btn-primary"
    //         }
    //       });
    //     }
    //   });
    // });

    // $(document).on('submit','#addPayrollForm',function(e){
      
    //   e.preventDefault();

    //   $('#addPayrollSubmit').text('Please wait...').attr('disabled','disabled');
      

    //   var isError = false;

    //   $('form#addPayrollForm .field_validation').each(function() {
          
    //     var id    = $(this).attr('id');
    //     var value = $(this).val();
    //     var field = $(this).attr('placeholder');

    //     if(value==null || value==""){
    //       $("form#addPayrollForm  #err_"+id).text(field+ " field is required.");
    //       $('form#addPayrollForm  #'+id).addClass('is-invalid');
    //       isError = true;
    //     }
    //     else
    //     {
    //       $("form#addPayrollForm #err_"+id).text("");
    //       $('form#addPayrollForm #'+id).removeClass('is-invalid');
    //       $('form#addPayrollForm #'+id).addClass('is-valid');
    //     }
    //   });
      
    //   if(isError == true)
    //   {
    //     $('#addPayrollSubmit').text('Save').removeAttr('disabled');
    //     return false;
    //   }
    //   else
    //   {
    //     var formData = $('#addPayrollForm').serialize();
        
    //     var payroll_id = $('#add_payroll_modal').find('input[name="payroll_id"]').val();
    //     payroll_id = (payroll_id == '') ? "" : "/"+payroll_id;

    //     $.ajax({
    //       url: "<?php echo base_url('payroll/add')?>"+payroll_id,
    //       type: "POST",
    //       data: formData,
    //       dataType: "JSON",
    //       success: function(response){
    //         if(response.code==1)
    //         { 
    //           $('#add_payroll_modal').modal('hide');
    //           $('form#addPayrollForm #addPayrollSubmit').text('Save').removeAttr('disabled');

    //           Swal.fire({
    //             text: response.message,
    //             icon: "success",
    //             buttonsStyling: !1,
    //             confirmButtonText: "Ok, got it!",
    //             customClass: {
    //                 confirmButton: "btn btn-primary"
    //             },
    //             timer:1000
    //           });
    //           initialize_datatable();
    //         }
    //         else if(response.code == 2)
    //         {
    //           $.each(response.errors, function(key, value) {
    //             $("form#addPayrollForm  #err_"+key).text(value);
    //             $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
    //           });
    //           $('form#addPayrollForm #addPayrollSubmit').text('Save').removeAttr('disabled');
    //         }
    //         else
    //         {
    //           Swal.fire({
    //             text: response.message,
    //             icon: "error",
    //             buttonsStyling: !1,
    //             confirmButtonText: "Ok, got it!",
    //             customClass: {
    //                 confirmButton: "btn btn-primary"
    //             },
    //             timer:1000
    //           });
    //           $('#addPayrollSubmit').text('Save').removeAttr('disabled');
    //         }
    //       }
    //     });
    //   }
    // });

    // $(document).on("blur change keyup", "form#addPayrollForm  .field_validation", function (event){
    //     var id    = $(this).attr('id');
    //     var value = $(this).val();
    //     var field = $(this).attr('placeholder');
        
    //     if(value==null || value==""){
    //       $("form#addPayrollForm #err_"+id).text(field+ " field is required.");
    //       return false;
    //     }
    //     else{
    //       $("form#addPayrollForm #err_"+id).text("");
    //     }
    // });


    // $(document).on('show.bs.modal','#delete_payroll', function (e) {
    //   var payroll_id = $(e.relatedTarget).data('payroll_id');
    //   $('#delete_payroll').find('#id').val(payroll_id);

    //   // alert(payroll_id);

    //   $.ajax({
    //     url: "<?php echo base_url('payroll/payroll_delete_confirmation')?>",
    //     type: "POST",
    //     data:{
    //       'payroll_id': payroll_id,
    //       '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
    //     },
    //     dataType: "JSON",
    //     success: function(data){
    //       $('#delete_payroll').find('.modal-content').html(data.payroll_delete_modal_body);
    //     }
    //   });


    // });

    // $(document).on('submit', '#deletePayrollForm' ,function (e) {
    //   e.preventDefault();

    //   $('#deletePayrollSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
    //   var formData = $('#deletePayrollForm').serialize();

    //   $.ajax({
    //   url: "<?php echo base_url('payroll/delete')?>",
    //     type: "POST",
    //     data: formData,
    //     dataType: "JSON",
    //     success: function(response){
    //       if(response.code==1)
    //       { 
           
    //         $('#delete_payroll').modal('hide');
    //         $('form#deletePayrollForm #deletePayrollSubmit').text('Delete').removeAttr('disabled');
    //         initialize_datatable();

    //         Swal.fire({
    //             text: response.message,
    //             icon: "success",
    //             buttonsStyling: !1,
    //             confirmButtonText: "Ok, got it!",
    //             customClass: {
    //                 confirmButton: "btn btn-primary"
    //             },
    //             timer:1000
    //           });
    //           initialize_datatable();
            
    //       }
    //       else
    //       {
            
    //         Swal.fire({
    //             text: response.message,
    //             icon: "error",
    //             buttonsStyling: !1,
    //             confirmButtonText: "Ok, got it!",
    //             customClass: {
    //                 confirmButton: "btn btn-primary"
    //             },
    //             timer:1000
    //           });
    //           initialize_datatable();        
    //       }

    //       $('#deletePayrollSubmit').text('Delete').removeAttr('disabled');
    //     }
    //   });
    // });

    /*************************** End Dynamic Employyee List with Datatables ****************************/

    $(document).on('change', '.all_payroll', function() {
      if(this.checked == true)
        $('.single_payroll').prop('checked',true);
      else
        $('.single_payroll').prop('checked',false);
    });

    $(document).on('change', '.single_payroll', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#payroll_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_payroll          = $('.single_payroll').length;
      var total_checked_single_payroll  = $('.single_payroll:checked').length;
      
      if(total_checked_single_payroll < total_single_payroll && total_checked_single_payroll > 0){
        $('.all_payroll').prop('indeterminate',true); 
      }
      else if(total_checked_single_payroll == total_single_payroll){
        $('.all_payroll').prop('indeterminate',false);
        $('.all_payroll').prop('checked',true);
      }
      else if(total_checked_single_payroll == 0){
        $('.all_payroll').prop('indeterminate',false);
        $('.all_payroll').prop('checked',false);
      }
    }

    $(document).on('click','#payroll_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_payroll:checked'); // Select only checked checkboxes
      var employee_id = $('#employee_id').val();
      var month = $('#month').val();
      var year = $('#year').val();
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); // Assuming the value holds the payroll_id
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
            title: 'FAILURE !!',
              text: "Please select at least one payroll record to export.",
              icon: "warning",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                  confirmButton: "btn btn-primary"
              }
          });
          return; // Stop further execution
      }

      // Redirect to export URL with selected parameters
      var exportUrl = '<?= base_url('payroll/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",") +
                  "&employee_id=" + employee_id +
                  "&month=" + month +
                  "&year=" + year;

      window.location.href = exportUrl;
    });

    
  });
</script>