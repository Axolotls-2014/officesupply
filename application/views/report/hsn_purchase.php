<?php $this->load->view('layout/header'); ?>

	<style type="text/css">
		.footer_data{
			font-size: 16px;
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
		            <li class="breadcrumb-item active"><?=$this->lang->line('header_hsn_purchase')?></li>
		          </ol>
		        </div>
		      </div>
		    </section>

		    <!-- Main content -->
		    <section class="content">
		    	<div class="row">
		    		<div class="col-md-12">
            <!-- general form elements disabled -->
            	<form role="form" id="hsnReportForm" name="hsnReportForm" method="POST" action="<?php echo base_url("report/hsn_purchase") ?>" target="_blank">
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
	                    <div class="col-sm-3">
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
	                    <div class="col-sm-3">
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
	                    <div class="col-sm-3">
	                    	<div class="form-group">
                          <label for="purchase_id">Purchase</label>
                          <select class="form-control form-control-sm select2bs4" name="purchase_id" id="purchase_id" width="100%" class="add-row">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($purchases as $value) {
                            ?>
                              <option value="<?=$value->id;?>">
                                <?=$value->reference_no.' - '.$value->company_name;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_purchase_id" class="error invalid-feedback"><?=form_error('purchase_id');?></span>
                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                    	<div class="form-group">
                          <label for="hsn_grouping"><?=$this->lang->line('hsn_grouping')?></label>
                          <select class="form-control form-control-sm select2bs4" name="hsn_grouping" id="hsn_grouping" width="100%" class="add-row">
                            <option value="<?=HSN_GROUPING_COMBINED?>"><?=clean_e_val(HSN_GROUPING_COMBINED)?></option>
                            <option value="<?=HSN_GROUPING_INDIVIDUAL?>"><?=clean_e_val(HSN_GROUPING_INDIVIDUAL)?></option>
                          </select>
                          <span id="err_hsn_grouping" class="error invalid-feedback"><?=form_error('hsn_grouping');?></span>
                        </div>
	                    </div>
	                  </div>
	                  <div class="col-sm-12 d-none">
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
		              	<button type="submit" name="submit" id="submitHsnReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
		              </div>
	            	</div>
	            </form>
	            <div class="card card-primary">
	              <div class="card-header">
	                <h3 class="card-title"><?=$this->lang->line('product_report')?></h3>
	                <div class="card-tools">
	                	<button type="button" class="btn btn-tool bt-danger" id="export">
                    	<i class="fas fa-file-export"></i>
                    	<?=$this->lang->line('export')?>
                    </button>
                    <!-- <button type="button" class="btn btn-tool" id='print'>
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
	              <div class="card-body hsn_list">
	                <table class="table table-bordered table-striped">
		                <thead>
		                  <tr>
		                  	<th>SL</th>
		                  	<th>HSN</th>
		                  	<th>Description</th>
		                  	<th>UOM</th>
		                  	<th>QTY</th>
		                  	<th>Total Value</th>
		                  	<th>Rate</th>
		                  	<th>Taxable Value</th>
		                  	<th>IGST</th>
		                  	<th>CGST</th>
		                  	<th>SGST</th>
		                  </tr>
		                </thead>
		                <tbody>
		                  <tr>
		                  	<td colspan="11">Please click on Search button to get records</td>
		                  </tr>
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
		$('#submitHsnReport').click(function(e){
			// e.preventDefault();

			var action_type = $('#action_type').val();

			if(action_type == 'export')
			{
				return true;
			}
			else if(action_type == 'pdf')
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

				var formData = $('#hsnReportForm').serialize();
				$(this).text('<?=$this->lang->line('searching')?>');

				setTimeout(function(){ 

					$.ajax({
		        url: '<?php echo base_url("report/hsn_purchase") ?>',
		        type: 'POST',
		        dataType : 'json',
		        data: formData,                       
		        success: function (response) {
		          $('.hsn_list').html(response.products);
		          $('#submitHsnReport').text('<?=$this->lang->line('search')?>');
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
			$('#submitHsnReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#print').click(function(e){
			$('#action_type').val('print');		
			$('#submitHsnReport').trigger('click');  
			$('#action_type').val('');
		});
		$('#pdf').click(function(e){
			$('#action_type').val('pdf');
			$('#submitHsnReport').trigger('click');  
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