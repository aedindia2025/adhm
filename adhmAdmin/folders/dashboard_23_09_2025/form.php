<style>
    .chart-widget-list {
        display: block;
        overflow-y: scroll;
        height: 257px;
    }

    .card.widget-flat.p1 {
        background: #f1f2fe;
    }

    .card.widget-flat.p2 {
        background: #fff9ef;
    }

    .card.widget-flat.p3 {
        background: #e7faf5;
    }

    .card.widget-flat.p4 {
        background: #ffeff2;
    }

    .chart-widget-list span.float-end {
        padding-right: 24px;
    }

    a.view {
        color: #fff;
        text-align: center;
        border-radius: 10px 10px 0px 0px;
        width: 51%;
        margin: 0px auto;
        font-size: 13px;
        padding: 4px;
    }

    .card.tilebox-one h6 {
        font-size: 15px !important;
        font-weight: 400;
    }

    .card.tilebox-one h2 {
        font-size: 22px;
        font-weight: 600;
        color: #565252;
    }

    .side-nav .side-nav-link i {
        width: 37px !important;
    }

    .bb-1 i {
        color: #6973e3;
    }

    .bb-2 i {
        color: #646c74;
    }

    .bb-3 i {
        color: #0acf97;
    }

    .bb-4 i {
        color: #fa5c7c;
    }

    .bb-5 i {
        color: #ffc35a;
    }

    .bb-6 i {
        color: #39afd1;
    }

    .bb-7 i {
        color: #8BC34A;
    }

    .bb-8 i {
        color: #009688;
    }

    .hh-1 {
        color: #6973e2;
    }

    .hh-2 {
        color: #646c74;
    }

    .hh-3 {
        color: #0acf97;
    }

    .hh-4 {
        color: #fa5c7c;
    }

    .hh-5 {
        color: #ffa300;
    }

    .hh-6 {
        color: #39afd1;
    }

    .hh-7 {
        color: #8BC34A;
    }

    .hh-8 {
        color: #009688;
    }

    .bb-1 {
        background: #eeeffc;
        border: 1px solid #6973e3;
    }

    .bb-2 {
        background: #f0f0f0;
        border: 1px solid #646c74;
    }

    .bb-3 {
        background: #ddfcf3;
        border: 1px solid #0acf97;
    }

    .bb-4 {
        background: #ffedf1;
        border: 1px solid #fa5c7c;
    }

    .bb-5 {
        background: #f1b8551c;
        border: 1px solid #ffa300;
    }

    .bb-6 {
        background: #39afd11c;
        border: 1px solid #39afd1;
    }

    .bb-7 {
        background: #7db0431c;
        border: 1px solid #8BC34A;
    }

    .bb-8 {
        background: #0096881c;
        border: 1px solid #8BC34A;
    }

    .bb-2 a.view {
        background: #646c74;
    }

    .bb-1 a.view {
        background: #6973e3;
    }

    .bb-3 a.view {
        background: #0acf97;
    }

    .bb-4 a.view {
        background: #fa5c7c;
    }

    .bb-5 a.view {
        background: #ffc35a;
    }

    .bb-6 a.view {
        background: #39afd1;
    }

    .bb-7 a.view {
        background: #8BC34A;
    }

    .bb-8 a.view {
        background: #009688;
    }

    .card.tilebox-one .card-body {
        padding-bottom: 5px;
    }

    .ap-1 i {
        color: #03A9F4;
    }

    .ap-1 {
        background: #effaff;
    }

    .ap-2 i {
        color: #FFC107;
    }

    .ap-2 {
        background: #fffbef;
    }

    .ap-3 i {
        color: #149019;
    }

    .ap-3 {
        background: #effff0;
    }

    .ap-4 i {
        color: #714ab7;
    }

    .ap-4 {
        background: #fdf2ff;
    }

    .psd p {
        color: #000;
        font-weight: 500;
    }

    .c-v p {
        font-size: 23px;
        font-weight: 500;
        color: #000;
    }

    .ap-5 i {
        color: #F44336;
    }

    .ap-5 {
        background: #fff1ef;
    }

    .ap-6 i {
        color: #009688;
    }

    .ap-6 {
        background: #ecfffd;
    }

    .ap-7 i {
        color: #E91E63;
    }

    .ap-7 {
        background: #fbecf1;
    }

    .bg-primary-lighten1 i {
        font-size: 35px;
    }
</style>
<?php
$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option($hostel_name_list, "Select Hostel", $hostel_name);

