<style>
    .logo-sm img {
        height: 37px;
        margin-left: -10px;
    }
</style>
<!DOCTYPE html>
<html lang="en">

<head>


    <?php
    // X-XSS-Protection: Enables the XSS filter built into most web browsers.
    header('X-XSS-Protection: 1; mode=block');
    header("X-Frame-Options: DENY");
    // header_remove('Server');
    
    // Content-Security-Policy: Defines which content sources are allowed.
    $csp_policy = "
    default-src 'self'; 
    script-src 'self';
    style-src 'self';
    img-src 'self' ;
    connect-src 'self'; 
    font-src 'self';
    object-src 'none'; 
    frame-ancestors 'self'; 
    base-uri 'self'; 
    form-action 'self'; 
    report-uri /csp-violation-report-endpoint";
    header("Content-Security-Policy: $csp_policy");

    // Referrer-Policy: Controls the amount of referrer information sent with requests.
    header('Referrer-Policy: no-referrer-when-downgrade');

    // X-Content-Type-Options: Prevents browsers from MIME-sniffing a response away from the declared content-type.
    header('X-Content-Type-Options: nosniff');

    // Permissions-Policy: Specifies which browser features can be used.
    $permissions_policy = "geolocation=(), midi=(), sync-xhr=(), microphone=(), camera=(), magnetometer=(), gyroscope=(), speaker=(), vibrate=(), fullscreen=(), payment=()";
    header("Permissions-Policy: $permissions_policy");

    // Strict-Transport-Security: Enforces secure (HTTP over SSL/TLS) connections to the server.
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    ?>
    <?php
    // header("X-Frame-Options: DENY");
// header("X-XSS-Protection: 1; mode=block");
// header("X-Content-Type-Options: nosniff");
// header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");
// header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Content-Type: text/html; charset=UTF-8");
    // header("Permissions-Policy: geolocation=(), camera=(), microphone=()");
    ?>
    <meta charset="utf-8" />
    <title>Adi Dravidar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="insight-app-sec-validation" content="36fd3a86-8e17-471b-9aa4-a3ca7fe746a7">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.css">
    <link rel="stylesheet" href="assets/fonts/linearicons.css">
    <link rel="stylesheet" href="assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.png">
    <!-- Daterangepicker css -->
    <link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css">
    <!-- Vector Map css -->
    <link rel="stylesheet" href="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css">
    <!-- Theme Config Js -->
    <script src="assets/js/hyper-config.js"></script>
    <!-- App css -->
    <link href="assets/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/app.min.css<?php echo $js_css_file_comment; ?>">
    <!-- Template CSS -->
    <!-- <link rel="stylesheet" href="assets/css/style.css<?php echo $js_css_file_comment; ?>"> -->
    <!-- <link rel="stylesheet" href="assets/css/new.css<?php echo $js_css_file_comment; ?>"> -->
    <!-- <link rel="stylesheet" href="assets/css/components.css<?php echo $js_css_file_comment; ?>"> -->
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="assets/css/custom.css<?php echo $js_css_file_comment; ?>">
    <!-- <link rel='shortcut icon' type='image/x-icon' href='assets/img/fevi.png' /> -->
    <!---data table style css----->
    <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css<?php echo $js_css_file_comment; ?>">
    <link rel="stylesheet"
        href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css<?php echo $js_css_file_comment; ?>">
    <!--model popup---->
    <link rel="stylesheet" href="assets/bundles/prism/prism.css<?php echo $js_css_file_comment; ?>">
    <link rel="stylesheet" href="assets/css/common.css<?php echo $js_css_file_comment; ?>">
    <!-- Select2 -->
    <link href="assets/libs/select2/css/select2.min.css<?php echo $js_css_file_comment; ?>" rel="stylesheet"
        type="text/css" />
    <link href="assets/libs/select2-bootstrap4/select2-bootstrap4.css<?php echo $js_css_file_comment; ?>"
        rel="stylesheet" type="text/css" />

    <link href="assets/css/select2.min.css" rel="stylesheet" />

    <!-- Dropify -->
    <link href="assets/libs/dropify/dist/css/dropify.min.css<?php echo $js_css_file_comment; ?>" rel="stylesheet"
        type="text/css" />
    <!-- jQuery Multiselect -->
    <link href="assets/libs/jquery_multiselect/jquery.multiselect.css<?php echo $js_css_file_comment; ?>"
        rel="stylesheet" type="text/css" />
    <!-- jQuery-->
    <!-- <script src="assets/libs/jquery/jquery-3.5.1.min.js<?php echo $js_css_file_comment; ?>"></script> -->
    <link rel="stylesheet" href="assets/bundles/summernote/summernote-bs4.css<?php echo $js_css_file_comment; ?>">
    <link rel="stylesheet" href="assets/bundles/codemirror/lib/codemirror.css<?php echo $js_css_file_comment; ?>">
    <link rel="stylesheet" href="assets/bundles/codemirror/theme/duotone-dark.css<?php echo $js_css_file_comment; ?>">



    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap.min.css">

