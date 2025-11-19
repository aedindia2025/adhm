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

// Form variables
$btn_text                  = "Save";
$btn_action                = "create";

$unique_id                 = "";
$district_id               = "";
$taluk_id                  = "";
$entry_date                = today();
$hostel_name               = "";
$facility_type             = "";
$facility_name             = "";
$received_date             = "";
$acc_year                  = acc_year();
$is_active                 = 1;

$user_name = $_SESSION["user_name"];

$received_date=date("Y-m-d");

// $hostel_taluk_options = "<option value=''>Select Taluk</option>";

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        // $unique_id  = $_GET["unique_id"];

        $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
        $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id; 

        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "hostel_facility";

        $columns    = [
            "district_id",
            "taluk_id",
            "entry_date",
            "hostel_name",
            "facility_type",
            "facility_name",
            "received_date",
            "unique_id",
            "is_active"
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values              = $result_values->data;
            $district_id                = $result_values[0]["district_id"];
            $taluk_id                   = $result_values[0]["taluk_id"];
            $entry_date                 = $result_values[0]["entry_date"];
            $hostel_name                = $result_values[0]["hostel_name"];
            $facility_type              = $result_values[0]["facility_type"];
            $facility_name              = $result_values[0]["facility_name"];
            $received_date              = $result_values[0]["received_date"];
            $unique_id                  = $result_values[0]["unique_id"];
            $is_active                  = $result_values[0]["is_active"];

            $btn_text           = "Update";
            $btn_action         = "update";

        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}

$facility_type_options        = [
    "1" => [
        "unique_id" => "Individual",
        "value"     => "Individual",
    ],
    "2" => [
        "unique_id" => "Common",
        "value"     => "Common",
    ]
];

$facility_type_options        = select_option($facility_type_options, "Select facility Type", $facility_type);

$active_status_options   = active_status($is_active);

?>
<!-- Modal with form -->

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Hostel Facility</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="">
                        <form class="was-validated" autocomplete="off">
                        <div class="card">
                                <div class="card-body">
                               
                                    <div class="row">
                                        <div class="col-md-4 fm">
                                            <label for="staff_id" class="form-label">Staff Id: <?= $_SESSION['staff_id'];?></label>
                                            <input type="hidden" id="staff_id" name="staff_id" value="<?php echo $_SESSION['staff_id'];?>"></input>
                                            <input type="hidden" id="entry_date" name="entry_date" value="<?php echo $entry_date;?>"></input>
                                        </div>
                                        <div class="col-md-4 fm">
                                        <label for="staff_name" class="form-label">Staff Name: <?php echo $_SESSION["user_name"];?></label>
                                            <input type="hidden" id="staff_name" name="staff_name" value="<?php echo $user_name; ?>"></input>

                                            <input type="hidden" id="unique_id" name="unique_id" value="<?php echo $unique_id;?>"></input>

                                        </div>
                                        <div class="col-md-4 fm">
                                            <label for="academic_year" class="form-label">Academic Year: <?php echo $_SESSION["acc_year"];?></label>
                                            <input type="hidden" id="academic_year" name="academic_year" value="<?php echo $ses_academic_year; ?>"></input>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-md-4 fm">
                                        <label for="district_id" class="form-label">District Name:  <?php echo $_SESSION["district_name"];?></label>

                                            <input type="hidden" id="district_id" name="district_id" value="<?php echo $_SESSION["district_id"];?>"></input>
                                        </div>
                                        <div class="col-md-4 fm">
                                        <label for="taluk_id" class="form-label">Taluk Name:  <?php echo  $_SESSION['taluk_name'];?></label>
                                            <input type="hidden" id="taluk_id" name="taluk_id" value="<?php echo  $_SESSION['taluk_id'];?>"></input>
                                        </div>
                                        <div class="col-md-4 fm">
                                        <label for="hostel_id" class="form-label">Hostel Name:  <?php echo $_SESSION['hostel_name'];?></label>
                                        <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                            <input type="hidden" id="hostel_id" name="hostel_id" value="<?php echo $_SESSION['hostel_id'];?>"></input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    
                                        <div class="row mb-3">
                                            <div class="col-md-3 fm">
                                                <label for="facility_type" class="form-label">Facility Type</label>
                                                <select class="form-select" id="facility_type" name="facility_type" required>
                                                    <?php echo $facility_type_options;?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="facility_name" class="form-label">Facility Name</label>
                                                <input type="text" class="form-control" id="facility_name" name="facility_name" oninput="valid_user_name(this)" value="<?php echo $facility_name; ?>" required>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="received_date" class="form-label">Received Date</label>
                                                <input type="date" class="form-control" id="received_date" name="received_date" value="<?php echo $received_date; ?>" required>
                                            </div>
                                            
                                       
                                        
                                            <div class="col-md-3">
                                                <label for="is_active" class="form-label">Status</label>
                                                <select name="is_active" id="is_active" class="select2 form-control" required>
                                                    <?php echo $active_status_options; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="btns">
                                            <?php echo btn_cancel($btn_cancel); ?>
                                            <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                        </div>
                                        </form>
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>