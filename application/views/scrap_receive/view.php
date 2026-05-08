<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><?=$this->lang->line('header_account')?></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_scrap_receive')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('scrap_receive_view')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('scrap_receive_view')?> </h3>
              
              <div class="card-tools">

                <?php 
                  if($this->permission_model->has_permission('pdf_scrap_receive'))
                  {
                ?>
                    <!-- <a href="#" data-url="<?=base_url('utility/download_scrap_receive/'.base64_encode($scrap_receive->id))?>" style="cursor: pointer" class="btn bg-secondary btn-sm copy-to-clickboard" data-tt="tooltip" title="Copy Pack slip link to Clipboard"> Copy
                      <i class="fas fa-regular fa-paste"></i>
                    </a> -->

                    <a href="<?=base_url('scrap_receive/pdf/'.base64_encode($scrap_receive->id).'/landscape')?>" class="btn bg-orange btn-sm" data-tt="tooltip" title="Download Scrap issue">
                      <i class="far fa-file-pdf"></i> Download Scrap Receive
                    </a>  
                
                <?php 
                  }
                ?>
                

                <?php 
                  if($this->permission_model->has_permission('edit_scrap_receive'))
                  {
                    
                ?>
                      <a href="<?=base_url('scrap_receive/edit/'.base64_encode($scrap_receive->id))?>" class="btn btn-info btn-sm"  data-tt="tooltip" title="Edit Pack slip">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                <?php 
                   
                  }
                ?>
              </div>
            </div>
            <div class="card-body">
              <div class="invoice p-3 mb-3">
                <div class="row">
                  <div class="col-12 text-center">
                     <br/><?=$this->lang->line('header_scrap_receive')?>(<?=$scrap_receive->reference_no?>)
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <h4>
                    <?php 
                        $image_data = '';
                        if ($company_setting->logo != '') {
                          $image_path = './assets/images/' . $company_setting->logo; // Adjust the path accordingly
                          if (file_exists($image_path)) {
                            $image_data = file_get_contents($image_path);
                            $base64_data = base64_encode($image_data);
                      ?>

                      
                      <img src="data:image/png;base64,<?=$base64_data?>" style="width: 50px;">
                      

                      <?php 
                          }
                        }
                      ?> <?=$company_setting->company_name?>
                      <small class="float-right"> <?=$this->lang->line('date')?>: <?=date('d-m-Y', strtotime($scrap_receive->scrap_receive_date))?></small>
                    </h4>
                  </div>
                  <!-- /.col -->
                </div>
                <div class="row">
                  <div class="col-12">
                    <table width="100%" class="table">
                      <tr style="font-size: 16px;font-weight: bolder;">
                        <td><?=$this->lang->line('company_detail')?></td>
                        <td><?=$this->lang->line('bill_to')?></td>
                        <td><?=$this->lang->line('invoice_details')?></td>
                      </tr>
                      <tr>
                        <td>
                          <address>
                            <strong><?=$company_setting->company_name?></strong><br>
                            <?=($company_setting->address_line1 != '') ? $company_setting->address_line1.'<br>':'' ?>
                            <?=($company_setting->address_line2 != '') ? $company_setting->address_line2.'<br>':'' ?>
                            <?=$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name?><br>
                            <?=($company_setting->pincode != '') ? $company_setting->pincode.'<br>':'' ?>
                            
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
                        <td style="vertical-align: text-top;">
                          <b><?=$this->lang->line('scrap_receive_reference_no')?>: </b><?=$scrap_receive->reference_no?><br>
                          <!-- <b><?=$this->lang->line('receipt_voucher_no')?></b><br/> -->
                          <b><?=$this->lang->line('rcm_applicability')?>: </b><?=($scrap_receive->rcm == "Y") ? "Yes" : "No"?>
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
                          <th><?=$this->lang->line('scrap_issue_sr')?></th>
                          <th><?=$this->lang->line('scrap_issue_items')?></th>
                          <th><?=$this->lang->line('scrap_issue_tax')?></th>
                       
                          <th><?=$this->lang->line('product_batch_no')?></th>
                          <th><?=$this->lang->line('product_price')?></th>
                          <th><?=$this->lang->line('product_cost')?></th>
                          <th><?=$this->lang->line('scrap_issue_qty')?></th>
                          
                          <th><?=$this->lang->line('scrap_issue_total_qty')?></th>
                        
                      
                          <!-- <th><?=$this->lang->line('scrap_issue_total_taxable_value')?></th> -->
                          <th><?=$this->lang->line('scrap_issue_subtotal')?></th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $i = 1;
                          $total_quantity = 0;
                         
                          $total_total_quantity = 0;
                         
                          $total_price    = 0;
                          $total_discount_amount = 0;
                          $total_taxable_value = 0;
                          $total_tax = 0;
                          $total_subtotal = 0;
                          $total_cost = 0;
                          $total = 0;
                          foreach ($scrap_receive_items as $row) 
                          {
                            $total_quantity         += $row->quantity;
                           
                            $total_total_quantity   += $row->total_quantity;
                          
                            $total_cost             += $row->cost;
                            $total_tax              += $row->igst_tax+$row->cgst_tax+$row->sgst_tax;
                            $total_subtotal         += $row->sub_total;
                            
                        ?>
                          <tr>
                            <td><?=$i++?></td>
                            <td>
                              <?php 
                                $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);

                                $product_description = ($row->description != '') ? '<br/>'.$row->description : '<br/>';
                                $optional = ($row->optional != '') ? '<br/>'.$row->optional.'<br/>' : '<br/>';
                              ?>
                              <?=$row->product_name.$product_description.$optional?>
                            </td>
                            <td>
                              <?php 
                                if($company_setting->country_id == $customer_detail->country_id)
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
                              ?>
                            </td>
                            
                            <td><?=$row->batch_no?></td>
                            
                            
                            <td><?=$row->price?></td>
                            <td><?=$row->cost?></td>
                            <td><?=$row->quantity?></td>
                            
                            <td><?=$row->total_quantity?></td>
                           
                            <td><?=$row->sub_total?></td>
                            
                          </tr>
                        <?php 
                          }
                        ?>
                        <tr>
                          <th colspan="2">Total</th>
                          <th><?=$total_tax?></th>
                          <th colspan="2"></th>
                          <th><?=number_format($total_cost , 2)?></th>
                          <th><?=number_format($total_quantity,2)?></th>
                          <th><?=number_format($total_total_quantity,2)?></th>
                          <th><?=$total_subtotal?></th>
                        </tr>

                        <tr>
                          <td colspan="8"><b>Total Taxable Value</b></td>
                          <td><b><?=$scrap_receive->total_taxable_value?><b></td>
                        </tr>

                        <tr>
                          <td colspan="8"><b>Total Amount</b></td>
                          <td><b><?=$scrap_receive->total?><b></td>
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
                        <td style="text-align:right">
                         <?=$this->lang->line('certified_message')?>                         
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <?=$scrap_receive->terms_and_condition?>
                        </td>
                        <td style="text-align:right">
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
                <div class="row no-print d-none">
                  <div class="col-12">
                    
                    <button type="button" class="btn btn-success float-left print_invoice">
                      <i class="fas fa-print"></i> <?=$this->lang->line('print')?>
                    </button>
                    <!-- <button type="button" class="btn btn-success float-right">
                      <i class="far fa-credit-card"></i> Submit Payment
                    </button>
                    <a class="btn btn-primary float-right" style="margin-right: 5px;" href="<?=base_url('sale/pdf/'.$scrap_receive->id)?>" target="_blank">
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


<?php $this->load->view('layout/footer');?>


<script type="text/javascript">
  $(document).ready(function(e){
    $('.print_invoice').click(function(e){
      $('.invoice').printThis();
    });
  });
</script>

