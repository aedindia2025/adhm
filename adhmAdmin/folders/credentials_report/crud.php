<?php
// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "staff_registration";

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
        $designation = $_POST["designation"];

        $data = [];

        $columns = [
            "'' as s_no",
            "(SELECT district_name FROM district_name WHERE unique_id = $table.district_office) AS district_name",
            "(SELECT taluk_name FROM taluk_creation WHERE unique_id = $table.taluk_office) AS taluk_name",
            "(SELECT hostel_id FROM hostel_name WHERE unique_id = $table.hostel_name) AS hostel_id",
            "(SELECT hostel_name FROM hostel_name WHERE unique_id = $table.hostel_name) AS hostel_name",
            "user_name",
            "password", // This is column index 5 (0-indexed)
            "unique_id" // This is column index 6 (View Password)
        ];
        $table_details = implode(", ", $columns);

        $where = "is_delete = 0";
        $where .= " AND designation = '65f3191aa725518258'";

        if (!empty($search_value)) {
            $search_value = $mysqli->real_escape_string($search_value);
            $where .= " AND (
                (SELECT district_name FROM district_name WHERE unique_id = $table.district_office) LIKE '%$search_value%' OR
                (SELECT taluk_name FROM taluk_creation WHERE unique_id = $table.taluk_office) LIKE '%$search_value%' OR
                (SELECT hostel_name FROM hostel_name WHERE unique_id = $table.hostel_name) LIKE '%$search_value%' OR
                user_name LIKE '%$search_value%' OR
                password LIKE '%$search_value%'
            )";
        }

        if ($district_name != '')
            $where .= " AND district_office = '$district_name'";
        if ($taluk_name != '')
            $where .= " AND taluk_office = '$taluk_name'";
        if ($hostel_name != '')
            $where .= " AND hostel_name = '$hostel_name'";

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

        $stmt->execute();
        $result = $stmt->get_result();

        $sno = $start + 1;
        while ($value = $result->fetch_assoc()) {

            $original_password = $value['password']; // Get the password from DB
            $masked_password = hash_password($original_password);

            $value['s_no'] = $sno++;

            // 1. Prepare the password column (index 5) with a wrapper span
            // Store both the original and masked values as data attributes for JS to use.
            $password_cell_content = '<span class="password-value-display is-masked" 
                                     data-original-value="' . htmlspecialchars($original_password) . '" 
                                     data-masked-value="' . htmlspecialchars($masked_password) . '">' . $masked_password . '</span>';
            $value['password'] = $password_cell_content;

            // 2. Create the toggle button with a new, descriptive class
            $btn_view = '<a href="#" class="password-toggle-btn" title="Toggle Password Visibility">
            <i class="fa fa-eye" style="font-size: 0.98em;"></i>
        </a>';

            // 3. Assign the button to the last column (index 6, View Password)
            $value['unique_id'] = $btn_view;

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

    case 'dadwo_datatable':

        $length = intval($_POST['length']);
        $start = intval($_POST['start']);
        $draw = intval($_POST['draw']);

        $district_name = $_POST["district_name"];
        $taluk_name = $_POST["taluk_name"];
        $hostel_name = $_POST["hostel_name"];
        $designation = $_POST["designation"];

        $data = [];

        $columns = [
            "'' as s_no",
            "(SELECT district_name FROM district_name WHERE unique_id = $table.district_office) AS district_name",
            "user_name",
            "password", // This is column index 5 (0-indexed)
            "unique_id" // This is column index 6 (View Password)
        ];
        $table_details = implode(", ", $columns);

        $where = "is_delete = 0";
        $where .= " AND designation = '65f31975f0ce678724'";

        if ($district_name != '')
            $where .= " AND district_office = '$district_name'";
        if ($taluk_name != '')
            $where .= " AND taluk_office = '$taluk_name'";
        if ($hostel_name != '')
            $where .= " AND hostel_name = '$hostel_name'";

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

        $stmt->execute();
        $result = $stmt->get_result();

        $sno = $start + 1;
        while ($value = $result->fetch_assoc()) {

            $original_password = $value['password']; // Get the password from DB
            $masked_password = hash_password($original_password);

            $value['s_no'] = $sno++;

            // 1. Prepare the password column (index 5) with a wrapper span
            // Store both the original and masked values as data attributes for JS to use.
            $password_cell_content = '<span class="password-value-display is-masked" 
                                     data-original-value="' . htmlspecialchars($original_password) . '" 
                                     data-masked-value="' . htmlspecialchars($masked_password) . '">' . $masked_password . '</span>';
            $value['password'] = $password_cell_content;

            // 2. Create the toggle button with a new, descriptive class
            $btn_view = '<a href="#" class="password-toggle-btn-dadwo" title="Toggle Password Visibility">
            <i class="fa fa-eye" style="font-size: 0.98em;"></i>
        </a>';

            // 3. Assign the button to the last column (index 6, View Password)
            $value['unique_id'] = $btn_view;

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