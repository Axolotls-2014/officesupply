<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_sales')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_service')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('service_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('service_list')?></h3>
              <?php 
                if($this->permission_model->has_permission('add_service'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('service/add')?>"><i class="far fa-snowflake mr-2"></i><?=$this->lang->line('service_add')?></a>
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
                    <th><?=$this->lang->line('service_name')?></th>
                    <th><?=$this->lang->line('service_description')?></th>
                    <th><?=$this->lang->line('service_tax')?></th>
                    <th><?=$this->lang->line('service_tax_type')?></th>
                    <th><?=$this->lang->line('service_sac_code')?></th>
                    <th><?=$this->lang->line('service_price').'('.$this->session->userdata('currency_symbol').')'?></th>
                    <th><?=$this->lang->line('service_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    foreach ($service as $value) 
                    {
                  ?>
                  <tr>                        
                    
                    <td><?php echo $value->name;?></td>
                    <td><?php echo $value->description;?></td>
                    
                    <td>
                      <?php 
                        echo $value->tax_name.'<br/>'.'(IGST ='.$value->igst.' CGST = '.$value->igst.' SGST = '.$value->sgst.')';

                      ?>
                      
                    </td>
                    <td>
                      <?php
                        if($value->tax_type == 1)
                        {
                      ?>
                        <span class="right badge bg-warning"><?=$this->lang->line('service_yes');?></span>
                      <?php
                        }
                        else
                        {
                      ?>
                          <span class="right badge bg-info"><?=$this->lang->line('service_no');?></span>
                      <?php 
                        }
                      ?>
                    </td>
                    <td><?php echo $value->sac_code;?></td>
                    <td><?php echo $value->price;?></td>
                    
                    <td>
                      <?php 
                        if($this->permission_model->has_permission('edit_service'))
                        {
                      ?>
                      <a href="<?php echo base_url('service/edit/'.$value->id);?>" class="btn btn-info btn-xs">
                        <i class="fas fa-edit"></i>
                      </a>
                      <?php 
                        }
                      ?>

                      <?php 
                        if($this->permission_model->has_permission('delete_service'))
                        {
                      ?>
                      <a href="#"  data-toggle="modal" data-target="#mymodal_<?php echo $value->id;?>" data-tt="tooltip" title="Delete" class="btn btn-danger btn-xs delete_product">
                        <i class="fas fa-trash"></i>
                      </a>

                      <div class="example-modal">
                        <div class="modal fade" id="mymodal_<?php echo $value->id;?>">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header failure-header">
                                <h4 class="modal-title">
                                  <?php echo $this->lang->line('service_delete');?>
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                
                              </div>
                              <div class="modal-body">
                                <?php echo $this->lang->line('service_delete_message') .$value->name."?";?>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                  <?php echo $this->lang->line('btn_modal_close');?>
                                </button>
                                <a href="<?php echo base_url('service/delete/'.$value->id);?>" class="btn btn-danger">
                                  <?php echo $this->lang->line('btn_modal_delete');?>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php 
                        }
                      ?>
                    </td>
                  </tr>
                  <?php  
                    }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('service_name')?></th>
                    <th><?=$this->lang->line('service_description')?></th>
                    <th><?=$this->lang->line('service_tax')?></th>
                    <th><?=$this->lang->line('service_tax_type')?></th>
                    <th><?=$this->lang->line('service_sac_code')?></th>
                    <th><?=$this->lang->line('service_price').'('.$this->session->userdata('currency_symbol').')'?></th>
                    <th><?=$this->lang->line('service_action')?></th>
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

<?php 
  $this->load->view('layout/footer');
?>
