<?php

$academic_year_options = academic_year();
$academic_year_options = select_option_acc($academic_year_options);


$district_name_list = district_name($_SESSION["district_id"]);
$district_name_list = select_option_acc($district_name_list, "Select District",$district_name);

$taluk_name_list = taluk_name("",$_SESSION["district_id"]);
$taluk_name_list = select_option($taluk_name_list, "Select Taluk",$taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel",$hostel_name);

$amc_name_list = academic_year($amc_name_list);
$amc_name_list = select_option_acc($amc_name_list);


$approval_status_option        = [
    "0" => [
        "unique_id" => "0",
        "value"     => "Pending",
    ],
    "1" => [
        "unique_id" => "1",
        "value"     => "Approved",
    ],
    "2" => [
        "unique_id" => "2",
        "value"     => "Rejected",
    ],

];
$approval_status_option        = select_option($approval_status_option, "Select", $approval_status);

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
                        <h4 class="page-title">Application Report</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        <div class="row">
                        <div class="col-3">
                            <label for="example-select" class="form-label">Academic Year:</label>
                           <!-- <input type ="text" readonly name="amc_name" id="amc_name" class="form-control" value="<?php echo $amc_name_list;?>"> -->
                           <select name="amc_name" id="amc_name" class="select2 form-control" disabled required><?php echo $amc_name_list;?> </select>
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
                                <!-- <option value="0">Select Taluk</option>
                                <option value="1">Tindal</option>
                                <option value="2">Saravanampatti</option>
                                <option value="3">Paladam</option> -->

                            </select>
                            
                        </div>

                        <div class="col-3">
                            <label for="example-select" class="form-label">Hostel Name</label>
                            <select name="hostel_name" id="hostel_name" class="select2 form-control" required>
                                
                            <?php echo $hostel_name_list?>
                               

                            </select>
                            
                        </div>
</div><br>
<div class="row">
                        <div class="col-3">
                            <label for="example-select" class="form-label">Status</label>
                            <select name="app_status" id="app_status" class="select2 form-control" required>
                            <option value="" selected>All</option>
                            <option value="0">Pending</option>
                            <option value="1">Approved</option>
                            <option value="2">Rejected</option>
                            
                               

                            </select>
                            
                        </div>

                        <div class="col-md-2 align-self-center">
                    <div class="page-title-right">
                    
                                  <button class="btn btn-primary" onclick="filter()" style="float:left;">GO</button>
                        </a>
                    </div>
                </div>
</div><br><br>
                            <table id="application_report_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Applied Date</th>
                                        <th>Application No</th>
                                        <th>Applicant Name</th>
                                        <th>Applied Hostel<br>District / Taluk</th>
                                        <th>Batch No / Batch Created Date</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                        <th>View Application</th>
                                        
                                        <!-- <th>Action</th> -->
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