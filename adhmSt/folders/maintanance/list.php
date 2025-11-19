
<?php

$hostel_name_list = hostel_name("", $_SESSION['taluk_id']);
// print_r($hostel_name_list);
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);

$academic_year = academic_year();
$academic_year = select_option($academic_year, "Select Academic Year", $academic_year);

?>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Maintenance</h4>
                    </div>
                </div>
                <div class="col-md-2 align-self-center">
                    <div class="page-title-right new">
                        <form class="d-flex">
                            <!-- <?php echo btn_add($btn_add); ?> -->
                        </form>

                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-2">

                                <div class="col-md-3 fm">
                                    <label class="form-label" for="example-select">Academic Year</label>
                                    <select class="select2 form-control" id="academic_year" name="academic_year">
                                        <?php echo $academic_year;?>
                                    </select>
                                </div>
                                


                                <div class="col-md-3">
                                    <label class="form-label" for="example-select">Hostel Name</label>
                                    <select class="form-control select2" data-toggle="select2" id="hostel_name" name="hostel_name">
                                        <?php echo $hostel_name_list;?></select>

                                </div>
                                <div class="col-md-2 align-self-center mt-3">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="get_table()">Filter</button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table id="maintanance_datatable" class="table dt-responsive nowrap w-100" >
                                            <thead>
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Date</th>
                                                    <th>Maintanance No</th>
                                                    <th>Asset Category</th>
                                                    <th>Asset Name</th>
                                                    <th>District Name</th>
                                                    <th>Hostel Name</th>
                                                    <th>Invoice</th>
                                                    <th>View</th>
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


            <!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
            <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script> -->