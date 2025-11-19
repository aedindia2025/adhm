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
$btn_text                   = "Save";
$btn_action                 = "create";

$unique_id                  = "";
$assembly_const_name        = "";
$is_active                  = 1;

$hostel_taluk_options = "<option value=''>Select Taluk</option>";

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "parliament_constituency";

        $columns    = [
            "district_name",
            "parliament_const_name",
            "is_active"
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values              = $result_values->data;
            $district_name              = $result_values[0]["district_name"];
            $parliament_const_name      = $result_values[0]["parliament_const_name"];
            $is_active                  = $result_values[0]["is_active"];



            $btn_text           = "Update";
            $btn_action         = "update";


            $district_options       = taluk_name("", $district_name);
            $hostel_taluk_options   = select_option($district_options, "Select Taluk", $taluk_name);
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}

$active_status_options   = active_status($is_active);

$district_name_options = district_name();
$district_name_options = select_option($district_name_options, "Select Hostel District", $district_name);

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

                        <h4 class="page-title">Parliament Constituency</h4>
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
                                                <label for="district_name" class="form-label">District Name</label>
                                                <select class="form-select" id="district_name" name="district_name" onchange="get_taluk_name()" required>
                                                    <?php echo  $district_name_options; ?>
                                                </select>
                                            </div>
                                        
                                            <div class="col-md-3 fm">
                                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <label for="assembly_const_name" class="form-label">Parliament Constituency</label>
                                                <input type="text" class="form-control" oninput="validateCharInput(this)"  id="parliament_const_name" name="parliament_const_name" value="<?php echo $parliament_const_name; ?>" required>
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
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>