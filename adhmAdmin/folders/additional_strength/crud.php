<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'additional_strength';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

// $district_name      = "";
$is_active = '';
$unique_id = '';
$prefix = '';

$data = '';
$msg = '';
$error = '';
$status = '';
$test = ''; // For Developer Testing Purpose\




function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {

       case 'createupdate':
        $token = $_POST['csrf_token'];

        // CSRF token validation
        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }

        $table = 'additional_strength';

        // Sanitize and validate input data
        $vali_from_district_name = sanitizeInput($_POST['from_district_name']);
        $vali_from_taluk_name = sanitizeInput($_POST['from_taluk_name']);
        $vali_from_hostel_name = sanitizeInput($_POST['from_hostel_name']);
        $vali_from_hostel_strength = sanitizeInput($_POST['from_hostel_strength']);
        $vali_to_district_name = sanitizeInput($_POST['to_district_name']);
        $vali_to_taluk_name = sanitizeInput($_POST['to_taluk_name']);
        $vali_to_hostel_name = sanitizeInput($_POST['to_hostel_name']);
        $vali_to_hostel_strength = sanitizeInput($_POST['to_hostel_strength']);
        $transfer_count = sanitizeInput($_POST['transfer_count']);
        $unique_id = sanitizeInput($_POST['unique_id']);
        $remarks = sanitizeInput($_POST['remarks']);
        $is_active = sanitizeInput($_POST['is_active']);

        // Check if the unique_id is set for update or it is a new insert
        $is_update = !empty($unique_id);

        if ($is_update) {
            // Update query
            $sql = "UPDATE $table SET from_district_name = ?, from_taluk_name = ?, from_hostel_name = ?, from_hostel_strength = ?, to_district_name = ?, to_taluk_name = ?, to_hostel_name = ?, to_hostel_strength = ?, transfer_count = ?, remarks = ?, is_active = ? WHERE unique_id = ?";
        } else {
            // Insert query
            $sql = "INSERT INTO $table (from_district_name, from_taluk_name, from_hostel_name, from_hostel_strength, to_district_name, to_taluk_name, to_hostel_name, to_hostel_strength, transfer_count, remarks, is_active, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }

        // Prepare the statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQLi prepare() error: ' . $mysqli->error);
        }

        // Bind parameters
        if ($is_update) {
            $stmt->bind_param("ssssssssssss", $vali_from_district_name, $vali_from_taluk_name, $vali_from_hostel_name, $vali_from_hostel_strength, $vali_to_district_name, $vali_to_taluk_name, $vali_to_hostel_name, $vali_to_hostel_strength, $transfer_count, $remarks, $is_active, $unique_id);
        } else {
            $new_unique_id = unique_id($prefix); // Generate new unique_id for insert
            $stmt->bind_param("ssssssssssss", $vali_from_district_name, $vali_from_taluk_name, $vali_from_hostel_name, $vali_from_hostel_strength, $vali_to_district_name, $vali_to_taluk_name, $vali_to_hostel_name, $vali_to_hostel_strength, $transfer_count, $remarks, $is_active, $new_unique_id);
        }

	
	
        // Execute the statement
        $result = $stmt->execute();
        if ($result === false) {
            die('Execution failed: ' . $stmt->error);
        }

        // Determine message based on whether it's an update or insert
        $msg = $is_update ? 'update' : 'create';

        $additional_strength = get_additional_strength($vali_to_hostel_name);
        $transfer_strength = get_transfer_strength($vali_from_hostel_name);

