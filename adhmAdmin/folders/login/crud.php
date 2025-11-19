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




$table = 'user';
$table_ses = 'active_sessions';

// Include DB file and Common Functions
include '../../config/dbconfig.php';
// include '../../config/dbclass.php';

// Variables Declaration
$action = $_POST['action'];
$action_obj = (object) [
    'status' => 0,
    'data' => '',
    'error' => 'Action Not Performed',
];
$json_array = '';
$sql = '';

$data = '';
$msg = '';
$error = '';
$status = '';
$test = ''; // For Developer Testing Purpose

// $acc_year       = explode(" to ",$_POST['acc_year']);
// $from_year      = (explode("-",$acc_year[0]))[0];
// $to_year        = (explode("-",$acc_year[1]))[0];

// $acc_year       = explode(" to ",$_POST['acc_year']);
$from_year = '2020';
$to_year = '2021';

$sess_acc_year = $from_year . '-' . $to_year;


switch ($action) {
    case 'login':
        $user_name = $_POST['user_name'];
        $password_with_token = $_POST['password']; // Password with token
        $captcha = $_POST['captcha'];
        $encode_token = $_POST['token'];
        $token = base256_decode($encode_token);
        // print_r($token);
        $token_length = strlen($token);
        $password = substr($password_with_token, 0, -$token_length);
        //print_r($password);

        $extracted_token = substr($password_with_token, -$token_length);



        if ($extracted_token == $token) {

            if ($captcha == $_SESSION['captcha_text'] && $captcha != '') {
                $columns = [
                    'COUNT(unique_id) AS count',
                    'user_type_unique_id as user_type',
                    'staff_name',
                    'unique_id',
                    '(SELECT user_type FROM user_type AS ust WHERE ust.unique_id = ' . $table . '.user_type_unique_id ) AS user_type_name',
                ];

                $table_details = [
                    $table,
                    $columns,
                ];

                $where = [
                    'user_name' => $user_name,
                    'password' => $password,
                    'is_active' => 1,
                    'is_delete' => 0,
                ];

                $where = "BINARY user_name = '" . $user_name . "' AND hashedPassword =  '" . $password . "' AND is_active = 1 AND is_delete = 0";

                $action_obj = $pdo->select($table_details, $where);

                // print_r($action_obj);

                if ($action_obj->status) {
                    $count_data = $action_obj->data[0]['count'];
                    if ($count_data == 1) {
                        $user = $action_obj->data[0];
                        $user_id = $user['unique_id'];

                        // // Check if user already has an active session
                        // $table_details = [
                        //     $table_ses,
                        //     [
                        //         "COUNT(user_id) AS count"
                        //     ]
                        // ];
                        // $select_where_ses = 'user_id = "' . $user_id . '" ';

                        // $action_obj_ses = $pdo->select($table_details, $select_where_ses);

                        // $active_count = $action_obj_ses->data[0]['count'];

                        // if ($active_count > 0) {
                        //     // User already has an active session, prevent login
                        //     $json_array = [
                        //         'status' => 0,
                        //         'data' => 0,
                        //         'error' => 0,
                        //         'msg' => 'already_logged_in',
                        //     ];
                        // } else {

                            $msg = 'success_login';

                            $user_id = $user['unique_id'];
                            $_SESSION['acc_year'] = $sess_acc_year;
                            $_SESSION['user_id'] = $user_id;
                            $_SESSION['sess_user_type_name'] = $user['user_type_name'];
                            $_SESSION['staff_name'] = $user['staff_name'];
                            $_SESSION['user_name'] = $user_name;
                            $_SESSION['sess_user_type'] = $user['user_type'];
                            $_SESSION['sess_user_id'] = $user_id;
                            $_SESSION['sess_company_id'] = 'comp5fa3b1c2a3bab70290';
                            $_SESSION['sess_branch_id'] = 'bran5hi9k2g6a3cas13270';
                            $msg = 'success_login';


                            session_regenerate_id(true); // Regenerate session ID and update cookie


                            $permissions = menu_permission($user['user_type']);

                            $main_screens = $permissions['main_screens'];

                            $screens = $permissions['screens'];

                            $_SESSION['main_screens'] = $main_screens;

                            $_SESSION['screens'] = $screens;

                            $_SESSION['root_folderName'] = $root_folderName;

                            $password = '3sc3RLrpd17';
                            $enc_method = 'aes-256-cbc';
                            $enc_password = substr(hash('sha256', $password, true), 0, 32);
                            $enc_iv = 'av3DYGLkwBsErphc';

                            $menu_screen = 'dashboard/form';
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


                            // $session_id = uniqid();
                            // $columns_session = [
                            //     "user_name" => $user_name,
                            //     'user_id' => $user_id,
                            //     'session_id' => $session_id,
                            // ];
                            // $result_ses = $pdo->insert($table_ses, $columns_session);

                            //print_r($result_ses);


                            $json_array = [
                                'status' => 1,
                                'data' => 1,
                                'error' => 0,
                                'msg' => 'success_login',
                                'url' => $file_name,
                                // "test"      => $_SESSION["file_status"],
                                'sql' => $_SESSION['user_id'],
                            ];

                        // }

                    } else {
                        // Incorrect username and password handling
                        $json_array = [
                            'status' => 0,
                            'data' => 1,
                            'error' => 0,
                            'msg' => 'incorrect',
                            'sql' => '',
                        ];
                    }
                } else {
                    $msg = 'error';
                }
            } else {
                $json_array = [
                    'status' => 0,
                    'data' => 1,
                    'error' => 0,
                    'msg' => 'invalid_captcha',
                    'sql' => '',
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