<?php

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);

?>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Disbursement</h4>

<input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    </div>
                </div>
              
            </div>



            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label for="example-select" class="form-label">District Name</label>
                                    <select name="district_name" id="district_name" class="select2 form-control" onchange="get_taluk()" required>
                                        <?php echo $district_name_list; ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Taluk Name</label>
                                    <select name="taluk_name" id="taluk_name" class="select2 form-control" onchange="get_hostel()" required>
                                        <?php echo $taluk_name_list; ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Hostel Name</label>
                                    <select name="hostel_name" id="hostel_name" class="select2 form-control" required>
                                        <?php echo $hostel_name_list; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 align-self-center  mt-3">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="go_filter()">GO</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d">
                                <!-- <button class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Copy</span></button> <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>CSV</span></button> <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Excel</span></button> <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>PDF</span></button> <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="student_onboarding_datatable"><span>Print</span></button> </div> -->
                                <table id="disbursement_datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <!-- <th>Student Name</th>
                    <th>Student ID</th> -->
                                            <th>Disbursement Type</th>
                                            <th>ACC. Year</th>
                                            <th>Month</th>
                                            <th>Conn.No.</th>
                                            <th>Letter No.</th>
                                            <th>Applied Date</th>
                                            <th>ST.Letter No.</th>
                                            <th>DADWO.Letter No.</th>
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