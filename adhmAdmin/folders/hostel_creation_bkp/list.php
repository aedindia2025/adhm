<?php

$district_name_list   = district_name();
$district_name_list   = select_option($district_name_list, "Select District", $district_name);

// $taluk_name_list      = taluk_name();
// $taluk_name_list      = select_option($taluk_name_list, "Select Taluk", $taluk_name);

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
                            <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                            </form>
                        </div>
                        <h4 class="page-title">Hostel Creation</h4>
                        <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 fm">
                                    <label class="form-label" for="example-select">District Name</label>
                                    <select class="select2 form-control" id="district_id" name="district_id"
                                        onchange="taluk()">
                                        <?php echo $district_name_list; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 fm">
                                    <label class="form-label" for="example-select">Taluk Name</label>
                                    <select class="select2 form-control" id="taluk_id" name="taluk_id">
                                        <?php echo $taluk_name_list; ?>
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
                            <table id="hostel_creation_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
					<th>Hostel ID</th>
                                        <th>Hostel Name</th>
                                        <th>Status</th>
                                        <th>
                                            <div align="center">Action</div>
                                        </th>
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