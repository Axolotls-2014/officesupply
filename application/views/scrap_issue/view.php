<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><?=$this->lang->line('header_account')?></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_scrap_issue')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('scrap_issue_view')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('scrap_issue_view')?> </h3>
              
              <div class="card-tools">

                <?php 
                  if($this->permission_model->has_permission('pdf_scrap_issue'))
                  {
                ?>
                    <!-- <a href="#" data-url="<?=base_url('utility/download_scrap_issue/'.base64_encode($scrap_issue->id))?>" style="cursor: pointer" class="btn bg-secondary btn-sm copy-to-clickboard" data-tt="tooltip" title="Copy Pack slip link to Clipboard"> Copy
                      <i class="fas fa-regular fa-paste"></i>
                    </a> -->

                    <a href="<?=base_url('scrap_issue/pdf/'.base64_encode($scrap_issue->id).'/landscape')?>" class="btn bg-orange btn-sm" data-tt="tooltip" title="Download Pack slip">
                      <i class="far fa-file-pdf"></i> Download Scrap issue
                    </a>  
                
                <?php 
                  }
                ?>
                

                <?php 
                  if($this->permission_model->has_permission('edit_scrap_issue'))
                  {
                    
                ?>
                      <a href="<?=base_url('scrap_issue/edit/'.base64_encode($scrap_issue->id))?>" class="btn btn-info btn-sm"  data-tt="tooltip" title="Edit Pack slip">
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
                     <br/><?=$this->lang->line('header_scrap_issue')?>(<?=$scrap_issue->reference_no?>)
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
                      <small class="float-right"> <?=$this->lang->line('date')?>: <?=date('d-m-Y', strtotime($scrap_issue->scrap_issue_date))?></small>
                    </h4>
                  </div>
                  <!-- /.col -->
                </div>
                <div class="row">
                  <div class="col-12">
                    <table width="100%" class="table">
                      <tr style="font-size: 16px;font-weight: bolder;">
                        <td><?=$this->lang->line('supplier')?></td>
                        <td><?=$this->lang->line('bill_to')?></td>
                        <td><?=$this->lang->line('invoice_details')?></td>
                      </tr>
                      <tr>
                        <td>
                          <address>
                            <strong><?=$company_setting->company_name?></strong><br>
                            <?=($company_setting->address_line1 != '') ? $company_setting->address_line1.'<br>':'' ?>
                            <?=($company_setting->address_line2 != '') ? $company_setting->address_line2.'<br>':'' ?>
                            <?=$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name?><br>
                            <?=($company_setting->pincode != '') ? $company_setting->pincode.'<br>':'' ?>
                            
                            <?=$this->lang->line('phone')?>: <?=$company_setting->mobile?><br>
                            <?=$this->lang->line('email')?>: <?=$company_setting->email?><br>
                            <strong><?=$this->lang->line('gstin')?>: <?=$company_setting->gstin?></strong><br>
                          </address>
                        </td>
                        <td>
                          <address>
                            <strong><?=$supplier_detail->company_name?></strong><br>
                            <?= ($supplier_detail->address != '') ? ($supplier_detail->address.'<br/>') : '' ?>
                            <?=$supplier_detail->city_name.', '.$supplier_detail->state_name.', '.$supplier_detail->country_name?><br>
                            <b><?=$this->lang->line('phone')?></b>: <?=$supplier_detail->phone?><br>
                            <b><?=$this->lang->line('email')?></b>: <?=$supplier_detail->email?><br>
                            <b><?=$this->lang->line('gstin')?></b>: <?=$supplier_detail->gstin?><br>
                          </address>
                        </td>
                        <td style="vertical-align: text-top;">
                          <b><?=$this->lang->line('scrap_issue_reference_no')?>: </b><?=$scrap_issue->reference_no?><br>
                          <!-- <b><?=$this->lang->line('receipt_voucher_no')?></b><br/> -->
                          <b><?=$this->lang->line('rcm_applicability')?>: </b><?=($scrap_issue->rcm == "Y") ? "Yes" : "No"?>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
               
                <!-- Table row -->
                <div class="row">
                  <div class="col-12 table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th><?=$this->lang->line('scrap_issue_sr')?></th>
                          <th><?=$this->lang->line('scrap_issue_items')?></th>
                          <th><?=$this->lang->line('scrap_issue_warehouse_name')?></th>
                          <th><?=$this->lang->line('scrap_issue_tax')?></th>
                       
                          <th><?=$this->lang->line('product_batch_no')?></th>
                          <th><?=$this->lang->line('product_price')?></th>
                          <th><?=$this->lang->line('product_cost')?></th>
                          <th><?=$this->lang->line('scrap_issue_qty')?></th>
                          
                          <th><?=$this->lang->line('scrap_issue_total_qty')?></th>
                        
                      
                          <!-- <th><?=$this->lang->line('scrap_issue_total_taxable_value')?></th> -->
                          <th><?=$this->lang->line('scrap_issue_subtotal')?></th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $i = 1;
                          $total_quantity = 0;
                         
                          $total_total_quantity = 0;
                         
                          $total_price    = 0;
                          $total_discount_amount = 0;
                          $total_taxable_value = 0;
                          $total_tax = 0;
                          $total_subtotal = 0;
                          $total_cost = 0;
                          $total = 0;
                          foreach ($scrap_issue_items as $row) 
                          {
                            $total_quantity         += $row->quantity;
                           
                            $total_total_quantity   += $row->total_quantity;
                          
                            $total_cost             += $row->cost;
                            $total_tax              += $row->igst_tax+$row->cgst_tax+$row->sgst_tax;
                            $total_subtotal         += $row->sub_total;
                            
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
                            <td><?=$row->warehouse_name?></td>
                            <td>
                              <?php 
                                if($company_setting->country_id == $supplier_detail->country_id)
                                {
                                    if($company_setting->state_id == $supplier_detail->state_id)
                                    {
                              ?>
                                      <?=$this->lang->line('cgst')?> : <?=$row->cgst_tax?>
                                      (<?=$row->cgst?>%)
                                      <br><?=$this->lang->line('sgst')?> : <?=$row->sgst_tax?>
                                      (<?=$row->sgst?>%)
                              <?php
                                    }
                                    else
                                    {
                              ?>
                                      <?=$this->lang->line('igst')?> : <?=$row->igst_tax?>
                                      (<?=$row->igst?>%)
                              <?php 
                                    }
                                }
                                else
                                {
                              ?>
                                  N/A
                              <?php
                                }
                              ?>
                            </td>
                            
                            <td><?=$row->batch_no?></td>
                            
                            
                            <td><?=$row->price?></td>
                            <td><?=$row->cost?></td>
                            <td><?=$row->quantity?></td>
                            
                            <td><?=$row->total_quantity?></td>
                           
                            <td><?=$row->sub_total?></td>
                            
                          </tr>
                        <?php 
                          }
                        ?>
                        <tr>
                          <th colspan="3">Total</th>
                          <th><?=$total_tax?></th>
                          <th colspan="2"></th>
                          <th><?=number_format($total_cost , 2)?></th>
                          <th><?=number_format($total_quantity,2)?></th>
                          <th><?=number_format($total_total_quantity,2)?></th>
                          <th><?=$total_subtotal?></th>
                        </tr>

                        <tr>
                          <td colspan="9"><b>Total Taxable Value</b></td>
                          <td><b><?=$scrap_issue->total_taxable_value?><b></td>
                        </tr>

                        <tr>
                          <td colspan="9"><b>Total Amount</b></td>
                          <td><b><?=$scrap_issue->total?><b></td>
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
                          <?=$scrap_issue->terms_and_condition?>
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
                    <a class="btn btn-primary float-right" style="margin-right: 5px;" href="<?=base_url('sale/pdf/'.$scrap_issue->id)?>" target="_blank">
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

<div class="transaction-modal">
  <div class="modal fade" id="transaction-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header light-purple-header">
          <h4 class="modal-title">
            <?php echo $this->lang->line('scrap_issue_transaction');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="accordion">
            <div class="card card-secondary">
              <div class="card-header saleDetail light-secondary-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#saleDetail" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('scrap_issue_view')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="saleDetail" class="panel-collapse in collapse" style="">
                <div class="card-body scrap_issue_detail  m-0 p-0">
                </div>
              </div>
            </div>
            <div class="card card-danger addTransactionCard">
              <div class="card-header addTransaction light-failure-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#addTransaction" class="" aria-expanded="true">
                    <?=$this->lang->line('scrap_issue_payment')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="addTransaction" class="panel-collapse collapse show" style="">
                <form id="addTransactionForm" name="addTransactionForm" method="POST">
                  <div class="card-body add_transaction">
                  </div>
                  <div class="card-footer">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="transaction_type" value="<?=RECEIPT_TRANSACTION_TYPE?>">
                    <input type="hidden" name="entry_id" id="scrap_issue_id" value="">
                    <input type="hidden" name="module" value="<?=SALE_MODULE?>">
                    <input type="hidden" name="from_account" id="from_account" value="">
                    <button type="submit" class="btn btn-info" name="addTransactionSubmit" id="addTransactionSubmit">
                      <?php echo $this->lang->line('submit');?>
                    </button>
                    <button type="reset" class="btn btn-default">
                      <?php echo $this->lang->line('reset');?>
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="card card-success">
              <div class="card-header transactionEntries light-success-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#transactionEntries" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('scrap_issue_previous_transaction')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="transactionEntries" class="panel-collapse collapse">
                <div class="card-body transaction_entries m-0 p-0">
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="is_there_change_in_transaction" id="is_there_change_in_transaction" value="false">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="delivery-modal">
  <div class="modal fade" id="delivery-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header light-purple-header">
          <h4 class="modal-title">
            <?php echo $this->lang->line('scrap_issue_delivery');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="accordion">
            <form id="addDeliveryDetailForm" name="addDeliveryDetailForm" method="POST">
              <div class="card card-info addDeliveryDetailCard">

                  <div class="card-header light-secondary-header addDeliveryDetail">
                    <h5 class="card-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#addDeliveryDetail" class="collapsed" aria-expanded="false">
                        <?=$this->lang->line('scrap_issue_delivery_add')?>
                        <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                      </a>
                    </h5>
                  </div>
                  <div id="addDeliveryDetail" class="panel-collapse in collapse" style="">
                    <div class="card-body add_delivery_detail">
                    
                    </div>
                    <div class="card-footer">
                      <input type="hidden" name="delivery_items" value="">
                      <input type="hidden" name="scrap_issue_id" value="">
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                      <button type="submit" name="addDeliveryDetailSubmit" id="addDeliveryDetailSubmit" class="btn btn-primary float-right">
                        <?=$this->lang->line('submit')?>
                      </button>
                      <button type="button" name="cancelDeliveryDetail" id="cancelDeliveryDetail" class="btn btn-default">
                        <?=$this->lang->line('cancel')?>
                      </button>
                    </div>
                  </div>
                
              </div>
            </form>
            <div class="card card-success deliveryEntriesCard">
              <div class="card-header deliveryEntries light-success-header">
                <h5 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#deliveryEntries" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('previous_scrap_issue_delivery')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h5>
                <div class="card-tools">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-default" id="addNewDeliveryDetail">Add New Delivery</button>
                  </div>
                </div>
              </div>
              <div id="deliveryEntries" class="panel-collapse collapse">
                <div class="card-body delivery_entries m-0 p-0">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="is_there_change_in_delivery_transaction" id="is_there_change_in_delivery_transaction" value="false">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="scrap_issue_edit">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="whatsapp-modal">
  <div class="modal fade" id="whatsapp-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">
            Send PDF
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="col-md-12">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 col-form-label">
                    supplier Phone
                  </label>
                  <div class="col-sm-8">
                    <input type="text" name="phone" value="<?=$supplier_detail->phone?>" class="form-control form-control-sm" id="phone" readonly>
                  </div>
                </div> 
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 col-form-label">
                    Other Phone
                  </label>
                  <div class="col-sm-8">
                     <textarea id="other_mobile_numbers" name="other_mobile_numbers" rows="5" class="form-control form-control-sm" placeholder="Enter mobile numbers separated by commas"></textarea>
                     <span class="text-info">Enter 10 digit mobile number without country code. (eg. 98765432XX)</span>
                  </div>
                </div> 
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <input type="hidden" name="scrap_issue_id" id="packslip_id" value="<?=$scrap_issue->id?>">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <button type="submit" name="submit" id="submitPDF" class="btn btn-primary float-right">
            <?=$this->lang->line('submit')?>
          </button>
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('layout/footer');?>

<script type="text/javascript">
   const packslipToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 10000
  });

  $(document).ready(function(e){

    $("#submitPDF").on("click", function (e) {

      $('#submitPDF').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      var scrap_issue_id = $("#packslip_id").val();

      // Get the supplier's phone number
      var supplierPhone = $("#phone").val().trim();

      // Get the other mobile numbers from the textarea and split them into an array
      var otherMobileNumbers = $("#other_mobile_numbers").val().trim();
      var mobileNumbersArray = otherMobileNumbers.split(',');

      // Add the supplier's phone number to the array if it's not empty
      if (supplierPhone !== '') {
        mobileNumbersArray.push(supplierPhone);
      }

      // Prepare the mobile numbers as a comma-separated string
      var mobileNumbers = mobileNumbersArray.join(',');


      $.ajax({
        url: "<?php echo base_url('scrap_issue/whatsapp_packslip'); ?>/" + btoa(scrap_issue_id),
        type: "POST",
        data: {
                 'mobile_numbers': mobileNumbers,
                 '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
        dataType: "json",
        success: function(response) {

          $('#submitPDF').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');

         
          // Show an alert on successful message delivery
          if (response.code==1) {

            $('#whatsapp-modal').modal('hide');

              packslipToast.fire({
                  type: 'success',
                  title: response.message
                });
          } else {

            packslipToast.fire({
                type: 'error',
                title: response.message
              });

          }
        },
        error: function(xhr, textStatus, errorThrown) {
          Swal.fire({
            title: 'FAILURE !!',
            text: "AJAX request failed. Error: " + errorThrown,
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn btn-primary"
            }
          });
        }
      });

    });

  });

