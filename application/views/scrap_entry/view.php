<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><?=$this->lang->line('header_account')?></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_scrap_entry')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('scrap_entry_view')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('scrap_entry_view')?> </h3>
              
              <div class="card-tools">

                <?php 
                  if($this->permission_model->has_permission('pdf_scrap_entry'))
                  {
                ?>
                    <!-- <a href="#" data-url="<?=base_url('utility/download_scrap_entry/'.base64_encode($scrap_entry->id))?>" style="cursor: pointer" class="btn bg-secondary btn-sm copy-to-clickboard" data-tt="tooltip" title="Copy Pack slip link to Clipboard"> Copy
                      <i class="fas fa-regular fa-paste"></i>
                    </a> -->

                    <a href="<?=base_url('scrap_entry/pdf/'.base64_encode($scrap_entry->id).'/landscape')?>" class="btn bg-orange btn-sm" data-tt="tooltip" title="Download Pack slip">
                      <i class="far fa-file-pdf"></i> Download Scrap entry
                    </a>  
                
                <?php 
                  }
                ?>
                

                <?php 
                  if($this->permission_model->has_permission('edit_scrap_entry'))
                  {
                    
                ?>
                      <a href="<?=base_url('scrap_entry/edit/'.base64_encode($scrap_entry->id))?>" class="btn btn-info btn-sm"  data-tt="tooltip" title="Edit Pack slip">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                <?php 
                   
                  }
                ?>
              </div>
            </div>
            <div class="card-body">
              <div class="invoice p-3 mb-3">
                <div class="row">
                  <div class="col-12 text-center">
                  <!-- <b><?=$this->lang->line('scrap_entry_reference_no')?>: </b><?=$scrap_entry->reference_no?><br> -->
                     <br/><?=$this->lang->line('header_scrap_entry')?>(<?=$scrap_entry->reference_no?>)
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <h4>
                    <?php 
                        $image_data = '';
                        if ($company_setting->logo != '') {
                          $image_path = './assets/images/' . $company_setting->logo; // Adjust the path accordingly
                          if (file_exists($image_path)) {
                            $image_data = file_get_contents($image_path);
                            $base64_data = base64_encode($image_data);
                      ?>

                      
                      <img src="data:image/png;base64,<?=$base64_data?>" style="width: 50px;">
                      

                      <?php 
                          }
                        }
                      ?> <?=$company_setting->company_name?>
                      <small class="float-right"> <?=$this->lang->line('date')?>: <?=date('d-m-Y', strtotime($scrap_entry->scrap_entry_date))?></small>
                    </h4>
                  </div>
                  <!-- /.col -->
                </div>
              
               
                <!-- Table row -->
                <div class="row">
                  <div class="col-12 table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th><?=$this->lang->line('scrap_entry_sr')?></th>
                          <th><?=$this->lang->line('scrap_entry_items')?></th>
                          <th><?=$this->lang->line('proforma_invoice_batch')?></th>
                          <th><?=$this->lang->line('product_cost')?></th>
                          <th><?=$this->lang->line('proforma_invoice_selling_price')?></th>
                          <th><?=$this->lang->line('product_price')?></th>
                          <th><?=$this->lang->line('proforma_invoice_qty')?></th>
                          <th><?=$this->lang->line('scrap_entry_taxable_value')?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $i = 1;
                          $total_quantity = 0;
                          
                          $total_price    = 0;
                          $total_discount_amount = 0;
                          $total_taxable_value = 0;
                         
                          $total_cost = 0;
                          $total_selling_price = 0;
                         
                          foreach ($scrap_entry_items as $row) 
                          {
                            $total_quantity         += $row->quantity;
                            $total_cost             += $row->cost;
                            $total_price            += $row->price;
                            $total_selling_price    += $row->selling_price;
                            $total_taxable_value    += $row->taxable_value;
                         
                            
                        ?>
                          <tr>
                            <td><?=$i++?></td>
                            <td>
                              <?php 
                                $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);

                                $product_description = ($row->description != '') ? '<br/>'.$row->description : '<br/>';
                                $optional = ($row->optional != '') ? '<br/>'.$row->optional.'<br/>' : '<br/>';
                              ?>
                              <?=$row->product_name.$product_description.$optional?>
                            </td>
                          
                          
                            <td><?=$row->batch_no?></td>
                            
                            <td><?=$row->cost?></td>
                            <td><?=$row->selling_price?></td>
                            <td><?=$row->price?></td>
                            
                            <td><?=$row->quantity?></td>
                       
                            <td><?=$row->taxable_value?></td>
                            
                          </tr>
                        <?php 
                          }
                        ?>
                        <tr>
                          <th colspan="3">Total</th>
                         
                          <th><?=number_format($total_cost , 2)?></th>
                          <th><?=number_format($total_selling_price , 2)?></th>
                          <th><?=number_format($total_price , 2)?></th>
                          <th><?=number_format($total_quantity,2)?></th>
                          <th><?=number_format($total_taxable_value,2)?></th>
                        </tr>


                      </tbody>

                    </table>
                  </div>
                </div>
                <!-- /.row -->
                <div class="row">
                  <div class="col-12">
                    <table width="100%" class="table">
                      <tr style="font-size: 16px;font-weight: bolder;">
                        <td width="50%">
                          <?=$this->lang->line('terms_condition')?>
                        </td>
                        <td style="text-align:right">
                         <?=$this->lang->line('certified_message')?>                         
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <?=$scrap_entry->terms_and_condition?>
                        </td>
                        <td style="text-align:right">
                          <?=$this->lang->line('for')?>, <?=strtoupper($company_setting->company_name)?><br/><br/><br/>

                          <?php 
                            $signature_data = '';

                            $company_settings 	= $this->company_settings_model->get_company_records();
                            $cid = $company_settings->cid;

                          if($company_setting->signature != '')
                          {
                            $signature_path = './assets/images/'.$cid.'/' . $company_setting->signature; // Adjust the path accordingly
                            if (file_exists($signature_path)) {
                              $signature_data = file_get_contents($signature_path);
                              $base64_data    = base64_encode($signature_data);
                          ?>
                            <img src="data:image/png;base64,<?=$base64_data?>" style="width: 100px;">
                          <?php
                            } 
                            }
                            else
                            {
                              echo '<br/><br/><br/>';
                            }
                          ?> 
                          <br/> 
                          <?=$this->lang->line('signatory')?>

                        </td>
                      </tr>
                    </table>
                  </div>
                </div>

                <!-- this row will not appear when printing -->
                <div class="row no-print d-none">
                  <div class="col-12">
                    
                    <button type="button" class="btn btn-success float-left print_invoice">
                      <i class="fas fa-print"></i> <?=$this->lang->line('print')?>
                    </button>
                    <!-- <button type="button" class="btn btn-success float-right">
                      <i class="far fa-credit-card"></i> Submit Payment
                    </button>
                    <a class="btn btn-primary float-right" style="margin-right: 5px;" href="<?=base_url('sale/pdf/'.$scrap_entry->id)?>" target="_blank">
                      <i class="fas fa-download"></i> Generate PDF
                    </a> -->
                  </div>
                </div>
              </div>
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



<script type="text/javascript">
  $(document).ready(function(e){
    $('.print_invoice').click(function(e){
      $('.invoice').printThis();
    });
  });
</script>


