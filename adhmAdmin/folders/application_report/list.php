<?php

$academic_year_options = academic_year();
$academic_year_options = select_option_acc($academic_year_options);


$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);

$amc_name_list = academic_year($amc_name_list);
$amc_name_list = select_option_acc($amc_name_list);


$approval_status_option = [
    "0" => [
        "unique_id" => "0",
        "value" => "Pending",
    ],
    "1" => [
        "unique_id" => "1",
        "value" => "Approved",
    ],
    "2" => [
        "unique_id" => "2",
        "value" => "Rejected",
    ],

];
$approval_status_option = select_option($approval_status_option, "Select", $approval_status);

?>

<style>
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

    table#application_report_datatable {
    width: 100%;
    display: block;
    overflow: scroll;
}
</style>

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
                        <h4 class="page-title">Application Report</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Academic Year:</label>
                                    <!-- <input type ="text" readonly name="amc_name" id="amc_name" class="form-control" value="<?php echo $amc_name_list; ?>"> -->
                                    <select name="amc_name" id="amc_name" class="select2 form-control"
                                        required><?php echo $amc_name_list; ?> </select>
                                </div>

                                <div class="col-3">
                                    <label for="example-select" class="form-label">District Name</label>
                                    <select name="district_name" id="district_name" class="select2 form-control"
                                        onchange=get_taluk()>

                                        <?php echo $district_name_list; ?>
                                    </select>

                                </div>

                                <div class="col-3">
                                    <label for="example-select" class="form-label">Taluk Name</label>
                                    <select name="taluk_name" id="taluk_name" class="select2 form-control"
                                        onchange="get_hostel()" required>
                                        <?php echo $taluk_name_list ?>
                                        <!-- <option value="0">Select Taluk</option>
                                <option value="1">Tindal</option>
                                <option value="2">Saravanampatti</option>
                                <option value="3">Paladam</option> -->

                                    </select>

                                </div>

                                <div class="col-3">
                                    <label for="example-select" class="form-label">Hostel Name</label>
                                    <select name="hostel_name" id="hostel_name" class="select2 form-control" required>

                                        <?php echo $hostel_name_list ?>


                                    </select>

                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Status</label>
                                    <select name="app_status" id="app_status" class="select2 form-control" required>
                                        <option value="" selected>All</option>
                                        <option value="0">Pending</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Rejected</option>



                                    </select>

                                </div>

                                <div class="col-md-2 align-self-center">
                                    <div class="page-title-right">

                                        <button class="btn btn-primary" onclick="filter()"
                                            style="float:left;">GO</button>
                                        </a>
                                    </div>
                                </div>
                            </div><br><br>
                            <button type="button" id="export" name="export"
                                class="btn   waves-effect waves-light wavw  mb-1"
                                style="background: #337734;color: #fff;font-size:16px;">
                                <i class="ri-file-excel-2-fill"
                                    style="font-size: 22px; padding-right: 10px;line-height: 0px;vertical-align: middle;"></i>Export
                            </button>
                            <br>

                            <div class="row">
                                <div class="col-md-12 load" id="loader">
                                    <i class="mdi mdi-loading mdi-spin"></i>
                                </div>
                            </div>
                            <table id="application_report_datatable" class="table nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Applied Date</th>
                                        <th>Appl. No</th>
                                        <th>Hostel Type</th>
                                        <th>Submit Status</th>
                                        <th>Std. EMIS/UMIS ID</th>
                                        <th>Std. Name As per EMIS/UMIS</th>
                                        <th>Gender</th>
                                        <th>Home Address</th>

                                        <th>Hostel District</th>
                                        <th>Hostel Taluk</th>
                                        <th>Hostel Name</th>
                                        <th>Warden Status</th>
                                        <th> Physical Submission Status</th>
                                        <th>DADWO Rec. Status</th>
                                        <th>DADWO Approval Status</th>
                                        <th>DADWO Approval Date</th>
                                        <th>View Application</th>
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