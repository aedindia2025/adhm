<?php
$district_name_list   = district_name();
$district_name_list   = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list      = taluk_name();
$taluk_name_list      = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list     = hostel_name();
$hostel_name_list     = select_option($hostel_name_list, "Select Hostel", $hostel_name);

$desination_type_list = designation();
$desination_type_list = select_option($desination_type_list, "select Designation", $designation);

$academic_year        = academic_year();
$academic_year        = select_option_acc($academic_year, "Select Academic Year", $academic_year);

?>
<style>
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #1A7DB7;
        color: white;
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
                        <h4 class="page-title">Staff Registration Details</h4>
                    </div>
                </div>
                <div class="col-md-2 align-self-center">
                    <div class="page-title-right">
                    <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                                <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            </form>
                    </div>
                </div>
            </div>
            <div class="row mb-2">

                <!-- <div class="col-md-3 fm">
                    <label for="example-select" class="form-label">Academic Year:</label>
                    <input type ="text" readonly name="amc_name" id="amc_name" class="form-control" value="<?php echo $amc_name_list; ?>">
                    <select name="academic_year" id="academic_year" class="select2 form-control" disabled required>
                        <?php echo $academic_year; ?>
                    </select>
                </div> -->
                <div class="col-md-3 fm">
                    <label class="form-label" for="example-select">District Name</label>
                    <select class="select2 form-control" id="district_name" name="district_name" onchange="taluk()">
                        <?php echo $district_name_list; ?>
                    </select>
                </div>
                <div class="col-md-3 fm">
                    <label class="form-label" for="example-select">Taluk Name</label>
                    <select class="select2 form-control" id="taluk_name" name="taluk_name">
                        <?php echo $taluk_name_list;?>
                    </select>
                </div>
                <div class="col-md-3 fm">
                    <label class="form-label" for="example-select">Designation</label>
                    <select class="select2 form-control" id="department_new" name="department_new">
                        <?php echo $desination_type_list; ?>
                    </select>
                </div>

                <div class="col-md-3 fm mt-3">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <buttont type="button" class="btn btn-primary" onclick="go_staff_filter()">Go</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="staff_registration_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Staff Name</th>
                                        <th>Designation</th>
                                        <th>Mobile Number</th>
                                        <th>District</th>
                                        <th>Taluk </th>
                                        <!-- <th>Hostel</th>    -->

                                        <th>Action</th>
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
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    new DataTable('#demo');
</script>