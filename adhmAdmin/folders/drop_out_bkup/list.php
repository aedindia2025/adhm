<?php

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);

$amc_name_list = academic_year($amc_name_list);
$amc_name_list = select_option_acc($amc_name_list);

?>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Dropout / Discontinue</h4>
                    </div>
                </div>
                <div class="col-md-2 align-self-center">
                    <div class="page-title-left" style="float: right;">
                        <form class="d-flex">

                            <!-- <button type="submit" class="btn btn-primary" style="float: right;">Add New</button> -->
                        </form>
                        <!-- <a href="index.php?file=drop_out/model">
                            <button class="btn btn-primary" style="float: right;">Add New</button>
                        </a> -->
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
                                    <select name="district_name" id="district_name" class="select2 form-control"
                                        onchange="get_taluk()" required>
                                        <?php echo $district_name_list; ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Taluk Name</label>
                                    <select name="taluk_name" id="taluk_name" class="select2 form-control"
                                        onchange="get_hostel()" required>
                                        <?php echo $taluk_name_list ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Hostel Name</label>
                                    <select name="hostel_name" id="hostel_name" class="select2 form-control" required>
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


                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">


                <table id="drop_out" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Reason For Dropout/Discontinue</th>
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

<!-- 
</div>
</div>
</div> -->