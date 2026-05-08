<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akshay | Narwade</title>
    <link rel="icon" href="<?= base_url('assets/admin/') ?>assets/images/favicon-32x32.png" type="image/png">
    <link href="<?= base_url('assets/admin/') ?>assets/css/pace.min.css" rel="stylesheet">
    <script src="<?= base_url('assets/admin/') ?>assets/js/pace.min.js"></script>
    <link href="<?= base_url('assets/admin/') ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/') ?>assets/plugins/metismenu/metisMenu.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/') ?>assets/plugins/metismenu/mm-vertical.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/') ?>assets/plugins/simplebar/css/simplebar.css">
    <link href="<?= base_url('assets/admin/') ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/main.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/dark-theme.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/blue-theme.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/semi-dark.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/bordered-theme.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/responsive.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>assets/css/extra-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>

<body>
    <!--start header-->
    <header class="top-header">
        <nav class="navbar navbar-expand align-items-center gap-4">
            <div class="btn-toggle">
                <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
            </div>
            <div class="search-bar flex-grow-1">
                <div class="position-relative">

                    <!-- <input class="form-control rounded-5 px-5 search-control d-lg-block d-none" type="text" placeholder="Search">
                    <span class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">search</span>
                    <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 search-close">close</span> -->
                    <div class="search-popup p-3">
                        <div class="card rounded-4 overflow-hidden">
                            <div class="card-header d-lg-none">
                                <div class="position-relative">
                                    <input class="form-control rounded-5 px-5 mobile-search-control" type="text" placeholder="Search">
                                    <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50">search</span>
                                    <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 mobile-search-close">close</span>
                                </div>
                            </div>
                            <div class="card-body search-content">
                                <p class="search-title">Recent Searches</p>
                                <div class="d-flex align-items-start flex-wrap gap-2 kewords-wrapper">
                                    <a href="javascript:;" class="kewords"><span>Angular Template</span><i class="material-icons-outlined fs-6">search</i></a>
                                </div>
                            </div>
                            <div class="card-footer text-center bg-transparent">
                                <a href="javascript:;" class="btn w-100">See All Search Results</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="navbar-nav gap-1 nav-right-links align-items-center">
                <li class="nav-item d-lg-none mobile-search-btn">
                    <a class="nav-link" href="javascript:;"><i class="material-icons-outlined">search</i></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" data-bs-auto-close="outside" data-bs-toggle="dropdown" href="javascript:;"><i class="material-icons-outlined">notifications</i>
                        <span class="badge-notify">5</span>
                    </a>
                    <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
                        <div class="px-3 py-1 d-flex align-items-center justify-content-between border-bottom">
                            <h5 class="notiy-title mb-0">Notifications</h5>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle dropdown-toggle-nocaret option" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="material-icons-outlined"> more_vert </span>
                                </button>
                                <div class="dropdown-menu dropdown-option dropdown-menu-end shadow">
                                    <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i class="material-icons-outlined fs-6">inventory_2</i>Archive All</a></div>
                                    <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i class="material-icons-outlined fs-6">done_all</i>Mark all as read</a></div>
                                    <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i class="material-icons-outlined fs-6">mic_off</i>Disable Notifications</a></div>
                                    <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i class="material-icons-outlined fs-6">grade</i>What's new ?</a></div>
                                    <div>
                                        <hr class="dropdown-divider">
                                    </div>
                                    <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i class="material-icons-outlined fs-6">leaderboard</i>Reports</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="notify-list">
                            <div>
                                <a class="dropdown-item border-bottom py-2" href="javascript:;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="">
                                            <img src="<?= base_url('assets/admin/') ?>assets/images/avatars/01.png" class="rounded-circle" width="45" height="45" alt="">
                                        </div>
                                        <div class="">
                                            <h5 class="notify-title">Congratulations Jhon</h5>
                                            <p class="mb-0 notify-desc">Many congtars jhon. You have won the gifts.</p>
                                            <p class="mb-0 notify-time">Today</p>
                                        </div>
                                        <div class="notify-close position-absolute end-0 me-3">
                                            <i class="material-icons-outlined fs-6">close</i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="javascrpt:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                      <img src="<?= base_url('assets/admin/') ?>assets/images/avatars/06.png" class="rounded-circle p-1 border" width="45" height="45" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
                        <a class="dropdown-item  gap-2 py-2" href="javascript:;">
                            <div class="text-center">
                                <img src="<?= base_url('assets/admin/') ?>assets/images/avatars/06.png" class="rounded-circle p-1 shadow mb-3" width="90" height="90" alt="">
                                <h5 class="user-name mb-0 fw-bold"><?= $this->db_model->get_name() ?></h5>
                            </div>
                        </a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= base_url('users/profile') ?>"><i class="material-icons-outlined">person_outline</i>Profile</a>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= base_url('admin/setting') ?>"><i class="material-icons-outlined">local_bar</i>Setting</a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= base_url('admin/logout') ?>"><i class="material-icons-outlined">power_settings_new</i>Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    <!--end top header-->

    <!--start sidebar-->
    <aside class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div class="logo-icon">
              <img src="<?= config_item('base_url2') ?>/assets/images/0/13972039167ee846674c9f.png" 
               style="width: 187px; height: 52px;" class="logo-img" alt="">
            </div>
            <!--<div class="logo-name flex-grow-1">-->
            <!--    <h5 class="mb-0">Axolotls</h5>-->
            <!--</d?iv>-->
            <div class="sidebar-close">
                <span class="material-icons-outlined">close</span>
            </div>
        </div>
        <div class="sidebar-nav">
            <!--navigation-->
            <ul class="metismenu" id="sidenav">
                <li>
                    <a href="<?= base_url('admin') ?>">
                        <div class="parent-icon"><i class="material-icons-outlined">home</i> </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                <li class="menu-label">Menu</li>
              
             
                <li>
                    <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="material-icons-outlined">card_giftcard</i> </div>
                        <div class="menu-title">Purchase</div>
                    </a>
                    <ul>
                        <li><a href="<?= base_url('demo') ?>"><i class="material-icons-outlined">arrow_right</i>Add</a></li>
                        <li><a href="<?= base_url('demo/history') ?>"><i class="material-icons-outlined">arrow_right</i>Purchase History</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?= base_url('admin/logout') ?>">
                        <div class="parent-icon"><i class="material-icons-outlined text-danger">power_settings_new</i> </div>
                        <div class="menu-title text-danger">Logout</div>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <!--end sidebar-->

    <?php
	  	  include_once($layout);
	  ?>

    <!--start footer-->
    <footer class="page-footer" style="position: fixed; bottom: 0; width: 100%; text-align: center; padding: 10px 0; box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);">
        <p class="mb-0" style="margin: 0;">Copyright © <?= date('Y') ?>. All rights reserved.</p>
    </footer>

    <!--end footer-->

    <!--start switcher-->
    <button class="btn btn-grd btn-grd-primary position-fixed bottom-0 end-0 m-3 mb-5 d-flex align-items-center gap-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop">
        <i class="material-icons-outlined">tune</i>Customize
    </button>
    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="staticBackdrop">
        <div class="offcanvas-header border-bottom h-70">
            <div class="">
                <h5 class="mb-0">Theme Customizer</h5>
                <p class="mb-0">Customize your theme</p>
            </div>
            <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="offcanvas">
                <i class="material-icons-outlined">close</i>
            </a>
        </div>
        <div class="offcanvas-body">
            <div>
                <p>Theme variation</p>
                <div class="row g-3">
                    <div class="col-12 col-xl-6">
                        <input type="radio" class="btn-check" name="theme-options" id="BlueTheme">
                        <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="BlueTheme">
                            <span class="material-icons-outlined">contactless</span>
                            <span>Blue</span>
                        </label>
                    </div>
                    <div class="col-12 col-xl-6">
                        <input type="radio" class="btn-check" name="theme-options" id="LightTheme" checked>
                        <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="LightTheme">
                            <span class="material-icons-outlined">light_mode</span>
                            <span>Light</span>
                        </label>
                    </div>
                    <div class="col-12 col-xl-6">
                        <input type="radio" class="btn-check" name="theme-options" id="DarkTheme">
                        <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="DarkTheme">
                            <span class="material-icons-outlined">dark_mode</span>
                            <span>Dark</span>
                        </label>
                    </div>
                    <div class="col-12 col-xl-6">
                        <input type="radio" class="btn-check" name="theme-options" id="SemiDarkTheme">
                        <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="SemiDarkTheme">
                            <span class="material-icons-outlined">contrast</span>
                            <span>Semi Dark</span>
                        </label>
                    </div>
                    <div class="col-12 col-xl-6">
                        <input type="radio" class="btn-check" name="theme-options" id="BoderedTheme">
                        <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="BoderedTheme">
                            <span class="material-icons-outlined">border_style</span>
                            <span>Bordered</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--plugins-->

    <script src="<?= base_url('assets/admin/') ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/admin/') ?>assets/js/jquery.min.js"></script>
    <script src="<?= base_url('assets/admin/') ?>assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="<?= base_url('assets/admin/') ?>assets/plugins/metismenu/metisMenu.min.js"></script>
    <script src="<?= base_url('assets/admin/') ?>assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="<?= base_url('assets/admin/') ?>assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="<?= base_url('assets/admin/') ?>assets/plugins/peity/jquery.peity.min.js"></script>
    <script>
        $(".data-attributes span").peity("donut")
    </script>
    <script src="<?= base_url('assets/admin/') ?>assets/js/main.js"></script>
    <script src="<?= base_url('assets/admin/') ?>assets/js/dashboard1.js"></script>
    <script>
        new PerfectScrollbar(".user-list")
    </script>
    <script src="<?= base_url('assets/admin/') ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets/admin/') ?>assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
          } );
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable( {
                lengthChange: false,
                buttons: [ 'copy', 'excel', 'pdf', 'print']
            } );
         
            table.buttons().container()
                .appendTo( '#example2_wrapper .col-md-6:eq(0)' );
        } );
    </script>
</body>

</html>