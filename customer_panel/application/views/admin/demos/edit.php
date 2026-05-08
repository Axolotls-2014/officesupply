<main class="main-wrapper">
    <div class="main-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Demo</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <form method="post" enctype="multipart/form-data" id="demoForm">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card pb-1">
                        <div class="card-body">
                            <h5 class="">Edit demo</h5>
                            <div class="mb-4 mt-3">
                                <label for="demo_name" class="form-label">Demo Name</label>
                                <input type="text" class="form-control" name="demo_name" id="demo_name" placeholder="Demo Name" value="<?= htmlspecialchars($data->project) ?>" required>
                            </div>
                            <div class="mb-4">
                                <label for="web_link" class="form-label">Website Link</label>
                                <input type="text" class="form-control" name="web_link" id="web_link" placeholder="Website Link" value="<?= htmlspecialchars($data->web_link) ?>">
                            </div>
                            <div class="mb-4">
                                <label for="apk_file" class="form-label">Apk Link</label>
                                <input type="text" class="form-control" name="apk_file" id="apk_file" placeholder="Apk Link" value="<?= htmlspecialchars($data->apk_file) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Attachment</label>
                                <input class="form-control" type="file" id="formFile" name="attachment" accept="image/*">
                                <?php if (!empty($data->attachment)): ?>
                                    <p>Current file: <a href="<?= base_url('uploads/demo/' . $data->attachment) ?>" target="_blank"><?= $data->attachment ?></a></p>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Category</label>
                                <select id="input7" class="form-select" name="type" required>
                                    <option value="" disabled>Choose...</option>
                                    <option value="E-ccommerce" <?= $data->type === 'E-ccommerce' ? 'selected' : '' ?>>E-ccommerce</option>
                                    <option value="MLM" <?= $data->type === 'MLM' ? 'selected' : '' ?>>MLM</option>
                                    <option value="Unity" <?= $data->type === 'Unity' ? 'selected' : '' ?>>Unity</option>
                                    <option value="Game" <?= $data->type === 'Game' ? 'selected' : '' ?>>Game</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="remark" class="form-label">Remark</label>
                                <textarea class="form-control" name="remark" id="remark" cols="4" rows="6" placeholder="write any remark or note here.."><?= htmlspecialchars($data->remark) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">Admin details</h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="admin_link" class="form-label">Login Link</label>
                                    <input type="text" class="form-control" name="admin_link" id="admin_link" placeholder="Login Link" value="<?= htmlspecialchars($data->admin_link) ?>">
                                </div>
                                <div class="col-12">
                                    <label for="admin_username" class="form-label">Username</label>
                                    <input type="text" class="form-control" name="admin_username" id="admin_username" placeholder="Username" value="<?= htmlspecialchars($data->admin_username) ?>">
                                </div>
                                <div class="col-12">
                                    <label for="admin_pass" class="form-label">Password</label>
                                    <input type="text" class="form-control" name="admin_pass" id="admin_pass" placeholder="Password" value="<?= htmlspecialchars($data->admin_password) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">User details</h5>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="user_link" class="form-label">Login Link</label>
                                        <input type="text" class="form-control" name="user_link" id="user_link" placeholder="Login Link" value="<?= htmlspecialchars($data->user_link) ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="user_username" class="form-label">Username</label>
                                        <input type="text" class="form-control" name="user_username" id="user_username" placeholder="Username" value="<?= htmlspecialchars($data->username) ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="user_pass" class="form-label">Password</label>
                                        <input type="text" class="form-control" name="user_pass" id="user_pass" placeholder="Password" value="<?= htmlspecialchars($data->user_password) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-grd btn-grd-primary px-5 p-2 btn-md w-100 mb-4" type="submit">Update</button>
        </form>
    </div>
</main>

<script>
document.querySelector('#demoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    fetch('<?= base_url('demo/update/' . $data->id) ?>', {
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
                window.location.href = '<?= base_url('demo/list') ?>';
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
