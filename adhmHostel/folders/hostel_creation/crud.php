<?php

// Get folder Name From Currnent Url
$folder_name = explode('/', $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = 'hostel_name';

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$hostel_name = '';
$is_active = '';
$unique_id = '';
$prefix = '';

$data = '';
$msg = '';
$error = '';
$status = '';
$test = ''; // For Developer Testing Purpose

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
switch ($action) {
    case 'createupdate':
        // $mysqli = new mysqli('localhost', 'username', 'password', 'database');

        // // Check connection
        // if ($mysqli->connect_errno) {
        //     die("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        // }

        $token = $_POST['csrf_token'];

        // Validate CSRF token
        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        // Sanitize input values - You can replace filter_input with your sanitizeInput function
        $vali_hostel_name = isset($_POST['hostel_name']) ? sanitizeInput($_POST['hostel_name']) : null;
        $vali_hostel_id = isset($_POST['hostel_id']) ? sanitizeInput($_POST['hostel_id']) : null;
        $vali_district_name = isset($_POST['district_name']) ? sanitizeInput($_POST['district_name']) : null;
        $vali_taluk_name = isset($_POST['taluk_name']) ? sanitizeInput($_POST['taluk_name']) : null;
        $vali_special_tahsildar = isset($_POST['special_tahsildar']) ? sanitizeInput($_POST['special_tahsildar']) : null;
        $vali_assembly_const = isset($_POST['assembly_const']) ? sanitizeInput($_POST['assembly_const']) : null;
        $vali_parliment_const = isset($_POST['parliment_const']) ? sanitizeInput($_POST['parliment_const']) : null;
        $vali_address = isset($_POST['address']) ? sanitizeInput($_POST['address']) : null;
        $vali_hostel_location = isset($_POST['hostel_location']) ? sanitizeInput($_POST['hostel_location']) : null;
        $vali_urban_type = isset($_POST['urban_type']) ? sanitizeInput($_POST['urban_type']) : null;
        $vali_corporation = isset($_POST['corporation']) ? sanitizeInput($_POST['corporation']) : null;
        $vali_municipality = isset($_POST['municipality']) ? sanitizeInput($_POST['municipality']) : null;
        $vali_town_panchayat = isset($_POST['town_panchayat']) ? sanitizeInput($_POST['town_panchayat']) : null;
        $vali_block_name = isset($_POST['block_name']) ? sanitizeInput($_POST['block_name']) : null;
        $vali_village_name = isset($_POST['village_name']) ? sanitizeInput($_POST['village_name']) : null;
        $vali_hostel_type = isset($_POST['hostel_type']) ? sanitizeInput($_POST['hostel_type']) : null;
        $vali_gender_type = isset($_POST['gender_type']) ? sanitizeInput($_POST['gender_type']) : null;
        $vali_yob = isset($_POST['yob']) ? sanitizeInput($_POST['yob']) : null;
        $vali_sanctioned_strength = isset($_POST['sanctioned_strength']) ? sanitizeInput($_POST['sanctioned_strength']) : null;
        $vali_distance_btw_phc = isset($_POST['distance_btw_phc']) ? sanitizeInput($_POST['distance_btw_phc']) : null;
        $vali_phc_name = isset($_POST['phc_name']) ? sanitizeInput($_POST['phc_name']) : null;
        $vali_distance_btw_ps = isset($_POST['distance_btw_ps']) ? sanitizeInput($_POST['distance_btw_ps']) : null;
        $vali_staff_count = isset($_POST['staff_count']) ? sanitizeInput($_POST['staff_count']) : null;
        $vali_ps_name = isset($_POST['ps_name']) ? sanitizeInput($_POST['ps_name']) : null;
        $vali_hybrid_hostel = isset($_POST['hybrid_hostel']) ? sanitizeInput($_POST['hybrid_hostel']) : null;
        $vali_ownership = isset($_POST['ownership']) ? sanitizeInput($_POST['ownership']) : null;
        $vali_building_status = isset($_POST['building_status']) ? sanitizeInput($_POST['building_status']) : null;
        $vali_rental_reason = isset($_POST['rental_reason']) ? sanitizeInput($_POST['rental_reason']) : null;
        $is_active = isset($_POST['is_active']) ? sanitizeInput($_POST['is_active']) : 1;
        $unique_id = isset($_POST['unique_id']) ? sanitizeInput($_POST['unique_id']) : null;
        $hostel_upgrade = isset($_POST['hostel_upgrade']) ? sanitizeInput($_POST['hostel_upgrade']) : null;
        $allowedExts = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls'];
        // Check if file is uploaded and handle accordingly
        $go_attach_org_name = '';
        $go_attach_file = '';

        if (isset($_FILES['test_file']) && $_FILES['test_file']['error'] === UPLOAD_ERR_OK) {
           
            $extension = pathinfo($_FILES['test_file']['name'], PATHINFO_EXTENSION);

            if (in_array($extension, $allowedExts)) {
                $file_exp = explode('.', $_FILES['test_file']['name']);
                $tem_name = random_strings(25).'.'.$file_exp[1];
                move_uploaded_file($_FILES['test_file']['tmp_name'], '../../uploads/disbursement/'.$tem_name);
                $go_attach_file = $tem_name;
                $go_attach_org_name = $_FILES['test_file']['name'];
            } else {
                $msg = 'form_alert';
                exit('File type not allowed.');
            }
        }

        // Prepare SQL statement
        if (!$vali_hostel_name) {
            $msg = 'form_alert';
        } else {
            // Prepare SQL statement based on whether you're updating or inserting
            if ($unique_id) {
                if($go_attach_file){
                // Update existing record
                $sql = 'UPDATE hostel_name SET hostel_name=?, hostel_id=?, district_name=?, taluk_name=?, special_tahsildar=?, assembly_const=?, parliment_const=?, address=?, hostel_location=?, urban_type=?, corporation=?, municipality=?, town_panchayat=?, block_name=?, village_name=?, hostel_type=?, gender_type=?, yob=?, sanctioned_strength=?, distance_btw_phc=?, phc_name=?, distance_btw_ps=?, ps_name=?, staff_count=?, go_attach_org_name=?, go_attach_file=?, ownership=?, building_status=?, rental_reason=?, hybrid_hostel=?, is_active=?, hostel_upgrade=?, latitude=?, longitude=? WHERE unique_id=?';
                $stmt = $mysqli->prepare($sql);
                if ($stmt === false) {
                    $msg = 'error';
                    $error = $mysqli->error;
                } else {
                    $stmt->bind_param('sssssssssssssssssssssssssssssssssss', $vali_hostel_name, $vali_hostel_id, $vali_district_name, $vali_taluk_name, $vali_special_tahsildar, $vali_assembly_const, $vali_parliment_const, strtoupper($vali_address), $vali_hostel_location, $vali_urban_type, $vali_corporation, $vali_municipality, $vali_town_panchayat, $vali_block_name, $vali_village_name, $vali_hostel_type, $vali_gender_type, $vali_yob, $vali_sanctioned_strength, $vali_distance_btw_phc, $vali_phc_name, $vali_distance_btw_ps, $vali_ps_name, $vali_staff_count, $go_attach_org_name, $go_attach_file, $vali_ownership, $vali_building_status, $vali_rental_reason, $vali_hybrid_hostel, $is_active, $hostel_upgrade, $latitude, $longitude, $unique_id);
                    $stmt->execute();

                    if ($stmt->error) {
                        $msg = 'error';
                        $error = $stmt->error;
                    } else {
                        $msg = 'update';
                        $status = 'Success'; // Assuming success if no errors
                    }

                    $stmt->close();
                }
            }else{
                $sql = 'UPDATE hostel_name SET hostel_name=?, hostel_id=?, district_name=?, taluk_name=?, special_tahsildar=?, assembly_const=?, parliment_const=?, address=?, hostel_location=?, urban_type=?, corporation=?, municipality=?, town_panchayat=?, block_name=?, village_name=?, hostel_type=?, gender_type=?, yob=?, sanctioned_strength=?, distance_btw_phc=?, phc_name=?, distance_btw_ps=?, ps_name=?, staff_count=?, ownership=?, building_status=?, rental_reason=?, hybrid_hostel=?, is_active=?, hostel_upgrade=?, latitude=?, longitude=?  WHERE unique_id=?';
                $stmt = $mysqli->prepare($sql);
                if ($stmt === false) {
                    $msg = 'error';
                    $error = $mysqli->error;
                } else {
                    $stmt->bind_param('sssssssssssssssssssssssssssssssss', $vali_hostel_name, $vali_hostel_id, $vali_district_name, $vali_taluk_name, $vali_special_tahsildar, $vali_assembly_const, $vali_parliment_const, strtoupper($vali_address), $vali_hostel_location, $vali_urban_type, $vali_corporation, $vali_municipality, $vali_town_panchayat, $vali_block_name, $vali_village_name, $vali_hostel_type, $vali_gender_type, $vali_yob, $vali_sanctioned_strength, $vali_distance_btw_phc, $vali_phc_name, $vali_distance_btw_ps, $vali_ps_name, $vali_staff_count, $vali_ownership, $vali_building_status, $vali_rental_reason, $vali_hybrid_hostel, $is_active, $hostel_upgrade, $latitude, $longitude, $unique_id);
                    $stmt->execute();

                    if ($stmt->error) {
                        $msg = 'error';
                        $error = $stmt->error;
                    } else {
                        $msg = 'update';
                        $status = 'Success'; // Assuming success if no errors
                    }

                    $stmt->close();
                }
            }
            } else {
                // Insert new record

                $sql = 'INSERT INTO hostel_name (hostel_name, hostel_id, district_name, taluk_name, special_tahsildar, assembly_const, parliment_const, address, hostel_location, urban_type, corporation, municipality, town_panchayat, block_name, village_name, hostel_type, gender_type, yob, sanctioned_strength, distance_btw_phc, phc_name, distance_btw_ps, staff_count, go_attach_org_name, go_attach_file, ownership, building_status, rental_reason, hybrid_hostel, is_active, unique_id, ps_name, hostel_upgrade, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

                $stmt = $mysqli->prepare($sql);

                if ($stmt === false) {
                    $msg = 'error';

                    $error = $mysqli->error;
                } else {
                    $stmt->bind_param('sssssssssssssssssssssssssssssssssss', $vali_hostel_name, $vali_hostel_id, $vali_district_name, $vali_taluk_name, $vali_special_tahsildar, $vali_assembly_const, $vali_parliment_const, strtoupper($vali_address), $vali_hostel_location, $vali_urban_type, $vali_corporation, $vali_municipality, $vali_town_panchayat, $vali_block_name, $vali_village_name, $vali_hostel_type, $vali_gender_type, $vali_yob, $vali_sanctioned_strength, $vali_distance_btw_phc, $vali_phc_name, $vali_distance_btw_ps, $vali_staff_count, $go_attach_org_name, $go_attach_file, $vali_ownership, $vali_building_status, $vali_rental_reason, $vali_hybrid_hostel, $is_active, unique_id($prefix), $vali_ps_name, $hostel_upgrade, $latitude, $longitude);
                    $stmt->execute();

                    if ($stmt->error) {
                        $msg = 'error';
                        $error = $stmt->error;
                    } else {
                        $msg = 'create';
                        $status = 'Success'; // Assuming success if no errors
                    }

                    $stmt->close();
                }
            }
        }

        // Prepare JSON response
        $json_array = [
            'status' => $status ?? '',
            'error' => $error ?? '',
            'msg' => $msg ?? '',
        ];

        echo json_encode($json_array);

        // Close MySQL connection
        $mysqli->close();

        break;

        case 'hostel_upgrade_sub':
          
    
            
    
            // Sanitize input values - You can replace filter_input with your sanitizeInput function
            $vali_go_no = isset($_POST['go_no']) ? sanitizeInput($_POST['go_no']) : null;
            $vali_go_date = isset($_POST['go_date']) ? sanitizeInput($_POST['go_date']) : null;
            $vali_go_abstract = isset($_POST['go_abstract']) ? sanitizeInput($_POST['go_abstract']) : null;
            $vali_go_attachment = isset($_POST['go_attachment']) ? sanitizeInput($_POST['go_attachment']) : null;
            $vali_old_hostel_name = isset($_POST['old_hostel_name']) ? sanitizeInput($_POST['old_hostel_name']) : null;
            $vali_old_sanc_cnt = isset($_POST['old_sanc_cnt']) ? sanitizeInput($_POST['old_sanc_cnt']) : null;
            $vali_old_hostel_type = isset($_POST['old_hostel_type']) ? sanitizeInput($_POST['old_hostel_type']) : null;
            $vali_old_hostel_gender = isset($_POST['old_hostel_gender']) ? sanitizeInput($_POST['old_hostel_gender']) : null;
            $vali_hostel_upgrade = isset($_POST['hostel_upgrade']) ? sanitizeInput($_POST['hostel_upgrade']) : null;
           
            $hostel_unique_id = isset($_POST['unique_id']) ? sanitizeInput($_POST['unique_id']) : null;


            
            $allowedExts = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'PNG']; // Added 'xlsx' and other types if needed

            // Check if file is uploaded and handle accordingly
            $go_attach_org_name = '';
            $go_attach_file = '';
    
            if (isset($_FILES['go_attachment']) && $_FILES['go_attachment']['error'] === UPLOAD_ERR_OK) {
               
                $extension = pathinfo($_FILES['go_attachment']['name'], PATHINFO_EXTENSION);
    
                if (in_array($extension, $allowedExts)) {
                    $file_exp = explode('.', $_FILES['go_attachment']['name']);
                    $tem_name = random_strings(25).'.'.$file_exp[1];
                    move_uploaded_file($_FILES['go_attachment']['tmp_name'], '../../uploads/hostel_upgrade_docs/'.$tem_name);
                    $go_attach_file = $tem_name;
                    $go_attach_org_name = $_FILES['go_attachment']['name'];
                } else {
                    $msg = 'form_alert';
                    exit('File type not allowed.');
                }
            }
    
            $sql_update = 'UPDATE hostel_name SET hostel_upgrade="'.$_POST['hostel_upgrade'].'" WHERE unique_id="'.$_POST['unique_id'].'"';
                $stmt_update = $mysqli->prepare($sql_update);
                $stmt_update->execute();
          
          
                    // Insert new record
    
                    $sql = 'INSERT INTO hostel_upgrade (hostel_unique_id, go_no, go_date, go_abstract, go_attachment, old_hostel_name, old_sanc_cnt, old_hostel_type, old_hostel_gender, hostel_upgrade,unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    
                    $stmt = $mysqli->prepare($sql);
    
                    if ($stmt === false) {
                        $msg = 'error';
    
                        $error = $mysqli->error;
                    } else {
                        $stmt->bind_param('sssssssssss', $hostel_unique_id, $vali_go_no, $vali_go_date, $vali_go_abstract, $go_attach_file, $vali_old_hostel_name, $vali_old_sanc_cnt, $vali_old_hostel_type, $vali_old_hostel_gender, $vali_hostel_upgrade, unique_id($prefix));
                        $stmt->execute();
    
                        if ($stmt->error) {
                            $msg = 'error';
                            $error = $stmt->error;
                        } else {
                            $msg = 'create';
                            $status = 'Success'; // Assuming success if no errors
                        }
    
                        $stmt->close();
                    }
                
          
    
            // Prepare JSON response
            $json_array = [
                'status' => $status ?? '',
                'error' => $error ?? '',
                'msg' => $msg ?? '',
            ];
    
            echo json_encode($json_array);
    
            // Close MySQL connection
            $mysqli->close();
    
            break;


            case 'hostel_upgrade_datatable':
                // DataTable Variables
                $length = $_POST['length'];
                $start = $_POST['start'];
                $draw = $_POST['draw'];
                $limit = $length == '-1' ? '' : $length;
        
                $data = [];

        
                $columns = [
                    '@a:=@a+1 s_no',
                    "go_no",
                    "go_attachment",
                    'unique_id',
                ];
                $table_upgrade = 'hostel_upgrade';
                $table_details = $table_upgrade.' , (SELECT @a:= ?) AS a ';
                $where = 'is_delete = 0 and hostel_unique_id = "'.$_POST['hostel_unique_id'].'"';
        
                $bind_params = 'i';
                $bind_values = [$start];
        
               
        
                $sql = 'SELECT SQL_CALC_FOUND_ROWS '.implode(', ', $columns).' FROM '.$table_details.' WHERE '.$where;
        
                if (!empty($limit)) {
                    $sql .= ' LIMIT ?, ?';
                    $bind_params .= 'ii'; // Add types for integer parameters
                    $bind_values[] = intval($start);
                    $bind_values[] = intval($length);
                }
        
                // Prepare the statement
                $stmt = $mysqli->prepare($sql);
        
               
        
                // Bind parameters if there are any
                if (!empty($bind_params)) {
                    $stmt->bind_param($bind_params, ...$bind_values);
                }
        
                // Execute the statement
                $stmt->execute();
                $result = $stmt->get_result();
        
                // Fetch total records count using FOUND_ROWS()
                $total_records_result = $mysqli->query('SELECT FOUND_ROWS() as total');
                $total_records = $total_records_result->fetch_assoc()['total'];
        
                if ($result) {
                    $res_array = $result->fetch_all(MYSQLI_ASSOC);
        
                    foreach ($res_array as $key => $value) {
        
                        
        $value['go_attachment'] = image_view($value['go_attachment']);
                        
                        $btn_delete = btn_delete("hostel_upgrade", $value['unique_id']);
        
                        if ($value['unique_id'] == '5f97fc3257f2525529') {
                            $btn_update = '';
                            $btn_delete = '';
                        }
        
                        $value['unique_id'] = $btn_update.$btn_delete;
                        $data[] = array_values($value);
                    }
        
                    $json_array = [
                        'draw' => intval($draw),
                        'recordsTotal' => intval($total_records),
                        'recordsFiltered' => intval($total_records),
                        'data' => $data,
                        // "testing" => $result->sql
                    ];
                } else {
                    exit('Query execution failed: '.$mysqli->error);
                }
        
                echo json_encode($json_array);
                break;

    case 'datatable':
        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length == '-1' ? '' : $length;

        $data = [];

        $columns = [
            '@a:=@a+1 s_no',
            "district_name",
            "taluk_name",
            "hostel_id",
            'hostel_name',
            'go_attach_file',
            'entrance_image',
            'dining_image',
            'building_image',
            
            'unique_id',
        ];
        $table_details = $table.' , (SELECT @a:= ?) AS a ';
        $where = 'is_delete = 0 and unique_id = "'.$_SESSION['hostel_id'].'"';

        $bind_params = 'i';
        $bind_values = [$start];

        if (!empty($_POST['district_name'])) {
            $where .= ' AND district_name = ?';
            $bind_params .= 's';
            $bind_values[] = $_POST['district_name'];
        }

        if (!empty($_POST['taluk_name'])) {
            $where .= ' AND taluk_name = ?';
            $bind_params .= 's';
            $bind_values[] = $_POST['taluk_name'];
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS '.implode(', ', $columns).' FROM '.$table_details.' WHERE '.$where;

        if (!empty($limit)) {
            $sql .= ' LIMIT ?, ?';
            $bind_params .= 'ii'; // Add types for integer parameters
            $bind_values[] = intval($start);
            $bind_values[] = intval($length);
        }

        // Prepare the statement
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            exit('Error in preparing SQL statement: '.$mysqli->error);
        }

        // Bind parameters if there are any
        if (!empty($bind_params)) {
            $stmt->bind_param($bind_params, ...$bind_values);
        }

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records count using FOUND_ROWS()
        $total_records_result = $mysqli->query('SELECT FOUND_ROWS() as total');
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {
            $res_array = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($res_array as $key => $value) {

                $value['district_name'] = district_name($value['district_name'])[0]['district_name'];
                $value['taluk_name'] = taluk_name($value['taluk_name'])[0]['taluk_name'];
                $value['hostel_name'] = disname($value['hostel_name']);
                $value['is_active'] = is_active_show($value['is_active']);

                if($value['go_attach_file']){
                    $value['go_attach_file'] = image_view_hostel($value['go_attach_file']);
                }else{
                    $value['go_attach_file'] = '-';
                }

                $value['entrance_image'] = isset($value['entrance_image']) ? image_view_hostel($value['entrance_image']) : '-';
                $value['dining_image'] = isset($value['dining_image']) ? image_view_hostel($value['dining_image']) : '-';
                $value['building_image'] = isset($value['building_image']) ? image_view_hostel($value['building_image']) : '-';


                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                if ($value['unique_id'] == '5f97fc3257f2525529') {
                    $btn_update = '';
                    $btn_delete = '';
                }

                $value['unique_id'] = $btn_update;
                $data[] = array_values($value);
            }

            $json_array = [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_records),
                'recordsFiltered' => intval($total_records),
                'data' => $data,
                // "testing" => $result->sql
            ];
        } else {
            exit('Query execution failed: '.$mysqli->error);
        }

        echo json_encode($json_array);
        break;


        case 'hostel_upgrade_delete':
           
            $unique_id = $_POST['unique_id'];
            $hostel_unique_id = $_POST['hostel_unique_id'];
    
            // Update specific record
            $is_delete = '1';
            $sql = 'UPDATE hostel_upgrade SET is_delete = ? WHERE unique_id = ?';
    
            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'Error preparing statement: '.$mysqli->error]);
                exit;
            }
    
            $stmt->bind_param('ss', $is_delete, $unique_id);
    
            $action_result = $stmt->execute();

            $sub_cnt = get_sub_cnt($hostel_unique_id);
            if($sub_cnt == '0'){
                $sql_main = 'UPDATE hostel_name SET hostel_upgrade = "No" WHERE unique_id = "'.$hostel_unique_id.'"';
            $stmt_main = $mysqli->prepare($sql_main);
            $stmt_main->execute();

            }


            $stmt->close();
    
            if ($action_result) {
                $status = true;
                $msg = 'success_delete';
            } else {
                $status = false;
                $msg = 'error';
            }
    
            $json_array = [
                'status' => $status,
                'msg' => $msg,
                'sub_cnt' => $sub_cnt,
            ];
    
            echo json_encode($json_array);
    
            $mysqli->close();
            break;

    case 'delete':
        $token = $_POST['csrf_token'];

        if (!validateCSRFToken($token)) {
            exit('CSRF validation failed.');
        }
        $unique_id = $_POST['unique_id'];

        // Update specific record
        $is_delete = '1';
        $sql = 'UPDATE hostel_name SET is_delete = ? WHERE unique_id = ?';

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Error preparing statement: '.$mysqli->error]);
            exit;
        }

        $stmt->bind_param('ss', $is_delete, $unique_id);

        $action_result = $stmt->execute();
        $stmt->close();

        if ($action_result) {
            $status = true;
            $msg = 'success_delete';
        } else {
            $status = false;
            $msg = 'error';
        }

        $json_array = [
            'status' => $status,
            'msg' => $msg,
        ];

        echo json_encode($json_array);

        $mysqli->close();
        break;

    case 'get_taluk_name':
        $district_name = $_POST['district_name'];

        $district_options = taluk_name('', $district_name);

        $hostel_taluk_options = select_option($district_options, 'Select Taluk');

        echo $hostel_taluk_options;

        break;

    case 'get_assembly':
        $district_name = $_POST['district_name'];

        $district_options = assembly_constituency('', $district_name);

        $assembly_options = select_option($district_options, 'Select Assembly Constituency');

        echo $assembly_options;

        break;

    case 'get_parliament':
        $district_name = $_POST['district_name'];

        $district_options = parliment_constituency('', $district_name);

        $parliament_options = select_option($district_options, 'Select Parliament Constituency');

        echo $parliament_options;

        break;

    case 'get_block':
        $district_name = $_POST['district_name'];

        $district_options = block('', $district_name);

        $block_options = select_option($district_options, 'Select Block Name');

        echo $block_options;

        break;

    case 'get_village':
        $block_name = $_POST['block_name'];

        $block_options = village_name('', $block_name);

        $village_options = select_option($block_options, 'Select Village Name');

        echo $village_options;

        break;

    case 'get_corporation':
        $district_name = $_POST['district_name'];

        $district_options = corporation('', $district_name);

        $corporation_options = select_option($district_options, 'Select Corporation Name');

        echo $corporation_options;

        break;

    case 'get_municipality':
        $district_name = $_POST['district_name'];

        $district_options = municipality('', $district_name);

        $municipality_options = select_option($district_options, 'Select Municipality Name');

        echo $municipality_options;

        break;

    case 'get_town_panchayat':
        $district_name = $_POST['district_name'];

        $district_options = town_panchayat('', $district_name);

        $town_panchayat_options = select_option($district_options, 'Select Town Panchayat Name');

        echo $town_panchayat_options;

        break;

    case 'district_id':
        $district_id = $_POST['district_id'];

        $district_id_options = taluk_name('', $district_id);

        $taluk_name_options = select_option($district_id_options, 'Select Taluk');

        echo $taluk_name_options;
        // print_r($taluk_name_options);

        break;

        case 'hostel_update':

            $vali_special_tahsildar = isset($_POST['special_tahsildar']) ? sanitizeInput($_POST['special_tahsildar']) : null;
            $vali_assembly_const = isset($_POST['assembly_const']) ? sanitizeInput($_POST['assembly_const']) : null;
            $vali_parliment_const = isset($_POST['parliment_const']) ? sanitizeInput($_POST['parliment_const']) : null;
            $vali_hostel_location = isset($_POST['hostel_location']) ? sanitizeInput($_POST['hostel_location']) : null;
            $vali_urban_type = isset($_POST['urban_type']) ? sanitizeInput($_POST['urban_type']) : null;
            $vali_corporation = isset($_POST['corporation']) ? sanitizeInput($_POST['corporation']) : null;
            $vali_municipality = isset($_POST['municipality']) ? sanitizeInput($_POST['municipality']) : null;
            $vali_town_panchayat = isset($_POST['town_panchayat']) ? sanitizeInput($_POST['town_panchayat']) : null;
            $vali_block_name = isset($_POST['block_name']) ? sanitizeInput($_POST['block_name']) : null;
            $vali_village_name = isset($_POST['village_name']) ? sanitizeInput($_POST['village_name']) : null;
            $vali_yob = isset($_POST['yob']) ? sanitizeInput($_POST['yob']) : null;
            $vali_distance_btw_phc = isset($_POST['distance_btw_phc']) ? sanitizeInput($_POST['distance_btw_phc']) : null;
            $vali_phc_name = isset($_POST['phc_name']) ? sanitizeInput($_POST['phc_name']) : null;
            $vali_distance_btw_ps = isset($_POST['distance_btw_ps']) ? sanitizeInput($_POST['distance_btw_ps']) : null;
            $vali_staff_count = isset($_POST['staff_count']) ? sanitizeInput($_POST['staff_count']) : null;
            $vali_ps_name = isset($_POST['ps_name']) ? sanitizeInput($_POST['ps_name']) : null;
            $unique_id = isset($_POST['unique_id']) ? sanitizeInput($_POST['unique_id']) : null;
            $latitude = isset($_POST['latitude']) ? sanitizeInput($_POST['latitude']) : null;
            $longitude = isset($_POST['longitude']) ? sanitizeInput($_POST['longitude']) : null;
            $address = isset($_POST['address']) ? sanitizeInput($_POST['address']) : null;
            $ownership = isset($_POST['ownership']) ? sanitizeInput($_POST['ownership']) : null;
            $rental_reason = isset($_POST['rental_reason']) ? sanitizeInput($_POST['rental_reason']) : null;
            $building_status = isset($_POST['building_status']) ? sanitizeInput($_POST['building_status']) : null;
    
            $allowedExts = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls'];
    
            // Handle file upload
            $go_attach_org_name = '';
            $go_attach_file = '';
    
            if (isset($_FILES['test_file']) && $_FILES['test_file']['error'] === UPLOAD_ERR_OK) {
                $extension = pathinfo($_FILES['test_file']['name'], PATHINFO_EXTENSION);
    
                if (in_array($extension, $allowedExts)) {
                    $tem_name = random_strings(25) . '.' . $extension;
                    move_uploaded_file($_FILES['test_file']['tmp_name'], '../../uploads/hostel_creation/' . $tem_name);
                    $go_attach_file = $tem_name;
                    $go_attach_org_name = $_FILES['test_file']['name'];
                } else {
                    exit(json_encode(['status' => 'error', 'msg' => 'File type not allowed.']));
                }
            }

             if (isset($_POST['entrance_image']) && !empty($_POST['entrance_image'])) {
            $data = $_POST['entrance_image'];

            // Remove the prefix 'data:image/png;base64,'
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $decodedData = base64_decode($data);

            if ($decodedData === false) {
                echo "Failed to decode base64 data.";
                die();
            }

            $entrance_image = random_strings(25) . '.png';

            $uploadDir = '../../uploads/hostel_creation/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filePath = '../../uploads/hostel_creation/' . $entrance_image;

            // Save the file
            if (file_put_contents($filePath, $decodedData)) {
                // echo "Image saved successfully at $filePath";

                // Optionally store $filePath in DB
            } else {
                echo "Failed to save image.";
            }
        } else {
            echo "No image captured";
        }

        if (isset($_POST['dining_image']) && !empty($_POST['dining_image'])) {
            $data = $_POST['dining_image'];

            // Remove the prefix 'data:image/png;base64,'
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $decodedData = base64_decode($data);

            if ($decodedData === false) {
                echo "Failed to decode base64 data.";
                die();
            }

            $dining_image = random_strings(25) . '.png';

            $uploadDir = '../../uploads/hostel_creation/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filePath = '../../uploads/hostel_creation/' . $dining_image;


            // Save the file
            if (file_put_contents($filePath, $decodedData)) {
                // echo "Image saved successfully at $filePath";

                // Optionally store $filePath in DB
            } else {
                echo "Failed to save image.";
            }
        } else {
            echo "No image captured";
        }

        if (isset($_POST['building_image']) && !empty($_POST['building_image'])) {
            $data = $_POST['building_image'];

            // Remove the prefix 'data:image/png;base64,'
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $decodedData = base64_decode($data);

            // if ($decodedData === false) {
            //     echo "Failed to decode base64 data.";
            //     die();
            // }

            $building_image = random_strings(25) . '.png';

            $uploadDir = '../../uploads/hostel_creation/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filePath = '../../uploads/hostel_creation/' . $building_image;

            // Save the file
            if (file_put_contents($filePath, $decodedData)) {
                // echo "Image saved successfully at $filePath";

            } else {
                echo "Failed to save image.";
            }
        } else {
            echo "No image captured";
        }



    
            if (!$unique_id) {
                $msg = 'form_alert';
                $status = 'Error';
                $error = 'Unique ID is missing.';
            } else {
                // Update query
                if ($go_attach_file) {
                    $sql = 'UPDATE hostel_name
                                SET special_tahsildar=?, assembly_const=?, parliment_const=?, hostel_location=?, 
                                    urban_type=?, corporation=?, municipality=?, town_panchayat=?, block_name=?, 
                                    village_name=?, yob=?, distance_btw_phc=?, phc_name=?, distance_btw_ps=?, ps_name=?, 
                                    staff_count=?, go_attach_org_name=?, go_attach_file=?, latitude=?, longitude=?, address=?, ownership=?, rental_reason=?, building_status=?,entrance_image=?,dining_image=?,building_image=?
                                WHERE unique_id=?';
                    $stmt = $mysqli->prepare($sql);
    
                    if ($stmt === false) {
                        $msg = 'error';
                        $error = $mysqli->error;
                    } else {
                        $stmt->bind_param(
                            'ssssssssssssssssssssssssssss',
                            $vali_special_tahsildar,
                            $vali_assembly_const,
                            $vali_parliment_const,
                            $vali_hostel_location,
                            $vali_urban_type,
                            $vali_corporation,
                            $vali_municipality,
                            $vali_town_panchayat,
                            $vali_block_name,
                            $vali_village_name,
                            $vali_yob,
                            $vali_distance_btw_phc,
                            $vali_phc_name,
                            $vali_distance_btw_ps,
                            $vali_ps_name,
                            $vali_staff_count,
                            $go_attach_org_name,
                            $go_attach_file,
                            $latitude,
                            $longitude,
                            $address,
                            $ownership,
                            $rental_reason,
                            $building_status,
                            $entrance_image,
                            $dining_image,
                            $building_image,
                            $unique_id
                        );
    
                        $stmt->execute();
    
                        if ($stmt->error) {
                            $msg = 'error';
                            $error = $stmt->error;
                        } else {
                            $msg = 'update';
                            $status = 'Success';
                        }
    
                        $stmt->close();
                    }
                } else {
                    $sql = 'UPDATE hostel_name
                                SET special_tahsildar=?, assembly_const=?, parliment_const=?, hostel_location=?, 
                                    urban_type=?, corporation=?, municipality=?, town_panchayat=?, block_name=?, 
                                    village_name=?, yob=?, distance_btw_phc=?, phc_name=?, distance_btw_ps=?, ps_name=?, 
                                    staff_count=?, latitude=?, longitude=?, address=?, ownership=?, rental_reason=?, building_status=?,entrance_image=?,dining_image=?,building_image=?
                                WHERE unique_id=?';
                    $stmt = $mysqli->prepare($sql);
    
                    if ($stmt === false) {
                        $msg = 'error';
                        $error = $mysqli->error;
                    } else {
                        $stmt->bind_param(
                            'ssssssssssssssssssssssssss',
                            $vali_special_tahsildar,
                            $vali_assembly_const,
                            $vali_parliment_const,
                            $vali_hostel_location,
                            $vali_urban_type,
                            $vali_corporation,
                            $vali_municipality,
                            $vali_town_panchayat,
                            $vali_block_name,
                            $vali_village_name,
                            $vali_yob,
                            $vali_distance_btw_phc,
                            $vali_phc_name,
                            $vali_distance_btw_ps,
                            $vali_ps_name,
                            $vali_staff_count,
                            $latitude,
                            $longitude,
                            $address,
                            $ownership,
                            $rental_reason,
                            $building_status,
                            $entrance_image,
                            $dining_image,
                            $building_image,
                            $unique_id
                        );
    
                        $stmt->execute();
    
                        if ($stmt->error) {
                            $msg = 'error';
                            $error = $stmt->error;
                        } else {
                            $msg = 'update';
                            $status = 'Success';
                        }
    
                        $stmt->close();
                    }
                }
            }
    
            // Prepare JSON response
            $json_array = [
                'status' => $status ?? '',
                'error' => $error ?? '',
                'msg' => $msg ?? '',
                'location' => $vali_hostel_location,
            ];
    
            echo json_encode($json_array);
    
            // Close MySQL connection
            $mysqli->close();
    
            break;

            case 'get_special_tahsildar':

                $district_name = $_POST['district_name'];
        
                $district_options = special_tahsildar('', $district_name);
        
                $hostel_special_tahsildar_options = select_option($district_options, 'Select Special Tahsildar');
        
                echo $hostel_special_tahsildar_options;
        
                break;

    default:
        break;
}

