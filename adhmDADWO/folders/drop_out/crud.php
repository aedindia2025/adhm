<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "dropout";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';


// // Variables Declaration
$action = $_POST['action'];
// $action_obj         = (object) [
//     "status"    => 0,
//     "data"      => "",
//     "error"     => "Action Not Performed"
// ];

$json_array = "";
$sql = "";

$feedback_type = "";
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

    case 'createupdate':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $student_id = $_POST["student_id"];
        $student_name = $_POST["student_name"];
        $drop_discontinue_date = $_POST["drop_discontinue_date"];
        $reason = $_POST["reason"];
        $staff_id = $_POST["staff_id"];
        $staff_name = $_POST["staff_name"];
        $academic_year = $_POST["academic_year"];
        $district_id = $_POST["district_id"];
        $taluk_id = $_POST["taluk_id"];
        $hostel_id = $_POST["hostel_id"];

        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "student_id" => $student_id,
            "student_name" => $student_name,
            "drop_discontinue_date" => $drop_discontinue_date,
            "reason" => $reason,
            "staff_id" => $staff_id,
            "staff_name" => $staff_name,
            "district_id" => $district_id,
            "taluk_id" => $taluk_id,
            "hostel_id" => $hostel_id,
            "is_active" => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];

        $select_where = 'student_id = "' . $student_id . '"  AND is_delete = 0  ';

        // When Update Check without current id
        if ($unique_id) {
            $select_where .= ' AND unique_id !="' . $unique_id . '" ';
        }

        $action_obj = $pdo->select($table_details, $select_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;

        } else {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = $action_obj->error;
            $sql = $action_obj->sql;
            $msg = "error";
        }
        if ($data[0]["count"]) {
            $msg = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
            if ($unique_id) {

                unset($columns['unique_id']);

                $update_where = [
                    "unique_id" => $unique_id
                ];

                $action_obj = $pdo->update($table, $columns, $update_where);

                // Update Ends
            } else {
                $update_columns = [
                    "dropout_status" => '2',
                    "dropout_date" => date('Y-m-d')
                ];
                $update_main_where = [
                    "unique_id" => $student_id
                ];


                // Insert Begins            
                $action_obj = $pdo->insert($table, $columns);
                $action_obj = $pdo->update("std_reg_p1", $update_columns, $update_main_where);


                // Insert Ends

            }
            // print_r($action_obj);die();

            if ($action_obj->status) {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $sql = $action_obj->sql;

                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
            } else {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = $action_obj->error;
                $sql = $action_obj->sql;
                $msg = "error";
            }
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            // "sql"       => $sql
        ];

        // print_r($msg);die();

        echo json_encode($json_array);

        break;

    case 'datatable':
        // DataTable Variables
        $length = isset($_POST['length']) ? $_POST['length'] : 10;
        $start = isset($_POST['start']) ? $_POST['start'] : 0;
        $draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $limit = $length;

        if ($length == '-1') {
            $limit = "";
        }

        // Initialize response data
        $data = [];
        $json_array = [];

        // SQL Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "(select hostel_name from hostel_name where hostel_name.unique_id = dropout.hostel_id) as hostel_name",
            "student_name",
            "(SELECT std_reg_no FROM std_reg_s WHERE std_reg_s.unique_id = dropout.student_id) AS student_id",
            "dropout_reason",
            "status",
            "status_upd_date",
            "reject_reason",
            "unique_id",
            "student_id as std_id",
            "cust_reason"
        ];

        $sql_columns = implode(", ", $columns);
        $table_with_counter = $table . ", (SELECT @a:=?) AS a";
        $where = "is_delete = ? AND district_id = ?";
        $is_delete = "0";
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
                $stmt->bind_param("issii", $start, $is_delete, $_SESSION['district_id'], $start, $limit);
            } else {
                $stmt->bind_param("iss", $start, $is_delete, $_SESSION['district_id']);
            }

            // Execute the query
            $stmt->execute();

            // Fetch the result
            $result = $stmt->get_result();

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $unique_id = $row['unique_id'];
                    if($row['dropout_reason'] == '673f05bd7d90c91668'){
                        $row['dropout_reason'] = $row['cust_reason'];
                        }else{
                            $row['dropout_reason'] = dropout_reason($row['dropout_reason'])[0]['dropout_reason'];
                        }
                    // if($row['status'] == '1'){
                    //     $row['status'] = '<p style="color:green">Approved</p>';
                    // }elseif($row['status'] == '2'){
                    //     $row['status'] = '<p style="color:red">Rejected</p>';
                    // }else{
                    //     $row['status'] = '<p style="color:blue">Pending</p>';
                    // }

                    if(!$row['status_upd_date']){
                        $row['status_upd_date'] = '-';
                    }

                    if(!$row['reject_reason']){
                        $row['reject_reason'] = '-';
                    }

                    if ($row['status'] == 0) {
                        $acceptButton = '<button class="accept-btn"  data-std-id="' . $row['std_id'] . '" data-unique-id="' . $row['unique_id'] . '">Accept</button>';
                        $rejectButton = '<button class="reject-btn"  data-std-id="' . $row['std_id'] . '" data-unique-id="' . $row['unique_id'] . '">Reject</button>';
                        $row['status'] = $acceptButton . ' ' . $rejectButton;
                    } elseif ($row['status'] == 1) {
                        $row['status'] = '<span style="color: green;">Accepted</span>';
                    } elseif ($row['status'] == 2) {
                        $row['status'] = '<span style="color: red;">Rejected</span>';
                    }

                    $btn_update = btn_update($folder_name, $unique_id);
                    $btn_delete = btn_delete($folder_name, $unique_id);
                    $eye_button = '<a class="btn btn-action specl2" href="javascript:leave_print(\'' . $unique_id . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';

                    $row['unique_id'] = $eye_button;
                    $data[] = array_values($row);
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
        }

        // Output JSON response
        echo json_encode($json_array);

        break;


        case 'at_accept':

      
            $table = 'dropout';
            $unique_id = $_POST['uniqueId'];
            $std_id = $_POST['std_id'];
           
                $status_upd_date = date('Y-m-d');
                $stmt = $mysqli->prepare("UPDATE $table SET status = ?, status_upd_date = ? WHERE unique_id = ?");
                $stmt->bind_param("sss", $status, $status_upd_date, $unique_id);
                $status = 1;
                $stmt->execute();
                $action_obj = $stmt->affected_rows;
                $stmt->close();
                // echo "ff";
    
                // Update for $table2
                $stmt2 = $mysqli->prepare("UPDATE std_reg_s SET dropout_status = ?, dropout_date = ? WHERE unique_id = ?");
                $stmt2->bind_param("sss", $status2, $status_upd_date, $std_id);
                $status2 = 2;
                $stmt2->execute();
                $action_obj_table2 = $stmt2->affected_rows;
                $stmt2->close();
                // echo "ff";
    
                
    
                // Handle errors if needed
                if ($action_obj === false || $action_obj_table2 === false) {
                    $status = false; // Assuming $status is used to track success/failure
                    $error = $mysqli->error;
                    $msg = "error";
                } else {
                    $status = true; // Assuming $status is used to track success/failure
                    $msg = "success"; // Assuming this is the success message
                }
           
    
            $json_array = [
                "status" => $status,
                "error" => $error ?? null, // Set error to null if not defined
                "msg" => $msg,
                // Optionally include more data if needed
            ];
    
            echo json_encode($json_array);
    
            // Close MySQLi connection
            $mysqli->close();
    
            break;
    
    
        case 'at_reject':
    
          
    
            $table = 'dropout';
            $unique_id = $_POST['uniqueId'];
            $std_id = $_POST['std_id'];
            $reason = $_POST['reason'];
           
                $status_upd_date = date('Y-m-d');
               
                $stmt = $mysqli->prepare("UPDATE $table SET status = ?, status_upd_date = ?, reject_reason = ? WHERE unique_id = ?");
                $stmt->bind_param("ssss", $status, $status_upd_date, $reason, $unique_id);
                $status = 2;

                $stmt->execute();

                $action_obj = $stmt->affected_rows;
                $stmt->close();
                // echo "ff";
    
                // Update for $table2
                // $stmt2 = $mysqli->prepare("UPDATE std_reg_s SET dropout_status = ?, dropout_date = ? WHERE unique_id = ?");
                // $stmt2->bind_param("sss", $status2, $status_upd_date, $std_id);
                // $status2 = 3;
                // $stmt2->execute();
                // $action_obj_table2 = $stmt2->affected_rows;
                // $stmt2->close();
                // echo "ff";
    
            // Handle errors if needed
            if ($action_obj === false) {
                $status = false; // Assuming $status is used to track success/failure
                $error = $mysqli->error;
                $msg = "error";
            } else {
                $status = true; // Assuming $status is used to track success/failure
                $msg = "success_delete"; // Assuming this is the success message
            }
    
            $json_array = [
                "status" => $status,
                "error" => $error ?? null, // Set error to null if not defined
                "msg" => $msg,
                // Optionally include more data if needed
            ];
    
            echo json_encode($json_array);
    
            // Close MySQLi connection
            $mysqli->close();
    
            break;



    case 'delete':

        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            die('CSRF validation failed.');
        }

        $unique_id = $_POST['unique_id'];

        $columns = [
            "is_delete" => 1
        ];

        $update_where = [
            "unique_id" => $unique_id
        ];

        $action_obj = $pdo->update($table, $columns, $update_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
            $msg = "success_delete";

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
            // "sql"       => $sql
        ];

        echo json_encode($json_array);
        break;

    case 'get_std_name':

        $student_id = $_POST['student_id'];


        // Query Variables
        $json_array = "";
        $columns = [

            "std_name"

        ];
        $table_details = [
            "std_reg_p1",
            $columns
        ];
        $where = "is_delete = 0 and unique_id = '" . $student_id . "'";




        // $sql_function   = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {


                $student_name = $value['std_name'];

                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "student_name" => $student_name,
                // "testing"           => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;

    default:

        break;
}

