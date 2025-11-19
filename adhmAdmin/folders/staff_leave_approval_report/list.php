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

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);

$amc_name_list = academic_year($amc_name_list);
$amc_name_list = select_option_acc($amc_name_list);

// print_r()
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
                            <form class="d-flex">
                                <!-- <?php echo btn_add($btn_add); ?> -->
                            </form>
                        </div>
                        <h4 class="page-title">Leave Approval Report</h4>
                        <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <!-- <div class="col-2">
                            <label for="example-select" class="form-label">Academic Year:</label>
                           <input type ="text" readonly name="amc_name" id="amc_name" class="form-control" value="<?php echo $amc_name_list; ?>">
                           <select name="amc_name" id="amc_name" class="select2 form-control" disabled required><?php echo $amc_name_list; ?> </select>
                        </div> -->

                                <div class="col-3">
                                    <label for="example-select" class="form-label">District Name</label>
                                    <select name="district_name" id="district_name" class="select2 form-control" onchange="get_taluk()" required>

                                        <?php echo $district_name_list; ?>
                                    </select>

                                </div>

                                <div class="col-3">
                                    <label for="example-select" class="form-label">Taluk Name</label>
                                    <select name="taluk_name" id="taluk_name" class="select2 form-control" onchange="get_hostel()" required>
                                        <?php echo $taluk_name_list ?>


                                    </select>

                                </div>

                                <div class="col-2">
                                    <label for="example-select" class="form-label">Hostel Name</label>
                                    <select name="hostel_name" id="hostel_name" class="select2 form-control" required>
                                        <?php echo $hostel_name_list ?>


                                    </select>

                                </div>

                                <div class="col-md-2 align-self-center">
                                    <div class="page-title-right mt-3">

                                        <button class="btn btn-primary" onclick="leave_filter()">GO</button>
                                        </a>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <table id="staff_leave_approval_datatable" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>S.no</th>
                                                            <th>Date</th>
                                                            <th>Staff Id</th>
                                                            <th>Staff Name</th>
                                                            <th>No Of Days</th>
                                                            <th>Reason</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>

                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>