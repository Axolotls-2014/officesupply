<?php 
  $tabindex = 101;
?>
<style type="text/css">
  .tax_field_container{
    background-color: #fffdd0; 
    padding: 20px 10px 5px ; 
  }
</style>  
<div class="example-modal">
  <div class="modal fade" id="add_product_category_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" method="post" name="addProductCategoryForm" id="addProductCategoryForm">
          <div class="modal-header text-left">
            <h4 class="modal-title"><?php echo $this->lang->line('product_category_add');?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4>
              <?php echo $this->lang->line('lbl_cust_delete_modal');?>
            </h4>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('product_category_name')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-8">
                <input type="text" name="name"  value="<?=set_value('name') ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('product_category_name')?>">
                <span id="err_name" class="error invalid-feedback"><!-- <?=form_error('name');?> --></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('product_category_description')?>
                 <span class="text-danger">*</span>
              </label>
              <div class="col-sm-8">
                <input type="text" name="description" value="<?=set_value('description') ?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line('product_category_description')?>">
                <span id="err_description" class="error invalid-feedback"><!-- <?=form_error('description');?> --></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('product_category_tax_name')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-8">
                <select class="form-control form-control-sm select2bs4 field_validation" name="tax_id" id="tax_id" placeholder="<?=$this->lang->line('product_category_tax_name')?>" width="100%">
                  <option value=""><?=$this->lang->line('select')?></option>
                  <?php
                    foreach ($tax as $value) {
                  ?>
                    <option value="<?=$value->id;?>" <?php echo set_select('tax_id', $value->id); ?>>
                      <?= $value->tax_name.' ( I : '.$value->igst.' | C : '.$value->cgst.' | S : '.$value->sgst.' )'?>
                    </option>
                  <?php 
                    }
                  ?>
                  <option value="new_tax"><?=$this->lang->line('tax_add_new')?></option>
                </select>
                <span id="err_tax_id" class="error invalid-feedback"></span>
              </div>
            </div>

            <!-- Start Tax Fields -->

            <div class="tax_field_container">
              <div class="form-group row tax_row">
                <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('tax_name')?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" name="tax_name" value="<?=set_value('tax_name') ?>" class="form-control form-control-sm" id="tax_name" placeholder="<?=$this->lang->line('tax_name')?>">
                  <span id="err_tax_name" class="error invalid-feedback"><?=form_error('tax_name');?></span>
                </div>
              </div>
              <div class="form-group row tax_row">
                <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('tax_sgst')?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" name="sgst" value="<?=set_value('sgst') ?>" class="form-control form-control-sm" id="sgst" placeholder="<?=$this->lang->line('tax_sgst')?>">
                  <span id="err_sgst" class="error invalid-feedback"><?=form_error('sgst');?></span>
                </div>
              </div>
              <div class="form-group row tax_row">
                <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('tax_cgst')?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" name="cgst" value="<?=set_value('cgst') ?>" class="form-control form-control-sm" id="cgst" placeholder="<?=$this->lang->line('tax_cgst')?>">
                  <span id="err_cgst" class="error invalid-feedback"><?=form_error('cgst');?></span>
                </div>
              </div>
              <div class="form-group row tax_row">
                <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('tax_igst')?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" name="igst" value="<?=set_value('igst') ?>" class="form-control form-control-sm" id="igst" placeholder="<?=$this->lang->line('tax_igst')?>">
                  <span id="err_igst" class="error invalid-feedback"><?=form_error('igst');?></span>
                </div>
              </div>
            </div>

            <!-- End Tax Fields -->
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('product_category_tax_type')?></label>
              <div class="col-sm-8">
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

            <button type="submit" name="submit" id="productCategorySubmit" tabindex="<?=$tabindex?>" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
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

    const ProductCategoryToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    $('#addProductCategoryForm').submit(function(e){
      e.preventDefault();

      var isError = false;

      $('form#addProductCategoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addProductCategoryForm #err_"+id).text(field+ " field is required.");
            if($('form#addProductCategoryForm #'+id).hasClass('is-valid')){
              $('form#addProductCategoryForm #'+id).removeClass('is-valid');
            }
            $('form#addProductCategoryForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addProductCategoryForm #err_"+id).text("");
            $('form#addProductCategoryForm #'+id).removeClass('is-invalid');
            $('form#addProductCategoryForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        return false;
      }  
      else 
      {
        var formData = $('#addProductCategoryForm').serialize();
        $('#productCategorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $.ajax({
            url: '<?php echo base_url("product_category/add") ?>',
            type: 'POST',
            dataType : 'json',
            data: formData,                       
            success: function (response) {

              if(response.code==1)
              {
                $('#add_product_category_modal').modal('hide');
                $('#productCategorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');

                if($('#product_category_id').length)
                {
                  $('#product_category_id').html('');
                  $('#product_category_id').append('<option value="">Select</option>');
                  
                  for(i=0;i<response['product_categories'].length;i++)
                  { 
                    $('#product_category_id').append('<option value="' + response['product_categories'][i].id + '">' + response['product_categories'][i].name+'</option>');
                  }

                  $('#product_category_id').val(response['id']).attr("selected","selected");  

                  // show_message('success-header',response.message);
                  ProductCategoryToast.fire({
                    type: 'success',
                    title: response.message
                  });  

                }
                else
                {
                  // show_message('success-header',response.message);
                  ProductCategoryToast.fire({
                    type: 'success',
                    title: response.message
                  });  

                  location.reload(true);
                }
              }
              else
              {
                // show_message('failure-header',response.message);
                ProductCategoryToast.fire({
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
    $("form#addProductCategoryForm .field_validation").on("blur change keyup",  function (event){
      var id    = $(this).attr('id');
      var value = $(this).val();
      var field = $(this).attr('placeholder');
      
      if(value==null || value=="")
      {
        $("form#addProductCategoryForm #err_"+id).text(field+ " field is required.");
        $('form#addProductCategoryForm #'+id).removeClass('is-valid');
        $('form#addProductCategoryForm #'+id).addClass('is-invalid');
        return false;
      }
      else
      {
        $("form#addProductCategoryForm #err_"+id).text("");
        $('form#addProductCategoryForm #'+id).removeClass('is-invalid');
        $('form#addProductCategoryForm #'+id).addClass('is-valid');
      }
    });

    $(document).on('hidden.bs.modal', '#add_product_category_modal' ,function () {
      $('form#addProductCategoryForm .field_validation').each(function() {
        var id    = $(this).attr('id');
        $("form#addProductCategoryForm #"+id).val("");
        $("form#addProductCategoryForm #err_"+id).text("");
        $('form#addProductCategoryForm #'+id).removeClass('is-invalid');
        $('form#addProductCategoryForm #'+id).removeClass('is-valid');
      });
    });

    $(document).on('show.bs.modal','#add_product_category_modal',function () {
      $('.tax_field_container').css('display','none');
    });
    
    $(document).on('change','form#addProductCategoryForm #tax_id',function(e){
      
      // Enable visibility of tax field
      $('.tax_field_container').css('display','block');

      var selected_tax_value = $(this).val();
      if(selected_tax_value == 'new_tax')
      {
        $('.tax_field_container').find('.form-control').addClass('field_validation');
      }
      else
      {
        $('.tax_field_container').css('display','none');
        $('.tax_field_container').find('.form-control').removeClass('field_validation'); 
      }
    });
  });
  
</script>