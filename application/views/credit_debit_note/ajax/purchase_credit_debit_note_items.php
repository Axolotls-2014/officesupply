<table class="table table-bordered" id="credit_debit_note_items_table">
  <thead>
    <tr>
      <th>Invoice No</th>
      <th>Product Name</th>
      <th>Tax Rate</th>
      <th>Quantity</th>
      <th>Old Price</th>      
      <th>Old Taxable Value</th>
      <th>Old Tax</th>
      <th>Old Subtotal</th>
      <th>New Price</th>
      <th>New Taxable Value</th>
      <th>New Tax</th>
      <th>New Subtotal</th>
    </tr>
  </thead>
  <tbody id="credit_debit_note_items_tbody">
    <?php 
    
      $total_old_taxable_value = 0;
      $total_old_tax = 0;
      $total_old_subtotal = 0;

      if(sizeof($records) >  0)
      {
        foreach ($records as $value) 
        {
          $total_old_taxable_value += $value->taxable_value;
          $total_old_tax += $value->igst_tax+$value->cgst_tax+$value->sgst_tax;
          $total_old_subtotal += $value->subtotal;
    ?>
          <tr>
            <td>
              <input type="hidden" name="entry_id" value="<?=$value->purchase_id?>">              
              <input type="hidden" name="entry_item_id" value="<?=$value->id?>">              
              <input type="hidden" name="entry_type" value="purchase">              
              <span name="reference_no"><?=$value->reference_no?></span>
            </td>
            <td>
              <span name="product_name"><?=$value->product_name?></span>
            </td>
            <td>
              <span name="tax_rate"><?=$value->igst+$value->sgst+$value->cgst?></span>
            </td>
            <td>
              <input type="text" name="quantity" style="width: fit-content" class="form-control" value="<?=$value->quantity?>">
              <!-- <span name="quantity"><?=$value->quantity?></span> -->
            </td>
            <td>
              <span name="old_price"><?=$value->cost?></span>
            </td>
            <td>
              <span name="old_taxable_value"><?=$value->taxable_value?></span>
            </td>
            <td>
              <input type="hidden" name="r_igst" value="<?=$value->igst?>">
              <input type="hidden" name="r_cgst" value="<?=$value->cgst?>">
              <input type="hidden" name="r_sgst" value="<?=$value->sgst?>">              
              <span name="old_tax"><?=$value->igst_tax+$value->sgst_tax+$value->cgst_tax?></span>
            </td>
            <td>
              <span name="old_sub_total"><?=$value->subtotal?></span>
            </td>
            <td>
              <input type="number" name="new_price" style="width: fit-content" class="form-control new_price" value="<?=$value->cost?>" step="0.01">
            </td>
            <td>
              <span name="new_taxable_value"><?=$value->taxable_value?></span>
            </td>
            <td>              
              <span name="new_tax"><?=$value->igst_tax+$value->sgst_tax+$value->cgst_tax?></span>
            </td>
            <td>
              <span name="new_sub_total"><?=$value->subtotal?></span>
            </td>
          </tr> 
    <?php 
        }
      }
      else
      {
    ?>
        <tr>
          <td colspan="13">
            No records are not found
          </td>
        </tr>
    <?php
      }
    ?>
    
    
  </tbody>
  <tfoot >
    <th>Total</th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th>
      <span name="total_old_taxable_value"><?=$total_old_taxable_value?></span>
    </th>
    <th>
      <span name="total_old_tax"><?=$total_old_tax?></span>
    </th>
    <th>
      <span name="total_old_subtotal"><?=$total_old_subtotal?></span>
    </th>
    <th></th>
    <th>
      <span name="total_new_taxable_value"><?=$total_old_taxable_value?></span>
    </th>
    <th>
      <span name="total_new_tax"><?=$total_old_tax?></span>
    </th>
    <th>
      <span name="total_new_subtotal"><?=$total_old_subtotal?></span>
    </th>
  </tfoot>

</table>