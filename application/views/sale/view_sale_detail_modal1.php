<?php
  //$sale_id      $this->input->post('reference_no')
  $sale_items = $this->sale_model->get_sales_detail($sale_id);

?>


<div class="example-modal">
  <div class="modal fade" id="view_sale_detail_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header  failure-header">
           <h4 class="modal-title">
            View Sale Details
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-bordered">
                <tr>
                  <th>Product Name</th>
                  <!-- <th>Total taxable Value</th>
                  <th>Total Tax </th> -->
                 
                </tr>
                <?php 
                  foreach ($sale_items as $item) 
                  { 
                ?>
                <tr>
                  <td><?=$item->product_name?></td>
               
                </tr>
                <?php
                  } 
                ?>
              </table>    
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
          
        </div>
      </div>
    </div>
  </div>
</div>
