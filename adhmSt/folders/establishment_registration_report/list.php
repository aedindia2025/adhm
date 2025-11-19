 <?php

    $taluk_id = $_SESSION["taluk_id"];
    $district_id = $_SESSION['district_id'];

    $district_name_list   = district_name();
    $district_name_list   = select_option($district_name_list, "Select District");

    // $st_district   = st_district();
    // $st_district_list   = select_option($district_name_list, "Select District");


    $taluk_name_list      = taluk_name($taluk_id);
    $taluk_name_list      = select_option($taluk_name_list, "Select Taluk",$taluk_id);

    $hostel_name_list     = hostel_name("", $taluk_id);
    $hostel_name_list     = select_option_host($hostel_name_list, "Select Hostel");

    $desination_type_list = designation();
    $desination_type_list = select_option($desination_type_list, "select Designation");

    $academic_year        = academic_year();
    $academic_year        = select_option_acc($academic_year, "Select Academic Year");

    ?>
 <style>
     .select2-container--default .select2-results__option--highlighted[aria-selected] {
         background-color: #1A7DB7;
         color: white;
     }
 </style>
 <div class="content-page">
     <div class="content">
         <!-- Start Content-->
         <div class="container-fluid">
             <!-- start page title -->
             <div class="row">
                 <div class="col-10">
                     <div class="page-title-box">
                         <h4 class="page-title">Establishment Registration Details</h4>
                     </div>
                 </div>
                 <div class="col-md-2 align-self-center">
                     <div class="page-title-right">
                         <!-- <a href="index.php?file=establishment_registration/model"> <button class="btn btn-primary" style="float: right;">Add New</button></a> -->
                     </div>
                 </div>
             </div>
             <div class="row mb-2">

                 <div class="col-md-3 fm">
                     <label for="example-select" class="form-label">Academic Year:</label>
                     <!-- <input type ="text" readonly name="amc_name" id="amc_name" class="form-control" value="<?php echo $amc_name_list; ?>"> -->
                     <select name="amc_name" id="amc_name" class="select2 form-control" disabled required><?php echo $academic_year; ?></select>

                 </div>
                 <div class="col-md-3 fm">
                     <label class="form-label" for="example-select">District Name</label>
                     
                     <input type="text" id="district" name="district" disabled class="form-control" value="<?php echo $_SESSION["district_name"]; ?>">
                     <input type="hidden" id="district_name" name="district_name" disabled class="form-control" value="<?php echo $_SESSION["district_id"]; ?>">
                </div>
                 <div class="col-md-3 fm">
                     <label class="form-label" for="example-select">Taluk Name</label>
                     <select class="select2 form-control" id="taluk_name" disabled  name="taluk_name" onchange="get_hostel()">
                         <?php echo $taluk_name_list;?>
                     </select>
                 </div>

                 <div class="col-md-3 fm">
                     <label class="form-label" for="example-select">Hostel Name</label>
                     <select class="select2 form-control" id="hostel_name" name="hostel_name">
                         <?php echo $hostel_name_list; ?>
                     </select>
                 </div>

                 <div class="col-md-3 fm">
                     <label class="form-label" for="example-select">Designation</label>
                     <select class="select2 form-control" id="department_new" name="department_new">
                         <?php echo $desination_type_list; ?>
                     </select>
                 </div>

                 <div class="col-md-3 fm mt-3">
                     <div class="page-title-right">
                         <form class="d-flex">
                             <buttont type="button" class="btn btn-primary" onclick="go_staff_filter()">Go</button>
                         </form>
                     </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-12">
                     <div class="card">
                         <div class="card-body">
                             <table id="staff_registration_datatable" class="table dt-responsive nowrap w-100">
                                 <thead>
                                     <tr>
                                         <th>S.no</th>
                                         <th>Staff Name</th>
                                         <th>Designation</th>
                                         <th>Mobile Number</th>
                                         <th>District</th>
                                         <th>Taluk </th>
                                         <th>Hostel</th>
                                     </tr>
                                 </thead>
                                 <tbody>

                                     </tr>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
 <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>