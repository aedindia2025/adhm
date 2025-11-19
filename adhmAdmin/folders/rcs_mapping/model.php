<?php include 'function.php'; ?>
<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Form variables
$btn_text = "Save";
$btn_action = "create";

$is_active = 1;
$warehouse_options = "";
if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);

        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "rcs_mapping";

        $columns = [

            "category",
            "item",
            "school_college",
            "quantity",
            "unit",
            "procure_mode",
            "is_active",
            "unique_id"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);
        if ($result_values->status) {

            $result_values = $result_values->data;


            $category = $result_values[0]["category"];
            $item = $result_values[0]["item"];
            $school_college = $result_values[0]["school_college"];
            $quantity = $result_values[0]["quantity"];
            $unit = $result_values[0]["unit"];
            $procure_mode = $result_values[0]["procure_mode"];
            $is_active = $result_values[0]["is_active"];

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


// $staff_options      = staff_name();
// $staff_options      = select_option($staff_options,"Select Staff",$staff_name); 

$item_name_options = item_name();
$item_name_options = select_option($item_name_options, "Select items", $item);

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

                        <h4 class="page-title">RCS Mapping</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <form class="was-validated" autocomplete="off">
                                    
                                        <div class="row mb-3">
                                            <div class="col-md-3 fm mb-3">
                                                <label for="example-select" class="form-label">Select Category</label>
                                                <select class="form-select mb-0" name="category" id="category" onchange="get_items()" required>
                                                        <option selected value="">Select Category</option>
                                                        <option value="grocery" <?php if ($category == "grocery") {
                                                            echo "selected";
                                                        } ?>>Grocery</option>
                                                        <option value="dailie" <?php if ($category == "dailie") {
                                                            echo "selected";
                                                        } ?>>Dailie</option>
                                                        
                                                    </select>
                                            </div>
                                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <div class="col-md-3 fm mb-3">
                                                <label for="simpleinput" class="form-label">Item Name</label>
                                                <select name="item_name" id="item_name" class="select2 form-control"
                                                    required>
                                                    <?php echo $item_name_options; ?>

                                                </select>
                                            </div>
                                            <div class="col-md-3 fm mb-3">
                                                <label for="example-select" class="form-label">Select Type</label>
                                                <select class="form-select mb-0" name="school_college" id="school_college" required>
                                                        <option selected value="">Select Type</option>
                                                        <option value="1" <?php if ($school_college == "1") {
                                                            echo "selected";
                                                        } ?>>School</option>
                                                        <option value="2" <?php if ($school_college == "2") {
                                                            echo "selected";
                                                        } ?>>College</option>
                                                        
                                                    </select>
                                            </div>

                                            <div class="col-md-3 fm mb-3">
                                                <label for="example-select" class="form-label">Quantity</label>
                                                <input type="text" class="form-control" name="quantity" id="quantity" value="<?= $quantity ?>" required>
                                            </div>
                                            
                                            <div class="col-md-3 fm mb-3">
                                                <label for="example-select" class="form-label">Select Unit</label>
                                                <select class="form-select mb-0" name="unit" id="unit" required>
                                                        <option selected value="">Select Unit</option>
                                                        <option value="1" <?php if ($unit == "1") {
                                                            echo "selected";
                                                        } ?>>Kgs</option>
                                                        <option value="2" <?php if ($unit == "2") {
                                                            echo "selected";
                                                        } ?>>Grams</option>
                                                        <option value="3" <?php if ($unit == "3") {
                                                            echo "selected";
                                                        } ?>>Lts</option>
                                                        <option value="4" <?php if ($unit == "4") {
                                                            echo "selected";
                                                        } ?>>Mili Lts</option>
                                                        <option value="5" <?php if ($unit == "5") {
                                                            echo "selected";
                                                        } ?>>Pcs</option>
                                                    </select>
                                            </div>
                                            <div class="col-md-3 fm mb-3">
                                                <label for="example-select" class="form-label">Procurement mode</label>
                                                <select class="form-select mb-0" name="procurement_mode" id="procurement_mode" required>
                                                        <option selected value="">Select Mode</option>
                                                        <option value="1" <?php if ($procure_mode == "1") {
                                                            echo "selected";
                                                        } ?>>RCS</option>
                                                        <option value="2" <?php if ($procure_mode == "2") {
                                                            echo "selected";
                                                        } ?>>Open Market</option>
                                                    </select>
                                            </div>
                                            <div class="col-md-3 fm mb-3">
                                                <label for="example-select" class="form-label">Status</label>
                                                <select name="is_active" id="is_active" class="select2 form-control"
                                                    required>
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