<?php
$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name('',$_SESSION['district_id']);
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);
?>
<style>
    /* Ensure text wrapping in both cells and headers */
    table.dataTable td,
    table.dataTable th { 
        white-space: normal;
        word-wrap: break-word;
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
</style>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">
                        <h4 class="page-title">Biometric Attendance Report</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label for="example-select" class="form-label">From Date</label>
                                    <input type='date' id="from_date" name='from_date' class='form-control' value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-3">
                                    <label for="example-select" class="form-label">To Date</label>
                                    <input type='date' id="to_date" name='to_date' class='form-control' value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Taluk Name</label>
                                    <select name="taluk_name" id="taluk_name" class="select2 form-control" onchange="get_hostel()" required>
                                        <?php echo $taluk_name_list ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Hostel Name</label>
                                    <select name="hostel_name" id="hostel_name" class="select2 form-control" required>
                                        <?php echo $hostel_name_list ?>
                                    </select>
                                </div>
<input type='hidden' id='district_id' value='<?php echo $_SESSION['district_id'];?>'>
                                
                                <div class="col-md-2 mt-4 align-self-center">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="filter()" style="float:left;">GO</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" id="export" name="export" class="btn   waves-effect waves-light wavw  mb-1" style="background: #337734;color: #fff;font-size:16px;">
                <i class="ri-file-excel-2-fill" style="font-size: 22px; padding-right: 10px;line-height: 0px;vertical-align: middle;"></i>Export
            </button>
 	    <button type="button" id="consolidated_export" name="consolidated_export" class="btn   waves-effect waves-light wavw  mb-1" style="background:rgb(13, 13, 13);color: #fff;font-size:16px;">
                <i class="ri-file-excel-2-fill" style="font-size: 22px; padding-right: 10px;line-height: 0px;vertical-align: middle;"></i>Consolidated Export
            </button>
            <div class="row">
                <div class="col-md-12 load" id="loader">
                    <i class="mdi mdi-loading mdi-spin"></i>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <table id="daily_attendance_datatable" class="table dt-responsive w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Hostel ID</th>
                                        <th>Hostel Name</th>
                                        <th>District</th>
                                        <th>Hostel Type</th>
                                        <th>Sanctioned Strength</th>
                                        <th>DADWO Approved Count</th>
                                        <th>Biometric Registered Count</th>
                                        <th>Date</th>
                                        <th>Attendance Count 1</th>
                                        <th>Attendance Count 2</th>
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