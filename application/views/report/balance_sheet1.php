<?php $this->load->view('layout/header'); ?>

	<style type="text/css">
		.footer_data{
			font-size: 20px;
		}
	</style>
  	<div class="wrapper">
	  	<div class="content-wrapper">
		    <section class="content-header">
		      <div class="row mb-2">
		        <div class="col-sm-12">
		          <ol class="breadcrumb breadcrumb-custom float-sm-left">
		            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
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
            <!-- general form elements disabled -->
            	<form role="form" id="balanceSheetReportForm" name="balanceSheetReportForm" method="POST" action="<?php echo base_url("report/balance_sheet") ?>" target="_blank">
	            	<div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title">
		                	<i class="fas fa-funnel-dollar"></i>
		                	<?=$this->lang->line('filter_report')?>
		                </h3>
		                <div class="card-tools">
	                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
	                    	<i class="fas fa-minus"></i>
	                    </button>
	                  </div>
		              </div>
	              	<!-- /.card-header -->
		              <div class="card-body"> 
	                  <div class="row">
	                    <div class="col-sm-4">
	                    	<div class="form-group">
                          <label for="year_ending"><?=$this->lang->line('year_ending')?></label>
                         <select class="form-control form-control-sm select2bs4 field_validation" name="year_ending" id="year_ending" width="100%" class="add-row" placeholder="<?=$this->lang->line('year_ending')?>">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                            	$year 									= date('Y')+1;
                              for ($i = $year; $i > 1975; $i--) 
                              { 
                            ?>
                              <option value="<?=$i?>"><?=$i.'-'.($i-1)?></option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_year_ending" class="error invalid-feedback"><?=form_error('customer_id');?></span>
                        </div>
	                    </div>
	                  </div>
		              </div>
	              	<!-- /.card-body -->
		              <div class="card-footer">
		              	<input type="hidden" name="action_type" id="action_type" value="">
		              	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		              	<button type="submit" name="submit" id="submitBalanceSheetReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
		              </div>
	            	</div>
	            </form>
	            <div class="card card-primary">
	              <div class="card-header">
	                <h3 class="card-title"><?=$this->lang->line('header_balance_sheet')?></h3>
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
	              <div class="card-body balance_sheet">
	              	
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
		$('#submitBalanceSheetReport').click(function(e){
			// e.preventDefault();
			var isError = false;
			var action_type = $('#action_type').val();

			if(action_type == 'pdf')
			{

				$('form#balanceSheetReportForm .field_validation').each(function() {
          
	          var id    = $(this).attr('id');
	          var value = $(this).val();
	          var field = $(this).attr('placeholder');

	          if(value==null || value==""){
	            $("form#balanceSheetReportForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
	            $('form#balanceSheetReportForm #'+id).addClass('is-invalid');
	            isError = true;
	          }
	          else
	          {
	            $("form#balanceSheetReportForm #err_"+id).text("").fadeOut('slow');
	            $('form#balanceSheetReportForm #'+id).removeClass('is-invalid');
	            $('form#balanceSheetReportForm #'+id).addClass('is-valid');
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
				
					return true;
				
			}
			else
			{
				e.preventDefault();

				$('form#balanceSheetReportForm .field_validation').each(function() {
          
	          var id    = $(this).attr('id');
	          var value = $(this).val();
	          var field = $(this).attr('placeholder');

	          if(value==null || value==""){
	            $("form#balanceSheetReportForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
	            $('form#balanceSheetReportForm #'+id).addClass('is-invalid');
	            isError = true;
	          }
	          else
	          {
	            $("form#balanceSheetReportForm #err_"+id).text("").fadeOut('slow');
	            $('form#balanceSheetReportForm #'+id).removeClass('is-invalid');
	            $('form#balanceSheetReportForm #'+id).addClass('is-valid');
	          }
	      });


	      if(isError == true)
	      {
	        return false;
	      }  
	      else 
	      {

					var formData = $('#balanceSheetReportForm').serialize();
					$(this).text('<?=$this->lang->line('searching')?>');

					setTimeout(function(){ 

						$.ajax({
			        url: '<?php echo base_url("report/balance_sheet") ?>',
			        type: 'POST',
			        dataType : 'json',
			        data: formData,                       
			        success: function (response) {
			          $('.balance_sheet').html(response.balance_sheet);
			          $('#submitBalanceSheetReport').text('<?=$this->lang->line('search')?>');
			        },
			        error: function () 
			        { 
			        	$('#submitBalanceSheetReport').text('<?=$this->lang->line('search')?>');
			          show_message('failure-header','Please contact the administrator if you are keep facing this issue.');   
			        }
			      });	
					}, 2000);
				}
			}
		});

		// $('#export').click(function(e){
		// 	$('#action_type').val('export');
		// 	$('#submitBalanceSheetReport').trigger('click');  
		// 	$('#action_type').val('');
		// });
		$('#print').click(function(e){
			$('#action_type').val('print');		
			$('#submitBalanceSheetReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#pdf').click(function(e){
			$('#action_type').val('pdf');
			$('#submitBalanceSheetReport').trigger('click');  
			$('#action_type').val('');
		});
	});
</script>