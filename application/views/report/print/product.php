<style type="text/css">
	th td{
		padding: 4px;
	}
	.footer_data{
		font-size: 10px;
		background-color: #dee2e6;
	}
</style>
<table width="100%" style="text-align: center;font-size: 10px;">
	<tr>
		<td>
			<?=$company_setting->company_name?>
		</td>
	</tr>
	<tr>
		<td>
			<?=$company_setting->address_line1.', '.$company_setting->address_line2.', '.$company_setting->city_name.', '.$company_setting->state_name.', state_name'.$company_setting->country_name.'. - '.$company_setting->pincode ?>
		</td>
	</tr>
	<tr>
		<td>
			<?= $this->lang->line('print_email').': '.$company_setting->email.', '.$this->lang->line('print_mobile').'.: '.$company_setting->mobile.'. '?>
		</td>
	</tr>
	<tr>
		<td>
			<?=$this->lang->line('product_report')?>
		</td>
	</tr>
	<!-- <tr>
		<td>
			<?=$this->lang->line('print_from_date').': '.$from_date.' - '.$this->lang->line('print_to_date').': '.$to_date?>
		</td>
	</tr> -->
</table>
<table border="1" style="text-align: center;font-size: 8px" width="100%" cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th>SL</th>
      <th>Product Name</th>

      <th>PO Ordered Qty (Box)</th>
      <th>PO Free Qty (Box)</th>

      <th>Purchase Qty</th>
      <th>Purchase Invoice</th>

      <th>Sale Ordered Qty (Box)</th>
      <th>Sale Free Qty (Box)</th>

      <th>Sales Qty</th>
      <th>Sales Invoice</th>
      <th>Available Qty</th>
      <th>Challan Qty</th>
      <th>Challan Invoice</th>
      <th>Stock(IN) Qty</th>
      <th>Stock(OUT) Qty</th>

      <th>Sale return Qty (Box)</th>
      <th>Sale return Free Qty (Box)</th>

      <th>Sales Return Qty</th>
      <th>Sales R. Invoice</th>

      <th>Purchase return Qty (Box)</th>
      <th>Purchase return Free Qty (Box)</th>

      <th>Purchase Return Qty</th>
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
						<td><?=$value->purchased_free_quantity?></td>
            
			    	<td>
			    		<span class="text-danger"><?=$value->purchase_quantity-$value->purchase_delivered_quantity?></span> | 
		    			<span class="text-success"><?=$value->purchase_delivered_quantity?></span>				
			    	</td>
			    	<td><?=$value->purchase_invoice?></td>

            <td><?=$value->sold_quantity?></td>
						<td><?=$value->sold_free_quantity?></td>

			    	<td>
			    		<span class="text-danger"><?=$value->sale_quantity-$value->sale_delivered_quantity?></span> | 
		    			<span class="text-success"><?=$value->sale_delivered_quantity?></span>			
			    	</td>
			    	<td><?=$value->sale_invoice?></td>
			    	<td><?=$value->available_quantity?></td>
			    	<td><?=$value->challan_quantity?></td>
            <td><?=$value->challan_invoice?></td>
			    	<td><?=$value->stockin_quantity?></td>
			    	<td><?=$value->stockout_quantity?></td>

            <td><?=$value->sold_return_quantity?></td>
						<td><?=$value->sold_return_free_quantity?></td>

			    	<td>
			    		<span class="text-danger"><?=$value->sales_return_quantity-$value->sales_return_delivered_quantity?></span> | 
		    			<span class="text-success"><?=$value->sales_return_delivered_quantity?></span>		
			    	</td>
			    	<td><?=$value->sales_return_invoice?></td>

            <td><?=$value->purchased_return_quantity?></td>
						<td><?=$value->purchased_return_free_quantity?></td>

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
<script>window.print();</script>