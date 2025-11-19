<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "renewal";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST["action"];
// print_r($action);die();

$feedback_type = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

switch ($action) {

    case 'datatable':

        $length = intval($_POST['length']);
        $start = intval($_POST['start']);
        $draw = intval($_POST['draw']);
        $search_value = $_POST['search']['value'] ?? '';

        $district_name = $_POST["district_name"];
        $taluk_name = $_POST["taluk_name"];
        $hostel_name = $_POST["hostel_name"];

        $data = [];

        $columns = [
            "'' as s_no",
            "(SELECT district_name FROM district_name WHERE unique_id = $table.hostel_district) AS district_name",
            "(SELECT taluk_name FROM hostel_name WHERE unique_id = $table.hostel_id) AS taluk_name",
            "(SELECT hostel_name FROM hostel_name WHERE unique_id = $table.hostel_id) AS hostel_name",
            "std_reg_no",
            "std_name",
            "renewal_opt"
        ];
        $table_details = implode(", ", $columns);

        $where = "1=1";

        if (!empty($search_value)) {
            $search_value = $mysqli->real_escape_string($search_value);
            $where .= " AND (
                (SELECT district_name FROM district_name WHERE unique_id = $table.hostel_district) LIKE '%$search_value%' OR
                (SELECT hostel_name FROM hostel_name WHERE unique_id = $table.hostel_id) LIKE '%$search_value%' OR
                std_reg_no LIKE '%$search_value%' OR
                std_name LIKE '%$search_value%'
            )";
            // (SELECT taluk_name FROM hostel_name WHERE unique_id = $table.hostel_id) LIKE '%$search_value%' OR
        }

        if ($district_name != '')
            $where .= " AND hostel_district = '$district_name'";
        if ($hostel_name != '')
            $where .= " AND hostel_id = '$hostel_name'";
        // Total count query
        $count_sql = "SELECT COUNT(*) as total FROM (SELECT 1 FROM $table WHERE $where) AS temp";

        $count_result = $mysqli->query($count_sql);

        $total_records = $count_result->fetch_assoc()['total'];

        // Main query
        $sql = "SELECT $table_details FROM $table WHERE $where";
        if ($length != -1) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($length != -1) {
            $stmt->bind_param("ii", $start, $length);
        }
        // echo '1 ';
        // echo $sql;
        // echo '2 ';

        $stmt->execute();
        $result = $stmt->get_result();

        $sno = $start + 1;
        while ($value = $result->fetch_assoc()) {

            $value['s_no'] = $sno++;

            // $value['taluk_name'] = "(SELECT taluk_name FROM taluk_creation WHERE unique_id = '" . $value['taluk_name'] . "' AS taluk_name";

            $result = $mysqli->query("SELECT taluk_name FROM taluk_creation WHERE unique_id = '" . $value['taluk_name'] . "' LIMIT 1");
            $row = $result->fetch_assoc();
            $value['taluk_name'] = $row ? $row['taluk_name'] : '';

            // Push the row data
            $data[] = array_values($value);
        }


        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data
        ];
        
        echo json_encode($json_array);

        $stmt->close();
        // $mysqli->close(); // Keep this closed outside the case, or ensure subsequent code runs if closed here

        break;

    case 'get_taluk':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel':

        $taluk_name = $_POST['taluk_name'];

        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;

    default:

        break;
}

function hash_password(string $password): string
{
    // Ensure we treat the string as UTF-8 and use mbstring functions
    $len = mb_strlen($password, 'UTF-8');

    if ($len <= 1) {
        // Nothing to mask
        return $password;
    }
    if ($len == 2) {
        // Show both characters (no room for masking)
        return $password;
    }

    // Get first and last character
    $first = mb_substr($password, 0, 1, 'UTF-8');
    $last = mb_substr($password, -1, 1, 'UTF-8');

    // Middle length
    $middleLen = $len - 2;

    // Build mask of '*' characters of correct length
    $mask = str_repeat('*', $middleLen);

    return $first . $mask . $last;
}
?>