</head>
<style>
    .logoutbtn {
        border: none;
        width: 100%;
    }

    a.side-nav-link {
        font-weight: 500;
        font-size: 15px !important;
    }

    li.bullet i {
        color: #fff;
        align-self: center;
    }

    li.bullet {
        display: flex;
    }

   
.notification-container {
  position: relative;
  display: inline-block;
}/* Modern Light Notification Dropdown */
.notification-container {
  position: relative;
  display: inline-block;
}

/* Bell icon */
.notification-bell {
  font-size: 22px;
  color: #6c757d;
  cursor: pointer;
  padding: 8px;
  border-radius: 50%;
  transition: all 0.3s ease;
}
.notification-bell:hover {
  background-color: #f0f2f5;
  color: #4a6cf7;
}

/* Notification badge */
.notification-count-value {
  position: absolute;
  top: 2px;
  right: 2px;
  background-color: #ff4757;
  color: #fff;
  border-radius: 50%;
  width: 17px;
  height: 17px;
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  box-shadow: 0 2px 6px rgba(255, 71, 87, 0.3);
}

/* Dropdown container */
.notification-dropdown {
  display: none;
  position: absolute;
  right: 0;
  top: 40px;
  width: 340px;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  z-index: 9999;
  font-family: "Poppins", sans-serif;
  padding-left: 0px;
}

.notification-dropdown::before {
  content: '';
  position: absolute;
  top: -10px;
  right: 20px;
  border-left: 8px solid transparent;
  border-right: 8px solid transparent;
  border-bottom: 10px solid #fff;
  filter: drop-shadow(0 -2px 1px rgba(0, 0, 0, 0.05));
}

/* Header */
.notification-dropdown-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 18px;
  background: #fafbfc;
  border-bottom: 1px solid #f0f0f0;
  font-weight: 600;
  color: #333;
  font-size: 15px;
}

.notification-dropdown-header a {
  color: #4a6cf7;
  font-size: 13px;
  font-weight: 500;
  text-decoration: none;
}
.notification-dropdown-header a:hover {
  text-decoration: underline;
}

/* Items list */
.notification-items {
  max-height: 320px;
  overflow-y: auto;
}

/* Each notification */
.notification-dropdown li {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 14px 18px;
  transition: background 0.2s ease;
  border-bottom: 1px solid #f5f5f5;
  cursor: pointer;
}
.notification-dropdown li:hover {
  background: #f8f9fa;
}
.notification-dropdown li:last-child {
  border-bottom: none;
}

