<table class="sticky-header-table table table-bordered table-striped">
	<thead>
	  <tr>
      <th>SL</th>
      <th>Product Name</th>
      <th>Total invoice</th>
      <th>Total qty</th>
      <th>Total Free</th>
      <th>OverAll Qty</th>
      <th>Taxable Amount</th>
      <th>Total Tax</th>
      <th>No of customer</th>
      <th>Repeat Customers</th>
      <th>Delivered</th>
	  </tr>
	</thead>
	<tbody>
	  <?php

	  	// $total_taxable_value 	= 0.0;
	  	// $total_discount 			= 0.0;
	  	// $total_tax 						= 0.0;
	  	// $total 								= 0.0;
	  	// $total_profit					= 0.0;
	  	// $total_igst 					= 0.0;
	  	// $total_cgst 					= 0.0;
	  	// $total_sgst 					= 0.0;
	  	// $total_tds  					= 0.0;

	    if(sizeof($sale_items) > 0)
	    {
	    	$i = 1;
	      foreach ($sale_items as $value) 
	      {
	      	
	      	// $total_taxable_value 	+= $value->total_taxable_value;
	      	// $total_discount 			+= $value->total_discount;
	      	// $total_tax 						+= $value->total_tax;
	      	// $total 								+= $value->total;
	      	// // $total_profit 				+= $total-$total_tax;
	      	// $total_tds 						+= $value->tds;

	      	// $sale = $this->sale_model->get_sale_tax_individual_and_tot_cost_and_total_price($value->id);


	      	// $total_igst 					+= $sale->igst_tax;
	      	// $total_cgst 					+= $sale->cgst_tax;
	      	// $total_sgst 					+= $sale->sgst_tax;

	      	// $total_profit 				+= $sale->total_price-$sale->total_cost;

	      	//$delivered_quantity = $this->sale_delivery_model->get_total_quantity_of_sale_item_delivered($value->sale_id,$value->warehouse_product_id);

	  ?>
				  <tr>                        
				    <td><?=$i++?></td>
			    	<td><?=$value->name?></td>
            <td><?=$value->total_no_of_invoice?></td>
            <td><?=$value->quantity?></td>
			    	<td><?=$value->free_quantity?></td>
			    	<td><?=$value->total_quantity?></td>
			    	<td><?=number_format_i($value->taxable_amount)?></td>
			    	<td><?=number_format_i($value->total_tax)?></td>
            <td><?=$value->total_no_of_customers?></td>
            <td><?=$value->repeat_customers?></td>
			    	
			    	<td>
			    		<?php 
			    			if($value->sold_quantity == $value->delivered_quantity)
			    				echo '<span class="badge badge-success">DELIEVERED</span>';
			    			else if(($value->sold_quantity > $value->delivered_quantity) && $value->delivered_quantity > 0)
			    				echo '<span class="badge badge-warning">PARTIAL</span>';
			    			else
			    				echo '<span class="badge badge-danger">NOT DELIVERED</span>';
			    		?>
			    	</td>
				  </tr>
	  <?php  
	      }
	    }
	    else
	    {
	  ?>
	  <tr>
	    <td colspan="11"><?=$this->lang->line('no_records_available')?></td>
	  </tr>
	  <?php
	    }
	  ?>
	</tbody>

</table>