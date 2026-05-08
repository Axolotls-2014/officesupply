<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_people')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('lead/feedback_list')?>"><?=$this->lang->line('header_feedback')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('feedback_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('feedback_list')?></h3>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('lead/addfeedback')?>"><i class="fas fa-user-friends mr-2"></i><?=$this->lang->line('add_feedback')?></a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="card-body">
              <table id="example3" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('Sr No.')?></th>
                    <th><?=$this->lang->line('name')?></th>
                    <th><?=$this->lang->line('address')?></th>
                    <th><?=$this->lang->line('phone_no')?></th>
                    <th><?=$this->lang->line('visit_date')?></th>
                    <th><?=$this->lang->line('reference')?></th>
                    <th><?=$this->lang->line('request')?></th>
                    <th><?=$this->lang->line('note')?></th>
                    <th><?=$this->lang->line('salesman')?></th>
                    <th><?=$this->lang->line('receptionist')?></th>

                    <th><?=$this->lang->line('lead_action')?></th>
                  </tr>
                  
                  <tr>
    <!-- Add the search inputs below each column header -->
    <th></th>
    <th><input type="text" placeholder="Search Name" /></th>
    <th><input type="text" placeholder="Search Address" /></th>
    <th><input type="text" placeholder="Search Phone No" /></th>
    <th><input type="text" placeholder="Search Visit Date" /></th>
    <th><input type="text" placeholder="Search Reference" /></th>
    <th><input type="text" placeholder="Search Request" /></th>
    <th><input type="text" placeholder="Search Note" /></th>
    <th><input type="text" placeholder="Search Salesman" /></th>
    <th><input type="text" placeholder="Search Receptionist" /></th>
    <th><input type="text" placeholder="Search Actions" /></th>
  </tr>
  
                </thead>
                
                <tbody>
                  <?php $i=1;
                    foreach ($feedback as $value) 
                    {
                  ?>
                  <tr>   
                    <td><?php echo $i++;?></td>
                    <td><?php echo $value->name;?></td>
                    <td><?php echo $value->address;?></td>
                    <td><?php echo $value->phone_no;?></td>
                    <td><?php echo $value->visit_date;?></td>
                    <td><?php echo $value->reference;?></td>
                    <td><?php echo $value->request; ?></td>
                    <td><?php echo $value->note;?></td>
                    
                    
                    <td>
    <?php 
    // Check if salesman_name is not empty
    if (!empty($value->salesman)) {
        // Split the salesman IDs into an array
        $salesman_ids = explode(',', $value->salesman);  

        // Loop through each salesman ID and retrieve the corresponding name
        foreach ($salesman_ids as $salesman_id) {
            // Query the database to get the first name of the salesman
            $this->db->select('first_name');
            $this->db->from('hr_employees');
            $this->db->where('employee_id', $salesman_id);
            $query = $this->db->get();
            
            // If a salesman is found, display their name
            if ($query->num_rows() > 0) {
                echo $query->row()->first_name . "<br>";
            }
        }
    } else {
        echo "No salesman assigned";
    }
    ?>
</td>

                    
                    
                    <td><?php echo $value->receptionist_name;?></td>

                    <td>
                      <table>
                        <tr>
                        

                          <?php 
                            if($this->permission_model->has_permission('edit_customer')){ ?>
               
                          <td class="p-2">
                            <a href="<?php echo base_url('lead/editfeedback/'.base64_encode($value->id));?>" class="btn btn-info btn-xs" data-tt="tooltip" title="">
                              <i class="fas fa-edit"></i>
                            </a>      
                          </td>
                     
<?php } ?>
            
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
                     <th><?=$this->lang->line('name')?></th>
                    <th><?=$this->lang->line('address')?></th>
                    <th><?=$this->lang->line('phone_no')?></th>
                    <th><?=$this->lang->line('visit_date')?></th>
                    <th><?=$this->lang->line('reference')?></th>
                    <th><?=$this->lang->line('request')?></th>
                    <th><?=$this->lang->line('note')?></th>
                    <th><?=$this->lang->line('salesman')?></th>
                   <th><?=$this->lang->line('receptionist')?></th>
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

<?php $this->load->view('layout/footer');?>
<script>
  $(document).ready(function() {
    var table = $('#example3').DataTable({
      "processing": true, 
      "serverSide": false, 
      "paging": true, 
      "searching": true, 
      "ordering": true, 
      "autoWidth": false, 
      "columnDefs": [
        { "targets": -1, "orderable": false },
      ]
    });

    // Apply the search for each column
    table.columns().every(function() {
      var that = this;
      $('input', this.header()).on('keyup change', function() {
        if (that.search() !== this.value) {
          that.search(this.value).draw();
        }
      });
    });
  });
</script>

