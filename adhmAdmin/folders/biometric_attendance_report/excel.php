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
}

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);

// Merge cells for report title
$excel->getActiveSheet()->mergeCells('A1:K1');
$excel->getActiveSheet()->setCellValue('A1', 'Biometric Attendance Report');

// Header fields
$excel->getActiveSheet()
    ->setCellValue('A3', 'S.No')
    ->setCellValue('B3', 'Date')
    ->setCellValue('C3', 'District')
    ->setCellValue('D3', 'Taluk')
    ->setCellValue('E3', 'Hostel ID')
    ->setCellValue('F3', 'Hostel Name')
    ->setCellValue('G3', 'Student Biometric ID')
    ->setCellValue('H3', 'Student Registration No')
    ->setCellValue('I3', 'Student Name')
    ->setCellValue('J3', 'Morning Punch Time')
    ->setCellValue('K3', 'Evening Punch Time');

$i = 0;
$row_val = 4;

// Get the filter values from the request
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$district_name = isset($_GET['district_name']) ? $_GET['district_name'] : '';
$taluk_name = isset($_GET['taluk_name']) ? $_GET['taluk_name'] : '';
$hostel_name = isset($_GET['hostel_name']) ? $_GET['hostel_name'] : '';

// Build the WHERE clause dynamically based on provided filters
$whereClauses = ["dropout_status = 1"];

if ($from_date && $to_date) {
    $whereClauses[] = "currentDate BETWEEN :from_date AND :to_date";
}
if ($district_name) {
    $whereClauses[] = "district_name = :district_name";
}
if ($taluk_name) {
    $whereClauses[] = "taluk_name = :taluk_name";
}
if ($hostel_name) {
    $whereClauses[] = "hostel_unique_id = :hostel_name";
}

// Combine all conditions into a WHERE clause
$whereSQL = '';
if (count($whereClauses) > 0) {
    $whereSQL = ' WHERE ' . implode(' AND ', $whereClauses);
}

// Final SQL query with dynamic filters
$sql = "SELECT 
            currentDate,
            userId,
            std_reg_no,
            userName,
            hostel_id,
            punch_mrg,
            punch_eve,
            district_name_value,
            taluk_name_value,
            hostel_name
        FROM dayattreport" . $whereSQL;

$statement = $conn->prepare($sql);

// Bind parameters if necessary
if ($from_date && $to_date) {
    $statement->bindParam(':from_date', $from_date);
    $statement->bindParam(':to_date', $to_date);
}
if ($district_name) {
    $statement->bindParam(':district_name', $district_name);
}
if ($taluk_name) {
    $statement->bindParam(':taluk_name', $taluk_name);
}
if ($hostel_name) {
    $statement->bindParam(':hostel_name', $hostel_name);
}

// Execute the query
$statement->execute();
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $row) {
    // Fill Excel cells
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, $row["currentDate"] ?: '-')
        ->setCellValue('C' . $row_val, $row["district_name_value"] ?: '-')
        ->setCellValue('D' . $row_val, $row["taluk_name_value"] ?: '-')
        ->setCellValue('E' . $row_val, $row["hostel_id"] ?: '-')
        ->setCellValue('F' . $row_val, $row["hostel_name"] ?: '-')
        ->setCellValue('G' . $row_val, $row["userId"] ?: '-')
        ->setCellValue('H' . $row_val, $row["std_reg_no"] ?: '-')
        ->setCellValue('I' . $row_val, $row["userName"] ?: '-')
        ->setCellValue('J' . $row_val, $row["punch_mrg"] ?: '-')
        ->setCellValue('K' . $row_val, $row["punch_eve"] ?: '-');

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
foreach (range('A', 'K') as $columnID) {
    $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Output the Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Biometric_Attendance_Report.xlsx"');
header('Cache-Control: max-age=0');

$fileDownload = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
ob_clean();
$fileDownload->save('php://output');
