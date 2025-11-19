<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('Invalid CSRF token');
    }
}
// Form variables
$btn_text = 'Save';
$btn_action = 'create';

$unique_id = '';
$district_id = '';
$district_name = '';
$is_active = 1;

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        $uni_dec = str_replace(' ', '+', $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            'unique_id' => $unique_id,
        ];

        $table = 'additional_strength';

        $columns = [
            'district_name',
            'from_taluk_name',
            'from_hostel_name',
            'from_hostel_strength',
            'to_taluk_name',
            'to_hostel_name',
            'to_hostel_strength',
            'transfer_count',
            'file_name',
            'remarks',
            'is_active',
            'unique_id',
        ];

        $table_details = [
            $table,
            $columns,
        ];

        $result_values = $pdo->select($table_details, $where);

        // print_r($result_values); die();

        if ($result_values->status) {
            $result_values = $result_values->data;

            $district_id = $result_values[0]['district_name'];
            $from_taluk_name = $result_values[0]['from_taluk_name'];
            $from_hostel_name = $result_values[0]['from_hostel_name'];
            $from_hostel_strength = $result_values[0]['from_hostel_strength'];
            $to_taluk_name = $result_values[0]['to_taluk_name'];
            $to_hostel_name = $result_values[0]['to_hostel_name'];
            $to_hostel_strength = $result_values[0]['to_hostel_strength'];
            $transfer_count = $result_values[0]['transfer_count'];
            $unique_id = $result_values[0]['unique_id'];
            $file_name = $result_values[0]['file_name'];
            // $additional_strength_file          = $result_values[0]["additional_strength_file"];
            // $additional_strength_org_name   = $result_values[0]["additional_strength_org_name"];
            $remarks = $result_values[0]['remarks'];
            $is_active = $result_values[0]['is_active'];

            $btn_text = 'Update';
            $btn_action = 'update';
        } else {
            $btn_text = 'Error';
            $btn_action = 'error';
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

if ($from_taluk_name != '') {
    $from_taluk_name = $from_taluk_name;
} else {
    $from_taluk_name = $_SESSION['taluk_id'];
}

if ($district_name != '') {
    $district_name = $district_name;
} else {
    $district_name = $_SESSION['district_id'];
}

if ($from_hostel_name != '') {
    $from_hostel_name = $from_hostel_name;
} else {
    $from_hostel_name = $_SESSION['hostel_id'];
}

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, 'Select District', $district_name);

$from_taluk_name_list = taluk_name('', $district_name);
$from_taluk_name_list = select_option($from_taluk_name_list, 'Select From Taluk', $from_taluk_name);

$to_taluk_name_list = taluk_name('', $district_name);
$to_taluk_name_list = select_option($to_taluk_name_list, 'Select To Taluk', $to_taluk_name);

$from_hostel_name_list = hostel_name('', '', $district_name);
$from_hostel_name_list = select_option_host($from_hostel_name_list, 'Select From Hostel', $from_hostel_name);

$to_hostel_name_list = hostel_name('', '', $district_name);
$to_hostel_name_list = select_option_host($to_hostel_name_list, 'Select Hostel', $to_hostel_name);

// $hostel_name_list       = hostel_name();
// $hostel_name_list       = select_option_host($hostel_name_list, "Select Hostel",$hostel_name_list);

$active_status_options = active_status($is_active);
?>
<!-- Modal with form -->

<style>
    .disabled-select {
        pointer-events: none;
        background-color: #f5f5f5;
        /* or any other color to indicate it's disabled */
        color: #999;
        /* or any other color to indicate it's disabled */
    }
    .btns{
        margin: 10px;
    }

    #error_message {
        color: red;
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

                        <h4 class="page-title">Additional Strength</h4>
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
                                            <div class="col-md-4">
                                                <label for="district_name" class="form-label">District Name</label>
                                                <input type="text" name="district_name" id="district_name"
                                                    class="select2 form-control" readonly
                                                    value="<?php echo $_SESSION['district_name']; ?>" required>
                                                <!-- <?php echo $district_name_list; ?> -->
                                                <input type="text" hidden name="district_id" id="district_id"
                                                    class="select2 form-control"
                                                    value="<?php echo $_SESSION['district_id']; ?>" required>
                                                <!-- </select> -->

                                                <input type="text" hidden name="unique_id" id="unique_id"
                                                    value="<?php echo $unique_id; ?>">

                                            </div>

                                            <div class="col-md-4">
                                                <label for="taluk_name" class="form-label">From Taluk Name</label>
                                                <select name="from_taluk_name" id="from_taluk_name"
                                                    class="select2 form-control" onchange="get_hostel()" required>
                                                    <?php echo $from_taluk_name_list; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="from_hostel_name" class="form-label">From Hostel
                                                    Name</label>
                                                <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <select name="from_hostel_name" id="from_hostel_name"
                                                    class="select2 form-control" onchange="get_hostel_strength()"
                                                    required>
                                                    <?php echo $from_hostel_name_list;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="from_hostel_strength" class="form-label">From Hostel
                                                    Strength</label>
                                                <input type="text" class="form-control" id="from_hostel_strength"
                                                    name="from_hostel_strength" readonly oninput="number_only(this)"
                                                    value="<?php echo $from_hostel_strength;?>" required>

                                                </select>

                                            </div>
                                            <div class="col-md-4">
                                                <label for="to_taluk_name" class="form-label">To Taluk Name</label>
                                                <select name="to_taluk_name" id="to_taluk_name"
                                                    class="select2 form-control" onchange="get_to_hostel()" required>
                                                    <?php echo $to_taluk_name_list; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="to_hostel_name" class="form-label">To Hostel Name</label>
                                                <select name="to_hostel_name" id="to_hostel_name"
                                                    class="select2 form-control" onchange="get_to_hostel_strength()"
                                                    required>
                                                    <?php echo $to_hostel_name_list; ?>
                                                </select>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-4">
                                                    <label for="to_hostel_strength" class="form-label">To Hostel
                                                        Strength</label>
                                                    <input type="text" class="form-control" id="to_hostel_strength"
                                                        name="to_hostel_strength" readonly oninput="number_only(this)"
                                                        value="<?php echo $to_hostel_strength; ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="transfer_count" class="form-label">Transfer
                                                        Count</label>
                                                    <input type="text" class="form-control" id="transfer_count"
                                                        name="transfer_count" oninput="number_only(this)" minlength="1" maxlength="4"
                                                        value="<?php echo $transfer_count; ?>" required>
                                                </div>
                                                <div class="col-md-4 ">
                                                    <label for="simpleinput" class="form-label">Document Upload</label>
                                                    <input type="file" class="form-control" id="test_file"
                                                        name="test_file" value="<?php echo $document_upload; ?>" accept=".pdf,.doc,.docx,image/*">
                                                    <input type="hidden" class="form-control" id="doc_pic"
                                                        name="doc_pic" value="<?php echo $file_name; ?>">
                                                    <span id="error_message"></span>
                                                </div>

                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-4">
                                                    <label for="remarks" class="form-label">Remarks</label>
                                                    <input type="text" id="remarks" name="remarks" class="form-control"
                                                        oninput="description_val(this)"
                                                        value="<?php echo $remarks; ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="is_active" class="form-label">Status</label>
                                                    <select name="is_active" id="is_active"
                                                        class="select2 form-control">
                                                        <?php echo $active_status_options; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="hidden" id="user_name" name="user_name" class="form-control"
                                                value="<?php echo $userid; ?>"></input>
                                            <input type="hidden" id="user_type" name="user_type" class="form-control"
                                                value="<?php echo $userid; ?>"></input>

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