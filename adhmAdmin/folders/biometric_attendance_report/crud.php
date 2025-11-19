<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// Database Country Table Name
$table = "attendance_report";

// Include DB file and Common Functions
include '../../config/dbconfig.php';

// Variables Declaration
$action = $_POST['action'];

$json_array = "";
$sql = "";

$feedback_type = "";
//$is_active = "";
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



    case 'datatable':
        // DataTable Variables
        $search     = $_POST['search']['value'];
        $length     = $_POST['length'];
        $start      = $_POST['start'];
        $draw       = $_POST['draw'];
        $limit      = $length;

        $data       = [];

        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];

        if ($length == '-1') {
            $limit = "";
        }

        if ($_POST['from_date'] && $_POST['to_date']) {
            $where = 'report_date >= "' . $_POST['from_date'] . '" and report_date <= "' . $_POST['to_date'] . '"';
        } else {
            if ($_POST['from_date']) {
                $where = 'report_date = "' . $_POST['from_date'] . '"';
            }
            if ($_POST['to_date']) {
                $where = 'report_date = "' . $_POST['to_date'] . '"';
            }
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "'' as s_no",
            "hostel_id",
            "hostel_name",
            "district_name",
            "hostel_type",
            "sanctioned_strength",
            "dadwo_approved_count",
            "biometric_reg_count",
            "report_date",
            "morning_punch_count", 
            "eve_punch_count"
        ];
        $table_details = [
            $table,
            $columns
        ];
        $order_by = "";

        if ($district_name != '') {
            $where .= " and district_id = '" . $district_name . "'";
        }

        if ($taluk_name != '') {
            $where .= " and taluk_name = '" . $taluk_name . "'";
        }

        if ($hostel_name != '') {
            $where .= " and hostel_unique_id = '" . $hostel_name . "'";
        }

        $where .= "order by hostel_id,report_date asc";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();

        if ($result->status) {
            $res_array = $result->data;

            // Start serial number from the correct position
            $sno = $start + 1;

            foreach ($res_array as $key => $value) {
                $value['s_no'] = $sno++;
                // Convert the 'report_date' format
                if (!empty($value['report_date'])) {
                    $originalDate = $value['report_date'];
                    $formattedDate = date("d-m-Y", strtotime($originalDate)); // Convert to DD-MM-YYYY
                    $value['report_date'] = $formattedDate;
                }

                $data[] = array_values($value);
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

    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;

    case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

	    case 'att_count':
           
            $yesterday = date('Y-m-d', strtotime('-1 day'));
    
            // Base query to get the count of online devices
            $sql = 'SELECT COUNT(*) as att_count FROM attendance_report where report_date = "'.$yesterday.'"';
    
            // Add conditions for district_name and taluk_name if provided
    
            // Prepare the SQL statement
            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'Error preparing statement: ' . $mysqli->error]);
                exit;
            }
    
            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $tot_bio_enrolled = $row['att_count'];
    
            // Close the statement
            $stmt->close();
    
            // Return the online count
            echo json_encode([
                'status' => true,
                'att_count' => $att_count,
            ]);
    
            $mysqli->close();
            break;

    default:

        break;
}
