<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "inspection";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';


// // Variables Declaration
$action = $_POST['action'];
$ses_userid = $_SESSION["user_id"];
$district_name = "";
$taluk_name = "";
$inspection_date = "";
$hostel_name = "";
$desc_text = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

switch ($action) {

    //
   case 'createupdate':
    $token = $_POST['csrf_token'];
    if (!validateCSRFToken($token)) {
        die('CSRF validation failed.');
    }

    // Sanitize and validate input
    $vali_user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
    $vali_user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING);
    $vali_district_name = filter_input(INPUT_POST, 'district_name', FILTER_SANITIZE_STRING);
    $vali_taluk_name = filter_input(INPUT_POST, 'taluk_name', FILTER_SANITIZE_STRING);
    $vali_inspection_date = filter_input(INPUT_POST, 'inspection_date', FILTER_SANITIZE_STRING);
    $vali_hostel_name = filter_input(INPUT_POST, 'hostel_name', FILTER_SANITIZE_STRING);
    $vali_description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $vali_inspection_id = filter_input(INPUT_POST, 'inspection_id', FILTER_SANITIZE_STRING);

    if (!$vali_user_name || !$vali_user_type || !$vali_district_name || !$vali_taluk_name || !$vali_inspection_date || !$vali_hostel_name || !$vali_description || !$vali_inspection_id) {
        $msg = "form_alert";
    } else {
        $user_name = sanitizeInput($_POST["user_name"]);
        $user_type = sanitizeInput($_POST["user_type"]);
        $district_name = sanitizeInput($_POST["district_name"]);
        $taluk_name = sanitizeInput($_POST["taluk_name"]);
        $inspection_date = sanitizeInput($_POST["inspection_date"]);
        $hostel_name = sanitizeInput($_POST["hostel_name"]);
        $description = sanitizeInput($_POST["description"]);
        $inspection_id = sanitizeInput($_POST["inspection_id"]);
        $unique_id = sanitizeInput($_POST["unique_id"]);
        $update_where = "";
        $allowedExts = array('pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls');

        $file_names = null;
        $file_org_names = null;
        if (!empty($_FILES['test_file']['name'])) {
            $extension = pathinfo($_FILES["test_file"]['name'], PATHINFO_EXTENSION);

            if (!in_array($extension, $allowedExts)) {
                die('File type not allowed.');
            }

            $file_exp = explode(".", $_FILES["test_file"]['name']);
            $tem_name = random_strings(25) . "." . $file_exp[1];
            move_uploaded_file($_FILES["test_file"]["tmp_name"], '../../../uploads/inspection/' . $tem_name);
            $file_names = $tem_name;
            $file_org_names = $_FILES["test_file"]['name'];
        }

        $columns = [
            "user_name" => $user_name,
            "user_type" => $user_type,
            "district_name" => $district_name,
            "taluk_name" => $taluk_name,
            "inspection_id" => $inspection_id,
            "inspection_date" => $inspection_date,
            "hostel_name" => $hostel_name,
            "description" => $description,
            "unique_id" => unique_id($prefix)
        ];

        // Check if already exists
        $sql = 'SELECT COUNT(unique_id) AS count FROM ' . $table . ' WHERE is_delete = 0 AND inspection_id = ?';
        if ($unique_id) {
            $sql .= ' AND unique_id != ?';
        }

        $stmt = $mysqli->prepare($sql);

        if ($unique_id) {
            $stmt->bind_param("ss", $inspection_id, $unique_id);
        } else {
            $stmt->bind_param("s", $inspection_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        if ($data["count"] > 0) {
            $status = true;
            $msg = "already";
        } else {
            if ($unique_id) {
                unset($columns['unique_id']);
                $sql = 'UPDATE ' . $table . ' SET user_name = ?, user_type = ?, district_name = ?, taluk_name = ?, inspection_id = ?, inspection_date = ?, hostel_name = ?, description = ?';
                $params = [$user_name, $user_type, $district_name, $taluk_name, $inspection_id, $inspection_date, $hostel_name, $description];
                $param_types = 'ssssssss';

                if ($file_names !== null) {
                    $sql .= ', file_name = ?, file_org_names = ?';
                    $params[] = $file_names;
                    $params[] = $file_org_names;
                    $param_types .= 'ss';
                }

                $sql .= ' WHERE unique_id = ?';
                $params[] = $unique_id;
                $param_types .= 's';

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param($param_types, ...$params);
            } else {
                $sql = 'INSERT INTO ' . $table . ' (user_name, user_type, district_name, taluk_name, inspection_id, inspection_date, hostel_name, description, file_name, file_org_names, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssssssss", $user_name, $user_type, $district_name, $taluk_name, $inspection_id, $inspection_date, $hostel_name, $description, $file_names, $file_org_names, $columns["unique_id"]);
            }

            if ($stmt->execute()) {
                $status = true;
                $msg = $unique_id ? "update" : "create";
            } else {
                $status = false;
                $msg = "error";
                $error = $stmt->error;
            }
            $stmt->close();
        }
    }

    $json_array = [
        "status" => $status,
        "data" => $data ?? [],
        "error" => $error ?? "",
        "msg" => $msg,
    ];
    echo json_encode($json_array);
    $mysqli->close();
    break;


    case 'datatable':
        // DataTable Variables

        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "inspection_date",
            "inspection_id",
            "(SELECT hostel_name FROM hostel_name AS hostel_name WHERE hostel_name.unique_id = " . $table . ".hostel_name ) AS hostel_name",
            "description",
            "file_name",
            "unique_id"
        ];

        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 and taluk_name = ?";
        $order_by = ""; // You can modify this to add an order by clause if needed
	$taluk_name = $_SESSION['taluk_id'];
        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";
        if ($limit) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);
        if ($limit) {
            $stmt->bind_param("isii", $start, $taluk_name, $start, $limit);
        } else {
            $stmt->bind_param("is", $start, $taluk_name);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];


        if ($result) {

            $res_array = $result->fetch_all(MYSQLI_ASSOC);


            foreach ($res_array as $key => $value) {

                $file_name = $value["file_name"];

                $value["file_name"] = image_view($file_name);


                $value['is_active'] = is_active_show($value['is_active']);
                $unique_id = $value['unique_id'];
                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);
                $eye_button = '<a class="btn btn-action specl2"  href="javascript:leave_print(\'' . $unique_id . '\')"><button type="button" ><i class="fa fa-eye" ></i></button></a>';


                if ($value['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update = "";
                    $btn_delete = "";
                }

                $value['unique_id'] = $btn_update . $btn_delete . $eye_button;
                $data[] = array_values($value);
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
            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $stmt->error,
                "testing" => $stmt->sqlstate
            ];
        }

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

    // case 'hostel_to_taluk':

    //     $unique_id          = $_POST['hostel_name'];
    //     $where      = [
    //         "unique_id" => $unique_id
    //     ];

    //     $table_hostel      =  "hostel_name";

    //     $columns    = [
    //         "district_name",
    //         "taluk_name",
    //     ];

    //     $table_details   = [
    //         $table_hostel,
    //         $columns
    //     ];

    //     $result_values  = $pdo->select($table_details, $where);
    //     // print_r($result_values);
    //     if ($result_values->status) {

    //         $result_values              = $result_values->data[0];
    //         // $data       = $action_obj->data;
    //         // $district_name              = $result_values[0]["district_name"];
    //         // $taluk_name                 = $result_values[0]["taluk_name"];
    //     }
    //     // print_r($result_values);


    //     break;

    case 'hostel_to_taluk':

        $hostel_name = $_POST["hostel_name"];
        // print_r($hostel_name);
        $details = [
            "district_name" => "",
            "taluk_name" => "",

        ];

        if ($hostel_name) {
            $staff_where = [
                "unique_id" => $hostel_name
            ];

            $staff_columns = [
                "district_name",
                "taluk_name",

            ];

            $staff_table_details = [
                "hostel_name",
                $staff_columns
            ];

            $staff_details = $pdo->select($staff_table_details, $staff_where);

            if ($staff_details->status) {
                if (!empty($staff_details->data)) {
                    $details = $staff_details->data[0];
                }
            } else {
                print_r($staff_details);
            }
        }

        echo json_encode($details);
        //print_r($staff_details);
        break;

    case 'get_hostel_name':

        $taluk_name = $_POST['taluk_name'];

        $taluk_options = hostel_name("", $taluk_name);

        $hostel_name_options = select_option($taluk_options, "Select Hostel");

        echo $hostel_name_options;

        break;

    default:

        break;
}





function image_view($file_name = "")
{
    // echo $file_name;
    $file_names = explode(',', $file_name);
    $image_view = '';

    if ($file_name) {
        foreach ($file_names as $file_key => $file_name) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }

            $cfile_name = explode('.', $file_name);

            if ($file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' . $file_name . '\')"><img src="../assets/images/images.png"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $file_name . '\')"><img src="../assets/images/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                }
            }
        }
    }

    return $image_view;
}



// function image_view($doc_file_name = "")
// {
// //    alert("hiiii");
// // echo $doc_file_name;
//             $cfile_name = explode('.', $doc_file_name);

//             if ($doc_file_name) {

//                 if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
//                     // echo "dd";
//                     $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../../uploads/inspection/pdf.png' . $doc_file_name . '"  width="20%" ></a>';
//                     // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
//                 } else if ($cfile_name[1] == 'pdf') {
//                     $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="../../uploads/inspection/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
//                 } 

//             }
//             return $image_view;
//         }

?>