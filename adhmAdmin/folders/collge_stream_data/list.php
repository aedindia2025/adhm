<?php


$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);



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
                        <h4 class="page-title">College Stream & Standard Wise Data List</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 fm">
                                    <label class="form-label" for="example-select">District Name</label>
                                    <select class="select2 form-control" id="district_name" name="district_name"
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

                            <table id="college_stream_data_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Student Name</th>
                                        <th>Batch No</th>
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