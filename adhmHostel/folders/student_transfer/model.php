<?php



$btn_text   = "Save";
$btn_action = "create";

$unique_id  = "";

$max_date   = date('Y-m-d');

$doj        = date("Y-m-d");
$is_active  = 1;

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        // $unique_id = $_GET["unique_id"];

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        $where = [
            "unique_id" => $unique_id
        ];

        $table = "student_transfer";

        $columns = [
            "std_reg_no",
            "std_id",
            "std_name",
            "from_hostel",
            "from_district",
            "from_taluk",
            "to_district",
            "to_taluk",
            "to_hostel",
            "unique_id"

        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        //print_r($result_values);die();
        if ($result_values->status) {

            $result_values = $result_values->data;

            $std_reg_no = $result_values[0]["std_reg_no"];
            $std_id = $result_values[0]["std_id"];
            $std_name = $result_values[0]["std_name"];
            $from_hostel = $result_values[0]["from_hostel"];
            $from_district = $result_values[0]["from_district"];
            $from_taluk = $result_values[0]["from_taluk"];
            $to_district = $result_values[0]["to_district"];
            $to_taluk = $result_values[0]["to_taluk"];
            $to_hostel = $result_values[0]["to_hostel"];


            $unique_id = $result_values[0]["unique_id"];


            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
} else {
    $transfer_std_list   = transfer_std_list();
}

$from_district_name_list = district_name($_SESSION['district_id']);
$from_district_name_list = select_option_acc($from_district_name_list, "Select District", $from_district);

$from_taluk_name_list    = taluk_name($_SESSION['taluk_id']);
$from_taluk_name_list    = select_option_acc($from_taluk_name_list, "Select Taluk", $from_taluk);

$from_hostel_name_list   = hostel_name($_SESSION['hostel_id']);
$from_hostel_name_list   = select_option_acc($from_hostel_name_list, "Select Hostel", $from_hostel);

$to_district_name_list   = district_name();
$to_district_name_list   = select_option($to_district_name_list, "Select District", $to_district);

$to_taluk_name_list      = taluk_name();
$to_taluk_name_list      = select_option($to_taluk_name_list, "Select Taluk", $to_taluk);


$to_hostel_name_list     = hostel_name();
$to_hostel_name_list     = select_option_host($to_hostel_name_list, "Select Hostel", $to_hostel, $hostel_type);

$student_id_options      = student_reg_list($std_id, $_SESSION['hostel_id'], $transfer_std_list);
$student_id_options      = select_option($student_id_options, 'Select Student ID', $std_id);

$active_status_options   = active_status($is_active);

$district_name           = $_SESSION["district_id"];


$gender_type =  $_SESSION['gender_id'];

$hostel_type =  $_SESSION['hostel_type'];



// print_r($gender_type);die();


?>

<style>
    .fm {
        margin-bottom: 10px;
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
                        <h4 class="page-title">Student Transfer</h4>
                    </div>
                </div>
            </div>

            <!-- end page title -->
            <!-- <div class="row"> -->
            <div class="col-12">
                <div class="row">
                    <div class="">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="arrow-overview" role="tabpanel">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <!-- <div class="row mb-3"> -->
                                                <form class="was-validated" autocomplete="off">
                                                    <input type="hidden" id="csrf_token" name="csrf_token"
                                                        value="<?php echo $_SESSION['csrf_token']; ?>">
                                                    <input type="hidden" id="unique_id" name="unique_id"
                                                        value="<?php echo $unique_id; ?>">

                                                    <input type="hidden" id="gender_type" name="gender_type"
                                                        value="<?php echo $gender_type; ?>">

                                                    <input type="hidden" id="hostel_type" name="hostel_type"
                                                        value="<?php echo $hostel_type; ?>">
                                                    <div class="row mb-3">
                                                        <div class="col-md-3 fm">
                                                            <label for="regno" class="form-label">Registration No</label>
                                                            <select class="select2 form-control" id="std_id" required
                                                                name="std_id"
                                                                onchange="get_std_name()"><?php echo $student_id_options; ?>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3 fm">
                                                            <label for="studentname" class="form-label">Student
                                                                Name</label>
                                                            <input type="text" id="std_name" name="std_name"
                                                                class="form-control" required
                                                                value="<?php echo $std_name; ?>" readonly>
                                                            <input type="hidden" id="std_reg_no" name="std_reg_no"
                                                                class="form-control" value="<?php echo $std_reg_no; ?>">
                                                        </div>

                                                        <div class="col-md-3 fm">
                                                            <label for="frm_dist" class="form-label">From
                                                                District</label>
                                                            <select class="select2 form-control" id="from_district"
                                                                name="from_district" required disabled>
                                                                <?php echo $from_district_name_list; ?>

                                                            </select>


                                                        </div>
                                                        <div class="col-md-3 fm">
                                                            <label for="frm_tlk" class="form-label">From
                                                                Taluk</label>
                                                            <select class="select2 form-control" id="from_taluk"
                                                                name="from_taluk" required disabled>

                                                                <?php echo $from_taluk_name_list; ?>

                                                            </select>

                                                        </div>


                                                        <!-- <input type="text" value="<?php echo $hostel_type; ?>"> -->


                                                        <div class="col-md-3 fm ">
                                                            <label for="frm_hstl" class="form-label">From
                                                                Hostel</label>
                                                            <select class="select2 form-control" id="from_hostel"
                                                                name="from_hostel" required disabled>

                                                                <?php echo $from_hostel_name_list; ?>

                                                            </select>
                                                        </div>

                                                        <div class="col-md-3 fm ">
                                                            <label for="to_dist" class="form-label">To
                                                                District</label>
                                                            <select class="select2 form-control" id="to_district"
                                                                name="to_district" onchange="get_taluk()" required>
                                                                <?php echo $to_district_name_list; ?>
                                                            </select>

                                                        </div>

                                                        <div class="col-md-3 fm">
                                                            <label for="to_tlk" class="form-label">To Taluk</label>
                                                            <select class="select2 form-control" id="to_taluk"
                                                                name="to_taluk" onchange="get_hostel()" required>

                                                                <?php echo $to_taluk_name_list; ?>

                                                            </select>

                                                        </div>

                                                        <div class="col-md-3 fm ">
                                                            <label for="to_hstl" class="form-label">To
                                                                Hostel</label>
                                                            <select class="select2 form-control" id="to_hostel"
                                                                name="to_hostel" required>

                                                                <?php echo $to_hostel_name_list; ?>

                                                            </select>

                                                        </div>

                                                    </div>
                                                    <div class="btns mt-3">
                                                        <?php echo btn_cancel($btn_cancel); ?>
                                                        <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div>
            </div>