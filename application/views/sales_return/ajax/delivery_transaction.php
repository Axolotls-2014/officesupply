<div class="row">
  <div class="col-md-12">
    <table class="table table-striped">
      <tr>
        <th>Reference No</th>
        <th>Received by</th>
        <th>No of Products Delivered</th>
        <th>Delivery Date</th>
        <th width="20%">Action</th>
      </tr>
      <style type="text/css">
        .view_delivery_transaction{
          cursor: pointer;
        }
      </style>
      <?php
        if(sizeof($sales_return_deliveries) > 0)
        {
          foreach ($sales_return_deliveries as $value) 
          {
            $total_quantity_delivered = $this->sales_return_delivery_model->get_total_no_of_quantity_of_delivery($value->id);
      ?>

            <tr>
              <td class="view_delivery_transaction" data-sales_return_id="<?=$value->sales_return_id?>"><?=$value->id?></td>
              <td class="view_delivery_transaction" data-sales_return_id="<?=$value->sales_return_id?>"><?=strtoupper($value->received_by_first_name.' '.$value->received_by_last_name)?></td>
              <td class="view_delivery_transaction" data-sales_return_id="<?=$value->sales_return_id?>"><?=$total_quantity_delivered?></td>
              <td class="view_delivery_transaction" data-sales_return_id="<?=$value->sales_return_id?>"><?=date('d-m-Y',strtotime($value->delivery_date))?></td>
              <td>
                <a href="#" data-tt="tooltip" title="<?=$this->lang->line('delivery_transaction_delete')?>" class="btn btn-danger btn-xs delete_delivery_transaction">
                  <i class="fas fa-trash"></i>
                </a>

                <span class="delete_delivery_transaction_confirmation delete_delivery_transaction_confirmation_label" style="display: none"><?=$this->lang->line('are_you_sure')?></span>

                <a href="#" data-tt="tooltip" title="<?=$this->lang->line('yes')?>" class="btn btn-info btn-xs delete_delivery_transaction_confirmation delete_delivery_transaction_yes" data-sales_return_delivery_id="<?=$value->id?>" style="display: none">
                  <?=$this->lang->line('yes')?>
                </a>

                <a href="#" data-tt="tooltip" title="<?=$this->lang->line('no')?>" class="btn btn-danger btn-xs delete_delivery_transaction_confirmation delete_delivery_transaction_no" style="display: none">
                  <?=$this->lang->line('no')?>
                </a>
              </td>
            </tr>

            <?php 
                $delivery_items = $this->sales_return_delivery_model->get_delivery_item_records($value->id);
            ?>

            <tr class="view_delivery_transaction_detail">
              <td colspan="5">
                <table class="table table-bordered" width="100%">
                  <tr>
                    <th>Product</th>
                    <th>Delivered Quantity</th>
                  </tr>
                  <?php 
                    foreach ($delivery_items as $item) 
                    { 
                  ?>
                  <tr>
                    <td><?=$item->product_name?></td>
                    <td><?=$item->quantity?></td>
                  </tr>
                  <?php
                    } 
                  ?>
                </table>
              </td>
            </tr>

      <?php
          } 
        }
        else
        {
      ?>
            <tr>
              <td colspan="5">
                No record(s) are available.
              </td>
            </tr>
      <?php
        } 
      ?>
    </table>    
  </div>
</div>
