<?php

ini_set('memory_limit', '-1'); // Increase memory limit
set_time_limit(0); // Prevent script timeout

require_once("../../assets/PHPExcel-1.8/Classes/PHPExcel.php");
require("../../config/common_fun.php");

// Set cache method to use less memory
PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_to_discISAM);

// Database connection details
$driver = "mysql";
$host = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$databasename = "adi_dravidar";

try {
    $conn = new PDO($driver . ":host=" . $host . ";dbname=" . $databasename, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);

// Merge cells for report title
$excel->getActiveSheet()->mergeCells('A1:J1');
$excel->getActiveSheet()->setCellValue('A1', 'Staff Attendance Report');

// Header fields
$excel->getActiveSheet()
    ->setCellValue('A3', 'S.No')
    ->setCellValue('B3', 'Hostel ID')
    ->setCellValue('C3', 'Hostel Name')
    ->setCellValue('D3', 'District')
    ->setCellValue('E3', 'Hostel Type')
    ->setCellValue('F3', 'Sanctioned Strength')
    ->setCellValue('G3', 'DADWO Approved Count')
    ->setCellValue('H3', 'Date')
    ->setCellValue('I3', 'Morning Punch Count')
    ->setCellValue('J3', 'Evening Punch Count');

$i = 0;
$row_val = 4;

// ✅ Get the filter values from the request
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$district_name = isset($_GET['district_name']) ? $_GET['district_name'] : '';
$taluk_name = isset($_GET['taluk_name']) ? $_GET['taluk_name'] : '';
$hostel_name = isset($_GET['hostel_name']) ? $_GET['hostel_name'] : '';

// $where = " WHERE sar.hostel_id != '' ";

// // Apply filters dynamically
// if ($from_date && $to_date) {
//     $where .= " AND sar.currentDate BETWEEN '$from_date' AND '$to_date'";
// }

// if ($taluk_name) {
//     $where .= " AND sar.taluk_name = '$taluk_name'";
// }

// if ($hostel_name) {
//     $where .= " AND sar.hostel_unique_id = '$hostel_name'";
// }

// if ($district_name) {
//     $where .= " AND sar.district_name = '$district_name'";
// }

// // ✅ Final SQL query with dynamic filters
// $sql = "SELECT 
//             sar.hostel_id, 
//             sar.hostel_name, 
//             sar.district_name_value,
//             (SELECT ht.hostel_type  FROM hostel_type ht WHERE ht.unique_id = (SELECT hn.hostel_type FROM hostel_name hn WHERE hn.unique_id = sar.hostel_unique_id)) AS hostel_type,
//             (SELECT hn.sanc_staff_count FROM hostel_name hn WHERE hn.unique_id = sar.hostel_unique_id) AS sanc_staff_count,
//             (SELECT COUNT(*) FROM establishment_registration er WHERE er.status = 1 AND er.is_delete = 0 AND er.hostel_name = sar.hostel_id) AS dadwo_approved_count,
//             sar.currentDate,
//             (SELECT COUNT(*) FROM staff_attendance_report sar2 WHERE sar2.punch_mrg IS NOT NULL AND sar2.hostel_unique_id = sar.hostel_unique_id AND sar2.currentDate BETWEEN '$from_date' AND '$to_date') AS morning_punch_count,
//             (SELECT COUNT(*) FROM staff_attendance_report sar3 WHERE sar3.punch_noon IS NOT NULL AND sar3.hostel_unique_id = sar.hostel_unique_id AND sar3.currentDate BETWEEN '$from_date' AND '$to_date') AS eve_punch_count
//         FROM staff_attendance_report sar
//         $where
//         GROUP BY sar.hostel_id, sar.hostel_name, sar.district_name_value, sar.hostel_unique_id
//         ORDER BY sar.hostel_id ASC";

// // Debugging
// $statement = $conn->prepare($sql);
// $statement->execute();
// $users = $statement->fetchAll(PDO::FETCH_ASSOC);

$where = " WHERE sar.hostel_id != '' ";

// Apply filters dynamically
if ($from_date && $to_date) {
    $where .= " AND sar.currentDate BETWEEN '$from_date' AND '$to_date'";
}

if ($taluk_name) {
    $where .= " AND sar.taluk_name = '$taluk_name'";
}

if ($hostel_name) {
    $where .= " AND sar.hostel_unique_id = '$hostel_name'";
}

if ($district_name) {
    $where .= " AND sar.district_name = '$district_name'";
}

// ✅ Final SQL query with HAVING to remove rows with both counts = 0
$sql = "SELECT 
            sar.hostel_id, 
            sar.hostel_name, 
            sar.district_name_value,
            (SELECT ht.hostel_type FROM hostel_type ht WHERE ht.unique_id = (SELECT hn.hostel_type FROM hostel_name hn WHERE hn.unique_id = sar.hostel_unique_id)) AS hostel_type,
            (SELECT hn.sanc_staff_count FROM hostel_name hn WHERE hn.unique_id = sar.hostel_unique_id) AS sanc_staff_count,
            (SELECT COUNT(*) FROM establishment_registration er WHERE er.status = 1 AND er.is_delete = 0 AND er.hostel_name = sar.hostel_id) AS dadwo_approved_count,
            sar.currentDate,
            (SELECT COUNT(*) FROM staff_attendance_report sar2 WHERE sar2.punch_mrg IS NOT NULL AND sar2.hostel_unique_id = sar.hostel_unique_id AND sar2.currentDate BETWEEN '$from_date' AND '$to_date') AS morning_punch_count,
            (SELECT COUNT(*) FROM staff_attendance_report sar3 WHERE sar3.punch_noon IS NOT NULL AND sar3.hostel_unique_id = sar.hostel_unique_id AND sar3.currentDate BETWEEN '$from_date' AND '$to_date') AS eve_punch_count
        FROM staff_attendance_report sar
        $where
        GROUP BY sar.hostel_id, sar.hostel_name, sar.district_name_value, sar.hostel_unique_id
        HAVING morning_punch_count > 0 OR eve_punch_count > 0
        ORDER BY sar.hostel_id ASC";

// Debugging
$statement = $conn->prepare($sql);
$statement->execute();
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

// ✅ Populate Excel
foreach ($users as $row) {
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, $row["hostel_id"] ?: '-')
        ->setCellValue('C' . $row_val, $row["hostel_name"] ?: '-')
        ->setCellValue('D' . $row_val, $row["district_name_value"] ?: '-')
        ->setCellValue('E' . $row_val, $row["hostel_type"] ?: '-')
        ->setCellValue('F' . $row_val, $row["sanc_staff_count"] ?: '-')
        ->setCellValue('G' . $row_val, $row["dadwo_approved_count"] ?: '0')
        ->setCellValue('H' . $row_val, ($row["currentDate"] ? disdate($row["currentDate"]) : '-'))
        ->setCellValue('I' . $row_val, $row["morning_punch_count"] ?: '0')
        ->setCellValue('J' . $row_val, $row["eve_punch_count"] ?: '0');

    $row_val++;
}

// Styling
$styleArray = array(
    'font' => array(
        'bold' => true
    )
);
$excel->getActiveSheet()->getStyle('A1:Z1')->applyFromArray($styleArray);
$excel->getActiveSheet()->getStyle('A3:Z3')->applyFromArray($styleArray);

$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
foreach (range('A', 'J') as $columnID) {
    $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Output the Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Staff_Attendance_Report.xlsx"');
header('Cache-Control: max-age=0');

$fileDownload = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

// Make sure buffer is clean before output
if (ob_get_length()) {
    ob_end_clean();
}

$fileDownload->save('php://output');
exit;

?>