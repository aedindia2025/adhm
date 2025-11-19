<?php
$host = 'localhost';
$db = 'adi_dravidar';
$user = 'root';
$pass = '4/rb5sO2s3TpL4gu';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 5: Fix orphaned hostel details using transfer_hostel
$sql = "SELECT unique_id, transfer_hostel FROM std_app_s WHERE is_delete = 0 AND transfer_hostel IS NOT NULL AND transfer_hostel != ''";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $unique_id = $row['unique_id'];
        $transfer_hostel = $row['transfer_hostel'];

        // Get district_name and taluk_name from hostel_name table
        $hostel_query = "SELECT district_name, taluk_name FROM hostel_name WHERE is_delete = 0 AND unique_id = ?";
        $stmt = $conn->prepare($hostel_query);
        $stmt->bind_param("s", $transfer_hostel);
        $stmt->execute();
        $hostel_result = $stmt->get_result();

        if ($hostel_result && $hostel_result->num_rows > 0) {
            $hostel_data = $hostel_result->fetch_assoc();
            $district_name = $hostel_data['district_name'];
            $taluk_name = $hostel_data['taluk_name'];

            // Update std_app_s
            $update_query = "UPDATE std_app_s SET hostel_1 = ?, hostel_district_1 = ?, hostel_taluk_1 = ? WHERE unique_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ssss", $transfer_hostel, $district_name, $taluk_name, $unique_id);
            if ($update_stmt->execute()) {
                echo "Updated std_app_s for unique_id: $unique_id\n";
            } else {
                echo "Failed to update std_app_s for unique_id: $unique_id\n";
            }
            $update_stmt->close();
        } else {
            echo "No hostel_name data found for transfer_hostel: $transfer_hostel\n";
        }

        $stmt->close();
    }
} else {
    echo "No students with transfer_hostel found.\n";
}

$conn->close();
?>