if(empty($additional_strength)){
	$additional_strength = 0;
}
if(empty($transfer_strength)){
	$transfer_strength = 0;
}


        // Update additional strength in hostel_name table
        $sql_hostel = "UPDATE hostel_name SET additional_strength = ? WHERE unique_id = ?";
        $stmt_hostel = $mysqli->prepare($sql_hostel); // Initialize $stmt_hostel
        if ($stmt_hostel === false) {
            die('MySQLi prepare() error: ' . $mysqli->error);
        }

        $stmt_hostel->bind_param("is", $additional_strength, $vali_to_hostel_name); // Assuming transfer_count is an integer
        $result_hostel = $stmt_hostel->execute();
        if ($result_hostel === false) {
            die('Execution failed: ' . $stmt_hostel->error);
        }

        $sql_from_hostel = "UPDATE hostel_name SET transfer_strength = ? WHERE unique_id = ?";
        $stmt_from_hostel = $mysqli->prepare($sql_from_hostel); // Initialize $stmt_hostel
        if ($stmt_from_hostel === false) {
            die('MySQLi prepare() error: ' . $mysqli->error);
        }

        $stmt_from_hostel->bind_param("is", $transfer_strength, $vali_from_hostel_name); // Assuming transfer_count is an integer
        $result_from_hostel = $stmt_from_hostel->execute();
        if ($result_from_hostel === false) {
            die('Execution failed: ' . $stmt_hostel->error);
        }
       



        // Prepare JSON response
        $json_array = [
            'status' => 200, // Assuming success HTTP status
            'data' => [], // You can add data here if needed
            'error' => '', // No error in this context
            'msg' => $msg,
            'sql' => $sql, // For debugging, remove in production
        ];

        echo json_encode($json_array);

        // Close MySQLi statement
        $stmt->close();
        $stmt_hostel->close(); // Close $stmt_hostel
       
        break;


        case 'datatable':
            // DataTable Variables
            $length = $_POST['length'];
            $start = $_POST['start'];
            $draw = $_POST['draw'];
            $limit = $length == '-1' ? "" : $length;
    
            $district_name      = $_POST["district_name"];
    
            $data = [];
    
            // if ($length == '-1') {
            //     $limit = '';
            // }
    
            // Query Variables
            // $json_array = '';
            $columns = [
                '@a:=@a+1 s_no',
                'from_district_name',
                'from_taluk_name',
                '(SELECT hostel_name FROM hostel_name AS hostel_name WHERE hostel_name.unique_id = ' . $table . '.from_hostel_name ) AS from_hostel_name',
                'from_hostel_strength',
                "to_district_name",
                "to_taluk_name",
                "(SELECT hostel_name FROM hostel_name AS hostel_name WHERE hostel_name.unique_id = " . $table . ".to_hostel_name ) AS to_hostel_name",
                "to_hostel_strength",
                'transfer_count',
                'remarks',
                'is_active',
                'unique_id',
            ];
            $table_details = $table . " , (SELECT @a:= ?) AS a ";

            $where = 'is_delete = ?';
    
            $bind_params = "ii";
            $bind_values = [$start,'0'];

            if (!empty($_POST['district_name'])) {
                $where .= " AND from_district_name = ?";
                $bind_params .= "s";
                $bind_values[] = $_POST['district_name'];
            }

    
            $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM " . $table_details . " WHERE " . $where;

            if (!empty($limit)) {
                $sql .= " LIMIT ?, ?";
                $bind_params .= "ii"; // Add types for integer parameters
                $bind_values[] = intval($start);
                $bind_values[] = intval($length);
            }

            $stmt = $mysqli->prepare($sql);

            if ($stmt === false) {
                die('Error in preparing SQL statement: ' . $mysqli->error);
            }
    
            // Bind parameters if there are any
            if (!empty($bind_params)) {
                $stmt->bind_param($bind_params, ...$bind_values);
            }
    
            // Execute the statement
            $stmt->execute();
            $result = $stmt->get_result();
    
            // Fetch total records count using FOUND_ROWS()
            $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
            $total_records = $total_records_result->fetch_assoc()['total'];

            
            // $total_records = total_records();
            // print_r($result);
    
            if ($result) {
                $res_array = $result->fetch_all(MYSQLI_ASSOC);
    
                foreach ($res_array as $key => $value) {
                    $value['from_district_name'] = district_name($value['from_district_name'])[0]['district_name'];
                    $value['to_district_name'] = district_name($value['to_district_name'])[0]['district_name'];
    
                    $value['from_taluk_name'] = taluk_name($value['from_taluk_name'])[0]['taluk_name'];
                    $value['to_taluk_name'] = taluk_name($value['to_taluk_name'])[0]['taluk_name'];
    
                    $value['is_active'] = is_active_show($value['is_active']);
    
                    $btn_update = btn_update($folder_name, $value['unique_id']);
                    $btn_delete = btn_delete($folder_name, $value['unique_id']);
    
                    $value['unique_id'] = $btn_update . $btn_delete;
                    $data[] = array_values($value);
                }
    
                $json_array = [
                    'draw' => intval($draw),
                    'recordsTotal' => intval($total_records),
                    'recordsFiltered' => intval($total_records),
                    'data' => $data,
                    // 'testing' => $result->sql,
                ];
            } else {
                die('Query execution failed: ' . $mysqli->error);

            }
    
            echo json_encode($json_array);
            break;

    case 'from_district_name':
        $from_district_name = $_POST['from_district_name'];

        $district_name_options = taluk_name('', $from_district_name);

        $taluk_name_options = select_option($district_name_options, 'Select Taluk');

        echo $taluk_name_options;

        break;

    case 'to_district_name':

        $to_district_name = $_POST['to_district_name'];

        $to_district_name_options = taluk_name('', $to_district_name);

        $taluk_name_options = select_option($to_district_name_options, 'Select Taluk');

        echo $taluk_name_options;

        break;

    case 'get_hostel_by_taluk_name':
        $from_taluk_name = $_POST['from_taluk_name'];

        $hostel_name_options = hostel_name('', $from_taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, 'Select Hostel');

        echo $hostel_name_options;

        break;

    case 'get_hostel_by_taluk_name_1':
        $to_taluk_name = $_POST['to_taluk_name'];

        $hostel_name_options = hostel_name('', $to_taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, 'Select Hostel');

        echo $hostel_name_options;

        break;

    case 'delete':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }


        $unique_id = $_POST['unique_id'];

        // Assuming $pdo is your existing PDO object, replace it with $mysqli for MySQLi
        $columns = [
            'is_delete' => 1,
        ];

        // Build the SQL query
        // Replace with your actual table name
        $sql = "UPDATE " . $table . " SET is_delete = ? WHERE unique_id = ?";

        // Prepare the statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        $is_delete = 1; // Assuming is_delete is always set to 1 for deletion
        $stmt->bind_param('is', $is_delete, $unique_id);

        // Execute the statement
        $result = $stmt->execute();

        // $transfer_count = get_transfer_count($unique_id)[0]['transfer_count'];
        $vali_from_hostel_name = get_transfer_count($unique_id)[0]['from_hostel_name'];
        $vali_to_hostel_name = get_transfer_count($unique_id)[0]['to_hostel_name'];
       
        $additional_strength = get_additional_strength($vali_to_hostel_name);
        $transfer_strength = get_transfer_strength($vali_from_hostel_name);
