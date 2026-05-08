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
		            <li class="breadcrumb-item active"><?=$this->lang->line('ledger_report')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
            <!-- general form elements disabled -->
            	<form role="form" id="ledgerReportForm" name="ledgerReportForm" method="POST" action="<?php echo base_url("report/ledger") ?>" target="_blank">
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
	                    <div class="col-sm-3">
	                      <div class="form-group">
				                  <label><?=$this->lang->line('from_date')?></label>
				                  <div class="input-group">
				                    <div class="input-group-prepend">
				                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
				                    </div>
				                    <input type="text" class="form-control datepicker" name="from_date" id="from_date" style="z-index:999 !important" readonly="readonly">
				                  </div>
				                </div>
	                    </div>
	                    <div class="col-sm-3">
	                      <div class="form-group">
				                  <label><?=$this->lang->line('to_date')?></label>
				                  <div class="input-group">
				                    <div class="input-group-prepend">
				                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
				                    </div>
				                    <input type="text" class="form-control datepicker" style="z-index:999 !important" name="to_date" id="to_date" value="<?=date('d-m-Y')?>"  readonly="readonly">
				                  </div>
				                </div>
	                    </div>
	                  	<div class="col-sm-3">
	                    	<div class="form-group">
                          <label for="ledger_id"><?=$this->lang->line('ledger')?></label>
                          <select class="form-control form-control-sm select2bs4 field_validation" name="ledger_id" id="ledger_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('ledger')?>">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($ledger_account as $value) 
                              {	
                            ?>
                              <option value="<?=$value->id;?>">
                                <?=$value->title.' - '.$value->group_title?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_ledger_id" class="error invalid-feedback"><?=form_error('ledger_id');?></span>
                        </div>
	                    </div>
	                  </div>
		              </div>
	              	<!-- /.card-body -->
		              <div class="card-footer">
		              	<input type="hidden" name="action_type" id="action_type" value="">
		              	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		              	<button type="submit" name="submit" id="submitLedgerReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
		              </div>
	            	</div>
	            </form>
	            <div class="card card-primary">
	              <div class="card-header">
	                <h3 class="card-title"><?=$this->lang->line('ledger_report')?></h3>
	                <div class="card-tools">
                    <button type="button" class="btn btn-tool" id='print'>
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
	              <div class="card-body ledger_detail">
	              	
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
		$('#submitLedgerReport').click(function(e){
			// e.preventDefault();

			var isError = false;

			var action_type = $('#action_type').val();

			if(action_type == 'pdf')
			{
				$('form#ledgerReportForm .field_validation').each(function() {
          
	          var id    = $(this).attr('id');
	          var value = $(this).val();
	          var field = $(this).attr('placeholder');

	          if(value==null || value==""){
	            $("form#ledgerReportForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
	            $('form#ledgerReportForm #'+id).addClass('is-invalid');
	            isError = true;
	          }
	          else
	          {
	            $("form#ledgerReportForm #err_"+id).text("").fadeOut('slow');
	            $('form#ledgerReportForm #'+id).removeClass('is-invalid');
	            $('form#ledgerReportForm #'+id).addClass('is-valid');
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
			else if(action_type == 'print')
			{
				$('form#ledgerReportForm .field_validation').each(function() {
          
	          var id    = $(this).attr('id');
	          var value = $(this).val();
	          var field = $(this).attr('placeholder');

	          if(value==null || value==""){
	            $("form#ledgerReportForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
	            $('form#ledgerReportForm #'+id).addClass('is-invalid');
	            isError = true;
	          }
	          else
	          {
	            $("form#ledgerReportForm #err_"+id).text("").fadeOut('slow');
	            $('form#ledgerReportForm #'+id).removeClass('is-invalid');
	            $('form#ledgerReportForm #'+id).addClass('is-valid');
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
			else
			{
			   console.log("1");
				e.preventDefault();

				$('form#ledgerReportForm .field_validation').each(function() {
                    console.log("2");
	          var id    = $(this).attr('id');
	          var value = $(this).val();
	          var field = $(this).attr('placeholder');

	          if(value==null || value==""){
	            $("form#ledgerReportForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
	            $('form#ledgerReportForm #'+id).addClass('is-invalid');
	            isError = true;
	          }
	          else
	          {
	            $("form#ledgerReportForm #err_"+id).text("").fadeOut('slow');
	            $('form#ledgerReportForm #'+id).removeClass('is-invalid');
	            $('form#ledgerReportForm #'+id).addClass('is-valid');
	          }
	      });


	      if(isError == true)
	      {
	        return false;
	      }  
	      else 
	      { console.log("3");
	        var formData = $('#ledgerReportForm').serialize();
					$(this).text('<?=$this->lang->line('searching')?>');

					setTimeout(function(){ 
                        console.log(formData);
$.ajax({
    url: '<?php echo base_url("report/ledger") ?>',
    type: 'POST',
    dataType: 'json',
    data: formData,
    success: function(response) {
        $('.ledger_detail').html(response.ledger_report);
        $('#submitLedgerReport').text('<?=$this->lang->line('search')?>');
    },
    error: function(xhr, status, error) {
        console.log(xhr.responseText); // This will show the actual server error
        $('#submitLedgerReport').text('<?=$this->lang->line('search')?>');
        show_message('failure-header', 'Error loading ledger report. Please try again.');
    }
});	
					}, 2000);
	      }

				
			}
		});
		
		$('#print').click(function(e){
			$('#action_type').val('print');		
			$('#submitLedgerReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#pdf').click(function(e){
			$('#action_type').val('pdf');
			$('#submitLedgerReport').trigger('click');  
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