<?php
session_start();

// Step 1: Check Authentication Status
if (!isset($_SESSION['user_id'])) {
    // Redirect unauthorized users to the login page
    header("Location: login.php");
    exit;
}

// Step 2: Secure File Access (optional)
// Implement authorization checks here if necessary

// Step 3: Fetch Unique ID
if(isset($_GET['unique_id'])) {
    $unique_id = $_GET['unique_id'];
    // Process the unique ID as needed
    // For example, retrieve the corresponding PDF file and display it
    // Make sure to implement appropriate security checks here
} else {
    // Handle case where unique ID is not provided
    echo "Error: Unique ID is missing.";
}
?>



<?php 
include '../../config/dbconfig.php';
if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $unique_id  = $_GET["unique_id"];
        $where      = [
            "unique_id" => $unique_id
        ];

        $table_1    =  "carrier_path_creation";
       

        $columns_1    = [
            // "(select std_name from std_reg_p1 where std_reg_p1.unique_id = carrier_path_creation.student_name) as student_name",
            "student_name",
            "DATE_FORMAT(created, '%Y-%m-%d') as created",
            // "(select staff_name from staff_registration where staff_registration.user_type='65cb092facaf836335' AND staff_registration.hostel_name =  $table_1.hostel_name) as warden_name",
            "(select std_app_no from std_reg_s where std_reg_s.unique_id = $table_1.student_id) as std_id",
            "employment_course",
            "job",
            "student_class",
            "course",
            "district_name as district",
            "taluk_name as taluk",
            "hostel_name as hostel",
            "acc_year as acc_yr",
            "unique_id",
            // "is_active"
        ];

        $table_details   = [
            $table_1,
            $columns_1
        ];

       
        $result_values  = $pdo->select($table_details, $where);


        if ($result_values->status) {

            $result_values            =   $result_values->data;
            $warden_name             =  $result_values[0]["warden_name"];
            // $qualification             =  $result_values[0]["qualification"];
            $student_name             =  $result_values[0]["student_name"];
            $created             =  $result_values[0]["created"];
            $entry_date             =  $result_values[0]["entry_date"];
            $student_id               =   $result_values[0]["std_id"];
            $student_qualification    =   $result_values[0]["student_class"];
            $employment_course        =   $result_values[0]["employment_course"];
            $job                      =   $result_values[0]["job"];

            $warden                     =   $result_values[0]["warden_name"];

            $taluk = $result_values[0]["taluk"];  
            $district =  $result_values[0]["district"];
            $hostel =  $result_values[0]["hostel"];
            // acc_yr
            $acc_yr =  $result_values[0]["acc_yr"];


            $result_values["taluk"] = taluk_name_get($taluk)[0]['taluk_name'];

            $result_values["district"] = district_name($district)[0]['district_name'];

            $result_values["hostel"] = hostel_name($hostel)[0]['hostel_name'];

            $job                      =   $result_values[0]["job"];

            $course                   =    $result_values[0]["course"];
            $is_active                =    $result_values["is_active"];

            $btn_text           = "Update";
            $btn_action         = "update";
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}

// $active_status_options   = active_status($is_active);
?> 

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<style>
    body {
    
    font-family: 'Poppins', sans-serif;
}
    .card-body {
        padding: 20px;
    }

    .vendorListHeading {
        background-color: #f3f3f3;
        color: black;
        -webkit-print-color-adjust: exact;
    }

    .mt-2.vendorListHeading p {
        margin-bottom: 0px;
        text-align: center;
        padding: 5px;
    }
</style>
<div class="card-body">
    <div class="clearfix">
        <div class=" mb-3 text-center">
            <img src="../../assets/images/ad-logo.png" alt="dark logo" height="50">
        </div>

    </div>
    <div class="row">
        <div class="col-sm-12 mb-2">
            <div class=" mt-2 vendorListHeading">
                <p><b>Carrier Path</b></p>

            </div>
        </div><!-- end col -->
        <div class="col-sm-12 ">
            <div class="mt-0 float-sm-left">
                <div class="row">
                    <div class="col-sm-6">
                        <p class="font-13">Entry Date: <strong><?=  $created; ?></strong></p>
                    </div>
                  <!--  <div class="col-sm-6">
                        <p class="font-13">Accademic Year: <strong><?= $acc_yr;?></strong></p>
                    </div>-->
                    <div class="col-sm-6">
                        <p class="font-13">Student ID: <strong><?=$student_id;?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-13">Student Name: <strong><?= $student_name; ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-13">Taluk Name: <strong><?= $result_values["taluk"] ; ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-13">District Name: <strong><?= $result_values["district"]; ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-13">Hostel Name: <strong><?= $result_values["hostel"]; ?></strong></p>
                    </div>
                    <!-- <div class="col-sm-6">
                        <p class="font-13">Warden Name: <strong><?=$warden_name; ?></strong></p>
                    </div> -->
                </div></div></div>
    </div>


                <div class="row">
        <div class="col-sm-12 mb-2">
            <div class=" mt-2 vendorListHeading">
            
            <p><b>Course Details</b></p>
            </div>
                    <div class="col-sm-4">
                        <p class="font-13">Qualification: <strong><?=  $student_qualification ; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-13">Employment/Course: <strong><?=  $employment_course; ?></strong></p>
                    </div>
                    <?php if($employment_course=='course'){?>
                    <div class="col-sm-4">
                        <p class="font-13">Course: <strong><?= $course; ?></strong></p>
                    </div> 
                    <?php ;}?>
                    <?php if($employment_course=='employment'){?>
                    <div class="col-sm-4">
                        <p class="font-13">Organization name: <strong><?= $job; ?></strong></p>
                    </div> 
                    <?php ;}?>
                </div>
            </div><!-- end col -->
        </div>
    <!-- </div> -->
<!--        
                </div>
            </div> -->
      

