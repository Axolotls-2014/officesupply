<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item "><a href="#">People</a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('auth/users')?>">Users</a></li>
                <li class="breadcrumb-item active">Edit User</li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editUserForm" id="editUserForm" method="post" action="<?=base_url('auth/edit_user/'.$user->id)?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('user_edit')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('userrole_header')?></label>
                    <div class="col-sm-4">
                      <?php 
                        if($user->id == 1)
                        {
                      ?>
                        <div class="input-group">
                          <input type="text" class="form-control form-control-sm" name="role_id" id="role_id" value="<?=$role_id->description?>" disabled="disabled">
                          <div class="input-group-append">
                            <span class="input-group-text" data-tt="tooltip" title="Administrator user role can not be changed."><i class="fas fa-question-circle"></i></span>
                          </div>
                        </div>
                        <input type="hidden" name="role_id" id="role_id" value="<?=$role_id->id?>">
                      <?php
                        }
                        else
                        {
                      ?>
                      <select class="form-control form-control-sm select2bs4 field_validation" name="role_id" id="role_id" width="100%" placeholder="<?=$this->lang->line('userrole_header')?>" class="add-row" readonly>
                        <option value=""><?=$this->lang->line('select')?></option>
                        <?php
                          foreach ($user_roles as $value) {
                        ?>
                          <option value="<?=$value->id;?>" 
                            <?php
                              if($value->id == $role_id->id)
                                echo ' selected'; 
                            ?>
                          >
                            <?= $value->description;?>
                          </option>
                        <?php 
                          }
                        ?>
                      </select>
                      <?php    
                        }
                      ?>
                      <span id="err_role_id" class="error invalid-feedback"><?=form_error('role_id');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_first_name')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="first_name" value="<?=$user->first_name?>" class="form-control form-control-sm field_validation" id="first_name" placeholder="<?=$this->lang->line('user_first_name')?>">
                      <span id="err_first_name" class="error invalid-feedback"><?=form_error('first_name');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_last_name')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="last_name" value="<?=$user->last_name?>" class="form-control form-control-sm field_validation" id="last_name" placeholder="<?=$this->lang->line('user_last_name')?>">
                      <span id="err_last_name" class="error invalid-feedback"><?=form_error('last_name');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_email')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="email" value="<?=$user->email?>" class="form-control form-control-sm field_validation" id="email" placeholder="<?=$this->lang->line('user_email')?>">
                      <span id="err_email" class="error invalid-feedback"><?=form_error('email');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_username')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="identity" value="<?=$user->username?>" class="form-control form-control-sm field_validation" id="username" placeholder="<?=$this->lang->line('user_username')?>">
                      <span id="err_username" class="error invalid-feedback"><?=form_error('username');?></span>
                    </div>
                  </div>
                  
                        <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_name')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="company_name" value="<?=$user->company_name?>" class="form-control form-control-sm field_validation" id="company_name" placeholder="<?=$this->lang->line('company_name')?>">
                      <span id="err_company_name" class="error invalid-feedback"><?=form_error('company_name');?></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_phone')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="phone" value="<?=$user->phone?>" class="form-control form-control-sm" id="phone" placeholder="<?=$this->lang->line('user_phone')?>">
                      <span id="err_phone" class="error invalid-feedback"></span>
                    </div>
                  </div>
                </div>
              
                 <div class="card-footer">
                  <input type="hidden"  name="company_id" value="<?=$user->company_id?>">
                  <input type="hidden"  name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden"  name="id" value="<?=$user->id?>">
                  <button type="submit" name="submit" id="userSubmit" class="btn btn-info"><?=$this->lang->line('user_save')?></button>
                  <?php 
                    if($user->id == $this->session->userdata('user_id'))
                    {
                  ?>
                      <button type="button" name="change_password" id="change_password" class="btn btn-danger" data-toggle="modal" data-target="#change_password_modal">
                        <?=$this->lang->line('user_change_password')?>
                      </button>
                  <?php 
                    }
                    else
                    {
                      if($this->permission_model->has_permission('reset_user_password'))
                      {
                  ?>
                        <button type="button" name="reset_password" id="reset_password" class="btn btn-danger" data-toggle="modal" data-target="#reset_password_modal">
                          <?=$this->lang->line('user_reset_password')?>
                        </button> 
                  <?php
                      } 
                    }
                  ?>
                  <button class="btn btn-default float-right"><?=$this->lang->line('cs_cancel')?></button>
                </div>
                
                
              </div>
              
            </form>
          </div>
        </div>
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>
  <!-- The Modal --> 
  <div class="modal" id="change_password_modal">
    <div class="modal-dialog">
      <div class="modal-content">

        <form id="change_password_form" name="change_password_form" method="POST">

          <!-- Modal Header -->
          <div class="modal-header">
           
            <h4 class="modal-title"><?=$this->lang->line('user_change_password')?></h4>
        
            <h4 class="modal-title"><?=$this->lang->line('user_reset_password')?></h4>
        
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <!-- Modal body -->
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('user_old_password')?></label>
                  <div class="col-sm-8">
                    <input type="password" name="old" value="" class="form-control form-control-sm" id="current_password" placeholder="<?=$this->lang->line('user_old_password')?>">
                    <span id="err_current_password" class="error invalid-feedback"><?=form_error('current_password');?></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('user_new_password')?></label>
                  <div class="col-sm-8">
                    <input type="password" name="new" value="" class="form-control form-control-sm" id="new_password" placeholder="<?=$this->lang->line('user_new_password')?>">
                    <span id="err_new_password" class="error invalid-feedback"><?=form_error('first_name');?></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('user_confirm_new_password')?></label>
                  <div class="col-sm-8">
                    <input type="password" name="new_confirm" value="" class="form-control form-control-sm" id="confirm_new_password" placeholder="<?=$this->lang->line('user_confirm_new_password')?>">
                    <span id="err_confirm_new_password" class="error invalid-feedback"><?=form_error('last_name');?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal footer -->
          <div class="modal-footer">
            <input type="hidden" name="id" value="<?=$user->id?>">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" id="changePasswordSubmit" class="btn btn-info"><?=$this->lang->line('user_change_password')?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('btn_modal_close')?></button>
          </div>

        </form>

      </div>
    </div>
  </div>

  <div class="modal" id="reset_password_modal">
    <div class="modal-dialog">
      <div class="modal-content">

        <form id="reset_password_form" name="reset_password_form" method="POST">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">
              <?=$this->lang->line('user_reset_password')?>
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <!-- Modal body -->
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('user_new_password')?></label>
                  <div class="col-sm-8">
                    <input type="password" name="new" value="" class="form-control form-control-sm" id="new_password" placeholder="<?=$this->lang->line('user_new_password')?>">
                    <span id="err_new_password" class="error invalid-feedback"><?=form_error('first_name');?></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('user_confirm_new_password')?></label>
                  <div class="col-sm-8">
                    <input type="password" name="new_confirm" value="" class="form-control form-control-sm" id="confirm_new_password" placeholder="<?=$this->lang->line('user_confirm_new_password')?>">
                    <span id="err_confirm_new_password" class="error invalid-feedback"><?=form_error('last_name');?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal footer -->
          <div class="modal-footer">
            <input type="hidden" name="id" value="<?=$user->id?>">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" id="resetPasswordSubmit" class="btn btn-info"><?=$this->lang->line('user_reset_password')?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('btn_modal_close')?></button>
          </div>
          
        </form>

      </div>
    </div>
  </div>

