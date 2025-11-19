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

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);

// Merge cells for report title
$excel->getActiveSheet()->mergeCells('A1:G1');
$excel->getActiveSheet()->setCellValue('A1', 'Student below 5KM from Institute');

// Header fields
$excel->getActiveSheet()
    ->setCellValue('A3', 'S.No')
    ->setCellValue('B3', 'Registration Number')
    ->setCellValue('C3', 'Student Name')
    ->setCellValue('D3', 'Biometric ID')
    ->setCellValue('E3', 'Face ID Status')
    ->setCellValue('F3', 'Fingerprint Status')
    ->setCellValue('G3', 'Direct/Exception Renewal');

$i = 0;
$row_val = 4;

$sql = ("SELECT 
            std_reg_no,
            std_name,
            face_id_status,
            fingerprint_status,
            renewal_status 
        FROM std_reg_s WHERE is_delete = 0 AND std_to_inst_distance <= 5");
        //  AND dropout_status = 1

if ($district_name) {
    $sql .= " AND hostel_district_1 = '" . $district_name . "'";
}
if ($taluk_name) {
    $sql .= " AND hostel_taluk_1 = '" . $taluk_name . "'";
}
if ($hostel_name) {
    $sql .= " AND hostel_1 = '" . $hostel_name . "'";
}

$users = $conn->query($sql);
foreach ($users as $row) {

    // Extract numeric part from std_reg_no
    $std_biometric_no = preg_replace('/\D/', '', $row['std_reg_no']);

    if ($row['face_id_status'] == 1) {
        $row['face_id_status'] = "Registered";
    } else {
        $row['face_id_status'] = "Not Registered";
    }

    if ($row['fingerprint_status'] == 1) {
        $row['fingerprint_status'] = "Registered";
    } else {
        $row['fingerprint_status'] = "Not Registered";
    }

    if ($row['renewal_status'] == 1) {
        $row['renewal_status'] = "Direct";
    } else if ($row['renewal_status'] == 2) {
        $row['renewal_status'] = "Exception";
    } else if ($row['renewal_status'] == 3) {
        $row['renewal_status'] = "Continuation";
    } else {
        $row['renewal_status'] = "New";
    }

    // Fill Excel cells
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, $row["std_reg_no"] ?: '-')
        ->setCellValue('C' . $row_val, $row["std_name"] ?: '-')
        ->setCellValue('D' . $row_val, $std_biometric_no ?: '-')
        ->setCellValue('E' . $row_val, $row["face_id_status"] ?: '-')
        ->setCellValue('F' . $row_val, $row["fingerprint_status"] ?: '-')
        ->setCellValue('G' . $row_val, $row["renewal_status"] ?: '-');

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