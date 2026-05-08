
<div class="example-modal">
  <div class="modal fade" id="add_expense_category_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" method="post" name="addExpenseCategoryForm" id="addExpenseCategoryForm">
          <div class="modal-header text-left">
            <h4 class="modal-title"><?php echo $this->lang->line('expense_category_add');?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4>
              <?php echo $this->lang->line('lbl_cust_delete_modal');?>
            </h4>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('expense_category_name')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="text" name="name" value="<?=set_value('name') ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('expense_category_name')?>"><?=form_error('name', '<div class="text-danger">', '</div>');?>
                <span id="err_name" class="error invalid-feedback"><?=form_error('name');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('expense_category_description')?><span class="text-danger">*</span></label>
              <div class="col-sm-6">
                <input type="text" name="description" value="<?=set_value('description') ?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line('expense_category_description')?>">
                <span id="err_description" class="error invalid-feedback"><?=form_error('description');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('expense_category_type')?><span class="text-danger">*</span></label>
              <div class="col-sm-6">
                <select class="form-control form-control-sm select2bs4 field_validation" name="account_group_id" id="account_group_id" placeholder="<?=$this->lang->line('expense_category_type')?>" width="100%">
                  <option value=""><?=$this->lang->line('select')?></option>
                  <?php
                    foreach ($account_groups as $value) {
                  ?>
                    <option value="<?=$value->id;?>" <?php echo set_select('expense_category_type', $value->id); ?>>
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
          <div class="modal-footer">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <button type="submit" name="submit" id="expenseCategorySubmit" class="btn btn-primary">Submit</button>
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

    const ExpenseCategoryToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    $('#expenseCategorySubmit').click(function(e){
      e.preventDefault();

      var isError = false;

      $('form#addExpenseCategoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addExpenseCategoryForm #err_"+id).text(field+ " field is required.");
            if($('form#addExpenseCategoryForm #'+id).hasClass('is-valid')){
              $('form#addExpenseCategoryForm #'+id).removeClass('is-valid');
            }
            $('form#addExpenseCategoryForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addExpenseCategoryForm #err_"+id).text("");
            $('form#addExpenseCategoryForm #'+id).removeClass('is-invalid');
            $('form#addExpenseCategoryForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        return false;
      }  
      else 
      {
        var formData = $('#addExpenseCategoryForm').serialize();
        $('#expenseCategorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $.ajax({
            url: '<?php echo base_url("expense_category/add") ?>',
            type: 'POST',
            dataType : 'json',
            data: formData,                       
            success: function (response) {

              if(response.code==1)
              {
                $('#add_expense_category_modal').modal('hide');
                $('#expenseCategorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
                
                $('#expense_category_id').html('');
                $('#expense_category_id').append('<option value="">Select</option>');
                
                for(i=0;i<response['expense_categories'].length;i++)
                { 
                  $('#expense_category_id').append('<option value="' + response['expense_categories'][i].id + '">' + response['expense_categories'][i].name+'</option>');
                }

                $('#expense_category_id').val(response['id']).attr("selected","selected");

                if($('#expense_category_id').hasClass('is-invalid'))
                {
                  $('#expense_category_id').addClass('is-valid');
                  $('#expense_category_id').removeClass('is-invalid');
                  $('#err_expense_category_id').text("").fadeOut('slow');
                }

                // show_message('success-header',response.message);
                ExpenseCategoryToast.fire({
                  type: 'success',
                  title: response.message
                });
              }
              else
              {
                // show_message('failure-header',response.message);
                $('#expenseCategorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
                ExpenseCategoryToast.fire({
                  type: 'error',
                  title: response.message
                });
              }
            },
            error: function () 
            { 
              $('#expenseCategorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');

              show_message('failure-header','Please contact the administrator if you are keep facing this issue.');   
            }
        });
      }    
    });

    $("form#addExpenseCategoryForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addExpenseCategoryForm #err_"+id).text(field+ " field is required.");
          if($('form#addExpenseCategoryForm #'+id).hasClass('is-valid')){
            $('form#addExpenseCategoryForm #'+id).removeClass('is-valid');
          }
          $('form#addExpenseCategoryForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addExpenseCategoryForm #err_"+id).text("");
          $('form#addExpenseCategoryForm #'+id).removeClass('is-invalid');
          $('form#addExpenseCategoryForm #'+id).addClass('is-valid');
        }
    });

    $('#add_expense_category_modal').on('hidden.bs.modal', function () {

      $('form#addExpenseCategoryForm .field_validation').each(function() {
        var id    = $(this).attr('id');
        $("form#addExpenseCategoryForm #"+id).val("");
        $("form#addExpenseCategoryForm #err_"+id).text("");
        $('form#addExpenseCategoryForm #'+id).removeClass('is-invalid');
        $('form#addExpenseCategoryForm #'+id).removeClass('is-valid');

      });
    });

    $('#add_expense_category_modal').on('shown.bs.modal', function () {
      $("form#addExpenseCategoryForm #name").focus();
    });
  });

  
</script>