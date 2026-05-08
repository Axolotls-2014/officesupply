<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_inventory')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_product')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('product_list')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('product_list')?></h3>
              
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('product/add')?>" data-tt="tooltip" title="Click here to Add product">
                      <i class="fas fa-dice-d20 mr-2"></i><?=$this->lang->line('product_add')?>
                    </a>
                  </li>
                </ul>
              </div>
              
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('product_name')?></th>
                    <th><?=$this->lang->line('product_description')?></th>
                    <th><?=$this->lang->line('product_category_name')?></th>
                    <th><?=$this->lang->line('product_hsn')?></th>
                    <th><?=$this->lang->line('product_markup')?></th>
                    <th><?=$this->lang->line('product_uom')?></th>
                    <th><?=$this->lang->line('quantity')?></th>
                    <th width="5%"><?=$this->lang->line('product_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    foreach ($product as $value) 
                    {
                  ?>
                  <tr>                        
                    
                    <td><?php echo $value->name;?></td>
                    <td><?php echo $value->description;?></td>
                    <td><?php echo $value->product_category_name;?></td>
                    <td><?php echo $value->hsn;?></td>
                    <td><?php echo $value->markup;?></td>
                    <td><?php echo $value->uom_name;?></td>
                    <td>
                      <?php 
                        $products = $this->warehouse_products_model->get_records_by_product_id($value->id);
                        $product_quantity = 0;

                        if(sizeof($products) > 0)
                        {
                          foreach ($products as $product) {
                            $product_quantity += $product->quantity; 
                          } 
                        }

                        echo $product_quantity;
                      ?>
                    </td>
                    <td>   
                      <table>
                        <tr>
                          <td class="p-2">
                            <a href="#" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#warehouse_wise_product" data-tt="tooltip" title="<?=$this->lang->line('product_quantity_warehouse_wise')?>" data-product_id="<?=$value->id?>">
                              <i class="fas fa-cubes"></i>
                            </a>
                          </td>
                          <?php 
                            if($this->permission_model->has_permission('edit_product'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="<?php echo base_url('product/edit/'.$value->id);?>" class="btn btn-info btn-xs" data-tt="tooltip" title="<?=$this->lang->line('product_edit')?>">
                              <i class="fas fa-edit"></i>
                            </a>        
                          </td>
                          <?php 
                            }
                          ?>
                          
                          <?php 
                            if($this->permission_model->has_permission('delete_product'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="#"  data-toggle="modal" data-target="#delete_product_<?php echo $value->id;?>" data-tt="tooltip" title="<?=$this->lang->line('product_delete')?>" class="btn btn-danger btn-xs delete_product">
                              <i class="fas fa-trash"></i>
                            </a>

                            <?php 
                              $sales        = $this->sale_model->get_sale_item_records_by_product_id($value->id);
                              $quotations   = $this->quotation_model->get_quotation_item_records_by_product_id($value->id);
                              $purchases    = $this->purchase_model->get_purchase_item_records_by_product_id($value->id);

                              if(sizeof($sales) > 0 || sizeof($quotations) > 0 || sizeof($purchases) > 0)
                              {
                            ?>
                              <div class="example-modal">
                                <div class="modal fade" id="delete_product_<?php echo $value->id;?>">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header  warning-header">
                                        <h4 class="modal-title">
                                          <?php echo $this->lang->line('product_delete');?>
                                        </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <p>
                                          <?=$this->lang->line('due_to_following_reason')?>
                                        </p>
                                        <ul style="list-style: disc;">
                                          <?php 
                                            if($sales != null)
                                            {
                                          ?>
                                            <li><?=$this->lang->line('due_to_following_reason_sale_items_exist')?></li>
                                          <?php
                                            }
                                          ?>

                                          <?php 
                                            if($purchases != null)
                                            {
                                          ?>
                                            <li><?=$this->lang->line('due_to_following_reason_purchase_items_exist')?></li>
                                          <?php
                                            }
                                          ?>

                                          <?php 
                                            if($quotations != null)
                                            {
                                          ?>
                                            <li><?=$this->lang->line('due_to_following_reason_quotation_items_exist')?></li>
                                          <?php
                                            }
                                          ?>
                                        </ul>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">
                                          <!-- Close -->
                                            <?php echo $this->lang->line('btn_modal_close');?>
                                          </button>
                                      </div>
                                    </div>
                                    <!-- /.modal-content -->
                                  </div>
                                  <!-- /.modal-dialog -->
                                </div>
                              </div>
                            <?php
                              } 
                              else
                              {
                            ?>
                              <div class="example-modal">
                                <div class="modal fade" id="delete_product_<?php echo $value->id;?>">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header  failure-header">
                                        <h4 class="modal-title">
                                          <?php echo $this->lang->line('product_delete');?>
                                        </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <p>
                                          <?php echo $this->lang->line('product_delete_message') .$value->name."?";?>
                                        </p>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">
                                          <!-- Close -->
                                            <?php echo $this->lang->line('btn_modal_close');?>
                                          </button>
                                          <form action="<?php echo base_url('product/delete');?>" method="POST">
                                            <input type="hidden" name="id" value="<?=$value->id?>">
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                            <button type="submit" name="submit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
                                          </form>
                                      </div>
                                    </div>
                                    <!-- /.modal-content -->
                                  </div>
                                  <!-- /.modal-dialog -->
                                </div>
                              </div>
                            <?php
                              }
                            ?>
                                  
                          </td>
                          <?php 
                            }
                          ?>
                        </tr>
                      </table>                  
                    </td>
                  </tr>
                  <?php  
                    }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('product_name')?></th>
                    <th><?=$this->lang->line('product_description')?></th>
                    <th><?=$this->lang->line('product_category_name')?></th>
                    <th><?=$this->lang->line('product_hsn')?></th>
                    <th><?=$this->lang->line('product_markup')?></th>
                    <th><?=$this->lang->line('product_uom')?></th>
                    <th><?=$this->lang->line('quantity')?></th>
                    <th><?=$this->lang->line('product_action')?></th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php $this->load->view('layout/footer');?>

<div class="example-modal">
  <div class="modal fade" id="warehouse_wise_product">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header  info-header">
          <h4 class="modal-title">
            <?php echo $this->lang->line('product_quantity_warehouse_wise');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body warehouse_wise_product_data">
          
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

<script type="text/javascript">
  $(document).ready(function(e){
    $('#warehouse_wise_product').on('show.bs.modal', function (e) {
      
      var product_id = $(e.relatedTarget).data('product_id');
      
      $.ajax({
        url: "<?php echo base_url('product/warehouse_wise_product_quantity')?>",
        type: "POST",
        data: {
            'product_id':product_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
        dataType: "JSON",
        success: function(data){

          $('.warehouse_wise_product_data').html(data.product_quantity_data);

        }
      });
    });

  });
</script>