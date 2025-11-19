<?php
// Database connection
$mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Step 1: Fetch records from std_reg_s table, ordered by unique_id and std_reg_no
$sql = "SELECT unique_id, std_reg_no FROM std_reg_s WHERE is_delete = '0' ORDER BY id ASC";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $counter = 1; // Counter to serialize the last 6 digits

    // Step 2: Loop through each record
    while ($row = $result->fetch_assoc()) {
        $unique_id = $row['unique_id'];
        $std_reg_no = $row['std_reg_no'];

        // Step 3: Extract the prefix and last 6 digits from std_reg_no
        $prefix = substr($std_reg_no, 0, -6); // Everything except the last 6 digits
        $new_last_six = str_pad($counter, 6, '0', STR_PAD_LEFT); // Generate new last 6 digits

        // Generate new std_reg_no by combining the prefix and incremented last 6 digits
        $new_std_reg_no = $prefix . $new_last_six;

        // Step 4: Update std_reg_no for the specific unique_id
        $update_sql = "UPDATE std_reg_s SET user_name = ?, std_reg_no = ? WHERE unique_id = ?";
        $stmt = $mysqli->prepare($update_sql);
        $stmt->bind_param("sss", $new_std_reg_no, $new_std_reg_no, $unique_id);

        if ($stmt->execute()) {
            echo "Updated std_reg_no for unique_id: " . $unique_id . " to " . $new_std_reg_no . "<br>";
        } else {
            echo "Error updating std_reg_no for unique_id: " . $unique_id . "<br>";
        }

        // Increment the counter for the next std_reg_no
        $counter++;
    }
} else {
    echo "No records found.";
}

// Close the connection
$mysqli->close();
?>
