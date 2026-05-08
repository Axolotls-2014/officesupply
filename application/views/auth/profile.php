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
                <li class="breadcrumb-item active">Edit Profile</li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editUserForm" id="editUserForm" method="post" action="<?=base_url('auth/profile/'.$user->id)?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('user_edit')?></h3>
                </div>
                <div class="card-body">
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
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('user_phone')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="phone" value="<?=$user->phone?>" class="form-control form-control-sm" id="phone" placeholder="<?=$this->lang->line('user_phone')?>">
                      <span id="err_phone" class="error invalid-feedback"></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="id" value="<?=$user->id?>">
                  <button type="submit" name="submit" id="userSubmit" class="btn btn-info"><?=$this->lang->line('user_save')?></button>
                  <button type="button" name="change_password" id="change_password" class="btn btn-danger" data-toggle="modal" data-target="#change_password_modal"><?=$this->lang->line('user_change_password')?></button>
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
        <?php echo form_open("auth/change_password",'class="form-horizontal row-border" id="change_password_form"');?>
          <!-- Modal Header -->
          <div class="modal-header info-header">
            <h4 class="modal-title"><?=$this->lang->line('user_change_password')?></h4>
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
                    <span id="err_new_password" class="error invalid-feedback"></span>
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
            <button type="submit" name="submit" id="changePasswordSubmit" class="btn btn-info"><?=$this->lang->line('user_change_password')?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('user_change_password_cancel')?></button>
          </div>
        <?php echo form_close();?>

      </div>
    </div>
  </div>

<?php $this->load->view('layout/footer');?>

