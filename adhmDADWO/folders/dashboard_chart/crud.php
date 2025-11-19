<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
// $table             = "leave_application";
$table_std_app = "std_app_p1";
$table = "holiday_creation";
$table_sub = "complaint_creation_doc_upload";
$table_stage_1 = "stage_1";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$unique_id = $_GET["unique_id"];


$fund_name = "";
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

        $feedback_name = $_POST["feedback_name"];
        $rating = $_POST["rating3"];
        $description = $_POST["description"];
        $student_id = $_POST["student_id"];
        $district_id = $_POST['district_id'];
        $taluk_id = $_POST['taluk_id'];
        $hostel_id = $_POST['hostel_id'];

        $date = $_POST['date'];
        // $is_active          = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];



        $update_where = "";

        $columns = [
            "feedback_name" => $feedback_name,
            "rating" => $rating,
            "description" => $description,
            "student_id" => $student_id,
            "district_id" => $district_id,

            "taluk_id" => $taluk_id,
            "hostel_id" => $hostel_id,

            // "is_active"           => $is_active,
            "unique_id" => unique_id($prefix)
        ];

        // check already Exist Or not
        $table_details1 = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];

        // $where1 = "from_year = '".$from_year."' AND to_year ='".$to_year."' AND amc_year ='".$amc_year."' ";

        $result = $pdo->select($table_details1, $where1);

        $res_array = $result->data;



        // print_r($res_array);die();


        if ($unique_id) {

            unset($columns['unique_id']);

            $update_where = [
                "unique_id" => $unique_id
            ];

            $action_obj = $pdo->update($table, $columns, $update_where);



        } else {
            // if($res_array[0]['count'] == 0){
            $action_obj = $pdo->insert($table, $columns);

            // $msg = "already";
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


        $json_array = [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "msg" => $msg,
            "sql" => $sql
        ];

        echo json_encode($json_array);

        break;


        case 'get_application_counts':
            $json_array = "";
        $date_type = $_POST['date_type'];
    
        
    
            if($date_type == '1'){
                $currentDate = date('Y-m-d');
                $from_date = date('Y-m-d', strtotime('monday last week', strtotime($currentDate)));
                $to_date = date('Y-m-d', strtotime('sunday last week', strtotime($currentDate)));
                $where_date = "and entry_date >= '".$from_date."' and entry_date <= '".$to_date."'"; 
               
            }else if($date_type == '2'){
                $currentDate = date('Y-m-d');
                $from_date = date('Y-m-d', strtotime('first day of last month', strtotime($currentDate)));
                $to_date = date('Y-m-d', strtotime('last day of last month', strtotime($currentDate)));
                $where_date = "and entry_date >= '".$from_date."' and entry_date <= '".$to_date."'"; 
    
                
            }else if($date_type == '3'){
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                if($from_date){
                    $where_date = "and entry_date >= '".$from_date."'";
                }
                if($to_date){
                    $where_date .= "and entry_date <= '".$to_date."'";
    
                }
            }
    
    
            // Define the columns to be fetched
            $columns = [
                "district_name",
                "(select ifnull(SUM(new_count),0) from view_chart_new_count where view_chart_new_count.district_name = district_name.district_name $where_date) as new_count",
                "(select ifnull(SUM(new_count),0) from view_chart_renewal_count where view_chart_renewal_count.district_name = district_name.district_name $where_date) as renewal_cnt",
            ];
        
            // Define table details
            $table_details = [
                "district_name",
                $columns
            ];
        
            // Construct the WHERE clause
            // Adjusted to match your specific requirement
            $where = "district_name != '' and is_delete = '0'";
            $group = "GROUP BY district_name";
        
            // Fetch the data
            try {
                $result = $pdo->select($table_details, $where . " " . $group);
                // print_r($result);
                // Initialize response variables
                $data = [];
                $district_name = [];
                // $new_count = [];
        
                if ($result->status) {
                    $res_array = $result->data;
        
                    foreach ($res_array as $value) {
                        // $data[] = [
                        //     'district_name' => $value['district_name'],
                        //     'new_count' => $value['new_count']
                        // ];
                        $district_name[] =$value['district_name'];
                       
                        $new_count[] = $value['new_count'];
                        $renewal_cnt[] = '-'.$value['renewal_cnt'];
                       
                    }
                }
        
                // Construct the JSON response
                $json_array = [
                    "status" => $result->status,
                    "district_name" => $district_name,
                    "new_count" => $new_count,
                    "renewal_cnt" => $renewal_cnt,
                    // "data" => $data,
                    "error" => $result->error ?? null,
                    "msg" => $result->status ? "Data fetched successfully" : "Failed to fetch data",
                ];
            } catch (Exception $e) {
                // Handle exceptions and errors
                $json_array = [
                    "status" => false,
                    "data" => [],
                    "error" => $e->getMessage(),
                    "msg" => "An error occurred",
                ];
            }
        
            // Send the JSON response
            echo json_encode($json_array);
        
            break;

    case 'district_name':
        $district_name = $_POST['district_name'];

        $district_options = taluk_name('', $district_name);

        $hostel_taluk_options = select_option($district_options, 'Select Taluk');

        echo $hostel_taluk_options;

        break;

    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option($hostel_name_options, "Select Hostel");
        // print_r( $hostel_name_options);

        echo $hostel_name_options;

        break;

        case 'get_chart_data':

            $district_id = $_POST['district_id'];
            $json_array = [];
            $columns = [
                "COUNT(unique_id) AS school_count",
                "(SELECT COUNT(unique_id) FROM std_app_s WHERE student_type = '65f00a327c08582160' AND hostel_district_1='".$district_id."' group by hostel_district_1) AS iti_count ",
                "(SELECT COUNT(unique_id) FROM std_app_s WHERE student_type = '65f00a495599589293' AND hostel_district_1='".$district_id."' group by hostel_district_1) AS college_ug_count",
                "(SELECT COUNT(unique_id) FROM std_app_s WHERE student_type = '65f00a53eef3015995' AND hostel_district_1='".$district_id."' group by hostel_district_1) AS college_pg_count",
                "(SELECT COUNT(unique_id) FROM std_app_s WHERE student_type = '65f00a3e3c9a337012' AND hostel_district_1='".$district_id."' group by hostel_district_1) AS diplomo_count",
            ];
            $table_details = [
                "std_app_s",
                $columns
            ];
            $where = "is_delete = 0  AND student_type = '65f00a259436412348' AND hostel_district_1='".$district_id."' group by hostel_district_1";
        
            $result = $pdo->select($table_details, $where);
            // print_r($result);
            if ($result->status) {
                $res_array = $result->data;
        
                // Initialize variables
                $school_count = 0;
                $iti_count = 0;
                $college_ug_count = 0;
                $college_pg_count = 0;
                $diplomo_count = 0;
        
                foreach ($res_array as $value) {
                    $school_count = $value['school_count'];
                    $iti_count = $value['iti_count'];
                    $college_ug_count = $value['college_ug_count'];
                    $college_pg_count = $value['college_pg_count'];
                    $diplomo_count = $value['diplomo_count'];
                }
            }
        if($school_count==''){
            $school_count=0;

        }
        if($iti_count==''){
            $iti_count=0;

        }
        if($college_ug_count==''){
            $college_ug_count=0;

        }
        if($college_pg_count==''){
            $college_pg_count=0;

        }
        if($diplomo_count==''){
            $diplomo_count=0;

        }

            $json_array = [
                "status" => $result->status,
                "data" => $data ?? [],
                "error" => $result->error ?? null,
                "msg" => $result->status ? "Data fetched successfully" : "Failed to fetch data",
                "school_count" => $school_count,
                "iti_count" => $iti_count,
                "college_ug_count" => $college_ug_count,
                "college_pg_count" => $college_pg_count,
                "diplomo_count" => $diplomo_count,
                "sql" => $sql ?? null
            ];
        
            echo json_encode($json_array);
            break;
        
            case 'pending_application_chart_data':

                $district_id = $_POST['district_id'];
                $json_array = "";
                $columns = [
                    "COUNT(id) as new_count", 
                    "(SELECT COUNT(id) as count FROM std_app_s WHERE  is_delete = 0 AND application_type = 2 AND hostel_district_1='".$district_id."' GROUP by hostel_district_1) AS renewal_count" 
                ];
                $table_details = [
                    "std_app_s",
                    $columns
                ];
                $where = "is_delete = 0 AND application_type = 1 AND hostel_district_1='".$district_id."' GROUP by hostel_district_1 ";
            
                $result = $pdo->select($table_details, $where);
                // print_r($result);
                if ($result->status) {
                    $res_array = $result->data;
            
                    // Initialize variables
                    $new_count = 0;
                    $renewal_count = 0;
            
                    foreach ($res_array as $value) {
                        $new_count = $value['new_count'];
                        $renewal_count = $value['renewal_count'];
                       
                    }
                }
            
                $json_array = [
                    "status" => $result->status,
                    "data" => $data ?? [],
                    "error" => $result->error ?? null,
                    "msg" => $result->status ? "Data fetched successfully" : "Failed to fetch data",
                    "new_count" => $new_count,
                    "renewal_count" => $renewal_count,
                    "sql" => $sql ?? null
                ];
            
                echo json_encode($json_array);
                break;
                


                case 'boys_girls_count':
                    
                    $district = $_POST['district_id'];
                    $json_array = [];
                    $columns = [
                        "(SELECT COUNT(id) FROM std_app WHERE gender = 'Male' AND hostel_district_1='".$_SESSION['district_name']."') as boys",
                        "(SELECT COUNT(id) FROM std_app WHERE gender = 'Female' AND hostel_district_1='".$_SESSION['district_name']."') as girls"
            
                    ];
                    $table_details = [
                        "std_app",
                        $columns
                    ];
            
                    // Applying condition to fetch only non-deleted records
                    $where = "is_delete = 0";
            
                    // Perform the select query
                    $result = $pdo->select($table_details);
            
                    $total_records = total_records();  // This function might return the total count
            
                    // Check if the query was successful
                    if ($result->status) {
                        $res_array = $result->data;
            
                        foreach ($res_array as $value) {
                            // Assign the values from the database response
                            $boys = $value['boys'];
                            $girls = $value['girls'];
                        }
                    }
            
                    // Prepare the response array
                    $json_array = [
                        "boys" => $boys,
                        "girls" => $girls
                    ];
            
                    // Send the response as JSON
                    echo json_encode($json_array);
            
                    break;
                    case 'new_renewal_count':

                        $district = $_POST['district'];
                
                        $json_array = [];
                
                        if ($district != '') {
                            $columns = [
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a259436412348' AND is_delete = 0 AND application_type = '1' AND hostel_district_1 = '" . $district . "') as school_new",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a259436412348' AND is_delete = 0 AND application_type = '2' AND hostel_district_1 = '" . $district . "') as school_renewal",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a327c08582160' AND is_delete = 0 AND application_type = '1' AND hostel_district_1 = '" . $district . "') as iti_new",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a327c08582160' AND is_delete = 0 AND application_type = '2' AND hostel_district_1 = '" . $district . "') as iti_renewal",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a3e3c9a337012' AND is_delete = 0 AND application_type = '1' AND hostel_district_1 = '" . $district . "') as diploma_new",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a3e3c9a337012' AND is_delete = 0 AND application_type = '2' AND hostel_district_1 = '" . $district . "') as diploma_renewal",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a495599589293' AND is_delete = 0 AND application_type = '1' AND hostel_district_1 = '" . $district . "') as ug_new",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a495599589293' AND is_delete = 0 AND application_type = '2' AND hostel_district_1 = '" . $district . "') as ug_renewal",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a53eef3015995' AND is_delete = 0 AND application_type = '1' AND hostel_district_1 = '" . $district . "') as pg_new",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a53eef3015995' AND is_delete = 0 AND application_type = '2' AND hostel_district_1 = '" . $district . "') as pg_renewal"
                
                            ];
                        } else {
                
                            $columns = [
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a259436412348' AND is_delete = 0 AND application_type = '1') as school_new",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a259436412348' AND is_delete = 0 AND application_type = '2') as school_renewal",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a327c08582160' AND is_delete = 0 AND application_type = '1') as iti_new",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a327c08582160' AND is_delete = 0 AND application_type = '2') as iti_renewal",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a3e3c9a337012' AND is_delete = 0 AND application_type = '1') as diploma_new",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a3e3c9a337012' AND is_delete = 0 AND application_type = '2') as diploma_renewal",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a495599589293' AND is_delete = 0 AND application_type = '1') as ug_new",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a495599589293' AND is_delete = 0 AND application_type = '2') as ug_renewal",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a53eef3015995' AND is_delete = 0 AND application_type = '1') as pg_new",
                                "(SELECT COUNT(id) as count FROM std_app_s WHERE student_type = '65f00a53eef3015995' AND is_delete = 0 AND application_type = '2') as pg_renewal"
                
                            ];
                        }
                        $table_details = [
                            "std_app_s",
                            $columns
                        ];
                
                        // Applying condition to fetch only non-deleted records
                        $where = "is_delete = 0 ";
                
                        // Perform the select query
                        $result = $pdo->select($table_details, $where);
                
                        $total_records = total_records();  // This function might return the total count
                
                        // Check if the query was successful
                        if ($result->status) {
                            $res_array = $result->data;
                
                            foreach ($res_array as $value) {
                                // Assign the values from the database response
                                $school_new = $value['school_new'];
                                $school_renewal = $value['school_renewal'];
                                $iti_new = $value['iti_new'];
                                $iti_renewal = $value['iti_renewal'];
                                $diploma_new = $value['diploma_new'];
                                $diploma_renewal = $value['diploma_renewal'];
                                $ug_new = $value['ug_new'];
                                $ug_renewal = $value['ug_renewal'];
                                $pg_new = $value['pg_new'];
                                $pg_renewal = $value['pg_renewal'];
                            }
                        }
                
                        // Prepare the response array
                        $json_array = [
                            "school_new" => $school_new,
                            "school_renewal" => $school_renewal,
                            "iti_new" => $iti_new,
                            "iti_renewal" => $iti_renewal,
                            "diploma_new" => $diploma_new,
                            "diploma_renewal" => $diploma_renewal,
                            "ug_new" => $ug_new,
                            "ug_renewal" => $ug_renewal,
                            "pg_new" => $pg_new,
                            "pg_renewal" => $pg_renewal
                        ];
                
                        // Send the response as JSON
                        echo json_encode($json_array);
                
                        break;
                  
                        
                            case 'occupancy_chart_data':
         
                                $district_id = $_POST['district_id'];
                                $json_array = "";
                                $columns = [
                                    "district_name",
                                    "(SELECT sum(sanctioned_strength) from hostel_name where district_name.unique_id = hostel_name.district_name) as availability",
                                    "(SELECT COUNT(id) FROM `std_reg_s` where district_name.unique_id = std_reg_s.hostel_district_1) as occupancy"            
                                ];
                                $table_details = [
                                    "district_name",
                                    $columns
                                ];
                                $where = 'is_delete = 0 AND unique_id="'.$district_id.'"';
                            
                                $result = $pdo->select($table_details, $where);
                                // print_r($result);
                                if ($result->status) {
                                    $res_array = $result->data;
                            
                                    // Initialize variables
                                    // $occupancy = 0;
                                    // $availability = 0;
                                   
                            
                                    foreach ($res_array as $value) {
                                        $district_name[] = $value['district_name'];
                                        $occupancy[] = $value['occupancy'];
                                        $availability[] = $value['availability'];
                                      
                                    }
                                }
                            
                                $json_array = [
                                    "status" => $result->status,
                                    "data" => $data ?? [],
                                    "error" => $result->error ?? null,
                                    "msg" => $result->status ? "Data fetched successfully" : "Failed to fetch data",
                                    "occupancy" => $occupancy,
                                    "availability" => $availability,
                                    "district_name" => $district_name,
                                    "sql" => $sql ?? null
                                ];
                            
                                echo json_encode($json_array);
                                break;

                            case 'get_student_count':
                                $district_id = $_POST['district_id'];
                                $json_array = "";
                                $columns = [
                                  "(select district_name from district_name where district_name.unique_id=std_app_s.hostel_district_1) as district_name",
                                  "count(id) as cnt"
                        
                                ];
                                $table_details = [
                                    "std_app_s",
                                    $columns
                                ];
                                $where = "is_delete = 0 and submit_status= 1 and hostel_district_1 = '".$district_id."' GROUP BY hostel_district_1 ORDER BY cnt DESC";
                              
                                $result = $pdo->select($table_details, $where);
                                // print_r($result);
                                $res_array = $result->data;
                                //    print_r($result);
                                foreach ($res_array as $value) {
                    
                        
                                    $district_name[] = $value['district_name'];
                                    // echo $district_name;
                                    $cnt[] = $value['cnt'];
                                    // echo $cnt;
                                }
                        
                                $json_array = [
                                    "district_name" => $district_name,
                                    "cnt" => $cnt
                                   
                        
                                ];
                        
                                echo json_encode($json_array);
                        
                                break;    
                
                        case 'get_processing_times':

                            $district_id = $_SESSION['district_id'];
                    // echo $district_id;
                            $json_array = [];
                    
                            $columns = [
                    
                                "(SELECT AVG(DATEDIFF(status_upd_date, DATE(created))) AS avg_time FROM std_app_s WHERE application_type = 1 AND hostel_district_1='".$district_id."' AND status_upd_date IS NOT NULL) AS new_time",
                                "(SELECT AVG(DATEDIFF(status_upd_date, DATE(created))) AS avg_time FROM std_app_s WHERE application_type = 2  AND hostel_district_1='".$district_id."' AND status_upd_date IS NOT NULL) AS renewal_time"
                    
                            ];
                    
                            $table_details = [
                                "std_app_s",
                                $columns
                            ];
                    
                            // Applying condition to fetch only non-deleted records
                            $where = "is_delete = 0";
                    
                            // Perform the select query
                            $result = $pdo->select($table_details, $where);
                    // print_r($result);
                            $total_records = total_records();  // This function might return the total count
                    
                            // Check if the query was successful
                            if ($result->status) {
                                $res_array = $result->data;
                    

                                foreach ($res_array as $value) {
                                    // Assign the values from the database response
                                    $new_time = $value['new_time'];
                                    $renewal_time = $value['renewal_time'];
                                }
                            }
                    
                            if($new_time=='')
                            {
                                $new_time=0;

                            }

                            if($renewal_time==''){
                                $renewal_time=0;

                            }

                            // Prepare the response array
                            $json_array = [
                                "new_time" => $new_time,
                                "renewal_time" => $renewal_time
                            ];
                    
                            // Send the response as JSON
                            echo json_encode($json_array);
                    
                            break;
                    
                
            
    case 'datatable':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];

        $feedback_name = $_POST["feedback_name"];
        $rating = $_POST["rating"];
        $description = $_POST["description"];
        $student_id = $_POST["student_id"];
        $date = $_POST['date'];
        $is_active = $_POST["is_active"];
        $unique_id = $_POST["unique_id"];

        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "current_date",
            "(select feedback_type from feedback_type where unique_id = $table.feedback_name)as feedback_name",
            "rating",
            "description",
            // "student_id",
            // "hostel_name",



            "is_active",
            "unique_id"
        ];
        $table_details = [
            $table . " , (SELECT @a:= " . $start . ") AS a ",
            $columns
        ];
        $where = "is_delete = 0 ";
        $order_by = "";

        if ($_POST['search']['value']) {
            $where .= " AND user_type LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }

        // Datatable Searching
        $search = datatable_searching($search, $columns);

        if ($search) {
            if ($where) {
                $where .= " AND ";
            }

            $where .= $search;
        }

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {
                // $value['product_category'] = disname($value['product_category']);
                // $value['description'] = disname($value['description']);

                // $from_year = $value['from_year'];

                // $to_year   = $value['$to_year'];
                $value['is_active'] = is_active_show($value['is_active']);


                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                // if ( $value['unique_id'] == "5f97fc3257f2525529") {
                //     $btn_update         = "";
                //     $btn_delete         = "";
                // } 

                $value['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;

    // case 'get_application_count':
    //     $district_name = $_POST['district_name'];
    //     $taluk_name = $_POST['taluk_name'];
    //     $hostel_name = $_POST['hostel_name'];

    //     if ($district_name != '') {
    //         $where .= " AND hostel_district_1 ='" . $district_name . "'";
    //         $batch_where .= " AND hostel_district ='" . $district_name . "'";
    //     }
    //     if ($taluk_name != '') {
    //         $where .= " AND hostel_taluk_1 ='" . $taluk_name . "'";
    //         $batch_where .= " AND hostel_taluk ='" . $taluk_name . "'";
    //     }
    //     if ($hostel_name != '') {
    //         $where .= " AND hostel_1 ='" . $hostel_name . "'";
    //         $batch_where .= " AND hostel_name ='" . $hostel_name . "'";
    //     }

    //     $json_array = "";
    //     $columns = [
    //         "((select count(id) from std_app_s where is_delete= 0 " . $where . " )) as applied_cnt",
    //         "((select count(id) from batch_creation where is_delete = 0 " . $batch_where . " )) as accp_cnt",
    //         "((select count(id) from std_reg_s where is_delete = 0  " . $where . ")) as approved_cnt",
    //         "((select count(id) from batch_creation where is_delete = 0 and status = '2' " . $batch_where . ")) as rejected_cnt",
    //         // "(select COUNT(id) where stage_1_status = 3 and is_delete = 0) as cancel_comp",
    //     ];
    //     $table_details = [
    //         "std_app_s",
    //         $columns
    //     ];
    //     // $where        = "hostel_1 = '".$_SESSION['hostel_id']."'";
    //     $where = "is_delete = 0";


    //     $result = $pdo->select($table_details, $where);
    //     // print_r($result);
    //     $res_array = $result->data;
    //     //    print_r($result);
    //     foreach ($res_array as $value) {

    //         $applied_cnt = $value['applied_cnt'];
    //         $accp_cnt = $value['accp_cnt'];
    //         $approved_cnt = $value['approved_cnt'];
    //         $rejected_cnt = $value['rejected_cnt'];

    //         $hostel_district = district_name($value['hostel_district'])[0]['district_name'];
    //         $hostel_taluk = taluk_name($value['hostel_taluk'])[0]['taluk_name'];
    //         $hostel_name = hostel_name($value['hostel_name'])[0]['hostel_name'];

    //     }

    //     $json_array = [
    //         "applied_cnt" => $applied_cnt,
    //         "accp_cnt" => $accp_cnt,
    //         "approved_cnt" => $approved_cnt,
    //         "rejected_cnt" => $rejected_cnt,


    //     ];

    //     echo json_encode($json_array);

    //     break;

    case 'fetch_chart_data':


        $json_array = "";
        $columns = [
            "reject_reason",
            "count"
        ];
        $table_details = [
            "view_reject_reason_count",
            $columns
        ];
        // $where        = "hostel_1 = '".$_SESSION['hostel_id']."'";
        $where = "is_delete = 0";


        $result = $pdo->select($table_details);
        // print_r($result);
        $res_array = $result->data;
        //    print_r($result);
        foreach ($res_array as $value) {

            $reject_reason[] = $value['reject_reason'];
            $count[] = $value['count'];


        }

        $json_array = [
            "reject_reason" => $reject_reason,
            "count" => $count,
        ];

        echo json_encode($json_array);

        break;

    case 'delete':

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


    case 'get_applied_count':

        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];

        $where = "";
        $batch_where = "";

        if ($district_name != '') {
            $where = " AND hostel_district_1 ='" . $district_name . "'";
            $batch_where = " AND hostel_district ='" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where = " AND hostel_taluk_1 ='" . $taluk_name . "'";
            $batch_where = " AND hostel_taluk ='" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where = " AND hostel_1 ='" . $hostel_name . "'";
            $batch_where = " AND hostel_name ='" . $hostel_name . "'";
        }

        $json_array = "";
        $columns = [
            "(select count(id) from std_app_s where is_delete= 0 " . $where . " ) as applied_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 " . $batch_where . " ) as accp_cnt",
            // "(select count(id) from std_reg_s where is_delete = 0  " . $where . ") as approved_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 and status = '2' " . $batch_where . ") as rejected_cnt",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];

        $result = $pdo->select($table_details);
        // print_r($result);

        $res_array = $result->data;

        $applied_cnt = 0;
        // $accp_cnt = 0;
        // $approved_cnt = 0;
        // $rejected_cnt = 0;

        foreach ($res_array as $value) {

            $applied_cnt = $value['applied_cnt'];

            // $accp_cnt = $value['accp_cnt'];
            // $approved_cnt = $value['approved_cnt'];
            // $rejected_cnt = $value['rejected_cnt'];

        }

        $json_array = [
            "applied_cnt" => $applied_cnt,
            // "accp_cnt" => $accp_cnt,
            // "approved_cnt" => $approved_cnt,
            // "rejected_cnt" => $rejected_cnt,

        ];

        echo json_encode($json_array);

        break;


    case 'get_accept_count':

        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];

        $where = "";
        $batch_where = "";

        if ($district_name != '') {
            $where = " AND hostel_district_1 ='" . $district_name . "'";
            $batch_where = " AND hostel_district ='" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where = " AND hostel_taluk_1 ='" . $taluk_name . "'";
            $batch_where = " AND hostel_taluk ='" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where = " AND hostel_1 ='" . $hostel_name . "'";
            $batch_where = " AND hostel_name ='" . $hostel_name . "'";
        }

        $json_array = "";
        $columns = [
            // "(select count(id) from std_app_s where is_delete= 0 " . $where . " ) as applied_cnt",
            "(select count(id) from batch_creation where is_delete = 0 " . $batch_where . " ) as accp_cnt",
            // "(select count(id) from std_reg_s where is_delete = 0  " . $where . ") as approved_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 and status = '2' " . $batch_where . ") as rejected_cnt",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];

        $result = $pdo->select($table_details);

        $res_array = $result->data;

        // $applied_cnt = 0;
        $accp_cnt = 0;
        // $approved_cnt = 0;
        // $rejected_cnt = 0;

        foreach ($res_array as $value) {

            // $applied_cnt = $value['applied_cnt'];
            $accp_cnt = $value['accp_cnt'];
            // $approved_cnt = $value['approved_cnt'];
            // $rejected_cnt = $value['rejected_cnt'];

        }

        $json_array = [
            // "applied_cnt" => $applied_cnt,
            "accp_cnt" => $accp_cnt,
            // "approved_cnt" => $approved_cnt,
            // "rejected_cnt" => $rejected_cnt,

        ];

        echo json_encode($json_array);

        break;


    case 'get_approved_count':

        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];

        $where = "";
        $batch_where = "";

        if ($district_name != '') {
            $where = " AND hostel_district_1 ='" . $district_name . "'";
            $batch_where = " AND hostel_district ='" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where = " AND hostel_taluk_1 ='" . $taluk_name . "'";
            $batch_where = " AND hostel_taluk ='" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where = " AND hostel_1 ='" . $hostel_name . "'";
            $batch_where = " AND hostel_name ='" . $hostel_name . "'";
        }

        $json_array = "";
        $columns = [
            // "(select count(id) from std_app_s where is_delete= 0 " . $where . " ) as applied_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 " . $batch_where . " ) as accp_cnt",
            "(select count(id) from std_reg_s where is_delete = 0  " . $where . ") as approved_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 and status = '2' " . $batch_where . ") as rejected_cnt",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];

        $result = $pdo->select($table_details);

        $res_array = $result->data;

        // $applied_cnt = 0;
        // $accp_cnt = 0;
        $approved_cnt = 0;
        // $rejected_cnt = 0;

        foreach ($res_array as $value) {

            // $applied_cnt = $value['applied_cnt'];
            // $accp_cnt = $value['accp_cnt'];
            $approved_cnt = $value['approved_cnt'];
            // $rejected_cnt = $value['rejected_cnt'];

        }

        $json_array = [
            // "applied_cnt" => $applied_cnt,
            // "accp_cnt" => $accp_cnt,
            "approved_cnt" => $approved_cnt,
            // "rejected_cnt" => $rejected_cnt,

        ];

        echo json_encode($json_array);

        break;

    case 'get_rejected_count':

        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        $hostel_name = $_POST['hostel_name'];

        $where = "";
        $batch_where = "";

        if ($district_name != '') {
            $where = " AND hostel_district_1 ='" . $district_name . "'";
            $batch_where = " AND hostel_district ='" . $district_name . "'";
        }
        if ($taluk_name != '') {
            $where = " AND hostel_taluk_1 ='" . $taluk_name . "'";
            $batch_where = " AND hostel_taluk ='" . $taluk_name . "'";
        }
        if ($hostel_name != '') {
            $where = " AND hostel_1 ='" . $hostel_name . "'";
            $batch_where = " AND hostel_name ='" . $hostel_name . "'";
        }

        $json_array = "";
        $columns = [
            // "(select count(id) from std_app_s where is_delete= 0 " . $where . " ) as applied_cnt",
            // "(select count(id) from batch_creation where is_delete = 0 " . $batch_where . " ) as accp_cnt",
            // "(select count(id) from std_reg_s where is_delete = 0  " . $where . ") as approved_cnt",
            "(select count(id) from batch_creation where is_delete = 0 and status = '2' " . $batch_where . ") as rejected_cnt",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];

        $result = $pdo->select($table_details);

        $res_array = $result->data;

        // $applied_cnt = 0;
        // $accp_cnt = 0;
        // $approved_cnt = 0;
        $rejected_cnt = 0;

        foreach ($res_array as $value) {

            // $applied_cnt = $value['applied_cnt'];
            // $accp_cnt = $value['accp_cnt'];
            // $approved_cnt = $value['approved_cnt'];
            $rejected_cnt = $value['rejected_cnt'];

        }

        $json_array = [
            // "applied_cnt" => $applied_cnt,
            // "accp_cnt" => $accp_cnt,
            // "approved_cnt" => $approved_cnt,
            "rejected_cnt" => $rejected_cnt,

        ];

        echo json_encode($json_array);

        break;





    case 'get_leave':
        // DataTable Variables
        $search = $_POST['search']['value'];
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $limit = $length;

        $data = [];



        if ($length == '-1') {
            $limit = "";
        }

        // Query Variables
        $json_array = "";
        $columns = [
            "@a:=@a+1 s_no",
            "current_date",
            "(select feedback_type from feedback_type where unique_id = $table.feedback_name)as feedback_name",
            "rating",
            "description",
            // "student_id",
            // "hostel_name",



            "is_active",
            "unique_id"
        ];
        $table_details = [
            $table . " , (SELECT @a:= " . $start . ") AS a ",
            $columns
        ];
        $where = "is_delete = 0 ";
        $order_by = "";

        if ($_POST['search']['value']) {
            $where .= " AND user_type LIKE '" . mysql_like($_POST['search']['value']) . "' ";
        }

        // Datatable Searching
        $search = datatable_searching($search, $columns);

        if ($search) {
            if ($where) {
                $where .= " AND ";
            }

            $where .= $search;
        }

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {
                // $value['product_category'] = disname($value['product_category']);
                // $value['description'] = disname($value['description']);

                // $from_year = $value['from_year'];

                // $to_year   = $value['$to_year'];
                $value['is_active'] = is_active_show($value['is_active']);


                $btn_update = btn_update($folder_name, $value['unique_id']);
                $btn_delete = btn_delete($folder_name, $value['unique_id']);

                // if ( $value['unique_id'] == "5f97fc3257f2525529") {
                //     $btn_update         = "";
                //     $btn_delete         = "";
                // } 

                $value['unique_id'] = $btn_update . $btn_delete;
                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data,
                "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;

    case 'get_vacancy_count':

        $json_array = "";
        $columns = [
            "(select sum(sanctioned_strength) as str_cnt from hostel_name where district_name = '" . $_SESSION['district_id'] . "') as tot_cap",
            "'' as old_std",
            "count(id) as approved_cnt",

            // "'' as hos_vacancy",
            // "(select COUNT(id) where stage_1_status = 3 and is_delete = 0) as cancel_comp",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];
        $where = "hostel_district_1 = '" . $_SESSION["district_id"] . "' and status = '1'";
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;
        //    print_r($result);die();
        foreach ($res_array as $value) {

            $tot_cap = $value['tot_cap'];
            // $accp_cnt       = $value['old_std'];
            if ($value['old_std'] == '') {
                $old_std = '0';
            }
            $approved_cnt = $value['approved_cnt'];

            $hos_vac = $tot_cap - $approved_cnt;


        }

        $json_array = [
            "tot_cap" => $tot_cap,
            "old_std" => $old_std,
            "approved_cnt" => $approved_cnt,
            "hos_vac" => $hos_vac,


        ];

        echo json_encode($json_array);

        break;


    case 'total_hostels':


        // $district_name = $_SESSION["district_id"]; 


        $table = "hostel_name";

        $columns = [
            "count(hostel_name)as hostel_count"

        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 ";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {

            $hostel_count = $value['hostel_count'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "hostel_count" => $hostel_count
        ];

        echo json_encode($json_array);

        break;

    case 'total_students':


        // $district_name = $_SESSION["district_id"]; 


        $table = "std_reg_s";

        $json_array = [];

        $columns = [

            // "count(id)as student_name",
            "count(id) as student_name",


        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 AND dropout_status = 1 ";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {
            $student_name = $value['student_name'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "student_name" => $student_name,

        ];

        echo json_encode($json_array);

        break;

    case 'total_staff_strength':


        // $district_name = $_SESSION["district_id"]; 


        $table = "establishment_registration";

        $columns = [

            "count(staff_name)as staff_name"

        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0";

        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;


        //    print_r($result);die();

        foreach ($res_array as $value) {

            $staff_name = $value['staff_name'];

            // $data[]             = array_values($value);
        }
        $json_array = [

            // "data"  => $data,
            "staff_cnt" => $staff_name,

        ];

        echo json_encode($json_array);

        break;







    case 'applied_leave_details':

        $table_leave = "leave_application";

        $district_name = $_SESSION["district_id"];

        $json_array = "";
        // $today  =  date('Y-m-d');

        $columns_leave = [
            "from_date",
            "no_of_days",
            "approval_status",
            "count(student_name)as student_name"
            // "approval_status"
        ];

        $table_details_leave = [
            $table_leave,
            $columns_leave,
        ];

        $where = "is_delete = 0";


        $sql_function = "SQL_CALC_FOUND_ROWS";




        $result = $pdo->select($table_details_leave, $where);
        // print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                // $from_date = $value['from_date'];
                $no_of_student_name = $value['student_name'];

                $status_color = '';

                if ($value['approval_status'] == 1) {
                    $value['approval_status'] = 'Pending';
                    $status_color = 'blue';
                }
                if ($value['approval_status'] == 2) {
                    $value['approval_status'] = 'Approved';
                    $status_color = 'green';
                }
                if ($value['approval_status'] == 3) {
                    $value['approval_status'] = 'Rejected';
                    $status_color = 'red';
                }

                $status_text = $value['approval_status'];
                // switch ($value['approval_status']) {
                //     case 1:
                //         $status_text = 'Approved';
                //         $status_color = 'green';
                //         break;
                //     case 2:
                //         $status_text = 'Rejected';
                //         $status_color = 'red';
                //         break;
                //     case 3:
                //         $status_text = 'Pending';
                //         $status_color = 'blue';
                //         break;
                // }

                // Assigning color to status
                // $value['approval_status'] = '<button type="button"  class="btn" style="margin-left:80px;" id="approval_status">Pending</button>';
                // $value['approval_status'] = '<span style="background: ' . $status_color . ';">' . $status_text . '</span>';

                $res_array = $result->data;
                // $data[]             = array_values($value);
            }
            $json_array = [

                "applied_leave_details" => $res_array,

                "no_of_student_name" => $no_of_student_name,

                // "approval_status"    =>     $value['approval_status']
            ];

            echo json_encode($json_array);
        }


        break;





    case 'staff_applied_leave_details':

        $table_leave = "staff_leave_application";

        // $district_name = $_SESSION["district_id"]; 

        $json_array = "";
        // $today  =  date('Y-m-d');

        $columns_leave = [
            "from_date",
            "no_of_days",
            "approval_status",
            "count(staff_name)as staff_name"
            // "approval_status"
        ];

        $table_details_leave = [
            $table_leave,
            $columns_leave,
        ];

        $where = "is_delete = 0";


        $sql_function = "SQL_CALC_FOUND_ROWS";




        $result = $pdo->select($table_details_leave, $where);
        // print_r($result);
        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {

                // $from_date = $value['from_date'];
                $staff_name = $value['staff_name'];

                $res_array = $result->data;
                // $data[]             = array_values($value);
            }
            $json_array = [

                "applied_leave_details" => $res_array,

                "staff_name" => $staff_name,

                // "approval_status"    =>     $value['approval_status']
            ];

            echo json_encode($json_array);
        }


        break;




    case 'hostel_vaccancy':

        $table_hs = "hostel_name";
        $json_array = "";
        $columns = [
            // "hostel_name",
            // "hostel_id",
            // "district_name",
            // "taluk_name",

            "sum(sanctioned_strength) as sanctioned_count",



            // "(select count(hostel_1 from std_reg_s where std_reg_s.hostel_1 = $table_hs.unique_id))as reg_hostel",
            // "student_count",
            // "unique_id"     

        ];
        $table_details = [

            "hostel_name",
            $columns

        ];

        $where = "is_delete = 0";
        $result = $pdo->select($table_details, $where);
        // print_r($result);
        $res_array = $result->data;
        //    
        foreach ($res_array as $value) {

            $sanctioned_cnt = $value['sanctioned_count'];

            $registered_cnt = total_reg_count();


            $hostel_vaccancy = $sanctioned_cnt - $registered_cnt;
        }

        $json_array = [

            "approved_cnt" => $sanctioned_cnt,
            "registered_cnt" => $registered_cnt,
            "hostel_vaccancy" => $hostel_vaccancy


        ];

        echo json_encode($json_array);

        break;


        case 'month_wise_counts':

            $table_dis = "std_reg_s";
            $district_id=  $_SESSION['district_id'] ;
           // Get the current date
$currentDate = date('Y-m-d');

// Get the first and last date of the previous month
$firstDayOfLastMonth = date('Y-m-01', strtotime('first day of last month', strtotime($currentDate)));
$lastDayOfLastMonth = date('Y-m-t', strtotime('last day of last month', strtotime($currentDate)));

// Initialize an array to store week ranges and their respective counts
$weekRanges = [];

// Loop through the days of the last month in intervals of 7 days (1 week)
for ($i = 0; $i < 4; $i++) {
    $startOfWeek = date('Y-m-d', strtotime($firstDayOfLastMonth . " + " . ($i * 7) . " days"));
    
    // Calculate the end of the week, but it should not go beyond the last day of the last month
    $endOfWeek = date('Y-m-d', min(strtotime($firstDayOfLastMonth . " + " . (($i + 1) * 7 - 1) . " days"), strtotime($lastDayOfLastMonth)));
    
// $week=1;
//     $entry_date[]=$week++;                  
    $new_cnt[] = week_reg($startOfWeek, $endOfWeek, $district_id,'1');
    $renewal_cnt[] = week_reg($startOfWeek, $endOfWeek, $district_id,'2');
    
}
// echo $new_cnt;
// echo $renewal_cnt;

            
            
        
            $json_array = [
                // "entry_date" => $entry_date,
                "entry_date" => ['week 1','week 2','week 3','week 4'],
                "new_cnt" => $new_cnt,
                "renewel" => $renewal_cnt

            ];
        // echo $json_array;
            echo json_encode($json_array);
            break;


case 'date_wise_counts':
    $table_dis = "std_app_s";
    $date_type = $_POST['date_type'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
  $district_id=  $_SESSION['district_id'] ;
// echo "jiiiii";
    // Define date ranges

    $currentDate = date('Y-m-d');
    $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
   


    // if ($date_type==1){
$monday = date('Y-m-d', strtotime('monday last week', strtotime($currentDate)));
$sunday = date('Y-m-d', strtotime('sunday last week', strtotime($currentDate)));


// Convert the dates to DateTime objects
$start = new DateTime($monday);
$end = new DateTime($sunday);

// Create an array to hold the dates
$dates = array();

// Loop through the period and add each date to the array
while ($start <= $end) {
    $dates[] = $start->format('Y-m-d');
    $start->modify('+1 day');
}
    // echo "kiii";

foreach ($dates as $date) {
        // $entry_date[]=$date;
        // echo $date;
        $explode = explode('-', $date);
                            $firstPart = $explode[2];
                            $entry_date=['day 1','day 2','day 3','day 4','day 5','day 6','day 7'];        

           $new_cnt[] = reg_register($date, $district_id,'1');
           $renewal_cnt[] = reg_register($date, $district_id,'2');

        // echo $regdistrict;
        }
    
    
    $json_array = [
        "entry_date" => $entry_date,
        "new_cnt" => $new_cnt,
        "renewel" => $renewal_cnt

    ];
// echo $json_array;
    echo json_encode($json_array);
    break;


    case 'custome_wise_counts':

        $table_dis = "std_app_s";
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $district_id=  $_SESSION['district_id'] ;

        // Extract year and week from from_date and to_date
        list($from_year, $from_week) = explode('-W', $from_date);
        list($to_year, $to_week) = explode('-W', $to_date);
    
        $currentYear = $from_year;
        $currentWeek = $from_week;
    
        $weekRanges = [];
        $s_no = 1;
        $entry_date = []; // Array to store serial numbers (s_no)
        $reg_district_arr = []; // Array to store registration data
    
        while ($currentYear < $to_year || ($currentYear == $to_year && $currentWeek <= $to_week)) {
            // Get start and end dates for the current week
            list($startDate, $endDate) = getWeekDates($currentYear, $currentWeek);
    
            // Get the registration data for the district
            $new_cnt[] = cus_reg($startDate, $endDate, $district_id, '1');
            $renewal_cnt[] = cus_reg($startDate, $endDate, $district_id, '2');


    
            // Add data for this week
            $entry_date[] = 'W'.$s_no; // Append serial number for this week
            // $reg_district_arr[] = $reg_district; // Append district registration for this week
    
            // Move to the next week
            $date = new DateTime();
            $date->setISODate($currentYear, $currentWeek);
            $date->modify('+1 week');
            $currentYear = $date->format('Y');
            $currentWeek = $date->format('W');
    
            $s_no++; // Increment serial number
        }
    
        // Construct the final JSON array
        $json_array = [
            "entry_date" => $entry_date,
            "new_cnt" => $new_cnt,
            "renewel" => $renewal_cnt
    
        ];
    
        // Return JSON response
        echo json_encode($json_array);
        break;
    


    case 'get_application_count':

        $json_array = "";
        $columns = [
            "(select count(id) from std_app_s where is_delete = 0 and hostel_district_1 = '" . $_SESSION['district_id'] . "') as applied_cnt",
            "(select count(id) from std_app_s where is_delete = 0 and batch_no != '' and hostel_district_1 = '" . $_SESSION['district_id'] . "') as accp_cnt",
            "(select count(id) from std_app_s where is_delete = 0 and status = '1' and hostel_district_1 = '" . $_SESSION['district_id'] . "') as approved_cnt",
            "(select count(id) from std_app_s where is_delete = 0 and status = '2' and hostel_district_1 = '" . $_SESSION['district_id'] . "') as rejected_cnt",

            // "(select COUNT(id) where stage_1_status = 3 and is_delete = 0) as cancel_comp",
        ];
        $table_details = [
            "std_app_s",
            $columns
        ];
        $where = "hostel_district_1 = '" . $_SESSION["district_id"] . "'";
        $result = $pdo->select($table_details, $where);
        $res_array = $result->data;
        //    print_r($result);
        foreach ($res_array as $value) {

            $applied_cnt = $value['applied_cnt'];
            $accp_cnt = $value['accp_cnt'];
            $approved_cnt = $value['approved_cnt'];
            $rejected_cnt = $value['rejected_cnt'];

        }

        $json_array = [
            "applied_cnt" => $applied_cnt,
            "accp_cnt" => $accp_cnt,
            "approved_cnt" => $approved_cnt,
            "rejected_cnt" => $rejected_cnt,


        ];

        echo json_encode($json_array);

        break;


    default:

        break;
}


function getApplicationCount($startDate, $endDate, $mysqli) {
    $sql = "SELECT COUNT(*) as count FROM std_app_report WHERE entry_date BETWEEN ? AND ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ss', $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'];
}





function getWeekDates($year, $week) {
    // Get the start date of the ISO week
    $startDate = new DateTime();
    $startDate->setISODate($year, $week);
    $startDate->setTime(0, 0);

    // Get the end date of the ISO week
    $endDate = clone $startDate;
    $endDate->modify('+6 days');

    return [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')];
}
function total_reg_count($unique_id = "")
{
    global $pdo;

    $table_name = "std_reg_s";
    $where = [
        "is_active" => 1,
        "is_delete" => 0,
    ];

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

?>