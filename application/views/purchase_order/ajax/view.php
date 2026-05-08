<table class="table table-striped">
  <tr>
    <td width="20%"><label><?=$this->lang->line('purchase_date')?></label></td>
    <td width="5%"> : </td>
    <td width="25%"><?=date('d-m-Y', strtotime($purchase->purchase_date)) ?></td>
  
    <td width="20%"><label><?=$this->lang->line('purchase_reference_no')?></label></td>
    <td width="5%"> : </td>
    <td width="25%"><?=$purchase->reference_no ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('purchase_total_discount')?></label></td>
    <td> : </td>
    <td><?=$purchase->total_discount ?></td>
    <td><label><?=$this->lang->line('purchase_supplier')?></label></td>
    <td> : </td>
    <td><?=$purchase->supplier_name ?></td>
    
    
  </tr>
  <tr>
    <td><label><?=$this->lang->line('purchase_total_taxable_value')?></label></td>
    <td> : </td>
    <td><?=$purchase->total_taxable_value ?></td>
    <td><label><?=$this->lang->line('purchase_total_tax')?></label></td>
    <td> : </td>
    <td><?=$purchase->total_tax ?></td>

  </tr>
  <tr>
    <td><label><?=$this->lang->line('purchase_total')?></label></td>
    <td> : </td>
    <td><?=$purchase->total ?></td>
    <td><label><?=$this->lang->line('purchase_paid')?></label></td>
    <td> : </td>
    <td><?=($paid_amount == 0) ? '0.00' : $paid_amount?></td>
    
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td><label><?=$this->lang->line('purchase_due')?></label></td>
    <td> : </td>
    <td>
      <?=$purchase->total-$paid_amount ?>
      <?php 
        if($purchase->total > $paid_amount)
        { 
      ?>
      <button type="button" id="make_payment" class="btn btn-warning make_payment float-right" data-transaction_amount="<?=$purchase->total-$paid_amount?>">
        Make Payment
      </button>
      <?php 
        }
      ?>
    </td>

  </tr>
</table>            