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
// include '../header.php';
if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $unique_id = $_GET["unique_id"];
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "leave_application";

        $columns = [
            "student_id",
            "student_name",
            "no_of_days",
            "reason",
            "unique_id",
            "from_date ",
            "to_date",
            "(select DATE_FORMAT(entry_date, '%Y-%m-%d')) as entry_date",
            "(select DATE_FORMAT(updated, '%Y-%m-%d')) as updated",
            "(select district_name from district_name where district_name.unique_id= $table.district_name)as district_name",
            "(select taluk_name from taluk_creation where taluk_creation.unique_id=$table.taluk_name)as taluk_name",
            "(select hostel_name from hostel_name where hostel_name.unique_id=$table.hostel_name)as hostel_name",
            "(select amc_year from academic_year_creation where academic_year_creation.unique_id=$table.academic_year)as academic_year",
            // "(select staff_name from staff_registration where staff_registration.hostel_name = $table.hostel_name and staff_registration.user_type='65cb092facaf836335') as hostel_warden",
            "(select staff_name from staff_registration where staff_registration.unique_id = $table.warden_name) as warden_name",
            "approval_status",
            "reject_reason"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result);

        if ($result_values->status) {

            $result_values      = $result_values->data;

            $entry_date         = $result_values[0]["entry_date"];
            $updated            = $result_values[0]["updated"];
            $student_name       = $result_values[0]["student_name"];
            $student_id         = $result_values[0]["student_id"];
            $no_of_days         = $result_values[0]["no_of_days"];
            $from_date          = $result_values[0]["from_date"];
            $to_date            = $result_values[0]["to_date"];
            $reason             = $result_values[0]["reason"];
            $district_name      = $result_values[0]["district_name"];
            $taluk_name         = $result_values[0]["taluk_name"];
            $hostel_name        = $result_values[0]["hostel_name"];
            $hostel_warden        = $result_values[0]["hostel_warden"];
            $warden_name        = $result_values[0]["warden_name"];
            $academic_year      = $result_values[0]["academic_year"];
            $approval_status    = $result_values[0]["approval_status"];
            $reject_reason      = $result_values[0]["reject_reason"];
            $is_active          = $result_values[0]["is_active"];

            if ($approval_status == '1') {
                $approval_status  = 'pending';
            }
            if ($approval_status == '2') {
                $approval_status  = 'Approved';
            }
            if ($approval_status == '3') {
                $approval_status  = 'Rejected';
            }


            $btn_text           = "Update";
            $btn_action         = "update";
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<style>
    body {

        font-family: 'Poppins', sans-serif;
    }

    .card-body {
    margin: 20px;
    border: 1px solid #ccc;
    padding: 20px;
}

    .vendorListHeading {
        background-color: #f3f3f3;
        color: black;
        -webkit-print-color-adjust: exact;
	 border: 1px solid #ccc;
    }

    .mt-2.vendorListHeading p {
        margin-bottom: 0px;
        text-align: center;
        padding: 5px;
    }
	.print-1 p {
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 13px;
}
</style>
<div class="card-body print-1">
    <div class="clearfix">
        <div class=" mb-3 text-center">
            <img src="../../assets/images/ad-logo.png" alt="dark logo" height="50">
        </div>

    </div>
    <div class="row">
        <div class="col-sm-12 mb-2">
            <div class=" mt-2 vendorListHeading">
                <p><b>Student Leave Approval</b></p>

            </div>
        </div><!-- end col -->
        <div class="col-sm-12 ">
            <div class="mt-0 float-sm-left">
                <div class="row">
                    <div class="col-sm-6">
                        <p class="font-12">Academic Year: <strong><?= $academic_year; ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">District Name: <strong><?= $district_name; ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Entry Date: <strong><?= $entry_date; ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Taluk Name: <strong><?= $taluk_name; ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Student ID: <strong><?= $student_id; ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Hostel Name: <strong><?= $hostel_name; ?></strong></p>
                    </div>

                    <div class="col-sm-6">
                        <p class="font-12">Student Name: <strong><?= $student_name; ?></strong></p>
                    </div>
                    <!-- <div class="col-sm-6">
                        <p class="font-12">Warden Name: <strong><?= $hostel_warden; ?></strong></p>
                    </div> -->
                    <div class="col-sm-6">
                        <p class="font-12">From Date: <strong><?= $from_date; ?></strong></p>
                    </div>
                  
                    <div class="col-sm-6">
                        <p class="font-12">To Date: <strong><?= $to_date; ?></strong></p>
                    </div>
                    

                    <div class="col-sm-6">
                        <p class="font-12">No Of Days: <strong><?= $no_of_days; ?></strong></p>
                    </div>


                </div>
            </div>
        </div><!-- end col -->
    </div>
    <div class="col-sm-12 mb-2">
        <div class=" mt-2 vendorListHeading">
            <p><b> Leave Reason</b></p>

        </div>
        <p></p>
        <div class="col-sm-6">
            <p class="font-12">Reason: <strong><?= $reason; ?></strong></p>
        </div>
    </div>
	
	
	<div class="col-sm-12 mb-2">
    <div class=" mt-2 vendorListHeading">
        <p><b> Approved</b></p>

    </div>
    <p></p>
    <div class="row">
    <?php if($approval_status !='pending'){?>
        <div class="col-sm-6">
            <p class="font-12">Date: <strong><?= $updated; ?></strong></p>
        </div>
        <?php ;}?>
        <div class="col-sm-6">
            <p class="font-12">Status: <strong><?= $approval_status; ?></strong></p>
        </div>
        <?php if($approval_status !='pending'){?>
        <div class="col-sm-6">
            <p class="font-12">Warden Name: <strong><?= $warden_name; ?></strong></p>
        </div>
        <?php ;}?>
        <?php if($approval_status=='Rejected'){?>
        <div class="col-sm-6" id="reject_reason_div" >
            <p class="font-12">Reject Reason: <strong><?= $reject_reason; ?></strong></p>
        </div>
        <?php ;}?>
    </div>



</div>
	
	
	
	
	
	
	
</div><!-- end col -->

</div>