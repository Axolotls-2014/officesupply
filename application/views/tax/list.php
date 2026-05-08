
<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('tax')?>"><?=$this->lang->line('header_tax')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_tax')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Tax</h3>

                <div class="card-tools">

                  <ul class="nav nav-pills ml-auto">

                    <?php 
                      if($this->permission_model->has_permission('import_tax'))
                      {
                    ?>
                    <li class="nav-item  ml-2">
                      <a class="nav-link import_tax_modal btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Import Tax in Bulk using CSV file">
                        <i class="fas fa-file-import"></i> Import Tax
                      </a>
                    </li>
                    <?php 
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_permission('add_tax'))
                      {
                    ?>
                        <li class="nav-item ml-2">
                          <a class="nav-link add_tax_modal btn-sm btn-primary text-white" href="#" data-toggle="modal" data-target="#add_tax_modal" data-tt="tooltip" title="Click here to Add Tax">
                            Add Tax
                          </a>
                        </li>
                    <?php
                      }
                    ?>
                  </ul>
                </div>

                <?php 
                if($this->permission_model->has_permission('add_tax'))
                  {
                ?>
                <!-- <div class="card-tools">
                <button type="button" class="btn btn-block btn-primary btn-sm add_tax_modal" data-toggle="modal" data-target="#add_tax_modal" data-tt="tooltip" title="Click here to Add Tax">Add Tax</button>
                </div> -->
                <?php
                  }
                ?>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><?=$this->lang->line('tax_name')?></th>
                      <th><?=$this->lang->line('tax_sgst')?></th>
                      <th><?=$this->lang->line('tax_cgst')?></th>
                      <th><?=$this->lang->line('tax_igst')?></th>
                      <th><?=$this->lang->line('tax_status')?></th>
                      <th width="15%"><?=$this->lang->line("tax_action")?></th>   
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
      <!-- /.row -->
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php $this->load->view('layout/footer');?>

<div class="example-modal">
  <div class="modal fade" id="import_tax_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="add_tax_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
<div class="modal fade" id="edit_tax_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
<div class="modal fade" id="delete_tax_modal" data-backdrop="static" data-keyboard="false">
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

     const taxToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    initialize_datatable();
    function initialize_datatable()
    {
     $('#example').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('tax/ajax_list')?>",
            "type": "POST",
            "data":  {
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 5 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
     });
    }

  $(document).on('click', ".add_tax_modal" ,function(){
    var tax_id = $(this).data('tax_id');

      $.ajax({
      url: "<?php echo base_url('tax/add')?>/"+tax_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_tax_modal').find('.modal-content').html(data.add_tax_modal_body);
          $('#add_tax_modal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });


    $(document).on('submit','#addTaxForm',function(e){
      
      e.preventDefault();

      $('#addTaxSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addTaxForm').serialize();

      var isError = false;

      $('form#addTaxForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addTaxForm  #err_"+id).text(field+ " field is required.");
            $('form#addTaxForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addTaxForm #err_"+id).text("");
            $('form#addTaxForm #'+id).removeClass('is-invalid');
            $('form#addTaxForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addTaxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('tax/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
            $('#add_tax_modal').modal('hide');
              $('form#addTaxForm #addTaxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              taxToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              taxToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addTaxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addTaxForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addTaxForm #err_"+id).text(field+ " field is required.");
          $('form#addTaxForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addTaxForm #err_"+id).text("");
          $('form#addTaxForm #'+id).removeClass('is-invalid');
          $('form#addTaxForm #'+id).addClass('is-valid');
        }
    });

  $(document).on('show.bs.modal','#edit_tax_modal', function (e) {
      
      var tax_id = $(e.relatedTarget).data('tax_id');
      $('#edit_tax_modal').find('#id').val(tax_id);

      $.ajax({
        url: "<?php echo base_url('tax/edit')?>/"+tax_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#edit_tax_modal').find('.modal-content').html(data.edit_tax_modal_body);
        }
      });

    });

    $(document).on('hidden.bs.modal','#edit_tax_modal', function (e) {
      $('#edit_tax_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
     $(document).on('submit','#editTaxForm',function(e){
      e.preventDefault();

      $('#editTaxSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#editTaxForm').serialize();

      var isError = false;

      $('form#editTaxForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editTaxForm  #err_"+id).text(field+ " field is required.");
            $('form#editTaxForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editTaxForm #err_"+id).text("");
            $('form#editTaxForm #'+id).removeClass('is-invalid');
            $('form#editTaxForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#editTaxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('tax/edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#edit_tax_modal').modal('hide');
              $('form#editTaxForm #editTaxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              taxToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#editTaxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

  $(document).on('shown.bs.modal','#delete_tax_modal', function (e) {
    var tax_id = $(e.relatedTarget).data('tax_id');
    $('#delete_tax_modal').find('#id').val(tax_id);

      $.ajax({
      url: "<?php echo base_url('tax/tax_delete_confirmation')?>",
        type: "POST",
        data:{
        'tax_id': tax_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
        $('#delete_tax_modal').find('.modal-content').html(data.delete_tax_modal_body);
        }
      });

    });

    $(document).on('submit', '#deleteTaxForm' ,function (e) {
      e.preventDefault();

      $('#deleteTaxSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteTaxForm').serialize();

      $.ajax({
      url: "<?php echo base_url('tax/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
            $('#delete_tax_modal').modal('hide');
            $('form#deleteTaxForm #deleteTaxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            initialize_datatable();

            taxToast.fire({
              type: 'success',
              title: response.message
            });
            
          }
          else
          {
            taxToast.fire({
              type: 'error',
              title: response.message
            });            
          }

          $('#deleteTaxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        }
      });
    });
  });

   /* Import tax using CSV function Begin */

    $(document).on('click', ".import_tax_modal" ,function(event){
      event.preventDefault();

      $.ajax({
        url: "<?php echo base_url('tax/import_tax')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#import_tax_modal').find('.modal-content').html(data.import_tax_modal_body);
          $('#import_tax_modal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // alert(xhr.status);
          show_message('failure-header',thrownError);
          // alert(thrownError);
          // alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','form#importTaxForm',function(event){
      // event.preventDefault();

      $('form#importTaxForm #importTaxSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('form#importTaxForm').serialize();

      var isError = false;

      $('form#importTaxForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#importTaxForm  #err_"+id).text(field+ " field is required.");
            $('form#importTaxForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#importTaxForm #err_"+id).text("");
            $('form#importTaxForm #'+id).removeClass('is-invalid');
            $('form#importTaxForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('form#importTaxForm #importTaxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        return true;
      }
    });

    /* Import tax using CSV function End */

</script>


<!--  -->