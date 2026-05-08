<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ionicons.min.css">
   <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"> -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font_family.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <link href="<?php echo base_url();?>assets/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/all.css">

  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/square/blue.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/autocomplete/') ?>/jquery_auto_complete.css">



  
<?php 
  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);

  $mfg_date   = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);

  $promotion = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'promotion', 'active',$row = true,$check_delete_status = false);

  $lr_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'lr_no', 'active',$row = true,$check_delete_status = false);

  $lr_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'lr_date', 'active',$row = true,$check_delete_status = false);

  $carrier = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'carrier', 'active',$row = true,$check_delete_status = false);

  $ewaybill_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'ewaybill_no', 'active',$row = true,$check_delete_status = false);

  $batch_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'batch_no', 'active',$row = true,$check_delete_status = false);

  
?>
<style>
/*    .invoice-container {*/
/*    max-width: 800px;*/
/*    margin: 0 auto;*/
/*    padding: 20px;*/
/*    border: 1px solid #ccc;*/
/*    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
/*}*/

/* Header */
header, footer {
    text-align: center;
    margin-bottom: 20px;
}

/* Company and Client Info */
.company-info, .client-info {
    margin-bottom: 20px;
}

/* Invoice Table */
/*.invoice-table {*/
/*    width: 100%;*/
/*    border-collapse: collapse;*/
/*    margin-bottom: 20px;*/
/*}*/

/*.invoice-table th, .invoice-table td {*/
/*    border: 1px solid #ccc;*/
/*    padding: 10px;*/
/*    text-align: left;*/
/*}*/

/*.invoice-table th {*/
/*    background-color: #f4f4f4;*/
/*}*/

