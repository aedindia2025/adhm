<?php

$academic_year_options = all_academic_year();
$academic_year_options = select_option($academic_year_options, "Select Academic Year");

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
                                <!-- <button type="submit" class="btn btn-primary" style="float: right;">Add New</button> -->
                            </form>
                        </div>
                        <h4 class="page-title">Student Marksheet List</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label">Academic Year</label>
                                    <select name="acc_year" id="acc_year" class="select2 form-control">
                                        <?php echo $academic_year_options; ?>
                                    </select>
                                </div>

                                <div class="col-md-2 align-self-center  mt-3">
                                    <button type="button" class="btn btn-primary" onclick="go_filter()">GO</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- end page title -->


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <table id="student_marksheet_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Registration No</th>
                                        <th>Student Name</th>
                                        <th>Semester Type</th>
                                        <th>CGPA</th>
                                        <th>Marksheet</th>
                                        <th>
                                            <div align="center">Action</div>
                                        </th>
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