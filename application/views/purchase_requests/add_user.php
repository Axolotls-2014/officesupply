<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_people')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('purchase_request/add_sub_user')?>"><?=$this->lang->line('header_users')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('user_add')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="createUserForm" id="createUserForm" method="post" action="<?=base_url('purchase_request/insert_user')?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('user_add')?></h3>
                </div>
                
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_first_name')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="first_name" value="<?=set_value('first_name')?>" class="form-control form-control-sm field_validation" id="first_name" placeholder="<?=$this->lang->line('user_first_name')?>">
                      <span id="err_first_name" class="error invalid-feedback"><?=form_error('first_name');?></span>
                    </div>
                  </div>
                  
                  
                 <div class="form-group row" style="display:none;">
                    <label for="role_id" class="col-sm-2 col-form-label"><?=$this->lang->line('userrole_header')?></label>
                    <div class="col-sm-4">
                        <input type="hidden" name="role_id" id="role_id" value="26">
                    </div>
                 </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_last_name')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="last_name" value="<?=set_value('last_name')?>" class="form-control form-control-sm field_validation" id="last_name" placeholder="<?=$this->lang->line('user_last_name')?>">
                      <span id="err_last_name" class="error invalid-feedback"><?=form_error('last_name');?></span>
                    </div>
                  </div>
                  
                   <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_company')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="company" value="" class="form-control form-control-sm" id="company" placeholder="<?=$this->lang->line('user_company')?>">
                      <span id="err_company" class="error invalid-feedback"><?=form_error('company');?></span>
                    </div>
                  </div> 
                  
                  <!-- <div class="form-group row">-->
                  <!--  <label for="inputEmail3" class="col-sm-2 col-form-label">GSTIN</label>-->
                  <!--  <div class="col-sm-4">-->
                  <!--    <input type="text" name="gstin" value="" class="form-control form-control-sm" id="gstin" placeholder="<?=$this->lang->line('gstin')?>">-->
                  <!--    <span id="err_company" class="error invalid-feedback"><?=form_error('gstin');?></span>-->
                  <!--  </div>-->
                  <!--</div> -->
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_email')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="email" value="<?=set_value('email')?>" class="form-control form-control-sm field_validation" id="email" placeholder="<?=$this->lang->line('user_email')?>">
                      <span id="err_email" class="error invalid-feedback"><?=form_error('email');?></span>
                    </div>
                  </div>
                  
                    <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-4">
                      <input type="text" name="address" value="<?=set_value('address')?>" class="form-control form-control-sm field_validation" id="address" placeholder="Address">
                      <span id="address" class="error invalid-feedback"><?=form_error('address');?></span>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_username')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="identity" value="<?=set_value('identity')?>" class="form-control form-control-sm field_validation <?=(form_error('identity') != '') ? 'is-invalid' : ''?>" id="username" placeholder="<?=$this->lang->line('user_username')?>">
                      <span id="err_username" class="error invalid-feedback"><?=form_error('identity');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_password')?></label>
                    <div class="col-sm-4">
                      <input type="password" name="password" value="" class="form-control form-control-sm field_validation" id="password" placeholder="<?=$this->lang->line('user_password')?>">
                      <span id="err_password" class="error invalid-feedback"><?=form_error('password');?></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_phone')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="phone" value="" class="form-control form-control-sm" id="phone" placeholder="<?=$this->lang->line('user_phone')?>">
                      <span id="err_phone" class="error invalid-feedback"></span>
                    </div>
                  </div>
                </div>
                
                <div class="card-footer">
                  <input type="hidden" name="company_id" value="1">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <button type="submit" name="submit" id="userSubmit" class="btn btn-info"><?=$this->lang->line('user_save')?></button>
                  <a href="<?=base_url('purchase_request/add_sub_user')?>" class="btn btn-default float-right"><?=$this->lang->line('user_cancel')?></a>
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

<script>

  function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }
  $(document).ready(function(e){

    $('form#createUserForm').submit(function(e){
      // e.preventDefault();

      var isError = false;

      $('#userSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#createUserForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#createUserForm #err_"+id).text(field+ " field is required.");
            $('form#createUserForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#createUserForm #err_"+id).text("");
            $('form#createUserForm #'+id).removeClass('is-invalid');
            $('form#createUserForm #'+id).addClass('is-valid');
          }


          if(id == 'email')
          {
            if(!isEmail(value)){
              $("form#createUserForm #err_"+id).text('Please enter valid email address');
              $('form#createUserForm #'+id).addClass('is-invalid');
              isError = true;  
            }
          }

          if(id == 'password')
          {
            if(value.length < 8){
              $("form#createUserForm #err_"+id).text('Password should have minimum 8 Characters');
              $('form#createUserForm #'+id).addClass('is-invalid');
              isError = true;  
            }
          }
      });

      if(isError == true)
      {
        $('#userSubmit').text('<?=$this->lang->line("user_save")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    
    });

    $("form#createUserForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#createUserForm #err_"+id).text(field+ " field is required.");
          $('form#createUserForm #'+id).removeClass('is-valid');
          $('form#createUserForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#createUserForm #err_"+id).text("");
          $('form#createUserForm #'+id).removeClass('is-invalid');
          $('form#createUserForm #'+id).addClass('is-valid');
        }

        if(id == 'email')
        {
          if(!isEmail(value)){
            $("form#createUserForm #err_"+id).text('Please enter valid email address');
            $('form#createUserForm #'+id).addClass('is-invalid');
            isError = true;  
          }
        }

        if(id == 'password')
        {
          if(value.length < 8){
            $("form#createUserForm #err_"+id).text('Password should have minimum 8 Characters');
            $('form#createUserForm #'+id).addClass('is-invalid');
            isError = true;  
          }
        }
    });

  });
</script>