<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_currency')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('currency_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('currency_list')?></h3>
              <?php 
                if($this->permission_model->has_permission('add_currency'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('currency/add')?>" data-tt="tooltip" title="Click here to Add Currency">
                      <i class="fas fa-money-check-alt mr-2"></i><?=$this->lang->line('currency_add')?>
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
                    <th><?=$this->lang->line('currency_name')?></th>
                    <th><?=$this->lang->line('currency_symbol')?></th>
                    <th width="10%"><?=$this->lang->line('currency_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    foreach ($currency as $value) 
                    {
                  ?>
                  <tr>                        
                    
                    <td><?php echo $value->name;?></td>
                    <td><?php echo $value->symbol;?></td>
                    
                    <td>

                      <table>
                        <tr>
                          <td class="p-2">
                            <?php 
                              if($this->permission_model->has_permission('edit_currency'))
                              {
                            ?>
                            <a href="<?php echo base_url('currency/edit/'.$value->id);?>" class="btn btn-info btn-xs" data-tt="tooltip" title="<?=$this->lang->line('currency_edit')?>">
                              <i class="fas fa-edit"></i>
                            </a>
                            <?php 
                              }
                            ?>      
                          </td>
                          <td class="p-2">
                            <?php 
                              if($this->permission_model->has_permission('delete_currency'))
                              {
                            ?>
                            <a href="#"  data-toggle="modal" data-target="#mymodal_<?php echo $value->id;?>" data-tt="tooltip" title="<?=$this->lang->line('currency_delete')?>" class="btn btn-danger btn-xs delete_product">
                              <i class="fas fa-trash"></i>
                            </a>

                            <div class="example-modal">
                              <div class="modal fade" id="mymodal_<?php echo $value->id;?>">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header  failure-header">
                                       <h4 class="modal-title">
                                        <?php echo $this->lang->line('currency_delete');?>
                                      </h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <p>
                                        <?php echo $this->lang->line('currency_delete_message') .$value->name."?";?>
                                      </p>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">
                                        <?php echo $this->lang->line('btn_modal_close');?>
                                      </button>
                                      <a href="<?php echo base_url('currency/delete/'.$value->id);?>" class="btn btn-danger">
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
                      </table>
                      

                      

                    </td>
                  </tr>
                  <?php  
                    }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('currency_name')?></th>
                    <th><?=$this->lang->line('currency_symbol')?></th>
                    <th><?=$this->lang->line('currency_action')?></th>
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