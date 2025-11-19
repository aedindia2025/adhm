<?php
session_start();
include '../../config/dbconfig.php'; // your DB connection

$table = "holiday_master";

$action = $_REQUEST['action'] ?? '';
$unique_id = $_REQUEST['unique_id'] ?? '';
$holiday_date = $_REQUEST['holiday_date'] ?? '';
$description = $_REQUEST['description'] ?? '';
$range_id = $_REQUEST['range_id'] ?? null; // new param for range_id
$status = "";
$msg = "";

// CSRF validation
function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Generate unique ID
function generateUniqueId()
{
    return bin2hex(random_bytes(8));
}

// Generate range ID (shorter unique id)
function generateRangeId()
{
    return bin2hex(random_bytes(6));
}

switch ($action) {

    case 'add_update':
        $token = $_REQUEST['csrf_token'] ?? '';
        if (!validateCSRFToken($token)) {
            die(json_encode(["status" => "error", "msg" => "CSRF validation failed"]));
        }

        // If range_id is provided, use it, else NULL (single date)
        $range_id = $range_id ?: null;

        if ($unique_id == "") {
            // INSERT new holiday entry
            $unique_id = generateUniqueId();
            $stmt = $mysqli->prepare("INSERT INTO $table (unique_id, holiday_date, description, range_id) VALUES (?,?,?,?) 
                                      ON DUPLICATE KEY UPDATE description=?, is_delete=0");
            $stmt->bind_param("sssss", $unique_id, $holiday_date, $description, $range_id, $description);
            $msg = "Holiday added successfully!";
            $execResult = $stmt->execute();
        } else {
            if ($range_id) {
                // UPDATE all holidays with this range_id (edit range description and dates if needed)
                // Usually date changes for ranges are complicated — you can decide to disallow or handle carefully.
                // Here we'll update description for all with same range_id
                $stmt = $mysqli->prepare("UPDATE $table SET description=?, is_delete=0 WHERE range_id=?");
                $stmt->bind_param("ss", $description, $range_id);
                $execResult = $stmt->execute();
                $msg = "Holiday range updated successfully!";
            } else {
                // UPDATE single holiday
                $stmt = $mysqli->prepare("UPDATE $table SET holiday_date=?, description=?, is_delete=0 WHERE unique_id=?");
                $stmt->bind_param("sss", $holiday_date, $description, $unique_id);
                $execResult = $stmt->execute();
                $msg = "Holiday updated successfully!";
            }
        }

        if ($execResult) {
            $status = "success";
        } else {
            $status = "error";
            $msg = "Database error: " . $stmt->error;
        }
        $stmt->close();
        header('Content-Type: application/json');
        echo json_encode(["status" => $status, "msg" => $msg]);
        break;

    case 'delete':
        $token = $_REQUEST['csrf_token'] ?? '';
        $range_id = $_REQUEST['range_id'] ?? '';
        $holiday_date = $_REQUEST['holiday_date'] ?? '';

        if (!validateCSRFToken($token)) {
            die(json_encode(["status" => "error", "msg" => "CSRF validation failed"]));
        }

        if ($range_id) {
            // Delete all holidays with this range_id
            $stmt = $mysqli->prepare("UPDATE $table SET is_delete=1 WHERE range_id=?");
            $stmt->bind_param("s", $range_id);
            $msg = "Holiday range deleted successfully!";
        } elseif ($holiday_date) {
            // Delete single holiday by date
            $stmt = $mysqli->prepare("UPDATE $table SET is_delete=1 WHERE holiday_date=?");
            $stmt->bind_param("s", $holiday_date);
            $msg = "Single holiday deleted successfully!";
        } else {
            echo json_encode(["status" => "error", "msg" => "Invalid parameters"]);
            exit;
        }

        if ($stmt->execute()) {
            $status = "success";
        } else {
            $status = "error";
            $msg = "Database error: " . $stmt->error;
        }
        $stmt->close();

        header('Content-Type: application/json');
        echo json_encode(["status" => $status, "msg" => $msg]);
        break;

    case 'fetch':
        $events = [];

        // First, fetch all holidays (not deleted)
        $result = $mysqli->query("SELECT unique_id, holiday_date, description, range_id FROM $table WHERE is_delete=0 ORDER BY holiday_date");

        $rangeGroups = [];
        $singleEvents = [];

        while ($row = $result->fetch_assoc()) {
            if ($row['range_id']) {
                // Group by range_id
                $rangeGroups[$row['range_id']][] = $row;
            } else {
                $singleEvents[] = $row;
            }
        }

        // Process ranges to create single event per range with date span
        foreach ($rangeGroups as $rId => $holidays) {
            // Sort holidays by date
            usort($holidays, function ($a, $b) {
                return strcmp($a['holiday_date'], $b['holiday_date']);
            });
            $startDate = $holidays[0]['holiday_date'];
            $endDate = end($holidays)['holiday_date'];

            $events[] = [
                'id' => $rId,
                'start' => $startDate,
                'end' => date('Y-m-d', strtotime($endDate . ' +1 day')), // FullCalendar end date is exclusive, so add 1 day
                'title' => $holidays[0]['description'],
                'color' => '#ff6666',
                'allDay' => true
            ];
        }

        // Add single date holidays
        foreach ($singleEvents as $single) {
            $events[] = [
                'id' => $single['unique_id'],
                'start' => $single['holiday_date'],
                'title' => $single['description'],
                'color' => '#ff6666',
                'allDay' => true
            ];
        }

        echo json_encode($events);
        break;

    default:
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "msg" => "Invalid action"]);
        break;
}
?>