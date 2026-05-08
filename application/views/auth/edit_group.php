<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_people')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('userrole_list')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="addRoleForm" id="addRoleForm" method="POST" action="<?=base_url('auth/user_roles')?>">
              <div class="card card-info"> 
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('userrole_edit')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('userrole_name')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="name" value="<?=set_value('name',$group->name) ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('userrole_name')?>">
                      <span id="err_name" class="error invalid-feedback"><?=form_error('name');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('userrole_description')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="description" value="<?=set_value('description',$group->description) ?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line('userrole_description')?>">
                      <span id="err_description" class="error invalid-feedback"><?=form_error('description');?></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="id" value="<?=$group->id?>">
                  <button type="submit" name="submit" id="userRoleSubmit" class="btn btn-info"><?=$this->lang->line('userrole_save')?></button>
                  <a href="<?=base_url('auth/user_roles')?>" class="btn btn-default float-right"><?=$this->lang->line('userrole_cancel')?></a>
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

    $('form#addRoleForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#userRoleSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#addRoleForm .field_validation').each(function() {
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#addRoleForm #err_"+id).text(field+ " field is required.");
          $('form#addRoleForm #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addRoleForm #err_"+id).text("");
          $('form#addRoleForm #'+id).removeClass('is-invalid');
          $('form#addRoleForm #'+id).addClass('is-valid');
        }
      });

      if(isError == true)
      {
        $('#userRoleSubmit').text('<?=$this->lang->line("userrole_save")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    
    });

    $("form#addRoleForm .field_validation").on("blur keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addRoleForm #err_"+id).text(field+ " field is required.");
          $('form#addRoleForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addRoleForm #err_"+id).text("");
          $('form#addRoleForm #'+id).removeClass('is-invalid');
          $('form#addRoleForm #'+id).addClass('is-valid');
        }
    });

  });
</script>
