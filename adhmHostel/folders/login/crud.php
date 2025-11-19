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


$table = "staff_registration";


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
$user_type_unique_id = "65cb092facaf836335";

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

            if ($captcha == $_SESSION['captcha_text'] && $captcha != "") {


                $columns = [
                    "COUNT(unique_id) AS count",
                    "user_type as user_type",
                    "staff_name",
                    "unique_id",
                    "designation",
                    "district_office",
                    "taluk_office",
                    "hostel_name as hostel_id",
                    "staff_id",
                    "academic_year",
                    "mobile_num",
                    "email_id",
                    "unique_id",
                    "file_names",

                    '(SELECT district_name FROM district_name WHERE district_name.unique_id = ' . $table . '.district_name ) AS district_address',
                    '(SELECT user_type FROM user_type AS ust WHERE ust.unique_id = ' . $table . '.user_type ) AS user_type_name',
                    '(SELECT designation_name FROM designation_creation AS des WHERE des.unique_id = ' . $table . '.designation ) AS designation_name',
                    '(SELECT district_name FROM district_name WHERE district_name.unique_id = ' . $table . '.district_office ) AS district_name',
                    '(SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = ' . $table . '.taluk_office ) AS taluk_name',
                    '(SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = ' . $table . '.hostel_name ) AS hostel_name',

                    '(SELECT hostel_id FROM hostel_name WHERE hostel_name.unique_id = ' . $table . '.hostel_name ) AS hostel_main_id',
                    "(select staff_count from hostel_name where hostel_name.unique_id = staff_registration.hostel_name) as staff_count",
                    '(SELECT com_status FROM hostel_name WHERE hostel_name.unique_id = ' . $table . '.hostel_name ) AS com_status',
		            '(SELECT hostel_type FROM hostel_name WHERE hostel_name.unique_id = ' . $table . '.hostel_name ) AS hostel_type',
                    '(SELECT gender_type FROM hostel_name WHERE hostel_name.unique_id = ' . $table . '.hostel_name ) AS gender_id',


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


                $where = "BINARY user_name = '" . $user_name . "' AND hashedPassword =  '" . $password . "' AND is_active = 1 AND is_delete = 0 AND user_type =  '" . $user_type_unique_id . "'";


                $action_obj = $pdo->select($table_details, $where);


                //  print_r($action_obj);


                if ($action_obj->status) {
                    $count_data = $action_obj->data[0]["count"];
                    if ($count_data == 1) {
                        // print_r($user['hostel_name']);die();


                        $user = $action_obj->data[0];
                        $msg = "success_login";
                   
                        $user_id = $user['unique_id'];
                        // $user_id                           = $user['unique_id'];
                        $_SESSION["acc_year"] = $sess_acc_year;
                        $_SESSION["user_id"] = $user_id;
                        $_SESSION["sess_user_type_name"] = $user['user_type_name'];
                        $_SESSION["staff_name"] = $user['staff_name'];
                        $_SESSION["user_name"] = $user_name;

                        $_SESSION["designation"] = $user['designation'];
                        $_SESSION["district_id"] = $user['district_office'];
                        $_SESSION['taluk_id'] = $user['taluk_office'];
                        $_SESSION['hostel_id'] = $user['hostel_id'];
                        $_SESSION['staff_id'] = $user['staff_id'];
                        $_SESSION['academic_year'] = $user['academic_year'];
                        $_SESSION['hostel_main_id'] = $user['hostel_main_id'];

                        $_SESSION['mobile_num'] = $user['mobile_num'];
                        $_SESSION['email_id'] = $user['email_id'];
                        $_SESSION['district_address'] = $user['district_address'];

                        $_SESSION["designation_name"] = $user['designation_name'];
                        $_SESSION["district_name"] = $user['district_name'];
                        $_SESSION['taluk_name'] = $user['taluk_name'];
                        $_SESSION['hostel_name'] = $user['hostel_name'];
                        $_SESSION['staff_count'] = $user['staff_count'];
                        $_SESSION['staff_image'] = $user['file_names'];

$_SESSION['hostel_type'] = $user['hostel_type'];

                        $_SESSION['gender_id'] = $user['gender_id'];

                        $_SESSION['sess_user_type'] = $user['user_type'];
                        $_SESSION['sess_user_id'] = $user_id;
                        $_SESSION['sess_company_id'] = "comp5fa3b1c2a3bab70290";
                        $msg = "success_login";
                        // $samesite_value = 'Strict';

                        session_regenerate_id(true); // Regenerate session ID and update cookie

                        
                        // $sessionId = uniqid();


                        // // Set cookie with HttpOnly flag
                        // setcookie('PHPSESSID', $sessionId, [
                        //     'expires' => time() + 60 * 60 * 24, // One day
                        //     'path' => '/',
                        //     'httpOnly' => true,
                        //     'secure' => true, // Set if using HTTPS
                        //     'samesite' => 'Strict'
                        // ]);
                        //$sessionId = uniqid(); // Implement your session ID generation logic here

                        // // Set the PHPSESSID cookie with the dynamic value
                        //setcookie('PHPSESSID', $sessionId, time() + 3600, '/', '', true, true);

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
                        $date = date('Y-m-d');
                        $time = date('H:i:s');
                        // Assuming this is the username
                        $referrer = $_SERVER['HTTP_REFERER'] ?? 'N/A';

                        $processId = getmypid();

                        $url = $_SERVER['REQUEST_URI'];
                        $userAgent = $_SERVER['HTTP_USER_AGENT'];
                        $browserName = getBrowserName($userAgent);
                        $country = 'India';

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

                        // / Generate a dynamic value for the PHPSESSID cookie


                        $json_array = [
                            "status" => 1,
                            "data" => 1,
                            "error" => 0,
                            "msg" => "success_login",
                            "url" => $file_name,
                            // "test"      => $_SESSION["file_status"],
                            "com_status" => $user['com_status'],
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


function base256_decode($str)
{
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