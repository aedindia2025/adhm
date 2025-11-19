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
$password = "";
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
$excel->getActiveSheet()->mergeCells('A1:AD1');
$excel->getActiveSheet()->setCellValue('A1', 'ADW Hostel Report');

// Header fields
$excel->getActiveSheet()
    ->setCellValue('A3', 'S.No')
    ->setCellValue('B3', 'Hostel Name')
    ->setCellValue('C3', 'Hostel ID')
    ->setCellValue('D3', 'District Name')
    ->setCellValue('E3', 'Taluk Name')
    ->setCellValue('F3', 'Special Thasildhar')
    ->setCellValue('G3', 'Assembly Constituency')
    ->setCellValue('H3', 'Parliament Constituency')
    ->setCellValue('I3', 'Hostel Address')
    ->setCellValue('J3', 'Hostel Location')
    ->setCellValue('K3', 'Block Name')
    ->setCellValue('L3', 'Village Name')
    ->setCellValue('M3', 'Urban Type')
    ->setCellValue('N3', 'Corporation Name')
    ->setCellValue('O3', 'Municipality Name')
    ->setCellValue('P3', 'Town Panchayat Name')
    ->setCellValue('Q3', 'Hostel Type')
    ->setCellValue('R3', 'Hostel Gender Category')
    ->setCellValue('S3', 'Year Of Established')
    ->setCellValue('T3', 'Sanctioned Strength')
    ->setCellValue('U3', 'Km Distance B/W PHC & Hostel')
    ->setCellValue('V3', 'PHC Name')
    ->setCellValue('W3', 'Km Distance B/W Police Station & Hostel')
    ->setCellValue('X3', 'Police Station Name')
    ->setCellValue('Y3', 'Staff Count')
    ->setCellValue('Z3', 'Building Status')
    ->setCellValue('AA3', 'Ownership')
    ->setCellValue('AB3', 'Reason')
    ->setCellValue('AC3', 'Hybrid Hostel')
    ->setCellValue('AD3', 'Hostel Upgraded?');


$i = 0;
$row_val = 4;

$sql = ("SELECT * FROM hostel_name WHERE is_delete = 0");

$users = $conn->query($sql);
foreach ($users as $row) {
    
    $row['district_name'] = district_name($row['district_name'])[0]['district_name'];
    $row['taluk_name'] = taluk_name($row['taluk_name'])[0]['taluk_name'];
    $row['hostel_type'] = hostel_type_name($row['hostel_type'])[0]['hostel_type'];
    $row['gender_type'] = hostel_gender_name($row['gender_type'])[0]['gender_type'];
    if($row['assembly_const']){
    $row['assembly_const'] = assembly_constituency($row['assembly_const'])[0]['assembly_const_name'];
    }
    if($row['parliment_const']){
    $row['parliment_const'] = parliment_constituency($row['parliment_const'])[0]['parliament_const_name'];
    }
    if($row['block_name']){
    $row['block_name'] = block($row['block_name'])[0]['block_name'];
    }
    if($row['village_name']){
    $row['village_name'] = village_name($row['village_name'])[0]['village_name'];
    }
    if($row['corporation']){
    $row['corporation'] = corporation($row['corporation'])[0]['corporation_name'];
    }
    if($row['municipality']){
    $row['municipality'] = municipality($row['municipality'])[0]['municipality_name'];
    }
    if($row['town_panchayat']){
    $row['town_panchayat'] = town_panchayat($row['town_panchayat'])[0]['town_panchayat_name'];
    }

    if($row['building_status']){
    $row['building_status'] = building_status($row['building_status'])[0]['building_status'];
    }

    if($row['ownership']){
    $row['ownership'] = onership_status($row['ownership'])[0]['ownership'];
    }

            if($row['rental_reason']){
                $row['rental_reason'] = rental_reason($row['rental_reason'])[0]['rental_reason'];
                }

    if($row['hostel_location'] == '1'){
        $row['hostel_location'] = 'Rural';
    }elseif($row['hostel_location'] == '2'){
        $row['hostel_location'] = 'Urban';
    }else{
        $row['hostel_location'] = '';
    }

    if($row['urban_type'] == '1'){
        $row['urban_type'] = 'Corporation';
    }elseif($row['urban_type'] == '2'){
        $row['urban_type'] = 'Municipality';
    }elseif($row['urban_type'] == '3'){
        $row['urban_type'] = 'Town Panchayat';
    }else{
        $row['urban_type'] = '';
    }





    // Fill Excel cells
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, $row["hostel_name"] ?: '-')
        ->setCellValue('C' . $row_val, $row["hostel_id"] ?: '-')
        ->setCellValue('D' . $row_val, $row["district_name"] ?: '-')
        ->setCellValue('E' . $row_val, $row["taluk_name"] ?: '-')
        ->setCellValue('F' . $row_val, $row["special_tahsildar"] ?: '-')
        ->setCellValue('G' . $row_val, $row["assembly_const"] ?: '-')
        ->setCellValue('H' . $row_val, $row["parliment_const"] ?: '-')
        ->setCellValue('I' . $row_val, $row["address"] ?: '-')
        ->setCellValue('J' . $row_val, $row['hostel_location'] ?: '-')
        ->setCellValue('K' . $row_val, $row['block_name'] ?: '-')
        ->setCellValue('L' . $row_val, $row['village_name'] ?: '-')
        ->setCellValue('M' . $row_val, $row['urban_type'] ?: '-')
        ->setCellValue('N' . $row_val, $row['corporation'] ?: '-')
        ->setCellValue('O' . $row_val, $row['municipality'] ?: '-')
        ->setCellValue('P' . $row_val, $row["town_panchayat"] ?: '-')
        ->setCellValue('Q' . $row_val, $row['hostel_type'] ?: '-')
        ->setCellValue('R' . $row_val, $row['gender_type'] ?: '-')
        ->setCellValue('S' . $row_val, $row['yob'] ?: '-')
        ->setCellValue('T' . $row_val, $row['sanctioned_strength'] ?: '-')
        ->setCellValue('U' . $row_val, $row['distance_btw_phc'] ?: '-')
        ->setCellValue('V' . $row_val, $row['phc_name'] ?: '-')
        ->setCellValue('W' . $row_val, $row['distance_btw_ps'] ?: '-')
        ->setCellValue('X' . $row_val, $row['ps_name'] ?: '-')
        ->setCellValue('Y' . $row_val, $row['staff_count'] ?: '-')
        ->setCellValue('Z' . $row_val, $row['building_status'] ?: '-')
        ->setCellValue('AA' . $row_val, $row['ownership'] ?: '-')
        ->setCellValue('AB' . $row_val, $row['rental_reason'] ?: '-')
        ->setCellValue('AC' . $row_val, $row['hybrid_hostel'] ?: '-')
        ->setCellValue('AD' . $row_val, $row['hostel_upgrade'] ?: '-');


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
foreach (range('A', 'AD') as $columnID) {
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
