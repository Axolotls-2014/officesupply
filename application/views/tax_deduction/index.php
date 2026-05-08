<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('employee')?>"><?=$this->lang->line('header_hrmodule')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_tax_deduction')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('tax_deduction_list')?></li>
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
                 
                  
                  <div class="col-md-4">
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

                  <div class="col-md-4">
                    <label><?=$this->lang->line('from_date')?></label>
                    <input type="text" class="form-control datepicker" name="from_date" id="from_date" style="z-index:999 !important;cursor:pointer;" autocomplete="off">
                  </div>

                  <div class="col-md-4">
                    <label><?=$this->lang->line('to_date')?></label>
                    <input type="text" class="form-control datepicker" name="to_date" id="to_date" style="z-index:999 !important;cursor:pointer;" value="<?=date('d-m-Y')?>"autocomplete="off">
                  </div>
                  
                

                </div>
            </div>
            <!-- /.card-body -->
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('tax_deduction_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item  ml-2">
                    <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Tax Deduction">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>

                  <?php 
                    if($this->permission_model->has_permission('add_tax_deduction'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    
                    <button type="button" class="btn btn-primary add_tax_deduction_modal">
                      <i class="fab fa-ravelry mr-2"></i><?=$this->lang->line('tax_deduction_add')?>
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
                    <th width="2%"><input type="checkbox" class="all_tax_deduction"></th>
                    <th><?=$this->lang->line('tax_deduction_employee_id')?></th>
                    <th><?=$this->lang->line('tax_deduction_date')?></th>
                    <th><?=$this->lang->line('tax_deduction_tax_type')?></th>
                    <th><?=$this->lang->line('tax_deduction_amount')?></th>
                    <th><?=$this->lang->line('tax_deduction_action')?></th>
                  </tr>
                </thead>
                
                <tbody id="tax_deduction_list">
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('tax_deduction_employee_id')?></th>
                    <th><?=$this->lang->line('tax_deduction_date')?></th>
                    <th><?=$this->lang->line('tax_deduction_tax_type')?></th>
                    <th><?=$this->lang->line('tax_deduction_amount')?></th>
                    <th><?=$this->lang->line('tax_deduction_action')?></th>
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

<div class="modal fade" id="add_tax_deduction_modal" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      
    </div>
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="delete_tax_deduction" data-backdrop="static">
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


    /*************************** Start Dynamic tax_deduction List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    { 

      var employee_id   = $('#employee_id').val();
      
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
          "url": "<?php echo site_url('tax_deduction/ajax_list')?>",
            "type": "POST",
            "data":  {
              'employee_id' : employee_id,
             
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

    function reinitialize(){
      $(".select2bs4").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }

    $(document).on('change','#employee_id,  #from_date, #to_date',function(e){
      initialize_datatable();
    })

    $(document).on('click', ".add_tax_deduction_modal" ,function(event){
      event.preventDefault();
      
      var tax_deduction_id = $(this).data('tax_deduction_id');
      tax_deduction_id = (tax_deduction_id === undefined) ? "" : "/"+tax_deduction_id;

      $.ajax({
        url: "<?=base_url('tax_deduction/add')?>"+tax_deduction_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_tax_deduction_modal').find('.modal-content').html(data.add_tax_deduction_modal_body);
          $('#add_tax_deduction_modal').modal('show');
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

    $(document).on('submit','#addtaxDeductionForm',function(e){
      
      e.preventDefault();

      $('#addtaxDeductionSubmit').text('Please wait...').attr('disabled','disabled');
      

      var isError = false;

      $('form#addtaxDeductionForm .field_validation').each(function() {
          
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#addtaxDeductionForm  #err_"+id).text(field+ " field is required.");
          $('form#addtaxDeductionForm  #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addtaxDeductionForm #err_"+id).text("");
          $('form#addtaxDeductionForm #'+id).removeClass('is-invalid');
          $('form#addtaxDeductionForm #'+id).addClass('is-valid');
        }
      });
      
      if(isError == true)
      {
        $('#addtaxDeductionSubmit').text('Save').removeAttr('disabled');
        return false;
      }
      else
      {
        var formData = $('#addtaxDeductionForm').serialize();
        
        var tax_deduction_id = $('#add_tax_deduction_modal').find('input[name="tax_deduction_id"]').val();
        tax_deduction_id = (tax_deduction_id == '') ? "" : "/"+tax_deduction_id;

        $.ajax({
          url: "<?php echo base_url('tax_deduction/add')?>"+tax_deduction_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_tax_deduction_modal').modal('hide');
              $('form#addtaxDeductionForm #addtaxDeductionSubmit').text('Save').removeAttr('disabled');

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
                $("form#addtaxDeductionForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#addtaxDeductionForm #addtaxDeductionSubmit').text('Save').removeAttr('disabled');
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
              $('#addtaxDeductionSubmit').text('Save').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addtaxDeductionForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addtaxDeductionForm #err_"+id).text(field+ " field is required.");
          $('form#addtaxDeductionForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addtaxDeductionForm #err_"+id).text("");
          $('form#addtaxDeductionForm #'+id).removeClass('is-invalid');
          $('form#addtaxDeductionForm #'+id).addClass('is-valid');
        }
    });


    $(document).on('show.bs.modal','#delete_tax_deduction', function (e) {
      var tax_deduction_id = $(e.relatedTarget).data('tax_deduction_id');
      $('#delete_tax_deduction').find('#id').val(tax_deduction_id);

      // alert(tax_deduction_id);

      $.ajax({
        url: "<?php echo base_url('tax_deduction/tax_deduction_delete_confirmation')?>",
        type: "POST",
        data:{
          'tax_deduction_id': tax_deduction_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_tax_deduction').find('.modal-content').html(data.tax_deduction_delete_modal_body);
        }
      });


    });

    $(document).on('submit', '#deletetaxDeductionForm' ,function (e) {
      e.preventDefault();

      $('#deletetaxDeductionSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deletetaxDeductionForm').serialize();

      $.ajax({
      url: "<?php echo base_url('tax_deduction/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#delete_tax_deduction').modal('hide');
            $('form#deletetaxDeductionForm #deletetaxDeductionSubmit').text('Delete').removeAttr('disabled');
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

          $('#deletetaxDeductionSubmit').text('Delete').removeAttr('disabled');
        }
      });
    });

    /*************************** End Dynamic Employyee List with Datatables ****************************/

    $(document).on('change', '.all_tax_deduction', function() {
      if(this.checked == true)
        $('.single_tax_deduction').prop('checked',true);
      else
        $('.single_tax_deduction').prop('checked',false);
    });

    $(document).on('change', '.single_tax_deduction', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#tax_deduction_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_tax_deduction          = $('.single_tax_deduction').length;
      var total_checked_single_tax_deduction  = $('.single_tax_deduction:checked').length;
      
      if(total_checked_single_tax_deduction < total_single_tax_deduction && total_checked_single_tax_deduction > 0){
        $('.all_tax_deduction').prop('indeterminate',true); 
      }
      else if(total_checked_single_tax_deduction == total_single_tax_deduction){
        $('.all_tax_deduction').prop('indeterminate',false);
        $('.all_tax_deduction').prop('checked',true);
      }
      else if(total_checked_single_tax_deduction == 0){
        $('.all_tax_deduction').prop('indeterminate',false);
        $('.all_tax_deduction').prop('checked',false);
      }
    }

    $(document).on('click','#tax_deduction_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_tax_deduction:checked'); // Select only checked checkboxes
      var employee_id = $('#employee_id').val();
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      var tax_type = $('#tax_type').val();
      var amount = $('#amount').val();
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); // Assuming the value holds the tax_deduction_id
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
              title: 'FAILURE !!',
              text: "Please select at least one tax deduction record to export.",
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
      var exportUrl = '<?= base_url('tax_deduction/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",") +
                  "&employee_id=" + employee_id +
                  "&from_date=" + from_date +
                  "&to_date=" + to_date +
                  "&tax_type=" + tax_type +
                  "&amount=" + amount;

      window.location.href = exportUrl;
    });

    
  });
</script>