  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="breadcrumb-title pe-3">Domain</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item active" aria-current="page">List</li>
					</ol>
				</nav>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="example2" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>SR</th>
								<th>Project</th>
								<th>Demo Link</th>
								<th>Type</th>
								<th>File</th>
								<th>Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
                        <?php
                            $i = 1;  
                            foreach($data as $tr){
                                $nul_val = '<span class=text-danger>Not found</span>';
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= !empty($tr->project) ? $tr->project : $nul_val ?></td>
                            <td><a target="_" href="<?= base_url('demo/info/'.$tr->id) ?>"><?= base_url('demo/info/'.$tr->id) ?></a></td>
                            <td><?= !empty($tr->type) ? $tr->type : $nul_val ?></td>
                            <td>
                                <?php if (!empty($tr->attachment)): ?>
                                    <a href="<?= base_url('uploads/demo/' . $tr->attachment) ?>" target="_"><img src="<?= base_url('uploads/demo/' . $tr->attachment) ?>" alt="Attachment" style="max-width: 100px; max-height: 100px;"></a>
                                <?php else: ?>
                                    <?= $nul_val ?>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($tr->date) ? $this->db_model->convert_date_format($tr->date) : $nul_val ?></td>
                            <td>
                                <div class="d-flex justify-content-between">
                                   <a href="#" class="share-link" 
                                       data-project="<?= htmlspecialchars($tr->project) ?>"
                                       data-web-link="<?= htmlspecialchars($tr->web_link) ?>"
                                       data-DemoLink="<?= htmlspecialchars(base_url('demo/info/'.$tr->id)) ?>"
                                       data-user-link="<?= htmlspecialchars($tr->user_link) ?>"
                                       data-username="<?= htmlspecialchars($tr->username) ?>"
                                       data-user-password="<?= htmlspecialchars($tr->user_password) ?>"
                                       data-admin-link="<?= htmlspecialchars($tr->admin_link) ?>"
                                       data-admin-username="<?= htmlspecialchars($tr->admin_username) ?>"
                                       data-admin-password="<?= htmlspecialchars($tr->admin_password) ?>"
                                       data-attachment-link="<?= htmlspecialchars($tr->attachment_link) ?>"
                                       data-apk-file="<?= base_url('uploads/demo/' . $tr->apk_file) ?>"
                                       data-attachment="<?= base_url('uploads/demo/' . $tr->attachment) ?>">
                                        <div class="font-11"><i class="lni lni-share text-success"></i></div>
                                    </a>
                                    <a href="<?= base_url('demo/edit/'.$tr->id) ?>"><div class="font-11"><i class="lni lni-pencil-alt text-info"></i></div></a>
                                    <a href="#" class="delete-link" data-id="<?= $tr->id ?>">
                                        <div class="font-11"><i class="lni lni-trash text-danger"></i></div>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
    </div>
  </main>
  
  
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.share-link').forEach(function (shareLink) {
            shareLink.addEventListener('click', function (e) {
                e.preventDefault();
                const project        = this.getAttribute('data-project') || '';
                const webLink        = this.getAttribute('data-web-link') || '';
                const userLink       = this.getAttribute('data-user-link') || '';
                const username       = this.getAttribute('data-username') || '';
                const userPassword   = this.getAttribute('data-user-password') || '';
                const adminLink      = this.getAttribute('data-admin-link') || '';
                const adminUsername  = this.getAttribute('data-admin-username') || '';
                const adminPassword  = this.getAttribute('data-admin-password') || '';
                const attachmentLink = this.getAttribute('data-attachment-link') || '';
                const apkFile        = this.getAttribute('data-apk-file') || '';
                const attachment     = this.getAttribute('data-attachment') || '';
                const DemoLink       = this.getAttribute('data-DemoLink') || '';
                let message = '';
                if (project) message += `*Project* : ${project}\n\n`;
                if (webLink) message += `*Web Link* : ${webLink}\n\n`;
                if (userLink || username || userPassword) {
                    message += `*User Credentials* : \n\n`;
                    if (userLink) message += `Login Link : ${userLink}\n`;
                    if (username) message += `Username : ${username}\n`;
                    if (userPassword) message += `Password  : ${userPassword}\n`;
                    message += `\n`;
                }
                if (adminLink || adminUsername || adminPassword) {
                    message += `*Admin Credentials*\n\n`;
                    if (adminLink) message += `Login Link : ${adminLink}\n`;
                    if (adminUsername) message += `Username : ${adminUsername}\n`;
                    if (adminPassword) message += `Password  : ${adminPassword}\n`;
                    message += `\n`;
                }
                if (DemoLink) message += `For more details click on below link :\n${DemoLink}\n\n`;
                message += `*Axolotls Innovative Technologies* !`;
                if (navigator.share) {
                    navigator.share({
                        title: 'Demo Details',
                        text: message,
                    }).catch(console.error);
                } else {
                    alert(message);
                }
            });
        });
    });
</script>
 <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.delete-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
          e.preventDefault(); 
          const deleteUrl = `<?= base_url('demo/delete/') ?>${this.getAttribute('data-id')}`;
          Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = deleteUrl; 
            }
          });
        });
      });
    });
</script>
  <!--end main wrapper-->