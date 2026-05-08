<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_setting')?></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('sms_setup_list')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="smsSetupForm" id="smsSetupForm" method="post" enctype="multipart/form-data">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('sms_setup_list')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('sms_setup_api_url')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="api_url" value="<?=set_value('api_url',$sms_setup->api_url) ?>" class="form-control form-control-sm field_validation" id="api_url" placeholder="API Url"><?=form_error('api_url', '<div class="text-danger">', '</div>');?>
                      <span id="err_api_url" class="error invalid-feedback"><?=form_error('api_url');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('sms_setup_sender')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="sender" value="<?=set_value('sender',$sms_setup->sender) ?>" class="form-control form-control-sm field_validation" id="sender" placeholder="Sender"><?=form_error('sender', '<div class="text-danger">', '</div>');?>
                      <span id="err_sender" class="error invalid-feedback"><?=form_error('sender');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('sms_setup_route')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="route" value="<?=set_value('route',$sms_setup->route) ?>" class="form-control form-control-sm field_validation" id="route" placeholder="Route"><?=form_error('api_route', '<div class="text-danger">', '</div>');?>
                      <span id="err_route" class="error invalid-feedback"><?=form_error('api_route');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('sms_setup_auth_key')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="auth_key" value="<?=set_value('auth_key',$sms_setup->auth_key) ?>" class="form-control form-control-sm field_validation" id="auth_key" placeholder="Auth key"><?=form_error('auth_key', '<div class="text-danger">', '</div>');?>
                      <span id="err_auth_key" class="error invalid-feedback"><?=form_error('auth_key');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('sms_setup_unicode')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="unicode" value="<?=set_value('unicode',$sms_setup->unicode) ?>" class="form-control form-control-sm field_validation" id="unicode" placeholder="Unicode"><?=form_error('unicode', '<div class="text-danger">', '</div>');?>
                      <span id="err_unicode" class="error invalid-feedback"><?=form_error('unicode');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('sms_setup_country')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="country" value="<?=set_value('country',$sms_setup->country) ?>" class="form-control form-control-sm field_validation" id="country" placeholder="Country"><?=form_error('country', '<div class="text-danger">', '</div>');?>
                      <span id="err_country" class="error invalid-feedback"><?=form_error('country');?></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <button type="submit" id="smssettingSubmit" name="smssettingSubmit" class="btn btn-info" data-tt="tooltip" title="Click here to Save"><?=$this->lang->line('sms_setup_save')?></button>
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
 
  $(document).ready(function(e){

    $('#smssettingSubmit').click(function(e){
      // e.preventDefault();

      var isError = false;

      $('form#smsSetupForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#smsSetupForm #err_"+id).text(field+ " field is required.");
            $('form#smsSetupForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#smsSetupForm #err_"+id).text("");
            $('form#smsSetupForm #'+id).removeClass('is-invalid');
            $('form#smsSetupForm #'+id).addClass('is-valid');
          }

          if(id == 'sender')
          {
            if(value.length != 6){
              $("form#smsSetupForm #err_"+id).text('Please enter 6 Character sender ID');
              $('form#smsSetupForm #'+id).addClass('is-invalid');
              isError = true;  
            }
          }

          if(id == 'password')
          {
            if(value.length < 8){
              $("form#smsSetupForm #err_"+id).text('Password should have minimum 8 Characters');
              $('form#smsSetupForm #'+id).addClass('is-invalid');
              isError = true;  
            }
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


    });

    $("form#smsSetupForm .field_validation").on("blur keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value=="")
        {
          $('form#smsSetupForm #'+id).addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#smsSetupForm #err_"+id).text("");
          $('form#smsSetupForm #'+id).removeClass('is-invalid');
          $('form#smsSetupForm #'+id).addClass('is-valid');
        }

        if(id == 'sender')
        {
          if(value.length != 6){
            $("form#smsSetupForm #err_"+id).text('Please enter 6 Character sender ID');
            $('form#smsSetupForm #'+id).addClass('is-invalid');
            isError = true;  
          }
        }

        if(id == 'password')
        {
          if(value.length < 8){
            $("form#smsSetupForm #err_"+id).text('Password should have minimum 8 Characters');
            $('form#smsSetupForm #'+id).addClass('is-invalid');
            isError = true;  
          }
        }
    });

  });
</script>