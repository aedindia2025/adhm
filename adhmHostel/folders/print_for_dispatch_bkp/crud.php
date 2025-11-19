<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];




// // Database Country Table Name
$table = "std_app_s";
$table_p2 = "std_app_p2";
$table_p3 = "std_app_p3";
$table_p4 = "std_app_p4";
$table_p5 = "std_app_p5";
$table_p6 = "std_app_p6";
$table_p7 = "std_app_p7";
$table_p8 = "std_app_p8";
$table_p9 = "std_app_p9";
$table_p10 = "std_app_p10";
$table_p11 = "std_app_p11";
$table_p12 = "std_app_p12";
$table_batch = "batch_creation";

$table2 = "print_for_dispatch";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';
// include 'function.php';

// // Variables Declaration
$action = $_POST['action'];

$hostel_type = "";
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

        $hostel_type = $_POST["hostel_type"];
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        $update_where = "";

        $columns = [
            "hostel_type" => $hostel_type,
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
        $select_where = 'hostel_type = "' . $hostel_type . '"  AND is_delete = 0  ';

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

                // Insert Begins            
                $action_obj = $pdo->insert($table, $columns);
                // Insert Ends

            }

            if ($action_obj->status) {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = "";
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
            // "sql" => $sql
        ];

        echo json_encode($json_array);

        break;

    case 'datatable':


        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;
        $hostel_id = $_SESSION['hostel_id'];
        $is_delete = '0';

        $data = [];

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "'' as s_no",
            "batch_cr_date",
            "(SELECT application_type FROM std_app_s WHERE std_app_s.unique_id = batch_creation.s1_unique_id) as application_type",
            "batch_no",
            "'' as total_cnt",
            "'' as app_cnt",
            "'' as rej_cnt",
            "print_status",
            "rec_status",
            "rec_time",
	    "batch_status",
            "batch_sub_date",
            "batch_no as unique_id",
            "batch_no as uniq_id",
        ];
        $table_batch = 'batch_creation';
        $where = "is_delete = ? AND batch_no IS NOT NULL AND hostel_name = ?";


        $sql_function = "SQL_CALC_FOUND_ROWS";
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_batch WHERE $where GROUP BY batch_no";

        if ($limit != "") {
            $sql .= " LIMIT ?, ?";
        }

        // Prepare and execute the query
        $stmt = $mysqli->prepare($sql);
        if ($limit != "") {
            $stmt->bind_param('ssii', $is_delete, $hostel_id, $start, $limit);
        } else {
            $stmt->bind_param('ss', $is_delete, $hostel_id);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        // Get total records count
        $result_total = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $result_total->fetch_assoc()['total'];

        if ($result->num_rows > 0) {
            $s_no = 0;
            while ($row = $result->fetch_assoc()) {
                $row['s_no'] = ++$s_no;
                $row['total_cnt'] = total_count($row['unique_id']);
                $row['app_cnt'] = app_count($row['unique_id']);
                $row['rej_cnt'] = rej_count($row['unique_id']);

                $unique_id = $row['unique_id'];
                $row['unique_id'] = btn_print1("print_for_dispatch", $unique_id, "batch_print");
		
		$row['uniq_id'] = '<a class="btn btn-action specl2" href="javascript:print_for_dispatch(\'' . $unique_id . '\')"><i class="fa fa-print" style="
    font-size: 20px;color: #128807;"></i></a>';

                $application_type = $row['application_type'];
                if ($application_type == '1') {
                    $row['application_type'] = 'New';
                } else {
                    $row['application_type'] = 'Renewal';
                }

                if ($row['rec_status'] == '1') {
                    $row['rec_status'] = '<span style="color:green;">Received</span>';
                } else if ($row['rec_status'] == '0' || $row['rec_status'] == '') {
                    $row['rec_status'] = '<span style="color:blue;">Pending</span>';
                    $row['rec_time'] = '-';
                }
                if ($row['app'] == '') {
                    $row['app'] = '0';
                }
                if ($row['rej'] == '') {
                    $row['rej'] = '0';
                }
                if ($row['print_status'] == '2') {
                    $disabled = "disabled";
                } else {
                    $disabled = "";
                }

  		if ($row['batch_status'] == 0) {
                    $row['batch_status'] = '<b><span style="color:red;">Pending</span></b>';
                } elseif ($row['batch_status'] == 1) {
                    $row['batch_status'] = '<b><span style="color:orange;">Partially Completed</span></b>';
                } elseif ($row['batch_status'] == 2) {
                    $row['batch_status'] = '<b><span style="color:green;">Completed</span></b>';
                }

                $row['batch_sub_date'] = $row['batch_sub_date'] ? (new DateTime($row['batch_sub_date']))->format('d-m-Y') : '-';


                // Add select box HTML for the status column
                $status_select = '<select class="select2 form-control" id="print_id" style="cursor: pointer;" onchange="updateStatus(\'' . $unique_id . '\', this.value)" ' . $disabled . '>';
                $status_select .= '<option value="0"' . ($row['print_status'] == '0' ? ' selected' : '') . '>Pending</option>';
                $status_select .= '<option value="1"' . ($row['print_status'] == '1' ? ' selected' : '') . '>Printed</option>';
                $status_select .= '<option value="2"' . ($row['print_status'] == '2' ? ' selected' : '') . '>Submitted</option>';
                $status_select .= '</select>';

                $row['print_status'] = $status_select;

                $data[] = array_values($row);
            }
        }


        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

        $json_array = [
            "draw" => $draw,
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data,
        ];


        echo json_encode($json_array);
        $stmt->close();
        break;



    case 'get_student_details':
        // DataTable Variables


        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];


        // Query Variables
        $json_array = "";
        $columns = [
            // "@a:=@a+1 s_no",
            "std_name",
            // "",
            // "",
            // ""
        ];
        $table_details = [
            $table,
            $columns
        ];
        $where = "is_delete = 0 and entry_date >= '" . $from_date . "' and entry_date <= '" . $to_date . "'";

        // if (isset($_POST['academic_year'])) {
        //     if ($_POST['academic_year']) {
        //         $where .= " AND academic_year >= '" . $_POST['academic_year'] . "' ";
        //     }
        // }

        $order_by = "";



        // Datatable Searching
        $search = datatable_searching($search, $columns);


        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                // $data[]             = array_values($value);
                $data .= "<h4>'" . $value["std_name"] . "'</h4>";
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                // "testing" => $result->sql
            ];
        } else {
            // print_r($result);
        }

        echo json_encode($json_array);
        break;




    case 'batch_update':
        $from_date = $_POST["from_date"];
        $to_date = $_POST["to_date"];
        // 
        $batch_no = batch_no();

        $host = 'localhost'; // Change this to your database host
        $dbname = 'adi_dravidar'; // Change this to your database name
        $username = 'root'; // Change this to your database username
        $password = '4/rb5sO2s3TpL4gu'; // Change this to your database password

        // Create a PDO instance
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            // Set PDO to throw exceptions on error
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

        // $stmt = $pdo->prepare("select std_app_p1.unique_id from std_app_p1 LEFT JOIN std_app_p2 on std_app_p1.unique_id = std_app_p2.p1_unique_id where std_app_p2.hostel_name = '65584660e85as2403310'");
        $stmt = $pdo->prepare("UPDATE std_app_p1 AS t1
                JOIN std_app_p2 AS t2 ON t1.unique_id = t2.p1_unique_id
                SET t1.batch_no = '" . $batch_no . "' and t1.batch_cr_date = '" . date('Y-m-d') . "'
                WHERE t2.hostel_name = '65584660e85as2403310'");
        // Bind parameters

        // $stmt->bindParam(':value2', $value2);
        // $stmt->bindParam(':condition', $condition);

        // Set parameter values
        $value1 = $batch_no;
        // $value2 = 'new_value2';
        // $condition = 'condition_value';

        // Execute the prepared statement
        $stmt->execute();

        echo "Records updated successfully";
        break;


    case 'dispatch_datatable':
        // DataTable Variables

        $hostel_id = $_SESSION['hostel_id'];
        // $hostel_id1 = $_SESSION['hostel_id'];
        $apptype = $_POST["appilicationtype"];
        $hostel_name = $_POST["hostel_name"];
        $batch_no = $_POST["batch_no"];
        $hostel_taluk = $_POST["hostel_taluk"];
        $hostel_district = $_POST["hostel_district"];
        $is_delete = '0';

        $data = [];



        // Query Variables
        $json_array = "";
        $columns = [
            "unique_id",
            "'' as  s_no",
            "(select std_name from std_app_s2 where std_app_s2.s1_unique_id=std_app_s.unique_id) as std_name",
	        "'' as std_umis_emis_no",
            "std_app_no",
            "entry_date",
            "unique_id as s1_unique_id",
            "academic_year",
        ];
        $table1 = 'std_app_s';
        // echo $_POST["appilicationtype"];
        // echo $hostel_id;
        // echo  $is_delete;
        // echo $_POST["appilicationtype"];
        $where = "is_delete = ? AND hostel_1 = ? AND (batch_no IS NULL or batch_no = '') and submit_status = '1'";
        $params = [];

        // Add parameters based on conditions
        $params[] = $is_delete;
        $params[] = $hostel_id;
        // $params = [$is_delete, $hostel_id];

        if (!empty($_POST["appilicationtype"])) {
            $where .= " AND application_type = ?";
            $params[] = $_POST["appilicationtype"];
        }

	 function fetchStdEmisNo($mysqli, $s1_unique_id)
        {
            $sql = "SELECT std_name FROM std_app_emis_s3 WHERE s1_unique_id = ? LIMIT 1";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $s1_unique_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return strtoupper($row['std_name']);
            }

            return ''; // Return empty string if no result
        }

        function fetchUmisName($mysqli, $s1_unique_id)
        {
            $sql = "SELECT umis_name FROM std_app_umis_s4 WHERE s1_unique_id = ? LIMIT 1";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $s1_unique_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return strtoupper($row['umis_name']);
            }

            return ''; // Return empty string if no result
        }

        function fetchNoUmisName($mysqli, $s1_unique_id)
        {
            $sql = "SELECT no_umis_name FROM std_app_umis_s4 WHERE s1_unique_id = ? LIMIT 1";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $s1_unique_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return strtoupper($row['no_umis_name']);
            }

            return ''; // Return empty string if no result
        }



        $sql_function = "SQL_CALC_FOUND_ROWS";
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table1 WHERE $where";
        // print_r($sql);



        $types = ''; // Initialize types string for bind_param

        // Construct types string based on the number of parameters
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i'; // Integer
            } elseif (is_float($param)) {
                $types .= 'd'; // Double
            } elseif (is_string($param)) {
                $types .= 's'; // String
            } else {
                $types .= 's'; // Default to string for other types
            }
        }

        // Prepare and execute the query
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        // Get total records count
        $result_total = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $result_total->fetch_assoc()['total'];

        if ($result->num_rows > 0) {
            $s_no = 0;
            while ($row = $result->fetch_assoc()) {
                $row['s_no'] = ++$s_no;
                $row['std_name'] = strtoupper($row['std_name']);
	$std_emis_name = fetchStdEmisNo($mysqli, $row['s1_unique_id']);
                $umis_name = fetchUmisName($mysqli, $row['s1_unique_id']);
                $no_umis_name = fetchNoUmisName($mysqli, $row['s1_unique_id']);

                if (!empty($std_emis_name)) {
                    $row['std_umis_emis_no'] = $std_emis_name;
                } elseif (!empty($umis_name)) {
                    $row['std_umis_emis_no'] = $umis_name;
                } elseif (!empty($no_umis_name)) {
                    $row['std_umis_emis_no'] = $no_umis_name;
                } else {
                    $row['std_umis_emis_no'] = ''; // Default value if none found
                }

                // Adjust unique_id with HTML inputs as needed
                $row['unique_id'] = '<input class="myCheck" type="checkbox" id="invoice_check" name="invoice_check[]" checked disabled>
                                <input type="hidden" id="s1_unique_id" name="s1_unique_id[]" value="' . $row['s1_unique_id'] . '">  
                                <input type="hidden" id="batch_cr_date" name="batch_cr_date[]" value="' . date('Y-m-d') . '">  
                                <input type="hidden" id="hostel_name" name="hostel_name[]" value="' . $hostel_name . '">  
                                <input type="hidden" id="hostel_taluk" name="hostel_taluk[]" value="' . $hostel_taluk . '">  
                                <input type="hidden" id="batch_no" name="batch_no[]" value="' . $batch_no . '">  
                                <input type="hidden" id="hostel_district" name="hostel_district[]" value="' . $hostel_district . '">  
                                <input type="hidden" id="std_name" name="std_name[]" value="' . $row['std_name'] . '">
                                <input type="hidden" id="academic_year" name="academic_year[]" value="' . $row['academic_year'] . '">
                                <input type="hidden" id="std_app_no" name="std_app_no[]" value="' . $row['std_app_no'] . '">
                                <input type="hidden" id="applied_date" name="applied_date[]" value="' . $row['entry_date'] . '">';

                // Other data manipulations as necessary

                $data[] = array_values($row);
            }
        }

        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

        $json_array = [
            "draw" => $draw,
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data,
        ];


        echo json_encode($json_array);
        $stmt->close();
        break;


    case 'batch_add':
        // Get POST data
        $s1_unique_id = $_POST["s1_unique_id"];
        $applied_date = $_POST["applied_date"];
        $hostel_name = $_POST["hostel_name"];
        $hostel_taluk = $_POST["hostel_taluk"];
        $hostel_district = $_POST["hostel_district"];
        $std_name = $_POST["std_name"];
        $std_app_no = $_POST["std_app_no"];
        $academic_year = $_POST["academic_year"];
        $batch_no = $_POST["batch_no"];


                $sql = $mysqli->prepare("select count(id) as cnt from batch_creation where batch_no = '".$batch_no."' and std_app_no = '".$std_app_no."'");
        $sql->execute();
        $result = $sql->get_result();
        $row = $result->fetch_assoc();

        if($row['cnt'] > '0'){

            $update_sql = "UPDATE batch_creation SET 
            s1_unique_id = ?, 
            applied_date = ?, 
            hostel_name = ?, 
            hostel_taluk = ?, 
            hostel_district = ?, 
            std_name = ?, 
            academic_year = ?, 
            batch_cr_date = ?, 
            unique_id = ?
        WHERE std_app_no = ? AND batch_no = ?";

$update_stmt = $mysqli->prepare($update_sql);
$update_stmt->bind_param(
"sssssssssss",
$s1_unique_id,
$applied_date,
$hostel_name,
$hostel_taluk,
$hostel_district,
$std_name,
$academic_year,
date('Y-m-d'),
unique_id($prefix),
$std_app_no,
$batch_no
);
$update_result = $update_stmt->execute();


        }else{

        // Prepare INSERT query and parameters
        $insert_sql = "INSERT INTO batch_creation 
                    (s1_unique_id, applied_date, hostel_name, hostel_taluk, hostel_district, std_name, std_app_no, academic_year, batch_cr_date, batch_no, unique_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $insert_stmt = $mysqli->prepare($insert_sql);
        $insert_stmt->bind_param(
            "sssssssssss",
            $s1_unique_id,
            $applied_date,
            $hostel_name,
            $hostel_taluk,
            $hostel_district,
            $std_name,
            $std_app_no,
            $academic_year,
            date('Y-m-d'),
            $batch_no,
            unique_id($prefix)
        );
        $insert_result = $insert_stmt->execute();
    }
        // Prepare UPDATE query and parameters
        $update_sql = "UPDATE std_app_s 
                    SET batch_cr_date = ?, batch_no = ? 
                    WHERE is_delete = '0' AND unique_id = ?";

        $update_stmt = $mysqli->prepare($update_sql);
        $update_stmt->bind_param("sss", date('Y-m-d'), $batch_no, $s1_unique_id);
        $update_result = $update_stmt->execute();

        // Construct JSON response based on results
        if ($update_result) {
            $status = 1;
            $msg = "create";
            $data = [];
            $error = "";
        } else {
            $status = 0;
            $msg = "error";
            $data = [];
            $error = "Failed to add batch and update application.";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);
        break;

case 'main_batch_add':
        // Get POST data
       
        $hostel_name = $_POST["hostel_name"];
        $academic_year = $_POST["academic_year"];
        $batch_no = $_POST["batch_no"];

        
        // Prepare INSERT query and parameters
        $insert_sql = "INSERT INTO batch_main 
                    (academic_year, batch_no, hostel_name, batch_cr_date) 
                    VALUES (?, ?, ?, ?)";

        $insert_stmt = $mysqli->prepare($insert_sql);
        $insert_stmt->bind_param(
            "ssss",
            $academic_year,
            $batch_no,
            $hostel_name,
            date('Y-m-d')
        );
        $insert_result = $insert_stmt->execute();

       
        if ($insert_result) {
            $status = 1;
            $msg = "create";
            $data = [];
            $error = "";
        } else {
            $status = 0;
            $msg = "error";
            $data = [];
            $error = "Failed to add batch and update application.";
        }

        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);
        break;




    case 'batch_detail_datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];

        $batch_no = $_POST['batch_no'];
        $is_delete = 0;
        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "batch_no",
            "std_app_no",
            "(SELECT std_name FROM std_app_s2 WHERE std_app_s2.s1_unique_id = std_app_s.unique_id) AS std_name",
