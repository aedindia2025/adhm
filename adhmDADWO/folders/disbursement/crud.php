<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "disbursement_creation";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';


// // Variables Declaration
$action             = $_POST['action'];

$feedback_type      = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {
    case 'createupdate':
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $vali_dadwo_letter_no = filter_input(INPUT_POST, 'dadwo_letter_no', FILTER_SANITIZE_STRING);
        $vali_dadwo_letter_date = filter_input(INPUT_POST, 'dadwo_letter_date', FILTER_SANITIZE_STRING);
        $vali_dadwo_login_user_id = filter_input(INPUT_POST, 'dadwo_login_user_id', FILTER_SANITIZE_STRING);

        if (!$vali_dadwo_letter_no || !$vali_dadwo_letter_date || !$vali_dadwo_login_user_id) {
            $msg = "form_alert";
        } else {
            $dadwo_letter_no = sanitizeInput($_POST["dadwo_letter_no"]);
            $dadwo_letter_date = $_POST["dadwo_letter_date"];
            $unique_id = $_POST["unique_id"];
            $dadwo_login_user_id = sanitizeInput($_POST["dadwo_login_user_id"]);

            $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls');
            $extension = pathinfo($_FILES['test_file']['name'], PATHINFO_EXTENSION);

            if (!in_array($extension, $allowedExts)) {
                die('File type not allowed.');
            }

            $file_exp = explode(".", $_FILES["test_file"]['name']);
            $tem_name = random_strings(25) . "." . $file_exp[1];
            move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../uploads/disbursement/' . $tem_name);
            $file_names = $tem_name;
            $file_org_names = $_FILES["test_file"]['name'];

            $update_where = "";
            $columns = [
                "dadwo_letter_no" => $dadwo_letter_no,
                "dadwo_letter_date" => $dadwo_letter_date,
                "dadwo_off_name" => $dadwo_login_user_id,
                "dadwo_attach_file" => $file_names,
                "dadwo_attach_org_name" => $file_org_names,
            ];

            // Update or Insert logic
            if ($unique_id) {
                $sql = "UPDATE $table SET dadwo_letter_no=?, dadwo_letter_date=?, dadwo_off_name=?, dadwo_attach_file=?, dadwo_attach_org_name=? WHERE unique_id=?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssssss", $dadwo_letter_no, $dadwo_letter_date, $dadwo_login_user_id, $file_names, $file_org_names, $unique_id);
            } else {
                $sql = "INSERT INTO $table (dadwo_letter_no, dadwo_letter_date, dadwo_off_name, dadwo_attach_file, dadwo_attach_org_name) VALUES (?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssss", $dadwo_letter_no, $dadwo_letter_date, $dadwo_login_user_id, $file_names, $file_org_names);
            }

            // Execute SQL statement
            if ($stmt->execute()) {
                $status = "success";
                $data = $stmt->affected_rows > 0 ? "Data updated successfully" : "No changes made";
                $msg = $unique_id ? "update" : "create";
            } else {
                $status = "error";
                $error = $stmt->error;
                $msg = "error";
            }

            $stmt->close();
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);
        break;

        case 'datatable':
            // DataTable Variables
            $length = isset($_POST['length']) ? $_POST['length'] : 10;
            $start = isset($_POST['start']) ? $_POST['start'] : 0;
            $draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
            $limit = $length;
        
            $data = [];
        
            if ($length == '-1') {
                $limit = "";
            }
        
            // Query Variables
            $json_array = "";
            $columns = [
               "@a:=@a+1 s_no",
            '(SELECT disbursement_type FROM disbursement_type AS dis WHERE dis.unique_id = ' . $table . '.disbursement_type ) AS disbursement_type',
            '(SELECT amc_year FROM academic_year_creation AS acc_year WHERE acc_year.unique_id = ' . $table . '.academic_year ) AS academic_year',
            "month",
            "connection_no",
            "letter_no",
            "applied_date",
            "tah_letter_no",
            // "tah_letter_date",
            // "is_active",
            "unique_id"
            ];
            $sql_columns = implode(", ", $columns);
            $table_with_counter = $table . ", (SELECT @a:=?) AS a";
            $where = "is_delete = ? AND district_unique_id = ? and tah_letter_no != ?";
            $is_delete = 0;
            $tah_no = '';
        
            // Initialize total records variable
            $total_records = total_records();
        
            // Prepare SQL query
            $sql_query = "SELECT SQL_CALC_FOUND_ROWS {$sql_columns} FROM {$table_with_counter} WHERE {$where}";
        
            if ($limit !== "") {
                $sql_query .= " LIMIT ?, ?";
            }
        
            // Execute query with parameterized statements
            $stmt = $mysqli->prepare($sql_query);
            if ($stmt) {
                // Bind parameters
                if ($limit !== "") {
                   
                    $stmt->bind_param("iiisis", $start, $is_delete, $_SESSION['district_id'], $tah_no,$start, $limit);
                } else {
                    $stmt->bind_param("iiss", $start, $is_delete, $_SESSION['district_id'],$tah_no);
                }
     
                // Execute the query
                $stmt->execute();
               
                // Fetch the result
                $result = $stmt->get_result();
               
        
                if ($result) {
                    while ($value = $result->fetch_assoc()) {
                        // Handle disbursement file
                        $btn_update         = btn_update($folder_name,$value['unique_id']);
                $btn_delete         = btn_delete($folder_name,$value['unique_id']);

                if ( $value['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update         = "";
                    $btn_delete         = "";
                } 

                $value['unique_id'] = $btn_update.$btn_delete;
                $data[]             = array_values($value);
                    }
        
                    // Fetch the total filtered records
                    $stmt_filtered = $mysqli->prepare("SELECT FOUND_ROWS()");
                    if ($stmt_filtered) {
                        $stmt_filtered->execute();
                        $stmt_filtered->bind_result($total_filtered);
                        $stmt_filtered->fetch();
                        $stmt_filtered->close();
                    } else {
                        $total_filtered = $total_records;
                    }
        
                    // Prepare JSON response
                    $json_array = [
                        "draw" => intval($draw),
                        "recordsTotal" => intval($total_filtered),
                        "recordsFiltered" => intval($total_filtered),
                        "data" => $data,
                    ];
                }
        
                $stmt->close();
                $mysqli->close();
            }
        
            // Output JSON response
            echo json_encode($json_array);
            break;
        
        
    
    
        case 'delete':

            $token = $_POST['csrf_token'];

            if (!validateCSRFToken($token)) {
                die('CSRF validation failed.');
            }
            
            $unique_id = $_POST['unique_id'];
    
            // Prepare SQL statement
            $sql = "UPDATE $table SET is_delete = 1 WHERE unique_id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $unique_id);
    
            // Execute SQL statement
            if ($stmt->execute()) {
                $status = "success";
                $msg = "success_delete";
            } else {
                $status = "error";
                $msg = "error";
            }
    
            $stmt->close();
    
            $json_array = [
                "status" => $status,
                "msg" => $msg,
            ];
    
            echo json_encode($json_array);
            break;

    default:
        
        break;
}
    
    
?>
