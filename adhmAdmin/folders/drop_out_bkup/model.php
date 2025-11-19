


<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Form variables
$btn_text = "Save";
$btn_action = "create";

$student_id = "";
$student_name = "";
$drop_discontinue_date = "";
$reason = "";

$unique_id = "";
$expenses_type = "";
$is_active = 1;
$staff_unique_id = $_SESSION['sess_user_id'] ;

$ses_staff_id = $_SESSION['staff_id'];
$user_name = $_SESSION["user_name"];
$ses_hostel_id = $_SESSION['hostel_id'];
$ses_hostel_name = $_SESSION['hostel_name'];
$hostel_district = $_SESSION["district_name"];
$ses_district_id = $_SESSION["district_id"];
$ses_academic_year = $_SESSION['academic_year'];
$ses_hostel_taluk = $_SESSION["hostel_taluk"];
$ses_taluk_id = $_SESSION['taluk_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "dropout";

        $columns = [
            // "(select std_reg_no from std_reg_p1 where dropout.student_id =std_reg_p1.student_id) as student_id",

            "student_id",
            "student_name",
            "drop_discontinue_date",
            "reason",
            // "is_active"
            "unique_id"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values = $result_values->data;

            // print_r($result_values);

            $student_id = $result_values[0]["student_id"];
            $student_name = $result_values[0]["student_name"];
            $drop_discontinue_date = $result_values[0]["drop_discontinue_date"];
            $reason = $result_values[0]["reason"];
            $is_active = $result_values[0]["is_active"];

            // print_r($student_id);
            // die();

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

$student_id_options = student_id($student_id, $_SESSION['hostel_id']);
$student_id_option = select_option($student_id_options, 'Select Student ID', $student_id);
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

                        <h4 class="page-title">Dropout/Discontinue</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">

                    <div class="row">
                    <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 fm">
                                        <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                            <label for="staff_id" class="form-label">Staff Id:<span class="xy-lab"> <?php echo $ses_staff_id; ?></span></label>
                                        </div>
                                        <div class="col-md-4 fm">
                                        <label for="staff_name" class="form-label">Staff Name: <span class="xy-lab"><?php echo $user_name; ?></span></label>
                                        </div>
                                        <div class="col-md-4 fm">
                                            <label for="academic_year" class="form-label">Academic Year: <span class="xy-lab"><?php echo academic_year($_SESSION['academic_year'])[0]['amc_year']; ?></span></label>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-md-4 fm">
                                        <label for="district_id" class="form-label">District Name:  <span class="xy-lab"><?php echo district_name($ses_district_id)[0]['district_name']; ?></span></label>
                                        </div>
                                        <div class="col-md-4 fm">
                                        <label for="taluk_id" class="form-label">Taluk Name:  <span class="xy-lab"><?php echo taluk_name($ses_taluk_id)[0]['taluk_name']; ?></span></label>
                                        </div>
                                        <div class="col-md-4 fm">
                                        <label for="hostel_id" class="form-label">Hostel Name:  <span class="xy-lab"><?php echo hostel_name($ses_hostel_id)[0]['hostel_name']; ?></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="">
                            <div class="card">
                                <div class="card-body">
                                    <form class="was-validated" autocomplete="off">
                                        <div class="row mb-3">
                                            <!-- <?php echo $student_id; ?>
                                            <?php echo $student_name; ?>
                                            <?php echo $drop_discontinue_date; ?>
                                            <?php echo $reason; ?> -->

                                            <!-- <div class="col-md-3 fm">
                                            <label for="drop-discont" class="form-label"> Dropout/Discontinued<span class="red">*</span></label>
                                                <select class="form-select" id="drop-discont" name="drop-discont" required>
                                                    <option>Dropout</option>
                                                    <option>Discontinued</option>
                                                </select>
                                            </div> -->
                                            <div class="col-md-3 fm">
                                                <label for="student_id" class="form-label"> Student ID</label>

                                                <select class="form-select" id="student_id" name="student_id"
                                                    onchange="get_std_name()" required>
                                                    <?php echo $student_id_option;?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="student_name" class="form-label"> Student Name</label>
                                                <input type="text" class="form-control" id="student_name"
                                                    name="student_name" placeholder="Student Name"
                                                    value="<?php echo $student_name; ?>" required>
                                                    <input type="hidden" id="staff_id" name="staff_id" value="<?php echo $ses_staff_id; ?>"></input>

                                                    <input type="hidden" id="staff_name" name="staff_name" value="<?php echo $staff_unique_id; ?>"></input>

                                                    <input type="hidden" id="district_id" name="district_id" value="<?php echo $ses_district_id; ?>"></input>
                                                    <input type="hidden" id="taluk_id" name="taluk_id" value="<?php echo $ses_taluk_id; ?>"></input>
                                                    <input type="hidden" id="hostel_id" name="hostel_id" value="<?php echo $ses_hostel_id; ?>"></input>
                                                    <input type="hidden" id="academic_year" name="academic_year" value="<?php echo $ses_academic_year; ?>"></input>

                                                <input type="hidden" name="district_name" id="district_name" value=<?php echo $_SESSION["district_id"]; ?>>
                                                <input type="hidden" name="taluk_name" id="taluk_name" value=<?php echo $_SESSION["taluk_id"]; ?>>
                                                <input type="hidden" name="hostel_name" id="hostel_name" value=<?php echo $_SESSION["hostel_id"]; ?>>
                                                <input type="hidden" name="staff_id" id="staff_id" value=<?php echo $_SESSION["staff_id"]; ?>>
                                                <!-- <select class="form-select" id="student-name" name="student-name">
                                                    <option>Student Name</option>
                                                </select> -->
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="drop_discontinue_date" class="form-label"> Dropout/
                                                    Discontinued Date</label>
                                                <input type="date" class="form-control" id="drop_discontinue_date"
                                                    name="drop_discontinue_date"
                                                    value="<?php echo $drop_discontinue_date; ?>" required>

                                            </div>

                                            <!-- <div class="col-md-3 fm">
                                            <label for="simpleinput" class="form-label">Academic  Year</label>
                                            <input type="date" class="form-control" id="carrier_options" name="carrier_options" value="<?= $carrier_options; ?>" required>
                                        
                                        </div> -->

                                            <div class="col-md-3 fm">
                                                <label for="reason" class="form-label">Reason</label>
                                                <textarea class="form-control" id="reason"
                                                    name="reason"><?php echo $reason; ?></textarea>
                                            </div>

                                            <div class="col-md-3 fm" hidden>
                                                <label class="form-label">Status</label>
                                                <select name="is_active" id="is_active" class="select2 form-control"
                                                    required>
                                                    <?php echo $active_status_options; ?>
                                                </select>
                                            </div>

                                        </div>


                                        <!-- <div class="btns">
                                       <a href="index.php?file=user_type/list"><button type="button" class="btn btn-danger  m-t-15 btn-rounded waves-effect waves-light float-right ml-2">Cancel</button></a>
                                    <button type="button" class="btn btn-primary m-t-15 waves-effect createupdate_btn" onclick="user_type_cu('')">Save</button>
                                    </div> -->
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