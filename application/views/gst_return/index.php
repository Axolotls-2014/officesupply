<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item active"><a href="#"><?=$this->lang->line('header_gst_return')?></a></li>
            <!-- <li class="breadcrumb-item active"><?=$this->lang->line('sale_view')?></li> -->
          </ol>
        </div>
      </div>
    </section>

    <style type="text/css">
    	.normal_font{
    		font-weight: normal !important;
    	}
    </style>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
          	<form class="form-horizontal" name="addGstReutrn" id="addGstReutrn" method="post" action="">
	          	<div class="card-header">
	          		<h3 class="card-title"><?=$this->lang->line('header_gst_return')?></h3>	
	          	</div>
	          	<div class="card-body">
	          		<div class="row">
	         				<div class="col-md-12">
	         					
	         					
	         					<div class="form-group clearfix row">
	         						<label for="inputEmail3" class="col-sm-2 col-form-label">
	                    	<?=$this->lang->line('gst_return_type')?>
	                    	<span class="text-danger">*</span>
	                    </label>
	                    <div class="col-sm-4">
	                    	<div class="icheck-primary d-inline">
		                      <input type="radio" id="gst_return_type_monthly" name="gst_return_type" value="0" checked>
		                      <label for="gst_return_type_monthly" class="normal_font">
		                      	<?=$this->lang->line('gst_return_type_monthly')?>
		                      </label>
		                    </div>
		                    <div class="icheck-primary d-inline">
		                      <input type="radio" id="gst_return_type_quarterly" name="gst_return_type" value="1">
		                      <label for="gst_return_type_quarterly" class="normal_font">
		                      	<?=$this->lang->line('gst_return_type_quarterly')?>
		                      </label>
		                    </div>
	                    </div>
	                  </div>

	                  <div class="form-group clearfix row quarterly_row">
	         						<label for="inputEmail3" class="col-sm-2 col-form-label" >
	                    	<?=$this->lang->line('gst_return_select_quarter')?>
	                    	<span class="text-danger">*</span>
	                    </label>
	                    <div class="col-sm-6">
	                    	<div class="icheck-primary d-inline">
		                      <input type="radio" id="gst_return_type_first_quarter" name="gst_return_type_quarter" value="1" checked="">
		                      <label for="gst_return_type_first_quarter" class="normal_font">
		                      	<?=$this->lang->line('gst_return_type_first_quarter')?>
		                      </label>
		                    </div>
		                    <div class="icheck-primary d-inline">
		                      <input type="radio" id="gst_return_type_second_quarter" name="gst_return_type_quarter" value="2">
		                      <label for="gst_return_type_second_quarter" class="normal_font">
		                      	<?=$this->lang->line('gst_return_type_second_quarter')?>
		                      </label>
		                    </div>
		                    <div class="icheck-primary d-inline">
		                      <input type="radio" id="gst_return_type_third_quarter" name="gst_return_type_quarter" value="3">
		                      <label for="gst_return_type_third_quarter" class="normal_font">
		                      	<?=$this->lang->line('gst_return_type_third_quarter')?>
		                      </label>
		                    </div>
		                    <div class="icheck-primary d-inline">
		                      <input type="radio" id="gst_return_type_forth_quarter" name="gst_return_type_quarter" value="4">
		                      <label for="gst_return_type_forth_quarter" class="normal_font">
		                      	<?=$this->lang->line('gst_return_type_forth_quarter')?>
		                      </label>
		                    </div>
	                    </div>
	                  </div>
										<div class="form-group row monthly_row">
	                    <label for="inputEmail3" class="col-sm-2 col-form-label">
	                    	<?=$this->lang->line('gst_return_month')?>
	                    	<span class="text-danger">*</span>
	                    </label>
	                    <div class="col-sm-4">
	                      <select class="form-control form-control-sm select2bs4 field_validation" name="month" id="month" width="100%">
	                      	<option value="">Select</option>
	                      	<?php 
	                      		for ($i=1; $i <= 12; $i++) 
	                      		{ 
	                      	?>
	                        		<option value="<?=$i?>"
	                        			<?php 
	                        				if($i == date('m'))
	                        					echo ' selected';
	                        			?>
	                        		><?=DateTime::createFromFormat('!m', $i)->format('F')?></option>
	                      	<?php
	                      		}
	                      	?>
	                      </select>
	                      <span id="err_month" class="error invalid-feedback"><?=form_error('month');?></span>
	                    </div>
	                  </div>
	                  <div class="form-group row">
	                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('gst_return_year')?><span class="text-danger">*</span></label>
	                    <div class="col-sm-4">
	                      <select class="form-control form-control-sm select2bs4 field_validation" name="year" id="year" width="100%">
	                      	<?php
                            	$year 									= date('Y')+1;
                              
                              
                              for ($i = $year; $i > 1975; $i--) 
                              { 
                            ?>
	                        		<option value="<?=$i?>"><?=$i?></option>
	                      	<?php
	                      		}
	                      	?>
	                      </select>
	                      <span id="err_year" class="error invalid-feedback"><?=form_error('year');?></span>
	                    </div>
	                  </div>             					
		                <div class="form-group clearfix row file_type_row">
	         						<label for="inputEmail3" class="col-sm-2 col-form-label" >
	                    	<?=$this->lang->line('gst_return_type_file_type')?>
	                    	<span class="text-danger">*</span>
	                    </label>
	                    <div class="col-sm-6">
	                    	<div class="icheck-primary d-inline">
		                      <input type="radio" id="gst_return_file_type_csv" name="gst_return_file_type" value="<?=GST_RETURN_FILE_TYPE_CSV?>" checked="">
		                      <label for="gst_return_file_type_csv" class="normal_font">
		                      	<?=$this->lang->line('gst_return_type_file_type_csv')?>
		                      </label>
		                    </div>
		                    <div class="icheck-primary d-inline">
		                      <input type="radio" id="gst_return_file_type_json" name="gst_return_file_type" value="<?=GST_RETURN_FILE_TYPE_JSON?>">
		                      <label for="gst_return_file_type_json" class="normal_font">
		                      	<?=$this->lang->line('gst_return_type_file_type_json')?>
		                      </label>
		                    </div>
	                    </div>
	                  </div>
	         				</div> 			
	          		</div>
	          	</div>
	          	<div class="card-footer">
	              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
	              <button type="submit" name="submit" id="gstReturnSubmit" class="btn btn-info" data-tt="tooltip" title="<?=$this->lang->line('export_json_file')?>">
	              	<?=$this->lang->line('export_json_file')?>	              		
	              	<i class="fa fa-spinner fa-spin text-light export_progress" style="font-size: 18px;display: none"></i>
	              </button>
	              <a href="#"  download="#" class="btn btn-warning file_download" data-tt="tooltip" title="Click here to Download the file" style="display: none">
	              	<i class="fas fa-download"></i>
	              	<?=$this->lang->line('click_to_download')?>
	              </a>
	            </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">
  $(document).ready(function(e){

  	const GSTReturnToast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 10000
      });
    
  	$('.quarterly_row').css('display','none');

		$(document).on('change', 'select[name^="month"], select[name^="year"], input[name^="gst_return_type"],input[name^="gst_return_type_quarter"]', function (event) {

			$('.file_download').fadeOut(100);
	    
   	});
	  

  	$('input[name^="gst_return_type"]').click(function(e){

  		if($(this).is(':checked')){

  			if($(this).val() == 0)
  			{
  				$('.monthly_row').fadeIn(800);
  				$('.quarterly_row').fadeOut(10);
  			}
  			else
  			{
  				$('.monthly_row').fadeOut(10);
  				$('.quarterly_row').fadeIn(800);	
  			}
  		}
    });

    $('#gstReturnSubmit').click(function(e){
    	e.preventDefault();

    	var formData = $('#addGstReutrn').serialize();
    	$('.export_progress').fadeIn(10);

    	setTimeout(function(){ 
    		$.ajax({
	        url: "<?php echo base_url('gst_return/index') ?>",
	        type: "POST",
	        dataType: "json",
	        data:formData,
	        success: function(data){

	          if(data.code == 1)
	          {
	          	GSTReturnToast.fire({
			          type: 'success',
			          title: data.message
			        });

	          	$('.file_download').attr('download',data.file_name);
	          	$('.file_download').attr('href',data.path);
	          	$('.file_download').fadeIn(100);
	          }
	          else
	          {
	          	GSTReturnToast.fire({
			          type: 'error',
			          title: data.message
			        });

			        $('.file_download').attr('download','');
	          	$('.file_download').attr('href','');
	          	$('.file_download').fadeIn(100);
	          }

	          $('.export_progress').fadeOut(10);
	        }
	      });    	
    	}, 2000);


    	
    });


  	// $('#gst_return_type_monthly')

  });
</script>