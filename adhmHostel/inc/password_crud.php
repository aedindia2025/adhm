<?php



// // Include DB file and Common Functions
include '../config/dbconfig.php';
// include 'header.php';

// // Variables Declaration
$action = $_POST['action'];

$table = "staff_registration";

$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

switch ($action) {


    case 'verify_password':

        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $user_name =  sanitizeInput($_SESSION["user_name"]);

        $old_password = sanitizeInput($_POST["old_password"]);

        // Query Variables
        $json_array = "";
        $columns_1 = [

            "user_name",
            "password"

        ];
        $table_details_1 = [
            $table,
            $columns_1
        ];

        $where_1 = " is_delete = 0 AND user_name = '".$user_name."' AND password ='".$old_password."'";



        $result_1 = $pdo->select($table_details_1, $where_1);
      //   print_r($result_1);die();
        $total_records = total_records();

        if ($result_1->status) {

            $res_array = $result_1->data;

            foreach ($res_array as $key => $value) {


                $server_password = $value['password'];
                
            }

            $json_array = [


                // "password" => $older_password,
                // "data" => $data,

                // "password" => $res_array
                "password_old" => $server_password
                // "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;



    case 'confirm_password':

        // DataTable Variables
        //$search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;



        $unique_id =    $_SESSION['sess_user_id'];


        $new_password   = sanitizeInput($_POST["new_password"]);

        $de_new_password = base256_decode($new_password);

        $confirm_password   = sanitizeInput($_POST["confirm_password"]);

        $de_confirm_password = base256_decode($confirm_password);

        $hashedPassword = sanitizeInput($_POST["hashedPassword"]);


        // Query Variables



        $update_data = [

            "password" => $de_new_password,
            "confirm_password" => $de_confirm_password,
            "hashedPassword" => $hashedPassword
        ];

        $update_where = [

            "unique_id" => $unique_id
        ];

        $action_obj = $pdo->update($table, $update_data, $update_where);


        if ($action_obj->status) {

            $msg = "Password updated successfully";
        } else {
            $msg = "Error updating password";
        }

        $json_array = [
            "status" => $action_obj->status,
            "data" => $action_obj->data,
            "error" => $action_obj->error,
            "msg" => $msg,
            "sql" => $action_obj->sql
        ];


        echo json_encode($json_array);
        break;
}


function base256_decode($str)
{
    $result = '';
    for ($i = 0; $i < strlen($str); $i += 3) {
        $charCode = intval(substr($str, $i, 3));
        $result .= chr($charCode);
    }
    return $result;
}

?>
