<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <!-- <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li> -->
            <li class="breadcrumb-item active"><?=$this->lang->line('header_whatsapp_message')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('whatsapp_message_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item  ml-2">
                    <a class="nav-link export btn btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Whatsapp message">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>

                  <li class="nav-item ml-2">
                    <a class="nav-link active text-white btn btn-secondary" href="<?=base_url('whatsapp_template')?>" data-tt="tooltip" title="Click here to show customer list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                  </li>
                </ul>
              </div>
             
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="2%"><input type="checkbox" class="all_whatsapp_message"></th>
                    <th><?=$this->lang->line('whatsapp_message_message')?></th>
                    <th><?=$this->lang->line('whatsapp_message_to')?></th>
                    <th><?=$this->lang->line('whatsapp_message_status')?></th>
                  </tr>
                </thead>
                
                <tbody id="whatsapp_message_list">
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('whatsapp_message_message')?></th>
                    <th><?=$this->lang->line('whatsapp_message_to')?></th>
                    <th><?=$this->lang->line('whatsapp_message_status')?></th>
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




<script type="text/javascript">
  $(document).ready(function(e){

   
    

    /*************************** Start Dynamic whatsapp_template List with Datatables **************************/

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
          "url": "<?php echo site_url('whatsapp_message/ajax_list')?>",
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
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
      });
    }

    function reinitialize(){
      $(".select2").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }

   

    /*************************** End Dynamic Employyee List with Datatables ****************************/

    // Use event delegation to handle the click event on .read-more-link
    $(document).on('click', '.read-more-link', function() {
      //e.preventDefault();
      // alert();
        var truncatedMessage = $(this).siblings('.truncated-message');
        var fullMessage = $(this).siblings('.full-message');

        if (truncatedMessage.is(':visible')) {
            truncatedMessage.hide();
            fullMessage.show();
            $(this).text('Read Less');
        } else {
            truncatedMessage.show();
            fullMessage.hide();
            $(this).text('Read More');
        }
    });

    $(document).on('change', '.all_whatsapp_message', function() {
      if(this.checked == true)
        $('.single_whatsapp_message').prop('checked',true);
      else
        $('.single_whatsapp_message').prop('checked',false);
    });

    $(document).on('change', '.single_whatsapp_message', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#whatsapp_message_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_whatsapp_message          = $('.single_whatsapp_message').length;
      var total_checked_single_whatsapp_message  = $('.single_whatsapp_message:checked').length;
      
      if(total_checked_single_whatsapp_message < total_single_whatsapp_message && total_checked_single_whatsapp_message > 0){
        $('.all_whatsapp_message').prop('indeterminate',true); 
      }
      else if(total_checked_single_whatsapp_message == total_single_whatsapp_message){
        $('.all_whatsapp_message').prop('indeterminate',false);
        $('.all_whatsapp_message').prop('checked',true);
      }
      else if(total_checked_single_whatsapp_message == 0){
        $('.all_whatsapp_message').prop('indeterminate',false);
        $('.all_whatsapp_message').prop('checked',false);
      }
    }

    $(document).on('click','#whatsapp_message_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_whatsapp_message:checked'); // Select only checked checkboxes
     
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); 
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
              title: "Warning !!",
              text: "Please select at least one whatsapp message record to export.",
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
      var exportUrl = '<?= base_url('whatsapp_message/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",");

      window.location.href = exportUrl;
    });




   
    
  });
</script>