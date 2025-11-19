<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];
// // Database Country Table Name
$table = "view_moveables_asset";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

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
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10; // Default to 10 if not set
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0; // Default to 0 if not set
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1; // Default to 1 if not set
        $limit = $length;

        $district_name = $_POST["district_name"];
        $taluk_name = $_POST["taluk_name"];
        $hostel_name = $_POST["hostel_name"];

        if ($length == '-1') {
            $limit = ""; // No limit if length is -1
        }
    
        // Initialize DataTable query variables
        $columns = [
            "'' AS s_no",
            "(SELECT district_name FROM district_name WHERE district_name.unique_id = view_moveables_asset.district_id) AS district_name",
            "(SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = view_moveables_asset.taluk_id) AS taluk_name",
            "(SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = view_moveables_asset.hostel_id) AS hostel_name",
            " '' as kitchen_asset",
"'' as digital_asset",
"hostel_id"
 ];
    
        $columns_str = implode(", ", $columns);
        $table_details = "$table, (SELECT @a:= 0) AS a";
        
        $where="district_id = '".$_SESSION["district_id"]."'";
        // Initialize @a variable for row numbering
        $group_by = "GROUP BY taluk_name, hostel_name";
        $order_by = ""; // Define your order by if needed
       
       
        // if (!empty($district_name)) {
        //     $where .= " AND district_id = '$district_name' ";
        // }
        if (!empty($taluk_name)) {
            $where .= " AND taluk_id = '$taluk_name' ";
          
        }
        if (!empty($hostel_name)) {
            $where .= " AND hostel_id = '$hostel_name' ";
          
        }



        
        // Construct the SQL query
        $sql = "SELECT SQL_CALC_FOUND_ROWS $columns_str FROM $table_details where $where $group_by";




        
        // Construct the SQL query
        $sql = "SELECT SQL_CALC_FOUND_ROWS $columns_str FROM $table_details where $where $group_by";

        // echo $sql;
    
      
       
        
        // Add LIMIT clause if needed
        if ($limit !== "") {
            $sql .= " LIMIT $start, $limit";
        }
    
        // Execute the query
        $result = $mysqli->query($sql);
    
        if (!$result) {
            // Handle query error
            $json_array = [
                "draw" => $draw,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $mysqli->error
            ];
            echo json_encode($json_array);
            break;
        }
        $s_no=1;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            
            $kitchen_asset_count = kitchen_asset_count($row['hostel_id'])[0]['count'];
            $digital_asset_count = digital_asset_count($row['hostel_id'])[0]['count'];
            $row['s_no']=$s_no++;
            // $data[] = array_values($row);
            $data[] = [
                $row['s_no'],
            $row['district_name'],
            $row['taluk_name'],
            $row['hostel_name'],
            "<span class='kitchen-asset-count' data-hostel-id='{$row['hostel_id']}' style='cursor:pointer;'>{$kitchen_asset_count}</span>",
            "<span class='digital-asset-count' data-hostel-id='{$row['hostel_id']}' style='cursor:pointer;'>{$digital_asset_count}</span>"
       
        
        
        ];
        }
    
        // Get total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() AS total");
        $total_records = $total_records_result->fetch_assoc()['total'];
    
        // Prepare JSON response
        $json_array = [
            "draw" => $draw,
            "recordsTotal" => intval($total_records),
            "recordsFiltered" => intval($total_records),
            "data" => $data,
            // "testing" => $sql  // Uncomment for debugging
        ];
    
        // Output the JSON response
        echo json_encode($json_array);
        break;
  
      
        
        // case 'fetch_kitchen_assets':

        //     $hostel_id = $_POST['hostel_id'];
        
        //     $sql = "SELECT asset, category, quantity, created FROM view_moveables_asset WHERE hostel_id = ? AND k_d_category = 1";
        //     $stmt = $mysqli->prepare($sql);
        
        //     if (!$stmt) {
        //         die("MySQLi prepare failed: " . $mysqli->error);
        //     }
        
        //     $stmt->bind_param('ss', $hostel_id);
        
        //     if ($stmt->execute() === false) {
        //         die("MySQLi execute failed: " . $stmt->error);
        //     }
        
        //     $result = $stmt->get_result();
        
        //     $assets = [];
        //     while ($row = $result->fetch_assoc()) {
        //         $category = kitchen_category($row['category'])[0]['category'];
        //         $asset = kitchen_asset($row['asset'])[0]['kitchen_asset'];
        
        //         // Group assets by category
        //         if (!isset($assets[$category])) {
        //             $assets[$category] = [];
        //         }
        
        //         $assets[$category][] = [
        //             'created'=> $row['created'],
        //             'asset' => $asset,
        //             'quantity' => $row['quantity']
        //         ];
        //     }
        
        //     echo json_encode($assets);
        //     break;
        
        case 'fetch_kitchen_assets':

            $hostel_id = $_POST['hostel_id'];
            
            // SQL query to fetch kitchen assets details including the created date
            $sql = "SELECT asset, category, quantity, created, big_small FROM view_moveables_asset WHERE hostel_id = ? AND k_d_category = 1";
            $stmt = $mysqli->prepare($sql);
        
            if (!$stmt) {
                die("MySQLi prepare failed: " . $mysqli->error);
            }
        
            // Bind hostel_id parameter to the statement
            $stmt->bind_param('s', $hostel_id);
        
            if ($stmt->execute() === false) {
                die("MySQLi execute failed: " . $stmt->error);
            }
        
            $result = $stmt->get_result();
        // echo "jii";
            $assets = [];
            while ($row = $result->fetch_assoc()) {
                $category = kitchen_category($row['category'])[0]['category'];
                $asset = kitchen_asset($row['asset'])[0]['kitchen_asset'];
                // echo $row['created'];
                $created_date = date('d-m-Y', strtotime($row['created']));
                // Group assets by category

                // echo $created_date;
                if (!isset($assets[$category])) {
                    $assets[$category] = [];
                }
        
               
                if($row['big_small']==''){
                    $row['big_small']='-';
                }else if($row['big_small']=='big'){
                    $row['big_small']='Big';
                }else if($row['big_small']=='small'){
                    $row['big_small']='Small';
                }
        
                $assets[$category][] = [
                    'created' => $created_date,
                    'asset' => $asset,
                    'quantity' => $row['quantity'],
                    'big_small' => $row['big_small'],
                ];         
   }
        
            echo json_encode($assets);
            break;
        

            case 'fetch_Digital_assets':

                $hostel_id = $_POST['hostel_id'];
            
                // SQL query to fetch digital assets details using a prepared statement
                $sql = "SELECT asset, category, quantity, created FROM view_moveables_asset WHERE hostel_id = ? AND k_d_category = 2";
                $stmt = $mysqli->prepare($sql);
            
                if (!$stmt) {
                    die("MySQLi prepare failed: " . $mysqli->error);
                }
            
                // Bind hostel_id parameter to the statement
                $stmt->bind_param('s', $hostel_id);
            
                if ($stmt->execute() === false) {
                    die("MySQLi execute failed: " . $stmt->error);
                }
            
                $result = $stmt->get_result();
            
                $assets = [];
                while ($row = $result->fetch_assoc()) {
                    // Fetch category and asset using functions
                    $category = digital_category($row['category'])[0]['digital_category'];
                    $asset = Digital_asset($row['asset'])[0]['digital_asset'];
                    $created_date = date('d-m-Y', strtotime($row['created']));
                    // Group assets by category
                    if (!isset($assets[$category])) {
                        $assets[$category] = [];
                    }
            
                    $assets[$category][] = [
                        'created' => $created_date,
                        'asset' => $asset,
                        'quantity' => $row['quantity']
                    ];
                }
            
                echo json_encode($assets);
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

