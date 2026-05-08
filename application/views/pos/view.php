<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('sale_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('sale_view')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('sale_view')?></h3>
              <?php 
                if($this->permission_model->has_permission('edit_sale'))
                {
              ?>
              <div class="card-tools">

                <?php 
                  if($this->permission_model->has_permission('pdf_download_sale'))
                  {
                ?>
                <a href="#" data-url="<?=base_url('utility/download_file/'.base64_encode($sale->id))?>" style="cursor: pointer" class="btn bg-secondary btn-sm copy-to-clickboard" data-tt="tooltip" title="Copy Invoice link to Clipboard">
                  <i class="fas fa-regular fa-paste"></i>
                </a>

                <a href="<?=base_url('sale/pdf/'.base64_encode($sale->id))?>" class="btn bg-orange btn-sm" data-tt="tooltip" title="Download Invoice">
                  <i class="far fa-file-pdf"></i> Download Invoice
                </a>  

                <a href="<?=base_url('sale/pdf/'.base64_encode($sale->id))?>" class="btn bg-lime btn-sm" data-tt="tooltip" title="Download Delivery Challan">
                  <i class="fas fa-shipping-fast"></i> Delivery Challan
                </a>    
                <?php 
                  }
                ?>

                <?php 
                  if($this->permission_model->has_permission('manage_sale_delivery'))
                  {
                ?>
                <a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn bg-maroon btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="<?=$sale->id?>"> 
                  <i class="fas fa-shipping-fast"></i> Delivery Details
                </a>  
                <?php 
                  }
                ?>

                <?php 
                  if($this->permission_model->has_permission('manage_transaction'))
                  {
                ?>
                <a href="#" data-target="#transaction-modal" data-toggle="modal" data-tt="tooltip" class="btn bg-purple btn-sm" title="Enter Payment" data-sale_id="<?=$sale->id?>"> 
                  <i class="fas fa-rupee-sign"></i> Enter Payment
                </a>
                <?php 
                  }
                ?>

                <?php 

                  $delivered_product_qty = $this->sale_delivery_model->get_total_no_of_quantity_delivered($sale->id);
                  $paid_amount = $this->transaction_model->get_total_transaction_amount($sale->id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE);
                  
                  if($this->permission_model->has_permission('edit_sale') || $this->permission_model->has_permission('edit_all_sale'))
                  { 
                    if($paid_amount > 0 || $delivered_product_qty > 0)
                    {
                ?>
                      <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#sale_edit" data-tt="tooltip" title="Edit Sale" data-sale_id="<?=$sale->id?>">
                        <i class="fas fa-edit"></i> Edit
                      </a> 
                <?php 
                    }
                    else
                    {
                ?>
                      <a href="<?=base_url('sale/edit/'.base64_encode($sale->id))?>" class="btn btn-info btn-sm"  data-tt="tooltip" title="Edit Sale">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                <?php 
                    }
                  }
                ?>
                
              </div>
              <?php 
                }
              ?>
            </div>
            <div class="card-body">
              <div class="invoice p-3 mb-3">
                <!-- title row -->
                <?php 
                  $paid_amount  = round($this->transaction_model->get_total_transaction_amount($sale->id,SALE_MODULE,RECEIPT_TRANSACTION_TYPE));
                  $total        = round($sale->total);
                ?>
                <div class="ribbon-wrapper ribbon-sm">
                  <div class="ribbon 
                    <?php 
                      if($paid_amount >= $total)
                      {
                        echo 'bg-success';
                      }
                      else if($paid_amount < $total && $paid_amount > 0)
                      {
                        echo 'bg-warning';
                      }
                      else
                      {
                        echo 'bg-danger'; 
                      }
                    ?>
                  ">
                    <?php
                      if($paid_amount >= $total)
                      {
                        echo $this->lang->line('paid');
                      }
                      else if($paid_amount < $total && $paid_amount > 0)
                      {
                        echo $this->lang->line('partially_paid');
                      }
                      else
                      {
                        echo $this->lang->line('unpaid'); 
                      }
                    ?>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-12 text-center">
                     <br/><?=$this->lang->line('invoice')?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <h4>
                      <?php 
                        $image_data = '';
                        if($company_setting->logo != '')
                        {
                          $image_data = file_get_contents(base_url().'assets/images/'.$company_setting->logo);
                          $base64_data = base64_encode($image_data);
                      ?>

                      
                      <img src="data:image/png;base64,<?=$base64_data?>" style="width: 50px;">
                      

                      <?php 
                        }
                      ?> <?=$company_setting->company_name?>
                      <small class="float-right"> <?=$this->lang->line('date')?>: <?=date('d-m-Y', strtotime($sale->invoice_date))?></small>
                    </h4>
                  </div>
                  <!-- /.col -->
                </div>
                <div class="row">
                  <div class="col-12">
                    <table width="100%" class="table">
                      <tr style="font-size: 16px;font-weight: bolder;">
                        <td><?=$this->lang->line('supplier')?></td>
                        <td><?=$this->lang->line('bill_to')?></td>
                        <td><?=$this->lang->line('ship_to')?></td>
                        <td><?=$this->lang->line('invoice_details')?></td>
                      </tr>
                      <tr>
                        <td>
                          <address>
                            <strong><?=$company_setting->company_name?></strong><br>
                            <?= ($company_setting->address_line1 != '') ? ($company_setting->address_line1.'<br/>') : '' ?>
                            <?= ($company_setting->address_line2 != '') ? ($company_setting->address_line2.'<br/>') : '' ?>
                            <?=$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name?><br>
                            <?= ($company_setting->pincode != '') ? ($company_setting->pincode.'<br/>') : '' ?>
                            <?=$this->lang->line('phone')?>: <?=$company_setting->mobile?><br>
                            <?=$this->lang->line('email')?>: <?=$company_setting->email?><br>
                            <?=$this->lang->line('gstin_of_supplier')?>: <?=$company_setting->gstin?><br>
                            <?=$this->lang->line('company_setting_pan_no')?>: <?=$company_setting->pan_no?><br>
                            <?=$this->lang->line('company_setting_fssai_no')?>: <?=$company_setting->fssai_no?><br>
                            <?=$this->lang->line('company_setting_dl_no')?>: <?=$company_setting->dl_no?><br>
                          </address>
                        </td>
                        <td>
                          <address>
                            <strong><?=$customer_detail->customer_name?></strong><br>
                            <?= ($customer_detail->address != '') ? ($customer_detail->address.'<br/>') : '' ?>
                            <?=$customer_detail->city_name.', '.$customer_detail->state_name.', '.$customer_detail->country_name?><br>
                            <?=$this->lang->line('phone')?>: <?=$customer_detail->phone?><br>
                            <?=$this->lang->line('email')?>: <?=$customer_detail->email?><br>
                            <?=$this->lang->line('gstin')?>: <?=$customer_detail->gstin?><br>
                            <?=$this->lang->line('company_setting_pan_no')?>: <?=$customer_detail->pan_no?><br>
                            <?=$this->lang->line('company_setting_fssai_no')?>: <?=$customer_detail->fssai_no?><br>
                            <?=$this->lang->line('company_setting_dl_no')?>: <?=$customer_detail->dl_no?><br>
                          </address>
                        </td>
                        <td>
                          <address>
                            <?= ($customer_detail->shipping_address != '') ? ($customer_detail->shipping_address.'<br/>') : '' ?>
                            <?=$customer_detail->shipping_city_name.', '.$customer_detail->shipping_state_name.', '.$customer_detail->shipping_country_name.'-'.$customer_detail->shipping_pincode?><br>
                          </address>
                        </td>
                        <td>
                          <?=$this->lang->line('sale_invoice_no')?>: <?=$sale->reference_no?><br>
                          <?=$this->lang->line('rcm_applicability')?>: <?=($sale->rcm == "Y") ? "Yes" : "No"?><br/>
                          <strong><?=$this->lang->line('sale_due_date')?>: <?=($sale->due_date != '' && $sale->due_date != '0000-00-00') ? date('d-m-Y',strtotime($sale->due_date)) : ''?></strong><br>
                          <?=$this->lang->line('sale_lori_no')?>: <?=$sale->lori_no?><br>
                          <?=$this->lang->line('sale_lori_date')?>: <?=($sale->lori_date != '' && $sale->lori_date != '0000-00-00') ? date('d-m-Y',strtotime($sale->lori_date)) : ''?><br>
                          <?=$this->lang->line('sale_booked_at')?>: <?=($sale->booked_at != '' && $sale->booked_at != '0000-00-00') ? date('d-m-Y',strtotime($sale->booked_at)) : ''?><br>
                          <?=$this->lang->line('sale_vehicle_no')?>: <?=$sale->vehicle_no?><br>
                          <?=$this->lang->line('sale_carrier')?>: <?=$sale->carrier?><br>
                          <?=$this->lang->line('sale_ewaybill_no')?>: <?=$sale->ewaybill_no?>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
               
                <!-- Table row -->
                <div class="row">
                  <div class="col-12">
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="span2">Sr</th>
                          <th class="span2"><?=$this->lang->line('product_hsn')?></th>
                          <th class="span2"><?=$this->lang->line('service_description')?></th>
                          <th class="span2"><?=$this->lang->line('product_pack')?></th>
                          <th class="span2"><?=$this->lang->line('product_mfg_name')?></th>
                          <th class="span2"><?=$this->lang->line('product_batch_no')?></th>
                          <th class="span2"><?=$this->lang->line('product_expiry_date')?></th>
                          <th class="span2"><?=$this->lang->line('sale_price')?></th>
                          <th class="span2"><?=$this->lang->line('product_sptr')?></th>
                          <th class="span2 d-none"><?=$this->lang->line('product_ptd')?></th>
                          <th class="span2"><?=$this->lang->line('sale_qty')?></th>
                          <th class="span2"><?=$this->lang->line('pack_slip_free_qty')?></th>
                          <th class="span2"><?=$this->lang->line('sale_discount')?></th>
                          <th class="span2"><?=$this->lang->line('sale_uom')?></th>
                          <th class="span2"><?=$this->lang->line('sale_taxable_value')?></th>
                          <th class="span2"><?=$this->lang->line('sale_tax')?></th>
                          <!-- <th class="span2"><?=$this->lang->line('sale_inclusive')?></th> -->
                          <th class="span2"><?=$this->lang->line('sale_total')?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $i = 1;
                          $total_quantity = 0;
                          $total_free_quantity = 0;
                          $total_price    = 0;
                          $total_discount_amount = 0;
                          $total_taxable_value = 0;
                          $total_tax = 0;
                          $total_subtotal = 0;
                          foreach ($sale_items as $row) 
                          {
                            $total_quantity         += $row->quantity;
                            $total_free_quantity    += $row->free_quantity;
                            $total_price            += $row->price;
                            $total_discount_amount  += $row->discount_amount;
                            $total_taxable_value    += $row->taxable_value;
                            $total_tax              += $row->cgst_tax+$row->sgst_tax+$row->igst_tax;
                            $total_subtotal         += $row->sub_total;
                        ?>
                          <tr>
                            <td><?=$i++?></td>
                            <td><?=$row->hsn?></td>
                            <td>
                              <?php 
                                $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);

                                // $variant_array = array();

                                // if($warehouse_product->variant1 != '')
                                //   $variant_array[] = clean_e_val($warehouse_product->variant1);

                                // if($warehouse_product->variant2 != '')
                                //   $variant_array[] = clean_e_val($warehouse_product->variant2);

                                // if($warehouse_product->variant3 != '')
                                //   $variant_array[] = clean_e_val($warehouse_product->variant3);

                                $product_description = ($row->description != '') ? '<br/>'.$row->description : '<br/>';
                                $optional = ($row->optional != '') ? '<br/>'.$row->optional.'<br/>' : '<br/>';


                              ?>
                              <?=$row->product_name.$product_description.$optional?>
                            </td>
                            <td><?=$row->pack?></td>
                            <td><?=$row->supplier_name?></td>
                            <td><?=($row->batch_no != 'null') ? $row->batch_no : '' ?></td>
                            <td><?=($row->expiry_date != '' && $row->expiry_date != '0000-00-00') ? date('d-m-Y',strtotime($row->expiry_date)) : '' ?></td>
                            <td><?=number_format_i($row->price)?></td>
                            <td><?=number_format_i($row->ptr)?></td>
                            <!-- <td><b><?=number_format_i($row->cost)?></b></td> -->
                            <td><?=$row->quantity?></td>
                            <td><?=$row->free_quantity?></td>
                            <td><?=number_format_i($row->discount_amount)?></td>
                            <td><?=$row->uom_uom?></td>
                            <td><?=number_format_i($row->taxable_value-$row->discount_amount)?></td>
                            <td>
                              <?php 
                                if($company_setting->country_id == $customer_detail->country_id)
                                {
                                  if($sale->rcm == 'N' && $company_setting->gstin != '')
                                  {
                                    if($company_setting->state_id == $customer_detail->state_id)
                                    {
                              ?>
                                      <?=$this->lang->line('cgst')?> : <?=$row->cgst_tax?>
                                      (<?=$row->cgst?>%)
                                      <br><?=$this->lang->line('sgst')?> : <?=$row->sgst_tax?>
                                      (<?=$row->sgst?>%)
                              <?php
                                    }
                                    else
                                    {
                              ?>
                                      <?=$this->lang->line('igst')?> : <?=$row->igst_tax?>
                                      (<?=$row->igst?>%)
                              <?php 
                                    }
                                  }
                                  else
                                  {
                              ?>
                                    N/A  
                              <?php
                                  }
                                }
                                else
                                {
                              ?>
                                  N/A
                              <?php
                                }
                              ?>
                            </td>
                            <td><?=number_format_i($row->sub_total)?></td>
                          </tr>
                        <?php 
                          }
                        ?>
                          <tr style="font-size: 16px;font-weight: bolder;">
                            <td colspan="9">
                              <?=$this->lang->line('total_amount_due')?>  
                              <br/>
                              <!-- (<?php echo $this->numbertowords->convert_number($total_price);?>) -->
                            </td>
                            <td><?=$total_quantity?></td>
                            <td><?=number_format_i($total_free_quantity)?></td>
                            <td><?=number_format_i($total_discount_amount)?></td>
                            <td></td>
                            <td><?=number_format_i($total_taxable_value-$total_discount_amount)?></td>
                            <td><?=number_format_i($total_tax)?></td>
                            <td><?=number_format_i($total_subtotal)?></td>
                          </tr>
                          <tr style="font-size: 16px;font-weight: bolder;">
                            <td colspan="15">
                              <?=$this->lang->line('rounded_off')?>
                            </td>
                            <td>
                              <?php 
                                if((round($sale->total_taxable_value+$sale->total_tax) - ($sale->total_taxable_value+$sale->total_tax)) > 0)
                                {
                                  echo number_format_i((round($sale->total_taxable_value+$sale->total_tax) - ($sale->total_taxable_value+$sale->total_tax)));
                                }
                                else
                                {
                                  echo number_format_i((round($sale->total_taxable_value+$sale->total_tax) - ($sale->total_taxable_value+$sale->total_tax)));
                                }
                              ?>
                            </td>
                          </tr>
                          <tr style="font-size: 16px;font-weight: bolder;">
                            <td colspan="15">
                              <?=$this->lang->line('sale_tds')?>
                            </td>
                            <td>
                              <?php 
                                echo number_format_i($sale->tds);
                              ?>
                            </td>
                          </tr>
                          <tr style="font-size: 16px;font-weight: bolder;">
                            <td colspan="15">
                              <?=$this->lang->line('paid_amount')?>
                              (<?php echo $this->numbertowords->convert_number($paid_amount);?>)
                            </td>
                            <td>
                              <?php echo number_format_i($paid_amount);?>
                            </td>
                          </tr>
                          <tr style="font-size: 16px;font-weight: bolder;">
                            <td colspan="15">
                              <?=$this->lang->line('balance_receivable')?>
                              (<?php echo $this->numbertowords->convert_number($sale->total_taxable_value+$sale->total_tax-$paid_amount);?>)
                            </td>
                            <td>
                              <?php echo number_format_i(round($sale->total_taxable_value+$sale->total_tax-$paid_amount));?>
                            </td>
                          </tr>
                          <!-- <tr style="font-size: 16px;font-weight: bold; ">
                            <td colspan="5">
                              <?=$this->lang->line('amount_in_words')?>
                            </td>
                            <td colspan="5" style="text-align: right">
                              <?php echo $this->numbertowords->convert_number(round($sale->total_taxable_value+$sale->total_tax-$paid_amount));?>
                            </td>
                          </tr> -->
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- /.row -->
                <div class="row">
                  <div class="col-12">
                    <table class="table" width="100%">
                      <tr style="font-size: 16px;font-weight: bolder;">
                        <td><strong>Internal Note</strong></td>
                      </tr>
                      <tr>
                        <td><?=$sale->internal_note?></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <table width="100%" class="table">
                      <tr style="font-size: 16px;font-weight: bolder;">
                        <td width="50%">
                          <?=$this->lang->line('bank_detail')?>
                        </td>
                        <td>
                          <?=$this->lang->line('remarks')?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <?=$sale->bank_detail?>
                        </td>
                        <td>
                          <?=$sale->external_note?>
                        </td>
                      </tr>
                      <tr style="font-size: 16px;font-weight: bolder;">
                        <td width="50%">
                          <?=$this->lang->line('terms_condition')?>
                        </td>
                        <td>
                         <?=$this->lang->line('certified_message')?>                         
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <?=$sale->terms_and_condition?>
                        </td>
                        <td>
                          <?=$this->lang->line('for')?>, <?=strtoupper($company_setting->company_name)?>
                          <br/>
                          <br/>
                          <?php 
                            $signature_data = '';
                            if($company_setting->signature != '')
                            {
                              $signature_data = file_get_contents(base_url().'assets/images/'.$company_setting->signature);
                              $base64_data = base64_encode($signature_data);
                          ?>
                            <img src="data:image/png;base64,<?=$base64_data?>" style="width: 100px;"><br/><br/>
                          <?php 
                            }
                            else
                            {
                              echo '<br/><br/><br/>';
                            }
                          ?>  
                          <?=$this->lang->line('signatory')?>

                        </td>
                      </tr>
                    </table>
                  </div>
                </div>

                <!-- this row will not appear when printing -->
                <div class="row no-print">
                  <div class="col-12">
                    
                    <button type="button" class="btn btn-success float-left print_invoice">
                      <i class="fas fa-print"></i> <?=$this->lang->line('print')?>
                    </button>
                    <!-- <button type="button" class="btn btn-success float-right">
                      <i class="far fa-credit-card"></i> Submit Payment
                    </button>
                    <a class="btn btn-primary float-right" style="margin-right: 5px;" href="<?=base_url('sale/pdf/'.$sale->id)?>" target="_blank">
                      <i class="fas fa-download"></i> Generate PDF
                    </a> -->
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<div class="transaction-modal">
  <div class="modal fade" id="transaction-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header light-purple-header">
          <h4 class="modal-title">
            <?php echo $this->lang->line('sale_transaction');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="accordion">
            <div class="card card-secondary">
              <div class="card-header saleDetail light-secondary-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#saleDetail" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('sale_view')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="saleDetail" class="panel-collapse in collapse" style="">
                <div class="card-body sale_detail  m-0 p-0">
                </div>
              </div>
            </div>
            <div class="card card-danger addTransactionCard">
              <div class="card-header addTransaction light-failure-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#addTransaction" class="" aria-expanded="true">
                    <?=$this->lang->line('sale_payment')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="addTransaction" class="panel-collapse collapse show" style="">
                <form id="addTransactionForm" name="addTransactionForm" method="POST">
                  <div class="card-body add_transaction">
                  </div>
                  <div class="card-footer">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="transaction_type" value="<?=RECEIPT_TRANSACTION_TYPE?>">
                    <input type="hidden" name="entry_id" id="sale_id" value="">
                    <input type="hidden" name="module" value="<?=SALE_MODULE?>">
                    <input type="hidden" name="from_account" id="from_account" value="">
                    <button type="submit" class="btn btn-info" name="addTransactionSubmit" id="addTransactionSubmit">
                      <?php echo $this->lang->line('submit');?>
                    </button>
                    <button type="reset" class="btn btn-default">
                      <?php echo $this->lang->line('reset');?>
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="card card-success">
              <div class="card-header transactionEntries light-success-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#transactionEntries" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('sale_previous_transaction')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="transactionEntries" class="panel-collapse collapse">
                <div class="card-body transaction_entries m-0 p-0">
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="is_there_change_in_transaction" id="is_there_change_in_transaction" value="false">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="delivery-modal">
  <div class="modal fade" id="delivery-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header light-purple-header">
          <h4 class="modal-title">
            <?php echo $this->lang->line('sale_delivery');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="accordion">
            <form id="addDeliveryDetailForm" name="addDeliveryDetailForm" method="POST">
              <div class="card card-info addDeliveryDetailCard">

                  <div class="card-header light-secondary-header addDeliveryDetail">
                    <h5 class="card-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#addDeliveryDetail" class="collapsed" aria-expanded="false">
                        <?=$this->lang->line('sale_delivery_add')?>
                        <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                      </a>
                    </h5>
                  </div>
                  <div id="addDeliveryDetail" class="panel-collapse in collapse" style="">
                    <div class="card-body add_delivery_detail">
                    
                    </div>
                    <div class="card-footer">
                      <input type="hidden" name="delivery_items" value="">
                      <input type="hidden" name="sale_id" value="">
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                      <button type="submit" name="addDeliveryDetailSubmit" id="addDeliveryDetailSubmit" class="btn btn-primary float-right">
                        <?=$this->lang->line('submit')?>
                      </button>
                      <button type="button" name="cancelDeliveryDetail" id="cancelDeliveryDetail" class="btn btn-default">
                        <?=$this->lang->line('cancel')?>
                      </button>
                    </div>
                  </div>
                
              </div>
            </form>
            <div class="card card-success deliveryEntriesCard">
              <div class="card-header deliveryEntries light-success-header">
                <h5 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#deliveryEntries" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('previous_sale_delivery')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h5>
                <div class="card-tools">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-default" id="addNewDeliveryDetail">Add New Delivery</button>
                  </div>
                </div>
              </div>
              <div id="deliveryEntries" class="panel-collapse collapse">
                <div class="card-body delivery_entries m-0 p-0">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="is_there_change_in_delivery_transaction" id="is_there_change_in_delivery_transaction" value="false">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="sale_edit">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>
