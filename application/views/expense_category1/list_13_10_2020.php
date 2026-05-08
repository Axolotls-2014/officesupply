<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_expense_category')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('expense_category_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('expense_category_list')?></h3>
              <?php 
                if($this->permission_model->has_permission('add_expense_category'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('expense_category/add')?>" data-tt="tooltip" title="Click here to Add Expense Category">
                      <i class="fas fa-money-check-alt mr-2"></i><?=$this->lang->line('expense_category_add')?>
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
                    <th><?=$this->lang->line('expense_category_name')?></th>
                    <th><?=$this->lang->line('expense_category_description')?></th>
                    <th><?=$this->lang->line('expense_category_type')?></th>
                    <th><?=$this->lang->line('expense_category_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    foreach ($expense_category as $value) 
                    {
                  ?>
                  <tr>                        
                    
                    <td><?php echo $value->name;?></td>
                    <td><?php echo $value->description;?></td>
                    <td><?php echo $value->account_group;?></td>
                    <td>
                      <table>
                        <tr>
                          <?php 
                            if($this->permission_model->has_permission('edit_expense_category'))
                            {   
                          ?>
                          <td class="p-2">
                            <?php 
                              if($this->expense_model->get_records_by_expense_category_id($value->id) == null)
                              {
                            ?>
                                <a href="<?php echo base_url('expense_category/edit/'.$value->id);?>"  data-tt="tooltip" title="<?=$this->lang->line('expense_category_edit')?>" class="btn btn-info btn-xs">
                                  <i class="fas fa-edit"></i>
                                </a>
                            <?php
                              }
                              else
                              {
                            ?>
                                <a href="#" data-tt="tooltip" title="<?=$this->lang->line('expense_category_edit')?>" data-toggle="modal" data-target="#edit_expense_category_<?php echo $value->id;?>" class="btn btn-info btn-xs">
                                  <i class="fas fa-edit"></i>
                                </a>
                                <div class="example-modal">
                                  <div class="modal fade" id="edit_expense_category_<?php echo $value->id;?>">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header warning-header">
                                          <h4 class="modal-title">
                                            <?php echo $this->lang->line('expense_category_delete');?>
                                          </h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                          
                                        </div>
                                        <div class="modal-body">
                                          <h6><?=$this->lang->line('can_not_delete_the_expense_category')?></h6>
                                          <ul>
                                            <li><?=$this->lang->line('expense_category_used_in_expense')?></li>
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
                          </td>
                          <?php 
                            }
                          ?>
                          <?php 
                            if($this->permission_model->has_permission('delete_expense_category'))
                            {   
                          ?>
                          <td class="p-2">
                            
                            <a href="#"  data-toggle="modal" data-target="#mymodal_<?php echo $value->id;?>" data-tt="tooltip" title="<?=$this->lang->line('expense_category_delete')?>" class="btn btn-danger btn-xs delete_product">
                              <i class="fas fa-trash"></i>
                            </a>
                              <?php
                                if($this->expense_model->get_records_by_expense_category_id($value->id) == null)
                                {
                              ?>
                                <div class="example-modal">
                                  <div class="modal fade" id="mymodal_<?php echo $value->id;?>">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header  failure-header">
                                          <h4 class="modal-title">
                                            <?php echo $this->lang->line('expense_category_delete');?>
                                          </h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                          
                                        </div>
                                        <div class="modal-body">
                                          <p>
                                            <?php echo $this->lang->line('expense_delete_message') .$value->name."?";?>
                                          </p>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">
                                            <?php echo $this->lang->line('btn_modal_close');?>
                                          </button>
                                          <form action="<?php echo base_url('expense_category/delete');?>" method="POST">
                                            <input type="hidden" name="id" value="<?=$value->id?>">
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                            <button type="submit" name="submit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
                                          </form>
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
                                  <div class="modal fade" id="mymodal_<?php echo $value->id;?>">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header warning-header">
                                          <h4 class="modal-title">
                                            <?php echo $this->lang->line('expense_category_delete');?>
                                          </h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                          
                                        </div>
                                        <div class="modal-body">
                                          <h6><?=$this->lang->line('can_not_delete_the_expense_category')?></h6>
                                          <ul>
                                            <li><?=$this->lang->line('expense_category_used_in_expense')?></li>
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
                    <th><?=$this->lang->line('expense_category_name')?></th>
                    <th><?=$this->lang->line('expense_category_description')?></th>
                    <th><?=$this->lang->line('expense_category_type')?></th>
                    <th><?=$this->lang->line('expense_category_action')?></th>
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

