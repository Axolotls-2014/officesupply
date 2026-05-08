<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_discount')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('discount_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('discount_list')?></h3>

              <?php
                if($this->permission_model->has_permission('add_discount'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('discount/add')?>" data-tt="tooltip" title="Click here to Add Discount">
                      <i class="fas fa-money-check-alt mr-2"></i><?=$this->lang->line('discount_add')?>
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
                    <th><?=$this->lang->line('discount_name')?></th>
                    <th><?=$this->lang->line('discount_type')?></th>
                    <th><?=$this->lang->line('discount_value')?></th>
                    <th><?=$this->lang->line('discount_valid_from')?></th>
                    <th><?=$this->lang->line('discount_valid_to')?></th>
                    <th><?=$this->lang->line('discount_description')?></th>
                    <th><?=$this->lang->line('discount_status')?></th>
                    <th><?=$this->lang->line('discount_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    foreach ($discount as $value) 
                    {
                  ?>
                  <tr>                        
                    
                    <td><?php echo $value->name;?></td>
                    <td>
                      <?php 
                        if($value->type == 1)
                        {
                          echo 'Percentage(%)';
                        }
                        else
                        {
                          echo 'Fixed'; 
                        }
                      ?>
                      
                    </td>
                    <td><?php echo $value->value;?></td>
                    <td><?php echo date('d-m-Y', strtotime($value->valid_from));;?></td>
                    <td><?php echo date('d-m-Y', strtotime($value->valid_to));;?></td>
                    <td><?php echo $value->description;?></td>
                    <td>
                      <?php 
                        if($value->status == 1)
                        {
                      ?>
                          <span class="badge badge-success" style="font-size: 12px;">Active</span>
                      <?php
                        }
                        else
                        {
                      ?>
                          <span class="badge badge-danger" style="font-size: 12px;">Inactive</span>
                      <?php 
                        }
                      ?>
                    </td>
                    <td>

                      <table>
                        <tr>
                          <td class="p-2">
                            <?php 
                              if($this->permission_model->has_permission('edit_discount'))
                              { 
                            ?>
                            <a href="<?php echo base_url('discount/edit/'.$value->id);?>" class="btn btn-info btn-xs" data-tt="tooltip" title="<?=$this->lang->line('discount_edit')?>">
                              <i class="fas fa-edit"></i>
                            </a>
                            <?php 
                              }
                            ?>      
                          </td>
                          <td class="p-2">
                            <?php 
                              if($this->permission_model->has_permission('delete_discount'))
                              { 
                            ?>
                            <a href="#"  data-toggle="modal" data-target="#mymodal_<?php echo $value->id;?>" data-tt="tooltip" title="<?=$this->lang->line('discount_delete')?>" class="btn btn-danger btn-xs delete_product">
                              <i class="fas fa-trash"></i>
                            </a>
                              <?php
                                if($this->sale_model->get_sale_item_records_by_discount_id($value->id) == null)
                                {
                              ?>
                                <div class="example-modal">
                                  <div class="modal fade" id="mymodal_<?php echo $value->id;?>">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header failure-header">
                                           <h4 class="modal-title">
                                            <?php echo $this->lang->line('discount_delete');?>
                                          </h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          <p>
                                            <?php echo $this->lang->line('discount_delete_message') .$value->name."?";?>
                                          </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                            <!-- Close -->
                                              <?php echo $this->lang->line('btn_modal_close');?>
                                            </button>
                                            <form action="<?php echo base_url('discount/delete');?>" method="POST">
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
                                else
                                {
                              ?>
                                <div class="example-modal">
                                  <div class="modal fade" id="mymodal_<?php echo $value->id;?>">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header warning-header">
                                           <h4 class="modal-title">
                                            <?php echo $this->lang->line('discount_delete');?>
                                          </h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          <h6><?=$this->lang->line('can_not_delete_the_discount')?></h6>
                                          <ul>
                                            <li><?=$this->lang->line('discount_used_in_sale')?></li>
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
                              ?>
                            <?php 
                              }
                            ?>      
                          </td>
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
                    <th><?=$this->lang->line('discount_name')?></th>
                    <th><?=$this->lang->line('discount_type')?></th>
                    <th><?=$this->lang->line('discount_value')?></th>
                    <th><?=$this->lang->line('discount_valid_from')?></th>
                    <th><?=$this->lang->line('discount_valid_to')?></th>
                    <th><?=$this->lang->line('discount_description')?></th>
                    <th><?=$this->lang->line('discount_status')?></th>
                    <th><?=$this->lang->line('discount_action')?></th>
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
