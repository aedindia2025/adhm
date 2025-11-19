<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "batch_creation";
$table2 = "std_app_s";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';


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
            "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = ($length == '-1') ? "" : intval($length);
        $data = [];


        if (!empty($_POST['academic_year'])) {
            $where_filter .= " and academic_year = '" . $_POST['academic_year'] . "'";
        }

        if (!empty($_POST['hostel_name'])) {
            $where_filter .= " and hostel_name = '" . $_POST['hostel_name'] . "'";
        }

        if (!empty($_POST['batch_no'])) {
            $where_filter .= " and batch_no = '" . $_POST['batch_no'] . "'";
        }

        // Query Variables
        $columns = [
            "'' AS s_no",
            "batch_cr_date",
            "hostel_name",
            "batch_no",
            "'' AS total_cnt",
            "'' AS app_cnt",
            "'' AS rej_cnt",
            "rec_status",
            "batch_status",
            "batch_sub_date",
            "batch_no AS unique_id",
            "print_status"
        ];
        $sql_columns = implode(", ", $columns);
        $table_with_counter = "{$table}, (SELECT @a:=?) AS a";
        $where = "is_delete = ? AND hostel_district = ? $where_filter";
        $order_by = "";
        $group_by = "batch_no";

        // Prepare SQL query
        $sql_query = "SELECT SQL_CALC_FOUND_ROWS {$sql_columns} FROM {$table_with_counter} WHERE {$where} GROUP BY {$group_by}";
        if ($limit !== "") {
            $sql_query .= " LIMIT ?, ?";
        }

        // Execute query with parameterized statements
        $stmt = $mysqli->prepare($sql_query);
        if ($stmt) {
            // Bind parameters
            $params = [$start, 0, $_SESSION['district_id']];
            if ($limit !== "") {
                $params[] = $start;
                $params[] = $limit;
            }

            // Bind parameters dynamically
            $types = str_repeat('i', count($params));
            $stmt->bind_param($types, ...$params);

            // Execute the query
            $stmt->execute();

            // Fetch the result
            $res = $stmt->get_result();

            if ($res) {
                $s_no = $start + 1;
                while ($value = $res->fetch_assoc()) {
                    $value['s_no'] = $s_no++;
                    $hostel_name = $value['hostel_name'];
                    $value['hostel_name'] = hostel_name($hostel_name)[0]['hostel_name'];
                    $value['total_cnt'] = total_count($value['unique_id']);
                    $value['app_cnt'] = app_count($value['unique_id']);
                    $value['rej_cnt'] = rej_count($value['unique_id']);

                    if ($value['batch_status'] == 0) {
                        $value['batch_status'] = '<b><span style="color:red;">Pending</span></b>';
                    } elseif ($value['batch_status'] == 1) {
                        $value['batch_status'] = '<b><span style="color:orange;">Partially Completed</span></b>';
                    } elseif ($value['batch_status'] == 2) {
                        $value['batch_status'] = '<b><span style="color:green;">Completed</span></b>';
                    }

                    $value['batch_sub_date'] = $value['batch_sub_date'] ? (new DateTime($value['batch_sub_date']))->format('d-m-Y') : '-';

                    $btn_view = btn_print_approval($folder_name, $value['batch_no'], 'approval', "", "", $hostel_name);
                    $value['unique_id'] = $btn_view;

                    if ($value['rec_status'] == 1) {
                        $value['rec_status'] = '<span style="color:green;">Received !!</span>';
                    } elseif ($value['print_status'] == '2') {
                        $button = '<button onclick="rec_status(\'' . $value['batch_no'] . '\')" style="background-color: #3bb6e9; border: none; color: white; padding: 5px 10px; text-align: center; text-decoration: none; display: inline-block; font-size: 12px; border-radius: 4px; cursor: pointer;" >Received</button>';
                        $value['rec_status'] = $button;
                    } else {
                        $value['rec_status'] = '<span style="color:Red;">Pending</span>';
                    }

                    $data[] = array_values($value);
                }

                // Fetch the total filtered records
                $stmt_filtered = $mysqli->query("SELECT FOUND_ROWS()");
                $total_filtered = $stmt_filtered->fetch_row()[0];

                // Prepare JSON response
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => intval($total_filtered),
                    "recordsFiltered" => intval($total_filtered),
                    "data" => $data,
                ];
            } else {
                // Handle query result error
                error_log("Error fetching results: " . $mysqli->error);
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => 0,
                    "recordsFiltered" => 0,
                    "data" => [],
                ];
            }

            // Close statement
            $stmt->close();
        } else {
            // Handle query prepare error
            error_log("MySQLi error: " . $mysqli->error);
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ];
        }

        // Output JSON response
        echo json_encode($json_array);
        break;



    case 'approval_datatable':
        $batch_no = $_POST["batch_no"];
        $sanc_cnt = $_POST["sanc_cnt"];

        // DataTable Variables
        $length = isset($_POST['length']) ? intval($_POST['length']) : -1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
        $limit = ($length == '-1') ? "" : intval($length);
        $data = [];

        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "4/rb5sO2s3TpL4gu";
        $dbname = "adi_dravidar";

        // Create MySQLi connection
        $mysqli = new mysqli($servername, $username, $password, $dbname);

        function fetchStdEmisNo($mysqli, $s1_unique_id)
        {
            $sql = "SELECT std_name FROM std_app_emis_s3 WHERE s1_unique_id = ?";
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
            $sql = "SELECT umis_name FROM std_app_umis_s4 WHERE s1_unique_id = ?";
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
            $sql = "SELECT no_umis_name FROM std_app_umis_s4 WHERE s1_unique_id = ?";
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



        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Query Variables
        $columns = [
            "'' AS s_no",
            "std_app_no",
            "std_name",
            "(select COALESCE(emis_name,umis_name,no_umis_name) from std_app where std_app.s1_unique_id = std_app_s.unique_id) as emis_umis_name",
            "'' as name_diff",
            "std_to_hostel_distance",
            "std_to_inst_distance",
            "(SELECT community_pdf FROM std_app_s5 WHERE std_app_s5.s1_unique_id = std_app_s.unique_id) AS community_pdf",
            "(SELECT income_pdf FROM std_app_s5 WHERE std_app_s5.s1_unique_id = std_app_s.unique_id) AS income_pdf",
            "unique_id",
            "unique_id AS unq_id",
            "status",
            "batch_no",
            "(SELECT c_file_name FROM std_app_s5 WHERE std_app_s5.s1_unique_id = std_app_s.unique_id) AS std_community_pdf",
            "(SELECT i_file_name FROM std_app_s5 WHERE std_app_s5.s1_unique_id = std_app_s.unique_id) AS std_income_pdf",
            "hostel_1",
        ];

        $table_details = "{$table2} , (SELECT @a := ?) AS a";
        $where = "is_delete = ? AND batch_no = ?";
        $order_by = "";
        $is_delete = "0";

        // Prepare SQL query
        $sql_query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM " . $table_details . " WHERE " . $where;
        if ($limit !== "") {
            $sql_query .= " LIMIT ?, ?";
        }

        // Prepare and bind parameters
        $stmt = $mysqli->prepare($sql_query);
        if ($stmt) {
            // Dynamically bind parameters
            if ($limit !== "") {
                $stmt->bind_param("issii", $start, $is_delete, $batch_no, $start, $limit);
            } else {
                $stmt->bind_param("iss", $start, $is_delete, $batch_no);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch the data
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            if ($res_array) {
                $s_no = $start + 1;

                foreach ($res_array as $value) {
                    $value['s_no'] = $s_no++;

                    $jac_similarity = jaccardSimilarity($value['std_name'], $value['emis_umis_name']);

                    $score = floatval($jac_similarity);

                    if ($score == 1) {
                        $name_status = '<span style="color: green; font-weight: bold;">Matched</span>';
                        $name_diff = 'matched';
                    } elseif ($score >= 0.6) {
                        $name_status = '<span style="color: orange; font-weight: bold;">Partially Matched</span>';
                        $name_diff = 'partially_matched';
                    } else {
                        $name_status = '<span style="color: red; font-weight: bold;">Mismatched</span>';
                        $name_diff = 'mismatched';
                    }
                    $value['name_diff'] = $name_status;


                    if ($value['std_to_inst_distance']) {
                        $inst_distance_check = floor($value['std_to_inst_distance']);

                        if ($inst_distance_check < 5) {
                            $value['std_to_inst_distance'] = '<span style="color: red; font-weight: bold;">' . $inst_distance_check . ' km</span>';
                        } else if ($inst_distance_check >= 5) {
                            $value['std_to_inst_distance'] = '<span style="color: green; font-weight: bold;">' . $inst_distance_check . ' km</span>';
                        } else {
                            $value['std_to_inst_distance'] = '-';
                        }
                    } else {
                        $value['std_to_inst_distance'] = '-';
                    }

                    if ($value['std_to_hostel_distance']) {
                        $host_distance_check = floor($value['std_to_hostel_distance']);

                        if ($host_distance_check < 5) {
                            $value['std_to_hostel_distance'] = '<span style="color: red; font-weight: bold;">' . $host_distance_check . ' km</span>';
                        } else if ($host_distance_check >= 5) {
                            $value['std_to_hostel_distance'] = '<span style="color: green; font-weight: bold;">' . $host_distance_check . ' km</span>';
                        } else {
                            $value['std_to_hostel_distance'] = '-';
                        }
                    } else {
                        $value['std_to_hostel_distance'] = '-';
                    }


                    if ($value['status'] == 0) {
                        $acceptButton = '<button class="accept-btn" style="background-color: green; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;" data-hostel-name="' . $value['hostel_1'] . '" data-batch-no="' . $value['batch_no'] . '" data-sanc-cnt="' . $sanc_cnt . '" data-unique-id="' . $value['unique_id'] . '"  data-name_diff="' . $name_diff . '" data-inst_distance_check="' . $inst_distance_check . '" >Accept</button>';
                        $rejectButton = '<button class="reject-btn" style="background-color: red; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;" data-hostel-name="' . $value['hostel_1'] . '" data-batch-no="' . $value['batch_no'] . '" data-unique-id="' . $value['unique_id'] . '"  data-name_diff="' . $name_diff . '" data-inst_distance_check="' . $inst_distance_check . '" data-sanc-cnt="' . $sanc_cnt . '" >Reject</button>';
                        $value['unique_id'] = $acceptButton . ' ' . $rejectButton;
                    } elseif ($value['status'] == 1) {
                        $value['unique_id'] = '<span style="color: green;">Accepted</span>';
                    } elseif ($value['status'] == 2) {
                        $value['unique_id'] = '<span style="color: red;">Rejected</span>';
                    }

                    $community_pdf = $value['std_community_pdf'];

                    $income_pdf = $value['std_income_pdf'];

                    if (!empty($community_pdf)) {
                        $value['community_pdf'] = image_view($community_pdf) . '<br>' . '&nbsp&nbsp&nbsp&nbsp' . '<span style="color:blue;">(Manually Uploaded)</span>';
                    } else {
                        $value['community_pdf'] = '<a href="' . $value['community_pdf'] . '"><img src="assets/images/pdf.png" width="35px;" height="35px;"></a>';
                    }

                    if (!empty($income_pdf)) {

                        $value['income_pdf'] = image_view($income_pdf) . '<br>' . '&nbsp&nbsp&nbsp&nbsp' . '<span style="color:blue;">(Manually Uploaded)</span>';
                    } else {
                        $value['income_pdf'] = '<a href="' . $value['income_pdf'] . '"><img src="assets/images/pdf.png" width="35px;" height="35px;"></a>';
                    }


                    $value['unq_id'] = '<a class="btn btn-action specl2" href="javascript:void(0);" onclick="print_new(\'' . $value['unq_id'] . '\')">
                                    <button type="button"><i class="fa fa-eye"></i></button>
                                </a>';

                    $data[] = array_values($value);
                }

                // Fetch total filtered records
                $total_filtered = $mysqli->query("SELECT FOUND_ROWS()")->fetch_row()[0];

                // Prepare JSON response
                $json_array = [
                    "draw" => intval($draw),
                    "recordsTotal" => intval($total_filtered),
                    "recordsFiltered" => intval($total_filtered),
                    "data" => $data,
                ];
            }

            $stmt->close();
        } else {
            // Handle query error
            error_log("MySQLi error: " . $mysqli->error);
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ];
        }

        // Close MySQLi connection
        $mysqli->close();

        // Output JSON response
        echo json_encode($json_array);
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
            "sql" => $sql
        ];

        echo json_encode($json_array);
        break;

    case 'at_accept':

        $table3 = 'std_app_s7';
        $unique_id = $_POST['uniqueId'];
        $batch_no = $_POST['batchNo'];
        $hostel_name = $_POST['hostelId'];
        $sanc_cnt = $_POST['sanc_cnt'];
        if ($_POST['reason']) {
            $reason = implode(',', $_POST['reason']);
        }
        $tot_reg_cnt = total_reg_count($hostel_name);

        if ($tot_reg_cnt < $sanc_cnt) {
            $status_upd_date = date('Y-m-d');
            // Update for $table
            $stmt = $mysqli->prepare("UPDATE $table SET status = ?, status_upd_date = ?, reason = ? WHERE s1_unique_id = ? AND batch_no = ?");
            $stmt->bind_param("sssss", $status, $status_upd_date, $reason, $unique_id, $batch_no);
            $status = 1;
            $stmt->execute();
            $action_obj = $stmt->affected_rows;
            $stmt->close();

            // Update for $table2
            $stmt2 = $mysqli->prepare("UPDATE $table2 SET status = ?, status_upd_date = ?  WHERE unique_id = ? AND batch_no = ?");
            $stmt2->bind_param("ssss", $status2, $status_upd_date, $unique_id, $batch_no);
            $status2 = 1;
            $stmt2->execute();
            $action_obj_table2 = $stmt2->affected_rows;
            $stmt2->close();

            // Update for $table3
            $stmt3 = $mysqli->prepare("UPDATE $table3 SET status = ?, reason = ? WHERE s1_unique_id = ? AND hostel_name = ? AND is_delete = '0'");
            $stmt3->bind_param("ssss", $status3, $reason, $unique_id, $hostel_name);
            $status3 = 1;
            $stmt3->execute();
            $action_obj_table3 = $stmt3->affected_rows;
            $stmt3->close();

            // Handle errors if needed
            if ($action_obj === false || $action_obj_table2 === false || $action_obj_table3 === false) {
                $status = false; // Assuming $status is used to track success/failure
                $error = $mysqli->error;
                $msg = "error";
            } else {
                $status = true; // Assuming $status is used to track success/failure
                $msg = "success"; // Assuming this is the success message
            }
        } else {
            $msg = "sanc_cnt_exceed";
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


        $table3 = 'std_app_s7'; // Replace with your actual table name

        $unique_id = $_POST['uniqueId'];
        $batch_no = $_POST['batchNo'];
        $reason = implode(',', $_POST['reason']);
        $hostel_name = $_POST['hostelId'];

        $status_upd_date = date('Y-m-d');

        // Update for $table
        $stmt = $mysqli->prepare("UPDATE $table SET status = ?, reason = ?, status_upd_date = ? WHERE s1_unique_id = ? AND batch_no = ?");
        $stmt->bind_param("sssss", $status, $reason, $status_upd_date, $unique_id, $batch_no);
        $status = 2;
        $stmt->execute();
        $action_obj = $stmt->affected_rows;
        $stmt->close();

        // Update for $table2
        $stmt2 = $mysqli->prepare("UPDATE $table2 SET status = ?, status_upd_date = ? WHERE unique_id = ? AND batch_no = ?");
        $stmt2->bind_param("ssss", $status2, $status_upd_date, $unique_id, $batch_no);
        $status2 = 2;
        $stmt2->execute();
        $action_obj_table2 = $stmt2->affected_rows;
        $stmt2->close();

        // Update for $table3
        $stmt3 = $mysqli->prepare("UPDATE $table3 SET status = ?, reason = ? WHERE s1_unique_id = ? AND hostel_name = ?");
        $stmt3->bind_param("ssss", $status3, $reason, $unique_id, $hostel_name);
        $status3 = 2;
        $stmt3->execute();
        $action_obj_table3 = $stmt3->affected_rows;
        $stmt3->close();

        // Handle errors if needed
        if ($action_obj === false || $action_obj_table2 === false || $action_obj_table3 === false) {
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


    // case 'register':

    //     $batch_no = $_POST["batch_no"];
    //     $data_get_table = "batch_creation";
    //     $table_hostel = "hostel_name";

    //     $columns_reg = [
    //         "batch_no",
    //         "std_app_no",
    //         "std_name",
    //         "unique_id",
    //         "hostel_name",
    //         "s1_unique_id"
    //     ];
    //     $table_details_get = [
    //         $data_get_table,
    //         $columns_reg
    //     ];

    //     $get_whare = "status= 1 and batch_no = '" . $batch_no . "'";

    //     $result = $pdo->select($table_details_get, $get_whare);

    //     // print_r($result);die();
    //     $total_records = total_records();

    //     if ($result->status) {

    //         $table_update = "std_reg_s";
    //         $res_array = $result->data;

    //         foreach ($res_array as $key => $value) {

    //             $table_details = [
    //                 $table_update,
    //                 [
    //                     "COUNT(unique_id) AS count"
    //                 ]
    //             ];
    //             $select_where = 'unique_id = "' . $value['s1_unique_id'] . '"  AND is_delete = 0  ';

    //             $action_obj = $pdo->select($table_details, $select_where);

    //             if ($action_obj->status) {
    //                 $status = $action_obj->status;
    //                 $data = $action_obj->data;
    //                 $error = "";
    //                 $sql = $action_obj->sql;
    //             } else {
    //                 $status = $action_obj->status;
    //                 $data = $action_obj->data;
    //                 $error = $action_obj->error;
    //                 $sql = $action_obj->sql;
    //                 $msg = "error";
    //             }
    //             if ($data[0]["count"]) {
    //                 $msg = "already";
    //             } else if ($data[0]["count"] == 0) {

    //                 // Fetch data from std_app_p2 based on unique_id
    //                 $table_details_p1 = [
    //                     "std_app_s",
    //                     ["*"]
    //                 ];
    //                 $select_where_p1 = 'unique_id = "' . $value['s1_unique_id'] . '"';
    //                 $action_obj_p1 = $pdo->select($table_details_p1, $select_where_p1);

    //                 if ($action_obj_p1->status) {
    //                     $status_p1 = $action_obj_p1->status;
    //                     $data_p1 = $action_obj_p1->data;
    //                     $error_p1 = "";
    //                     $sql_p1 = $action_obj_p1->sql;

    //                     // Insert fetched data into std_reg_p1
    //                     if (!empty($data_p1)) {
    //                         foreach ($data_p1 as $row_p1) {
    //                             unset($row_p1['id']);

    //                             $std_reg_no = reg_no($academic_year, $value['s1_unique_id']);

    //                             $row_p1['user_name'] = $std_reg_no;
    //                             $row_p1['std_reg_no'] = $std_reg_no;

    //                             $table_details_fetch = [
    //                                 "std_app_s6",
    //                                 ["dob"]
    //                             ];

    //                             $select_where_fetch = 's1_unique_id = "' . $value['s1_unique_id'] . '"';

    //                             $action_obj_fetch = $pdo->select($table_details_fetch, $select_where_fetch);

    //                             if ($action_obj_fetch->status && !empty($action_obj_fetch->data)) {
    //                                 $std_dob = $action_obj_fetch->data[0]['dob'];

    //                                 // Append std_dob to password and confirm_password fields
    //                                 $row_p1['password'] .= date('d/m/Y', strtotime($std_dob));
    //                                 $row_p1['confirm_password'] .= date('d/m/Y', strtotime($std_dob));
    //                                 // $row_p1['enc_password'] .= md5(date('d/m/Y', strtotime($std_dob)));
    //                                 $row_p1['enc_password'] = hash('sha256', $row_p1['password']);
    //                             } else {
    //                                 // Handle if std_dob not found or any error occurs
    //                                 $status_p1 = $action_obj_fetch->status;
    //                                 $error_p1 = $action_obj_fetch->error;
    //                                 $msg_p1 = "error";
    //                                 break; // Exit loop if error occurs
    //                             }

    //                             $action_obj_p1 = $pdo->insert("std_reg_s", $row_p1); // Insert each row
    //                             if (!$action_obj_p1->status) {
    //                                 $status_p1 = $action_obj_p1->status;
    //                                 $error_p1 = $action_obj_p1->error;
    //                                 $msg_p1 = "error";
    //                                 break;
    //                             }
    //                         }
    //                         if ($action_obj_p1->status) {
    //                             $msg_p1 = "inserted";
    //                         }
    //                     } else {
    //                         $msg_p1 = "no_data_found";
    //                     }
    //                 } else {
    //                     $status_p1 = $action_obj_p1->status;
    //                     $error_p1 = $action_obj_p1->error;
    //                     $sql_p1 = $action_obj_p1->sql;
    //                     $msg_p1 = "error";
    //                 }


    //                 $table_details = [
    //                     "std_app_s2",
    //                     ["*"]
    //                 ];
    //                 $select_where = 's1_unique_id = "' . $value['s1_unique_id'] . '"';
    //                 $action_obj = $pdo->select($table_details, $select_where);

    //                 if ($action_obj->status) {
    //                     $status = $action_obj->status;
    //                     $data = $action_obj->data;
    //                     $error = "";
    //                     $sql = $action_obj->sql;

    //                     // Insert fetched data into std_reg_p2
    //                     if (!empty($data)) {
    //                         foreach ($data as $row) {
    //                             unset($row['id']);
    //                             //$row['unique_id'] = $common_unique_id;

    //                             $action_obj = $pdo->insert("std_reg_s2", $row); // Insert each row
    //                             if (!$action_obj->status) {
    //                                 $status = $action_obj->status;
    //                                 $error = $action_obj->error;
    //                                 $msg = "error";
    //                                 break;
    //                             }
    //                         }
    //                         if ($action_obj->status) {
    //                             $msg = "inserted";
    //                         }
    //                     } else {
    //                         $msg = "no_data_found";
    //                     }
    //                 } else {
    //                     $status = $action_obj->status;
    //                     $error = $action_obj->error;
    //                     $sql = $action_obj->sql;
    //                     $msg = "error";
    //                 }

    //                 // Fetch data from std_app_p3 based on unique_id
    //                 $table_details_p3 = [
    //                     "std_app_emis_s3",
    //                     ["*"]
    //                 ];
    //                 $select_where_p3 = 's1_unique_id = "' . $value['s1_unique_id'] . '"';
    //                 $action_obj_p3 = $pdo->select($table_details_p3, $select_where_p3);

    //                 if ($action_obj_p3->status) {
    //                     $status_p3 = $action_obj_p3->status;
    //                     $data_p3 = $action_obj_p3->data;
    //                     $error_p3 = "";
    //                     $sql_p3 = $action_obj_p3->sql;

    //                     // Insert fetched data into std_reg_p3
    //                     if (!empty($data_p3)) {
    //                         foreach ($data_p3 as $row_p3) {
    //                             unset($row_p3['id']);
    //                             //$row['unique_id'] = $common_unique_id;

    //                             $action_obj_p3 = $pdo->insert("std_reg_emis_s3", $row_p3); // Insert each row
    //                             if (!$action_obj_p3->status) {
    //                                 $status_p3 = $action_obj_p3->status;
    //                                 $error_p3 = $action_obj_p3->error;
    //                                 $msg_p3 = "error";
    //                                 break;
    //                             }
    //                         }
    //                         if ($action_obj_p3->status) {
    //                             $msg_p3 = "inserted";
    //                         }
    //                     } else {
    //                         $msg_p3 = "no_data_found";
    //                     }
    //                 } else {
    //                     $status_p3 = $action_obj_p3->status;
    //                     $error_p3 = $action_obj_p3->error;
    //                     $sql_p3 = $action_obj_p3->sql;
    //                     $msg_p3 = "error";
    //                 }

    //                 $table_details_p4 = [
    //                     "std_app_umis_s4",
    //                     ["*"]
    //                 ];
    //                 $select_where_p4 = 's1_unique_id = "' . $value['s1_unique_id'] . '"';
    //                 $action_obj_p4 = $pdo->select($table_details_p4, $select_where_p4);

    //                 if ($action_obj_p4->status) {
    //                     $status_p4 = $action_obj_p4->status;
    //                     $data_p4 = $action_obj_p4->data;
    //                     $error_p4 = "";
    //                     $sql_p4 = $action_obj_p4->sql;

    //                     // Insert fetched data into std_reg_p4
    //                     if (!empty($data_p4)) {
    //                         foreach ($data_p4 as $row_p4) {
    //                             unset($row_p4['id']);
    //                             //$row['unique_id'] = $common_unique_id;

    //                             $action_obj_p4 = $pdo->insert("std_reg_umis_s4", $row_p4); // Insert each row
    //                             if (!$action_obj_p4->status) {
    //                                 $status_p4 = $action_obj_p4->status;
    //                                 $error_p4 = $action_obj_p4->error;
    //                                 $msg_p4 = "error";
    //                                 break;
    //                             }
    //                         }
    //                         if ($action_obj_p4->status) {
    //                             $msg_p4 = "inserted";
    //                         }
    //                     } else {
    //                         $msg_p4 = "no_data_found";
    //                     }
    //                 } else {
    //                     $status_p4 = $action_obj_p4->status;
    //                     $error_p4 = $action_obj_p4->error;
    //                     $sql_p4 = $action_obj_p4->sql;
    //                     $msg_p4 = "error";
    //                 }

    //                 $table_details_p5 = [
    //                     "std_app_s5",
    //                     ["*"]
    //                 ];
    //                 $select_where_p5 = 's1_unique_id = "' . $value['s1_unique_id'] . '"';
    //                 $action_obj_p5 = $pdo->select($table_details_p5, $select_where_p5);

    //                 if ($action_obj_p5->status) {
    //                     $status_p5 = $action_obj_p5->status;
    //                     $data_p5 = $action_obj_p5->data;
    //                     $error_p5 = "";
    //                     $sql_p5 = $action_obj_p5->sql;

    //                     // Insert fetched data into std_reg_p5
    //                     if (!empty($data_p5)) {
    //                         foreach ($data_p5 as $row_p5) {
    //                             unset($row_p5['id']);
    //                             //$row['unique_id'] = $common_unique_id;

    //                             $action_obj_p5 = $pdo->insert("std_reg_s5", $row_p5); // Insert each row
    //                             if (!$action_obj_p5->status) {
    //                                 $status_p5 = $action_obj_p5->status;
    //                                 $error_p5 = $action_obj_p5->error;
    //                                 $msg_p5 = "error";
    //                                 break;
    //                             }
    //                         }
    //                         if ($action_obj_p5->status) {
    //                             $msg_p5 = "inserted";
    //                         }
    //                     } else {
    //                         $msg_p5 = "no_data_found";
    //                     }
    //                 } else {
    //                     $status_p5 = $action_obj_p5->status;
    //                     $error_p5 = $action_obj_p5->error;
    //                     $sql_p5 = $action_obj_p5->sql;
    //                     $msg_p5 = "error";
    //                 }

    //                 $table_details_p6 = [
    //                     "std_app_s6",
    //                     ["*"]
    //                 ];
    //                 $select_where_p6 = 's1_unique_id = "' . $value['s1_unique_id'] . '"';
    //                 $action_obj_p6 = $pdo->select($table_details_p6, $select_where_p6);

    //                 if ($action_obj_p6->status) {
    //                     $status_p6 = $action_obj_p6->status;
    //                     $data_p6 = $action_obj_p6->data;
    //                     $error_p6 = "";
    //                     $sql_p6 = $action_obj_p6->sql;

    //                     // Insert fetched data into std_reg_p6
    //                     if (!empty($data_p6)) {
    //                         foreach ($data_p6 as $row_p6) {
    //                             unset($row_p6['id']);
    //                             //$row['unique_id'] = $common_unique_id;

    //                             $action_obj_p6 = $pdo->insert("std_reg_s6", $row_p6); // Insert each row
    //                             if (!$action_obj_p6->status) {
    //                                 $status_p6 = $action_obj_p6->status;
    //                                 $error_p6 = $action_obj_p6->error;
    //                                 $msg_p6 = "error";
    //                                 break;
    //                             }
    //                         }
    //                         if ($action_obj_p6->status) {
    //                             $msg_p6 = "inserted";
    //                         }
    //                     } else {
    //                         $msg_p6 = "no_data_found";
    //                     }
    //                 } else {
    //                     $status_p6 = $action_obj_p6->status;
    //                     $error_p6 = $action_obj_p6->error;
    //                     $sql_p6 = $action_obj_p6->sql;
    //                     $msg_p6 = "error";
    //                 }

    //                 $table_details_p7 = [
    //                     "std_app_s7",
    //                     ["*"]
    //                 ];
    //                 $select_where_p7 = 's1_unique_id = "' . $value['s1_unique_id'] . '"';
    //                 $action_obj_p7 = $pdo->select($table_details_p7, $select_where_p7);

    //                 if ($action_obj_p7->status) {
    //                     $status_p7 = $action_obj_p7->status;
    //                     $data_p7 = $action_obj_p7->data;
    //                     $error_p7 = "";
    //                     $sql_p7 = $action_obj_p7->sql;

    //                     // Insert fetched data into std_reg_p7
    //                     if (!empty($data_p7)) {
    //                         foreach ($data_p7 as $row_p7) {
    //                             unset($row_p7['id']);
    //                             //$row['unique_id'] = $common_unique_id;

    //                             $action_obj_p7 = $pdo->insert("std_reg_s7", $row_p7); // Insert each row
    //                             if (!$action_obj_p7->status) {
    //                                 $status_p7 = $action_obj_p7->status;
    //                                 $error_p7 = $action_obj_p7->error;
    //                                 $msg_p7 = "error";
    //                                 break;
    //                             }
    //                         }
    //                         if ($action_obj_p7->status) {
    //                             $msg_p7 = "inserted";
    //                         }
    //                     } else {
    //                         $msg_p7 = "no_data_found";
    //                     }
    //                 } else {
    //                     $status_p7 = $action_obj_p7->status;
    //                     $error_p7 = $action_obj_p7->error;
    //                     $sql_p7 = $action_obj_p7->sql;
    //                     $msg_p7 = "error";
    //                 }

    //             }
    //         }
    //     }

    //     $password = '3sc3RLrpd17';
    //     $enc_method = 'aes-256-cbc';
    //     $enc_password = substr(hash('sha256', $password, true), 0, 32);
    //     $enc_iv = "av3DYGLkwBsErphc";

    //     $menu_screen = "registration/list";
    //     $file_name = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));

    //     $json_array = [
    //         "status" => $status && $status_p3, // combine statuses
    //         "data" => isset($data) ? $data : null,
    //         "error" => $error . " " . $error_p3, // combine errors
    //         "url" => $file_name,
    //         "msg" => $msg . " " . $msg_p3, // combine messages
    //         "sql" => $sql . " " . $sql_p3 // combine SQL queries
    //     ];

    //     echo json_encode($json_array);

    //     break;


    case 'register':

        $batch_no = $_POST["batch_no"];
        $sanc_cnt = $_POST["sanc_cnt"];
        $data_get_table = "batch_creation";
        $table_hostel = "hostel_name";

        $columns_reg = [
            "batch_no",
            "std_app_no",
            "std_name",
            "unique_id",
            "hostel_name",
            "s1_unique_id"
        ];
        $table_details_get = [
            $data_get_table,
            $columns_reg
        ];

        $get_whare = "status= 1 and batch_no = ?";

        $stmt = $mysqli->prepare("SELECT * FROM $data_get_table WHERE $get_whare");
        $stmt->bind_param("s", $batch_no);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $total_records = total_records();
        if ($result) {
            $table_update = "std_reg_s";
            $res_array = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($res_array as $key => $value) {
                $tot_reg_cnt = total_registered($value['hostel_name']);

                if ($tot_reg_cnt < $sanc_cnt) {

                    $select_where = 'unique_id = ? AND is_delete = 0';
                    $stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM $table_update WHERE $select_where");
                    $stmt->bind_param("s", $value['s1_unique_id']);
                    $stmt->execute();
                    $action_obj = $stmt->get_result();
                    $stmt->close();

                    if ($action_obj) {
                        $data = $action_obj->fetch_assoc();
                        $error = "";
                    } else {
                        $data = [];
                        $error = $mysqli->error;
                    }

                    if ($data["count"]) {
                        $msg = "already";
                    } else if ($data["count"] == 0) {

                        // Fetch data from std_app_p2 based on unique_id
                        $select_where_p1 = 'unique_id = ?';
                        $stmt = $mysqli->prepare("SELECT * FROM std_app_s WHERE $select_where_p1");
                        $stmt->bind_param("s", $value['s1_unique_id']);
                        $stmt->execute();
                        $action_obj_p1 = $stmt->get_result();
                        $stmt->close();

                        if ($action_obj_p1) {
                            $data_p1 = $action_obj_p1->fetch_all(MYSQLI_ASSOC);
                            $error_p1 = "";

                            // Insert fetched data into std_reg_p1
                            if (!empty($data_p1)) {
                                foreach ($data_p1 as $row_p1) {
                                    unset($row_p1['id']);
                                    $transfer_status = $row_p1['transfer_hostel'];



                                    if (in_array($row_p1['renewal_status'], [1, 2, 3, 4])) {

                                        $stmt = $mysqli->prepare("SELECT std_reg_no, user_name, sync_status, bio_reg_status, face_id_status, fingerprint_status  FROM std_reg_s WHERE std_app_no = ? ORDER BY id DESC LIMIT 1");
                                        $stmt->bind_param("s", $value['std_app_no']);
                                        $stmt->execute();
                                        $renewal_result = $stmt->get_result();
                                        $stmt->close();

                                        if ($renewal_result && $renewal_row = $renewal_result->fetch_assoc()) {
                                            // Append values to current insertion
                                            $row_p1['std_reg_no'] = $renewal_row['std_reg_no'];
                                            $row_p1['user_name'] = $renewal_row['user_name'];
                                            if ($transfer_status != '' || $row_p1['renewal_status'] == 4) {
                                                $row_p1['sync_status'] = 0;
                                                $row_p1['bio_reg_status'] = 0;
                                                $row_p1['face_id_status'] = 0;
                                                $row_p1['fingerprint_status'] = 0;
                                              
                                            } else {
                                                  $row_p1['sync_status'] = 0;
                                                $row_p1['bio_reg_status'] = $renewal_row['bio_reg_status'];
                                                $row_p1['face_id_status'] = $renewal_row['face_id_status'];
                                                $row_p1['fingerprint_status'] = $renewal_row['fingerprint_status'];
                                            }
                                        }
                                    } else {

                                        $std_reg_no = reg_no($academic_year, $value['s1_unique_id']);
                                        $row_p1['user_name'] = $std_reg_no;
                                        $row_p1['std_reg_no'] = $std_reg_no;
                                        // $row_p1['sync_status'] = 0;
                                    }

                                    $select_where_fetch = 's1_unique_id = ?';
                                    $stmt = $mysqli->prepare("SELECT dob FROM std_app_s6 WHERE $select_where_fetch");
                                    $stmt->bind_param("s", $value['s1_unique_id']);
                                    $stmt->execute();
                                    $action_obj_fetch = $stmt->get_result();
                                    $stmt->close();


                                    if ($action_obj_fetch && $row = $action_obj_fetch->fetch_assoc()) {
                                        $std_dob = $row['dob'];
                                        $formatted_dob = date('d/m/Y', strtotime($std_dob));
                                        $row_p1['password'] .= $formatted_dob;
                                        $row_p1['confirm_password'] .= $formatted_dob;
                                        $row_p1['enc_password'] = hash('sha256', $row_p1['password']);
                                    } else {
                                        $error_p1 = $mysqli->error;
                                        $msg_p1 = "error";
                                        // Handle the error accordingly, maybe log it or set an error response
                                    }

                                    $stmt = $mysqli->prepare("INSERT INTO std_reg_s (" . implode(", ", array_keys($row_p1)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p1), "?")) . ")");

                                    $stmt->bind_param(str_repeat("s", count($row_p1)), ...array_values($row_p1));


                                    if (!$stmt->execute()) {
                                        $error_p1 = $mysqli->error;
                                        $msg_p1 = "error";
                                        break;
                                    }

                                    $stmt->close();
                                }
                                $msg_p1 = "inserted";
                            } else {
                                $msg_p1 = "no_data_found";
                            }
                        } else {
                            $error_p1 = $mysqli->error;
                            $msg_p1 = "error";
                        }

                        $select_where = 's1_unique_id = ?';
                        $stmt = $mysqli->prepare("SELECT * FROM std_app_s2 WHERE $select_where");
                        $stmt->bind_param("s", $value['s1_unique_id']);
                        $stmt->execute();
                        $action_obj = $stmt->get_result();
                        $stmt->close();

                        if ($action_obj) {
                            $data = $action_obj->fetch_all(MYSQLI_ASSOC);
                            $error = "";

                            if (!empty($data)) {
                                foreach ($data as $row) {
                                    unset($row['id']);
                                    $stmt = $mysqli->prepare("INSERT INTO std_reg_s2 (" . implode(", ", array_keys($row)) . ") VALUES (" . implode(", ", array_fill(0, count($row), "?")) . ")");
                                    $stmt->bind_param(str_repeat("s", count($row)), ...array_values($row));
                                    if (!$stmt->execute()) {
                                        $error = $mysqli->error;
                                        $msg = "error";
                                        break;
                                    }
                                    $stmt->close();
                                }
                                $msg = "inserted";
                            } else {
                                $msg = "no_data_found";
                            }
                        } else {
                            $error = $mysqli->error;
                            $msg = "error";
                        }

                        // Fetch data from std_app_p3 based on unique_id
                        $select_where_p3 = 's1_unique_id = ?';
                        $stmt = $mysqli->prepare("SELECT * FROM std_app_emis_s3 WHERE $select_where_p3");
                        $stmt->bind_param("s", $value['s1_unique_id']);
                        $stmt->execute();
                        $action_obj_p3 = $stmt->get_result();
                        $stmt->close();

                        if ($action_obj_p3) {
                            $data_p3 = $action_obj_p3->fetch_all(MYSQLI_ASSOC);
                            $error_p3 = "";

                            if (!empty($data_p3)) {
                                foreach ($data_p3 as $row_p3) {
                                    unset($row_p3['id']);

                                    $stmt = $mysqli->prepare("INSERT INTO std_reg_emis_s3 (" . implode(", ", array_keys($row_p3)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p3), "?")) . ")");
                                    $stmt->bind_param(str_repeat("s", count($row_p3)), ...array_values($row_p3));
                                    if (!$stmt->execute()) {
                                        $error_p3 = $mysqli->error;
                                        $msg_p3 = "error";
                                        break;
                                    }
                                    $stmt->close();
                                }
                                $msg_p3 = "inserted";
                            } else {
                                $msg_p3 = "no_data_found";
                            }
                        } else {
                            $error_p3 = $mysqli->error;
                            $msg_p3 = "error";
                        }

                        $select_where_p4 = 's1_unique_id = ?';
                        $stmt = $mysqli->prepare("SELECT * FROM std_app_umis_s4 WHERE $select_where_p4");
                        $stmt->bind_param("s", $value['s1_unique_id']);
                        $stmt->execute();
                        $action_obj_p4 = $stmt->get_result();
                        $stmt->close();

                        if ($action_obj_p4) {
                            $data_p4 = $action_obj_p4->fetch_all(MYSQLI_ASSOC);
                            $error_p4 = "";

                            if (!empty($data_p4)) {
                                foreach ($data_p4 as $row_p4) {
                                    unset($row_p4['id']);

                                    $stmt = $mysqli->prepare("INSERT INTO std_reg_umis_s4 (" . implode(", ", array_keys($row_p4)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p4), "?")) . ")");
                                    $stmt->bind_param(str_repeat("s", count($row_p4)), ...array_values($row_p4));
                                    if (!$stmt->execute()) {
                                        $error_p4 = $mysqli->error;
                                        $msg_p4 = "error";
                                        break;
                                    }
                                    $stmt->close();
                                }
                                $msg_p4 = "inserted";
                            } else {
                                $msg_p4 = "no_data_found";
                            }
                        } else {
                            $error_p4 = $mysqli->error;
                            $msg_p4 = "error";
                        }

                        $select_where_p5 = 's1_unique_id = ?';
                        $stmt = $mysqli->prepare("SELECT * FROM std_app_s5 WHERE $select_where_p5");
                        $stmt->bind_param("s", $value['s1_unique_id']);
                        $stmt->execute();
                        $action_obj_p5 = $stmt->get_result();
                        $stmt->close();

                        if ($action_obj_p5) {
                            $data_p5 = $action_obj_p5->fetch_all(MYSQLI_ASSOC);
                            $error_p5 = "";

                            if (!empty($data_p5)) {
                                foreach ($data_p5 as $row_p5) {
                                    unset($row_p5['id']);

                                    $stmt = $mysqli->prepare("INSERT INTO std_reg_s5 (" . implode(", ", array_keys($row_p5)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p5), "?")) . ")");
                                    $stmt->bind_param(str_repeat("s", count($row_p5)), ...array_values($row_p5));
                                    if (!$stmt->execute()) {
                                        $error_p5 = $mysqli->error;
                                        $msg_p5 = "error";
                                        break;
                                    }
                                    $stmt->close();
                                }
                                $msg_p5 = "inserted";
                            } else {
                                $msg_p5 = "no_data_found";
                            }
                        } else {
                            $error_p5 = $mysqli->error;
                            $msg_p5 = "error";
                        }



                        $select_where_p6 = 's1_unique_id = ?';
                        $stmt = $mysqli->prepare("SELECT * FROM std_app_s6 WHERE $select_where_p6");
                        $stmt->bind_param("s", $value['s1_unique_id']);
                        $stmt->execute();
                        $action_obj_p6 = $stmt->get_result();
                        $stmt->close();

                        if ($action_obj_p6) {
                            $data_p6 = $action_obj_p6->fetch_all(MYSQLI_ASSOC);
                            $error_p6 = "";

                            if (!empty($data_p6)) {
                                foreach ($data_p6 as $row_p6) {
                                    unset($row_p6['id']);

                                    $stmt = $mysqli->prepare("INSERT INTO std_reg_s6 (" . implode(", ", array_keys($row_p6)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p6), "?")) . ")");
                                    $stmt->bind_param(str_repeat("s", count($row_p6)), ...array_values($row_p6));
                                    if (!$stmt->execute()) {
                                        $error_p6 = $mysqli->error;
                                        $msg_p6 = "error";
                                        break;
                                    }
                                    $stmt->close();
                                }
                                $msg_p6 = "inserted";
                            } else {
                                $msg_p6 = "no_data_found";
                            }
                        } else {
                            $error_p6 = $mysqli->error;
                            $msg_p6 = "error";
                        }

                        $select_where_p7 = 's1_unique_id = ?';
                        $stmt = $mysqli->prepare("SELECT * FROM std_app_s7 WHERE $select_where_p7");
                        $stmt->bind_param("s", $value['s1_unique_id']);
                        $stmt->execute();
                        $action_obj_p7 = $stmt->get_result();
                        $stmt->close();

                        if ($action_obj_p7) {
                            $data_p7 = $action_obj_p7->fetch_all(MYSQLI_ASSOC);
                            $error_p7 = "";

                            if (!empty($data_p7)) {
                                foreach ($data_p7 as $row_p7) {
                                    unset($row_p7['id']);

                                    $stmt = $mysqli->prepare("INSERT INTO std_reg_s7 (" . implode(", ", array_keys($row_p7)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p7), "?")) . ")");
                                    $stmt->bind_param(str_repeat("s", count($row_p7)), ...array_values($row_p7));
                                    if (!$stmt->execute()) {
                                        $error_p7 = $mysqli->error;
                                        $msg_p7 = "error";
                                        break;
                                    }
                                    $stmt->close();
                                }
                                $msg_p7 = "inserted";
                            } else {
                                $msg_p7 = "no_data_found";
                            }
                        } else {
                            $error_p7 = $mysqli->error;
                            $msg_p7 = "error";
                        }
                    }
                } else {
                    $sanc_cnt_exceed = 'sanc_cnt_exceed';
                    break;
                }
            }
        }

        $password = '3sc3RLrpd17';
        $enc_method = 'aes-256-cbc';
        $enc_password = substr(hash('sha256', $password, true), 0, 32);
        $enc_iv = "av3DYGLkwBsErphc";

        $menu_screen = "registration/list";
        $file_name = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));

        $json_array = [
            "status" => $result && $status_p3, // combine statuses
            "data" => isset($data) ? $data : null,
            "error" => $error . " " . $error_p3, // combine errors
            "url" => $file_name,
            "msg" => $msg . " " . $msg_p3, // combine messages
            "sql" => "" // SQL queries are handled in prepared statements
        ];

        echo json_encode($json_array);

        break;




    case 'rec_status':
        $batchNo = $_POST['batchNo'];

        // Create connection

        // Prepared statement
        $sql = "UPDATE batch_creation SET rec_status = ?, rec_time = ? WHERE batch_no = ?";
        $rc_sts = '1';
        // Prepare and bind
        if ($stmt = $mysqli->prepare($sql)) {
            $rec_time = date('Y-m-d H:i:s');
            $stmt->bind_param("sss", $rc_sts, $rec_time, $batchNo);

            // Execute the update
            // $stmt->execute();

            // Check if update was successful
            if ($stmt->execute()) {
                $status = true;
                $data = "Updated all records with batch number: $batchNo";
                $error = "";
            } else {
                $status = false;
                $data = "";
                $error = "Failed to update records with batch number: $batchNo";
            }

            $stmt->close();
        } else {
            $status = false;
            $data = "";
            $error = "Prepare statement error: " . $mysqli->error;
        }

        // JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => ($status ? "Updated all records with batch number: $batchNo" : "Error updating records"),
            "sql" => $sql
        ];

        echo json_encode($json_array);

        // Close connection
        $mysqli->close();

        break;

    case 'update_date':

        $table_batch = 'batch_creation';
        $batch_no = $_POST['batch_no'];
        $total_cnt = $_POST['total_cnt'];
        $acc_cnt = acc_cnt($batch_no);
        $rej_cnt = rej_cnt($batch_no);
        $current_date = date('Y-m-d');

        // Determine batch_status based on the provided conditions
        if ($acc_cnt + $rej_cnt == 0) {
            $batch_status = 0; // No records processed, pending
        } elseif ($acc_cnt + $rej_cnt < $total_cnt) {
            $batch_status = 1; // Partially completed
        } elseif ($total_cnt == ($acc_cnt + $rej_cnt)) {
            $batch_status = 2; // Fully completed
        }


        $columns = [
            "batch_status" => $batch_status,
            "batch_sub_date" => $current_date
        ];

        $update_where = [
            "batch_no" => $batch_no
        ];

        $action_obj = $pdo->update($table_batch, $columns, $update_where);

        if ($action_obj->status) {
            $status = $action_obj->status;
            $data = $action_obj->data;
            $error = "";
            $sql = $action_obj->sql;
            $msg = "success_submit";
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
            "sql" => $sql
        ];

        echo json_encode($json_array);
        break;



    case 'get_batch_no':

        $hostel_name = $_POST['hostel_name'];
        $academic_year = $_POST['academic_year'];
        $batch_no_options = batch_no('', '', $hostel_name, $academic_year);
        $batch_no_options = select_option($batch_no_options, "Select Batch");
        echo $batch_no_options;

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
        print_r($amc_name_list);
        return 0;
    }
}

