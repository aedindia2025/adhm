<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->
<?php

$hostel_ = $_SESSION["district_name"];

// session_start();


if (isset($_SESSION['hostel_name']) && !empty($_SESSION['hostel_name'])) {

    $hostel_name = $_SESSION['hostel_name'];

} else {

    $hostel_name = "Default Hostel";
}

$count_hstl = is_array($hostel_name) ? count($hostel_name) : 1;


?>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                           
                        </div>
                        <h4 class="page-title" style=" line-height: 18px;margin: 16px 0px;">Dashboard</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="card widget-flat text-bg-success">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-hospital-building  widget-icon bg-white text-success"></i>
                            </div>
                            <h6 class="text-uppercase text-reset mt-0" title="Customers">Total Hostels</h6>
                            <h3 class="mt-3 mb-3 text-reset" id="total_hostel"
                                onclick="new_external_window_print_new(event,'folders/dashboard/hostel_print.php');">>
                            </h3>

                            <p class="mb-0">
                                <span class="badge bg-white bg-opacity-10 me-1">
                                    <!-- <i class="mdi mdi-arrow-up-bold"></i> -->
                                </span>
                                <span class="text-nowrap"></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card widget-flat text-bg-danger ">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-account-multiple widget-icon bg-white text-danger"></i>
                            </div>
                            <h6 class="text-uppercase text-reset mt-0" title="Customers">Total Student</h6>
                            <h3 class="mt-3 mb-3 text-reset" id="total_students"
                                onclick="new_external_window_print_new(event,'folders/dashboard/student_print.php');">>
                            </h3>
                            <p class="mb-0">
                                <span class="badge bg-white bg-opacity-10 me-1">
                                    <!-- <i class="mdi mdi-arrow-up-bold"></i> -->
                                </span>
                                <span class="text-nowrap"></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card widget-flat text-bg-primary">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-timeline-check-outline  widget-icon bg-white text-primary"></i>
                            </div>
                            <h6 class="text-uppercase text-reset mt-0" title="Customers">Total Staff</h6>
                            <h3 class="mt-3 mb-3 text-reset" id="total_staff"
                                onclick="new_external_window_print_new(event,'folders/dashboard/staff.php');">></h3>
                            <p class="mb-0">
                                <span class="badge bg-white bg-opacity-10 me-1">
                                    <!-- <i class="mdi mdi-arrow-up-bold"></i> -->
                                </span>
                                <span class="text-nowrap"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title" style="line-height: unset;margin-top: 0px;margin-bottom: 8px;">Application</h4>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-1">
                                            <i class="mdi mdi-file-check "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Applied Application</h5>
                                    <p class="mb-0" id="appl_cnt"
                                        onclick="new_external_window_print(event,'folders/dashboard/print.php','applied');">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-2">
                                            <i class="mdi mdi-thumb-up "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Warden Acceptance</h5>
                                    <p class="mb-0" id="accp_cnt"
                                        onclick="new_external_window_print(event,'folders/dashboard/print.php','acceptance');">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-3">
                                            <i class="mdi mdi-checkbox-marked-circle-outline "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Approval</h5>
                                    <p class="mb-0" id="appr_cnt"
                                        onclick="new_external_window_print(event,'folders/dashboard/print.php','approved');">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-4">
                                            <i class="mdi mdi-file-sign "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Rejected</h5>
                                    <p class="mb-0" id="rej_cnt"
                                        onclick="new_external_window_print(event,'folders/dashboard/print.php','rejected');">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <!-- <div class="row">
                <div class="col-md-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-4">
                                            <i class="mdi mdi-file-sign "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Pending Warden Processing</h5>
                                    <p class="mb-0" id="pen_warden_pr"
                                        onclick="new_external_window_print(event,'folders/dashboard/print.php','pen_warden_pr');">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-4">
                                            <i class="mdi mdi-file-sign "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Pending DADWO Processing</h5>
                                    <p class="mb-0" id="pen_dadwo_pr"
                                        onclick="new_external_window_print(event,'folders/dashboard/print.php','pen_dadwo_pr');">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <form class="d-flex">

                                <!-- <a href="javascript: void(0);" class="btn thme-colo text-white  ms-2">
                                    <i class="mdi mdi-autorenew "></i>
                                </a> -->

                            </form>
                        </div>
                        <h4 class="page-title" style="line-height: unset;margin-top: 14px;margin-bottom: 8px;">Registration</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-3">
                                            <i class="mdi mdi-timeline-check-outline "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1"> Total Capacity </h5>
                                    <p class="mb-0" id="tot_cap"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-5">
                                            <i class="mdi mdi-account-multiple-outline "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Old Students</h5>
                                    <p class="mb-0" id="old_std"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-6">
                                            <i class="mdi mdi-account-plus-outline "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">New Students</h5>
                                    <p class="mb-0" id="new_std"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-7">
                                            <i class="mdi mdi-hospital-building "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Hostel Vacancy</h5>
                                    <p class="mb-0" id="hos_vac"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right mt-0 mb-2">
                        <form class="d-flex">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-light" id="dash-daterange">
                                    <!-- <span class="input-group-text thme-colo  border-primary text-white"> -->
                                    <!-- <i class="mdi mdi-calendar-range font-13"></i> -->
                                    </span>
                                </div>
                                <!-- <a href="javascript: void(0);" class="btn thme-colo text-white  ms-2">
                                    <i class="mdi mdi-autorenew "></i>
                                </a> -->
                                <a href="javascript: void(0);" class="btn thme-colo  text-white ms-1">
                                    <i class="mdi mdi-filter-variant"></i>
                                </a>
                            </form>
                        </div>
                        <h4 class="page-title" style="line-height: unset;margin-top: 14px;margin-bottom: 8px;">Attendance</h4>
                    </div>
                </div>
            </div>
            <div class="row">


                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card widget-flat">
                                <div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
                                    <div class="cd i3">
                                        <i class="ri-shield-user-line widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted  mt-0 vf" title="Number of Customers">Total Strength</h5>
                                    <h3 class="mt-2 mb-0 v-1 count" id="total_strength">0</h3>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>
                        <div class="col-sm-3">
                            <div class="card widget-flat">
                                <div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
                                    <div class="cd i1">
                                        <i class="ri-checkbox-circle-line widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted  mt-0 vf" title="Number of Customers">Present Today</h5>
                                    <h3 class="mt-2 mb-0 v-1 count" id="present">0</h3>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>
                        <div class="col-sm-3">
                            <div class="card widget-flat">
                                <div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
                                    <div class="cd i2">
                                        <i class="ri-close-circle-line widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted  mt-0 vf" title="Number of Customers">Absent Today</h5>
                                    <h3 class="mt-2 mb-0 v-1 count" id="absent">0</h3>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>

                        <div class="col-sm-3">
                            <div class="card cta-box text-bg-primary">
                                <div class="card-body red-pad">
                                    <div class="text-center">
                                        <h3 class="m-0  cta-box-title text-reset">Applied Leave</h3>

                                        <div class="fx1`f">
                                            <h5></h5>
                                        </div>
                                        <a href="#" data-bs-toggle="modal" data-bs-target=".applied-leave-modal" type="button">
                                            <h4 class="mt-2 mb-0 v-1 count"
                                                style="color: #fff;margin-top:10px !important;" id="no_of_student_name"
                                                onclick="new_external_window_print_new(event,'folders/dashboard/student_leave_count.php');">
                                            </h4>
                                        </a>
                                    </div>
                                    <!-- end card-body -->
                                </div>
                            </div>

                        </div>
                        <!--<div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                                    <h4 class="header-title">Application Details</h4>
                                </div>
                                <div class="card-body pt-0">
                                    <div id="dash-campaigns-chart" class="apex-charts" data-colors="#ffbc00,#727cf5,#0acf97"></div>

                                    <div class="row text-center ">
                                        <div class="col-sm-4">
                                            <i class="mdi mdi-file-check widget-icon rounded-circle bg-warning-lighten text-warning"></i>
                                            <h3 class="fw-normal mt-3">
                                                <span>28</span>
                                            </h3>
                                            <p class="text-muted mb-0 "> Applied App</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <i class="mdi mdi-checkbox-marked-circle-outline widget-icon rounded-circle bg-primary-lighten1 text-primary"></i>
                                            <h3 class="fw-normal mt-3">
                                                <span>26</span>
                                            </h3>
                                            <p class="text-muted mb-0 "> Approval</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <i class="mdi mdi-file-sign widget-icon rounded-circle bg-success-lighten text-success"></i>
                                            <h3 class="fw-normal mt-3">
                                                <span>2</span>
                                            </h3>
                                            <p class="text-muted mb-0 ">Rejected</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 full">
                            <div class="card-header py-1 text-center thme-colo">
                                <h3>Canteen Details</h3>
                            </div>
                            <div class="card mb-0">
                                <div class="card-body p-0 text-center">
                                    <div class="row">
                                        <div class="col-md-6 ex">
                                            <h3>Misc. Expenses</h3>
                                        </div>
                                        <div class="col-md-6 ex">
                                            <h3>FEB - 2024</h3>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body p-0">

                                <div class="row g-0">
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="card rounded-0 shadow-none m-0">
                                            <div class="card-body text-center food-det">
                                                <i class="mdi mdi-food-fork-drink text-muted font-24"></i>
                                                <h3><span>230</span></h3>
                                                <p class="text-muted font-15 mb-0">Breakfast </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-lg-4">
                                        <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                            <div class="card-body text-center food-det">
                                                <i class="mdi mdi-food-turkey text-muted font-24"></i>
                                                <h3><span>198</span></h3>
                                                <p class="text-muted font-15 mb-0">Lunch </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-lg-4">
                                        <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                            <div class="card-body text-center food-det">
                                                <i class="mdi mdi-food-outline text-muted font-24"></i>
                                                <h3><span>203</span></h3>
                                                <p class="text-muted font-15 mb-0">Dinner</p>
                                            </div>
                                        </div>
                                    </div>



                                </div> 
                            </div>
                            <div class="card-header py-1 text-center thme-colo">
                                <h3 class="counts">236 <small>Student</small> / 7<small>days</small></h3>
                            </div>
                            <div class="card">
                                <div class="card-body p-0 text-center">
                                    <div class="row">
                                        <div class="col-md-6 ex">
                                            <h3>Misc. Expenses</h3>
                                        </div>
                                        <div class="col-md-6 ex">
                                            <h3>FEB - 2024</h3>
                                        </div>
                                    </div>
                                    <h1 class="mb-3 rupee">11,590 <small>Rs </small></h1>
                                </div>
                            </div>
                        </div>--->


                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="sct">
                            <div class="card">

                                <div class="alert alert-danger new-bgg  rounded-0 mb-1" role="alert">
                                    <i class="uil-folder-heart me-1 h4 align-middle"></i> <b>Holiday</b>
                                </div>

                                <div class="card-body pt-1 notify" id="holiday_list">
                                    <!-- <div class=" align-items-center border border-light rounded p-1 mb-1">
                                        <div class="d-flex align-items-center ">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="uil-calender widget-icon rounded-circle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">No Smoking Day</h5>
                                                
                                            </div>
                                            <p class="mb-0 fw-semibold">March 09, 2024</p>
                                            </div>
                                            
                                        </div>
                                        
                                        
                                        <div class=" align-items-center border border-light rounded p-1 mb-1">
                                        <div class="d-flex align-items-center ">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="uil-calender widget-icon bg-warning-lighten text-warning" rounded-circle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">CISF Raising Day </h5>
                                                
                                            </div>
                                            <p class="mb-0 fw-semibold">March 10, 2024</p>
                                            </div>
                                        
                                        
                                        </div>

                                        <div class=" align-items-center border border-light rounded p-1 mb-1">
                                        <div class="d-flex align-items-center ">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="uil-calender widget-icon rounded-circle bg-success-lighten text-success"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">World Sparrow Day </h5>
                                                
                                            </div>
                                            <p class="mb-0 fw-semibold">March 20, 2024</p>
                                            </div>
                                            </div>

                                        <div class="align-items-center border border-light rounded p-1 mb-1">
                                        <div class="d-flex align-items-center ">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="uil-calender widget-icon rounded-circle bg-info-lighten text-info"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">World Forestry Day</h5>
                                                
                                            </div>
                                            <p class="mb-0 fw-semibold">March 21, 2024</p>
                                            </div>
                                        </div>
                                        <div class=" align-items-center border border-light rounded p-1 mb-1">
                                    <div class="d-flex align-items-center ">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="uil-calender widget-icon rounded-circle bg-danger-lighten text-danger"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">Ordnance Factories Day</h5>
                                                
                                            </div>
                                            <p class="mb-0 fw-semibold">March 18, 2024</p>
                                            </div>
                                        </div> -->

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="sct">
                            <div class="card border-1">

                                <div class="alert alert-warning bg-22 rounded-0 mb-1" role="alert">
                                    <i class="uil-folder-heart me-1 h4 align-middle"></i> <b>Notifications</b>
                                </div>

                                <div class="card-body pt-1 notify" id="notification_list">
                                    <!-- <div class=" align-items-center border border-light rounded p-1 mb-1">
                                        <div class="d-flex align-items-center br-noti">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-information-outline widget-icon rounded-circle"></i>
                                            </div> -->
                                    <!-- <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">Student Details</h5>
                                                
                                            </div>
                                            <p class="mb-0 fw-semibold">20-03-2024</p>
                                            </div>
                                            <div class="noti-des">
                                            <p class="mb-0">Filler text is text that shares some characteristics of a real written</p>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class=" align-items-center border border-light rounded p-1 mb-1">
                                        <div class="d-flex align-items-center br-noti">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-information-outline widget-icon rounded-circle bg-warning-lighten text-warning"></i>
                                            </div>
                                            
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">In Progress Work</h5>
                                                
                                            </div>
                                            <p class="mb-0 fw-semibold">20-03-2024</p>
                                            </div>
                                        <div class="noti-des">
                                            <p class="mb-0">Filler text is text that shares some characteristics of a real written</p>
                                            </div> -->


                                    <!-- </div>

                                        <div class=" align-items-center border border-light rounded p-1 mb-1">
                                        <div class="d-flex align-items-center br-noti">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-information-outline widget-icon rounded-circle bg-danger-lighten text-danger"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">Completed Application</h5>
                                                
                                            </div>
                                            <p class="mb-0 fw-semibold">20-03-2024</p>
                                            </div>
                                        <div class="noti-des">
                                            <p class="mb-0">Filler text is text that shares some characteristics of a real written</p>
                                            </div>
                                        </div>

                                        <div class="align-items-center border border-light rounded p-1">
                                        <div class="d-flex align-items-center br-noti">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-information-outline widget-icon rounded-circle bg-success-lighten text-success"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">Send Application</h5>
                                               
                                            </div>
                                            <p class="mb-0 fw-semibold">20-03-2024</p>
                                            </div>
                                            <div class="noti-des">
                                            <p class="mb-0">Filler text is text that shares some characteristics of a real written</p>
                                            </div>
                                        </div>
                                        <div class=" align-items-center border border-light rounded p-1 mb-1">
                                        <div class="d-flex align-items-center br-noti">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-information-outline widget-icon rounded-circle bg-danger-lighten text-danger"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">Application Status</h5>
                                                
                                            </div>
                                            <p class="mb-0 fw-semibold">20-03-2024</p>
                                            </div>
                                           <div class="noti-des">
                                            <p class="mb-0">Filler text is text that shares some characteristics of a real written</p>
                                            </div>
                                        </div>
                                         <div class=" align-items-center border border-light rounded p-1 mb-1">
                                         <div class="d-flex align-items-center br-noti">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-information-outline widget-icon rounded-circle bg-warning-lighten text-warning"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">In Progress</h5>
                                                
                                            </div>
                                            <p class="mb-0 fw-semibold">20-03-2024</p>
                                            </div>
                                            <div class="noti-des">
                                            <p class="mb-0">Filler text is text that shares some characteristics of a real written</p>
                                            </div>
                                        </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>




    </div>
    <!-- container -->

</div>
<!-- content -->



<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->

</div>
<!-- END wrapper -->