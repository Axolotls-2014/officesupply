<?php $this->load->view('layout/header');?>


<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_people')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('lead')?>"><?=$this->lang->line('header_lead')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('lead_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('lead_list')?></h3>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('lead/add')?>"><i class="fas fa-user-friends mr-2"></i><?=$this->lang->line('lead_add')?></a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('Sr No.')?></th>
                    <th><?=$this->lang->line('date')?></th>
                    <th><?=$this->lang->line('customer_name')?></th>
                    <th><?=$this->lang->line('lead_address')?></th>
                    <th><?=$this->lang->line('lead_phone')?></th>
                    <th><?=$this->lang->line('lead_source')?></th>
                    <th><?=$this->lang->line('lead_status')?></th>
                    <th><?=$this->lang->line('reffered_by')?></th>
                    <th><?=$this->lang->line('requirement')?></th>
                    <th>Remark</th>

                    <th><?=$this->lang->line('lead_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php $i=1;
                    foreach ($customer as $value) 
                    {
                  ?>
                  <tr>
                    <td><?php echo $i++;?></td>
                    <td><?php echo $value->date_added;?></td>
                    <td><?php echo $value->lead_name;?></td>
                    <td><?php echo $value->address;?></td>
                    <td><?php echo $value->phone;?></td>
                    <td><?php echo $value->source;?></td>
                    <td style="color: <?php echo ($value->status == 'Converted') ? 'red' : 'black'; ?>;">
                        <?php echo $value->status; ?>
                    </td>
                    <td><?php echo $value->reffered_by;?></td>
  
                    <td><?php echo $value->requirement;?></td>
                    <td><?php echo $value->remark;?></td>

                    <td>
                      <table>
                        <tr>
                          <?php 
                            if($this->permission_model->has_permission('view_customer'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="<?php echo base_url('lead/view/'.base64_encode($value->id));?>" class="btn btn-default btn-xs">
                              <i class="fas fa-eye"></i>
                            </a>
                          </td>
                          <?php 
                            }
                          ?>

                          <?php 
                            if($this->permission_model->has_permission('edit_customer'))
                            {
                            if($value->status != "Converted") {
                          ?>
                          <td class="p-2">
                            <a href="<?php echo base_url('lead/edit/'.base64_encode($value->id));?>" class="btn btn-info btn-xs" data-tt="tooltip" title="">
                              <i class="fas fa-edit"></i>
                            </a>      
                          </td>
                          <?php 
                           } }
                          ?>

            
                        </tr>
                      </table>
                      
                      
                      
                    

                    </td>
                  </tr>
                  <?php  
                    }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                                          <th><?=$this->lang->line('Sr No.')?></th>
                    <th><?=$this->lang->line('date')?></th>
                    <th><?=$this->lang->line('customer_name')?></th>
                    <th><?=$this->lang->line('lead_address')?></th>
                    <th><?=$this->lang->line('lead_phone')?></th>
                    <th><?=$this->lang->line('lead_source')?></th>
                    <th><?=$this->lang->line('lead_status')?></th>
                    <th><?=$this->lang->line('reffered_by')?></th>
                    <th><?=$this->lang->line('requirement')?></th>
                    <th>Remark</th>

                    <th><?=$this->lang->line('lead_action')?></th>
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
<script>
    $(document).ready(function() {
  $('#example1).DataTable({
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'excelHtml5',
        title: 'Lead_List'
      },
      {
        extend: 'pdfHtml5',
        title: 'Lead_List',
        orientation: 'landscape',
        pageSize: 'A4'
      },
      {
        extend: 'print',
        title: 'Lead List'
      }
    ]
  });
});

</script>
<?php $this->load->view('layout/footer');?>
