<?php

// Get folder Name From Current Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Country Table Name

// Include DB file and Common Functions
include '../../config/dbconfig.php';

// Variables Declaration
$action = $_POST['action'] ?? '';

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
        // DataTable Variables
        $search = $_POST['search']['value'] ?? '';
        $length = $_POST['length'] ?? '';
        $start = $_POST['start'] ?? 0;
        $draw = $_POST['draw'] ?? 1;
        $limit = $length;

        if ($length == '-1') {
            $limit = "";
        }

        $date_type = $_POST['date_type'];
        

        if($date_type == '1'){
            $currentDate = date('Y-m-d');
            $from_date = date('Y-m-d', strtotime('monday last week', strtotime($currentDate)));
            $to_date = date('Y-m-d', strtotime('sunday last week', strtotime($currentDate)));
           
        }else if($date_type == '2'){
            $currentDate = date('Y-m-d');
            $from_date = date('Y-m-d', strtotime('first day of last month', strtotime($currentDate)));
            $to_date = date('Y-m-d', strtotime('last day of last month', strtotime($currentDate)));
            
        }else if($date_type == '3'){
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
        }


        

        // Query Variables
        $json_array = [];
        $columns = [
            "@a:=@a+1 s_no",
            "district_name",
            "'' as new_app",
            "'' as renewal_app",
            "unique_id"
        ];
        $table = "district_name"; // Replace with your actual table name
        $table_details = $table . ", (SELECT @a:= " . $start . ") AS a";
        $where = "is_delete = 0 and unique_id = '".$_SESSION['district_id']."'";
        $bindParams = [];
        $bindTypes = '';
        $order_by = "";

        $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM " . $table_details . " WHERE " . $where;

        if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
            $bindParams[] = $start;
            $bindParams[] = $limit;
            $bindTypes .= 'ii'; // 'i' for integer
        }

        // Prepare statement
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        if (!empty($bindParams)) {
            $stmt->bind_param($bindTypes, ...$bindParams);
        }

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch data
        $data = [];
        while ($value = $result->fetch_assoc()) {
            $district_id = $value['unique_id'];
            $value['new_app'] = getNewCount($district_id,$from_date,$to_date);
            $value['renewal_app'] = getRenewalCount($district_id,$from_date,$to_date);
            // $value['accepted_count'] = getAcceptedCount($district_id);
            // $value['approved_count'] = getApprovedCount($district_id);
            // $value['rejected_count'] = getRejectedCount($district_id);

            // $total_applied += $value['applied_count'];
            // $total_accepted += $value['accepted_count'];
            // $total_approved += $value['approved_count'];
            // $total_rejected += $value['rejected_count'];

            $data[] = array_values($value);
        }

        // Total records count
        $total_records = $mysqli->query("SELECT FOUND_ROWS() AS total")->fetch_assoc()['total'];

        $json_array = [
            "draw" => intval($draw),
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data,
            // "totals" => [
            //     "total_applied" => $total_applied,
            //     "total_accepted" => $total_accepted,
            //     "total_approved" => $total_approved,
            //     "total_rejected" => $total_rejected
            // ]
        ];

        echo json_encode($json_array);
        break;

    default:
        break;
}

function getAppliedCount($district_id)
{
     $host = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $databasename = "adi_dravidar";
    $mysqli = new mysqli($host, $username, $password, $databasename);

    $sql = "SELECT COUNT(id) as count FROM std_app_s WHERE hostel_district_1 = ? AND is_delete = 0";
    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param('s', $district_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['count'];
}

function getNewCount($district_id,$from_date,$to_date)
{
     $host = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $databasename = "adi_dravidar";
    $mysqli = new mysqli($host, $username, $password, $databasename);

    if($from_date){
        // if($from_date != '' && $to_date == ''){
        $where_date = 'and entry_date >= "'.$from_date.'"';
        // }else if($from_date != '' && $to_date != ''){
        // $where_date = 'and entry_date >= "'.$from_date.'"';
        // }
    }
    if($to_date){
        // if($from_date == '' && $to_date != ''){
            $where_date .= 'and entry_date <= "'.$to_date.'"';
            // }else if($from_date != '' && $to_date != ''){
            // $where_date .= 'and entry_date <= "'.$to_date.'"';
            // }
    }

    $sql = "SELECT COUNT(id) as count FROM std_app_s WHERE hostel_district_1 = ? AND is_delete = 0 AND application_type = 1 $where_date";
    // echo $sql;
    
    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param('s', $district_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['count'];
}

function getRenewalCount($district_id,$from_date,$to_date)
{
     $host = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $databasename = "adi_dravidar";
    $mysqli = new mysqli($host, $username, $password, $databasename);

    if($from_date){
                $where_date = 'and entry_date >= "'.$from_date.'"';
      }
    if($to_date){
            $where_date .= 'and entry_date <= "'.$to_date.'"'; 
    }

    $sql = "SELECT COUNT(id) as count FROM std_app_s WHERE hostel_district_1 = ? AND is_delete = 0 AND application_type = 2 $where_date";
    // echo $sql;
    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param('s', $district_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['count'];
}

// // Function to get accepted count
// function getAcceptedCount($district_id)
// {
//     $host = "localhost";
//     $username = "root";
//     $password = "H_Cw3O4CM*fXcGtz";
//     $databasename = "adi_dravidar";
//     $mysqli = new mysqli($host, $username, $password, $databasename);

//     $sql = "SELECT COUNT(id) as count FROM batch_creation WHERE hostel_district = ? AND is_delete = 0";
//     $stmt = $mysqli->prepare($sql);

//     $stmt->bind_param('s', $district_id);
//     $stmt->execute();
//     $result = $stmt->get_result()->fetch_assoc();
//     return $result['count'];
// }

// // Function to get approved count
// function getApprovedCount($district_id)
// {
//     $host = "localhost";
//     $username = "root";
//     $password = "H_Cw3O4CM*fXcGtz";
//     $databasename = "adi_dravidar";
//     $mysqli = new mysqli($host, $username, $password, $databasename);

//     $sql = "SELECT COUNT(id) as count FROM std_reg_s WHERE hostel_district_1 = ? AND is_delete = 0 AND status = 1";
//     $stmt = $mysqli->prepare($sql);

//     $stmt->bind_param('s', $district_id);
//     $stmt->execute();
//     $result = $stmt->get_result()->fetch_assoc();
//     return $result['count'];
// }

// // Function to get rejected count
// function getRejectedCount($district_id)
// {
//     $host = "localhost";
//     $username = "root";
//     $password = "H_Cw3O4CM*fXcGtz";
//     $databasename = "adi_dravidar";
//     $mysqli = new mysqli($host, $username, $password, $databasename);

//     $sql = "SELECT COUNT(id) as count FROM std_reg_s WHERE hostel_district_1 = ? AND is_delete = 0 AND status = 2";
//     $stmt = $mysqli->prepare($sql);

//     $stmt->bind_param('s', $district_id);
//     $stmt->execute();
//     $result = $stmt->get_result()->fetch_assoc();
//     return $result['count'];
// }

