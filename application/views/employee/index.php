<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('employee')?>"><?=$this->lang->line('header_hrmodule')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('employee')?>"><?=$this->lang->line('header_employee')?></a></li>            
            <li class="breadcrumb-item active"><?=$this->lang->line('employee_list')?></li>
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
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('employee_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <?php 
                    if($this->permission_model->has_permission('import_product'))
                    {
                  ?>

                      <li class="nav-item  ml-2">
                        <a class="nav-link import_employee_modal btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Import Employee in Bulk using CSV file">
                          <i class="fas fa-file-import"></i> Import Employees
                        </a>
                      </li>

                  <?php
                    }
                  ?>
                  <li class="nav-item  ml-2">
                    <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Employee">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>

                  <?php 
                    if($this->permission_model->has_permission('add_employee'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    
                    <button type="button" class="btn btn-primary add_employee_modal">
                      <i class="fas fa-user mr-2"></i><?=$this->lang->line('employee_add')?>
                    </button>
                  </li>
                  <?php 
                    }
                  ?>
                </ul>
              </div>
             
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="2%"><input type="checkbox" class="all_employee"></th>
                    <th><?=$this->lang->line('employee_first_name')?></th>
                    <th><?=$this->lang->line('employee_last_name')?></th>
                    <th><?=$this->lang->line('employee_code')?></th>
                    <th><?=$this->lang->line('employee_email')?></th>
                    <th><?=$this->lang->line('employee_department_id')?></th>
                    <th><?=$this->lang->line('employee_position_id')?></th>
                    <th><?=$this->lang->line('employee_hire_date')?></th>
                    <th><?=$this->lang->line('employee_salary')?></th>
                    <th><?=$this->lang->line('employee_action')?></th>
                  </tr>
                </thead>
                
                <tbody id="employee_list">
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('employee_first_name')?></th>
                    <th><?=$this->lang->line('employee_last_name')?></th>
                    <th><?=$this->lang->line('employee_code')?></th>
                    <th><?=$this->lang->line('employee_email')?></th>
                    <th><?=$this->lang->line('employee_department_id')?></th>
                    <th><?=$this->lang->line('employee_position_id')?></th>
                    <th><?=$this->lang->line('employee_hire_date')?></th>
                    <th><?=$this->lang->line('employee_salary')?></th>
                    <th><?=$this->lang->line('employee_action')?></th>
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


<div class="modal fade" id="add_employee_modal" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
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

<div class="example-modal">
  <div class="modal fade" id="import_employee_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="add_department_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="add_position_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>


<?php $this->load->view('layout/footer');?>




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
            "targets": [ 0,9 ], //first column / numbering column
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
    $(document).on("blur change keyup input", "#ifsc_code", function (event) {
        var value = $(this).val();
        
        // Remove non-alphanumeric characters and convert to uppercase
        var transformedValue = value.replace(/[^a-z0-9]/gi, '').toUpperCase();
        
        // Set the transformed value back to the input
        $(this).val(transformedValue);

        // Validate the input
        var id = $(this).attr('id');
        var field = $(this).attr('placeholder');

        if (transformedValue == null || transformedValue == "") {
            $("form#addEmployeeForm #err_" + id).text(field + " field is required.");
            $('form#addEmployeeForm #' + id).addClass('is-invalid').removeClass('is-valid');
        } else {
            $("form#addEmployeeForm #err_" + id).text("");
            $('form#addEmployeeForm #' + id).removeClass('is-invalid').addClass('is-valid');
        }
    });

    // Form submission validation
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
            } else {
                $("form#addEmployeeForm #err_"+id).text("");
                $('form#addEmployeeForm #'+id).removeClass('is-invalid').addClass('is-valid');
            }
        });

        if(isError == true) {
            $('#addEmployeeSubmit').text('Save').removeAttr('disabled');
            return false;
        } else {
            var formData = $('#addEmployeeForm').serialize();
            var employee_id = $('#add_employee_modal').find('input[name="employee_id"]').val();
            employee_id = (employee_id == '') ? "" : "/"+employee_id;

            $.ajax({
                url: "<?php echo base_url('employee/add')?>"+employee_id,
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(response){
                    if(response.code==1) { 
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
                    } else if(response.code == 2) {
                        $.each(response.errors, function(key, value) {
                            $("form#addEmployeeForm  #err_"+key).text(value);
                            $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
                        });
                        $('form#addEmployeeForm #addEmployeeSubmit').text('Save').removeAttr('disabled');
                    } else {
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
        } else {
            $("form#addEmployeeForm #err_"+id).text("");
            $('form#addEmployeeForm #'+id).removeClass('is-invalid').addClass('is-valid');
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

    $(document).on('click', ".add_department_modal" ,function(){

      $.ajax({
        url: "<?php echo base_url('department/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_department_modal').find('.modal-content').html(data.add_department_modal_body);
          $('#add_department_modal').modal('show');
          reinitialize();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','#addDepartmentForm',function(e){

      e.preventDefault();

      $('#addDepartmentSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addDepartmentForm').serialize();

      var isError = false;

      $('form#addDepartmentForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addDepartmentForm  #err_"+id).text(field+ " field is required.");
            $('form#addDepartmentForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addDepartmentForm #err_"+id).text("");
            $('form#addDepartmentForm #'+id).removeClass('is-invalid');
            $('form#addDepartmentForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addDepartmentSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('department/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_department_modal').modal('hide');
              $('form#addDepartmentForm #addDepartmentSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              /* initialize_datatable();*/
              if($('form#addEmployeeForm #department_id').length)
              {
                $('form#addEmployeeForm #department_id').html('');
                $('form#addEmployeeForm #department_id').append('<option value="">Select</option>');
                
                for(i=0;i<response['departments'].length;i++)
                { 
                  $('form#addEmployeeForm #department_id').append('<option value="' + response['departments'][i].department_id + '">' + response['departments'][i].department_name +'</option>');
                }

                $('form#addEmployeeForm #department_id').val(response['id']).attr("selected","selected");


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
              }
              else
              {
                // show_message('success-header',response.message);
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

                location.reload(true);
              }
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
              $('#addDepartmentSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }

    });


    $(document).on('click', ".add_position_modal" ,function(){

      $.ajax({
        url: "<?php echo base_url('position/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_position_modal').find('.modal-content').html(data.add_position_modal_body);
          $('#add_position_modal').modal('show');
          reinitialize();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','#addPositionForm',function(e){

      e.preventDefault();

      $('#addPositionSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addPositionForm').serialize();

      var isError = false;

      $('form#addPositionForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addPositionForm  #err_"+id).text(field+ " field is required.");
            $('form#addPositionForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addPositionForm #err_"+id).text("");
            $('form#addPositionForm #'+id).removeClass('is-invalid');
            $('form#addPositionForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addPositionSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('position/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_position_modal').modal('hide');
              $('form#addPositionForm #addPositionSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              /* initialize_datatable();*/
              if($('form#addEmployeeForm #position_id').length)
              {
                $('form#addEmployeeForm #position_id').html('');
                $('form#addEmployeeForm #position_id').append('<option value="">Select</option>');
                
                for(i=0;i<response['positions'].length;i++)
                { 
                  $('form#addEmployeeForm #position_id').append('<option value="' + response['positions'][i].position_id + '">' + response['positions'][i].position_title +'</option>');
                }

                $('form#addEmployeeForm #position_id').val(response['id']).attr("selected","selected");


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
              }
              else
              {
                // show_message('success-header',response.message);
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

                location.reload(true);
              }
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
              $('#addDepartmentSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }

    });

    /*************************** End Dynamic Employyee List with Datatables ****************************/

    $(document).on('change', '.all_employee', function() {
      if(this.checked == true)
        $('.single_employee').prop('checked',true);
      else
        $('.single_employee').prop('checked',false);
    });

    $(document).on('change', '.single_employee', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#employee_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_employee          = $('.single_employee').length;
      var total_checked_single_employee  = $('.single_employee:checked').length;
      
      if(total_checked_single_employee < total_single_employee && total_checked_single_employee > 0){
        $('.all_employee').prop('indeterminate',true); 
      }
      else if(total_checked_single_employee == total_single_employee){
        $('.all_employee').prop('indeterminate',false);
        $('.all_employee').prop('checked',true);
      }
      else if(total_checked_single_employee == 0){
        $('.all_employee').prop('indeterminate',false);
        $('.all_employee').prop('checked',false);
      }
    }

    $(document).on('click','#employee_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_employee:checked'); // Select only checked checkboxes
     
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); // Assuming the value holds the employee_id
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
            title: 'FAILURE !!',
              title:'Warning',
              text: "Please select at least one employee record to export.",
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
      var exportUrl = '<?= base_url('employee/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",");

      window.location.href = exportUrl;
    });

      /* Import employee using CSV function Begin */

    $(document).on('click', ".import_employee_modal" ,function(event){
      event.preventDefault();

      $.ajax({
        url: "<?php echo base_url('employee/import_employee')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#import_employee_modal').find('.modal-content').html(data.import_employee_modal_body);
          $('#import_employee_modal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // alert(xhr.status);
          show_message('failure-header',thrownError);
          // alert(thrownError);
          // alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','form#importEmployeeForm',function(event){
      // event.preventDefault();

      $('form#importEmployeeForm #importEmployeeSUbmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('form#importEmployeeForm').serialize();

      var isError = false;

      $('form#importEmployeeForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#importEmployeeForm  #err_"+id).text(field+ " field is required.");
            $('form#importEmployeeForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#importEmployeeForm #err_"+id).text("");
            $('form#importEmployeeForm #'+id).removeClass('is-invalid');
            $('form#importEmployeeForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('form#importEmployeeForm #importEmployeeSUbmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        return true;
      }
    });

    /* Import eployee using CSV function End */

    
  });
</script>