<table class="table table-bordered">
	<thead>
		<tr style="background-color: #D3D3D3">
			<th><?=$this->lang->line('warehouse_name')?></th>
			<th><?=$this->lang->line('quantity')?></th>
			<th><?=$this->lang->line('cost')?></th>
			<th><?=$this->lang->line('price')?></th>
		</tr>
	</thead>
	<tbody>
		<?php

			$total_quantity = 0;
			$total_cost 		= 0;
			$total_price    = 0;

			if(sizeof($product_quantity_data) > 0)
			{
                   
				foreach ($product_quantity_data as $value) 
				{
			    $temp = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost($value->warehouse_id,$value->product_id,$value->cost);
					if($value->quantity > 0)
					{
						$total_quantity += $value->quantity;
						$total_cost 		+= $value->quantity*$value->cost;
						$total_price 		+= $value->quantity*$temp->price;
					}
		?>
		<tr>
			<td><?=$this->warehouse_model->get_single_record($value->warehouse_id)->name?></td>
			<td><?=$value->quantity?></td>
			<td><?=$value->cost?></td>
			<td><?=$temp->price?></td>
		</tr>
		<?php
				}	 
		?>
		<tr style="font-weight: bolder;background-color: #ccc">
			<td>
				<?=$this->lang->line('total');?>
			</td>	
			<td><?=$total_quantity?></td>
			<td><?=$total_cost?></td>
			<td><?=$total_price?></td>
		</tr>
		<?php
			}
			else
			{
		?>
			<tr>
				<td colspan="4"><?=$this->lang->line('no_records_available')?></td>
			</tr>
		<?php
			} 
		?>

	</tbody>
	
</table>