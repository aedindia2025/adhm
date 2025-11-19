<?php 

$taluk_name = $_SESSION["taluk_id"];
    
$district_name = $_SESSION["district_id"];

$hostel_name = $_SESSION["hostel_id"];

$district_name_list = district_name($_SESSION['district_id']);
$district_name_list = select_option($district_name_list, "Select District",$district_name);

$taluk_name_list = taluk_name('',$district_name);
$taluk_name_list = select_option($taluk_name_list, "Select Taluk",$taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option($hostel_name_list, "Select Hostel",$hostel_name);

$amc_name_list = academic_year();
$amc_name_list = select_option_acc($amc_name_list);



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
                                <!-- <?php echo btn_add($btn_add); ?> -->
                            </form>
                        </div>
                        <h4 class="page-title">Feedback Report</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        <div class="row">
                        <div class="col-2">
                            <label for="example-select" class="form-label">Academic Year:</label>
                           <input type ="text" readonly name="amc_name" id="amc_name" class="form-control" value="2023-2024">
                        </div>

                        <div class="col-3">
                            <label for="example-select" class="form-label">District Name</label>
                            <select name="district_name" id="district_name" disabled class="select2 form-control" onchange="get_taluk()" required>
                                   <?php echo $district_name_list;?> 
                            </select>

                            
                        </div>

                        <div class="col-3">
                            <label for="example-select" class="form-label">Taluk Name</label>
                            <select name="taluk_name" id="taluk_name" class="select2 form-control" onchange="get_hostel()" required>
                                                    <?php echo $taluk_name_list;?>
                                <!-- <option value="0">Select Taluk</option>
                                <option value="1">Tindal</option>
                                <option value="2">Saravanampatti</option>
                                <option value="3">Paladam</option> -->

                            </select>
                            
                        </div>

                        <div class="col-2">
                            <label for="example-select" class="form-label">Hostel Name</label>
                            <select name="hostel_name" id="hostel_name" class="select2 form-control" required>
                            <?php echo $hostel_name_list?>
                               

                            </select>
                            
                        </div>

                        <div class="col-md-2 align-self-center mt-3">
                    <div class="page-title-right">
                    
                                  <button class="btn btn-primary" onclick="go_filter()">GO</button>
                        </a>
                    </div>
                </div>
                        </div>
                       
             <div class="row p-2 mt-3">
                <!-- <div class="col-12">
                    <div class="card">
                        <div class="card-body"> --> 
                        <table id="feedback_creation_datatable" class="table dt-responsive nowrap w-100  ">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Date</th>
                                        <th>Student Name</th>
                                        <th>District Name</th>
                                        <th>Taluk Name</th>
                                        <th>Hostel Name</th>
                                        <th>Feedback </th>
                                        <th>Rating</th>
                                        <th>Description</th>
                                        <!-- <th>Status</th>
                                        <th>Action</th> -->
                                    </tr>
                                
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>            
                </div>                    
            </div>
            <script>
                
  </div>

</script>