/* Icon style */
.notification-item-icon {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  color: white;
  flex-shrink: 0;
}
.notification-item-icon.info { background: #42A5F5; }
.notification-item-icon.warning { background: #FFB300; }
.notification-item-icon.error { background: #E53935; }
.notification-item-icon.success { background: #4CAF50; }

/* Text details */
.notification-item-text {
  color: #111;
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 4px;
}
.notification-item-time {
  color: #888;
  font-size: 12px;
}

/* Footer */
.notification-dropdown-footer {
  padding: 12px 16px;
  text-align: center;
  background: #fafbfc;
  border-top: 1px solid #f0f0f0;
}
.notification-dropdown-footer a {
  color: #4a6cf7;
  text-decoration: none;
  font-size: 13px;
  font-weight: 500;
}
.notification-dropdown-footer a:hover {
  text-decoration: underline;
}
</style>

<body>
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Topbar Start ========== -->
        <div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-lg-2 gap-1">
                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <!-- Logo light -->
                        <a href="index.php" class="logo-light">
                            <span class="logo-lg">
                                <img src="assets/images/ad-logo.png" alt="logo">
                            </span>
                            <span class="logo-sm">
                                <img src="assets/images/logo-sm-2.png" alt="small logo">
                            </span>
                        </a>
                        <!-- Logo Dark -->
                        <a href="index.php" class="logo-dark">
                            <span class="logo-lg">
                                <img src="assets/images/ad-logo.png" alt="dark logo">
                            </span>
                            <span class="logo-sm">
                                <img src="assets/images/logo-sm-2.png" alt="small logo">
                            </span>
                        </a>
                    </div>
                    <!-- Sidebar Menu Toggle Button -->
                    <button class="button-toggle-menu">
                        <i class="mdi mdi-menu"></i>
                    </button>
                    <!-- Horizontal Menu Toggle Button -->
                    <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                    <!-- Topbar Search Form -->
                    <div class="app-search dropdown d-none d-lg-block">
                        
                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h5 class="text-overflow mb-2">Found <span class="text-danger">17</span> results</h5>
                            </div>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="uil-notes font-16 me-1"></i>
                                <span>Analytics Report</span>
                            </a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="uil-life-ring font-16 me-1"></i>
                                <span>How can I help you?</span>
                            </a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="uil-cog font-16 me-1"></i>
                                <span>User profile settings</span>
                            </a>
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow mb-2 text-uppercase">Users</h6>
                            </div>
                            <div class="notification-list">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="d-flex">
                                        <img class="d-flex me-2 rounded-circle" src="assets/images/users/avatar-2.jpg"
                                            alt="Generic placeholder image" height="32">
                                        <div class="w-100">
                                            <h5 class="m-0 font-14">Erwin Brown</h5>
                                            <span class="font-12 mb-0">UI Designer</span>
                                        </div>
                                    </div>
                                </a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="d-flex">
                                        <img class="d-flex me-2 rounded-circle" src="assets/images/users/avatar-5.jpg"
                                            alt="Generic placeholder image" height="32">
                                        <div class="w-100">
                                            <h5 class="m-0 font-14">Jacob Deo</h5>
                                            <span class="font-12 mb-0">Developer</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="topbar-menu d-flex align-items-center gap-3">

                    <!-- <li class="dropdown">
                        <ul class="topbar-menu d-flex align-items-center gap-3">
  <li class="dropdown">
    <div class="notification-container" id="notificationBell">
      <span class="mdi mdi-bell notification-bell"></span>
      <div class="notification-count-value"></div>
      <ul class="notification-dropdown">
        <div class="notification-dropdown-header">
          Notifications
        </div>

        <div class="notification-items"></div>

      </ul>
    </div>
  </li> -->
<!-- 
  <li class="dropdown">
    <a class="nav-link dropdown-toggle arrow-none nav-user px-2" data-bs-toggle="dropdown" href="#" role="button">
      <span class="account-user-avatar">
        <img src="assets/images/users/avatar-1.jpg" alt="user-image" width="32" class="rounded-circle">
      </span>
      <span class="d-lg-flex flex-column gap-1 d-none">
        <h5 class="my-0"><?= $_SESSION['user_name']; ?></h5>
      </span>
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
      <div class="dropdown-header noti-title">
        <h6 class="text-overflow m-0">Welcome!</h6>
      </div>
      <a href="logout.php" class="dropdown-item">
        <i class="mdi mdi-logout me-1"></i>
        <span>Logout</span>
      </a>
    </div>
  </li> -->
</ul>

                    </li>


                    
                    <li class="dropdown d-lg-none">
                        <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <i class="ri-search-line font-22"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                            <form class="p-3">
                                <input type="search" class="form-control" placeholder="Search ..."
                                    aria-label="Recipient's username">
                            </form>
                        </div>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle arrow-none nav-user px-2" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar">
                                <img src="assets/images/users/avatar-1.jpg" alt="user-image" width="32"
                                    class="rounded-circle">
                            </span>
                            <span class="d-lg-flex flex-column gap-1 d-none">
                                <!--<h5 class="my-0">Karthick</h5>-->
                                <h5 class="my-0"><?= $_SESSION['user_name']; ?></h5>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                            <!-- item-->
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <a href="logout.php" class="dropdown-item">
                                <i class="mdi mdi-logout me-1"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ========== Topbar End ========== -->
        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">
            <!-- Brand Logo Light -->
            <a href="index.php" class="logo logo-light">
                <span class="logo-lg">
                    <img src="assets/images/ad-logo.png" alt="logo">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo-sm-2.png" alt="small logo">
                </span>
            </a>
            <!-- Brand Logo Dark -->
            <a href="index.php" class="logo logo-dark">
                <span class="logo-lg">
                    <img src="assets/images/ad-logo.png" alt="dark logo">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo-sm-2.png" alt="small logo">
                </span>
            </a>
            <!-- Sidebar Hover Menu Toggle Button -->
            <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
                <i class="ri-checkbox-blank-circle-line align-middle"></i>
            </div>
            <!-- Full Sidebar Menu Close Button -->
            <div class="button-close-fullsidebar">
                <i class="ri-close-fill align-middle"></i>
            </div>
            <!-- Sidebar -left -->
            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <!-- Leftbar User -->
                <div class="leftbar-user">
                    <a href="pages-profile.html">
                        <img src="assets/images/users/avatar-1.jpg" alt="user-image" height="42"
                            class="rounded-circle shadow-sm">
                        <span class="leftbar-user-name mt-2">Dominic Keller</span>
                    </a>
                </div>
                <!--- Sidemenu -->
                <ul class="side-nav">
                    <?php

                    $password = '3sc3RLrpd17';
                    $enc_method = 'aes-256-cbc';
                    $enc_password = substr(hash('sha256', $password, true), 0, 32);
                    $enc_iv = "av3DYGLkwBsErphc";

                    if (isset($_GET['file'])) {
                        $file_str = openssl_decrypt(base64_decode($_GET['file']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);
                        $file_arr = explode("/", $file_str);
                        $folder_name_org = $file_arr[0];
                        $file_name_org = $file_arr[1];
                    }

                    // Dashboard active state
                    $dashboard_active = ($folder_name_org == 'dashboard') ? "menuitem-active" : "";
                    ?>
                    <?php if ($_SESSION['sess_user_type'] != '6213273aa04b228161') { ?>

                        <li class="side-nav-item  <?= $dashboard_active; ?>">
                            <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false"
                                aria-controls="sidebarDashboards" class="side-nav-link">
                                <i class="uil-home-alt"></i>

                                <span> Dashboards <span class="menu-arrow"></span></span>
                            </a>
                            <div class="collapse" id="sidebarDashboards">
                                <ul class="side-nav-second-level ">
                                    <li>
                                        <?php
                                        $menu_screen = "dashboard/form";
                                        $file_name = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                                        ?>
                                        <a href="index.php?file=<?php echo $file_name; ?>" aria-expanded="false"
                                            aria-controls="sidebarDashboards"
                                            class="sidenav-link  <?= $dashboard_active ? 'active' : ''; ?>">

                                            <span> Dashboard </span>
                                        </a>
                                    </li>
                                    <li class="side-nav-item">
                                        <?php
                                        $menu_screen = "dashboard_chart/form";
                                        $file_name = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                                        ?>
                                        <a href="index.php?file=<?php echo $file_name; ?>" aria-expanded="false"
                                            aria-controls="sidebarDashboards"
                                            class="sidenav-link  <?= $dashboard_active ? 'active' : ''; ?>">

                                            <span>Chart Dashboard </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                    <?php } ?>
                    <?php
                    $main_screens = main_screen();
                    foreach ($main_screens as $main_key => $main_value) {
                        if (in_array($main_value['unique_id'], $_SESSION['main_screens'])) {
                            $menu_main_name = disname($main_value["screen_main_name"]);
                            $menu_main_icon = "";
                            if ($main_value["icon_name"]) {
                                $menu_main_icon = '<i class="nav-icon ' . $main_value["icon_name"] . '"></i>';
                            }

                            // Check if any sub screen under this main screen is active
                            $is_active_main = false;
                            $user_screens = user_screen('', $main_value['unique_id']);
                            foreach ($user_screens as $sub_value) {
                                if ($folder_name_org == $sub_value["folder_name"]) {
                                    $is_active_main = true;
                                    break;
                                }
                            }

                            $active = $is_active_main ? "menuitem-active" : "";
                            $show = $is_active_main ? "show" : "";
                            $aria_expanded = $is_active_main ? "true" : "false";
                            ?>
                            <li class="side-nav-item <?= $active; ?>">
                                <a data-bs-toggle="collapse" href="#sidebarEcommerce<?= $main_value['unique_id']; ?>"
                                    aria-expanded="<?= $aria_expanded; ?>"
                                    aria-controls="sidebarEcommerce<?= $main_value['unique_id']; ?>" class="side-nav-link">
                                    <?php echo $menu_main_icon; ?>&nbsp;<span>
                                        <?php echo $menu_main_name; ?>
                                        <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse <?= $show; ?>" id="sidebarEcommerce<?= $main_value['unique_id']; ?>">
                                    <ul class="side-nav-second-level">
                                        <?php
                                        foreach ($user_screens as $sub_key => $sub_value) {
                                            if (in_array($sub_value['unique_id'], $_SESSION['screens'])) {
                                                $screen_name = disname($sub_value["screen_name"]);
                                                $screen_icon = "";
                                                $folder = $sub_value["folder_name"];
                                                if ($sub_value["icon_name"]) {
                                                    $screen_icon = '<i class="nav-icon"></i>';
                                                }

                                                $menu_screen = $folder . "/list";
                                                $file_name = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));

                                                // Check if the current sub screen is active
                                                $sub_active = ($folder_name_org == $folder) ? "active" : "";
                                                ?>
                                                <li class="menuitem-active">
                                                    <a href="index.php?file=<?php echo urlencode($file_name); ?>"
                                                        class="sidenav-link <?= $sub_active; ?>">
                                                        <?php echo $screen_icon; ?>                 <?php echo $screen_name; ?>
                                                    </a>
                                                </li>
                                            <?php }
                                        } ?>
                                    </ul>
                                </div>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <!--- End Sidemenu -->
                <div class="clearfix"></div>
            </div>
        </div>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const bell = document.getElementById('notificationBell');
  const dropdown = bell.querySelector('.notification-dropdown');
  const countDiv = bell.querySelector('.notification-count-value');
  const itemsContainer = dropdown.querySelector('.notification-items');

  window.loadNotificationCount = function () {
    $.ajax({
      url: 'folders/indent_count/crud.php',
      type: 'POST',
      data: { action: 'notification_count' },
      success: function (res) {
        try {
          const json = JSON.parse(res);
          countDiv.innerText = (json.status && json.count) ? json.count : 0;
        } catch (e) { console.error("Invalid response:", res); }
      }
    });
  };

  function loadNotificationDropdown() {
    $.ajax({
      url: 'folders/indent_count/crud.php',
      type: 'POST',
      data: { action: 'notification_list' },
      success: function (res) {
        try {
          const json = JSON.parse(res);
          itemsContainer.innerHTML = '';

          if (json.status && Array.isArray(json.data) && json.data.length > 0) {
            json.data.forEach(item => {
              let iconClass = 'info';
              if (item.dadwo_requested_count > 10) iconClass = 'warning';
              else if (item.dadwo_requested_count > 3) iconClass = 'success';

              const li = document.createElement('li');
              li.innerHTML = `
                <div class="notification-item-icon ${iconClass}">
                  <i class="mdi mdi-bell-outline"></i>
                </div>
                <div style="flex:1;">
                  <div class="notification-item-text">${item.hostel_id}</div>
                  <div class="notification-item-time">
                    Request Count: <strong>${item.dadwo_requested_count}</strong>
                  </div>
                </div>
              `;
              li.addEventListener('click', e => {
                e.stopPropagation();
                window.location.href = 'index.php?file=3tEd2GfQcxZxmIEkLbV8Zw15ft%2BeH4qO5vL1%2BC1pbVY%3D';
              });
              itemsContainer.appendChild(li);
            });
          } else {
            const li = document.createElement('li');
            li.style.textAlign = 'center';
            li.style.color = '#777';
            li.textContent = 'No new notifications available';
            itemsContainer.appendChild(li);
          }
        } catch (e) { console.error("Invalid server response:", res); }
      }
    });
  }

  loadNotificationCount();
  setInterval(loadNotificationCount, 60000);

  bell.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
    loadNotificationDropdown();
  });

  document.addEventListener('click', function (e) {
    if (!bell.contains(e.target)) dropdown.style.display = 'none';
  });
});
</script>
<script src="folders/indent_count/indent_count.js"></script>
        <!-- ========== Left Sidebar End ========== -->