<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
      table, th, td {
        border-collapse: collapse;
        page-break-inside: avoid !important;
        page-break-inside: auto !important;
        page-break-after:auto !important;

        white-space: nowrap;

      }
      tr{ 
        page-break-inside:avoid;
        page-break-after:auto 
      }

      th, td {
        text-align: left;
        border-bottom: 1px solid #ddd;
        padding: 15px;

      }
    </style>
  </head>
  <body>
      <center><h3>TAX INVOICE UNDER GST ACT <br/>(ORIGINAL FOR RECIPIENT)</h3></center>
      <h3 style="float: left"><i class="fa fa-globe"></i> <?=$company_setting->company_name?> </h3>                     
      <h4 style="float: right">Date: <?=date('d-m-Y', strtotime($sale->invoice_date))?></h3>
    
      <table style="width:100%">
        <tr>
          <th style="width: 30%;">Name & Address of the Supplier</th>
          <th style="width: 30%;">Bill To</th>
          <th style="width: 30%;">Invoice Details</th>
        </tr>
        <tr>
          <td>
            <strong><?=$company_setting->company_name?></strong><br>
            <?=$company_setting->address_line1?><br>
            <?=$company_setting->address_line2?><br>
            <?=$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name?><br>
            <?=$company_setting->pincode?><br>
            Phone: <?=$company_setting->mobile?><br>
            Email: <?=$company_setting->email?><br>
            <strong>GSTIN of Supplier: <?=$company_setting->gstin?></strong><br>
          </td>
          <td style="vertical-align: text-top;">
            <strong><?=$customer_detail->customer_name?></strong><br>
            <?=$customer_detail->address?><br>
            <?=$customer_detail->city_name.', '.$customer_detail->state_name.', '.$customer_detail->country_name?><br>
            Phone: <?=$customer_detail->phone?><br>
            Email: <?=$customer_detail->email?><br>
            GSTIN: <?=$customer_detail->gstin?><br>
          </td>
          <td style="vertical-align: text-top;">
            <b>Invoice #<?=$sale->reference_no?></b><br>
            <b>Receipt Voucher No #</b>
          </td>
        </tr>
      </table>
      <br/>
      <table style="width: 100%;">
        <thead>
          <tr>
            <th>Sr.</th>
            <th>Product</th>
            <th>HSN</th>
            <th>Qty</th>
            <th>Price (<?=$this->session->userdata('currency_symbol')?>)</th>
            <th>Discount (<?=$this->session->userdata('currency_symbol')?>)</th>
            <th>Total Taxable Value (<?=$this->session->userdata('currency_symbol')?>)</th>
            <th>Tax (<?=$this->session->userdata('currency_symbol')?>)</th>
            <th>Subtotal (<?=$this->session->userdata('currency_symbol')?>)</th>
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
            $total_subtotal = 0;
            foreach ($sale_items as $row) 
            {
              $total_quantity         += $row->quantity;
              $total_price            += $row->price;
              $total_discount_amount  += $row->discount_amount;
              $total_taxable_value    += $row->taxable_value;
              $total_tax              += $row->cgst_tax+$row->sgst_tax+$row->igst_tax;
              $total_subtotal         += $row->sub_total;
          ?>
            <tr>
              <td><?=$i++?></td>
              <td><?=$row->service_name.'<br/>'.$row->service_description?></td>
              <td><?=$row->sac_code?></td>
              <td><?=$row->quantity?></td>
              <td><?=$row->price?></td>
              <td><?=$row->discount_amount?></td>
              <td><?=$row->taxable_value?></td>
              <td>
                <?php 
                  if($company_setting->country_id == $customer_detail->country_id)
                  {
                    if($company_setting->state_id == $customer_detail->state_id)
                    {
                ?>
                      CGST : <?=$row->cgst_tax?>
                      (<?=$row->cgst?>%)
                      <br>SGST : <?=$row->sgst_tax?>
                      (<?=$row->sgst?>%)
                <?php
                    }
                    else
                    {
                ?>
                      IGST : <?=$row->igst_tax?>
                      (<?=$row->cgst?>%)
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
              <td><?=$row->sub_total?></td>
            </tr>
          <?php 
            }
          ?>
            <tr style="font-size: 16px;font-weight: bolder;">
              <td colspan="3">
                Total Amount Due   
              </td>
              <td><?=$total_quantity?></td>
              <td><?=$total_price?></td>
              <td><?=$total_discount_amount?></td>
              <td><?=$total_taxable_value?></td>
              <td><?=$total_tax?></td>
              <td><?=$total_subtotal?></td>
            </tr>
            <tr style="font-size: 16px;font-weight: bolder;">
              <td colspan="8">
                Advance Adjusted (if any)
              </td>
              <td><?="0.0"?></td>
            </tr>
            <tr style="font-size: 16px;font-weight: bolder;">
              <td colspan="8">
                Rounded Off 
              </td>
              <td>
                <?php 
                  if((round($sale->total_taxable_value+$sale->total_tax) - ($sale->total_taxable_value+$sale->total_tax)) > 0)
                  {
                    echo '+'.number_format((round($sale->total_taxable_value+$sale->total_tax) - ($sale->total_taxable_value+$sale->total_tax)),2);
                  }
                  else
                  {
                    echo '-'.number_format((round($sale->total_taxable_value+$sale->total_tax) - ($sale->total_taxable_value+$sale->total_tax)),2);
                  }
                ?>
              </td>
            </tr>
            <tr style="font-size: 16px;font-weight: bolder;">
              <td colspan="8">
                Balance Payable
              </td>
              <td>
                <?php echo round($sale->total_taxable_value+$sale->total_tax);?>
              </td>
            </tr>
            <tr style="font-size: 16px;font-weight: bold; ">
              <td colspan="5">
                Amount in Words
              </td>
              <td colspan="4" style="text-align: right">
                <?php echo $this->numbertowords->convert_number(round($sale->total_taxable_value+$sale->total_tax));?>
              </td>
            </tr>
        </tbody>
      </table>
      <table style="width: 100%;">
        <tr>
          <td colspan="6" style="font-size: 16px;font-weight: bolder;">Bank Details</td>
          <td colspan="3" style="font-size: 16px;font-weight: bolder;">Remarks (if any)</td>
        </tr>
        <tr>
          <td colspan="6">
            <?=$sale->bank_detail?>
          </td>
          <td colspan="3">
            <?=$sale->external_note?>
          </td>
        </tr>
        <tr>
          <td colspan="6" style="font-size: 16px;font-weight: bolder;">Terms & Condition</td>
          <td colspan="3" style="font-size: 16px;font-weight: bolder;">Certified that the perticulars given above are true and correct</td>
        </tr>
        <tr>
          <td colspan="6">
            <?=$sale->terms_and_condition?>
          </td>
          <td colspan="3">
            For, <?=strtoupper($company_setting->company_name)?><br/>
            <h3>Authorised Signatory</h3>
          </td>
        </tr>
      </table>
      <a style="margin-right: 5px; float: right;" href="<?=base_url('sale/pdf/'.$sale->id)?>">
        <i class="fa fa-download"></i> Generate PDF
      </a>
  </body>
</html>
