<?php $this->load->view('layout/header');?>

<style type="text/css">
  .custom-control-label{
    font-weight: normal !important;
  }
  .role_module h6{
    font-weight: bolder !important;
  }
</style>
<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?=base_url('auth/dashboard')?>">Home</a></li>
            <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_people')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('userrole_list')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('userrole_list')?></h3>

              <?php 
                if($this->permission_model->has_permission('add_user_role'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('auth/add_user_role')?>" data-tt="tooltip" title="Click here to Add User Role"><i class="fas fa-user-cog mr-2"></i><?=$this->lang->line('userrole_add')?></a>
                  </li>
                </ul>
              </div>
              <?php 
                }
              ?>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('userrole_name')?></th>
                    <th><?=$this->lang->line('userrole_description')?></th>
                    <th><?=$this->lang->line('userrole_action')?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    foreach ($groups as $value) 
                    {
                  ?>
                    <tr>
                      <td><?php echo $value->name;?></td>
                      <td><?php echo $value->description;?></td>
                      <td>
                        <a href="<?php echo base_url('auth/add_user_role/'.$value->id);?>" class="btn btn-info btn-xs">
                          <i class="fas fa-edit"></i>
                        </a>

                        <?php 
                          if($this->permission_model->has_permission('assign_permission_to_user_role'))
                          {
                        ?>
                        <button type="button" class="btn btn-xs btn-outline-secondary" data-toggle="modal" data-target="#permission-modal-<?php echo $value->id;?>" data-group_id="<?=$value->id?>" data-tt="tooltip" title="Set permission">
                          <i class="fas fa-key"></i> <?php echo $this->lang->line('userrole_permission_set');?>
                        </button>

                        <div class="permission-modal">
                          <div class="modal fade load_permission_modal" id="permission-modal-<?php echo $value->id;?>">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <form name="permissionForm_<?=$value->id?>" id="permissionForm_<?=$value->id?>">
                                  <div class="modal-header info-header">
                                    <h4 class="modal-title">
                                      <?php echo $this->lang->line('userrole_permission');?>
                                    </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body" style="height: 700px; overflow-y: auto;">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input select_all" id="<?=$value->id?>" type="checkbox">
                                            <label for="<?=$value->id?>" class="custom-control-label">
                                              <h6 class="mt-1">Select All</h6>
                                            </label>
                                          </div>
                                        </div>
                                      </div>
                                      <br/>
                                    <?php 
                                      $i = 0;
                                      foreach ($modules as $row) 
                                      {
                                        $permissions = $this->permission_model->get_permission_records_by_module($row->module);
                                    ?>  
                                      <div class="row border">
                                        <div class="col-md-12 role_module border-bottom  bg-light">
                                            <div class="custom-control custom-checkbox">
                                              <input class="custom-control-input select_h" type="checkbox" id="header_<?=$row->module?>_<?=$i?>_<?=$value->id?>" data-id="<?=$i?>" data-group_id="<?=$value->id?>" data-module="<?=$row->module?>">
                                              <label for="header_<?=$row->module?>_<?=$i?>_<?=$value->id?>" class="custom-control-label">
                                                <h6 class="pt-1"><b><?=ucwords(str_replace("_"," ",$row->module))?></b></h6>
                                              </label>
                                            </div>
                                        </div>
                                        <?php 
                                          foreach ($permissions as $value_p) 
                                          {
                                        ?>
                                        <div class="col-md-4">
                                          <div class="custom-control custom-checkbox" style="margin-left: 5px;">
                                            <input 
                                              class="custom-control-input select_p <?=$value_p->module?>_<?=$i?> <?=$value_p->module?>_<?=$i?>_<?=$value->id?>"
                                              id="<?=$value_p->id?>_<?=$value->id?>"
                                              type="checkbox"
                                              value="<?=$value_p->id?>"
                                              name="permission"
                                              data-module="<?=$value_p->module?>"
                                              data-module_id="<?=$i?>"
                                              data-group_id="<?=$value->id?>"
                                            >
                                            <label for="<?=$value_p->id?>_<?=$value->id?>" class="custom-control-label">
                                              <?=$value_p->display_name?>
                                            </label>
                                          </div>
                                        </div>
                                        <?php 
                                          }
                                        ?>
                                      </div>  
                                      <br/>
                                    <?php 
                                        $i++;
                                      }
                                    ?>
                                  </div>
                                  <div class="modal-footer">
                                    <input type="hidden" name="role_id" id="role_id" value="<?=$value->id?>">
                                    <button type="submit" class="btn btn-info save_permission" data-group_id='<?=$value->id?>'>
                                      <?php echo $this->lang->line('userrole_permission_save');?>
                                    </button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                      <?php echo $this->lang->line('btn_modal_close');?>
                                    </button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php 
                          }
                        ?>
                      </td>
                    </tr>
                  <?php  
                    }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('userrole_name')?></th>
                    <th><?=$this->lang->line('userrole_description')?></th>
                    <th><?=$this->lang->line('userrole_action')?></th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">

  $(document).ready(function(e){

    const Toast1 = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 7000
    });

    $('.select_all').change(function(e){

      var form = $(this).closest('form');

      if($(this).prop("checked") == true)
      {
        form.find('.select_h').prop('checked', true);
        form.find('.select_p').prop('checked', true);
      }
      else
      {
        form.find('.select_h').prop('checked', false);
        form.find('.select_p').prop('checked', false);
      }
    });

    $('.select_h').change(function(e){

      var form = $(this).closest('form');

      var permission_id = $(this).data('id');
      var module_name   = $(this).data('module');
      var group_id      = $(this).data('group_id');

      if($(this).prop("checked") == true)
      {
        form.find('.'+module_name+'_'+permission_id+'_'+group_id).prop('checked', true);
      }
      else
      {
        form.find('.'+module_name+'_'+permission_id+'_'+group_id).prop('checked', false);
      }
    });

    $('.select_p').change(function(e){

      var form = $(this).closest('form');

      var permission_id = $(this).val();
      var module_name   = $(this).data('module');
      var module_id     = $(this).data('module_id');
      var group_id      = $(this).data('group_id');

      var checkedPermission     = form.find('.'+module_name+'_'+module_id+':checked').length;
      var nonCheckedPermission  = form.find('.'+module_name+'_'+module_id).length;

      if(checkedPermission == nonCheckedPermission)
      {
        form.find('#header_'+module_name+'_'+module_id+'_'+group_id).prop('checked',true);  
      }
      else if(checkedPermission != nonCheckedPermission)
      {
        form.find('#header_'+module_name+'_'+module_id+'_'+group_id).prop('checked',false);   
      }
    });

    $('.save_permission').click(function(e){
      e.preventDefault();

      var form  = $(this).closest('form');
      var id    = $(this).data('group_id');

      form.find('.save_permission').html('Saving Permission...');
      form.find('.save_permission').attr('disabled',true);

      var permission = [];

      $.each(form.find("input[name='permission']:checked"), function(){
        permission.push($(this).val());
      });

      $.ajax({
          url: "<?php echo base_url('auth/save_permission') ?>/",
          type: "POST",
          dataType: "JSON",
          data: {
            'group_id'   : id,
            'permission' : permission.join(','),
            '<?=$this->security->get_csrf_token_name();?>' : '<?=$this->security->get_csrf_hash();?>'
          },
          success: function(response){
              
              if(response.code == 1)
              {
                Toast1.fire({
                  type: 'success',
                  title: response.message
                });
              }
              else if(response.code == 2)
              { 
                Toast1.fire({
                  type: 'warning',
                  title: response.message
                });
              }

              form.find('.save_permission').html('Save Permission');
              form.find('.save_permission').removeAttr('disabled');
          }
      });
    });

    $('.load_permission_modal').on('show.bs.modal', function (e) {

      var group_id = $(e.relatedTarget).data('group_id');

      $.ajax({
        url: '<?php echo base_url('auth/get_permission');?>',
        type: 'POST',
        dataType: 'JSON',
        data: {
          'group_id' : group_id,
          '<?=$this->security->get_csrf_token_name();?>' : '<?=$this->security->get_csrf_hash();?>'
        },
        success: function(response){
          
          var permission_array  = response.permission.split(',');
          var group_id          = response.group_id;

          for (var i = permission_array.length - 1; i >= 0; i--) {
            $('#'+permission_array[i]+'_'+group_id).prop('checked',true);
          }

          $('.select_p').trigger('change');
        }
      });
    });  
  });
</script>


