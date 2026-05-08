<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_people')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('lead_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('lead_view')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header secondary-header">
              <h3 class="card-title"><?=$this->lang->line('lead_view')?></h3>
              <div class="card-tools">
  
              </div>
              <!-- <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-tt="tooltip" title="<?=$this->lang->line('hide_details')?>">
                  <i class="fas fa-minus"></i>
                </button>
              </div> -->
            </div>
            <div class="card-body p-0">

               <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 50%;background-color: #DCDCDC;">Personal Details</th>
                      <th style="width: 50%;background-color: #DCDCDC;"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <label><?=$this->lang->line('lead_name')?></label> 
                        : 
                        <?=$customer->lead_name ?>
                      </td>
                      <td>
                        <label><?=$this->lang->line('customer_country_id')?></label> 
                        : 
                        <?=$customer->country_name ?>
                      </td>
              
                    </tr>
                    <tr>
                      <td>
                       <label><?=$this->lang->line('customer_gstin')?></label> 
                        : 
                        <?=$customer->gstin ?>
                      </td>
                      <td>
                        <label><?=$this->lang->line('customer_state_id')?></label> 
                        : 
                        <?=$customer->state_name ?>
                      </td>
                    
                    </tr>
                    <tr>
                      <td>
                        <label><?=$this->lang->line('customer_email')?></label> 
                        : 
                        <?=$customer->email ?>
                      </td>
                      <td>
                        <label><?=$this->lang->line('customer_city_id')?></label> 
                        : 
                        <?=$customer->city_name ?>
                      </td>
                      
                      
                    </tr>
                    <tr>
                      <td>
                        <label><?=$this->lang->line('customer_phone')?></label> 
                        : 
                        <?=$customer->phone ?>
                      </td>
                      <td>
                        <label><?=$this->lang->line('customer_address')?></label> 
                        : 
                        <?=$customer->address ?>
                      </td>
                     
                    </tr>
                    <tr>
    <td>
                        <label><?=$this->lang->line('lead_status')?></label> 
                        : 
                        <?=$customer->status ?>
                      </td>
                      <td>
                        <label><?=$this->lang->line('customer_pincode')?></label> 
                        : 
                        <?=$customer->pincode ?>
                      </td>
                     
                    </tr>
                              <tr>
                      
                      <td>
                        <label><?=$this->lang->line('lead_source')?></label> 
                        : 
                        <?=$customer->source ?>
                      </td>
                     <td>
                        
                      </td>
   
                  
                        
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>

   

      <div class="row">
        <div class="col-12">
          <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="pt-2 px-3"><h3 class="card-title">View</h3></li>
      
       
                    <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-task-tab" data-toggle="pill" href="#custom-tabs-task" role="tab" aria-controls="custom-tabs-task" aria-selected="false" style="display:none;"><?=$this->lang->line('lead_task')?></a>
                </li>
                
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-reminders-tab" data-toggle="pill" href="#custom-tabs-reminders" role="tab" aria-controls="custom-tabs-reminders" aria-selected="false" style="display:none;"><?=$this->lang->line('lead_reminder')?></a>
                </li>
                
                  <li class="nav-item">
                  <a class="nav-link active" id="custom-tabs-followups-tab" data-toggle="pill" href="#custom-tabs-followups" role="tab" aria-controls="custom-tabs-followups" aria-selected="true"><?=$this->lang->line('lead_followups')?></a>
                </li>
              </ul>
            </div>
            
            <div class="card-body  m-0 p-0">
              <div class="tab-content" id="custom-tabs-one-tabContent">
               

