
<form role="form" method="POST" name="importproductForm" id="importproductForm" enctype="multipart/form-data">
  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('import_products');?></h4>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
    <button type="submit" name="submit" id="importproductSubmit" class="btn btn-primary" style="float: right"><?=$this->lang->line('submit')?></button>
    
  </div>
  <div class="modal-body">

      <div class="form-group row">
        <label for="csvfile" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_csv_file")?>
        </label>
        <div class="col-sm-8">
          <div class="input-group input-group-sm">
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="csvfile" name="csvfile" accept=".csv">
              <label class="custom-file-label" id="fileLabel" for="exampleInputFile">Choose file</label>
            </div>
          </div>
          <span id="err_csvfile" class="error invalid-feedback"></span>
        </div>
      </div> 

      <!-- <div class="row">
        <div class="col-md-12" style="opacity: 0.7; border-radius: 5px; background-color: #F8E4A4; font-size: 16px;">
          <div class="m-4">
            Download sample file  <a href="<?=base_url('assets/sample_files/sample_pack_slip_items.csv')?>" target="_blank" class="btn btn-info" style="float:right">Click here to Download</a>  
          </div>
        </div>
      </div> -->

  </div>

  <div class="modal-footer">
    
    <a href="<?=base_url('quotation/export_products')?>" target="_blank" class="btn btn-success">Download Existing products</a>  
    <a href="<?=base_url('assets/sample_files/sample_quotation_product.csv')?>" target="_blank" class="btn btn-info">Download sample file</a>  
  </div>

</form>



  
