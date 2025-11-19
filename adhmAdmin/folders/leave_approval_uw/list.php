<?php

$academic_year_options = academic_year();
$academic_year_options = select_option_acc($academic_year_options);

$approval_status_option        = [
    "0" => [
        "unique_id" => "0",
        "value"     => "Pending",
    ],
    "1" => [
        "unique_id" => "1",
        "value"     => "Approved",
    ],
    "2" => [
        "unique_id" => "2",
        "value"     => "Rejected",
    ],

];
$approval_status_option        = select_option($approval_status_option, "Select", $approval_status);

?>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">\
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">

                        </div>
                        <h4 class="page-title">Leave Approval</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3  was-validated">
                                <div class="col-md-3">
                                    <label for="academic_year" class="form-label">Academic Year </label>
                                    <select class="form-select" name="academic_year" id="academic_year">
                                        <?php echo $academic_year_options; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="approval_status" class="form-label">Status</label>
                                    <select name="approval_status" id="approval_status" class="select2 form-control" required>
                                        <?= $approval_status_option; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-3 align-self-center">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="statusFilter()">Filter</button>
                                    </div>
                                </div>
                            </div>
                            <table id="leave_approvel_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>No Of Days</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <!-- <th>Action</th> -->
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