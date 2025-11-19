<?php

$district_unique_id = $_SESSION["district_id"];
$taluk_unique_id = $_SESSION['taluk_id'];
$academic_year = $_SESSION['academic_year'];

$academic_year_options = academic_year($academic_year);
$academic_year_options = select_option_acc($academic_year_options);

$district_name_list = district_name($district_unique_id);
$district_name_list = select_option($district_name_list, "Select District", $district_unique_id);

$taluk_name_list = taluk_name($taluk_unique_id);
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_unique_id);

$hostel_name_list = hostel_name('',$taluk_unique_id);
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel");

$current_month = date('%M,%Y');

// print_r($current_month);die()
?>

<style>
    .disabled-select {
    pointer-events: none;
    background-color: #f5f5f5; /* or any other color to indicate it's disabled */
    color: #999; /* or any other color to indicate it's disabled */
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

                        <h4 class="page-title">Stock Entry - Report</h4>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form class="was-validated" autocomplete="off">

                        <div class="row">
                            <!-- <div class="col-3">
                                <label for="example-select" class="form-label">Academic Year</label>
                                <select class="form-select disabled-select" name="academic_year" id="academic_year">
                                    <?php echo $academic_year_options; ?>
                                </select>
                            </div> -->
                            <div class="col-3">
                                <label for="example-select" class="form-label">District</label>
                                <select class="form-select disabled-select" name="district" id="district">
                                    <?php echo $district_name_list; ?>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="example-select" class="form-label">Taluk</label>
                                <select class="form-select disabled-select" name="taluk" id="taluk">
                                    <?php echo $taluk_name_list; ?>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="example-select" class="form-label">Hostel</label>
                                <select class="form-select" name="hostel" id="hostel">
                                    <?php echo $hostel_name_list; ?>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="example-select" class="form-label">Month</label>
                                <input type="month" id="month_fill" name="month_fill" class="form-control"
                                    value="<?php echo date('Y-m'); ?>">
                            </div>

                            <div class="col-3">
                                <form class="d-flex">
                                        <buttont type="button" class="btn btn-primary" style="margin-top: 28px;" onclick="stock_report_filter()">Go</button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>





            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- <div class="dt-buttons btn-group"> <button class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Copy</span></button> <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>CSV</span></button> <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Excel</span></button> <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>PDF</span></button> <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="student_onboarding_datatable"><span>Print</span></button> </div>
                            <br>
                            <br> -->
                            <table id="stock_report_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <!-- <th>Month&Year</th> -->
					<th>Hostel Name</th>
                                        <th>Item Name</th>
                                        <th>Opening Stock</th>
                                        <th>Inward</th>
                                        <th>Outward</th>
                                        <th>Closing Stock</th>
                                        <!-- <th>Description</th> -->
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