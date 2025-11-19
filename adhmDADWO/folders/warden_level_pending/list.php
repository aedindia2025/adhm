<?php
// $district_name_list = district_name();
// $district_name_list = select_option($district_name_list, "Select District", $district_name);
$taluk_name = $_SESSION["taluk_id"];
    
$district_name = $_SESSION["district_id"];

$hostel_name = $_SESSION["hostel_id"];

$district_name_list = district_name($_SESSION['district_id']);
$district_name_list = select_option($district_name_list, "Select District",$district_name);

$taluk_name_list = taluk_name('',$district_name);
$taluk_name_list = select_option($taluk_name_list, "Select Taluk",$taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option($hostel_name_list, "Select Hostel",$hostel_name);

$academic_year_options = all_academic_year();
$academic_year_options = select_option_acc($academic_year_options,'Select Academic Year');


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

.dt-buttons.btn-group.flex-wrap {
    display: none;
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
                        <h4 class="page-title">Warden Level Pending Application List</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 fm">
                                    <label class="form-label" for="example-select">Academic Year</label>
                                    <select class="select2 form-control" id="academic_year" name="academic_year">
                                        <?php echo $academic_year_options; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 fm">
                                    <label class="form-label" for="example-select">District Name</label>
                                    <select class="select2 form-control" id="district_name" name="district_name"  disabled
                                        onchange="taluk()">
                                        <?php echo $district_name_list; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 fm">
                                    <label class="form-label" for="example-select">Taluk Name</label>
                                    <select class="select2 form-control" id="taluk_name" name="taluk_name" onchange="get_hostel();">
                                        <?php echo $taluk_name_list; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 fm">
                                    <label class="form-label" for="example-select">Hostel Name</label>
                                    <select class="select2 form-control" id="hostel_name" name="hostel_name">
                                        <?php echo $hostel_name_list; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 align-self-center mt-3">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="go_filter()">GO</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                        <!-- <button type="button" id="export" name="export"
                                class="btn   waves-effect waves-light wavw  mb-1" style="background: #337734;color: #fff;font-size:16px;">
                                <i class="ri-file-excel-2-fill" style="font-size: 22px; padding-right: 10px;line-height: 0px;vertical-align: middle;"></i>Export
                            </button> -->
                            <br>

                            <div class="row">
                                <div class="col-md-12 load" id="loader">
                                    <i class="mdi mdi-loading mdi-spin"></i>
                                </div>
                            </div>

                            <table id="warden_level_pending_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Student Name</th>
                                        <th>District Name</th>
                                        <th>Taluk Name</th>
                                        <th>Hostel Name</th>
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