<?php
$academic_year = $_SESSION['academic_year'];
$hostel_unique_id = $_SESSION['hostel_id'];
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
                                    <input type="text" name="filter_date" class="filter_date form-control form-control-light" id="dash-daterange">
                                    <span class="input-group-text thme-colo  border-primary text-white">
                                        <i class="mdi mdi-calendar-range font-13"></i>
                                    </span>
                                </div>

                                <input type="text" name="academic_year" class="academic_year form-control" id="academic_year" value="<?= $academic_year;?>">

                                <input type="text" name="hostel_unique_id" class="hostel_unique_id form-control" id="hostel_unique_id" value="<?= $hostel_unique_id;?>">


                                <a href="javascript: void(0);" class="btn thme-colo text-white  ms-2">
                                    <i class="mdi mdi-autorenew "></i>
                                </a>
                                <a href="javascript: void(0);" class="btn thme-colo  text-white ms-1">
                                    <i class="mdi mdi-filter-variant"></i>
                                </a>
                            </form>
                        </div>
                        <h4 class="page-title">Application</h4>
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
				<p class="mb-0">50</p>
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
				<h5 class="mt-0 mb-1">Acceptance</h5>
				<p class="mb-0">40</p>
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
				<p class="mb-0">35</p>
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
				<p class="mb-0">05</p>
				</div>
				</div>
				</div>
				</div>
			</div>
			</div>
			 <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                                           <div class="page-title-right">
                            <form class="d-flex">
                               
                                <a href="javascript: void(0);" class="btn thme-colo text-white  ms-2">
                                    <i class="mdi mdi-autorenew "></i>
                                </a>
                              
                            </form>
                        </div>
                        <h4 class="page-title">Registration</h4>
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
				<p class="mb-0">75</p>
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
				<p class="mb-0">50</p>
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
				<p class="mb-0">5</p>
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
				<p class="mb-0">20</p>
				</div>
				</div>
				</div>
				</div>
			</div>
			
			</div>


						 <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                                           <div class="page-title-right">
                            <form class="d-flex">
                               
                                <a href="javascript: void(0);" class="btn thme-colo text-white  ms-2">
                                    <i class="mdi mdi-autorenew "></i>
                                </a>
                               
                            </form>
                        </div>
                        <h4 class="page-title">Attendance</h4>
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
                                            <h5>20-03-2024</h5>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm mt-2">Pending</button>
                                    </div>
                                </div>
                                <!-- end card-body -->
                            </div>
                        </div>
                       
						
						<div class="col-md-6">
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
                                                    <span>50</span>
                                                </h3>
                                                <p class="text-muted mb-0 "> Applied App</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <i class="mdi mdi-checkbox-marked-circle-outline widget-icon rounded-circle bg-primary-lighten1 text-primary"></i>
                                                <h3 class="fw-normal mt-3">
                                                    <span>35</span>
                                                </h3>
                                                <p class="text-muted mb-0 "> Approval</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <i class="mdi mdi-file-sign widget-icon rounded-circle bg-success-lighten text-success"></i>
                                                <h3 class="fw-normal mt-3">
                                                    <span>05</span>
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
                    </div><div class="card mb-0">
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
                                                        <h3><span>21</span></h3>
                                                        <p class="text-muted font-15 mb-0">Breakfast  </p>
                                                    </div>
                                                </div>
                                            </div>
                
                                            <div class="col-sm-6 col-lg-4">
                                                <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                                    <div class="card-body text-center food-det">
                                                        <i class="mdi mdi-food-turkey text-muted font-24"></i>
                                                        <h3><span>23</span></h3>
                                                        <p class="text-muted font-15 mb-0">Lunch  </p>
                                                    </div>
                                                </div>
                                            </div>
                
                                            <div class="col-sm-6 col-lg-4">
                                                <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                                    <div class="card-body text-center food-det">
                                                        <i class="mdi mdi-food-outline text-muted font-24"></i>
                                                        <h3><span>20</span></h3>
                                                        <p class="text-muted font-15 mb-0">Dinner</p>
                                                    </div>
                                                </div>
                                            </div>
                
                                           
                
                                        </div> <!-- end row -->
                                    </div>
									 <div class="card-header py-1 text-center thme-colo">
                        <h3 class="counts">25 <small>Student</small> / 7<small>days</small></h3>
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
					 <h1 class="mb-3 rupee">2,490 <small>Rs </small></h1>
					 </div>
					 </div>
                            </div><!-- end col-->
                       
                        
                    </div>
                </div>
				<div class="col-md-3">
                <div class="col-sm-12 full">
                    <div class="card-header py-1 text-center thme-colo">
                        <h3>HoliDay</h3>
                    </div>
                    <div class="card ">
                        <ul class="list-unstyled mb-0" id="holiday_list">
                             
                            <!-- <li class=" mb-1  psd">
                                <p class=" mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 01-01-2024
                                </p>
                                <h5 class="text-primary">New Year</h5>
                            </li>
                            <li class=" mb-1  psd">
                                <p class=" mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 15-01-2024
                                </p>
                                <h5 class="link-secondary">Pongal</h5>
                            </li>
                            <li class="mb-1  psd">
                                <p class=" mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 17-01-2024
                                </p>
                                <h5 class="link-danger">Thiruvalluvar Day</h5>
                            </li>
                            <li class=" psd">
                                <p class=" mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 26-01-2024
                                </p>
                                <h5 class="link-warning">Republic day</h5>
                            </li>
                            <li class="mb-1  psd">
                                <p class=" mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 01-05-2024
                                </p>
                                <h5 class="link-success">Labour Day</h5>
                            </li>
                            <li class="mb-1  psd">
                                <p class="mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 07-09-2024
                                </p>
                                <h5 class="link-danger">Ganesh Chaturthi</h5>
                            </li>
                            <li class="mb-1  psd">
                                <p class=" mb-1 font-13">
                                    <i class="mdi mdi-calendar"></i> 12-10-2024
                                </p>
                                <h5 class="link-secondary">Ayudha Puja</h5>
                            </li> -->
                        </ul>
                    </div>
                </div>
							<div class="col-md-12">
										<div class="sct">
									<div class="card">
                                   
                                    <div class="alert alert-warning border-0 rounded-0 mb-1" role="alert">
                                        <i class="uil-folder-heart me-1 h4 align-middle"></i> <b>Notifications</b> 
                                    </div>

                                    <div class="card-body pt-1 notify">
                                        <div class="d-flex align-items-center border border-light rounded p-1 mb-1">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-account-group widget-icon rounded-circle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">Student Details</h5>
                                                
                                            </div>
                                            <a href="javasript:void(0)" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Info" data-bs-original-title="Info"><i class="mdi mdi-information-outline h4  my-0"></i></a>
                                        </div>
                                        
                                        <div class="d-flex align-items-center border border-light rounded p-1 mb-1">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-progress-pencil widget-icon rounded-circle bg-warning-lighten text-warning"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">In Progress Work</h5>
                                                
                                            </div>
                                            <a href="javasript:void(0)" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Info" data-bs-original-title="Info"><i class="mdi mdi-information-outline h4  my-0"></i></a>
                                        </div>

                                        <div class="d-flex align-items-center border border-light rounded p-1 mb-1">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-checkbox-marked-circle-outline widget-icon rounded-circle bg-danger-lighten text-danger"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">Completed Application</h5>
                                                
                                            </div>
                                            <a href="javasript:void(0)" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Info" data-bs-original-title="Info"><i class="mdi mdi-information-outline h4  my-0"></i></a>
                                        </div>

                                        <div class="d-flex align-items-center border border-light rounded p-1">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-send widget-icon rounded-circle bg-success-lighten text-success"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">Send Application</h5>
                                               
                                            </div>
                                            <a href="javasript:void(0)" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Info" data-bs-original-title="Info"><i class="mdi mdi-information-outline h4  my-0"></i></a>
                                        </div>
										<div class="d-flex align-items-center border border-light rounded p-1 mb-1">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-checkbox-marked-circle-outline widget-icon rounded-circle bg-danger-lighten text-danger"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">Application Status</h5>
                                                
                                            </div>
                                            <a href="javasript:void(0)" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Info" data-bs-original-title="Info"><i class="mdi mdi-information-outline h4  my-0"></i></a>
                                        </div>
										 <div class="d-flex align-items-center border border-light rounded p-1 mb-1">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="mdi mdi-progress-pencil widget-icon rounded-circle bg-warning-lighten text-warning"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold my-0">In Progress</h5>
                                                
                                            </div>
                                            <a href="javasript:void(0)" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Info" data-bs-original-title="Info"><i class="mdi mdi-information-outline h4  my-0"></i></a>
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