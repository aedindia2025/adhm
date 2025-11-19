<?php
    // $ses_district_office    = $_SESSION["district_id"];
    // $ses_district_name      = $_SESSION["district_name"];
    // $ses_taluk_name         = $_SESSION['taluk_name'];
    // $ses_taluk_id           = $_SESSION['taluk_id'];    
    // $ses_designation        = $_SESSION["designation"];       
    // $ses_designation_name   = $_SESSION["designation_name"]; 


    

    
        $taluk_name = $_SESSION["taluk_id"];
    
        $district_name = $_SESSION["district_id"];

        $hostel_name = $_SESSION["hostel_id"];
    
    
    // if($from_hostel_name != ''){
    //     $from_hostel_name = $from_hostel_name;
    // }else{
    //     $from_hostel_name = $_SESSION["hostel_id"];
    // }

    $district_name_list     = district_name($district_name);
    $district_name_list     = select_option($district_name_list, "Select District",$district_name);

    $taluk_name_list        = taluk_name('',$district_name);
    $taluk_name_list        = select_option($taluk_name_list,"Select Taluk",$taluk_name_list);

//     $from_taluk_name_list = taluk_name('',$district_name);
// $from_taluk_name_list = select_option($from_taluk_name_list,"Select From Taluk",$from_taluk_name);

    $hostel_name_list       = hostel_name();
    $hostel_name_list       = select_option_host($hostel_name_list, "Select Hostel",$hostel_name_list);

        // $hostel_options = hostel_name();
        // $hostel_name_options = select_option($hostel_options, "Select Hostel");

    $desination_type_list   = hostel_designation();
    $desination_type_list   = select_option($desination_type_list,"select Designation");

    $academic_year          = academic_year();
    $academic_year          = select_option_acc($academic_year, "Select Academic Year");

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
                        <h4 class="page-title">Establishment</h4>
                    </div>
                </div>
            </div>
            <div class="row mb-2">

<div class="col-md-3 fm">
    <label for="example-select" class="form-label">Academic Year:</label>
    <!-- <input type ="text" readonly name="amc_name" id="amc_name" class="form-control" value="<?php echo $amc_name_list; ?>"> -->

    <select name="academic_year" id="academic_year" class="select2 form-control" disabled required>
        <?php echo $academic_year; ?>
    </select>
</div>
<div class="col-md-3 fm">
    <label class="form-label" for="example-select">District Name</label>
    <!-- <input type="text" id="district_name" name="district_name" value="<?php echo $_SESSION["staff_"];?>" onchange="taluk()"> -->
    <select name="district_name" id="district_name" class="select2 form-control" disabled  required>
    <?php echo  $district_name_list;?>
    </select>
  
</div>
<div class="col-md-3 fm">
    <label class="form-label" for="example-select">Taluk Name</label>
    <select class="select2 form-control" id="taluk_name" name="taluk_name" onchange="get_hostel()">
        <?php echo $taluk_name_list;?>
    </select>
</div>
<div class="col-md-3 fm">
    <label class="form-label" for="example-select">Hostel Name</label>
    <select class="select2 form-control" id="hostel_name" name="hostel_name">
        <?php echo $hostel_name_list;?>
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
                            <div class="row">
                                <table id="establishment_registration_datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>S.no</th>
                                            <th>Staff Name</th>
                                            <th>Designation</th>
                                            <th>Mobile Number</th>
                                            <th>District</th>
                                            <th>Taluk </th>
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
</div>
