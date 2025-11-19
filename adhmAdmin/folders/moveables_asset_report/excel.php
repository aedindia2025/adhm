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
$excel->getActiveSheet()->mergeCells('A1:J1');
$excel->getActiveSheet()->setCellValue('A1', 'ADW Moveables Report');

// Header fields
$excel->getActiveSheet()
    ->setCellValue('A3', 'S.No')
    ->setCellValue('B3', 'Hostel Name')
    ->setCellValue('C3', 'Hostel ID')
    ->setCellValue('D3', 'District Name')
    ->setCellValue('E3', 'Taluk Name')
    ->setCellValue('F3', 'Asset ID')
    ->setCellValue('G3', 'Category')
    ->setCellValue('H3', 'Asset Type')
    ->setCellValue('I3', 'Asset Name')
    ->setCellValue('J3', 'Quantity')
    ->setCellValue('K3', 'Size');
   
    


$i = 0;
$row_val = 4;
if ($_GET['district_name']) {
    $where .= " AND  district_id = '".$_GET["district_name"]."'";
}
if($_GET['taluk_name']){
    $where .= " AND  taluk_id = '".$_GET['taluk_name']."'"; 
}
if($_GET['hostel_name'] && $_GET['hostel_name'] != NULL){
    $where .= " AND  hostel_id = '".$_GET['hostel_name']."'"; 
}

$sql = "SELECT asset_id,district_id,taluk_id,hostel_id,quantity,category,asset,big_small,k_d_category FROM view_moveables_asset WHERE hostel_id != '' $where order by hostel_id ASC";

$users = $conn->query($sql);
foreach ($users as $row) {
    
    $row['district_id'] = district_name($row['district_id'])[0]['district_name'];
   
    $row['taluk_id'] = taluk_name($row['taluk_id'])[0]['taluk_name'];
    $hostel_id = hostel_name($row['hostel_id'])[0]['hostel_id'];
    $row['hostel_id'] = hostel_name($row['hostel_id'])[0]['hostel_name'];
    
    if($row['k_d_category'] == '1'){
        $row['k_d_category'] = 'Kitchen';
        $row['category'] = kitchen_category($row['category'])[0]['category'];
        $row['asset'] = kitchen_asset($row['asset'])[0]['kitchen_asset'];

    }elseif($row['k_d_category'] == '2'){
        $row['k_d_category'] = 'Digital';
        $row['big_small'] = '-';
        $row['category'] = digital_category($row['category'])[0]['digital_category'];
        $row['asset'] = digital_asset($row['asset'])[0]['digital_asset'];
    }
    
 

    // Fill Excel cells
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, $row["hostel_id"] ?: '-')
        ->setCellValue('C' . $row_val, $hostel_id ?: '-')
        ->setCellValue('D' . $row_val, $row["district_id"] ?: '-')
        ->setCellValue('E' . $row_val, $row["taluk_id"] ?: '-')
        ->setCellValue('F' . $row_val, $row["asset_id"] ?: '-')
        ->setCellValue('G' . $row_val, $row["k_d_category"] ?: '-')
        ->setCellValue('H' . $row_val, $row["category"] ?: '-')
        ->setCellValue('I' . $row_val, $row["asset"] ?: '-')
        ->setCellValue('J' . $row_val, $row['quantity'] ?: '-')
        ->setCellValue('K' . $row_val, $row['big_small'] ? disname($row['big_small']) : '-');
        
       


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
foreach (range('A', 'K') as $columnID) {
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
