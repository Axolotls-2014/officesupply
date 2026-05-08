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

		            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_stock_value')?></a></li>
		            <li class="breadcrumb-item active"><?=$this->lang->line('stock_value_report')?></li>
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
		                <h3 class="card-title"><?=$this->lang->line('stock_value_report')?></h3>
		                <div class="card-tools">
		                	<button type="button" class="btn btn-tool bt-danger" id="export-button">
	                    	<i class="fas fa-file-export"></i>
                    		<?=$this->lang->line('export')?>
	                    </button>
	                  <!--   <button type="button" class="btn btn-tool" id="print">
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
		                <table class="sticky-header-table table table-bordered table-striped" id="stock-value-data">
			                <thead>
			                  <tr>
			                    <th><?=$this->lang->line('product_name')?></th>
			                    <th>Available Total Quantity</th>
                          <th>Total Purchase Value</th>
                          <th>Total Sale Value</th>
			                  </tr>
			                </thead>
			                <tbody>
			                  <?php

                          if(sizeof($products) > 0)
			                    {
                            $total_product_cost = 0;
                            $total_quantity     = 0;
                            $total_sale_value   = 0;
                            

			                      foreach ($products as $value) 
			                      {
                              $isQuantityNegative = false;

                              if($value->available_quantity < 0)
                                $isQuantityNegative = true;

                              if(!$isQuantityNegative)
                              {
                                $total_product_cost += $value->purchase_cost;
                                $total_quantity     += $value->available_quantity;
                                $total_sale_value   += $value->selling_price;
                              }
                        ?>
			                  <tr class="<?=($value->purchase_cost > $value->selling_price ||  $value->selling_price < 0 || $value->available_quantity < 0) ? "warning-data" : ''?>">                       
			                    <td><?php echo $value->product_name;?></td>
			                    <td>
                            <?php 
                              $purchase_delivered_qty         = $value->purchase_delivered_quantity;
                              $sale_delivered_qty             = $value->sale_delivered_quantity;
                             
                              $stockin_qty                    = $value->stockin_quantity;
                              $stockout_qty                   = $value->stockout_quantity;
                              $sales_return_delivered_qty     = $value->sales_return_delivered_quantity;
                              $sales_return_qty               = $value->stockout_quantity;
                              $purchase_return_delivered_qty  = $value->purchase_return_delivered_quantity;

                              $available_quantity             = $value->available_quantity;

                              echo $available_quantity;
                            ?>
                          </td>
                          <td>
                            <?php
                              echo number_format_i($value->purchase_cost);
                            ?>
                          </td>
                          <?php 
                            $warehouse_products = $this->warehouse_products_model->get_records_by_product_id($value->product_id);
                            $isPtdWithZeroExist = false;
                            
                            foreach ($warehouse_products as $wp) 
                            {
                              if($wp->batch_no != null && $wp->selling_price == 0)
                                $isPtdWithZeroExist = true;
                            }
                          ?>

                          <td class="<?=($isPtdWithZeroExist == true) ? 'bg-danger':''?>">
                            <?php
                              if($value->selling_price == 0)
                                echo 'Please update selling price';
                              else
                                echo number_format_i($value->selling_price); 
                            ?>
                          </td>
                        </tr>
			                  <?php  
			                      }
                        ?>
                        
                        <tr style="font-weight:bold">
                          <td>
                            Total
                          </td>
                          <td>
                            <?=number_format_i($total_quantity)?>
                          </td>
                          <td>
                            <?=number_format_i($total_product_cost)?>
                          </td>
                          <td>
                            <?=number_format_i($total_sale_value)?>
                          </td>
                        </tr>

                        <?php
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

<script>
  $(document).ready(function () {
      $("#export-button").click(function () {
          // Initialize an empty array to store the data
          var data = [];

          // Get the table headers
          var headers = [];
          $("#stock-value-data thead th").each(function () {
              headers.push('"' + $(this).text() + '"');
          });
          data.push(headers);

          // Get the table rows with the "customer_row" class, trim the data
          $("#stock-value-data tbody tr").each(function () {
              var rowData = [];
              $(this).find("td").each(function () {
                  var cellData = $(this).text().trim(); // Trim the data
                  rowData.push('"' + cellData + '"');
              });
              data.push(rowData);
          });

          // Get the table footer, trim the data
          var footerData = [];
          $("#stock-value-data tfoot th").each(function () {
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
          link.setAttribute("download", "StockValue.csv");
          document.body.appendChild(link);

          link.click(); // Trigger the download
      });
  });
</script>