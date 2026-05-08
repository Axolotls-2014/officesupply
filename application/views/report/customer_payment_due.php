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

		            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_customer_payment_due')?></a></li>
		            <li class="breadcrumb-item active"><?=$this->lang->line('customer_payment_due_report')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

        <?php

          $f_invoice_amount = 0;
          $f_pending_amount = 0;
          $f_paid_amount    = 0;
          

          if(sizeof($customers) > 0)
          {
            foreach ($customers as $value) 
            {
              $f_total_amount = 0;
              $f_total_paid_amount = 0;
              $f_total_pending_amount = 0;
              $f_total_negative_due_amount = 0; 
              $sales = $this->sale_model->get_sale_records_by_customer_id($value->id);
              
              $today = new DateTime(); // Current date

              $f_isThereAnyPaymentDue = false;

              foreach ($sales as $vlue) 
              {
                $paid_amount = $this->transaction_model->get_total_transaction_amount($vlue->id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE)+ $this->transaction_model->get_total_transaction_amount($vlue->id,SALE_MODULE,CREDIT_TRANSACTION_TYPE);

                

                $due_amount   = $vlue->total -$paid_amount;
                $dueDate 			= new DateTime($vlue->due_date);
                
                $interval 		= $today->diff($dueDate);
                $no_of_days 	= $interval->days;

                if ($dueDate < $today) 
                  $no_of_days *= -1;
                else
                  $no_of_days = '(<span class="text-primary">'.$no_of_days.' days</span>)';

                if($no_of_days < 0 and $due_amount >= 0.99)
                {

                  $f_total_amount           += $vlue->total;
                  $f_total_pending_amount   += $due_amount;  
                  $f_total_paid_amount      += $paid_amount;

                  $f_isThereAnyPaymentDue = true;
                }
              }

              if($f_isThereAnyPaymentDue == true)
              {
                $f_invoice_amount  += $f_total_amount;
                $f_paid_amount     += $f_total_paid_amount;
                $f_pending_amount  += $f_total_pending_amount;
        
              }
            }
          }
        ?>

        <section class="content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-6">
                  <!-- small box -->
                  <div class="info-box bg-secondary">
                    <div class="info-box-content">
                      <span class="info-box-text">Total Invoice Amount</span>
                      <span class="info-box-number footer_data"><?=number_format_i($f_invoice_amount)?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                </div>
                <div class="col-lg-4 col-6">
                  <!-- small box -->
                  <div class="info-box bg-secondary">
                    <div class="info-box-content">
                      <span class="info-box-text">Total Paid Amount</span>
                      <span class="info-box-number footer_data"><?=number_format_i($f_paid_amount)?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-6">
                  <!-- small box -->
                  <div class="info-box bg-secondary">
                    <div class="info-box-content">
                      <span class="info-box-text">Total Pending Amount</span>
                      <span class="info-box-number footer_data"><?=number_format_i($f_pending_amount)?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  
                </div>
                <!-- ./col -->
            </div>
          </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
		            <div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title"><?=$this->lang->line('customer_payment_due_report')?></h3>
		                <div class="card-tools">
		                	<button type="button" class="btn btn-tool bt-danger" id="export-button">
	                    	<i class="fas fa-file-export"></i>
                    		<?=$this->lang->line('export')?>
	                    </button>
	                    <!-- <button type="button" class="btn btn-tool" id="print">
	                    	<i class="fas fa-print"></i>
                    	<?=$this->lang->line('print')?>
	                    </button>
	                    <button type="button" class="btn btn-tool" id='pdf'>
	                    	<i class="fas fa-file-pdf"></i>
	                    	<?=$this->lang->line('pdf')?>
	                    </button> -->
	                  </div>
		              </div>
		              <!-- /.card-header -->
		              <div class="card-body expense_list">
		                <table class="sticky-header-table table table-bordered table-striped" id="customer-due-payment-data">
			                <thead>
			                  <tr>
			                    <th><?=$this->lang->line('customer_name')?></th>
                          <th><?=$this->lang->line('customer_invoice_amount')?></th>
                          <th><?=$this->lang->line('customer_paid_amount')?></th>
                          <th>Pending Amount</th>
			                  </tr>
			                </thead>
			                <tbody>
			                  <?php

                          $t_invoice_amount = 0;
                          $t_pending_amount = 0;
                          $t_paid_amount    = 0;
                          

			                    if(sizeof($customers) > 0)
			                    {
			                      foreach ($customers as $value) 
			                      {
                              $total_amount = 0;
                              $total_paid_amount = 0;
                              $total_pending_amount = 0;
                              $total_negative_due_amount = 0; 
                              $sales = $this->sale_model->get_sale_records_by_customer_id($value->id);
                              
                              $today = new DateTime(); // Current date

                              $isThereAnyPaymentDue = false;
    
                              foreach ($sales as $vlue) 
                              {
                                $paid_amount = $this->transaction_model->get_total_transaction_amount($vlue->id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE)+ $this->transaction_model->get_total_transaction_amount($vlue->id,SALE_MODULE,CREDIT_TRANSACTION_TYPE);

                                

                                $due_amount   = $vlue->total -$paid_amount;
                                $dueDate 			= new DateTime($vlue->due_date);
                                
                                $interval 		= $today->diff($dueDate);
                                $no_of_days 	= $interval->days;

                                if ($dueDate < $today) 
                                  $no_of_days *= -1;
                                else
                                  $no_of_days = '(<span class="text-primary">'.$no_of_days.' days</span>)';

                                if($no_of_days < 0 and $due_amount >= 0.99)
                                {

                                  $total_amount           += $vlue->total;
                                  $total_pending_amount   += $due_amount;  
                                  $total_paid_amount      += $paid_amount;

                                  $isThereAnyPaymentDue = true;
                                }
                                
                              }
			                      	 
                              

                              if($isThereAnyPaymentDue == true)
                              {
                                

                                $t_invoice_amount  += $total_amount;
                                $t_paid_amount     += $total_paid_amount;
                                $t_pending_amount  += $total_pending_amount;
                                
			                  ?>
			                  <tr class="customer_row" data-customer_id="<?=$value->id?>" style="cursor:pointer;">                       
			                    <td><?php echo $value->customer_name;?></td>
                          <td><?php echo number_format_i($total_amount);?></td>
                          <td><?php echo number_format_i($total_paid_amount);?></td>
                          <td><?php echo number_format_i($total_pending_amount);?></td>
			                  </tr>
			                  <?php  
                              }
			                      }
			                    }
			                    else
			                    {
			                  ?>
			                  <tr>
			                    <td colspan="4"><?=$this->lang->line('no_records_available')?></td>
			                  </tr>
			                  <?php
			                    }
			                  ?>
			                </tbody>
                      <tfoot>
		                  <tr style="font-size: 16px;font-weight: bolder;">
		                    <th><?=$this->lang->line('total')?></th>
		                    <th><?=number_format_i($t_invoice_amount)?></th>
		                    <th><?=number_format_i($t_paid_amount)?></th>
		                    <th><?=number_format_i($t_pending_amount)?></th>
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
		$(document).on('click', '.customer_row', function() {
		  var customer_id = $(this).data('customer_id');

		  var nextTr = $(this).next();

		  // Check if the new_customer_sale row already exists
		  if (nextTr.hasClass('new_customer_sale')) 
		  {
		    $('.new_customer_sale').remove();
		  }
		  else
		  {
		  	$('.new_customer_sale').remove();
			  var currentRow = $(this).closest('tr');
			  currentRow.after('<tr class="new_customer_sale"><td colspan="5"></td></tr>');

			  var newRow = currentRow.next('.new_customer_sale');
			  
			  $.ajax({
			    url: "<?php echo base_url('report/view_sale_list_by_customer_id') ?>",
			    type: "POST",
			    dataType: "JSON",
			    data: {
	        	'customer_id' : customer_id,
	        	'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
	        },
			    success: function(data) {
			    	// var data = JSON.parse(data);
			      newRow.find('td').html(data.sale_list_view);
			    },
			    error: function(xhr, status, error) {
			    	alert();
			      console.log(error);
			    }
			  });
		  }
		});

    $("#export-button").click(function () {
      // Initialize an empty array to store the data
      var data = [];

      // Get the table headers
      var headers = [];
      $("#customer-due-payment-data thead th").each(function () {
          headers.push('"' + $(this).text() + '"');
      });
      data.push(headers);

      // Get the table rows with the "customer_row" class
      $("#customer-due-payment-data tbody tr.customer_row").each(function () {
          var rowData = [];
          $(this).find("td").each(function () {
              rowData.push('"' + $(this).text() + '"');
          });
          data.push(rowData);
      });

      // Get the table footer
      var footerData = [];
      $("#customer-due-payment-data tfoot th").each(function () {
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
      link.setAttribute("download", "CustomerPaymentDue.csv");
      document.body.appendChild(link);

      link.click(); // Trigger the download
    });
	});
  
</script>