if(empty($additional_strength)){
	$additional_strength = 0;
}
if(empty($transfer_strength)){
	$transfer_strength = 0;
}

        // Update additional strength in hostel_name table
        $sql_hostel = "UPDATE hostel_name SET additional_strength = ? WHERE unique_id = ?";
        $stmt_hostel = $mysqli->prepare($sql_hostel); // Initialize $stmt_hostel
        if ($stmt_hostel === false) {
            die('MySQLi prepare() error: ' . $mysqli->error);
        }

        $stmt_hostel->bind_param("is", $additional_strength, $vali_to_hostel_name); // Assuming transfer_count is an integer
        $result_hostel = $stmt_hostel->execute();
        if ($result_hostel === false) {
            die('Execution failed: ' . $stmt_hostel->error);
        }

        $sql_from_hostel = "UPDATE hostel_name SET transfer_strength = ? WHERE unique_id = ?";
        $stmt_from_hostel = $mysqli->prepare($sql_from_hostel); // Initialize $stmt_hostel
        if ($stmt_from_hostel === false) {
            die('MySQLi prepare() error: ' . $mysqli->error);
        }

        $stmt_from_hostel->bind_param("is", $transfer_strength, $vali_from_hostel_name); // Assuming transfer_count is an integer
        $result_from_hostel = $stmt_from_hostel->execute();
        if ($result_from_hostel === false) {
            die('Execution failed: ' . $stmt_hostel->error);
        }


        if ($result === false) {
            $status = false;
            $data = null;
            $error = 'Error executing query: ' . $stmt->error;
            $msg = 'error';
            $sql = $sql; // You may optionally log the SQL query for debugging purposes
        } else {
            $status = true;
            $data = ['affected_rows' => $stmt->affected_rows];
            $error = '';
            $msg = 'success_delete';
            $sql = $sql; // You may optionally log the SQL query for debugging purposes
        }

        // $sql_hostel = "
        //     UPDATE hostel_name 
        //     SET additional_strength = ? 
        //     WHERE unique_id = (
        //         SELECT to_hostel_name 
        //         FROM additional_strength 
        //         WHERE unique_id = ? 
        //         LIMIT 1
        //     )
        // ";

        // // Prepare the statement for hostel table
        // $stmt_hostel = $mysqli->prepare($sql_hostel);
        // if ($stmt_hostel === false) {
        //     die('MySQL prepare error: ' . $mysqli->error);
        // }

        // // Bind parameters
        // $additional_strength = 0; // Resetting additional_strength on deletion
        // $stmt_hostel->bind_param('is', $additional_strength, $unique_id);

        // // Execute the statement
        // $result_hostel = $stmt_hostel->execute();

        // if ($result_hostel) {
        //     // Success logic
        //     $status = true;
        // } else {
        //     // Error handling
        //     echo 'Error executing query: ' . $stmt_hostel->error;
        // }

        // Prepare JSON response
        $json_array = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'msg' => $msg,
            'sql' => $sql,
        ];

        // Output JSON
        echo json_encode($json_array);

        // Close statement
        $stmt->close();
        $stmt_hostel->close();
        $mysqli->close();
        break;

    case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name(' ', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;

    case 'get_hostel_strength':
        $table = 'hostel_name';

        $from_hostel_name = $_POST['from_hostel_name'];
        $where = [];

        $columns = [
            '(sanctioned_strength + additional_strength - transfer_strength) as sanctioned_strength',
            "(select count(id) from std_app_s where status = 1 and hostel_1 = '".$_POST['from_hostel_name']."') as reg_cnt",
            'unique_id',
        ];
        $table_details = [
            $table,
            $columns,
        ];

        $where = "unique_id = '" . $_POST['from_hostel_name'] . "' ";

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {
            $result_values = $result_values->data;
            if ($result_values != '') {
                $sanctioned_strength = $result_values[0]['sanctioned_strength'];
                $reg_cnt = $result_values[0]['reg_cnt'];
                $final_sanc_strength = $sanctioned_strength - $reg_cnt;
                print_r($final_sanc_strength);
                exit;
            }
        }

        $json_array = [
            'data' => $data,
            'sanctioned_strength' => $final_sanc_strength,
        ];

        break;

    case 'get_to_hostel_strength':
        $table = 'hostel_name';

        $to_hostel_name = $_POST['to_hostel_name'];
        // $where=[];

        $columns = [
            '(sanctioned_strength + additional_strength - transfer_strength) as sanctioned_strength',
            "(select count(id) from std_app_s where status = 1 and hostel_1 = '".$_POST['to_hostel_name']."') as reg_cnt",
            'unique_id',
        ];

        $table_details = [
            $table,
            $columns,
        ];

        $where = "unique_id = '" . $_POST['to_hostel_name'] . "' ";

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {
            $result_values = $result_values->data;

            $sanctioned_strength = $result_values[0]['sanctioned_strength'];
                $reg_cnt = $result_values[0]['reg_cnt'];
                $final_sanc_strength = $sanctioned_strength - $reg_cnt;
            print_r($final_sanc_strength);
            exit;
        }

        $json_array = [
            'data' => $data,
            'sanctioned_strength' => $final_sanc_strength,
        ];

        break;

    default:
        break;
}


