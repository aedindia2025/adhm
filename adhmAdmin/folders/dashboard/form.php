
<?php
$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option($hostel_name_list, "Select Hostel", $hostel_name);

$hostel_type_list = hostel_type_name();
$hostel_type_list = select_option($hostel_type_list, "Select Hostel Type", $hostel_type);

$hostel_gender_list = hostel_gender_name();
$hostel_gender_list = select_option($hostel_gender_list, "Select Gender", $hostel_gender);
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="folders/dashboard/style-dash.css">


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
                                </div>
                            </form>
                        </div>
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>

            <div class="row m-2 filter_style">
                <div class="col fm">
                    <label class="form-label" for="example-select">District Name</label>
                    <select class="select2 form-control" id="district_name" name="district_name" onchange="get_taluk()">
                        <?php echo $district_name_list; ?>
                    </select>
                </div>
                <div class="col fm">
                    <label class="form-label" for="example-select">Taluk Name</label>
                    <select class="select2 form-control" id="taluk_name" name="taluk_name" onchange="get_hostel()">
                        <?php echo $taluk_name_list; ?>
                    </select>
                </div>
                <div class="col fm">
                    <label class="form-label" for="example-select">Hostel Name</label>
                    <select class="select2 form-control" id="hostel_name" name="hostel_name">
                        <?php echo $hostel_name_list ?>
                    </select>
                </div>
                <div class="col fm">
                    <label class="form-label" for="example-select">Gender type</label>
                    <select class="select2 form-control" id="gender_type" name="gender_type" >
                        <?php echo $hostel_gender_list ?>
                    </select>
                </div>
                <div class="col fm">
                    <label class="form-label" for="example-select">Hostel type</label>
                    <select class="select2 form-control" id="hostel_type" name="hostel_type" >
                        <?php echo $hostel_type_list ?>
                    </select>
                </div>
                <div class="col fm ">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <button type="button" class="btn btn-primary mt-3"
                                onclick="get_application_count()">Filter</button>
                        </form>
                    </div>
                </div>
            </div>

        
            <div class="student-info-section">
  <h4 class="section-title">Student Information</h4>

  <!-- Top Cards (All in one row) -->
  <div class="dashboard-cards-stu-info single-row">
    <div class="stat-card">
      <div class="icon icon-blue"><i class="mdi mdi-file-document"></i></div>
      <h3 id="appl_cnt">104813</h3>
      <p>Applications Received</p>
    </div>

    <div class="stat-card">
      <div class="icon icon-purple"><i class="mdi mdi-account-cog"></i></div>
      <h3 id="accp_cnt">92289</h3>
      <p>Processed by Warden</p>
    </div>

    <div class="stat-card">
      <div class="icon icon-green"><i class="mdi mdi-check-decagram"></i></div>
      <h3 id="appr_cnt">73701</h3>
      <p>Approval</p>
    </div>

    <div class="stat-card">
      <div class="icon icon-red"><i class="mdi mdi-close-octagon"></i></div>
      <h3 id="rej_cnt">1972</h3>
      <p>Rejected</p>
    </div>

    <div class="stat-card">
      <div class="icon icon-orange"><i class="mdi mdi-account-off"></i></div>
      <h3 id="dropout_cnt">1523</h3>
      <p>Drop / Discontinued</p>
    </div>
  </div>

  <!-- Bottom Summary -->
  <div class="info-summary mt-4">
    <div class="col">
      <span>Total Students</span>
      <h4 id="total_students">73701</h4>
      <p>Read More</p>
    </div>
    <div class="col">
      <span>Total Hostels</span>
      <h4 id="total_hostel">1297</h4>
      <p>Read More</p>
    </div>
    <div class="col">
      <span>Hostel Vacancy</span>
      <h4 id="total_hostel_vaccancy">27537</h4>
      <p>Read More</p>
    </div>
  </div>
