<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_people')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('auth/users')?>"><?=$this->lang->line('header_users')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('user_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('user_list')?></h3>
              
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                 
                  <li class="nav-item ml-2">
                    <a class="nav-link active" href="<?=base_url('purchase_request/add_sub_user')?>"><i class="fas fa-user mr-2"></i>Add Branch Manager</a>
                  </li>
                  
                </ul>
              </div>
              
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('user_first_name')?></th>
                    <th><?=$this->lang->line('user_last_name')?></th>
                    <th><?=$this->lang->line('user_username')?></th>
                    <th><?=$this->lang->line('user_email')?></th>
                    <th><?=$this->lang->line('user_phone')?></th>
                    <th><?=$this->lang->line('user_status')?></th>
                    <th><?=$this->lang->line('user_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    foreach ($sub_users as $user) 
                    {
                  ?>
                  <tr>                        
                    <td><?php echo $user->first_name;?></td>
                    <td><?php echo $user->last_name;?></td>
                    <td><?php echo $user->username;?></td>
                    <td><?php echo $user->email;?></td>
                    <td><?php echo $user->phone;?></td>
                    <td>
                      <?php
                        if(($user->active == 1)){
                      ?>
                          <a href="<?=base_url('purchase_request/deactivate/'.$user->id)?>" class="change_status" data-tt="tooltip" title="Click here to Deactivate User"><span class="badge badge-success">Active</span></a>
                      <?php
                        }
                        else
                        {
                      ?>
                          <a href="<?=base_url('purchase_request/activate/'.$user->id)?>" class="change_status" data-tt="tooltip" title="Click here to Activate User"><span class="badge badge-danger">Inactive</span></a>
                      <?php
                        }
                      ?>
                    
                    </td>
                    <td>
                      <a href="<?php echo base_url('auth/edit_user/'.$user->id);?>" class="btn btn-info btn-xs">
                        <i class="fas fa-edit"></i>
                      </a>
                    </td>
                    
                  </tr>
                  <?php  
                    }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('user_first_name')?></th>
                    <th><?=$this->lang->line('user_last_name')?></th>
                    <th><?=$this->lang->line('user_username')?></th>
                    <th><?=$this->lang->line('user_email')?></th>
                    <th><?=$this->lang->line('user_phone')?></th>
                    <th><?=$this->lang->line('user_status')?></th>
                    <th><?=$this->lang->line('user_action')?></th>
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
  
  $(function() {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timerProgressBar: true,
      timer: 3000
    });

    // <?php if($this->session->flashdata('success')) { ?>
    //     Toast.fire({
    //       type: 'success',
    //       title: '<?=$this->session->flashdata('success')?>'
    //     });
    // <?php } ?>

    // <?php if($this->session->flashdata('message')) { ?>
    //     Toast.fire({
    //       icon: 'error',
    //       title: '<?=$this->session->flashdata('message')?>'
    //     });
    // <?php } ?>

  });

  $(document).on('click','.change_status',function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var message = ''; 

        if($(this).find('span').hasClass('badge-success'))
          message = 'Are you sure want to deactivate the user.';
        else
          message = 'Are you sure want to activate the user.';

        Swal.fire({
          title: "Are you sure?",
          text: message,
          icon: "warning",
          type: "warning",
          buttonsStyling: !1,
          customClass: {
              confirmButton: "btn btn-danger",
              cancelButton: "btn btn-default"
          },
          confirmButtonText: "Yes, do it!",
          closeOnConfirm: false,
          showCancelButton: true
        }).then((result) => {  
            /* Read more about isConfirmed, isDenied below */  
            if (result.value) 
            {    
              $.ajax({
                url: href,
                type: "GET",
                dataType: "JSON",
                success: function(data){
                  if(data.code == 1)
                  {
                    Swal.fire({
                      title: 'SUCCESS !!',
                      text: data.message,
                      icon: "success",
                      buttonsStyling: !1,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                          confirmButton: "btn btn-primary"
                      }
                    });
                    location.reload();
                  }
                  else
                  {
                    Swal.fire({
                      title: 'FAILURE !!',
                      text: data.message,
                      icon: "error",
                      buttonsStyling: !1,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                          confirmButton: "btn btn-primary"
                      }
                    });
                  }
                }
              });
            } 
        });
      });

</script>
