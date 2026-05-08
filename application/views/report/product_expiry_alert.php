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
		            <li class="breadcrumb-item active"><?=$this->lang->line('product_expiry_alert_report')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
            <!-- general form elements disabled -->
            	<form role="form" id="productexpiryalertReport" name="productexpiryalertReport" method="POST" action="<?php echo base_url("report/product_expiry_alert") ?>" target="_blank">
	            	<div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title">
		                	<i class="fas fa-funnel-dollar"></i>
		                	<?=$this->lang->line('filter_report')?>
		                </h3>
		                <div class="card-tools">

	                  </div>
		              </div>
	              	<!-- /.card-header -->
		              <div class="card-body"> 
	                  <div class="row">
	                    <div class="col-sm-3">
	                      <div class="form-group">
				                  <label><?=$this->lang->line('expiry_month')?></label>
                          <select class="form-control form-control-sm select2bs4" name="expiry_month" id="expiry_month">
                          <?php
                            for ($i = 1; $i <= 30; $i++) {
                                echo "<option value=\"$i\">$i " . 'month' . "</option>";
                            }
                            ?> 
                          </select>
				                </div>
	                    </div>
	                  </div>

	               
		              </div>
	              	<!-- /.card-body -->
		              <div class="card-footer">
		              	<!-- <input type="hidden" name="action_type" id="action_type" value=""> -->
		              	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		              	<button type="submit" name="submit" id="submitproductexpiryalertReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
		              </div>
	            	</div>
	            </form>
	            <div class="card card-primary">
	              <div class="card-header">
	                <h3 class="card-title"><?=$this->lang->line('product_expiry_alert_report')?></h3>
	                <div class="card-tools">
	                	<button type="button" class="btn btn-tool bt-danger" id="export">
                    	<i class="fas fa-file-export"></i>
                    	<?=$this->lang->line('export')?>
                    </button>
                 
                  </div>
	              </div>
	              <!-- /.card-header -->
	              <div class="card-body product_list">
	                <table class="sticky-header-table table table-bordered table-striped" id="product-data">
		                <thead>
		                  <tr>
		                  	<th>SL</th>
									    	<th>PID</th>
                        <th>Product Name</th>
												<th>Product Description</th>
                        <th>Batch No</th>
                        <th>Quantity</th>
									    	<th>Mfg Date</th>
                        <th>Expiry Date</th>
											</tr>
		                </thead>
		                <tbody>
		                  <?php

                        if(sizeof($warehouse_products) > 0)
		                    {
                          $i = 1;
		                      foreach ($warehouse_products as $value) 
		                      {
		                      	
		                  ?>
		                  <tr>  
                        <td><?=$i++?></td>
		                  	<td><?=$value->pid;?></td>                      
		                    <td><?=$value->product_name;?></td>
                        <td><?=$value->product_description;?></td>
                        <td><?=$value->batch_no;?></td>
                        <td><?=number_format($value->quantity, 2);?></td>
                        <td>
                          <?=($value->mfg_date != '' && $value->mfg_date != '0000-00-00') ? date('d-m-Y',strtotime($value->mfg_date)) : '' ?>
                        </td>
                        <td>
                          <?=($value->expiry_date != '' && $value->expiry_date != '0000-00-00') ? date('d-m-Y',strtotime($value->expiry_date)) : '' ?>
                        </td>
		                  
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
		$('#submitproductexpiryalertReport').click(function(e){
			// e.preventDefault();

			var action_type = $('#action_type').val();

			// if(action_type == 'export')
			// {
			// 	return true;
			// }
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

				var formData = $('#productexpiryalertReport').serialize();
				$(this).text('<?=$this->lang->line('searching')?>');

				setTimeout(function(){ 

					$.ajax({
		        url: '<?php echo base_url("report/product_expiry_alert") ?>',
		        type: 'POST',
		        dataType : 'json',
		        data: formData,                       
		        success: function (response) {
		          $('.product_list').html(response.warehouse_products);
		          $('#submitproductexpiryalertReport').text('<?=$this->lang->line('search')?>');
		        },
		        error: function () 
		        { 
		          show_message('failure-header','Please contact the administrator if you are keep facing this issue.');   
		        }
		      });	
				}, 2000);
			}
		});


    $('#export').click(function (e) {
    e.preventDefault();

    // Get today's date in the required format
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = today.toLocaleString('default', { month: 'short' });
    var yyyy = today.getFullYear();
    var currentDate = dd + '-' + mm + '-' + yyyy;

    // Initialize an empty array to store the data
    var data = [];

    // Get the table headers
    var headers = [];
    $("#product-data thead th").each(function () {
        headers.push('"' + $(this).text() + '"');
    });
    data.push(headers);

    // Get the table rows with the "customer_row" class, trim the data
    $("#product-data tbody tr").each(function () {
        var rowData = [];
        $(this).find("td").each(function () {
            var cellData = $(this).text().trim(); // Trim the data
            rowData.push('"' + cellData + '"');
        });
        data.push(rowData);
    });

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
        link.setAttribute("download", "ProductExpiryAlert_" + currentDate + ".csv"); // Append with today's date
        document.body.appendChild(link);

        link.click(); // Trigger the download
    });

	});
</script>