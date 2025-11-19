<?php

$district_name_list = district_name($district_unique_id);
$district_name_list = select_option($district_name_list, "Select District");

$taluk_name_list = taluk_name($taluk_unique_id);
$taluk_name_list = select_option($taluk_name_list, "Select Taluk");

$hostel_name_list = hostel_name($hostel_unique_id);
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel");

?>

<style>
    .disabled-select {
        pointer-events: none;
        background-color: #f5f5f5;
        color: #999;
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">
                        <h4 class="page-title">Student Movement</h4>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label">District</label>
                            <select class="form-select" id="district" onchange="get_taluk()">
                                <?php echo $district_name_list; ?>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Taluk</label>
                            <select class="form-select" id="taluk" onchange="get_hostel()">
                                <?php echo $taluk_name_list; ?>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Hostel</label>
                            <select class="form-select" id="hostel">
                                <?php echo $hostel_name_list; ?>
                            </select>
                        </div>

                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100"
                                onclick="stock_report_filter()">Go</button>
                        </div>
                    </div>

                    <table id="student_movement_datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>District</th>
                                <th>Taluk</th>
                                <th>Hostel</th>
                                <th>Reg No</th>
                                <th>Student Name</th>
                                <th>Status</th>
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