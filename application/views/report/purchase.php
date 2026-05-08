<?php $this->load->view('layout/header'); ?>

	<style type="text/css">
		.footer_data{
			font-size: 20px;
		}
	</style>
  	<div class="wrapper">
	  	<div class="content-wrapper">
		    <section class="content-header">
		      <div class="row mb-2">
		        <div class="col-sm-12">
		          <ol class="breadcrumb breadcrumb-custom float-sm-left">
<li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>

		            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_reports')?></a></li>
		            <!--<li class="breadcrumb-item active"><?=$this->lang->line('purchase_report')?></li>-->
		            <li class="breadcrumb-item active"> Purchase Register Report </li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
            <!-- general form elements disabled -->
            	<form role="form" id="purchaseReportForm" name="purchaseReportForm" method="POST" action="<?php echo base_url("report/purchase") ?>" target="_blank">
	            	<div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title">
		                	<i class="fas fa-funnel-dollar"></i>
		                	<?=$this->lang->line('filter_report')?>
		                </h3>
		              </div>
	              	<!-- /.card-header -->
		              <div class="card-body"> 
	                  <div class="row">
	                    <div class="col-sm-2">
	                    	<label><?=$this->lang->line('select_date_range')?></label>
	                      <div class="input-group">
			                    <button type="button" class="btn btn-block btn-outline-secondary float-right" id="daterange-btn">
			                      <i class="far fa-calendar-alt"></i> Date Range
			                      <!-- <i class="fas fa-caret-down"></i> -->
			                    </button>
			                  </div>
	                    </div>
	                    <div class="col-sm-2">
	                      <div class="form-group">
				                  <label><?=$this->lang->line('from_date')?></label>
				                  <div class="input-group">
				                    <div class="input-group-prepend">
				                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
				                    </div>
				                    <input type="text" class="form-control" name="from_date" id="from_date" style="z-index:999 !important" readonly="readonly">
				                  </div>
				                </div>
	                    </div>
	                    <div class="col-sm-2">
	                      <div class="form-group">
				                  <label><?=$this->lang->line('to_date')?></label>
				                  <div class="input-group">
				                    <div class="input-group-prepend">
				                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
				                    </div>
				                    <input type="text" class="form-control" name="to_date" id="to_date" value="<?=date('d-m-Y')?>"  readonly="readonly">
				                  </div>
				                </div>
	                    </div>
											<div class="col-sm-3">
	                    	<div class="form-group">
                          <label for="customer"><?=$this->lang->line('warehouse')?></label>
                          <select class="form-control form-control-sm select2bs4" name="warehouse_id" id="warehouse_id" width="100%" class="add-row">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($warehouse as $value) {
                            ?>
                              <option value="<?=$value->id;?>" <?php echo set_select('id', $value->id); ?>>
                                <?= $value->name;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_warehouse_id" class="error invalid-feedback"><?=form_error('warehouse_id');?></span>
                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                    	<div class="form-group">
                          <label for="customer"><?=$this->lang->line('purchase_supplier')?></label>
                          <select class="form-control form-control-sm select2bs4" name="supplier_id" id="supplier_id" width="100%" class="add-row">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($suppliers as $value) {
                            ?>
                              <option value="<?=$value->id;?>" <?php echo set_select('id', $value->id); ?>>
                                <?= $value->company_name;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_supplier_id" class="error invalid-feedback"><?=form_error('supplier_id');?></span>
                        </div>
	                    </div>
	                  </div>
		              </div>
	              	<!-- /.card-body -->
		              <div class="card-footer">
		              	<input type="hidden" name="action_type" id="action_type" value="">
		              	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		              	<button type="submit" name="submit" id="submitPurchaseReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
		              </div>
	            	</div>
	            </form>
	            <div class="card card-primary">
	              <div class="card-header">
	                <!--<h3 class="card-title"><?=$this->lang->line('purchase_report')?></h3>-->
	                <h3 class="card-title"> Purchase Register Report </h3>
	                <div class="card-tools">
	                	<button type="button" class="btn btn-tool bt-danger" id="export-button">
                    	<i class="fas fa-file-export"></i>
                    	<?=$this->lang->line('export')?>
                    </button>
                    <button type="button" class="btn btn-tool" id='print'>
                    	<i class="fas fa-print"></i>
                    	<?=$this->lang->line('print')?>
                    </button>
                    <button type="button" class="btn btn-tool" id='pdf'>
                    	<i class="fas fa-file-pdf"></i>
                    	<?=$this->lang->line('pdf')?>
                    </button>
                  </div>
	              </div>
	              <!-- /.card-header -->
	              <div class="card-body purchase_list">
	             		<table class="table table-bordered table-striped" id="purchase-data">
		                <thead>
                              <tr>
                                <th width="2%">Sr.No</th>
                                <th>Invoice Date</th>
                                <th>Invoice No</th>
                                <th>Branch</th>
                                <th>Party Name</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Taxable Amount</th>
                                <th>Tax</th>
                                <th>Total Amount</th>
                              </tr>
                        </thead>
		               <tbody>
                                  <?php
                                    $total_taxable_value 	= 0.0;
                                    $total_tax_amt 			= 0.0;
                                    $total_grand 			= 0.0;
                                    $i = 1;
                                
                                    if(!empty($purchases))
                                    {
                                      foreach ($purchases as $value) 
                                      {
                                        // 1. Fetch tax data (This was missing and caused the blank screen)
                                        $purchase_tax = $this->purchase_model->get_purchase_tax_individual($value->id);
                                        
                                        $item_tax = 0;
                                        if($purchase_tax !== null) {
                                            $item_tax = (float)$purchase_tax->igst_tax + (float)$purchase_tax->cgst_tax + (float)$purchase_tax->sgst_tax;
                                        }
                                
                                        // 2. Identify variables from your specific query
                                        // Some systems use 'total_taxable_value', others use 'taxable_value'
                                        $row_taxable = isset($value->taxable_value) ? $value->taxable_value : $value->total_taxable_value;
                                        $row_total   = isset($value->subtotal) ? $value->subtotal : $value->total;
                                
                                        // 3. Add to footer totals
                                        $total_taxable_value += $row_taxable;
                                        $total_tax_amt       += $item_tax;
                                        $total_grand         += $row_total;
                                  ?>
                                  <tr>                        
                                    <td><?= $i++; ?></td>
                                    <td><?= date('d-m-Y', strtotime($value->purchase_date)); ?></td>
                                    <td><?= $value->invoice_no; ?></td>
                                    <td><?= $value->warehouse_name; ?></td>
                                    <td><?= $value->company_name; ?></td>
                                    <td><?= $value->product_name; ?></td>
                                    <td><?= $value->quantity; ?></td>
                                    <!--<td class="text-right"><?= number_format_i($value->cost); ?></td>-->
                                    <td class="text-right">
                                    <?php 
                                        // Logic: Rate = Taxable Amount divided by Quantity
                                        $row_taxable = isset($value->taxable_value) ? $value->taxable_value : $value->total_taxable_value;
                                        $calculated_rate = ($value->quantity > 0) ? ($row_taxable / $value->quantity) : 0;
                                        echo number_format_i($calculated_rate); 
                                    ?>
                                    </td>
                                    <td class="text-right"><?= number_format_i($row_taxable); ?></td>
                                    <td class="text-right"><?= number_format_i($item_tax); ?></td>
                                    <td class="text-right"><?= number_format_i($row_total); ?></td>
                                  </tr>
                                  <?php  
                                      }
                                    }
                                    else
                                    {
                                  ?>
                                  <tr>
                                    <td colspan="11" class="text-center">No Record Found</td>
                                  </tr>
                                  <?php
                                    }
                                  ?>
                        </tbody>
		                <tfoot class="footer_data">
                              <tr>
                                <th colspan="8" class="text-right">Total</th>
                                <th class="text-right"><?= number_format_i($total_taxable_value) ?></th>
                                <th class="text-right"><?= number_format_i($total_tax_amt) ?></th>
                                <th class="text-right"><?= number_format_i($total_grand) ?></th>
                              </tr>
                            </tfoot>
		              </table>
	              </div>
	            </div>
	            <!-- /.card -->
	          </div>
		    	</div>

		    </section>
		    <!-- /.content -->
	  	</div>
	    <aside class="control-sidebar control-sidebar-dark"></aside>
	    
  	</div>
  
<?php $this->load->view('layout/footer'); ?>

<script type="text/javascript">
	$(document).ready(function(e){
		$('#submitPurchaseReport').click(function(e){
			// e.preventDefault();

			var action_type = $('#action_type').val();

			if(action_type == 'export')
			{
				return true;
			}
			else if(action_type == 'pdf')
			{
				return true;
			}
			else if(action_type == 'print')
			{
				return true;
			}
			else
			{
				e.preventDefault();

				var formData = $('#purchaseReportForm').serialize();
				$(this).text('<?=$this->lang->line('searching')?>');

				setTimeout(function(){ 

					$.ajax({
		        url: '<?php echo base_url("report/purchase") ?>',
		        type: 'POST',
		        dataType : 'json',
		        data: formData,                       
		      //  success: function (response) {
		      //    $('.purchase_list').html(response.purchases);
		      //    $('#submitPurchaseReport').text('<?=$this->lang->line('search')?>');
		      //  },
		      success: function (response) {
                    $('.purchase_list').html(response.purchases);
                    $('#submitPurchaseReport').text('<?=$this->lang->line('search')?>');
                    
                    // ADD THIS LINE to fix the Export button after filtering
                    $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
                },
		        error: function () 
		        { 
		          show_message('failure-header','Please contact the administrator if you are keep facing this issue.');   
		        }
		      });	
				}, 2000);
			}
		});

		$("#export-button").click(function () {
        // Initialize an empty array to store the data
        var data = [];

        // Get the table headers
        var headers = [];
        $("#purchase-data thead th").each(function () {
            headers.push('"' + $(this).text() + '"');
        });
        data.push(headers);

        $("#purchase-data tbody tr").each(function () {
            var rowData = [];
            $(this).find("td").each(function () {
                rowData.push('"' + $(this).text() + '"');
            });
            data.push(rowData);
        });

      // Get the table footer
    
      var footerData = [];
      $("#purchase-data tfoot th").each(function () {
          footerData.push('"' + $(this).text() + '"');
      });
      data.push(footerData);

        // Create a CSV content
        var csvContent = "data:text/csv;charset=utf-8,\n";
        data.forEach(function (rowArray) {
            var row = rowArray.join(",");
            csvContent += row + "\n";
        });

        // Create a download link and trigger click to download
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "PurchaseReport.csv");
        document.body.appendChild(link);

        link.click(); // Trigger the download
    });

		$('#export').click(function(e){
			$('#action_type').val('export');
			$('#submitPurchaseReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#print').click(function(e){
			$('#action_type').val('print');		
			$('#submitPurchaseReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#pdf').click(function(e){
			$('#action_type').val('pdf');
			$('#submitPurchaseReport').trigger('click');  
			$('#action_type').val('');
		});

		
		//Date range as a button
    $('#daterange-btn').daterangepicker({

        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        // startDate: moment().subtract(29, 'days'),
        // endDate  : moment()
      },
      function (start, end) {

      	// alert(start.format('DD-MM-Y') + ' - ' + end.format('DD-MM-Y'));
      	$('#from_date').val(start.format('DD-MM-Y'));
				$('#to_date').val(end.format('DD-MM-Y'));


        // $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
    });
	});
</script>