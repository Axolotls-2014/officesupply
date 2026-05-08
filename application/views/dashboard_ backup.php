
<?php $this->load->view('layout/header'); ?>
  	<div class="wrapper">
	  	<div class="content-wrapper">
		    <!-- Content Header (Page header) -->
		    <div class="content-header">
		      	<div class="container-fluid">
			        <div class="row mb-2">
			          	<div class="col-sm-6">
				            <h1 class="m-0 text-dark"><?=$this->lang->line('dashboard')?></h1>
			        	</div><!-- /.col -->
				        <div class="col-sm-6">
				            <ol class="breadcrumb float-sm-right">
				              	<li class="breadcrumb-item"><a href="#">Home</a></li>
				              	<li class="breadcrumb-item active"><?=$this->lang->line('dashboard')?></li>
				            </ol>
				        </div><!-- /.col -->
			        </div><!-- /.row -->
		      	</div><!-- /.container-fluid -->
		    </div>
		    <!-- /.content-header -->
		   
		   	<section class="content">
		   		<div class="container-fluid">
		   			<style type="text/css">
		        	.info-box-icon{
		        		/*padding-left: 5px !important;*/
		        		margin-left: 5px !important;
		        		margin-right: 5px !important;
		        		background-color: #E8E8E8	!important;
		        	}
		        </style>
		        <div class="info-box">
		        	<?php 
                if($this->permission_model->has_permission('add_warehouse'))
                {
            	?>
              <span class="info-box-icon bg-light elevation-1" style="">
              	<a href="<?=base_url('warehouse/add')?>" data-tt="tooltip" title="<?=$this->lang->line('warehouse_add')?>"><i class="fas fa-warehouse text-red"></i></a>
              </span>
              <?php 
	              }
	          	?>

	          	<?php 
                if($this->permission_model->has_permission('add_product_category'))
                {
            	?>
              <span class="info-box-icon bg-light elevation-1" style="">
              	<a href="<?=base_url('product_category/add')?>" data-tt="tooltip" title="<?=$this->lang->line('product_category_add')?>"><i class="fas fa-shapes text-blue"></i></a>
              </span>
              <?php 
                }
            	?>

            	<?php 
                if($this->permission_model->has_permission('add_product'))
                {
            	?>
              <span class="info-box-icon bg-light elevation-1" style="">
              	<a href="<?=base_url('product/add')?>" data-tt="tooltip" title="<?=$this->lang->line('product_add')?>"><i class="fas fa-dice-d20 text-green"></i></a>
              </span>
              <?php 
                }
            	?>

            	<?php 
                if($this->permission_model->has_permission('add_purchase'))
                {
            	?>
              <span class="info-box-icon bg-light elevation-1" style="">
              	<a href="<?=base_url('purchase/add')?>" data-tt="tooltip" title="<?=$this->lang->line('purchase_add')?>"><i class="fas fa-shopping-bag text-yellow"></i></a>
              </span>
              <?php 
                }
            	?>

            	<?php 
                if($this->permission_model->has_permission('add_sale'))
                {
            	?>
              <span class="info-box-icon bg-light elevation-1" style="">
              	<a href="<?=base_url('sale/add')?>" data-tt="tooltip" title="<?=$this->lang->line('sale_add')?>"><i class="fas fa-store text-red"></i></a>
              </span>
              <?php 
                }
            	?>

            	<?php 
                if($this->permission_model->has_permission('add_quotation'))
                {
            	?>
              <span class="info-box-icon bg-light elevation-1" style="">
              	<a href="<?=base_url('quotation/add')?>" data-tt="tooltip" title="<?=$this->lang->line('quotation_add')?>"><i class="fas fa-file-invoice-dollar text-green"></i></a>
              </span>
              <?php 
                }
            	?>

            	<?php 
                if($this->permission_model->has_permission('add_bank_account'))
                {
            	?>
              <span class="info-box-icon bg-light elevation-1" style="">
              	<a href="<?=base_url('bank_account/add')?>" data-tt="tooltip" title="<?=$this->lang->line('bank_account_add')?>"><i class="fas fa-piggy-bank text-yellow"></i></a>
              </span>
              <?php 
                }
            	?>

            	<?php 
                if($this->permission_model->has_permission('add_expense'))
                {
            	?>
              <span class="info-box-icon bg-light elevation-1" style="">
              	<a href="<?=base_url('expense/add')?>" data-tt="tooltip" title="<?=$this->lang->line('expense_add')?>"><i class="fab fa-codepen text-green"></i></a>
              </span>
              <?php 
                }
            	?>

            	<?php 
                if($this->permission_model->has_permission('add_supplier'))
                {
            	?>
              <span class="info-box-icon bg-light elevation-1" style="">
              	<a href="<?=base_url('supplier/add')?>" data-tt="tooltip" title="<?=$this->lang->line('supplier_add')?>"><i class="fas fa-male text-yellow"></i></a>
              </span>
              <?php 
                }
            	?>

            	<?php 
                if($this->permission_model->has_permission('add_tax'))
                {
            	?>
              <span class="info-box-icon bg-light elevation-1" style="">
              	<a href="<?=base_url('tax/add')?>" data-tt="tooltip" title="<?=$this->lang->line('tax_add')?>"><i class="fas fa-file-invoice text-green"></i></a>
              </span>
              <?php 
                }
            	?>

            	<?php 
                if($this->permission_model->has_permission('add_expense_category'))
                {
            	?>
              <span class="info-box-icon bg-light elevation-1" style="">
              	<a href="<?=base_url('expense_category/add')?>" data-tt="tooltip" title="<?=$this->lang->line('expense_category_add')?>"><i class="fas fa-receipt text-yellow"></i></a>
              </span>
              <?php 
                }
            	?>
            </div>
		   		</div>
		   	</section>
		    <!-- Main content -->
		    <section class="content">
		      	<div class="container-fluid">
			        <!-- Small boxes (Stat box) -->
			        <div class="row">
			          	<div class="col-lg-3 col-6">
				            <!-- small box -->
				            <div class="small-box bg-warning">
			              	<div class="inner">
				                <h3><?=sizeof($customers)?></h3>

				                <p><?=$this->lang->line('customers')?></p>
			              	</div>
			              	<div class="icon">
			                	<i class="nav-icon fas fa-user-friends"></i>
			              	</div>
			              	<a href="<?=base_url('customer')?>" class="small-box-footer">
			              		<?=$this->lang->line('more_info')?>
			              		<i class="fas fa-arrow-circle-right"></i>
			              	</a>
				            </div>
			          	</div>
			          	<div class="col-lg-3 col-6">
				            <!-- small box -->
				            <div class="small-box bg-info">
			              	<div class="inner">
				                <h3><?=($purchase_amount == '') ? '0.00' : $purchase_amount;;?><sup style="font-size: 20px"><?=$default_currency->currency_symbol?></sup></h3>
				                <p><?=$this->lang->line('purchase_header')?></p>
			              	</div>
			              	<div class="icon">
			                	<i class="nav-icon fas fa-shopping-bag"></i>
			              	</div>
			              	<a href="<?=base_url('purchases')?>" class="small-box-footer">
			              		<?=$this->lang->line('more_info')?>
			              		<i class="fas fa-arrow-circle-right"></i>
			              	</a>
				            </div>
			          	</div>
			          	<!-- ./col -->
			          	<div class="col-lg-3 col-6">
				            <!-- small box -->
				            <div class="small-box bg-success">
			              	<div class="inner">
				                <h3><?=($expense_amount == '') ? '0.00' : $expense_amount;;?><sup style="font-size: 20px"><?=$default_currency->currency_symbol?></sup></h3>
				                <p><?=$this->lang->line('expenses')?></p>
			              	</div>
			              	<div class="icon">
				                <i class="nav-icon fas fa-money-check-alt"></i>
			              	</div>
			              	<a href="<?=base_url('expense')?>" class="small-box-footer">
			              		<?=$this->lang->line('more_info')?>
			              		<i class="fas fa-arrow-circle-right"></i>
			              	</a>
				            </div>
			          	</div>
			          	<!-- ./col -->
			          	
			          	<!-- ./col -->
			          	<div class="col-lg-3 col-6">
				            <!-- small box -->
				            <div class="small-box bg-danger">
			              	<div class="inner">
			                	<h3><?=($sale_amount == '') ? '0.00' : $sale_amount;?><sup style="font-size: 20px"><?=$default_currency->currency_symbol?></sup></h3>
				                <p><?=$this->lang->line('sales')?></p>
			              	</div>
			              	<div class="icon">
			                	<i class="nav-icon fas fa-store"></i>
			              	</div>
			              	<a href="<?=base_url('sale')?>" class="small-box-footer">
			              		<?=$this->lang->line('more_info')?>
			              		<i class="fas fa-arrow-circle-right"></i>
			              	</a>
				            </div>
			          	</div>
			          <!-- ./col -->
			        </div>
			        <!-- /.row -->
			        
			        <div class="row">
			        	<div class="col-lg-12">
			            <div class="card">
		              	<div class="card-header border-0">
			                <div class="d-flex justify-content-between">
			                  <h3 class="card-title">Sales & Expenses & Purchase - <?=date('Y')?></h3>
			                  <!-- <a href="javascript:void(0);">View Report</a> -->
			                  <div class="float-right">
			                  	<table width="100%">
			                  		<tr>
			                  			<td style="padding: 5px;">
			                  				<div style="width: 10px; height: 10px; background-color: #007bff;"></div>
			                  			</td>
			                  			<td style="padding: 5px;">
			                  				Sales
			                  			</td>
			                  			<td style="padding: 5px;">
			                  				<div style="width: 10px; height: 10px; background-color: #ced4da;"></div>
			                  			</td>
			                  			<td style="padding: 5px;">
			                  				Expense
			                  			</td>
			                  			<td style="padding: 5px;">
			                  				<div style="width: 10px; height: 10px; background-color: #b9e817;"></div>
			                  			</td>
			                  			<td style="padding: 5px;">
			                  				Purchase
			                  			</td>
			                  		</tr>
			                  	</table>
			                  </div>
			                </div>
		              	</div>
		              	<div class="card-body">
			                <div class="d-flex">
			                	<p class="d-flex flex-column">
				                    <span class="text-bold text-lg"><?=$this->session->userdata('currency_symbol')?> <?=number_format((float)array_sum($sales_data), 2, '.', '')?></span>
				                    <span>Sales Over Time</span>
			                  	</p>

			                  	<p class="d-flex flex-column" style="padding-left: 20px;">
				                    <span class="text-bold text-lg"><?=$this->session->userdata('currency_symbol')?> <?=number_format((float)array_sum($expense_data), 2, '.', '')?></span>
				                    <span>Expense Over Time</span>
			                  	</p>

			                  	<p class="d-flex flex-column" style="padding-left: 20px;">

				                    <span class="text-bold text-lg"><?=$this->session->userdata('currency_symbol')?> <?=number_format((float)array_sum($purchase_data), 2, '.', '')?></span>
				                    <span>Purchase Over Time</span>
			                  	</p>

			                  	<p class="ml-auto d-flex flex-column text-right">
			                  		<?php 

			                  			// echo '<pre>';
			                  			// print_r($sales_data[0]);
				                  		

				                  		if(((int)date('m')- 2) > 0 )
				                  		{
				                  			$current_month 	=  	$sales_data[(int)date('m')-1];
				                  			$past_month 		= 	$sales_data[(int)date('m')- 2]; 
				                  			$growth 				= 	0;

				                  			if($past_month != 0)
				                  			{
				                  				$growth	=   (($current_month - $past_month)/$past_month)*100;
				                  			}

				                  			

				                  			if($growth >= 0)
				                  			{
				                  	?>

						                    <span class="text-success">
						                      <i class="fas fa-arrow-up"></i> <?=number_format((float)$growth, 2, '.', '')?>%
						                    </span>
						                <?php 
						                		}
						                		else
						                		{
						               	?>
						               			<span class="text-danger">
						                      <i class="fas fa-arrow-down"></i> <?=number_format((float)$growth, 2, '.', '')?>%
						                    </span>
						               	<?php
						                		}
						                ?>
					                    	<span class="text-muted">Since last month</span>
					                <?php 
					                	}
					                ?>
			                  	</p>
			                </div>
		                

			                <div class="position-relative mb-4">
			                  	<canvas id="sales-chart" height="200"></canvas>
			                </div>

			                <!-- <div class="d-flex flex-row justify-content-end">
			                  	<span class="mr-2">
			                    	<div class="form-check">
			                          	<input class="form-check-input chart_data_year" type="radio" name="year" value="2020" checked="checked">
			                          	<label class="form-check-label">This Year</label>
			                        </div> 
			                  	</span>

			                  	<span>
			                    	<div class="form-check">
			                          	<input class="form-check-input chart_data_year" type="radio" name="year" value="2019">
			                          	<label class="form-check-label">Last Year</label>
			                        </div> 
			                  	</span>
			                </div> -->
		              	</div>
			            </div>
			          	</div>
			        </div>
			        
			        <div class="row">
			        	<?php 
	                if($this->permission_model->has_permission('show_recent_sales'))
	                {
              	?>
			        	<div class="col-md-6">
			            <div class="card">
		              	<div class="card-header">
			                <h3 class="card-title">
												<i class="nav-icon fas fa-store"></i>
			                	<strong class="ml-1"><?=$this->lang->line('recent_sales')?></strong>
			                </h3>
			                <div class="card-tools">
			                	<button type="button" class="btn btn-outline-info btn-xs" onclick="window.location.href='<?=base_url('sale/add')?>'">
			                		<i class="fas fa-plus-circle"></i>
			                		<?=$this->lang->line('sale_add')?>
			                	</button>
			                </div>
		              	</div>
		              	<!-- /.card-header -->
		              	<div class="card-body p-0">
			                <table class="table table-striped">
			                  	<thead>
				                    <tr>
				                      	<th style="width: 10px">#</th>
				                      	<th><?=$this->lang->line('sale_reference_no')?></th>
				                      	<th><?=$this->lang->line('sale_customer')?></th>
				                      	<th><?=$this->lang->line('sale_total_discount')?></th>
				                      	<th><?=$this->lang->line('sale_total_tax')?></th>
				                      	<th><?=$this->lang->line('sale_total')?></th>
				                    </tr>
			                  	</thead>
			                  	<tbody>
			                  		<?php 
			                  			$i = 1;
			                  			if(sizeof($sales) > 0 )
			                  			{
			                  				foreach ($sales as $value) 
			                  				{
			                  					if($i < 6)
			                  					{
			                  		?>
			                  		<tr>
			                  			<td><?=$i++?></td>
			                  			<td><?=$value->reference_no?></td>
			                  			<td><?=$value->customer_name?></td>
			                  			<td><?=$value->total_discount?></td>
			                  			<td><?=$value->total_tax?></td>
			                  			<td><?=$value->total?></td>
			                  		</tr>
			                  		<?php 
			                  					}
			                  				}
			                  			}
			                  			else
			                  			{
			                  		?>
		                  			<tr>
			                  			<td colspan="6">No Record(s) are available.</td>
			                  		</tr>
			                  		<?php
			                  			}
			                  		?>
			                  	</tbody>
			                </table>
		              	</div>
		              	<!-- /.card-body -->
			            </div>
			            <!-- /.card -->
			          </div>
			        	<?php 
				        	}
				        ?>  	

				        <?php 
			                if($this->permission_model->has_permission('show_recent_expenses'))
			                {
		              	?>
			          	<div class="col-md-6">
				            <div class="card">
			              	<div class="card-header">
				                <h3 class="card-title">
													<i class="nav-icon fas fa-money-check-alt"></i>
				                	<strong class="ml-1"><?=$this->lang->line('recently_added_expenses')?></strong>
				                </h3>
				                <div class="card-tools">
				                	<button type="button" class="btn btn-outline-info btn-xs" onclick="window.location.href='<?=base_url('expense/add')?>'">
				                		<i class="fas fa-plus-circle"></i>
				                		<?=$this->lang->line('expense_add')?>
				                	</button>
				                </div>
			              	</div>
			              	<div class="card-body p-0">
				                <table class="table table-striped">
				                  	<thead>
					                    <tr>
					                      	<th style="width: 10px">#</th>
					                      	<th><?=$this->lang->line('expense_date')?></th>
					                      	<th><?=$this->lang->line('expense_expense_category')?></th>
					                      	<th width="40%"><?=$this->lang->line('expense_total_amount')?></th>
					                    </tr>
				                  	</thead>
				                  	<tbody>
				                  		<?php 
				                  			$i = 1;
				                  			if(sizeof($expenses) > 0)
				                  			{
				                  				foreach ($expenses as $value) 
				                  				{
				                  					if($i < 6)
				                  					{
				                  		?>
				                  		<tr>
				                  			<td><?=$i++?></td>
				                  			<td><?=date('d-m-Y', strtotime($value->created_date));?></td>
				                  			<td><?=$value->expense_category_name?></td>
				                  			<td><?=$value->total_amount?></td>
				                  		</tr>
				                  		<?php
				                  					} 
				                  				}
				                  			}
				                  			else
				                  			{
				                  		?>
				                  		<tr>
				                  			<td colspan="4">No Record(s) are available.</td>
				                  			
				                  		</tr>
				                  		<?php 
				                  			}
				                  		?>
				                  	</tbody>
				                </table>
			              	</div>
				            </div>
			          	</div>
			          	<?php 
			              	}
		              	?>

		              	<?php 
			                if($this->permission_model->has_permission('show_recent_customers'))
			                {
		              	?>
			          	<div class="col-md-6">
				            <div class="card">
			              	<div class="card-header">
				                <h3 class="card-title">
													<i class="nav-icon fas fa-user-friends"></i>	
				                	<strong class="ml-1"><?=$this->lang->line('recently_added_customers')?></strong>
				                </h3>
				                <div class="card-tools">
				                	<button type="button" class="btn btn-outline-info btn-xs" data-toggle="modal" data-target="#add_customer_modal" accesskey="c">
				                		<i class="fas fa-plus-circle"></i>
				                		<?=$this->lang->line('customer_add')?>
				                	</button>
				                </div>
			              	</div>
			              	<div class="card-body p-0">
				                <table class="table table-striped">
				                  	<thead>
					                    <tr>
					                      	<th style="width: 10px">#</th>
					                      	<th><?=$this->lang->line('customer_name')?></th>
					                      	<th><?=$this->lang->line('customer_email')?></th>
					                      	<th><?=$this->lang->line('customer_phone')?></th>
					                    </tr>
				                  	</thead>
				                  	<tbody>
				                  		<?php 
				                  			$i = 1;
				                  			if(sizeof($customers) > 0)
				                  			{
				                  				foreach ($customers as $value) 
				                  				{
				                  					if($i < 6)
				                  					{
				                  		?>
				                  		<tr>
				                  			<td><?=$i++?></td>
				                  			<td><?=$value->customer_name?></td>
				                  			<td><?=$value->email?></td>
				                  			<td><?=$value->phone?></td>
				                  		</tr>
				                  		<?php 
				                  					}
				                  				}
				                  			}
				                  			else
				                  			{
				                  		?>
			                  			<tr>
				                  			<td colspan="4">No Record(s) are available.</td>
				                  		</tr>
				                  		<?php
				                  			}
				                  		?>
				                  	</tbody>
				                </table>
			              	</div>
				            </div>
				            <!-- /.card -->
			          	</div>
			          	<?php 
			              	}
		              	?>

		              	<?php 
			                if($this->permission_model->has_permission('show_recent_products'))
			                {
		              	?>
			          	<div class="col-md-6">
				            <div class="card">
				              	<div class="card-header">
					                <h3 class="card-title">
														<i class="nav-icon far fa-snowflake"></i>
					                	<strong class="ml-1"><?=$this->lang->line('recently_added_products')?></strong>
					                </h3>
					                <div class="card-tools">
					                	<button type="button" class="btn btn-outline-info btn-xs"  data-toggle="modal" data-target="#add_product_modal" accesskey="s">
					                		<i class="fas fa-plus-circle"></i>
					                		<?=$this->lang->line('product_add')?>
					                	</button>
					                </div>
				              	</div>
				              	<!-- /.card-header -->
				              	<div class="card-body p-0">
					                <table class="table table-striped">
					                  	<thead>
						                    <tr>
						                      	<th style="width: 10px">#</th>
						                      	<th>Name</th>
						                      	<th>Description</th>
						                      	<th>Tax</th>
						                    </tr>
					                  	</thead>
					                  	<tbody>

					                  		<?php 
					                  			$i = 1;
					                  			
					                  			if(sizeof($products) > 0)
					                  			{
						                  			foreach ($products as $value) 
						                  			{
						                  				if($i < 6)
					                  					{
					                  		?>
						                  		<tr>
						                  			<td><?=$i++?></td>
						                  			<td><?=$value->name?></td>
						                  			<td><?=$value->description?></td>
						                  			<td>
						                  				<a href="#" data-tt="tooltip" title="<?='IGST = '.$value->igst.' CGST = '.$value->igst.' SGST = '.$value->sgst?>">
						                  					<?=$value->tax_name?>
					                  					</a>
						                  			</td>
						                  		</tr>
					                  		<?php 
					                  					}
					                  				}
					                  			}
					                  			else
					                  			{
					                  		?>
					                  			<tr>
					                  				<td colspan="4">
					                  					<?=$this->lang->line('no_records_available')?>
					                  				</td>
					                  			</tr>
					                  		<?php
					                  			}
					                  		?>
					                  	</tbody>
					                </table>
				              	</div>
				              	<!-- /.card-body -->
				            </div>
				            <!-- /.card -->
			          	</div>
			          	<?php 
			              	}
		              	?>
			        </div>
			        
			        
			        
		      	</div><!-- /.container-fluid -->
		    </section>
		    <!-- /.content -->
	  	</div>
	    <aside class="control-sidebar control-sidebar-dark"></aside>
	    
  	</div>
  	<!-- <div style="position: absolute;bottom: 15%; left: 5px; width: 260px;z-index: 1111111" id="shortcut-modal">
        <div class="row">
          	<div class="col-md-12">
	            <div class="card card-outline card-primary">
	              	<div class="card-header" style="cursor: pointer">
	                	<h3 class="card-title">Shortcuts for adding Records</h3>
		                <div class="card-tools">
		                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
		                  </button>
		                </div>
	              	</div>
	              	<div class="card-body" style="font-size: 14px;">
		                <div class="row">
		                  <div class="col-md-9">
		                    <?=$this->lang->line('expense_category_add')?>
		                  </div>
		                  <div class="col-md-3">
		                    ALT + C
		                  </div>
		                </div>
		                <div class="row">
		                  <div class="col-md-9">
		                    <?=$this->lang->line('merchant_add')?>
		                  </div>
		                  <div class="col-md-3">
		                    ALT + M
		                  </div>
		                </div>
	              	</div>
	            </div>
          	</div>
        </div>
    </div> -->
