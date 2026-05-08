<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_people')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('customer_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('customer_view')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('customer_view')?></h3>
             
              <!-- <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-tt="tooltip" title="<?=$this->lang->line('hide_details')?>">
                  <i class="fas fa-minus"></i>
                </button>
              </div> -->
            </div>
                <!-- Replace the entire table section with this code -->
                <div class="card-body p-0">
                  <div class="p-3">
                    <!-- Personal Details Section -->
                    <div class="card card-info mb-3">
                      <div class="card-header">
                        <h3 class="card-title">Personal Details</h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label><?=$this->lang->line('customer_name')?></label>
                              <p><?=$customer->customer_name ?></p>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label><?=$this->lang->line('customer_gstin')?></label>
                              <p><?=$customer->gstin ?></p>
                            </div>
                            
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label><?=$this->lang->line('customer_email')?></label>
                              <p><?=$customer->email ?></p>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label><?=$this->lang->line('customer_phone')?></label>
                              <p><?=$customer->phone ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                
                    <!-- Billing Details Section -->
                    <div class="card card-info mb-3">
                      <div class="card-header">
                        <h3 class="card-title">Billing Details</h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label><?=$this->lang->line('customer_country_id')?></label>
                              <p><?=$customer->country_name ?></p>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label><?=$this->lang->line('customer_state_id')?></label>
                              <p><?=$customer->state_name ?></p>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label><?=$this->lang->line('customer_city_id')?></label>
                              <p><?=$customer->city_name ?></p>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-8">
                            <div class="form-group">
                              <label><?=$this->lang->line('customer_address')?></label>
                              <p><?=$customer->address ?></p>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label><?=$this->lang->line('customer_pincode')?></label>
                              <p><?=$customer->pincode ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                
                    <!-- Shipping Details Section -->
                    <div class="card card-info">
                      <div class="card-header">
                        <h3 class="card-title">Shipping Details</h3>
                      </div>
                      <div class="card-body">
                        <?php if (!empty($shipping_addresses)): ?>
                          <div class="row">
                            <?php foreach ($shipping_addresses as $index => $address): ?>
                              <div class="col-md-6 mb-3">
                                <div class="shipping-address-block p-3 border rounded h-100">
                                  <?php if ($address->is_default): ?>
                                    <span class="badge badge-success float-right">Default</span>
                                  <?php endif; ?>
                                  
                                  <h5><strong><?=!empty($address->shipping_name) ? $address->shipping_name : 'Shipping Address #'.($index+1)?></strong></h5>
                                  
                                  <div class="address-details">
                                    <div class="form-group">
                                      <label>Country</label>
                                      <p><?=$address->country_name ?></p>
                                    </div>
                                    <div class="form-group">
                                      <label>State</label>
                                      <p><?=$address->state_name ?></p>
                                    </div>
                                    <div class="form-group">
                                      <label>City</label>
                                      <p><?=$address->city_name ?></p>
                                    </div>
                                    <div class="form-group">
                                      <label>Address</label>
                                      <p><?=$address->shipping_address ?></p>
                                    </div>
                                    <div class="form-group">
                                      <label>Pincode</label>
                                      <p><?=$address->shipping_pincode ?></p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php endforeach; ?>
                          </div>
                        <?php else: ?>
                          <div class="alert alert-info">No shipping addresses found</div>
                        <?php endif; ?>
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

   

      <div class="row">
        <div class="col-12">
          <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="pt-2 px-3"><h3 class="card-title">View</h3></li>
                <li class="nav-item">
                  <a class="nav-link active" id="custom-tabs-one-sale-tab" data-toggle="pill" href="#custom-tabs-one-sale" role="tab" aria-controls="custom-tabs-one-sale" aria-selected="true"><?=$this->lang->line('sale_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-sale-return-tab" data-toggle="pill" href="#custom-tabs-one-sale-return" role="tab" aria-controls="custom-tabs-one-sale-return" aria-selected="true"><?=$this->lang->line('sales_return_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-quotation-tab" data-toggle="pill" href="#custom-tabs-one-quotation" role="tab" aria-controls="custom-tabs-one-quotation" aria-selected="true"><?=$this->lang->line('quotation_list')?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-proforma-invoice-tab" data-toggle="pill" href="#custom-tabs-one-proforma-invoice" role="tab" aria-controls="custom-tabs-one-proforma-invoice" aria-selected="false"><?=$this->lang->line('proforma_invoice_list')?></a>
                </li>
              </ul>
            </div>
            <div class="card-body  m-0 p-0">
              <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-sale" role="tabpanel" aria-labelledby="custom-tabs-one-sale-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('sale_reference_no')?></th>
                        <th><?=$this->lang->line('sale_invoice_date')?></th>
                        
                       
                        <th><?=$this->lang->line('sale_customer')?></th>
                        <th><?=$this->lang->line('sale_total_discount').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('sale_total_taxable_value').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('sale_tds').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('purchase_igst_tax')?></th>
                        <th><?=$this->lang->line('purchase_cgst_tax')?></th>
                        <th><?=$this->lang->line('purchase_sgst_tax')?></th>
                        <th><?=$this->lang->line('sale_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
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
                        $total_tds            = 0;
                        

                        if(sizeof($sales) > 0)
                        {
                          foreach ($sales as $value) 
                          {
                            $total_taxable_value  += $value->total_taxable_value;
                            $total_discount       += $value->total_discount;
                            $total_tax            += $value->total_tax;
                            $total                += $value->total;
                            $total_tds            += $value->tds;

                            $sale = $this->sale_model->get_sale_tax_individual($value->id);

                            if($sale !== null) 
                            {
                              $total_igst           += $sale->igst_tax;
                              $total_cgst           += $sale->cgst_tax;
                              $total_sgst           += $sale->sgst_tax;
                            }

                            
                      ?>
                      <tr>                        
                        <td><a href="<?=base_url('sale/view/'.base64_encode($value->id))?>" target="_blank"><?=$value->reference_no?></a></td>
                        <td><?=date('d-m-Y', strtotime($value->invoice_date));?></td>
                        <td><?=$value->customer_name;?></td>
                        <td><?=number_format_i($value->total_discount)?></td>
                        <td><?=number_format_i($value->total_taxable_value)?></td>
                        <td><?=number_format_i($value->tds)?></td>
                        <?php if ($sale !== null){ ?>
                          <td><?=number_format_i($sale->igst_tax)?></td>
                          <td><?=number_format_i($sale->cgst_tax)?></td>
                          <td><?=number_format_i($sale->sgst_tax)?></td>
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
                        <th colspan="3"></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_discount)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_taxable_value)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_tds)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_igst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_cgst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_sgst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total)?></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-sale-return" role="tabpanel" aria-labelledby="custom-tabs-one-sale-return-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('sales_return_reference_no')?></th>
                        <th><?=$this->lang->line('sales_return_invoice_no')?></th>
                        <th><?=$this->lang->line('sales_return_date')?></th>
                        <th><?=$this->lang->line('sales_return_warehouse')?></th>
                        <th><?=$this->lang->line('sale_customer')?></th>
                        <th><?=$this->lang->line('sales_return_total_discount').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('sales_return_total_taxable_value').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('sales_return_igst_tax')?></th>
                        <th><?=$this->lang->line('sales_return_cgst_tax')?></th>
                        <th><?=$this->lang->line('sales_return_sgst_tax')?></th>
                        <th><?=$this->lang->line('sales_return_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
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
                        

                        if(sizeof($sales_return) > 0)
                        {
                          foreach ($sales_return as $value) 
                          {
                            $total_taxable_value  += $value->total_taxable_value;
                            $total_discount       += $value->total_discount;
                            $total_tax            += $value->total_tax;
                            $total                += $value->total;

                            $sales_return = $this->sales_return_model->get_sales_return_tax_individual($value->id);

                            if($sales_return !== null) 
                            {
                              $total_igst           += $sales_return->igst_tax;
                              $total_cgst           += $sales_return->cgst_tax;
                              $total_sgst           += $sales_return->sgst_tax;
                            }

                            
                      ?>
                      <tr>                        
                        <td><a href="<?=base_url('sales_return/view/'.base64_encode($value->id))?>" target="_blank"><?=$value->reference_no?></a></td>
                        <td><?=$value->invoice_no?></td>
                        <td><?=date('d-m-Y', strtotime($value->sales_return_date));?></td>
                        <td><?=$value->name;?></td>
                        <td><?=$value->customer_name;?></td>
                        <td><?=number_format_i($value->total_discount)?></td>
                        <td><?=number_format_i($value->total_taxable_value)?></td>
                        <?php if ($sales_return !== null){ ?>
                          <td><?=number_format_i($sales_return->igst_tax)?></td>
                          <td><?=number_format_i($sales_return->cgst_tax)?></td>
                          <td><?=number_format_i($sales_return->sgst_tax)?></td>
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

                <div class="tab-pane fade" id="custom-tabs-one-quotation" role="tabpanel" aria-labelledby="custom-tabs-one-quotation-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('quotation_reference_no')?></th>
                        <th><?=$this->lang->line('quotation_date')?></th>
                        <th><?=$this->lang->line('sale_customer')?></th>
                        <th><?=$this->lang->line('quotation_total_discount').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('quotation_total_taxable_value').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('sales_return_igst_tax')?></th>
                        <th><?=$this->lang->line('sales_return_cgst_tax')?></th>
                        <th><?=$this->lang->line('sales_return_sgst_tax')?></th>
                        <th><?=$this->lang->line('quotation_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
                       
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
                        

                        if(sizeof($quotations) > 0)
                        {
                          foreach ($quotations as $value) 
                          {
                            $total_taxable_value  += $value->total_taxable_value;
                            $total_discount       += $value->total_discount;
                            $total_tax            += $value->total_tax;
                            $total                += $value->total;

                            $quotation = $this->quotation_model->get_quotation_tax_individual($value->id);

                            if($quotation !== null) 
                            {
                              $total_igst           += $quotation->igst_tax;
                              $total_cgst           += $quotation->cgst_tax;
                              $total_sgst           += $quotation->sgst_tax;
                            }

                            
                      ?>
                      <tr>                        
                        <td><a href="<?=base_url('quotation/view/'.base64_encode($value->id))?>" target="_blank"><?=$value->reference_no?></a></td>
                      
                        <td><?=date('d-m-Y', strtotime($value->quotation_date));?></td>
                        
                        <td><?=$value->customer_name;?></td>
                        <td><?=number_format_i($value->total_discount)?></td>
                        <td><?=number_format_i($value->total_taxable_value)?></td>
                        <?php if ($quotation !== null){ ?>
                          <td><?=number_format_i($quotation->igst_tax)?></td>
                          <td><?=number_format_i($quotation->cgst_tax)?></td>
                          <td><?=number_format_i($quotation->sgst_tax)?></td>
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
                        <td colspan="19"><?=$this->lang->line('no_records_available')?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                    <tfoot class="bg-gray disabled footer_data">
                      <tr>
                        <th colspan="3"></th>
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

                <div class="tab-pane fade" id="custom-tabs-one-proforma-invoice" role="tabpanel" aria-labelledby="custom-tabs-one-proforma-invoice-tab">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?=$this->lang->line('proforma_invoice_reference_no')?></th>
                        <th><?=$this->lang->line('proforma_invoice_invoice_date')?></th>
                        
                       
                        <th><?=$this->lang->line('proforma_invoice_customer')?></th>
                        <th><?=$this->lang->line('proforma_invoice_total_discount').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('proforma_invoice_total_taxable_value').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('proforma_invoice_tds').' ('.$this->session->userdata('currency_symbol').')'?></th>
                        <th><?=$this->lang->line('purchase_igst_tax')?></th>
                        <th><?=$this->lang->line('purchase_cgst_tax')?></th>
                        <th><?=$this->lang->line('purchase_sgst_tax')?></th>
                        <th><?=$this->lang->line('proforma_invoice_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
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
                        $total_tds            = 0;
                        

                        if(sizeof($proforma_invoices) > 0)
                        {
                          foreach ($proforma_invoices as $value) 
                          {
                            $total_taxable_value  += $value->total_taxable_value;
                            $total_discount       += $value->total_discount;
                            $total_tax            += $value->total_tax;
                            $total                += $value->total;
                            $total_tds            += $value->tds;

                            $proforma_invoice = $this->proforma_invoice_model->get_proforma_invoice_tax_individual($value->id);

                            if($proforma_invoice !== null) 
                            {
                              $total_igst           += $proforma_invoice->igst_tax;
                              $total_cgst           += $proforma_invoice->cgst_tax;
                              $total_sgst           += $proforma_invoice->sgst_tax;
                            }

                            
                      ?>
                      <tr>                        
                        <td><a href="<?=base_url('proforma_invoice/view/'.base64_encode($value->id))?>" target="_blank"><?=$value->reference_no?></a></td>
                      
                        <td><?=date('d-m-Y', strtotime($value->invoice_date));?></td>
                      
                        <td><?=$value->customer_name;?></td>
                        <td><?=number_format_i($value->total_discount)?></td>
                        <td><?=number_format_i($value->total_taxable_value)?></td>
                        <td><?=number_format_i($value->tds)?></td>
                        <?php if ($proforma_invoice !== null){ ?>
                          <td><?=number_format_i($proforma_invoice->igst_tax)?></td>
                          <td><?=number_format_i($proforma_invoice->cgst_tax)?></td>
                          <td><?=number_format_i($proforma_invoice->sgst_tax)?></td>
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
                        <th colspan="3"></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_discount)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_taxable_value)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_tds)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_igst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_cgst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_sgst)?></th>
                        <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total)?></th>
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

<div class="example-modal">
  <div class="modal fade" id="create_login_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<?php $this->load->view('layout/footer');?>

<script>
  $(document).ready(function(e){

    const customerToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    $(document).on('click','.create_login_modal',function(e){
      var id = $(this).data('id');
      //alert(id);
      $.ajax({
        url: "<?php echo base_url('customer/create_login')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#create_login_modal').find('.modal-content').html(data.create_login_modal_body);
          $('#create_login_modal').modal('show');
          // reinitialise();   
          $('.datepicker').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
          });
        }
      });
    });

    $(document).on('hidden.bs.modal','#create_login_modal',function(e){
      $('#create_login_modal').find('.modal-content').html('');
    });

    $(document).on('submit','#customerDetailForm',function(e){
      
      e.preventDefault();

      $('#customerDetailSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#customerDetailForm').serialize();

      // alert(formData);

      var isError = false;

      $('form#customerDetailForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value=="" || value == 0){
            $("form#customerDetailForm  #err_"+id).text(field+ " field is required.");
            $('form#customerDetailForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#customerDetailForm #err_"+id).text("");
            $('form#customerDetailForm #'+id).removeClass('is-invalid');
            $('form#customerDetailForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#customerDetailSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('customer/create_login')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if (response.code == 0) {
                // Handle validation errors
                $.each(response.errors, function(key, value) {
                  $('#'+key).addClass('is-invalid');
                  $("#err_" + key ).text(value); // Display errors beside respective fields
                });
                $('#customerDetailSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
            else if(response.code==1)
            { 
              $('#create_login_modal').modal('hide');
              $('form#customerDetailForm #customerDetailSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              //initialize_datatable();

              customerToast.fire({
                type: 'success',
                title: response.message
              });

              setTimeout(function() {
                location.reload(); // For example, refresh the page after 2 seconds
            }, 1000);
            }
            else
            {
              customerToast.fire({
                type: 'error',
                title: response.message
              });
              $('#customerDetailSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#customerDetailForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#customerDetailForm #err_"+id).text(field+ " field is required.");
          $('form#customerDetailForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#customerDetailForm #err_"+id).text("");
          $('form#customerDetailForm #'+id).removeClass('is-invalid');
          $('form#customerDetailForm #'+id).addClass('is-valid');
        }
    });

    $(document).on('click','.disable_login_modal',function(e){
      var user_id = $(this).data('user_id');
      //alert(id);
      $.ajax({
        url: "<?php echo base_url('customer/disable_login')?>",
        type: "POST",
        data: {
          'user_id':user_id,
          
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(response){
          if(response.code == 1)
          { 
            Swal.fire({
                title: 'SUCCESS !!',
                text: 'User account is disable successfully.',
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });

            setTimeout(function() {
                location.reload(); // For example, refresh the page after 2 seconds
            }, 1000);
          }

          else if(response.code == 0)
          { 
            Swal.fire({
                title: 'FAILURE !!',
                text: 'User account failed to disable.',
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
          }
        }
      });
    });

    $(document).on('click','.enable_login_modal',function(e){
      var user_id = $(this).data('user_id');
      //alert(id);
      $.ajax({
        url: "<?php echo base_url('customer/enable_login')?>",
        type: "POST",
        data: {
          'user_id':user_id,
          
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(response){
          if(response.code == 1)
          { 
            Swal.fire({
                title: 'SUCCESS !!',
                text: response.message,
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });

            setTimeout(function() {
                location.reload(); // For example, refresh the page after 2 seconds
            }, 1000);
          }

          else if(response.code == 0)
          { 
            Swal.fire({
                title: 'FAILURE !!',
                text: response.message,
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
          }
        }
      });
    });
    
    $(document).on('click','.resend_password_modal',function(e){
      var user_id = $(this).data('user_id');
      //alert(user_id);
      $.ajax({
        url: "<?php echo base_url('customer/resend_password')?>",
        type: "POST",
        data: {
          'user_id':user_id,
          
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(response){
          if(response.code == 1)
          { 
            Swal.fire({
                title: 'SUCCESS !!',
                text: 'Updated password sent to customer email address.',
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
          }
          
          else if(response.code == 0)
          { 
            Swal.fire({
                title: 'FAILURE !!',
                text: 'Failed to update password.',
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
          }
        }
      });
    });

  });


</script>