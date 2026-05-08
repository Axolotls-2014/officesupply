<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_setting')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('expense_category/list')?>"><?=$this->lang->line('header_expense_category')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('expense_category_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editexpenseCategoryForm" id="editexpenseCategoryForm" method="post" action="<?php echo base_url('expense_category/edit');?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('expense_category_edit')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_category_name')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="name" value="<?=set_value('name',$expense_category->name) ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('expense_category_name')?>"><?=form_error('name', '<div class="text-danger">', '</div>');?>
                      <span id="err_name" class="error invalid-feedback"><?=form_error('name');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_category_description')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="description" value="<?=set_value('description',$expense_category->description) ?>" class="form-control form-control-sm field_validation" id="symbol" placeholder="<?=$this->lang->line('expense_category_description')?>"><?=form_error('description', '<div class="text-danger">', '</div>');?>
                      <span id="err_description" class="error invalid-feedback"><?=form_error('description');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_category_type')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4 field_validation" name="account_group_id" id="account_group_id" placeholder="<?=$this->lang->line('expense_category_type')?>" width="100%">
                        <option value=""><?=$this->lang->line('select')?></option>
                        <?php
                          foreach ($account_groups as $value) {
                        ?>
                          <option value="<?=$value->id;?>"
                            <?php 
                              if($value->id == $ledger->account_group_id)
                                echo ' selected';
                            ?>
                          >
                            <?= ucfirst($value->group_title);?>
                          </option>
                        <?php 
                          }
                        ?>
                      </select>
                      <span id="err_account_group_id" class="error invalid-feedback"><?=form_error('account_group_id');?></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="id" value="<?=$expense_category->id?>">
                  <button type="submit" name="submit" id="expensecategorySubmit" class="btn btn-info"><?=$this->lang->line('expense_category_edit')?></button>
                   <a href="<?=base_url('expense_category')?>" class="btn btn-default float-right"><?=$this->lang->line('expense_category_cancel')?></a>
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

    $('form#editexpenseCategoryForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#expensecategorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#editexpenseCategoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editexpenseCategoryForm #err_"+id).text(field+ " field is required.");
            $('form#editexpenseCategoryForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editexpenseCategoryForm #err_"+id).text("");
            $('form#editexpenseCategoryForm #'+id).removeClass('is-invalid');
            $('form#editexpenseCategoryForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#expensecategorySubmit').text('<?=$this->lang->line("expense_category_edit")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#editexpenseCategoryForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editexpenseCategoryForm #err_"+id).text(field+ " field is required.");
          $('form#editexpenseCategoryForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editexpenseCategoryForm #err_"+id).text("");
          $('form#editexpenseCategoryForm #'+id).removeClass('is-invalid');
          $('form#editexpenseCategoryForm #'+id).addClass('is-valid');
        }
    });

  });
</script>

