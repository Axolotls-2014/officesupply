<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('quotation_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('quotation_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('quotation_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <?php 
                      if($this->permission_model->has_permission('add_quotation'))
                      {
                    ?>
                        <a class="nav-link active" href="<?=base_url('quotation/add')?>"><i class="far fa-snowflake mr-2"></i><?=$this->lang->line('quotation_add')?></a>
                    <?php 
                      }
                    ?>
                  </li>
                </ul>
              </div>
            
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('quotation_reference_no')?></th>
                    <th><?=$this->lang->line('quotation_date')?></th>
                    <th><?=$this->lang->line('quotation_customer')?></th>
                    <th><?=$this->lang->line('quotation_total_taxable_value').' ('.$this->session->userdata('currency_symbol').')'?></th>
                    <th><?=$this->lang->line('quotation_total_discount').' ('.$this->session->userdata('currency_symbol').')'?></th>
                    <th><?=$this->lang->line('quotation_total_tax').' ('.$this->session->userdata('currency_symbol').')'?></th>
                    <th><?=$this->lang->line('quotation_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
                    <th width="12%"><?=$this->lang->line('quotation_action')?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    if(sizeof($quotation) > 0)
                    {
                      foreach ($quotation as $value) 
                      {
                  ?>
                  <tr>                        
                    <td>
                      <a href="<?=base_url('quotation/view/'.$value->id)?>" data-tt="tooltip" title="<?=$this->lang->line('quotation_view')?>">
                        <?=$value->reference_no?>
                      </a>
                    </td>
                    <td><?php echo date('d-m-Y', strtotime($value->quotation_date));?></td>
                    <td>
                        <a href="<?=base_url('customer/view/'.$value->id)?>" data-tt="tooltip" title="<?=$this->lang->line('sale_view_customer_detail')?>">
                          <?php echo $value->customer_name;?>
                        </a>
                    </td>
                    <td><?=$value->total_taxable_value?></td>
                    <td><?=$value->total_discount?></td>
                    <td><?=$value->total_tax?></td>
                    <td><?=$value->total?></td>
                    <td>
                      <table>
                        <tr>
                        
                          <?php 
                            if($this->permission_model->has_permission('pdf_quotation'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="<?php echo base_url('quotation/pdf/'.$value->id);?>" class="btn bg-orange btn-xs" data-tt="tooltip" title="<?=$this->lang->line('quotation_pdf')?>">
                              <i class="far fa-file-pdf"></i>
                            </a>
                          </td>
                          <?php 
                            }
                          ?>      
                        
                        
                          <?php 
                            if($this->permission_model->has_permission('view_quotation'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="<?php echo base_url('quotation/view/'.$value->id);?>" class="btn btn-default btn-xs" data-tt="tooltip" title="<?=$this->lang->line('quotation_view')?>">
                              <i class="fas fa-eye"></i>
                            </a>
                          </td>
                          <?php 
                            }
                          ?>

                          
                          <?php 
                            if($this->permission_model->has_permission('edit_quotation'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="<?php echo base_url('quotation/edit/'.$value->id);?>" class="btn btn-info btn-xs" data-tt="tooltip" title="<?=$this->lang->line('quotation_edit')?>">
                              <i class="fas fa-edit"></i>
                            </a>  
                          </td>
                          <?php 
                            }
                          ?>

                          <?php 
                            if($this->permission_model->has_permission('delete_quotation'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="#"  data-toggle="modal" data-target="#mymodal_<?php echo $value->id;?>" data-tt="tooltip" title="<?=$this->lang->line('quotation_delete')?>" class="btn btn-danger btn-xs delete_product">
                              <i class="fas fa-trash"></i>
                            </a>

                            <div class="example-modal">
                              <div class="modal fade" id="mymodal_<?php echo $value->id;?>">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header  failure-header">
                                      <h4 class="modal-title">
                                        <?php echo $this->lang->line('quotation_delete');?>
                                      </h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                      
                                    </div>
                                    <div class="modal-body">
                                      <p>
                                        <?php echo "Are you sure want to delete this Quotation with Reference No : ".$value->reference_no."?";?>
                                      </p>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">
                                        <?php echo $this->lang->line('btn_modal_close');?>
                                      </button>
                                      <a href="<?php echo base_url('quotation/delete/'.$value->id);?>" class="btn btn-danger">
                                        <?php echo $this->lang->line('btn_modal_delete');?>
                                      </a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
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
                    }
                    else
                    {
                  ?>
                  <tr>
                    <td colspan="8"><?=$this->lang->line('no_records_available')?></td>
                  </tr>
                  <?php
                    }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('quotation_reference_no')?></th>
                    <th><?=$this->lang->line('quotation_date')?></th>
                    <th><?=$this->lang->line('quotation_customer')?></th>
                    <th><?=$this->lang->line('quotation_total_taxable_value').' ('.$this->session->userdata('currency_symbol').')'?></th>
                    <th><?=$this->lang->line('quotation_total_discount').' ('.$this->session->userdata('currency_symbol').')'?></th>
                    <th><?=$this->lang->line('quotation_total_tax').' ('.$this->session->userdata('currency_symbol').')'?></th>
                    <th><?=$this->lang->line('quotation_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
                    <th><?=$this->lang->line('quotation_action')?></th>
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
