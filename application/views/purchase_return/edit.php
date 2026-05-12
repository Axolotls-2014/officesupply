<?php 
  $this->load->view('layout/header');
  $currency = $this->company_settings_model->get_company_records()->currency_symbol;

  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);

  $mfg_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);

  $promotion = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'promotion', 'active',$row = true,$check_delete_status = false);
   
?>

<style type="text/css">
  .search { position: relative; }
  .search input { text-indent: 20px;}
  .search .fa-search { 
    position: absolute;
    top: 9px;
    left: 20px;
    font-size: 18px;
    padding-left: 8px;
  }
  .search_product{
    background-color: #F8F8F8;
    height: 35px;
    padding-left: 25px;
    font-size: 18px;
  }

  .search_product::-webkit-input-placeholder { /* Chrome/Opera/Safari */
    padding-left: 5px;
    font-size: 18px;
  }
  .search_product::-moz-placeholder { /* Firefox 19+ */
    font-size: 18px;
  }
  .search_product:-ms-input-placeholder { /* IE 10+ */
    font-size: 18px;
  }
  .search_product:-moz-placeholder { /* Firefox 18- */
    font-size: 18px;
  }
  .product_row{
    background: #fffee9 !important;
  }
  .highlight_row{
    /*animation: fadeOut 5s forwards;*/
    background: #fed9c6 !important;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
  }
  .quantity_update_message{
    width: 100% !important;
    padding-left: 40% !important;
  }

  .discount_type{
    padding-left: 20px;
  }
  .discount_amount{
    padding-right: 10px !important;
  }
  .delete_item{
    cursor:pointer;
  }
  .total_data{
    font-size: 18px;
    font-weight: bolder;
  }



