<?php
    $ses_district_office    = $_SESSION["district_id"];
    $ses_district_name      = $_SESSION["district_name"];
    $ses_taluk_name         = $_SESSION['taluk_name'];
    $ses_taluk_id           = $_SESSION['taluk_id'];    
    $ses_designation        = $_SESSION["designation"];       
    $ses_designation_name   = $_SESSION["designation_name"]; 

    $district_name_list     = district_name();
    $district_name_list     = select_option($district_name_list, "Select District");

    $taluk_name_list        = taluk_name();
    $taluk_name_list        = select_option($taluk_name_list, "Select Taluk");

    $hostel_name_list       = hostel_name();
    $hostel_name_list       = select_option($hostel_name_list, "Select Hostel");

    $desination_type_list   = designation();
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
                                <?php echo btn_add($btn_add); ?>
                            </form>
                        </div>
                        <h4 class="page-title">Establishment</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
                                <table id="establishment_registration_datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>S.no</th>
                                            <th>Staff Name</th>
                                            <th>Designation</th>
                                            <th>Mobile Number</th>
                                            <th>District</th>
                                            <th>Taluk </th>
                                            <th>Action</th>
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
