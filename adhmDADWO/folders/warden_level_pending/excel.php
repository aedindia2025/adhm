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
$username = "media_root";
$password = "media@123";
$databasename = "adw_biometric";

try {
    $conn = new PDO($driver . ":host=" . $host . ";dbname=" . $databasename, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);

// Merge cells for report title
$excel->getActiveSheet()->mergeCells('A1:F1');
$excel->getActiveSheet()->setCellValue('A1', 'Registered Student List - Biometric Device');

// Header fields
$excel->getActiveSheet()
    ->setCellValue('A3', 'S.No')
    ->setCellValue('B3', 'Hostel Name')
    ->setCellValue('C3', 'Taluk Name')
    ->setCellValue('D3', 'Hostel ID')
    ->setCellValue('E3', 'Hostel Name')
    ->setCellValue('F3', 'Registration Number')
    ->setCellValue('G3', 'Student Name');

$i = 0;
$row_val = 4;

$sql = ("SELECT 
    (SELECT district_name FROM district_name WHERE district_name.unique_id = std_reg_s.hostel_district_1) as district_name,
    (SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = std_reg_s.hostel_taluk_1) as taluk_name,
    (SELECT hostel_name FROM hostel_name WHERE hostel_name.unique_id = std_reg_s.hostel_1) as hostel_name,
    (SELECT hostel_id FROM hostel_name WHERE hostel_name.unique_id = std_reg_s.hostel_1) as hostel_id,
    std_reg_no,
    std_name
    
FROM std_reg_s WHERE is_delete = 0 AND bio_reg_status = 1");

$users = $conn->query($sql);
foreach ($users as $row) {
   
    // Fill Excel cells
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, $row["district_name"] ?: '-')
        ->setCellValue('C' . $row_val, $row["taluk_name"] ?: '-')
        ->setCellValue('D' . $row_val, $row["hostel_name"] ?: '-')
        ->setCellValue('E' . $row_val, $row["hostel_id"] ?: '-')
        ->setCellValue('F' . $row_val, $row["std_reg_no"] ?: '-')
        ->setCellValue('G' . $row_val, $row["std_name"] ?: '-');

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
