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
        $table_main = "master_diet_chart";

        $columns = [
            "' ' as sno",
            "hostel_type",
            "description",
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


            $hostel_type = $result_values[0]["hostel_type"];
            $description = $result_values[0]["description"];
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

$hostel_type_options = hostel_type_name();
$hostel_type_options = select_option($hostel_type_options, "Select Hostel Type", $hostel_type);

$category_name_options = category_name();
$category_name_options = select_option($category_name_options, "Select Category", $category_name);

$item_options = item();
$item_options = select_option($item_options, "Select Items", $item);

?>

<style>
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
                        <h4 class="page-title">Diet Chart Entry</h4>
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
                                            <label class="form-label">Hostel Type</label>
                                            <select name="hostel_type" id="hostel_type" class="form-control select2"
                                                required <?= $btn_action == 'update' ? 'disabled' : '' ?>>
                                                <?php echo $hostel_type_options; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 fm mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description"
                                                oninput="description_val(this)"
                                                style="height: 60px"><?= $description ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <input type="hidden" id="sublist_unique_id" name="sublist_unique_id" value="">
                                        <div class="col-md-3 fm mb-3">
                                            <label for="example-select" class="form-label">Item Category</label>
                                            <select name="item_category" id="item_category" class="form-control select2"
                                                onchange="get_items();null_unit()">
                                                <?php echo $category_name_options; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 fm mb-3" id="veg_type_container" style="display:none;">
                                            <label class="form-label">Distribution Type</label>
                                            <select id="veg_type" name="veg_type" class="form-control"
                                                onchange="toggleVegMode();">
                                                <option value="">Select Type</option>
                                                <option value="common">Common Veg</option>
                                                <option value="individual">Individual</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 fm mb-3" id="item_container">
                                            <label class="form-label">Item</label>
                                            <select name="item" id="item" class="form-control select2"
                                                onchange="get_unit()">
                                                <?php echo $item_options; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 fm mb-3">
                                            <label class="form-label">Unit</label>
                                            <input type="text" id="unit" name="unit" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3 fm mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="text" id="quantity" name="quantity" class="form-control"
                                                oninput="validate_quantity(this)" maxlength="5">
                                        </div>
                                        <div class="col-md-3 fm mb-3 mt-1">
                                            <button class="btn btn-info add_update_btn mt-3" type="button"
                                                onclick="sublist_cu()" id="add_update_btn">Save</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <br>
                                        <div class="col-12">
                                            <table id="diet_chart_sub_datatable"
                                                class="table dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Category</th>
                                                        <th>Item</th>
                                                        <th>Unit</th>
                                                        <th>Quantity</th>
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