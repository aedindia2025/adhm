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
    ->setCellValue('B3', 'Hostel ID')
    ->setCellValue('C3', 'Hostel Name')
    ->setCellValue('D3', 'District')
    ->setCellValue('E3', 'Hostel Type')
    ->setCellValue('F3', 'Sanctioned Strength')
    ->setCellValue('G3', 'DADWO Approved Count')
    ->setCellValue('H3', 'Biometric Registered Count')
    ->setCellValue('I3', 'Date')
    ->setCellValue('J3', 'Morning Punch Count')
    ->setCellValue('K3', 'Evening Punch Count');

$i = 0;
$row_val = 4;

// Get the filter values from the request
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

$taluk_name = isset($_GET['taluk_name']) ? $_GET['taluk_name'] : '';
$hostel_name = isset($_GET['hostel_name']) ? $_GET['hostel_name'] : '';

// Build the WHERE clause dynamically based on provided filters
$where = "district_id = '".$_GET['district_id']."'";

if ($from_date && $to_date) {
    $where .= " and report_date BETWEEN '$from_date' AND '$to_date'";
}

if ($taluk_name) {
    $where .= " and taluk_name = '$taluk_name'";
}

if ($hostel_name) {
    $where .= " and hostel_unique_id = '$hostel_name'";
}

// Final SQL query with dynamic filters
$sql = "SELECT 
            hostel_id,
            hostel_name,
            district_name,
            hostel_type,
            sanctioned_strength,
            dadwo_approved_count,
            biometric_reg_count,
            report_date,
            morning_punch_count as morning_punch_count, 
            eve_punch_count as eve_punch_count
        FROM attendance_report where " . $where . "order by hostel_id,report_date ASC";
      

$statement = $conn->prepare($sql);




// Execute the query
$statement->execute();
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $row) {
    // Fill Excel cells
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, $row["hostel_id"] ?: '-')
        ->setCellValue('C' . $row_val, $row["hostel_name"] ?: '-')
        ->setCellValue('D' . $row_val, $row["district_name"] ?: '-')
        ->setCellValue('E' . $row_val, $row["hostel_type"] ?: '-')
        ->setCellValue('F' . $row_val, $row["sanctioned_strength"] ?: '-')
        ->setCellValue('G' . $row_val, $row["dadwo_approved_count"] ?: '-')
        ->setCellValue('H' . $row_val, $row["biometric_reg_count"] ?: '-')
        ->setCellValue('I' . $row_val, disdate($row["report_date"]) ?: '-')
        ->setCellValue('J' . $row_val, $row["morning_punch_count"] ?: '-')
        ->setCellValue('K' . $row_val, $row["eve_punch_count"] ?: '-');

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
