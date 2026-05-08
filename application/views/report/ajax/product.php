<table class="sticky-header-table table table-bordered table-striped" id="product-data">
	<thead>
    <tr>
      <th>SL</th>
      <th>Product Name</th>

      <th>PO Ordered Qty</th>
      
      <th>Purchase Total Qty</th>
      <th>Purchase Invoice</th>

      <th>Sale Ordered Qty</th>
     

      <th>Sales Total Qty</th>
      <th>Sales Invoice</th>
      <!-- <th>Billed Available Qty</th>
			<th>Billed Available Free Qty</th> -->
      <th>Billed Available Total Qty</th>
     

      <th>Stock(IN) Qty</th>
      <th>Stock(OUT) Qty</th>

      <th>Sale return Qty</th>
    

      <th>Sales Return Total Qty</th>
      <th>Sales R. Invoice</th>

      <th>Purchase return Qty</th>
    

      <th>Purchase Return Total Qty</th>
      <th>Purchase R. Invoice</th>
    </tr>
	</thead>
	<tbody>
	  <?php

	    if(sizeof($products) > 0)
	    {
	    	$i = 1;
	      foreach ($products as $value) 
	      {
	  ?>
				  <tr>                        
				    <td><?=$i++?></td>
			    	<td><?=$value->product_name?></td>

            <td><?=$value->purchased_quantity?></td>
						
            
			    	<td>
			    		<span class="text-danger"><?=$value->purchase_quantity-$value->purchase_delivered_quantity?></span> | 
		    			<span class="text-success"><?=$value->purchase_delivered_quantity?></span>				
			    	</td>
			    	<td><?=$value->purchase_invoice?></td>

            <td><?=$value->sold_quantity?></td>
						

			    	<td>
			    		<span class="text-danger"><?=$value->sale_quantity-$value->sale_delivered_quantity?></span> | 
		    			<span class="text-success"><?=$value->sale_delivered_quantity?></span>			
			    	</td>
			    	<td><?=$value->sale_invoice?></td>

			    	<td><?=$value->available_quantity?></td>
			   

			    	<td><?=$value->stockin_quantity?></td>
			    	<td><?=$value->stockout_quantity?></td>

            <td><?=$value->sold_return_quantity?></td>
					

			    	<td>
			    		<span class="text-danger"><?=$value->sales_return_quantity-$value->sales_return_delivered_quantity?></span> | 
		    			<span class="text-success"><?=$value->sales_return_delivered_quantity?></span>		
			    	</td>
			    	<td><?=$value->sales_return_invoice?></td>

            <td><?=$value->purchased_return_quantity?></td>
						

			    	<td>
		    			<span class="text-danger"><?=$value->purchase_return_quantity-$value->purchase_return_delivered_quantity?></span> | 
		    			<span class="text-success"><?=$value->purchase_return_delivered_quantity?></span>
			    	</td>
			    	<td><?=$value->purchase_return_invoice?></td>
				  </tr>
	  <?php  
	      }
	    }
	    else
	    {
	  ?>
	  <tr>
	    <td colspan="13"><?=$this->lang->line('no_records_available')?></td>
	  </tr>
	  <?php
	    }
	  ?>
	</tbody>
</table>