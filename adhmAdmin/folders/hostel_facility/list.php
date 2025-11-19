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
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <!-- <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                            </form> -->
                        </div>
                        <h4 class="page-title">Hostel Facility</h4>
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
                        <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <table id="hostel_facility_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Entry Date</th>
                                        <th>Facility Type</th>
                                        <th>Facility Name</th>
                                        <th>Received Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
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