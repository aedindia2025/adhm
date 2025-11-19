<?php
// fetch_kitchen_assets.php

// Database connection
$mysqli = new mysqli('localhost', 'root', '4/rb5sO2s3TpL4gu', 'adi_dravidar');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$hostel_id = intval($_POST['hostel_id']);

// SQL query to fetch kitchen assets details
$sql = "SELECT asset AS name, category, quantity, big_small FROM view_moveables_asset WHERE hostel_id = $hostel_id";
$result = $mysqli->query($sql);

$assets = [];
while ($row = $result->fetch_assoc()) {
    $assets[] = $row;
}

echo json_encode($assets);
?>
