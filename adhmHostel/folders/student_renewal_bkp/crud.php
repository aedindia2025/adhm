<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Country Table Name
$table = "std_app_s";
$table_p2 = "std_app_p2";
$table_p3 = "std_app_p3";
$table_p4 = "std_app_p4";
$table_p5 = "std_app_p5";
$table_p6 = "std_app_p6";
$table_p7 = "std_app_p7";
$table_p8 = "std_app_p8";
$table_p9 = "std_app_p9";
$table_p10 = "std_app_p10";
$table_p11 = "std_app_p11";
$table_p12 = "std_app_p12";
$table_batch = "batch_creation";

$table2 = "print_for_dispatch";

// Include DB file and Common Functions
include '../../config/dbconfig.php';
// include 'function.php';

// Variables Declaration
$action = $_POST['action'];

$hostel_type = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose
function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
switch ($action) {

    case 'auto_datatable':
        // DataTable Variables

        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;
        $hostel_id = $_SESSION['hostel_id'];
        $is_delete = '0';

        $data = [];

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "std_reg_no",
            "std_name",
            "name_emis_umis",
            "name_diff",
            "std_to_inst_distance",
            "std_to_hostel_distance",
            "father_name",
            "std_degree",
            "'' as action",
            "(select unique_id from std_reg_s where std_reg_s.std_reg_no = renewal.std_reg_no) as s1_unique_id",


        ];
        $table1 = 'renewal';

        $where = "renewal_opt = ? and hostel_id = ? and submit_status = ? and transfer_status = ? and exit_status = ?";
        $params = [];
        $renewal_opt = 1;
        $submit_status = 0;
        $transfer_status = 0;
        $exit_status = 0;

        $params[] = $hostel_id;
        $params[] = $renewal_opt;
        $params[] = $submit_status;
        $params[] = $transfer_status;
        $params[] = $exit_status;

        $sql_function = "SQL_CALC_FOUND_ROWS";
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table1 WHERE $where";

        if ($limit != "") {
            $sql .= " LIMIT ?, ?";
        }

        $types = ''; // Initialize types string for bind_param

        // Prepare and execute the query
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }


        if ($limit != "") {
            $stmt->bind_param('isiiiii', $renewal_opt, $hostel_id, $submit_status, $transfer_status, $exit_status, $start, $limit);
        } else {
            $stmt->bind_param('isiii', $renewal_opt, $hostel_id, $submit_status, $transfer_status, $exit_status);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Get total records count
        $result_total = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $result_total->fetch_assoc()['total'];

        if ($result->num_rows > 0) {
            $s_no = 0;
            while ($row = $result->fetch_assoc()) {

                // Convert name_diff to descriptive and colored label
                $score = floatval($row['name_diff']);
                if ($score == 1) {
                    $name_status = '<span style="color: green; font-weight: bold;">Matched</span>';
                } elseif ($score >= 0.6) {
                    $name_status = '<span style="color: orange; font-weight: bold;">Partially Matched</span>';
                } else {
                    $name_status = '<span style="color: red; font-weight: bold;">Mismatched</span>';
                }
                $row['name_diff'] = $name_status;

                $inst_distance_check = floor($row['std_to_inst_distance']);
                if ($row['std_to_inst_distance']) {
                    if ($inst_distance_check <= 5) {
                        $row['std_to_inst_distance'] = '<span style="color: red; font-weight: bold;">' . $inst_distance_check . ' km</span>';
                    } else if ($inst_distance_check > 5) {
                        $row['std_to_inst_distance'] = '<span style="color: green; font-weight: bold;">' . $inst_distance_check . ' km</span>';
                    } else {
                        $row['std_to_inst_distance'] = '-';
                    }
                } else {
                    $row['std_to_inst_distance'] = '-';
                }

                $host_distance_check = floor($row['std_to_hostel_distance']);

                if ($row['std_to_hostel_distance']) {
                    if ($host_distance_check <= 5) {
                        $row['std_to_hostel_distance'] = '<span style="color: red; font-weight: bold;">' . $host_distance_check . ' km</span>';
                    } else if ($host_distance_check > 5) {
                        $row['std_to_hostel_distance'] = '<span style="color: green; font-weight: bold;">' . $host_distance_check . ' km</span>';
                    } else {
                        $row['std_to_hostel_distance'] = '-';
                    }
                } else {
                    $row['std_to_hostel_distance'] = '-';
                }


                $transferbtn = '<button class="transferbtn" data-std_name="' . $row['std_name'] . '" data-reg_no="' . $row['std_reg_no'] . '" data-s1_unique_id="' . $row['s1_unique_id'] . '" onclick="transferStudent(this)">Transfer</button>';
                $exitBtn = '<button class="exitBtn" data-std_name="' . $row['std_name'] . '" data-reg_no="' . $row['std_reg_no'] . '" data-s1_unique_id="' . $row['s1_unique_id'] . '">Exit Hostel</button>';
                $renewBtn = '<button class="renewBtn" data-std_name="' . $row['std_name'] . '" data-reg_no="' . $row['std_reg_no'] . '" data-s1_unique_id="' . $row['s1_unique_id'] . '" onclick="autoRenewal(this)">Renew</button>';
                $row['action'] = $transferbtn . $exitBtn . $renewBtn;

                $data[] = array_values($row);
            }
        }

        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

        $json_array = [
            "draw" => $draw,
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data,
        ];


        echo json_encode($json_array);
        $stmt->close();
        break;


    case 'm_datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;
        $hostel_id = $_SESSION['hostel_id'];
        $is_delete = '0';

        $data = [];
        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "std_reg_no",
            "std_name",
            "name_emis_umis",
            "name_diff",
            "std_to_inst_distance",
            "std_to_hostel_distance",
            "father_name",
            "std_degree", 
            "'' as action",
            "(select unique_id from std_reg_s where std_reg_s.std_reg_no = renewal.std_reg_no order by id desc limit 1) as s1_unique_id"
        ];
        $table1 = 'renewal';

        $where = "renewal_opt = ? and hostel_id = ? and manual_submit_status = ? and transfer_status = ? and exit_status = ?";
        $params = [];
        $renewal_opt = 2;
        $manual_submit_status = 0;
        $transfer_status = 0;
        $exit_status = 0;

        $params[] = $hostel_id;
        $params[] = $renewal_opt;
        $params[] = $manual_submit_status;
        $params[] = $transfer_status;
        $params[] = $exit_status;

        $sql_function = "SQL_CALC_FOUND_ROWS";
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table1 WHERE $where";
        // print_r($sql);

        $types = ''; // Initialize types string for bind_param

        if ($limit != "") {
            $sql .= " LIMIT ?, ?";
        }
        // Prepare and execute the query
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        if ($limit != "") {
            $stmt->bind_param('isiiiii', $renewal_opt, $hostel_id, $manual_submit_status, $transfer_status, $exit_status, $start, $limit);
        } else {
            $stmt->bind_param('isiii', $renewal_opt, $hostel_id, $manual_submit_status, $transfer_status, $exit_status);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Get total records count
        $result_total = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $result_total->fetch_assoc()['total'];

        if ($result->num_rows > 0) {
            $s_no = 0;
            while ($row = $result->fetch_assoc()) {

                // Convert name_diff to descriptive and colored label
                $score = floatval($row['name_diff']);
                if ($score == 1) {
                    $name_status = '<span style="color: green; font-weight: bold;">Matched</span>';
                } elseif ($score >= 0.6) {
                    $name_status = '<span style="color: orange; font-weight: bold;">Partially Matched</span>';
                } else {
                    $name_status = '<span style="color: red; font-weight: bold;">Mismatched</span>';
                }
                $row['name_diff'] = $name_status;

                $inst_distance_check = floor($row['std_to_inst_distance']);
                if ($row['std_to_inst_distance']) {
                    if ($inst_distance_check <= 5) {
                        $row['std_to_inst_distance'] = '<span style="color: red; font-weight: bold;">' . $inst_distance_check . ' km</span>';
                    } else if ($inst_distance_check > 5) {
                        $row['std_to_inst_distance'] = '<span style="color: green; font-weight: bold;">' . $inst_distance_check . ' km</span>';
                    } else {
                        $row['std_to_inst_distance'] = '-';
                    }
                } else {
                    $row['std_to_inst_distance'] = '-';
                }

                $host_distance_check = floor($row['std_to_hostel_distance']);

                if ($row['std_to_hostel_distance']) {
                    if ($host_distance_check <= 5) {
                        $row['std_to_hostel_distance'] = '<span style="color: red; font-weight: bold;">' . $host_distance_check . ' km</span>';
                    } else if ($host_distance_check > 5) {
                        $row['std_to_hostel_distance'] = '<span style="color: green; font-weight: bold;">' . $host_distance_check . ' km</span>';
                    } else {
                        $row['std_to_hostel_distance'] = '-';
                    }
                } else {
                    $row['std_to_hostel_distance'] = '-';
                }

                $m_transferbtn = '<button class="m_transferbtn" data-std_name="' . $row['std_name'] . '" data-reg_no="' . $row['std_reg_no'] . '" data-s1_unique_id="' . $row['s1_unique_id'] . '" onclick="m_transferStudent(this)">Transfer</button>';
                $m_exitBtn = '<button class="m_exitBtn" data-std_name="' . $row['std_name'] . '" data-reg_no="' . $row['std_reg_no'] . '" data-s1_unique_id="' . $row['s1_unique_id'] . '" >Exit Hostel</button>';
                $m_renewBtn = '<button class="m_renewBtn" data-std_name="' . $row['std_name'] . '" data-reg_no="' . $row['std_reg_no'] . '" data-s1_unique_id="' . $row['s1_unique_id'] . '" onclick="manualRenewal(this)">Request Renew</button>';
                $row['action'] = $m_transferbtn . $m_exitBtn . $m_renewBtn;

                // Other data manipulations as necessary

                $data[] = array_values($row);
            }
        }

        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

        $json_array = [
            "draw" => $draw,
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data,
        ];


        echo json_encode($json_array);
        $stmt->close();
        break;

    case 'autoRenewal':
        $s1_unique_id = $_POST['s1_unique_id'];
        $common_unique_id = unique_id($prefix);
        $common_entry_date = date("Y-m-d");

        // Get latest academic year unique_id
        $academic_year_id = '';
        $res = $pdo->select(["academic_year_creation", ["unique_id"]], "is_delete = 0 ORDER BY s_no DESC LIMIT 1");
        if ($res->status && !empty($res->data)) {
            $academic_year_id = $res->data[0]['unique_id'];
        } else {
            echo json_encode(["status" => false, "msg" => "error", "error" => "Failed to fetch academic year"]);
            exit;
        }

        // Helper function to duplicate related tables
        function duplicateData($pdo, $table, $s1_unique_id, $new_unique_id, $entry_date)
        {
            $res = $pdo->select([$table, ["*"]], 's1_unique_id = "' . $s1_unique_id . '"');
            if (!$res->status || empty($res->data))
                return (object) ["status" => true];

            foreach ($res->data as &$row) {
                unset($row['id'], $row['created'], $row['updated']);
                $row['entry_date'] = $entry_date;
                $row['s1_unique_id'] = $new_unique_id;
            }

            foreach ($res->data as $row) {
                $check = $pdo->select([$table, ["id"]], 's1_unique_id = "' . $new_unique_id . '"');
                if ($check->status && empty($check->data)) {
                    $insert = $pdo->insert($table, $row);
                    if (!$insert->status)
                        return $insert;
                }
            }

            return (object) ["status" => true];
        }

        // Step 1: Duplicate std_app_s
        $res_main = $pdo->select(["std_app_s", ["*"]], 'unique_id = "' . $s1_unique_id . '"');
        if (!$res_main->status || empty($res_main->data)) {
            echo json_encode(["status" => false, "msg" => "no_data_found", "error" => $res_main->error]);
            exit;
        }

        $row = $res_main->data[0];

        unset($row['id'], $row['batch_no'], $row['batch_cr_date'], $row['status_upd_date'], $row['status'], $row['submit_status'], $row['created'], $row['updated'], $row['application_type']);
        $row['unique_id'] = $common_unique_id;
        $row['batch_no'] = null;
        $row['batch_cr_date'] = null;
        $row['status_upd_date'] = null;
        $row['status'] = 0;
        $row['submit_status'] = 1;
        $row['entry_date'] = $common_entry_date;
        $row['academic_year'] = $academic_year_id;
        $row['application_type'] = 2;

        $insert_main = $pdo->insert("std_app_s", $row);
        if (!$insert_main->status) {
            echo json_encode(["status" => false, "msg" => "error", "error" => $insert_main->error]);
            exit;
        }

        // Step 2: Duplicate child tables
        $tables = ["std_app_s2", "std_app_emis_s3", "std_app_umis_s4", "std_app_s5", "std_app_s6", "std_app_s7"];
        foreach ($tables as $table) {
            $res_copy = duplicateData($pdo, $table, $s1_unique_id, $common_unique_id, $common_entry_date);
            if (!$res_copy->status) {
                echo json_encode(["status" => false, "msg" => "error", "error" => $res_copy->error]);
                exit;
            }
        }

        // Step 4: Update submit status in renewal table
        $update_columns = ["submit_status" => 1];
        $update_where = 's1_unique_id = "' . $s1_unique_id . '"';
        $update_action = $pdo->update("renewal", $update_columns, $update_where);

        // Step 5: If transfer_hostel is set, update hostel fields in std_app_s and std_app_s7

        // Select transfer_hostel from std_app_s
        $table_transfer_h = [
            "std_app_s",
            ["transfer_hostel"]
        ];
        $select_where_h = 'is_delete = 0 AND unique_id = "' . $s1_unique_id . '"';

        $action_obj_h = $pdo->select($table_transfer_h, $select_where_h);

        if ($action_obj_h->status && !empty($action_obj_h->data)) {
            $transfer_hostel = $action_obj_h->data[0]["transfer_hostel"];

            if (!empty($transfer_hostel)) {
                // Select district and taluk from hostel_name
                $table_hostel = [
                    "hostel_name",
                    ["district_name", "taluk_name"]
                ];
                $where_hostel = 'is_delete = 0 AND unique_id = "' . $transfer_hostel . '"';

                $hostel_info = $pdo->select($table_hostel, $where_hostel);

                if ($hostel_info->status && !empty($hostel_info->data)) {
                    $district_name = $hostel_info->data[0]["district_name"];
                    $taluk_name = $hostel_info->data[0]["taluk_name"];

                    // Update std_app_s
                    $update_std_s_data = [
                        "hostel_1" => $transfer_hostel,
                        "hostel_district_1" => $district_name,
                        "hostel_taluk_1" => $taluk_name
                    ];
                    $update_std_s_where = 'unique_id = "' . $s1_unique_id . '"';

                    $update_std_app_s = $pdo->update("std_app_s", $update_std_s_data, $update_std_s_where);

                    if (!$update_std_app_s) {
                        echo json_encode(["status" => false, "msg" => "error", "error" => "Failed to update std_app_s"]);
                        exit;
                    }

                    // Update std_app_s7
                    $update_std_s7_data = [
                        "hostel_name" => $transfer_hostel,
                        "hostel_district" => $district_name,
                        "hostel_taluk" => $taluk_name
                    ];
                    $update_std_s7_where = 's1_unique_id = "' . $s1_unique_id . '"';

                    $update_std_app_s7 = $pdo->update("std_app_s7", $update_std_s7_data, $update_std_s7_where);

                    if (!$update_std_app_s7) {
                        echo json_encode(["status" => false, "msg" => "error", "error" => "Failed to update std_app_s7"]);
                        exit;
                    }
                }
            }
        }

        // Step 6: Update std_app_umis_s4 with UMIS data if renewal_umis_no is present
        $table_renewal = ["std_app_umis_s4", ["renewal_umis_no"]];
        $where_renewal = 'is_delete = 0 AND s1_unique_id = "' . $s1_unique_id . '"';
        $res_renewal = $pdo->select($table_renewal, $where_renewal);


        if ($res_renewal->status && !empty($res_renewal->data)) {
            $renewal_umis_no = $res_renewal->data[0]["renewal_umis_no"];

            if (!empty($renewal_umis_no)) {
                // Get values from umis_1
                $table_umis1 = ["umis_1", ["name", "dateOfBirth"]];
                $where_umis1 = 'is_delete = 0 AND umis_no = "' . $renewal_umis_no . '"';
                $res_umis1 = $pdo->select($table_umis1, $where_umis1);

                // Get values from umis_2
                $table_umis2 = ["umis_2", ["dateOfAdmission", "caAddress", "courseSpecializationId", "courseId"]];
                $where_umis2 = 'is_delete = 0 AND umis_no = "' . $renewal_umis_no . '"';
                $res_umis2 = $pdo->select($table_umis2, $where_umis2);

                // Get values from umis_3
                $table_umis3 = ["umis_3", ["yearOfStudy", "instituteName"]];
                $where_umis3 = 'is_delete = 0 AND umis_no = "' . $renewal_umis_no . '"';
                $res_umis3 = $pdo->select($table_umis3, $where_umis3);

                if (
                    $res_umis1->status && !empty($res_umis1->data) &&
                    $res_umis2->status && !empty($res_umis2->data) &&
                    $res_umis3->status && !empty($res_umis3->data)
                ) {
                    $name = $res_umis1->data[0]["name"];
                    $dob = date("Y-m-d", strtotime($res_umis1->data[0]["dateOfBirth"]));
                    $yoa = date("Y", strtotime($res_umis2->data[0]["dateOfAdmission"]));
                    $caAddress = $res_umis2->data[0]["caAddress"];
                    $courseSpecializationId = $res_umis2->data[0]["courseSpecializationId"];
                    $courseId = $res_umis2->data[0]["courseId"];
                    $yearOfStudy = $res_umis3->data[0]["yearOfStudy"];
                    $instituteName = $res_umis3->data[0]["instituteName"];

                    // Get branch name
                    $table_branch = ["umis_coursebranch", ["CourseBranchName"]];
                    $where_branch = 'id = "' . $courseSpecializationId . '"';
                    $res_branch = $pdo->select($table_branch, $where_branch);
                    $branchName = ($res_branch->status && !empty($res_branch->data)) ? $res_branch->data[0]["CourseBranchName"] : NULL;

                    // Get course name
                    $table_course = ["umis_course", ["CourseName"]];
                    $where_course = 'id = "' . $courseId . '"';
                    $res_course = $pdo->select($table_course, $where_course);
                    $courseName = ($res_course->status && !empty($res_course->data)) ? $res_course->data[0]["CourseName"] : NULL;

                    // Prepare update
                    $update_umis_s4_data = [
                        "umis_name" => $name,
                        "umis_dob" => $dob,
                        "umis_yoa" => $yoa,
                        "umis_yos" => $yearOfStudy,
                        "umis_clg_name" => $instituteName,
                        "umis_clg_add" => $caAddress,
                        "umis_std_course" => $branchName,
                        "umis_std_degree" => $courseName
                    ];
                    $update_umis_s4_where = 's1_unique_id = "' . $common_unique_id . '"';
                    $update_umis_s4_result = $pdo->update("std_app_umis_s4", $update_umis_s4_data, $update_umis_s4_where);

                    if (!$update_umis_s4_result) {
                        echo json_encode(["status" => false, "msg" => "error", "error" => "Failed to update std_app_umis_s4 from UMIS"]);
                        exit;
                    }
                }
            }
        }

        // Step 7: Get student_type from std_app_s
        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        // Step 7: Get student_type from std_app_s
        $sql = "SELECT student_type FROM std_app_s WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $common_unique_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $student_type = $row['student_type'];

            if ($student_type === '65f00a259436412348') {

                // Fetch current class and increment it
                $check_sql = "SELECT class FROM std_app_emis_s3 WHERE s1_unique_id = ?";
                $check_stmt = $mysqli->prepare($check_sql);
                $check_stmt->bind_param("s", $common_unique_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($class_row = $check_result->fetch_assoc()) {
                    $current_class = (int) $class_row['class'];
                    $new_class = $current_class + 1;

                    $update_sql = "UPDATE std_app_emis_s3 SET class = ? WHERE s1_unique_id = ?";
                    $update_stmt = $mysqli->prepare($update_sql);
                    $update_stmt->bind_param("is", $new_class, $common_unique_id);
                    $update_stmt->execute();
                }
            } else {

                // Fetch current year_studying and increment it
                $check_sql = "SELECT year_studying FROM std_app_umis_s4 WHERE s1_unique_id = ?";
                $check_stmt = $mysqli->prepare($check_sql);
                $check_stmt->bind_param("s", $common_unique_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($year_row = $check_result->fetch_assoc()) {

                    $current_year = (int) $year_row['year_studying'];
                    $new_year = $current_year + 1;

                    $update_sql = "UPDATE std_app_umis_s4 SET year_studying = ? WHERE s1_unique_id = ?";
                    $update_stmt = $mysqli->prepare($update_sql);
                    $update_stmt->bind_param("is", $new_year, $common_unique_id);
                    $update_stmt->execute();
                }
            }
        }


        echo json_encode(["status" => true, "msg" => "auto_renewal_success", "new_unique_id" => $common_unique_id]);
        break;

    case 'manualRenewal':
        $m_s1_unique_id = $_POST['m_s1_unique_id'];

        // Step 1: Update manual submit status in renewal table
        $update_columns = ["manual_submit_status" => 1];
        $update_where = 's1_unique_id = "' . $m_s1_unique_id . '"';
        $update_action = $pdo->update("renewal", $update_columns, $update_where);

        echo json_encode(["status" => true, "msg" => "manual_renewal_success"]);
        break;


    case 'check_marksheet':
        $table_m = "student_marksheet";
        $s1_unique_id = $_POST["s1_unique_id"];
        $is_delete = 0;

        // DB connection
        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Step 0: Check if the student is ITI
        $sql_type = "SELECT student_type FROM std_app_s WHERE unique_id = ? LIMIT 1";
        $stmt_type = $mysqli->prepare($sql_type);
        if (!$stmt_type) {
            echo json_encode(["status" => false, "msg" => "error"]);
            break;
        }

        $stmt_type->bind_param("s", $s1_unique_id);
        $stmt_type->execute();
        $result_type = $stmt_type->get_result();

        if ($row_type = $result_type->fetch_assoc()) {
            $student_type = $row_type['student_type'];

            // If ITI student, consider marksheet as valid
            if ($student_type === '65f00a327c08582160') {
                echo json_encode(["status" => true, "msg" => "valid"]);
                $stmt_type->close();
                $mysqli->close();
                break;
            }
        }
        $stmt_type->close();

        // Step 1: Check if any record exists in student_marksheet
        $sql_count = "SELECT COUNT(*) AS total FROM $table_m WHERE is_delete = ? AND std_unique_id = ?";
        $stmt_count = $mysqli->prepare($sql_count);

        if (!$stmt_count) {
            echo json_encode(["status" => false, "msg" => "error"]);
            break;
        }

        $stmt_count->bind_param("is", $is_delete, $s1_unique_id);
        $stmt_count->execute();
        $stmt_count->bind_result($total_count);
        $stmt_count->fetch();
        $stmt_count->close();

        // If no record found
        if ($total_count == 0) {
            echo json_encode(["status" => false, "msg" => "no_exist"]);
            $mysqli->close();
            break;
        }

        // Step 2: Get latest sem_status
        $sql_status = "SELECT sem_status FROM $table_m WHERE is_delete = ? AND std_unique_id = ? ORDER BY id DESC LIMIT 1";
        $stmt_status = $mysqli->prepare($sql_status);

        if (!$stmt_status) {
            echo json_encode(["status" => false, "msg" => "error"]);
            break;
        }

        $stmt_status->bind_param("is", $is_delete, $s1_unique_id);
        $stmt_status->execute();
        $result = $stmt_status->get_result();

        $status = false;
        $msg = "error";

        if ($row = $result->fetch_assoc()) {
            $sem_status = intval($row['sem_status']);

            if ($sem_status === 5 || $sem_status === 6) {
                $msg = "not_valid";
            } else {
                $msg = "valid";
                $status = true;
            }
        }

        $stmt_status->close();
        $mysqli->close();

        echo json_encode(["status" => $status, "msg" => $msg]);

        break;


    case 'm_check_marksheet':
        $table_m = "student_marksheet";
        $m_s1_unique_id = $_POST["m_s1_unique_id"];
        $is_delete = 0;

        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Step 0: Check if the student is ITI
        $sql_type = "SELECT student_type FROM std_app_s WHERE unique_id = ?";
        $stmt_type = $mysqli->prepare($sql_type);
        if ($stmt_type) {
            $stmt_type->bind_param("s", $m_s1_unique_id);
            $stmt_type->execute();
            $stmt_type->bind_result($student_type);
            if ($stmt_type->fetch()) {
                if ($student_type === "65f00a327c08582160") {
                    // ITI student — automatically valid
                    echo json_encode(["status" => true, "msg" => "valid"]);
                    $stmt_type->close();
                    $mysqli->close();
                    break;
                }
            }
            $stmt_type->close();
        }

        // Step 1: Check if any record exists
        $sql_count = "SELECT COUNT(*) AS total FROM $table_m WHERE is_delete = ? AND std_unique_id = ?";
        $stmt_count = $mysqli->prepare($sql_count);
        if (!$stmt_count) {
            echo json_encode(["status" => false, "msg" => "error"]);
            break;
        }

        $stmt_count->bind_param("is", $is_delete, $m_s1_unique_id);
        $stmt_count->execute();
        $stmt_count->bind_result($total_count);
        $stmt_count->fetch();
        $stmt_count->close();

        if ($total_count == 0) {
            echo json_encode(["status" => false, "msg" => "no_exist"]);
            $mysqli->close();
            break;
        }

        // Step 2: Get latest sem_status
        $sql_status = "SELECT sem_status FROM $table_m WHERE is_delete = ? AND std_unique_id = ? ORDER BY id DESC LIMIT 1";
        $stmt_status = $mysqli->prepare($sql_status);
        if (!$stmt_status) {
            echo json_encode(["status" => false, "msg" => "error"]);
            break;
        }

        $stmt_status->bind_param("is", $is_delete, $m_s1_unique_id);
        $stmt_status->execute();
        $result = $stmt_status->get_result();

        $status = false;
        $msg = "error";

        if ($row = $result->fetch_assoc()) {
            $sem_status = intval($row['sem_status']);
            if ($sem_status === 5 || $sem_status === 6) {
                $msg = "not_valid";
            } else {
                $msg = "valid";
                $status = true;
            }
        }

        $stmt_status->close();
        $mysqli->close();

        echo json_encode(["status" => $status, "msg" => $msg]);
        break;

    case 'get_student_type':
        $s1_unique_id = $_POST['s1_unique_id'];
        $stmt = $mysqli->prepare("SELECT student_type FROM std_app_s WHERE unique_id = ?");
        $stmt->bind_param("s", $s1_unique_id);
        $stmt->execute();
        $stmt->bind_result($student_type);
        $stmt->fetch();
        $stmt->close();

        echo json_encode(["student_type" => $student_type]);
        break;

    case 'm_get_student_type':
        $m_s1_unique_id = $_POST['m_s1_unique_id'];
        $stmt = $mysqli->prepare("SELECT student_type FROM std_app_s WHERE unique_id = ?");
        $stmt->bind_param("s", $m_s1_unique_id);
        $stmt->execute();
        $stmt->bind_result($m_student_type);
        $stmt->fetch();
        $stmt->close();

        echo json_encode(["m_student_type" => $m_student_type]);
        break;


    case 'check_umis_no':
        $s1_unique_id = $_POST['s1_unique_id'];

        $stmt = $mysqli->prepare("SELECT umis_no, renewal_umis_no FROM std_app_umis_s4 WHERE s1_unique_id = ?");
        $stmt->bind_param("s", $s1_unique_id);
        $stmt->execute();
        $stmt->bind_result($umis_no, $renewal_umis_no);
        $stmt->fetch();
        $stmt->close();

        $response = [
            "status" => 1,
            "umis_no" => $umis_no,
            "renewal_umis_no" => $renewal_umis_no,
            "msg" => "UMIS check complete"
        ];
        echo json_encode($response);
        break;

    case 'm_check_umis_no':
        $m_s1_unique_id = $_POST['m_s1_unique_id'];

        $stmt = $mysqli->prepare("SELECT umis_no, renewal_umis_no FROM std_app_umis_s4 WHERE s1_unique_id = ?");
        $stmt->bind_param("s", $m_s1_unique_id);
        $stmt->execute();
        $stmt->bind_result($umis_no, $renewal_umis_no);
        $stmt->fetch();
        $stmt->close();

        $response = [
            "status" => 1,
            "umis_no" => $umis_no,
            "renewal_umis_no" => $renewal_umis_no,
            "msg" => "UMIS check complete"
        ];
        echo json_encode($response);
        break;

    case 'insert_umis':
        $umis_number = $_POST["umis_number"];

        $url = 'https://umisapi.tnega.org/api/ADWD/GetStudentData/' . $umis_number;

        // Data to send in the request
        $data = array(
            'umis_id' => $umis_number
        );

        // Convert data array to JSON
        $data_json = json_encode($data);

        // Authorization token
        $authorization_token = '4acdca2cc493c1ec28e1f68e0d37c49a';

        // Set headers
        $headers = array(
            'Content-Type: application/json',
            'Authorization: ' . $authorization_token
        );

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => json_decode($response),
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;


    case 'm_insert_umis':
        $m_umis_number = $_POST["m_umis_number"];

        $url = 'https://umisapi.tnega.org/api/ADWD/GetStudentData/' . $m_umis_number;

        // Data to send in the request
        $data = array(
            'umis_id' => $m_umis_number
        );

        // Convert data array to JSON
        $data_json = json_encode($data);

        // Authorization token
        $authorization_token = '4acdca2cc493c1ec28e1f68e0d37c49a';

        // Set headers
        $headers = array(
            'Content-Type: application/json',
            'Authorization: ' . $authorization_token
        );

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Prepare response
        $json_array = [
            "status" => ($response !== false),
            "data" => json_decode($response),
            "error" => isset($error) ? $error : "Data is not available!..",
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;


    case 'umis_already':
        // Decoding the uuid
        $table = "std_app_umis_s4";
        $umis_number = $_POST["umis_number"];
        $is_delete = 0;

        // Include the connection
        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Prepare the SQL statement to check both umis_no and renewal_umis_no
        $sql = "SELECT COUNT(unique_id) AS count 
            FROM $table 
            WHERE is_delete = ? 
              AND (umis_no = ? OR renewal_umis_no = ?)";

        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
            echo json_encode([
                "status" => false,
                "msg" => "error"
            ]);
            break;
        }

        // Bind the parameters
        $stmt->bind_param("iss", $is_delete, $umis_number, $umis_number);

        // Execute the statement
        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            echo json_encode([
                "status" => false,
                "msg" => "error"
            ]);
            break;
        }

        // Bind the result variable
        $stmt->bind_result($count);

        // Fetch the result
        $status = false;
        $msg = "error";
        if ($stmt->fetch()) {
            $status = true;
            $msg = ($count > 0) ? "already" : "not_found";
        }

        // Close the statement and connection
        $stmt->close();
        $mysqli->close();

        // Return JSON response
        echo json_encode([
            "status" => $status,
            "msg" => $msg
        ]);
        break;

    case 'm_umis_already':
        // Decoding the uuid
        $table = "std_app_umis_s4";
        $m_umis_number = $_POST["m_umis_number"];
        $is_delete = 0;

        // Include the connection
        $mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Prepare the SQL statement to check both umis_no and renewal_umis_no
        $sql = "SELECT COUNT(unique_id) AS count 
            FROM $table 
            WHERE is_delete = ? 
              AND (umis_no = ? OR renewal_umis_no = ?)";

        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
            echo json_encode([
                "status" => false,
                "msg" => "error"
            ]);
            break;
        }

        // Bind the parameters
        $stmt->bind_param("iss", $is_delete, $m_umis_number, $m_umis_number);

        // Execute the statement
        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            echo json_encode([
                "status" => false,
                "msg" => "error"
            ]);
            break;
        }

        // Bind the result variable
        $stmt->bind_result($count);

        // Fetch the result
        $status = false;
        $msg = "error";
        if ($stmt->fetch()) {
            $status = true;
            $msg = ($count > 0) ? "already" : "not_found";
        }

        // Close the statement and connection
        $stmt->close();
        $mysqli->close();

        // Return JSON response
        echo json_encode([
            "status" => $status,
            "msg" => $msg
        ]);
        break;


    case 'umis_insert':

        $update_where = "";
        $s1_unique_id = $_POST['s1_unique_id'];

        $columns_1 = [
            "umis_no" => $_POST['umis_number'],
            "name" => $_POST['name'],
            "emsid" => $_POST['emsid'],
            "dateOfBirth" => $_POST['dateOfBirth'],
            "nationalityId" => $_POST['nationalityId'],
            "religionId" => $_POST['religionId'],
            "communityId" => $_POST['communityId'],
            "casteId" => $_POST['casteId'],
            "isFirstGraduate" => $_POST['isFirstGraduate'],
            "isDifferentlyAbled" => $_POST['isDifferentlyAbled'],
            "isSpecialCategory" => $_POST['isSpecialCategory'],
            "udid" => $_POST['udid'],
            "disabilityId" => $_POST['disabilityId'],
            "extentOfDisability" => $_POST['extentOfDisability'],
            "bloodGroupId" => $_POST['bloodGroupId'],
            "genderId" => $_POST['genderId'],
            "salutationId" => $_POST['salutationId'],
            "instituteId" => $_POST['instituteId'],
            "umisId" => $_POST['umisId'],
            "nameAsOnCertificate" => $_POST['nameAsOnCertificate'],
            "isFirstGraduateVerifiedbyUniversity" => $_POST['isFirstGraduateVerifiedbyUniversity'],
            "isFirstGraduateVerifiedbyHod" => $_POST['isFirstGraduateVerifiedbyHod'],
            "mobileNumber" => $_POST['mobileNumber'],
            "emailId" => $_POST['emailId'],
            "permAddress" => $_POST['permAddress'],
            "s1_unique_id" => $_POST['s1_unique_id'],
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details_1 = [
            "umis_1",
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where_1 = '';
        $select_where_1 .= ' is_delete = 0 and s1_unique_id ="' . $_POST['s1_unique_id'] . '"';


        $action_obj_1 = $pdo->select($table_details_1, $select_where_1);

        $data = $action_obj_1->data;

        if ($data[0]["count"]) {

            $update_where_1 = [
                "s1_unique_id" => $s1_unique_id
            ];
            $action_obj = $pdo->update("umis_1", $columns_1, $update_where_1);
        } else if ($data[0]["count"] == 0) {
            $action_obj = $pdo->insert("umis_1", $columns_1);
        }

        $columns_2 = [
            "umis_no" => $_POST['umis_number'],
            "countryId" => $_POST['countryId'],
            "stateId" => $_POST['stateId'],
            "districtId" => $_POST['districtId'],
            "zoneId" => $_POST['zoneId'],
            "blockId" => $_POST['blockId'],
            "caCountryId" => $_POST['caCountryId'],
            "caStateId" => $_POST['caStateId'],
            "caDistrictId" => $_POST['caDistrictId'],
            "caZoneId" => $_POST['caZoneId'],
            "caCorporationId" => $_POST['caCorporationId'],
            "caAddress" => $_POST['caAddress'],
            "caBlockId" => $_POST['caBlockId'],
            "caVillagePanchayatId" => $_POST['caVillagePanchayatId'],
            "caWardId" => $_POST['caWardId'],
            "caTalukId" => $_POST['caTalukId'],
            "caVillageId" => $_POST['caVillageId'],
            "talukId" => $_POST['talukId'],
            "villageId" => $_POST['villageId'],
            "wardId" => $_POST['wardId'],
            "corporationId" => $_POST['corporationId'],
            "villagePanchayatId" => $_POST['villagePanchayatId'],
            "courseId" => $_POST['courseId'],
            "courseSpecializationId" => $_POST['courseSpecializationId'],
            "dateOfAdmission" => $_POST['dateOfAdmission'],
            "academicYearId" => $_POST['academicYearId'],
            "s1_unique_id" => $_POST['s1_unique_id'],
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details_2 = [
            "umis_2",
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where_2 = '';
        $select_where_2 .= ' is_delete = 0 and s1_unique_id ="' . $_POST['s1_unique_id'] . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj_2 = $pdo->select($table_details_2, $select_where_2);
        $data = $action_obj_2->data;

        if ($data[0]["count"]) {

            $update_where_2 = [
                "s1_unique_id" => $s1_unique_id
            ];
            $action_obj_2 = $pdo->update("umis_2", $columns_2, $update_where_2);
        } else if ($data[0]["count"] == 0) {
            $action_obj_2 = $pdo->insert("umis_2", $columns_2);
        }


        $columns_3 = [
            "umis_no" => $_POST['umis_number'],
            "courseType" => $_POST['courseType'],
            "streamInfoId" => $_POST['streamInfoId'],
            "mediumOfInstructionType" => $_POST['mediumOfInstructionType'],
            "academicStatusType" => $_POST['academicStatusType'],
            "yearOfStudy" => $_POST['yearOfStudy'],
            "isLateralEntry" => $_POST['isLateralEntry'],
            "isHosteler" => $_POST['isHosteler'],
            "hostelAdmissionDate" => $_POST['hostelAdmissionDate'],
            "leavingFromHostelDate" => $_POST['leavingFromHostelDate'],
            "studentId" => $_POST['studentId'],
            "parentMobileNo" => $_POST['parentMobileNo'],
            "fatherOccupationId" => $_POST['fatherOccupationId'],
            "motherOccupationId" => $_POST['motherOccupationId'],
            "guardianOccupationId" => $_POST['guardianOccupationId'],
            "aisheId" => $_POST['aisheId'],
            "instituteName" => $_POST['instituteName'],
            "instituteTypeId" => $_POST['instituteTypeId'],
            "instituteOwnershipId" => $_POST['instituteOwnershipId'],
            "instituteCategoryId" => $_POST['instituteCategoryId'],
            "instituteStatusType" => $_POST['instituteStatusType'],
            "universityName" => $_POST['universityName'],
            "universityTypeId" => $_POST['universityTypeId'],
            "hodName" => $_POST['hodName'],
            "departmentName" => $_POST['departmentName'],
            "s1_unique_id" => $_POST['s1_unique_id'],
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details_3 = [
            "umis_3",
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where_3 = '';
        $select_where_3 .= ' is_delete = 0 and s1_unique_id ="' . $_POST['s1_unique_id'] . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj_3 = $pdo->select($table_details_3, $select_where_3);
        $data = $action_obj_3->data;
        if ($data[0]["count"]) {

            $update_where_3 = [
                "s1_unique_id" => $s1_unique_id
            ];
            $action_obj_3 = $pdo->update("umis_3", $columns_3, $update_where_3);
        } else if ($data[0]["count"] == 0) {
            $action_obj_3 = $pdo->insert("umis_3", $columns_3);
        }

        $update_std_app_umis_s4_columns = [
            "renewal_umis_no" => $_POST['umis_number']
        ];

        $update_std_app_umis_s4_where = [
            "s1_unique_id" => $s1_unique_id
        ];

        $action_obj_4 = $pdo->update("std_app_umis_s4", $update_std_app_umis_s4_columns, $update_std_app_umis_s4_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;


    case 'm_umis_insert':

        $update_where = "";
        $m_s1_unique_id = $_POST['m_s1_unique_id'];

        $columns_1 = [
            "umis_no" => $_POST['m_umis_number'],
            "name" => $_POST['name'],
            "emsid" => $_POST['emsid'],
            "dateOfBirth" => $_POST['dateOfBirth'],
            "nationalityId" => $_POST['nationalityId'],
            "religionId" => $_POST['religionId'],
            "communityId" => $_POST['communityId'],
            "casteId" => $_POST['casteId'],
            "isFirstGraduate" => $_POST['isFirstGraduate'],
            "isDifferentlyAbled" => $_POST['isDifferentlyAbled'],
            "isSpecialCategory" => $_POST['isSpecialCategory'],
            "udid" => $_POST['udid'],
            "disabilityId" => $_POST['disabilityId'],
            "extentOfDisability" => $_POST['extentOfDisability'],
            "bloodGroupId" => $_POST['bloodGroupId'],
            "genderId" => $_POST['genderId'],
            "salutationId" => $_POST['salutationId'],
            "instituteId" => $_POST['instituteId'],
            "umisId" => $_POST['umisId'],
            "nameAsOnCertificate" => $_POST['nameAsOnCertificate'],
            "isFirstGraduateVerifiedbyUniversity" => $_POST['isFirstGraduateVerifiedbyUniversity'],
            "isFirstGraduateVerifiedbyHod" => $_POST['isFirstGraduateVerifiedbyHod'],
            "mobileNumber" => $_POST['mobileNumber'],
            "emailId" => $_POST['emailId'],
            "permAddress" => $_POST['permAddress'],
            "s1_unique_id" => $_POST['m_s1_unique_id'],
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details_1 = [
            "umis_1",
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where_1 = '';
        $select_where_1 .= ' is_delete = 0 and s1_unique_id ="' . $_POST['m_s1_unique_id'] . '"';


        $action_obj_1 = $pdo->select($table_details_1, $select_where_1);

        $data = $action_obj_1->data;

        if ($data[0]["count"]) {

            $update_where_1 = [
                "s1_unique_id" => $m_s1_unique_id
            ];
            $action_obj = $pdo->update("umis_1", $columns_1, $update_where_1);
        } else if ($data[0]["count"] == 0) {
            $action_obj = $pdo->insert("umis_1", $columns_1);
        }

        $columns_2 = [
            "umis_no" => $_POST['m_umis_number'],
            "countryId" => $_POST['countryId'],
            "stateId" => $_POST['stateId'],
            "districtId" => $_POST['districtId'],
            "zoneId" => $_POST['zoneId'],
            "blockId" => $_POST['blockId'],
            "caCountryId" => $_POST['caCountryId'],
            "caStateId" => $_POST['caStateId'],
            "caDistrictId" => $_POST['caDistrictId'],
            "caZoneId" => $_POST['caZoneId'],
            "caCorporationId" => $_POST['caCorporationId'],
            "caAddress" => $_POST['caAddress'],
            "caBlockId" => $_POST['caBlockId'],
            "caVillagePanchayatId" => $_POST['caVillagePanchayatId'],
            "caWardId" => $_POST['caWardId'],
            "caTalukId" => $_POST['caTalukId'],
            "caVillageId" => $_POST['caVillageId'],
            "talukId" => $_POST['talukId'],
            "villageId" => $_POST['villageId'],
            "wardId" => $_POST['wardId'],
            "corporationId" => $_POST['corporationId'],
            "villagePanchayatId" => $_POST['villagePanchayatId'],
            "courseId" => $_POST['courseId'],
            "courseSpecializationId" => $_POST['courseSpecializationId'],
            "dateOfAdmission" => $_POST['dateOfAdmission'],
            "academicYearId" => $_POST['academicYearId'],
            "s1_unique_id" => $_POST['m_s1_unique_id'],
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details_2 = [
            "umis_2",
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where_2 = '';
        $select_where_2 .= ' is_delete = 0 and s1_unique_id ="' . $_POST['m_s1_unique_id'] . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj_2 = $pdo->select($table_details_2, $select_where_2);
        $data = $action_obj_2->data;

        if ($data[0]["count"]) {

            $update_where_2 = [
                "s1_unique_id" => $m_s1_unique_id
            ];
            $action_obj_2 = $pdo->update("umis_2", $columns_2, $update_where_2);
        } else if ($data[0]["count"] == 0) {
            $action_obj_2 = $pdo->insert("umis_2", $columns_2);
        }


        $columns_3 = [
            "umis_no" => $_POST['m_umis_number'],
            "courseType" => $_POST['courseType'],
            "streamInfoId" => $_POST['streamInfoId'],
            "mediumOfInstructionType" => $_POST['mediumOfInstructionType'],
            "academicStatusType" => $_POST['academicStatusType'],
            "yearOfStudy" => $_POST['yearOfStudy'],
            "isLateralEntry" => $_POST['isLateralEntry'],
            "isHosteler" => $_POST['isHosteler'],
            "hostelAdmissionDate" => $_POST['hostelAdmissionDate'],
            "leavingFromHostelDate" => $_POST['leavingFromHostelDate'],
            "studentId" => $_POST['studentId'],
            "parentMobileNo" => $_POST['parentMobileNo'],
            "fatherOccupationId" => $_POST['fatherOccupationId'],
            "motherOccupationId" => $_POST['motherOccupationId'],
            "guardianOccupationId" => $_POST['guardianOccupationId'],
            "aisheId" => $_POST['aisheId'],
            "instituteName" => $_POST['instituteName'],
            "instituteTypeId" => $_POST['instituteTypeId'],
            "instituteOwnershipId" => $_POST['instituteOwnershipId'],
            "instituteCategoryId" => $_POST['instituteCategoryId'],
            "instituteStatusType" => $_POST['instituteStatusType'],
            "universityName" => $_POST['universityName'],
            "universityTypeId" => $_POST['universityTypeId'],
            "hodName" => $_POST['hodName'],
            "departmentName" => $_POST['departmentName'],
            "s1_unique_id" => $_POST['m_s1_unique_id'],
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details_3 = [
            "umis_3",
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where_3 = '';
        $select_where_3 .= ' is_delete = 0 and s1_unique_id ="' . $_POST['m_s1_unique_id'] . '"';
        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj_3 = $pdo->select($table_details_3, $select_where_3);
        $data = $action_obj_3->data;
        if ($data[0]["count"]) {

            $update_where_3 = [
                "s1_unique_id" => $m_s1_unique_id
            ];
            $action_obj_3 = $pdo->update("umis_3", $columns_3, $update_where_3);
        } else if ($data[0]["count"] == 0) {
            $action_obj_3 = $pdo->insert("umis_3", $columns_3);
        }

        $update_std_app_umis_s4_columns = [
            "renewal_umis_no" => $_POST['m_umis_number']
        ];

        $update_std_app_umis_s4_where = [
            "s1_unique_id" => $m_s1_unique_id
        ];

        $action_obj_4 = $pdo->update("std_app_umis_s4", $update_std_app_umis_s4_columns, $update_std_app_umis_s4_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

            if ($unique_id) {
                $msg = "update";
            } else {
                $msg = "save";
            }
        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;


    case 'get_taluk_id':

        $district_id = $_POST['district_id'];

        $taluk_name_options = taluk_name('', $district_id);

        $taluk_name_options = select_option($taluk_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel_id':

        $taluk_id = $_POST['taluk_id'];

        $gender_id = $_POST['gender_id'];

        $hostel_type_id = $_POST['hostel_type_id'];

        $hostel_name_options = hostel_gender_type('', $taluk_id, $gender_id, $hostel_type_id);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;

    case 'm_get_taluk_id':

        $m_district_id = $_POST['m_district_id'];

        $m_taluk_name_options = taluk_name('', $m_district_id);

        $m_taluk_name_options = select_option($m_taluk_name_options, "Select Taluk");

        echo $m_taluk_name_options;

        break;

    case 'm_get_hostel_id':

        $m_taluk_id = $_POST['m_taluk_id'];

        $m_gender_id = $_POST['m_gender_id'];

        $m_hostel_type_id = $_POST['m_hostel_type_id'];

        $m_hostel_name_options = hostel_gender_type('', $m_taluk_id, $m_gender_id, $m_hostel_type_id);

        $m_hostel_name_options = select_option_host($m_hostel_name_options, "Select Hostel");

        echo $m_hostel_name_options;

        break;

    case 'transfer_student':

        $std_id = $_POST['std_id'];
        $std_reg_no = $_POST['std_reg_no'];
        $std_name = $_POST['std_name'];
        $from_district = $_POST['from_district'];
        $from_taluk = $_POST['from_taluk'];
        $from_hostel = $_POST['from_hostel'];
        $to_district = $_POST['to_district'];
        $to_taluk = $_POST['to_taluk'];
        $to_hostel = $_POST['to_hostel'];

        // Check if already transferred
        $table_check = [
            "student_transfer",
            ["COUNT(unique_id) AS count"]
        ];
        $where_check = 'is_delete = 0 AND std_id = "' . $std_id . '"';

        $check_action = $pdo->select($table_check, $where_check);
        $check_data = $check_action->data;

        if ($check_data[0]["count"] > 0) {
            echo json_encode([
                "status" => "already",
                "message" => "Student already transferred"
            ]);
            return;
        }

        // Prepare columns for insert
        $columns = [
            "std_id" => $std_id,
            "std_reg_no" => $std_reg_no,
            "std_name" => $std_name,
            "from_district" => $from_district,
            "from_taluk" => $from_taluk,
            "from_hostel" => $from_hostel,
            "to_district" => $to_district,
            "to_taluk" => $to_taluk,
            "to_hostel" => $to_hostel,
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix)
        ];

        $insert_action = $pdo->insert("student_transfer", $columns);

        if ($insert_action) {

            $update_columns = ["transfer_status" => 1];
            $update_where = 'std_reg_no = "' . $std_reg_no . '"';
            $update_action = $pdo->update("renewal", $update_columns, $update_where);

            echo json_encode([
                "status" => "success",
                "message" => "Student transferred successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Insert failed"
            ]);
        }

        break;


    case 'm_transfer_student':

        $m_std_id = $_POST['m_std_id'];
        $m_std_reg_no = $_POST['m_std_reg_no'];
        $m_std_name = $_POST['m_std_name'];
        $m_from_district = $_POST['m_from_district'];
        $m_from_taluk = $_POST['m_from_taluk'];
        $m_from_hostel = $_POST['m_from_hostel'];
        $m_to_district = $_POST['m_to_district'];
        $m_to_taluk = $_POST['m_to_taluk'];
        $m_to_hostel = $_POST['m_to_hostel'];

        // Check if already transferred
        $table_check = [
            "student_transfer",
            ["COUNT(unique_id) AS count"]
        ];
        $where_check = 'is_delete = 0 AND std_id = "' . $m_std_id . '"';

        $check_action = $pdo->select($table_check, $where_check);
        $check_data = $check_action->data;

        if ($check_data[0]["count"] > 0) {
            echo json_encode([
                "status" => "already",
                "message" => "Student already transferred"
            ]);
            return;
        }

        // Prepare columns for insert
        $columns = [
            "std_id" => $m_std_id,
            "std_reg_no" => $m_std_reg_no,
            "std_name" => $m_std_name,
            "from_district" => $m_from_district,
            "from_taluk" => $m_from_taluk,
            "from_hostel" => $m_from_hostel,
            "to_district" => $m_to_district,
            "to_taluk" => $m_to_taluk,
            "to_hostel" => $m_to_hostel,
            "entry_date" => date('Y-m-d'),
            "unique_id" => unique_id($prefix)
        ];

        $insert_action = $pdo->insert("student_transfer", $columns);

        if ($insert_action) {

            $update_columns = ["transfer_status" => 1];
            $update_where = 'std_reg_no = "' . $m_std_reg_no . '"';
            $update_action = $pdo->update("renewal", $update_columns, $update_where);

            echo json_encode([
                "status" => "success",
                "message" => "Student transferred successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Insert failed"
            ]);
        }

        break;

    case 'exit_student':

        $stdRegNo = $_POST['stdRegNo'];
        $reason = $_POST['reason'];

        // Prepare columns for insert
        $columns = [
            "exit_status" => "1",
            "exit_reason" => $reason
        ];

        $where = 'std_reg_no = "' . $stdRegNo . '"';

        $update_action = $pdo->update("renewal", $columns, $where);

        if ($update_action) {
            echo json_encode([
                "status" => "success",
                "message" => "Student exited successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Exit failed"
            ]);
        }

        break;


    case 'm_exit_student':

        $m_stdRegNo = $_POST['m_stdRegNo'];
        $reason = $_POST['reason'];

        // Prepare columns for insert
        $columns = [
            "exit_status" => "1",
            "exit_reason" => $reason
        ];

        $where = 'std_reg_no = "' . $m_stdRegNo . '"';

        $update_action = $pdo->update("renewal", $columns, $where);

        if ($update_action) {
            echo json_encode([
                "status" => "success",
                "message" => "Student exited successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Exit failed"
            ]);
        }

        break;


    default:

        break;
}