<script>

  function isEmail(email) 
  {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
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


  $(document).ready(function(e){

    $('#userSubmit').click(function(e){
      // e.preventDefault();

      var isError = false;

      $('form#editUserForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
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

    $("#current_password").on("blur change",  function (event){
        
        var current_password  = $('#current_password').val();


        if(current_password.trim() != ""){

          $.ajax({
              url: "<?=base_url('auth/old_password_verify')?>",
              type: "POST",
              data: {
                      'current_password' : current_password,
                      '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
              dataType: "JSON",
              success: function (response) {

                
                if(response['code'] == 0)
                {
                  if($('#current_password').hasClass('is-valid'))
                  {
                    $('#current_password').removeClass('is-valid');
                  }
                  $('#current_password').addClass('is-invalid');
                  $('#err_current_password').text('You have entered invalid password');
                }
                else
                {
                  if($('#current_password').hasClass('is-invalid'))
                  {
                    $('#current_password').removeClass('is-invalid');
                  }
                  $('#current_password').addClass('is-valid');
                  $('#err_current_password').text(''); 
                }
              },
              error: function () {
                  alert("There is technical issue while submitting your query, Kindly call on number mentioned in contact page.");
              }
          });
        }

    });

    $("#new_password").on("blur keyup change",  function (event){

        var confirm_new_password  = $('#confirm_new_password').val();
        var new_password          = $('#new_password').val();


        if(new_password.trim() != "")
        {

          if(validPasswordLength(new_password))
          {
            if($('#new_password').hasClass('is-invalid'))
            {
              $('#new_password').removeClass('is-invalid') 
            }
            
            $('#err_new_password').text('');
            $('#new_password').addClass('is-valid');


          }
          else
          {
            if($('#new_password').hasClass('is-valid'))
            {
              $('#new_password').removeClass('is-valid') 
            }

            $('#err_new_password').text('Password length must be between 8 to 256');
            $('#new_password').addClass('is-invalid');
          }  
        }

        if(confirm_new_password.trim() != '')
        {
          if(new_password == confirm_new_password)
          {
            if($('#new_password').hasClass('is-invalid'))
            {
              $('#new_password').removeClass('is-invalid') 
            }
            if($('#confirm_new_password').hasClass('is-invalid'))
            {
              $('#confirm_new_password').removeClass('is-invalid') 
            }
            
            $('#err_new_password').text('');
            $('#new_password').addClass('is-valid');
            $('#confirm_new_password').addClass('is-valid');            
          }
          else
          {
            if($('#new_password').hasClass('is-valid'))
            {
              $('#new_password').removeClass('is-valid') 
            }
            if($('#confirm_new_password').hasClass('is-valid'))
            {
              $('#confirm_new_password').removeClass('is-valid') 
            }

            $('#err_new_password').text('Password and Confirm password must match.');
            $('#new_password').addClass('is-invalid');
            $('#confirm_new_password').addClass('is-invalid');
          }
        }
        
    });

    $("#confirm_new_password").on("blur keyup change",  function (event){

        var confirm_new_password  = $('#confirm_new_password').val();
        var new_password          = $('#new_password').val();

        if(new_password.trim() != '')
        {
          if(new_password == confirm_new_password)
          {
            if($('#confirm_new_password').hasClass('is-invalid'))
            {
              $('#confirm_new_password').removeClass('is-invalid') 
            }

            if($('#new_password').hasClass('is-invalid'))
            {
              $('#new_password').removeClass('is-invalid') 
            }
            
            $('#err_confirm_new_password').text('');
            $('#confirm_new_password').addClass('is-valid');
            $('#new_password').addClass('is-valid');
          }
          else
          {
            if($('#confirm_new_password').hasClass('is-valid'))
            {
              $('#confirm_new_password').removeClass('is-valid') 
            }

            if($('#new_password').hasClass('is-valid'))
            {
              $('#new_password').removeClass('is-valid') 
            }

            $('#err_confirm_new_password').text('Password and Confirm password must match.');
            $('#confirm_new_password').addClass('is-invalid');
            $('#new_password').addClass('is-invalid');
          }
        }

    });

    $('#change_password_modal').on('hidden.bs.modal', function () {
        $('#current_password').val('');
        $('#new_password').val('');
        $('#confirm_new_password').val('');
    });

    $('#change_password_form').submit(function (event) {
      event.preventDefault();
      
      var formData = $('#change_password_form').serialize();

      var current_password      = $('#current_password').val().trim();
      var new_password          = $('#new_password').val().trim();
      var confirm_new_password  = $('#confirm_new_password').val().trim();
      
      if((new_password != confirm_new_password) || (new_password == '' && confirm_new_password == ''))
      {  
        if((new_password != confirm_new_password))
        {
          if($('#confirm_new_password').hasClass('is-invalid'))
          {
            $('#confirm_new_password').removeClass('is-invalid') 
          }

          if($('#new_password').hasClass('is-invalid'))
          {
            $('#new_password').removeClass('is-invalid') 
          }
          
          $('#err_confirm_new_password').text('Password and Confirm password must match.');
          $('#err_new_password').text('Password and Confirm password must match.');
          $('#confirm_new_password').addClass('is-valid');
          $('#new_password').addClass('is-valid');
          return false;  
        }

        if((new_password == '' && confirm_new_password == ''))
        {
          if($('#confirm_new_password').hasClass('is-valid'))
          {
            $('#confirm_new_password').removeClass('is-valid') 
          }

          if($('#new_password').hasClass('is-invalid'))
          {
            $('#new_password').removeClass('is-invalid') 
          }
          
          $('#err_confirm_new_password').text('Password and Confirm password must not be blank.');
          $('#err_new_password').text('Password and Confirm password must not be blank.');
          $('#confirm_new_password').addClass('is-invalid');
          $('#new_password').addClass('is-invalid');
          return false;  
        }
        
      } 
      else
      {
        if($('#confirm_new_password').hasClass('is-invalid'))
        {
          $('#confirm_new_password').removeClass('is-invalid') 
        }

        if($('#new_password').hasClass('is-invalid'))
        {
          $('#new_password').removeClass('is-invalid') 
        }
        
        $('#err_confirm_new_password').text('');
        $('#confirm_new_password').addClass('is-valid');
        $('#new_password').addClass('is-valid');
      }

      if(current_password == "")
      {
        if($('#current_password').hasClass('is-valid'))
        {
          $('#current_password').removeClass('is-valid');
        }
        $('#current_password').addClass('is-invalid');
        $('#err_current_password').text('Current password can not be blank.');
        return false;
      }
      else
      {
        

        $.ajax({
            url: "<?=base_url('auth/old_password_verify')?>",
            type: "POST",
            data: {
                    'current_password' : current_password,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
            dataType: "JSON",
            success: function (response) {

              
              if(response['code'] == 0)
              {
                if($('#current_password').hasClass('is-valid'))
                {
                  $('#current_password').removeClass('is-valid');
                }
                $('#current_password').addClass('is-invalid');
                $('#err_current_password').text('You have entered invalid password');
                return false;
              }
              else
              {
                if($('#current_password').hasClass('is-invalid'))
                {
                  $('#current_password').removeClass('is-invalid');
                }
                $('#current_password').addClass('is-valid');
                $('#err_current_password').text(''); 
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

      $('#changePasswordSubmit').text('<?=$this->lang->line("please_wait")?>');

              
      $.ajax({
          url: "<?php echo base_url('auth/change_password')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function (response) {

            
            if(response['code'] == 1)
            {
              $('#change_password_modal').modal('toggle');

              ChangePasswordToast.fire({
                type: 'success',
                title: response['message']
              });

              $('#changePasswordSubmit').text('<?=$this->lang->line("user_change_password")?>');
              // change_message('success-header',response['message']);
              // $('#message').modal('show');
            }
            else
            {
              ChangePasswordToast.fire({
                type: 'error',
                title: response['message']
              });
              $('#changePasswordSubmit').text('<?=$this->lang->line("user_change_password")?>');
              // $('#message').val('');
              // $('.mentiony-content').html('');
              // $('#err_new_password').addClass("text-danger");
              // $('#err_new_password').html(response['message']);
              // $('#err_new_password').toggle();


            }

            // if(response['code']==1)
            // { 
            //   $('h4.message').text(project_manager+" added successfully.");
            //   $('#message').modal('show');
            //   $('#').modal('hide');
            // }

            

          },
          error: function () {
              ChangePasswordToast.fire({
                type: 'error',
                title: "There is technical issue while submitting your query, Kindly call on number mentioned in contact page."
              });
              $('#changePasswordSubmit').text('<?=$this->lang->line("user_change_password")?>');
          }
      });
        

      setTimeout(function() {
          $('.err_change_password').fadeOut('slow');
          
          if($(".err_change_password").hasClass("text-success"))
            $(".err_change_password").removeClass("text-success");

          if($(".err_change_password").hasClass("text-danger"))
            $(".err_change_password").removeClass("text-danger");

          $('.err_change_password').html('');

      }, 5000); 
    });
  });

</script>


<script type="text/javascript">
  
  $(function() {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

    <?php if($this->session->flashdata('success')) { ?>
        Toast.fire({
          type: 'success',
          title: '<?=$this->session->flashdata('success')?>'
        });
    <?php } ?>

    <?php if($this->session->flashdata('failure')) { ?>
        Toast.fire({
          type: 'danger',
          title: '<?=$this->session->flashdata('failure')?>'
        });
    <?php } ?>
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
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('cs_cancel')?></button>
      </div>

    </div>
  </div>
</div>
