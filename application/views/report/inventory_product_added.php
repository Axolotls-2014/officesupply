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
		            <li class="breadcrumb-item active"><?=$this->lang->line('header_inventory_product_added')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
              <form role="form" id="saleReportForm" name="saleReportForm" method="POST" action="<?php echo base_url("report/inventory_product_added") ?>">
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
                      <div class="col-sm-3">
                        <label><?=$this->lang->line('select_date_range')?></label>
                        <div class="input-group">
                          <button type="button" class="btn btn-block btn-outline-secondary float-right" id="daterange-btn">
                            <i class="far fa-calendar-alt"></i> Date Range
                            <!-- <i class="fas fa-caret-down"></i> -->
                          </button>
                        </div>
                      </div>
                      <!-- <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('product_expiry_date')?></label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control datepicker" name="expiry_date" id="expiry_date" value="<?=$expiry_date?>" style="z-index:999 !important" readonly="readonly">
                            <span class="input-group-append">
                              <span class="input-group-text expiry_date_cancel text-danger" style="cursor:pointer;"><i class="fas fa-times"></i></span>
                            </span>
                          </div>
                        </div>
                      </div> -->
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label><?=$this->lang->line('from_date')?></label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="<?=$from_date?>" style="z-index:999 !important" readonly="readonly">
                            <span class="input-group-append">
                              <span class="input-group-text form_date_cancel text-danger" style="cursor:pointer;"><i class="fas fa-times"></i></span>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label><?=$this->lang->line('to_date')?></label>
                          <span class="text-danger">*</span>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control datepicker" name="to_date" id="to_date" style="z-index:999 !important" value="<?=date('d-m-Y', strtotime($to_date))?>"  readonly="readonly">
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                        <label for="product_id"><?=$this->lang->line('product_product')?></label>
		                    <select class="form-control form-control-sm select2bs4" name="pid" id="pid" width="100%">
		                      <option value="">All</option>
		                      <?php
		                        foreach ($products as $value) {
		                      ?>
		                        <option value="<?=$value->pid;?>">
		                          <?= $value->name;?>
		                        </option>
		                      <?php 
		                        }
		                      ?>
		                    </select>
                        </div>
                      </div>
                  
                    </div>

                   

                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <input type="hidden" name="action_type" id="action_type" value="">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" name="submit" id="submitSaleReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
                  </div>
                </div>
              </form>
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('header_inventory_product_added')?></h3>
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
                        <th><?=$this->lang->line('sr_no')?></th>
                        <th><?=$this->lang->line('product_name')?></th>
                        <th>Batch No</th>
                        <!-- <th>Expiry Date</th> -->
                        <th>Purchase Qty</th>
                        <th>Purchase value</th>
                       
                        
                      </tr>
                    </thead>
                  <tbody id="stock-value-body">
    <?php
    if(sizeof($products) > 0)
    {
        $i = 1;
        foreach ($products as $value) 
        { 
            // Check if inventory data exists for this product
            if(isset($inventory_data[$value->pid]) && !empty($inventory_data[$value->pid]))
            {
                foreach ($inventory_data[$value->pid] as $ipd) 
                { 
    ?>
                    <tr data-pid = "<?=$value->pid?>">                       
                        <td><?=$i++?></td>
                        <td><?php echo $value->name;?></td>
                        <td><?php echo $ipd->batch_no;?></td>
                        <td><?php echo $ipd->total_quantity;?></td>
                        <td><?php echo $ipd->stock_value;?></td>
                    </tr>
    <?php  
                }
            }
        }
    }
    else
    {
        echo '<tr><td colspan="5" class="text-center">No data found</td></tr>';
    }
    ?>
</tbody>
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

<script>
  $(document).ready(function () {

    $(document).on('click', "#submitSaleReport" , function(e){

      Swal.fire({
        title: "Message",
        html: "Please wait <br> while we are generating report for you.<br>",
    
        showConfirmButton: false    // icon: "warning",
      });
    });

      
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
        link.setAttribute("download", "InventoryProductAdded.csv");
        document.body.appendChild(link);

        link.click(); // Trigger the download
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

    $('#pid').on('change', function () {
        var selectedProductId = $(this).val();
       // Synchronize the table list with the selected product
        if (selectedProductId) {
            // A product is selected, filter rows
            $('#stock-value-data tbody tr').hide();
            $('#stock-value-data tbody tr[data-pid="' + selectedProductId + '"]').show();
        } else {
            // No product is selected, show all rows
            $('#stock-value-data tbody tr').show();
        }
    });

    $('.form_date_cancel').on('click', function (e){
      $('#from_date').val('');
    });

    // $('.expiry_date_cancel').on('click', function (e){
    //   $('#expiry_date').val('');
    // });

 

  });
</script>