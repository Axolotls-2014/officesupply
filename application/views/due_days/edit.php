<?php $this->load->view('layout/header');?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb breadcrumb-custom float-sm-left">
                        <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                        <li class="breadcrumb-item"><a href="<?=base_url('due_days')?>">Due Days</a></li>
                        <li class="breadcrumb-item active">Edit Due Day</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" name="editDueDayForm" id="editDueDayForm" method="post" action="<?php echo base_url('due_days/edit');?>">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Edit Due Day</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="due_day" class="col-sm-2 col-form-label">Due Day<span class="text-danger">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="due_day" value="<?=set_value('due_day', $due_day->due_day) ?>" 
                                            class="form-control form-control-sm field_validation" id="due_day" 
                                            placeholder="Due Day">
                                        <span id="err_due_day" class="error invalid-feedback"><?=form_error('due_day');?></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="terms_and_condition" class="col-sm-2 col-form-label">Terms and Condition<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <textarea name="terms_and_condition" class="form-control form-control-sm field_validation" 
                                            id="terms_and_condition" placeholder="Terms and Condition"><?=set_value('terms_and_condition', $due_day->terms_and_condition) ?></textarea>
                                        <span id="err_terms_and_condition" class="error invalid-feedback"><?=form_error('terms_and_condition');?></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="status" class="col-sm-2 col-form-label">Status<span class="text-danger">*</span></label>
                                    <div class="col-sm-4">
                                        <select class="form-control form-control-sm field_validation" name="status" id="status">
                                            <option value="1" <?=set_select('status', '1', ($due_day->status == 1)) ?>>Active</option>
                                            <option value="0" <?=set_select('status', '0', ($due_day->status == 0)) ?>>Inactive</option>
                                        </select>
                                        <span id="err_status" class="error invalid-feedback"><?=form_error('status');?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" 
                                    value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" value="<?=$due_day->id?>" name="id">
                                <button type="submit" name="submit" id="dueDaySubmit" class="btn btn-info">Save</button>
                                <a href="<?=base_url('due_days')?>" class="btn btn-default float-right">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">
$(document).ready(function(){
    // Form submission
    $('form#editDueDayForm').submit(function(e){
        var isError = false;
        $('#dueDaySubmit').text('Please wait...').prop('disabled', true);

        $('form#editDueDayForm .field_validation').each(function() {
            var id = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');
            
            if(value == null || value == ""){
                $("#err_" + id).text(field + " field is required.");
                $(this).addClass('is-invalid');
                isError = true;
            } else {
                $("#err_" + id).text("");
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
        });

        if(isError) {
            $('#dueDaySubmit').text('Save').prop('disabled', false);
            return false;
        }
        return true;
    });

    // Field validation on blur/change
    $("form#editDueDayForm .field_validation").on("blur keyup change", function(){
        var id = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value == null || value == ""){
            $("#err_" + id).text(field + " field is required.");
            $(this).addClass('is-invalid');
        } else {
            $("#err_" + id).text("");
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
});
</script>