<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered">
      <tr>
        <th>Product</th>
        <th>Ordered</th>
        <th>Delivered </th>
        <th width="15%">Quantity</th>
      </tr>
      <?php 
        foreach ($purchase_items as $item) 
        { 
      ?>
      <tr>
        <td><?=$item->product_name?></td>
        <td><?=$item->quantity?></td>
        <td>1</td>
        <td>
          1
        </td>
      </tr>
      <?php
        } 
      ?>
    </table>    
  </div>
</div>
