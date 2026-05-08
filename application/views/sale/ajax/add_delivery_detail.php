
<div class="row">
  <div class="col-md-4">
    <table width="100%">
      <tr>
        <td>
          <label>Reference No</label>
          <br/>
        </td>
      </tr>
    </table>
  </div>
  <div class="col-md-4">
    <label><?=$this->lang->line('sale_delivery_date')?></label>
    <input type="text" name="delivery_date" id="delivery_date" placeholder="<?=$this->lang->line('sale_delivery_date')?>" class="form-control datepicker field_validation" autocomplete="off">
    <span id="err_delivery_date" class="error invalid-feedback"></span>
  </div>
  <div class="col-md-4">
    <label><?=$this->lang->line('sale_delivered_by')?></label>
   <select class="form-control select2bs4 field_validation" name="delivered_by" placeholder="<?=$this->lang->line('sale_delivered_by')?>" id="delivered_by" style="width: 100% !important;">
      <option value=""><?=$this->lang->line('sale_delivered_by_user_select')?></option>
      <?php
        foreach ($users as $user) 
        {  
      ?>
        <option value="<?=$user->id?>"><?=$user->first_name.' '.$user->last_name?></option>
      <?php
        } 
      ?>
    </select>
    <span id="err_delivered_by" class="error invalid-feedback"></span>
  </div>
</div>
<br/>
<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Product</th>
          <th>Ordered</th>
          <th>Delivered </th>
          <th width="15%">Delivering now</th>
        </tr>
      </thead>
      <tbody id="product_delivery_item_body">
        <?php 
          foreach ($sale_items as $item) 
          { 
            $ordered_quantity   = $item->quantity;
            $delivered_quantity = $this->sale_delivery_model->get_total_quantity_of_sale_item_delivered($item->sale_id,$item->product_id); 
            $warehouse_product  = $this->warehouse_products_model->get_single_record($item->warehouse_product_id);
        ?>
          <tr>
            <td>
              <?=$item->product_name?>
              <input type="hidden" name="warehouse_product_id" value="<?=$warehouse_product->id?>">    
            </td>
            <td><?=$item->quantity?></td>
            <td><?=$delivered_quantity?></td>
            <td>
              <?php
                if($ordered_quantity != $delivered_quantity)
                {
              ?>
                <input type="number" 
                  name="quantity" 
                  id="quantity_<?=$item->product_id?>" 
                  value="<?=$ordered_quantity-$delivered_quantity?>" 
                  max="<?=$ordered_quantity-$delivered_quantity?>" 
                  step="0.01"  
                  data-product_id="<?=$item->product_id?>" 
                  data-cost="<?=$item->cost?>" 
                  data-price="<?=$item->price?>"
                  data-selling_price="<?=$item->selling_price?>"
                
                  placeholder="<?=$this->lang->line('quantity')?>" 
                  class="form-control field_validation">
                <span id='err_quantity_<?=$item->product_id?>' class="error invalid-feedback"></span>
              <?php
                } 
              ?>
            </td>
          </tr>
        <?php
          } 
        ?>
      </tbody>
    </table>    
  </div>
</div>
