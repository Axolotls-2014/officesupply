<?php $this->load->view('layout/header'); ?>
<style>
    .code{
        background-color:#3B5478;
    }
</style>
  	<div class="wrapper">
	  	<div class="content-wrapper">
		    <!-- Content Header (Page header) -->
		    <div class="content-header">
		      	<div class="container-fluid">
			        <div class="row mb-2">
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
		        		padding-left: 5px !important;
		        		margin-left : 5px !important;
		        		margin-right: 5px !important;
		        		/*background-color: #E8E8E8	!important;*/
		        	}
		        	.bg{
		        	    background-color:#2B4367;
		        	}
		        </style>
		   		</div>
		   	</section>
		    <!-- Main content -->
		    <section class="content">
		      	<div class="container-fluid">
			        <!-- Small boxes (Stat box) -->
			        <div class="row text-light">
			          	<div class="col-lg-3 col-6">
				            <!-- small box -->
				            <div class="info-box bg">
				              <span class="info-box-icon"><i class="fa fa-user-friends"></i></span>
				              <div class="info-box-content">
				                <span class="info-box-text"><?=$this->lang->line('customers'); ?></span>
				                <span class="info-box-number"><?=sizeof($customers);?></span>
				                <div class="progress">
				                  <div class="progress-bar" style="width: 70%"></div>
				                </div>
				                <span class="progress-description" style="font-size:14px;">
				                  <?php 
				                  	echo $this->lang->line('customers').' : '.sizeof($customers);
				                  ?>
				                </span>
				              </div>
				              <!-- /.info-box-content -->
				            </div>
			          	</div>
			          	 	<!-- ./col -->
			          	 	<?php 
                                $user_id = $this->session->userdata('user_id');
                                $udata = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
                                if (empty($udata->branch_id)) {
                                    $sales_count = $this->db->count_all('sale'); 
                                } else {
                                    $sales_count = $this->db->where('warehouse_id', $udata->branch_id)->count_all_results('sale');
                                }
                            ?>
                            
			          	<div class="col-lg-3 col-6">
				            <!-- small box -->
				            <div class="info-box bg">
				              <span class="info-box-icon"><i class="fas fa-store"></i></span>

				              <div class="info-box-content">
				                <span class="info-box-text"><?=$this->lang->line('sales')?></span>
				                <span class="info-box-number">₹ <?=($sale_amount == '') ? '0.00' : number_format_i($sale_amount);?> | &nbsp;&nbsp;Sales Count: <?= $sales_count; ?></span>
				                <div class="progress">
				                  <div class="progress-bar" style="width: 70%"></div>
				                </div>
				                <span class="progress-description" style="font-size:14px;">
				                  <?php 
				                  	$total_sale	= $this->transaction_model->get_total_transaction_amount(null, SALE_MODULE, SALE_TRANSACTION_TYPE);
														$total_paid_amount= $this->transaction_model->get_total_transaction_amount(null, SALE_MODULE, RECEIPT_TRANSACTION_TYPE) + $this->transaction_model->get_total_transaction_amount(null,SALE_MODULE,CREDIT_TRANSACTION_TYPE);
				                  ?>
				                  Pending : ₹ <?=(number_format_i($total_sale-$total_paid_amount))?>
				                </span>
				              </div>
				              <!-- /.info-box-content -->
				            </div>
			          	</div>
			          <!-- ./col -->
			          
			          	<!-- ./col -->
			          		<?php 
                                $user_id = $this->session->userdata('user_id');
                                $udata = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
                            
                                if (empty($udata->branch_id)) {
                                    $ex_count = $this->db->count_all('expense'); 
                                } else {
                                    $ex_count = $this->db->where('warehouse_id', $udata->branch_id)->count_all_results('expense');
                                }
                            ?>
			          	<div class="col-lg-3 col-6">
				            <!-- small box -->
				            <div class="info-box bg">
				              <span class="info-box-icon"><i class="fas fa-money-check-alt"></i></span>
				              <div class="info-box-content">
				                <span class="info-box-text"><?=$this->lang->line('expenses')?></span>
				                <span class="info-box-number">₹ <?=($expense_amount == '') ? '0.00' : number_format_i($expense_amount);?> &nbsp;&nbsp; | Expenses Count: <?= $ex_count; ?></span>
				                <div class="progress">
				                  <div class="progress-bar" style="width: 70%"></div>
				                </div>
				                <span class="progress-description" style="font-size:14px;">
				                  <?php 
				                  	$total_expense		= $this->transaction_model->get_total_transaction_amount(null, EXPENSE_MODULE, EXPENSE_TRANSACTION_TYPE);
														$total_paid_amount= $this->transaction_model->get_total_transaction_amount(null, EXPENSE_MODULE, PAYMENT_TRANSACTION_TYPE);
				                  ?>
				                  Pending :₹ <?=(number_format_i($total_expense-$total_paid_amount))?>
				                </span>
				              </div>
				            </div>
				            
			          	</div>
			          	<!-- ./col -->
			          	
    			        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="info-box bg">
                                <span class="info-box-icon"><i class="fas fa-building"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Branches</span>
                                    <span class="info-box-number">
                                        <?php 
                                        $total_branches = $this->db->where('delete_status', 0)
                                                                   ->from('warehouse')
                                                                   ->count_all_results(); 
                                        echo $total_branches;
                                        ?>
                                    </span>
                        
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 100%"></div>
                                    </div>
                                    <span class="progress-description" style="font-size:14px;">
                                        Total active branches in the system.
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
			        </div>
			        <!-- /.row -->

                    <div class="row  d-none">
			        	<div class="col-12">
			            <div class="card card-primary card-outline card-outline-tabs">
			              <div class="card-header p-0 pt-1">
			                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
			                  <li class="nav-item">
			                    <a class="nav-link active" id="daily-tab" data-toggle="pill" href="#daily" role="tab" aria-controls="daily" aria-selected="true">Daily</a>
			                  </li>
			                  <li class="nav-item">
			                    <a class="nav-link" id="weekly-tab" data-toggle="pill" href="#weekly" role="tab" aria-controls="weekly" aria-selected="false">Weekly</a>
			                  </li>
			                  <li class="nav-item">
			                    <a class="nav-link" id="bi-weekly-tab" data-toggle="pill" href="#bi-weekly" role="tab" aria-controls="bi-weekly" aria-selected="false">Bi-weekly</a>
			                  </li>
			                  <li class="nav-item">
			                    <a class="nav-link" id="monthly-tab" data-toggle="pill" href="#monthly" role="tab" aria-controls="monthly" aria-selected="false">Monthly (current month)</a>
			                  </li>
			                  <li class="nav-item">
			                    <a class="nav-link" id="yearly-tab" data-toggle="pill" href="#yearly" role="tab" aria-controls="yearly" aria-selected="false">Yearly (current year)</a>
			                  </li>
			                </ul>
			              </div>
			              <div class="card-body">
			                <div class="tab-content" id="custom-tabs-two-tabContent">
			                  <div class="tab-pane fade active show" id="daily" role="tabpanel" aria-labelledby="daily-tab">
			                  
						        		<div class="card">
							              	<!-- /.card-header -->
							              	<div class="card-body p-0">
								                <table class="table table-bordered">
								                  	<thead>
									                    <tr>
									                      	<th style="width: 10px"></th>
									                      	<th>Sales</th>
									                      	<th>Sales return</th>
									                      	<th>Purchase</th>
									                      	<th>Purchase return</th>
                                                            <th>Expense</th>
									                    </tr>
								                  	</thead>
								                  	<tbody>
                                                      <tr>
                                                        <th>Amount</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                      </tr>
                                                      <tr>
                                                        <th>Paid</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                      </tr>
                                                      <tr>
                                                        <th>Pending</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                      </tr>
								                  	</tbody>
								                </table>
							              	</div>
							              	<!-- /.card-body -->
								            </div>
								            <!-- /.card -->
				                </div>
			                </div>
			              </div>
			            </div>
          			</div>
			        </div>
			        
			        <div class="row">
			         <div class="col-lg-12">
			            <div class="card">
		              	  <div class="card-header border-0">
			                <div class="d-flex justify-content-between">
			                  <h3 class="card-title">Sales & Expenses & Purchase - <?=date('Y')?></h3>
			                  
			                  <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                   <?php 
                                    $user_id = $this->session->userdata('user_id');
                                    $udata = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
                                    if (empty($udata->branch_id)): 
                                    ?>
                                        <div class="float-right">
                                          <select id="warehouse-select" class="form-control" onchange="updateGraph(this.value)">
                                            <option value="all">All Branches</option>
                                            <?php foreach ($warehouses as $warehouse): ?>
                                                <option value="<?= $warehouse['id'] ?>"><?= $warehouse['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
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
                                <span id="sales-total" class="text-bold text-lg"><?=$this->session->userdata('currency_symbol')?> </span>
                                <span>Sales Over Time</span>
                            </p>
                            
                            <p class="d-flex flex-column" style="padding-left: 20px;">
                                <span id="expense-total" class="text-bold text-lg"><?=$this->session->userdata('currency_symbol')?> </span>
                                <span>Expense Over Time</span>
                            </p>
                            
                            <p class="d-flex flex-column" style="padding-left: 20px;">
                                <span id="purchase-total" class="text-bold text-lg"><?=$this->session->userdata('currency_symbol')?> </span>
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
		              	</div>
			            </div>
			          	</div>
			        </div>

			        <div class="row">
			        	<div class="col-12">
			            <div class="card card card-tabs">
			              <div class="card-header code text-light p-0 pt-1">
			                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
			                  <li class="pt-2 px-3"><h3 class="card-title">Recently added</h3></li>
			                  <li class="nav-item">
			                    <a class="nav-link active" id="sales-tab" data-toggle="pill" href="#sales" role="tab" aria-controls="sales" aria-selected="true"><?=$this->lang->line('header_sales').' ('.sizeof($sales).')'?></a>
			                  </li>
			                  <!--<li class="nav-item">-->
			                  <!--  <a class="nav-link" id="expenses-tab" data-toggle="pill" href="#expenses" role="tab" aria-controls="expenses" aria-selected="false"><?=$this->lang->line('header_expenses').' ('.sizeof($expenses).')'?></a>-->
			                  <!--</li>-->
			                  <li class="nav-item text-light">
			                    <a class="nav-link text-light" id="customers-tab" data-toggle="pill" href="#customers" role="tab" aria-controls="customers" aria-selected="false"><?=$this->lang->line('header_customers').' ('.sizeof($customers).')'?></a>
			                  </li>
			                  <li class="nav-item">
			                    <a class="nav-link text-light" id="products-tab" data-toggle="pill" href="#products" role="tab" aria-controls="products" aria-selected="false"><?=$this->lang->line('header_all_product').' ('.sizeof($products).')'?></a>
			                  </li>
			                  <li class="nav-item">
			                    <a class="nav-link text-light" id="product-alert-tab" data-toggle="pill" href="#product-alert" role="tab" aria-controls="product-alert" aria-selected="false"><?=$this->lang->line('header_product_alert').' ('.sizeof($product_alerts).')'?></a>
			                  </li>
			                </ul>
			              </div>
			              <div class="card-body">
			                <div class="tab-content" id="custom-tabs-two-tabContent">
			                  <div class="tab-pane fade active show" id="sales" role="tabpanel" aria-labelledby="sales-tab">
			                    <?php 
				                		if($this->permission_model->has_permission('show_recent_sales'))
				                		{
			              			?>
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
									                      	<th>Branch Name</th>
									                      	<th><?=$this->lang->line('sale_reference_no')?></th>
									                      	<th><?=$this->lang->line('sale_customer')?></th>
									                      	<th>Discount</th>
									                      	<th>GST</th>
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
								                  					// if($i < 6)
								                  					// {
								                  		?>
								                  		<tr>
								                  			<td><?=$i++?></td>
								                  			<?php $warehouse_name = $this->db->select('name')->from('warehouse')->where('id', $value->warehouse_id)->get()->row()->name;?>
								                  			<td><?=$warehouse_name;?></td>
								                  			<td><a href="<?php echo base_url('sale/view/'.base64_encode($value->id));?>"><?=$value->reference_no?></a></td>
								                  			<?php $cust_name = $this->db->select('customer_name')->from('customer')->where('id', $value->customer_id)->get()->row()->customer_name;?>
								                  			<td><?=$cust_name ?></td>
								                  			<td><?=number_format_i($value->total_discount)?></td>
								                  			<td><?=number_format_i($value->total_tax)?></td>
								                  			<td><?=number_format_i($value->total)?></td>
								                  		</tr>
								                  		<?php 
								                  					// }
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
								          <?php 
									        	}
									        ?>  
				                </div>
			                  <div class="tab-pane fade" id="expenses" role="tabpanel" aria-labelledby="expenses-tab">
			                    <?php 
					                	if($this->permission_model->has_permission('show_recent_expenses'))
					                	{
				              		?>
						          	
								            <div class="card">
							              	<div class="card-header">
								                <h3 class="card-title">
																	<i class="nav-icon fas fa-money-check-alt"></i>
								                	<strong class="ml-1"><?=$this->lang->line('recently_added_expenses')?></strong>
								                </h3>
								                <div class="card-tools">
								                	<!-- <button type="button" class="btn btn-outline-info btn-xs" onclick="window.location.href='<?=base_url('expense/add')?>'">
								                		<i class="fas fa-plus-circle"></i>
								                		<?=$this->lang->line('expense_add')?>
								                	</button> -->
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
								                  					// if($i < 6)
								                  					// {
								                  		?>
								                  		<tr>
								                  			<td><?=$i++?></td>
								                  			<td><a href="<?php echo base_url('expense/view/'.base64_encode($value->id));?>"><?=date('d-m-Y', strtotime($value->created_date));?></a></td>
								                  			<td><?=$value->expense_category_name?></td>
								                  			<td><?=number_format_i($value->total_amount)?></td>
								                  		</tr>
								                  		<?php
								                  					// } 
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
						          	
						          		<?php 
						              	}
					              	?>
			                  </div>
			                  <div class="tab-pane fade" id="customers" role="tabpanel" aria-labelledby="customers-tab">
			                    <?php 
						                if($this->permission_model->has_permission('show_recent_customers'))
						                {
					              	?>
						          	
							            <div class="card">
						              	<div class="card-header">
							                <h3 class="card-title">
																<i class="nav-icon fas fa-user-friends"></i>	
							                	<strong class="ml-1"><?=$this->lang->line('recently_added_customers')?></strong>
							                </h3>
							                <div class="card-tools">
							                	<!-- <button type="button" class="btn btn-outline-info btn-xs" data-toggle="modal" data-target="#add_customer_modal" accesskey="c">
							                		<i class="fas fa-plus-circle"></i>
							                		<?=$this->lang->line('customer_add')?>
							                	</button> -->
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
							                  					// if($i < 6)
							                  					// {
							                  		?>
							                  		<tr>
							                  			<td><?=$i++?></td>
							                  			<td><a href="<?php echo base_url('customer/view/'.base64_encode($value->id));?>"><?=$value->customer_name?></a></td>
							                  			<td><?=$value->email?></td>
							                  			<td><?=$value->phone?></td>
							                  		</tr>
							                  		<?php 
							                  					// }
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
							           
						          	 <?php 
						              	}
					              	?>
			                  </div>
			                  <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
			                    <?php 
						                if($this->permission_model->has_permission('show_recent_products'))
						                {
					              	?>
						          	
							            <div class="card">
							              	<div class="card-header">
								                <h3 class="card-title">
																	<i class="nav-icon far fa-snowflake"></i>
								                	<strong class="ml-1"><?=$this->lang->line('recently_added_products')?></strong>
								                </h3>
								                <div class="card-tools">
								                	<!-- <button type="button" class="btn btn-outline-info btn-xs"  data-toggle="modal" data-target="#add_product_modal" accesskey="s">
								                		<i class="fas fa-plus-circle"></i>
								                		<?=$this->lang->line('product_add')?>
								                	</button> -->
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
									                      	<th>GST</th>
									                    </tr>
								                  	</thead>
								                  	<tbody>

								                  		<?php 
								                  			$i = 1;
								                  			
								                  			if(sizeof($products) > 0)
								                  			{
									                  			foreach ($products as $value) 
									                  			{
									                  				// if($i < 6)
								                  					// {
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
								                  					// }
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
						          	
						          	 <?php 
						              	}
					              	?>
			                  </div>
			                  <div class="tab-pane fade" id="product-alert" role="tabpanel" aria-labelledby="product-alert-tab">
			                      <?php 
						                if($this->permission_model->has_permission('show_product_alert'))
						                {
					              	?>
						          	
							            	<div class="card">
							              	<div class="card-header">
								                <h3 class="card-title">
																	<i class="nav-icon far fa-snowflake"></i>
								                	<strong class="ml-1"><?=$this->lang->line('product_alerts')?></strong>
								                </h3>
							              	</div>
							              	<!-- /.card-header -->
							              	<div class="card-body p-0">
								                <table class="table table-striped">
								                  	<thead>
									                    <tr>
									                      	<th style="width: 10px">#</th>
									                      	<th>Name</th>
									                      	<th>Description</th>
									                      	<th>Branch Name</th>
									                      	<th>Qty</th>
									                      	<th>Alert Qty</th>
									                    </tr>
								                  	</thead>
								                  	<tbody>

								                  		<?php 	


								                  			if(sizeof($product_alerts) > 0)
								                  			{
								                  				$i = 1;
									                  			foreach ($product_alerts as $pa) 
									                  			{
									                  				$product = $this->product_model->get_single_record($pa->product_id);

									                  				if($pa->quantity <= $product->alert_quantity)
									                  				{
								                  		?>
												                  		<tr>
												                  			<td><?=$i++?></td>

												                  			<td><?=$pa->product_name?></td>
												                  			<td><?=$product->description?></td>
												                  			<td><?=$pa->warehouse_name?></td>
												                  			<td><?=$pa->quantity?></td>
												                  			<td><?=$product->alert_quantity?></td>
												                  		</tr>
								                  		<?php 
								                  					}
							                  					}
								                  			}
								                  			
								                  			else
								                  			{
								                  		?>
								                  			<tr>
								                  				<td colspan="6">
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
						          	
						          	 <?php 
						              	}
					              	?>
			                  </div>
			                </div>
			              </div>
			            </div>
          			</div>
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




	<!--// $(document).ready(function() {-->
	<!--// 		// Function to detect monitor size-->
	<!--// 		function detectMonitorSize() {-->
	<!--// 				// Get the screen width and height-->
	<!--// 				var screenWidth = screen.width;-->
	<!--// 				var screenHeight = screen.height;-->

	<!--// 				// Assuming standard DPI for simplicity-->
	<!--// 				var dpi = 96;-->

	<!--// 				// Calculate the diagonal screen size in inches-->
	<!--// 				var screenSize = Math.sqrt(Math.pow(screenWidth / dpi, 2) + Math.pow(screenHeight / dpi, 2));-->

	<!--// 				return screenSize;-->
	<!--// 		}-->

	<!--// 		// Check if the monitor size is 19 inches or less-->
	<!--// 		if (detectMonitorSize() <= 19) {-->
	<!--// 				// Apply zoom out to the application-->
	<!--// 				var currentZoom = parseFloat($('body').css('zoom')) || 1;-->
	<!--// 				var newZoom = currentZoom * 0.8; // 20% zoom out-->
	<!--// 				$('body').css('zoom', newZoom);-->
	<!--// 		}-->
	<!--// });-->

<script type="text/javascript">
updateGraph();
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

    function updateGraph(branchId) {
        $.ajax({
            url: '<?=base_url("auth/update_graph_data")?>',
            type: 'POST',
            data: {
                branch_id: branchId,
                <?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash(); ?>'
            },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                console.log(data);
                if (data.success) {
                  
                    const salesData = data.data.sales_data;
                    const expenseData = data.data.expense_data;
                    const purchaseData = data.data.purchase_data;

                    salesChart.data.datasets[0].data = salesData;
                    salesChart.data.datasets[1].data = expenseData;
                    salesChart.data.datasets[2].data = purchaseData;

                    salesChart.update();
                   
                    const salesTotal = salesData.reduce((a, b) => a + b, 0);
                    const expenseTotal = expenseData.reduce((a, b) => a + b, 0);
                    const purchaseTotal = purchaseData.reduce((a, b) => a + b, 0);

                    const formatValue = (value) => {
                    
                      return value.replace(/^0+/, '');
                    };
                    
                    const value_1 = '<?=$this->session->userdata("currency_symbol")?> ' + formatValue(salesTotal.toLocaleString());
                    const value_2 = '<?=$this->session->userdata("currency_symbol")?> ' + formatValue(expenseTotal.toLocaleString());
                    const value_3 = '<?=$this->session->userdata("currency_symbol")?> ' + formatValue(purchaseTotal.toLocaleString());

                    document.getElementById('sales-total').innerText = value_1;
                    document.getElementById('expense-total').innerText = value_2;
                    document.getElementById('purchase-total').innerText = value_3;
                    
                } else {
                    alert("Failed to load data. Please try again.");
                }
            } catch (error) {
                console.error("Error parsing response: ", error);
                alert("Invalid response format. Please check the server.");
            }
        },
        error: function() {
            alert("Error occurred while fetching data.");
        }
    });
}

</script>