<?php $this->load->view('layout/footer');?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const selectAllCheckbox = document.getElementById('select_all_products');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const productPrices = document.querySelectorAll('.product-price');

    // Toggle individual price inputs based on checkbox
    productCheckboxes.forEach((checkbox, index) => {
        checkbox.addEventListener('change', function() {
            const priceInput = this.closest('.card').querySelector('.product-price');
            priceInput.disabled = !this.checked;
        });
    });

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            const priceInput = checkbox.closest('.card').querySelector('.product-price');
            priceInput.disabled = !checkbox.checked;
        });
    });
});
</script>

<script>

  function validPasswordLength(string)
  {
    if(string.length < 8 || string.length > 256)
    {
      return false;
    } 
    else
    {
      return true;
    }
  }

  function isNumberExist(string)
  {
    var i = 0;
    var strings = string.trim();
    var isNumberExistInString = false;
    var character = '';
    while (i <= strings.length){
        character = strings.charAt(i);
        if (/[0-9]/.test( character)){
            isNumberExistInString = true;
            break;
        }
        i++;
    }

    return isNumberExistInString;
  }

  function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }
  
  $(document).ready(function(e){

    $('form#editUserForm').submit(function(e){
      // e.preventDefault();

      var isError = false;

      $('form#editUserForm .field_validation').each(function() {
          
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value=="")
        {
          $("form#editUserForm #err_"+id).text(field+ " field is required.");
          $('form#editUserForm #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#editUserForm #err_"+id).text("");
          $('form#editUserForm #'+id).removeClass('is-invalid');
          $('form#editUserForm #'+id).addClass('is-valid');
        }


        if(id == 'email')
        {
          if(!isEmail(value)){
            $("form#editUserForm #err_"+id).text('Please enter valid email address');
            $('form#editUserForm #'+id).addClass('is-invalid');
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

    $("form#editUserForm .field_validation").on("blur keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editUserForm #err_"+id).text(field+ " field is required.");
          $('form#editUserForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editUserForm #err_"+id).text("");
          $('form#editUserForm #'+id).removeClass('is-invalid');
          $('form#editUserForm #'+id).addClass('is-valid');
        }

        if(id == 'email')
        {
          if(!isEmail(value)){
            $("form#editUserForm #err_"+id).text('Please enter valid email address');
            $('form#editUserForm #'+id).addClass('is-invalid');
            isError = true;  
          }
        }
    });

    /************************************** Change Password ****************************************/

    $("form#change_password_form #current_password").on("blur change",  function (event){
        
      var current_password  = $('form#change_password_form #current_password').val();
      var form              = $(this).closest('form');
      var id                = form.find('input[name="id"]').val();


      if(current_password.trim() != ""){

        $.ajax({
            url: "<?=base_url('auth')?>/old_password_verify",
            type: "POST",
            data: {
                    'current_password' : current_password,
                    'id' : id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
            dataType: "JSON",
            success: function (response) {

              
              if(response['code'] == 0)
              {
                if($('form#change_password_form #current_password').hasClass('is-valid'))
                {
                  $('form#change_password_form #current_password').removeClass('is-valid');
                }
                $('form#change_password_form #current_password').addClass('is-invalid');
                $('form#change_password_form #err_current_password').text('You have entered invalid password');
              }
              else
              {
                if($('form#change_password_form #current_password').hasClass('is-invalid'))
                {
                  $('form#change_password_form #current_password').removeClass('is-invalid');
                }
                $('form#change_password_form #current_password').addClass('is-valid');
                $('form#change_password_form #err_current_password').text(''); 
              }
            },
            error: function () {
                alert("There is technical issue while submitting your query, Kindly call on number mentioned in contact page.");
            }
        });
      }

    });

    $("form#change_password_form #new_password").on("blur keyup change",  function (event){

      var confirm_new_password  = $('form#change_password_form #confirm_new_password').val();
      var new_password          = $('form#change_password_form #new_password').val();

      if(new_password.trim() != "")
      {

        if(validPasswordLength(new_password))
        {
          if($('form#change_password_form #new_password').hasClass('is-invalid'))
          {
            $('form#change_password_form #new_password').removeClass('is-invalid') 
          }
          
          $('form#change_password_form #err_new_password').text('');
          $('form#change_password_form #new_password').addClass('is-valid');


        }
        else
        {
          if($('form#change_password_form #new_password').hasClass('is-valid'))
          {
            $('form#change_password_form #new_password').removeClass('is-valid') 
          }

          $('form#change_password_form #err_new_password').text('Password length must be between 8 to 256');
          $('form#change_password_form #new_password').addClass('is-invalid');
        }  
      }

      if(confirm_new_password.trim() != '')
      {
        if(new_password == confirm_new_password)
        {
          if($('form#change_password_form #new_password').hasClass('is-invalid'))
          {
            $('form#change_password_form #new_password').removeClass('is-invalid') 
          }
          if($('form#change_password_form #confirm_new_password').hasClass('is-invalid'))
          {
            $('form#change_password_form #confirm_new_password').removeClass('is-invalid') 
          }
          
          $('form#change_password_form #err_new_password').text('');
          $('form#change_password_form #new_password').addClass('is-valid');
          $('form#change_password_form #confirm_new_password').addClass('is-valid');            
        }
        else
        {
          if($('form#change_password_form #new_password').hasClass('is-valid'))
          {
            $('form#change_password_form #new_password').removeClass('is-valid') 
          }
          if($('form#change_password_form #confirm_new_password').hasClass('is-valid'))
          {
            $('form#change_password_form #confirm_new_password').removeClass('is-valid') 
          }

          $('form#change_password_form #err_new_password').text('Password and Confirm password must match.');
          $('form#change_password_form #new_password').addClass('is-invalid');
          $('form#change_password_form #confirm_new_password').addClass('is-invalid');
        }
      }
        
    });

    $("form#change_password_form #confirm_new_password").on("blur keyup change",  function (event){

      var confirm_new_password  = $('form#change_password_form #confirm_new_password').val();
      var new_password          = $('form#change_password_form #new_password').val();

      if(new_password.trim() != '')
      {
        if(new_password == confirm_new_password)
        {
          if($('form#change_password_form #confirm_new_password').hasClass('is-invalid'))
          {
            $('form#change_password_form #confirm_new_password').removeClass('is-invalid') 
          }

          if($('form#change_password_form #new_password').hasClass('is-invalid'))
          {
            $('form#change_password_form #new_password').removeClass('is-invalid') 
          }
          
          $('form#change_password_form #err_confirm_new_password').text('');
          $('form#change_password_form #confirm_new_password').addClass('is-valid');
          $('form#change_password_form #new_password').addClass('is-valid');
        }
        else
        {
          if($('form#change_password_form #confirm_new_password').hasClass('is-valid'))
          {
            $('form#change_password_form #confirm_new_password').removeClass('is-valid') 
          }

          if($('form#change_password_form #new_password').hasClass('is-valid'))
          {
            $('form#change_password_form #new_password').removeClass('is-valid') 
          }

          $('form#change_password_form #err_confirm_new_password').text('Password and Confirm password must match.');
          $('form#change_password_form #confirm_new_password').addClass('is-invalid');
          $('form#change_password_form #new_password').addClass('is-invalid');
        }
      }

    });

    $('#change_password_modal').on('hidden.bs.modal', function () {
      $('form#change_password_form #current_password').val('');
      $('form#change_password_form #new_password').val('');
      $('form#change_password_form #confirm_new_password').val('');
      
      $('form#change_password_form #current_password').removeClass('is-invalid');
      $('form#change_password_form #current_password').removeClass('is-valid'); 
      
      $('form#change_password_form #new_password').removeClass('is-invalid');
      $('form#change_password_form #new_password').removeClass('is-valid'); 
      
      $('form#change_password_form #confirm_new_password').removeClass('is-invalid');
      $('form#change_password_form #confirm_new_password').removeClass('is-valid'); 
    });

    $('form#change_password_form').submit(function (event) {
      event.preventDefault();
      
      var form     = $(this).closest('form');
      var id       = form.find('input[name="id"]').val();

      var formData = $('form#change_password_form').serialize();

      var current_password      = $('form#change_password_form #current_password').val().trim();
      var new_password          = $('form#change_password_form #new_password').val().trim();
      var confirm_new_password  = $('form#change_password_form #confirm_new_password').val().trim();
      
      if((new_password != confirm_new_password) || (new_password == '' && confirm_new_password == ''))
      {  
        if((new_password != confirm_new_password))
        {
          if($('form#change_password_form #confirm_new_password').hasClass('is-invalid'))
          {
            $('form#change_password_form #confirm_new_password').removeClass('is-invalid') 
          }

          if($('form#change_password_form #new_password').hasClass('is-invalid'))
          {
            $('form#change_password_form #new_password').removeClass('is-invalid') 
          }
          
          $('form#change_password_form #err_confirm_new_password').text('Password and Confirm password must match.');
          $('form#change_password_form #err_new_password').text('Password and Confirm password must match.');
          $('form#change_password_form #confirm_new_password').addClass('is-valid');
          $('form#change_password_form #new_password').addClass('is-valid');
          return false;  
        }

        if((new_password == '' && confirm_new_password == ''))
        {
          if($('form#change_password_form #confirm_new_password').hasClass('is-valid'))
          {
            $('form#change_password_form #confirm_new_password').removeClass('is-valid') 
          }

          if($('form#change_password_form #new_password').hasClass('is-invalid'))
          {
            $('form#change_password_form #new_password').removeClass('is-invalid') 
          }
          
          $('form#change_password_form #err_confirm_new_password').text('Password and Confirm password must not be blank.');
          $('form#change_password_form #err_new_password').text('Password and Confirm password must not be blank.');
          $('form#change_password_form #confirm_new_password').addClass('is-invalid');
          $('form#change_password_form #new_password').addClass('is-invalid');
          return false;  
        }
        
      } 
      else
      {
        if($('form#change_password_form #confirm_new_password').hasClass('is-invalid'))
        {
          $('form#change_password_form #confirm_new_password').removeClass('is-invalid') 
        }

        if($('form#change_password_form #new_password').hasClass('is-invalid'))
        {
          $('form#change_password_form #new_password').removeClass('is-invalid') 
        }
        
        $('form#change_password_form #err_confirm_new_password').text('');
        $('form#change_password_form #confirm_new_password').addClass('is-valid');
        $('form#change_password_form #new_password').addClass('is-valid');
      }

      if(current_password == "")
      {
        if($('form#change_password_form #current_password').hasClass('is-valid'))
        {
          $('form#change_password_form #current_password').removeClass('is-valid');
        }
        $('form#change_password_form #current_password').addClass('is-invalid');
        $('form#change_password_form #err_current_password').text('Current password can not be blank.');
        return false;
      }
      else
      {
        $.ajax({
            url: "<?=base_url('auth/old_password_verify')?>",
            type: "POST",
            data: {
                    'current_password' : current_password,
                    'id' : id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
            dataType: "JSON",
            success: function (response) {
              
              if(response['code'] == 0)
              {
                if($('form#change_password_form #current_password').hasClass('is-valid'))
                {
                  $('form#change_password_form #current_password').removeClass('is-valid');
                }
                $('form#change_password_form #current_password').addClass('is-invalid');
                $('form#change_password_form #err_current_password').text('You have entered invalid password');
                return false;
              }
              else
              {
                if($('form#change_password_form #current_password').hasClass('is-invalid'))
                {
                  $('form#change_password_form #current_password').removeClass('is-invalid');
                }
                $('form#change_password_form #current_password').addClass('is-valid');
                $('form#change_password_form #err_current_password').text(''); 
              }
            },
            error: function () {
                alert("There is technical issue while submitting your query, Kindly call on number mentioned in contact page.");
            }
        });
      }

      const ChangePasswordToast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 10000
        });
      
      $('form#change_password_form #changePasswordSubmit').text('<?=$this->lang->line("please_wait")?>');

              
      $.ajax({
          url: "<?php echo base_url('auth/change_password')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function (response) {
            
            if(response['code'] == 1)
            {
              $('#change_password_modal').modal('hide');

              ChangePasswordToast.fire({
                type: 'success',
                title: response['message']
              });
              $('form#change_password_form #changePasswordSubmit').text('<?=$this->lang->line("user_change_password")?>');
            }
            else
            {
             
              ChangePasswordToast.fire({
                type: 'error',
                title: response['message']
              });
              $('form#change_password_form #changePasswordSubmit').text('<?=$this->lang->line("user_change_password")?>');
            }

         
            

          },
          error: function () {
              alert("There is technical issue while submitting your query, Kindly call on number mentioned in contact page.");
              $('form#change_password_form #changePasswordSubmit').text('<?=$this->lang->line("user_change_password")?>');
          }
      });
        

      setTimeout(function() {
          $('form#change_password_form .err_change_password').fadeOut('slow');
          
          if($("form#change_password_form .err_change_password").hasClass("text-success"))
            $("form#change_password_form .err_change_password").removeClass("text-success");

          if($("form#change_password_form .err_change_password").hasClass("text-danger"))
            $("form#change_password_form .err_change_password").removeClass("text-danger");

          $('form#change_password_form .err_change_password').html('');

      }, 5000); 
    });

    /************************************** Reset Password ****************************************/


    $("form#reset_password_form #new_password").on("blur keyup change",  function (event){

      var confirm_new_password  = $('form#reset_password_form #confirm_new_password').val();
      var new_password          = $('form#reset_password_form #new_password').val();

      if(new_password.trim() != "")
      {

        if(validPasswordLength(new_password))
        {
          if($('form#reset_password_form #new_password').hasClass('is-invalid'))
          {
            $('form#reset_password_form #new_password').removeClass('is-invalid') 
          }
          
          $('form#reset_password_form #err_new_password').text('');
          $('form#reset_password_form #new_password').addClass('is-valid');


        }
        else
        {
          if($('form#reset_password_form #new_password').hasClass('is-valid'))
          {
            $('form#reset_password_form #new_password').removeClass('is-valid') 
          }

          $('form#reset_password_form #err_new_password').text('Password length must be between 8 to 256');
          $('form#reset_password_form #new_password').addClass('is-invalid');
        }  
      }

      if(confirm_new_password.trim() != '')
      {
        if(new_password == confirm_new_password)
        {
          if($('form#reset_password_form #new_password').hasClass('is-invalid'))
          {
            $('form#reset_password_form #new_password').removeClass('is-invalid') 
          }
          if($('form#reset_password_form #confirm_new_password').hasClass('is-invalid'))
          {
            $('form#reset_password_form #confirm_new_password').removeClass('is-invalid') 
          }
          
          $('form#reset_password_form #err_new_password').text('');
          $('form#reset_password_form #new_password').addClass('is-valid');
          $('form#reset_password_form #confirm_new_password').addClass('is-valid');            
        }
        else
        {
          if($('form#reset_password_form #new_password').hasClass('is-valid'))
          {
            $('form#reset_password_form #new_password').removeClass('is-valid') 
          }
          if($('form#reset_password_form #confirm_new_password').hasClass('is-valid'))
          {
            $('form#reset_password_form #confirm_new_password').removeClass('is-valid') 
          }

          $('form#reset_password_form #err_new_password').text('Password and Confirm password must match.');
          $('form#reset_password_form #new_password').addClass('is-invalid');
          $('form#reset_password_form #confirm_new_password').addClass('is-invalid');
        }
      }
        
    });

    $("form#reset_password_form #confirm_new_password").on("blur keyup change",  function (event){

      var confirm_new_password  = $('form#reset_password_form #confirm_new_password').val();
      var new_password          = $('form#reset_password_form #new_password').val();

      if(new_password.trim() != '')
      {
        if(new_password == confirm_new_password)
        {
          if($('form#reset_password_form #confirm_new_password').hasClass('is-invalid'))
          {
            $('form#reset_password_form #confirm_new_password').removeClass('is-invalid') 
          }

          if($('form#reset_password_form #new_password').hasClass('is-invalid'))
          {
            $('form#reset_password_form #new_password').removeClass('is-invalid') 
          }
          
          $('form#reset_password_form #err_confirm_new_password').text('');
          $('form#reset_password_form #confirm_new_password').addClass('is-valid');
          $('form#reset_password_form #new_password').addClass('is-valid');
        }
        else
        {
          if($('form#reset_password_form #confirm_new_password').hasClass('is-valid'))
          {
            $('form#reset_password_form #confirm_new_password').removeClass('is-valid') 
          }

          if($('form#reset_password_form #new_password').hasClass('is-valid'))
          {
            $('form#reset_password_form #new_password').removeClass('is-valid') 
          }

          $('form#reset_password_form #err_confirm_new_password').text('Password and Confirm password must match.');
          $('form#reset_password_form #confirm_new_password').addClass('is-invalid');
          $('form#reset_password_form #new_password').addClass('is-invalid');
        }
      }

    });

    $('#reset_password_modal').on('hidden.bs.modal', function () {
      $('form#reset_password_form #current_password').val('');
      $('form#reset_password_form #new_password').val('');
      $('form#reset_password_form #confirm_new_password').val('');
      
      $('form#reset_password_form #current_password').removeClass('is-invalid');
      $('form#reset_password_form #current_password').removeClass('is-valid'); 
      
      $('form#reset_password_form #new_password').removeClass('is-invalid');
      $('form#reset_password_form #new_password').removeClass('is-valid'); 
      
      $('form#reset_password_form #confirm_new_password').removeClass('is-invalid');
      $('form#reset_password_form #confirm_new_password').removeClass('is-valid'); 
    });

    $('form#reset_password_form').submit(function (event) {
      event.preventDefault();
      
      var form     = $(this).closest('form');
      var id       = form.find('input[name="id"]').val();

      var formData = $('form#reset_password_form').serialize();

      var new_password          = $('form#reset_password_form #new_password').val().trim();
      var confirm_new_password  = $('form#reset_password_form #confirm_new_password').val().trim();
      
      if((new_password != confirm_new_password) || (new_password == '' && confirm_new_password == ''))
      {  
        if((new_password != confirm_new_password))
        {
          if($('form#reset_password_form #confirm_new_password').hasClass('is-invalid'))
          {
            $('form#reset_password_form #confirm_new_password').removeClass('is-invalid') 
          }

          if($('form#reset_password_form #new_password').hasClass('is-invalid'))
          {
            $('form#reset_password_form #new_password').removeClass('is-invalid') 
          }
          
          $('form#reset_password_form #err_confirm_new_password').text('Password and Confirm password must match.');
          $('form#reset_password_form #err_new_password').text('Password and Confirm password must match.');
          $('form#reset_password_form #confirm_new_password').addClass('is-valid');
          $('form#reset_password_form #new_password').addClass('is-valid');
          return false;  
        }

        if((new_password == '' && confirm_new_password == ''))
        {
          if($('form#reset_password_form #confirm_new_password').hasClass('is-valid'))
          {
            $('form#reset_password_form #confirm_new_password').removeClass('is-valid') 
          }

          if($('form#reset_password_form #new_password').hasClass('is-invalid'))
          {
            $('form#reset_password_form #new_password').removeClass('is-invalid') 
          }
          
          $('form#reset_password_form #err_confirm_new_password').text('Password and Confirm password must not be blank.');
          $('form#reset_password_form #err_new_password').text('Password and Confirm password must not be blank.');
          $('form#reset_password_form #confirm_new_password').addClass('is-invalid');
          $('form#reset_password_form #new_password').addClass('is-invalid');
          return false;  
        }
        
      } 
      else
      {
        if($('form#reset_password_form #confirm_new_password').hasClass('is-invalid'))
        {
          $('form#reset_password_form #confirm_new_password').removeClass('is-invalid') 
        }

        if($('form#reset_password_form #new_password').hasClass('is-invalid'))
        {
          $('form#reset_password_form #new_password').removeClass('is-invalid') 
        }
        
        $('form#reset_password_form #err_confirm_new_password').text('');
        $('form#reset_password_form #confirm_new_password').addClass('is-valid');
        $('form#reset_password_form #new_password').addClass('is-valid');
      }

      

      const ChangePasswordToast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 10000
        });
      
      $('form#reset_password_form #resetPasswordSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

              
      $.ajax({
          url: "<?php echo base_url('auth/reset_password_user_defined')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function (response) {
            
            if(response['code'] == 1)
            {
              $('#reset_password_modal').modal('hide');

              ChangePasswordToast.fire({
                type: 'success',
                title: response['message']
              });
            }
            else
            {
             
              ChangePasswordToast.fire({
                type: 'error',
                title: response['message']
              });
              
            }

            $('form#reset_password_form #resetPasswordSubmit').text('<?=$this->lang->line("user_reset_password")?>').removeAttr('disabled');
         
            

          },
          error: function () {
              alert("There is technical issue while submitting your query, Kindly call on number mentioned in contact page.");
              $('form#reset_password_form #resetPasswordSubmit').text('<?=$this->lang->line("user_reset_password")?>').removeAttr('disabled');
          }
      });
        

      setTimeout(function() {
          $('form#reset_password_form .err_change_password').fadeOut('slow');
          
          if($("form#reset_password_form .err_change_password").hasClass("text-success"))
            $("form#reset_password_form .err_change_password").removeClass("text-success");

          if($("form#reset_password_form .err_change_password").hasClass("text-danger"))
            $("form#reset_password_form .err_change_password").removeClass("text-danger");

          $('form#reset_password_form .err_change_password').html('');

      }, 5000); 
    });

  });
</script>


<div class="modal" id="message">
  <div class="modal-dialog message-dialog">
    <div class="modal-content message-content">
      <!-- Modal Header -->
      <div class="modal-header message-header success-header">
        <h4 class="modal-title message-title"><?=$this->lang->line('message')?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body message-body">
        <?=$this->lang->line('message')?>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('btn_modal_close')?></button>
      </div>

    </div>
  </div>
</div>