<?php

$district_unique_id = $_SESSION["district_id"];
$taluk_unique_id = $_SESSION['taluk_id'];
$hostel_unique_id = $_SESSION['hostel_id'];
$academic_year = $_SESSION['academic_year'];

$academic_year_options = academic_year();
$academic_year_options = select_option_acc($academic_year_options);

$district_name_list = district_name($district_unique_id);
$district_name_list = select_option($district_name_list, "Select District", $district_unique_id);

$taluk_name_list = taluk_name($taluk_unique_id);
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_unique_id);

$hostel_name_list = hostel_name($hostel_unique_id);
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_unique_id);

$designation_list = [
    "1" => [
        "unique_id" => "65f3191aa725518258",
        "value" => "Warden"
    ],
    "2" => [
        "unique_id" => "65f31975f0ce678724",
        "value" => "DADWO"
    ]
];
$designation_list = select_option($designation_list, "Select Designation");
?>

<style>
    .disabled-select {
        pointer-events: none;
        background-color: #f5f5f5;
        /* or any other color to indicate it's disabled */
        color: #999;
        /* or any other color to indicate it's disabled */
    }

    .load {
        text-align: center;
        position: absolute;
        top: 17%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;

    }

    i.mdi.mdi-loading.mdi-spin {
        font-size: 75px;
        color: #17a8df;
    }

    .dt-buttons.btn-group.flex-wrap {
        display: none;
    }

    .dt-body-wrap {
        white-space: normal !important;
        word-break: break-word;
        /* Optional: for very long words */
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">
                        <h4 class="page-title">Credentials Report</h4>
                    </div>
                </div>
                <div class="col-2 mt-3 d-flex align-items-end">
                    <button type="button" id="consolidatedExport" class="btn w-100"
                        style="background: #337734;color: #fff;">
                        <i class="ri-file-excel-2-fill me-2"></i>Consolidated Export
                    </button>
                </div>
            </div>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#warden-tab" role="tab">
                        Hostel Warden
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#dadwo-tab" role="tab">
                        DADWO
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <!-- Filter Section -->
            <div class="tab-content">

                <!-- Hostel Warden Table -->
                <div class="tab-pane fade show active" id="warden-tab" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="card mb-3">

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
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Hostel Warden Credentials</h5>
                                <button type="button" id="export_warden" class="btn" onclick="warden_export();"
                                    style="background: #337734;color:#fff;">
                                    <i class="ri-file-excel-2-fill me-2"></i>Export
                                </button>
                            </div>

                            <table id="credentials_report_datatable"
                                class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>District</th>
                                        <th>Taluk</th>
                                        <th>Hostel ID</th>
                                        <th>Hostel</th>
                                        <th>User ID</th>
                                        <th>Password</th>
                                        <th>View Password</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- DADWO Table -->
                <div class="tab-pane fade" id="dadwo-tab" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>DADWO Credentials</h5>
                                <button type="button" id="export_dadwo" class="btn" onclick="dadwo_export();"
                                    style="background: #337734;color:#fff;">
                                    <i class="ri-file-excel-2-fill me-2"></i>Export
                                </button>
                            </div>

                            <table id="dadwo_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>District</th>
                                        <th>User ID</th>
                                        <th>Password</th>
                                        <th>View Password</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> <!-- end tab-content -->
        </div>
    </div>
</div>