<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "student_marksheet";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$user_type = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

switch ($action) {
    case 'createupdate':

        // $token = $_POST['csrf_token'];

        // // Validate CSRF token
        // if (!validateCSRFToken($token)) {
        //     die(json_encode(['status' => 'error', 'msg' => 'CSRF validation failed.']));
        // }

        // Sanitize input values
        $sem_status = $_POST["sem_status"];
        $academic_year = $_POST["academic_year"];
        $sem_type = $_POST["sem_type"];
        $cgpa = $_POST["cgpa"];
        $std_unique_id = $_POST["reg_no"];

        $reg_no = student_reg_list($_POST["reg_no"])[0]['std_reg_no'];

        $hostel_name = $_SESSION["hostel_id"];
        $district_name = $_SESSION["district_id"];
        $taluk_name = $_SESSION["taluk_id"];

        $unique_id = $_POST["unique_id"];

        // Handle entry date
        $entry_date = date('Y-m-d');

        // File upload handling
        $file_names = '';
        $file_org_names = '';



        $allowedExts = array('pdf', 'jpg', 'jpeg', 'png');
        if (isset($_FILES["file_name"]) && $_FILES["file_name"]["error"] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES["file_name"]['name'], PATHINFO_EXTENSION);

            // Check if file extension is allowed
            if (in_array($extension, $allowedExts)) {
                $file_exp = explode(".", $_FILES["file_name"]['name']);
                $tem_name = random_strings(25) . "." . $file_exp[1];
                if (move_uploaded_file($_FILES["file_name"]["tmp_name"], '../../uploads/marksheet/' . $tem_name)) {
                    $file_names = $tem_name;
                    $file_org_names = $_FILES["file_name"]['name'];
                } else {
                    die(json_encode(['status' => 'error', 'msg' => 'Failed to move uploaded file.']));
                }
            } else {
                die(json_encode(['status' => 'error', 'msg' => 'File type not allowed.']));
            }
        }

        // Prepare SQL statements for INSERT and UPDATE
        if ($unique_id) {
            // Update query
            if ($file_names != '') {
                $sql = "UPDATE student_marksheet SET semester_type=?, cgpa=?, entry_date=?, std_reg_no=?, std_unique_id=?, district_name=?, taluk_name=?, hostel_name=?, file_name=?, file_org_name=?, sem_status=?, academic_year=? WHERE unique_id=?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssssssssss", $sem_type, $cgpa, $entry_date, $reg_no, $std_unique_id, $district_name, $taluk_name, $hostel_name, $file_names, $file_org_names, $sem_status, $academic_year, $unique_id);
            } else {
                $sql = "UPDATE student_marksheet SET semester_type=?, cgpa=?, entry_date=?, std_reg_no=?, std_unique_id=?, district_name=?, taluk_name=?, hostel_name=?, sem_status=?, academic_year=? WHERE unique_id=?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssssssssss", $sem_type, $cgpa, $entry_date, $reg_no, $std_unique_id, $district_name, $taluk_name, $hostel_name, $sem_status, $academic_year, $unique_id);
            }
        } else {
            // Insert query

            $sql = "INSERT INTO student_marksheet (semester_type, cgpa, entry_date, std_reg_no, std_unique_id, district_name, taluk_name, hostel_name, file_name, file_org_name, sem_status, academic_year, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sssssssssssss", $sem_type, $cgpa, $entry_date, $reg_no, $std_unique_id, $district_name, $taluk_name, $hostel_name, $file_names, $file_org_names, $sem_status, $academic_year, unique_id($prefix));

        }

        // Execute statement
        if ($stmt->execute()) {
            $status = "success";
            $data = [];
            $error = "";
            $msg = $unique_id ? 'update' : 'create';
        } else {
            $status = "error";
            $data = [];
            $error = "Failed to execute query: " . $stmt->error;
            $msg = "error";
        }

        // Close statement
        $stmt->close();

        // Construct JSON response
        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
        ];

        echo json_encode($json_array);

        break;




    case 'datatable':
        // Database connection parameters


        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

         $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];
        $academic_year = $_POST['amc_name'];

        $data = [];

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "academic_year",
            "district_name",
            "taluk_name",
            "'' as hostel_id",
            "hostel_name",
            
            "std_reg_no",
            "(select std_name from std_reg_s where std_reg_s.unique_id = student_marksheet.std_unique_id) as std_name",
            "semester_type",
            "sem_status",
            "cgpa",
            "file_name",
            "unique_id"
        ];
        $table = "student_marksheet"; // Replace with your actual table name
        $table_details = $table . " , (SELECT @a:= ?) AS a ";
        $where = "is_delete = 0 ";
        $order_by = "";



        $sql_function = "SQL_CALC_FOUND_ROWS";

        // SQL query for data fetching
      

         if (!empty($academic_year)) {
            $where .= " AND academic_year='".$academic_year."'";
           
        }
        if (!empty($district_name)) {
            $where .= " AND district_name='$district_name'";
          
        }
        if (!empty($taluk_name)) {
            $where .= " AND taluk_name='$taluk_name'";
           
        }
        if (!empty($hostel_name)) {
            $where .= " AND hostel_name='$hostel_name'";
        
        }
         $sql = "SELECT $sql_function " . implode(", ", $columns) . " FROM $table_details WHERE $where";

           if (!empty($limit)) {
            $sql .= " LIMIT ?, ?";
        }

        $stmt = $mysqli->prepare($sql);

        // Bind parameters
        $param_types = "i"; // Types for the bind_param
        $bind_params = [$start]; // Parameters to bind


        if (!empty($limit)) {
            $param_types .= "ii";
            $bind_params[] = $start;
            $bind_params[] = $limit;
        }

        // Dynamically bind parameters
        if (!empty($bind_params)) {
            $stmt->bind_param($param_types, ...$bind_params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() AS total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if ($row['file_name']) {
                    $row['file_name'] = image_view($row['file_name']);
                } else {
                    $row['file_name'] = '-';

                }

                $row['district_name'] = district_name($row['district_name'])[0]['district_name'];
                $row['taluk_name'] = taluk_name($row['taluk_name'])[0]['taluk_name'];
                $row['hostel_id'] = hostel_name($row['hostel_name'])[0]['hostel_id'];
                $row['hostel_name'] = hostel_name($row['hostel_name'])[0]['hostel_name'];
                

                switch ($row['sem_status']) {
                    case 'all_pass':
                        $row['sem_status'] = 'All Pass';
                        break;
                    case '1':
                        $row['sem_status'] = '1 Arrear';
                        break;
                    case '2':
                        $row['sem_status'] = '2 Arrear';
                        break;
                    case '3':
                        $row['sem_status'] = '3 Arrear';
                        break;
                    case '4':
                        $row['sem_status'] = '4 Arrear';
                        break;
                    case '5':
                        $row['sem_status'] = '5 Arrear';
                        break;
                    case '6':
                        $row['sem_status'] = '6 Arrear';
                        break;
                  
                }


                $row['academic_year'] = academic_year($row['academic_year'])[0]['amc_year'];


                $data[] = array_values($row); // Add row data to $data array
            }

            // Construct JSON response for DataTables
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
                "error" => $mysqli->error // Provide error details if any
            ];
        }

        echo json_encode($json_array); // Output JSON response

        // Close statement and connection
        $stmt->close();
        $mysqli->close();
        break;




    case 'delete':
        // Validate input
        $unique_id = isset($_POST['unique_id']) ? $_POST['unique_id'] : '';

        if (!$unique_id) {
            $json_array = [
                "status" => false,
                "msg" => "missing_unique_id"
            ];
            echo json_encode($json_array);
            break;
        }
        $is_delete = '1';
        // Prepare and execute SQL statement
        $sql = "UPDATE $table SET is_delete = ? WHERE unique_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $is_delete, $unique_id);

        // Execute statement and handle result
        if ($stmt->execute()) {
            $status = true;
            $msg = "success_delete";
        } else {
            $status = false;
            $msg = "error";
            $error = $stmt->error;
        }

        // Prepare JSON response
        $json_array = [
            "status" => $status,
            "msg" => $msg,
            "error" => $error
        ];

        echo json_encode($json_array);
        break;


        case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

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


function image_view($doc_file_name = "")
{
    $image_view = "";

    $cfile_name = explode('.', $doc_file_name);

    if ($doc_file_name) {

        if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg') || ($cfile_name[1] == 'PNG')) {
            // echo "dd";
            $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"> <img src="../adhmHostel/uploads/student_marksheet/' . $doc_file_name . '" style="width:50px; height:50px;" /></a>';
            // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
        } else if ($cfile_name[1] == 'pdf') {

            $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="../assets/images/pdf.png" style="margin-left: 15px; width:35px; height:40px;"; ></a>';
        }

    }
    return $image_view;
}
?>