function get_additional_strnth($unique_id = "")
{
    // echo $zone_name;
// print_r($unique_id);die();
    global $pdo;
// echo "eeeee";
    $table_name = "hostel_name";
    $where = [];
    $table_columns = [
      
        "additional_strength",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] .= $unique_id;
    }

    
    $asset_type_name = $pdo->select($table_details, $where);

    // print_R( $product_type_list);
    if ($asset_type_name->status) {
        return $asset_type_name->data[0]['additional_strength'];
    } else {
        print_r($asset_type_name);
        return 0;
    }
}

function get_transfer_strnth($unique_id = "")
{
    // echo $zone_name;
// print_r($unique_id);die();
    global $pdo;
// echo "eeeee";
    $table_name = "hostel_name";
    $where = [];
    $table_columns = [
      
        "transfer_strength",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] .= $unique_id;
    }

    
    $asset_type_name = $pdo->select($table_details, $where);

    // print_R( $product_type_list);
    if ($asset_type_name->status) {
        return $asset_type_name->data[0]['transfer_strength'];
    } else {
        print_r($asset_type_name);
        return 0;
    }
}

function get_transfer_count($unique_id = "")
{
    // echo $zone_name;
// print_r($unique_id);die();
    global $pdo;
// echo "eeeee";
    $table_name = "additional_strength";
    $where = [];
    $table_columns = [
      
        "transfer_count",
        "from_hostel_name",
        "to_hostel_name"

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    
    $asset_type_name = $pdo->select($table_details, $where);

    // print_R( $product_type_list);
    if ($asset_type_name->status) {
        return $asset_type_name->data;
    } else {
        print_r($asset_type_name);
        return 0;
    }
}


function get_additional_strength($unique_id = "")
{
    // echo $zone_name;
// print_r($unique_id);die();
    global $pdo;
// echo "eeeee";
    $table_name = "additional_strength";
    $where = [];
    $table_columns = [
      
        "sum(transfer_count) as transfer_count",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        // $where = [];
        $where["to_hostel_name"] .= $unique_id;
    }

    
    $asset_type_name = $pdo->select($table_details, $where);

    // print_r($asset_type_list);
    if ($asset_type_name->status) {
        return $asset_type_name->data[0]['transfer_count'];
    } else {
        print_r($asset_type_name);
        return 0;
    }
}

function get_transfer_strength($unique_id = "")
{
    // echo $zone_name;
// print_r($unique_id);die();
    global $pdo;
// echo "eeeee";
    $table_name = "additional_strength";
    $where = [];
    $table_columns = [
      
        "sum(transfer_count) as transfer_count",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        // $where = [];
        $where["from_hostel_name"] .= $unique_id;
    }

    
    $asset_type_name = $pdo->select($table_details, $where);

    // print_R( $product_type_list);
    if ($asset_type_name->status) {
        return $asset_type_name->data[0]['transfer_count'];
    } else { 
        print_r($asset_type_name);
        return 0;
    }
}



 