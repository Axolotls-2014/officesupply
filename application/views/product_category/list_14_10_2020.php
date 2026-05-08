<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_inventory')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_product_category')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('product_category_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('product_category_list')?></h3>
              
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('product_category/add')?>" data-tt="tooltip" title="Click here to Add Product Category">
                      <i class="fas fa-shapes mr-2"></i><?=$this->lang->line('product_category_add')?>
                    </a>
                  </li>
                </ul>
              </div>
              
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('product_category_name')?></th>
                    <th><?=$this->lang->line('product_category_description')?></th>
                    <th><?=$this->lang->line('product_category_tax_name')?></th>
                    <th width="5%"><?=$this->lang->line('product_category_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    foreach ($product_category as $value) 
                    {
                  ?>
                  <tr>                        
                    
                    <td><?php echo $value->name;?></td>
                    <td><?php echo $value->description;?></td>
                    <td><?php echo $value->tax_name;?></td>
                    
                    <td>
                      <table>
                        <tr>
                          <?php 
                            if($this->permission_model->has_permission('edit_product_category'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="<?php echo base_url('product_category/edit/'.$value->id);?>" class="btn btn-info btn-xs" data-tt="tooltip" title="<?=$this->lang->line('product_category_edit')?>">
                              <i class="fas fa-edit"></i>
                            </a>      
                          </td>
                          <?php 
                            }
                          ?>

                          <?php 
                            if($this->permission_model->has_permission('delete_product_category'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="#"  data-toggle="modal" data-target="#delete_product_category_<?php echo $value->id;?>" data-tt="tooltip" title="<?=$this->lang->line('product_category_delete')?>" class="btn btn-danger btn-xs delete_product">
                              <i class="fas fa-trash"></i>
                            </a>
                            <?php
                              $products = $this->product_model->get_records_by_product_category($value->id);
                              if($products != null)
                              {
                            ?>
                              <div class="example-modal">
                                <div class="modal fade" id="delete_product_category_<?php echo $value->id;?>">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header  warning-header">
                                         <h4 class="modal-title">
                                          <?php echo $this->lang->line('product_category_delete');?>
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
                                            if($products != null)
                                            {
                                          ?>
                                            <li><?=$this->lang->line('due_to_following_reason_product_exist')?></li>
                                          <?php
                                            }
                                          ?>
                                        </ul>
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
                            <?php
                              } 
                              else
                              {
                            ?>
                              <div class="example-modal">
                                <div class="modal fade" id="delete_product_category_<?php echo $value->id;?>">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header  failure-header">
                                         <h4 class="modal-title">
                                          <?php echo $this->lang->line('product_category_delete');?>
                                        </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <p>
                                          <?php echo $this->lang->line('product_category_delete_message') .$value->name."?";?>
                                        </p>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                          <?php echo $this->lang->line('btn_modal_close');?>
                                        </button>
                                        <form action="<?php echo base_url('product_category/delete');?>" method="POST">
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
                    <th><?=$this->lang->line('product_category_name')?></th>
                    <th><?=$this->lang->line('product_category_description')?></th>
                    <th><?=$this->lang->line('product_category_tax_name')?></th>
                    <th><?=$this->lang->line('product_category_action')?></th>
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