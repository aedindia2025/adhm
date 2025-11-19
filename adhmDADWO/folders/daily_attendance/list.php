<?php
$district_name_list = district_name($_SESSION["district_id"]);
$district_name_list = select_option_acc($district_name_list, "Select District",$district_name);

$taluk_name_list = taluk_name("",$_SESSION["district_id"]);
$taluk_name_list = select_option($taluk_name_list, "Select Taluk",$taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel",$hostel_name);
?>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Daily Attendance</h4>
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
                        <!-- <div class="col-3">
                            <label for="example-select" class="form-label">Academic Year:</label>
                           
                           <select name="amc_name" id="amc_name" class="select2 form-control" disabled required><?php echo $amc_name_list;?> </select>
                        </div> -->

			<div class="col-3">
                            <label for="example-select" class="form-label">From Date</label>
                            <input type='date' id="from_date" name='from_date' class='form-control' value="<?php echo date('Y-m-d');?>">                            
                        </div>

			<div class="col-3">
                            <label for="example-select" class="form-label">To Date</label>
                            <input type='date' id="to_date" name='to_date' class='form-control' value="<?php echo date('Y-m-d');?>">                            
                        </div>

                        <div class="col-3">
                            <label for="example-select" class="form-label">District Name</label>
                            <select name="district_name" id="district_name" class="select2 form-control"  required disabled>
        
                                 <?php echo $district_name_list;?> 
                            </select>
                            
                        </div>

                        <div class="col-3">
                            <label for="example-select" class="form-label">Taluk Name</label>
                            <select name="taluk_name" id="taluk_name" class="select2 form-control" onchange="get_hostel()" required>
                                                    <?php echo $taluk_name_list ?>
                               
                            </select>
                            
                        </div>

                        <div class="col-3">
                            <label for="example-select" class="form-label">Hostel Name</label>
                            <select name="hostel_name" id="hostel_name" class="select2 form-control" required>
                                
                            <?php echo $hostel_name_list?>
                               

                            </select>
                            
                        </div>
<div class="col-md-2 mt-3 align-self-center">
                    <div class="page-title-right">
                    
                                  <button class="btn btn-primary" onclick="filter()" style="float:left;">GO</button>
                        </a>
                    </div>
                </div>

                        </div>
                        </div>
                        </div>
                        </div>
                        </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- <div class="dt-buttons btn-group mt-2 mb-3"> <button class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Copy</span></button> <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>CSV</span></button> <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Excel</span></button> <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>PDF</span></button> <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="student_onboarding_datatable"><span>Print</span></button> </div>
                            <br>
                            <br> -->
                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <table id="daily_attendance_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>User ID</th>
                                        <th>Student Name</th>
                                        <th>Punch IN</th>
                                        <th>Punch OUT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                        <td>1</td>
                                        <td>Praveen</td>
                                        <td>ADH026</td>
                                        <td>Moved to native place</td>
                                        <td class=" text-center">
                                            <a href="javascript: void(0);" class="btn btn-success ms-2"><i class=" uil-edit"></i></a>
                                            <a href="javascript: void(0);" class="btn btn-danger ms-2"><i class="uil-trash-alt"></i></a>
                                        </td>
                                    </tr> -->
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