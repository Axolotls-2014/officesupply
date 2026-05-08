<?php 
  $tabindex = 101;
?>  
<div class="example-modal">
  <div class="modal fade" id="add_warehouse_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form role="form" method="post" name="addWarehouseForm" id="addWarehouseForm">
          <div class="modal-header text-left">
            <h4 class="modal-title">Add Branch</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4>
              <?php echo $this->lang->line('lbl_cust_delete_modal');?>
            </h4>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('warehouse_name')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="text" name="name" value="<?=set_value('name') ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('warehouse_name')?>" tabindex="<?=$tabindex++?>">
                <span id="err_name" class="error invalid-feedback"><?=form_error('name');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('warehouse_code')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="text" name="code" value="<?=set_value('code') ?>" class="form-control form-control-sm field_validation" id="code" placeholder="<?=$this->lang->line('warehouse_code')?>" tabindex="<?=$tabindex++?>">
                <span id="err_code" class="error invalid-feedback"><?=form_error('code');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('warehouse_description')?>
                <span class="text-danger">*</span> 
              </label>
              <div class="col-sm-6">
                <input type="text" name="description" value="<?=set_value('description') ?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line('warehouse_description')?>" tabindex="<?=$tabindex++?>">
                <span id="err_description" class="error invalid-feedback"><?=form_error('description');?></span>
              </div>
            </div>
            
          </div>
          <div class="modal-footer">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <button type="submit" name="submit" id="warehouseSubmit" tabindex="<?=$tabindex?>" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
              <?php echo $this->lang->line('btn_modal_close');?>
            </button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function(e){

    const warehouseToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    $('#warehouseSubmit').click(function(e){
      e.preventDefault();

      var isError = false;

      $('form#addWarehouseForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addWarehouseForm #err_"+id).text(field+ " field is required.");
            if($('form#addWarehouseForm #'+id).hasClass('is-valid')){
              $('form#addWarehouseForm #'+id).removeClass('is-valid');
            }
            $('form#addWarehouseForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addWarehouseForm #err_"+id).text("");
            $('form#addWarehouseForm #'+id).removeClass('is-invalid');
            $('form#addWarehouseForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        return false;
      }  
      else 
      {
        var formData = $('#addWarehouseForm').serialize();
        $('#warehouseSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $.ajax({
            url: '<?php echo base_url("warehouse/add") ?>',
            type: 'POST',
            dataType : 'json',
            data: formData,                       
            success: function (response) {

              if(response.code==1)
              {
                $('#add_warehouse_modal').modal('hide');
                $('#warehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');

                if($('#warehouse_id').length)
                {
                  $('#warehouse_id').html('');
                  $('#warehouse_id').append('<option value="">Select</option>');
                  
                  for(i=0;i<response['warehouse'].length;i++)
                  { 
                    $('#warehouse_id').append('<option value="' + response['warehouse'][i].id + '">' + response['warehouse'][i].name+'</option>');
                  }

                  $('#warehouse_id').val(response['id']).attr("selected","selected");  

                  if($('#customer_id').length)
                  {
                    if($("#customer_id").val() != '')
                    {
                      $("#customer_id").closest('.row').siblings().fadeIn(10);
                    }
                  }
                }
                else
                {
                  // show_message('success-header',response.message);
                  warehouseToast.fire({
                    type: 'success',
                    title: response.message
                  });  

                  location.reload(true);
                }
              }
              else
              {
                // show_message('failure-header',response.message);
                warehouseToast.fire({
                  type: 'error',
                  title: response.message
                });
              }
            },
            error: function () 
            { 
              show_message('failure-header','Please contact the administrator if you are keep facing this issue.');   
            }
        });
      }    
    });
    $("form#addWarehouseForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addWarehouseForm #err_"+id).text(field+ " field is required.");
          if($('form#add_warehouse_modal #'+id).hasClass('is-valid')){
            $('form#addWarehouseForm #'+id).removeClass('is-valid');
          }
          $('form#addWarehouseForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addWarehouseForm #err_"+id).text("");
          $('form#addWarehouseForm #'+id).removeClass('is-invalid');
          $('form#addWarehouseForm #'+id).addClass('is-valid');
        }
    });

    $('#add_warehouse_modal').on('hidden.bs.modal', function () {
      $('form#addWarehouseForm .field_validation').each(function() {
        var id    = $(this).attr('id');
        $("form#addWarehouseForm #"+id).val("");
        $("form#addWarehouseForm #err_"+id).text("");
        $('form#addWarehouseForm #'+id).removeClass('is-invalid');
        $('form#addWarehouseForm #'+id).removeClass('is-valid');
      });
    });
    
    $('#add_warehouse_modal').on('shown.bs.modal', function () {

      $("form#addWarehouseForm #warehouse_name").focus();
      
      var company_country_id  = '<?=$this->company_settings_model->get_company_records()->country_id?>';

      $('#country_id').html('<option value="">Select</option>');

      $.ajax({
        url: "<?php echo base_url('utility/get_countries') ?>/",
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          for(i=0;i<data.length;i++)
          {
            $('#country_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
          }

          $('#country_id').val(company_country_id).trigger('change');
        }
      });
    });

    $('#country_id').change(function(){

      var id = $(this).val();

      var company_state_id    = '<?=$this->company_settings_model->get_company_records()->state_id?>';
       // alert(id);
      $('#state_id').html('<option value="">Select</option>');
      $('#city_id').html('<option value="">Select</option>');
      $.ajax({
        url: "<?php echo base_url('utility/get_states') ?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          for(i=0;i<data.length;i++){
            $('#state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
          }

          $('#state_id').val(company_state_id).trigger('change');
        }
      });
    });

    $('#state_id').change(function(){
      var id = $(this).val();
      
      $('#city_id').html('<option value="">Select</option>');
      $.ajax({
         url: "<?php echo base_url('utility/get_cities') ?>/"+id,
         type: "GET",
         dataType: "JSON",
         success: function(data){
           for(i=0;i<data.length;i++){
             $('#city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
           }
         }
      });
    });
  });
  
</script>