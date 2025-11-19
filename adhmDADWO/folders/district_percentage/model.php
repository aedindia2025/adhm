<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<meta http-equiv="refresh" content="<?php echo $sec ?>;URL='<?php echo $page ?>'">
<?php
// Form variables
$btn_text = "Save";
$btn_action = "create";
$screen_unique_id = unique_id($preifx);

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        $where = "unique_id = '$unique_id'";
        $table_main = "district_percentage";

        $columns = [
            "' ' as sno",
            "district",
            "month",
            "unique_id",
            "screen_unique_id",
        ];

        $table_details = [
            $table_main,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);
        if ($result_values->status) {

            $result_values = $result_values->data;

            $district = $result_values[0]["district"];
            $month = $result_values[0]["month"];
            $unique_ids = $result_values[0]["unique_id"];
            $screen_unique_id = $result_values[0]["screen_unique_id"];


            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

// $district_name_options = district_name($_SESSION['district_id']);
// $district_name_options = select_option($district_name_options, "Select District", $district);


$district_data = district_name($_SESSION['district_id']); // returns array

$district_id   = $district_data[0]['unique_id'];
$district_name = $district_data[0]['district_name'];

$district_name_options = '<option value="'.$district_id.'" selected>'.$district_name.'</option>';

$hostel_type_options = hostel_type_name();
$hostel_type_options = select_option($hostel_type_options, "Select Hostel Type", $hostel_type);

$readonlyClass = !empty($unique_id) ? 'readonly-field' : '';

?>

<style>
    .readonly-field {
        pointer-events: none;
        background-color: #f5f5f5;
        opacity: 0.7;
    }

    /* Greyed-out Select2 look */
    .select2-disabled-look .select2-selection {
        background-color: #e9ecef !important;
        color: #6c757d !important;
        cursor: not-allowed !important;
    }

    .select2-disabled-look {
        pointer-events: none;
        opacity: 0.9;
    }

    /* Greyed-out input look (for month) */
    .input-disabled-look {
        background-color: #e9ecef !important;
        color: #6c757d !important;
        cursor: not-allowed !important;
        pointer-events: none;
        opacity: 0.9;
    }
</style>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <input type="hidden" id="screen_unique_id" name="screen_unique_id"
                            value="<?= $screen_unique_id; ?>">
                        <h4 class="page-title">District Percentage</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                <input type="hidden" id="csrf_token" name="csrf_token"
                                    value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" class="form-control" name="unique_id" id="unique_id"
                                    value="<?php echo $unique_id; ?>">
                                <form class="was-validated" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-3 fm mb-3">
                                            <label class="form-label">District</label>
                                            <select name="district" id="district" class="form-control select2" required
                                                <?= $btn_action == 'update' ? 'disabled' : '' ?>>
                                                <?php echo $district_name_options; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 fm mb-3">
                                            <label class="form-label">Month</label>
                                            <input type="month" id="month" name="month"
                                                class="form-control <?= $readonlyClass ?>" value="<?= $month ?>"
                                                <?= !empty($unique_id) ? 'readonly' : '' ?>>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 fm mb-3">
                                            <input type="hidden" id="sublist_unique_id" name="sublist_unique_id">
                                            <label class="form-label">Hostel Type</label>
                                            <select name="hostel_type" id="hostel_type" class="form-control select2"
                                                required>
                                                <?php echo $hostel_type_options; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-3 fm mb-3">
                                            <label class="form-label">Percentage</label>
                                            <div class="input-group">
                                                <input type="text" id="percentage" name="percentage"
                                                    class="form-control" maxlength="2"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 fm mb-3 mt-1">
                                            <button class="btn btn-info add_update_btn mt-3" type="button"
                                                onclick="sublist_cu()" id="add_update_btn">Save</button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <br>
                                        <div class="col-12">
                                            <table id="district_percent_sub_datatable"
                                                class="table dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Hostel Type</th>
                                                        <th>Month</th>
                                                        <th>Percentage</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="btns">
                                        <?php echo btn_cancel($btn_cancel); ?>
                                        <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>