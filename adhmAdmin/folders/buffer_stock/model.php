<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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

        $table = "master_buffer_stock";

        $columns = [

            "category",
            "days",
            "description",
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
            $days = $result_values[0]["days"];
            $description = $result_values[0]["description"];
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

$item_category = buffer_stock_category();
$item_category = select_option($item_category, "Select Category", $category);

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

                        <h4 class="page-title">Buffer Stock</h4>
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
                                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                            <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Category</label>
                                                <select name="category" id="category" class="select2 form-control"
                                                    required>
                                                    <?php echo $item_category; ?>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-3 fm">
                                                <label for="simpleinput" class="form-label">No of Days</label>
                                                <input class="form-control" type="text"  id="days" name="days" value="<?= $days ?>" oninput="limitDays(this)" maxlength="5">
                                            </div>

                                            <div class="col-md-3 fm">
                                                <label for="simpleinput" class="form-label">Description</label>
                                                <textarea class="form-control" type="text" id="description" name="description"><?= $description ?></textarea>
                                            </div>

                                            <div class="col-md-3 fm">
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
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>