function total_reg_count($unique_id = "")
{
    // echo $zone_name;
    $academic_year = last_academic_year();

    global $pdo;

    $table_name = "std_reg_s";
    $where = [];
    $table_columns = [
        "count(id) as tot_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "status"    => 1,
        "is_active" => 1,
        "is_delete" => 0,
        "dropout_status" => 1,
        "academic_year" => $academic_year
    ];

    if ($unique_id) {
        // $where              = [];
        $where["hostel_1"] .= $unique_id;
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
        print_r($amc_name_list);
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
        print_r($amc_name_list);
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
        print_r($amc_name_list);
        return 0;
    }
}


function reg_no_1($academic_year, $s1_unique_id)
{
    // $date = date("Y");
    // $st_date = substr($date, 4);

    $servername = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $database_name = "adi_dravidar";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    }

    $sql = $conn->query("SELECT * FROM academic_year_creation where is_delete = '0'  ORDER BY s_no DESC LIMIT 1");
    $row = $sql->fetch();

    $acc_year = $row['amc_year'];
    $a = str_split($acc_year);
    $splt_acc_yr = $a[0] . $a[1] . $a[2] . $a[3];

    $stmt = $conn->query("SELECT std_reg_no FROM std_reg_s WHERE is_delete = '0' ORDER BY CAST(RIGHT(std_reg_no, 6) AS UNSIGNED) DESC LIMIT 1;");
    $last_reg_no = $stmt->fetchColumn();

    $hosteltype = $conn->query("SELECT student_type FROM std_app_s where unique_id='" . $s1_unique_id . "' ");
    $row = $hosteltype->fetch();
    $hosteltype = $row['student_type'];

    if ($hosteltype == '65f00a259436412348') {
        $hosteltype = 'S';
    } elseif ($hosteltype == '65f00a327c08582160') {
        $hosteltype = 'I';
    } elseif ($hosteltype == '65f00a3e3c9a337012') {
        $hosteltype = 'D';
    } elseif ($hosteltype == '65f00a495599589293' || $hosteltype == '65f00a53eef3015995') {
        $hosteltype = 'C';
    }


    if ($last_reg_no == '') {
        $new_seq_no = 1;
    } else {
        // Extract year and sequence number from the last registration number
        $last_seq_no = intval(substr($last_reg_no, -6)); // Extract last 4 digits

        // Increment the sequence number
        $new_seq_no = $last_seq_no + 1;
    }

    // Format the new registration number
    $registration_no = $splt_acc_yr . 'ADTW' . $hosteltype . str_pad($new_seq_no, 6, '0', STR_PAD_LEFT);

    return $registration_no;
}