<?php $this->load->view('layout/footer'); ?>
<?php $this->load->view('customer/add_customer_modal'); ?>
<?php $this->load->view('service/add_service_modal'); ?>



<script type="text/javascript">
	var ticksStyle = {
	    fontColor: '#495057',
	    fontStyle: 'bold'
	};

	var mode      = 'index';
	var intersect = true;

	var $salesChart = $('#sales-chart');
	var salesChart  = new Chart($salesChart, {
		    type   : 'bar',
		    data   : {
		      labels  : ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
		      datasets: [
		        {
		          backgroundColor: '#007bff',
		          borderColor    : '#007bff',
		          data           : [<?=implode(",",array_values($sales_data))?>]
		        },
		        {
		          backgroundColor: '#ced4da',
		          borderColor    : '#ced4da',
		          data           : [<?=implode(",",array_values($expense_data))?>]
		        },
		        {
		          backgroundColor: '#b9e817',
		          borderColor    : '#b9e817',
		          data           : [<?=implode(",",array_values($purchase_data))?>]
		        }
		      ]
			},
		    options: {
		      maintainAspectRatio: false,
		      tooltips           : {
		        mode     : mode,
		        intersect: intersect
		      },
		      hover              : {
		        mode     : mode,
		        intersect: intersect
		      },
		      legend             : {
		        display: false
		      },
		      scales             : {
		        yAxes: [{
		          // display: false,
		          gridLines: {
		            display      : true,
		            lineWidth    : '4px',
		            color        : 'rgba(0, 0, 0, .2)',
		            zeroLineColor: 'transparent'
		          },
		          ticks    : $.extend({
		            beginAtZero: true,

		            // Include a dollar sign in the ticks
		            callback: function (value, index, values) {
		              if (value >= 1000) {
		                value /= 1000
		                value += 'k'
		              }
		              return '<?=$this->session->userdata("currency_symbol")?>' + value
		            }
		          }, ticksStyle)
		        }],
		        xAxes: [{
		          display  : true,
		          gridLines: {
		            display: false
		          },
		          ticks    : ticksStyle
		        }]
		      }
		    }
	  	});

	function updateChart(chart_data)
	{
		salesChart.destroy();
		salesChart.data.datasets[0].data.push(chart_data.sales_data);
  	salesChart.data.datasets[1].data.push(chart_data.expense_data);
  	salesChart.data.datasets[2].data.push(chart_data.purhcase_data);

  	salesChart.update();
	}

	$(document).ready(function(e){

		$('.chart_data_year').click(function(e){
			var year = $('input[name="year"]:checked').val();

			$.ajax({
        url: '<?php echo base_url("auth/get_sale_expense_chart_data") ?>',
        type: 'POST',
        dataType : 'json',
        data: {
        	'year' : year,
        	'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },                       
        success: function (response) {
        	updateChart(response);
        },
        error: function () { 
          show_message('failure-header','Please contact the administrator if you are keep facing this issue.');   
        }
	    });
		});
	});
</script>