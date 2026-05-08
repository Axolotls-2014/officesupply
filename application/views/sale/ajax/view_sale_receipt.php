<div class="modal-header">
    <h4 class="modal-title">Sale Documents - <?= html_escape($sale->reference_no) ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <?php 
            $documents = [];
            if (!empty($sale->sale_receipt)) {
                $documents = json_decode($sale->sale_receipt, true);
            }
            
            if (!empty($documents)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="30%">Title</th>
                                <th width="25%">Date</th>
                                <th width="25%">Uploaded By</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $doc): 
                                $file_path = base_url($doc['file']);
                                $file_ext = pathinfo($doc['file'], PATHINFO_EXTENSION);
                                $icon_class = 'fa-file';
                                
                                // Set icon based on file type
                                if (in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $icon_class = 'fa-file-image text-primary';
                                } elseif (strtolower($file_ext) == 'pdf') {
                                    $icon_class = 'fa-file-pdf text-danger';
                                } elseif (in_array(strtolower($file_ext), ['doc', 'docx'])) {
                                    $icon_class = 'fa-file-word text-primary';
                                } elseif (in_array(strtolower($file_ext), ['xls', 'xlsx'])) {
                                    $icon_class = 'fa-file-excel text-success';
                                }
                            ?>
                            <tr>
                                <td>
                                    <i class="fas <?= $icon_class ?> mr-2"></i>
                                    <?= html_escape($doc['title']) ?>
                                </td>
                                <td><?= date('d-m-Y H:i', strtotime($doc['date'])) ?></td>
                                <td>
                                    <?php 
                                        echo html_escape($this->db->select('username')->from('users')->where('id', $doc['uploaded_by'])->get()->row()->username ?? 'Unknown');
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= $file_path ?>" target="_blank" class="btn btn-sm btn-info" data-toggle="tooltip" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= $file_path ?>" download class="btn btn-sm btn-success" data-toggle="tooltip" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5>No documents found</h5>
                    <p class="text-muted">Upload documents using the upload button</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>