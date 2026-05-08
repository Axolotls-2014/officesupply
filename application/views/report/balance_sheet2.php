<?php $this->load->view('layout/header'); ?>

<style type="text/css">
			.footer_data{
				font-size: 20px;
			}
      .warning-data{
        background-color: #FF7F50 !important;
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
		            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
		            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_reports')?></a></li>
		            <li class="breadcrumb-item active"><?=$this->lang->line('header_balance_sheet')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">

          <div class="col-md-4">
          <h3>Current Assets</h3>
    <table>
        <tr>
            <th>Description</th>
            <th>Amount</th>
        </tr>
        <tr>
            <td>Cash in Hand</td>
            <td>$<?php echo number_format($cash_in_hand, 2); ?></td>
        </tr>
        <tr>
            <td>Bank Accounts</td>
            <td>$<?php echo number_format($bank_account_total, 2); ?></td>
        </tr>
        <tr>
            <td>Trade Receivables</td>
            <!-- <td>$<?php echo number_format($total_trade_receivables, 2); ?></td> -->
             <td>0</td>
        </tr>
        <!-- Add rows for other current assets -->
        <tr>
            <td>Total Current Assets</td>
            <td>$<?php echo number_format($total_current_assets, 2); ?></td>
        </tr>
    </table>
            <!-- /.card -->
          </div>
		    		<!-- <div class="col-md-12">
              <div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title">Current Assets</h3>
		              </div>
		             
		              <div class="card-body">
		                <table class="sticky-header-table table table-bordered table-striped">
			                <thead>
			                  <tr>
			                    <th>Description</th>
			                    <th>Amount</th>
                        </tr>
			                </thead>
			                <tbody>
			                  <tr>
                          <td>Trade Payables</td>
                          <td>$<?php echo number_format($trade_payables, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Other Payables</td>
                            <td>$<?php echo number_format($other_payables, 2); ?></td>
                        </tr>
                        <tr>
                            <td>PDC Payable</td>
                            <td>$<?php echo number_format($pdc_payable, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Accrued Expenses</td>
                            <td>$<?php echo number_format($accrued_expenses, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Short-term Loans and Borrowings</td>
                            <td>$<?php echo number_format($short_term_loans, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Total Current Liabilities</td>
                            <td>$<?php echo number_format($total_current_liabilities, 2); ?></td>
                        </tr>
			                </tbody>
                     
			              </table>
		              </div>
		            </div>
	            </div>
             
	          </div> -->

            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Non-Current Assets</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="sticky-header-table table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Property, Plant, and Equipment</td>
                        <td>$<?php echo number_format($property_plant_equipment, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Intangible Assets</td>
                          <td>$<?php echo number_format($intangible_assets, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Investments</td>
                          <td>$<?php echo number_format($investments, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Other Long-term Assets</td>
                          <td>$<?php echo number_format($other_long_term_assets, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Total Non-Current Assets</td>
                          <td>$<?php echo number_format($total_non_current_assets, 2); ?></td>
                      </tr>
                    </tbody>
                    
                  </table>
                </div>
              </div>
	          </div>

            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Total Assets</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="sticky-header-table table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Total Assets</th>
                        <th>$<?php echo number_format($total_assets, 2); ?></th>
                      </tr>
                    </thead>
                 
                  </table>
                </div>
              </div>
	          </div>

            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Current Liabilities</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="sticky-header-table table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                          <td>Trade Payables</td>
                          <td>$<?php echo number_format($trade_payables, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Other Payables</td>
                          <td>$<?php echo number_format($other_payables, 2); ?></td>
                      </tr>
                      <tr>
                          <td>PDC Payable</td>
                          <td>$<?php echo number_format($pdc_payable, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Accrued Expenses</td>
                          <td>$<?php echo number_format($accrued_expenses, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Short-term Loans and Borrowings</td>
                          <td>$<?php echo number_format($short_term_loans, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Total Current Liabilities</td>
                          <td>$<?php echo number_format($total_current_liabilities, 2); ?></td>
                      </tr>
                    </tbody>
                    
                  </table>
                </div>
              </div>
	          </div>
            
            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Non-Current Liabilities</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="sticky-header-table table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                          <td>Long-term Loans and Borrowings</td>
                          <td>$<?php echo number_format($long_term_loans, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Deferred Tax Liabilities</td>
                          <td>$<?php echo number_format($deferred_tax_liabilities, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Other Long-term Liabilities</td>
                          <td>$<?php echo number_format($other_long_term_liabilities, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Total Non-Current Liabilities</td>
                          <td>$<?php echo number_format($total_non_current_liabilities, 2); ?></td>
                      </tr>
                    </tbody>
                    
                  </table>
                </div>
              </div>
	          </div>
            
            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Total Liabilities</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="sticky-header-table table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Total Liabilities</th>
                        <th>$<?php echo number_format($total_liabilities, 2); ?></th>
                      </tr>
                    </thead>
                   
                  </table>
                </div>
              </div>
	          </div>

            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Shareholder's Equity</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="sticky-header-table table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                          <td>Share Capital</td>
                          <td>$<?php echo number_format($share_capital, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Retained Earnings</td>
                          <td>$<?php echo number_format($retained_earnings, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Additional Paid-in Capital</td>
                          <td>$<?php echo number_format($additional_paid_in_capital, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Other Reserves</td>
                          <td>$<?php echo number_format($other_reserves, 2); ?></td>
                      </tr>
                      <tr>
                          <td>Total Shareholder's Equity</td>
                          <td>$<?php echo number_format($total_equity, 2); ?></td>
                      </tr>
                    </tbody>
                    
                  </table>
                </div>
              </div>
	          </div>

            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Total Liabilities and Shareholder's Equity</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="sticky-header-table table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Total Liabilities and Shareholder's Equity</th>
                        <th>$<?php echo number_format($total_liabilities_equity, 2); ?></th>
                      </tr>
                    </thead>
                   
                  </table>
                </div>
              </div>
	          </div>
            

          

		    	</div>

          
				</section>
		    <!-- /.content -->
	  	</div>
	    <aside class="control-sidebar control-sidebar-dark"></aside>
	    
  	</div>
  
<?php $this->load->view('layout/footer'); ?>

