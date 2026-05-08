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
		            <li class="breadcrumb-item active"><?=$this->lang->line('header_stock_report')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
            <!-- general form elements disabled -->
	            <div class="card card-primary">
	              <div class="card-header">
	                <h3 class="card-title"><?=$this->lang->line('stock_report')?></h3>
	                <div class="card-tools d-none">
	                	<button type="button" class="btn btn-tool bt-danger" id="export">
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
	                <table class="table table-striped table-bordered" width="100%">
	                	<tr>
	                		<td width="30%">Total Purchase</td>
	                		<td><?=number_format_i($purchase_cost)?></td>
	                		<td><?=number_format_i($purchase_quantity)?></td>
	                	</tr>
	                	<tr>
	                		<td width="30%">Total Purchase Delivered</td>
	                		<td><?=number_format_i($purchase_delivered_cost)?></td>
	                		<td><?=number_format_i($purchase_quantity_delivered)?></td>
	                	</tr>
	                	<tr>
	                		<td width="30%">Total Purchase Return</td>
	                		<td><?=number_format_i($purchase_return_cost)?></td>
	                		<td><?=number_format_i($purchase_return_quantity)?></td>
	                	</tr>
	                	<tr>
	                		<td width="30%">Total Purchase Return Delivered</td>
	                		<td><?=number_format_i($purchase_return_delivered_cost)?></td>
	                		<td><?=number_format_i($purchase_return_quantity_delivered)?></td>
	                	</tr>
	                	<tr>
	                		<td width="30%">Total Purchase sold</td>
	                		<td><?=number_format_i($sale_cost)?></td>
	                		<td><?=number_format_i($sale_quantity)?></td>
	                	</tr>
	                	<tr>
	                		<td width="30%">Total Purchase sold return</td>
	                		<td><?=number_format_i($sale_return_cost)?></td>
	                		<td><?=number_format_i($sale_return_quantity)?></td>
	                	</tr>
	                	<tr>
	                		<td width="30%">Total Purchase Available</td>
	                		<td><?=number_format_i($stock_cost)?></td>
	                		<td><?=number_format_i($stock_quantity)?></td>
	                	</tr>
	                	<tr>
	                		<td width="30%">Total Sales (taxable)</td>
	                		<td></td>
	                	</tr>
	                	<tr>
	                		<td width="30%">GP (Gross Profit)</td>
	                		<td></td>
	                	</tr>
	                	<tr>
	                		<td width="30%">Profit Percentage</td>
	                		<td></td>
	                	</tr>
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


        // $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
    })
	});
</script>