function get_sub_cnt($unique_id = "")
{
    global $pdo;

    $table_name = "hostel_upgrade";
    $where = 'hostel_unique_id = "'.$unique_id.'" and is_delete = "0"';

    // if ($unique_id) {
    //     $where["hostel_1"] = $unique_id; // Corrected the way unique_id is added to the where clause
    // }

    $table_columns = [
        "count(id) as tot_cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    // Use the select method from $pdo to query the database
    $amc_name_list = $pdo->select($table_details, $where);

    // print_r($amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['tot_cnt'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

function image_view($doc_file_name = "")
{
    $image_view = "";

            $cfile_name = explode('.', $doc_file_name);

            if ($doc_file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg') || ($cfile_name[1] == 'PNG')) {
                    // echo "dd";
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../adhmAdmin/uploads/hostel_upgrade_docs/' . $doc_file_name . '"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="../assets/images/pdf.png"   width="10%" style="margin-left: 15px;" ></a>';
                } 
                // else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/excel.png"  height="30px" width="30px" ></a>';
                // } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/word.png"  height="30px" width="30px" ></a>';
                // }
            }
            return $image_view;
        }


        function image_view_hostel($doc_file_name = "")
{
    $image_view = "";

            $cfile_name = explode('.', $doc_file_name);

            if ($doc_file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg') || ($cfile_name[1] == 'PNG')) {
                    // echo "dd";
                    $image_view .= '<a href="javascript:print_view_image(\'/' . $doc_file_name . '\')"> <img src="uploads/hostel_creation/' . $doc_file_name . '" style="width:50px; height:50px;" /></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf_go(\'/' . $doc_file_name . '\')"><img src="../assets/images/pdf.png" style="margin-left: 15px; width:35px; height:40px;"; ></a>';
                } 
               
            }
            return $image_view;
        }
