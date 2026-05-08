<table id="example1" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Product Id</th>
      <th>User Id</th>
      <th>Productname</th>
      <th>Price</th>
      <th>productcode</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      foreach ($products as $product) 
      {
    ?>
    <tr>
      <td><?php echo $product->id;?></td>
      <td><?php echo $product->username;?></td>
      <td><?php echo $product->product_name;?></td>
      <td><?php echo $product->price;?></td>
      <td><?php echo $product->product_code;?></td>
      <td class="text-left py-0 align-middle">
        <div class="btn-group btn-group-sm">
          <a href="<?php echo base_url('product/edit/'.base64_encode($product->id));?>" class="btn btn-info">
            <i class="fas fa-edit"></i>
          </a> &nbsp;&nbsp;
          <a href="#"  data-toggle="modal" data-target="#edit_product_modal" data-id="<?php echo $product->id;?>" class="btn btn-default">
            <i class="fas fa-pen-square"></i>
          </a> &nbsp;&nbsp;
          <a href="#"  data-toggle="modal" data-target="#mymodal_<?php echo $product->id;?>" data-tt="tooltip" title="Delete" class="btn btn-danger delete_product">
            <i class="fas fa-trash"></i>
          </a>

          <div class="example-modal">
            <div class="modal fade" id="mymodal_<?php echo $product->id;?>">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4>
                      <?php echo $this->lang->line('lbl_cust_delete_modal');?>
                    </h4>
                  </div>
                  <div class="modal-body">
                    <p>
                      <?php echo "Are you sure want to delete this service: ".$product->product_code."?";?>
                    </p>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">
                      <!-- Close -->
                        <?php echo $this->lang->line('btn_modal_close');?>
                      </button>
                      <a href="<?php echo base_url('product/delete/'.$product->id);?>" class="btn btn-danger">
                      <!-- Delete -->
                      <?php echo $this->lang->line('btn_modal_delete');?>
                      </a>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
          </div>
        </div>
      </td>
    </tr>
    <?php  
      }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <th>Product Id</th>
      <th>User Id</th>
      <th>Productname</th>
      <th>Price</th>
      <th>productcode</th>
      <th>Action</th>
    </tr>
  </tfoot>
</table>