<style>
    table#student_marksheet_datatable {
    width: 100%;
    display: block;
    overflow: scroll;
}
</style>
<?php
$academic_year_options = academic_year();
$academic_year_options = select_option($academic_year_options, 'Select');


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
                           
                        </div>
                        <h4 class="page-title">Student Marksheet List</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">


                         <div class="row">
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Academic Year:</label>
                                    <!-- <input type ="text" readonly name="amc_name" id="amc_name" class="form-control" value="<?php echo $amc_name_list; ?>"> -->
                                    <select name="amc_name" id="amc_name" class="select2 form-control"
                                        required><?php echo $academic_year_options; ?> </select>
                                </div>

                                <div class="col-3">
                                    <label for="example-select" class="form-label">District Name</label>
                                    <select name="district_name" id="district_name" class="select2 form-control"
                                        onchange=get_taluk()>

                                        <?php echo $district_name_list; ?>
                                    </select>

                                </div>

                                <div class="col-3">
                                    <label for="example-select" class="form-label">Taluk Name</label>
                                    <select name="taluk_name" id="taluk_name" class="select2 form-control"
                                        onchange="get_hostel()" required>
                                        <?php echo $taluk_name_list ?>
                                        <!-- <option value="0">Select Taluk</option>
                                <option value="1">Tindal</option>
                                <option value="2">Saravanampatti</option>
                                <option value="3">Paladam</option> -->

                                    </select>

                                </div>

                                <div class="col-3">
                                    <label for="example-select" class="form-label">Hostel Name</label>
                                    <select name="hostel_name" id="hostel_name" class="select2 form-control" required>

                                        <?php echo $hostel_name_list ?>


                                    </select>

                                </div>
                            </div><br>
                            <div class="row">
                            <div class="col-md-2 align-self-center">
                                    <div class="page-title-right">

                                        <button class="btn btn-primary" onclick="filter()"
                                            style="float:left;">GO</button>
                                        </a>
                                    </div>
                                </div>
                                </div><br>

                            <table id="student_marksheet_datatable" class="table  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Academic Year</th>
                                        <th>Hostel District</th>
                                        <th>Hostel Taluk</th>
                                        <th>Hostel ID</th>
                                        <th>Hostel Name</th>
                                        <th>Registration No</th>
                                        <th>Student Name</th>
                                        <th>Semester Type</th>
                                        <th>Semester Status</th>
                                        <th>CGPA</th>
                                        <th>Marksheet</th>
                                      
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