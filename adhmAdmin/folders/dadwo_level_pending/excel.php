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

$district_name = $_GET['district_name'] ?? '';
$taluk_name = $_GET['taluk_name'] ?? '';
$hostel_name = $_GET['hostel_name'] ?? '';
$academic_year = $_GET['academic_year'] ?? '';

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);

// Merge cells for report title
$excel->getActiveSheet()->mergeCells('A1:G1');
$excel->getActiveSheet()->setCellValue('A1', 'DADWO Level Pending');

// Header fields
$excel->getActiveSheet()
    ->setCellValue('A3', 'S.No')
    ->setCellValue('B3', 'Entry Date')
    ->setCellValue('C3', 'Application No')
    ->setCellValue('D3', 'Student Name')
    ->setCellValue('E3', 'Hostel ID')
    ->setCellValue('F3', 'Hostel Name')
    ->setCellValue('G3', 'District Name');

$i = 0;
$row_val = 4;

$sql = ("SELECT 
            entry_date,
            std_app_no,
            std_name,
            (SELECT hostel_id FROM hostel_name WHERE unique_id = std_app_s.hostel_1) as hostel_id,
            (SELECT hostel_name FROM hostel_name WHERE unique_id = std_app_s.hostel_1) as hostel_name,
            (SELECT district_name FROM district_name WHERE district_name.unique_id = std_app_s.hostel_district_1) as district_name 
        FROM std_app_s WHERE is_delete = 0 AND submit_status = 1 and batch_no is NOT NULL and status = 0");

if ($district_name) {
    $sql .= " AND hostel_district_1 = '" . $district_name . "'";
}
if ($taluk_name) {
    $sql .= " AND hostel_taluk_1 = '" . $taluk_name . "'";
}
if ($hostel_name) {
    $sql .= " AND hostel_1 = '" . $hostel_name . "'";
}
if ($academic_year) {
    $sql .= " AND academic_year = '" . $academic_year . "'";
}

// print_r($sql);
$users = $conn->query($sql);
// print_r($users);
foreach ($users as $row) {

    // Fill Excel cells
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, $row["entry_date"] ?: '-')
        ->setCellValue('C' . $row_val, $row["std_app_no"] ?: '-')
        ->setCellValue('D' . $row_val, $row["std_name"] ?: '-')
        ->setCellValue('E' . $row_val, $row["hostel_id"] ?: '-')
        ->setCellValue('F' . $row_val, $row["hostel_name"] ?: '-')
        ->setCellValue('G' . $row_val, $row["district_name"] ?: '-');

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
foreach (range('A', 'G') as $columnID) {
    $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Output the Excel file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Student_Application_Report.xls"');
header('Cache-Control: max-age=0');

$fileDownload = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
ob_clean();
$fileDownload->save('php://output');

?>