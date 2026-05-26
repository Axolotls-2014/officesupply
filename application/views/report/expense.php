<?php $this->load->view('layout/header'); ?>

		<style type="text/css">
			.footer_data{
				font-size: 20px;
			}
			.expense_list {
                max-height: 700px;
                overflow: auto;
                padding: 0 !important;
            }
            #expense-data thead th {
                position: sticky !important; top: 0;
                background-color: #343a40 !important; color: white !important;
                z-index: 1000; white-space: nowrap; box-shadow: inset 0 -1px 0 #dee2e6;
            }
            #expense-data tfoot th {
                position: sticky !important; bottom: 0;
                background-color: #f39c12 !important; color: white !important;
                z-index: 1000; font-size: 18px; box-shadow: inset 0 1px 0 #dee2e6;
            }
            #expense-data { border-collapse: separate !important; border-spacing: 0 !important; width: 100%; }
            .text-right { text-align: right !important; }
		</style>
  	<div class="wrapper">
	  	<div class="content-wrapper">
		    <section class="content-header">
		      <div class="row mb-2">
		        <div class="col-sm-12">
		          <ol class="breadcrumb breadcrumb-custom float-sm-left">
<li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>

		            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_expense')?></a></li>
		            <li class="breadcrumb-item active"><?=$this->lang->line('expense_report')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
		    				<form role="form" id="expenseReportForm" name="expenseReportForm" method="POST" action="<?php echo base_url("report/expense") ?>" target="_blank">
		            <!-- general form elements disabled -->
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
		                        <label for="customer"><?=$this->lang->line('supplier_name')?></label>
		                        <select class="form-control form-control-sm select2bs4" name="supplier_id" id="supplier_id" width="100%" class="add-row">
		                          <option value=""><?=$this->lang->line('select')?></option>
		                         <?php
		                            foreach ($suppliers as $value) {
		                          ?>
		                            <option value="<?=$value->id;?>" <?php echo set_select('supplier_id', $value->id); ?>>
		                              <?= ucfirst($value->company_name);?>
		                            </option>
		                          <?php 
		                            }
		                          ?>
		                        </select>
		                        <span id="err_supplier_id" class="error invalid-feedback"><?=form_error('supplier_id');?></span>
		                      </div>
		                    </div>
		                    <div class="col-sm-3">
		                    	<div class="form-group">
		                        <label for="customer"><?=$this->lang->line('expense_category_name')?></label>
		                        <select class="form-control form-control-sm select2bs4" name="expense_category_id" id="expense_category_id" width="100%" class="add-row">
		                          <option value=""><?=$this->lang->line('select')?></option>
		                         <?php
		                            foreach ($expense_categories as $value) {
		                          ?>
		                            <option value="<?=$value->id;?>" <?php echo set_select('expense_expense_category', $value->id); ?>>
		                              <?= ucfirst($value->name);?>
		                            </option>
		                          <?php 
		                            }
		                          ?>
		                        </select>
		                        <span id="err_expense_category_id" class="error invalid-feedback"><?=form_error('expense_category_id');?></span>
		                      </div>
		                    </div>
		                  </div>
										</div>
			              <!-- /.card-body -->
			              <div class="card-footer">
			              	<input type="hidden" name="action_type" id="action_type" value="">
		              		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		              		<button type="submit" name="submit" id="submitExpenseReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
			              </div>
			            </div>
			          </form>
		            <div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title"><?=$this->lang->line('expense_report')?></h3>
		                <div class="card-tools">
		                	<button type="button" class="btn btn-tool bt-danger" id="export">
	                    	<i class="fas fa-file-export"></i>
                    		<?=$this->lang->line('export')?>
	                    </button>
	                    <button type="button" class="btn btn-tool" id="print">
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
		              <div class="card-body expense_list p-0">
                            <table class="table table-bordered table-striped m-0" id="expense-data">
                                <thead>
                                    <tr>
                                        <th>Bill Date</th>
                                        <th>Expense Category</th>
                                        <th>Expense Name</th>
                                        <th>Paid To</th>
                                        <th>Payment Mode</th>
                                        <th class="text-right">Taxable Amount(₹)</th>
                                        <th class="text-right">CGST(₹)</th>
                                        <th class="text-right">SGST(₹)</th>
                                        <th class="text-right">IGST(₹)</th>
                                        <th class="text-right">Total Amount(₹)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $t_taxable = 0; $t_cgst = 0; $t_sgst = 0; $t_igst = 0; $t_grand = 0;
                                    $filter_status = $this->input->post('status');
                        
                                    if (!empty($expenses)) {
                                        foreach ($expenses as $value) {
                                            // 1. Calculate Tax Values
                                            $cgst_amt = ($value->amount * (float)$value->cgst) / 100;
                                            $sgst_amt = ($value->amount * (float)$value->sgst) / 100;
                                            $igst_amt = ($value->amount * (float)$value->igst) / 100;
                                            $row_total = (float)$value->total_amount;
                        
                                            // 2. Identify Payment Mode Label
                                            $modes = [0=>"Cash", 1=>"Card", 2=>"Cheque", 3=>"NEFT/RTGS"];
                                            $p_mode = isset($modes[$value->payment_mode]) ? $modes[$value->payment_mode] : "N/A";
                        
                                            // 3. Payment Status Logic
                                            $paid = round($this->transaction_model->get_total_transaction_amount($value->id, 'E', 'P'), 2);
                                            if($paid >= round($row_total, 2)) $curr_status = 'Paid';
                                            elseif($paid > 0) $curr_status = 'Partial';
                                            else $curr_status = 'Unpaid';
                        
                                            // 4. Status Filter Check
                                            if (!empty($filter_status) && $filter_status !== $curr_status) continue;
                        
                                            // Sum totals for Footer
                                            $t_taxable += $value->amount;
                                            $t_cgst    += $cgst_amt;
                                            $t_sgst    += $sgst_amt;
                                            $t_igst    += $igst_amt;
                                            $t_grand   += $row_total;
                                    ?>
                                        <tr>
                                            <td><?= date('d-m-Y', strtotime($value->date)); ?></td>
                                            <td><?= $value->expense_category_name; ?></td>
                                            <td><?= $value->name; // or $value->description ?></td>
                                            <td><?= $value->company_name; ?></td>
                                            <td><?= $p_mode; ?></td>
                                            <td class="text-right"><?= number_format($value->amount, 2); ?></td>
                                            <td class="text-right"><?= number_format($cgst_amt, 2); ?></td>
                                            <td class="text-right"><?= number_format($sgst_amt, 2); ?></td>
                                            <td class="text-right"><?= number_format($igst_amt, 2); ?></td>
                                            <td class="text-right"><strong><?= number_format($row_total, 2); ?></strong></td>
                                        </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="10" class="text-center">No Record Found</td></tr>';
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="footer_data">
                                    <tr>
                                        <th colspan="5" class="text-right">Total:</th>
                                        <th class="text-right"><?= number_format($t_taxable, 2) ?></th>
                                        <th class="text-right"><?= number_format($t_cgst, 2) ?></th>
                                        <th class="text-right"><?= number_format($t_sgst, 2) ?></th>
                                        <th class="text-right"><?= number_format($t_igst, 2) ?></th>
                                        <th class="text-right"><?= number_format($t_grand, 2) ?></th>
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
		$('#submitExpenseReport').click(function(e){
			// e.preventDefault();

			var action_type = $('#action_type').val();

			if(action_type == 'export')
			{
				return true;
			}
			else if(action_type == 'print')
			{
				return true;
			}
			else if(action_type == 'pdf')
			{
				return true;
			}
			else
			{
				e.preventDefault();

				var formData = $('#expenseReportForm').serialize();
				$(this).text('<?=$this->lang->line('searching')?>');

				setTimeout(function(){ 

					$.ajax({
		        url: '<?php echo base_url("report/expense") ?>',
		        type: 'POST',
		        dataType : 'json',
		        data: formData,                       
		        success: function (response) {
		          $('.expense_list').html(response.expenses);
		          $('#submitExpenseReport').text('<?=$this->lang->line('search')?>');
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
			$('#submitExpenseReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#print').click(function(e){
			$('#action_type').val('print');
			$('#submitExpenseReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#pdf').click(function(e){
			$('#action_type').val('pdf');
			$('#submitExpenseReport').trigger('click');  
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