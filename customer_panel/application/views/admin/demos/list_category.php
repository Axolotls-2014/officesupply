 <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="breadcrumb-title pe-3">Add Categories</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item active" aria-current="page">List</li>
					</ol>
				</nav>
			</div>
		</div>
		<div class="card">
		  <div class="container mt-4">
		      <a href="<?= base_url('demo/category') ?>">
		        
             <button class="btn btn-grd btn-grd-primary px-5 p-2 btn-md w-100 mb-4">Add Categories</button>
            
             </a>
           </div>

			<div class="card-body">
				<div class="table-responsive">
					<table id="example2" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>SR</th>
								<th>Name</th>
								<th>Status</th>
								<th>Date</th>
								<!--<th>Action</th>-->
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
                            <td><?= !empty($tr->name) ? $tr->name : $nul_val ?></td>
                            <td><?= !empty($tr->status) ? $tr->status : $nul_val ?></td>
                            <td><?= !empty($tr->date) ? $tr->date : $nul_val ?></td>
                           
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