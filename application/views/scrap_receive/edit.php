<?php 
  $this->load->view('layout/header');
  $currency = $this->company_settings_model->get_company_records()->currency_symbol;
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

    .table-responsive > table th{
      white-space: nowrap !important;
      min-width:120px;
    }

    .is-green {
        border: 1px solid red; /* Example: Green border */
    }
  </style>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><?=$this->lang->line('header_account')?></li>
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_scrap_receive')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('scrap_receive_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editScrapreceiveForm" id="editScrapreceiveForm" method="POST" action="<?=base_url('scrap_receive/edit')?>">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('scrap_receive_edit')?></h3>
                  <div class="card-tools">
                    
                    <ul class="nav nav-pills ml-auto">

                      <li class="nav-item ml-2">
                        <a class="nav-link active btn btn-sm" href="<?=base_url('scrap_receive')?>" data-tt="tooltip" title="Click here to show scrap receive list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                      </li>
                   

                      <li class="nav-item  ml-2">
                        <button type="submit" name="submit" id="ScrapreceiveSubmit" class="btn btn-info"><?=$this->lang->line('scrap_receive_save')?></button>
                      </li>

                    </ul>
                    <!-- <div class="btn-group">

                      <button type="submit" name="submit" id="ScrapreceiveSubmit" class="btn btn-info"><?=$this->lang->line('scrap_receive_save')?></button>
                    </div> -->
                  </div>
                </div>
                <div class="card-body">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label><?=$this->lang->line('scrap_receive_reference_no')?></label>
                          <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?=$scrap_receive->reference_no?>" readonly>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label><?=$this->lang->line('scrap_receive_date')?></label>
                          <input type="text" class="form-control datepicker" id="scrap_receive_date" name="scrap_receive_date" value="<?=date('d-m-Y', strtotime($scrap_receive->scrap_receive_date))?>" placeholder="Pack Slip Date">
                          <span id="err_scrap_receive_date" class="error invalid-feedback"><?=form_error('scrap_receive_date');?></span>
                        </div>
                      </div>
                      <?php
                        $warehouse = $this->warehouse_model->get_record_by_is_default();
                      ?>
                      <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$warehouse->id?>">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="customer"><?=$this->lang->line('scrap_receive_customer')?><span class="validation-color">*</span>
                          </label>
                          <select class="form-control form-control-sm select2bs4 field_validation" name="customer_id" id="customer_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('purchase_customer')?>">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($customers as $value) {
                            ?>
                              <option value="<?=$value->id;?>"
                                data-country_id="<?=$value->country_id?>"  
                                data-state_id="<?=$value->state_id?>"  
                                data-gstin="<?=$value->gstin?>"  
                                <?php 
                                  if($value->id == $scrap_receive->customer_id)
                                    echo ' selected';
                                ?>
                              >
                                <?= $value->customer_name;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <input type="hidden" name="customer_state_id" id="customer_state_id" value="<?=($customer_detail != null) ? $customer_detail->state_id : ''?>">
                          <input type="hidden" name="customer_country_id" id="customer_country_id" value="<?=($customer_detail != null) ? $customer_detail->country_id : ''?>">
                          <span id="err_customer_id" class="error invalid-feedback"><?=form_error('customer_id');?></span>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 ">
                        <div class="well">
                          <div class="row">
                            <!-- <div class="col-sm-12">
                              <a href="" data-toggle="modal" data-tt="tooltip" data-target="#add_service_modal" class="float-right"><?=$this->lang->line('customer_add_new_service')?></a>
                            </div> -->
                            <div class="col-sm-12 search">
                              <span class="fa fa-search"></span>
                              <input id="search_product" class="form-control search_product" type="text" name="search_product"  placeholder="<?=$this->lang->line('customer_item_search')?>">
                              <span id="err_search_product" class="validation"></span>
                            </div>
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
                      <div class="col-sm-12 ">
                        <div class="">
                          <div class="row">
                            <!-- <div class="col-sm-12">
                              <a href="" data-toggle="modal" data-tt="tooltip" data-target="#add_service_modal" class="float-right"><?=$this->lang->line('scrap_receive_add_new_service')?></a>
                            </div> -->
                            <div class="col-sm-12">
                              <table class="table table-bordered" style="background-color:#FFFDD0">
                                <tr>
                                  <th>Total Taxable Value  :  <span class="total_taxable_value"><?=$scrap_receive->total_taxable_value?></span></th>
                                  <th>Total Tax  :  <span class="total_tax"><?=$scrap_receive->total_tax?></span></th>
                                  <th>Total Amount  :  <span class="total"><?=$scrap_receive->total?></span></th>
                                </tr>
                              </table>
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
                          <label><?=$this->lang->line('scrap_receive_items')?></label>
                          <div class="table-responsive">
                            
                          
                            <table class="table items table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
                              <thead>
                                <tr>
                                  <th>
                                    <img src="<?php  echo base_url(); ?>assets/images/bin1.png" />
                                  </th>
                                  <th class="span2"><?=$this->lang->line('product_name')?></th>
                                  <th class="span2"><?=$this->lang->line('product_price')?></th>
                                  <th class="span2"><?=$this->lang->line('product_cost')?></th>
                                  
                                  <th class="span2"><?=$this->lang->line('scrap_issue_ordered_qty')?></th>
                                  <th class="span2"><?=$this->lang->line('scrap_issue_qty')?></th>
                                 
                                  <th class="span2"><?=$this->lang->line('scrap_issue_total_qty')?></th>
                                 
                                  <th class="span2"><?=$this->lang->line('scrap_issue_tax')?></th>
                                
                                  <th class="span2"><?=$this->lang->line('product_batch_no')?></th>
                                  
                                  
                                  
                                  <th class="span2 d-none"><?=$this->lang->line('scrap_issue_discount')?></th>
                                  <th class="span2 d-none"><?=$this->lang->line('scrap_issue_uom')?></th>
                                  <th class="span2"><?=$this->lang->line('scrap_issue_taxable_value')?></th>
                                  <!-- <th class="span2"><?=$this->lang->line('sale_tax')?></th>
                                   -->
                                  <th class="span2 d-none"><?=$this->lang->line('scrap_issue_inclusive')?></th>
                                  <th class="span2"><?=$this->lang->line('scrap_issue_total')?></th>
                                </tr>
                              </thead>
                              <tbody id="product_table_body">
                                <?php 
                                  foreach ($scrap_receive_items as $row) {
                                ?>
                                 <tr class="product_row">
                                    <td>
                                      <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i>
                                      </span>
                                      <input type="hidden" name="product_id" value="<?=$row->product_id?>">
                                      <?php 
                                        $product_id   = $row->product_id;
                                        $cost         = $row->cost;
                                        $warehouse_id = $scrap_receive->warehouse_id;

                                        $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);
                                        $scrap_product = $this->scrap_products_model->get_single_record($product_id);
                                      ?>
                                      <input type="hidden" name="warehouse_product_id" value="<?=$row->warehouse_product_id?>">
                                      <input type="hidden" name="pid" value="<?=$row->pid?>">
                                    </td>
                                    <td>
                                      <span name="product_name"><?=$row->product_name?></span><br/>
                                      <span name="description"><?=$row->description?></span><br/>
                                      <textarea class="form-control d-none" name="optional" rows="4" maxlength="50"><?=str_replace("<br />","",$row->optional)?></textarea>
                                    </td>
                                    <td>
                                      <input type="number" class="form-control text-right" name="price" step="0.01" value="<?=$row->price?>">
                                    </td>

                                  

                                    <td>
                                      <input type="number" class="form-control text-right" name="cost" step="0.01" value="<?=$row->cost?>">
                                     
                                    </td>
                                   
                                    <td>
                                      <input type="number" class="form-control" name="ordered_qty" value="<?=$row->ordered_qty?>" step="0.01" min="0.01">
                                    </td>
                                    <td>
                                     
                                      <input type="number" class="form-control" name="quantity" value="<?=$row->quantity?>" step="0.01" max="<?=$warehouse_product->quantity?>" min="0.01" readonly>

                                      <!-- <input type="number" class="form-control" name="quantity" value="<?=$row->quantity?>" step="1" min="1">
                                      <span name="quantity_update_message" class="quantity_update_message"></span> -->
                                    </td>
                                   
                                    <td><span name="total_quantity"><?=$row->total_quantity?></span></td>
                                  
                                    <td class="tax_td">
                                      <?php 
                                        if($company_setting->country_id == $customer_detail->country_id)
                                        {
                                          if($company_setting->state_id == $customer_detail->state_id)
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
                                          <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">
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
                                   <td><span name="batch_no"><?=$row->batch_no?></span></td>
                                    
                                    <td class="d-none">
                                      <select class="form-control select2bs4" name="item_discount" style="width: 100%;">
                                        <option value="">Select</option>
                                        <?php 
                                          foreach ($discounts as $value) {
                                        ?>
                                          <option value="<?=$value->id?>"
                                            <?php 
                                              if($value->id == $row->discount_id)
                                                echo ' selected';
                                            ?>
                                          >
                                            <?=$value->name.' ('.$value->value.$this->session->userdata('currency_symbol').')' ?>
                                          </option>
                                        <?php
                                          }
                                        ?>
                                        </option>
                                      </select>
                                      <span name="span_discount_type" class="discount_type">Discount Value :  <?=$this->session->userdata('currency_symbol')?> </span>
                                      <input type="hidden" name="discount_type" value="<?=$row->discount_type?>">
                                      <span name="discount_amount" class="discount_amount"><?=$row->discount_amount?></span>
                                      <input type="hidden" name="discount_value" value="<?=$row->discount_value?>">
                                    </td>
                                    <td class="d-none">
                                      <input type="hidden" name="uom_id" value="<?=$row->uom_id?>" data-uom_name="<?=$row->uom_name?>" data-uom_uom="<?=$row->uom_uom?>">  
                                      <?=$row->uom_uom?>
                                    </td>
                                    <td class=""><span name="taxable_value"><?=$row->taxable_value-$row->discount_amount?></span></td>
                                    
                                 
                                    <td class="d-none">
                                      <input type="hidden" name="tax_type" value="<?=$row->tax_type?>">
                                      <?=($row->tax_type == 0) ? 'No' : 'Yes';?>
                                    </td>
                                    
                                    <td class=""><span name="sub_total"><?=$row->sub_total?></span></td>
                                  </tr>
                                <?php 
                                  }
                                ?>
                              </tbody>
                            </table>
                          </div>
                          <table class="table table-striped table-bordered table-condensed table-hover total_data d-none" style="font-size: 18px;">
                            <tr>
                              <td align="right"  width="66%"> <?=$this->lang->line('scrap_receive_total_discount')?>(<?=$currency?>)</td>
                              <td align='right' class="text-danger"  width="34%">
                                -<span id="total_discount"><?=$scrap_receive->total_discount?></span>
                                <input type="hidden" name="total_discount" id="t_discount" value="<?=$scrap_receive->total_discount?>">
                              </td>
                            </tr>
                            <tr>
                              <td align="right"  width="66%"><?=$this->lang->line('scrap_receive_total_taxable_value')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                +<span id="total_taxable_value"><?=$scrap_receive->total_taxable_value?></span>
                                <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="<?=$scrap_receive->total_taxable_value-$scrap_receive->tds?>">
                              </td>
                            </tr>
                            <tr>
                              <td align="right"  width="66%"><?=$this->lang->line('scrap_receive_total_tax')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                +<span id="total_tax"><?=$scrap_receive->total_tax?></span>
                                <input type="hidden" name="total_tax" id="t_tax" value="<?=$scrap_receive->total_tax?>">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('tds')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                <input type="number" class="form-control" name="tds" id="tds" value="<?=$scrap_receive->tds?>" style="text-align: right">
                              </td>
                            </tr>
                            <tr>
                              <td align="right"  width="66%"><?=$this->lang->line('scrap_receive_total')?>(<?=$currency?>)</td>
                              <td align='right' width="34%">
                                <span id="total"><?=$scrap_receive->total?></span>
                                <input type="hidden" name="total" id="t" value="<?=$scrap_receive->total?>">
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
                                  <a class="nav-link active" href="#external_note" data-toggle="pill"><?php echo $this->lang->line('scrap_receive_external_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#internal_note" data-toggle="pill"><?php echo $this->lang->line('scrap_receive_internal_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#bank_detail" data-toggle="pill"><?php echo $this->lang->line('scrap_receive_bank_detail'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#terms_and_condition" data-toggle="pill"><?php echo $this->lang->line('scrap_receive_terms_and_condition'); ?></a>
                                </li>
                              </ul>                           
                              <br>
                              <div class="tab-content">
                                <div class="tab-pane active" id="external_note">
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('scrap_receive_external_note'); ?>"><?=str_replace("<br />","",$scrap_receive->external_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('scrap_receive_internal_note'); ?>"><?=str_replace("<br />","",$scrap_receive->internal_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="bank_detail">
                                  <textarea class="col-sm-12 form-control" name="bank_detail" rows="4" placeholder="<?php echo $this->lang->line('scrap_receive_bank_detail'); ?>"><?=str_replace("<br />","",$scrap_receive->bank_detail)?></textarea>
                                </div>
                                <div class="tab-pane" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('scrap_receive_terms_and_condition'); ?>"><?=str_replace("<br />","",$scrap_receive->terms_and_condition)?></textarea>
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
                  
                  <input type="hidden" name="id" value="<?=$scrap_receive->id?>">
                  <input type="hidden" name="scrap_receive_items" id="scrap_receive_items" value="">
                  <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  <!-- <button type="submit" name="submit" id="ScrapreceiveSubmit" class="btn btn-info"><?=$this->lang->line('scrap_receive_add')?></button> -->
                  <!-- <button type="submit" name="submit" id="ScrapreceiveSubmitPayNow" value="pay" name="pay" class="btn btn-info">Add Sale & Pay Now</button>                      -->
                  <span class="btn btn-default float-right" id="cancel" onclick="cancel('sale')"><?=$this->lang->line('scrap_receive_cancel')?></span>
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
  // $this->load->view('customer/add_customer_modal');
  // $this->load->view('service/add_service_modal');
?>

<div class="modal fade" id="emptySaleItemWarningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header warning-header">
        <h5 class="modal-title" id="exampleModalLabel"> <?=$this->lang->line('scrap_receive_message_label')?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <?=$this->lang->line('empty_scrap_receive_warning_label')?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=$this->lang->line('close')?></button>
      </div>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/disableautofill/src/jquery.disableAutoFill.min.js"></script>

<script type="text/javascript">

  $(document).ready(function(e){

    const ScrapreceiveToast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 10000
                      });


      <?php 
        if($customer_detail == null)
        {
      ?>
          $("#customer_id").closest('.row').siblings().css('display','none');
      <?php
        }
      ?>

      $('#customer_id').change(function(e){

        var customer_id = $(this).val();
        if(customer_id != '')
        {
          if($('#warehouse_id').val() != '')
          {
            $("#customer_id").closest('.row').siblings().fadeIn(10);  
          }

          var country_id = $(this).find('option:selected').data('country_id');
          var state_id = $(this).find('option:selected').data('state_id');
          // var gstin = $(this).find('option:selected').data('gstin');

          $('#customer_state_id').val(state_id);
          $('#customer_country_id').val(country_id);
          // $('#customer_gstin').val(gstin);

          refresh_tax_td();


          // $.ajax({
          //     url: "<?php echo base_url('customer/get_record_details') ?>",
          //     type: "POST",
          //     dataType: "json",
          //     data: {
          //         'customer_id' : customer_id,
          //         '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          //     },
          //     success: function(data){
          //       var customer = data.customer;
          //       $('#customer_state_id').val(customer.state_id);
          //       $('#customer_country_id').val(customer.country_id);

          //       // $("#product_table_body tr").remove();
          //       // calculateGrandTotal();
          //       refresh_tax_td();
          //     }
          // });
        }
      }); 

     

      function refresh_tax_td()
      {
        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var customer_state_id   = $('#customer_state_id').val();
        var customer_country_id = $('#customer_country_id').val();
        
        $("#product_table_body").find('tr').each(function () {
          var tr              = $(this).closest("tr");
          var tax_td          = tr.find('.tax_td');
          var igst            = parseFloat(tax_td.find('span[name="igst"]').text());
          var cgst            = parseFloat(tax_td.find('span[name="cgst"]').text());
          var sgst            = parseFloat(tax_td.find('span[name="sgst"]').text());

          var tax_rate        = igst+cgst+sgst;

          var igst            = tax_rate;
          var cgst            = (parseFloat(tax_rate/2)).toFixed(2);
          var sgst            = (parseFloat(tax_rate/2)).toFixed(2);

          var tax_id          = tax_td.find('input[name="tax_id"]').val();
          var tax             = '<input type="hidden" name="tax_id" value="'+tax_id+'">';

          if(company_country_id == customer_country_id)
          { 
            if(company_state_id == customer_state_id)
            {
              tax += 'CGST : <span name="c_tax">'+0.0+'</span>(<span name="cgst">'+cgst+'</span>)<br/>';
              tax += 'SGST : <span name="s_tax">'+0.0+'</span>(<span name="sgst">'+sgst+'</span>)';
              tax += '<span name="i_tax"style="display:none">0</span><span name="igst" style="display:none">0</span>';
            }
            else
            {
              tax += 'IGST : <span name="i_tax">'+0.0+'</span>(<span name="igst">'+igst+'</span>)<br/>';
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


          tax_td.html(tax);
          calculateRow(tr);
        });
      }

      // Service search code begin

      var c_mapping = { };

      $(function(){
        $('#search_product').autoComplete({
          minChars: 1,
          cache: 0,
          source: function(term, suggest){
            term = term.toLowerCase();

            // var warehouse_id = $('#warehouse_id').val();
            
            $.ajax({
              url: "<?php echo base_url('scrap_product/warehouse_product_search') ?>",
              type: "POST",
              dataType: "json",
              data:{
                'term': term,
                // 'warehouse_id': warehouse_id,
                'module': '<?=SCRAP_RECEIVE_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var products = data;
                var suggestions = [];
                for(var i = 0; i < products.length; ++i) {
                
                  suggestions.push(products[i].id+' - '+products[i].name+" - "+products[i].quantity);
                  c_mapping[products[i].warehouse_product_id] = products[i].name;
                }

                if(suggestions.length == 0)
                {
                  $('#err_search_product').text('No Product(s) are available with this term OR products matches with this term are having 0 quantity. Please try different term');
                }
                else
                {
                  $('#err_search_product').text(''); 
                }

                suggest(suggestions);
              }
            });
          },
          onSelect: function(event, ui) {
            var str = ui.split(' - ');
            
            var warehouse_product_id = str[0];

           

            $.ajax({
              url: "<?php echo base_url('scrap_product/get_warehouse_product_record_detail') ?>",
              type: "POST",
              dataType: "json",
              data:{
                'warehouse_product_id': warehouse_product_id, // here product is warehouse_product_id 
                'module': '<?=SCRAP_RECEIVE_MODULE?>',
                'customer_id': $('#customer_id').val(),
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var product = data.product;
                var discounts     = data.discount;

                var customer_id = $('#customer_id').val();
                if(customer_id != '')
                {
                  if(!is_product_exist_in_row(product.warehouse_product_id))
                  {
                    autosaveForm();
                   
                    add_row(product,discounts);
                  }
                  else
                  {
                    highlight_row(product.warehouse_product_id);
                  }

                  calculateGrandTotal();
                  $('#search_product').val('');
                }
                else{
                  $('#search_product').val('');
                  Swal.fire({
                    title: "Message",
                    text: "Please select customer",
                    // type: "warning",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    timer: 5000,
                    customClass: {
                      confirmButton: "btn btn-primary"
                    }
                  });
                }
              }
            });
          } 
        });
      });

      function add_row(product,discounts)
      {
      
        var customer_country_id = $('#customer_country_id').val();
        var customer_state_id   = $('#customer_state_id').val();

        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var company_gstin       = '<?=$company_setting->gstin?>';

        // var product   = data;
        // var discounts = discount;
        //var product = data.product;
        //var discounts = data.discount;
        //var hold_quantity = parseFloat(hold_quantity);

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
            select_discount += '<span name="discount_amount" class="discount_amount">0.0</span><input type="hidden" name="discount_value" value="'+0+'">';

        var input_uom = '<input type="hidden" name="uom_id" value="'+product.uom_id+'" data-uom_name="'+product.uom_name+'" data-uom_uom="'+product.uom_uom+'">'+product.uom_uom;
        var input_quantity = "";
        /* input_quantity  +=      'MIN: '+product.minimum_order_quantity+' <span data-tt="tooltip" title="Minimum qty must be ordered by customer"><i class="fas fa-question" style="float:right;font-size:11px;padding-top:5px"></i></span>';*/

        
        input_quantity  +=  '<input type="number" class="form-control" name="quantity" value="1" step="0.01"  min="0.01" readonly>';
        // input_quantity  +=  'QTY: '+((product.quantity).toFixed(2))+' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/> Hold QTY: <span name="hold_quantity">'+hold_quantity+'</span><br/>';

       
        input_quantity  +=  '<span name="quantity_update_message" class="quantity_update_message"></span>';

        var ordered_qty  =  '<input type="number" class="form-control" name="ordered_qty" value="1" step="0.01" min="0.01">';

        input_optional  = "";

        input_optional += '<textarea class="form-control d-none" id="textarea" name="optional" rows="4" placeholder="" maxlength="50"></textarea><br/>';


        /*var input_quantity =  '<input type="number" class="form-control" name="quantity" value="1" step="1" min="1" max="'+product.quantity+'"><span name="quantity_update_message" class="quantity_update_message"></span>';*/
        var taxable_value  =  '<span name="taxable_value">'+product.price+'</span>';

        /************    tax begin   *****************/
        var tax = '<input type="hidden" name="tax_id" value="'+product.tax_id+'">';

        // alert(company_state_id);
        // alert(customer_state_id);

        if(company_country_id == customer_country_id)
        { 
          if(company_state_id == customer_state_id)
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


        var newRow = $('<tr class="service_row">');
            var cols = "";

            cols += '<td>'
                      + '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
                      + '<input type="hidden" name="product_id" value="'+product.id+'">'
                     
                     
                      + '<input type="hidden" name="warehouse_product_id" value="'+product.id+'">'  
                    +'</td>';
            cols += '<td>'
                      + '<span name="product_name">'+product.name+'</span><br/>'
                     
                     
                    +'</td>';
            cols += '<td>' 
                      +'<input type="number" class="form-control text-right" name="price" step="0.01" value="'+product.price+'">'
                    +'</td>';
            cols += '<td>' 
                      +'<input type="number" class="form-control text-right" name="cost" value="'+product.cost+'" step="0.01">'
          
                    +'</td>';
            cols += '<td>'+ordered_qty+'</td>';
            cols += '<td>'+input_quantity+'</td>';
            
            cols += '<td><span name="total_quantity"></span></td>';
           
            cols += '<td class="tax_td">'+tax+'</td>';
          
            cols += '<td><span name="batch_no">'+product.batch_no+'</span></td>';
            
            cols += '<td class="d-none">'+select_discount+'</td>';
            cols += '<td class="d-none">'+input_uom+'</td>';
            cols += '<td class="">'+taxable_value+'</td>';
            
           
            cols += '<td class="d-none">'+tax_type+'</td>';
            cols += '<td class=""><span name="sub_total"></span></td>';
            cols += '</tr>';

            newRow.append(cols);
            $("table.product_table").prepend(newRow);
            $('.select2bs4').select2({theme: 'bootstrap4'});
            $('[data-tt="tooltip"]').tooltip({trigger : 'hover'});
            $('.datepicker').datepicker({
                weekStart: 1,
                daysOfWeekHighlighted: "6,0",
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });

            newRow.find('input[name="ordered_qty"]').trigger('keyup');
            calculateRow(newRow);

      }

   

      // Submit purchase return items

      // $('#productForm').submit(function(e){
      //   e.preventDefault();
      //   $('#view_product_detail_modal').modal('hide');
      // });

      $('#productForm').submit(function(e) {
        e.preventDefault();
        var isValid = true;
        $('.ordered-quantity').each(function() {
          if ($(this).val() === "" || parseFloat($(this).val()) < 0.01) {
            isValid = false;
            return false; // Exit the loop early
          }
        });
        if (isValid) {
          $('#view_product_detail_modal').modal('hide');
          // Submit the form here if everything is valid
        } 
        else 
        {
          Swal.fire({
            title: "Message",
            text: "Quantity should not be blank.",
            // type: "warning",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            timer: 5000,
            customClass: {
              confirmButton: "btn btn-primary"
            }
          }); 
        }
      });

      function change_date_format(date)
      {
        if(date != '' && date != null )
        {
          // Get the date string in Y-m-d format
          var dateString = date;

          // Split the date string into year, month, and day components
          var dateParts = dateString.split("-");

          // Create a new date string in d-m-Y format using the day, month, and year components
          var newDateString = dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0];

          // Output the new date string
          return newDateString;
        }
        else{
          return '';
        }
      }



      function is_product_exist_in_row(warehouse_product_id)
      { 
        var isProductExist = false;
        $("#product_table_body").find('tr').each(function () {

          var tr = $(this).closest("tr");

          if(tr.find('input[name="warehouse_product_id"]').val() == warehouse_product_id)
          {
            isProductExist = true;
          }
        });

        return isProductExist;
      }

      function highlight_row(warehouse_product_id)
      {
        $("#product_table_body").find('tr').each(function () {
          var tr  = $(this).closest("tr");

          if(tr.find('input[name="warehouse_product_id"]').val() == warehouse_product_id)
          {
            tr.addClass('highlight_row');

            var existing_quantity = +tr.find('input[name^="quantity"]').val();
            // alert(existing_quantity + 1);
            // tr.find('input[name^="quantity"]').val(existing_quantity + 1).trigger('change');
            // tr.find('span[name^="quantity_update_message"]').text('+1');

            // setTimeout(function(){
            //   tr.removeClass('highlight_row');
            //   tr.find('span[name^="quantity_update_message"]').text('');
            // },3000);
          }
        });        
      }

      $("table.product_table").on('change blur keyup', 'input[name="cost"], input[name="quantity"], select[name^="item_discount"]', function (event) {

        var tr          = $(this).closest('tr');
        var eventType = event.type; // Get the event type


        if($(this).attr('name') == 'item_discount')
        {
          var discount_id = $(this).val();
          if(discount_id != '')
          {
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

                tr.find('input[name^="discount_type"]').val(discount.type);
                tr.find('input[name^="discount_value"]').val(discount.value);
                
                calculateRow(tr);
                calculateGrandTotal();
              }
            });    
          }
          else
          {
              tr.find('input[name^="discount_type"]').val(0);
              tr.find('input[name^="discount_value"]').val(0);

              calculateRow(tr);
              calculateGrandTotal();
          }
        }
        else if($(this).attr('name') == 'quantity')
        {
          calculateRow($(this).closest("tr"));
          calculateGrandTotal();

          var total_quantity  = parseFloat(tr.find('span[name="total_quantity"]').text());
          var max_quantity    = (parseFloat(tr.find('input[name="quantity"]').attr('max'))).toFixed(2);
          var quantity        = parseFloat(tr.find('input[name="quantity"]').val());
         

          if((quantity < 0) )
          { 
            // Swal.fire({
            //   text: "Quantity and Free quantity should be greater than 0",
            //   type: "warning",
            //   buttonsStyling: !1,
            //   confirmButtonText: "Ok, got it!",
            //   timer: 5000,
            //   customClass: {
            //       confirmButton: "btn btn-primary"
            //   }
            // });
            ScrapreceiveToast.fire({
              type: 'warning',
              title:  "Quantity  should be greater than 0"
            });

            tr.find(this).focus();
            if(!tr.hasClass('highlight_row'))
              tr.addClass('highlight_row');
          }
          else if(total_quantity > max_quantity)
          {
            // Swal.fire({
            //   text: 'Quantity + free quantity should be less than '+max_quantity,
            //   type: "warning",
            //   buttonsStyling: !1,
            //   confirmButtonText: "Ok, got it!",
            //   timer: 5000,
            //   customClass: {
            //       confirmButton: "btn btn-primary"
            //   }
            // });
            ScrapreceiveToast.fire({
              type: 'warning',
              title:  'Quantity should be less than '+max_quantity
            });

            tr.find(this).focus();
            if(!tr.hasClass('highlight_row'))
              tr.addClass('highlight_row');
          }
          else
          {
            if(tr.hasClass('highlight_row'))
              tr.removeClass('highlight_row');

            calculateRow($(this).closest("tr"));
            calculateGrandTotal();
          }
        }
        else
        { 
          calculateRow($(this).closest("tr"));
          calculateGrandTotal();
        }

        if (eventType != 'keyup') 
        {
            if(tr.find('input[name="quantity"]').val() > 0)
            {
              autosaveForm(); // Call autosaveForm only on keyup event
            }
          
        }
        
      });
      
      $('table.product_table').on('click',"span.delete_item", function(e){
        var tr = $(this).closest('tr');
        tr.remove();
        calculateGrandTotal();
        autosaveForm();
      });

      $(document).on('change','input[name="tds"]',function(event){
        // alert();
        var tds             = parseFloat($(this).val());
        var total           = parseFloat($('#t').val());
        var total_tax       = parseFloat($('#t_tax').val());
        var total_discount  = parseFloat($('#t_discount').val());

        var total_taxable_value = total-total_tax-tds;
        $('#total_taxable_value').text((total_taxable_value).toFixed(2));
        $('#t_taxable_value').val((total_taxable_value).toFixed(2));
      });

      // $(document).on('change keyup blur','input[name="ordered_qty"]',function(event){
        
      //   var tr            = $(this).closest('tr');
        
      //   var ordered_qty   = tr.find('input[name="ordered_qty"]').val();
      //   var product_id    = tr.find('input[name="product_id"]').val();
        
      //   calculateFreeQuantity(product_id,ordered_qty,row = tr);

      //   calculateRow(tr);
      //   calculateGrandTotal();
      // });

      // latest commented
      // $(document).on('change keyup blur','input[name="ordered_qty"]',function(event){
        
      //   var tr            = $(this).closest('tr');
        
      //   var ordered_qty   = tr.find('input[name="ordered_qty"]').val();

      //   if(ordered_qty == '')
      //   {
      //     Swal.fire({
      //       title: "Message",
      //       text: "Ordered quantity must be integer.",
      //       // type: "warning",
      //       buttonsStyling: !1,
      //       confirmButtonText: "Ok, got it!",
      //       timer: 5000,
      //       customClass: {
      //         confirmButton: "btn btn-primary"
      //       }
      //     }); 
      //   }

      //   // if (!isNaN(ordered_qty)) {
      //   //   ordered_qty = 1;
      //   //   $('input[name="ordered_qty"]').val(1);
      //   // }

      //   var product_id    = tr.find('input[name="product_id"]').val();
        
      //   calculateFreeQuantity(product_id,ordered_qty,row = tr);

      //   calculateRow(tr);
      //   calculateGrandTotal();
      // });

      $(document).on('change keyup blur','select[name="selling_type"], input[name="ordered_qty"]',function(event){
       
        var tr            = $(this).closest('tr');
        var selling_type  = tr.find('select[name="selling_type"]').val();
        var ordered_qty = tr.find('input[name="ordered_qty"]').val();
        ordered_qty = ordered_qty.trim() !== '' ? parseFloat(ordered_qty) : 0;

       
        var product_id    = tr.find('input[name="product_id"]').val();

       
        tr.find('input[name="quantity"]').val(ordered_qty);

        tr.find('input[name="quantity"]').trigger('keyup');

        calculateRow(tr);
        calculateGrandTotal();
      });

      // Calculate free quantity
      // function calculateFreeQuantity(productId, orderedQuantity, tr = null) {
      //   // let freeQuantity = 0;
      //   let globalRules = null;
      //   let productRules = null;

      //   // // Retrieve promotions from hidden field
      //   const promotionsString = $('#promotions').val();
      //   const promotions = JSON.parse(promotionsString.replace(/&quot;/g, '"'));

      //   // console.log('product id : '+promotions[0].product_id);
      //   // console.log('customer_id : '+promotions[0].customer_id);

        
      //   // Find global promotion rules
      //   let globalRules_str = '';
      //   let globalPromotionType = '';
      //   let globalPromotionPercentage = 0;
      //   globalRules = promotions.find(promo => promo.product_id === '0' && promo.customer_id === '0');
        
      //   if (globalRules) 
      //   {
      //     globalPromotionType       = globalRules.promotion_type;  
      //     globalPromotionPercentage = globalRules.percentage;  

      //     // console.log(globalRules.promotion_type);
      //     // console.log(globalPromotionType);

      //     globalRules     = globalRules.configuration.split('|').map(rule => JSON.parse(rule));
      //     globalRules_str = (JSON.stringify(globalRules)).substr(1, (JSON.stringify(globalRules)).length - 2);
          
      //   }

      //   // Find promotion rules for specific product
      //   let customerId = $('#customer_id').val();
      //   let productRules_str = '';
      //   let productPromotionType = '';
      //   let productPromotionPercentage = 0;
      //   productRules = promotions.find(promo => (promo.product_id === productId || promo.product_id === '0') && promo.customer_id === customerId);
      //   if (productRules) {
      //     productPromotionType        = productRules.promotion_type;  
      //     productPromotionPercentage  = productRules.percentage;  

      //     // console.log('product prmotion type : '+productPromotionType);

      //     productRules      = productRules.configuration.split('|').map(rule => JSON.parse(rule));
      //     productRules_str  = (JSON.stringify(productRules)).substr(1, (JSON.stringify(productRules)).length - 2);
      //   }

        
      //   if(productRules_str != '')
      //   {
          
      //     const rules = $.parseJSON(productRules_str)

      //     // alert(rules);
      //     let totalFreeQuantity = 0;

      //     if(productPromotionType === 'quantity')
      //     {
      //       //('product quantity if');
      //       // alert(rules.length);
      //       for (let i = 0; i < rules.length; i++) {
      //         const ruleQuantity = parseInt(rules[i].quantity);
      //         const freeQuantity = parseInt(rules[i].free_quantity);
      //         const eligibleQuantity = Math.floor(orderedQuantity / ruleQuantity) * ruleQuantity;
      //         totalFreeQuantity += Math.floor(eligibleQuantity / ruleQuantity) * freeQuantity;
      //       }

      //       tr.find('input[name="quantity"]').val(orderedQuantity);
      //       tr.find('input[name="free_quantity"]').val(totalFreeQuantity);
      //     }
      //     else
      //     {
      //       //alert('product quantity else');
      //       var quantity = parseFloat(orderedQuantity * (100 - productPromotionPercentage) / 100).toFixed(2);
      //       totalFreeQuantity = parseFloat(orderedQuantity - quantity).toFixed(2);

      //       tr.find('input[name="quantity"]').val(quantity);
      //       tr.find('input[name="free_quantity"]').val(totalFreeQuantity);
      //     }
          

      //     // return totalFreeQuantity;
      //   }
      //   else
      //   {
      //     const rules = $.parseJSON(globalRules_str)

      //     // alert(rules);
      //     let totalFreeQuantity = 0;
      //     // alert(rules.length);
        
      //     // console.log('gbpromotiontype : '+ globalPromotionType)
      //     if(globalPromotionType === 'quantity')
      //     {
      //       // console.log('in quantity global');
      //       for (let i = 0; i < rules.length; i++) {
      //         const ruleQuantity = parseInt(rules[i].quantity);
      //         const freeQuantity = parseInt(rules[i].free_quantity);
      //         const eligibleQuantity = Math.floor(orderedQuantity / ruleQuantity) * ruleQuantity;
      //         totalFreeQuantity += Math.floor(eligibleQuantity / ruleQuantity) * freeQuantity;
      //       }

      //       tr.find('input[name="quantity"]').val(orderedQuantity);
      //       tr.find('input[name="free_quantity"]').val(totalFreeQuantity);
      //     }
      //     else
      //     {
      //       // console.log('else quantity global');
      //       var quantity = parseFloat(orderedQuantity * (100 - globalPromotionPercentage) / 100).toFixed(2);
      //       totalFreeQuantity = parseFloat(orderedQuantity - quantity).toFixed(2);

      //       tr.find('input[name="quantity"]').val(quantity);
      //       tr.find('input[name="free_quantity"]').val(totalFreeQuantity);
      //     }

      //     // return totalFreeQuantity;
      //   }
      // }

      function calculateRow(row)
      {
        var tax_type    = row.find('input[name^="tax_type"]').val();
        // var free_quantity = parseFloat(row.find('input[name="free_quantity"]').val());
       
        if(tax_type == 0)
        {
          var product_id  = row.find('input[name^="product_id"]').val();
          var quantity    = parseFloat(row.find('input[name^="quantity"]').val());

          row.find('span[name="total_quantity"]').text(quantity);
          // set box and case
        

          var price       = parseFloat(row.find('input[name^="cost"]').val());
          var final_discount_value = 0;

          var taxable_value   = parseFloat(quantity * price);

          var discount_type   = row.find('input[name^="discount_type"]').val();
          var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val());  

          var final_discount_value = 0;

          if(discount_type == 0)
          {
            final_discount_value = discount_value;
          }
          else
          {
            final_discount_value = (taxable_value * discount_value)/100;
          }

          taxable_value   = taxable_value - final_discount_value;
          
          var igst        = parseFloat(row.find('span[name^="igst"]').text());
          var cgst        = parseFloat(row.find('span[name^="cgst"]').text());
          var sgst        = parseFloat(row.find('span[name^="sgst"]').text());

          var igst_tax    = parseFloat((taxable_value * igst)/100) ;
          var cgst_tax    = parseFloat((taxable_value * cgst)/100) ;
          var sgst_tax    = parseFloat((taxable_value * sgst)/100) ;

          var sub_total       = parseFloat(taxable_value + igst_tax + cgst_tax + sgst_tax);

          row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
          row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
          row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
          row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

          row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
          row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));  
        }
        else
        {
          var final_discount_value = 0;
          var quantity    = parseFloat(row.find('input[name^="quantity"]').val());

          row.find('span[name="total_quantity"]').text(parseFloat(quantity));
          // set box and case
        
          var price       = parseFloat(row.find('input[name^="cost"]').val());

          var discount_type   = row.find('input[name^="discount_type"]').val();
          var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val()); 

          var sub_total       = (quantity * price);

          if(discount_type == 0)
          {
            final_discount_value = discount_value;
          }
          else
          {
            final_discount_value = (subtotal * discount_value)/100;
          }

          sub_total = sub_total - final_discount_value;

          // alert(final_discount_value);

          var igst        = parseFloat(row.find('span[name^="igst"]').text());
          var cgst        = parseFloat(row.find('span[name^="cgst"]').text());
          var sgst        = parseFloat(row.find('span[name^="sgst"]').text());

          var tax_rate    = igst + cgst + sgst;

          var tax_amount    = parseFloat((sub_total * tax_rate) / (100 + tax_rate));

          var igst_tax = 0;
          var cgst_tax = 0;
          var sgst_tax = 0;

          if(igst > 0)
          {
            igst_tax = tax_amount;
          }
          else
          {
            cgst_tax = tax_amount/2;
            sgst_tax = tax_amount/2;
          }

          var taxable_value = sub_total - (igst_tax + cgst_tax + sgst_tax);

          row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
          row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
          row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
          row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

          row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
          row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));   

        }
        
      }

      function calculateGrandTotal()
      {
        var total_taxable_value = 0.0;
        var total_cgst          = 0.0;
        var total_sgst          = 0.0;
        var total_igst          = 0.0;
        var total               = 0.0;
        var total_discount      = 0.0;

        // TDS part
        var tds                 = parseFloat($('#tds').val()); 


        $("#product_table_body").find('tr').each(function () {
          var tr              = $(this).closest("tr");
          total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text());
          total_discount      += parseFloat(tr.find('span[name^="discount_amount"]').text());
          total_cgst          += parseFloat(tr.find('span[name^="c_tax"]').text());
          total_sgst          += parseFloat(tr.find('span[name^="s_tax"]').text());
          total_igst          += parseFloat(tr.find('span[name^="i_tax"]').text());
          total               += parseFloat(tr.find('span[name^="sub_total"]').text()); 
        });

        $('#total_taxable_value').text(parseFloat(total_taxable_value.toFixed(2))-tds);
        $('#t_taxable_value').val(parseFloat(total_taxable_value.toFixed(2))-tds);
        $('.total_taxable_value').text(parseFloat(total_taxable_value.toFixed(2))-tds);

        $('#total_discount').text(total_discount.toFixed(2));
        $('#t_discount').val(total_discount.toFixed(2));

        $('#total_tax').text((total_cgst+total_sgst+total_igst).toFixed(2));
        $('#t_tax').val((total_cgst+total_sgst+total_igst).toFixed(2));
        $('.total_tax').text((total_cgst+total_sgst+total_igst).toFixed(2));

        $('#total').text(total.toFixed(2));
        $('#t').val(total.toFixed(2));
        $('.total').text(total.toFixed(2));
      }

      function check_if_quantity_is_proper_added()
      {
        var isError = false;

        if ($('#product_table_body').find('tr').length) {
          $("#product_table_body").find('tr').each(function () {
            var tr                    = $(this).closest("tr");
            var warehouse_product_id  = tr.find('input[name="warehouse_product_id"]').val();

            var total_quantity  = parseFloat(tr.find('span[name="total_quantity"]').text());
            var max_quantity    = (parseFloat(tr.find('input[name="quantity"]').attr('max'))).toFixed(2);
            var quantity        = parseFloat(tr.find('input[name="quantity"]').val());
            // var free_quantity   = parseFloat(tr.find('input[name="free_quantity"]').val());

            if(total_quantity < 0)
            {
              isError = true;
              // alert('check_if_quantity_is_proper_added - in if: '+warehouse_product_id);
              highlight_row(warehouse_product_id);
            }
            else if((quantity < 0))
            { 
              isError = true;
              // alert('check_if_quantity_is_proper_added - in if: '+warehouse_product_id);
              highlight_row(warehouse_product_id);
            }
            else if(total_quantity > max_quantity || (quantity > max_quantity))
            {
              isError = true;
              // alert('check_if_quantity_is_proper_added - in else: '+warehouse_product_id);
              highlight_row(warehouse_product_id);
            }
          });
        }
        else
        {
          isError = true;
          // alert('no rpoduct available to save')
        };

        return isError;
      }

      $('#editScrapreceiveForm').submit(function(e){
        // e.preventDefault();

        var isError = false;
        $('#ScrapreceiveSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $('form#editScrapreceiveForm .field_validation').each(function() {
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editScrapreceiveForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#editScrapreceiveForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editScrapreceiveForm #err_"+id).text("").fadeOut('slow');
            $('form#editScrapreceiveForm #'+id).removeClass('is-invalid');
            $('form#editScrapreceiveForm #'+id).addClass('is-valid');
          }
        });

        var productDataArray = [];

        $("#product_table_body").find('tr').each(function () {

          var tr              = $(this).closest("tr");
          var productData     = {};

          productData['warehouse_product_id']   = tr.find('input[name^="warehouse_product_id"]').val();
          productData['product_id']             = tr.find('input[name^="product_id"]').val();
          productData['pid']                    = tr.find('input[name^="pid"]').val();
          productData['product_name']           = tr.find('span[name^="product_name"]').text();
       
          productData['batch_no']               = tr.find('span[name="batch_no"]').text();
                
          // productData['free_quantity']          = tr.find('input[name="free_quantity"]').val();
          productData['total_quantity']         = tr.find('span[name="total_quantity"]').text();
        
          productData['description']      = tr.find('span[name^="description"]').text();
          productData['optional']         = tr.find('textarea[name^="optional"]').val();
          productData['ordered_qty']      = tr.find('input[name="ordered_qty"]').val();
          productData['quantity']         = tr.find('input[name^="quantity"]').val();
          //productData['cost']             = tr.find('input[name^="cost"]').val();

          productData['cost'] = tr.find('input[name^="cost"]').val();

          // Check if cost value is 0
          if (parseFloat(productData['cost']) === 0) {
              tr.find('input[name^="cost"]').addClass('is-green'); // Assuming you have a class for green color
          } else {
              tr.find('input[name^="cost"]').removeClass('is-green');
          }

          productData['price']            = tr.find('input[name^="price"]').val();
          productData['taxable_value']    = tr.find('span[name="taxable_value"]').text();
          productData['discount_id']      = tr.find('select[name^="item_discount"]').val();
          productData['discount_type']    = tr.find('input[name^="discount_type"]').val();
          productData['discount_value']   = tr.find('input[name^="discount_value"]').val();
          productData['discount_amount']  = tr.find('span[name^="discount_amount"]').text();
          productData['uom_id']           = tr.find('input[name^="uom_id"]').val();
          productData['uom_name']         = tr.find('input[name^="uom_id"]').data('uom_name');
          productData['uom_uom']          = tr.find('input[name^="uom_id"]').data('uom_uom');
          productData['tax_id']           = tr.find('input[name^="tax_id"]').val();
          productData['tax_type']         = tr.find('input[name^="tax_type"]').val();
          productData['igst']             = tr.find('span[name^="igst"]').text();
          productData['igst_tax']         = tr.find('span[name^="i_tax"]').text();
          productData['cgst']             = tr.find('span[name^="cgst"]').text();
          productData['cgst_tax']         = tr.find('span[name^="c_tax"]').text();
          productData['sgst']             = tr.find('span[name^="sgst"]').text();
          productData['sgst_tax']         = tr.find('span[name^="s_tax"]').text();
          productData['sub_total']        = tr.find('span[name^="sub_total"]').text();

          productDataArray.push(JSON.stringify(productData));
        });

        if(productDataArray.length > 0)
        {
          $('#scrap_receive_items').val(productDataArray.join('|'));
        }
        else
        {
          isError = true;
          $('#emptySaleItemWarningModal').modal('show');
        }

        
        // Check if any cost value is 0
        var hasZeroCost = $("#product_table_body").find('input[name^="cost"].is-green').length > 0;

        if (hasZeroCost) {
            isError = true;
            $('#ScrapreceiveSubmit').text('<?=$this->lang->line("scrap_receive_add")?>').removeAttr('disabled');
            return false;
            //alert('Cost value cannot be 0. Please correct the values before submitting.');
        }

        isError = check_if_quantity_is_proper_added();

        if(isError == true)
        {
          $('#ScrapreceiveSubmit').text('<?=$this->lang->line("scrap_receive_add")?>').removeAttr('disabled');
          return false;
        }
        else 
        {
          return true;
        }
      });

      $("form#editScrapreceiveForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editScrapreceiveForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#editScrapreceiveForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editScrapreceiveForm #err_"+id).text("").fadeOut('slow');
          $('form#editScrapreceiveForm #'+id).removeClass('is-invalid');
          $('form#editScrapreceiveForm #'+id).addClass('is-valid');
        }
      });

      const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 10000
                          });

      function autosaveForm()
      {
        var customer_id =  $("#customer_id").val();
        var isError = false;

        if((customer_id != '') && ($('#product_table_body').find('tr').length > 0))
        {
          var productDataArray = [];

          $("#product_table_body").find('tr').each(function () {

            var tr              = $(this).closest("tr");
            var productData     = {};

            productData['warehouse_product_id']   = tr.find('input[name^="warehouse_product_id"]').val();
            productData['product_id']             = tr.find('input[name^="product_id"]').val();
            productData['pid']                    = tr.find('input[name^="pid"]').val();
            productData['product_name']           = tr.find('span[name^="product_name"]').text();

            productData['batch_no']               = tr.find('span[name="batch_no"]').text();
             
            // productData['free_quantity']          = tr.find('input[name="free_quantity"]').val();
            productData['total_quantity']         = tr.find('span[name="total_quantity"]').text();
            

            productData['description']      = tr.find('span[name^="description"]').text();
            productData['optional']         = tr.find('textarea[name^="optional"]').val();
            productData['ordered_qty']      = tr.find('input[name="ordered_qty"]').val();
            productData['quantity']         = tr.find('input[name^="quantity"]').val();
            productData['cost']             = tr.find('input[name^="cost"]').val();
            productData['price']            = tr.find('input[name^="price"]').val();
            productData['taxable_value']    = tr.find('span[name="taxable_value"]').text();
            productData['discount_id']      = tr.find('select[name^="item_discount"]').val();
            productData['discount_type']    = tr.find('input[name^="discount_type"]').val();
            productData['discount_value']   = tr.find('input[name^="discount_value"]').val();
            productData['discount_amount']  = tr.find('span[name^="discount_amount"]').text();
            productData['uom_id']           = tr.find('input[name^="uom_id"]').val();
            productData['uom_name']         = tr.find('input[name^="uom_id"]').data('uom_name');
            productData['uom_uom']          = tr.find('input[name^="uom_id"]').data('uom_uom');
            productData['tax_id']           = tr.find('input[name^="tax_id"]').val();
            productData['tax_type']         = tr.find('input[name^="tax_type"]').val();
            productData['igst']             = tr.find('span[name^="igst"]').text();
            productData['igst_tax']         = tr.find('span[name^="i_tax"]').text();
            productData['cgst']             = tr.find('span[name^="cgst"]').text();
            productData['cgst_tax']         = tr.find('span[name^="c_tax"]').text();
            productData['sgst']             = tr.find('span[name^="sgst"]').text();
            productData['sgst_tax']         = tr.find('span[name^="s_tax"]').text();
            productData['sub_total']        = tr.find('span[name^="sub_total"]').text();

            productDataArray.push(JSON.stringify(productData));
          });

          if(productDataArray.length > 0)
            $('#scrap_receive_items').val(productDataArray.join('|'));


          var packslipFormData       = $('#editScrapreceiveForm').serialize();

          isError = check_if_quantity_is_proper_added();

          if(!isError)
          {
            $.ajax({
              async:false,
              url: "<?php echo base_url('scrap_receive/edit')?>",
              type: "POST",
              data:packslipFormData,
              dataType: "JSON",
              success: function(data){
                // if(data.code == 1)
                // {
                //   Toast.fire({
                //     type: 'success',
                //     title: data.message
                //   });
                // }
                // else
                // {
                //   Toast.fire({
                //     type: 'error',
                //     title: data.message
                //   }); 
                // }
              }
            });    
          }
          else{
            // alert('there is issue in check_if_quantity_is_proper_added');
          }

          
        }
      }

      $('.rcm_btn').click(function(e){
        e.preventDefault();

        if($('#product_table_body tr').length > 0)
        {
          $('.rcm-confirmation-body').html('<?=$this->lang->line('scrap_receive_rcm_confirmation_message')?>');

          $('#rcm-confirmation').modal({
              backdrop: 'static',
              keyboard: false
          }).on('click', '#rcm-confirmation-confirm', function(e) {

              $("#product_table_body tr").remove();
              calculateGrandTotal();

              var rcm = $('#rcm').val();

              if(rcm == '0')
              {
                $('#rcm').val('1');
                $('.rcm_btn').text('<?=$this->lang->line('scrap_receive_disable_rcm')?>');
              }
              else
              {
                $('#rcm').val('0');
                $('.rcm_btn').text('<?=$this->lang->line('scrap_receive_enable_rcm')?>');
              }

              $('#rcm-confirmation').modal('hide');
          });
        }
        else
        {
          var rcm = $('#rcm').val();
          if(rcm == '0')
          {
            $('#rcm').val('1');
            $('.rcm_btn').text('<?=$this->lang->line('scrap_receive_disable_rcm')?>');
          }
          else
          {
            $('#rcm').val('0');
            $('.rcm_btn').text('<?=$this->lang->line('scrap_receive_enable_rcm')?>');
          }  
        }
      });
  });
</script>

<div class="rcm-confirmation-modal">
  <div class="modal fade" id="rcm-confirmation">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header warning-header text-left">
          <h4 class="modal-title message_title"><?php echo $this->lang->line('message');?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body rcm-confirmation-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="rcm-confirmation-confirm">
            <?=$this->lang->line('confirm')?>
          </button>
          <button type="button" class="btn btn-default" id="rcm-confirmation-cancel" data-dismiss="modal">
            <?=$this->lang->line('close')?>
          </button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
</div>