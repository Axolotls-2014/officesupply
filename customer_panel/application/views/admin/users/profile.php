<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Users</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card rounded-4">
            <div class="card-body p-4">
                <div class="position-relative mb-5">
                    <!-- <img src="<?= base_url('assets/admin/') ?>assets/images/gallery/profile-cover" class="img-fluid rounded-4 shadow"> -->
                    <div class="profile-avatar position-absolute top-100 start-50 translate-middle">
                        <img src="<?= base_url('assets/admin/') ?>assets/images/avatars/06.png" class="img-fluid rounded-circle p-1 bg-grd-danger shadow" width="170" height="170" alt="User Avatar">
                    </div>
                </div>
                <div class="profile-info pt-5 d-flex align-items-center justify-content-between">
                    <div>
                        <h3><?= $data->firstname.' '.$data->lastname ?>!</h3>
                        <p class="mb-0"><?= $this->db_model->select('role','roles',array('id' => $data->role)) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card rounded-4 border-top border-4 border-primary border-gradient-1">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h5 class="mb-0 fw-bold">Edit Profile</h5>
                            </div>
                        </div>
                        <form class="row g-4">
                            <div class="col-md-6">
                                <label for="input1" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="input1" name="first_name" placeholder="First Name" value="<?= $data->firstname ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="input2" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="input2" name="last_name" placeholder="Last Name" value="<?= $data->lastname ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="input3" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="input3" name="phone" placeholder="Phone" value="<?= $data->phonenumber ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="input4" class="form-label">Email</label>
                                <input type="email" class="form-control" id="input4" name="email" placeholder="Email" value="<?= $data->email ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="input5" class="form-label">Password</label>
                                <input type="password" class="form-control" id="input5" name="password" placeholder="Password" value="">
                            </div>
                            <div class="col-md-12">
                                <label for="input6" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="input6" name="dob" value="<?= $data->dob ?>">
                            </div>
                            <div class="col-md-12">
                                <label for="input7" class="form-label">Country</label>
                                <select id="input7" class="form-select" name="country">
                                    <option value="">Choose...</option>
                                    <option value="One" <?= $data->country == 'One' ? 'selected' : '' ?>>One</option>
                                    <option value="Two" <?= $data->country == 'Two' ? 'selected' : '' ?>>Two</option>
                                    <option value="Three" <?= $data->country == 'Three' ? 'selected' : '' ?>>Three</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="input8" class="form-label">City</label>
                                <input type="text" class="form-control" id="input8" name="city" placeholder="City" value="<?= $data->city ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="input9" class="form-label">State</label>
                                <select id="input9" class="form-select" name="state">
                                    <option value="">Choose...</option>
                                    <option value="One" <?= $data->state == 'One' ? 'selected' : '' ?>>One</option>
                                    <option value="Two" <?= $data->state == 'Two' ? 'selected' : '' ?>>Two</option>
                                    <option value="Three" <?= $data->state == 'Three' ? 'selected' : '' ?>>Three</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="input10" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" id="input10" name="zip" placeholder="Zip" value="<?= $data->zip ?>">
                            </div>
                            <div class="col-md-12">
                                <label for="input11" class="form-label">Address</label>
                                <textarea class="form-control" id="input11" name="address" placeholder="Address..." rows="4" cols="4"><?= $data->address ?></textarea>
                            </div>
                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="button" class="btn btn-grd-primary px-4 text-white">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</main>