// $user_type          = $_POST["user_type"];
// $is_active          = $_POST["is_active"];
// $unique_id          = $_POST["unique_id"];

// $update_where       = "";

// //count user_type
// if($unique_id == ''){
//     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1'");
// }else{
//     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1' and `unique_id` != '".$unique_id."'" );
// }

// $get_user_type->execute();
// $user_type_count  = $get_user_type->fetchColumn();    

// if($user_type_count == 0){


//     if($unique_id == ''){//insert
//         $unique_id = uniqid().rand(10000,99999);

//         if($prefix) {
//             $unique_id = $prefix.$unique_id;
//         }

//         $Insql=$pdo_conn->prepare("insert into user_type (`unique_id`,`user_type`, `is_active`, `is_delete`)values('".$unique_id."','".$user_type."','1','0')");
//         $Insql->execute();
//         $msg = "Created";
//         echo $msg;
//     }else{//update
//         $Insql=$pdo_conn->prepare("UPDATE `user_type` SET `user_type`= '".$user_type."',`is_active`= '".$is_active."' WHERE unique_id = '".$unique_id."'");

//         $Insql->execute();
//         $msg  = "Updated";
//         echo $msg;
//     }
// }else{ 
//     $msg  = "already";
//     echo $msg;
// }
//     break;
//     case 'delete' :
//         $unique_id          = $_POST["unique_id"];
//         $del_sql=$pdo_conn->prepare("update user_type set is_delete='1' where unique_id ='".$unique_id."'" );
//         $del_sql->execute();
//             $msg  = "success_delete";
//             echo $msg;

//     break;
//     default:

//     break;
// }
?>