<?php $this->load->view('layout/footer');?>

<script type="text/javascript">
  $(document).ready(function(e){
    $('.print_invoice').click(function(e){
      $('.invoice').printThis();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('.saleDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

    $('#transaction-modal').on('show.bs.modal', function (e) {

      $('.addTransactionCard').css('display','block');

      var sale_id = $(e.relatedTarget).data('sale_id');
      $('#sale_id').val(sale_id);
      
      $.ajax({
        url: "<?php echo base_url('sale/view')?>/"+sale_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){

          $(".sale_detail").html(data.sale_view);
          $(".add_transaction").html(data.add_transaction);
          $("#from_account").val(data.from_account);
          $(".transaction_entries").html(data.previous_transaction_view);

          if(data.due_amount == 0)
          {
            $('.addTransactionCard').css('display','none');
            // collapse all accordian
            $('#transactionEntries').collapse("hide");
            $('#saleDetail').collapse("show");  
          }
          else
          {
            // collapse all accordian
            $('#transactionEntries').collapse("hide");
            $('#addTransaction').collapse("hide");
            $('#saleDetail').collapse("show");  
          }
        }
      });
    });

    $(document).on('hide.bs.modal','#transaction-modal' ,function (e) {

      // alert($('#is_there_change_in_transaction').val());
      
      if($('#is_there_change_in_transaction').val() == "true")
      {
        location.reload();
      }
      
    });

    $('#delivery-modal').on('show.bs.modal', function (e) {

      var sale_id = $(e.relatedTarget).data('sale_id');
      $('#sale_id').val(sale_id);
      $('#addNewDeliveryDetail').data('sale_id',sale_id);

      if($(e.relatedTarget).hasClass('open_delivery_modal'))
      {
        $.ajax({
          url: "<?php echo base_url('sale/view_delivery')?>",
          type: "POST",
          data: {
            'sale_id':sale_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            // if all quantity is delivered, hide the add new delivery button
            if(data.is_all_products_delivered == true)
            {
              $('#addNewDeliveryDetail').css('display','none');
            }
            else
            {
              $('#addNewDeliveryDetail').css('display','block'); 
            }  

            // $(".add_delivery_detail").html(data.add_delivery_detail);
            $(".delivery_entries").html(data.delivery_transaction);
            $(".sale_delivery_detail").html(data.sale_delivery_detail);
            
            $('.view_delivery_transaction_detail').css('display','none');
            $('#deliveryEntries').collapse("show");

            $('#addDeliveryDetailForm').find('input[name="sale_id"]').val(sale_id);

            
          }
        });  
      }      
    });

    $(document).on('hide.bs.modal','#delivery-modal' ,function (e) {
  
      if($('#is_there_change_in_delivery_transaction').val() == "true")
      {
        location.reload();
      }
         
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('.saleDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

    // View delivery transaction
    $(document).on('click', '.view_delivery_transaction', function (event) {

      $('.view_delivery_transaction_detail').css('display','none');
      $(this).parents('tr').next('tr').toggle(200);
      
    });

    // Add new delivery of sale
    $(document).on('click','#addNewDeliveryDetail',function(e){

      $('.addDeliveryDetailCard').toggle(200);

      var sale_id = $('#addDeliveryDetailForm').find('input[name="sale_id"]').val();

      $.ajax({
        url: "<?php echo base_url('sale/add_sale_delivery')?>/"+sale_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){

          $(".add_delivery_detail").html(data.add_delivery_detail);

          // Initialize the dropdown and date
  
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
  
          $('#delivery-modal').find('input[name="delivery_date"]').datepicker({
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
          });
            
        }
      });

      $('#addDeliveryDetail').collapse('show');
    });

    // Populate delivery data of sale
    $(document).on('click','#addDeliveryDetailSubmit',function(e){
      e.preventDefault();

      var deliveryDataArray = [];

      $("#product_delivery_item_body").find('tr').each(function () {

        var tr                      = $(this).closest("tr");
        var deliveryData            = {};

        deliveryData['product_id']            = tr.find('input[name="quantity"]').data('product_id');
        deliveryData['warehouse_product_id']  = tr.find('input[name="quantity"]').data('warehouse_product_id');
        deliveryData['quantity']              = tr.find('input[name="quantity"]').val();

        deliveryDataArray.push(JSON.stringify(deliveryData));
      });

       
      $('#addDeliveryDetailForm').find('input[name="delivery_items"]').val(deliveryDataArray.join('|'));

      $('#addDeliveryDetailForm').trigger('submit');
    });

    // Submit delivery data of sale
    $(document).on('submit','#addDeliveryDetailForm',function(e){
      e.preventDefault();

      var isError = false;

      $('form#addDeliveryDetailForm .field_validation').each(function() {
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');
          var name  = $(this).attr('name');

          // alert(name);
          
          if(value==null || value=="")
          {
            $("form#addDeliveryDetailForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
            $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
            $('form#addDeliveryDetailForm #'+id).addClass('is-valid');
          }

          if(name == 'quantity')
          {
            var max = parseInt($(this).attr('max'));
            if(parseInt(value) > max)
            {
              $("form#addDeliveryDetailForm #err_"+id).text(field+ " should be <= "+ max).fadeIn('slow');
              $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
              isError = true;   
            }
          }


      });

      if(isError == true)
      {
        return false;
      }
      else
      {
        var DeliveryDetailFormData  = $('#addDeliveryDetailForm').serialize();  
        var sale_id                 = $('form#addDeliveryDetailForm').find('input[name="sale_id"]').val(); 

        Swal.fire({
          title: "Warning",
          text: "Please wait while we are saving",
          icon: "warning",
          showConfirmButton: false
        });

        $('#addDeliveryDetailSubmit').off('click');
        $('#addDeliveryDetailSubmit').text('Please wait while we are saving').addClass('d-none');


        setTimeout(function(){ 

          $.ajax({
            url: "<?php echo base_url('sale/add_sale_delivery')?>",
            type: "POST",
            data: DeliveryDetailFormData,
            dataType: "JSON",
            success: function(data){


              if(data.code == 1)
              {
                $('#addDeliveryDetailSubmit').text('Submit').removeClass('disabled');
                $.ajax({
                  url: "<?php echo base_url('sale/view_delivery')?>",
                  type: "POST",
                  data: {
                    'sale_id':sale_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                  dataType: "JSON",
                  success: function(data){

                    // if all quantity is delivered, hide the add new delivery button
                    if(data.is_all_products_delivered == true)
                    {
                      $('#addNewDeliveryDetail').css('display','none');
                    }
                    else
                    {
                      $('#addNewDeliveryDetail').css('display','block'); 
                    }  

                    Swal.close();

                    $('.addDeliveryDetailCard').toggle(200);
                    $(".add_delivery_detail").html('');
                    $(".delivery_entries").html(data.delivery_transaction);
                    // $(".sale_delivery_detail").html(data.sale_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="sale_id"]').val(sale_id);

                    $("#is_there_change_in_delivery_transaction").val("true");
                  }
                });  
              }
            }
          });
        }, 500);  
      }
    });

    $("form#addDeliveryDetailForm").on("change", '.field_validation', function (event){

      var id    = $(this).attr('id');
      var value = $(this).val();
      var field = $(this).attr('placeholder');
      var name  = $(this).attr('name');
      
      if(value==null || value==""){
        $("form#addDeliveryDetailForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
        $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
        return false;
      }
      else{
        $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
        $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
        $('form#addDeliveryDetailForm #'+id).addClass('is-valid');
      }

      if(name == 'quantity')
      {
        var max = parseInt($(this).attr('max'));
        if(parseInt(value) > max)
        {
          $("form#addDeliveryDetailForm #err_"+id).text(field+ " should be <= "+ max).fadeIn('slow');
          $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
          return false;  
        }
        else
        {
          $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
          $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
          $('form#addDeliveryDetailForm #'+id).addClass('is-valid');   
        }
      }
    });

    $(document).on('click','#cancelDeliveryDetail',function(e){
      $('.addDeliveryDetailCard').toggle(200);
      $('#addDeliveryDetail').collapse('hide');
      
    });

    $(document).on('click','.delete_delivery_transaction',function(e){
      $(this).fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeIn();
    });

    $(document).on('click', '.delete_delivery_transaction_no', function (event) {
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction').fadeIn(20);
    });

    $(document).on('click', '.delete_delivery_transaction_yes', function (event) {
      // $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeOut(1);
      

      var $this                 = $(this);
      var sale_delivery_id  = $(this).data('sale_delivery_id');
      var sale_id           = $('#addDeliveryDetailForm').find('input[name="sale_id"]').val();

      $(this).closest('td').find('.delete_delivery_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('sale/delete_sale_delivery')?>",
          type: "POST",
          data: {
            'sale_delivery_id':sale_delivery_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $('#is_there_change_in_delivery_transaction').val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                $.ajax({
                  url: "<?php echo base_url('sale/view_delivery')?>",
                  type: "POST",
                  data: {
                    'sale_id':sale_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                  dataType: "JSON",
                  success: function(data){

                    // if all quantity is delivered, hide the add new delivery button
                    if(data.is_all_products_delivered == true)
                    {
                      $('#addNewDeliveryDetail').css('display','none');
                    }
                    else
                    {
                      $('#addNewDeliveryDetail').css('display','block'); 
                    }  

                    // $(".add_delivery_detail").html(data.add_delivery_detail);
                    $(".delivery_entries").html(data.delivery_transaction);
                    $(".sale_delivery_detail").html(data.sale_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="sale_id"]').val(sale_id);
                  }
                });

                
              }

            },500);
          }
        });
      }, 500);
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('body').on('click', '.transactionEntries', function() {
      $('#transactionEntries').collapse("show");
      $('#addTransaction').collapse("hide");
      $('#saleDetail').collapse("hide");
    });

    $('body').on('click', '.addTransaction', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("show");
      $('#saleDetail').collapse("hide");
    });

    $('body').on('click', '.saleDetail', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("hide");
      $('#saleDetail').collapse("show");
    });

    $(document).on('click', '.make_payment', function (event) {
      var transaction_amount = $(this).data('transaction_amount');
      $('#transaction_amount').val(transaction_amount);
      $('.addTransaction').trigger('click');
    });

    $('body').on('change', '#payment_mode', function() {
      var payment_mode = $(this).val();

      if(payment_mode == '')
      {
        $('.transaction_mode_row').fadeOut(10);
      }
      else if(payment_mode == 0)
      {
        $('.transaction_mode_row').fadeOut(10);
      }
      else if(payment_mode == 1)
      {
        $('.transaction_mode_row').fadeOut(10);
        $('.credit_card_row').fadeIn();
      }
      else if(payment_mode == 2)
      {
        $('.transaction_mode_row').fadeOut(10);
        $('.cheque_row').fadeIn();
      }

      $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
      $('form#addTransactionForm #credit_card_no').removeClass('is-valid');

      $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
      $('form#addTransactionForm #cheque_no').removeClass('is-valid');
    });    

    $('body').on('click', '#addTransactionSubmit', function(e) {
      e.preventDefault();
      var isError = false;

      $('form#addTransactionForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addTransactionForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addTransactionForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addTransactionForm #err_"+id).text("").fadeOut('slow');
            $('form#addTransactionForm #'+id).removeClass('is-invalid');
            $('form#addTransactionForm #'+id).addClass('is-valid');
          }
      });

      if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
      {
        var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
        $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
        $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
        isError = true;
      }
      else
      {
        $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
        $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
        $('form#addTransactionForm #credit_card_no').addClass('is-valid');
      }

      if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
      {
        var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
        $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
        $('form#addTransactionForm #cheque_no').addClass('is-invalid');
        isError = true;
      }
      else
      {
        $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
        $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
        $('form#addTransactionForm #cheque_no').addClass('is-valid');
      }

      if(isError == true)
      {
        return false;
      }  
      else 
      {
        var transactionFormData = $('#addTransactionForm').serialize();
        var sale_id = $('#sale_id').val();

        $('#addTransactionSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled')

        $.ajax({
          url: "<?php echo base_url('transaction/add')?>",
          type: "POST",
          data: transactionFormData,
          dataType: "JSON",
          success: function(data){

            $(".transaction_entries").html(data.previous_transaction);

            $('#addTransactionSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            $('#is_there_change_in_transaction').val('true');

            $.ajax({
              url: "<?php echo base_url('sale/view')?>/"+sale_id,
              type: "GET",
              dataType: "JSON",
              success: function(data){

                $(".sale_detail").html(data.sale_view);
                $(".add_transaction").html(data.add_transaction);
                $("#to_account").val(data.to_account);
                $(".transaction_entries").html(data.previous_transaction_view);

                if(data.due_amount == 0)
                {
                  $('.addTransactionCard').css('display','none');
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#saleDetail').collapse("hide");  
                }
                else
                {
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#addTransaction').collapse("hide");
                  $('#saleDetail').collapse("hide");  
                }
              }
            });
          }
        });
      } 

    });

    $(document).on("blur change keyup", "form#addTransactionForm .field_validation" , function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addTransactionForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addTransactionForm #err_"+id).text("").fadeOut('slow');
          $('form#addTransactionForm #'+id).removeClass('is-invalid');
          $('form#addTransactionForm #'+id).addClass('is-valid');
        }

        if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
        {
          var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
          $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
          $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
          $('form#addTransactionForm #credit_card_no').addClass('is-valid');
        }

        if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
        {
          var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
          $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #cheque_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
          $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
          $('form#addTransactionForm #cheque_no').addClass('is-valid');
        }
    });

    $(document).on("blur change keyup","form#addTransactionForm #credit_card_no",  function (event){
              
        if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
        {
          var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
          $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
          $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
          $('form#addTransactionForm #credit_card_no').addClass('is-valid');
        }
        
    });

    $(document).on("blur change keyup","form#addTransactionForm #cheque_no",  function (event){              
        
        if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
        {
          var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
          $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #cheque_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
          $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
          $('form#addTransactionForm #cheque_no').addClass('is-valid');
        }
    });

    $(document).on('click', '.delete_transaction', function (event) {
      $(this).fadeOut(10);
      $(this).closest('td').find('.delete_transaction_confirmation').fadeIn();
    });

    $(document).on('click', '.delete_transaction_no', function (event) {
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_transaction').fadeIn(20);
    });

    $(document).on('click', '.delete_transaction_yes', function (event) {
      // $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(1);
      

      var $this           = $(this);
      var transaction_id  = $(this).data('transaction_id');
      var sale_id         = $(this).data('entry_id');

      $(this).closest('td').find('.delete_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('transaction/delete')?>",
          type: "POST",
          data: {
            'transaction_id':transaction_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $('#is_there_change_in_transaction').val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                $.ajax({
                  url: "<?php echo base_url('sale/view')?>/"+sale_id,
                  type: "GET",
                  dataType: "JSON",
                  success: function(data){

                    $(".sale_detail").html(data.sale_view);
                    $(".add_transaction").html(data.add_transaction);
                    $("#to_account").val(data.to_account);
                    $(".transaction_entries").html(data.previous_transaction_view);

                    if(data.due_amount == 0)
                    {
                      $('.addTransactionCard').css('display','none');
                      // collapse all accordian
                      $('#transactionEntries').collapse("show");
                      $('#saleDetail').collapse("hide");  
                    }
                    else
                    {
                      // collapse all accordian
                      $('.addTransactionCard').css('display','block');
                      $('#transactionEntries').collapse("hide");
                      $('#addTransaction').collapse("hide");
                      $('#saleDetail').collapse("show");  
                    }
                  }
                });  
              }

            },500);
          }
        });
      }, 500);
    });

    $(document).on('show.bs.modal','#sale_edit', function (e) {
      var sale_id = $(e.relatedTarget).data('sale_id');
      $('#sale_edit').find('#id').val(sale_id);

      // alert(sale_id);

      $.ajax({
        url: "<?php echo base_url('sale/sale_edit_confirmation')?>",
        type: "POST",
        data:{
          'sale_id': sale_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#sale_edit').find('.modal-content').html(data.sale_edit_modal_body);
        }
      });
    });

    const messageToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    // Copy to clipboard
    $(document).on('click','.copy-to-clickboard',function(e){
      e.preventDefault();
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(this).data('url')).select();
      document.execCommand("copy");
      $temp.remove();

      messageToast.fire({
        type: 'success',
        title: "Link is copied into your clipboard. CTRL + V to paste the URL."
      });  

    })

  })
</script>