$hostel_type_list = hostel_type_name();
$hostel_type_list = select_option($hostel_type_list, "Select Hostel", $hostel_type);

$hostel_gender_list = hostel_gender_name();
$hostel_gender_list = select_option($hostel_gender_list, "Select Hostel", $hostel_gender);
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include ApexCharts -->
<!-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> -->
<!-- Include the Material Design Icons CSS -->
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">

<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->

<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->



<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <form class="d-flex">
                                <div class="input-group">
                                    <!-- <input type="text" class="form-control form-control-light" id="dash-daterange">
                                                <span class="input-group-text bg-primary border-primary text-white">
                                                    <i class="mdi mdi-calendar-range font-13"></i>
                                                </span> -->
                                </div>
                                <!-- <a href="javascript: void(0);" class="btn btn-primary ms-2">
                                                <i class="mdi mdi-autorenew"></i>
                                            </a>
                                            <a href="javascript: void(0);" class="btn btn-primary ms-1">
                                                <i class="mdi mdi-filter-variant"></i>
                                            </a> -->
                            </form>
                        </div>
                        <h4 class="page-title">Dashboard</h4>

                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fm">
                    <label class="form-label" for="example-select">District Name</label>
                    <select class="select2 form-control" id="district_name" name="district_name" onchange="get_taluk()">
                        <?php echo $district_name_list; ?>
                    </select>
                </div>
                <div class="col-md-3 fm">
                    <label class="form-label" for="example-select">Taluk Name</label>
                    <select class="select2 form-control" id="taluk_name" name="taluk_name" onchange="get_hostel()">
                        <?php echo $taluk_name_list; ?>
                    </select>
                </div>
                <div class="col-md-3 fm">
                    <label class="form-label" for="example-select">Hostel Name</label>
                    <select class="select2 form-control" id="hostel_name" name="hostel_name">
                        <?php echo $hostel_name_list ?>
                    </select>
                </div>
                <div class="col-md-3 fm">
                    <label class="form-label" for="example-select">Gender type</label>
                    <select class="select2 form-control" id="gender_type" name="gender_type" multiple>
                        <?php echo $hostel_gender_list ?>
                    </select>
                </div>
                <div class="col-md-3 fm mt-3">
                    <label class="form-label" for="example-select">Hostel type</label>
                    <select class="select2 form-control" id="hostel_type" name="hostel_type" multiple>
                        <?php echo $hostel_type_list ?>
                    </select>
                </div>
                <div class="col-md-2 fm mt-3">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <button type="button" class="btn btn-primary mt-3"
                                onclick="get_application_count()">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Student Information</h4>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <div class="row mb-3">
                <div class="col-md-4 col-lg-4 mb-mb">
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
                                    <h5 class="mt-0 mb-1">Applications Received</h5>
                                    <p class="mb-0" id="appl_cnt"
                                        onclick="new_external_window_print(event,'folders/dashboard/print.php','applied');">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 mb-mb">
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
                                    <h5 class="mt-0 mb-1">Processed by Warden</h5>
                                    <p class="mb-0" id="accp_cnt"
                                        onclick="new_external_window_print(event,'folders/dashboard/print.php','acceptance');">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 mb-mb">
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
                <div class="col-md-4 col-lg-4 mb-mb mt-3">
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
                <div class="col-md-4 col-lg-4 mb-mb mt-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-5">
                                            <i class="mdi mdi-logout-variant"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Drop/Discontinued</h5>
                                    <p class="mb-0" id="dropout_cnt"
                                        onclick="new_external_window_print(event,'folders/dashboard/print.php','dropout');">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-4">
                    <div class="card tilebox-one bb-1">
                        <div class="card-body">
                            <i class="uil uil-users-alt float-end"></i>
                            <!-- <h6 class=" mt-0 hh-1">Total Students</h6> -->
                            <h6 class=" text-reset mt-0" title="Customers">Total Student</h6>
                            <h3 class="mt-3 mb-3 text-reset" id="total_students">
                            </h3>

                            <!-- <h2 class="my-2" id="active-users-count">1,45,000</h2> -->

                        </div> <!-- end card-body-->
                        <a href="javascript:void(0)" class="view"
                            onclick="new_external_window_print_new(event,'folders/dashboard/total_students.php');">Read
                            More</a>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card tilebox-one bb-2">
                        <div class="card-body">
                            <i class=" uil-building float-end"></i>
                            <h6 class=" mt-0 hh-2">Total Hostels </h6>

                            <h2 class="mt-3 mb-3 text-reset" id="total_hostel">
                            </h2>
                            <!-- <h2 class="my-2" id="active-users-count">1,383</h2> -->

                        </div>
                        <!-- end card-body-->
                        <a href="javascript:void(0)"
                            onclick="new_external_window_print_new(event,'folders/dashboard/total_hostel.php');"
                            class="view">Read More</a>
                    </div>
                </div> <!-- end col-->

                <div class="col-sm-4">
                    <div class="card tilebox-one bb-5">
                        <div class="card-body">
                            <i class=" uil-building float-end"></i>
                            <h6 class=" mt-0 hh-5">Hostel Vaccancy</h6>
                            <h2 class="my-2 mt-3 mb-3" id="total_hostel_vaccancy"></h2>

                        </div> <!-- end card-body-->
                        <a href="javascript:void(0)"
                            onclick="new_external_window_print_new(event,'folders/dashboard/hostel_vaccancy.php');"
                            class="view">Read More</a>
                    </div>
                </div> <!-- end col-->
            </div> <!-- end col-->

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Staff Information</h4>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>

            <div class="row mb-3">
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-1">
                                            <i class="mdi mdi-account "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Warden</h5>
                                    <p class="mb-0" id="warden_cnt">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-1">
                                            <i class="mdi mdi-account-outline "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Warden Incharge</h5>
                                    <p class="mb-0" id="warden_inc_cnt">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-2">
                                            <i class="mdi mdi-fire "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Cook</h5>
                                    <p class="mb-0" id="cook_cnt">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-2">
                                            <i class="mdi mdi-pizza"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Cook Deputation</h5>
                                    <p class="mb-0" id="cook_dep_cnt">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-3">
                                            <i class="mdi mdi-security "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Watchman</h5>
                                    <p class="mb-0" id="watchman_cnt">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-3">
                                            <i class="mdi mdi-shield-half-full"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Watchman Deputation</h5>
                                    <p class="mb-0" id="watchman_dep_cnt">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-4">
                                            <i class="mdi mdi-washing-machine "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Sweeper</h5>
                                    <p class="mb-0" id="sweeper_cnt">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-4">
                                            <i class="mdi mdi-home-outline "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Sweeper Deputation</h5>
                                    <p class="mb-0" id="sweeper_dep_cnt">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="card tilebox-one bb-3">
                        <div class="card-body">
                            <i class=" uil-location float-end"></i>
                            <h6 class=" mt-0 hh-3">Total Staffs</h6>
                            <h2 class="mt-3 mb-3 text-reset" id="total_staff"></h2>

                        </div>
                        <a href="javascript:void(0)"
                            onclick="new_external_window_print_new(event,'folders/dashboard/total_staffs.php');"
                            class="view">Read More</a>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card tilebox-one bb-6">
                        <div class="card-body">
                            <i class=" uil-building float-end"></i>
                            <h6 class=" mt-0 hh-2">Total Active Hostel</h6>

                            <h2 class="mt-3 mb-3 text-reset" id="total_hostel_1">
                            </h2>
                        </div>
                        <a href="javascript:void(0)"
                            onclick="new_external_window_print_new(event,'folders/dashboard/total_hostel.php');"
                            class="view">Read More</a>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Attandance</h4>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Attendance</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <!-- Sub Heading -->
                            <h5 class="mb-3">Student Attandance</h5>

                            <!-- Filter Row -->
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="from_date" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="std_att_from_date" name="from_date">
                                </div>
                                <div class="col-md-3">
                                    <label for="to_date" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="std_att_to_date" name="to_date">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary w-100" id="std_att"
                                        onclick="get_students_attendance()">
                                        Go
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-4 col-lg-3 mb-mb mb-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-1">
                                                <i class="mdi mdi-ticket-account"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 c-v">
                                        <h5 class="mt-0 mb-1">Approved Count</h5>
                                        <p class="mb-0" id="std_app_count"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 mb-mb mb-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-2">
                                                <i class="mdi mdi-marker-check"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 c-v">
                                        <h5 class="mt-0 mb-1">Online Hostel Approve</h5>
                                        <p class="mb-0" id="warden_inc_cnt">2365
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 mb-mb mb-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-3">
                                                <i class="mdi mdi-timer-sand-full"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 c-v">
                                        <h5 class="mt-0 mb-1">Online Hostel Attendance</h5>
                                        <p class="mb-0" id="cook_cnt">2849
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 mb-mb mb-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-4">
                                                <i class="mdi mdi-timer-sand-empty"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 c-v">
                                        <h5 class="mt-0 mb-1">Zero Attendance</h5>
                                        <p class="mb-0" id="cook_dep_cnt">49
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <!-- Sub Heading -->
                            <h5 class="mb-3">Staff Attandance</h5>

                            <!-- Filter Row -->
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="from_date" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="stf_att_from_date" name="from_date">
                                </div>
                                <div class="col-md-3">
                                    <label for="to_date" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="stf_att_to_date" name="to_date">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary w-100" id="stf_att"
                                        onclick="get_staff_attendance()">
                                        Go
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 col-lg-3 mb-mb mb-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-5">
                                                <i class="mdi mdi-account-circle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 c-v">
                                        <h5 class="mt-0 mb-1">Approved Count</h5>
                                        <p class="mb-0" id="stf_app_count"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 mb-mb mb-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-6">
                                                <i class="mdi mdi-bookmark-check"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 c-v">
                                        <h5 class="mt-0 mb-1">Online Hostel Approve</h5>
                                        <p class="mb-0" id="warden_inc_cnt">3456
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 mb-mb mb-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-7">
                                                <i class="mdi mdi-chart-histogram"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 c-v">
                                        <h5 class="mt-0 mb-1">Online Hostel Attendance</h5>
                                        <p class="mb-0" id="cook_cnt">1652
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 mb-mb mb-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-1">
                                                <i class="mdi mdi-calendar-text"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 c-v">
                                        <h5 class="mt-0 mb-1">Zero Attendance</h5>
                                        <p class="mb-0" id="cook_dep_cnt">234
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Leave Details</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="card tilebox-one bb-7">
                        <div class="card-body">
                            <i class=" uil-location float-end"></i>
                            <h6 class=" mt-0 hh-7">Student Leave Application</h6>
                            <h2 class="mt-3 mb-3 text-reset" id="no_of_student_name"></h2>

                        </div>
                        <a href="javascript:void(0)"
                            onclick="new_external_window_print_new(event,'folders/dashboard/student_leave.php');"
                            class="view">Read More</a>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card tilebox-one bb-8">
                        <div class="card-body">
                            <i class=" uil-building float-end"></i>
                            <h6 class=" mt-0 hh-8">Staff Leave Application</h6>

                            <h2 class="mt-3 mb-3 text-reset" id="staff_name"></h2>
                        </div>
                        <a href="javascript:void(0)"
                            onclick="new_external_window_print_new(event,'folders/dashboard/staff_leave.php');"
                            class="view">Read More</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Biometric</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="card tilebox-one bb-2">
                        <div class="card-body">
                            <i class=" uil-location float-end"></i>
                            <h6 class=" mt-0 hh-2">DADWO Approved</h6>
                            <h2 class="mt-3 mb-2 text-reset" id="students_approved"></h2>

                        </div>
                        <a href="javascript:void(0)" class="view">Read More</a>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card tilebox-one bb-1">
                        <div class="card-body">
                            <i class=" uil-building float-end"></i>
                            <h6 class=" mt-0 hh-1">Pushed to device</h6>

                            <h2 class="mt-3 mb-2 text-reset" id="std_pushed"></h2>
                        </div>
                        <a href="javascript:void(0)" class="view">Read More</a>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-3">
                                            <i class="mdi mdi-face"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Face Registered</h5>
                                    <p class="mb-0" id="std_face_reg">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-4">
                                            <i class="mdi mdi-account-outline "></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Face Not Registered</h5>
                                    <p class="mb-0" id="std_face_not_reg">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-5">
                                            <i class="mdi mdi-fingerprint"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Finger Registered</h5>
                                    <p class="mb-0" id="std_finger_reg">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 mb-mb mb-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-lighten1 text-primary rounded ap-6">
                                            <i class="mdi mdi-minus-circle-outline"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 c-v">
                                    <h5 class="mt-0 mb-1">Finger Not Registered</h5>
                                    <p class="mb-0" id="std_finger_not_reg">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <script>document.write(new Date().getFullYear())</script> © Adi Dravidar Welfare Department -
                        Managed by <a href="https://aedindia.com/">Ascent e Digit Solutions </a>
                    </div>
                    <div class="col-md-4">
                        <div class="text-md-end footer-links d-none d-md-block">

                            <a href="javascript: void(0);">Support</a>
                            <a href="javascript: void(0);">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

</div>
<!-- END wrapper -->