function get_reason($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "batch_creation";
    $where = [];
    $table_columns = [
        "reason",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        // "p1_unique_id"    => $unique_id,
    ];

    // if ($unique_id) {
    //     // $where              = [];
    //     $where["batch_no"] .= $unique_id;
    // }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['reason'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

function fetchStdEmisNo($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "std_app_emis_s3";
    $where = [];
    $table_columns = [
        "std_name",
        "emis_no",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        // "p1_unique_id"    => $unique_id,
    ];

    if ($unique_id) {
        // $where              = [];
        $where["s1_unique_id"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data;
    } else {
        print_r($amc_name_list);
        return 0;
    }
}


function fetchNoUmisName($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "std_app_umis_s4";
    $where = [];
    $table_columns = [
        "no_umis_name",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        // "p1_unique_id"    => $unique_id,
    ];

    if ($unique_id) {
        // $where              = [];
        $where["s1_unique_id"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['no_umis_name'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

function fetchUmisName($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "std_app_umis_s4";
    $where = [];
    $table_columns = [
        "umis_name",
        "umis_no",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        // "batch_no"  => $unique_id,
        // "p1_unique_id"    => $unique_id,
    ];

    if ($unique_id) {
        // $where              = [];
        $where["s1_unique_id"] .= $unique_id;
    }
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data;
    } else {
        print_r($amc_name_list);
        return 0;
    }
}



function image_view($folder_name = "", $unique_id = "", $doc_file_name = "")
{
    // echo $dc_file_name;
    $file_names = explode(',', $doc_file_name);
    $image_view = '';

    if ($doc_file_name) {
        foreach ($file_names as $file_key => $doc_file_name) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }

            $cfile_name = explode('.', $doc_file_name);

            if ($doc_file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="uploads/' . $folder_name . '/' . $doc_file_name . '"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="uploads/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }

    return $image_view;
}