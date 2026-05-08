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
		            <li class="breadcrumb-item active"><?=$this->lang->line('product_sale_report')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
            <!-- general form elements disabled -->
            	<form role="form" id="productReport" name="productReport" method="POST" action="<?php echo base_url("report/product_sale") ?>" target="_blank">
	            	<div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title">
		                	<i class="fas fa-funnel-dollar"></i>
		                	<?=$this->lang->line('filter_report')?>
		                </h3>
		                <div class="card-tools">

		                  <div class="input-group">
		                    <button type="button" class="btn btn-default float-right" id="daterange-btn">
		                      <i class="far fa-calendar-alt"></i> Date range picker
		                      <i class="fas fa-caret-down"></i>
		                    </button>
		                  </div>
		            
	                    <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse">
	                    	<i class="fas fa-minus"></i>
	                    </button> -->
	                  </div>
		              </div>
	              	<!-- /.card-header -->
		              <div class="card-body"> 
	                  <div class="row">
	                    <div class="col-sm-4">
	                      <div class="form-group">
				                  <label><?=$this->lang->line('from_date')?></label>
				                  <div class="input-group">
				                    <div class="input-group-prepend">
				                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
				                    </div>
				                    <input type="text" class="form-control datepicker" name="from_date" id="from_date" style="z-index:999 !important" autocomplete="off">
				                  </div>
				                </div>
	                    </div>
	                    <div class="col-sm-4">
	                      <div class="form-group">
				                  <label><?=$this->lang->line('to_date')?></label>
				                  <div class="input-group">
				                    <div class="input-group-prepend">
				                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
				                    </div>
				                    <input type="text" class="form-control datepicker" name="to_date" id="to_date" style="z-index:999 !important" value="<?=date('d-m-Y')?>" autocomplete="off">
				                  </div>
				                </div>
	                    </div>
											<!-- <div class="col-sm-2">
	                    	<div class="form-group">
                          <label for="product_search_type"><?=$this->lang->line('product_search_type')?></label>
                          <select class="form-control form-control-sm select2bs4" name="product_search_type" id="product_search_type" width="100%" class="add-row">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <option value="sold">Sold</option>
                            <option value="purchased">Purchased</option>
                          </select>
                          <span id="err_product_search_type" class="error invalid-feedback"><?=form_error('product_search_type');?></span>
                        </div>
	                    </div> -->
	                    <div class="col-sm-4 customer_col">
	                    	<div class="form-group">
                          <label for="customer_id"><?=$this->lang->line('product_customer')?></label>
                          <select class="form-control form-control-sm select2bs4" name="customer_id" id="customer_id" width="100%" class="add-row">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($customers as $value) {
                            ?>
                              <option value="<?=$value->id;?>">
                                <?= $value->customer_name;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_customer_id" class="error invalid-feedback"><?=form_error('customer_id');?></span>
                        </div>
	                    </div>
	                    <!-- <div class="col-sm-2 supplier_col">
	                    	<div class="form-group">
                          <label for="supplier_id"><?=$this->lang->line('product_supplier')?></label>
                          <select class="form-control form-control-sm select2bs4" name="supplier_id" id="supplier_id" width="100%" class="add-row">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($suppliers as $value) {
                            ?>
                              <option value="<?=$value->id;?>" <?php echo set_select('id', $value->id); ?>>
                                <?= $value->company_name;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_supplier_id" class="error invalid-feedback"><?=form_error('supplier_id');?></span>
                        </div>
	                    </div> -->
	                  </div>
	                  <div class="col-sm-12">
		                	<div class="form-group">
		                    <label for="product_id"><?=$this->lang->line('product_product')?></label>
		                    <select class="form-control form-control-sm select2bs4" name="product_id[]" id="product_id" width="100%" multiple="multiple">
		                      <!-- <option value=""><?=$this->lang->line('select')?></option> -->
		                      <?php
		                        foreach ($products as $value) {
		                      ?>
		                        <option value="<?=$value->id;?>">
		                          <?= $value->name;?>
		                        </option>
		                      <?php 
		                        }
		                      ?>
		                    </select>
		                    <span id="err_product_id" class="error invalid-feedback"><?=form_error('product_id');?></span>
		                  </div>
		                </div>
		              </div>
	              	<!-- /.card-body -->
		              <div class="card-footer">
		              	<input type="hidden" name="action_type" id="action_type" value="">
		              	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		              	<button type="submit" name="submit" id="submitProductReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
		              </div>
	            	</div>
	            </form>
	            <div class="card card-primary">
	              <div class="card-header">
	                <h3 class="card-title"><?=$this->lang->line('product_sale_report')?></h3>
	                <div class="card-tools">
	                	<button type="button" class="btn btn-tool bt-danger" id="export">
                    	<i class="fas fa-file-export"></i>
                    	<?=$this->lang->line('export')?>
                    </button>
                    <button type="button" class="btn btn-tool" id='print'>
                    	<i class="fas fa-print"></i>
                    	<?=$this->lang->line('print')?>
                    </button>
                    <button type="button" class="btn btn-tool d-none" id='pdf'>
                    	<i class="fas fa-file-pdf"></i>
                    	<?=$this->lang->line('pdf')?>
                    </button>
                  </div>
	              </div>
	              <!-- /.card-header -->
	              <div class="card-body product_list">
	                <table class="sticky-header-table table table-bordered table-striped" id="product-sale-data">
		                <thead>
		                  <tr>
		                  	<th>SL</th>
		                  	<th>Product Name</th>
                        <th>Sale Date</th>
		                  	<th>Sale Invoice Number</th>
                        <th>Batch</th>
		                  
		                  	<th>Billed Qty</th>
                       
		                  	<th>Selling Price</th>
		                  	<th>Total Amount</th>
                        <th>Tax Percentage</th>
                        <th>Tax Amount</th>
                       
		                  	<th>Customer Name</th>
		                  	<th>Delivered</th>
		                  </tr>
		                </thead>
		                <tbody>
		                  <tr>
		                  	<td colspan="12">Please click on Search button to get records</td>
		                  </tr>
		                </tbody>
		                <!-- <tfoot class="bg-gray disabled footer_data">
		                	<tr>
		                    <th colspan="3"></th>
		                    <th><?=$this->session->userdata('currency_symbol').' '.$total_discount?></th>
		                    <th><?=$this->session->userdata('currency_symbol').' '.$total_taxable_value?></th>
		                    <th><?=$this->session->userdata('currency_symbol').' '.$total_tds?></th>
		                    <th><?=$this->session->userdata('currency_symbol').' '.$total_igst?></th>
										    <th><?=$this->session->userdata('currency_symbol').' '.$total_cgst?></th>
										    <th><?=$this->session->userdata('currency_symbol').' '.$total_sgst?></th>
		                    <th><?=$this->session->userdata('currency_symbol').' '.$total?></th>
		                    <th><?=$this->session->userdata('currency_symbol').' '.$total_profit?></th>
		                  </tr>
		                </tfoot> -->
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
		$('#submitProductReport').click(function(e){
			// e.preventDefault();

			var action_type = $('#action_type').val();

			/*if(action_type == 'export')
			{
				return true;
			}
			else */
      if(action_type == 'pdf')
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

				var formData = $('#productReport').serialize();
				$(this).text('<?=$this->lang->line('searching')?>');

				setTimeout(function(){ 

					$.ajax({
		        url: '<?php echo base_url("report/product_sale") ?>',
		        type: 'POST',
		        dataType : 'json',
		        data: formData,                       
		        success: function (response) {
		          $('.product_list').html(response.products);
		          $('#submitProductReport').text('<?=$this->lang->line('search')?>');
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
      e.preventDefault();
			// Initialize an empty array to store the data
      var data = [];

      // Get the table headers
      var headers = [];
      $("#product-sale-data thead th").each(function () {
          headers.push('"' + $(this).text() + '"');
      });
      data.push(headers);

      // Get the table rows with the "customer_row" class, trim the data
      $("#product-sale-data tbody tr").each(function () {
          var rowData = [];
          $(this).find("td").each(function () {
              var cellData = $(this).text().trim(); // Trim the data
              rowData.push('"' + cellData + '"');
          });
          data.push(rowData);
      });

      // Get the table footer, trim the data
      var footerData = [];
      $("#product-sale-data tfoot th").each(function () {
          var cellData = $(this).text().trim(); // Trim the data
          footerData.push('"' + cellData + '"');
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
      link.setAttribute("download", "ProductSale.csv");
      document.body.appendChild(link);

      link.click(); // Trigger the download
		});
		$('#print').click(function(e){
			$('#action_type').val('print');		
			$('#submitProductReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#pdf').click(function(e){
			$('#action_type').val('pdf');
			$('#submitProductReport').trigger('click');  
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

    // $('.customer_col').css('display','none');
    // $('.supplier_col').css('display','none');

    // $(document).on('change','#product_search_type',function(e){
    // 	var product_search_type = $(this).val();

    // 	if(product_search_type == 'sold')
    // 	{
    // 		$('.customer_col').css('display','block');
    // 		$('.supplier_col').css('display','none');
    // 	}
    // 	else
    // 	{
    // 		$('.customer_col').css('display','none');
    // 		$('.supplier_col').css('display','block');	
    // 	}
    // });
	});
</script>