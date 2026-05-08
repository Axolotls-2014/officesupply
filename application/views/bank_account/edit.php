<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><a href="<?=base_url('bank_account')?>"><?=$this->lang->line('header_bank_account')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('bank_account_add')?></li>
          </ol>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <form class="form-horizontal" name="editBankAccount" id="editBankAccount" method="post" action="">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title"><?=$this->lang->line('bank_account_add')?></h3>
              </div>
              <div class="card-body">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">
                    <?=$this->lang->line('bank_account_name')?>
                    <span class="text-danger">*</span>
                  </label>
                  <div class="col-sm-4">
                    <input type="text" name="account_name" value="<?=set_value('account_name',$bank_account->account_name) ?>" class="form-control form-control-sm field_validation" id="account_name" placeholder="<?=$this->lang->line('bank_account_name')?>">
                    <span id="err_account_name" class="error invalid-feedback"><!-- <?=form_error('account_name');?> --></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">
                    <?=$this->lang->line('bank_account_account_type')?>
                    <span class="text-danger">*</span>
                  </label>
                  <div class="col-sm-4">
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="account_type_saving" name="account_type" value="0" <?=($bank_account->account_type == 0)? 'checked':''?>>
                      <label for="account_type_saving" class="normal_font">
                        <?=$this->lang->line('bank_account_account_type_saving')?>
                      </label>
                    </div>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="account_type_current" name="account_type" value="1" <?=($bank_account->account_type == 1)? 'checked': ''?>>
                      <label for="account_type_current" class="normal_font">
                        <?=$this->lang->line('bank_account_account_type_current')?>
                      </label>
                    </div>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="account_type_credit" name="account_type" value="2" <?=($bank_account->account_type == 2)? 'checked': ''?>>
                      <label for="account_type_credit" class="normal_font">
                        <?=$this->lang->line('bank_account_account_type_credit')?>
                      </label>
                    </div>
                    <span id="err_account_type" class="error invalid-feedback"><!-- <?=form_error('account_type');?> --></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">
                    <?=$this->lang->line('bank_account_number')?>
                    <span class="text-danger">*</span>
                  </label>
                  <div class="col-sm-4">
                    <input type="text" name="account_number" value="<?=set_value('account_number',$bank_account->account_number) ?>" class="form-control form-control-sm field_validation" id="account_number" placeholder="<?=$this->lang->line('bank_account_number')?>">
                    <span id="err_account_number" class="error invalid-feedback"><!-- <?=form_error('account_number');?> --></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">
                    <?=$this->lang->line('bank_account_bank_name')?>
                    <span class="text-danger">*</span>
                  </label>
                  <div class="col-sm-4">
                    <input type="text" name="bank_name" value="<?=set_value('bank_name',$bank_account->bank_name) ?>" class="form-control form-control-sm field_validation" id="bank_name" placeholder="<?=$this->lang->line('bank_account_bank_name')?>">
                    <span id="err_bank_name" class="error invalid-feedback"><!-- <?=form_error('bank_name');?> --></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">
                    <?=$this->lang->line('bank_account_ifsc')?>
                    <span class="text-danger" id="ifsc-required">*</span>
                  </label>
                  <div class="col-sm-4">
                    <input type="text" name="ifsc" value="<?=set_value('ifsc',$bank_account->ifsc) ?>" class="form-control form-control-sm field_validation" id="ifsc" placeholder="<?=$this->lang->line('bank_account_ifsc')?>">
                    <span id="err_ifsc" class="error invalid-feedback"><!-- <?=form_error('ifsc');?> --></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">
                    <?=$this->lang->line('bank_account_description')?>
                  </label>
                  <div class="col-sm-4">
                    <input type="text" name="description" value="<?=set_value('description',$bank_account->description) ?>" class="form-control form-control-sm" id="description" placeholder="<?=$this->lang->line('bank_account_description')?>">
                    <span id="err_description" class="error invalid-feedback"><!-- <?=form_error('description');?> --></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">
                    <?=$this->lang->line('bank_account_opening_balance')?>
                    <span class="text-danger">*</span>
                  </label>
                  <div class="col-sm-4">
                    <input type="number" name="opening_balance" value="<?=set_value('opening_balance',$bank_account->opening_balance) ?>" class="form-control form-control-sm field_validation" id="opening_balance" placeholder="<?=$this->lang->line('bank_account_opening_balance')?>" step="0.01" readonly>
                    <span class="text-danger"><?=$this->lang->line('bank_account_op_bal_restriction')?></span>
                    <span id="err_opening_balance" class="error invalid-feedback"><!-- <?=form_error('opening_balance');?> --></span>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" value="<?=$bank_account->id?>">
                <button type="submit" name="submit" id="bankAccountSubmit" class="btn btn-info"><?=$this->lang->line('bank_account_save')?></button>
                <a href="<?=base_url('currency')?>" class="btn btn-default float-right"><?=$this->lang->line('bank_account_cancel')?></a>
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

    function toggleIfscValidation() {
      if ($('input[name="account_type"]:checked').val() == '2') { 
        $('#ifsc-required').hide();  // Hide the danger span
        $('#ifsc').removeClass('field_validation is-invalid is-valid');
        $('#err_ifsc').text('');
      } else {
        $('#ifsc-required').show();  // Show the danger span
        $('#ifsc').addClass('field_validation');
      }
    }

    // Initial check on page load
    toggleIfscValidation();

    // Attach the check function to the change event of the account type radio buttons
    $('input[name="account_type"]').on('change', function() {
      toggleIfscValidation();
    });

    $('form#editBankAccount').submit(function(e){
      var isError = false;
      $('#bankAccountSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#editBankAccount .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editBankAccount  #err_"+id).text(field+ " field is required.");
            $('form#editBankAccount  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editBankAccount #err_"+id).text("");
            $('form#editBankAccount #'+id).removeClass('is-invalid');
            $('form#editBankAccount #'+id).addClass('is-valid');
          }
      });

      if(isError == true)
      {
        $('#bankAccountSubmit').text('<?=$this->lang->line("bank_account_save")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }  
    });

    $("form#editBankAccount  .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#editBankAccount #err_"+id).text(field+ " field is required.");
          $('form#editBankAccount #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editBankAccount #err_"+id).text("");
          $('form#editBankAccount #'+id).removeClass('is-invalid');
          $('form#editBankAccount #'+id).addClass('is-valid');
        }
    });

  });
</script>