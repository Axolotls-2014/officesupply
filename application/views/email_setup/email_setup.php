<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_setting')?></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('emailsetup_list')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="emailSetupForm" id="emailSetupForm" method="post" enctype="multipart/form-data">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('emailsetup_list')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('emailsetup_protocol')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="protocol" value="<?=set_value('protocol',$email_setup->protocol)?>" class="form-control form-control-sm field_validation" id="protocol" placeholder="<?=$this->lang->line('emailsetup_protocol')?>">
                      <span id="err_protocol" class="error invalid-feedback"><?=form_error('protocol');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('emailsetup_encryption')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="encryption" value="<?=set_value('encryption',$email_setup->encryption)?>" class="form-control form-control-sm field_validation" id="encryption" placeholder="<?=$this->lang->line('emailsetup_encryption')?>">
                      <span id="err_encryption" class="error invalid-feedback"><?=form_error('encryption');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('emailsetup_host')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="host" value="<?=set_value('host',$email_setup->host)?>" class="form-control form-control-sm field_validation" id="host" placeholder="<?=$this->lang->line('emailsetup_host')?>">
                      <span id="err_host" class="error invalid-feedback"><?=form_error('host');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('emailsetup_port')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="port" value="<?=set_value('port',$email_setup->port)?>" class="form-control form-control-sm field_validation" id="port" placeholder="<?=$this->lang->line('emailsetup_port')?>">
                      <span id="err_port" class="error invalid-feedback"><?=form_error('port');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('emailsetup_email')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="email" name="email" value="<?=set_value('email',$email_setup->email)?>" class="form-control form-control-sm field_validation" id="email" placeholder="<?=$this->lang->line('emailsetup_email')?>">
                      <span id="err_email" class="error invalid-feedback"><?=form_error('email');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('emailsetup_username')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="username" value="<?=set_value('username',$email_setup->username)?>" class="form-control form-control-sm field_validation" id="username" placeholder="<?=$this->lang->line('emailsetup_username')?>">
                      <span id="err_username" class="error invalid-feedback"><?=form_error('username');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('emailsetup_password')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="password" name="password" value="<?=set_value('password',$email_setup->password)?>" class="form-control form-control-sm field_validation" id="password" placeholder="<?=$this->lang->line('emailsetup_password')?>">
                      <span id="err_password" class="error invalid-feedback"><?=form_error('password');?></span>
                    </div>
                  </div>
                  
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <button type="submit" id="emailSettingSubmit" name="emailSettingSubmit" class="btn btn-info" data-tt="tooltip" title="Click here to Save"><?=$this->lang->line('emailsetup_save')?></button>
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

    $('#emailSettingSubmit').click(function(e){
      // e.preventDefault();

      var isError = false;
      $('#emailSettingSubmit').text('<?=$this->lang->line("please_wait")?>');


      $('form#emailSetupForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#emailSetupForm #err_"+id).text(field+ " field is required.");
            $('form#emailSetupForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#emailSetupForm #err_"+id).text("");
            $('form#emailSetupForm #'+id).removeClass('is-invalid');
            $('form#emailSetupForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#emailSettingSubmit').text('<?=$this->lang->line("emailsetup_save")?>');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#emailSetupForm .field_validation").on("blur keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#emailSetupForm #err_"+id).text(field+ " field is required.");
          $('form#emailSetupForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#emailSetupForm #err_"+id).text("");
          $('form#emailSetupForm #'+id).removeClass('is-invalid');
          $('form#emailSetupForm #'+id).addClass('is-valid');
        }
    });

  });
</script>