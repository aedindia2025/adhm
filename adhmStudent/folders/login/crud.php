<?php

$url = $_SERVER['REQUEST_URI'];

// Parse the URL using parse_url
$parsedUrl = parse_url($url);

// Extract the path from the parsed URL
$path = $parsedUrl['path'];

// Remove the leading slash from the path (if present)
$path = ltrim($path, '/');

// Explode the path into an array of segments
$pathSegments = explode('/', $path);

// Get the first segment (assuming the folder name is the first segment)
$root_folderName = $pathSegments[0];

$folder_name = explode('/', $_SERVER['PHP_SELF']);

$folder_name = $folder_name[count($folder_name) - 2];


$table = "std_reg_s";


// Include DB file and Common Functions
include '../../config/dbconfig.php';
// include '../../config/dbclass.php';

// Variables Declaration
$action = $_POST['action'];
$action_obj = (object) [
    "status" => 0,
    "data" => "",
    "error" => "Action Not Performed"
];
$json_array = "";
$sql = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

// $acc_year       = explode(" to ",$_POST['acc_year']);
// $from_year      = (explode("-",$acc_year[0]))[0];
// $to_year        = (explode("-",$acc_year[1]))[0];

// $acc_year       = explode(" to ",$_POST['acc_year']);
$from_year = "2020";
$to_year = "2021";
$user_type_unique_id = "65589f69ce65d32654";

$sess_acc_year = $from_year . "-" . $to_year;

$sess_acc_year = acc_year();