<div class="tab-pane fade" id="custom-tabs-task" role="tabpanel" aria-labelledby="custom-tabs-task-tab">
    <div class="card-header">
  <?php if($customer->status != "Converted") { ?>
  <button class="btn btn-primary" data-toggle="modal" data-target="#addTaskModal">Add Task</button>
<?php } ?>
</div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Title</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>Due Date</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Updated Date</th>
                <?php if($customer->status != "Converted") { ?>
                <th>Action</th>
                <?php } ?>

            </tr>
        </thead>
        <tbody>
            <?php
            if (sizeof($tasks) > 0) {
                                $serial = 1;

                foreach ($tasks as $task) {
                    // Display each task's details in the table rows
            ?>
            <tr>
                <td><?=$serial++ ?></td>
                <td><?=$task->title?></td>
                <td><?=$task->description?></td>
                <td><?=date('d-m-Y H:i', strtotime($task->start_date))?></td>
                <td><?=date('d-m-Y H:i', strtotime($task->due_date))?></td>
                <td><?=$task->priority?></td>
                <td><?=$task->status?></td>
                <td><?=$task->created_date ?></td>
                <td><?=$task->updated_date ?></td>
            <?php if($customer->status != "Converted") { ?>
                 <td>
        <!-- Edit Button (trigger the modal) -->
        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editTaskModal" 
            data-id="<?=$task->task_id?>" 
            data-title="<?=$task->title?>" 
            data-description="<?=$task->description?>" 
            data-start_date="<?=$task->start_date?>" 
            data-due_date="<?=$task->due_date?>" 
            data-priority="<?=$task->priority?>" 
            data-status="<?=$task->status?>">
            Edit
        </button>
    </td>
    <?php } ?>

            </tr>
            <?php  
                }
            } else {
            ?>
            <tr>
                <td colspan="8">No tasks available</td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>




<div class="tab-pane fade" id="custom-tabs-reminders" role="tabpanel" aria-labelledby="custom-tabs-reminders-tab">
        <div class="card-header">
             <?php if($customer->status != "Converted") { ?>
  <button class="btn btn-primary" data-toggle="modal" data-target="#addReminderModal">Add Reminder</button>
  <?php } ?>
</div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Reminder Time</th>
                <th>Note</th>
                <th>Created At</th>
                <th>Updated At</th>
                <?php if($customer->status != "Converted") { ?>
                <th>Action</th>
                  <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if (sizeof($reminders) > 0) {
                $serial = 1;
                foreach ($reminders as $reminder) {
            ?>
            <tr>
                <td><?=$serial++ ?></td>
                <td><?=date('d-m-Y H:i', strtotime($reminder->reminder_time))?></td>
                <td><?=$reminder->note?></td>
                <td><?=$reminder->created_at ?></td>
                <td><?=$reminder->updated_at ?></td>
                <?php if($customer->status != "Converted") { ?>
                <td> <!-- Edit Button -->
                    <button class="btn btn-info" data-toggle="modal" data-target="#editReminderModal"
                            data-id="<?=$reminder->reminder_id?>" 
                            data-reminder_time="<?=$reminder->reminder_time?>"
                            data-note="<?=$reminder->note?>">
                        Edit
                    </button></td>
                <?php } ?>

            </tr>
            <?php  
                }
            } else {
            ?>
            <tr>
                <td colspan="5">No reminders available</td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>



<div class="tab-pane fade  show active" id="custom-tabs-followups" role="tabpanel" aria-labelledby="custom-tabs-followups-tab">
    <div class="card-header">
   <?php if($customer->status != "Converted") { ?>
  <button class="btn btn-primary" data-toggle="modal" data-target="#addFollowupModal">Add Followup</button>
  <?php } ?>

</div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Follow-up Date</th>
                <th>Notes</th>
                <th>Status</th>
                <th>Reminder Date</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Updated At</th>
                <?php if($customer->status != "Converted") { ?>
                <th>Action</th>
                <?php } ?>

            </tr>
        </thead>
        <tbody>
            <?php
            if (sizeof($followups) > 0) {
                $serial = 1;
                foreach ($followups as $followup) {
            ?>
            <tr>
                <td><?=$serial++ ?></td>
                <td><?=date('d-m-Y', strtotime($followup->followup_date))?></td>
                <td><?=$followup->followup_notes?></td>
                <td><?=$followup->status?></td>
                <td><?=date('d-m-Y', strtotime($followup->reminder))?></td>
                                <td><?=$followup->first_name ?></td>

                <td><?=$followup->created_at ?></td>
                <td><?=$followup->updated_at ?></td>
                <?php if($customer->status != "Converted") { ?>
                 <td>
                    <button class="btn btn-info" data-toggle="modal" data-target="#editFollowupModal"
                            data-id="<?=$followup->id?>" 
                            data-followup_date="<?=$followup->followup_date?>"
                            data-followup_notes="<?=$followup->followup_notes?>"
                            data-status="<?=$followup->status?>"
                            data-reminder="<?=$followup->reminder?>">
                        Edit
                    </button>
                </td>                
                <?php } ?>

            </tr>
            <?php  
                }
            } else {
            ?>
            <tr>
                <td colspan="9">No follow-ups available</td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>




              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
      
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<div class="example-modal">
  <div class="modal fade" id="create_login_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editTaskForm" action="<?= base_url('Lead/update_task') ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="taskTitle">Title</label>
                        <input type="text" class="form-control" id="taskTitle" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="taskDescription">Description</label>
                        <textarea class="form-control" id="taskDescription" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="taskStartDate">Start Date</label>
                        <input type="datetime-local" class="form-control" id="taskStartDate" name="start_date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="taskDueDate">Due Date</label>
                        <input type="datetime-local" class="form-control" id="taskDueDate" name="due_date" required>
                    </div>
                    <div class="form-group">
                        <label for="taskPriority">Priority</label>
                        <select class="form-control" id="taskPriority" name="priority" required>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="taskStatus">Status</label>
                        <select class="form-control" id="taskStatus" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="In Progress">In Progress</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="task_id" id="taskId" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--  ADD TASK Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('Lead/add_task') ?>" method="POST">
          <!-- CSRF token -->
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="lead_id" value="<?=$customer->id ?>">

          <div class="form-group">
            <label for="taskTitle">Title</label>
            <input type="text" class="form-control" id="taskTitle" name="title" required>
          </div>
          <div class="form-group">
            <label for="taskDescription">Description</label>
            <textarea class="form-control" id="taskDescription" name="description" rows="3" required></textarea>
          </div>
        <div class="form-group">
            <label for="dueDate">Start Date</label>
            <input type="datetime-local" class="form-control" id="startdate" name="start_date" required>
          </div>
          
          <div class="form-group">
            <label for="dueDate">Due Date</label>
            <input type="datetime-local" class="form-control" id="dueDate" name="due_date" required>
          </div>
          <div class="form-group">
            <label for="taskPriority">Priority</label>
            <select class="form-control" id="taskPriority" name="priority" required>
              <option value="High">High</option>
              <option value="Medium">Medium</option>
              <option value="Low">Low</option>
            </select>
          </div>
          <div class="form-group">
            <label for="taskStatus">Status</label>
            <select class="form-control" id="taskStatus" name="status" required>
              <option value="Pending">Pending</option>
              <option value="Completed">Completed</option>
              <option value="In Progress">In Progress</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Add Task</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- ADD REMINDER Modal -->
<div class="modal fade" id="addReminderModal" tabindex="-1" role="dialog" aria-labelledby="addReminderModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addReminderModalLabel">Add New Reminder</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('Lead/add_reminder') ?>" method="POST">
          <!-- CSRF token -->
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

          <div class="form-group">
            <label for="reminderTime">Reminder Time</label>
            <input type="datetime-local" class="form-control" id="reminderTime" name="reminder_time" required>
          </div>
          <div class="form-group">
            <label for="note">Note</label>
            <textarea class="form-control" id="note" name="note" rows="3" required></textarea>
          </div>
          <input type="hidden" name="lead_id" value="<?= isset($customer->id) ? $customer->id : '' ?>"> <!-- Assuming you pass this value when loading the modal -->
          <button type="submit" class="btn btn-primary">Add Reminder</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Edit Reminder Modal -->
<div class="modal fade" id="editReminderModal" tabindex="-1" role="dialog" aria-labelledby="editReminderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editReminderForm" method="POST">
                          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                <div class="modal-header">
                    <h5 class="modal-title" id="editReminderModalLabel">Edit Reminder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Hidden input for reminder ID -->
                    <input type="hidden" name="id" id="reminderId">

                    <div class="form-group">
                        <label for="reminderTime">Reminder Time</label>
                        <input type="datetime-local" class="form-control" id="reminderTime" name="reminder_time" required>
                    </div>
                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea class="form-control" id="note" name="note" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ADD FOLLOWUP Modal -->
<div class="modal fade" id="addFollowupModal" tabindex="-1" role="dialog" aria-labelledby="addFollowupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFollowupModalLabel">Add New Follow-up</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('Lead/add_followup') ?>" method="POST">
                    <!-- CSRF token -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="lead_id" value="<?=$customer->id ?>">

                    <div class="form-group">
                        <label for="followupDate">Follow-up Date</label>
                        <input type="date" class="form-control" id="followupDate" name="followup_date" required>
                    </div>
                    <div class="form-group">
                        <label for="followupNotes">Notes</label>
                        <textarea class="form-control" id="followupNotes" name="followup_notes" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="followupStatus">Status</label>
                        <select class="form-control" id="followupStatus" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="In Progress">In Progress</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="followupReminder">Reminder Date</label>
                        <input type="date" class="form-control" id="followupReminder" name="reminder" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Follow-up</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Follow-up Modal -->
<div class="modal fade" id="editFollowupModal" tabindex="-1" role="dialog" aria-labelledby="editFollowupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editFollowupForm" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFollowupModalLabel">Edit Follow-up</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Hidden input for follow-up ID -->
                    <input type="hidden" name="id" id="followupId">

                    <div class="form-group">
                        <label for="followupDate">Follow-up Date</label>
                        <input type="date" class="form-control" id="followupDate" name="followup_date" required>
                    </div>
                    <div class="form-group">
                        <label for="followupNotes">Notes</label>
                        <textarea class="form-control" id="followupNotes" name="followup_notes" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="In Progress">In Progress</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reminder">Reminder Date</label>
                        <input type="date" class="form-control" id="reminder" name="reminder" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $this->load->view('layout/footer');?>
<script>
$(document).ready(function() {
    // Populate the modal with the follow-up data when the edit button is clicked
    $('#editFollowupModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var followupId = button.data('id');
        var followupDate = button.data('followup_date');
        var followupNotes = button.data('followup_notes');
        var status = button.data('status');
        var reminder = button.data('reminder');

        // Update the modal's content
        var modal = $(this);
        modal.find('#followupId').val(followupId);
        modal.find('#followupDate').val(followupDate);
        modal.find('#followupNotes').val(followupNotes);
        modal.find('#status').val(status);
        modal.find('#reminder').val(reminder);
    });

    // Handle the form submission via AJAX
    $('#editFollowupForm').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting the normal way

        $.ajax({
            url: '<?= base_url('Lead/update_followup') ?>', // URL for updating follow-up
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message); // Show success message
                    $('#editFollowupModal').modal('hide'); // Hide the modal
                    location.reload(); // Reload the page to show updated data
                } else {
                    alert(response.message); // Show error message
                }
            },
            error: function() {
                alert('An error occurred while updating the follow-up. Please try again.');
            }
        });
    });
});

