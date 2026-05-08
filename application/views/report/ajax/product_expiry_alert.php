<table class="sticky-header-table table table-bordered table-striped" id="product-data">
	<thead>
  <tr>
    <th>SL</th>
    <th>PID</th>
    <th>Product Name</th>
    <th>Product Description</th>
    <th>Batch No</th>
    <th>Quantity</th>
    <th>Mfg Date</th>
    <th>Expiry Date</th>
  </tr>
	</thead>
  <tbody>
    <?php

      if(sizeof($warehouse_products) > 0)
      {
        $i = 1;
        foreach ($warehouse_products as $value) 
        {
          
    ?>
    <tr>  
      <td><?=$i++?></td>
      <td><?=$value->pid;?></td>                      
      <td><?=$value->product_name;?></td>
      <td><?=$value->product_description;?></td>
      <td><?=$value->batch_no;?></td>
      <td><?=number_format($value->quantity, 2);?></td>
      <td>
        <?=($value->mfg_date != '' && $value->mfg_date != '0000-00-00') ? date('d-m-Y',strtotime($value->mfg_date)) : '' ?>
      </td>
      <td>
        <?=($value->expiry_date != '' && $value->expiry_date != '0000-00-00') ? date('d-m-Y',strtotime($value->expiry_date)) : '' ?>
      </td>
    
    </tr>
    <?php  
        }
      }
      else
      {
    ?>
    <tr>
      <td colspan="7"><?=$this->lang->line('no_records_available')?></td>
    </tr>
    <?php
      }
    ?>
	</tbody>
</table>