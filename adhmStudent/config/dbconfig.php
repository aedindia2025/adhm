<?php
error_reporting(0);

session_start();
date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)

$trusted_domains = [
//    '10.236.204.175',
//  'nallosai.tn.gov.in'
'103.110.236.187',
'192.168.0.31',
'localhost'
];

// Retrieve the Host header
$host = $_SERVER['HTTP_HOST'];

// Validate the Host header
if (!in_array($host, $trusted_domains)) {
    // If the host is not in the whitelist, reject the request
    // header('HTTP/1.1 400 Bad Request');
    // header('Location: http://10.236.204.175/404.html');

    exit;
}

// Development Server Configuration
$de_driver         = "mysql";
$de_host           = "localhost";
$de_username       = "root";
$de_password       = "4/rb5sO2s3TpL4gu";
$de_databasename   = "adi_dravidar";

// Production Server Configuration
$pr_driver         = "mysql";
$pr_host           = "localhost";
$pr_username       = "root";
$pr_password       = "4/rb5sO2s3TpL4gu";
$pr_databasename   = "adi_dravidar";

// Import Database Common Class file
include_once 'dbclass.php';


// Production Server Auto Detect PHP function Start
$whitelist = array(
    '127.0.0.1',
    '::1'
);


$host = $username = $password = $databasename = '';

if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
    // Production server configuration
    $host = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $databasename = "adi_dravidar";
} else {
    // Development server configuration
    $host = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $databasename = "adi_dravidar";
}

// Connect to the database using MySQLi
$mysqli = new mysqli($host, $username, $password, $databasename);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}



require_once "SecureUPload/src/autoloader.php";


if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    $pdo            = new Db($pr_driver, $pr_host, $pr_username, $pr_password, $pr_databasename);
} else {
    $pdo            = new Db($de_driver, $de_host, $de_username, $de_password, $de_databasename);
}

// File Upload Configuration
try {

    $fileUploadConfig = new Alirdn\SecureUPload\Config\SecureUPloadConfig();

    // Upload Configuration
    // Below Configuration over writes default configuration
    $fileUploadConfig->setArray(
        array(
            'upload_folder' => dirname( __FILE__ ,2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR,
            'storage_type'  => 3
        )
    );
    // Current Upload Directiry [upload_folder] => C:\xampp\htdocs\ascent\uploads\



    // More configuration Details see config/SecureUPload/src/config/SecureUPloadConfig.php file 

} catch ( Alirdn\SecureUPload\Exceptions\UploadFolderException $exception ) {
    echo "Exception: " . $exception->getMessage() . ' Code: ' . $exception->getCode() . ' Note: For more information check php error_log.';
    die();
}
    // Common Functions Import 
    include_once 'common_fun.php';
    
// Import Database Common Class file
include_once 'dbclass.php';

?>