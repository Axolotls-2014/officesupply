  
<form role="form" method="POST" name="importCustomerForm" id="importCustomerForm" enctype="multipart/form-data" action="<?=base_url('customer/import_customer')?>">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('customer_import_customer');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

      <div class="row">
        <div class="col-md-12">
          <div class="form-group mt-2">
            <div class="row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line("customer_csv_file")?>
              </label>
              <div class="col-sm-8">
                <div class="input-group input-group-sm">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="csvfile" name="csvfile" accept=".csv">
                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                  </div>
                </div>
                <span id="err_csvfile" class="error invalid-feedback"></span>
              </div>
            </div>     
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12" style="opacity: 0.7; border-radius: 5px; background-color: #F8E4A4; font-size: 16px;">
          <div class="m-4">
            Download sample file  <a href="<?=base_url('assets/sample_files/sample_customer.csv')?>" target="_blank" class="btn btn-info" style="float:right">Click here to Download</a>  
          </div>
        </div>
      </div>

      
      
  </div>

  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="importProductSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
