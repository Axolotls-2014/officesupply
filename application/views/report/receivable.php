<?php $this->load->view('layout/header'); ?>

	<style type="text/css">
		.footer_data{
			font-size: 20px;
		}

    /* Add a class to the table for styling */
    .sticky-header-table {
        width: 100%;
        border-collapse: collapse;
      }

      /* Style the table headers */
      .sticky-header-table th {
        background-color: #f0f0f0;
        position: sticky;
        top: 0;
        z-index: 2;
      }

      /* Style the table body */
      .sticky-header-table tbody {
        display: block;
        max-height: 500px; /* Set a maximum height to enable scrolling */
        overflow-y: auto; /* Enable vertical scrolling */
      }

      /* Style the table rows */
      .sticky-header-table tr {
        display: table;
        width: 100%;
        table-layout: fixed;
      }

      /* Increase the width of the scrollbar (for WebKit browsers) */
      .sticky-header-table tbody::-webkit-scrollbar {
        width: 12px; /* Adjust the width as needed */
      }

      /* Customize the scrollbar's track */
      .sticky-header-table tbody::-webkit-scrollbar-track {
        background: #f0f0f0; /* Track color */
      }

      /* Customize the scrollbar's thumb (the draggable part) */
      .sticky-header-table tbody::-webkit-scrollbar-thumb {
        background: #888; /* Thumb color */
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
		            <li class="breadcrumb-item active"><?=$this->lang->line('header_receivable')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
            	
	            <div class="card card-primary">
	              <div class="card-header">
	                <h3 class="card-title"><?=$this->lang->line('header_receivable')?></h3>
	                <div class="card-tools">
	                	<!-- <button type="button" class="btn btn-tool bt-danger" id="export">
                    	<i class="fas fa-file-export"></i>
                    	<?=$this->lang->line('export')?>
                    </button> -->
                    <a href="<?php echo base_url("report/receivable/print")?>" type="button" class="btn btn-tool" id='print'>
                    	<i class="fas fa-print"></i>
                    	<?=$this->lang->line('print')?>
                    </a>
                    <a href="<?php echo base_url("report/receivable/pdf")?>" type="button" class="btn btn-tool" id='pdf'>
                    	<i class="fas fa-file-pdf"></i>
                    	<?=$this->lang->line('pdf')?>
                    </a>
                  </div>
	              </div>
	              <!-- /.card-header -->
	              <div class="card-body sales_list">
	                <table id="example" class="sticky-header-table table table-bordered table-striped">
		                <thead>
		                  <tr>
		                    <th><?=$this->lang->line('customer_name')?></th>
		                    <th><?=$this->lang->line('customer_gstin')?></th>
		                    <th><?=$this->lang->line('customer_email')?></th>
		                    <th><?=$this->lang->line('customer_phone')?></th>
		                    <th><?=$this->lang->line('customer_country_id')?></th>
		                    <th><?=$this->lang->line('customer_state_id')?></th>
		                    <th><?=$this->lang->line('customer_invoice_amount')?></th>
		                    <th><?=$this->lang->line('customer_paid_amount')?></th>
		                    <th><?=$this->lang->line('customer_receivable')?></th>
		                  </tr>
		                </thead>
		                
		                <tbody>
		                	<?php 

		                		$total_total_amount = 0;
		                		$total_paid_amount  = 0;
		                		$total_receivable_amount  = 0;

		                		foreach ($customers as $row) {

		                			$total_amount = $this->transaction_model->get_total_transaction_amount_by_customer($row->id, SALE_MODULE, SALE_TRANSACTION_TYPE);
													$paid_amount = $this->transaction_model->get_total_transaction_amount_by_customer($row->id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE)+ $this->transaction_model->get_total_transaction_amount($row->id,SALE_MODULE,CREDIT_TRANSACTION_TYPE);

													$total_total_amount += $total_amount;
													$total_paid_amount += $paid_amount;

													$total_receivable_amount += (($total_amount-$paid_amount));

													$receivable_amount = $total_amount-$paid_amount;
													if($receivable_amount > 0)
													{

		                	?>
		                			<tr>
		                				<td><?=$row->customer_name?></td>
            				        <td><?=$row->gstin?></td>
										        <td><?=$row->email?></td>
										        <td><?=$row->phone?></td>
										        <td><?=$row->country_name?></td>
										        <td><?=$row->state_name?></td>
										        <td><?=($total_amount) ? number_format_i($total_amount) : "0.00"?></td>
										        <td><?=($paid_amount) ? number_format_i($paid_amount) : "0.00"?></td>
										        <td><?=number_format_i(($total_amount-$paid_amount))?></td>
		                			</tr>
		                	<?php
													}
		                		}
		                	?>
		                </tbody>
		                <tfoot>
		                  <tr>
		                    <th colspan="6"><?=$this->lang->line('total')?></th>
		                    <th><?=number_format_i($total_total_amount)?></th>
		                    <th><?=number_format_i($total_paid_amount)?></th>
		                    <th><?=number_format_i($total_receivable_amount)?></th>
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