switch ($action) {


    case 'login':

        $user_name = $_POST['user_name'];
        $password_with_token = $_POST['password']; // Password with token
        $captcha = $_POST['captcha'];

        $encode_token = $_POST['token'];
        $token = base256_decode($encode_token);
        $token_length = strlen($token);
        $password = substr($password_with_token, 0, -$token_length);
        //print_r($password);


        $extracted_token = substr($password_with_token, -$token_length);
        //print_r($extracted_token);

        if ($extracted_token == $token) {


            if ($_SESSION['captcha_text'] === $_POST['captcha']) {



                $columns = [
                    "COUNT(unique_id) AS count",
                    "(select mobile_no from std_reg_s2 where std_reg_s2.s1_unique_id = std_reg_s.unique_id) as std_mobile_no",
                    "(select std_name from std_reg_s2 where std_reg_s2.s1_unique_id = std_reg_s.unique_id) as std_name",
                    "user_name",
                    "std_app_no",
                    "std_reg_no",
                    "unique_id",
                    "batch_no",
                    "batch_cr_date",
                    "hostel_1",
                    "hostel_district_1",
                    "hostel_taluk_1",
                    "academic_year",
                    "user_name",
                    "(select email_id from std_reg_s6 where std_reg_s6.s1_unique_id = std_reg_s.unique_id) as email_id",
                    "(SELECT hostel_id FROM hostel_name WHERE hostel_name.unique_id = std_reg_s.hostel_1 ) AS hostel_main_id",
                    // "(SELECT district_name FROM district_name WHERE district_name.unique_id = std_reg_s.hostel_district_1) AS district_name",

                    // "(SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = '.$table.'.hostel_taluk_1) AS taluk_name",
                    "(SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = std_reg_s.hostel_1) as hostel_names",
                    "(SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = std_reg_s.hostel_taluk_1) as taluk_name",
                    "(SELECT district_name FROM district_name WHERE district_name.unique_id = std_reg_s.hostel_district_1) as district_name",
                    //   "(SELECT staff_name FROM staff_registration WHERE staff_registration.taluk_name = std_reg_p1.hostel_taluk and staff_registration.user_type='65d5c08ca6ad312866') as tahsildar_name"

                ];

                $table_details = [
                    $table,
                    $columns
                ];

                $where = [
                    "user_name" => $user_name,
                    "password" => $password,
                    "is_active" => 1,
                    "is_delete" => 0,
                ];


                $where = " BINARY user_name = '" . $user_name . "' AND enc_password =  '" . $password . "' AND is_active = 1 AND is_delete = 0 AND status = 1 and dropout_status = '1'";


                $action_obj = $pdo->select($table_details, $where);




                if ($action_obj->status) {
                    $count_data = $action_obj->data[0]["count"];
                    if ($count_data == 1) {
                        // session_start();
                        $user = $action_obj->data[0];
                        $msg = "success_login";

                        $user_id = $user['unique_id'];
                        $_SESSION["academic_year"] = $user['academic_year'];
                        $_SESSION["user_name"] = $user['user_name'];
                        $_SESSION["std_mobile_no"] = $user['std_mobile_no'];
                        $_SESSION["std_name"] = $user['std_name'];
                        $_SESSION["std_reg_no"] = $user['std_reg_no'];
                        $_SESSION["std_app_no"] = $user['std_app_no'];
                        $_SESSION["batch_no"] = $user['batch_no'];
                        $_SESSION["batch_cr_date"] = $user['batch_cr_date'];

                        $_SESSION["hostel_name"] = $user['hostel_1'];
                        $_SESSION["hostel_district"] = $user['hostel_district_1'];
                        $_SESSION["hostel_taluk"] = $user['hostel_taluk_1'];

                        $_SESSION["hostel_names"] = $user['hostel_names'];
                        $_SESSION["taluk_name"] = $user['taluk_name'];
                        $_SESSION["district_name"] = $user['district_name'];
                        $_SESSION["tahsildar_name"] = $user['tahsildar_name'];


                        $_SESSION["hostel_name_id"] = $user['hostel_1'];
                        $_SESSION["hostel_taluk_id"] = $user['hostel_taluk_1'];
                        $_SESSION["hostel_district_id"] = $user['hostel_district_1'];


                        // $_SESSION["user_name"]              = $user['user_name'];
                        $_SESSION["hostel_main_id"] = $user['hostel_main_id'];
                        $_SESSION["email_id"] = $user['email_id'];
                        $_SESSION["user_id"] = $user_id;


                        $_SESSION['sess_user_id'] = $user_id;
                        $_SESSION['sess_user_type'] = "65589f69ce65d32654";
                        $_SESSION['sess_company_id'] = "comp5fa3b1c2a3bab70290";
                        $_SESSION['sess_user_id'] = $user['unique_id'];
                        $_SESSION['acc_year'] = academic_year($user['academic_year'])[0]['amc_year'];
                        $msg = "success_login";

                        // $sessionId = uniqid();

                        // // Set cookie with HttpOnly flag
                        // setcookie('PHPSESSID', $sessionId, [
                        //     'expires' => time() + 60 * 60 * 24, // One day
                        //     'path' => '/',
                        //     'httpOnly' => true,
                        //     'secure' => true, // Set if using HTTPS
                        //     'samesite' => 'Strict'
                        // ]);

                        session_regenerate_id(true); // Regenerate session ID and update cookie

                        
                        $permissions = menu_permission($user['user_type']);

                        $main_screens = $permissions["main_screens"];

                        $screens = $permissions["screens"];

                        $_SESSION['main_screens'] = $main_screens;
                        $_SESSION['root_folderName'] = $root_folderName;


                        $_SESSION['screens'] = $screens;

                        $password = '3sc3RLrpd17';
                        $enc_method = 'aes-256-cbc';
                        $enc_password = substr(hash('sha256', $password, true), 0, 32);
                        $enc_iv = "av3DYGLkwBsErphc";

                        $menu_screen = "dashboard/form";
                        $file_name = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));

                        $table_trace = "user_logs";
                        $user_name = $_POST['user_name'];
                        $ipAddress = getClientIP();
                        // $ipAddress = $_SERVER['REMOTE_ADDR'];

                        $date = date('Y-m-d');
                        $time = date('H:i:s');

                        // Assuming this is the username
                        $referrer = $_SERVER['HTTP_REFERER'] ?? 'N/A';

                        $processId = getmypid();

                        $country = 'India';

                        $url = $_SERVER['REQUEST_URI'];
                        $userAgent = $_SERVER['HTTP_USER_AGENT'];
                        $browserName = getBrowserName($userAgent);

                        $columns_logs = [

                            "entry_date" => $date,
                            "user_name" => $user_name,
                            "ip_address" => $ipAddress,
                            "log_date" => $date,
                            "log_time" => $time,
                            "referrer" => $referrer,
                            "process_id" => $processId,
                            "country" => $country,
                            "url" => $url,
                            "user_agent" => $browserName,
                            "unique_id" => unique_id($prefix)
                        ];

                        $result = $pdo->insert($table_trace, $columns_logs);
                        //    print_r($result);
                        $json_array = [
                            "status" => 1,
                            "data" => 1,
                            "error" => 0,
                            "msg" => "success_login",
                            "url" => $file_name,
                            // "test"      => $_SESSION["file_status"],
                            "sql" => $_SESSION["user_id"]
                        ];

                    } else {
                        // Incorrect username and password handling 
                        $json_array = [
                            "status" => 0,
                            "data" => 1,
                            "error" => 0,
                            "msg" => "incorrect",
                            "sql" => ""
                        ];
                    }
                } else {
                    $msg = "error";
                }
            } else {
                $json_array = [
                    "status" => 0,
                    "data" => 1,
                    "error" => 0,
                    "msg" => "invalid_captcha",
                    "sql" => ""
                ];
            }
        } else {

        }
        echo json_encode($json_array);

        break;

    default:

        break;
}


function base256_decode($str) {
    $result = '';
    for ($i = 0; $i < strlen($str); $i += 3) {
        $charCode = intval(substr($str, $i, 3));
        $result .= chr($charCode);
    }
    return $result;
}

function getClientIP() {
    $ipAddress = '';

    // Check for proxy IP address in $_SERVER parameters
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }

    // Validate IP address (optional)
    if (filter_var($ipAddress, FILTER_VALIDATE_IP)) {
        return $ipAddress;
    } else {
        return 'Unknown';
    }
}

function getBrowserName($userAgent) {
    $browsers = [
        'Opera' => 'Opera',
        'OPR/' => 'Opera',
        'Edge' => 'Edge',
        'Edg' => 'Edge',
        'Chrome' => 'Chrome',
        'Safari' => 'Safari',
        'Firefox' => 'Firefox',
        'MSIE' => 'Internet Explorer',
        'Trident' => 'Internet Explorer'
    ];

    foreach ($browsers as $key => $browser) {
        if (strpos($userAgent, $key) !== false) {
            return $browser;
        }
    }

    return 'Unknown';
}


?>