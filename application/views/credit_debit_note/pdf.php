<!DOCTYPE html>
<html>
<head>
  <style type="text/css">
     table {
      width: 100%;
    }

  /*  th, td {
      border: 1px solid black;
      padding:4px;
    }*/

    /* Left align content in a specific cell */
    .left-align {
      text-align: right;
      padding-right: 10px;
    }

    /* Right align content in a specific cell */
    .right-align {      
      text-align: left;
      padding-left: 10px;
    }

    .bordered-table {
      border: 1px solid black;
    }

    .bordered-table th,
    .bordered-table td {
      border: 1px solid black;
    
      font-size: 12px;
    }

    .page-break {
      page-break-before: always;
    }
  </style>
</head>
<body>
  <table border="0" style="border-right: 1px;border-top: 1px;border-left: 1px;border-bottom: 1px;">
    <tr>
      <td colspan="3
      " style="text-align: center">
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
      </td>
    </tr>
    <tr>
      <td colspan="3">
        <center>
          <strong><?=$company_setting->company_name?></strong><br>
          <?= ($company_setting->address_line1 != '') ? ($company_setting->address_line1.'<br/>') : '' ?>
          <?= ($company_setting->address_line2 != '') ? ($company_setting->address_line2.'<br/>') : '' ?>
          <?=$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name?><br>
          <?= ($company_setting->pincode != '') ? ($company_setting->pincode.'<br/>') : '' ?>
          <strong>Phone :</strong> <?= ($company_setting->mobile != '') ? ($company_setting->mobile) : '' ?>&nbsp;&nbsp;
          <strong>Email :</strong> <?= ($company_setting->email != '') ? ($company_setting->email.'<br/>') : '' ?>
        </center>
      </td>
    </tr>
  
    <tr>
      <th style="text-align: left;font-size:12px;">
        GST No.     : <?= ($company_setting->gstin != '') ? ($company_setting->gstin) : '' ?><br/>
        State Code  : <?=$company_setting->state_code;?>
      </th>
      <th></th>
      <!-- <th style="text-align: right;font-size:12px;">
        D.L No. : <?= ($company_setting->dl_no != '') ? ($company_setting->dl_no) : '' ?>
      </th> -->
    </tr>

    <tr>
      <td colspan="3">
        <center>
           <strong>
            <?php
              $cdn_note_type = '';
              if($credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT || $credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)
                $cdn_note_type = "CREDIT NOTE";
              else if($credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_DEBIT || $credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_DEBIT_CUSTOMER)
                $cdn_note_type = "DEBIT NOTE";
              else
                $cdn_note_type = "Advance Refund Voucher";

                echo '<span style="letter-spacing: 4px;">' . $cdn_note_type . '</span>';
            ?>
          </strong>
        </center>
      </td>

    <tr>
      <td colspan="3"><br/>
      </td>
    </tr>

    <tr>
      <td colspan="3">
        <table border="1" width="100%" cellspacing="0">
          <tr>
            <?php
              if($credit_debit_note->cdn_note_type === CREDIT_NOTE_TYPE_DEBIT || $credit_debit_note->cdn_note_type === CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)
              {
            ?>
                <td style="text-align: left;padding:4px;border-right: 0;" width="40%">
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

                <td style="text-align: left;padding:4px;border-right: 0;" width="40%">
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

              

              <td style="text-align: right;padding:4px;verticle-align:top; border-left: 0;">
                <table border = "0" style="width: 60%;float:right;">
                  <tr  style="verticle-align:top;">
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
      </td>
    </tr>

    <tr>
      <td colspan="3" style="text-align: left;padding: 8px">
        <strong>Your Account has been Adjusted following:</strong><br/>
      </td>
    </tr>

    <tr>
      <td colspan="3">
        <table border="1" width="100%" cellspacing="0">
          <tr>
            <th style="text-align: left;padding:4px;border-right: 0;">Particulars</th>
            
            <th style="text-align: right;padding:4px;border-left: 0;"><?=$this->lang->line('cdn_amount')?></th>
          </tr>

          <tr>
            <td style="padding:4px;border-right: 0;"><?=$credit_debit_note->title;?></td>
            <td style="text-align: right;padding:4px;border-left: 0;"><?=$credit_debit_note->cdn_amount;?></td>
          </tr>      
        </table>
      </td>
    </tr>



    <?php
      if($credit_debit_note->cdn_sale_ids != ''){
    ?>

        <tr class="page-break">
          <td colspan="3">
            <br/>
            <strong>Sales Reference No : </strong>
            <p><?=$credit_debit_note->sale_reference_no?></p>
          </td>
        </tr>

    <?php
      }
    ?>

    <?php
      if($credit_debit_note->cdn_purchase_ids != ''){
    ?>

        <tr class="page-break">
          <td colspan="3">
          
            <strong>Purchase Reference No : </strong>
            <p><?=$credit_debit_note->purchase_reference_no?></p>
          </td>
        </tr>

    <?php
      }
    ?>

    <?php
      if(sizeof($credit_debit_note_items) >  0)
      {
    ?>

        <tr>
          <td colspan="3">
            <table class="bordered-table" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th style="padding:4px;text-align:left;">Invoice No</th>
                  <th style="padding:4px;text-align:left;">Product Name</th>
                  <th style="padding:4px;text-align:left;">Tax Rate</th>
                  <th style="padding:4px;text-align:left;">Quantity</th>
                  <th style="padding:4px;text-align:left;">Old Price</th>      
                  <th style="padding:4px;text-align:left;">Old Taxable Value</th>
                  <th style="padding:4px;text-align:left;">Old Tax</th>
                  <th style="padding:4px;text-align:left;">Old Subtotal</th>
                  <th style="padding:4px;text-align:left;">New Price</th>
                  <th style="padding:4px;text-align:left;">New Taxable Value</th>
                  <th style="padding:4px;text-align:left;">New Tax</th>
                  <th style="padding:4px;text-align:left;">New Subtotal</th>
                  <!-- <th style="text-align: left;padding:4px;">Invoice No</th>
                  
                  <th style="text-align: right;padding:4px;">Product Name</th> -->
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
                          <td style="padding:4px;text-align:left;">
                            <span name="reference_no"><?=$value->reference_no?></span>
                          </td>
                          <td style="padding:4px;text-align:left;">
                            <span name="product_name"><?=$value->product_name?></span>
                          </td>
                          <td style="padding:4px;text-align:left;">
                            <span name="tax_rate"><?=$value->r_igst+$value->r_sgst+$value->r_cgst?></span>
                          </td>
                          <td style="padding:4px;text-align:left;">
                            <span name="quantity"><?=$value->quantity?></span>
                          </td>
                          <td style="padding:4px;text-align:left;">
                            <span name="old_price"><?=$value->old_price?></span>
                          </td>
                          <td style="padding:4px;text-align:left;">
                            <span name="old_taxable_value"><?=$value->old_taxable_value?></span>
                          </td>
                          <td style="padding:4px;text-align:left;">
                            <span name="old_tax"><?=$value->old_tax?></span>
                          </td>
                          <td style="padding:4px;text-align:left;">
                            <span name="old_sub_total"><?=$value->old_sub_total?></span>
                          </td>
                          <td style="padding:4px;text-align:left;">
                            <span><?=$value->new_price?></span>  
                          </td>
                          <td style="padding:4px;text-align:left;">
                            <span name="new_taxable_value"><?=$value->new_taxable_value?></span>
                          </td>
                          <td style="padding:4px;text-align:left;">              
                            <span name="new_tax"><?=$value->new_tax?></span>
                          </td>
                          <td style="padding:4px;text-align:left;">
                            <span name="new_sub_total"><?=$value->new_sub_total?></span>
                          </td>
                        </tr> 
                  <?php 
                      // }
                      
                    }
                  ?>
            
              
              </tbody>
              <tfoot>
                <th style="padding:4px;text-align:left;" colspan="5">Total</th>
                <!-- <th></th>
                <th></th>
                <th></th>
                <th></th> -->
                <th style="padding:4px;text-align:left;">
                  <span name="total_old_taxable_value"><?=$total_old_taxable_value?></span>
                </th>
                <th style="padding:4px;text-align:left;">
                  <span name="total_old_tax"><?=$total_old_tax?></span>
                </th>
                <th style="padding:4px;text-align:left;">
                  <span name="total_old_subtotal"><?=$total_old_subtotal?></span>
                </th>
                <th></th>
                <th style="padding:4px;text-align:left;">
                  <span name="total_new_taxable_value"><?=$total_new_taxable_value?></span>
                </th>
                <th style="padding:4px;text-align:left;">
                  <span name="total_new_tax"><?=$total_new_tax?></span>
                </th>
                <th style="padding:4px;text-align:left;">
                  <span name="total_new_subtotal"><?=$total_new_subtotal?></span>
                </th>
              </tfoot>
            </table>
          </td>
        </tr>

        <br/>
        <tr>
          <td colspan="3">
            <table class="bordered-table" width="100%" cellspacing="0">
              <tr>
                <th width="25%"></th>
                <th width="25%" style="padding:4px;;text-align:left;">Old</th>
                <th width="25%" style="padding:4px;text-align:left;">New</th>
                <th width="25%" style="padding:4px;text-align:left;">Difference</th>
              </tr>

              <tr>
                <td style="padding:4px;text-align:left;"><b>Taxable Value</b></td>
                <td style="padding:4px;text-align:left;"><?=$total_old_taxable_value?></td>
                <td style="padding:4px;text-align:left;"><?=$total_new_taxable_value?></td>
              
                <td style="padding:4px;text-align:left;">
                  <?php
                    if($total_new_taxable_value > $total_old_taxable_value)
                      echo number_format(($total_new_taxable_value - $total_old_taxable_value), 2);
                    else
                      echo number_format(($total_old_taxable_value - $total_new_taxable_value), 2);
                  ?>
                  
                </td>
              </tr> 
              
              <tr>
                <td style="padding:4px;text-align:left;"><b>Tax</b></td>
                <td style="padding:4px;text-align:left;"><?=$total_old_tax?></td>
                <td style="padding:4px;text-align:left;"><?=$total_new_tax?></td>
                
                <td style="padding:4px;text-align:left;">
                  <?php
                    if($total_new_tax > $total_old_tax)
                      echo number_format(($total_new_tax - $total_old_tax) , 2);
                    else
                      echo number_format(($total_old_tax - $total_new_tax), 2);
                  ?>
                  
                </td>

                
              </tr>
              
              <tr>
                <td style="padding:4px;text-align:left;"><b>Sub Total</b></td>
                <td style="padding:4px;text-align:left;"><?=$total_old_subtotal?></td>
                <td style="padding:4px;text-align:left;"><?=$total_new_subtotal?></td>
                
                <td style="padding:4px;text-align:left;"><b>
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
          </td>
        </tr>
    <?php
      }
    ?>


    <tr>
      <td colspan="3">
        <br/>
        <strong>Narration : </strong>
        
        <p><?=$credit_debit_note->cdn_description?></p>
        <!-- <p>Please make note of the credit amount stated above, which will be applied to your account. The total credited amount will be deducted/added from/to any outstanding balance or can be utilized towards future purchases/sales.</p> -->

        <!--  <p>Should you have any questions or concerns regarding this credit note, feel free to reach out to our dedicated customer support team at [Customer Support Phone Number] or [Customer Support Email].</p> -->
        <!-- <p>Once again, we apologize for any inconvenience caused and appreciate your understanding in this matter. We value your continued patronage and look forward to serving you in the future.</p><br/>
        <p>Thank you for your cooperation.</p> -->
   
      </td>
    </tr>
    
   
    <tr>
      <td colspan="3">
        <br/>
        <br/>
        <br/>
        <span style="border: 1px; width: 30px"></span>
        <hr style="width: 10%; border-color: black;margin-left: 0px;">
        Signature
      </td>
    </tr>

    
  </table>
</body>

