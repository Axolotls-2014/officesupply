<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_expense')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('supplier_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('supplier_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('supplier_list')?></h3>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('supplier/add')?>"><i class="fas fa-user-friends mr-2"></i><?=$this->lang->line('supplier_add')?></a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('supplier_company_name')?></th>
                    <th><?=$this->lang->line('supplier_gst_registration_type')?></th>
                    <th><?=$this->lang->line('supplier_gstin')?></th>
                    <th><?=$this->lang->line('supplier_email')?></th>
                    <th><?=$this->lang->line('supplier_phone')?></th>
                    <th><?=$this->lang->line('supplier_contact_person_name')?></th>
                    <th><?=$this->lang->line('supplier_contact_person_designation')?></th>
                    <!-- <th><?=$this->lang->line('supplier_address')?></th> -->
                    <th><?=$this->lang->line('supplier_website')?></th>
                    <th width="20%"><?=$this->lang->line('supplier_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    foreach ($suppliers as $value) 
                    {
                  ?>
                  <tr>                        
                    <td><?php echo $value->company_name;?></td>
                    <td>
                      <?php 
                        if($value->gst_registration_type == 0)
                        {
                          echo $this->lang->line('gst_reg_type_not_reg');
                        }
                        else if($value->gst_registration_type == 2)
                        {
                          echo $this->lang->line('gst_reg_type_composite'); 
                        }
                        else if($value->gst_registration_type == 1)
                        {
                          echo $this->lang->line('gst_reg_type_reg'); 
                        }
                      ?>                      
                    </td>
                    <td><?php echo $value->gstin;?></td>
                    <td><?php echo $value->email;?></td>
                    <td><?php echo $value->phone;?></td>
                    <td><?php echo $value->contact_person_name;?></td>
                    <td><?php echo $value->contact_person_designation;?></td>
                    <!-- <td><?php echo $value->address.', '.$value->city_name.', '.$value->state_name.', '.$value->country_name;?></td> -->
                    <td><?php echo $value->website;?></td>
                    <td>
                      <table>
                        <tr>
                          <?php 
                            if($this->permission_model->has_permission('view_supplier'))
                            {
                          ?>
                          <td class="p-2 m-2">
                            <a href="<?php echo base_url('supplier/view/'.$value->id);?>" class="btn btn-default btn-xs" data-tt="tooltip" title="<?=$this->lang->line('supplier_view')?>">
                              <i class="fas fa-eye"></i>
                            </a>
                          </td>
                          <?php 
                            }
                          ?>

                          <?php 
                            if($this->permission_model->has_permission('edit_supplier'))
                            {
                          ?>
                          <td class="p-2 m-2">
                            <a href="<?php echo base_url('supplier/edit/'.$value->id);?>" class="btn btn-info btn-xs" data-tt="tooltip" title="<?=$this->lang->line('supplier_edit')?>">
                              <i class="fas fa-edit"></i>
                            </a>      
                          </td>
                          <?php 
                            }
                          ?>

                          <?php 
                            if($this->permission_model->has_permission('delete_supplier'))
                            {
                          ?>
                          <td class="p-2 m-2">
                            <a href="#"  data-toggle="modal" data-target="#delete_supplier_<?php echo $value->id;?>" data-tt="tooltip" title="<?=$this->lang->line('supplier_delete')?>" class="btn btn-danger btn-xs">
                              <i class="fas fa-trash"></i>
                            </a>

                            <?php 
                              $purchases = $this->purchase_model->get_purchase_records_by_supplier_id($value->id);
                              $expenses  = $this->expense_model->get_records_by_supplier_id($value->id);

                              if(sizeof($purchases) > 0 || sizeof($expenses) > 0)
                              {
                            ?>
                              <div class="example-modal">
                                <div class="modal fade" id="delete_supplier_<?php echo $value->id;?>">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header warning-header">
                                        <h4 class="modal-title">
                                          <?php echo $this->lang->line('supplier_delete');?>
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
                                            if($purchases != null)
                                            {
                                          ?>
                                            <li><?=$this->lang->line('due_to_following_reason_purchase_exist')?></li>
                                          <?php
                                            }
                                          ?>

                                          <?php 
                                            if($expenses != null)
                                            {
                                          ?>
                                            <li><?=$this->lang->line('due_to_following_reason_expense_exist')?></li>
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
                                <div class="modal fade" id="delete_supplier_<?php echo $value->id;?>">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header failure-header">
                                        <h4 class="modal-title">
                                          <?php echo $this->lang->line('supplier_delete');?>
                                        </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <p>
                                          <?php echo $this->lang->line('expense_delete_message') .$value->company_name."?";?>
                                        </p>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                          <?php echo $this->lang->line('btn_modal_close');?>
                                        </button>
                                        <form action="<?php echo base_url('supplier/delete');?>" method="POST">
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
                    <th><?=$this->lang->line('supplier_company_name')?></th>
                    <th><?=$this->lang->line('supplier_gst_registration_type')?></th>
                    <th><?=$this->lang->line('supplier_gstin')?></th>
                    <th><?=$this->lang->line('supplier_email')?></th>
                    <th><?=$this->lang->line('supplier_phone')?></th>
                    <th><?=$this->lang->line('supplier_contact_person_name')?></th>
                    <th><?=$this->lang->line('supplier_contact_person_designation')?></th>
                    <!-- <th><?=$this->lang->line('supplier_address')?></th> -->
                    <th><?=$this->lang->line('supplier_website')?></th>
                    <th width="10%"><?=$this->lang->line('supplier_action')?></th>
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