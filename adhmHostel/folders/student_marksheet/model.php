<?php
// Form variables
$btn_text = "Verify & Save";
$btn_action = "create";

$unique_id = "";
$user_type = "";
$is_active = 1;

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);

        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "student_marksheet";

        $columns = [
            "semester_type",
            "cgpa",
            "std_unique_id",
            "file_name",
            "unique_id",
            "academic_year",
            "sem_status"

        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $sem_type = $result_values[0]["semester_type"];
            $cgpa = $result_values[0]["cgpa"];
            $student_id = $result_values[0]["std_unique_id"];
            $upd_file = $result_values[0]["file_name"];
            $academic_year = $result_values[0]["academic_year"];
            $sem_status = $result_values[0]["sem_status"];



            $btn_text = "Verify & Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$active_status_options = active_status($is_active);

$student_id_options = student_reg_list('', $_SESSION['hostel_id']);
$student_id_options = select_option($student_id_options, 'Select Student ID', $student_id);

$academic_year_options = all_academic_year();
$academic_year_options = select_option($academic_year_options, 'Select', $academic_year);


$sem_status_options = [
    "1" => [
        "unique_id" => "all_pass",
        "Value" => "All Pass"
    ],
    "2" => [
        "unique_id" => "1",
        "Value" => "1 Subject Arrear"
    ],
    "3" => [
        "unique_id" => "2",
        "value" => "2 Subject Arrear"
    ],
    "4" => [
        "unique_id" => "3",
        "value" => "3 Subject Arrear"
    ],
    "5" => [
        "unique_id" => "4",
        "value" => "4 Subject Arrear"
    ],
    "6" => [
        "unique_id" => "5",
        "value" => "5 Subject Arrear"
    ],
    "7" => [
        "unique_id" => "6",
        "value" => "6 Subject Arrear"
    ]

];
$sem_status_options = select_option($sem_status_options, "Select", $sem_status);



$sem_type_options = [
    "1" => [
        "unique_id" => "EVEN",
        "Value" => "EVEN"
    ],
    "2" => [
        "unique_id" => "ODD",
        "value" => "ODD"
    ],
];
$sem_type_options = select_option($sem_type_options, "Select", $sem_type);


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

                        <h4 class="page-title">Student Marksheet</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="was-validated" autocomplete="off">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Academic Year</label>
                                            <select name="academic_year" id="academic_year" class="select2 form-control"
                                                required>
                                                <?php echo $academic_year_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Register No</label>
                                            <select name="reg_no" id="reg_no" class="select2 form-control" onchange="get_std_name()" required>
                                                <?php echo $student_id_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Student Name</label>
                                            <input type="text" class="form-control" id="student_name" name="student_name"
                                                value="<?= $student_name; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Semester Type</label>
                                            <select name="sem_type" id="sem_type" class="select2 form-control" required>
                                                <?php echo $sem_type_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Status</label>
                                            <select name="sem_status" id="sem_status" class="select2 form-control"
                                                required>
                                                <?php echo $sem_status_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>CGPA</label>
                                            <input type="text" name="cgpa" id="cgpa" class="form-control"
                                                value="<?php echo $cgpa; ?>" dir="rtl" oninput="dec_number(this)"
                                                minlength="1" maxlength="5" required>

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Marksheet Upload</label>
                                            <input type="file" class="form-control" name="file_name" id="file_name"
                                                accept=".pdf,.doc,.docx,image/*" required>
                                            <input type="hidden" id="upd_file" value="<?php echo $upd_file; ?>">
                                            <input type="hidden" id="unique_id"
                                                value="<?php echo $unique_id; ?>">

                                        </div>
                                    </div>

                                </div>
                                <div class="btns">
                                    <?php echo btn_cancel($btn_cancel); ?>
                                    <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>