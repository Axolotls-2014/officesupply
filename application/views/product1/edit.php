<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_inventory')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('product/list')?>"><?=$this->lang->line('header_product')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('product_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editProductForm" id="editProductForm" method="post" action="<?php echo base_url('product/edit');?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('product_edit')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_name')?>
                      <span class="text-danger">*</span> 
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="name" value="<?=set_value('name',$product->name) ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('product_name')?>"><?=form_error('name', '<div class="text-danger">', '</div>');?>
                      <span id="err_name" class="error invalid-feedback"><!-- <?=form_error('name');?> --></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_description')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="description" value="<?=set_value('description',$product->description) ?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line('product_description')?>"><?=form_error('description', '<div class="text-danger">', '</div>');?>
                      <span id="err_description" class="error invalid-feedback"><!-- <?=form_error('symbol');?> --></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('product_product_category_name')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <?php
                        if($this->permission_model->has_permission('add_product_category'))
                        { 
                      ?>
                      <div class="input-group input-group-sm">
                        <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_product_category_name')?>" width="100%">
                          <?php
                            foreach ($product_categories as $value) {
                          ?>
                            <option value="<?=$value->id;?>"
                              <?php 
                                if($value->id == $product->product_category_id)
                                  echo ' selected';
                              ?>
                            >
                              <?= $value->name;?>
                            </option>
                          <?php 
                            }
                          ?>
                        </select>
                        <span class="input-group-append">
                          <button type="button" class="btn btn-info btn-flat add_product_category_modal" data-toggle="modal" data-target="#add_product_category_modal" data-tt="tooltip" title="<?=$this->lang->line('product_category_add')?>"><i class="fas fa-plus"></i></button>
                        </span>
                      </div>
                      <?php
                        }
                        else
                        {
                      ?>
                      <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_product_category_name')?>" width="100%">
                        <?php
                          foreach ($product_categories as $value) {
                        ?>
                          <option value="<?=$value->id;?>"
                            <?php 
                              if($value->id == $product->product_category_id)
                                echo ' selected';
                            ?>
                          >
                            <?= $value->name;?>
                          </option>
                        <?php 
                          }
                        ?>
                      </select>
                      <?php
                        } 
                      ?>
                      
                      <span id="err_product_category_id" class="error invalid-feedback"><?=form_error('product_category_id');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_hsn')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="hsn" value="<?=set_value('hsn',$product->hsn) ?>" class="form-control form-control-sm field_validation" id="hsn" placeholder="<?=$this->lang->line('product_hsn')?>">
                      <span id="err_hsn" class="error invalid-feedback"><?=form_error('hsn');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_markup')?>
                       <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="number" name="markup" value="<?=set_value('markup',$product->markup) ?>" class="form-control form-control-sm field_validation" id="markup" placeholder="<?=$this->lang->line('product_markup')?>" step="0.1">
                      <span id="err_markup" class="error invalid-feedback"><?=form_error('markup');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_alert_quantity')?>
                       <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="number" name="alert_quantity" value="<?=set_value('alert_quantity',$product->alert_quantity)?>" class="form-control form-control-sm field_validation" id="alert_quantity" placeholder="<?=$this->lang->line('product_alert_quantity')?>" step="0.1">
                      <span id="err_alert_quantity" class="error invalid-feedback"><?=form_error('alert_quantity');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_uom')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4" name="uom_id" id="uom_id" placeholder="<?=$this->lang->line('product_uom')?>" width="100%">
                        <option value=""><?=$this->lang->line('select')?></option>
                        <?php
                          foreach ($uoms as $value) {
                        ?>
                          <option value="<?=$value->id;?>" 
                            <?php 
                              if(isset($uom_id))
                              {
                                if($uom_id == $value->id)
                                {
                                  echo ' selected';
                                }
                              }
                              else
                              {
                                if($value->id == $product->uom_id)
                                {
                                  echo ' selected';
                                }
                              }
                            ?>
                          >
                            <?= $value->name.' ('.$value->uom.')';?>
                          </option>
                        <?php 
                          }
                        ?>
                      </select>
                      <span id="err_uom_id" class="error invalid-feedback"><?=form_error('uom_id');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_status')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4 field_validation" name="status" id="status" placeholder="<?=$this->lang->line('product_status')?>" width="100%">
                        <option value="<?=PRODUCT_STATUS_ACTIVE?>"
                          <?php 
                            if($product->status == PRODUCT_STATUS_ACTIVE)
                              echo ' selected';
                          ?>
                        >
                          <?=ucfirst(PRODUCT_STATUS_ACTIVE)?>
                        </option>
                        <option value="<?=PRODUCT_STATUS_INACTIVE?>"
                          <?php 
                            if($product->status == PRODUCT_STATUS_INACTIVE)
                              echo ' selected';
                          ?>
                        >
                          <?=ucfirst(PRODUCT_STATUS_INACTIVE)?>
                        </option>
                        
                      </select>
                      <span id="err_status" class="error invalid-feedback"><?=form_error('status')?></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="id" value="<?=$product->id?>">
                  <button type="submit" name="submit" id="productSubmit" class="btn btn-info"><?=$this->lang->line('product_edit')?></button>
                   <a href="<?=base_url('product')?>" class="btn btn-default float-right"><?=$this->lang->line('product_cancel')?></a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php 
  $this->load->view('layout/footer');
