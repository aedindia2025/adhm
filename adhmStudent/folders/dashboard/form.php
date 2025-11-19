

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

    .ui-datepicker-calendar {
        display: none;
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

    #approval_status {
        /* margin-left: 57px; */
        padding: 2px;
        color: orange;
        font-size: 20px;
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


// Get the current month in full textual representation

$date = "2024-03-20"; // Your date in YYYY-MM-DD format

$month = date("m", strtotime($date));
// echo $month;


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
                                    <input class="form-control" id="example-month" type="month" name="month" value="<?php echo date('Y-m'); ?>">
                                    <!-- <input type="date" value="<?php echo $month; ?>" class="form-control form-control-light" > -->
                                    <!-- <input type="text" class="form-control form-control-light" id="dash-daterange">  -->
                                    <!-- <span class="input-group-text thme-colo  border-primary text-white"> -->
                                    <!-- <i class="mdi mdi-calendar-range font-13"></i> -->
                                    <button type="button" id="date_filter" name="date_filter" class="input-group-text thme-colo  border-primary text-white">GO</button>
                                    </span>
                                </div>
                                <!-- <a href="javascript: void(0);" class="btn thme-colo text-white  ms-2">
                                    <i class="mdi mdi-autorenew "></i>
                                </a>
                                <a href="javascript: void(0);" class="btn thme-colo  text-white ms-1">
                                    <i class="mdi mdi-filter-variant"></i>
                                </a> -->
                            </form>
                        </div>
                        <h4 class="page-title">Dashboard &nbsp;<?php echo  $_SESSION['acc_year']; ?></h4>


                    </div>
                </div>
            </div>

            <div class="">
                <div class="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card widget-flat">
                                        <div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
                                            <div class="cd i1">
                                                <i class="ri-checkbox-circle-line widget-icon"></i>
                                            </div>
                                            <h5 class="text-muted  mt-0 vf" title="Number of Customers">Present</h5>
                                            <h3 class="mt-2 mb-0 v-1 count">0</h3>

                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div>
                                <div class="col-sm-6">
                                    <div class="card widget-flat">
                                        <div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
                                            <div class="cd i2">
                                                <i class="ri-close-circle-line widget-icon"></i>
                                            </div>
                                            <h5 class="text-muted  mt-0 vf" title="Number of Customers">Absent</h5>
                                            <h3 class="mt-2 mb-0 v-1 count">0</h3>

                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div>
                                <div class="col-sm-6">
                                    <div class="card widget-flat">
                                        <div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
                                            <div class="cd i3">
                                                <i class="ri-shield-user-line widget-icon"></i>
                                            </div>
                                            <h5 class="text-muted  mt-0 vf" title="Number of Customers">Leave</h5>
                                            <h3 class="mt-2 mb-0 v-1 count">0</h3>

                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div>
                                <div class="col-sm-6">
                                    <div class="card cta-box text-bg-primary">
                                        <div class="card-body red-pad">
                                            <div class="text-center">
                                                <h3 class="m-0  cta-box-title text-reset">Applied Leave</h3>
                                                <h3 class="m-0  cta-box-title text-reset" id="no_of_days"></h3>
                                                <h5 id="from_date"></h5>



                                                <!-- <div id ="approval_status"> -->


                                                <button type="button" classs="btn" style="border: 0px;padding: 5px 9px;color: white; background-color:orange; margin-left:60px;" id="approval_status"></button>
                                            </div>

                                            <!-- </div> -->

                                        </div>
                                        <!-- end card-body -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xl-12">
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
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="sct">
                                <div class="card border-1">

                                    <div class="alert alert-danger new-bgg rounded-0 mb-1" role="alert">
                                        <i class="uil-folder-heart me-1 h4 align-middle"></i> <b>HoliDay</b>
                                    </div>



                                    <div class="card-body pt-1 notify" id="holiday_list">
                                        <!--  <div class=" align-items-center border border-light rounded p-1 mb-1">
										<div class="d-flex align-items-center ">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="uil-calender widget-icon rounded-circle"></i>
                                            </div>

                                            <div id="holiday_list">


                                         
											 </div>
											
                                        </div>
                                    </div>
                                        </div>
                                        </div>
                                    </div></div></div></div> -->


                                        <!-- <div class=" align-items-center border border-light rounded p-1 mb-1">
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

                                    <div class="alert alert-warning bg-22  rounded-0 mb-1" role="alert">
                                        <i class="uil-folder-heart me-1 h4 align-middle"></i><b>Notifications</b>
                                    </div>

                                    <div class="card-body pt-1 notify " id="notification_list">
                                        <!-- <div class=" align-items-center border border-light rounded p-1 mb-1">
										<div class="d-flex align-items-center br-noti">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-information-outline widget-icon rounded-circle"></i>
                                            </div> -->


                                    </div>

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
											</div>
										
										
										</div>

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
                            </div>
                        </div>
                    </div>
                    <!-- content -->



                    <!-- ============================================================== -->
                    <!-- End Page content -->
                    <!-- ============================================================== -->

                </div>
            </div>
            <!-- END wrapper -->
            <script>
                $("#datepicker").datepicker({
                    format: "mm-yyyy",
                    viewMode: "months",
                    minViewMode: "months"
                });
            </script>

            <script>
                // $(function() {
                //       $("#datepicker").datepicker({
                //         dateFormat: "mm",
                //         changeMonth: true,
                //         showButtonPanel: true,
                //         onChangeMonthYear: function(year, month, inst) {
                //           $(this).datepicker('setDate', new Date(year, month - 1, 1));
                //         },
                //         onClose: function(dateText, inst) {
                //           var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                //           $(this).datepicker("setDate", new Date(year, month, 1));
                //         }
                //       });
                //     });
                // // Get the current date
                // var currentDate = new Date();

                // // Get the current month and year
                // var currentMonth = currentDate.getMonth() + 1; // Months are zero-based, so we add 1
                // var currentYear = currentDate.getFullYear();

                // // Display the current month and year
                // var currentDateElement = document.getElementById("currentDate");
                // currentDateElement.innerHTML = "Current Month: " + currentMonth + ", Current Year: " + currentYear;
            </script>