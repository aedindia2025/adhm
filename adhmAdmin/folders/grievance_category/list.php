<style>
a.btn.btn-action.specl2 {
    padding: 0px;
}
a.btn.btn-action.specl2 button {
    background: unset;
    border: 0px;
    color: #02aeee;
}
</style>
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
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Grievance List</h4>
                    </div>
                </div>
                <div class="col-md-2 align-self-center">
                <div class="page-title-right">
                            <!-- <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                            </form> -->
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
                                    <select name="district_id" id="district_id" class="select2 form-control" onchange="get_taluk()" required>
                                        <?php echo $district_name_list;?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Taluk Name</label>
                                    <select name="taluk_id" id="taluk_id" class="select2 form-control" onchange="get_hostel()" required>
                                        <?php echo $taluk_name_list;?>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label for="example-select" class="form-label">Hostel Name</label>
                                    <select name="hostel_id" id="hostel_id" class="select2 form-control" required>
                                        <?php echo $hostel_name_list;?>
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
                            <table id="grievance_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Entry Date</th>
                                        <th>Grievance Id</th>
                                        <th>Grievance Category</th>
                                        <th>Grievance Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    </tr>
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
