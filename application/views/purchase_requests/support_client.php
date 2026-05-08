<?php $this->load->view('layout/header');?>


  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item active">Support</li>
                <li class="breadcrumb-item ">Manage</a></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Support</h3>
             <?php 
                $user_id = $this->session->userdata('user_id');
                $user_info = $this->db->select('role')
                                      ->where('id', $user_id)
                                      ->get('users')
                                      ->row();
                
                if ($user_info) {
                    if ($user_info->role != 'super_admin') { ?>
                        <div class="card-tools">
                            <button type="button" class="btn btn-block btn-primary btn-sm add_warehouse_modal" 
                                    data-toggle="modal" data-target="#add_warehouse_modal" 
                                    data-tt="tooltip" title="Click here to add new issue">
                                Raise Issue
                            </button>
                        </div>
                <?php 
                    }
                }
?>

                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>SR. NO.</th>
                      <th>Subject</th>
                      <th>Description</th>
                      <th>Attachment</th> 
                      <th>Comment</th>  
                      <th>Review</th>
                      <th>Date</th> 
                      <?php if($user_info->role == 'super_admin'){ ?>
                      <th>Action</th> 
                      <?php } ?>

                    </tr>
                  </thead>
                  <tbody>
                        <?php if (!empty($client_support)) : ?>
                            <?php $i = 1; foreach ($client_support as $tr) : ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= htmlspecialchars($tr->subject) ?></td>
                                    <td><?= htmlspecialchars($tr->description) ?></td>
                                    <td>
                                        <?php if (!empty($tr->attachment)): ?>
                                            <a href="<?= base_url('uploads/support/' . $tr->attachment) ?>" target="_blank">View</a>
                                        <?php else: ?>
                                            No Attachment
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($tr->comment) ?></td>
                                    <?php if($user_info->role !='super_admin'){ ?>
                                    <td class="review-status" data-review="<?= htmlspecialchars($tr->review) ?>">
                                        <?php if ($tr->review == 'Pending') : ?>
                                            <select class="form-control review-select" data-id="<?= $tr->id ?>">
                                                <option value="">Select Review</option>
                                                <option value="Satisfied">Satisfied</option>
                                                <option value="Not Satisfied">Not Satisfied</option>
                                            </select>
                                        <?php else: ?>
                                            <span class="badge <?= ($tr->review == 'Satisfied') ? 'badge-success' : 'badge-danger' ?>">
                                                <?= $tr->review ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <?php } ?>
                                   <?php if($user_info->role == 'super_admin'){ ?>
                                    <?php 
                                        $review = htmlspecialchars($tr->review);
                                        $colorClass = '';
                                        if ($review == 'Satisfied') {
                                            $colorClass = 'text-success'; // Green for satisfied
                                        } elseif ($review == 'Not Satisfied') {
                                            $colorClass = 'text-danger'; // Red for not satisfied
                                        } elseif ($review == 'Pending') {
                                            $colorClass = 'text-warning'; // Yellow for pending
                                        }
                                    ?>
         
                                    <td class="<?= $colorClass ?>"><?= $review ?></td>
                                    <td><?= htmlspecialchars($tr->date) ?></td>
                                    <td>
                                        
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_comment_modal" 
                                    data-id="<?= $tr->id ?>" data-comment="<?= htmlspecialchars($tr->comment) ?>" title="Comment"><i class="fas fa-comment"></i></button>
                                    <?php } else { ?>
                                    <?= htmlspecialchars($tr->comment) ?> 
                                    </td>
                                <?php } ?>





                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center">No Tickets found.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
      <!-- /.row -->
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php $this->load->view('layout/footer');?>


