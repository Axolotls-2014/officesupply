
<div class="example-modal">
  <div class="modal fade" id="add_tax_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" method="post" name="addTaxForm" id="addTaxForm">
          <div class="modal-header text-left">
            <h4 class="modal-title"><?php echo $this->lang->line('tax_add');?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4>
              <?php echo $this->lang->line('lbl_cust_delete_modal');?>
            </h4>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('tax_name')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="text" name="tax_name" value="<?=set_value('tax_name') ?>" class="form-control form-control-sm field_validation" id="tax_name" placeholder="<?=$this->lang->line('tax_name')?>">
                <span id="err_tax_name" class="error invalid-feedback"><?=form_error('tax_name');?></span>
              </div>
            </div>
            
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('tax_sgst')?> (%)
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="text" name="sgst" value="<?=set_value('sgst') ?>" class="form-control form-control-sm field_validation" id="sgst" placeholder="<?=$this->lang->line('tax_sgst')?>"><?=form_error('sgst', '<div class="text-danger">', '</div>');?>
                <span id="err_sgst" class="error invalid-feedback"><?=form_error('sgst');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('tax_cgst')?> (%)
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="text" name="cgst" value="<?=set_value('cgst') ?>" class="form-control form-control-sm field_validation" id="cgst" placeholder="<?=$this->lang->line('tax_cgst')?>"><?=form_error('cgst', '<div class="text-danger">', '</div>');?>
                <span id="err_cgst" class="error invalid-feedback"><?=form_error('cgst');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('tax_igst')?> (%)
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="text" name="igst" value="<?=set_value('igst') ?>" class="form-control form-control-sm field_validation" id="igst" placeholder="<?=$this->lang->line('tax_igst')?>"><?=form_error('igst', '<div class="text-danger">', '</div>');?>
                <span id="err_igst" class="error invalid-feedback"><?=form_error('igst');?></span>
              </div>
            </div>
            <div class="form-group row">
                     <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('tax_status')?><span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                      <select class="form-control form-control-sm select2bs4 field_validation" name="status" id="status" width="100%">
                        <option value="1"><?=$this->lang->line('tax_status_active')?></option>
                        <option value="0"><?=$this->lang->line('tax_status_inactive')?></option>
                      </select>
                      <span id="err_status" class="error invalid-feedback"><?=form_error('status');?></span>
                    </div>
                  </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <button type="submit" name="submit" id="taxSubmit" class="btn btn-primary"><?php echo $this->lang->line('submit');?></button>
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

    const TaxToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    $('#addTaxForm').submit(function(e){
      e.preventDefault();

      var isError = false;

      $('form#addTaxForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addTaxForm #err_"+id).text(field+ " field is required.");
            if($('form#addTaxForm #'+id).hasClass('is-valid')){
              $('form#addTaxForm #'+id).removeClass('is-valid');
            }
            $('form#addTaxForm #'+id).addClass('is-invalid');
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
        return false;
      }  
      else 
      {
        var formData = $('#addTaxForm').serialize();
        $('#taxSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $.ajax({
            url: '<?php echo base_url("tax/add") ?>',
            type: 'POST',
            dataType : 'json',
            data: formData,                       
            success: function (response) {

              if(response.code==1)
              {
                $('#add_tax_modal').modal('hide');
                /*$('#add_product_category_modal').modal('hide');*/
                $('#taxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
                
                if($('#tax_id').length)
                {
                  $('#tax_id').html('');
                  $('#tax_id').append('<option value="">Select</option>');
                  
                  for(i=0;i<response['taxes'].length;i++)
                  { 
                    $('#tax_id').append('<option value="' + response['taxes'][i].id + '">' + response['taxes'][i].tax_name +'</option>');
                  }

                  $('#tax_id').val(response['id']).attr("selected","selected");  

                  // show_message('success-header',response.message);
                  TaxToast.fire({
                    type: 'success',
                    title: response.message
                  });  

                }
                else
                {
                  // show_message('success-header',response.message);
                  TaxToast.fire({
                    type: 'success',
                    title: response.message
                  });  

                  location.reload(true);
                }
              }
              else
              {
                // show_message('failure-header',response.message);
                TaxToast.fire({
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
    $("form#addTaxForm .field_validation").on("blur keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addTaxForm #err_"+id).text(field+ " field is required.");
          if($('form#addTaxForm #'+id).hasClass('is-valid')){
            $('form#addTaxForm #'+id).removeClass('is-valid');
          }
          $('form#addTaxForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addTaxForm #err_"+id).text("");
          $('form#addTaxForm #'+id).removeClass('is-invalid');
          $('form#addTaxForm #'+id).addClass('is-valid');
        }
    });
    $('#add_tax_modal').on('hidden.bs.modal', function () {
      $('form#addTaxForm .field_validation').each(function() {
          var id    = $(this).attr('id');
          $("form#addTaxForm #"+id).val("");
          $("form#addTaxForm #err_"+id).text("");
          $('form#addTaxForm #'+id).removeClass('is-invalid');
          $('form#addTaxForm #'+id).removeClass('is-valid');
      });
    });  

    $('#add_tax_modal').on('shown.bs.modal', function () {
      //alert();
      $("#add_product_category_modal").modal("hide");
    });
  })
  
</script>