?>

<div class="example-modal">
  <div class="modal fade" id="add_product_category_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
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

<script type="text/javascript">
 
  $(document).ready(function(e){

    const product_categoryToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    $(document).on('click', ".add_product_category_modal" ,function(){

     $.ajax({
        url: "<?php echo base_url('product_category/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_product_category_modal').find('.modal-content').html(data.add_product_category_modal_body);
          $('#add_product_category_modal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','#addProduct_categoryForm',function(e){
      
      e.preventDefault();

      $('#addProduct_categorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addProduct_categoryForm').serialize();

      var isError = false;

      $('form#addProduct_categoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addProduct_categoryForm  #err_"+id).text(field+ " field is required.");
            $('form#addProduct_categoryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addProduct_categoryForm #err_"+id).text("");
            $('form#addProduct_categoryForm #'+id).removeClass('is-invalid');
            $('form#addProduct_categoryForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('product_category/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
            $('#add_product_category_modal').modal('hide');
              $('form#addProduct_categoryForm #addProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
             /* initialize_datatable();*/
              if($('form#editProductForm #product_category_id').length)
              {
                $('form#editProductForm #product_category_id').html('');
                $('form#editProductForm #product_category_id').append('<option value="">Select</option>');
                
                for(i=0;i<response['product_categories'].length;i++)
                { 
                  $('form#editProductForm #product_category_id').append('<option value="' + response['product_categories'][i].id + '">' + response['product_categories'][i].name +'</option>');
                }

                $('form#editProductForm #product_category_id').val(response['id']).attr("selected","selected");


                product_categoryToast.fire({
                  type: 'success',
                  title: response.message
                });
               }
                else
                {
                  // show_message('success-header',response.message);
                  product_categoryToast.fire({
                    type: 'success',
                    title: response.message
                  });  

                  location.reload(true);
                }
            }
            else
            {
              product_categoryToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on('click', ".add_tax_modal" ,function(){

     $.ajax({
        url: "<?php echo base_url('tax/add')?>",
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

      $('#taxSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
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
        var formData = $('#addTaxForm').serialize();
        $('#taxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
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
                /*$('#add_product_category_modal').modal('hide');*/
                $('#taxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
                
                if($('form#addProduct_categoryForm #tax_id').length)
                {
                  $('form#addProduct_categoryForm #tax_id').html('');
                  $('form#addProduct_categoryForm #tax_id').append('<option value="">Select</option>');
                  
                  for(i=0;i<response['taxes'].length;i++)
                  { 
                    $('form#addProduct_categoryForm #tax_id').append('<option value="' + response['taxes'][i].id + '">' + response['taxes'][i].tax_name + ' ( I : ' + response['taxes'][i].igst + ' | C : ' +response['taxes'][i].cgst + ' | S : ' + response['taxes'][i].sgst + ' ) ' +'</option>');

                    /*<?=$value->tax_name.' ( I : '.$value->igst.' | C : '.$value->cgst.' | S : '.$value->sgst.' )'?>*/
                  }

                  $('form#addProduct_categoryForm #tax_id').val(response['id']).attr("selected","selected");  

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
              TaxToast.fire({
                type: 'error',
                title: response.message
              });
              $('#taxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
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


    $('form#editProductForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#productSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#editProductForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editProductForm #err_"+id).text(field+ " field is required.");
            $('form#editProductForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editProductForm #err_"+id).text("");
            $('form#editProductForm #'+id).removeClass('is-invalid');
            $('form#editProductForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#productSubmit').text('<?=$this->lang->line("product_save")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#editProductForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editProductForm #err_"+id).text(field+ " field is required.");
          $('form#editProductForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editProductForm #err_"+id).text("");
          $('form#editProductForm #'+id).removeClass('is-invalid');
          $('form#editProductForm #'+id).addClass('is-valid');
        }
    });

  });
</script>