<div class="example-modal">
 <div class="modal fade" id="add_warehouse_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title">Raise Issue</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form action="<?= base_url('support_client/insert_ticket') ?>" method="post" enctype="multipart/form-data">

          <div class="modal-body">

            <div class="form-group">
              <label>Subject <span class="text-danger">*</span></label>
              <input type="text" name="subject" class="form-control" required placeholder="Enter Subject">
            </div>

            <div class="form-group">
              <label>Description <span class="text-danger">*</span></label>
              <input type="text" name="description" class="form-control" required placeholder="Enter Description">
            </div>

            <div class="form-group">
              <label>Attachment</label>
              <input type="file" name="attachment" class="form-control">
            </div>

          </div>

          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>

      </div>
    </div>
 </div>
</div>


<!-- Edit Comment Modal -->
<div class="modal fade" id="edit_comment_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h4 class="modal-title">Edit Comment</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="<?= base_url('support_client/update_comment') ?>" method="post">
        <div class="modal-body">
          <input type="hidden" name="ticket_id" id="ticket_id">
          
          <div class="form-group">
            <label for="comment">Comment</label>
            <textarea class="form-control" name="comment" id="comment" rows="3" placeholder="Enter your comment here"></textarea>
          </div>
        </div>

        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Comment</button>
        </div>
      </form>

    </div>
  </div>
</div>



<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable({
      responsive: true,
      "autoWidth": false,
      "order": [],
      "columnDefs": [
        { 
          "targets": [0], // Adjust targets based on which columns you don't want sortable
          "orderable": false
        }
      ]
    });

    $('.select2bs4').select2({ theme: 'bootstrap4' });
    $('[data-tt="tooltip"]').tooltip({ trigger: 'hover' });

    // If you want to filter based on "sales_payment" dropdown
    $('#sales_payment').on('change', function() {
      const selected = $(this).val().toLowerCase();
      $('#example').DataTable().search(selected).draw();
    });
  });
  
  
$(document).ready(function() {
    // Handle review selection
    $('.review-select').on('change', function() {
        var ticketId = $(this).data('id');
        var reviewStatus = $(this).val();

        // Get the current review status from the parent <td> using the data-review attribute
        var currentReview = $(this).closest('td').data('review');  // Use the data-review attribute

        console.log('Current review status:', currentReview);  // Debugging line

        // Only send AJAX request if the current review status is Pending
        if (currentReview === 'Pending' && reviewStatus) {
            // Proceed with the review update if the status is Pending
            $.ajax({
                url: '<?= base_url('Support_client/update_review') ?>', // PHP function to update review
                type: 'POST',
                contentType: 'application/json',  // Sending data as JSON
                data: JSON.stringify({ id: ticketId, review: reviewStatus }),  // Convert to JSON
                success: function(response) {
                    console.log('Full response:', response);  // Log the full response to check

              
                        if (response) {
                            var badgeClass = reviewStatus == 'Satisfied' ? 'badge-success' : 'badge-danger';
                            var badgeText = reviewStatus;

                            // Find the correct row based on ticketId and update the review badge
                            var row = $('tr').find('[data-id="' + ticketId + '"]').closest('tr'); // Find the row by ticketId
                            row.find('.review-badge').removeClass('badge-warning').addClass(badgeClass).text(badgeText); // Update the badge

                            // Optionally, update the dropdown (if needed)
                            row.find('.review-select').val(reviewStatus);
                            alert('Reviwed Saved Successfully..');

                            // Optionally, reload the page if necessary
                            location.reload(); // Optional if you want to reload the page

                        } else {
                            alert('Error updating review: ' + response.message);
                        }
                   
                },
                error: function(xhr, status, error) {
                    alert('AJAX error: ' + error);
                }
            });
        } else {
            alert('Review can only be updated if the current status is Pending');
        }
    });
});



$('#edit_comment_modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var ticketId = button.data('id'); // Extract ticket id
    var comment = button.data('comment'); // Extract current comment

    // Update the modal's content with the ticket's comment and ID
    var modal = $(this);
    modal.find('#ticket_id').val(ticketId);
    modal.find('#comment').val(comment);
});




</script>
