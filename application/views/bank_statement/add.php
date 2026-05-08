<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_account')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('cash_bank_entry')?>"><?=$this->lang->line('header_cash_bank_entry')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('cash_bank_entry_add')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="addcashBankEntryForm" id="addcashBankEntryForm" method="post" action="">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('cash_bank_entry_add')?></h3>
                </div>
                <div class="card-body">

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('cash_bank_entry_voucher_type')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="voucher_type"  value="<?=set_value('voucher_type') ?>" class="form-control form-control-sm field_validation" id="voucher_type" placeholder="<?=$this->lang->line('cash_bank_entry_voucher_type')?>"><?=form_error('voucher_type', '<div class="text-danger">', '</div>');?>
                      <span id="err_voucher_type" class="error invalid-feedback"><!-- <?=form_error('voucher_type');?> --></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('cash_bank_entry_voucher_date')?>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="voucher_date"  value="<?=set_value('voucher_date') ?>" class="form-control form-control-sm datepicker" id="voucher_date" placeholder="<?=$this->lang->line('cash_bank_entry_voucher_date')?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('cash_bank_entry_reference_no')?>
                       <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="reference_no" value="<?=set_value('reference_no') ?>" class="form-control form-control-sm field_validation" id="reference_no" placeholder="<?=$this->lang->line('cash_bank_entry_reference_no')?>"><?=form_error('reference_no', '<div class="text-danger">', '</div>');?>
                      <span id="err_reference_no" class="error invalid-feedback"><!-- <?=form_error('reference_no');?> --></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('cash_bank_entry_from_account_id')?>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="from_account_id"  value="<?=set_value('from_account_id') ?>" class="form-control form-control-sm" id="from_account_id" placeholder="<?=$this->lang->line('cash_bank_entry_from_account_id')?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('cash_bank_entry_to_account_id')?>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="to_account_id"  value="<?=set_value('to_account_id') ?>" class="form-control form-control-sm" id="to_account_id" placeholder="<?=$this->lang->line('cash_bank_entry_to_account_id')?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('cash_bank_entry_amount')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="amount"  value="<?=set_value('amount') ?>" class="form-control form-control-sm field_validation" id="amount" placeholder="<?=$this->lang->line('cash_bank_entry_amount')?>"><?=form_error('amount', '<div class="text-danger">', '</div>');?>
                      <span id="err_amount" class="error invalid-feedback"><!-- <?=form_error('amount');?> --></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('cash_bank_entry_narration')?>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="narration"  value="<?=set_value('narration') ?>" class="form-control form-control-sm" id="narration" placeholder="<?=$this->lang->line('cash_bank_entry_narration')?>">
                    </div>
                  </div>



                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <button type="submit" name="submit" id="currencySubmit" class="btn btn-info"><?=$this->lang->line('currency_save')?></button>
                  <a href="<?=base_url('currency')?>" class="btn btn-default float-right"><?=$this->lang->line('currency_cancel')?></a>
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

    $('form#addcashBankEntryForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#cashBankEntrySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#addcashBankEntryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addcashBankEntryForm  #err_"+id).text(field+ " field is required.");
            $('form#addcashBankEntryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addcashBankEntryForm #err_"+id).text("");
            $('form#addcashBankEntryForm #'+id).removeClass('is-invalid');
            $('form#addcashBankEntryForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#cashBankEntrySubmit').text('<?=$this->lang->line("currency_save")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#addcashBankEntryForm  .field_validation").on("blur keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addcashBankEntryForm #err_"+id).text(field+ " field is required.");
          $('form#addcashBankEntryForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addcashBankEntryForm #err_"+id).text("");
          $('form#addcashBankEntryForm #'+id).removeClass('is-invalid');
          $('form#addcashBankEntryForm #'+id).addClass('is-valid');
        }
    });

  });
</script>

