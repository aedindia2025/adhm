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

$max_date = date('Y-m-d');

$doj = date("Y-m-d");

// $establishment_filter = establishment_filter();


$is_active = 1;

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        // $unique_id = $_GET["unique_id"];

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        $where = [
            "unique_id" => $unique_id
        ];

        $table = "establishment_registration";

        $columns = [
            "staff_name",
            "ifhrms_id",
            "gender_name",
           
            "dob",
            "mobile_num",
            "district_name",
           
            "aadhaar_no",
            
            "designation",
            "warden_category",
            "district_office",
            "taluk_office",
            "image_file",
          
            "unique_id"

        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        //print_r($result_values);die();
        if ($result_values->status) {

            $result_values = $result_values->data;

            $staff_name = $result_values[0]["staff_name"];
            $ifhrms_id = $result_values[0]["ifhrms_id"];
            $gender_name = $result_values[0]["gender_name"];
           

            $mobile_num = $result_values[0]["mobile_num"];

            $district_name = $result_values[0]["district_name"];
           
            
            $aadhaar_no = $result_values[0]["aadhaar_no"];
            $dob = $result_values[0]["dob"];
           
            $designation = $result_values[0]["designation"];
            $warden_category = $result_values[0]["warden_category"];
          

            $district_office = $result_values[0]["district_office"];
            $taluk_office = $result_values[0]["taluk_office"];

            $hostel_office = $result_values[0]["hostel_office"];
            $image_file = $result_values[0]["image_file"];

           

            $unique_id = $result_values[0]["unique_id"];


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
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);

$establishment_type_options = establishment_type();
$establishment_type_options = select_option($establishment_type_options, "Select Designation", $designation);


$warden_category_options = [
    "1" => [
        "unique_id" => "Second Grade Warden",
        "value" => "Second Grade Warden"
    ],
    "2" => [
        "unique_id" => "BT Warden",
        "value" => "BT Warden"
    ],
    "3" => [
        "unique_id" => "PG Warden",
        "value" => "PG Warden"
    ]
];
$warden_category_options = select_option($warden_category_options,'Select Warden Category',$warden_category);


$active_status_options = active_status($is_active);







$district_name = $_SESSION["district_id"];

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
                <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden"  id="unique_id" name="unique_id" value="<?php echo $unique_id; ?>">
                <input type="hidden"  id="img" name="img" value="<?php echo $image_file; ?>">
                <input type="hidden"  id="cur_date" name="cur_date" value="<?php echo date('Y-m-d'); ?>">

                <!-- end page title -->
                <!-- <div class="row"> -->
                <div class="col-12">
                    <div class="row">
                        <div class="">
                            <div class="card">
                                <div class="card-body">


                                    <!-- <ul class="nav nav-pills arrow-navtabs bg-light mb-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#arrow-overview"
                                                role="tab">
                                                <span class="d-block d-sm-none"><i
                                                        class="mdi mdi-home-variant"></i></span>
                                                <span class="d-none d-sm-block">Personal Details</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#arrow-contact" role="tab">
                                                <span class="d-block d-sm-none"><i class="mdi mdi-email"></i></span>
                                                <span class="d-none d-sm-block">Office Details</span>
                                            </a>
                                        </li> -->

                                        <!-- <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#arrow-third" role="tab">
                                                <span class="d-block d-sm-none"><i class="mdi mdi-email"></i></span>
                                                <span class="d-none d-sm-block">User Credentials</span>
                                            </a>
                                        </li> -->

                                    <!-- </ul> -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="arrow-overview" role="tabpanel">
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <!-- <div class="row mb-3"> -->
                                                    <form class="was-validated" autocomplete="off">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6 fm">
                                                                <label for="simpleinput" class="form-label">Full
                                                                    Name</label>
                                                                <input type="text" id="staff_name" name="staff_name"
                                                                    oninput="valid_user_name(this)" required
                                                                    class="form-control"
                                                                    value="<?php echo $staff_name; ?>">
                                                                <span class="error-message text-danger"
                                                                    id="error-staff-name"></span>
                                                            </div>
                                                            <input type="hidden" id="staff_count"
                                                                value="<?= $_SESSION['staff_count']; ?>">
                                                            <div class="col-md-6 fm">
                                                                <label for="simpleinput" class="form-label">IFHRMS ID</label>
                                                                <input type="text" id="ifhrms_id" name="ifhrms_id"
                                                                    class="form-control" minlength = "10" maxlength = '11'
                                                                    oninput="number_only(this)" required
                                                                    value="<?php echo $ifhrms_id; ?>">
                                                                <span class="error-message text-danger"
                                                                    id="error-ifhrms-id"></span>
                                                            </div>
                                                            <div class="col-md-6 fm">
                                                                <label for="simpleinput"
                                                                    class="form-label">Gender</label>
                                                                <select class="select2 form-control" id="gender_name"
                                                                    name="gender_name" required>
                                                                    <option value="">Select Gender</option>
                                                                    <option value="male" <?php if ($gender_name === 'male') {
                                                                        echo 'selected';
                                                                    } ?>>
                                                                        Male</option>
                                                                    <option value="female" <?php if ($gender_name === 'female') {
                                                                        echo 'selected';
                                                                    } ?>>Female</option>

                                                                </select>
                                                                <span class="error-message text-danger"
                                                                    id="error-gender-name"></span>

                                                            </div>
                                                            <div class="col-md-6 fm">
                                                                <label for="simpleinput" class="form-label">DOB</label>
                                                                <!-- <input type="date"  id="dateofbirth" name="dateofbirth" value="<? $dob; ?>"> -->
                                                                <input type="date" id="dob" name="dob"
                                                                    class="form-control" 
                                                                    value="<?php echo $dob; ?>" max="<?php echo $max_date ;?>" required>
                                                                <span class="error-message text-danger"
                                                                    id="error-dateofbirth"></span>
                                                            </div>
                                                            
                                                            <div class="col-md-6 fm ">
                                                                <label for="simpleinput" class="form-label">Mobile
                                                                    No</label>
                                                                <input type="text" id="mobile_num" name="mobile_num"
                                                                    required oninput="valid_mobile_number(this)"
                                                                    class="form-control" maxlength="10" minlength="10"
                                                                    value="<?php echo $mobile_num; ?>">
                                                                <span class="error-message text-danger"
                                                                    id="error-mobile-num"></span>
                                                            </div>
                                                            <div class="col-md-6 fm ">
                                                                <label for="example-select"
                                                                    class="form-label">Home District</label>
                                                                <select class="select2 form-control" id="district_name"
                                                                    name="district_name"    required>
                                                                    <?php echo $district_name_list; ?>
                                                                </select>
                                                                <span class="error-message text-danger"
                                                                    id="error-district-name"></span>
                                                            </div>
                                                            <div class="col-md-6 fm ">
                                                                <label for="example-select"
                                                                    class="form-label">Designation</label>
                                                                <select class="select2 form-control" id="designation"
                                                                    name="designation" onchange="get_warden_category()"    required>
                                                                    <?php echo $establishment_type_options; ?>
                                                                </select>
                                                                <span class="error-message text-danger"
                                                                    id="error-designation"></span>
                                                            </div>

                                                            <div class="col-md-6 fm " id="wardencategory_div" style="display:none">
                                                                <label for="example-select"
                                                                    class="form-label">Warden Category</label>
                                                                <select class="select2 form-control" id="warden_category"
                                                                    name="warden_category"    required>
                                                                    <?php echo $warden_category_options; ?>
                                                                </select>
                                                                <span class="error-message text-danger"
                                                                    id="error-warden_category"></span>
                                                            </div>

                                                            <input type="hidden" id="district_office" name="district_office"
                                                            class="form-control"
                                                            value="<?php echo $_SESSION["district_id"]; ?>">
                                                        <input type="hidden" id="taluk_office" name="taluk_office"
                                                            class="form-control"
                                                            value="<?php echo $_SESSION["taluk_id"]; ?>">
                                                        <input type="hidden" id="hostel_office" name="hostel_office"
                                                            class="form-control"
                                                            value="<?php echo $_SESSION["hostel_id"]; ?>">
                                                           
                                                            
                                                            
                                                            <div class="col-md-6 fm">
                                                                <label for="simpleinput" class="form-label">Aadhaar Number
                                                                    </label>
                                                                <input type="text" class="form-control"
                                                                    id="aadhaar_no" name="aadhaar_no" maxlength = '12'
                                                                    oninput="number_only(this)" onkeyup="onAadharKeyPress()" required
                                                                    value="<?php echo $aadhaar_no; ?>">
                                                                <span class="error-message text-danger"
                                                                    id="error-aadhaar-no"></span>
                                                            </div>
                                                            <div class="col-md-6 fm">
                                            <label for="example-select" class="form-label">Photo Upload </label>
                                            <input type="file" class="form-control"  accept="image/*" id="image_file" name="image_file" <?php if($_GET['unique_id']){}else{ echo  'required';}?>>
                                           
                                        </div>
                                                        </div>

                                                        <div class="btns mt-3">
                                                        <?php echo btn_cancel($btn_cancel); ?>
                                                        <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                                    </div>
                                                    </form>


                                                </div>
                                            </div>
                                        </div>
                                   </div>
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                </div>