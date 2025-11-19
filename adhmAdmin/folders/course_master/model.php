<?php

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

        $table = "course_master";

        $columns = [

            "hostel_type",
            "course_name",
            "is_active",
            "unique_id"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);
        // Print_r($unique_id);
        if ($result_values->status) {

            $result_values = $result_values->data;

            $hostel_type = $result_values[0]["hostel_type"];
            $course_name = $result_values[0]["course_name"];
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
                        <h4 class="page-title">Course</h4>
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
                                            <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Select Degree</label>
                                                <!-- <select name="hostel_type" id="hostel_type"
                                                    class="select2 form-control" required>
                                                    <?php echo $hostel_type_options; ?>
                                                </select> -->
                                                <select class="form-select mb-0 " id="hostel_type" name="hostel_type">
                                                    <option value="" selected>Select Course</option>
                                                    <option value="1" <?php if ($hostel_type == "1") {
                                                        echo "selected";
                                                    } ?>>ITI</option>
                                                    <option value="2" <?php if ($hostel_type == "2") {
                                                        echo "selected";
                                                    } ?>>Diploma</option>
                                                    <option value="3" <?php if ($hostel_type == "3") {
                                                        echo "selected";
                                                    } ?>>UG</option>
                                                    <option value="4" <?php if ($hostel_type == "4") {
                                                        echo "selected";
                                                    } ?>>PG</option>
                                                    <option value="5" <?php if ($hostel_type == "5") {
                                                        echo "selected";
                                                    } ?>>PHD</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="simpleinput" class="form-label">Course Name</label>
                                                <input type="text" class="form-control"
                                                    oninput="validateCharInput(this)" id="course_name"
                                                    name="course_name" value="<?= $course_name; ?>" required>
                                                <input type="hidden" id="csrf_token" name="csrf_token"
                                                    value="<?php echo $_SESSION['csrf_token']; ?>">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>