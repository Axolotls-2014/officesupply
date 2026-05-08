<table id="example" class=" sticky-header-table table table-bordered table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th><?=$this->lang->line('pack_slip_reference_no')?></th>
        <th>Product name</th>
        <th>Pack</th>
        <th>Packing type</th>
        <th>Batch no</th>
        <th>Expiry date</th>
        <!-- <th><?=$this->lang->line('pack_slip_sale_invoice_no')?></th> -->
        <th><?=$this->lang->line('pack_slip_total_amount')?></th>
        <!-- <th><?=$this->lang->line('pack_slip_total_product')?></th> -->
        <th><?=$this->lang->line('pack_slip_total_quantity')?></th>
        <th><?=$this->lang->line('pack_slip_total_free_quantity')?></th>
        <th><?=$this->lang->line('pack_slip_date')?></th>

      </tr>
    </thead>
    <tbody>
	  <?php
	    if(sizeof($pack_slip_items) > 0)
	    {
	    	$i = 1;
	      foreach ($pack_slip_items as $item){ ?>
			  <tr>
				<td><?=$i++?></td>
				<td><?=$item->reference_no?></td>
				<td><?=$item->product_name?></td>
				<td><?=$item->pack?></td>
				<td><?=$item->packing_type?></td>
				<td><?=$item->batch_no?></td>
				<td><?=date('d-m-Y', strtotime($item->expiry_date))?></td>
				<td><?=$item->sub_total?></td>
				<td><?=$item->total_quantity?></td>
				<td><?=$item->free_quantity?></td>
				<td><?=date('d-m-Y', strtotime($item->pack_slip_date))?></td>
			</tr>
	  <?php  

	      }

	    }

	    else

	    {

	  ?>

	  <tr>

	    <td colspan="11">Please click on Search button to get records</td>

	  </tr>

	  <?php

	    }

	  ?>

	</tbody>
</table>