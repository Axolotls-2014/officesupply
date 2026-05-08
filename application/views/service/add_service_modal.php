<div class="example-modal">
  <div class="modal fade" id="add_service_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" method="post" name="addServiceForm" id="addServiceForm">
          <div class="modal-header text-left">
            <h4 class="modal-title"><?php echo $this->lang->line('service_add');?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4>
              <?php echo $this->lang->line('lbl_cust_delete_modal');?>
            </h4>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('service_name')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="text" name="name" value="<?=set_value('name') ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('service_name')?>"><?=form_error('name');?>
                <span id="err_name" class="error invalid-feedback"><?=form_error('name');?></span>
              </div>
            </div>
          
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('service_description')?><span class="text-danger">*</span></label>
              <div class="col-sm-6">
                <input type="text" name="description" value="<?=set_value('description')?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line('service_description')?>"><?=form_error('description');?>
                <span id="err_description" class="error invalid-feedback"><?=form_error('description');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('service_sac_code')?> <br/>(<?=$this->lang->line('service_sac_code_full')?>)
                <!-- <span class="text-danger">*</span> -->
              </label>
              <div class="col-sm-6">
                <input type="text" name="sac_code" value="<?=set_value('sac_code')?>" class="form-control form-control-sm" id="sac_code" placeholder="<?=$this->lang->line('service_sac_code')?>"><?=form_error('sac_code');?>
                <span id="err_sac_code" class="error invalid-feedback"><?=form_error('sac_code');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('service_price')?><span class="text-danger">*</span></label>
              <div class="col-sm-6">
                <input type="number" name="price" value="<?=set_value('price',0.0)?>" class="form-control form-control-sm field_validation" id="price" placeholder="<?=$this->lang->line('service_price')?>"><?=form_error('price');?>
                <span id="err_price" class="error invalid-feedback"><?=form_error('price');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('service_tax')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <select class="form-control form-control-sm select2bs4 field_validation" placeholder="<?=$this->lang->line('service_tax')?>" name="tax_id" id="tax_id" width="100%">
                    <option value=""><?=$this->lang->line('service_select_tax')?></option>
                    <?php
                      foreach ($taxes as $value) {
                    ?>
                      <option value="<?=$value->id;?>"
                        <?php 
                          if(isset($tax_id))
                          {
                            if($tax_id == $value->id)  
                              echo ' selected';
                          }
                        ?>
                      >
                        <?= $value->tax_name.' (IGST='.$value->igst.' SGST='.$value->sgst.' CGST='.$value->cgst.')';?>
                      </option>
                      
                    <?php 
                      }
                    ?>
                  </select>
                <span id="err_tax_id" class="error invalid-feedback"><?=form_error('tax_id');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('service_tax_type')?></label>
              <div class="col-sm-6">
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="tax_type" type="checkbox" id="tax_type" value="1">
                    <label for="tax_type" class="custom-control-label"></label>
                  </div>
                <span id="err_tax_type" class="error invalid-feedback"><?=form_error('tax_type');?></span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" name="submit" id="serviceSubmit" class="btn btn-primary">Submit</button>
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

    const ServiceToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    $('#serviceSubmit').click(function(e){
      e.preventDefault();

      var isError = false;

      $('form#addServiceForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addServiceForm #err_"+id).text(field+ " field is required.");
            if($('form#addServiceForm #'+id).hasClass('is-valid')){
              $('form#addServiceForm #'+id).removeClass('is-valid');
            }
            $('form#addServiceForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addServiceForm #err_"+id).text("");
            $('form#addServiceForm #'+id).removeClass('is-invalid');
            $('form#addServiceForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        return false;
      }  
      else 
      {
        var formData = $('#addServiceForm').serialize();

        $.ajax({
            url: '<?php echo base_url("service/add") ?>',
            type: 'POST',
            dataType : 'json',
            data: formData,                       
            success: function (response) {

              if(response.code==1)
              {
                $('#add_service_modal').modal('hide');

                if($('#service_id').length)
                {
                  $('#service_id').html('');
                  $('#service_id').append('<option value="">Select</option>');
                  
                  for(i=0;i<response['services'].length;i++)
                  { 
                    $('#service_id').append('<option value="' + response['services'][i].id + '">' + response['services'][i].name+'</option>');
                  }

                  $('#service_id').val(response['id']).attr("selected","selected");  
                }
                else
                {
                  // show_message('success-header',response.message);
                  ServiceToast.fire({
                    type: 'success',
                    title: response.message
                  });  

                  // location.reload(true);
                }
              }
              else
              {
                // show_message('failure-header',response.message);
                ServiceToast.fire({
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
    $("form#addServiceForm .field_validation").on("blur keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addServiceForm #err_"+id).text(field+ " field is required.");
          if($('form#addServiceForm #'+id).hasClass('is-valid')){
            $('form#addServiceForm #'+id).removeClass('is-valid');
          }
          $('form#addServiceForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addServiceForm #err_"+id).text("");
          $('form#addServiceForm #'+id).removeClass('is-invalid');
          $('form#addServiceForm #'+id).addClass('is-valid');
        }
    });
    $('#add_service_modal').on('hidden.bs.modal', function () {
      $('form#addServiceForm .field_validation').each(function() {
        var id    = $(this).attr('id');
        $("form#addServiceForm #"+id).val("");
        $("form#addServiceForm #err_"+id).text("");
        $('form#addServiceForm #'+id).removeClass('is-invalid');
        $('form#addServiceForm #'+id).removeClass('is-valid');
      });
    });
    $('#add_service_modal').on('shown.bs.modal', function () {

      $("form#addServiceForm #name").focus();
      

      $('form#addServiceForm #tax_id').html('<option value="">Select</option>');

      $.ajax({
        url: "<?php echo base_url('tax/index') ?>",
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          var taxes = data.taxes;
          for(i=0;i<taxes.length;i++)
          {
            $('form#addServiceForm #tax_id').append('<option value="' + taxes[i].id + '">' + taxes[i].tax_name + '</option>');
          }
        }
      });
    });
  });
  
</script>