/* Footer */
footer {
    font-size: 0.9em;
    color: #555;
}
</style>
<div class="wrapper">
  <div class="content-wrapper">
  
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">

          <?php 
            if ($sale->document != NULL)
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
                          $documents = explode(',', $sale->document);
                          $company_settings 	= $this->company_settings_model->get_company_records();
                          $cid = $company_settings->cid;
                          
                          foreach ($documents as $document) {

                              $documentPath = base_url('assets/documents/'.$cid.'/sale/' . trim($document));
                              
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
            <div class="card-body">
             
              <a href="<?=base_url('sale/pdf/'.base64_encode($sale->id))?>" class="btn bg-orange btn-sm  mr-1" data-tt="tooltip" title="Download Invoice">
                <i class="far fa-file-pdf"></i> Download Invoice
              </a>  

            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('sale_view')?></h3>
              <div class="card-tools">
              <?php 
                $delivered_product_qty = $this->sale_delivery_model->get_total_no_of_quantity_delivered($sale->id);
                $paid_amount = $this->transaction_model->get_total_transaction_amount($sale->id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE) + $this->transaction_model->get_total_transaction_amount(null,SALE_MODULE,CREDIT_TRANSACTION_TYPE);

                if($this->permission_model->has_permission('edit_sale') || $this->permission_model->has_permission('edit_all_sale'))
                { 
                  if($paid_amount > 0 || $delivered_product_qty > 0)
                  {
                ?>

                    <a href="#" class="btn btn-info btn-sm  mr-1" data-toggle="modal" data-target="#sale_edit" data-tt="tooltip" title="Edit Sale" data-sale_id="<?=$sale->id?>">
                      <i class="fas fa-edit"></i> Edit
                    </a> 

                <?php 
                  }
                  else
                  {
                ?>
                    <a href="<?=base_url('sale/edit/'.base64_encode($sale->id))?>" class="btn btn-info btn-sm  mr-1"  data-tt="tooltip" title="Edit Sale">
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
                <!-- title row -->
                <?php 
                  $paid_amount  = round($this->transaction_model->get_total_transaction_amount($sale->id,SALE_MODULE,RECEIPT_TRANSACTION_TYPE) + $this->transaction_model->get_total_transaction_amount(null,SALE_MODULE,CREDIT_TRANSACTION_TYPE));
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
                
 <style>
        .bordered {
            border: 2px solid black;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid black !important; 
        }

      
        .no-padding {
            padding: 0;
        }

   
        h6, p {
            margin: 0;
        }

        .bordered-bottom {
            border-bottom: 2px solid black;
        }

        .bordered-right {
            border-right: 2px solid black;
        }

        .bordered-top {
            border-top: 2px solid black;
        }

        .full-width-table {
            width: 100%;
        }
    </style>

    <div class="container mt-3 bordered">

    <div class="row bordered-bottom no-padding">
    <div class="col-12 text-center p-2">
        <img src="<?= base_url('/assets/images/0/' . $company_setting->logo); ?>" style="width: 8%; height: auto; vertical-align: middle;">
        <h4><?= htmlspecialchars($company_setting->company_name); ?></h4>
        <h6>
            <span><h5><?= htmlspecialchars($warehouse->address_line1); ?>GSTIN- <?= htmlspecialchars($company_setting->gstin); ?></h5></span>
            <!--<span><?= htmlspecialchars($warehouse->address_line2); ?></span>-->
        </h6>
    </div>
   </div>

    <div class="row bordered-bottom no-padding">
        <div class="col-12 text-center p-2">
            <h5 class="text-danger"><?= htmlspecialchars($warehouse->license_no); ?></h5>
        </div>
    </div>
    <div class="row bordered-bottom no-padding">
        <div class="col-12 text-center p-1">
            <h6><b>TAX INVOICE</b></h6>
        </div>
    </div>
    <div class="row no-padding">
        <div class="col-6 p-2 bordered-right">
            <p>Invoice No: <?= $sale->reference_no; ?></p>
            <p>Invoice Date: <?= $sale->invoice_date; ?></p>
            <p>State: <?= $company_setting->state_name; ?></p>
        </div>
        <div class="col-6 p-2">
            <p>Transportation Mode: <?= htmlspecialchars($invoice->transport_mode); ?></p>
            <p>Vehicle No: <?= htmlspecialchars($invoice->vehicle_no); ?></p>
            <p>Mode: <?= htmlspecialchars($invoice->mode); ?></p>
        </div>
    </div>
    <hr class="bordered-bottom">

    <!-- Receiver and Consignee Details -->
    <div class="row no-padding">
        <div class="col-6 bordered-right p-1">
            <h6 class="bordered-bottom text-center"><b>Details of Receiver/Billed to:</b></h6>
            <p>Company Name: <?= $customer_detail->customer_company_name; ?></p>
            <p>Custometr name :<?= $customer_detail->customer_name?></p>
            <p>Address: <?= $customer_detail->address ?></p>
            <p>GSTIN: <?=$customer_detail->gstin  ?></p>
            <p>Email: <?= $customer_detail->email ?></p>
            <p>Mob: <?= $customer_detail->phone ?></p>
            <p>State: <?= $customer_detail->state_name ?></p>
        </div>
        <div class="col-6 p-1">
            <h6 class="bordered-bottom text-center"><b>Details of Consignee / Shipped to:</b></h6>
            <p>Name: <?=$company_setting->company_name?></p>
            <p>Address: <?=$company_setting->address_line1?></p>
           
            <p>GSTIN: <?=$company_setting->gstin?></p>
            <p>Email: <?=$company_setting->email?></p>
            <p>Mob: <?=$company_setting->mobile?></p>
            <p>State: <?= $company_setting->state_name?></p>
        </div>
    </div>
    <hr class="bordered-bottom">
    
    <!-- Product Details Table -->
   <table class="table table-bordered no-padding full-width-table">
    <thead>
        <tr>
            <th>Name of the Product</th>
            <th>Batch No.</th>
            <th>HSN Code</th>
            <th>GST Rate</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Taxable</th>
            <th>IGST</th>
            <th>CGST</th>
            <th>SGST</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_igst = 0;
        $total_cgst = 0;
        $total_sgst = 0;
        $total_quantity = 0;
        $total_selling_price = 0;
        $total_discount_amount = 0;
        $total_taxable_value = 0;
        $total_tax = 0;
        $total_subtotal = 0;

        foreach ($sale_items as $row) {
            $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);
            
            $total_igst += $row->igst_tax;
            $total_cgst += $row->cgst_tax;
            $total_sgst += $row->sgst_tax;

            // Calculate totals
            $total_quantity += $row->quantity;
            $total_selling_price += $row->selling_price;
            $total_discount_amount += $row->discount_amount;
            $total_taxable_value += $row->taxable_value;
            $total_tax += $row->cgst_tax + $row->sgst_tax + $row->igst_tax;
            $total_subtotal += $row->sub_total;
        ?>
            <tr>
                <td><?= htmlspecialchars($row->product_name) ?></td>
                <td><?= htmlspecialchars($warehouse_product->batch_no) ?></td>
                <td><?= htmlspecialchars($row->hsn) ?></td>
                <td><?= htmlspecialchars($row->gst_rate) ?></td>
                <td><?= htmlspecialchars($row->quantity) ?></td>
                <td><?= number_format_i($row->selling_price) ?></td>
                <td><?= number_format_i($row->taxable_value - $row->discount_amount) ?></td>
                <td><?= htmlspecialchars($row->igst_tax) ?></td>
                <td><?= htmlspecialchars($row->cgst_tax) ?></td>
                <td><?= htmlspecialchars($row->sgst_tax) ?></td>
                <td><?= number_format_i($row->sub_total) ?></td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr style="font-size: 16px; font-weight: bolder;">
            <td colspan="4"><?= $this->lang->line('total_amount_due') ?></td>
            <td><?= $total_quantity ?></td>
            <td><?= number_format_i($total_selling_price) ?></td>
            <td><?= number_format_i($total_taxable_value - $total_discount_amount) ?></td>
            <td colspan="3" class="text-end"><?= number_format_i($total_tax) ?></td>
            <td><?= number_format_i($total_subtotal) ?></td>
        </tr>
        <tr style="font-size: 16px; font-weight: bolder;">
            <td colspan="10"><?= $this->lang->line('rounded_off') ?></td>
            <td>
                <?php
                $rounded_off = round($sale->total_taxable_value + $sale->total_tax) - ($sale->total_taxable_value + $sale->total_tax);
                echo number_format_i($rounded_off);
                ?>
            </td>
        </tr>
        <tr style="font-size: 16px; font-weight: bolder;">
            <td colspan="10"><?= $this->lang->line('sale_tds') ?></td>
            <td><?= number_format_i($sale->tds) ?></td>
        </tr>
        <tr style="font-size: 16px; font-weight: bolder;">
            <td colspan="10"><?= $this->lang->line('paid_amount') ?></td>
            <td><?= number_format_i($paid_amount) ?></td>
        </tr> 
        <tr style="font-size: 16px; font-weight: bolder;">
            <td colspan="10"><?= $this->lang->line('balance_receivable') ?></td>
            <td><?= number_format_i(round($sale->total_taxable_value + $sale->total_tax - $paid_amount)) ?></td>
        </tr>
        <tr style="font-size: 16px; font-weight: bold;">
            <td colspan="5"><?= $this->lang->line('amount_in_words') ?></td>
            <td colspan="6" style="text-align: right">
                <?php echo strtoupper($this->numbertowords->convert_number(round($sale->total_taxable_value + $sale->total_tax - $paid_amount))); ?>
            </td>
        </tr>
    </tfoot>
</table>

    <hr class="bordered-bottom">
    
    <!-- Payment and Bank Details -->
   <!-- Payment and Bank Details -->
<div class="row no-padding">
    <div class="col-4  p-2">
        <p>Rs: <?= number_format_i($total_subtotal) ?></p>
        <p> <?= $sale->bank_detail ?></p>
        <p>IFSC Code: <?= htmlspecialchars($payment->ifsc_code); ?></p>
    </div>
    
     <div class="col-4  p-2">
        <p>Paid Rs: <?= number_format_i($paid_amount) ?></p>
        <p>Current Balance: <?= htmlspecialchars($payment->current_balance); ?></p>
        <p>Total Dues: <?= number_format_i($total_subtotal) ?></p>
    </div>
    
   <div class="col-4 p-2">
        <p>Taxable Amount</p>
        <p>IGST: <?= number_format_i($total_igst) ?></p>
        <p>CGST: <?= number_format_i($total_cgst) ?></p>
        <p>SGST: <?= number_format_i($total_sgst) ?></p>
        <p><b>Total: <?= number_format_i($total_tax) ?></b></p>
    </div>
</div>
 <hr class="bordered-bottom">
    <!-- Tax Details Table -->
    <!--<table class="table table-bordered no-padding full-width-table">-->
    <!--    <thead>-->
    <!--        <tr>-->
    <!--            <th>Tax Details</th>-->
    <!--            <th>IGST</th>-->
    <!--            <th>CGST</th>-->
    <!--            <th>SGST</th>-->
    <!--            <th>Total GST</th>-->
    <!--        </tr>-->
    <!--    </thead>-->
    <!--    <tbody>-->
    <!--        <?php foreach ($tax_details as $tax): ?>-->
    <!--        <tr>-->
    <!--            <td><?= htmlspecialchars($tax->description); ?></td>-->
    <!--            <td><?= htmlspecialchars($tax->igst); ?></td>-->
    <!--            <td><?= htmlspecialchars($tax->cgst); ?></td>-->
    <!--            <td><?= htmlspecialchars($tax->sgst); ?></td>-->
    <!--            <td><?= htmlspecialchars($tax->total_gst); ?></td>-->
    <!--        </tr>-->
    <!--        <?php endforeach; ?>-->
    <!--    </tbody>-->
    <!--</table>-->

    <!-- Footer Section -->
    <div class="row no-padding">
        <div class="col-4">
            <p>Company Name & Seal: ________________</p>
        </div>
        <div class="col-4">
            <p>Terms And Conditions :<br><?= $company_setting->terms_and_condition ?></p>
        </div>
        <div class="col-4 text-end">

                            <p>Signature</p> <?php 
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

        </div>
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

<!-- Generate Ewaybill Modal -->
<div id="generate_ewaybill_modal" class="modal">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header" style="background-color: white;">
              <h5 class="modal-title">Generate Ewaybill</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
             <div id="calc-wrapper">
              <div id="calculator">

                <!-- <button type="button" data-type="1" style="font-size:24px; border-radius: 5px; width:45%;" class="btn btn-primary generate_ewaybill">Generate</button> -->
                <a href="<?php echo base_url("sale/ewaybill/".base64_encode($sale->id)); ?>" data-type="2" style="font-size:24px; border-radius: 5px; width:45%;" class="btn btn-primary">Download Json</a>
              </div>
            </div>
          </div>
      </div>
  </div>
</div>



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

      if (!(typeof sale_id === 'undefined')) {

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
      }


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
        deliveryData['warehouse_product_id']  = tr.find('input[name="warehouse_product_id"]').val();
        deliveryData['quantity']              = tr.find('input[name="quantity"]').val();
        deliveryData['cost']                  = tr.find('input[name="quantity"]').data('cost');
        deliveryData['price']                 = tr.find('input[name="quantity"]').data('price');
        deliveryData['selling_price']         = tr.find('input[name="quantity"]').data('selling_price');

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

        $('#addDeliveryDetailSubmit').text('Please wait while we are saving').addClass('disabled');

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
      else if(payment_mode == 3)
      {
        $('.transaction_mode_row').fadeOut(10);
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

  })
</script>