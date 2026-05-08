<style>
  .error-message {
    color: red;
}
</style>
<form role="form" method="post" name="editCreditDebitNoteForm" id="editCreditDebitNoteForm">
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
            <input type="text" name="cdn_date" id="cdn_date" value="<?=set_value("cdn_date",(($credit_debit_note->cdn_date != '') ? date('d-m-Y',strtotime($credit_debit_note->cdn_date)) : '')) ?>" class="form-control form-control-sm datepicker datepicker" placeholder="<?=$this->lang->line("cdn_date")?>" readonly="readonly">
            <span id="err_cdn_date" class="error invalid-feedback"></span>
          </div>
        </div>

        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label required">
            <?=$this->lang->line("cdn_note_type")?>
          </label>
          <div class="col-sm-9">
            <!-- <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_note_type')?>" id="cdn_note_type" name="cdn_note_type">
              <option value="">Select</option>
              <?php 
                $cdn_note_type_array = enum_select('credit_debit_note','cdn_note_type');
                for($i = 0; $i < sizeof($cdn_note_type_array); $i++)
                {
              ?>
                  <option value="<?=$cdn_note_type_array[$i]?>"
                    <?php 
                      if($credit_debit_note->cdn_note_type == $cdn_note_type_array[$i])
                        echo ' selected';
                    ?>
                  ><?=clean_e_val($cdn_note_type_array[$i])?></option>
              <?php 
                } 
              ?>
            </select> -->
            <input type="text" name="cdn_note_type" class="form-control form-control-sm" id="cdn_note_type" value="<?=$credit_debit_note->cdn_note_type?>" readonly="readonly">
            <span id="err_cdn_note_type" class="error invalid-feedback"><?=form_error('cdn_note_type');?></span>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_description")?>
          </label>
          <div class="col-sm-9">
            <input type="text" name="cdn_description" id="cdn_description" value="<?=set_value("cdn_description",$credit_debit_note->cdn_description) ?>" class="form-control form-control-sm" placeholder="<?=$this->lang->line("cdn_description")?>">
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
              <?php 

                $record_type = '';
                if($credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_DEBIT || $credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)
                  $record_type = SUNDRY_CREDITORS_GROUP;
                else
                  $record_type = SUNDRY_DEBTORS_GROUP;

                foreach($ledger_accounts as $ledger_account)
                {
              ?>
                  <option value="<?=$ledger_account->ledger_id?>"

                        <?php 
                          if($record_type == SUNDRY_CREDITORS_GROUP)
                          {
                        ?> 
                        data-country_id="<?=$ledger_account->country_id?>"                   
                        data-state_id="<?=$ledger_account->state_id?>"                   
                        <?php 
                          }
                          else
                          {
                        ?>
                        data-country_id="<?=$ledger_account->shipping_country_id?>"                   
                        data-state_id="<?=$ledger_account->shipping_state_id?>"                   
                        <?php
                          }
                        ?>

                        <?php 
                          if($ledger_account->ledger_id == $credit_debit_note->cdn_ledger_id)
                            echo ' selected';
                        ?>
                  >
                    
                    <?php 
                      if($record_type == SUNDRY_CREDITORS_GROUP)
                        echo $ledger_account->company_name.' - '.$ledger_account->state_name;
                      else
                        echo $ledger_account->customer_name.' - '.$ledger_account->shipping_state_name;
                    ?>
                  </option>
              <?php
                }
              ?>
            </select>
            <span id="err_cdn_ledger_id" class="error invalid-feedback"></span>
          </div>
        </div>    

        <div class="form-group row <?php 
          if(!in_array($credit_debit_note->cdn_note_type,array(CREDIT_NOTE_TYPE_CREDIT,CREDIT_NOTE_TYPE_ADVANCE_REFUND_VOUCHER,CREDIT_NOTE_TYPE_DEBIT_CUSTOMER)))
            echo ' d-none';
        ?>">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_sale_ids")?>
          </label>
          <div class="col-sm-9">
            <select class="form-control form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_sale_ids')?>" id="cdn_sale_ids" name="cdn_sale_ids[]" multiple="multiple">
              <option value="">Select</option>
              <?php 
                if(!is_null($sales)) 
                {
                  $sales_arr  = (($credit_debit_note->cdn_sale_ids != '') ? explode(",",$credit_debit_note->cdn_sale_ids) : array());
                  foreach ($sales as $sl) {
              ?>
                    <option value="<?=$sl->id?>"
                      <?php
                        if(in_array($sl->id,$sales_arr))
                          echo ' selected';
                      ?>
                    >
                      <?=$sl->reference_no?>
                    </option>
              <?php
                  }
                }
              ?>
            </select>
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="show_sale_product_entries" <?php 
                if(sizeof($records) > 0)
                  echo ' checked';
              ?>>
              <label class="custom-control-label" for="show_sale_product_entries" style="font-weight:normal !important">Toggle this to Show Products from selected entries</label>
            </div>
            <span id="err_cdn_sale_ids" class="error invalid-feedback"></span>            
          </div>
        </div>
          
        <div class="form-group row <?php 
          if(!in_array($credit_debit_note->cdn_note_type,array(CREDIT_NOTE_TYPE_DEBIT_CUSTOMER)))
            echo ' d-none';
        ?>">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_sale_return_ids")?>
          </label>
          <div class="col-sm-9">
            <select class="form-control form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_sale_return_ids')?>" id="cdn_sale_return_ids" name="cdn_sale_return_ids[]" multiple="multiple">
              <option value="">Select</option>
              <?php 
                if(!is_null($sales_return)) 
                {
                  $sales_return_arr  = (($credit_debit_note->cdn_sale_return_ids != '') ? explode(",",$credit_debit_note->cdn_sale_return_ids) : array());
                  foreach ($sales_return as $sl) {
              ?>
                    <option value="<?=$sl->id?>"
                      <?php
                        if(in_array($sl->id,$sales_return_arr))
                          echo ' selected';
                      ?>
                    >
                      <?=$sl->reference_no?>
                    </option>
              <?php
                  }
                }
              ?>
            </select>
            
            <span id="err_cdn_sale_return_ids" class="error invalid-feedback"></span>            
          </div>
        </div>


        <div class="form-group row <?php 
          if(!in_array($credit_debit_note->cdn_note_type,array(CREDIT_NOTE_TYPE_DEBIT,CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)))
            echo ' d-none';
        ?>">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_purchase_ids")?>
          </label>
          <div class="col-sm-9">
            <select class="form-control form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_purchase_ids')?>" id="cdn_purchase_ids" name="cdn_purchase_ids[]" multiple="multiple">
              <option value="">Select</option>
              <?php 
                if(!is_null($purchases)) 
                {
                  $purchases_arr  = (($credit_debit_note->cdn_purchase_ids != '') ? explode(",",$credit_debit_note->cdn_purchase_ids) : array());
                  foreach ($purchases as $pr) {
              ?>
                    <option value="<?=$pr->id?>"
                      <?php
                        if(in_array($pr->id,$purchases_arr))
                          echo ' selected';
                      ?>
                    >
                      <?=$pr->reference_no?>
                    </option>
              <?php
                  }
                }
              ?>
            </select>
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="show_purchase_product_entries" <?php 
                if(sizeof($records) > 0)
                  echo ' checked';
              ?>>
              <label class="custom-control-label" for="show_purchase_product_entries" style="font-weight:normal !important">Toggle this to Show Products from selected entries</label>
            </div>
            <span id="err_cdn_purchase_ids" class="error invalid-feedback"></span>
          </div>
        </div>
        
        <div class="form-group row <?php 
          if(!in_array($credit_debit_note->cdn_note_type,array(CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)))
            echo ' d-none';
        ?>">
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_purchase_return_ids")?>
          </label>
          <div class="col-sm-9">
            <select class="form-control form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_purchase_return_ids')?>" id="cdn_purchase_return_ids" name="cdn_purchase_return_ids[]" multiple="multiple">
              <option value="">Select</option>
              <?php 
                if(!is_null($purchase_return)) 
                {
                  $purchase_return_arr  = (($credit_debit_note->cdn_purchase_return_ids != '') ? explode(",",$credit_debit_note->cdn_purchase_return_ids) : array());
                  foreach ($purchase_return as $pr) {
              ?>
                    <option value="<?=$pr->id?>"
                      <?php
                        if(in_array($pr->id,$purchase_return_arr))
                          echo ' selected';
                      ?>
                    >
                      <?=$pr->reference_no?>
                    </option>
              <?php
                  }
                }
              ?>
            </select>
            
            <span id="err_cdn_purchase_return_ids" class="error invalid-feedback"></span>
          </div>
        </div>
        
        
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label required">
            <?=$this->lang->line("cdn_taxable_amount")?>
          </label>
          <div class="col-sm-9">
            <input type="number" name="cdn_taxable_amount" id="cdn_taxable_amount" min="0.01" step="0.01" value="<?=set_value("cdn_taxable_amount",$credit_debit_note->cdn_taxable_amount) ?>"  class="form-control form-control-sm field_validation" placeholder="<?=$this->lang->line("cdn_taxable_amount")?>" step="0.01">
            <span id="err_cdn_taxable_amount" class="error invalid-feedback"></span>
          </div>
        </div>
        <div class="form-group row
          <?php 
            if(sizeof($records)>0)
                echo ' d-none';
          ?>
        ">
          <label for="inputEmail3" class="col-sm-3 col-form-label required">
            <?=$this->lang->line("cdn_tax_id")?>
          </label>
          <div class="col-sm-9">
            <select class="form-control <?php 
            if(!(sizeof($records)>0))
                echo ' field_validation';
          ?> form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_tax_id')?>" id="cdn_tax_id" name="cdn_tax_id">
              <option value="">Select</option>
              <?php
                foreach($taxes as $tax)
                {
              ?>
                <option value="<?=$tax->id?>"
                  data-igst="<?=$tax->igst?>"
                  data-cgst="<?=$tax->cgst?>"
                  data-sgst="<?=$tax->sgst?>"

                  <?php 
                    if($credit_debit_note->cdn_tax_id == $tax->id)
                      echo ' selected';
                  ?>
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
          <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("cdn_tax_type")?><span class="text-danger">*</span>
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
                      if($credit_debit_note->cdn_tax_type == $cdn_tax_type_array[$i])
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
              <input type="text" class="form-control rounded-0" name="cdn_igst_tax" id="cdn_igst_tax" value="<?=set_value("cdn_igst_tax",$credit_debit_note->cdn_igst_tax) ?>" placeholder="<?=$this->lang->line("cdn_igst_tax")?>" readonly="readonly">
              <span class="input-group-append  <?php 
                if(sizeof($records)>0)
                    echo ' d-none';
              ?>">
                <button type="button" class="btn btn-info btn-flat"><?=$credit_debit_note->cdn_igst?> %</button>
                <input type="hidden" name="cdn_igst" id="cdn_igst" value="<?=$credit_debit_note->cdn_igst?>">
              </span>
            </div>
            <span id="err_cdn_igst_tax" class="error invalid-feedback"></span>
          </div>
          <div class="col-sm-3">
            <div class="input-group mb-3">
              <input type="text" class="form-control rounded-0" name="cdn_cgst_tax" id="cdn_cgst_tax" value="<?=set_value("cdn_cgst_tax",$credit_debit_note->cdn_cgst_tax) ?>" placeholder="<?=$this->lang->line("cdn_cgst_tax")?>" readonly="readonly">
              <span class="input-group-append <?php 
                if(sizeof($records)>0)
                    echo ' d-none';
              ?>">
                <button type="button" class="btn btn-info btn-flat"><?=$credit_debit_note->cdn_cgst?> %</button>
                <input type="hidden" name="cdn_cgst" id="cdn_cgst" value="<?=$credit_debit_note->cdn_cgst?>">
              </span>
            </div>
            <span id="err_cdn_cgst_tax" class="error invalid-feedback"></span>
          </div>
          <div class="col-sm-3">
            <div class="input-group mb-3">
              <input type="text" class="form-control rounded-0" name="cdn_sgst_tax" id="cdn_sgst_tax" value="<?=set_value("cdn_sgst_tax",$credit_debit_note->cdn_sgst_tax) ?>" placeholder="<?=$this->lang->line("cdn_sgst_tax")?>" readonly="readonly">
              <span class="input-group-append  <?php 
                if(sizeof($records)>0)
                    echo ' d-none';
              ?>">
                <button type="button" class="btn btn-info btn-flat"><?=$credit_debit_note->cdn_sgst?> %</button>
                <input type="hidden" name="cdn_sgst" id="cdn_sgst" value="<?=$credit_debit_note->cdn_sgst?>">
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
            <input type="number" name="cdn_amount" id="cdn_amount" value="<?=set_value("cdn_amount",$credit_debit_note->cdn_amount) ?>" class="form-control form-control-sm" placeholder="<?=$this->lang->line("cdn_amount")?>" step="any" readonly="readonly">
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
            <?php
              $data['company_setting'] 	= $this->company_settings_model->get_company_records();

              $cid = $data['company_setting']->cid; 

              //$targetDir = "./assets/documents/$cid/credit_debit_note/";

            ?>

              <a href="<?= base_url('assets/documents/' . $cid . '/credit_debit_note/' . $credit_debit_note->cdn_document) ?>" target="_blank"><?= $credit_debit_note->cdn_document ?></a>

          
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
                  <?php 
                  
                    $total_old_taxable_value = 0;
                    $total_old_tax = 0;
                    $total_old_subtotal = 0;

                    $total_new_taxable_value = 0;
                    $total_new_tax = 0;
                    $total_new_subtotal = 0;

                    if(sizeof($records) >  0)
                    {
                      foreach ($records as $value) 
                      { 
                        $total_old_taxable_value += $value->old_taxable_value;
                        $total_old_tax += $value->old_tax;
                        $total_old_subtotal += $value->old_sub_total;

                        $total_new_taxable_value += $value->new_taxable_value;
                        $total_new_tax += $value->new_tax;
                        $total_new_subtotal += $value->new_sub_total;
                  ?>
                        <tr>
                          <td>
                            <input type="hidden" name="entry_id" value="<?=$value->entry_id?>">              
                            <input type="hidden" name="entry_item_id" value="<?=$value->entry_item_id?>">              
                            <input type="hidden" name="entry_type" value="<?=$value->entry_type?>">  
                            <span name="reference_no"><?=$value->reference_no?></span>
                          </td>
                          <td>
                            <span name="product_name"><?=$value->product_name?></span>
                          </td>
                          <td>
                            <span name="tax_rate"><?=$value->r_igst+$value->r_sgst+$value->r_cgst?></span>
                          </td>
                          <td>
                            <span name="quantity"><?=$value->quantity?></span>
                          </td>
                          <td>
                            <span name="old_price"><?=$value->old_price?></span>
                          </td>
                          <td>
                            <span name="old_taxable_value"><?=$value->old_taxable_value?></span>
                          </td>
                          <td>
                            <input type="hidden" name="r_igst" value="<?=$value->r_igst?>">
                            <input type="hidden" name="r_cgst" value="<?=$value->r_cgst?>">
                            <input type="hidden" name="r_sgst" value="<?=$value->r_sgst?>">              
                            <span name="old_tax"><?=$value->old_tax?></span>
                          </td>
                          <td>
                            <span name="old_sub_total"><?=$value->old_sub_total?></span>
                          </td>
                          <td>
                            <input type="number" name="new_price" class="form-control new_price" value="<?=$value->new_price?>" step="0.01">
                          </td>
                          <td>
                            <span name="new_taxable_value"><?=$value->new_taxable_value?></span>
                          </td>
                          <td>              
                            <span name="new_tax"><?=$value->new_tax?></span>
                          </td>
                          <td>
                            <span name="new_sub_total"><?=$value->new_sub_total?></span>
                          </td>
                        </tr> 
                  <?php 
                      }
                    }
                    else
                    {
                  ?>
                      <tr>
                        <td colspan="13">
                          No records are not found
                        </td>
                      </tr>
                  <?php
                    }
                  ?>
                </tbody>
                <tfoot >
                  <th>Total</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>
                    <span name="total_old_taxable_value"><?=$total_old_taxable_value?></span>
                  </th>
                  <th>
                    <span name="total_old_tax"><?=$total_old_tax?></span>
                  </th>
                  <th>
                    <span name="total_old_subtotal"><?=$total_old_subtotal?></span>
                  </th>
                  <th></th>
                  <th>
                    <span name="total_new_taxable_value"><?=$total_new_taxable_value?></span>
                  </th>
                  <th>
                    <span name="total_new_tax"><?=$total_new_tax?></span>
                  </th>
                  <th>
                    <span name="total_new_subtotal"><?=$total_new_subtotal?></span>
                  </th>
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
    <input type="hidden" name="id" id="id" value="<?=$credit_debit_note->cdn_id?>">
    <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
    <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="editCreditDebitNoteSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>