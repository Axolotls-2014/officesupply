<table class="table table-bordered table-striped">
	<thead>
	  <tr>
    	<th>SL</th>
    	<th>HSN</th>
    	<th>Description</th>
    	<th>UOM</th>
    	<th>QTY</th>
    	<th>Total Value</th>
    	<th>Rate</th>
    	<th>Taxable Value</th>
    	<th>IGST</th>
    	<th>CGST</th>
    	<th>SGST</th>
    </tr>
	</thead>
	<tbody>
	  <?php

	  	$total_amount 				= 0.0;
	  	$total_taxable_value 	= 0.0;
	  	$total_igst_tax 			= 0.0;
	  	$total_cgst_tax 			= 0.0;
	  	$total_sgst_tax				= 0.0;
	  	$total_quantity				= 0.0;
	  	
	    if(sizeof($purchase_items) > 0)
	    {
	    	$i = 1;
	      foreach ($purchase_items as $value) 
	      {
	      		$total_quantity += $value->total_quantity;
	      		$total_amount 	+= $value->total_amount;
	      		$total_taxable_value += $value->taxable_value;
	      		$total_igst_tax += $value->igst_tax;
	      		$total_cgst_tax += $value->cgst_tax;
	      		$total_sgst_tax += $value->sgst_tax;
	  ?>
				  <tr>                        
				    <td><?=$i++?></td>
			    	<td><?=$value->hsn?></td>
			    	<td></td>
			    	<td><?=$value->uom?></td>
			    	<td><?=$value->total_quantity?></td>
			    	<td><?=number_format_i($value->total_amount)?></td>
			    	<td><?=$value->tax_rate?></td>
			    	<td><?=number_format_i($value->taxable_value)?></td>
			    	<td><?=number_format_i($value->igst_tax)?></td>
			    	<td><?=number_format_i($value->cgst_tax)?></td>
			    	<td><?=number_format_i($value->sgst_tax)?></td>
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
	<tfoot class="bg-gray disabled footer_data">
		<tr>
	    <th colspan="4"></th>
	    <th><?=number_format_i($total_quantity)?></th>
	    <th><?=number_format_i($total_amount)?></th>
	    <th></th>
	    <th><?=number_format_i($total_taxable_value)?></th>
	    <th><?=number_format_i($total_igst_tax)?></th>
	    <th><?=number_format_i($total_cgst_tax)?></th>
	    <th><?=number_format_i($total_sgst_tax)?></th>
	  </tr>
	</tfoot>
</table>