<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_expense')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('supplier')?>"><?=$this->lang->line('supplier_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('supplier_view')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header secondary-header">
              <h3 class="card-title"><?=$this->lang->line('supplier_view')?></h3>
              <!-- <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div> -->
            </div>
            <div class="card-body p-0">
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <td width="20%"><label><?=$this->lang->line('supplier_company_name')?></label></td>
                    <td width="5%"> : </td>
                    <td><?=$supplier->company_name ?></td>
                  </tr>
                  <tr>
                    <td><label><?=$this->lang->line('supplier_gst_registration_type')?></label></td>
                    <td> : </td>
                    <td>
                      <?php 
                        if($supplier->gst_registration_type == 0)
                        {
                          echo $this->lang->line('gst_reg_type_not_reg');
                        }
                        else if($supplier->gst_registration_type == 2)
                        {
                          echo $this->lang->line('gst_reg_type_composite'); 
                        }
                        else if($supplier->gst_registration_type == 1)
                        {
                          echo $this->lang->line('gst_reg_type_reg'); 
                        }
                      ?>  
                    </td>
                  </tr>
                  <tr>
                    <td><label><?=$this->lang->line('supplier_gstin')?></label></td>
                    <td> : </td>
                    <td><?=$supplier->gstin?></td>
                  </tr>
                  <tr>
                    <td><label><?=$this->lang->line('supplier_email')?></label></td>
                    <td> : </td>
                    <td><?=$supplier->email?></td>
                  </tr>
                  <tr>
                    <td><label><?=$this->lang->line('supplier_phone')?></label></td>
                    <td> : </td>
                    <td><?=$supplier->phone?></td>
                  </tr>
                  <tr>
                    <td><label><?=$this->lang->line('supplier_address')?></label></td>
                    <td> : </td>
                    <td><?=$supplier->address?></td>
                  </tr>
                  <tr>
                    <td><label><?=$this->lang->line('supplier_country_id')?></label></td>
                    <td> : </td>
                    <td><?=$supplier->country_name?></td>
                  </tr>
                  <tr>
                    <td><label><?=$this->lang->line('supplier_state_id')?></label></td>
                    <td> : </td>
                    <td><?=$supplier->state_name ?></td>
                  </tr>
                  <tr>
                    <td><label><?=$this->lang->line('supplier_city_id')?></label></td>
                    <td> : </td>
                    <td><?=$supplier->city_name ?></td>
                  </tr>
                  <tr>
                    <td><label><?=$this->lang->line('supplier_website')?></label></td>
                    <td> : </td>
                    <td><?=$supplier->website ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="pt-2 px-3"><h3 class="card-title">View</h3></li>
                <li class="nav-item">
                  <a class="nav-link active" id="custom-tabs-one-purchases-tab" data-toggle="pill" href="#custom-tabs-one-purchases" role="tab" aria-controls="custom-tabs-one-purchases" aria-selected="true"><?=$this->lang->line('purchase_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-purchase-order-tab" data-toggle="pill" href="#custom-tabs-one-purchase-order" role="tab" aria-controls="custom-tabs-one-purchase-order" aria-selected="true"><?=$this->lang->line('purchase_order_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-purchase-return-tab" data-toggle="pill" href="#custom-tabs-one-purchase-return" role="tab" aria-controls="custom-tabs-one-purchase-return" aria-selected="true"><?=$this->lang->line('purchase_return_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-expenses-tab" data-toggle="pill" href="#custom-tabs-one-expenses" role="tab" aria-controls="custom-tabs-one-expenses" aria-selected="false"><?=$this->lang->line('expense_list')?></a>
                </li>
              </ul>
            </div>
            <div class="card-body  m-0 p-0">
              <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-purchases" role="tabpanel" aria-labelledby="custom-tabs-one-purchases-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('purchase_reference_no')?></th>
                        <th><?=$this->lang->line('purchase_invoice_no')?></th>
                        <th><?=$this->lang->line('purchase_date')?></th>
                        <th><?=$this->lang->line('purchase_warehouse')?></th>
                        <th><?=$this->lang->line('purchase_supplier')?></th>
                        <th><?=$this->lang->line('purchase_total_discount').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('purchase_total_taxable_value').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('purchase_igst_tax')?></th>
                        <th><?=$this->lang->line('purchase_cgst_tax')?></th>
                        <th><?=$this->lang->line('purchase_sgst_tax')?></th>
                        <th><?=$this->lang->line('purchase_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
                      </tr>
                    </thead>
                    <!--<tbody>
                      <?php

                        $total_taxable_value  = 0.0;
                        $total_discount       = 0.0;
                        $total_tax            = 0.0;
                        $total                = 0.0;
                        $total_igst           = 0.0;
                        $total_cgst           = 0.0;
                        $total_sgst           = 0.0;
                        

                        if(sizeof($purchases) > 0)
                        {
                          foreach ($purchases as $value) 
                          {
                            $total_taxable_value  += $value->total_taxable_value;
                            $total_discount       += $value->total_discount;
                            $total_tax            += $value->total_tax;
                            $total                += $value->total;

                            $purchase = $this->purchase_model->get_purchase_tax_individual($value->id);

                            if($purchase !== null) 
                            {
                              $total_igst           += $purchase->igst_tax;
                              $total_cgst           += $purchase->cgst_tax;
                              $total_sgst           += $purchase->sgst_tax;
                            }

                            
                      ?>
                      <tr>                        
                        <td><a href="<?=base_url('purchase/view/'.base64_encode($value->id))?>" target="_blank"><?=$value->reference_no?></a></td>
                        <td><?=$value->invoice_no?></td>
                        <td><?=date('d-m-Y', strtotime($value->purchase_date));?></td>
                        <td><?=$value->name;?></td>
                        <td><?=$value->company_name;?></td>
                        <td><?=number_format_i($value->total_discount)?></td>
                        <td><?=number_format_i($value->total_taxable_value)?></td>
                        <?php if ($purchase !== null){ ?>
                          <td><?=number_format_i($purchase->igst_tax)?></td>
                          <td><?=number_format_i($purchase->cgst_tax)?></td>
                          <td><?=number_format_i($purchase->sgst_tax)?></td>
                        <?php } else {?>
                          <td>0.00</td>
                          <td>0.00</td>
                          <td>0.00</td>
                        <?php } ?>
                        <td><?=number_format_i($value->total)?></td>
                      </tr>
                      <?php  
                          }
                        }
                        else
                        {
                      ?>
                      <tr>
                        <td colspan="11"><?=$this->lang->line('no_records_available')?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    <tfoot class="bg-gray disabled footer_data">
                      <tr>
                        <th colspan="5"></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_discount)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_taxable_value)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_igst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_cgst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_sgst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total)?></th>
                      </tr>
                    </tfoot>-->


                    <tbody>
    <?php
    $total_taxable_value = 0.0;
    $total_discount = 0.0;
    $total_tax = 0.0;
    $total = 0.0;
    $total_igst = 0.0;
    $total_cgst = 0.0;
    $total_sgst = 0.0;

    if(sizeof($purchases) > 0)
    {
        foreach ($purchases as $value) 
        {
            $total_taxable_value  += $value->taxable_value;
            $total_discount       += $value->total_discount;
            $total                += $value->grand_total;

            // Get tax details - you may need to adjust this based on your model
            $purchase = $this->purchase_model->get_purchase_tax_individual($value->id);
            
            if($purchase !== null) 
            {
                $total_igst += $purchase->igst_tax;
                $total_cgst += $purchase->cgst_tax;
                $total_sgst += $purchase->sgst_tax;
            }
    ?>
    <tr>                        
        <td><a href="<?=base_url('purchase/view/'.base64_encode($value->id))?>" target="_blank"><?=$value->purchase_no?></a></td>
        <td><?=$value->invoice_no?></td>
        <td><?=date('d-m-Y', strtotime($value->purchase_date));?></td>
        <td><?=$value->warehouse_name;?></td>
        <td><?=$value->company_name;?></td>
        <td><?=number_format_i($value->total_discount)?></td>
        <td><?=number_format_i($value->taxable_value)?></td>
        <?php if ($purchase !== null){ ?>
            <td><?=number_format_i($purchase->igst_tax)?></td>
            <td><?=number_format_i($purchase->cgst_tax)?></td>
            <td><?=number_format_i($purchase->sgst_tax)?></td>
        <?php } else { ?>
            <td>0.00</td>
            <td>0.00</td>
            <td>0.00</td>
        <?php } ?>
        <td><?=number_format_i($value->grand_total)?></td>
    </tr>
    <?php  
        }
    }
    else
    {
    ?>
    <tr>
        <td colspan="11"><?=$this->lang->line('no_records_available')?></td>
    </tr>
    <?php
    }
    ?>
