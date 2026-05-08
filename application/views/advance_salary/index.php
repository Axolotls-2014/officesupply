<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('employee')?>"><?=$this->lang->line('header_hrmodule')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_advance_salary')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('advance_salary_list')?></li>
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
                    <label><?=$this->lang->line('from_date')?></label>
                    <input type="text" class="form-control datepicker" name="from_date" id="from_date" style="z-index:999 !important;cursor:pointer;" autocomplete="off">
                  </div>

                  <div class="col-md-3">
                    <label><?=$this->lang->line('to_date')?></label>
                    <input type="text" class="form-control datepicker" name="to_date" id="to_date" style="z-index:999 !important;cursor:pointer;" value="<?=date('d-m-Y')?>"autocomplete="off">
                  </div>
                  

                  <div class="col-md-3">
                    <label>Status</label>
                    <select class="form-control form-control-sm select2bs4" id="status">
                      <option value="">All</option>
                      <option value="<?=STATUS_PENDING?>">Pending</option>
                      <option value="<?=STATUS_APPROVED?>">Approved</option>
                      <option value="<?=STATUS_REJECTED?>">Rejected</option>
                    </select>
                  </div>

                </div>
            </div>
            <!-- /.card-body -->
          </div>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('advance_salary_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item  ml-2">
                    <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Advance Salary">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>

                  <?php 
                    if($this->permission_model->has_permission('add_advance_salary'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    
                    <button type="button" class="btn btn-primary add_advance_salary_modal">
                      <i class="fas fa-comment-dollar mr-2"></i><?=$this->lang->line('advance_salary_add')?>
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
                    <th width="2%"><input type="checkbox" class="all_advance_salary"></th>
                    <th><?=$this->lang->line('advance_salary_employee_id')?></th>
                    <th><?=$this->lang->line('advance_salary_request_date')?></th>
                    <th><?=$this->lang->line('advance_salary_amount')?></th>
                    <th><?=$this->lang->line('advance_salary_status')?></th>
                    <th><?=$this->lang->line('advance_salary_action')?></th>
                  </tr>
                </thead>
                
                <tbody id="advance_salary_list" >
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('advance_salary_employee_id')?></th>
                    <th><?=$this->lang->line('advance_salary_request_date')?></th>
                    <th><?=$this->lang->line('advance_salary_amount')?></th>
                    <th><?=$this->lang->line('advance_salary_status')?></th>
                    <th><?=$this->lang->line('advance_salary_action')?></th>
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

<div class="modal fade" id="add_advance_salary_modal" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      
    </div>
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="delete_advance_salary" data-backdrop="static">
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

    // Get the first date of the current month
    var date = new Date();
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);

    // Format the date as mm/dd/yyyy
    var day = ("0" + firstDay.getDate()).slice(-2);
    var month = ("0" + (firstDay.getMonth() + 1)).slice(-2);
    var formattedDate = day + "/" + month + "/" + firstDay.getFullYear();

    // Set the datepicker value
    $('#from_date').datepicker({
        dateFormat: 'dd/mm/yy'
    }).datepicker("setDate", formattedDate);

    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0); // Setting day to 0 goes to the last day of the previous month

    // Format the date as dd/mm/yyyy
    var day = ("0" + lastDay.getDate()).slice(-2);
    var month = ("0" + (lastDay.getMonth() + 1)).slice(-2);
    var formattedDate = day + "/" + month + "/" + lastDay.getFullYear();

    // Set the datepicker value for the last date of the month
    $('#to_date').datepicker({
        dateFormat: 'dd/mm/yy'
    }).datepicker("setDate", formattedDate);

    /*************************** Start Dynamic salary List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    { 
      var employee_id   = $('#employee_id').val();
      var status        = $('#status').val();
      var from_date     = $('#from_date').val();
      var to_date       = $('#to_date').val();

      $('#example').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [], //Initial no order.
        "pageLength": 50,
 
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('advance_salary/ajax_list')?>",
            "type": "POST",
            "data":  {
              'employee_id' : employee_id,
              'status' : status,
              'from_date' : from_date,
              'to_date' : to_date,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 0,5 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
      });
    }

    $(document).on('change','#employee_id, #status, #from_date, #to_date',function(e){
      initialize_datatable();
    })

    function reinitialize(){
      $(".select2bs4").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }

    $(document).on('click', ".add_advance_salary_modal" ,function(event){
      event.preventDefault();
      
      var advance_id = $(this).data('advance_id');
      advance_id = (advance_id === undefined) ? "" : "/"+advance_id;
      

      $.ajax({
        url: "<?=base_url('advance_salary/add')?>"+advance_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_advance_salary_modal').find('.modal-content').html(data.add_advance_salary_modal_body);
          $('#add_advance_salary_modal').modal('show');
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

    $(document).on('submit','#addadvanceSalaryForm',function(e){
      
      e.preventDefault();

      $('#addadvanceSalarySubmit').text('Please wait...').attr('disabled','disabled');
      

      var isError = false;

      $('form#addadvanceSalaryForm .field_validation').each(function() {
          
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#addadvanceSalaryForm  #err_"+id).text(field+ " field is required.");
          $('form#addadvanceSalaryForm  #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addadvanceSalaryForm #err_"+id).text("");
          $('form#addadvanceSalaryForm #'+id).removeClass('is-invalid');
          $('form#addadvanceSalaryForm #'+id).addClass('is-valid');
        }
      });
      
      if(isError == true)
      {
        $('#addadvanceSalarySubmit').text('Save').removeAttr('disabled');
        return false;
      }
      else
      {
        var formData = $('#addadvanceSalaryForm').serialize();
        
        var advance_id = $('#add_advance_salary_modal').find('input[name="advance_id"]').val();
        advance_id = (advance_id == '') ? "" : "/"+advance_id;

        $.ajax({
          url: "<?php echo base_url('advance_salary/add')?>"+advance_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_advance_salary_modal').modal('hide');
              $('form#addadvanceSalaryForm #addadvanceSalarySubmit').text('Save').removeAttr('disabled');

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
                $("form#addadvanceSalaryForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#addadvanceSalaryForm #addadvanceSalarySubmit').text('Save').removeAttr('disabled');
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
              $('#addadvanceSalarySubmit').text('Save').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addadvanceSalaryForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addadvanceSalaryForm #err_"+id).text(field+ " field is required.");
          $('form#addadvanceSalaryForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addadvanceSalaryForm #err_"+id).text("");
          $('form#addadvanceSalaryForm #'+id).removeClass('is-invalid');
          $('form#addadvanceSalaryForm #'+id).addClass('is-valid');
        }
    });


    $(document).on('show.bs.modal','#delete_advance_salary', function (e) {
      var advance_id = $(e.relatedTarget).data('advance_id');
      $('#delete_advance_salary').find('#id').val(advance_id);

      // alert(advance_id);

      $.ajax({
        url: "<?php echo base_url('advance_salary/advance_salary_delete_confirmation')?>",
        type: "POST",
        data:{
          'advance_id': advance_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_advance_salary').find('.modal-content').html(data.advance_salary_delete_modal_body);
        }
      });


    });

    $(document).on('submit', '#deleteadvanceSalaryForm' ,function (e) {
      e.preventDefault();

      $('#deleteadvanceSalarySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteadvanceSalaryForm').serialize();

      $.ajax({
      url: "<?php echo base_url('advance_salary/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#delete_advance_salary').modal('hide');
            $('form#deleteadvanceSalaryForm #deleteadvanceSalarySubmit').text('Delete').removeAttr('disabled');
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

          $('#deleteadvanceSalarySubmit').text('Delete').removeAttr('disabled');
        }
      });
    });

    /*************************** End Dynamic Employyee List with Datatables ****************************/

    $(document).on('change', '.all_advance_salary', function() {
      if(this.checked == true)
        $('.single_advance_salary').prop('checked',true);
      else
        $('.single_advance_salary').prop('checked',false);
    });

    $(document).on('change', '.single_advance_salary', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#advance_salary_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_advance_salary          = $('.single_advance_salary').length;
      var total_checked_single_advance_salary  = $('.single_advance_salary:checked').length;
      
      if(total_checked_single_advance_salary < total_single_advance_salary && total_checked_single_advance_salary > 0){
        $('.all_advance_salary').prop('indeterminate',true); 
      }
      else if(total_checked_single_advance_salary == total_single_advance_salary){
        $('.all_advance_salary').prop('indeterminate',false);
        $('.all_advance_salary').prop('checked',true);
      }
      else if(total_checked_single_advance_salary == 0){
        $('.all_advance_salary').prop('indeterminate',false);
        $('.all_advance_salary').prop('checked',false);
      }
    }

    $(document).on('click','#advance_salary_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_advance_salary:checked'); // Select only checked checkboxes
      var employee_id = $('#employee_id').val();
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      var amount = $('#amount').val();
      var status = $('#status').val();
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); // Assuming the value holds the advance_salary_id
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
              text: "Please select at least one advance salary record to export.",
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
      var exportUrl = '<?= base_url('advance_salary/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",") +
                  "&employee_id=" + employee_id +
                  "&from_date=" + from_date +
                  "&to_date=" + to_date +
                  "&amount=" + amount +
                  "&status=" + status;

      window.location.href = exportUrl;
    });


    
  });
</script>