function reg_no($academic_year, $s1_unique_id)
{
    $servername = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $database_name = "adi_dravidar";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database_name", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    }

    // Begin transaction
    $conn->beginTransaction();

    // Fetch academic year and format it
    $sql = $conn->query("SELECT * FROM academic_year_creation WHERE is_delete = '0' ORDER BY s_no DESC LIMIT 1");
    $row = $sql->fetch();
    $acc_year = $row['amc_year'];
    $academic_year = $row['unique_id'];
    $splt_acc_yr = substr($acc_year, 0, 4); // Get first 4 characters

    // Fetch last registration number from register_no
    $stmt = $conn->query("SELECT std_reg_no FROM std_reg_s where academic_year = '" . $academic_year . "' and application_type = 1 ORDER BY CAST(RIGHT(std_reg_no, 6) AS UNSIGNED) DESC LIMIT 1 FOR UPDATE");

    $last_reg_no = $stmt->fetchColumn();

    // Determine hostel type
    $hosteltypeQuery = $conn->prepare("SELECT student_type FROM std_app_s WHERE unique_id = ?");
    $hosteltypeQuery->execute([$s1_unique_id]);
    $row = $hosteltypeQuery->fetch();
    $hosteltype = $row['student_type'];

    if ($hosteltype == '65f00a259436412348') {
        $hosteltype = 'S';
    } elseif ($hosteltype == '65f00a327c08582160') {
        $hosteltype = 'I';
    } elseif ($hosteltype == '65f00a3e3c9a337012') {
        $hosteltype = 'D';
    } elseif ($hosteltype == '65f00a495599589293' || $hosteltype == '65f00a53eef3015995') {
        $hosteltype = 'C';
    }

    // Generate new sequence number
    if ($last_reg_no == '') {
        $new_seq_no = 1;
    } else {
        // $last_seq_no = intval(substr($last_reg_no, -6));
        $new_seq_no = $last_reg_no + 1;
    }

    // Format the new registration number
    $registration_no = $splt_acc_yr . 'ADTW' . $hosteltype . str_pad($new_seq_no, 6, '0', STR_PAD_LEFT);
    $reg_no = substr($registration_no, -6);

    // Insert the new registration number into register_no
    $insertStmt = $conn->prepare("INSERT INTO register_no (academic_year,reg_no) VALUES ('$academic_year','$reg_no')");

    // try{
    $insertStmt->execute();

    $conn->commit();

    return $registration_no;
    //     }catch(PDOException $e){

    //         $conn->rollBack();

    //         // Check if the error is a duplicate key error (Error code 23000)
    //         if ($e->getCode() == 23000) {

    //             return reg_no($academic_year, $s1_unique_id, $batch_no); // Recursive retry

    //     }
    // }


}





