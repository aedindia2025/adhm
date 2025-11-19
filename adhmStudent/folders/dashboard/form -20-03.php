<style>
    .logo-heigt img {
        height: 50px !important;
    }

    .leftside-menu.change-bg {
        background: #00afef;
    }


    li.side-nav-title.cor {
        color: #fff;
    }

    span.logo-sm.logo-small img {
        height: auto;
    }

    .red-pad {
        padding: 14px 21px;
    }

    .i1 i {
        background: unset;
        color: #138b18;
        font-size: 52px;
    }

    .i2 i {
        background: unset;
        color: #f44336;
        font-size: 52px;

    }

    .i3 i {
        background: unset;
        color: #ff9800;
        font-size: 52px;

    }

    .mne i {
        font-size: 47px;
        color: #00afef;
    }

    .psd h5 {
        margin-bottom: 0px;
    }

    .card-body.mne {
        padding: 10px;
    }

    .card.text-bg-secondary1 {
        background: #ffffff;
        border: 1px solid #b6b4ba;
    }

    .card.text-bg-secondary1:hover p {
        color: #fff;
    }

    .card.text-bg-secondary1:hover i {
        color: #fff;
    }

    .card.text-bg-secondary1:hover {
        background: #00afef;
    }

    li.psd {
        padding: 10px;
        border-bottom: 1px dotted #00afef;
    }

    .com-class p {
        font-size: 17px;
        font-weight: 600;
    }

    .i4 i {
        background: #fb7d5b;

    }

    .com-class i {
        padding: 13px;
        border-radius: 50px;
        color: #fff !important;
    }

    .com-class h3 {
        margin-top: 23px;
    }

    .pro-mar {
        margin: 12px;
        background: #02a6e2;
        border: 1px solid #fff;
    }

    .text-start.n-colo p {
        color: #fff;
    }

    .text-start.n-colo h4 {
        text-align: center;
        font-size: 20px;
        text-transform: uppercase;
        margin-bottom: 19px;
        color: #fff;
    }

    .thme-colo {
        background: #00afef;
		border: 0px
    }

    .thme-colo:hover {
        background: #00afef;
    }

    .widget-flat i.widget-icon {
        display: inline-block;
    }

    .vf {
        margin-top: 13px !important;
        font-size: 15px;
        font-weight: 500;
        color: black !important;
    }

    h3.m-0.crlo.text-center {
        font-size: 20px;
        color: #fff;
    }

    .ff p {
        font-size: 16px;
    }

    .ff h5 {
        font-size: 20px;
        margin-top: 17px;
    }

    .cta-box .cta-box-title {
        font-size: 20px;
        line-height: 30px;
        border-bottom: 1px dotted;
        padding-bottom: 7px;
    }

    .v-1 {
        color: black;
    }

    .cta-box {
        background-image: url(../images/bg-pattern.png);
        background-size: cover;
        background-color: #00afef !important;
    }

    .full h3 {
        color: #fff;
        font-size: 17px;
        margin: 4px;
    }

    .count {
        font-size: 32px;
    }
	.mne p {
    color: #000;
}
</style>

<?php 



