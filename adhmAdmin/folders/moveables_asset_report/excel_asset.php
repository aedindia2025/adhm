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
$excel->getActiveSheet()->mergeCells('A1:E1');
$excel->getActiveSheet()->setCellValue('A1', 'ADW Moveables Report');

// Header fields
$excel->getActiveSheet()
    ->setCellValue('A3', 'S.No')
    ->setCellValue('B3', 'Category')
    ->setCellValue('C3', 'Asset Type')
    ->setCellValue('D3', 'Asset Name')
    ->setCellValue('E3', 'Quantity');
    
   
    


$i = 0;
$row_val = 4;

if($_GET['list_type']){
    $where .= " AND k_d_category = ".$_GET['list_type']; 
}
if($_GET['list_category'] && $_GET['list_category'] != NULL){
    $where .= " AND category = '".$_GET['list_category']."'"; 
}

$sql = "SELECT k_d_category,category,asset,'' as tot_quantity FROM view_moveables_asset WHERE  hostel_id != '' $where";

$users = $conn->query($sql);
foreach ($users as $row) {
    
    
    $row['tot_quantity'] = asset_quantity($row['asset']);
    
    if($row['k_d_category'] == '1'){
        $row['k_d_category'] = 'Kitchen';
        $row['category'] = kitchen_category($row['category'])[0]['category'];
        $row['asset'] = kitchen_asset($row['asset'])[0]['kitchen_asset'];

    }elseif($row['k_d_category'] == '2'){
        $row['k_d_category'] = 'Digital';
       
        $row['category'] = digital_category($row['category'])[0]['digital_category'];
        $row['asset'] = digital_asset($row['asset'])[0]['digital_asset'];
    }

 

    // Fill Excel cells
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, $row["k_d_category"] ?: '-')
        ->setCellValue('C' . $row_val, $row['category'] ?: '-')
        ->setCellValue('D' . $row_val, $row["asset"] ?: '-')
        ->setCellValue('E' . $row_val, $row["tot_quantity"] ?: '-');
       
        
       


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
foreach (range('A', 'E') as $columnID) {
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
<?php

function asset_quantity($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "view_moveables_asset";
    $where = "";
    $table_columns = [
        "sum(quantity) as tot_quantity",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    

    // if ($unique_id) {
        
    //     $where["asset"] = $unique_id;
    // }
    $where = "asset = '".$unique_id."'";
    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        
        return $amc_name_list->data[0]['tot_quantity'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}

?>