</tbody>
<tfoot class="bg-gray disabled footer_data">
    <tr>
        <th colspan="5"></th>
        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_discount)?></th>
        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_taxable_value)?></th>
        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_igst)?></th>
        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_cgst)?></th>
        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_sgst)?></th>
        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total)?></th>
    </tr>
</tfoot>
                  </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-purchase-order" role="tabpanel" aria-labelledby="custom-tabs-one-purchase-order-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('purchase_order_reference_no')?></th>
                      
                        <th><?=$this->lang->line('purchase_order_date')?></th>
                        <th><?=$this->lang->line('purchase_order_warehouse')?></th>
                        <th><?=$this->lang->line('purchase_order_supplier')?></th>
                        <th><?=$this->lang->line('purchase_order_total_discount').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('purchase_order_total_taxable_value').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('purchase_order_igst_tax')?></th>
                        <th><?=$this->lang->line('purchase_order_cgst_tax')?></th>
                        <th><?=$this->lang->line('purchase_order_sgst_tax')?></th>
                        <th><?=$this->lang->line('purchase_order_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                        $total_taxable_value  = 0.0;
                        $total_discount       = 0.0;
                        $total_tax            = 0.0;
                        $total                = 0.0;
                        $total_igst           = 0.0;
                        $total_cgst           = 0.0;
                        $total_sgst           = 0.0;
                        

                        if(sizeof($purchase_orders) > 0)
                        {
                          foreach ($purchase_orders as $value) 
                          {
                            $total_taxable_value  += $value->total_taxable_value;
                            $total_discount       += $value->total_discount;
                            $total_tax            += $value->total_tax;
                            $total                += $value->total;

                            $purchase_order = $this->purchase_order_model->get_purchase_order_tax_individual($value->id);

                            if($purchase_order !== null) 
                            {
                              $total_igst           += $purchase_order->igst_tax;
                              $total_cgst           += $purchase_order->cgst_tax;
                              $total_sgst           += $purchase_order->sgst_tax;
                            }

                            
                      ?>
                      <tr>                        
                        <td><a href="<?=base_url('purchase_order/view/'.base64_encode($value->id))?>" target="_blank"><?=$value->reference_no?></a></td>
                       
                        <td><?=date('d-m-Y', strtotime($value->purchase_order_date));?></td>
                        <td><?=$value->name;?></td>
                        <td><?=$value->company_name;?></td>
                        <td><?=number_format_i($value->total_discount)?></td>
                        <td><?=number_format_i($value->total_taxable_value)?></td>
                        <?php if ($purchase_order !== null){ ?>
                          <td><?=number_format_i($purchase_order->igst_tax)?></td>
                          <td><?=number_format_i($purchase_order->cgst_tax)?></td>
                          <td><?=number_format_i($purchase_order->sgst_tax)?></td>
                        <?php } else {?>
                          <td>0.00</td>
                          <td>0.00</td>
                          <td>0.00</td>
                        <?php } ?>
                        <td><?=number_format_i($value->total)?></td>
                      </tr>
                      <?php  
                          }
                        }
                        else
                        {
                      ?>
                      <tr>
                        <td colspan="10"><?=$this->lang->line('no_records_available')?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    <tfoot class="bg-gray disabled footer_data">
                      <tr>
                        <th colspan="4"></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_discount)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_taxable_value)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_igst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_cgst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_sgst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total)?></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-purchase-return" role="tabpanel" aria-labelledby="custom-tabs-one-purchase-return-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('purchase_return_reference_no')?></th>
                        <th><?=$this->lang->line('purchase_return_invoice_no')?></th>
                        <th><?=$this->lang->line('purchase_return_date')?></th>
                        <th><?=$this->lang->line('purchase_return_warehouse')?></th>
                        <th><?=$this->lang->line('purchase_return_supplier')?></th>
                        <th><?=$this->lang->line('purchase_return_total_discount').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('purchase_return_total_taxable_value').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('purchase_return_igst_tax')?></th>
                        <th><?=$this->lang->line('purchase_return_cgst_tax')?></th>
                        <th><?=$this->lang->line('purchase_return_sgst_tax')?></th>
                        <th><?=$this->lang->line('purchase_return_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                        $total_taxable_value  = 0.0;
                        $total_discount       = 0.0;
                        $total_tax            = 0.0;
                        $total                = 0.0;
                        $total_igst           = 0.0;
                        $total_cgst           = 0.0;
                        $total_sgst           = 0.0;
                        

                        if(sizeof($purchase_returns) > 0)
                        {
                          foreach ($purchase_returns as $value) 
                          {
                            $total_taxable_value  += $value->total_taxable_value;
                            $total_discount       += $value->total_discount;
                            $total_tax            += $value->total_tax;
                            $total                += $value->total;

                            $purchase_return = $this->purchase_return_model->get_purchase_return_tax_individual($value->id);

                            if($purchase_return !== null) 
                            {
                              $total_igst           += $purchase_return->igst_tax;
                              $total_cgst           += $purchase_return->cgst_tax;
                              $total_sgst           += $purchase_return->sgst_tax;
                            }

                            
                      ?>
                      <tr>                        
                        <td><a href="<?=base_url('purchase_return/view/'.base64_encode($value->id))?>" target="_blank"><?=$value->reference_no?></a></td>
                        <td><?=$value->invoice_no?></td>
                        <td><?=date('d-m-Y', strtotime($value->purchase_return_date));?></td>
                        <td><?=$value->name;?></td>
                        <td><?=$value->company_name;?></td>
                        <td><?=number_format_i($value->total_discount)?></td>
                        <td><?=number_format_i($value->total_taxable_value)?></td>
                        <?php if ($purchase_return !== null){ ?>
                          <td><?=number_format_i($purchase_return->igst_tax)?></td>
                          <td><?=number_format_i($purchase_return->cgst_tax)?></td>
                          <td><?=number_format_i($purchase_return->sgst_tax)?></td>
                        <?php } else {?>
                          <td>0.00</td>
                          <td>0.00</td>
                          <td>0.00</td>
                        <?php } ?>
                        <td><?=number_format_i($value->total)?></td>
                      </tr>
                      <?php  
                          }
                        }
                        else
                        {
                      ?>
                      <tr>
                        <td colspan="11"><?=$this->lang->line('no_records_available')?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    <tfoot class="bg-gray disabled footer_data">
                      <tr>
                        <th colspan="5"></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_discount)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_taxable_value)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_igst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_cgst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_sgst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total)?></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-expenses" role="tabpanel" aria-labelledby="custom-tabs-one-expenses-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('expense_date')?></th>
                        <th><?=$this->lang->line('expense_expense_category')?></th>
                        <th><?=$this->lang->line('expense_supplier')?></th>
                        <th><?=$this->lang->line('expense_document')?></th>
                        <th><?=$this->lang->line('expense_remarks')?></th>
                        <!-- <th><?=$this->lang->line('expense_amount')?></th>
                        <th><?=$this->lang->line('expense_cgst')?></th>
                        <th><?=$this->lang->line('expense_sgst')?></th>
                        <th><?=$this->lang->line('expense_igst')?></th> -->
                        <th><?=$this->lang->line('expense_total_amount').'('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('expense_paid_amount').'('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('expense_due_amount').'('.$this->session->userdata('currency_symbol').')'?></th>
                     
                      </tr>
                    </thead>
                    
                    <tbody>
                      <?php 
                        $total_amount   = 0;
                        $total_cgst     = 0;
                        $total_sgst     = 0;
                        $total_igst     = 0;
                        $total_t_amount = 0;
                        $total_paid_amount = 0;

                        if(sizeof($expenses) > 0)
                        {
                          foreach ($expenses as $value) 
                          {
                            $total_amount   += $value->amount;
                            $total_cgst     += $value->cgst_tax;
                            $total_sgst     += $value->sgst_tax;
                            $total_igst     += $value->igst_tax;
                            $total_t_amount += $value->total_amount


                      ?>
                      <tr>                        
                        
                        <td><?php echo date('d-m-Y', strtotime($value->date));?></td>
                        <td><?php echo $value->expense_category_name;?></td>
                        <td><?php echo $value->company_name;?></td>
                        
                        <td>
                          <div id="document_list">
                            <?php 
                              

                              if($value->document != '' || $value->document != null)
                              {
                                $document_array = explode(",", $value->document);
                                for ($i=0; $i < sizeof($document_array); $i++) 
                                {
                                  $file_name_without_ext  = explode(".", $document_array[$i])[0];
                                  $file_ext         = explode(".", $document_array[$i])[1];

                                  $actual_file_name     = substr($file_name_without_ext, 0, -14).'.'.$file_ext;
                            ?>
                            <div class="btn-group" style="padding-bottom: 10px;">
                              <a target="_blank" class="btn btn-warning btn-xs" id="<?=$document_array[$i]?>" href="<?=base_url('attachment/download_attachment/'.$document_array[$i])?>" data-tt="tooltip" title="<?php echo $actual_file_name?>">
                                <i class="fa fa-paperclip"></i>
                              </a>
                            </div>
                            <?php 
                                }
                              }
                            ?>
                          </div>
                        </td>
                        <td>
                          <?=$value->remarks?>
                        </td>
                        <!-- <td><?php echo $value->amount;?></td>
                        <td><?php echo $value->cgst;?></td>
                        <td><?php echo $value->sgst;?></td>
                        <td><?php echo $value->igst;?></td> -->
                        <td><?php echo number_format_i($value->total_amount);?></td>
                        <td>
                          <?php
                            $paid_amount = $this->transaction_model->get_total_transaction_amount($value->id,EXPENSE_MODULE,PAYMENT_TRANSACTION_TYPE);
                            $total_paid_amount += $paid_amount;
                            echo number_format_i($paid_amount);
                          ?>
                        </td>
                        <td><?php echo number_format_i($value->total_amount-$paid_amount);?></td>
                        
                        
                      </tr>
                      <?php  
                          }
                        }
                        else
                        {
                      ?>
                      <tr>
                        <td colspan="11"><?php echo $this->lang->line('no_records_available');?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    <tfoot>
                      <tr class="bg-gray disabled footer_data">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <!-- <th><?=$this->session->userdata('currency_symbol').' '.$total_amount?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.$total_cgst?>s</th>
                        <th><?=$this->session->userdata('currency_symbol').' '.$total_sgst?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.$total_igst?></th> -->
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_t_amount)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_paid_amount)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i(($total_t_amount-$total_paid_amount))?></th>
                        
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php $this->load->view('layout/footer');?>