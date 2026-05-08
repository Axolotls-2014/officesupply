<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('employee')?>"><?=$this->lang->line('header_hrmodule')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_weekends')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('weekend_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('weekend_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item  ml-2">
                    <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Weekend">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>

                  <?php 
                    if($this->permission_model->has_permission('add_weekend'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    
                    <button type="button" class="btn btn-primary add_weekend_modal">
                      <i class="fas fa-hat-wizard mr-2"></i><?=$this->lang->line('weekend_add')?>
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
                    <th width="2%"><input type="checkbox" class="all_weekend"></th>
                    <th><?=$this->lang->line('weekend_date')?></th>
                    <th><?=$this->lang->line('weekend_description')?></th>
                    <th><?=$this->lang->line('weekend_action')?></th>
                  </tr>
                </thead>
                
                <tbody id="weekend_list">
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('weekend_date')?></th>
                    <th><?=$this->lang->line('weekend_description')?></th>
                    <th><?=$this->lang->line('weekend_action')?></th>
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

<div class="modal fade" id="add_weekend_modal" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      
    </div>
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="delete_weekend" data-backdrop="static">
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

    // Populate year options
    var currentYear = new Date().getFullYear();
    var startYear = 1991;
    
    for (var year = currentYear; year >= startYear; year--) {
        $('#year').append(new Option(year, year));
    }

    /*************************** Start Dynamic weekend List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    { 

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
          "url": "<?php echo site_url('weekend/ajax_list')?>",
            "type": "POST",
            "data":  {
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
            "targets": [ 0,3 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
      });
    }

    function reinitialize(){
      $(".select2bs4").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }

    $(document).on('change','#month, #year',function(e){
      initialize_datatable();
    })

    $(document).on('click', ".add_weekend_modal" ,function(event){
      event.preventDefault();
      
      var weekend_id = $(this).data('weekend_id');
      weekend_id = (weekend_id === undefined) ? "" : "/"+weekend_id;

      $.ajax({
        url: "<?=base_url('weekend/add')?>"+weekend_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_weekend_modal').find('.modal-content').html(data.add_weekend_modal_body);
          $('#add_weekend_modal').modal('show');
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

    $(document).on('submit','#addWeekendForm',function(e){
      
      e.preventDefault();

      $('#addWeekendSubmit').text('Please wait...').attr('disabled','disabled');
      

      var isError = false;

      $('form#addWeekendForm .field_validation').each(function() {
          
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#addWeekendForm  #err_"+id).text(field+ " field is required.");
          $('form#addWeekendForm  #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addWeekendForm #err_"+id).text("");
          $('form#addWeekendForm #'+id).removeClass('is-invalid');
          $('form#addWeekendForm #'+id).addClass('is-valid');
        }
      });
      
      if(isError == true)
      {
        $('#addWeekendSubmit').text('Save').removeAttr('disabled');
        return false;
      }
      else
      {
        var formData = $('#addWeekendForm').serialize();
        
        var weekend_id = $('#add_weekend_modal').find('input[name="weekend_id"]').val();
        weekend_id = (weekend_id == '') ? "" : "/"+weekend_id;

        $.ajax({
          url: "<?php echo base_url('weekend/add')?>"+weekend_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_weekend_modal').modal('hide');
              $('form#addWeekendForm #addWeekendSubmit').text('Save').removeAttr('disabled');

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
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                    $("#err_" + key).text(value).addClass('invalid-feedback').show();
                    $('#' + key).addClass('is-invalid');
                });
                $('#addWeekendSubmit').text('Save').removeAttr('disabled');
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
              $('#addWeekendSubmit').text('Save').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addWeekendForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addWeekendForm #err_"+id).text(field+ " field is required.");
          $('form#addWeekendForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addWeekendForm #err_"+id).text("");
          $('form#addWeekendForm #'+id).removeClass('is-invalid');
          $('form#addWeekendForm #'+id).addClass('is-valid');
        }

        
    });


    $(document).on('show.bs.modal','#delete_weekend', function (e) {
      var weekend_id = $(e.relatedTarget).data('weekend_id');
      $('#delete_weekend').find('#id').val(weekend_id);

      // alert(weekend_id);

      $.ajax({
        url: "<?php echo base_url('weekend/weekend_delete_confirmation')?>",
        type: "POST",
        data:{
          'weekend_id': weekend_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_weekend').find('.modal-content').html(data.weekend_delete_modal_body);
        }
      });


    });

    $(document).on('submit', '#deleteWeekendForm' ,function (e) {
      e.preventDefault();

      $('#deleteWeekendSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteWeekendForm').serialize();

      $.ajax({
      url: "<?php echo base_url('weekend/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#delete_weekend').modal('hide');
            $('form#deleteWeekendForm #deleteWeekendSubmit').text('Delete').removeAttr('disabled');
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

          $('#deleteWeekendSubmit').text('Delete').removeAttr('disabled');
        }
      });
    });

    /*************************** End Dynamic Employyee List with Datatables ****************************/

    $(document).on('change', '.all_weekend', function() {
      if(this.checked == true)
        $('.single_weekend').prop('checked',true);
      else
        $('.single_weekend').prop('checked',false);
    });

    $(document).on('change', '.single_weekend', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#weekend_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_weekend          = $('.single_weekend').length;
      var total_checked_single_weekend  = $('.single_weekend:checked').length;
      
      if(total_checked_single_weekend < total_single_weekend && total_checked_single_weekend > 0){
        $('.all_weekend').prop('indeterminate',true); 
      }
      else if(total_checked_single_weekend == total_single_weekend){
        $('.all_weekend').prop('indeterminate',false);
        $('.all_weekend').prop('checked',true);
      }
      else if(total_checked_single_weekend == 0){
        $('.all_weekend').prop('indeterminate',false);
        $('.all_weekend').prop('checked',false);
      }
    }

    $(document).on('click','#weekend_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_weekend:checked'); // Select only checked checkboxes
      
      var month = $('#month').val();
      var year = $('#year').val();
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); // Assuming the value holds the weekend_id
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
            title: 'FAILURE !!',
              text: "Please select at least one weekend record to export.",
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
      var exportUrl = '<?= base_url('weekend/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",") +
                  
                  "&month=" + month +
                  "&year=" + year;

      window.location.href = exportUrl;
    });

    
  });
</script>