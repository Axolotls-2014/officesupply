<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th width="5%"><input type="checkbox" id="selectAll"></th>
          <th>Product Name</th>
          <th>Selling Price</th>
          <th>Sold Qty</th>
          <th>Previously Returned</th>
          <th>Available for Return</th>
          <th width="15%">Return Quantity</th>
        </tr>
      </thead>
      <tbody id="existing_sale_items">
      <?php 
        foreach ($sale_items as $item) 
        { 
          // Calculated in controller: Original Sold - Already Returned
          $max_returnable = $item->available_to_return;
      ?>
        <tr>
          <td>
            <input type="checkbox" name="checkbox" class="checkbox" <?= ($max_returnable <= 0) ? 'disabled' : '' ?>>
            <input type="hidden" name="product_id" value="<?=$item->product_id?>">
            <input type="hidden" name="warehouse_product_id" value="<?=$item->warehouse_product_id?>">
            <input type="hidden" name="product_name" value="<?=$item->product_name?>">
            <input type="hidden" name="cost" value="<?=$item->cost?>">
            <input type="hidden" name="price" value="<?=$item->price?>">
            <input type="hidden" name="selling_price" value="<?=$item->selling_price?>">
          </td>
          <td><?=$item->product_name?></td>
          <td><?=number_format($item->selling_price, 2)?></td>
          <td><?=$item->quantity?></td>
          <td><?=$item->previously_returned?></td>
          <td class="<?= ($max_returnable <= 0) ? 'text-danger' : 'text-success' ?>">
            <b><?=number_format($max_returnable, 2)?></b>
          </td>
          <td>
            <input type="number" class="form-control return_quantity" 
                   name="return_quantity" 
                   value="0" 
                   min="0" 
                   max="<?=$max_returnable?>"
                   <?= ($max_returnable <= 0) ? 'disabled' : '' ?>>
            <?php if($max_returnable <= 0): ?>
                <small class="text-danger">Fully Returned</small>
            <?php else: ?>
                <small class="text-muted">Max: <?=$max_returnable?></small>
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