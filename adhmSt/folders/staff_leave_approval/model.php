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
?>



<?php



// Form variables
$btn_text       =   "Save";
$btn_action     =   "create";

$unique_id      =    "";
$userid         =   $_SESSION["user_id"];

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
        $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id; 
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "staff_leave_application";

        $columns = [
            "staff_id",
            "staff_name",
            "no_of_days",
            "reason",
            "unique_id",
            "from_date ",
            "to_date",
            "approval_status",
            "reject_reason"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result);

        if ($result_values->status) {

            $result_values      = $result_values->data;

            $staff_name       = $result_values[0]["staff_name"];
            $staff_id         = $result_values[0]["staff_id"];
            $no_of_days         = $result_values[0]["no_of_days"];
            $from_date          = $result_values[0]["from_date"];
            $to_date            = $result_values[0]["to_date"];
            $reason             = $result_values[0]["reason"];
            $approval_status    = $result_values[0]["approval_status"];
            $reject_reason      = $result_values[0]["reject_reason"];
            $is_active          = $result_values[0]["is_active"];

            $btn_text           = "Update";
            $btn_action         = "update";
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}

$active_status_options = active_status($is_active);

$approval_status_option        = [
    "1" => [
        "unique_id" => "2",
        "value"     => "Approve",
    ],
    "2" => [
        "unique_id" => "3",
        "value"     => "Reject",
    ],

];
$approval_status_option        = select_option($approval_status_option, "Select", $approval_status);

?>
<style>

.btns{
    margin:10px;
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
                        <h4 class="page-title">Leave Application Approval</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <form class="was-validated" autocomplete="off">
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 fm">
                                            <label for="staff_name" class="form-label"> Staff Name</label>
                                            <input type="text" class="form-control" id="staff_name" name="staff_name" placeholder="Staff Name" value="<?php echo $staff_name; ?>" readonly>
                                        </div>
                                        <div class="col-md-3 fm">
                                            
                                        <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                            <label for="staff_id" class="form-label"> Staff ID</label>
                                            <input type="text" class="form-control" id="staff_id" name="staff_id" placeholder="Staff Name" value="<?php echo $staff_id; ?>" readonly>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <label for="no_of_days" class="form-label"> No Of Days</label>
                                            <input type="text" class="form-control" id="no_of_days" name="no_of_days" placeholder="Student Name" value="<?php echo $no_of_days; ?>" readonly>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <label for="from_date" class="form-label"> From Date</label>
                                            <input type="text" class="form-control" id="from_date" name="from_date" placeholder="Student Name" value="<?php echo $from_date; ?>" readonly>
                                        </div>
                                        <div class="col-md-3 fm mt-2">
                                            <label for="to_date" class="form-label"> To Date</label>
                                            <input type="text" class="form-control" id="to_date" name="to_date" placeholder="Student Name" value="<?php echo $to_date; ?>" readonly>
                                        </div>
                                        <div class="col-md-3 fm mt-2">
                                            <label for="reason" class="form-label"> Reason</label>
                                            <input type="text" class="form-control" id="reason" name="reason" placeholder="Student Name" value="<?php echo $reason; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                    <div class="col-md-3 fm">
                                            <label for="approval_status" class="form-label">Status</label>
                                            <select name="approval_status" id="approval_status" class="select2 form-control" onchange="get_value(this.value)" required>
                                                <?= $approval_status_option; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 fm" id="reject_reason_div" style="display:none;">
                                            <label for="reject_reason" class="form-label">Reject Reason</label>
                                            <textarea id="reject_reason" name="reject_reason" class="form-control" oninput="description_val(this)" required><?= $reject_reason; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="st_name" name="st_name" class="form-control" value="<?= $userid; ?>"></input>
                            <div class="btns">
                                <?php echo btn_cancel($btn_cancel); ?>
                                <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
