<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('Invalid CSRF token');
    }
}

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);

$academic_year = academic_year();
$academic_year = select_option($academic_year, "Select Academic Year", $academic_year);



?>


<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Maintenance List</h4>
                    </div>
                </div>
                <div class="col-md-2 align-self-center">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <!-- <?php echo btn_add($btn_add); ?> -->
                        </form>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">

                                <!-- <div class="col-md-3 fm">
                                    <label class="form-label" for="example-select">Academic Year</label>
                                    <select class="select2 form-control" id="academic_year" name="academic_year">
                                        <?php echo $academic_year; ?>
                                    </select>
                                </div> -->

                                <div class="col-3">
                                    <label class="form-label" for="example-select">District Name</label>
                                    <select class="form-control select2" data-toggle="select2" id="district_name" name="district_name" onchange=get_taluk()>
                                        <?php echo $district_name_list; ?>
                                        <!-- <option>Select District</option>
                                        <option value="AK">Erode</option>
                                        <option value="HI">Salem</option> -->
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="example-select">Taluk Name</label>
                                    <select class="form-control select2" data-toggle="select2" id="taluk_name" name="taluk_name" onchange=get_hostel()>
                                        <?php echo $taluk_name_list; ?>

                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="example-select">Hostel Name</label>
                                    <select class="form-control select2" data-toggle="select2" id="hostel_name" name="hostel_name">
                                        <?php echo $hostel_name_list; ?>

                                    </select>
                                </div>


                                <div class="col-md-2 align-self-center mt-3">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="filter_records()">GO</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>





                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <table id="maintanance_datatable" class="table dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>S.no</th>
                                                <th>Date</th>
                                                <th>Maintenance No</th>
                                                <th>Asset Category</th>
                                                <th>Asset name</th>
                                                <th>Hostel District</th>
                                                <th>Hostel name</th>
                                                <th>Invoice</th>
                                                <th>View</th>

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
        </div>


        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script>
            new DataTable('#demo');
        </script>