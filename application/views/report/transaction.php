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
		            <li class="breadcrumb-item active"><?=$this->lang->line('header_transaction')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>
        <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
                <form role="form" id="transactionReportForm" name="transactionReportForm" method="POST" action="<?php echo base_url("report/transaction") ?>" target="_blank">
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
                              <input type="text" class="form-control datepicker" name="from_date" id="from_date" style="z-index:999 !important" readonly="readonly">
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
                              <input type="text" class="form-control datepicker" name="to_date" id="to_date" value="<?=date('d-m-Y')?>"  readonly="readonly">
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-group">
                            <label for="ledger_id"><?=$this->lang->line('ledger')?></label>
                            <select class="form-control form-control-sm select2bs4" name="ledger_id" id="ledger_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('ledger')?>">
                              <option value=""><?=$this->lang->line('select')?></option>
                              <?php
                                foreach ($ledger_account as $value) 
                                {	
                              ?>
                                <option value="<?=$value->id;?>">
                                  <?=$value->title.' - '.$value->group_title?>
                                </option>
                              <?php 
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-group">
                            <label for="mode">Mode</label>
                            <select class="form-control form-control-sm select2bs4" name="mode" id="mode" width="100%" class="add-row">
                              <option value="">All</option>
                              <option value="<?=CASH_MODE?>">Cash</option>
                              <option value="<?=CREDIT_CARD_MODE?>">Credit Card</option>
                              <option value="<?=CHEQUE_MODE?>">Cheque</option>
                              <option value="<?=NEFT_MODE?>">NEFT</option>
                              <option value="<?=CREDIT_MODE?>">Credit</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-group">
                            <label for="mode">Type</label>
                            <select class="form-control form-control-sm select2bs4" name="type" id="type" width="100%" class="add-row">
                              <option value="">All</option>
                              <option value="<?=PAYMENT_TRANSACTION_TYPE?>">Payment</option>
                              <option value="<?=RECEIPT_TRANSACTION_TYPE?>">Receipt</option>
                              <option value="<?=CREDIT_TRANSACTION_TYPE?>">Credit</option>
                              <option value="<?=CONTRA_TRANSACTION_TYPE?>">Contra</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-group">
                            <label for="mode">Module</label>
                            <select class="form-control form-control-sm select2bs4" name="module" id="module" width="100%" class="add-row">
                              <option value="">All</option>
                              <option value="<?=SALE_MODULE?>">Sale</option>
                              <option value="<?=PURCHASE_RETURN_MODULE?>">Purchase Return</option>
                              <option value="<?=SALE_RETURN_MODULE?>">Sale Return</option>
                              <option value="<?=EXPENSE_MODULE?>">Expense</option>
                              <option value="<?=BANK_MODULE?>">Bank</option>
                              <option value="<?=PURCHASE_MODULE?>">Purchase</option>
                              <option value="<?=CREDIT_DEBIT_NOTE_MODULE?>">Credit Debit Note</option>
                              <option value="<?=CASH_BANK_MODULE?>">Cash Bank</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-group">
                            <label for="mode">Reference no</label>
                            <input type="text" class="form-control" name="reference_no" id="reference_no">
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-group">
                            <label for="mode">Narration</label>
                            <input type="text" class="form-control" name="narration" id="narration">
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                      <input type="hidden" name="action_type" id="action_type" value="">
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                      <button type="submit" name="submit" id="submitTransaction" class="btn btn-info"><?=$this->lang->line('search')?></button>
                    </div>
                  </div>
                </form>
		            <div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title"><?=$this->lang->line('header_transaction')?></h3>
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
		              <div class="card-body transaction_list">
		                <table class="sticky-header-table table table-bordered table-striped" id="transaction-data">
			                <thead>
			                  <tr>
			                    <th>Voucher Date</th>
                          <th>Module</th>
                          <th>From Account</th>
                          <th>Reference No</th>
                          <th>Narration</th>
                          <th>Mode</th>
                          <th>Amount</th>
			                  </tr>
			                </thead>
			                <tbody>
			                  <?php

                          $total_amount = 0;
                          
			                    if(sizeof($transactions) > 0)
			                    {
			                      foreach ($transactions as $value) 
			                      {
                              $total_amount += $value->amount;
			                  ?>
			                  <tr>                       
			                    <td><?php echo $value->voucher_date;?></td>
                          <td>
                            <?php 
                              if($value->module == SALE_MODULE)
                                echo 'Sale';
                              else if($value->module == PURCHASE_RETURN_MODULE)
                                echo 'Purchase Return';
                              else if($value->module == SALE_RETURN_MODULE)
                                echo 'Sale Return';
                              else if($value->module == EXPENSE_MODULE)
                                echo 'Expense';
                              else if($value->module == BANK_MODULE)
                                echo 'Bank';
                              else if($value->module == PURCHASE_MODULE)
                                echo 'Purchase';
                              else if($value->module == CREDIT_DEBIT_NOTE_MODULE)
                                echo 'Credit Debit Note';
                              else if($value->module == CASH_BANK_MODULE)
                                echo 'Cash Bank';
                            ?>
                          </td>
                          <td><?php echo $value->title;?></td>
                          <td><?php echo $value->reference_no;?></td>
                          <td><?php echo $value->narration;?></td>
                          <td>
                            <?php
                              if($value->mode == 0)
                                echo 'Cash';
                              else if($value->mode == 1)
                                echo 'Credit Card';
                              else if($value->mode == 2)
                                echo 'Cheque';         
                              else if($value->mode == 3)
                                echo 'NEFT/RTGS';                            
                            ?>
                          </td>
                          <td><?php echo number_format_i($value->amount);?></td>
			                  </tr>
			                  <?php 
                            }
			                    }
			                    else
			                    {
			                  ?>
			                  <tr>
			                    <td colspan="7"><?=$this->lang->line('no_records_available')?></td>
			                  </tr>
			                  <?php
			                    }
			                  ?>
			                </tbody>
                      <tfoot>
                        <tr style="font-size: 16px;font-weight: bolder;">
                          <th><?=$this->lang->line('total')?></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th><?=number_format_i($total_amount)?></th>
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

    $('#submitTransaction').click(function(e){
			// e.preventDefault();

			var isError = false;

			var action_type = $('#action_type').val();

			if(action_type == 'pdf')
			{
				$('form#transactionReportForm .field_validation').each(function() {
          
	          var id    = $(this).attr('id');
	          var value = $(this).val();
	          var field = $(this).attr('placeholder');

	          if(value==null || value==""){
	            $("form#transactionReportForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
	            $('form#transactionReportForm #'+id).addClass('is-invalid');
	            isError = true;
	          }
	          else
	          {
	            $("form#transactionReportForm #err_"+id).text("").fadeOut('slow');
	            $('form#transactionReportForm #'+id).removeClass('is-invalid');
	            $('form#transactionReportForm #'+id).addClass('is-valid');
	          }
	      });


	      if(isError == true)
	      {
	        return false;
	      }  
	      else 
	      {
					return true;
				}
			}
			else if(action_type == 'print')
			{
				$('form#transactionReportForm .field_validation').each(function() {
          
	          var id    = $(this).attr('id');
	          var value = $(this).val();
	          var field = $(this).attr('placeholder');

	          if(value==null || value==""){
	            $("form#transactionReportForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
	            $('form#transactionReportForm #'+id).addClass('is-invalid');
	            isError = true;
	          }
	          else
	          {
	            $("form#transactionReportForm #err_"+id).text("").fadeOut('slow');
	            $('form#transactionReportForm #'+id).removeClass('is-invalid');
	            $('form#transactionReportForm #'+id).addClass('is-valid');
	          }
	      });

	      if(isError == true)
	      {
	        return false;
	      }  
	      else 
	      {
					return true;
				}
			}
			else
			{
				e.preventDefault();

				$('form#transactionReportForm .field_validation').each(function() {
          
	          var id    = $(this).attr('id');
	          var value = $(this).val();
	          var field = $(this).attr('placeholder');

	          if(value==null || value==""){
	            $("form#transactionReportForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
	            $('form#transactionReportForm #'+id).addClass('is-invalid');
	            isError = true;
	          }
	          else
	          {
	            $("form#transactionReportForm #err_"+id).text("").fadeOut('slow');
	            $('form#transactionReportForm #'+id).removeClass('is-invalid');
	            $('form#transactionReportForm #'+id).addClass('is-valid');
	          }
	      });


	      if(isError == true)
	      {
	        return false;
	      }  
	      else 
	      {
	        var formData = $('#transactionReportForm').serialize();
					$(this).text('<?=$this->lang->line('searching')?>');

					setTimeout(function(){ 

						$.ajax({
			        url: '<?php echo base_url("report/transaction") ?>',
			        type: 'POST',
			        dataType : 'json',
			        data: formData,                       
			        success: function (response) {
			          $('.transaction_list').html(response.transaction);
			          $('#submitTransaction').text('<?=$this->lang->line('search')?>');
			        },
			        error: function () 
			        { 
			          show_message('failure-header','Please contact the administrator if you are keep facing this issue.');   
			        }
			      });	
					}, 2000);
	      }

				
			}
		});

    $("#export-button").click(function () {
      // Initialize an empty array to store the data
      var data = [];

      // Get the table headers
      var headers = [];
      $("#transaction-data thead th").each(function () {
          headers.push('"' + $(this).text() + '"');
      });
      data.push(headers);

      // Get the table rows with the "supplier_row" class
      $("#transaction-data tbody tr").each(function () {
          var rowData = [];
          $(this).find("td").each(function () {
              rowData.push('"' + $(this).text() + '"');
          });
          data.push(rowData);
      });

      // Get the table footer
      var footerData = [];
      $("#transaction-data tfoot th").each(function () {
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
      link.setAttribute("download", "Transaction_Report.csv");
      document.body.appendChild(link);

      link.click(); // Trigger the download
    });

    $('#daterange-btn').daterangepicker({

      ranges   : {
        'Today'       : [moment(), moment()],
        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment().subtract(29, 'days'),
      endDate  : moment()
      },
      function (start, end) {

      // alert(start.format('DD-MM-Y') + ' - ' + end.format('DD-MM-Y'));
      $('#from_date').val(start.format('DD-MM-Y'));
      $('#to_date').val(end.format('DD-MM-Y'));
      // $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      });
	});
  
</script>