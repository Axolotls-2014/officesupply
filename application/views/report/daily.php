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
		            <li class="breadcrumb-item active"><?=$this->lang->line('daily_report')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
            <!-- general form elements disabled -->
            	<form role="form" id="dailyReportForm" name="dailyReportForm" method="POST" action="<?php echo base_url("report/daily") ?>" target="_blank">
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
	                      <div class="form-group">
				                  <label><?=$this->lang->line('date')?></label>
				                  <div class="input-group">
				                    <div class="input-group-prepend">
				                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
				                    </div>
				                    <input type="text" class="form-control datepicker" name="date" id="date" value="<?=date('d-m-Y')?>" style="z-index:999 !important; cursor: pointer;">
				                  </div>
				                </div>
	                    </div>

											<div class="col-sm-3">
	                      <div class="form-group">
				                  <label><?=$this->lang->line('report_type')?></label>
                          <select class="form-control form-control-sm select2bs4" name="report_type" id="report_type" width="100%" class="add-row" placeholder="<?=$this->lang->line('report_type')?>">
                          	<option value="<?=$this->lang->line('report_type_customer_wise')?>"><?=str_replace('_', ' ', strtoupper($this->lang->line('report_type_customer_wise')));?></option>  
														<option value="<?=$this->lang->line('report_type_product_wise')?>"><?=str_replace('_', ' ', strtoupper($this->lang->line('report_type_product_wise')));?></option>
                          </select>
				                </div>
	                    </div>
                      <div class="col-sm-2">
	                    	<div class="form-group">
													<label for="warehouse">
                            <?=$this->lang->line('purchase_warehouse')?>
                            <span class="text-danger">*</span>
                          </label>
                          <select class="form-control form-control-sm select2bs4" name="warehouse_id" id="warehouse_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('purchase_warehouse')?>">
                            <option value="">All</option>
                            <?php
                              foreach ($warehouses as $value) {
                            ?>
                              <option value="<?=$value->id;?>"
                                <?php 
                                  if($value->is_default == WAREHOUSE_IS_DEFAULT_YES)
                                    echo ' selected';
                                ?>
                              >
                                <?= $value->name;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_warehouse_id" class="error invalid-feedback"><?=form_error('warehouse_id');?></span>
                        </div>
	                    </div>

											<div class="col-sm-2">
	                    	<div class="form-group">
                          <label for="customer_id"><?=$this->lang->line('customer')?></label>
                          <select class="form-control form-control-sm select2bs4" name="customer_id" id="customer_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('daily')?>">
                            <option value="">ALL</option>
                            <?php
                              foreach ($customers as $value) 
                              {	
                            ?>
                              <option value="<?=$value->id;?>">
                                <?=$value->customer_name?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <!-- <span id="err_customer_id" class="error invalid-feedback"><?=form_error('customer_id');?></span> -->
                        </div>
	                    </div>

	                    <div class="col-sm-2">
	                      <div class="form-group">
				                  <label><?=$this->lang->line('order_time')?></label>
                          <select class="form-control form-control-sm select2bs4" name="order_time" id="order_time" width="100%" class="add-row" placeholder="<?=$this->lang->line('order_time')?>">
                            <option value=""><?=$this->lang->line('order_time_all')?></option>
                            <option value="<?=$this->lang->line('order_time_pm')?>"><?=$this->lang->line('order_time_am')?></option>
                            <option value="<?=$this->lang->line('order_time_am')?>"><?=$this->lang->line('order_time_pm')?></option>
                          </select>
				                </div>
	                    </div>
											
	                  </div>
		              </div>
	              	<!-- /.card-body -->
		              <div class="card-footer">
		              	<input type="hidden" name="action_type" id="action_type" value="">
		              	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		              	<button type="submit" name="submit" id="submitDailyReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
		              </div>
	            	</div>
	            </form>
	            <div class="card card-primary">
	              <div class="card-header">
	                <h3 class="card-title"><?=$this->lang->line('daily_report')?></h3>
	                <div class="card-tools">
                    <!-- <button type="button" class="btn btn-tool" id='print'>
                    	<i class="fas fa-print"></i>
                    	<?=$this->lang->line('print')?>
                    </button> -->
                    <button type="button" class="btn btn-tool" id='pdf'>
                    	<i class="fas fa-file-pdf"></i>
                    	<?=$this->lang->line('pdf')?>
                    </button>
                  </div>
	              </div>
	              <!-- /.card-header -->
	              <div class="card-body daily_detail">
	              	Click on Search to get the Daily report.
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

   
    // Change event listener for report type dropdown
    $('#report_type').change(function() {
        var reportType = $(this).val();
        if (reportType === 'product_wise') {
            $('#warehouse_id').closest('.col-sm-2').hide().find('select').val('').trigger('change.select2');
            $('#customer_id').closest('.col-sm-2').hide().find('select').val('').trigger('change.select2');
            $('#order_time').closest('.col-sm-2').hide().find('select').val('').trigger('change.select2');
        
        } else {
            $('#warehouse_id').closest('.col-sm-2').show(); 
            $('#customer_id').closest('.col-sm-2').show();
            $('#order_time').closest('.col-sm-2').show(); 
        }
    });

		$('#submitDailyReport').click(function(e){
			// e.preventDefault();

			var isError = false;

			var action_type = $('#action_type').val();

			if(action_type == 'pdf')
			{
				$('form#dailyReportForm .field_validation').each(function() {
          
	          var id    = $(this).attr('id');
	          var value = $(this).val();
	          var field = $(this).attr('placeholder');

	          if(value==null || value==""){
	            $("form#dailyReportForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
	            $('form#dailyReportForm #'+id).addClass('is-invalid');
	            isError = true;
	          }
	          else
	          {
	            $("form#dailyReportForm #err_"+id).text("").fadeOut('slow');
	            $('form#dailyReportForm #'+id).removeClass('is-invalid');
	            $('form#dailyReportForm #'+id).addClass('is-valid');
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
			// else if(action_type == 'print')
			// {
			// 	$('form#dailyReportForm .field_validation').each(function() {
          
	    //       var id    = $(this).attr('id');
	    //       var value = $(this).val();
	    //       var field = $(this).attr('placeholder');

	    //       if(value==null || value==""){
	    //         $("form#dailyReportForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
	    //         $('form#dailyReportForm #'+id).addClass('is-invalid');
	    //         isError = true;
	    //       }
	    //       else
	    //       {
	    //         $("form#dailyReportForm #err_"+id).text("").fadeOut('slow');
	    //         $('form#dailyReportForm #'+id).removeClass('is-invalid');
	    //         $('form#dailyReportForm #'+id).addClass('is-valid');
	    //       }
	    //   });

	    //   if(isError == true)
	    //   {
	    //     return false;
	    //   }  
	    //   else 
	    //   {
			// 		return true;
			// 	}
			// }
			else
			{
				e.preventDefault();

				$('form#dailyReportForm .field_validation').each(function() {
          
	          var id    = $(this).attr('id');
	          var value = $(this).val();
	          var field = $(this).attr('placeholder');

	          if(value==null || value==""){
	            $("form#dailyReportForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
	            $('form#dailyReportForm #'+id).addClass('is-invalid');
	            isError = true;
	          }
	          else
	          {
	            $("form#dailyReportForm #err_"+id).text("").fadeOut('slow');
	            $('form#dailyReportForm #'+id).removeClass('is-invalid');
	            $('form#dailyReportForm #'+id).addClass('is-valid');
	          }
	      });


	      if(isError == true)
	      {
	        return false;
	      }  
	      else 
	      {
	        var formData = $('#dailyReportForm').serialize();
					$(this).text('<?=$this->lang->line('searching')?>');

					setTimeout(function(){ 

						$.ajax({
			        url: '<?php echo base_url("report/daily") ?>',
			        type: 'POST',
			        dataType : 'json',
			        data: formData,                       
			        success: function (response) {
			          $('.daily_detail').html(response.daily_report);
			          $('#submitDailyReport').text('<?=$this->lang->line('search')?>');
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

    $('#order_time').change(function() {
        var selectedTime = $(this).val();

        // if (selectedTime === 'AM' || selectedTime === 'PM') {
            var formData = $('#dailyReportForm').serialize();

            $.ajax({
                url: '<?php echo base_url("report/daily") ?>',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(response) {
                    $('.daily_detail').html(response.daily_report);
                },
                error: function() {
                    alert('Please contact the administrator if you keep facing this issue.');
                }
            });
        // } else {
        //     $('.daily_detail').html('');
        // }
    });
		
		//$('#print').click(function(e){
			//$('#action_type').val('print');		
			//$('#submitDailyReport').trigger('click');  
			//$('#action_type').val('');
		//});
		$('#pdf').click(function(e){
			$('#action_type').val('pdf');
			$('#submitDailyReport').trigger('click');  
			$('#action_type').val('');
		});

		//Date range as a button
    
    // $('#daterange-btn').daterangepicker({

    //     ranges   : {
    //       'Today'       : [moment(), moment()],
    //       'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //       'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
    //       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    //       'This Month'  : [moment().startOf('month'), moment().endOf('month')],
    //       'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //     },
    //     // startDate: moment().subtract(29, 'days'),
    //     // endDate  : moment()
    //   },
    //   function (start, end) {

    //   	// alert(start.format('DD-MM-Y') + ' - ' + end.format('DD-MM-Y'));
    //   	$('#from_date').val(start.format('DD-MM-Y'));
		// 		$('#to_date').val(end.format('DD-MM-Y'));
    //     // $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
    // });
	});
</script>