function image_view($doc_file_name = "")
{
    // echo 'hi';

    // echo 'test';
    // echo $doc_file_name;
    // $file_names = explode(',', $doc_file_name);
    $image_view = '';



    $cfile_name = explode('.', $doc_file_name);

    if ($doc_file_name) {

        if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
            // echo "dd";
            $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../../../uploads/' . $doc_file_name . '"  width="20%" ></a>';
            // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
        } else if ($cfile_name[1] == 'pdf') {
            $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="assets/images/pdf.png"   width="35px" height="35px" style="margin-left: 15px;" ></a>';
        }
        // else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
        //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="assets/images/excel.png"  height="30px" width="30px" ></a>';
        // } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
        //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="assets/images/word.png"  height="30px" width="30px" ></a>';
        // }
    }
    return $image_view;
}



function acc_cnt($unique_id = "")
{
    global $pdo;

    $table_name = "batch_creation";
    $where = [];
    $table_columns = [
        "count(id) as acc_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "status" => 1,
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {

        $where["batch_no"] .= $unique_id;
    }

    $amc_name_list = $pdo->select($table_details, $where);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['acc_cnt'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}


function rej_cnt($unique_id = "")
{
    global $pdo;

    $table_name = "batch_creation";
    $where = [];
    $table_columns = [
        "count(id) as rej_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "status" => 2,
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {

        $where["batch_no"] .= $unique_id;
    }

    $amc_name_list = $pdo->select($table_details, $where);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['rej_cnt'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

function total_registered($unique_id = "")
{
    // echo $zone_name;

    $academic_year = last_academic_year();


    global $pdo;

    $table_name = "std_reg_s";
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
        "dropout_status" => 1,
        "academic_year" => $academic_year
    ];

    if ($unique_id) {
        // $where              = [];
        $where["hostel_1"] .= $unique_id;
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
        print_r($amc_name_list);
        return 0;
    }
}

function jaccardSimilarity($str1, $str2)
{
    $set1 = array_unique(str_split(mb_strtolower($str1)));
    $set2 = array_unique(str_split(mb_strtolower($str2)));

    $intersection = array_intersect($set1, $set2);
    $union = array_unique(array_merge($set1, $set2));

    $similarity = count($union) > 0 ? count($intersection) / count($union) : 0;

    return round($similarity, 1); // Round to 1 decimal place
}
