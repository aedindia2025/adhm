<?php

ini_set('memory_limit', '-1'); // Increase memory limit
set_time_limit(0); // Prevent script timeout

require_once("../../assets/PHPExcel-1.8/Classes/PHPExcel.php");
require("../../config/dbconfig.php");

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
$excel->getActiveSheet()->mergeCells('A1:P1');
$excel->getActiveSheet()->setCellValue('A1', 'ADW Establishment Report');

// Header fields
$excel->getActiveSheet()
    ->setCellValue('A3', 'S.No')
    ->setCellValue('B3', 'Hostel Name')
    ->setCellValue('C3', 'Hostel ID')
    ->setCellValue('D3', 'District Name')
    ->setCellValue('E3', 'Taluk Name')
    ->setCellValue('F3', 'Applied Date')
    ->setCellValue('G3', 'Staff Name')
    ->setCellValue('H3', 'DOB')
    ->setCellValue('I3', 'Gender')
    ->setCellValue('J3', 'Mobile Number')
    ->setCellValue('K3', 'Home District')
    ->setCellValue('L3', 'IFHRMS ID')
    ->setCellValue('M3', 'Designation')
    ->setCellValue('N3', 'Status')
    ->setCellValue('O3', 'DADWO Action Date')
    ->setCellValue('P3', 'Reject Reason');
    


$i = 0;
$row_val = 4;

$sql = "SELECT * FROM establishment_registration WHERE is_delete = 0";

$users = $conn->query($sql);
foreach ($users as $row) {
    
    $row['district_office'] = district_name($row['district_office'])[0]['district_name'];
    $row['district_name'] = district_name($row['district_name'])[0]['district_name'];
    $row['taluk_office'] = taluk_name($row['taluk_office'])[0]['taluk_name'];
    $hostel_id = hostel_name($row['hostel_name'])[0]['hostel_id'];
    $row['hostel_name'] = hostel_name($row['hostel_name'])[0]['hostel_name'];
    $row['designation'] = establishment_type($row['designation'])[0]['establishment_type'];
    
   
   

    if($row['status'] == '1'){
        $row['status'] = 'Approved';
        $row['reject_reason'] = '-';
        
    }elseif($row['status'] == '2'){
        $row['status'] = 'Rejected';
    }else{
        $row['status'] = 'Pending';
        $row['status_upd_date'] = '-';
        $row['reject_reason'] = '-';
    }

 





    // Fill Excel cells
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, $row["hostel_name"] ?: '-')
        ->setCellValue('C' . $row_val, $hostel_id ?: '-')
        ->setCellValue('D' . $row_val, $row["district_office"] ?: '-')
        ->setCellValue('E' . $row_val, $row["taluk_office"] ?: '-')
        ->setCellValue('F' . $row_val, disdate($row["entry_date"]) ?: '-')
        ->setCellValue('G' . $row_val, disname($row["staff_name"]) ?: '-')
        ->setCellValue('H' . $row_val, disdate($row["dob"]) ?: '-')
        ->setCellValue('I' . $row_val, disname($row["gender_name"]) ?: '-')
        ->setCellValue('J' . $row_val, $row['mobile_num'] ?: '-')
        ->setCellValue('K' . $row_val, $row['district_name'] ?: '-')
        ->setCellValue('L' . $row_val, $row['ifhrms_id'] ?: '-')
        ->setCellValue('M' . $row_val, $row['designation'] ?: '-')
        ->setCellValue('N' . $row_val, $row['status'] ?: '-')
        ->setCellValue('O' . $row_val, disdate($row['status_upd_date']) ?: '-')
        ->setCellValue('P' . $row_val, $row["reject_reason"] ?: '-');
       


    $row_val++;
}

// Styling
$styleArray = array(
    'font' => array(
        'bold' => true
    )
);
$excel->getActiveSheet()->getStyle('A1:Z1')->applyFromArray($styleArray);
$excel->getActiveSheet()->getStyle('A3:AD3')->applyFromArray($styleArray);

$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
foreach (range('A', 'P') as $columnID) {
    $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Output the Excel file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Hostel_Creation_Report.xls"');
header('Cache-Control: max-age=0');

$fileDownload = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
ob_clean();
$fileDownload->save('php://output');

?>
