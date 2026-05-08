<style>
  .error-message {
    color: red;
}
</style>
<form role="form" method="post" name="addCreditDebitNoteForm" id="addCreditDebitNoteForm">
  <div class="modal-header text-left">
    <h4 class="modal-title">
      <?php 
        if($cdn_id == '')
          echo $this->lang->line('cdn_add');
        else
          echo $this->lang->line('cdn_edit');
      ?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label required">
            <?=$this->lang->line("cdn_date")?>
          </label>
          <div class="col-sm-9">
            <input type="text" name="cdn_date" id="cdn_date" value="<?=set_value("cdn_date",date('d-m-Y')) ?>" class="form-control form-control-sm datepicker" placeholder="<?=$this->lang->line("cdn_date")?>" readonly="readonly">
            <span id="err_cdn_date" class="error invalid-feedback"></span>
          </div>
        </div>    
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_note_type")?><span class="text-danger">*</span>
          </label>
          <div class="col-sm-9">
            <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_note_type')?>" id="cdn_note_type" name="cdn_note_type">
              <option value="">Select</option>
              <?php 
                $cdn_note_type_array = enum_select('credit_debit_note','cdn_note_type');
                for($i = 0; $i < sizeof($cdn_note_type_array); $i++)
                {
              ?>
                  <option value="<?=$cdn_note_type_array[$i]?>"><?=clean_e_val($cdn_note_type_array[$i])?></option>
              <?php 
                } 
              ?>
            </select>
            <span id="err_cdn_note_type" class="error invalid-feedback"><?=form_error('cdn_note_type');?></span>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_description")?>
          </label>
          <div class="col-sm-9">
            <input type="text" name="cdn_description" id="cdn_description" value="<?=set_value("cdn_description") ?>" class="form-control form-control-sm" placeholder="<?=$this->lang->line("cdn_description")?>">
            <span id="err_cdn_description" class="error invalid-feedback"></span>
          </div>
        </div>            
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_ledger_id")?><span class="text-danger">*</span>
          </label>
          <div class="col-sm-9">
            <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_ledger_id')?>" id="cdn_ledger_id" name="cdn_ledger_id">
              <option value="">Select</option>
            </select>
            <span id="err_cdn_ledger_id" class="error invalid-feedback"></span>
          </div>
        </div>   
            
        <div class="form-group row d-none">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_sale_ids")?>
          </label>
          <div class="col-sm-9">
            <select class="form-control form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_sale_ids')?>" id="cdn_sale_ids" name="cdn_sale_ids[]" multiple="multiple">
              <option value="">Select</option>
            </select>
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="show_sale_product_entries">
              <label class="custom-control-label" for="show_sale_product_entries" style="font-weight:normal !important">Toggle this to Show Products from selected entries</label>
            </div>
            <span id="err_cdn_sale_ids" class="error invalid-feedback"></span>
          </div>
        </div>

        <div class="form-group row d-none">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_sale_return_ids")?>
          </label>
          <div class="col-sm-9">
            <select class="form-control form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_sale_return_ids')?>" id="cdn_sale_return_ids" name="cdn_sale_return_ids[]" multiple="multiple">
              <option value="">Select</option>
            </select>
            
            <!-- <span id="err_cdn_sale_return_ids" class="error invalid-feedback"></span> -->
          </div>
        </div>



        <div class="form-group row d-none">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_purchase_ids")?>
          </label>
          <div class="col-sm-9">
            <select class="form-control  form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_purchase_ids')?>" id="cdn_purchase_ids" name="cdn_purchase_ids[]" multiple="multiple">
              <option value="">Select</option>
            </select>
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="show_purchase_product_entries">
              <label class="custom-control-label" for="show_purchase_product_entries" style="font-weight:normal !important">Toggle this to Show Products from selected entries</label>
            </div>
            <span id="err_cdn_purchase_ids" class="error invalid-feedback"></span>
          </div>
        </div> 

        <div class="form-group row d-none">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_purchase_return_ids")?>
          </label>
          <div class="col-sm-9">
            <select class="form-control  form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_purchase_return_ids')?>" id="cdn_purchase_return_ids" name="cdn_purchase_return_ids[]" multiple="multiple">
              <option value="">Select</option>
            </select>
          
            <!-- <span id="err_cdn_purchase_return_ids" class="error invalid-feedback"></span> -->
          </div>
        </div> 
        
        


        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label required">
            <?=$this->lang->line("cdn_taxable_amount")?>
          </label>
          <div class="col-sm-9">
            <input type="number" name="cdn_taxable_amount" id="cdn_taxable_amount" min="0.01" step="0.01" value="<?=set_value("cdn_taxable_amount",0) ?>" class="form-control form-control-sm field_validation" placeholder="<?=$this->lang->line("cdn_taxable_amount")?>">
            <span id="err_cdn_taxable_amount" class="error invalid-feedback"></span>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label required">
            <?=$this->lang->line("cdn_tax_id")?>
          </label>
          <div class="col-sm-9">
            <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_tax_id')?>" id="cdn_tax_id" name="cdn_tax_id">
              <option value="">Select</option>
              <?php
                foreach($taxes as $tax)
                {
              ?>
                <option value="<?=$tax->id?>"
                  data-igst="<?=$tax->igst?>"
                  data-cgst="<?=$tax->cgst?>"
                  data-sgst="<?=$tax->sgst?>"
                >
                  <?=$tax->tax_name.' ( IGST : '.$tax->igst.', CGST : '.$tax->cgst.', SGST : '.$tax->sgst.' )'?>
                </option>
              <?php
                }
              ?>
            </select>
            <span id="err_cdn_tax_id" class="error invalid-feedback"></span>
          </div>
        </div>  
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label required">
            <?=$this->lang->line("cdn_tax_type")?>
          </label>
          <div class="col-sm-9">
            <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_tax_type')?>" id="cdn_tax_type" name="cdn_tax_type">
              <option value="">Select</option>
              <?php 
                $cdn_tax_type_array = enum_select('credit_debit_note','cdn_tax_type');
                for($i = 0; $i < sizeof($cdn_tax_type_array); $i++)
                {
              ?>
                  <option value="<?=$cdn_tax_type_array[$i]?>"
                    <?php 
                      if($cdn_tax_type_array[$i] == '<?=CREDIT_NOTE_TAX_EXCLUSIVE?>')
                        echo ' selected';  
                    ?>
                  >
                    <?=clean_e_val($cdn_tax_type_array[$i])?>
                  </option>
              <?php 
                } 
              ?>
            </select>
            <span id="err_cdn_tax_type" class="error invalid-feedback"><?=form_error('cdn_tax_type');?></span>
          </div>
        </div>          
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label required">
            <?=$this->lang->line("cdn_tax_amount")?>
          </label>
          <div class="col-sm-3">
            <div class="input-group mb-3">
              <input type="number" class="form-control rounded-0" name="cdn_igst_tax" id="cdn_igst_tax" value="<?=set_value("cdn_igst_tax",0) ?>" placeholder="<?=$this->lang->line("cdn_igst_tax")?>" readonly="readonly" step="0.01">
              <span class="input-group-append">
                <button type="button" class="btn btn-info btn-flat">0 %</button>
                <input type="hidden" name="cdn_igst" id="cdn_igst" value="0">
              </span>
            </div>
            <span id="err_cdn_igst_tax" class="error invalid-feedback"></span>
          </div>
          <div class="col-sm-3">
            <div class="input-group mb-3">
              <input type="number" class="form-control rounded-0" name="cdn_cgst_tax" id="cdn_cgst_tax" value="<?=set_value("cdn_cgst_tax",0) ?>" placeholder="<?=$this->lang->line("cdn_cgst_tax")?>" readonly="readonly" step="0.01">
              <span class="input-group-append">
                <button type="button" class="btn btn-info btn-flat">0 %</button>
                <input type="hidden" name="cdn_cgst" id="cdn_cgst" value="0">
              </span>
            </div>
            <span id="err_cdn_cgst_tax" class="error invalid-feedback"></span>
          </div>
          <div class="col-sm-3">
            <div class="input-group mb-3">
              <input type="number" class="form-control rounded-0" name="cdn_sgst_tax" id="cdn_sgst_tax" value="<?=set_value("cdn_sgst_tax",0) ?>" placeholder="<?=$this->lang->line("cdn_sgst_tax")?>" readonly="readonly" step="0.01">
              <span class="input-group-append">
                <button type="button" class="btn btn-info btn-flat">0 %</button>
                <input type="hidden" name="cdn_sgst" id="cdn_sgst" value="0">
              </span>
            </div>
            <span id="err_cdn_sgst_tax" class="error invalid-feedback"></span>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label required">
            <?=$this->lang->line("cdn_amount")?>
          </label>
          <div class="col-sm-9">
            <input type="number" name="cdn_amount" id="cdn_amount" value="<?=set_value("cdn_amount",0) ?>" class="form-control form-control-sm" placeholder="<?=$this->lang->line("cdn_amount")?>" step="any" step="0.01" readonly="readonly" step="any">
            <span id="err_cdn_amount" class="error invalid-feedback"></span>
          </div>
        </div>

        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_document")?>
          </label>
          <div class="col-sm-9">
            <div class="input-group input-group-sm">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="cdn_document_upload" name="cdn_document_upload" accept=".pdf">
                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
              </div>
              
              <input type="hidden" value="" name="cdn_document" id="cdn_document">
            </div>
            <span id="fileTypeError" class="error-message"></span> 
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-12">
            <div class="table-container">
              <table class="table table-bordered" id="credit_debit_note_items_table">
                <thead>
                  <tr>
                    <th>Invoice No</th>
                    <th>Product Name</th>
                    <th>Tax Rate</th>
                    <th>Quantity</th>
                    <th>Old Price</th>
                    <th>Old Taxable Value</th>
                    <th>Old Tax</th>
                    <th>Old Subtotal</th>
                    <th width="18%">New Price</th>
                    <th>New Taxable Value</th>
                    <th>New Tax</th>
                    <th>New Subtotal</th>
                  </tr>
                </thead>
                <tbody id="credit_debit_note_items_tbody">
                  <tr>
                    <td colspan="13">
                      No records are not selected.
                    </td>
                  </tr>
                </tbody>
                <tfoot >
                  <th>Total</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    

  </div>
  <div class="modal-footer">
    <input type="hidden" name="credit_debit_note_items" value="">
    <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
    <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addCreditDebitNoteSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>