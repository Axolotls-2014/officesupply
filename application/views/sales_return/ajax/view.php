<table class="table table-striped">
  <tr>
    <td width="20%"><label><?=$this->lang->line('sales_return_date')?></label></td>
    <td width="5%"> : </td>
    <td width="25%"><?=date('d-m-Y', strtotime($sales_return->sales_return_date)) ?></td>
  
    <td width="20%"><label><?=$this->lang->line('sales_return_reference_no')?></label></td>
    <td width="5%"> : </td>
    <td width="25%"><?=$sales_return->reference_no ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('sales_return_total_discount')?></label></td>
    <td> : </td>
    <td><?=$sales_return->total_discount ?></td>
    <td><label><?=$this->lang->line('sales_return_customer')?></label></td>
    <td> : </td>
    <td><?=$sales_return->customer_name ?></td>
    
    
  </tr>
  <tr>
    <td><label><?=$this->lang->line('sales_return_total_taxable_value')?></label></td>
    <td> : </td>
    <td><?=$sales_return->total_taxable_value ?></td>
    <td><label><?=$this->lang->line('sales_return_total_tax')?></label></td>
    <td> : </td>
    <td><?=$sales_return->total_tax ?></td>

  </tr>
  <tr>
    <td><label><?=$this->lang->line('sales_return_total')?></label></td>
    <td> : </td>
    <td><?=$sales_return->total ?></td>
    <td><label><?=$this->lang->line('sales_return_paid')?></label></td>
    <td> : </td>
    <td><?=($paid_amount == 0) ? '0.00' : $paid_amount?></td>
    
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td><label><?=$this->lang->line('sales_return_due')?></label></td>
    <td> : </td>
    <td>
      <?=$sales_return->total-$paid_amount ?>
      <?php 
        if($sales_return->total > $paid_amount)
        { 
      ?>
      <button type="button" id="make_payment" class="btn btn-warning make_payment float-right" data-transaction_amount="<?=$sales_return->total-$paid_amount?>">
        Make Payment
      </button>
      <?php 
        }
      ?>
    </td>

  </tr>
</table>            