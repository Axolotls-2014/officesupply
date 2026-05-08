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
<li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>

		            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_reports')?></a></li>
		            <li class="breadcrumb-item active"><?=$this->lang->line('header_balance_sheet')?></li>
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
		                <h3 class="card-title">Balance Sheet</h3>
                    <div class="card-tools">
                    
                      <a href="<?php echo base_url("report/balance_sheet/pdf")?>" type="button" class="btn btn-tool" id='pdf'>
                        <i class="fas fa-file-pdf"></i>
                        <?=$this->lang->line('pdf')?>
                      </a>
                    </div>
		              </div>
		             
		              <div class="card-body">
		                <table class="table table-bordered">
			                <thead>
			                  <tr style="background-color: #f0f0f0;">
			                    <th colspan="2">Current Assets</th>
                          <th colspan="2">Non-Current Assets</th>
                        </tr>
			                </thead>
			                <tbody>
                        <tr>
                          <td width="30%">Cash in Hand</td>
                          <td width="20%">₹<?php echo number_format($cash_in_hand, 2); ?></td>
                          <td width="30%">Property, Plant, and Equipment</td>
                          <td width="20%">₹<?php echo number_format($property_plant_equipment, 2); ?></td>
                        </tr>
                        <tr>
                          <td width="30%">Bank Accounts</td>
                          <td width="20%">₹<?php echo number_format($pdc_payable, 2); ?></td>
                          <td width="30%">Intangible Assets</td>
                          <td width="20%">₹<?php echo number_format($intangible_assets, 2); ?></td>
                        </tr>
                        <tr>
                          <td width="30%">Trade Receivables</td>
                          <td width="20%">₹<?php echo number_format($accrued_expenses, 2); ?></td>
                          <td width="30%">Investments</td>
                          <td width="20%">₹<?php echo number_format($investments, 2); ?></td>
                        </tr>
                        <tr>
                          <td width="30%">PDC Receivable</td>
                          <td width="20%"><b>₹0</b></td>
                          <td width="30%">Other Long-term Assets</td>
                          <td width="20%">₹<?php echo number_format($other_long_term_assets, 2); ?></td>
                        </tr>
                        <tr>
                          <td width="30%"><b>Total Current Assets</b></td>
                          <td width="20%"></td>
                          <td width="30%"><b>Total Non-Current Assets</b></td>
                          <td width="20%"><b>₹<?php echo number_format($total_non_current_assets, 2); ?></b></td>
                        </tr>
                       
                        <!-- <tr>
                          <td width="30%">Total Assets</td>
                          <td width="20%">₹<?php echo number_format($total_assets, 2); ?></td>
                          <td width="30%"></td>
                          <td width="20%"></td>
                          
                        </tr> -->
                        <tr style="background-color: #f0f0f0;">
                          <th colspan="2">Current Liabilities</th>
                          <th colspan="2">Non-Current Liabilities</th>
                        </tr>
                        <tr>
                       
                          <td width="30%">Trade Payables</td>
                          <td width="20%">₹<?php echo number_format($trade_payables, 2); ?></td>
                          <td width="30%">Long-term Loans and Borrowings</td>
                          <td width="20%">₹<?php echo number_format($long_term_loans, 2); ?></td>
                          
                        </tr>

                        <tr>
                          <td width="30%">PDC Payable</td>
                          <td width="20%">₹<?php echo number_format($pdc_payable, 2); ?></td>
                          <td width="30%">Deferred Tax Liabilities</td>
                          <td width="20%">₹<?php echo number_format($deferred_tax_liabilities, 2); ?></td>
                          
                        </tr>

                        <tr>
                          <td width="30%">Accrued Expenses</td>
                          <td width="20%">₹<?php echo number_format($accrued_expenses, 2); ?></td>
                          <td width="30%">Other Long-term Liabilities</td>
                          <td width="20%">₹<?php echo number_format($other_long_term_liabilities, 2); ?></td>
                          
                        </tr>

                        <tr>
                          <td width="30%"><b>Total Current Liabilities</b></td>
                          <td width="20%"><b>₹<?php echo number_format($total_current_liabilities, 2); ?></b></td>
                          <td width="30%"><b>Total Non-Current Liabilities</b></td>
                          <td width="20%"><b>₹<?php echo number_format($total_non_current_liabilities, 2); ?></b></td>
                          
                        </tr>

                        <tr>
                          <td width="30%"></td>
                          <td width="20%"><b></td>
                          <td width="30%"><b>Total Liabilities</b></td>
                          <td width="20%"><b>₹<?php echo number_format($total_liabilities, 2); ?></b></td>
                          
                        </tr>

                        

                        <tr style="background-color: #f0f0f0;">
                          <th colspan="2">Shareholder's Equity</th>
                          <!-- <th colspan="2">Non-Current Liabilities</th> -->
                        </tr>
                      

                        <tr>
                          <td width="30%">Retained Earnings</td>
                          <td width="20%">₹<?php echo number_format($retained_earnings, 2); ?></td>
                          <!-- <td width="30%">Deferred Tax Liabilities</td>
                          <td width="20%">$<?php echo number_format($deferred_tax_liabilities, 2); ?></td> -->
                        </tr>


                        <tr>
                          <td width="30%">Other Reserves</td>
                          <td width="20%">₹<?php echo number_format($other_reserves, 2); ?></td>
                          <!-- <td width="30%">Other Long-term Liabilities</td>
                          <td width="20%">$<?php echo number_format($other_long_term_liabilities, 2); ?></td> -->
                        </tr>

                        <tr>
                          <td width="30%"><b>Total Shareholder's Equity</b></td>
                          <td width="20%"><b>₹<?php echo number_format($total_equity, 2); ?></b></td>
                          <!-- <td width="30%">Other Long-term Liabilities</td>
                          <td width="20%">$<?php echo number_format($other_long_term_liabilities, 2); ?></td> -->
                        </tr>

                        <tr>
                          <td width="30%"><b>Total Liabilities and Shareholder's Equity</b></td>
                          <td width="20%"><b>₹<?php echo number_format($total_liabilities_equity, 2); ?></b></td>
                          <!-- <td width="30%">Other Long-term Liabilities</td>
                          <td width="20%">$<?php echo number_format($other_long_term_liabilities, 2); ?></td> -->
                        </tr>

                        <tr style="background-color: #f0f0f0;">
                          <th colspan="4">The acoompanying notes are an integral part of this statement.</th>
                        </tr>


			                </tbody>
                     
			              </table>
		              </div>
		            </div>
	            </div>

          
          
				</section>
		    <!-- /.content -->
	  	</div>
	    <aside class="control-sidebar control-sidebar-dark"></aside>
	    
  	</div>
  
<?php $this->load->view('layout/footer'); ?>

