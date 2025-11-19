<?php
ini_set('display_errors', 0);
error_reporting(0);
// Get folder Name From Currnent Url 
$folder_name        = explode("/", $_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table             = "std_app_s";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$fund_name          = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose
function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {
    case 'datatable':
        $length     = $_POST['length'];
        $start      = $_POST['start'];
        $draw       = $_POST['draw'];
        $limit      = $length;

        $data = [];

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "entry_date",
            "(SELECT district_name FROM district_name WHERE unique_id = s.hostel_district_1) AS hostel_district_1",
            "(SELECT hostel_id FROM hostel_name WHERE unique_id = s.hostel_1) AS hostel_1",
            "s.std_app_no",
            "s.std_name",
            "s.unique_id"
        ];

        // Join the batch_creation table using alias 'b'
        $table_details = "$table AS s 
            LEFT JOIN batch_creation AS b 
            ON b.s1_unique_id = s.unique_id 
            AND b.reason = 'Sanction count exceeded - student transfer initiated',
            (SELECT @a:= ?) AS a";

        $where = '(s.is_delete = 0 AND s.status = 2 AND s.ap_transfer_status = 0 AND s.hostel_district_1 = "' . $_SESSION["district_id"] . '")';

        $bind_params = "i";
        $bind_values = [$start];

        $sql_function = "SQL_CALC_FOUND_ROWS";
        $order_by = ""; // Optional: add order by if needed

        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";

        if ($limit !== "") {
            $sql .= " LIMIT ?, ?";
            $bind_params .= "ii";
            $bind_values[] = $start;
            $bind_values[] = $limit;
        }

        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
            ];
            echo json_encode($json_array);
            break;
        }

        // Bind parameters dynamically
        $bind_params_arr = array_merge([$bind_params], ...array_map(function ($v) {
            return [$v];
        }, $bind_values));
        call_user_func_array([$stmt, 'bind_param'], $bind_params_arr);

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {
                $btn_update         = btn_update($folder_name, $value['unique_id']);
                $value['unique_id'] = $btn_update . $btn_delete;
                $data[]             = array_values($value);
            }

            $json_array = [
                "draw"              => intval($draw),
                "recordsTotal"      => intval($total_records),
                "recordsFiltered"   => intval($total_records),
                "data"              => $data,
            ];
        }

        echo json_encode($json_array);
        break;


    case 'transfer_datatable':
        $length     = $_POST['length'] ?? 10;
        $start      = $_POST['start'] ?? 0;
        $draw       = $_POST['draw'] ?? 1;
        $limit      = $length;
        $hostel_district_final = $_POST['hostel_district_final'];
        $s1_unique_id = $_POST['s1_unique_id'];
        $gender = $_POST['gender'];
        $student_type = $_POST['student_type'];
        $district_id = $_SESSION['district_id'];
        $hostel_id = $_POST['hostel_id'];

        $ids = array_map('trim', explode(',', $hostel_district_final));
        $quotedIds = array_map(function ($id) {
            return "'" . $id . "'";
        }, $ids);
        $idList = implode(',', $quotedIds);

        $data = [];

        // Columns to select
        $columns = [
            "@a:=@a+1 AS s_no",
            "hostel_id",
            "hostel_name",
            "address",
            "(
                SELECT COUNT(*) 
                FROM std_app_s 
                WHERE is_delete = 0 
                    AND academic_year = '664dc72a74d5299717' 
                    AND ap_transfer_hostel = hostel_name.unique_id
            ) AS transfer_count",
            "(
                SELECT COUNT(*) 
                FROM std_app_s 
                WHERE is_delete = 0 
                    AND (submit_status = 1 OR batch_no IS NOT NULL)
                    AND academic_year = '664dc72a74d5299717' 
                    AND hostel_1 = hostel_name.unique_id
            ) AS applied_count",
            "sanctioned_strength",
            "unique_id",
            "(SELECT COUNT(*) FROM std_reg_s 
                WHERE is_delete = 0 
                AND academic_year = '664dc72a74d5299717' 
                AND hostel_1 = hostel_name.unique_id) AS taken_count",
        ];

        $table_hostel = "hostel_name";

        // Add conditional hostel_type logic
        if ($student_type === '65f00a259436412348') {
            $hostel_type_condition = "(hostel_type = '65f00a259436412348' OR hybrid_hostel = 'Yes')";
        } else {
            $hostel_type_condition = "(hostel_type != '65f00a259436412348' OR hybrid_hostel = 'Yes')";
        }

        // WHERE clause
        $where = "is_delete = 0 AND is_active = 1 
          AND district_name IN ($idList) 
          AND gender_type = '$gender' 
          AND district_name = '$district_id' 
          AND $hostel_type_condition 
          AND hostel_id != '$hostel_id'";

        // Initialize session variable for row number
        $mysqli->query("SET @a := 0");

        $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM $table_hostel WHERE $where LIMIT ?, ?";

        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            echo json_encode([
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
            ]);
            break;
        }

        // Bind parameters (start and limit)
        $stmt->bind_param("ii", $start, $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        // Get total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'] ?? 0;

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {

                $availableSeats = $value['sanctioned_strength'] - $value['taken_count'];

                $formattedSeats = $value['sanctioned_strength'] . "/" . $availableSeats;
                $value['sanctioned_strength'] = $formattedSeats;

                $transferBtn = '<button class="renewBtnn" id="renewbtnn" type="button" '
                    . 'data-s1_unique_id="' . htmlspecialchars($s1_unique_id, ENT_QUOTES, 'UTF-8') . '" '
                    . 'data-hostel_un_id="' . htmlspecialchars($value['unique_id'], ENT_QUOTES, 'UTF-8') . '" '
                    . 'data-hostel_id="' . htmlspecialchars($value['hostel_id'], ENT_QUOTES, 'UTF-8') . '" '
                    . 'onclick="transferStudent(this)">Transfer</button>';

                $value['unique_id'] = $transferBtn;
                $data[] = array_values($value);
            }

            echo json_encode([
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data
            ]);
        } else {
            echo json_encode([
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => "No result found"
            ]);
        }

        break;

    case 'get_district_name_by_code':
        $districtCode = $_POST['district_code'] ?? '';
        $data = $pdo->select(["umis_district", ["DistrictName as district_name"]], ["Id" => $districtCode]);
        echo json_encode(["status" => $data->status, "data" => $data->data[0] ?? []]);
        break;

    case 'get_group_district':
        $districtName = $_POST['district_name'] ?? '';
        $data = $pdo->select(["district_name_test", ["group_district", "group_district_unique_id", "unique_id"]], ["district_name" => $districtName]);
        echo json_encode(["status" => $data->status, "data" => $data->data[0] ?? []]);
        break;

    case 'get_district_name_by_unique_id':
        $uniqueId = $_POST['unique_id'] ?? '';
        $data = $pdo->select(["district_name_test", ["group_district", "group_district_unique_id", "unique_id"]], ["unique_id" => $uniqueId]);
        echo json_encode(["status" => $data->status, "data" => $data->data[0] ?? []]);
        break;

    case 'application_transfer':
        $s1_unique_id = $_POST['s1_unique_id'];
        $common_unique_id = unique_id($prefix);
        $common_entry_date = date("Y-m-d");
        $hostel_un_id = $_POST['hostel_unique_id'];

        // Helper function to duplicate related tables
        function duplicateData($pdo, $table_taken, $s1_unique_id, $new_unique_id, $entry_date)
        {
            $res = $pdo->select([$table_taken, ["*"]], 's1_unique_id = "' . $s1_unique_id . '"');
            if (!$res->status || empty($res->data)) return (object)["status" => true];

            foreach ($res->data as &$row) {
                unset($row['id'], $row['created'], $row['updated']);
                $row['entry_date'] = $entry_date;
                $row['s1_unique_id'] = $new_unique_id;
            }

            foreach ($res->data as $row) {
                $check = $pdo->select([$table_taken, ["id"]], 's1_unique_id = "' . $new_unique_id . '"');
                if ($check->status && empty($check->data)) {
                    $insert = $pdo->insert($table_taken, $row);
                    if (!$insert->status) return $insert;
                }
            }

            return (object)["status" => true];
        }

        // Step 1: Duplicate std_app_s
        $res_main = $pdo->select(["std_app_s", ["*"]], 'unique_id = "' . $s1_unique_id . '"');
        if (!$res_main->status || empty($res_main->data)) {
            echo json_encode(["status" => false, "msg" => "no_data_found", "error" => $res_main->error]);
            exit;
        }

        $row = $res_main->data[0];

        unset($row['id'], $row['batch_no'], $row['batch_cr_date'], $row['status_upd_date'], $row['status'], $row['submit_status'], $row['created'], $row['updated'], $row['ap_transfer_hostel']);
        $row['unique_id'] = $common_unique_id;
        $row['batch_no'] = null;
        $row['batch_cr_date'] = null;
        $row['status_upd_date'] = null;
        $row['status'] = 0;
        $row['submit_status'] = 1;
        $row['entry_date'] = $common_entry_date;
        $row['ap_transfer_hostel'] = NULL;

        $insert_main = $pdo->insert("std_app_s", $row);
        if (!$insert_main->status) {
            echo json_encode(["status" => false, "msg" => "error", "error" => $insert_main->error]);
            exit;
        }

        // Step 2: Duplicate child tables
        $table_records = ["std_app_s2", "std_app_emis_s3", "std_app_umis_s4", "std_app_s5", "std_app_s6", "std_app_s7"];
        foreach ($table_records as $table_taken) {
            $res_copy = duplicateData($pdo, $table_taken, $s1_unique_id, $common_unique_id, $common_entry_date);
            if (!$res_copy->status) {
                echo json_encode([
                    "status" => false,
                    "msg" => "error",
                    "error" => $res_copy->error,
                    "failing_table" => $table_taken
                ]);
                exit;
            }
        }

        // Select district and taluk from hostel_name
        $table_hostel = [
            "hostel_name",
            ["district_name", "taluk_name"]
        ];
        $where_hostel = 'is_delete = 0 AND unique_id = "' . $hostel_un_id . '"';

        $hostel_info = $pdo->select($table_hostel, $where_hostel);

        if ($hostel_info->status && !empty($hostel_info->data)) {
            $district_name = $hostel_info->data[0]["district_name"];
            $taluk_name = $hostel_info->data[0]["taluk_name"];

            // Update std_app_s
            $update_std_s_data = [
                "hostel_1" => $hostel_un_id,
                "hostel_district_1" => $district_name,
                "hostel_taluk_1" => $taluk_name
            ];
            $update_std_s_where = 'unique_id = "' . $common_unique_id . '"';

            $update_std_app_s = $pdo->update("std_app_s", $update_std_s_data, $update_std_s_where);

            if (!$update_std_app_s) {
                echo json_encode(["status" => false, "msg" => "error", "error" => "Failed to update std_app_s"]);
                exit;
            }

            // Update std_app_s7
            $update_std_s7_data = [
                "hostel_name" => $hostel_un_id,
                "hostel_district" => $district_name,
                "hostel_taluk" => $taluk_name,
                "status" => NULL,
                "reason" => NULL
            ];
            $update_std_s7_where = 's1_unique_id = "' . $common_unique_id . '" AND priority = 1';

            $update_std_app_s7 = $pdo->update("std_app_s7", $update_std_s7_data, $update_std_s7_where);

            if (!$update_std_app_s7) {
                echo json_encode(["status" => false, "msg" => "error", "error" => "Failed to update std_app_s7"]);
                exit;
            }
        }

        // Update ap_transfer_status = 1 in the original std_app_s record
        $update_original_std_app_s = $pdo->update("std_app_s", ["ap_transfer_status" => 1, "ap_transfer_hostel" => $hostel_un_id], 'unique_id = "' . $s1_unique_id . '"');

        if (!$update_original_std_app_s) {
            echo json_encode(["status" => false, "msg" => "error", "error" => "Failed to update ap_transfer_status in original std_app_s"]);
            exit;
        }

        echo json_encode(["status" => true, "msg" => "auto_renewal_success", "new_unique_id" => $common_unique_id]);
        break;

    case 'check_hostel_capacity':
        $hostel_un_id = $_POST['hostel_unique_id'] ?? '';

        if (!$hostel_un_id) {
            echo json_encode(['status' => false, 'msg' => 'Hostel ID is missing']);
            break;
        }

        // Fetch sanctioned strength
        $hostelData = $pdo->select(
            ["hostel_name", ["sanctioned_strength"]],
            ["is_delete" => 0, "unique_id" => $hostel_un_id]
        );

        // Fetch registered count
        $registeredData = $pdo->select(
            ["std_reg_s", ["COUNT(*) AS count"]],
            ["is_delete" => 0, "hostel_1" => $hostel_un_id]
        );

        if ($hostelData->status && $registeredData->status) {
            $sanctioned_strength = $hostelData->data[0]['sanctioned_strength'] ?? 0;
            $registered_count = $registeredData->data[0]['count'] ?? 0;

            echo json_encode([
                "status" => true,
                "registered_count" => intval($registered_count),
                "sanctioned_strength" => intval($sanctioned_strength)
            ]);
        } else {
            echo json_encode(["status" => false, "msg" => "Failed to fetch data"]);
        }
        break;


    default:

        break;
}

function getStudentCountByHostel($hostelId)
{
    global $pdo; // use global PDO connection

    $query = "SELECT COUNT(*) as count FROM std_reg_s WHERE is_delete = 0 AND hostel_1 = :hostel_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':hostel_id', $hostelId);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? intval($row['count']) : 0;
}
