<?php $this->load->view('layout/header'); ?>

	<style type="text/css">
		.footer_data{
			font-size: 17px;
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
		            <li class="breadcrumb-item active"><?=$this->lang->line('sales_return_report')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
            <!-- general form elements disabled -->
            	<form role="form" id="salesReturnReportForm" name="salesReturnReportForm" method="POST" action="<?php echo base_url("report/sales_return") ?>" target="_blank">
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

										
	                    <div class="col-sm-2">
	                    	<div class="form-group">
                          <label for="customer"><?=$this->lang->line('sale_customer')?></label>
                          <select class="form-control form-control-sm select2bs4" name="customer_id" id="customer_id" width="100%" class="add-row">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($customers as $value) {
                            ?>
                              <option value="<?=$value->id;?>" <?php echo set_select('id', $value->id); ?>>
                                <?= $value->customer_name;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_customer_id" class="error invalid-feedback"><?=form_error('customer_id');?></span>
                        </div>
	                    </div>
	                    <div class="col-sm-2">
        <div class="form-group">
            <label>Select Status</label>
            <select class="form-control select2bs4" name="status">
                <option value="">All Status</option>
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
                <option value="partial">Partial</option>
            </select>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-group">
            <label>Select Type</label>
            <select class="form-control" name="type">
                <option value="">All Types</option>
                <option value="credit_note">Credit Note</option>
            </select>
        </div>
    </div>
	                    
	                  </div>
		              </div>
	              	<!-- /.card-body -->
		              <div class="card-footer">
		              	<input type="hidden" name="action_type" id="action_type" value="">
		              	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		              	<button type="submit" name="submit" id="submitsalesReturnReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
		              </div>
	            	</div>
	            </form>
	            <div class="card card-primary">
	              <div class="card-header">
	                <h3 class="card-title"><?=$this->lang->line('sales_return_report')?></h3>
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
	              <div class="card-body sales_return_list">
	                <table class="table table-bordered table-striped" id="sales-return-data">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th width="2%">Sr.No</th>
                <th>Invoice Date</th>
                <th>Invoice No</th>
                <th>Customer Name</th>
                <th>GST No</th>
                <th class="text-right">Taxable Amount</th>
                <th class="text-right">CGST</th>
                <th class="text-right">SGST</th>
                <th class="text-right">IGST</th>
                <th class="text-right">Total Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1; 
            $t_taxable = 0; $t_cgst = 0; $t_sgst = 0; $t_igst = 0; $t_grand = 0;
            if(!empty($sales_return)): 
                foreach($sales_return as $row): 
                    $t_taxable += (float)$row->taxable_amount;
                    $t_cgst    += (float)$row->total_cgst;
                    $t_sgst    += (float)$row->total_sgst;
                    $t_igst    += (float)$row->total_igst;
                    $t_grand   += (float)$row->total_amount;

                    // Calculation for Status
                    $paid = $this->transaction_model->get_total_transaction_amount($row->return_id, SALE_RETURN_MODULE, PAYMENT_TRANSACTION_TYPE);
                    if($paid >= $row->total_amount) $status = '<span class="badge badge-success">Paid</span>';
                    elseif($paid > 0) $status = '<span class="badge badge-warning">Partial</span>';
                    else $status = '<span class="badge badge-danger">Unpaid</span>';
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= date('d-m-Y', strtotime($row->invoice_date)) ?></td>
                <td>
                    <a href="<?=base_url('sales_return/view/'.base64_encode($row->return_id))?>" target="_blank">
                        <?= $row->invoice_no ?>
                    </a>
                </td>
                <td><?= !empty($row->customer_company_name) ? $row->customer_company_name : $row->customer_name ?></td>
                <td><?= !empty($row->gst_no) ? $row->gst_no : 'N/A' ?></td>
                <td class="text-right"><?= number_format($row->taxable_amount, 2) ?></td>
                <td class="text-right"><?= number_format($row->total_cgst, 2) ?></td>
                <td class="text-right"><?= number_format($row->total_sgst, 2) ?></td>
                <td class="text-right"><?= number_format($row->total_igst, 2) ?></td>
                <td class="text-right"><b><?= number_format($row->total_amount, 2) ?></b></td>
                <td class="text-center"><?= $status ?></td>
            </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="11" class="text-center">No Records Found</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot class="footer_data" style="background-color: #f39c12; color: white;">
            <tr>
                <th colspan="5" class="text-right">Total:</th>
                <th class="text-right"><?= number_format($t_taxable, 2) ?></th>
                <th class="text-right"><?= number_format($t_cgst, 2) ?></th>
                <th class="text-right"><?= number_format($t_sgst, 2) ?></th>
                <th class="text-right"><?= number_format($t_igst, 2) ?></th>
                <th class="text-right"><?= number_format($t_grand, 2) ?></th>
                <th></th>
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
		$('#submitsalesReturnReport').click(function(e){
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

				var formData = $('#salesReturnReportForm').serialize();
				$(this).text('<?=$this->lang->line('searching')?>');

				setTimeout(function(){ 

					$.ajax({
		        url: '<?php echo base_url("report/sales_return") ?>',
		        type: 'POST',
		        dataType : 'json',
		        data: formData,                       
		        success: function (response) {
		          $('.sales_return_list').html(response.sales_return);
		          $('#submitsalesReturnReport').text('<?=$this->lang->line('search')?>');
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
        $("#sales-return-data thead th").each(function () {
            headers.push('"' + $(this).text() + '"');
        });
        data.push(headers);

        $("#sales-return-data tbody tr").each(function () {
            var rowData = [];
            $(this).find("td").each(function () {
                rowData.push('"' + $(this).text() + '"');
            });
            data.push(rowData);
        });

      // Get the table footer
    
      var footerData = [];
      $("#sales-return-data tfoot th").each(function () {
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
        link.setAttribute("download", "SalesReturnReport.csv");
        document.body.appendChild(link);

        link.click(); // Trigger the download
    });

		$('#export').click(function(e){
			$('#action_type').val('export');
			$('#submitsalesReturnReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#print').click(function(e){
			$('#action_type').val('print');		
			$('#submitsalesReturnReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#pdf').click(function(e){
			$('#action_type').val('pdf');
			$('#submitsalesReturnReport').trigger('click');  
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
    })
	});
</script>