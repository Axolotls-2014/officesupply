<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('tax_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('tax_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('tax_list')?></h3>
              <?php 
                if($this->permission_model->has_permission('add_tax'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('tax/add')?>" data-tt="tooltip" title="Click here to Add Tax">
                      <i class="fas fa-money-check-alt mr-2"></i><?=$this->lang->line('tax_add')?>
                    </a>
                  </li>
                </ul>
              </div>
              <?php 
                }
              ?>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('tax_name')?></th>
                    <th><?=$this->lang->line('tax_sgst')?></th>
                    <th><?=$this->lang->line('tax_cgst')?></th>
                    <th><?=$this->lang->line('tax_igst')?></th>
                    <th><?=$this->lang->line('tax_status')?></th>
                    <th width="5%"><?=$this->lang->line('tax_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    foreach ($tax as $value) 
                    {
                  ?>
                  <tr>                        
                    <td><?php echo $value->tax_name;?></td>
                    <td><?php echo $value->sgst;?></td>
                    <td><?php echo $value->cgst;?></td>
                    <td><?php echo $value->igst;?></td>
                   
                    <td class="project-state">
                      <?php 
                        if($value->status == 1)
                        {
                          echo '<span class="badge badge-success" style="font-size:12px">Active</span>';
                        }
                        else
                        {
                          echo '<span class="badge badge-danger" style="font-size:12px">Inactive</span>'; 
                        }
                      ?>
                    </td>
                    <td>
                      <table>
                        <tr>
                          <?php 
                            if($this->permission_model->has_permission('edit_tax'))
                            {
                          ?>
                          <td class="p-2">
                            <?php
                              $product_categories = $this->product_category_model->get_records_by_tax_id($value->id);
                              $sale_items         = $this->sale_model->get_sale_item_records_by_tax_id($value->id);
                              $purchase_items     = $this->purchase_model->get_purchase_item_records_by_tax_id($value->id);
                              if($sale_items == null && $purchase_items == null)
                              {
                            ?>
                              <a href="<?php echo base_url('tax/edit/'.$value->id);?>" class="btn btn-info btn-xs" data-tt="tooltip" title="<?=$this->lang->line('tax_edit')?>">
                                <i class="fas fa-edit"></i>
                              </a>
                            <?php
                              } 
                              else
                              {
                            ?>
                              <a href="#" class="btn btn-info btn-xs" data-toggle="modal" data-target="#tax_edit_<?php echo $value->id;?>" data-tt="tooltip" title="<?=$this->lang->line('tax_edit')?>">
                                <i class="fas fa-edit"></i>
                              </a>
                              <div class="example-modal">
                                <div class="modal fade" id="tax_edit_<?php echo $value->id;?>">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header warning-header">
                                        <h4 class="modal-title">
                                          <?php echo $this->lang->line('tax_edit');?>
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
                                            if($sale_items != null)
                                            {
                                          ?>
                                            <li><?=$this->lang->line('due_to_following_reason_sale_item')?></li>
                                          <?php
                                            }
                                          ?>

                                          <?php 
                                            if($purchase_items != null)
                                            {
                                          ?>
                                            <li><?=$this->lang->line('due_to_following_reason_purchase_item')?></li>
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
                          <?php 
                            if($this->permission_model->has_permission('delete_tax'))
                            {
                          ?>
                            <td class="p-2">
                              
                              <a href="#"  data-toggle="modal" data-target="#tax_<?php echo $value->id;?>" data-tt="tooltip" title="<?=$this->lang->line('tax_delete')?>" class="btn btn-danger btn-xs">
                                <i class="fas fa-trash"></i>
                              </a>

                              <?php
                                if($product_categories != null || $sale_items != null || $purchase_items != null)
                                {
                              ?>
                                <div class="example-modal">
                                  <div class="modal fade" id="tax_<?php echo $value->id;?>">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header warning-header">
                                          <h4 class="modal-title">
                                            <?php echo $this->lang->line('tax_delete');?>
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
                                              if($product_categories != null)
                                              {
                                            ?>
                                              <li><?=$this->lang->line('due_to_following_reason_product_category_exist')?></li>
                                            <?php
                                              }
                                            ?>

                                            <?php 
                                              if($sale_items != null)
                                              {
                                            ?>
                                              <li><?=$this->lang->line('due_to_following_reason_sale_item')?></li>
                                            <?php
                                              }
                                            ?>

                                            <?php 
                                              if($purchase_items != null)
                                              {
                                            ?>
                                              <li><?=$this->lang->line('due_to_following_reason_purchase_item')?></li>
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
                                  <div class="modal fade" id="tax_<?php echo $value->id;?>">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header failure-header">
                                          <h4 class="modal-title">
                                            <?php echo $this->lang->line('tax_delete');?>
                                          </h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          <p>
                                            <?php echo $this->lang->line('tax_delete_message') .$value->tax_name."?";?>
                                          </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                            <!-- Close -->
                                              <?php echo $this->lang->line('btn_modal_close');?>
                                            </button>
                                            <form action="<?php echo base_url('tax/delete');?>" method="POST">
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
                    <th><?=$this->lang->line('tax_name')?></th>
                    <th><?=$this->lang->line('tax_sgst')?></th>
                    <th><?=$this->lang->line('tax_cgst')?></th>
                    <th><?=$this->lang->line('tax_igst')?></th>
                    <th><?=$this->lang->line('tax_status')?></th>
                    <th><?=$this->lang->line('tax_action')?></th>
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
