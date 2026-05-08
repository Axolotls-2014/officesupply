<?php $this->load->view('layout/header');?>

<style>
  .custom-modal-lg {
      max-width: 80%; /* Adjust the percentage to your desired width */
  }
  .calendar_view {
    margin-top: 10px;
    margin-bottom: 10px;
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
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_attendance')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('attendance_list')?></li>
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

                  <div class="col-md-3">
                    <label>Status</label>
                    <select class="form-control form-control-sm select2bs4" id="attendance_status">
                      <option value="">All</option>
                      <option value="<?=ATTENDANCE_STATUS_PRESENT?>">Present</option>
                      <option value="<?=ATTENDANCE_STATUS_ABSENT?>">Absent</option>
                      <option value="<?=ATTENDANCE_STATUS_HALF_LEAVE?>">Half Leave</option>
                      <option value="<?=ATTENDANCE_STATUS_ONE_FOURTH_LEAVE?>">One-fourth Leave</option>
                    </select>
                  </div>

                
                

                </div>
            </div>
            <!-- /.card-body -->
          </div>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('attendance_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item  ml-2">
                    <a class="nav-link bulk_attendance_modal btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Add Attendance in Bulk">
                      <i class="fas fa-file-import"></i> Bulk Attendance
                    </a>
                  </li>

                  <li class="nav-item  ml-2">
                    <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Attendance">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>
                    
                  <?php 
                    if($this->permission_model->has_permission('add_attendance'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    
                    <button type="button" class="btn btn-primary add_attendance_modal">
                      <i class="fas fa-filter mr-2"></i><?=$this->lang->line('attendance_add')?>
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
                    <th width="2%"><input type="checkbox" class="all_attendance"></th>
                    <th><?=$this->lang->line('attendance_employee_id')?></th>
                    <th><?=$this->lang->line('attendance_date')?></th>
                    <th><?=$this->lang->line('attendance_status')?></th>
                    <th><?=$this->lang->line('attendance_action')?></th>
                  </tr>
                </thead>
                
                <tbody id="attendance_list">
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('attendance_employee_id')?></th>
                    <th><?=$this->lang->line('attendance_date')?></th>
                    <th><?=$this->lang->line('attendance_status')?></th>
                    <th><?=$this->lang->line('attendance_action')?></th>
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

<div class="modal fade" id="add_attendance_modal" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      
    </div>
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="delete_attendance" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="bulk_attendance_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>



<script type="text/javascript">
  $(document).ready(function(e){

   
    

    var currentyear = new Date().getFullYear();
    var currentmonth = new Date().getMonth() + 1; // Months are zero-based in JavaScript
    var startYear = 1991;

    // Populate year dropdown
    for (var year = currentyear; year >= startYear; year--) {
        $('#year').append(new Option(year, year));
    }

    // Set current year and month as selected
    $('#year').val(currentyear).trigger('change');
    $('#month').val(currentmonth).trigger('change');

    /*************************** Start Dynamic attendance List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    { 
      var employee_id         = $('#employee_id').val();
      var attendance_status   = $('#attendance_status').val();
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
          "url": "<?php echo site_url('attendance/ajax_list')?>",
            "type": "POST",
            "data":  {
              'employee_id' : employee_id,
              'attendance_status' : attendance_status,
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
            "targets": [0, 4], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
      });
    }

    $(document).on('change','#employee_id, #attendance_status, #month, #year',function(e){
      initialize_datatable();
    })

    function reinitialize(){
      $(".select2bs4").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }

    $(document).on('click', ".bulk_attendance_modal" ,function(event){
      event.preventDefault();

      $.ajax({
        url: "<?php echo base_url('attendance/bulk_attendance')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#bulk_attendance_modal').find('.modal-content').html(data.bulk_attendance_modal_body);
          $(".select2bs4").select2({theme:'bootstrap4'});
          $('#bulk_attendance_modal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // alert(xhr.status);
          show_message('failure-header',thrownError);
          // alert(thrownError);
          // alert(ajaxOptions);
        }
      });
    });

    $(document).on('click', ".add_attendance_modal" ,function(event){
      event.preventDefault();
      
      var attendance_id = $(this).data('attendance_id');
      attendance_id = (attendance_id === undefined) ? "" : "/"+attendance_id;

      $.ajax({
        url: "<?=base_url('attendance/add')?>"+attendance_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_attendance_modal').find('.modal-content').html(data.add_attendance_modal_body);
          $('#add_attendance_modal').modal('show');
          reinitialize();
          $('.datepicker').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy',
              endDate: new Date() // This line will disable all future dates
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

    $(document).on('submit','#addAttendanceForm',function(e){
      
      e.preventDefault();

      $('#addAttendanceSubmit').text('Please wait...').attr('disabled','disabled');
      

      var isError = false;

      $('form#addAttendanceForm .field_validation').each(function() {
          
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#addAttendanceForm  #err_"+id).text(field+ " field is required.");
          $('form#addAttendanceForm  #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addAttendanceForm #err_"+id).text("");
          $('form#addAttendanceForm #'+id).removeClass('is-invalid');
          $('form#addAttendanceForm #'+id).addClass('is-valid');
        }
      });
      
      if(isError == true)
      {
        $('#addAttendanceSubmit').text('Save').removeAttr('disabled');
        return false;
      }
      else
      {
        var formData = $('#addAttendanceForm').serialize();
        
        var attendance_id = $('#add_attendance_modal').find('input[name="attendance_id"]').val();
        attendance_id = (attendance_id == '') ? "" : "/"+attendance_id;

        $.ajax({
          url: "<?php echo base_url('attendance/add')?>"+attendance_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_attendance_modal').modal('hide');
              $('form#addAttendanceForm #addAttendanceSubmit').text('Save').removeAttr('disabled');

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
                  $("#err_" + key).text(value).addClass('invalid-feedback').show();
                  $('#' + key).addClass('is-invalid');
              });
              $('#addAttendanceSubmit').text('Save').removeAttr('disabled');
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
              $('#addAttendanceSubmit').text('Save').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addAttendanceForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addAttendanceForm #err_"+id).text(field+ " field is required.");
          $('form#addAttendanceForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addAttendanceForm #err_"+id).text("");
          $('form#addAttendanceForm #'+id).removeClass('is-invalid');
          $('form#addAttendanceForm #'+id).addClass('is-valid');
        }
    });

    $(document).on('hidden.bs.modal','#bulk_attendance_modal', function (e) {
      initialize_datatable();
    });

    $(document).on('show.bs.modal','#delete_attendance', function (e) {
      var attendance_id = $(e.relatedTarget).data('attendance_id');
      $('#delete_attendance').find('#id').val(attendance_id);

      // alert(attendance_id);

      $.ajax({
        url: "<?php echo base_url('attendance/attendance_delete_confirmation')?>",
        type: "POST",
        data:{
          'attendance_id': attendance_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_attendance').find('.modal-content').html(data.attendance_delete_modal_body);
        }
      });


    });

    $(document).on('submit', '#deleteAttendanceForm' ,function (e) {
      e.preventDefault();

      $('#deleteAttendanceSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteAttendanceForm').serialize();

      $.ajax({
      url: "<?php echo base_url('attendance/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#delete_attendance').modal('hide');
            $('form#deleteAttendanceForm #deleteAttendanceSubmit').text('Delete').removeAttr('disabled');
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

          $('#deleteAttendanceSubmit').text('Delete').removeAttr('disabled');
        }
      });
    });
    



    /*************************** End Dynamic Employyee List with Datatables ****************************/

    $(document).on('change', '.all_attendance', function() {
      if(this.checked == true)
        $('.single_attendance').prop('checked',true);
      else
        $('.single_attendance').prop('checked',false);
    });

    $(document).on('change', '.single_attendance', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#attendance_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_attendance          = $('.single_attendance').length;
      var total_checked_single_attendance  = $('.single_attendance:checked').length;
      
      if(total_checked_single_attendance < total_single_attendance && total_checked_single_attendance > 0){
        $('.all_attendance').prop('indeterminate',true); 
      }
      else if(total_checked_single_attendance == total_single_attendance){
        $('.all_attendance').prop('indeterminate',false);
        $('.all_attendance').prop('checked',true);
      }
      else if(total_checked_single_attendance == 0){
        $('.all_attendance').prop('indeterminate',false);
        $('.all_attendance').prop('checked',false);
      }
    }

    $(document).on('click','#attendance_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_attendance:checked'); // Select only checked checkboxes
      var employee_id = $('#employee_id').val();
      var month = $('#month').val();
      var year = $('#year').val();
      var status = $('#attendance_status').val();
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); // Assuming the value holds the attendance_id
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
              title: 'FAILURE !!',
              text: "Please select at least one attendance record to export.",
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
      var exportUrl = '<?= base_url('attendance/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",") +
                  "&employee_id=" + employee_id +
                  "&month=" + month +
                  "&year=" + year +
                  "&status=" + status;

      window.location.href = exportUrl;
    });






    
  });
</script>