</style>
  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><a href="https://ossdemo.tastytap.in/product_core"><?=$this->lang->line('header_inventory')?></a></li>
         
                <li class="breadcrumb-item "><a href="<?=base_url('purchase_return')?>"><?=$this->lang->line('purchase_return_header')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('purchase_return_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editpurchaseReturnForm" id="editpurchaseReturnForm" method="POST" action="<?=base_url('purchase_return/edit')?>">
              <div class="card">
                <div class="card-header"><?=$this->lang->line('purchase_return_edit')?></h6>
                <div class="card-tools">

                    <ul class="nav nav-pills ml-auto">
                      <!-- <li class="nav-item  ml-2">
                        <a class="nav-link reset btn-sm btn-warning" href="#" data-tt="tooltip" title="Click here to Reset Purchase return Items">
                          <i class="fas fa-redo-alt"></i> Reset
                        </a>
                      </li>

 -->

                      <li class="nav-item ml-2">
                        <a class="nav-link active" href="<?=base_url('purchase_return')?>" data-tt="tooltip" title="Click here to show purchase return list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="card-body">
                  <div class="col-sm-12">
                    <div class="row">
                     <!-- <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('purchase_return_reference_no')?></label>
                          <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?=$purchase_return->reference_no?>" readonly>
                        </div>
                      </div>-->
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('purchase_return_invoice_no')?></label><br/>
                          <?php 
                            echo $purchase_return->invoice_no.' - '.$supplier_detail->company_name;
                            $purchase = $this->purchase_model->get_purchase_single_record($purchase_return->invoice_no);
                          ?><br/>
                          <a href=""  data-toggle="modal" data-tt="tooltip" data-target="#view_purchase_detail_modal" data-purchase_id="<?=$purchase->id?>" id="open_view_purchase_detail_modal">Click here to show details of Purchase</a>
                          <input type="hidden" name="invoice_no" id="invoice_no" value="<?=$purchase_return->invoice_no?>">
                          <span id="err_invoice_no" class="error invalid-feedback"><?=form_error('invoice_no');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('purchase_return_date')?></label>
                          <input type="text" class="form-control datepicker" id="purchase_return_date" name="purchase_return_date" value="<?=date('d-m-Y', strtotime($purchase_return->purchase_return_date))?>" placeholder="Invoice Date">
                          <span id="err_purchase_return_date" class="error invalid-feedback"><?=form_error('purchase_return_date');?></span>
                        </div>
                      </div>
<div class="col-sm-3">
  <div class="form-group">
    <label for="warehouse_display"><?=$this->lang->line('purchase_return_warehouse')?><span class="validation-color">*</span></label>
    <div class="form-control form-control-sm bg-light" style="cursor: default; background-color: #e9ecef !important; height: 38px; padding: 6px 12px;" readonly>
      <?php
        $warehouse_name = '';
        foreach ($warehouses as $value) {
          if($value->id == $purchase_return->warehouse_id) {
            $warehouse_name = $value->name;
            break;
          }
        }
        echo $warehouse_name ?: 'N/A';
      ?>
    </div>
    <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$purchase_return->warehouse_id?>">
  </div>
</div>

<div class="col-sm-3">
  <div class="form-group">
    <label for="supplier_display"><?=$this->lang->line('purchase_return_supplier')?><span class="validation-color">*</span></label>
    <div class="form-control form-control-sm bg-light" style="cursor: default; background-color: #e9ecef !important; height: 38px; padding: 6px 12px;" readonly>
      <?php
        $supplier_name = '';
        foreach ($supplier as $value) {
          if($value->id == $purchase_return->supplier_id) {
            $supplier_name = $value->company_name;
            break;
          }
        }
        echo $supplier_name ?: 'N/A';
      ?>
    </div>
    <input type="hidden" name="supplier_id" id="supplier_id" value="<?=$purchase_return->supplier_id?>">
    <input type="hidden" name="supplier_gstin" id="supplier_gstin" value="<?=$supplier_detail->gstin?>">
    <input type="hidden" name="supplier_state_id" id="supplier_state_id" value="<?=$supplier_detail->state_id?>">
    <input type="hidden" name="supplier_country_id" id="supplier_country_id" value="<?=$supplier_detail->country_id?>">
    <span id="err_supplier_id" class="error invalid-feedback"><?=form_error('supplier_id');?></span>
  </div>
</div>
                      
                    </div>

                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                            <label><?=$this->lang->line('cdn_document')?></label>
                            <div class="input-group input-group-sm">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="upload_document" name="upload_document[]" accept=".pdf" multiple>
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <input type="hidden" value="<?= $purchase_return->document ?>" name="document[]" id="document">
                            </div>
                            <span id="fileTypeError" class="error-message"></span>
                            <?php
                              $document_filenames = explode(',', $purchase_return->document);
                              foreach ($document_filenames as $filename){
                                if($filename != ''){
                            ?>
                            <div class="btn-group attached-file" style="margin-top:5px; margin-left:5px;margin-right:5px;">
                                <a href="#" class="btn btn-default"><?=$filename?></a>
                                <button type="button" class="btn btn-danger deleteAttachedFile" data-filename="<?=$filename?>">X</button>
                            </div>
                            <?php
                                }
                              }
                            ?>

                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 ">
                        <div class="well">
                          <div class="row">
                            <div class="col-sm-12">
                              <a href="" data-toggle="modal" data-tt="tooltip" data-target="#add_product_modal" class="float-right"><?=$this->lang->line('purchase_return_add_new_product')?></a>
                            </div>
                           <!-- <div class="col-sm-12 search">
                              <span class="fa fa-search"></span>
                              <input id="search_product" class="form-control search_product"  type="text" name="search_product"  placeholder="<?=$this->lang->line('purchase_return_item_search')?>">
                            </div>-->
                            <div class="col-sm-8">
                              <span class="validation-color" id="err_product"></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12" style="height: 20px;"></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label><?=$this->lang->line('purchase_return_items')?></label>
                          <table class="table items table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
                            <thead>
                              <tr>
                                <th width="2%">
                                  <img src="<?php  echo base_url(); ?>assets/images/bin1.png" />
                                </th>
                                <th class="span2" width="15%"><?=$this->lang->line('product_description')?></th>
                                <!--<th class="span2" width="8%"><?=$this->lang->line('product_batch_no')?></th>-->
                                <th class="span2" width="8%"><?=$this->lang->line('purchase_return_qty')?></th>
                                <!--<th class="span2 <?= (empty($promotion) || $promotion->field_status !== 'active') ? 'd-none' : '' ?>" width="10%"><?=$this->lang->line('proforma_invoice_free_qty')?></th>-->
                                <th class="span2" width="10%"><?=$this->lang->line('purchase_return_cost')?></th>

                                <!--<th class="span2 <?= (empty($mfg_date) || $mfg_date->field_status !== 'active') ? 'd-none' : '' ?>" width="10%">-->
                                <!--  <?= $this->lang->line('product_mfg_date') ?>-->
                                <!--</th>-->
                                <!--<th class="span2 <?= (empty($expiry_date) || $expiry_date->field_status !== 'active') ? 'd-none' : '' ?>" width="10%">-->
                                <!--  <?= $this->lang->line('product_expiry_date') ?>-->
                                <!--</th>-->


                                <!--<th class="span2" width="14%"><?=$this->lang->line('purchase_return_discount')?></th>-->
                                <th class="span2" width="11%"><?=$this->lang->line('purchase_return_uom')?></th>
                                <th class="span2" width="8%"><?=$this->lang->line('purchase_return_taxable_value')?></th>
                                <th class="span2" width="12%"><?=$this->lang->line('purchase_return_tax')?></th>
                                <!--<th class="span2" width="5%"><?=$this->lang->line('purchase_return_inclusive')?></th>-->
                                <th class="span2" width="7%"><?=$this->lang->line('purchase_return_total')?></th>
                              </tr>
                            </thead>
                            <tbody id="product_table_body">
                              <?php 
                                foreach ($purchase_return_items as $row) {
                              ?>
                                <tr class="product_row">
                                  <td>
                                    <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i>
                                    </span>
                                    <input type="hidden" name="product_id" value="<?=$row->product_id?>">
                                  </td>

                                  <td>
                                    <span name="product_name"><?=$row->product_name?></span><br/>
                                    <span name="description"><?=$row->description?></span>
                                  </td>

                                  <!--<td>-->
                                  <!--<input type="text" name="batch_no" value="<?=$row->batch_no?>" class="form-control" required>-->
                                  <!--</td>-->
                                  
                                  <td>
                                    <?php
                                      $product_id = $row->product_id;
                                      $purchase       = $this->purchase_model->get_purchase_single_record($purchase_return->invoice_no);

                                      $purchase_id    = $purchase->id;

                                      $purchase_item_record = $this->purchase_model->get_single_purchase_item_record($purchase_id,$product_id);

                                    ?> 
                                    <input type="number" class="form-control" name="quantity" value="<?=$row->quantity?>" step="any" min="0.01" max="<?=$purchase_item_record->quantity?>">
                                    <span name="quantity_update_message" class="quantity_update_message"></span>
                                  </td>

                                  <!--<td <?= (empty($promotion)) ? 'class="d-none"' : '' ?>>-->
                                  <!--  <input type="number" class="form-control" name="free_quantity" value="<?= (empty($promotion)) ? '0.00' : ($row->free_quantity) ?>" step="0.01" min="0.00" max="<?= (empty($promotion)) ? '0' : ($purchase_item_record->free_quantity) ?>">-->
                                  <!--</td>-->
                                  
                                  <td>
                                    <span id="cost_span">
                                      <input type="number" class="form-control text-right" name="cost" step="0.01" value="<?=$row->cost?>" min="1">
                                    </span>
                                  </td>

                                  <!--<td <?= (empty($mfg_date)) ? 'class="d-none"' : '' ?>>-->
                                  <!--    <input type="text" class="form-control datepicker" name="mfg_date" value="<?= (empty($mfg_date)) ? '' : (($row->mfg_date != '' && $row->mfg_date != '0000-00-00') ? date('d-m-Y', strtotime($row->mfg_date)) : '') ?>" autocomplete="off">-->
                                  <!--</td>-->

                                  <!--<td <?= (empty($expiry_date)) ? 'class="d-none"' : '' ?>>-->
                                  <!--    <input type="text" class="form-control datepicker" name="expiry_date" value="<?= (empty($expiry_date)) ? '' : (($row->expiry_date != '' && $row->expiry_date != '0000-00-00') ? date('d-m-Y', strtotime($row->expiry_date)) : '') ?>" autocomplete="off">-->
                                  <!--</td>-->
                                  
                                 
                                  <td>
                                    <input type="hidden" name="uom_id" value="<?=$row->uom_id?>" data-uom_name="<?=$row->uom_name?>" data-uom_uom="<?=$row->uom_uom?>">  
                                    <?=$row->uom_uom?>
                                  </td>
                                  
                                  
                                  <td><span name="taxable_value"><?=$row->taxable_value?></span></td>
                                  
                                  <td>
                                    <?php 
                                      if($company_setting->country_id == $supplier_detail->country_id)
                                      {
                                        if($company_setting->state_id == $supplier_detail->state_id)
                                        {
                                    ?>
                                          <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">CGST : <span name="c_tax"><?=$row->cgst_tax?></span>
                                          (<span name="cgst"><?=$row->cgst?></span>)
                                          <br>SGST : <span name="s_tax"><?=$row->sgst_tax?></span>
                                          (<span name="sgst"><?=$row->sgst?></span>)
                                          <span name="i_tax" style="display:none"><?=$row->igst_tax?></span>
                                          <span name="igst" style="display:none"><?=$row->igst?></span>
                                    <?php
                                        }
                                        else
                                        {
                                    ?>
                                          <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">IGST : <span name="i_tax"><?=$row->igst_tax?></span>
                                          (<span name="igst"><?=$row->igst?></span>)
                                          <span name="c_tax" style="display:none"><?=$row->cgst_tax?></span>
                                          <span name="cgst" style="display:none"><?=$row->cgst?></span>
                                          <span name="s_tax" style="display:none"><?=$row->sgst_tax?></span>
                                          <span name="sgst" style="display:none"><?=$row->sgst?></span>
                                    <?php 
                                        }
                                      }
                                      else
                                      {
                                    ?>
                                        N/A
                                        <input type="hidden" name="tax_id" value="1">
                                        <span name="c_tax" style="display:none"><?=$row->cgst_tax?></span>
                                        <span name="cgst" style="display:none"><?=$row->cgst?></span>
                                        <span name="s_tax" style="display:none"><?=$row->sgst_tax?></span>
                                        <span name="sgst" style="display:none"><?=$row->sgst?></span>
                                        <span name="i_tax" style="display:none"><?=$row->igst_tax?></span>
                                        <span name="igst" style="display:none"><?=$row->igst?></span>
                                    <?php
                                      }
                                    ?>
                                    
                                  </td>
                                  
                                  <!--<td>-->
                                  <!--  <input type="hidden" name="tax_type" value="<?=$row->tax_type?>">-->
                                  <!--  <?=($row->tax_type == 0) ? 'No' : 'Yes';?>-->
                                  <!--</td>-->
                                  
                                  <td><span name="subtotal"><?=$row->subtotal?></span></td>
                                </tr>
                              <?php 
                                }
                              ?>
                            </tbody>
                          </table>
                          <table class="table table-striped table-bordered table-condensed table-hover total_data" style="font-size: 18px;">
                            <tr>
                              <td align="right" colspan="7"> <?=$this->lang->line('purchase_return_total_discount')?>(<?=$currency?>)</td>
                              <td align='right' class="text-danger">
                                -<span id="total_discount"><?=$purchase_return->total_discount?></span>
                                <input type="hidden" name="total_discount" id="t_discount" value="<?=$purchase_return->total_discount?>">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="7"><?=$this->lang->line('purchase_return_total_taxable_value')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success">
                                +<span id="total_taxable_value"><?=$purchase_return->total_taxable_value?></span>
                                <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="<?=$purchase_return->total_taxable_value?>">
                              </td>
                            </tr>
                            
                            <tr>
                              <td align="right" colspan="7"><?=$this->lang->line('purchase_return_total_tax')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success">
                                +<span id="total_tax"><?=$purchase_return->total_tax?></span>
                                <input type="hidden" name="total_tax" id="t_tax" value="<?=$purchase_return->total_tax?>">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="7"><?=$this->lang->line('purchase_return_total')?>(<?=$currency?>)</td>
                              <td align='right'>
                                <span id="total"><?=$purchase_return->total?></span>
                                <input type="hidden" name="total" id="t" value="<?=$purchase_return->total?>">
                              </td>
                            </tr>
                          </table>

                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="control-group">                     
                          <div class="controls">
                            <div class="tabbable">
                              <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                <li class="nav-item">
                                  <a class="nav-link active" href="#external_note" data-toggle="pill"><?php echo $this->lang->line('purchase_return_external_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#internal_note" data-toggle="pill"><?php echo $this->lang->line('purchase_return_internal_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#terms_and_condition" data-toggle="pill"><?php echo $this->lang->line('purchase_return_terms_and_condition'); ?></a>
                                </li>
                              </ul>                           
                              <br>
                              <div class="tab-content">
                                <div class="tab-pane active" id="external_note">
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('purchase_return_external_note'); ?>"><?=str_replace("<br />","",$purchase_return->external_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('purchase_return_internal_note'); ?>"><?=str_replace("<br />","",$purchase_return->internal_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('purchase_return_terms_and_condition'); ?>"><?=str_replace("<br />","",$purchase_return->terms_and_condition)?></textarea>
                                </div>
                              </div>
                            </div>   
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="id" value="<?=$purchase_return->id?>">
                  <input type="hidden" name="purchase_return_items" id="purchase_return_items" value="">
                  <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  <button type="submit" name="submit" id="purchase_returnReturnSubmit" class="btn btn-info"><?=$this->lang->line('purchase_return_save')?></button>
                  <!-- <button type="submit" name="submit" id="purchase_returnReturnSubmitPayNow" value="pay" name="pay" class="btn btn-info">Add purchase_return & Pay Now</button>                      -->
                  <span class="btn btn-default float-right" id="cancel" onclick="window.history.back()"><?=$this->lang->line('purchase_return_cancel')?></span>
                </div>
            </form>
          </div>
        </div>
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php 
  $this->load->view('layout/footer');
  $this->load->view('supplier/add_supplier_modal');
  $this->load->view('warehouse/add_warehouse_modal');
?>

<div class="modal fade" id="emptypurchaseReturnItemWarningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header warning-header">
        <h5 class="modal-title" id="exampleModalLabel">Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        There are no item(s) in Invoice. Please atleast 1 item in invoice.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="view_purchase_detail_modal">
  <div class="modal-dialog modal-lg">
    <form name="purchaseItemsReturnForm" id="purchaseItemsReturnForm" method="POST">
      <div class="modal-content">
        <div class="modal-header info-header">
          <h5 class="modal-title" >purchase Products</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="view_purchase_items">
         
        </div>
        <div class="modal-footer">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <button type="submit" class="btn btn-primary" name="purchaseItemsReturnSubmit" id="purchaseItemsReturnSubmit" value="submit">Add to Purchase Return</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/disableautofill/src/jquery.disableAutoFill.min.js"></script>

<script type="text/javascript">

  $(document).ready(function(e){

      // $("#supplier_id").closest('.row').siblings().css('display','none');

      // $("#supplier_id").select2({disabled:readonly});

      $('#supplier_id').change(function(e){

        var supplier_id = $(this).val();
        if(supplier_id != '')
        {
          $("#supplier_id").closest('.row').siblings().fadeIn(10);

          $.ajax({
              url: "<?php echo base_url('supplier/get_record_details') ?>",
              type: "POST",
              dataType: "json",
              data: {
                  'supplier_id' : supplier_id,
                  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var supplier = data.supplier;
                $('#supplier_state_id').val(supplier.state_id);
                $('#supplier_country_id').val(supplier.country_id);
              }
          });
        }
      }); 

      // product search code begin

      var c_mapping = { };

      $(function(){
        $('#search_product').autoComplete({
          minChars: 1,
          source: function(term, suggest){
            term = term.toLowerCase();

            $.ajax({
              url: "<?php echo base_url('product/search') ?>",
              type: "POST",
              dataType: "json",
              data:{
                'term': term,
                'module': '<?=PURCHASE_RETURN_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var products = data;
                var suggestions = [];
                for(var i = 0; i < products.length; ++i) {
                    suggestions.push(products[i].id+' - '+products[i].name+' - '+products[i].product_category_name);
                    c_mapping[products[i].id] = products[i].name;
                }
                suggest(suggestions);
              }
            });
          },
          onSelect: function(event, ui) {
            var str = ui.split(' - ');
            
            var product_id = str[0];

            $.ajax({
              url: "<?php echo base_url('product/get_record_detail') ?>",
              type: "POST",
              dataType: "json",
              data:{
                'product_id': product_id, 
                'module': '<?=PURCHASE_RETURN_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var product = data.product;

                if(!is_product_exist_in_row(product.id))
                {
                  add_row(data,batch_no);
                }
                else
                {
                  highlight_row(product.id);
                }

                calculateGrandTotal();
                $('#search_product').val('');
              }
            });
          } 
        });
      });

      function add_row(data,return_quantity = null,free_quantity = null,purchased_quantity = null,delivered_quantity = null,product_name = null,description = null,cost = null, price = null,batch_no = null,selling_price = null)
      {
        var supplier_country_id = $('#supplier_country_id').val();
        var supplier_state_id   = $('#supplier_state_id').val();
        var supplier_gstin      = $('#supplier_gstin').val();

        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var company_gstin       = '<?=$company_setting->gstin?>';

        var product   = data.product;
        var discounts = data.discount;

        var select_discount = "";
            select_discount += '<select class="form-control select2bs4" name="item_discount" style="width: 100%;">';
            select_discount += '<option value="">Select</option>';
              for(a=0;a<discounts.length;a++)
              {
                var type_symbol;
                if(discounts[a].type == 0)
                {
                  type_symbol = "<?=$this->session->userdata('currency_symbol')?>";
                }
                else
                {
                  type_symbol = "%"; 
                }
                select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name+'('+discounts[a].value  + type_symbol +')'+ '</option>';
              }
            select_discount += '</select>'
            select_discount += '<span name="span_discount_type" class="discount_type">Discount Value :  <?=$this->session->userdata('currency_symbol');?> </span><input type="hidden" name="discount_type" value="0">';
            select_discount += '<span name="discount_amount" class="discount_value">0.0</span><input type="hidden" name="discount_value" value="'+0+'">';

        var input_uom = '<input type="hidden" name="uom_id" value="'+product.uom_id+'" data-uom_name="'+product.uom_name+'" data-uom_uom="'+product.uom_uom+'">'+product.uom_uom;

        var input_quantity =  '<input type="number" class="form-control" name="quantity" value="1" step="1" min="1"><span name="quantity_update_message" class="quantity_update_message"></span>';
        var taxable_value  =  '<span name="taxable_value">'+product.cost+'</span>';

        /************    tax begin   *****************/
        var tax = '<input type="hidden" name="tax_id" value="'+product.tax_id+'">';

        if(company_country_id == supplier_country_id)
        {
          if(company_state_id == supplier_state_id)
          {
            tax += 'CGST : <span name="c_tax">'+0.0+'</span>(<span name="cgst">'+product.cgst+'</span>)<br/>';
            tax += 'SGST : <span name="s_tax">'+0.0+'</span>(<span name="sgst">'+product.sgst+'</span>)';
            tax += '<span name="i_tax"style="display:none">0</span><span name="igst" style="display:none">0</span>';
          }
          else
          {
            tax += 'IGST : <span name="i_tax">'+0.0+'</span>(<span name="igst">'+product.igst+'</span>)<br/>';
            tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
            tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
          }
        }
        else
        {
          tax += 'N/A';
          tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
          tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
          tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
        }

        /************    tax end   *****************/

        /************    tax type begin   *****************/

        var tax_type  = '<input type="hidden" name="tax_type" value="'+product.tax_type+'">';
        tax_type      += (product.tax_type == 0) ? "No" : "Yes";

        /************    tax type end   *****************/

        var free_quantity = '<input type="number" class="form-control" name="free_quantity" value="' + free_quantity + '" max="' + free_quantity + '"  min="0" step="any">';

        var newRow = $('<tr class="product_row">');
            var cols = "";

            cols += '<td>'
                      + '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
                      + '<input type="hidden" name="product_id" value="'+product.id+'">' 
                    +'</td>';
            cols += '<td><span name="product_name">'+product.name+'</span><br/><span name="description">'+product.description+'</span></td>';
            // cols += '<td><input type="text" name="batch_no" value="'+batch_no+'" class="form-control " required></td>';
            cols += '<td>'+input_quantity+'</td>';
            // cols += '<td class="<?php echo empty($promotion) ? 'd-none' : ''; ?>">'+free_quantity+'</td>';
            cols += '<td>' 
                      +'<span id="cost_span">'
                        +'<input type="number" class="form-control text-right" name="cost" step="0.01" value="0" min="1">'
                      +'</span>'
                    +'</td>';

            // cols += '<td class="<?php echo empty($mfg_date) ? 'd-none' : ''; ?>">'
            //           +'<input type="text" name="mfg_date" value="" class="form-control datepicker" autocomplete="off">'
            //         +'</td>';
            // cols += '<td class="<?php echo empty($expiry_date) ? 'd-none' : ''; ?>">'
            //           +'<input type="text" name="expiry_date" value="" class="form-control datepicker" autocomplete="off">'
            //         +'</td>';
            // cols += '<td>'+select_discount+'</td>';
            cols += '<td>'+input_uom+'</td>';
            cols += '<td>'+taxable_value+'</td>';
            cols += '<td>'+tax+'</td>';
           
            // cols += '<td>'+tax_type+'</td>';
            cols += '<td><span name="subtotal"></span></td>';
            cols += '</tr>';

            newRow.append(cols);
            $("table.product_table").append(newRow);
            $('.select2bs4').select2({theme: 'bootstrap4'});

            calculateRow(newRow);
      }

      function is_product_exist_in_row(product_id)
      {
        var isproductExist = false;
        $("#product_table_body").find('tr').each(function () {

          var tr = $(this).closest("tr");

          if(tr.find('input[name^="product_id"]').val() == product_id)
          {
            isproductExist = true;
          }
        });

        return isproductExist;
      }

      function highlight_row(product_id)
      {
        $("#product_table_body").find('tr').each(function () {
          var tr  = $(this).closest("tr");

          if(tr.find('input[name^="product_id"]').val() == product_id)
          {
            tr.addClass('highlight_row');

            var existing_quantity = +tr.find('input[name^="quantity"]').val();
            // alert(existing_quantity + 1);
            tr.find('input[name^="quantity"]').val(existing_quantity + 1).trigger('change');
            tr.find('span[name^="quantity_update_message"]').text('+1');

            setTimeout(function(){
              tr.removeClass('highlight_row');
              tr.find('span[name^="quantity_update_message"]').text('');
            },3000);
          }
        });        
      }

      $("table.product_table").on('change', 'input[name^="cost"], input[name^="quantity"], select[name^="item_discount"]', function (event) {

        if($(this).attr('name') == 'item_discount')
        {
          var discount_id = $(this).val();
          var tr          = $(this).closest('tr');

          $.ajax({
            url: "<?php echo base_url('discount/get_record_detail') ?>",
            type: "POST",
            dataType: "json",
            data:{
              'discount_id' : discount_id,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(data){
              var discount = data.discount;
              var discount_type = '';

              tr.find('input[name^="discount_type"]').val(discount.type);
              tr.find('input[name^="discount_value"]').val(discount.value);
              
              calculateRow(tr);
              calculateGrandTotal();
            }
          });  
        }
        else
        {
          calculateRow($(this).closest("tr"));
          calculateGrandTotal();
        }
       });
      
      $('table.product_table').on('click',"span.delete_item", function(e){
        var tr = $(this).closest('tr');
        tr.remove();
        calculateGrandTotal();
      });

    //   function calculateRow(row)
    //   {
    //     var tax_type    = row.find('input[name^="tax_type"]').val();
        
    //     var supplier_country_id = $('#supplier_country_id').val();
    //     var supplier_state_id   = $('#supplier_state_id').val();

    //     var company_country_id  = $('#company_country_id').val();
    //     var company_state_id    = $('#company_state_id').val();

    //     if(tax_type == 0)
    //     {
    //       var product_id  = row.find('input[name^="product_id"]').val();
    //       var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
    //       var cost       = parseFloat(row.find('input[name^="cost"]').val());
    //       var final_discount_value = 0;

    //       var taxable_value   = parseFloat(quantity * cost);

    //       var discount_type   = row.find('input[name^="discount_type"]').val();
    //       var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val());  

    //       var final_discount_value = 0;

    //       if(discount_type == 0)
    //       {
    //         final_discount_value = discount_value;
    //       }
    //       else
    //       {
    //         final_discount_value = (taxable_value * discount_value)/100;
    //       }

    //       taxable_value   = taxable_value - final_discount_value;
          
    //       var igst        = 0;
    //       var cgst        = 0;
    //       var sgst        = 0;

    //       if(company_country_id == supplier_country_id)
    //       {
    //         if(company_state_id == supplier_state_id)
    //         {
    //           cgst        = parseFloat(row.find('input[name^="cgst_rate"]').val());
    //           sgst        = parseFloat(row.find('input[name^="sgst_rate"]').val());
    //         }
    //         else
    //         {
    //           igst        = parseFloat(row.find('input[name^="igst_rate"]').val());
    //         }  
    //       }

    //       if(company_country_id == supplier_country_id)
    //       {
    //         if(company_state_id == supplier_state_id)
    //         {
    //           var igst_tax    = 0 ;
    //           var cgst_tax    = parseFloat((taxable_value * cgst)/100) ;
    //           var sgst_tax    = parseFloat((taxable_value * sgst)/100) ;
    //         }
    //         else
    //         {
    //           var igst_tax    = parseFloat((taxable_value * igst)/100) ;
    //           var cgst_tax    = 0 ;
    //           var sgst_tax    = 0 ;
    //         }  
    //       }
    //       else
    //       {
    //         var igst_tax    = 0 ;
    //         var cgst_tax    = 0 ;
    //         var sgst_tax    = 0 ;
    //       }
         
    //       var subtotal       = parseFloat(taxable_value + igst_tax + cgst_tax + sgst_tax);

    //       row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
    //       row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
    //       row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
    //       row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

    //       row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
    //       row.find('span[name^="subtotal"]').text(subtotal.toFixed(2));  
    //     }
    //     else
    //     {
    //       var final_discount_value = 0;
    //       var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
    //       var cost       = parseFloat(row.find('input[name^="cost"]').val());

    //       var discount_type   = row.find('input[name^="discount_type"]').val();
    //       var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val()); 

    //       var subtotal       = (quantity * cost);

    //       if(discount_type == 0)
    //       {
    //         final_discount_value = discount_value;
    //       }
    //       else
    //       {
    //         final_discount_value = (subtotal * discount_value)/100;
    //       }

    //       subtotal = subtotal - final_discount_value;

    //       // alert(final_discount_value);

    //       var igst        = 0;
    //       var cgst        = 0;
    //       var sgst        = 0;

    //       if(company_country_id == supplier_country_id)
    //       {
    //         if(company_state_id == supplier_state_id)
    //         {
    //           cgst        = parseFloat(row.find('input[name^="cgst_rate"]').val());
    //           sgst        = parseFloat(row.find('input[name^="sgst_rate"]').val());
    //         }
    //         else
    //         {
    //           igst        = parseFloat(row.find('input[name^="igst_rate"]').val());
    //         }  
    //       }


    //       var tax_rate    = igst+cgst+sgst;

    //       var tax_amount    = parseFloat((subtotal * tax_rate) / (100 + tax_rate));

    //       var igst_tax = 0;
    //       var cgst_tax = 0;
    //       var sgst_tax = 0;

    //       if(company_country_id == supplier_country_id)
    //       {
    //         if(company_state_id == supplier_state_id)
    //         {
    //           cgst_tax = tax_amount/2;
    //           sgst_tax = tax_amount/2;
    //         }
    //         else
    //         {
    //           igst_tax = tax_amount;
    //         }  
    //       }
    //       else
    //       {
    //         var igst_tax    = 0 ;
    //         var cgst_tax    = 0 ;
    //         var sgst_tax    = 0 ;
    //       }

    //       var taxable_value = subtotal - (igst_tax + cgst_tax + sgst_tax);    

    //       row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
    //       row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
    //       row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
    //       row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

    //       row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
    //       row.find('span[name^="subtotal"]').text(subtotal.toFixed(2));   

    //     }
        
    //   }

function calculateRow(row)
{
  // GET VALUES
  var qty  = parseFloat(row.find('input[name="quantity"]').val()) || 0;
  var cost = parseFloat(row.find('input[name="cost"]').val()) || 0;

  // TAXABLE VALUE
  var taxable_value = qty * cost;

  // TAX RATES FROM SPANS
  var igst_rate = parseFloat(row.find('span[name="igst"]').text()) || 0;
  var cgst_rate = parseFloat(row.find('span[name="cgst"]').text()) || 0;
  var sgst_rate = parseFloat(row.find('span[name="sgst"]').text()) || 0;

  // TAX AMOUNTS
  var igst_tax = taxable_value * igst_rate / 100;
  var cgst_tax = taxable_value * cgst_rate / 100;
  var sgst_tax = taxable_value * sgst_rate / 100;

  // SUBTOTAL
  var subtotal = taxable_value + igst_tax + cgst_tax + sgst_tax;

  // UPDATE UI
  row.find('span[name="taxable_value"]').text(taxable_value.toFixed(2));
  row.find('span[name="i_tax"]').text(igst_tax.toFixed(2));
  row.find('span[name="c_tax"]').text(cgst_tax.toFixed(2));
  row.find('span[name="s_tax"]').text(sgst_tax.toFixed(2));
  row.find('span[name="subtotal"]').text(subtotal.toFixed(2));
}
      function calculateGrandTotal()
      {
        var total_taxable_value = 0.0;
        var total_cgst          = 0.0;
        var total_sgst          = 0.0;
        var total_igst          = 0.0;
        var total               = 0.0;
        var total_discount      = 0.0;

        $("#product_table_body").find('tr').each(function () {
            var tr              = $(this).closest("tr");
            total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text());
            total_discount      += parseFloat(tr.find('span[name^="discount_amount"]').text());
            total_cgst          += parseFloat(tr.find('span[name^="c_tax"]').text());
            total_sgst          += parseFloat(tr.find('span[name^="s_tax"]').text());
            total_igst          += parseFloat(tr.find('span[name^="i_tax"]').text());
            total               += parseFloat(tr.find('span[name^="subtotal"]').text()); 
        });

        $('#total_taxable_value').text(total_taxable_value.toFixed(2));
        $('#t_taxable_value').val(total_taxable_value.toFixed(2));

        $('#total_discount').text(total_discount.toFixed(2));
        $('#t_discount').val(total_discount.toFixed(2));

        $('#total_tax').text((total_cgst+total_sgst+total_igst).toFixed(2));
        $('#t_tax').val((total_cgst+total_sgst+total_igst).toFixed(2));

        $('#total').text(total.toFixed(2));
        $('#t').val(total.toFixed(2));
      }

      $('#editpurchaseReturnForm').submit(function(e){
        // e.preventDefault();

        var isError = false;
        $('#purchaseReturnSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $('form#editpurchaseReturnForm .field_validation').each(function() {
            var id    = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if(value==null || value==""){
              $("form#editpurchaseReturnForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
              $('form#editpurchaseReturnForm #'+id).addClass('is-invalid');
              isError = true;
            }
            else
            {
              $("form#editpurchaseReturnForm #err_"+id).text("").fadeOut('slow');
              $('form#editpurchaseReturnForm #'+id).removeClass('is-invalid');
              $('form#editpurchaseReturnForm #'+id).addClass('is-valid');
            }
        });

        var productDataArray = [];

        $("#product_table_body").find('tr').each(function () {

            var tr              = $(this).closest("tr");
            var productData     = {};

            productData['product_id']       = tr.find('input[name^="product_id"]').val();
            productData['batch_no']         = tr.find('input[name^="batch_no"]').val();
            productData['product_name']     = tr.find('span[name^="product_name"]').text();
            productData['description']      = tr.find('span[name^="description"]').text();
            productData['quantity']         = tr.find('input[name^="quantity"]').val();
            productData['cost']             = tr.find('input[name^="cost"]').val();
            productData['selling_price']    = tr.find('input[name^="selling_price"]').val();
            productData['price']            = tr.find('input[name^="price"]').val();

            productData['mfg_date']      = tr.find('input[name="mfg_date"]').val();
            productData['expiry_date']      = tr.find('input[name="expiry_date"]').val();
            
            productData['taxable_value']    = tr.find('span[name^="taxable_value"]').text(); 
            productData['discount_id']      = tr.find('select[name^="item_discount"]').val();
            productData['uom_id']           = tr.find('input[name^="uom_id"]').val();
            productData['uom_name']         = tr.find('input[name^="uom_id"]').data('uom_name');
            productData['uom_uom']          = tr.find('input[name^="uom_id"]').data('uom_uom');
            productData['discount_type']    = tr.find('input[name^="discount_type"]').val();
            productData['discount_value']   = tr.find('input[name^="discount_value"]').val();
            productData['discount_amount']  = tr.find('span[name^="discount_amount"]').text();
            productData['tax_id']           = tr.find('input[name^="tax_id"]').val();
            productData['tax_type']         = tr.find('input[name^="tax_type"]').val();
            productData['igst']             = tr.find('span[name^="igst"]').text();
            productData['igst_tax']         = tr.find('span[name^="i_tax"]').text();
            productData['cgst']             = tr.find('span[name^="cgst"]').text();
            productData['cgst_tax']         = tr.find('span[name^="c_tax"]').text();
            productData['sgst']             = tr.find('span[name^="sgst"]').text();
            productData['sgst_tax']         = tr.find('span[name^="s_tax"]').text();
            productData['subtotal']        = tr.find('span[name^="subtotal"]').text();
            productData['free_quantity']          = tr.find('input[name="free_quantity"]').val();

            productDataArray.push(JSON.stringify(productData));
        });

        if(productDataArray.length > 0)
        {
          $('#purchase_return_items').val(productDataArray.join('|'));
        }
        else
        {
          isError = true;
          $('#emptypurchaseReturnItemWarningModal').modal('show');
        }

        if(isError == true)
        {
          $('#purchaseReturnSubmit').text('<?=$this->lang->line("purchase_return_add")?>').removeAttr('disabled');
          return false;
        }
        else 
        {
          return true;
        }
      });

      $("form#editpurchaseReturnForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editpurchaseReturnForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#editpurchaseReturnForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editpurchaseReturnForm #err_"+id).text("").fadeOut('slow');
          $('form#editpurchaseReturnForm #'+id).removeClass('is-invalid');
          $('form#editpurchaseReturnForm #'+id).addClass('is-valid');
        }
      });

      $('#invoice_no').on('change',function(){

        var invoice_no = $('#invoice_no').val();
        if(invoice_no != '')
        {
          $.ajax({
            url: "<?php echo base_url(); ?>purchase/get_record_detail_by_invoice_no",
            method: "POST",
            dataType:'json',
            data: {
              'invoice_no':invoice_no,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(data){

              if(data.code == 1)
              {
                $('#reference_invoice_no').text('invoice no is not available');
              }
              else
              {
                var purchase    = data.purchase;
                $('#reference_invoice_no').html('<a href=""  data-toggle="modal" data-tt="tooltip" data-target="#view_purchase_detail_modal" data-purchase_id="'+purchase.id+'" id="open_view_purchase_detail_modal">invoice no are already exists.Click here to show details of purchases</a>');
                $('#supplier_id').val(purchase.supplier_id).trigger('change');
                $('#warehouse_id').val(purchase.warehouse_id).trigger('change');
                $('#open_view_purchase_detail_modal').trigger('click');
              }
            }
          });
        }
      });

      $(document).on('change','input[name="return_quantity"]',function(event){
        
        if($(this).val() > 0)
        {
          var tr = $(this).closest("tr");
          tr.find('.checkbox').prop('checked',true);  
        }
      });


      $('#view_purchase_detail_modal').on('shown.bs.modal', function (e) {
        var purchase_id = $(e.relatedTarget).data('purchase_id');

        $.ajax({
            url: "<?php echo base_url('purchase_return/get_purchase_items_by_purchase_id')?>",
            type: "POST",
            dataType: "JSON",
            data: {
              'purchase_id': purchase_id,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(data){
              $("#view_purchase_items").html(data.view_purchase_items);

              // select all purchase return items

              $('#selectAll').on('click',function(){
                if(this.checked)
                {
                    $('.checkbox').each(function(){
                        this.checked = true;
                    });
                }
                else
                {
                     $('.checkbox').each(function(){
                        this.checked = false;
                    });
                }
              });
            }
          });
      });

      $('#view_purchase_detail_modal').on('hide.bs.modal', function (e) {
        var existing_product_array = new Array();

        $("#existing_purchase_items").find('tr').each(function () {
          var tr  = $(this).closest("tr");

          if(tr.find('.checkbox').is(':checked'))
          {
            //var product_id = tr.find('input[name="product_id"]').val();
            var product_id              = tr.find('input[name="product_id"]').val();
            var return_quantity         = tr.find('input[name="return_quantity"]').val();
            var purchased_quantity      = tr.find('input[name="purchased_quantity"]').val();
            var delivered_quantity      = tr.find('input[name="delivered_quantity"]').val();
            var product_name            = tr.find('input[name="product_name"]').val();
            var description             = tr.find('input[name="description"]').val();
            var cost                    = tr.find('input[name="cost"]').val();
            var selling_price           = tr.find('input[name="selling_price"]').val();
            var price                   = tr.find('input[name="price"]').val();
            var batch_no                = tr.find('input[name="batch_no"]').val();
            
            var free_quantity           = tr.find('input[name="free_quantity"]').val();

            $.ajax({
              url: "<?php echo base_url('product/get_record_detail') ?>",
              type: "POST",
              dataType: "json",
              data:{
                'product_id': product_id, 
                'module': '<?=PURCHASE_RETURN_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var product = data.product;

                if(!is_product_exist_in_row(product.id))
                {
                  add_row(data,return_quantity,free_quantity,purchased_quantity,delivered_quantity,product_name,description,cost,price,batch_no,selling_price);
                }
                else
                {
                  highlight_row(product.id);
                }

                $('.datepicker').datepicker({
                    weekStart: 1,
                    daysOfWeekHighlighted: "6,0",
                    autoclose: true,
                    todayHighlight: true,
                    format: 'dd-mm-yyyy'
                });
                
                $("#supplier_id").closest('.row').siblings().fadeIn(10);
                // $('#search_product').val('').focus();
                calculateGrandTotal();
              }
            });  
          }
        });
      });

      // Submit purchase return items

      $('#purchaseItemsReturnForm').submit(function(e){
        e.preventDefault();
        $('#view_purchase_detail_modal').modal('hide');
      });

      $(document).on('change', '#upload_document', function(e) {
        var allowedExtensions = ["pdf"];
        var files = this.files;
        var formData = new FormData();
        formData.append('csrf_test_name', '<?= $this->security->get_csrf_hash(); ?>');

        // Get existing filenames
        var existingFilenames = $('#document').val().split(', ');

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var fileExtension = file.name.split(".").pop().toLowerCase();
            var errorSpan = $('#fileTypeError');
            var fileInputLabel = $(this).next('.custom-file-label');

            var fileName = file.name;

            // Regular expression to match only alphanumeric characters and underscores
            var regex = /^[a-zA-Z0-9_]+(\.[a-zA-Z0-9]+)?$/;

            if (!regex.test(fileName)) {
                Swal.fire({
                    title: "Message",
                    text: "File name should only contain alphanumeric (a-z0-9) and underscore ( _ ).",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    timer: 5000,
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });

                $(this).val(""); // Clear the file input

                return;
            }

            if ($.inArray(fileExtension, allowedExtensions) === -1) {
                errorSpan.text("Only PDF files are allowed.");
                $(this).val(""); // Clear the file input
                return;
            }

            // Append the file to form data
            formData.append("upload_document[]", file);
            // Append the filename to existing filenames
            existingFilenames.push(file.name);
        }

        // Join existing filenames with newly uploaded filenames
        var combinedFilenames = existingFilenames.join(', ');

        // Set the value of the hidden input field to the combined filenames
        // $('#document').val(combinedFilenames);

        // Create a new FormData object to send the files to the server
        var formData = new FormData();
        for (var j = 0; j < files.length; j++) {
            formData.append("upload_document[]", files[j]);
        }
        var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
        var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

        // Add the CSRF token to the form data
        formData.append(csrfTokenName, csrfTokenValue);

          // Perform the AJAX request to the server
          $.ajax({
              url: "<?php echo base_url('purchase_return/upload_documents')?>", // Replace with your server-side script URL
              type: "POST",
              data: formData,
              dataType: "JSON",
              contentType: false,
              processData: false,
              success: function(response) {
                if (response.length > 0) {
                  var currentDocumentValue = $('#document').val();
                  var newFilenames = response.join(', ');

                  // Append new filenames to the existing document value
                  var updatedDocumentValue = currentDocumentValue ? currentDocumentValue + ', ' + newFilenames : newFilenames;

                  // Update the document field with the combined filenames
                  $('#document').val(updatedDocumentValue);
              }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  // Handle the error (if needed)
                  alert("Error uploading document: " + errorThrown);
              }
          });
      });


      $(document).on('click', '.deleteAttachedFile', function() {
        var filename = $(this).data('filename');
        
        $(this).closest('.attached-file').remove();

        var filenames = [];

        $('.attached-file').each(function() {
            filenames.push($(this).find('a').text());
        });

        $('#document').val(filenames.join(','));

      });

      
  });
</script>