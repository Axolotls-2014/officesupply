<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_people')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('customer')?>"><?=$this->lang->line('header_customer')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('customer_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('customer_list')?></h3>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('customer/add')?>"><i class="fas fa-user-friends mr-2"></i><?=$this->lang->line('customer_add')?></a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('customer_name')?></th>
                    <th><?=$this->lang->line('customer_gstin')?></th>
                    <th><?=$this->lang->line('customer_email')?></th>
                    <th><?=$this->lang->line('customer_phone')?></th>
                    <th><?=$this->lang->line('customer_country_id')?></th>
                    <th><?=$this->lang->line('customer_state_id')?></th>
                    <th><?=$this->lang->line('customer_address')?></th>
                    <th><?=$this->lang->line('customer_city_id')?></th>
                    <th><?=$this->lang->line('customer_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    foreach ($customer as $value) 
                    {
                  ?>
                  <tr>                        
                    
                    <td><?php echo $value->customer_name;?></td>
                    <td><?php echo $value->gstin;?></td>
                    <td><?php echo $value->email;?></td>
                    <td><?php echo $value->phone;?></td>
                    <td><?php echo $value->country_name;?></td>
                    <td><?php echo $value->state_name;?></td>
                    <td><?php echo $value->address;?></td>
                    <td><?php echo $value->city_name;?></td>
                    
                    <td>
                      <table>
                        <tr>
                          <?php 
                            if($this->permission_model->has_permission('view_customer'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="<?php echo base_url('customer/view/'.$value->id);?>" class="btn btn-default btn-xs">
                              <i class="fas fa-eye"></i>
                            </a>
                          </td>
                          <?php 
                            }
                          ?>

                          <?php 
                            if($this->permission_model->has_permission('edit_customer'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="<?php echo base_url('customer/edit/'.$value->id);?>" class="btn btn-info btn-xs" data-tt="tooltip" title="">
                              <i class="fas fa-edit"></i>
                            </a>      
                          </td>
                          <?php 
                            }
                          ?>

                          <?php 
                            if($this->permission_model->has_permission('delete_customer'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="#"  data-toggle="modal" data-target="#delete_customer_<?php echo $value->id;?>" data-tt="tooltip" title="Delete" class="btn btn-danger btn-xs delete_product">
                              <i class="fas fa-trash"></i>
                            </a>

                            <?php 
                              $sales        = $this->sale_model->get_sale_records_by_customer_id($value->id);
                              $quotations   = $this->quotation_model->get_quotation_records_by_customer_id($value->id);

                              if(sizeof($sales) > 0 || sizeof($quotations) > 0)
                              {
                            ?>
                                <div class="example-modal">
                                  <div class="modal fade" id="delete_customer_<?php echo $value->id;?>">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header warning-header">
                                          <h4 class="modal-title">
                                            <?php echo $this->lang->line('customer_delete');?>
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
                                              <li><?=$this->lang->line('due_to_following_reason_sale_exist')?></li>
                                            <?php
                                              }
                                            ?>

                                            <?php 
                                              if($quotations != null)
                                              {
                                            ?>
                                              <li><?=$this->lang->line('due_to_following_reason_quotation_exist')?></li>
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
                                  <div class="modal fade" id="delete_customer_<?php echo $value->id;?>">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header failure-header">
                                          <h4 class="modal-title">
                                            <?php echo $this->lang->line('customer_delete');?>
                                          </h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          <p>
                                            <?php echo $this->lang->line('customer_delete_message') .$value->customer_name."?";?>
                                          </p>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">
                                            <?php echo $this->lang->line('btn_modal_close');?>
                                          </button>
                                          <form action="<?php echo base_url('customer/delete');?>" method="POST">
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
                    <th><?=$this->lang->line('customer_name')?></th>
                    <th><?=$this->lang->line('customer_gstin')?></th>
                    <th><?=$this->lang->line('customer_email')?></th>
                    <th><?=$this->lang->line('customer_phone')?></th>
                    <th><?=$this->lang->line('customer_country_id')?></th>
                    <th><?=$this->lang->line('customer_state_id')?></th>
                    <th><?=$this->lang->line('customer_address')?></th>
                    <th><?=$this->lang->line('customer_city_id')?></th>
                    <th><?=$this->lang->line('customer_action')?></th>
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