$(document).ready(function() {
    // Populate the modal with the reminder data when the edit button is clicked
    $('#editReminderModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var reminderId = button.data('id');
        var reminderTime = button.data('reminder_time');
        var note = button.data('note');

        // Update the modal's content
        var modal = $(this);
        modal.find('#reminderId').val(reminderId);
        modal.find('#reminderTime').val(reminderTime);
        modal.find('#note').val(note);
    });

    // Handle the form submission via AJAX
    $('#editReminderForm').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting the normal way

        $.ajax({
            url: '<?= base_url('Lead/update_reminder') ?>', // URL for updating reminder
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message); // Show success message
                    $('#editReminderModal').modal('hide'); // Hide the modal
                    location.reload(); // Reload the page to show updated data
                } else {
                    alert(response.message); // Show error message
                }
            },
            error: function() {
                alert('An error occurred while updating the reminder. Please try again.');
            }
        });
    });
});

// Populate the modal with the task data when the edit button is clicked
$(document).ready(function() {
    // Populate the modal with the task data when the edit button is clicked
    $('#editTaskModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var taskId = button.data('id');
        var taskTitle = button.data('title');
        var taskDescription = button.data('description');
        var taskStartDate = button.data('start_date');
        var taskDueDate = button.data('due_date');
        var taskPriority = button.data('priority');
        var taskStatus = button.data('status');

        // Update the modal's content
        var modal = $(this);
        modal.find('#taskId').val(taskId);
        modal.find('#taskTitle').val(taskTitle);
        modal.find('#taskDescription').val(taskDescription);
        modal.find('#taskStartDate').val(taskStartDate);
        modal.find('#taskDueDate').val(taskDueDate);
        modal.find('#taskPriority').val(taskPriority);
        modal.find('#taskStatus').val(taskStatus);
    });

    // Handle the form submission via AJAX
    $('#editTaskModal form').submit(function(e) {
        e.preventDefault(); // Prevent normal form submission

        $.ajax({
            url: '<?= base_url('Lead/update_task') ?>',
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message); // Success message
                    $('#editTaskModal').modal('hide'); // Hide the modal
                    location.reload(); // Reload the page
                } else {
                    alert(response.message); // Show error message
                }
            },
            error: function() {
                alert('An error occurred while updating the task. Please try again.');
            }
        });
    });
});


    $(document).ready(function() {
        $('#addTaskModal form').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            $.ajax({
                url: '<?= base_url('Lead/add_task') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message); // Show success message
                        $('#addTaskModal').modal('hide');
                        location.reload(); // Reload the page to show the updated data
                    } else {
                        alert(response.message); // Show error message
                    }
                },
                error: function() {
                    alert('An error occurred while adding the task. Please try again.');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#addReminderModal form').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            $.ajax({
                url: '<?= base_url('Lead/add_reminder') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message); // Show success message
                        $('#addReminderModal').modal('hide');
                        location.reload(); // Reload the page to show the updated data
                    } else {
                        alert(response.message); // Show error message
                    }
                },
                error: function() {
                    alert('An error occurred while adding the reminder. Please try again.');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#addFollowupModal form').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            $.ajax({
                url: '<?= base_url('Lead/add_followup') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message); // Show success message
                        $('#addFollowupModal').modal('hide');
                        location.reload(); // Reload the page to show the updated data
                    } else {
                        alert(response.message); // Show error message
                    }
                },
                error: function() {
                    alert('An error occurred while adding the reminder. Please try again.');
                }
            });
        });
    });
