<style>
    .logo-sm img {
    height: 37px;
    margin-left: -10px;
}
</style>
<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from coderthemes.com/hyper/saas/index.php by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 09 Nov 2023 13:48:40 GMT -->
<head>
    <meta charset="utf-8" />
    <title>Adi Dravidar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
</head>
<style>
.logoutbtn {
    border: none;
    width: 100%;
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
                        <!--<form>-->
                        <!--    <div class="input-group">-->
                        <!--        <input type="search" class="form-control dropdown-toggle" placeholder="Search..."-->
                        <!--            id="top-search">-->
                        <!--        <span class="mdi mdi-magnify search-icon"></span>-->
                        <!--        <button class="input-group-text btn btn-primary" type="submit">Search</button>-->
                        <!--    </div>-->
                        <!--</form>-->
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
                                <h5 class="my-0">Admin</h5>
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
      if (isset($_GET['file'])) {
        $file_str        = $_GET['file'];
        $file_arr        = explode("/", $file_str);
        $folder_name_org = $file_arr[0];
        $file_name_org   = $file_arr[1];
      }
      if ($folder_name_org == 'dashboard') {
        $active = "menuitem-active";
      } else {
        $active = "";
      } ?>
                    <?php if($_SESSION['sess_user_type'] != '6213273aa04b228161'){?>
                    <!-- <li class="side-nav-title">Navigation</li>-->
                    <li class="side-nav-item <?=$active;?>">
                        <a data-bs-toggle="collapse show" href="index.php?file=dashboard/form" aria-expanded="false"
                            aria-controls="sidebarDashboards" class="side-nav-link active">
                            <i class="uil-home-alt"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
                    <?php }?>
                    <!--<li class="side-nav-title">Menu</li>-->
                    <?php
      if (isset($_GET['file'])) {
        $file_str        = $_GET['file'];
        $file_arr        = explode("/", $file_str);
        $folder_name_org = $file_arr[0];
        $file_name_org   = $file_arr[1];
      }
      $main_screens  = main_screen();
      foreach ($main_screens as $main_key => $main_value) {
        if (in_array($main_value['unique_id'], $_SESSION['main_screens'])) {
          $menu_main_name    =  disname($main_value["screen_main_name"]);
          $menu_main_icon    = "";
          if ($main_value["icon_name"]) {
            $menu_main_icon    =  '<i class="nav-icon ' . $main_value["icon_name"] . '"></i>';
          }
          $user_screens_act     = user_screen('', '', $folder_name_org);
      ?>
                   <?php  if ($user_screens_act[0]['main_screen_unique_id'] == $main_value['unique_id']) {
            $active = "menuitem-active";
            $show = "show";
          } else {
            $active = "";
            $show = "";
          } ?>
                    <li class="side-nav-item <?=$active;?>">
                        <a data-bs-toggle="collapse" href="#sidebarEcommerce<?=$main_value['unique_id'];?>" aria-expanded="false"
                            aria-controls="sidebarEcommerce" class="side-nav-link">
                              <!-- <<i class=" uil-users-alt"></i>
                              span> Admin </span> -->
                            <?php echo $menu_main_icon; ?>&nbsp;<span><?php echo $menu_main_name; ?>
                                <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse <?=$show;?>" id="sidebarEcommerce<?=$main_value['unique_id'];?>">
                            <ul class="side-nav-second-level">
                                <?php
              $user_screens     = user_screen('', $main_value['unique_id']);
              foreach ($user_screens as $sub_key => $sub_value) {
                if (in_array($sub_value['unique_id'], $_SESSION['screens'])) {
                $screen_name    =  disname($sub_value["screen_name"]);
                $screen_icon    = "";
                $folder         = $sub_value["folder_name"];
                if ($sub_value["icon_name"]) {
                  $screen_icon    =  '<i class="nav-icon ' . $sub_value["icon_name"] . '"></i>';
                }
                
                if($folder_name_org == $folder) {
                    $sub_active = "active";
                    
                }else{
                    $sub_active = "";
                }
              ?>
                                <li class="menuitem-active"><a href="index.php?file=<?php echo $folder; ?>/list"
                                        class="sidenav-link <?=$sub_active;?>"><?php echo $screen_name; ?></a></li>
                                <?php } }?>
                            </ul>
                        </div>
                    </li>
                    <?php
        }
      }
      ?>
               <!--<button type="button" class="logoutbtn" onclick="logout()"><a-->
               <!--             class="dropdown-item has-icon text-danger" style="cursor: pointer;">-->
               <!--             <i class="fas fa-sign-out-alt">Logout</i>-->
               <!--         </a> </button>-->
                </ul>
                <!--- End Sidemenu -->
                <div class="clearfix"></div>
            </div>
        </div>
        <script>
        function logout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }
        </script>
        <!-- ========== Left Sidebar End ========== -->