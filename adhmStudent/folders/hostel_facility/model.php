<?php
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
                            <div class="card">
                                <div class="card-body">
                                    <form class="was-validated" autocomplete="off">
                                        <div class="row mb-3">
                                            <div class="col-md-3 fm">
                                                <label for="facility_type" class="form-label">Facility Type</label>
                                                <select class="form-select" id="facility_type" name="facility_type" required>
                                                    <option value="individual">Individual</option>
                                                    <option value="common">Common</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="facility_name" class="form-label">Facility Name</label>
                                                <input type="text" class="form-control" id="facility_name" name="facility_name" value="<?php echo $facility_name; ?>" required>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="received_date" class="form-label">Received Date</label>
                                                <input type="date" class="form-control" id="received_date" name="received_date" value="<?php echo $received_date; ?>" required>
                                            </div>
                                            
                                            <!-- =========================================== -->
                                            <!-- Hidden Fields -->
                                            <!-- =========================================== -->

                                            <input type="hidden" class="form-control" id="user_name" name="user_name" value="<?php echo $staff_name; ?>" required>
                                            <input type="hidden" class="form-control" id="hostel_name" name="hostel_name" value="65584660e85as2403310" required>
                                            <input type="hidden" class="form-control" id="entry_date" name="entry_date" value="<?php echo $entry_date; ?>" required>
                                            <input type="hidden" class="form-control" id="taluk_id" name="taluk_id" value="65584660e85d24200559" required>
                                            <input type="hidden" class="form-control" id="district_id" name="district_id" value="65584660e85f131401" required>
                                            
                                            <!-- =========================================== -->
                                            <!-- Hidden Field Ends -->
                                            <!-- =========================================== -->
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
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>