</script>

<script type="text/javascript">
  $(document).ready(function(e){
    $('.print_invoice').click(function(e){
      $('.invoice').printThis();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){



    $('.saleDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

    $('#transaction-modal').on('show.bs.modal', function (e) {

      $('.addTransactionCard').css('display','block');

      var scrap_issue_id = $(e.relatedTarget).data('scrap_issue_id');
      $('#scrap_issue_id').val(scrap_issue_id);
      
      $.ajax({
        url: "<?php echo base_url('sale/view')?>/"+scrap_issue_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){

          $(".scrap_issue_detail").html(data.scrap_issue_view);
          $(".add_transaction").html(data.add_transaction);
          $("#from_account").val(data.from_account);
          $(".transaction_entries").html(data.previous_transaction_view);

          if(data.due_amount == 0)
          {
            $('.addTransactionCard').css('display','none');
            // collapse all accordian
            $('#transactionEntries').collapse("hide");
            $('#saleDetail').collapse("show");  
          }
          else
          {
            // collapse all accordian
            $('#transactionEntries').collapse("hide");
            $('#addTransaction').collapse("hide");
            $('#saleDetail').collapse("show");  
          }
        }
      });
    });

    $(document).on('hide.bs.modal','#transaction-modal' ,function (e) {

      // alert($('#is_there_change_in_transaction').val());
      
      if($('#is_there_change_in_transaction').val() == "true")
      {
        location.reload();
      }
      
    });

    $('#delivery-modal').on('show.bs.modal', function (e) {

      var scrap_issue_id = $(e.relatedTarget).data('scrap_issue_id');
      $('#scrap_issue_id').val(scrap_issue_id);
      $('#addNewDeliveryDetail').data('scrap_issue_id',scrap_issue_id);

      if($(e.relatedTarget).hasClass('open_delivery_modal'))
      {
        $.ajax({
          url: "<?php echo base_url('sale/view_delivery')?>",
          type: "POST",
          data: {
            'scrap_issue_id':scrap_issue_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            // if all quantity is delivered, hide the add new delivery button
            if(data.is_all_products_delivered == true)
            {
              $('#addNewDeliveryDetail').css('display','none');
            }
            else
            {
              $('#addNewDeliveryDetail').css('display','block'); 
            }  

            // $(".add_delivery_detail").html(data.add_delivery_detail);
            $(".delivery_entries").html(data.delivery_transaction);
            $(".scrap_issue_delivery_detail").html(data.scrap_issue_delivery_detail);
            
            $('.view_delivery_transaction_detail').css('display','none');
            $('#deliveryEntries').collapse("show");

            $('#addDeliveryDetailForm').find('input[name="scrap_issue_id"]').val(scrap_issue_id);

            
          }
        });  
      }      
    });

    $(document).on('hide.bs.modal','#delivery-modal' ,function (e) {
  
      if($('#is_there_change_in_delivery_transaction').val() == "true")
      {
        location.reload();
      }
         
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('.saleDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

    // View delivery transaction
    $(document).on('click', '.view_delivery_transaction', function (event) {

      $('.view_delivery_transaction_detail').css('display','none');
      $(this).parents('tr').next('tr').toggle(200);
      
    });

    // Add new delivery of sale
    $(document).on('click','#addNewDeliveryDetail',function(e){

      $('.addDeliveryDetailCard').toggle(200);

      var scrap_issue_id = $('#addDeliveryDetailForm').find('input[name="scrap_issue_id"]').val();

      $.ajax({
        url: "<?php echo base_url('sale/add_scrap_issue_delivery')?>/"+scrap_issue_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){

          $(".add_delivery_detail").html(data.add_delivery_detail);

          // Initialize the dropdown and date
  
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
  
          $('#delivery-modal').find('input[name="delivery_date"]').datepicker({
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
          });
            
        }
      });

      $('#addDeliveryDetail').collapse('show');
    });

    // Populate delivery data of sale
    $(document).on('click','#addDeliveryDetailSubmit',function(e){
      e.preventDefault();

      var deliveryDataArray = [];

      $("#product_delivery_item_body").find('tr').each(function () {

        var tr                      = $(this).closest("tr");
        var deliveryData            = {};

        deliveryData['product_id']            = tr.find('input[name="quantity"]').data('product_id');
        deliveryData['warehouse_product_id']  = tr.find('input[name="quantity"]').data('warehouse_product_id');
        deliveryData['quantity']              = tr.find('input[name="quantity"]').val();

        deliveryDataArray.push(JSON.stringify(deliveryData));
      });

       
      $('#addDeliveryDetailForm').find('input[name="delivery_items"]').val(deliveryDataArray.join('|'));

      $('#addDeliveryDetailForm').trigger('submit');
    });

    // Submit delivery data of sale
    $(document).on('submit','#addDeliveryDetailForm',function(e){
      e.preventDefault();

      var isError = false;

      $('form#addDeliveryDetailForm .field_validation').each(function() {
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');
          var name  = $(this).attr('name');

          // alert(name);
          
          if(value==null || value=="")
          {
            $("form#addDeliveryDetailForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
            $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
            $('form#addDeliveryDetailForm #'+id).addClass('is-valid');
          }

          if(name == 'quantity')
          {
            var max = parseInt($(this).attr('max'));
            if(parseInt(value) > max)
            {
              $("form#addDeliveryDetailForm #err_"+id).text(field+ " should be <= "+ max).fadeIn('slow');
              $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
              isError = true;   
            }
          }


      });

      if(isError == true)
      {
        return false;
      }
      else
      {
        var DeliveryDetailFormData  = $('#addDeliveryDetailForm').serialize();  
        var scrap_issue_id                 = $('form#addDeliveryDetailForm').find('input[name="scrap_issue_id"]').val(); 

        $('#addDeliveryDetailSubmit').text('Please wait while we are saving').addClass('disabled');

        setTimeout(function(){ 

          $.ajax({
            url: "<?php echo base_url('sale/add_scrap_issue_delivery')?>",
            type: "POST",
            data: DeliveryDetailFormData,
            dataType: "JSON",
            success: function(data){


              if(data.code == 1)
              {
                $('#addDeliveryDetailSubmit').text('Submit').removeClass('disabled');
                $.ajax({
                  url: "<?php echo base_url('sale/view_delivery')?>",
                  type: "POST",
                  data: {
                    'scrap_issue_id':scrap_issue_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                  dataType: "JSON",
                  success: function(data){

                    // if all quantity is delivered, hide the add new delivery button
                    if(data.is_all_products_delivered == true)
                    {
                      $('#addNewDeliveryDetail').css('display','none');
                    }
                    else
                    {
                      $('#addNewDeliveryDetail').css('display','block'); 
                    }  

                    $('.addDeliveryDetailCard').toggle(200);
                    $(".add_delivery_detail").html('');
                    $(".delivery_entries").html(data.delivery_transaction);
                    // $(".scrap_issue_delivery_detail").html(data.scrap_issue_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="scrap_issue_id"]').val(scrap_issue_id);

                    $("#is_there_change_in_delivery_transaction").val("true");
                  }
                });  
              }
            }
          });
        }, 500);  
      }
    });

    $("form#addDeliveryDetailForm").on("change", '.field_validation', function (event){

      var id    = $(this).attr('id');
      var value = $(this).val();
      var field = $(this).attr('placeholder');
      var name  = $(this).attr('name');
      
      if(value==null || value==""){
        $("form#addDeliveryDetailForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
        $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
        return false;
      }
      else{
        $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
        $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
        $('form#addDeliveryDetailForm #'+id).addClass('is-valid');
      }

      if(name == 'quantity')
      {
        var max = parseInt($(this).attr('max'));
        if(parseInt(value) > max)
        {
          $("form#addDeliveryDetailForm #err_"+id).text(field+ " should be <= "+ max).fadeIn('slow');
          $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
          return false;  
        }
        else
        {
          $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
          $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
          $('form#addDeliveryDetailForm #'+id).addClass('is-valid');   
        }
      }
    });

    $(document).on('click','#cancelDeliveryDetail',function(e){
      $('.addDeliveryDetailCard').toggle(200);
      $('#addDeliveryDetail').collapse('hide');
      
    });

    $(document).on('click','.delete_delivery_transaction',function(e){
      $(this).fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeIn();
    });

    $(document).on('click', '.delete_delivery_transaction_no', function (event) {
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction').fadeIn(20);
    });

    $(document).on('click', '.delete_delivery_transaction_yes', function (event) {
      // $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeOut(1);
      

      var $this                 = $(this);
      var scrap_issue_delivery_id  = $(this).data('scrap_issue_delivery_id');
      var scrap_issue_id           = $('#addDeliveryDetailForm').find('input[name="scrap_issue_id"]').val();

      $(this).closest('td').find('.delete_delivery_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('sale/delete_scrap_issue_delivery')?>",
          type: "POST",
          data: {
            'scrap_issue_delivery_id':scrap_issue_delivery_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $('#is_there_change_in_delivery_transaction').val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                $.ajax({
                  url: "<?php echo base_url('sale/view_delivery')?>",
                  type: "POST",
                  data: {
                    'scrap_issue_id':scrap_issue_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                  dataType: "JSON",
                  success: function(data){

                    // if all quantity is delivered, hide the add new delivery button
                    if(data.is_all_products_delivered == true)
                    {
                      $('#addNewDeliveryDetail').css('display','none');
                    }
                    else
                    {
                      $('#addNewDeliveryDetail').css('display','block'); 
                    }  

                    // $(".add_delivery_detail").html(data.add_delivery_detail);
                    $(".delivery_entries").html(data.delivery_transaction);
                    $(".scrap_issue_delivery_detail").html(data.scrap_issue_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="scrap_issue_id"]').val(scrap_issue_id);
                  }
                });

                
              }

            },500);
          }
        });
      }, 500);
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('body').on('click', '.transactionEntries', function() {
      $('#transactionEntries').collapse("show");
      $('#addTransaction').collapse("hide");
      $('#saleDetail').collapse("hide");
    });

    $('body').on('click', '.addTransaction', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("show");
      $('#saleDetail').collapse("hide");
    });

    $('body').on('click', '.saleDetail', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("hide");
      $('#saleDetail').collapse("show");
    });

    $(document).on('click', '.make_payment', function (event) {
      var transaction_amount = $(this).data('transaction_amount');
      $('#transaction_amount').val(transaction_amount);
      $('.addTransaction').trigger('click');
    });

    $('body').on('change', '#payment_mode', function() {
      var payment_mode = $(this).val();

      if(payment_mode == '')
      {
        $('.transaction_mode_row').fadeOut(10);
      }
      else if(payment_mode == 0)
      {
        $('.transaction_mode_row').fadeOut(10);
      }
      else if(payment_mode == 1)
      {
        $('.transaction_mode_row').fadeOut(10);
        $('.credit_card_row').fadeIn();
      }
      else if(payment_mode == 2)
      {
        $('.transaction_mode_row').fadeOut(10);
        $('.cheque_row').fadeIn();
      }

      $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
      $('form#addTransactionForm #credit_card_no').removeClass('is-valid');

      $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
      $('form#addTransactionForm #cheque_no').removeClass('is-valid');
    });    

    $('body').on('click', '#addTransactionSubmit', function(e) {
      e.preventDefault();
      var isError = false;

      $('form#addTransactionForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addTransactionForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addTransactionForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addTransactionForm #err_"+id).text("").fadeOut('slow');
            $('form#addTransactionForm #'+id).removeClass('is-invalid');
            $('form#addTransactionForm #'+id).addClass('is-valid');
          }
      });

      if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
      {
        var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
        $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
        $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
        isError = true;
      }
      else
      {
        $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
        $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
        $('form#addTransactionForm #credit_card_no').addClass('is-valid');
      }

      if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
      {
        var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
        $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
        $('form#addTransactionForm #cheque_no').addClass('is-invalid');
        isError = true;
      }
      else
      {
        $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
        $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
        $('form#addTransactionForm #cheque_no').addClass('is-valid');
      }

      if(isError == true)
      {
        return false;
      }  
      else 
      {
        var transactionFormData = $('#addTransactionForm').serialize();
        var scrap_issue_id = $('#scrap_issue_id').val();

        $('#addTransactionSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled')

        $.ajax({
          url: "<?php echo base_url('transaction/add')?>",
          type: "POST",
          data: transactionFormData,
          dataType: "JSON",
          success: function(data){

            $(".transaction_entries").html(data.previous_transaction);

            $('#addTransactionSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            $('#is_there_change_in_transaction').val('true');

            $.ajax({
              url: "<?php echo base_url('sale/view')?>/"+scrap_issue_id,
              type: "GET",
              dataType: "JSON",
              success: function(data){

                $(".scrap_issue_detail").html(data.scrap_issue_view);
                $(".add_transaction").html(data.add_transaction);
                $("#to_account").val(data.to_account);
                $(".transaction_entries").html(data.previous_transaction_view);

                if(data.due_amount == 0)
                {
                  $('.addTransactionCard').css('display','none');
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#saleDetail').collapse("hide");  
                }
                else
                {
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#addTransaction').collapse("hide");
                  $('#saleDetail').collapse("hide");  
                }
              }
            });
          }
        });
      } 

    });

    $(document).on("blur change keyup", "form#addTransactionForm .field_validation" , function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addTransactionForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addTransactionForm #err_"+id).text("").fadeOut('slow');
          $('form#addTransactionForm #'+id).removeClass('is-invalid');
          $('form#addTransactionForm #'+id).addClass('is-valid');
        }

        if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
        {
          var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
          $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
          $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
          $('form#addTransactionForm #credit_card_no').addClass('is-valid');
        }

        if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
        {
          var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
          $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #cheque_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
          $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
          $('form#addTransactionForm #cheque_no').addClass('is-valid');
        }
    });

    $(document).on("blur change keyup","form#addTransactionForm #credit_card_no",  function (event){
              
        if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
        {
          var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
          $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
          $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
          $('form#addTransactionForm #credit_card_no').addClass('is-valid');
        }
        
    });

    $(document).on("blur change keyup","form#addTransactionForm #cheque_no",  function (event){              
        
        if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
        {
          var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
          $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #cheque_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
          $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
          $('form#addTransactionForm #cheque_no').addClass('is-valid');
        }
    });

    $(document).on('click', '.delete_transaction', function (event) {
      $(this).fadeOut(10);
      $(this).closest('td').find('.delete_transaction_confirmation').fadeIn();
    });

    $(document).on('click', '.delete_transaction_no', function (event) {
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_transaction').fadeIn(20);
    });

    $(document).on('click', '.delete_transaction_yes', function (event) {
      // $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(1);
      

      var $this           = $(this);
      var transaction_id  = $(this).data('transaction_id');
      var scrap_issue_id         = $(this).data('entry_id');

      $(this).closest('td').find('.delete_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('transaction/delete')?>",
          type: "POST",
          data: {
            'transaction_id':transaction_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $('#is_there_change_in_transaction').val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                $.ajax({
                  url: "<?php echo base_url('sale/view')?>/"+scrap_issue_id,
                  type: "GET",
                  dataType: "JSON",
                  success: function(data){

                    $(".scrap_issue_detail").html(data.scrap_issue_view);
                    $(".add_transaction").html(data.add_transaction);
                    $("#to_account").val(data.to_account);
                    $(".transaction_entries").html(data.previous_transaction_view);

                    if(data.due_amount == 0)
                    {
                      $('.addTransactionCard').css('display','none');
                      // collapse all accordian
                      $('#transactionEntries').collapse("show");
                      $('#saleDetail').collapse("hide");  
                    }
                    else
                    {
                      // collapse all accordian
                      $('.addTransactionCard').css('display','block');
                      $('#transactionEntries').collapse("hide");
                      $('#addTransaction').collapse("hide");
                      $('#saleDetail').collapse("show");  
                    }
                  }
                });  
              }

            },500);
          }
        });
      }, 500);
    });

    $(document).on('show.bs.modal','#scrap_issue_edit', function (e) {
      var scrap_issue_id = $(e.relatedTarget).data('scrap_issue_id');
      $('#scrap_issue_edit').find('#id').val(scrap_issue_id);

      // alert(scrap_issue_id);

      $.ajax({
        url: "<?php echo base_url('sale/scrap_issue_edit_confirmation')?>",
        type: "POST",
        data:{
          'scrap_issue_id': scrap_issue_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#scrap_issue_edit').find('.modal-content').html(data.scrap_issue_edit_modal_body);
        }
      });
    });

    const messageToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    // Copy to clipboard
    $(document).on('click','.copy-to-clickboard',function(e){
      e.preventDefault();
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(this).data('url')).select();
      document.execCommand("copy");
      $temp.remove();

      messageToast.fire({
        type: 'success',
        title: "Link is copied into your clipboard. CTRL + V to paste the URL."
      });  

    })

  })
</script>