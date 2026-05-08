<style>
  .font-size-14{
    font-size: 14px;
  }
  .font-weight-bolder{
    font-weight: bolder;
  }
</style>
<div class="row">
  <div class="col-12 font-size-14">
    <table class="table table-bordered" width="100%">
      <tr class="font-weight-bolder" style="background-color:gray;color:white">
        <td>AM ORDER</td>
        <td>PM ORDER</td>
      </tr>
      <tr>
        <td>
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
        <td>
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
    
  </div>
</div>