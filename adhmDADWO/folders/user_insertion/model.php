<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Form variables
$btn_text = "Save";
$btn_action = "create";

$unique_id = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);

        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "std_reg_s";

        $columns = [
            "std_reg_no",
            "std_name",
            "(SELECT hostel_id FROM hostel_name WHERE hostel_name.unique_id = std_reg_s.hostel_1) as hostel_id",
            "(SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = std_reg_s.hostel_1) as hostel_name",
            "(SELECT ip FROM device_registration WHERE device_registration.hostel_unique_id = std_reg_s.hostel_1) as ip",
            "(SELECT d_user_name FROM device_registration WHERE device_registration.hostel_unique_id = std_reg_s.hostel_1) as d_user_name",
            "(SELECT d_password FROM device_registration WHERE device_registration.hostel_unique_id = std_reg_s.hostel_1) as d_password",
            "pro_image",
            "unique_id"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $std_reg_no = $result_values[0]["std_reg_no"];
            $std_name = $result_values[0]["std_name"];
            $hostel_id = $result_values[0]["hostel_id"];
            $hostel_name = $result_values[0]["hostel_name"];
            $ip = $result_values[0]["ip"];
            $d_user_name = $result_values[0]["d_user_name"];
            $d_password = $result_values[0]["d_password"];
            $pro_image = $result_values[0]["pro_image"];
            $image_src = 'data:image/jpeg;base64,' . $pro_image;

            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

?>
<!-- Modal with form -->
<style>
    .no-bos input {
        border: 0px;
        font-size: 16px;
        font-weight: 600;
    }

    img.face {
        width: 20%;
        padding: 12px;
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

                        <h4 class="page-title">User Insertion</h4>
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
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h4 class="page-title mb-2">Applicant Photo</h4>
                                            <div class="boc-app">
                                                <div class="">
                                                    <img src="<?php echo $image_src; ?>"
                                                        class=" avatar-lg img-thumbnail" alt="profile-image">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <h4 class="page-title mb-2">Student Information</h4>
                                            <div class="row ">
                                                <div class="col-md-4 fm mt-2">
                                                    <label for="example-select" class="form-label">Student Name</label>
                                                </div>
                                                <div class="col-md-8 no-bos">

                                                    <input type="text" class="form-control" id="std_name"
                                                        oninput="validateCharInput(this)" name="std_name"
                                                        value="<?php echo $std_name; ?>" required readonly>
                                                </div>

                                            </div>

                                            <div class="row ">
                                                <div class="col-md-4 fm mt-2">
                                                    <label for="example-select" class="form-label">Hostel ID</label>
                                                </div>
                                                <div class="col-md-6 no-bos">
                                                    <input type="text" class="form-control" id="hostel_id"
                                                        oninput="validateCharInput(this)" name="hostel_id"
                                                        value="<?php echo $hostel_id; ?>" required readonly>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-4 fm mt-2">
                                                    <label for="example-select" class="form-label">Student Reg
                                                        No</label>
                                                </div>
                                                <div class="col-md-6 no-bos">
                                                    <input type="text" class="form-control" id="std_reg_no"
                                                        oninput="validateCharInput(this)" name="std_reg_no"
                                                        value="<?php echo $std_reg_no; ?>" required readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <h4 class="page-title mb-2">Device Information</h4>
                                            <div class="row ">
                                                <div class="col-md-4 fm mt-2">
                                                    <label for="example-select" class="form-label">Hostel Id</label>
                                                </div>
                                                <div class="col-md-6  no-bos">
                                                    <input type="text" class="form-control" id="hostel_name"
                                                        oninput="validateCharInput(this)" name="hostel_name"
                                                        value="<?php echo $hostel_name; ?>" required readonly>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-4 fm mt-2">
                                                    <label for="example-select" class="form-label">IP</label>
                                                </div>
                                                <div class="col-md-6  no-bos">
                                                    <input type="text" class="form-control" id="ip"
                                                        oninput="validateCharInput(this)" name="ip"
                                                        value="<?php echo $ip; ?>" required readonly>
                                                </div>

                                            </div>
                                            <div class="row ">
                                                <div class="col-md-4 fm mt-2">
                                                    <label for="example-select" class="form-label">Device User
                                                        Name</label>
                                                </div>
                                                <div class="col-md-6  no-bos">
                                                    <input type="text" class="form-control" id="d_user_name"
                                                        oninput="validateCharInput(this)" name="d_user_name"
                                                        value="<?php echo $d_user_name; ?>" required readonly>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-4 fm mt-2">
                                                    <label for="example-select" class="form-label">Device
                                                        Password</label>
                                                </div>
                                                <div class="col-md-6  no-bos">
                                                    <input type="text" class="form-control" id="d_password"
                                                        oninput="validateCharInput(this)" name="d_password"
                                                        value="<?php echo $d_password; ?>" required readonly>
                                                </div>
                                            </div>

                                            <div class="row">


                                                <div class="col-md-12 fm mt-2 ">
                                                    <button type="submit" class="btn btn-primary"
                                                        onclick="user_insertion();">Go
                                                        to biometric</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <hr>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <h4 class="page-title mb-2">Face Recognition</h4>
                                            <img src="assets/images/face-id.png" class="face">
                                        </div>
                                        <div class="col-md-4">
                                            <h4 class="page-title mb-2">Fingerprint detection</h4>
                                            <img src="assets/images/finger-wrong.png" class="face">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <!-- <div class="col-md-3 fm mt-2">
                                        <button type="submit" class="btn btn-primary" onclick="user_insertion();">Insert To Device</button>
                                        </div>--->
                                    </div>
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                </div>
            </div>
        </div>
    </div>