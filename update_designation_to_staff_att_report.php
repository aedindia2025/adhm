<?php

// DB Connection
$host = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$database = "adi_dravidar";

$mysqli = new mysqli($host, $username, $password, $database);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Step 1: Get all userIds from staff_attendance_report
$userIdQuery = "SELECT DISTINCT userId FROM staff_attendance_report WHERE userId IS NOT NULL";
$result = $mysqli->query($userIdQuery);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['userId'];
        $designation_un = null;
        $designation = null;

        // Step 2: Get designation from establishment_registration
        $stmt1 = $mysqli->prepare("SELECT designation FROM establishment_registration WHERE ifhrms_id = ?");
        $stmt1->bind_param("s", $userId);
        $stmt1->execute();
        $stmt1->bind_result($designation_un);
        $stmt1->fetch();
        $stmt1->close();

        if ($designation_un) {
            // Step 3: Get establishment_type from establishment_type
            $stmt2 = $mysqli->prepare("SELECT establishment_type FROM establishment_type WHERE unique_id = ?");
            $stmt2->bind_param("s", $designation_un);
            $stmt2->execute();
            $stmt2->bind_result($designation);
            $stmt2->fetch();
            $stmt2->close();
        }

        // Step 4: Update staff_attendance_report
        $updateStmt = $mysqli->prepare("UPDATE staff_attendance_report SET designation_un = ?, designation = ? WHERE userId = ?");
        $updateStmt->bind_param("sss", $designation_un, $designation, $userId);
        $updateStmt->execute();
        $updateStmt->close();
    }

    echo "Designation data updated successfully.";
} else {
    echo "No userIds found.";
}

$mysqli->close();
?>
