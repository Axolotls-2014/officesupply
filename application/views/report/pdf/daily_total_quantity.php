<html>
  <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/adminlte.min.css"> -->
    <style>
      body {
        font-size: 12px;
        padding: 0px;
        margin: 0px;
      }
      table, th, td {
        border-collapse: collapse;
      }

      thead, .table_header {
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
        if($warehouse_id != '') {
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
      <tr class="font-weight-bolder" style="background-color:gray;color:white">
        <td>AM ORDER</td>
        <td>PM ORDER</td>
      </tr>
      <tr>
        <td style="vertical-align: top;">
          <table width="100%" style="vertical-align: top;">
            <thead>
              <tr>
                <th width="80%">Product Name</th>
                <th width="20%">Quantity</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                if($warehouses) {
                  foreach ($warehouses as $wh) {
                    $warehouse = $this->warehouse_model->get_single_record($wh->warehouse_id);
                    $product_items = $this->report_model->get_product_details_with_totals($date,'PM',$wh->warehouse_id)->result();

                    if($product_items) { 
                      ?>
                      <tr style="background-color:#E5E4E2">
                        <td colspan="2"><?=$warehouse->name?></td>
                      </tr>
                      <?php 
                      $total_product_quantity = 0;
                      $total_amount = 0;
                      foreach ($product_items as $pi) {
                        $total_product_quantity += $pi->total_quantity;
                        $total_amount += ($pi->total_taxable_value - $pi->total_discount_amount + ($pi->total_igst_tax + $pi->total_cgst_tax + $pi->total_sgst_tax));
                        ?>
                        <tr>
                          <td><?=$pi->product_name?></td>
                          <td><?=$pi->total_quantity?></td>
                        </tr>
                        <?php
                      }
                      ?>
                      <tr>
                        <td colspan="2">Total Quantity : <strong><?=$total_product_quantity?></strong></td>
                      </tr>
                      <tr>
                        <td colspan="2">Total Amount : <strong><?=$total_amount?></strong></td>
                      </tr>
                      <?php
                    }
                  }
                } else {
              ?>
              <tr>
                <td colspan="2">
                  No record(s) are available.      
                </td>
              </tr>
              <?php
                }
              ?>
            </tbody>
          </table>
        </td>
        <td style="vertical-align: text-top !important;">
          <table width="100%">
            <thead>
              <tr>
                <th width="80%">Product Name</th>
                <th width="20%">Quantity</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                if($warehouses) {
                  foreach ($warehouses as $wh) {
                    $warehouse = $this->warehouse_model->get_single_record($wh->warehouse_id);
                    $product_items = $this->report_model->get_product_details_with_totals($date,'AM',$wh->warehouse_id)->result();

                    if($product_items) { 
                      ?>
                      <tr style="background-color:#E5E4E2">
                        <td colspan="2"><?=$warehouse->name?></td>
                      </tr>
                      <?php 
                      $total_product_quantity = 0;
                      $total_amount = 0;
                      foreach ($product_items as $pi) {
                        $total_product_quantity += $pi->total_quantity;
                        $total_amount += ($pi->total_selling_price - $pi->total_discount_amount + ($pi->total_igst_tax + $pi->total_cgst_tax + $pi->total_sgst_tax));
                        ?>
                        <tr>
                          <td><?=$pi->product_name?></td>
                          <td><?=$pi->total_quantity?></td>
                        </tr>
                        <?php
                      }
                      ?>
                      <tr>
                        <td colspan="2">Total Quantity : <strong><?=$total_product_quantity?></strong></td>
                      </tr>
                      <tr>
                        <td colspan="2">Total Amount : <strong><?=number_format_i($total_amount)?></strong></td>
                      </tr>
                      <?php
                    }
                  }
                } else {
              ?>
              <tr>
                <td colspan="2">
                  No record(s) are available.      
                </td>
              </tr>
              <?php
                }
              ?>
            </tbody>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
