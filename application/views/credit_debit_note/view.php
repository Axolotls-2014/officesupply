<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('credit_debit_note')?>"><?=$this->lang->line('header_credit_debit_note')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('cdn_view')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('cdn_view')?> </h3>
              <div class="card-tools">

              <?php
                if($this->permission_model->has_permission('download_credit_debit_note'))
                { 
              ?>

                <a href="<?=base_url('credit_debit_note/pdf/'.base64_encode($credit_debit_note->cdn_id).'/landscape')?>" class="btn bg-orange btn-sm" data-tt="tooltip" title="Download Credit Debit Note">
                  <i class="far fa-file-pdf"></i> Download
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
                    <?php 
                      $image_data = '';
                      if ($company_setting->logo != '') {
                        $image_path = './assets/images/' . $company_setting->logo; // Adjust the path accordingly
                        if (file_exists($image_path)) {
                          $image_data = file_get_contents($image_path);
                          $base64_data = base64_encode($image_data);
                    ?>
                      <img src="data:image/png;base64,<?=$base64_data?>" style="width: 100px;">
                    <?php 
                        }
                      }
                    ?>  
                   
                  </div>
                </div>

                <div class="row" style="font-size:17px;">
                  <div class="col-12 text-center">
                    <center>
                      <strong><?=$company_setting->company_name?></strong><br>
                      <?= ($company_setting->address_line1 != '') ? ($company_setting->address_line1.'<br/>') : '' ?>
                      <?= ($company_setting->address_line2 != '') ? ($company_setting->address_line2.'<br/>') : '' ?>
                      <?=$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name?><br>
                      <?= ($company_setting->pincode != '') ? ($company_setting->pincode.'<br/>') : '' ?>
                      <strong>Phone :</strong> <?= ($company_setting->mobile != '') ? ($company_setting->mobile) : '' ?>&nbsp;&nbsp;
                      <strong>Email :</strong> <?= ($company_setting->email != '') ? ($company_setting->email.'<br/>') : '' ?>
                    </center>
                  </div>
                </div>


                <div class="row">
                  <div class="col-12">
                    <h6>
                      GST No.     : <?= ($company_setting->gstin != '') ? ($company_setting->gstin) : '' ?><br/>
                      State Code  : <?=$company_setting->state_code;?>
                      <!-- <span class="float-right"> D.L No. : <?= ($company_setting->dl_no != '') ? ($company_setting->dl_no) : '' ?></span> -->
                    </h6>
                  </div>
                  <!-- /.col -->
                </div>

                <div class="row" style="margin-bottom:17px;">
                  <div class="col-12 text-center">
                    <strong>
                      <?php
                        $cdn_note_type = '';
                        if($credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT || $credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)
                          $cdn_note_type = "CREDIT NOTE";
                        else if($credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_DEBIT || $credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_DEBIT_CUSTOMER)
                          $cdn_note_type = "DEBIT NOTE";
                        else
                          $cdn_note_type = "Advance Refund Voucher";

                          echo '<span style="letter-spacing: 4px;font-size:20px;">' . $cdn_note_type . '</span>';
                      ?>
                    </strong>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">

                  </div>
                </div>

                <div class="row" style="verticle-align:top!important;">
                  <div class="col-12">
                    <table border="1" width="100%" cellspacing="0" style="font-size:17px;">
                      <tr>
                        <?php
                          if($credit_debit_note->cdn_note_type === CREDIT_NOTE_TYPE_DEBIT || $credit_debit_note->cdn_note_type === CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)
                          {
                        ?>
                            <td style="text-align: left;padding: 8px;border-right: 0;" width="40%">
                              <table border="0" width="100%">
                                <tr>
                                  <td width="30%">Name</td>
                                  <td width="10%">:</td>
                                  <td><strong><?=$supplier->company_name?></strong></td>
                                </tr>

                                <tr>
                                  <td>Address</td>
                                  <td>:</td>
                                  <td>
                                    <?= ($supplier->address != '') ? ($supplier->address.'<br/>') : '' ?>
                                    <?=$supplier->city_name.', '.$supplier->state_name.', '.$supplier->country_name?>
                                  </td>
                                </tr>

                                <tr>
                                  <td>GST No </td>
                                  <td> : </td>
                                  <td><?= ($supplier->gstin != '') ? ($supplier->gstin) : '' ?></td>
                                </tr>

                                <!-- <tr>
                                  <td>D.L No </td>
                                  <td> : </td>
                                  <td><?= ($supplier->dl_no != '') ? ($supplier->dl_no) : '' ?></td>
                                </tr> -->

                              </table>
                            </td>
                      <?php
                          }
                          else
                          {
                        ?>

                            <td style="text-align: left;padding: 8px;border-right: 0;" width="40%">
                              <table border="0" width="100%">
                                <tr>
                                  <td width="30%">Name</td>
                                  <td width="10%">:</td>
                                  <td><strong><?=$customer->customer_name?></strong></td>
                                </tr>

                                <tr>
                                  <td>Address</td>
                                  <td>:</td>
                                  <td>
                                    <?= ($customer->address != '') ? ($customer->address.'<br/>') : '' ?>
                                    <?=$customer->city_name.', '.$customer->state_name.', '.$customer->country_name?>
                                  </td>
                                </tr>

                                <tr>
                                  <td>GST No </td>
                                  <td> : </td>
                                  <td><?= ($customer->gstin != '') ? ($customer->gstin) : '' ?></td>
                                </tr>

                                <!-- <tr>
                                  <td>D.L No </td>
                                  <td> : </td>
                                  <td><?= ($customer->dl_no != '') ? ($customer->dl_no) : '' ?></td>
                                </tr> -->

                              </table>
                            </td>
                        <?php
                          }
                        ?>

                          

                          <td style="text-align: right;padding: 8px; border-left: 0; verticle-align:top!important;">
                            <table border = "0" style="width: 60%;float:right;">
                                <td><?=ucwords($cdn_note_type)?> No.</td>
                                <td>:</td>
                                <td><?=$credit_debit_note->cdn_reference_no?></td>
                              </tr>
                              <tr>
                                <td><?=ucwords($cdn_note_type)?> Dt.</td>
                                <td>:</td>
                                <td><?=date('d-m-Y', strtotime($credit_debit_note->cdn_date))?></td>
                              </tr>
                            </table>
                          </td>

                    
                      </tr>

                      
                    </table>
                  </div>
                </div>

                <div class="row" style="margin-top: 15px;margin-bottom: 10px;">
                  <div class="col-12">
                    <h6><b>Your Account has been Adjusted following:</b></h6>
                   
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">
                    <table border="1" width="100%" cellspacing="0" style="font-size:17px;">
                      <tr>
                        <th style="text-align: left;padding: 8px;border-right: 0;">Particulars</th>
                        
                        <th style="text-align: right;padding: 8px;border-left: 0;"><?=$this->lang->line('cdn_amount')?></th>
                      </tr>

                      <tr>
                        <td style="padding: 8px;border-right: 0;"><?=$credit_debit_note->title;?></td>
                        <td style="text-align: right;padding: 8px;border-left: 0;"><?=$credit_debit_note->cdn_amount;?></td>
                      </tr>      
                    </table>
                  </div>
                </div>

               
                <?php
                  if($credit_debit_note->cdn_sale_ids != ''){
                ?>

                    <div class="row" style="font-size:16px;margin-top: 15px;margin-bottom: 10px;">
                      <div class="col-12">
                        <strong>Sales Reference No : </strong>
                          <p><?=$credit_debit_note->sale_reference_no?></p>
                      </div>
                    </div>
                <?php
                  }
                ?>

                <?php
                  if($credit_debit_note->cdn_purchase_ids != ''){
                ?>


                    <div class="row" style="font-size:16px;margin-top: 15px;margin-bottom: 10px;">
                      <div class="col-12">
                        <strong>Purchase Reference No : </strong>
                          <p><?=$credit_debit_note->purchase_reference_no?></p>
                      </div>
                    </div>

                <?php 
                  }
                ?>

                <?php
                  if(sizeof($credit_debit_note_items) >  0)
                  {
                ?>
                    <div class="row">
                      <div class="col-12">
                        <table border="1" width="100%" cellspacing="0" style="font-size:17px;">
                          <thead>
                            <tr>
                              <th style="padding: 8px;">Invoice No</th>
                              <th style="padding: 8px;">Product Name</th>
                              <th style="padding: 8px;">Tax Rate</th>
                              <th style="padding: 8px;">Quantity</th>
                              <th style="padding: 8px;">Old Price</th>      
                              <th style="padding: 8px;">Old Taxable Value</th>
                              <th style="padding: 8px;">Old Tax</th>
                              <th style="padding: 8px;">Old Subtotal</th>
                              <th style="padding: 8px;">New Price</th>
                              <th style="padding: 8px;">New Taxable Value</th>
                              <th style="padding: 8px;">New Tax</th>
                              <th style="padding: 8px;">New Subtotal</th>
                              <!-- <th style="text-align: left;padding: 8px;">Invoice No</th>
                              
                              <th style="text-align: right;padding: 8px;">Product Name</th> -->
                            </tr>
                          </thead>
                          
                          <tbody>
                          
                              <?php 
                        
                                $total_old_taxable_value = 0;
                                $total_old_tax = 0;
                                $total_old_subtotal = 0;

                                $total_new_taxable_value = 0;
                                $total_new_tax = 0;
                                $total_new_subtotal = 0;

                                
                                foreach ($credit_debit_note_items as $value) 
                                { 
                                  // if($value->old_price != $value->new_price)
                                  // {
                                    $total_old_taxable_value += $value->old_taxable_value;
                                    $total_old_tax += $value->old_tax;
                                    $total_old_subtotal += $value->old_sub_total;

                                    $total_new_taxable_value += $value->new_taxable_value;
                                    $total_new_tax += $value->new_tax;
                                    $total_new_subtotal += $value->new_sub_total;
                              ?>
                                    <tr>
                                      <td style="padding: 8px;">
                                        <span name="reference_no"><?=$value->reference_no?></span>
                                      </td>
                                      <td style="padding: 8px;">
                                        <span name="product_name"><?=$value->product_name?></span>
                                      </td>
                                      <td style="padding: 8px;">
                                        <span name="tax_rate"><?=$value->r_igst+$value->r_sgst+$value->r_cgst?></span>
                                      </td>
                                      <td style="padding: 8px;">
                                        <span name="quantity"><?=$value->quantity?></span>
                                      </td>
                                      <td style="padding: 8px;">
                                        <span name="old_price"><?=$value->old_price?></span>
                                      </td>
                                      <td style="padding: 8px;">
                                        <span name="old_taxable_value"><?=$value->old_taxable_value?></span>
                                      </td>
                                      <td style="padding: 8px;">
                                        <span name="old_tax"><?=$value->old_tax?></span>
                                      </td>
                                      <td style="padding: 8px;">
                                        <span name="old_sub_total"><?=$value->old_sub_total?></span>
                                      </td>
                                      <td style="padding: 8px;">
                                        <span><?=$value->new_price?></span>  
                                      </td>
                                      <td style="padding: 8px;">
                                        <span name="new_taxable_value"><?=$value->new_taxable_value?></span>
                                      </td>
                                      <td style="padding: 8px;">              
                                        <span name="new_tax"><?=$value->new_tax?></span>
                                      </td>
                                      <td style="padding: 8px;">
                                        <span name="new_sub_total"><?=$value->new_sub_total?></span>
                                      </td>
                                    </tr> 
                              <?php 
                                  // }
                                  
                                }
                              ?>
                        
                          
                          </tbody>
                          <tfoot>
                            <th style="padding: 8px;" colspan="5">Total</th>
                            <!-- <th></th>
                            <th></th>
                            <th></th>
                            <th></th> -->
                            <th style="padding: 8px;">
                              <span name="total_old_taxable_value"><?=$total_old_taxable_value?></span>
                            </th>
                            <th style="padding: 8px;">
                              <span name="total_old_tax"><?=$total_old_tax?></span>
                            </th>
                            <th style="padding: 8px;">
                              <span name="total_old_subtotal"><?=$total_old_subtotal?></span>
                            </th>
                            <th></th>
                            <th style="padding: 8px;">
                              <span name="total_new_taxable_value"><?=$total_new_taxable_value?></span>
                            </th>
                            <th style="padding: 8px;">
                              <span name="total_new_tax"><?=$total_new_tax?></span>
                            </th>
                            <th style="padding: 8px;">
                              <span name="total_new_subtotal"><?=$total_new_subtotal?></span>
                            </th>
                          </tfoot>
                                  
                        </table>
                      </div>
                    </div>
                    <br/>
                    <div class="row">
                      <div class="col-12">
                        <table border="1" width="100%" cellspacing="0" style="font-size:17px;">
                          <tr>
                            <th width="25%"></th>
                            <th width="25%" style="padding: 8px;;">Old</th>
                            <th width="25%" style="padding: 8px;">New</th>
                            <th width="25%" style="padding: 8px;">Difference</th>
                          </tr>

                          <tr>
                            <td style="padding: 8px;"><b>Taxable Value</b></td>
                            <td style="padding: 8px;"><?=$total_old_taxable_value?></td>
                            <td style="padding: 8px;"><?=$total_new_taxable_value?></td>
                          
                            <td style="padding: 8px;">
                              <?php
                                if($total_new_taxable_value > $total_old_taxable_value)
                                  echo number_format(($total_new_taxable_value - $total_old_taxable_value), 2);
                                else
                                  echo number_format(($total_old_taxable_value - $total_new_taxable_value), 2);
                              ?>
                             
                            </td>
                          </tr> 
                          
                          <tr>
                            <td style="padding: 8px;"><b>Tax</b></td>
                            <td style="padding: 8px;"><?=$total_old_tax?></td>
                            <td style="padding: 8px;"><?=$total_new_tax?></td>
                           
                            <td style="padding: 8px;">
                              <?php
                                if($total_new_tax > $total_old_tax)
                                  echo number_format(($total_new_tax - $total_old_tax) , 2);
                                else
                                  echo number_format(($total_old_tax - $total_new_tax), 2);
                              ?>
                             
                            </td>

                            
                          </tr>
                          
                          <tr>
                            <td style="padding: 8px;"><b>Sub Total</b></td>
                            <td style="padding: 8px;"><?=$total_old_subtotal?></td>
                            <td style="padding: 8px;"><?=$total_new_subtotal?></td>
                            
                            <td style="padding: 8px;"><b>
                              <?php
                                if($total_new_subtotal > $total_old_subtotal)
                                  echo number_format(($total_new_subtotal - $total_old_subtotal) ,2);
                                else
                                  echo number_format(($total_old_subtotal - $total_new_subtotal), 2);
                              ?>
                             </b>
                            </td>
                          </tr> 
                        </table>
                      </div>
                    </div>

                <?php
                  }
                ?>

             


                <div class="row" style="font-size:17px;margin-top: 15px;margin-bottom: 10px;">
                  <div class="col-12">
                    <strong>Narration : </strong>
                      <p><?=$credit_debit_note->cdn_description?></p>
                  </div>
                </div>

                <div class="row"  style="font-size:17px;">
                  <div class="col-6">
                  <hr style="width: 10%; border-color: black;margin-left: 0px;">
                  Signature
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

