<main class="main-wrapper">
    <div class="main-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Categories</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
            </div>
        </div>

        <form method="post" enctype="multipart/form-data" class="w-100">
            <div class="row">
                <div class="col-12  mx-auto"> <!-- Center the column -->
                    <div class="card pb-1">
                        <div class="card-body" >
                            <h5 class="">Add Categories</h5>
                            <div class="mb-4 mt-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Categories Name" required>
                            </div>
                            
                            <div>
                              
                                <button class="btn btn-grd btn-grd-primary px-5 p-2 btn-md w-100 mb-4">Save</button> 
                            </div>
                
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); 
    var formData = new FormData(this);
    fetch('<?= base_url('demo/insert_Category') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success'
            }).then(() => {
                window.location.href = '<?= base_url('demo/categorylist') ?>';
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message,
                icon: 'error'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error!',
            text: 'An unexpected error occurred.',
            icon: 'error'
        });
    });
});
</script>
