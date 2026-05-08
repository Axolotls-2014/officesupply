<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_inventory')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('warehouse')?>"><?=$this->lang->line('header_warehouse')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('warehouse_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editWarehouseForm" id="editWarehouseForm" method="post" action="<?php echo base_url('warehouse/edit');?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('warehouse_edit')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('warehouse_name')?>
                      <span class="text-danger">*</span> 
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="name" value="<?=set_value('name',$warehouse->name) ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('warehouse_name')?>">
                      <span id="err_name" class="error invalid-feedback"><?=form_error('name');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('warehouse_code')?>
                      <span class="text-danger">*</span> 
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="code" value="<?=set_value('code',$warehouse->code) ?>" class="form-control form-control-sm field_validation <?=(!empty(form_error('code')) ? 'is-invalid' : '')?>" id="code" placeholder="<?=$this->lang->line('warehouse_code')?>">
                      <span id="err_code" class="error invalid-feedback"><?=(!empty(form_error('code')) ? str_ireplace('<p>','',str_ireplace('</p>','',form_error('code'))) : '')?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('warehouse_description')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="description" value="<?=set_value('description',$warehouse->description) ?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line('warehouse_description')?>">
                      <span id="err_description" class="error invalid-feedback"><?=form_error('description');?></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="id" value="<?=$warehouse->id?>">
                  <button type="submit" name="submit" id="warehouseSubmit" class="btn btn-info"><?=$this->lang->line('warehouse_edit')?></button>
                   <a href="<?=base_url('warehouse')?>" class="btn btn-default float-right"><?=$this->lang->line('warehouse_cancel')?></a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">
 
  $(document).ready(function(e){

    $('form#editWarehouseForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#warehouseSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#editWarehouseForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editWarehouseForm #err_"+id).text(field+ " field is required.");
            $('form#editWarehouseForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editWarehouseForm #err_"+id).text("");
            $('form#editWarehouseForm #'+id).removeClass('is-invalid');
            $('form#editWarehouseForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#warehouseSubmit').text('<?=$this->lang->line("warehouse_edit")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#editWarehouseForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editWarehouseForm #err_"+id).text(field+ " field is required.");
          $('form#editWarehouseForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editWarehouseForm #err_"+id).text("");
          $('form#editWarehouseForm #'+id).removeClass('is-invalid');
          $('form#editWarehouseForm #'+id).addClass('is-valid');
        }
    });

    

  });
</script>

