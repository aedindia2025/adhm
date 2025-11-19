<?php

include '../../config/dbconfig.php';

// Capture relevant information
$ipAddress = $_SERVER['REMOTE_ADDR'];
$date = date('Y-m-d');
$time = date('H:i:s');
$username = $_POST['user_name']; // Assuming this is the username
$referrer = $_SERVER['HTTP_REFERER'] ?? 'N/A';
$processId = getmypid();
$url = $_SERVER['REQUEST_URI'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];

// Log the authentication process
$logMessage = "IP: $ipAddress, Date: $date, Time: $time, Username: $username, Referrer: $referrer, Process ID: $processId, URL: $url, User Agent: $userAgent";
logEvent('Authentication Process', $logMessage);

echo $time = date('Y-m-d');


function logEvent($action, $details) {
    // Open or create the log file
    $logFile = fopen('logs/logfile.txt', 'a');

    // Get the next log number (auto-incrementing)
    $logNumber = getNextLogNumber(); // You need to implement this function

    // Format the log entry
    $logEntry = "$logNumber, $action, $details";

    // Write the log entry to the file
    fwrite($logFile, "$logEntry\n");

    // Close the log file
    fclose($logFile);
}

function getNextLogNumber() {
    // Open the log file to read the last log number
    $logFile = fopen('logs/logfile.txt', 'r');

    // Read the last line to get the last log number
    $lastLine = '';
    while (!feof($logFile)) {
        $lastLine = fgets($logFile);
    }

    // Extract the log number from the last line
    $lastLogNumber = explode(',', $lastLine)[0];

    // Increment the last log number to get the next log number
    $nextLogNumber = $lastLogNumber + 1;

    // Close the log file
    fclose($logFile);

    // Return the next log number
    return $nextLogNumber;
}


?>