</script>


<script>
  $(document).ready(function(e){

    const customerToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    $(document).on('click','.create_login_modal',function(e){
      var id = $(this).data('id');
      //alert(id);
      $.ajax({
        url: "<?php echo base_url('customer/create_login')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#create_login_modal').find('.modal-content').html(data.create_login_modal_body);
          $('#create_login_modal').modal('show');
          // reinitialise();   
          $('.datepicker').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
          });
        }
      });
    });

    $(document).on('hidden.bs.modal','#create_login_modal',function(e){
      $('#create_login_modal').find('.modal-content').html('');
    });

    $(document).on('submit','#customerDetailForm',function(e){
      
      e.preventDefault();

      $('#customerDetailSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#customerDetailForm').serialize();

      // alert(formData);

      var isError = false;

      $('form#customerDetailForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value=="" || value == 0){
            $("form#customerDetailForm  #err_"+id).text(field+ " field is required.");
            $('form#customerDetailForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#customerDetailForm #err_"+id).text("");
            $('form#customerDetailForm #'+id).removeClass('is-invalid');
            $('form#customerDetailForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#customerDetailSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('customer/create_login')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if (response.code == 0) {
                // Handle validation errors
                $.each(response.errors, function(key, value) {
                  $('#'+key).addClass('is-invalid');
                  $("#err_" + key ).text(value); // Display errors beside respective fields
                });
                $('#customerDetailSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
            else if(response.code==1)
            { 
              $('#create_login_modal').modal('hide');
              $('form#customerDetailForm #customerDetailSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              //initialize_datatable();

              customerToast.fire({
                type: 'success',
                title: response.message
              });

              setTimeout(function() {
                location.reload(); // For example, refresh the page after 2 seconds
            }, 1000);
            }
            else
            {
              customerToast.fire({
                type: 'error',
                title: response.message
              });
              $('#customerDetailSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#customerDetailForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#customerDetailForm #err_"+id).text(field+ " field is required.");
          $('form#customerDetailForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#customerDetailForm #err_"+id).text("");
          $('form#customerDetailForm #'+id).removeClass('is-invalid');
          $('form#customerDetailForm #'+id).addClass('is-valid');
        }
    });

    $(document).on('click','.disable_login_modal',function(e){
      var user_id = $(this).data('user_id');
      //alert(id);
      $.ajax({
        url: "<?php echo base_url('customer/disable_login')?>",
        type: "POST",
        data: {
          'user_id':user_id,
          
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(response){
          if(response.code == 1)
          { 
            Swal.fire({
                title: 'SUCCESS !!',
                text: 'User account is disable successfully.',
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });

            setTimeout(function() {
                location.reload(); // For example, refresh the page after 2 seconds
            }, 1000);
          }

          else if(response.code == 0)
          { 
            Swal.fire({
                title: 'FAILURE !!',
                text: 'User account failed to disable.',
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
          }
        }
      });
    });

    $(document).on('click','.enable_login_modal',function(e){
      var user_id = $(this).data('user_id');
      //alert(id);
      $.ajax({
        url: "<?php echo base_url('customer/enable_login')?>",
        type: "POST",
        data: {
          'user_id':user_id,
          
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(response){
          if(response.code == 1)
          { 
            Swal.fire({
                title: 'SUCCESS !!',
                text: response.message,
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });

            setTimeout(function() {
                location.reload(); // For example, refresh the page after 2 seconds
            }, 1000);
          }

          else if(response.code == 0)
          { 
            Swal.fire({
                title: 'FAILURE !!',
                text: response.message,
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
          }
        }
      });
    });
    
    $(document).on('click','.resend_password_modal',function(e){
      var user_id = $(this).data('user_id');
      //alert(user_id);
      $.ajax({
        url: "<?php echo base_url('customer/resend_password')?>",
        type: "POST",
        data: {
          'user_id':user_id,
          
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(response){
          if(response.code == 1)
          { 
            Swal.fire({
                title: 'SUCCESS !!',
                text: 'Updated password sent to customer email address.',
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
          }
          
          else if(response.code == 0)
          { 
            Swal.fire({
                title: 'FAILURE !!',
                text: 'Failed to update password.',
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 2000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
          }
        }
      });
    });

  });


</script>