</div>
            <div class="student-info-section">
                <h4 class="section-title">Staff Information</h4>

                <!-- Top Cards -->
                <div class="dashboard-cards">
                    <div class="stat-card">
                    <div class="icon icon-blue"><i class="mdi mdi-account-tie"></i></div>
                    <h3 id="warden_cnt">104813</h3>
                    <p>Warden</p>
                    </div>

                    <div class="stat-card">
                    <div class="icon icon-purple"><i class="mdi mdi-account-cog"></i></div>
                    <h3 id="warden_inc_cnt">92289</h3>
                    <p>Warden Incharge</p>
                    </div>

                    <div class="stat-card">
                    <div class="icon icon-green"><i class="mdi mdi-chef-hat"></i></div>
                    <h3 id="cook_cnt">73701</h3>
                    <p>Cook</p>
                    </div>

                    <div class="stat-card">
                    <div class="icon icon-red"><i class="mdi mdi-silverware-fork-knife"></i></div>
                    <h3 id="cook_dep_cnt">1972</h3>
                    <p>Cook Deputation</p>
                    </div>

                    <div class="stat-card">
                    <div class="icon icon-orange"><i class="mdi mdi-shield-account"></i></div>
                    <h3 id="watchman_cnt">1523</h3>
                    <p>Watchman</p>
                    </div>

                    <div class="stat-card">
                    <div class="icon icon-teal"><i class="mdi mdi-account-switch"></i></div>
                    <h3 id="watchman_dep_cnt">1523</h3>
                    <p>Watchman Deputation</p>
                    </div>

                    <div class="stat-card">
                    <div class="icon icon-pink"><i class="mdi mdi-broom"></i></div>
                    <h3 id="sweeper_cnt">1523</h3>
                    <p>Sweeper</p>
                    </div>

                    <div class="stat-card">
                    <div class="icon icon-rose"><i class="mdi mdi-account-convert"></i></div>
                    <h3 id="sweeper_dep_cnt">1523</h3>
                    <p>Sweeper Deputation</p>
                    </div>
                </div>

                <!-- Bottom Summary -->
                <div class="info-summary mt-4">
                    <div class="col">
                    <span>Total Staffs</span>
                    <h4 id="total_staff">73701</h4>
                    <div class="mouse_hover">
                        <a href="javascript:void(0)" class="view" style="color: #1f1f1fff;">Read More</a>
                    </div>
                    </div>
                    <div class="col">
                    <span>Total Active Hostels</span>
                    <h4 id="total_hostel_1">1297</h4>
                    <div class="mouse_hover">
                        <a href="javascript:void(0)" class="view" style="color: #1f1f1fff;">Read More</a>
                    </div>
                    </div>
                </div>
                </div>

            <div class="student-info-section">
                <h4 class="section-title">Attendance</h4>        
    
                    <div class="row">
                        <div class="col-md-6 " >
                            <div class="p-3 " style="border: 1px solid #e5e7eb; border-radius:10px">
                                <div class="card-body pb-3" >
                                <!-- Sub Heading -->
                                <h4 class="section-title">Student Attandance</h4>   
                                    <!-- Filter Row -->
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="from_date" class="form-label">Get Attendance On</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" id="std_att_from_date" name="from_date">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-primary w-100" id="std_att"
                                                onclick="get_students_attendance()">
                                                Go
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="dashboard-cards">
                                    <!-- Bank Balance -->
                                    <div class="col stat-card">
                                        <div class="icon icon-purple"><i class="mdi mdi-account-check"></i></div>
                                        <h3 id="std_app_count"></h3>
                                        <p>Approved Count</p>
                                    </div>
                                    <div class="col stat-card">
                                        <div class="icon icon-green "><i class="mdi mdi-check-decagram"></i></div>
                                        <h3 id="warden_inc_cnt">02</h3>
                                        <p>Online Hostel Approve</p>
                                    </div>
                                    <div class="col stat-card">
                                        <div class="icon icon-blue"><i class="mdi mdi-clock-check-outline"></i></div>
                                        <h3 id="cook_cnt">02</h3>
                                        <p>Online Hostel Attendance</p>
                                    </div>
                                    <div class="col stat-card">
                                        <div class="icon icon-red"><i class="mdi mdi-close-octagon"></i></div>
                                        <h3 id="cook_dep_cnt">02</h3>
                                        <p>Zero Attendance</p>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" >
                         <div class="p-3 " style="border: 1px solid #e5e7eb; border-radius:10px">
                         <div class="card-body pb-3" >
                            <!-- Sub Heading -->
                              <h4 class="section-title">Staff Attandance</h4>   

                                <!-- Filter Row -->
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-3"><label for="from_date" class="form-label">From</label>

                                            </div>
                                            <div class="col-md-9"><input type="date" class="form-control" id="stf_att_from_date" name="from_date">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-3"><label for="from_date" class="form-label">To</label>

                                            </div>
                                            <div class="col-md-9"><input type="date" class="form-control" id="stf_att_from_date" name="from_date">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary w-100" id="stf_att"
                                            onclick="get_staff_attendance()">
                                            Go
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="dashboard-cards">
                                <!-- Bank Balance -->
                                <div class="col stat-card">
                                    <div class="icon icon-purple"><i class="mdi mdi-account-check"></i></div>
                                    <h3 id="stf_app_count"></h3>
                                    <p>Approved Count</p>
                                </div>
                                <div class="col stat-card">
                                    <div class="icon icon-green"><i class="mdi mdi-check-decagram"></i></div>
                                    <h3 id="warden_inc_cnt">02</h3>
                                    <p>Online Hostel Approve</p>
                                </div>
                                <div class="col stat-card">
                                    <div class="icon icon-blue"><i class="mdi mdi-clock-check-outline"></i></div>
                                    <h3 id="cook_cnt">02</h3>
                                    <p>Online Hostel Attendance</p>
                                </div>
                                <div class="col stat-card">
                                    <div class="icon icon-red"><i class="mdi mdi-close-octagon"></i></div>
                                    <h3 id="cook_dep_cnt">02</h3>
                                    <p>Zero Attendance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="student-info-row">
                <!-- Leave Details -->
                <div class="student-info-section leave-section">
                    <h4 class="section-title">Leave Details</h4>
                    <div class="dashboard-cards">
                        <div class="stat-card">
                        <div class="icon icon-lightblue"><i class="mdi mdi-school"></i></div>
                        <h3 id="no_of_student_name">104813</h3>
                        <p>Student Leave Application</p>
                        </div>

                        <div class="stat-card">
                        <div class="icon icon-purple"><i class="mdi mdi-account-tie"></i></div>
                        <h3 id="staff_name">92289</h3>
                        <p>Staff Leave Application</p>
                        </div>
                    </div>
                </div>

                <!-- Biometric Details -->
                <div class="student-info-section bio-section">
                    <h4 class="section-title">Biometric</h4>
                    <div class="dashboard-cards-bio">
                    <div class="stat-card">
                        <div class="icon icon-brown"><i class="mdi mdi-file-document"></i></div>
                        <h3 id="students_approved">104813</h3>
                        <p>DADWO Approved</p>
                    </div>

                    <div class="stat-card">
                        <div class="icon icon-yellow"><i class="mdi mdi-account-cog"></i></div>
                        <h3 id="std_pushed">92289</h3>
                        <p>Pushed to Device</p>
                    </div>

                    <div class="stat-card">
                        <div class="icon icon-purple1"><i class="mdi mdi-face-recognition"></i></div>
                        <h3 id="std_face_reg">92289</h3>
                        <p>Face Registered</p>
                    </div>

                    <div class="stat-card">
                        <div class="icon icon-pink"><i class="mdi mdi-face-woman"></i></div>
                        <h3 id="std_face_not_reg">92289</h3>
                        <p>Face Not Registered</p>
                    </div>

                    <div class="stat-card">
                        <div class="icon icon-teal"><i class="mdi mdi-fingerprint"></i></div>
                        <h3 id="std_finger_reg">92289</h3>
                        <p>Finger Registered</p>
                    </div>

                    <div class="stat-card">
                        <div class="icon icon-orange"><i class="mdi mdi-fingerprint-off"></i></div>
                        <h3 id="std_finger_not_reg">92289</h3>
                        <p>Finger Not Registered</p>
                    </div>
                    </div>
                </div>
                </div>
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <script>document.write(new Date().getFullYear())</script> © Adi Dravidar Welfare Department -
                        Managed by <a href="https://aedindia.com/">Ascent e Digit Solutions </a>
                    </div>
                    
                </div>
            </div>
        </footer>

    </div>
</div>
<!-- END wrapper -->