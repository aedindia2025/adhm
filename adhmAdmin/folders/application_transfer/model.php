<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Form variables
$btn_text   =   "Save";
$btn_action =   "create";

$unique_id  =    "";
$std_app_no  =    "";
$std_name  =    "";
$uuid  =    "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}
if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);
        $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "std_app_s s";

        $columns = [
            "s.entry_date",
            "(SELECT district_name FROM district_name WHERE unique_id = s.hostel_district_1) AS hostel_district_1",
            "(SELECT taluk_name FROM taluk_creation WHERE unique_id = s.hostel_taluk_1) AS hostel_taluk_1",
            "(SELECT hostel_id FROM hostel_name WHERE unique_id = s.hostel_1) AS hostel_id",
            "(SELECT hostel_name FROM hostel_name WHERE unique_id = s.hostel_1) AS hostel_name",
            "s.std_app_no",
            "s.std_name",
            "(SELECT reason FROM batch_creation WHERE s1_unique_id = s.unique_id) AS reason",
            "s.unique_id",
            "(SELECT caDistrictId FROM std_app_umis_s4 WHERE s1_unique_id = s.unique_id) AS ca_district",
            "(SELECT no_umis_clg_district FROM std_app_umis_s4 WHERE s1_unique_id = s.unique_id) AS ca_district_no",
            "(SELECT school_district FROM std_app_emis_s3 WHERE s1_unique_id = s.unique_id) AS s_district",
            "s.student_type",
            "(SELECT gender FROM std_app_s2 WHERE s1_unique_id = s.unique_id) AS gender",

        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        //print_r($result_values);

        if ($result_values->status) {

            $result_values = $result_values->data;
            $entry_date = $result_values[0]["entry_date"];
            $hostel_district_1 = $result_values[0]["hostel_district_1"];
            $hostel_taluk_1 = $result_values[0]["hostel_taluk_1"];
            $hostel_id = $result_values[0]["hostel_id"];
            $hostel_name = $result_values[0]["hostel_name"];
            $std_app_no = $result_values[0]["std_app_no"];
            $std_name = $result_values[0]["std_name"];
            $reason = $result_values[0]["reason"];
            $student_type = $result_values[0]['student_type'];
            $ca_district = $result_values[0]['ca_district'];
            $s_district = $result_values[0]['s_district'];
            $ca_district_no = $result_values[0]['ca_district_no'];
            $gender = $result_values[0]['gender'];

            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$menu_screen    = 'application_transfer/list';
$encrypted_path = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
$url            = "index.php?file=" . urlencode($encrypted_path);

?>
<style>
    label.form-label2 {
        font-weight: 400;
    }

    h5#entry_date {
        margin-bottom: 0px;
        margin-top: 4px;
        font-weight: 700;
    }

    table#transfer_datatable {
        width: 100%;
        display: block;
        overflow: scroll;
    }

    button.renewBtnn {
        background: #dff6ff;
        border: 1px solid #00aff0;
        margin: 0px 10px;
        border-radius: 3px;
        font-weight: 400;
        font-size: 13px;
        vertical-align: sub;
        margin-top: 10px;
    }

    .modal-new {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(240, 240, 240, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        display: none;
        z-index: 999;
    }

    /* Modal content box */
    .modal-content-new {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 350px;
    }

    .loader-new {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 4px;
        margin-bottom: 20px;
    }

    .loader-new div {
        width: 6px;
        height: 40px;
        background: #333;
        animation: bounce 1.2s infinite ease-in-out;
    }

    .loader-new div:nth-child(1) {
        animation-delay: -0.4s;
    }

    .loader-new div:nth-child(2) {
        animation-delay: -0.2s;
    }

    .loader-new div:nth-child(3) {
        animation-delay: 0s;
    }

    .loader-new div:nth-child(4) {
        animation-delay: -0.2s;
    }

    .loader-new div:nth-child(5) {
        animation-delay: -0.4s;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: scaleY(0.4);
        }

        50% {
            transform: scaleY(1);
        }
    }

    .spe-h5 {
        margin-top: 3px;
        font-weight: 600;
    }

    .heading_t {
        background: #eeeeeeeb;
        padding: 8px 10px;
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Application Transfer</h4>
                    </div>
                </div>
            </div>
            <div class="modal-new" id="modal-new">
                <div class="modal-content-new">
                    <div class="loader-new">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <div class="welcome-text">Loading please wait...</div>
                </div>
            </div>
            <input type="hidden" id="s1_unique_id" value="<?php echo $unique_id; ?>">
            <input type="hidden" id="student_type" value="<?php echo $student_type; ?>">
            <input type="hidden" id="s_district" value="<?php echo $s_district; ?>">
            <input type="hidden" id="ca_district" value="<?php echo $ca_district; ?>">
            <input type="hidden" id="ca_district_no" value="<?php echo $ca_district_no; ?>">
            <input type="hidden" id="nearby_district_id">
            <input type="hidden" id="hostel_district_final">
            <input type="hidden" id="act_district">
            <input type="hidden" id="gender" value="<?php echo $gender; ?>">
            <input type="hidden" id="url" value="<?php echo $url; ?>">

            <div class="row">
                <div class="col-12">
                    <form class="was-validated" autocomplete="off">
                        <div class="row">
                            <div class="">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="row ">
                                                    <h4 class="heading_t">Hostel Details</h4>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="std_app_no" class="form-label2">District : <span id="hostel_district_1" name="hostel_district_1" class="tag spe-h5"><?php echo $hostel_district_1; ?></span></label>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="std_app_no" class="form-label2">Taluk : <span id="hostel_taluk_1" name="hostel_taluk_1" class="tag spe-h5"><?php echo $hostel_taluk_1; ?></span></label>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="std_app_no" class="form-label2">Hostel ID : <span id="hostel_id" name="hostel_id" class="tag spe-h5"><?php echo $hostel_id; ?></span></label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="std_app_no" class="form-label2">Hostel Name :<span id="hostel_name" name="hostel_name" class="tag spe-h5"> <?php echo $hostel_name; ?></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row mt-3">
                                                    <h4 class="heading_t">Student Details</h4>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="std_app_no" class="form-label2">Applied Date : <span id="entry_date" name="entry_date" class="tag spe-h5"><?php echo $entry_date; ?></span></label>
                                                    </div>
                                                    <div class="col-md-4 mb-3"><label for="std_app_no" class="form-label2">Application Number : <span id="std_app_no" name="std_app_no" class="tag spe-h5"><?php echo $std_app_no; ?></span></label>
                                                    </div>
                                                    <div class="col-md-4 mb-3">

                                                        <label for="std_app_no" class="form-label2">Student Name : <span id="std_name" name="std_name" class="tag spe-h5"><?php echo $std_name; ?></span></label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="std_app_no" class="form-label2">Reject Reason : <span id="reson" name="reson" class="tag spe-h5"><?php echo $reason; ?></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row mt-3">
                                                    <h4 class="heading_t">Transfer Details</h4>
                                                    <div class="col-md-12">
                                                        <label for="std_app_no" class="form-label2">Near By Districts : <span id="nearby_district" name="nearby_district" class="tag spe-h5">Loading...</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="">
                                <div class="card">
                                    <div class="card-body">
                                        <table id="transfer_datatable" class="table w-100">
                                            <thead>
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Hostel ID</th>
                                                    <th>Hostel Name</th>
                                                    <th>Hostel Address</th>
                                                    <th>Sanctioned / Vacancy</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div class="btns mr-3">
                                            <?php echo btn_cancel($btn_cancel); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>