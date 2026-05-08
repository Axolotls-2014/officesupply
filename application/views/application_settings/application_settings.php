<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('application_settings_header')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="applicationSettingsForm" id="applicationSettingsForm" method="POST" enctype="multipart/form-data">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('application_settings_list')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('application_settings_app_name')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="app_name" value="<?=set_value('app_name',$application_settings->app_name) ?>" class="form-control form-control-sm" id="app_name" placeholder="App name"><!-- <?=form_error('app_name', '<div class="text-danger">', '</div>');?> -->
                      <span id="err_app_name" class="error invalid-feedback"><?=form_error('app_name');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('application_settings_app_version')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="app_version" value="<?=set_value('app_version',$application_settings->app_version) ?>" class="form-control form-control-sm" id="app_version" placeholder="app version">
                      <span id="err_app_version" class="error invalid-feedback"><?=form_error('app_version');?></span>
                    </div>
                  </div>
                  <!-- <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('application_settings_app_language')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="app_language" value="<?=set_value('app_language',$application_settings->app_language) ?>" class="form-control form-control-sm" id="app_language" placeholder="app language"><?=form_error('app_language', '<div class="text-danger">', '</div>');?>
                      <span id="err_app_language" class="error invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('application_settings_app_timezone')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="app_timezone" value="<?=set_value('app_timezone',$application_settings->app_timezone) ?>" class="form-control form-control-sm" id="app_timezone" placeholder="App timezone"><?=form_error('app_timezone', '<div class="text-danger">', '</div>');?>
                      <span id="err_app_timezone" class="error invalid-feedback"></span>
                    </div>
                  </div> -->
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('application_settings_sidebar_menu_text_size')?></label>
                    <div class="col-sm-4">

                      <select class="form-control form-control-sm" name="sidebar_menu_text_size" id="sidebar_menu_text_size" placeholder="Sidebar Menu Text Size">
                        <option value="0" <?php if($application_settings->sidebar_menu_text_size == 0) echo ' selected'; ?>>Small</option>
                        <option value="1" <?php if($application_settings->sidebar_menu_text_size == 1) echo ' selected'; ?>>Big</option>
                      </select>
                      <span id="err_sidebar_menu_text_size" class="error invalid-feedback"><!-- <?=form_error('sidebar_menu_text_size');?> --></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('application_settings_sidebar_menu_flat_style')?></label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm" name="sidebar_menu_flat_style" id="sidebar_menu_flat_style" placeholder="Sidebar Menu Flat Style">
                        <option value="0" <?php if($application_settings->sidebar_menu_flat_style == 0) echo ' selected'; ?>>No</option>
                        <option value="1" <?php if($application_settings->sidebar_menu_flat_style == 1) echo ' selected'; ?>>Yes</option>
                      </select>
                      <span id="err_sidebar_menu_flat_style" class="error invalid-feedback"><!-- <?=form_error('sidebar_menu_flat_style');?> --></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('application_settings_sidebar_nav_legacy_style')?></label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm" name="sidebar_nav_legacy_style" id="sidebar_nav_legacy_style" placeholder="Sidebar Nav Legacy Style">
                        <option value="0" <?php if($application_settings->sidebar_nav_legacy_style == 0) echo ' selected'; ?>><?=$this->lang->line('yes')?></option>
                        <option value="1" <?php if($application_settings->sidebar_nav_legacy_style == 1) echo ' selected'; ?>><?=$this->lang->line('no')?></option>
                      </select>
                      <span id="err_sidebar_nav_legacy_style" class="error invalid-feedback"><!-- <?=form_error('sidebar_nav_legacy_style');?> --></span>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('application_settings_application_text_size')?></label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm" name="application_text_size" id="application_text_size" placeholder="Application Text Size">
                        <option value="0" <?php if($application_settings->application_text_size == 0) echo ' selected'; ?>><?=$this->lang->line('small')?></option>
                        <option value="1" <?php if($application_settings->application_text_size == 1) echo ' selected'; ?>><?=$this->lang->line('big')?></option>
                      </select>
                      <span id="err_application_text_size" class="error invalid-feedback"><!-- <?=form_error('application_text_size');?> --></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('application_settings_sidebar_theme')?></label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4" name="sidebar_theme" id="sidebar_theme" placeholder="Sidebar Theme">
                        <option value="sidebar-dark-primary" <?php if($application_settings->sidebar_theme == 'sidebar-dark-primary') echo ' selected'; ?>>Dark Primary</option>
                        <option value="sidebar-dark-warning" <?php if($application_settings->sidebar_theme == 'sidebar-dark-warning') echo ' selected'; ?>>Dark Warning</option>
                        <option value="sidebar-dark-danger" <?php if($application_settings->sidebar_theme == 'sidebar-dark-danger') echo ' selected'; ?>>Dark Danger</option>
                        <option value="sidebar-dark-success" <?php if($application_settings->sidebar_theme == 'sidebar-dark-success') echo ' selected'; ?>>Dark Success</option>
                        <option value="sidebar-dark-indigo" <?php if($application_settings->sidebar_theme == 'sidebar-dark-indigo') echo ' selected'; ?>>Dark Indigo</option>
                        <option  value="navbar-dark navbar-indigo" <?php if($application_settings->sidebar_theme == 'sidebar-dark-lightblue') echo ' selected'; ?>>Dark Lightblue</option>
                        <option  value="sidebar-dark-navy" <?php if($application_settings->sidebar_theme == 'sidebar-dark-navy') echo ' selected'; ?>>Dark Navy</option>
                        <option  value="sidebar-dark-purple" <?php if($application_settings->sidebar_theme == 'sidebar-dark-purple') echo ' selected'; ?>>Dark Purple</option>
                        <option  value="sidebar-dark-fuchsia" <?php if($application_settings->sidebar_theme == 'sidebar-dark-fuchsia') echo ' selected'; ?>>Dark Fuchsia</option>
                        <option  value="sidebar-dark-maroon" <?php if($application_settings->sidebar_theme == 'sidebar-dark-maroon') echo ' selected'; ?>>Dark Maroon</option>
                        <option  value="sidebar-dark-orange" <?php if($application_settings->sidebar_theme == 'sidebar-dark-orange') echo ' selected'; ?>>Dark Orange</option>
                        <option  value="sidebar-dark-lime" <?php if($application_settings->sidebar_theme == 'sidebar-dark-lime') echo ' selected'; ?>>Dark Lime</option>
                        <option  value="sidebar-dark-teal" <?php if($application_settings->sidebar_theme == 'sidebar-dark-teal') echo ' selected'; ?>>Dark Teal</option>
                        <option  value="sidebar-dark-olive" <?php if($application_settings->sidebar_theme == 'sidebar-dark-olive') echo ' selected'; ?>>Dark Olive</option>
                        <option  value="sidebar-dark-primary" <?php if($application_settings->sidebar_theme == 'sidebar-light-primary') echo ' selected'; ?>>Light Primary</option>
                        <option  value="sidebar-dark-warning" <?php if($application_settings->sidebar_theme == 'sidebar-light-warning') echo ' selected'; ?>>Light Warning</option>
                        <option  value="sidebar-dark-danger" <?php if($application_settings->sidebar_theme == 'sidebar-light-danger') echo ' selected'; ?>>Light Danger</option>
                        <option  value="sidebar-dark-success" <?php if($application_settings->sidebar_theme == 'sidebar-light-success') echo ' selected'; ?>>Light Success</option>
                        <option  value="sidebar-light-indigo" <?php if($application_settings->sidebar_theme == 'sidebar-light-indigo') echo ' selected'; ?>>Light Ingido</option>
                        <option value="sidebar-light-lightblue" <?php if($application_settings->sidebar_theme == 'sidebar-light-lightblue') echo ' selected'; ?>>Light Lightblue</option>
                        <option value="sidebar-light-navy" <?php if($application_settings->sidebar_theme == 'sidebar-light-navy') echo ' selected'; ?>>Light Navy</option>
                        <option value="sidebar-light-purple" <?php if($application_settings->sidebar_theme == 'sidebar-light-purple') echo ' selected'; ?>>Light Purple</option>
                        <option value="sidebar-light-fuchsia" <?php if($application_settings->sidebar_theme == 'sidebar-light-fuchsia') echo ' selected'; ?>>Light Fuchsia</option>
                        <option value="sidebar-light-maroon" <?php if($application_settings->sidebar_theme == 'sidebar-light-maroon') echo ' selected'; ?>>Light Maroon</option>
                        <option value="sidebar-light-orange" <?php if($application_settings->sidebar_theme == 'sidebar-light-orange') echo ' selected'; ?>>Light Orange</option>
                        <option value="sidebar-light-lime" <?php if($application_settings->sidebar_theme == 'sidebar-light-lime') echo ' selected'; ?>>Light Lime</option>
                        <option value="sidebar-light-teal" <?php if($application_settings->sidebar_theme == 'sidebar-light-teal') echo ' selected'; ?>>Light Teal</option>
                        <option value="sidebar-light-olive" <?php if($application_settings->sidebar_theme == 'sidebar-light-olive') echo ' selected'; ?>>Light Olive</option>
                      </select>
                      <span id="err_sidebar_theme" class="error invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('application_settings_navbar_theme')?></label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4" name=" navbar_theme" id=" navbar_theme" placeholder="Navbar Theme">
                        <option value="navbar-dark navbar-primary" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-primary') echo ' selected'; ?>>Dark Primary</option>
                        <option value="navbar-dark navbar-secondary" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-secondary') echo ' selected'; ?>>Dark Secondary</option>
                        <option value="navbar-dark navbar-info" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-info') echo ' selected'; ?>>Dark Info</option>
                        <option value="navbar-dark navbar-success" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-success') echo ' selected'; ?>>Dark Success</option>
                        <option value="navbar-dark navbar-danger" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-danger"') echo ' selected'; ?>>Dark Danger</option>
                        <option  value="navbar-dark navbar-indigo" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-indigo') echo ' selected'; ?>>Dark indigo</option>
                        <option  value="navbar-dark navbar-purple" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-purple"') echo ' selected'; ?>>Dark Purple</option>
                        <option  value="navbar-dark navbar-pink" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-pink') echo ' selected'; ?>>Dark Pink</option>
                        <option  value="navbar-dark navbar-navy" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-navy') echo ' selected'; ?>>Dark Navy</option>
                        <option  value="navbar-dark navbar-lightblue" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-lightblue') echo ' selected'; ?>>Dark Lightblue</option>
                        <option  value="navbar-dark navbar-teal" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-teal') echo ' selected'; ?>>Dark teal</option>
                        <option  value="navbar-dark navbar-cyan" <?php if($application_settings->navbar_theme == 'navbar-dark navbar-cyan') echo ' selected'; ?>>Dark Cyan</option>
                        <option  value="navbar-expand navbar-dark" <?php if($application_settings->navbar_theme == 'navbar-expand navbar-dark') echo ' selected'; ?>>Expand Dark</option>
                        <option  value="navbar-expand navbar-dark navbar-gray-dark" <?php if($application_settings->navbar_theme == 'navbar-expand navbar-dark navbar-gray-dark') echo ' selected'; ?>>Expand Dark Graydark</option>
                        <option  value="avbar-expand navbar-dark navbar-gray" <?php if($application_settings->navbar_theme == 'avbar-expand navbar-dark navbar-gray') echo ' selected'; ?>>Expand Dark Gray</option>
                        <option  value="navbar-expand navbar-light" <?php if($application_settings->navbar_theme == 'navbar-expand navbar-light') echo ' selected'; ?>>Expand Light</option>
                        <option  value="navbar-expand navbar-light navbar-warning" <?php if($application_settings->navbar_theme == 'navbar-expand navbar-light navbar-warning') echo ' selected'; ?>>Light Warning</option>
                        <option  value="navbar-expand navbar-light navbar-white" <?php if($application_settings->navbar_theme == 'navbar-expand navbar-light navbar-white') echo ' selected'; ?>>Light White</option>
                        <option  value="navbar-expand navbar-light navbar-orange" <?php if($application_settings->navbar_theme == 'navbar-expand navbar-light navbar-orange') echo ' selected'; ?>>Light Orange</option>
                      
                      </select>
                      <span id="err_navbar_theme" class="error invalid-feedback"></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <button type="submit" id="applicationsettingsSubmit" name="applicationsettingsSubmit" class="btn btn-info" data-tt="tooltip" title="Click here to Save"><?=$this->lang->line('application_settings_save')?></button>
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

    $('form#applicationSettingsForm').submit(function(e){
      // e.preventDefault();
      var isError = false;
      $('#applicationsettingsSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#applicationSettingsForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#applicationSettingsForm #err_"+id).text(field+ " field is required.");
            $('form#applicationSettingsForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#applicationSettingsForm #err_"+id).text("");
            $('form#applicationSettingsForm #'+id).removeClass('is-invalid');
            $('form#applicationSettingsForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#applicationsettingsSubmit').text('<?=$this->lang->line("application_settings_save")?>').removeAttr('disabled','disabled');

        return false;
      }  
      else 
      {
        return true;
      }    
    });

    $("form#applicationSettingsForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#applicationSettingsForm #err_"+id).text(field+ " field is required.");
          $('form#applicationSettingsForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#applicationSettingsForm #err_"+id).text("");
          $('form#applicationSettingsForm #'+id).removeClass('is-invalid');
          $('form#applicationSettingsForm #'+id).addClass('is-valid');
        }
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
  });
</script>
