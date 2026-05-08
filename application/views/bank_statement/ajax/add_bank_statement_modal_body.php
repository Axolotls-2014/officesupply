  
<form role="form" method="post" name="addbankStatementForm" id="addbankStatementForm" enctype="multipart/form-data">
  <div class="modal-header text-left">
    <h4 class="modal-title">
      <?php 
        if($bank_statement_id == '')
          echo $this->lang->line('bank_statement_add');
        else
          echo $this->lang->line('bank_statement_edit');
      ?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">
    
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line('bank_statement_bank_account_id')?>
      </label>
      <div class="col-sm-9">
        <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('bank_statement_bank_account_id')?>" id="bank_account_id" name="bank_account_id">
          <option value="">Select</option>
          <?php
            foreach ($bank_accounts as $value) {
          ?>
            <option value="<?=$value->id;?>">
              <?= $value->account_name.' - '.$value->account_number.' - '.$value->ifsc;?>
            </option>
          <?php 
            }
          ?>
        </select>
        <span id="err_bank_account_id" class="error invalid-feedback"></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line('bank_statement')?>
      </label>
      <div class="col-sm-9">
        <input type="file" class="form-control field_validation" id="upload_statement" name="upload_statement" accept=".xlsx" placeholder="Statement">
        <input type="hidden" value="" name="statement" id="statement">
        <span id="err_upload_statement" class="error invalid-feedback"></span>
      </div>
    </div>       
    

    <div class="form-group row">
      <div class="col-md-12" style="opacity: 0.7; border-radius: 5px; background-color: #F8E4A4; font-size: 16px;">
        <div class="m-4">
          Download sample file  <a href="<?=base_url('assets/sample_files/sample_bank_statement.xlsx')?>" target="_blank" class="btn btn-info" style="float:right">Click here to Download</a>  
        </div>
      </div>
    </div>

  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addbankStatementSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>