?>
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
                                    <input type="text" class="form-control form-control-light" id="dash-daterange">
                                    <span class="input-group-text thme-colo  border-primary text-white">
                                        <i class="mdi mdi-calendar-range font-13"></i>
                                    </span>
                                </div>
                                <a href="javascript: void(0);" class="btn thme-colo text-white  ms-2">
                                    <i class="mdi mdi-autorenew "></i>
                                </a>
                                <a href="javascript: void(0);" class="btn thme-colo  text-white ms-1">
                                    <i class="mdi mdi-filter-variant"></i>
                                </a>
                            </form>
                        </div>
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card widget-flat">
                                <div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
                                    <div class="cd i1">
                                        <i class="ri-checkbox-circle-line widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted  mt-0 vf" title="Number of Customers">Present</h5>
                                    <h3 class="mt-2 mb-0 v-1 count">25</h3>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>
                        <div class="col-sm-3">
                            <div class="card widget-flat">
                                <div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
                                    <div class="cd i2">
                                        <i class="ri-close-circle-line widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted  mt-0 vf" title="Number of Customers">Absent</h5>
                                    <h3 class="mt-2 mb-0 v-1 count">02</h3>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>
                        <div class="col-sm-3">
                            <div class="card widget-flat">
                                <div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
                                    <div class="cd i3">
                                        <i class="ri-shield-user-line widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted  mt-0 vf" title="Number of Customers">Leave</h5>
                                    <h3 class="mt-2 mb-0 v-1 count">03</h3>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>
                        <div class="col-sm-3">
                            <div class="card cta-box text-bg-primary">
                                <div class="card-body red-pad">
                                    <div class="text-center">
                                        <h3 class="m-0  cta-box-title text-reset">Applied Leave</h3>
                                        <div class="ff">
                                            <h5 id="leave_date">20-03-2024</h5>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm mt-2">Pending</button>
                                    </div>
                                </div>
                                <!-- end card-body -->
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Over All Performence</h4>
                                    <div dir="ltr">
                                        <div id="average-sales" class="apex-charts mb-4 mt-2" data-colors="#299fcb,#e3e9ee"></div>
                                    </div>
                                    <div class="chart-widget-list">
                                        <p>
                                            <i class="mdi mdi-square" style="color:#299fcb;"></i> Pass
                                            <!-- <span class="float-end">$300.56</span> -->
                                        </p>
                                        <p>
                                            <i class="mdi mdi-square text-danger"></i> Fail
                                            <!-- <span class="float-end">$135.18</span> -->
                                        </p>

                                    </div>
                                </div>
                                <!-- end card body-->
                            </div>
                            <!-- end card -->
                        </div>

                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Exam Details</h4>
                                    <div dir="ltr">
                                        <div id="high-performing-product" class="apex-charts" data-colors="#299fcb,#91a6bd40"></div>
                                    </div>
                                </div>
                                <!-- end card body-->
                            </div>
                            <!-- end card -->
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 full">
                    <div class="card-header py-1 text-center thme-colo">
                        <h3>HOLIDAY</h3>
                    </div>
                    <div class="card ">
                        <ul class="list-unstyled mb-0" id="holiday_list">
                            <!-- <li class=" mb-1  psd">
                                <p class="text-muted mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 01-01-2024
                                </p>
                                <h5 class="text-primary">New Year</h5>
                            </li>
                            <li class=" mb-1  psd">
                                <p class="text-muted mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 15-01-2024
                                </p>
                                <h5 class="link-secondary">Pongal</h5>
                            </li>
                            <li class="mb-1  psd">
                                <p class="text-muted mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 17-01-2024
                                </p>
                                <h5 class="link-danger">Thiruvalluvar Day</h5>
                            </li>
                            <li class=" psd">
                                <p class="text-muted mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 26-01-2024
                                </p>
                                <h5 class="link-warning">Republic day</h5>
                            </li>
                            <li class="mb-1  psd">
                                <p class="text-muted mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 01-05-2024
                                </p>
                                <h5 class="link-success">Labour Day</h5>
                            </li>
                            <li class="mb-1  psd">
                                <p class="text-muted mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 07-09-2024
                                </p>
                                <h5 class="link-danger">Ganesh Chaturthi</h5>
                            </li>
                            <li class="mb-1  psd">
                                <p class="text-muted mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 12-10-2024
                                </p>
                                <h5 class="link-secondary">Ayudha Puja</h5>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-lg-2 col-sm-6">
                    <div class="card text-bg-secondary1">
                    <a href="index.php?file=attendance/list">
                        <div class="card-body mne text-center">
                            <i class=" uil-presentation-check"></i>
                            <p class="mb-0">Attendance</p>
                        </div> end card-body -->
                    <!-- </a> -->
                    <!-- </div> end card -->
                <!-- </div> end col -->

                <!-- <div class="col-lg-2 col-sm-6">
                    <div class="card text-bg-secondary1">
                        <a href="index.php?file=accomodation/list">
                            <div class="card-body mne text-center">
                                <i class=" uil-building"></i>
                                <p class="mb-0">Accommodation </p> -->
                            <!-- </div> end card-body -->
                        <!-- </a> -->
                    <!-- </div> end card -->
                <!-- </div> end col -->

                <!-- <div class="col-lg-2 col-sm-6">
                    <div class="card text-bg-secondary1">
                    <a href="index.php?file=mark_sheet/list">
                        <div class="card-body mne text-center">
                            <i class=" uil-newspaper"></i>
                            <p class="mb-0"> Mark Sheet</p>
                        </div> end card-body -->
                    <!-- </a> -->
                    <!-- </div> end card -->
                <!-- </div> end col -->

                <!-- <div class="col-lg-2 col-sm-6">
                    <div class="card text-bg-secondary1">
                    <a href="index.php?file=feedback_form/list">
                        <div class="card-body mne text-center">
                            <i class=" uil-feedback"></i>
                            <p class="mb-0">Feed Back</p>
                        </div> end card-body -->
                    <!-- </a>
                    </div> end card -->
                <!-- </div> end col -->

                <!-- <div class="col-lg-2 col-sm-6">
                    <div class="card text-bg-secondary1">
                    <a href="index.php?file=grievance_category/list">
                        <div class="card-body mne text-center">
                            <i class=" uil-file-alt"></i>
                            <p class="mb-0">Grievance</p> -->
                        <!-- </div> end card-body -->
                    <!-- </a> -->
                    <!-- </div> end card -->
                <!-- </div> end col -->

                <!-- <div class="col-lg-2 col-sm-6">
                    <div class="card text-bg-secondary1 ">
                    <a href="index.php?file=carrier_guidance/list">
                        <div class="card-body mne text-center">
                            <i class=" uil-books"></i>
                            <p class="mb-0">Carrier Guidance </p> -->
                        <!-- </div> end card-body -->
                    <!-- </a> -->
                    <!-- </div> end card -->
                <!-- </div> end col -->
            <!-- </div> -->



        </div>
        <!-- container -->

    </div>
    <!-- content -->



    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

</div>
<!-- END wrapper -->