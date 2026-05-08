<html>
  <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/adminlte.min.css"> -->
    <style>
     body{
        font-size: 12px;
        padding: 0px;
        margin: 0px;
      }
      table, th, td {
        border-collapse: collapse;
      }

      thead,.table_header{
        background-color: #f2f2f2;
      }

      th, td {
        text-align: left;
        border-bottom: 1px solid #ddd;
        padding: 1px;
      }
      
    </style>
  </head>
  <body>
    
    <center>
      <h4><b><?=$company_setting->company_name?></b></h4>       
      <?php 
        if($warehouse_id != '')
        {
          $warehouse = $this->warehouse_model->get_single_record($warehouse_id);
          echo $warehouse->name;  
        }
      ?>
      <h5><b><?=$order_time?> Order Report of <?=date('d-m-Y',strtotime($date))?></b></h5>
    </center>

    <table style="width: 100%;" >
      <tr>
        <td>
          <strong>Name: </strong><?=$company_setting->company_name?><br>
          <strong>Phone: </strong><?=$company_setting->mobile?><br>
          <strong>Email: </strong><?=$company_setting->email?><br><br>
        </td>
        <td>
          <strong>Address: </strong><?=$company_setting->address_line1.', '.$company_setting->address_line2.', '.$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name.'. - '.$company_setting->pincode ?><br>
          <strong>GST No: </strong><?=$company_setting->gstin?><br><br>
        </td>
      </tr>
    
    </table>
    
    <table border="0" width="100%">
      <tr  style="background-color:gray;color:white">
        <td width="35%">Name</td>
        <td width="25%">Qty</td>
        <td width="15%">Price</td>
        <td width="25%">Total</td>
      </tr>

      <?php  
        if($customers){
          foreach ($customers as $value) {
            $customer = $this->customer_model->get_single_record($value->customer_id);
            $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);

      ?>
          <tr>
            <td colspan="5">
              <b><?=strtoupper($customer->customer_name)?></b>
            </td>
          </tr>
          <?php 
            $sales = $this->report_model->daily_report($date, $value->customer_id,$order_time)->result();
            $sales_amount = 0;
            $total_quantity = 0;
            $paid_amount = 0;
            $total_due_amount = 0;
            foreach ($sales as $sl) 
            {
              $sales_amount += $sl->total;
              $paid_amount += ($this->transaction_model->get_total_transaction_amount($sl->id,SALE_MODULE,RECEIPT_TRANSACTION_TYPE) 
                              + $this->transaction_model->get_total_transaction_amount($sl->id,SALE_MODULE,CREDIT_TRANSACTION_TYPE));
              $total_due_amount += ($sales_amount - $paid_amount);
              $sale_items = $this->sale_model->get_sale_item_records($sl->id);
              foreach ($sale_items as $si) 
              {
                $total_quantity += $si->quantity;
          ?>
              <tr>
                <td><?=$si->product_name?></td>
                <td><?=$si->quantity?></td>
                <td><?=$si->selling_price?></td>
                <td><?=($si->taxable_value+($si->cgst_tax+$si->sgst_tax+$si->igst_tax)-$si->discount_amount)?></td>
              </tr>
          <?php 
              }
            }
          ?>
          <tr class="font-weight-bolder">
            <td></td>
            <td><?=$total_quantity?></td>
            <td></td>
            <td><?=$sales_amount?></td>
          </tr>
          <tr>
            <td style="text-align:right;font-weight:bolder;padding-right:10px">Debit:</td>
            <td style="text-align:left"><?=number_format_i($customer_ledger->closing_balance-($sales_amount-$paid_amount))?></td>
            <td style="text-align:right;font-weight:bolder;padding-right:10px">Balance:</td>
            <td style="text-align:left"><?=number_format_i($customer_ledger->closing_balance)?> (Received amount : <?=number_format_i($paid_amount)?>)</td>
          </tr>
      <?php
          }
        }
        else
        {
      ?>
            <tr>
              <td colspan="4">
                No record(s) are not found.
              </td>
            </tr>
      <?php
        }
      ?>
    </table>
    
  </body>
</html>
