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
		            <!--<li class="breadcrumb-item active"><?=$this->lang->line('sales_report')?></li>-->
		            <li class="breadcrumb-item active">Sales Register Report</li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
            <!-- general form elements disabled -->
            	<form role="form" id="saleReportForm" name="saleReportForm" method="POST" action="<?php echo base_url("report/sale") ?>" target="_blank">
	            	<div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title">
		                	<i class="fas fa-funnel-dollar"></i>
		                	<?=$this->lang->line('filter_report')?>
		                </h3>
		                <div class="card-tools">

		                  <!-- <div class="input-group">
		                    <button type="button" class="btn btn-default float-right" id="daterange-btn">
		                      <i class="far fa-calendar-alt"></i> Date range picker
		                      <i class="fas fa-caret-down"></i>
		                    </button>
		                  </div> -->
		            
	                    <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse">
	                    	<i class="fas fa-minus"></i>
	                    </button> -->
	                  </div>
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
                          <select class="form-control form-control-sm select2bs4 add-row" name="warehouse_id" id="warehouse_id" width="100%">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($warehouse as $value) {
                            ?>
                              <option value="<?=$value->id;?>" <?php echo set_select('warehouse_id', $value->id); ?>>
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
                          <label for="customer"><?=$this->lang->line('sale_customer')?></label>
                          <select class="form-control form-control-sm select2bs4 add-row" name="customer_id" id="customer_id" width="100%">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($customers as $value) {
                                $customer_label = trim(($value->first_name ?? '') . ' ' . ($value->last_name ?? '')) ?: ($value->email ?? '');
                            ?>
                              <option value="<?=$value->id;?>" <?php echo set_select('customer_id', $value->id); ?>>
                                <?= $customer_label; ?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_customer_id" class="error invalid-feedback"><?=form_error('customer_id');?></span>
                        </div>
	                    </div>
	                  </div>
		              </div>
	              	<!-- /.card-body -->
		              <div class="card-footer">
		              	<input type="hidden" name="action_type" id="action_type" value="">
		              	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		              	<button type="submit" name="submit" id="submitSaleReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
		              </div>
	            	</div>
	            </form>
	            <div class="card card-primary">
	              <div class="card-header">
	                <!--<h3 class="card-title"><?=$this->lang->line('sales_report')?></h3>-->
	                <h3 class="card-title">Sales Register Report</h3>
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
	             <div class="card-body sales_list">
     <table class="table table-bordered table-striped" id="sale-data">
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
            $total_taxable = 0;
            $total_tax = 0;
            $total_grand = 0;
            $i = 1;

            if(!empty($sales) && count($sales) > 0): 
                foreach($sales as $sale): 
                    // Calculate individual tax for this row
                    // $item_tax = (float)$sale->igst_tax + (float)$sale->cgst_tax + (float)$sale->sgst_tax;
                    
                    // // Identify row values (Handling possible different naming in queries)
                    // $row_taxable = isset($sale->taxable_value) ? $sale->taxable_value : ($sale->total_taxable_value / count($sales)); 
                    // $row_total = isset($sale->sub_total) ? $sale->sub_total : $sale->total;
                     $item_tax = (float)$sale->igst_tax + (float)$sale->cgst_tax + (float)$sale->sgst_tax;
        
        // 2. Identify Taxable Value
                    $row_taxable = (float)$sale->taxable_value;
                    
                    // 3. FIX: Calculate Total Amount manually (Taxable + Tax)
                    // This ensures it never shows 0 if the other two columns have values
                    $row_total = $row_taxable + $item_tax;
                    // Logic: Rate = Taxable Amount / Quantity
                    $rate = ($sale->quantity > 0) ? ($row_taxable / $sale->quantity) : 0;

                    $total_taxable += $row_taxable;
                    $total_tax     += $item_tax;
                    $total_grand   += $row_total;
            ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= !empty($sale->invoice_date) ? date('d-m-Y', strtotime($sale->invoice_date)) : 'N/A' ?></td>
                    <td>
                        <?php if(!empty($sale->invoice_no) && !empty($sale->sale_id)): ?>
                            <a href="<?= base_url('sale/pdf_request/' . base64_encode($sale->sale_id)) ?>" target="_blank"><?= $sale->invoice_no ?></a>
                        <?php else: ?>
                            <?= $sale->invoice_no ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $sale->warehouse_name ?></td>
                    <td><?= !empty($sale->customer_company_name) ? $sale->customer_company_name : $sale->customer_name ?></td>
                    <td><?= $sale->product_name ?></td>
                    <td><?= number_format($sale->quantity, 2) ?></td>
                    <td class="text-right"><?= number_format($rate, 2) ?></td>
                    <td class="text-right"><?= number_format($row_taxable, 2) ?></td>
                    <td class="text-right"><?= number_format($item_tax, 2) ?></td>
                    <td class="text-right"><?= number_format($row_total, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="11" class="text-center">No sales records found</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot class="footer_data">
            <tr>
                <th colspan="8" class="text-right">Total:</th>
                <th class="text-right"><?= number_format($total_taxable, 2) ?></th>
                <th class="text-right"><?= number_format($total_tax, 2) ?></th>
                <th class="text-right"><?= number_format($total_grand, 2) ?></th>
            </tr>
        </tfoot>
    </table>
</div>            </div>
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
		$('#submitSaleReport').click(function(e){
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

				var formData = $('#saleReportForm').serialize();
				$(this).text('<?=$this->lang->line('searching')?>');

				setTimeout(function(){ 

					$.ajax({
		        url: '<?php echo base_url("report/sale") ?>',
		        type: 'POST',
		        dataType : 'json',
		        data: formData,                       
		        success: function (response) {
		          $('.sales_list').html(response.sales);
		          $('#submitSaleReport').text('<?=$this->lang->line('search')?>');
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
        $("#sale-data thead th").each(function () {
            headers.push('"' + $(this).text() + '"');
        });
        data.push(headers);

        $("#sale-data tbody tr").each(function () {
            var rowData = [];
            $(this).find("td").each(function () {
                //rowData.push('"' + $(this).text() + '"');
				rowData.push('"' + $(this).text().replace(/"/g, '""') + '"');
            });
            data.push(rowData);
        });

      // Get the table footer
    
      var footerData = [];
      $("#sale-data tfoot th").each(function () {
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
        link.setAttribute("download", "SaleReport.csv");
        document.body.appendChild(link);

        link.click(); // Trigger the download
    });

		$('#export').click(function(e){
			$('#action_type').val('export');
			$('#submitSaleReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#print').click(function(e){
			$('#action_type').val('print');		
			$('#submitSaleReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#pdf').click(function(e){
			$('#action_type').val('pdf');
			$('#submitSaleReport').trigger('click');  
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
    })
	});
</script>