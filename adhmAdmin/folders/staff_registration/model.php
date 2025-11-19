<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}

$btn_text = "Save";
$btn_action = "create";

$unique_id = "";


$is_active = 1;

//$dob=date("Y-m-d");
$doj=date("Y-m-d");
if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;

        $where = [
            "unique_id" => $unique_id
        ];

        $table = "staff_registration";

        $columns = [

            "staff_name",
            "staff_id",
            "dob",
            "father_name",
            "gender_name",
            "age",
            "academic_year",
            "mobile_num",
            "district_name",
            "taluk_name",
            "address",
            "aadhaar_no",
            "email_id",
            "doj",
            "department",
            "designation",
            "district_office",
            "taluk_office",
            "hostel_name",
            "user_name",
            "password",
            "confirm_password",
            "biometric_id",
            "user_type",
            "file_names",
            "file_org_names",
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

            $staff_name          = $result_values[0]["staff_name"];
            $staff_id          = $result_values[0]["staff_id"];
            $dob          = $result_values[0]["dob"];

            $father_name         = $result_values[0]["father_name"];
            $gender_name         = $result_values[0]["gender_name"];
            $age                 = $result_values[0]["age"];
            $academic_year       = $result_values[0]["academic_year"];

            $mobile_num          = $result_values[0]["mobile_num"];

            $district_name       = $result_values[0]["district_name"];
            // $hostel_name         = $result_values[0]["hostel_name"];

            $taluk_name          = $result_values[0]["taluk_name"];
            $address             = $result_values[0]["address"];

            $aadhaar_no          = $result_values[0]["aadhaar_no"];
            $email_id            = $result_values[0]["email_id"];

            $doj                 = $result_values[0]["doj"];
            $department          = $result_values[0]["department"];

            $designation         = $result_values[0]["designation"];
            // $hostel_name         = $result_values[0]["hostel_name"];

            $district_office      = $result_values[0]["district_office"];

            $taluk_office         = $result_values[0]["taluk_office"];

            $hostel_name         = $result_values[0]["hostel_name"];

            $user_name          =  $result_values[0]["user_name"];

            $passwords         =  $result_values[0]["password"];

            $confirm_password          =  $result_values[0]["confirm_password"];

            $biometric_id          =  $result_values[0]["biometric_id"];

            $user_type          =  $result_values[0]["user_type"];

            $file_names          =  $result_values[0]["file_names"];

            $file_org_names          =  $result_values[0]["file_org_names"];



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

$active_status_options = active_status($is_active);



$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$district_name_list_new = district_name();
$district_name_list_new = select_option($district_name_list_new, "Select District", $district_office);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$taluk_name_list_new = taluk_name();
$taluk_name_list_new = select_option($taluk_name_list_new, "Select Taluk", $taluk_office);

$exp_hostel_name = explode(',',$hostel_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option($hostel_name_list, "Select Hostel", $exp_hostel_name);

$designation_name_list = designation();
$designation_name_list = select_option($designation_name_list, "Select designation", $designation_name);

$admin_name_list = admin_name();
$admin_name_list    = select_option($admin_name_list, "Select designation",$designation);

$academic_year = academic_year();
$academic_year = select_option_acc($academic_year, "Select Academic Year", $academic_year);

$user_type_list = user_type();
$user_type_list =  select_option($user_type_list, "Select User",$user_type);



$gender_type_option        = [
    "1" => [
        "unique_id" => "male",
        "value"     => "male",
    ],
    "2" => [
        "unique_id" => "female",
        "value"     => "female",
    ]
];

$gender_type_options        = select_option( $gender_type_option, "Select gender Type", $gender_name);



?>

<style>
    .fm {
        margin-bottom: 10px;
    }

    .imagePreview {
        width: 100px;
        height: 100px;
        border: 1px solid #ccc;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 10px;
    }

    .imagePreview img {
        max-width: 100%;
        max-height: 100%;
    }
    #error_message{
        color:red;
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
                        <h4 class="page-title">Staff Registration</h4>
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
                                <!-- <input type="text" value="<?=$hostel_name;?>"> -->
                                <div hidden>
                                    <select name="academic_year" id="academic_year" class="select2 form-control" style="visibility: hidden;" disabled required>
                                        <?php echo $academic_year;?>
                                    </select>
                                </div>

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
                                                            <input type="text" oninput="valid_user_name(this)" id="staff_name" name="staff_name" required class="form-control" value="<?php echo $staff_name; ?>">
                                                        </div>
                                                        <div class="col-md-6 fm">
                                                            <label for="simpleinput" class="form-label">Staff ID</label>
                                                            <input type="text" oninput="off_id(this)" id="staff_id" name="staff_id" required class="form-control" value="<?php echo $staff_id; ?>">
                                                        </div>
                                                        <div class="col-md-6 fm">
                                                            <label for="simpleinput" class="form-label">Father's
                                                                Name</label>
                                                            <input type="text"  oninput="valid_user_name(this)"id="father_name" name="father_name" class="form-control" required value="<?php echo $father_name; ?>">
                                                        </div>
                                                        <div class="col-md-6 fm">
                                                            <label for="simpleinput" class="form-label">Gender</label>
                                                            <select class="select2 form-control" id="gender_name" name="gender_name"  required>
                                                                <?php echo $gender_type_options; ?>
                                                            </select>

                                                        </div>
                                                        <div class="col-md-6 fm">
                                                            <label for="simpleinput" class="form-label">DOB</label>
                                                            <input type="date" id="dob" required name="dob" class="form-control" onchange="calculateAge()" value="<?php echo $dob;?>">
                                                        </div>
                                                        <div class="col-md-6 fm">
                                                            <label for="simpleinput" class="form-label">Age</label>
                                                            <input type="text" readonly id="age" name="age" required class="form-control" required value="<?php echo $age; ?>">
                                                        </div>
                                                        <div class="col-md-6 fm ">
                                                            <label for="simpleinput" class="form-label">Mobile No</label>
                                                            <input type="text" oninput="valid_mobile_number(this)" id="mobile_num" name="mobile_num" required oninput="check_phone_number()" class="form-control" oninput="number_only(this)" value="<?php echo $mobile_num; ?>" maxlength="10" minlength="10">
                                                        </div>
                                                        <div class="col-md-6 fm ">
                                                            <label for="example-select" class="form-label">District</label>
                                                            <select class="select2 form-control" id="district_name" name="district_name" onchange="taluk()" required>
                                                                <?php echo $district_name_list; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 fm ">
                                                            <label for="example-select" class="form-label">Taluk
                                                                Name</label>
                                                            <select class="select2 form-control" id="taluk_name" name="taluk_name" required >
                                                                <?php echo $taluk_name_list; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 fm ">
                                                            <label for="address" class="form-label">Address</label>
                                                            <textarea type="text"  oninput="valid_address(this)" id="address" name="address" required class="form-control"  required> <?php echo $address; ?></textarea>
                                                        </div>
                                                        <!-- <div class="col-md-6 fm ">
                                                            <label for="simpleinput" class="form-label">Aadhar
                                                                Number</label> -->
                                                            <!-- <input type="number" id="aadhaar_no" name="aadhaar_no"required
                                                           min="12" max="12" onchange="validate_adhaar_number()"  class="form-control" >
                                                         -->
                                                            <!-- <input type="text" id="aadhaar_no" name="aadhaar_no" maxlength="12" minlength="12"  class="form-control" oninput="valid_aadhar_number(this)" onkeyup="onAadharKeyPress()" value="<?php echo $aadhaar_no; ?>" required>
                                                            <span id="aadhaarError" oninput="valid_aadhar_number(this)" class="error" style="color:red"></span>
                                                        </div> -->
                                                        <div class="col-md-6 fm ">
                                                            <div class="">
                                                                <label class="form-label" for="example-select">E-mail
                                                                    Id</label>
                                                                <input type="email" oninput="mail_valid(this)" class="form-control" id="email_id" required name="email_id"  value="<?php echo $email_id; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 fm">
                                                            <label for="biometric_id" class="form-label">Biometric Id</label>
                                                            <input type="text" oninput="off_id(this)" class="form-control" id="biometric_id" name="biometric_id" required value="<?php echo $biometric_id; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 fm">
                                                            <label for="test_file1" class="form-label">Image Upload:</label>
                                                            <input type="file" class="form-control" id="test_file1" name="test_file1" accept="image/*">
                                                            <input type="hidden" id="img_file" name="img_file" value="<?=$file_names;?>">
                                                        </div>
                                                        <!-- <span id="error_message"></span>

                                                        <div class="col-md-3 fm">
                                                            <?php if ($file_names == '') { ?>
                                                                <img class="imagePreview" id="cm_image_preview" src='uploads/download.png'></img>
                                                            <?php } else if ($file_names != '') { ?>
                                                                <img class="imagePreview" id="cm_image_preview" src='uploads/image_uplode/tn_cm/<?php echo $file_names; ?>'></img>
                                                            <?php } ?>
                                                        </div> -->
                                                    </div>
                                                    <ul class="list-inline wizard mb-0">
                                                        <li class="next list-inline-item float-end">
                                                        <?php echo btn_cancel($btn_cancel); ?>
                                                            <a href="javascript:void(0);" onclick="nextTab()" class="btn btn-info">Next<i class="mdi mdi-arrow-right ms-1"></i></a>
                                                        </li>
                                                    </ul>

                                            </div>
                                            <!-- <i class="mdi mdi-arrow-right ms-1"> -->
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="arrow-contact" role="tabpanel">

                                        <div class="">
                                            <!-- <table id="invoice_datatable" class="table table-bordered nowrap table-striped align-middle"> -->
                                                <div class="row">
                                                    <div class="col-md-6 fm">
                                                        <label for="simpleinput" class="form-label">Date of
                                                            Joining</label>
                                                        <input type="date" class="form-control" id="doj" name="doj" required value="<?php echo $doj; ?>">
                                                    </div>
                                                    <div class="col-md-6 fm">
                                                        <label for="simpleinput" class="form-label">Department</label>
                                                        <input type="text" oninput="valid_user_name(this)" id="department" name="department" class="form-control" required value="<?php echo $department; ?>">

                                                        <input type="hidden" id="hostel_arr" name="hostel_arr" class="form-control"  value="<?php echo $hostel_arr; ?>">

                                                        <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                                        <input type="hidden" id="unique_id" name="unique_id" class="form-control"  value="<?php echo $unique_id; ?>">

                                                    </div>

                                                    <div class="col-md-6 fm">
                                                        <label for="simpleinput" class="form-label">Designation</label>
                                                        <select name="department_new" id="department_new" required class="select2 form-control" onchange="staff_filter()">
                                                            <?php echo $admin_name_list; ?>

                                                        </select>
                                                    </div>                                                

                                              
                                                    <div class="col-md-6 fm" id="district_div">
                                                        <label for="simpleinput" class="form-label">District
                                                            Name</label>
                                                        <select class="select2 form-control" id="district_name_new" name="district_name_new" onchange="get_taluk()" required>
                                                            <?php echo $district_name_list_new;?>
                                                        </select>
                                                    </div>
                                              
                                                    <div class="col-md-6 fm" id="taluk_div">
                                                        <label for="simpleinput" class="form-label">Taluk Name</label>
                                                        <select id="taluk_name_new" name="taluk_name_new" class="select2 form-control" required onchange="get_hostel_name()">
                                                            <?php echo $taluk_name_list_new; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 fm" id="hostel_div_warden">
                                                        <label for="simpleinput" class="form-label">Hostel Name</label>
                                                        <select name="hostel_warden" id="hostel_warden" required class="select2 form-control">
                                                            <?php echo $hostel_name_list; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 fm" id="hostel_div">
                                                        <label for="simpleinput" class="form-label">Hostel Name</label>
                                                        <select name="hostel_tash" id="hostel_tash" required onchange="get_tashil()" class="select2 form-control" multiple>
                                                            <?php echo $hostel_name_list; ?>
                                                        </select>
                                                        <input type="hidden"  name="host_tash" id="host_tash">
                                                    </div>
                                                </div>
                                                                
                                                <ul class="list-inline wizard mb-0">
                                                    <li class="next list-inline-item float-end">
                                                    <?php echo btn_cancel($btn_cancel); ?>
                                                        <a href="javascript:void(0);" onclick="nextTab()" class="btn btn-info">Next<i class="mdi mdi-arrow-right ms-1"></i></a>
                                                    </li>
                                                </ul>
                                        </div>
                                    </div>
                                   

                                    <div class="tab-pane fade" id="arrow-third" role="tabpanel">

                                        <div class="">
                                            <table id="invoice_datatable" class="table table-bordered nowrap table-striped align-middle">
                                                <div class="row">

                                                    <div class="col-md-6 fm ">
                                                        <label for="example-select" class="form-label">User Type</label>
                                                        <select class="select2 form-control" id="user_type" name="user_type" required>
                                                            <?php echo $user_type_list; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 fm"></div>
                                                    <div class="col-md-6 fm">
                                                        <label for="simpleinput" class="form-label">User Name</label>
                                                        <input type="text" oninput="valid_user_name(this)" class="form-control" id="user_name" name="user_name" value="<?php echo $user_name; ?>" required>
                                                    </div>
                                                    <div class="col-md-6 fm"></div>

                                                    <div class="col-md-6 fm">
                                                        <label for="simpleinput" class="form-label">Password</label>
                                                        <div class="input-group input-group-merge">
                                                            <input type="password" id="password" class="form-control"  oninput="valid_password(this)" placeholder="" value="<?php echo $passwords;?>" onkeyup="password_vali()" minlength="8">
                                                            <div class="input-group-text" data-password="false">
                                                                <span class="password-eye"></span>
                                                            </div>
                                                            
                                                        </div>
                                                        <span id="password_vali" class="error" style="color:red"></span>
                                                        <!--<input type="text" id="password" name="password"  class="form-control" value= "<?php echo $password; ?>">-->
                                                        <!-- <button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility('password')"><i id="eyeIcon" class="bi bi-eye"></i></button> -->
                                                    </div>
                                                    <div class="col-md-6 fm"></div>

                                                    <div class="col-md-6 fm">
                                                        <label for="simpleinput" class="form-label">Confirm Password</label>
                                                        <div class="input-group input-group-merge">
                                                            <input type="password" id="confirm_password" class="form-control" placeholder="" oninput="valid_password(this)" value="<?php echo $confirm_password;?>" onkeyup="validatePassword()">
                                                            <div class="input-group-text" data-password="false">
                                                                <span class="password-eye" ></span>
                                                            </div>
                                                        </div>
                                                        <span id="passwordError" class="error" style="color:red"></span>
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

            <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>

            <script>
                $(document).ready(function() {
                    $('#district_div').hide();
                    $('#taluk_div').hide();
                    $('#hostel_div').hide();
                    $('#hostel_div_warden').hide();
                });

                // function taluk() {
                //     var district_name = $('#district_name').val();
                //     var data = "district_name=" + district_name + "&action=district_name";
                //     var ajax_url = sessionStorage.getItem("folder_crud_link");
                //     $.ajax({
                //         type: "POST",
                //         url: ajax_url,
                //         data: data,
                //         success: function (data) {
                //             if (data) {
                //                 $("#taluk_name").html(data);
                //             }
                //         }
                //     });
                // }
                // function get_hostel() {
                //     var taluk_name = $('#taluk_name').val();
                //     var data = "taluk_name=" + taluk_name + "&action=get_hostel_by_taluk_name";
                //     var ajax_url = sessionStorage.getItem("folder_crud_link");
                //     $.ajax({
                //         type: "POST",
                //         url: ajax_url,
                //         data: data,
                //         success: function (data) {
                //             if (data) {
                //                 // $("#hostel_tash").html(data);
                //                 $("#hostel_warden").html(data);
                //             }
                //         }
                //     });
                // }

                function staff_filter() {

                    var value = $('#department_new').val();

                    // var designation = $('#department_new').val();
                    // if (designation=="65f095651367622307"){
                    //     var tash = $('hostel_tash').val();
                    //     console.log(tash);
                    // }
                    // else{
                    //     var warden =
                    // }
                    // alert(hi);

                    // // Show fields based on the selected option
                    if (value === "65f3191aa725518258") {

                        $('#district_div').show();
                        $('#taluk_div').show();
                        $('#hostel_div_warden').show();
                        $('#hostel_div').hide();

                    } else if (value === "65f3195bb6bcf35260") {
                        $('#district_div').show();
                        $('#taluk_div').show();
                        $('#hostel_div').show();
                        $('#hostel_div_warden').hide();

                    } else if (value === "65f31975f0ce678724") {
                        $('#district_div').show();
                        $('#taluk_div').hide();
                        $('#hostel_div').hide();
                        $('#hostel_div_warden').hide();
                    } else if (value === "655856887fcb671064") {
                        $('#district_div').hide();
                        $('#taluk_div').hide();
                        $('#hostel_div').hide();
                        $('#hostel_div_warden').hide();
                    } else if (value === "65f095814493b21576") {
                        $('#district_div').hide();
                        $('#taluk_div').hide();
                        $('#hostel_div').hide();
                        $('#hostel_div_warden').hide();
                    } else if (value === "6558567457c7361273") {
                        $('#district_div').hide();
                        $('#taluk_div').hide();
                        $('#hostel_div').hide();
                        $('#hostel_div_warden').hide();
                    }



                    // Show fields based on the selected option
                    //  if (value === "65f09520f357079230" || value === "65f095651367622307") {
                    //     $('#district_name_new').show();
                    //     $('#taluk_name_new').show();
                    //     $('#hostel_new').show();

                    //     // Add the 'multiple' attribute if value === "2"
                    //     if (value === "65f095651367622307") {
                    //         $('#district_name_new').prop('multiple', true);
                    //     } else {
                    //         // Remove the 'multiple' attribute if value is not "2"
                    //         $('#district_name_new').prop('multiple', false);
                    //     }

                    //     else if(value === "65f09574db69186006") {
                    //     $('#district_div').show();
                    //     $('#taluk_div').hide();
                    //     $('#hostel_div').hide();

                    // }
                    //     if (value === "65f09520f357079230" || value === "65f095651367622307") {
                    //     $('#district_div').show();
                    //     $('#taluk_div').show();
                    //     $('#hostel_div_warden').show();

                    //     // Add the 'multiple' attribute if value === "2"
                    //     if (value === "65f095651367622307") {
                    //         $('#hostel_div').prop('multiple', true);
                    //     } else {
                    //         // Remove the 'multiple' attribute if value is not "2"
                    //         $('#hostel_div').prop('multiple', false);
                    //     }
                    // } else if (value === "65f09574db69186006") {
                    //     $('#district_div').show();
                    //     $('#taluk_div').hide();
                    //     $('#hostel_div').hide();
                    // }


                }


                test_file1.onchange = evt => {
                    const [file] = test_file1.files;
                    if (file) {
                        cm_image_preview.src = URL.createObjectURL(file);
                    } else {
                        cm_image_preview.src = 'uploads/download.png';
                    }
                };
            </script>