<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th width="5%"><input type="checkbox" id="selectAll"></th>
          <th>Product Name</th>
          <th>Purchase Price</th>
          <th>Purchase Qty</th>
          <th>Previously Returned Qty</th>
          <th>Available Stock</th>
          <th width="15%">Return Quantity</th>
        </tr>
      </thead>
      <tbody id="existing_purchase_items">
      <?php 
        // CHANGE: Use the correct variable name from controller
        // Use $purchase_return_items if that's what controller passes
        $items_to_loop = isset($purchase_return_items) ? $purchase_return_items : (isset($purchase_items) ? $purchase_items : array());
        
        foreach ($items_to_loop as $item) 
        { 
          $product = $this->product_model->get_single_record($item->product_id);

          // Get current stock in warehouse
          $warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price(
              $purchase_return->warehouse_id,  // CHANGE: Use $purchase_return instead of $purchase
              $item->product_id, 
              $item->batch_no, 
              $item->cost, 
              $item->price, 
              $item->selling_price
          );

          // Get already returned quantity for this purchase return
        //   $already_returned = $this->purchase_return_delivery_model->get_total_no_of_quantity_purchase_return_created(
        //       $purchase_return->id,  // CHANGE: Use $purchase_return->id
        //       $item->product_id, 
        //       $item->batch_no
        //   ); 
          
        //   // Calculate available for return (original purchase qty - already returned)
        //   $available_for_return = $item->quantity - $already_returned;
          
        //   // Get current return quantity from existing record
        //   $current_return_qty = isset($item->return_quantity) ? $item->return_quantity : 
        //                         (isset($item->quantity) ? $item->quantity : 0);
         $already_returned = $this->purchase_return_model->get_already_returned_qty($purchase->invoice_no, $item->product_id); 
          
          // 2. MODIFIED: Calculate returnable balance (Original 100 - Previous 10 = 90)
          $available_for_return = $item->quantity - $already_returned;
          
          // 3. EXISTING CODE: Keep your logic for the default quantity in the input
          $current_return_qty = isset($item->return_quantity) ? $item->return_quantity : 
                                (isset($item->quantity) ? $item->quantity : 0);
          
          // Safety Check: If the default qty is higher than what's left, cap it at 90
          if($current_return_qty > $available_for_return) $current_return_qty = $available_for_return;
      ?>
        <tr>
          <td>
            <input type="checkbox" name="checkbox" class="checkbox">
            <input type="hidden" name="product_id" value="<?=$item->product_id?>">
            <input type="hidden" name="product_name" value="<?=$item->product_name?>">
            <input type="hidden" name="description" value="<?=$item->description?>">
            <input type="hidden" name="cost" value="<?=$item->cost?>">
            <input type="hidden" name="price" value="<?=$item->price?>">
            <input type="hidden" name="selling_price" value="<?=$item->selling_price?>">
            <input type="hidden" name="batch_no" value="<?=$item->batch_no?>">
            <input type="hidden" name="free_quantity" value="<?=$item->free_quantity?>">
            <input type="hidden" name="purchase_item_id" value="<?=$item->id?>">
            <input type="hidden" name="discount_id" value="<?php echo $item->discount_id; ?>">
            <input type="hidden" name="discount_type" value="<?php echo $item->discount_type; ?>">
            <input type="hidden" name="discount_value" value="<?php echo $item->discount_value; ?>">
            <input type="hidden" name="discount_amount" value="<?php echo $item->discount_amount; ?>">
          </td>
          <td><?=$item->product_name?></td>
          <td><?=number_format($item->cost, 2)?></td>
          <td>
            <?=$item->quantity?>
            <input type="hidden" name="purchased_quantity" value="<?=$item->quantity?>">
           </td>
          <td>
            <?=$already_returned?>
            <input type="hidden" name="delivered_quantity" value="<?=$already_returned?>">
           </td>
          <!--<td><?=number_format($warehouse_product->quantity, 2)?></td>-->
          <td><?=number_format($available_for_return, 2)?></td>
          <td>
            <input type="number" class="form-control return_quantity" 
                   name="return_quantity" 
                   value="<?=$current_return_qty?>" 
                   min="0" 
                   max="<?=$available_for_return?>"
                   step="1"
            <?php if($available_for_return <= 0): ?>
                <small class="text-danger">No quantity available for return</small>
            <?php else: ?>
                <small class="text-muted">Max: <?=$available_for_return?></small>
            <?php endif; ?>
          </td>
        </tr>
      <?php
        } 
      ?>
      </tbody>
    </table>    
  </div>
</div>