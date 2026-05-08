<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th><input type="checkbox" id="selectAll"></th>
          <th>Product Name</th>
          <th>Cost</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Free</th>
          <th>Returned</th>
          <th>Available Quantity</th>
          <th>Return Quantity</th>
        </tr>
      </thead>
      <tbody id="existing_sale_items">
      <?php 
        foreach ($sale_items as $item) 
        { 
          $warehouse_product = $this->warehouse_products_model->get_single_record($item->warehouse_product_id);

          $delivered_quantity = $this->sales_return_delivery_model->get_total_no_of_quantity_sales_return_created($item->sale_id,$item->warehouse_product_id); 
     
     ?>
        <tr>
          <td>
            <input type="checkbox" name="checkbox" class="checkbox">
            <input type="hidden" name="warehouse_product_id" value="<?=$warehouse_product->id?>">
            <input type="hidden" name="product_name" value="<?=$item->product_name?>">
            <input type="hidden" name="description" value="<?=$item->description?>">
            <input type="hidden" name="cost" value="<?=$item->cost?>">
            <input type="hidden" name="price" value="<?=$item->price?>">
            <input type="hidden" name="selling_price" value="<?=$item->selling_price?>">
            <input type="hidden" name="free_quantity" value="<?=$item->free_quantity?>">
          </td>
          <td><?=$item->product_name?></td>
          <td><?=$item->cost?></td>
          <td><?=$item->price?></td>
          <td>
            <?=$item->quantity?>
            <input type="hidden" name="sold_quantity" value="<?=$item->quantity?>">
          </td>
          <td>
            <?=$item->free_quantity?>
          </td>
          <td>
            <?=($delivered_quantity)?>
            <input type="hidden" name="delivered_quantity" value="<?=$delivered_quantity?>">
          </td>
          <td><?=number_format($warehouse_product->quantity,2)?></td>
          <td><input type="number" class="form-control" name="return_quantity" value="0" max="<?=$item->quantity?>"></td>
        </tr>
      <?php
        } 
      ?>
      </tbody>
    </table>    
  </div>
</div>
