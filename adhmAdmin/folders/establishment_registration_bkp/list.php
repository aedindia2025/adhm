<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}
    $ses_district_office    = $_SESSION["district_id"];
    $ses_district_name      = $_SESSION["district_name"];
    $ses_taluk_name         = $_SESSION['taluk_name'];
    $ses_taluk_id           = $_SESSION['taluk_id'];    
    $ses_designation        = $_SESSION["designation"];       
    $ses_designation_name   = $_SESSION["designation_name"]; 

    $district_name_list     = district_name();
    $district_name_list     = select_option($district_name_list, "Select District");

    $taluk_name_list        = taluk_name();
    $taluk_name_list        = select_option($taluk_name_list, "Select Taluk");

    $hostel_name_list       = hostel_name();
    $hostel_name_list       = select_option($hostel_name_list, "Select Hostel");

    $desination_type_list   = hostel_designation();
    $desination_type_list   = select_option($desination_type_list,"Select Designation");

    $academic_year          = academic_year();
    $academic_year          = select_option_acc($academic_year, "Select Academic Year");

?>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            
                        </div>
                        <h4 class="page-title">Establishment</h4>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <!-- <div class="col-md-3 fm">
                    <label for="example-select" class="form-label">Academic Year:</label>
                    <input type ="text" readonly name="amc_name" id="amc_name" class="form-control" value="<?php echo $amc_name_list; ?>">
                    <select name="academic_year" id="academic_year" class="select2 form-control" disabled required>
                        <?php echo $academic_year; ?>
                    </select>
                </div> -->
                <div class="col-md-3 fm">
                    <label class="form-label" for="example-select">District Name</label>
                    <select class="select2 form-control" id="district_name" name="district_name" onchange="taluk()">
                        <?php echo $district_name_list; ?>
                    </select>
                </div>
                <div class="col-md-3 fm">
                    <label class="form-label" for="example-select">Taluk Name</label>
                    <select class="select2 form-control" id="taluk_name" name="taluk_name">
                        <?php echo $taluk_name_list;?>
                    </select>
                </div>
                <div class="col-md-3 fm">
                    <label class="form-label" for="example-select">Designation</label>
                    <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

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
                            <div class="row">
                                <table id="establishment_registration_datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>S.no</th>
                                            <th>Staff Name</th>
                                            <th>Designation</th>
                                            <th>Mobile Number</th>
                                            <th>District</th>
                                            <th>Taluk </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