"'' as std_umis_emis_no",
            "status",
            "unique_id"
        ];
        $table = "std_app_s";
        $table_with_vars = "$table, (SELECT @a:=?) AS a";
        $where = "is_delete = ? AND batch_no = ?";

        // Datatable Searching
        $search_query = datatable_searching($search, $columns);
        if ($search_query) {
            $where .= " AND " . $search_query;
        }


function fetchStdEmisNo($mysqli, $s1_unique_id)
        {
            $sql = "SELECT std_name FROM std_app_emis_s3 WHERE s1_unique_id = ? LIMIT 1";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $s1_unique_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return strtoupper($row['std_name']);
            }

            return ''; // Return empty string if no result
        }

        function fetchUmisName($mysqli, $s1_unique_id)
        {
            $sql = "SELECT umis_name FROM std_app_umis_s4 WHERE s1_unique_id = ? LIMIT 1";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $s1_unique_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return strtoupper($row['umis_name']);
            }

            return ''; // Return empty string if no result
        }

        function fetchNoUmisName($mysqli, $s1_unique_id)
        {
            $sql = "SELECT no_umis_name FROM std_app_umis_s4 WHERE s1_unique_id = ? LIMIT 1";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $s1_unique_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return strtoupper($row['no_umis_name']);
            }

            return ''; // Return empty string if no result
        }
        $sql_function = "SQL_CALC_FOUND_ROWS";
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_with_vars WHERE $where";

        if ($limit != "") {
            $sql .= " LIMIT ?, ?";
        }

        // Prepare and execute the query
        if (!$stmt = $mysqli->prepare($sql)) {
            error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
            echo json_encode(["error" => "Internal Server Error"]);
            exit;
        }

        // Bind parameters
        if ($limit != "") {
            $stmt->bind_param('iisii', $start, $is_delete, $batch_no, $start, $limit);
        } else {
            $stmt->bind_param('iis', $start, $is_delete, $batch_no);
        }

        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            echo json_encode(["error" => "Internal Server Error"]);
            exit;
        }

        $result = $stmt->get_result();

        // Get total records count
        if (!$result_total = $mysqli->query("SELECT FOUND_ROWS() AS total")) {
            error_log("Query for total records failed: (" . $mysqli->errno . ") " . $mysqli->error);
            echo json_encode(["error" => "Internal Server Error"]);
            exit;
        }

        $total_records = $result_total->fetch_assoc()['total'];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {


$std_emis_name = fetchStdEmisNo($mysqli, $row['unique_id']);
                $umis_name = fetchUmisName($mysqli, $row['unique_id']);
                $no_umis_name = fetchNoUmisName($mysqli, $row['unique_id']);

                if (!empty($std_emis_name)) {
                    $row['std_umis_emis_no'] = $std_emis_name;
                } elseif (!empty($umis_name)) {
                    $row['std_umis_emis_no'] = $umis_name;
                } elseif (!empty($no_umis_name)) {
                    $row['std_umis_emis_no'] = $no_umis_name;
                } else {
                    $row['std_umis_emis_no'] = ''; // Default value if none found
                }
                switch ($row['status']) {
                    case 0:
                        $row['status'] = 'Pending';
                        break;
                    case 1:
                        $row['status'] = 'Approved';
                        break;
                    case 2:
                        $row['status'] = 'Rejected';
                        break;
                }

                $unique_id = $row['unique_id'];
                $row['unique_id'] = '<a class="btn btn-action specl2-icon" href="javascript:batch_print(\'' . $unique_id . '\')"><button type="button"><i class="fa fa-eye"></i></button></a>';

                $data[] = array_values($row);
            }


        }
        // else {
        //     $json_array = [
        //         "draw" => intval($draw),
        //         "recordsTotal" => 0,
        //         "recordsFiltered" => 0,
        //         "data" => [],
        //     ];
        // }

        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

        $json_array = [
            "draw" => $draw,
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data,
        ];
        echo json_encode($json_array);
        $stmt->close();
        break;



    case 'update_status':
        $batch_no = $_POST['unique_id'];
        $print_status = $_POST['print_status'];

        // Database connection setup (assuming already established)
        $host = "localhost";
        $username = "root";
        $password = "4/rb5sO2s3TpL4gu";
        $databasename = "adi_dravidar";

        $mysqli = new mysqli($host, $username, $password, $databasename);

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Define the SQL statement
        $sql = "";
        $params = [];

        if ($print_status == 1) {
            $sql = "UPDATE batch_creation SET print_status = ?, print_time = ? WHERE batch_no = ?";
            $params = [$print_status, date('Y-m-d H:i:s'), $batch_no];
        } elseif ($print_status == 2) {
            $sql = "UPDATE batch_creation SET print_status = ?, submitted_time = ? WHERE batch_no = ?";
            $params = [$print_status, date('Y-m-d H:i:s'), $batch_no];
        } else {
            // Invalid print_status value, handle the error
            $json_array = [
                "status" => "error",
                "data" => null,
                "error" => "Invalid print_status value",
                "msg" => "Invalid print_status value provided",
                "sql" => ""
            ];
            echo json_encode($json_array);
            break;
        }

        // Prepare and execute the statement
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        $types = 'iss'; // Assuming print_status is integer and batch_no is string/integer
        $stmt->bind_param($types, ...$params);

        // Execute the statement
        $stmt->execute();

        // Check for errors or success
        if ($stmt->affected_rows > 0) {
            $status = "success";
            $msg = "Updated all records with batch number: $batch_no";
            $data = []; // You can set relevant data if needed
            $error = "";
        } else {
            $status = "error";
            $msg = "Failed to update records";
            $data = [];
            $error = $mysqli->error;
        }

        // Close statement and connection
        $stmt->close();
        // $mysqli->close();

        // Construct JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;



    default:

        break;
}

