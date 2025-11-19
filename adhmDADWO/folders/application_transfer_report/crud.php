<?php
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
        $length = $_POST['length'] ?? 10;
        $start = $_POST['start'] ?? 0;
        $draw = $_POST['draw'] ?? 1;
        $limit = $length;

        $district_id = $_SESSION["district_id"] ?? '';
        $data = [];

        // 1. Set serial number initializer
        $mysqli->query("SET @a := $start");

        // 2. Build query
        $columns = [
            "@a:=@a+1 AS s_no",
            "entry_date",
            "(SELECT district_name FROM district_name WHERE unique_id = hostel_district_1) AS hostel_district_1",
            "(SELECT hostel_id FROM hostel_name WHERE unique_id = hostel_1) AS hostel_1",
            "std_app_no",
            "std_name",
            "'' as toDistrict",
            "ap_transfer_hostel"
        ];

        $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
        FROM $table
        WHERE is_delete = 0 AND ap_transfer_status = 1 AND hostel_district_1 = ?
        LIMIT ?, ?";

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

        $stmt->bind_param("sii", $district_id, $start, $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result && $result->num_rows > 0) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as &$value) {
                $ToHostel = getToHostel($value['ap_transfer_hostel'], $mysqli);
                $ToDistrict = getToDistrict($value['ap_transfer_hostel'], $mysqli);

                $value['toDistrict'] = $ToDistrict;
                $value['ap_transfer_hostel'] = $ToHostel;

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
                "error" => "No data found or error in query."
            ]);
        }
        break;

    default:

        break;
}


function getToHostel($stdAppNo, $conn)
{
    $toHostel = '-';

    // Now get the hostel_id from hostel_name
    $stmt2 = mysqli_prepare($conn, "SELECT hostel_id FROM hostel_name WHERE unique_id = ? LIMIT 1");
    if ($stmt2) {
        mysqli_stmt_bind_param($stmt2, "s", $stdAppNo);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_bind_result($stmt2, $hostelId);
        if (mysqli_stmt_fetch($stmt2)) {
            $toHostel = $hostelId;
        }
        mysqli_stmt_close($stmt2);
    }

    return $toHostel;
}

function getToDistrict($stdAppNo, $conn)
{
    $toDistrict = '-';

    // First, get the latest hostel_district_1
    $stmt = mysqli_prepare($conn, "SELECT district_name FROM hostel_name WHERE is_delete = 0 AND unique_id = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $stdAppNo);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $districtUnId);
        if (mysqli_stmt_fetch($stmt) && $districtUnId) {
            mysqli_stmt_close($stmt);

            // Now get the district_name from district_name table
            $stmt2 = mysqli_prepare($conn, "SELECT district_name FROM district_name WHERE unique_id = ? LIMIT 1");
            if ($stmt2) {
                mysqli_stmt_bind_param($stmt2, "s", $districtUnId);
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_bind_result($stmt2, $districtName);
                if (mysqli_stmt_fetch($stmt2)) {
                    $toDistrict = $districtName;
                }
                mysqli_stmt_close($stmt2);
            }
        } else {
            mysqli_stmt_close($stmt);
        }
    }

    return $toDistrict;
}
