<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Form variables
$btn_text = 'Save';
$btn_action = 'create';

$unique_id = '';

$user_id = $_SESSION['user_id'];
// $district_name      = "";
$is_active = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('Invalid CSRF token');
    }
}

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        // $unique_id  = $_GET["unique_id"];
        $uni_dec = str_replace(' ', '+', $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        $where = [
            'unique_id' => $unique_id,
        ];

        $table = 'additional_strength';

        $columns = [
            'from_district_name',
            'from_taluk_name',
            'from_hostel_name',
            'from_hostel_strength',
            'to_district_name',
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

        if ($result_values->status) {
            $result_values = $result_values->data;

            $from_district_name = $result_values[0]['from_district_name'];
            $from_taluk_name = $result_values[0]['from_taluk_name'];
            $from_hostel_name = $result_values[0]['from_hostel_name'];
            $from_hostel_strength = $result_values[0]['from_hostel_strength'];
            $to_district_name = $result_values[0]['to_district_name'];
            $to_taluk_name = $result_values[0]['to_taluk_name'];
            $to_hostel_name = $result_values[0]['to_hostel_name'];
            $to_hostel_strength = $result_values[0]['to_hostel_strength'];
            $transfer_count = $result_values[0]['transfer_count'];
            $unique_id = $result_values[0]['unique_id'];
            $file_name = $result_values[0]['file_name'];
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

$from_district_name_list = district_name();
$from_district_name_list = select_option($from_district_name_list, 'Select From District', $from_district_name);

$to_district_name_list = district_name();
$to_district_name_list = select_option($to_district_name_list, 'Select To District', $to_district_name);

$from_taluk_name_list = taluk_name();
$from_taluk_name_list = select_option($from_taluk_name_list, 'Select From Taluk', $from_taluk_name);

$to_taluk_name_list = taluk_name();
$to_taluk_name_list = select_option($to_taluk_name_list, 'Select To Taluk', $to_taluk_name);

$from_hostel_name_list = hostel_name();
$from_hostel_name_list = select_option_host($from_hostel_name_list, 'Select From Hostel', $from_hostel_name);

$to_hostel_name_list = hostel_name();
$to_hostel_name_list = select_option_host($to_hostel_name_list, 'Select To Hostel', $to_hostel_name);

$active_status_options = active_status($is_active);
?>



<style>
    #error_message{
        color:red;
    }
</style>
<!-- Modal with form -->


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
                                                <label for="from_district_name" class="form-label">From District Name</label>
                                                <select name="from_district_name" id="from_district_name" class="select2 form-control" onchange="get_taluk_options()" required>
                                                    <?php echo $from_district_name_list;?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="from_taluk_name" class="form-label">From Taluk Name</label>
                                                <select name="from_taluk_name" id="from_taluk_name" class="select2 form-control" onchange="get_hostel_name()" required>
                                                    <?php echo $from_taluk_name_list;?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                            <input type="text" hidden  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                                <label for="from_hostel_name" class="form-label">From Hostel Name</label>
                                                <select name="from_hostel_name" id="from_hostel_name" class="select2 form-control" onchange="get_hostel_strength()" required>
                                                    <?php echo $from_hostel_name_list; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="from_hostel_strength" class="form-label">From Hostel Strength</label>
                                                <input type="text" readonly class="form-control" id="from_hostel_strength" name="from_hostel_strength" value="<?php echo $from_hostel_strength;?>"required>
                                                <input type="text" hidden name="unique_id" id="unique_id" value="<?php echo $unique_id; ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="to_district_name" class="form-label">To District Name</label>
                                                <select name="to_district_name" id="to_district_name" class="select2 form-control" onchange="get_taluk_district_wise()" required >
                                                    <?php echo $to_district_name_list; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="to_taluk_name" class="form-label">To Taluk Name</label>
                                                <select name="to_taluk_name" id="to_taluk_name" class="select2 form-control" onchange="get_hostel_1()" required>
                                                    <?php echo $to_taluk_name_list;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <label for="to_hostel_name" class="form-label">To Hostel Name</label>
                                                <select name="to_hostel_name" id="to_hostel_name" class="select2 form-control" onchange="get_to_hostel_strength()" required>
                                                    <?php echo $to_hostel_name_list; ?>
                                                </select>
                                            </div>
                               
                                            <div class="col-md-4">
                                            <label for="to_hostel_strength" class="form-label">To Hostel Strength</label>
                                                <input type="text" readonly class="form-control" id="to_hostel_strength" name="to_hostel_strength" value="<?php echo $to_hostel_strength; ?>"required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="transfer_count" class="form-label">Transfer Count</label>
                                                <input type="text" class="form-control" id="transfer_count" oninput="year_only(this)" onkeyup="check_strength()" maxlength="4" minlength="1" name="transfer_count" value="<?php echo $transfer_count; ?>"required>
                                                <span class="error-message text-danger"
                                                                    id="error-transfer-count"></span>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <!-- <div class="col-md-4 ">
                                                <label for="test_file" class="form-label">Document Upload</label>
                                                <input type="file" class="form-control" id="test_file" name="test_file" accept=".jpg, .pdf, .jpeg, .png, .xlsx, .xls">

                                                <input type="hidden" id="doc_pic" name="doc_pic" value=<?php echo $file_name; ?>>
                                            </div> -->
                                           

                                
                                            <div class="col-md-4">
                                                <label for="remarks" class="form-label">Remarks</label>
                                                <input type="text" id="remarks" oninput="valid_user_name(this)" name="remarks" class="form-control" value="<?php echo $remarks;?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="is_active" class="form-label">Status</label>
                                                    <select name="is_active" id="is_active" class="select2 form-control" >
                                                        <?php echo $active_status_options;?>
                                                    </select>
                                            </div>
                                        </div>
                                        <!-- <input type="hidden" id="user_name" name="user_name" class="form-control" value="<?php echo $user_id; ?>"></input>
                                        <input type="hidden" id="user_type" name="user_type" class="form-control" value="<?php echo $user_id; ?>"></input> -->
                                    </form>
                            
                                <div class="btns mt-3">
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