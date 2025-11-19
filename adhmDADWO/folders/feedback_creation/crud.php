<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "feedback_creation";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

// $fund_name          = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

switch ($action) {
    
    
    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;
    
        $data = [];
    
        
        // Query Variables
        $columns = [
            "@a:=@a+1 s_no",
            "current_date",
            "student_id",
            "district_id",
            "taluk_id",
            "hostel_id",
            "feedback_name",
            "rating",
            "description"
        ];
        $table = "feedback_creation"; // Adjust your table name here
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = ?";
    $is_delete = '0';
        // Prepare conditions for bind_param
        $bind_params = "ss";
        $bind_values = [$start,$is_delete];

    
        // Additional conditions based on POST values
        if (!empty($_POST["district_name"])) {
            $where .= " AND district_id = ?";
            $bind_params .= "s";
            $bind_values[] = $_POST["district_name"];
        }
        if (!empty($_POST["taluk_name"])) {
            $where .= " AND taluk_id = ?";
            $bind_params .= "s";
            $bind_values[] = $_POST["taluk_name"];
        }
        if (!empty($_POST["hostel_name"])) {
            $where .= " AND hostel_id = ?";
            $bind_params .= "s";
            $bind_values[] = $_POST["hostel_name"];
        }
    
        // SQL function for counting total records
        $sql_function = "SQL_CALC_FOUND_ROWS";
    
        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
            $bind_params .= "ii";
            $bind_values[] = $start;
            $bind_values[] = $limit;
        }
    
        $stmt = $mysqli->prepare($sql);
    
        // Bind parameters dynamically
        if (!empty($bind_params)) {
            $stmt->bind_param($bind_params, ...$bind_values);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];
    
        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);
    
            foreach ($res_array as $key => $value) {
                // Modify district_id, taluk_id, hostel_id, feedback_name as per your functions
                $value['district_id'] = district_name_un($value['district_id']);
                $value['taluk_id'] = taluk_name_un($value['taluk_id']);
                $value['hostel_id'] = hostel_name_un($value['hostel_id']);
                $value['feedback_name'] = feedback_name_un($value['feedback_name']);
    
                // Example modification of fields
                // $value['field_name'] = custom_function($value['field_name']);
    
                $data[] = array_values($value);
            }
    
            // Prepare JSON response for DataTables
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data
            ];
        } else {
            // Handle the error case
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error
            ];
        }
    
        echo json_encode($json_array);
    
       
        break;
    


            case 'district_name':

                $district_name = $_POST['district_name'];
                    
                // echo $district_name;
    
                $district_name_options = taluk_name('',$district_name);
    
                $taluk_name_options = select_option($district_name_options,"Select Taluk");
                
                echo $taluk_name_options;
    
                break;
    
            case 'get_hostel_by_taluk_name':
    
                $taluk_name = $_POST['taluk_name'];
    
    
                $hostel_name_options = hostel_name('',$taluk_name);
    
                $hostel_name_options = select_option($hostel_name_options,"Select Hostel");
    
                echo $hostel_name_options;
    
                break;
    
         
          
    
    case 'delete':
        
        $unique_id      = $_POST['unique_id'];

        $columns        = [
            "is_delete"   => 1
        ];

        $update_where   = [
            "unique_id"     => $unique_id
        ];

        $action_obj     = $pdo->update($table,$columns,$update_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;
            $msg        = "success_delete";

        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            // "sql"       => $sql
        ];

        echo json_encode($json_array);
        break;

    default:
        
        break;
}
    
        //
?>
