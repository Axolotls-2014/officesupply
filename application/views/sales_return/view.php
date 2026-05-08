<?php 
  $this->load->view('layout/header');

  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);

  $mfg_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);

  $promotion = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'promotion', 'active',$row = true,$check_delete_status = false);
?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_inventory')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('sales_return')?>"><?=$this->lang->line('header_sales_return')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('sales_return_view')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">

        <?php 
            if ($sales_return->document != NULL)
            {
          ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Attached documents</h3>
                </div>
                <div class="card-body">
                  <div class="row">
                      <div class="col-12">
                        <?php
                          $documents = explode(',', $sales_return->document);
                          $company_settings 	= $this->company_settings_model->get_company_records();
                          $cid = $company_settings->cid;
                          
                          foreach ($documents as $document) {

                              $documentPath = base_url('assets/documents/'.$cid.'/sales_return/' . trim($document));
                              
                          ?>
                          <div class="btn-group attached-file" style="margin-top:5px; margin-left:5px;margin-right:5px;">
                              <a href="<?= $documentPath ?>" class="btn btn-default"><?= $document ?></a>
                              <a href="<?= $documentPath ?>" class="btn btn-primary" download="<?= trim($document) ?>"><i class="fas fa-download"></i></a>
                          </div>

                        <?php } ?>
                      </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
          <?php 
            }
          ?>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Shortcuts</h3>
            </div>
            <div class="card-body">
            <?php 
                  //Manage Transaction
                  if($this->permission_model->has_permission('manage_transaction'))
                  { 
                ?>
                    <a href="#" data-target="#transaction-modal" data-toggle="modal" class="btn bg-purple btn-sm mr-1" data-tt="tooltip" title="Enter Payment" data-sales_return_id="<?= $sales_return->id?>"> 
                      <i class="fas fa-rupee-sign"></i> Enter Payment
                    </a>  
                <?php                    
                  }
                ?>

                <?php
                  //Manage sales_return Delievery
                  if($this->permission_model->has_permission('manage_sale_return_delivery'))
                  { 
                ?>
                    <a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn bg-maroon btn-sm open_delivery_modal mr-1" data-tt="tooltip" title="Enter Product Delivery" data-sales_return_id="<?=$sales_return->id?>"> 
                      <i class="fas fa-shipping-fast"></i> Delivery Detail
                    </a>  
                <?php
                  }
                ?>

                <?php
                  //PDF Button
                  if($this->permission_model->has_permission('pdf_download_sale_return'))
                  { 
                ?>
                    <a href="<?=base_url('sales_return/pdf/'.base64_encode($sales_return->id))?>" class="btn bg-orange btn-sm mr-1" data-tt="tooltip" title="Generate PDF">
                      <i class="far fa-file-pdf"></i> Downlaod Sales Return
                    </a>      
                <?php
                  }
                ?>
            </div>
          </div>
         
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('sales_return_view')?></h3>
              <div class="card-tools">
                <?php 
                  if($this->permission_model->has_permission('edit_sales_return'))
                  {
                ?>
                  <a href="<?=base_url('sales_return/edit/'.base64_encode($sales_return->id))?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-edit"></i> <?=$this->lang->line('edit')?>
                  </a>
                <?php 
                  }
                ?>

                <?php
                  // Edit Button   
                  $delivered_product_qty = $this->sales_return_delivery_model->get_total_no_of_quantity_delivered($sales_return->id);
                   $paid_amount  = $this->transaction_model->get_total_transaction_amount($sales_return->id, SALE_RETURN_MODULE, PAYMENT_TRANSACTION_TYPE);
            
                  if($this->permission_model->has_permission('edit_sale_return') || $this->permission_model->has_permission('edit_all_sales_return'))
                  { 
                     if($delivered_product_qty > 0 || $paid_amount > 0)
                    {
                ?>
                      <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#sales_return_edit" data-tt="tooltip" title="Edit Sales Return" data-sales_return_id="<?= $sales_return->id ?>">
                        <i class="fas fa-edit"></i> Edit
                      </a> 
                <?php
                    }
                    else
                    {
                ?>
                      <a href="<?=base_url('sales_return/edit/'.base64_encode($sales_return->id))?>" class="btn btn-info btn-sm"  data-tt="tooltip" title="Edit Sales Return">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                <?php
                    }
                  }
                ?>
                
              </div>
            </div>
            <div class="card-body">
              <div class="invoice p-3 mb-3">

                <?php
                  $sales_return_amount      = $sales_return->total;
                  $sales_return_paid_amount = $this->transaction_model->get_total_transaction_amount($sales_return->id,SALE_RETURN_MODULE,PAYMENT_TRANSACTION_TYPE);  
                ?>
                <div class="ribbon-wrapper ribbon-sm">
                  <div class="ribbon 
                    <?php 
                      if($sales_return_paid_amount == $sales_return_amount)
                      {
                        echo 'bg-success';
                      }
                      else if($sales_return_paid_amount < $sales_return_amount && $sales_return_paid_amount > 0)
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
                      if($sales_return_paid_amount >= $sales_return_amount)
                      {
                        echo $this->lang->line('paid');
                      }
                      else if($sales_return_paid_amount < $sales_return_amount && $sales_return_paid_amount > 0)
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
                <!-- title row -->
                <div class="row">
                  <div class="col-12 text-center">
                    <?=$this->lang->line('tax_sales_return_under_gst')?> <br/><?=$this->lang->line('sales_return_for_recipient')?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <h4>
                      <i class="fas fa-globe"></i> <?=$company_setting->company_name?>
                      <small class="float-right"> <?=$this->lang->line('date')?>: <?=date('d-m-Y', strtotime($sales_return->sales_return_date))?></small>
                    </h4>
                  </div>
                  <!-- /.col -->
                </div>
                <div class="row">
                  <div class="col-12">
                    <table width="100%" class="table">
                      <tr style="font-size: 16px;font-weight: bolder;">
                        <td><?=$this->lang->line('supplier')?></td>
                        <td><?=$this->lang->line('name_address_customer')?></td>
                        <td><?=$this->lang->line('sales_return_details')?></td>
                      </tr>
                      <tr>
                        <td>
                          <address>
                            <strong><?=$company_setting->company_name?></strong><br>
                            <?=$company_setting->address_line1?><br>
                            <?=$company_setting->address_line2?><br>
                            <?=$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name?><br>
                            <?=$company_setting->pincode?><br>
                            <?=$this->lang->line('phone')?>: <?=$company_setting->mobile?><br>
                            <?=$this->lang->line('email')?>: <?=$company_setting->email?><br>
                            <strong><?=$this->lang->line('gstin')?>: <?=$company_setting->gstin?></strong><br>
                          </address>
                        </td>
                        <td>
                          <address>
                            <strong><?=$customer_detail->customer_name?></strong><br>
                            <?= ($customer_detail->address != '') ? ($customer_detail->address.'<br/>') : '' ?>
                            <?=$customer_detail->city_name.', '.$customer_detail->state_name.', '.$customer_detail->country_name?><br>
                            <b><?=$this->lang->line('phone')?></b>: <?=$customer_detail->phone?><br>
                            <b><?=$this->lang->line('email')?></b>: <?=$customer_detail->email?><br>
                            <b><?=$this->lang->line('gstin')?></b>: <?=$customer_detail->gstin?><br>
                          </address>
                        </td>
                        <td>
                          <b><?=$this->lang->line('sales_return_id')?></b><?=$sales_return->reference_no?><br>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
               
                <!-- Table row -->
                <div class="row">
                  <div class="col-12 table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th><?=$this->lang->line('sales_return_sr')?></th>
                          <th><?=$this->lang->line('sales_return_items')?></th>
                          <th><?=$this->lang->line('sales_return_hsn')?></th>
                          <th><?=$this->lang->line('sales_return_qty')?></th>
                          <th class="<?= empty($promotion) ? 'd-none' : '' ?>"><?=$this->lang->line('proforma_invoice_free_qty')?></th>
                          <th><?=$this->lang->line('sales_return_price')?> (<?=$this->session->userdata('currency_symbol')?>)</th>
                          <th class="span2"><?=$this->lang->line('proforma_invoice_batch')?></th>
                          <th class="<?= empty($mfg_date) ? 'd-none' : '' ?>"><?=$this->lang->line('product_mfg_date')?></th>
                          <th class="<?= empty($expiry_date) ? 'd-none' : '' ?>"><?=$this->lang->line('product_expiry_date')?></th>
                          <th><?=$this->lang->line('sales_return_discount')?> (<?=$this->session->userdata('currency_symbol')?>)</th>
                          <th><?=$this->lang->line('sales_return_uom')?></th>
                          <th><?=$this->lang->line('sales_return_total_taxable_value')?> (<?=$this->session->userdata('currency_symbol')?>)</th>
                          <th><?=$this->lang->line('sales_return_tax')?> (<?=$this->session->userdata('currency_symbol')?>)</th>
                          <th><?=$this->lang->line('sales_return_sub_total')?> (<?=$this->session->userdata('currency_symbol')?>)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $i = 1;
                          $total_quantity = 0;
                          $total_price    = 0;
                          $total_discount_amount = 0;
                          $total_taxable_value = 0;
                          $total_tax = 0;
                          $total_sub_total = 0;

                          $colspan = 14;

                          if($mfg_date == '')
                            $colspan = $colspan-1;

                          if($expiry_date == '')
                            $colspan = $colspan-1;
                          
                          if($promotion == '')
                            $colspan = $colspan-1; 

                          foreach ($sales_return_items as $row) 
                          {
                            $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);

                            $total_quantity         += $row->quantity;
                            $total_price             += $row->price;
                            $total_discount_amount  += $row->discount_amount;
                            $total_taxable_value    += $row->taxable_value;
                            $total_tax              += $row->cgst_tax+$row->sgst_tax+$row->igst_tax;
                            $total_sub_total         += $row->sub_total;
                        ?>
                          <tr>
                            <td><?=$i++?></td>
                            <td><?=$row->product_name.'<br/>'.$row->description?></td>
                            <td><?=$row->hsn?></td>
                            <td><?=$row->quantity?></td>
                            <td class="<?= empty($promotion) ? 'd-none' : '' ?>"><?=$row->free_quantity?></td>
                            <td><?=number_format_i($row->price)?></td>
                            <td><?=$warehouse_product->batch_no?> </td>

                            <td class="<?= empty($mfg_date) ? 'd-none' : '' ?>"><?=($row->mfg_date != '' && $row->mfg_date != '0000-00-00') ? date('d-m-Y',strtotime($row->mfg_date)) : ''?></td>

                            <td class="<?= empty($expiry_date) ? 'd-none' : '' ?>"><?=($row->expiry_date != '' && $row->expiry_date != '0000-00-00') ? date('d-m-Y',strtotime($row->expiry_date)) : ''?></td>

                            <td><?=number_format_i($row->discount_amount)?></td>
                            <td><?=$row->uom_uom?></td>
                            <td><?=number_format_i($row->taxable_value)?></td>
                            <td>
                              <?php 
                                if($company_setting->country_id == $customer_detail->country_id)
                                {
                                  if($company_setting->state_id == $customer_detail->state_id)
                                  {
                              ?>
                                    CGST : <?=number_format_i($row->cgst_tax)?>
                                  
                                    <br>SGST : <?=number_format_i($row->sgst_tax)?>
                                   
                              <?php
                                  }
                                  else
                                  {
                              ?>
                                    IGST : <?=number_format_i($row->igst_tax)?>
                                   
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
                            <td colspan="3">
                              <?=$this->lang->line('total_amount_due')?>   
                            </td>
                            <td><?=$total_quantity?></td>
                            <td class="<?= empty($promotion) ? 'd-none' : '' ?>"></td>
                            <td><?=number_format_i($total_price)?></td>
                            <td></td>
                            <td class="<?= empty($mfg_date) ? 'd-none' : '' ?>"></td>
                            <td class="<?= empty($expiry_date) ? 'd-none' : '' ?>"></td>
                            <td><?=number_format_i($total_discount_amount)?></td>
                            <td></td>
                            <td><?=number_format_i($total_taxable_value)?></td>
                            <td><?=number_format_i($total_tax)?></td>
                            <td><?=number_format_i($total_sub_total)?></td>
                          </tr>
                          <tr style="font-size: 16px;font-weight: bolder;">
                            <td colspan="<?=($colspan-1)?>">
                              <?=$this->lang->line('rounded_off')?>
                            </td>
                            <td>
                              <?php 
                                if((round($sales_return->total_taxable_value+$sales_return->total_tax) - ($sales_return->total_taxable_value+$sales_return->total_tax)) > 0)
                                {
                                  echo '+'.number_format_i((round($sales_return->total_taxable_value+$sales_return->total_tax) - ($sales_return->total_taxable_value+$sales_return->total_tax)));
                                }
                                else
                                {
                                  echo number_format_i((round($sales_return->total_taxable_value+$sales_return->total_tax) - ($sales_return->total_taxable_value+$sales_return->total_tax)));
                                }
                              ?>
                            </td>
                          </tr>
                          
                          <tr style="font-size: 16px;font-weight: bolder;">
                            <td colspan="<?=($colspan-1)?>">
                              <?=$this->lang->line('paid_amount')?>
                            </td>
                            <td>
                              <?php echo number_format_i($sales_return_paid_amount);?>
                            </td>
                          </tr>
                          <tr style="font-size: 16px;font-weight: bolder;">
                            <td colspan="<?=($colspan-1)?>">
                              <?=$this->lang->line('balance_payable')?>
                            </td>
                            <td>
                              <?php echo number_format_i(round($sales_return->total_taxable_value+$sales_return->total_tax-$sales_return_paid_amount));?>
                            </td>
                          </tr>
                          <tr style="font-size: 16px;font-weight: bold; ">
                            <td colspan="<?=($colspan%2 == 0) ? ($colspan/2) : (round($colspan/2) + 1) ?>">
                              <?=$this->lang->line('amount_in_words')?>
                            </td>
                            <td colspan="<?=($colspan%2 == 0) ? ($colspan/2) : (round($colspan/2) - 1) ?>" style="text-align: right">
                             <?php 
    $amount_in_words = $this->numbertowords->convert_number(round($sales_return->total_taxable_value + $sales_return->total_tax - $sales_return_paid_amount));
    echo ucwords(strtolower(trim($amount_in_words))) . ' Only'; 
    ?>
                            </td>
                          </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- /.row -->

                <div class="row">
                  <div class="col-12">
                    <table width="100%" class="table">
                      <tr style="font-size: 16px;font-weight: bolder;">
                        <td width="50%">
                          <?=$this->lang->line('terms_condition')?>
                        </td>
                        <td width="25%"></td>
                        <td width="25%">
                         <?=$this->lang->line('certified_message')?>                         
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <?=$sales_return->terms_and_condition?>
                        </td>
                        <td></td>
                        <td>
                          <?=$this->lang->line('for')?>, <?=strtoupper($company_setting->company_name)?><br/><br/><br/>

                          <?php 
                          $signature_data = '';

                          $company_settings 	= $this->company_settings_model->get_company_records();
                          $cid = $company_settings->cid;

                          if($company_setting->signature != '')
                          {
                            $signature_path = './assets/images/'.$cid.'/' . $company_setting->signature; // Adjust the path accordingly
                            if (file_exists($signature_path)) {
                              $signature_data = file_get_contents($signature_path);
                              $base64_data    = base64_encode($signature_data);
                          ?>
                            <img src="data:image/png;base64,<?=$base64_data?>" style="width: 100px;">
                          <?php
                            } 
                            }
                            else
                            {
                              echo '<br/><br/><br/>';
                            }
                          ?> 
                          <br/> 
                          <?=$this->lang->line('signatory')?>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>

                <!-- this row will not appear when printing -->
                <div class="row no-print">
                  <div class="col-12">
                    
                    <!-- <button type="button" class="btn btn-success float-left print_invoice">
                      <i class="fas fa-print"></i> <?=$this->lang->line('print')?>
                    </button> -->
                    <!-- <button type="button" class="btn btn-success float-right">
                      <i class="far fa-credit-card"></i> Submit Payment
                    </button>
                    <a class="btn btn-primary float-right" style="margin-right: 5px;" href="<?=base_url('sales_return/pdf/'.$sales_return->id)?>" target="_blank">
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
            <?php echo $this->lang->line('sales_return_transaction');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="accordion">
            <div class="card card-secondary">
              <div class="card-header sales_returnDetail light-secondary-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#sales_returnDetail" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('sales_return_view')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="sales_returnDetail" class="panel-collapse in collapse" style="">
                <div class="card-body sales_return_detail  m-0 p-0">
                </div>
              </div>
            </div>
            <div class="card card-danger addTransactionCard">
              <div class="card-header addTransaction light-failure-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#addTransaction" class="" aria-expanded="true">
                    <?=$this->lang->line('sales_return_payment')?>
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
                    <input type="hidden" name="transaction_type" value="<?=PAYMENT_TRANSACTION_TYPE?>">
                    <input type="hidden" name="entry_id" id="sales_return_id" value="">
                     <input type="hidden" name="warehouse_id" value="<?= $warehouse->id ?>">
                    <input type="hidden" name="module" value="<?=SALE_RETURN_MODULE?>">
                    <input type="hidden" name="to_account" id="to_account" value="">
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
                    <?=$this->lang->line('sales_return_previous_transaction')?>
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
            <?php echo $this->lang->line('sales_return_delivery');?>
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
                        <?=$this->lang->line('sales_return_delivery_add')?>
                        <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                      </a>
                    </h5>
                  </div>
                  <div id="addDeliveryDetail" class="panel-collapse in collapse" style="">
                    <div class="card-body add_delivery_detail">
                    
                    </div>
                    <div class="card-footer">
                      <input type="hidden" name="delivery_items" value="">
                      <input type="hidden" name="sales_return_id" value="">
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
                    <?=$this->lang->line('previous_sales_return_delivery')?>
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
  <div class="modal fade" id="sales_return_edit">
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
    $('#transaction-modal').on('show.bs.modal', function (e) {

      $('.addTransactionCard').css('display','block');

      var sales_return_id = $(e.relatedTarget).data('sales_return_id');

      if (!(typeof sales_return_id === 'undefined')) {

        $('#sales_return_id').val(sales_return_id);
      
        $.ajax({
          url: "<?php echo base_url('sales_return/view')?>/"+sales_return_id,
          type: "GET",
          dataType: "JSON",
          success: function(data){

            $(".sales_return_detail").html(data.sales_return_view);
            $(".add_transaction").html(data.add_transaction);
            $("#to_account").val(data.to_account);
            $(".transaction_entries").html(data.previous_transaction_view);

            $('#transaction-modal').find('input[name="voucher_date"]').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
            });

            if(data.due_amount == 0)
            {
              $('.addTransactionCard').css('display','none');
              // collapse all accordian
              $('#transactionEntries').collapse("hide");
              $('#sales_returnDetail').collapse("show");  
            }
            else
            {
              // collapse all accordian
              $('#transactionEntries').collapse("hide");
              $('#addTransaction').collapse("hide");
              $('#sales_returnDetail').collapse("show");  
            }
          }
        });
      }
    });

    $(document).on('hide.bs.modal', '#transaction-modal', function (e) {

        if($('#is_there_change_in_transaction').val() == "true")
        {
          location.reload();
        }

    });

    $('#delivery-modal').on('show.bs.modal', function (e) {

      var sales_return_id = $(e.relatedTarget).data('sales_return_id');
      $('#sales_return_id').val(sales_return_id);
      $('#addNewDeliveryDetail').data('sales_return_id',sales_return_id);

      if($(e.relatedTarget).hasClass('open_delivery_modal'))
      {
        $.ajax({
          url: "<?php echo base_url('sales_return/view_delivery')?>",
          type: "POST",
          data: {
            'sales_return_id':sales_return_id,
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
            $(".sales_return_delivery_detail").html(data.sales_return_delivery_detail);
            
            $('.view_delivery_transaction_detail').css('display','none');
            $('#deliveryEntries').collapse("show");

            $('#addDeliveryDetailForm').find('input[name="sales_return_id"]').val(sales_return_id);
          }
        });  
      }      
    });


    $(document).on('hide.bs.modal', '#delivery-modal', function (e) {
   
      if($('#is_there_change_in_delivery_transaction').val() == "true")
      {
        location.reload();
      }
    });


  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('.sales_returnDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

    // View delivery transaction
    $(document).on('click', '.view_delivery_transaction', function (event) {

      $('.view_delivery_transaction_detail').css('display','none');
      $(this).parents('tr').next('tr').toggle(200);
      
    });

    // Add new delivery of sales_return
    $(document).on('click','#addNewDeliveryDetail',function(e){

      $('.addDeliveryDetailCard').toggle(200);

      var sales_return_id = $('#addDeliveryDetailForm').find('input[name="sales_return_id"]').val();

      $.ajax({
        url: "<?php echo base_url('sales_return/add_sales_return_delivery')?>/"+sales_return_id,
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

    // Populate delivery data of sales_return
    $(document).on('click','#addDeliveryDetailSubmit',function(e){
      e.preventDefault();

      var deliveryDataArray = [];

      $("#product_delivery_item_body").find('tr').each(function () {

        var tr                      = $(this).closest("tr");
        var deliveryData            = {};

        deliveryData['warehouse_product_id']  = tr.find('input[name="warehouse_product_id"]').data('warehouse_product_id');
        deliveryData['product_id']  = tr.find('input[name="quantity"]').data('product_id');
        deliveryData['quantity']    = tr.find('input[name="quantity"]').val();

        deliveryData['cost']            = tr.find('input[name="quantity"]').data('cost');
        deliveryData['price']           = tr.find('input[name="quantity"]').data('price');
        deliveryData['selling_price']   = tr.find('input[name="quantity"]').data('selling_price');

        deliveryDataArray.push(JSON.stringify(deliveryData));
      });

       
      $('#addDeliveryDetailForm').find('input[name="delivery_items"]').val(deliveryDataArray.join('|'));

      $('#addDeliveryDetailForm').trigger('submit');
    });

    // Submit delivery data of sales_return
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
        var sales_return_id             = $('form#addDeliveryDetailForm').find('input[name="sales_return_id"]').val(); 

        $('#addDeliveryDetailSubmit').text('Please wait while we are saving').addClass('disabled');

        setTimeout(function(){ 

          $.ajax({
            url: "<?php echo base_url('sales_return/add_sales_return_delivery')?>",
            type: "POST",
            data: DeliveryDetailFormData,
            dataType: "JSON",
            success: function(data){


              if(data.code == 1)
              {
                $('#addDeliveryDetailSubmit').text('Submit').removeClass('disabled');
                $.ajax({
                  url: "<?php echo base_url('sales_return/view_delivery')?>",
                  type: "POST",
                  data: {
                    'sales_return_id':sales_return_id,
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

                    $('.addDeliveryDetailCard').toggle(200);
                    $(".add_delivery_detail").html('');
                    $(".delivery_entries").html(data.delivery_transaction);
                    // $(".sales_return_delivery_detail").html(data.sales_return_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="sales_return_id"]').val(sales_return_id);

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
      

      var $this                     = $(this);
      var sales_return_delivery_id  = $(this).data('sales_return_delivery_id');
      var sales_return_id           = $('#addDeliveryDetailForm').find('input[name="sales_return_id"]').val();

      $(this).closest('td').find('.delete_delivery_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('sales_return/delete_sales_return_delivery')?>",
          type: "POST",
          data: {
            'sales_return_delivery_id':sales_return_delivery_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $("#is_there_change_in_delivery_transaction").val("true");

            setTimeout(function(){ 

              if(data.code == 1)
              {
                

                $.ajax({
                  url: "<?php echo base_url('sales_return/view_delivery')?>",
                  type: "POST",
                  data: {
                    'sales_return_id':sales_return_id,
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
                    $(".sales_return_delivery_detail").html(data.sales_return_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="sales_return_id"]').val(sales_return_id);
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
      $('#sales_returnDetail').collapse("hide");
    });

    $('body').on('click', '.addTransaction', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("show");
      $('#sales_returnDetail').collapse("hide");
    });

    $('body').on('click', '.sales_returnDetail', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("hide");
      $('#sales_returnDetail').collapse("show");
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
      else if(payment_mode == 3)
      {
        $('.transaction_mode_row').fadeOut(10);
      }

      $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
      $('form#addTransactionForm #credit_card_no').removeClass('is-valid');

      $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
      $('form#addTransactionForm #cheque_no').removeClass('is-valid');
    });    

    $('body').on('submit', '#addTransactionForm', function(e) {
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

      var closing_balance     = parseFloat($('form#addTransactionForm #from_account').find(':selected').data('closing_balance'));
      var transaction_amount  = parseFloat($('form#addTransactionForm #transaction_amount').val()); 


      if(closing_balance < transaction_amount)
      {
        var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');
        var text        = $('form#addTransactionForm #from_account').find(':selected').text();
        
        $("form#addTransactionForm #err_from_account").text("There is not enough balance in "+text).fadeIn('slow');
        $('form#addTransactionForm #from_account').addClass('is-invalid');
        isError = true;
      }
      else if($('form#addTransactionForm #from_account').find(':selected').val() == '' || $('form#addTransactionForm #from_account').find(':selected').val() == null)
      {
        var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');

        $("form#addTransactionForm #err_from_account").text(placeholder+ " field is required.").fadeIn('slow');
        $('form#addTransactionForm #from_account').addClass('is-invalid');
        isError = true;
      }
      else
      {
        $("form#addTransactionForm #err_from_account").text("").fadeOut('slow');
        $('form#addTransactionForm #from_account').removeClass('is-invalid');
        $('form#addTransactionForm #from_account').addClass('is-valid');
      }

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
        var sales_return_id = $('#sales_return_id').val();

        $('#addTransactionSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled')
        

        $.ajax({
          url: "<?php echo base_url('transaction/add')?>",
          type: "POST",
          data: transactionFormData,
          dataType: "JSON",
          success: function(data){

            $(".transaction_entries").html(data.previous_transaction);
            $("#is_there_change_in_transaction").val('true');

            $.ajax({
              url: "<?php echo base_url('sales_return/view')?>/"+sales_return_id,
              type: "GET",
              dataType: "JSON",
              success: function(data){

                $('#addTransactionSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        
                $(".sales_return_detail").html(data.sales_return_view);
                $(".add_transaction").html(data.add_transaction);
                $("#from_account").val(data.from_account);
                $(".transaction_entries").html(data.previous_transaction_view);

                if(data.due_amount == 0)
                {
                  $('.addTransactionCard').css('display','none');
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#sales_returnDetail').collapse("hide");  
                }
                else
                {
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#addTransaction').collapse("hide");
                  $('#sales_returnDetail').collapse("hide");  
                }
              }
            });
          }
        });
      } 

    });

    $(document).on("blur change keyup","form#addTransactionForm .field_validation",  function (event){
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

        var closing_balance     = parseFloat($('form#addTransactionForm #from_account').find(':selected').data('closing_balance'));
        var transaction_amount  = parseFloat($('form#addTransactionForm #transaction_amount').val()); 

        if(closing_balance < transaction_amount)
        {
          var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');
          var text        = $('form#addTransactionForm #from_account').find(':selected').text();

          $("form#addTransactionForm #err_from_account").text("There is not enough balance in "+text).fadeIn('slow');
          $('form#addTransactionForm #from_account').addClass('is-invalid');
          return false;
        }
        else if($('form#addTransactionForm #from_account').find(':selected').val() == '' || $('form#addTransactionForm #from_account').find(':selected').val() == null)
        {
          var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');
          
          $("form#addTransactionForm #err_from_account").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #from_account').addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addTransactionForm #err_from_account").text("").fadeOut('slow');
          $('form#addTransactionForm #from_account').removeClass('is-invalid');
          $('form#addTransactionForm #from_account').addClass('is-valid');
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
      var sales_return_id         = $(this).data('entry_id');

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
            $("#is_there_change_in_transaction").val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                $.ajax({
                  url: "<?php echo base_url('sales_return/view')?>/"+sales_return_id,
                  type: "GET",
                  dataType: "JSON",
                  success: function(data){

                    $(".sales_return_detail").html(data.sales_return_view);
                    $(".add_transaction").html(data.add_transaction);
                    $("#from_account").val(data.from_account);
                    $(".transaction_entries").html(data.previous_transaction_view);

                    if(data.due_amount == 0)
                    {
                      $('.addTransactionCard').css('display','none');
                      // collapse all accordian
                      $('#transactionEntries').collapse("show");
                      $('#sales_returnDetail').collapse("hide");  
                    }
                    else
                    {
                      // collapse all accordian
                      $('.addTransactionCard').css('display','block');
                      $('#transactionEntries').collapse("hide");
                      $('#addTransaction').collapse("hide");
                      $('#sales_returnDetail').collapse("show");  
                    }
                  }
                });  
              }

            },500);
          }
        });
      }, 500);
    });

    $(document).on('show.bs.modal','#sales_return_edit', function (e) {
      var sales_return_id = $(e.relatedTarget).data('sales_return_id');
      $('#sales_return_edit').find('#id').val(sales_return_id);

      // alert(sales_return_id);

      $.ajax({
        url: "<?php echo base_url('sales_return/sales_return_edit_confirmation')?>",
        type: "POST",
        data:{
          'sales_return_id': sales_return_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#sales_return_edit').find('.modal-content').html(data.sales_return_edit_modal_body);
        }
      });


    });
  })
</script>