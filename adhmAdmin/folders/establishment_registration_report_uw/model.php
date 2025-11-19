<?php

$btn_text = "Save";
$btn_action = "create";


$unique_id = "";





// $establishment_filter = establishment_filter();


$is_active = 1;

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "establishment_registration";

        $columns = [
            "staff_name",
            "father_name",
            "gender_name",
            "age",
            "mobile_num",
            "district_name",
            "taluk_name",
            "address",
            "aadhaar_no",
            "email_id",
            "biometric_id",
            "doj",
            "department",
            "designation",
            "district_office",
            "taluk_office",
            "user_name",
            "password",
            "confirm_password",
            // "hostel_office",
            // "is_active" 
            "unique_id"

        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);die();
        if ($result_values->status) {

            $result_values = $result_values->data;

            $staff_name       = $result_values[0]["staff_name"];
            $father_name          = $result_values[0]["father_name"];
            $gender_name         = $result_values[0]["gender_name"];
            $age       = $result_values[0]["age"];

            $mobile_num          = $result_values[0]["mobile_num"];

            $district_name       = $result_values[0]["district_name"];
            // $hostel_name         = $result_values[0]["hostel_name"];

            $taluk_name          = $result_values[0]["taluk_name"];

            $address             = $result_values[0]["address"];

            $aadhaar_no          = $result_values[0]["aadhaar_no"];
            $email_id         = $result_values[0]["email_id"];
            $biometric_id         = $result_values[0]["biometric_id"];


            $doj       = $result_values[0]["doj"];
            $department          = $result_values[0]["department"];

            $designation       = $result_values[0]["designation"];
            // $hostel_name         = $result_values[0]["hostel_name"];

            $district_office          = $result_values[0]["district_office"];
            $taluk_office         = $result_values[0]["taluk_office"];

            $hostel_office         = $result_values[0]["hostel_office"];

            $user_name        = $result_values[0]["user_name"];

            $password        = $result_values[0]["password"];
            $confirm_password         = $result_values[0]["confirm_password"];

            $unique_id           = $result_values[0]["unique_id"];


            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option($hostel_name_list, "Select Hostel", $hostel_name);

$establishment_name_list = establishment_name();
$establishment_name_list = select_option($establishment_name_list, "Select Establishment");

$active_status_options = active_status($is_active);







$district_name = $_SESSION["district_id"];

?>
<?php

$btn_back   = "Back";
?>

<style>
    .fm {
        margin-bottom: 10px;
    }
</style>
<!-- Modal with form -->
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Establishment Registration</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <!-- <div class="row"> -->
            <div class="col-12">
                <div class="row">
                    <div class="">
                        <div class="card">
                            <div class="card-body">

                                <ul class="nav nav-pills arrow-navtabs bg-light mb-3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#arrow-overview" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-home-variant"></i></span>
                                            <span class="d-none d-sm-block">Personal Details</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#arrow-contact" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-email"></i></span>
                                            <span class="d-none d-sm-block">Office Details</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#arrow-third" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-email"></i></span>
                                            <span class="d-none d-sm-block">User Credentials</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="arrow-overview" role="tabpanel">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <!-- <div class="row mb-3"> -->
                                                <form class="was-validated" autocomplete="off">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6 fm">
                                                            <label for="simpleinput" class="form-label">Staff Name</label>
                                                            <input type="text" id="staff_name" name="staff_name" required class="form-control" value="<?php echo $staff_name; ?>">
                                                        </div>

                                                        <div class="col-md-6 fm">
                                                            <label for="simpleinput" class="form-label">Father's
                                                                Name</label>
                                                            <input type="text" id="father_name" name="father_name" class="form-control" required value="<?php echo $father_name; ?>">
                                                        </div>
                                                        <div class="col-md-6 fm">
                                                            <label for="simpleinput" class="form-label">Gender</label>
                                                            <select class="select2 form-control" id="gender_name" name="gender_name" value=required>
                                                                <option value="select">Select Gender</option>
                                                                <option value="male" <?php if ($gender_name === 'male') {
                                                                                            echo 'selected';
                                                                                        } ?>>Male</option>
                                                                <option value="female" <?php if ($gender_name === 'female') {
                                                                                            echo 'selected';
                                                                                        } ?>>Female</option>

                                                            </select>

                                                        </div>
                                                        <div class="col-md-6 fm">
                                                            <label for="simpleinput" class="form-label">DOB</label>
                                                            <input type="date" id="dob" required name="dob" class="form-control" onchange="calculateAge()">
                                                        </div>
                                                        <div class="col-md-6 fm">
                                                            <label for="simpleinput" class="form-label">Age</label>
                                                            <input type="text" readonly id="age" name="age" required class="form-control" required value="<?php echo $age; ?>">
                                                            <input type="date" hidden id="current_date" name="current_date">

                                                            <input hidden id="unique_id" name="unique_id" value="<?php echo $unique_id; ?>">
                                                        </div>
                                                        <div class="col-md-6 fm ">
                                                            <label for="simpleinput" class="form-label">Mobile No</label>
                                                            <input type="number" id="mobile_num" name="mobile_num" required oninput="check_phone_number()" class="form-control" value="<?php echo $mobile_num; ?>">
                                                        </div>
                                                        <div class="col-md-6 fm ">
                                                            <label for="example-select" class="form-label">District</label>
                                                            <select class="select2 form-control" id="district_name" name="district_name" onchange="taluk()" value="<?php echo $district_name; ?>" required>
                                                                <?php echo $district_name_list; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 fm ">
                                                            <label for="example-select" class="form-label">Taulk
                                                                Name</label>
                                                            <select class="select2 form-control" id="taluk_name" name="taluk_name" required onchange="get_hostel()">
                                                                <?php echo $taluk_name_list; ?>
                                                            </select>

                                                        </div>
                                                        <div class="col-md-6 fm ">
                                                            <label for="simpleinput" class="form-label">Address</label>
                                                            <textarea type="text" id="address" name="address" required class="form-control"> <?php echo $address; ?> </textarea>
                                                        </div>
                                                        <div class="col-md-6 fm ">
                                                            <label for="simpleinput" class="form-label">Aadhar
                                                                Number</label>
                                                            <input type="text" id="aadhaar_no" required name="aadhaar_no" max="12" class="form-control" value="<?php echo $aadhaar_no; ?>" maxlength="12" minlength="12" onkeyup="onAadharKeyPress()">
                                                        </div>
                                                        <div class="col-md-6 fm ">
                                                            <div class="">
                                                                <label class="form-label" for="example-select">E-mail
                                                                    Id</label>
                                                                <input type="email" class="form-control" id="email_id" required name="email_id" value="<?php echo $email_id; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 fm">
                                                            <label for="simpleinput" class="form-label">Biometric Id</label>
                                                            <input type="text" class="form-control" id="biometric_id" name="biometric_id" required value="<?php echo $biometric_id; ?>">
                                                        </div>
                                                    </div>
                                                    <div >
                                                    
                                                    </div>
                                                    <ul class="list-inline wizard mb-0">
                                                    <button type="button"  onclick="goToListPage()" class="btn btn-info">Back</button>

                                                        <li class="next list-inline-item float-end">
                                                            <a href="javascript:void(0);" onclick="nextTab()" class="btn btn-info">Next<i class="mdi mdi-arrow-right ms-1"></i></a>
                                                        </li>
                                                    </ul>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="tab-pane" id="arrow-contact" role="tabpanel">

                                        <div class="">
                                            <table id="invoice_datatable" class="table table-bordered nowrap table-striped align-middle">
                                                <div class="row">
                                                    <div class="col-md-3 fm">
                                                        <label for="simpleinput" class="form-label">District Name:</label>&nbsp;&nbsp;<?php echo $_SESSION["district_name"]; ?>
                                                        
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="simpleinput" class="form-label">Taluk Name:</label>&nbsp;&nbsp;<?php echo $_SESSION["taluk_name"]; ?>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="simpleinput" class="form-label">Hostel Name:</label>&nbsp;&nbsp;<?php echo $_SESSION["hostel_name"]; ?>
                                                    </div>
                                                </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 fm">
                                                <label for="simpleinput" class="form-label">Date of
                                                    Joining</label>
                                                <input type="date" class="form-control" id="doj" name="doj" required value="<?php echo $doj; ?>">
                                            </div>
                                            <div class="col-md-6 fm">
                                                <label for="simpleinput" class="form-label">Department</label>
                                                <input type="text" id="department" name="department" class="form-control" required value="<?php echo $department; ?>">

                                                <!-- <input type="hidden" id="hostel_arr" name="hostel_arr" class="form-control" required value="<?php echo $hostel_arr; ?>"> -->

                                                
                                                <input type="hidden" id="district_office" name="district_office" class="form-control" required value="<?php echo $_SESSION["district_id"];?>">
                                                
                                                <input type="hidden" id="taluk_office" name="taluk_office" class="form-control" required value="<?php echo $_SESSION["taluk_id"];?>">
                                                
                                                <input type="hidden" id="hostel_office" name="hostel_office" class="form-control" required value="<?php echo $_SESSION["hostel_id"];?>">

                                            </div>

                                            <div class="col-md-6 fm">
                                                <label for="simpleinput" class="form-label">Designation</label>
                                                <select name="department_new" id="department_new" required class="select2 form-control" <?php echo $establishment_name_list; ?>>

                                                </select>
                                            </div>


                                        </div>
                                      


                                        
                                        <ul class="list-inline wizard mb-0">
                                            <li class="next list-inline-item float-end">
                                                <a href="javascript:void(0);" onclick="nextTab()" class="btn btn-info">Next<i class="mdi mdi-arrow-right ms-1"></i></a>
                                            </li>
                                        </ul>

                                        <!-- <div class="col-md-6 fm" id="hostel_div">
                                            <label for="simpleinput" class="form-label">Hostel Name</label>
                                            <select name="hostel_tash" id="hostel_tash" required onchange="get_tashil()" class="select2 form-control" multiple>
                                                <?php echo $hostel_name_list; ?>
                                            </select> -->
                                            <!-- <input type="text" hidden name="host_tash" id="host_tash">  -->
                                        </div>
                                    </div>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="arrow-third" role="tabpanel">

                                <div class="">
                                    <table id="invoice_datatable" class="table table-bordered nowrap table-striped align-middle">
                                        <div class="row">
                                            <div class="col-md-6 fm">
                                                <label for="simpleinput" class="form-label">User Name</label>
                                                <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $user_name; ?>" required>
                                            </div>
                                            <div class="col-md-6 fm"></div>

                                            <div class="col-md-6 fm">
                                                <label for="simpleinput" class="form-label">Password</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="password" id="password" class="form-control" placeholder="" value="<?php echo $password; ?>">
                                                    <div class="input-group-text" data-password="false">
                                                        <span class="password-eye"></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6 fm"></div>

                                            <div class="col-md-6 fm">
                                                <label for="simpleinput" class="form-label">Confirm Password</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="password" id="confirm_password" class="form-control" placeholder="" value="<?php echo $confirm_password; ?>">
                                                    <div class="input-group-text" data-password="false">
                                                        <span class="password-eye"></span>
                                                    </div>
                                                </div>
                                                <!--<input type="text" id="confirm_password" minlength="8" name="confirm_password"
                    class="form-control"  value= "<?php echo $confirm_password; ?>" onkeyup="password_check()">--->
                                                <!-- <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword" onclick="togglePasswordVisibility('confirm_password')"><i id="confirmEyeIcon" class="bi bi-eye"></i></button> -->

                                            </div>
                                            <div class="col-md-6 fm"></div>


                                        </div>






                                        <div class="btns mt-3">
                                            <?php echo btn_cancel($btn_cancel); ?>
                                            <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
    </div>