function total_count($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "batch_creation";
    $where = [];
    $table_columns = [
        "count(id) as tot_cnt",
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
        // $where              = [];
        $where["batch_no"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['tot_cnt'];
    } else {
        // print_r($amc_name_list);
        return 0;
    }
}

function app_count($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "batch_creation";
    $where = [];
    $table_columns = [
        "count(id) as tot_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        "status" => 1
    ];

    if ($unique_id) {
        // $where              = [];
        $where["batch_no"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['tot_cnt'];
    } else {
        // print_r($amc_name_list);
        return 0;
    }



}

function rej_count($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "batch_creation";
    $where = [];
    $table_columns = [
        "count(id) as tot_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        "status" => 2
    ];

    if ($unique_id) {
        // $where              = [];
        $where["batch_no"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['tot_cnt'];
    } else {
        // print_r($amc_name_list);
        return 0;
    }
}

function batch_no($academic_year = "")
{
    // $date = date("Y");
    // $st_date = substr($date, 4);

    $servername = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $database_name = "adi_dravidar";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
    } catch (PDOException $e) {
        // echo "Connection failed: " . $e->getMessage();
    }

    $acmc_year = academic_year($academic_year)[0]['amc_year'];
    $a = str_split($acmc_year);
    $splt_acc_yr = $a[0] . $a[1] . $a[2] . $a[3];
    //  $hostel_id = '65584660e85as2403119'; 
    //  $hos_id = substr($hostel_id,);




    // echo "SELECT * FROM apply_application_form where application_no LIKE 'APN%' order by id desc";echo "<br>";
    $stmt = $conn->query("SELECT max(batch_no) as batch_no FROM batch_creation where is_delete = '0' and hostel_name = '" . $hostel_id . "' order by id desc");


    // if($res1=$stmt->fetch($stmt))
    if ($res1 = $stmt->fetch()) {
        if ($res1['batch_no'] != '') {


            $pur_array = explode("-", $res1['batch_no']);


            //  echo $pur_array[1];

            $booking_no = $pur_array[1];
        }
        // else{
        //     $booking_no  = '';
        // }

    }
    //  $booking_nos = 'APN-' . $splt_acc_yr .'-' . ''. $splt_dis .'-' .'' .$splt_zone.'-' .'' .$splt_host.'-' .'-0001';
    if ($booking_no == '') {
        // echo "ff";
        $booking_nos = $splt_acc_yr . 'BAT-' . '0001';
    }
    // else if ($year != date("Y")){
    //     $booking_nos = 'APN-'.date('Y'). $splt_acc_yr . ''.$splt_dis.'' .$splt_zone.'' .$splt_host.'-0001';
    // }
    else {
        $booking_no += 1;

        $booking_nos = $splt_acc_yr . 'BAT-' . str_pad($booking_no, 4, '0', STR_PAD_LEFT);
    }

    return $booking_nos;
}



