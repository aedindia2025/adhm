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
$excel->getActiveSheet()->mergeCells('A1:AE1');
$excel->getActiveSheet()->setCellValue('A1', 'Student Application Report');



// Header fields
$excel->getActiveSheet()
    ->setCellValue('A3', 'S.No')
    ->setCellValue('B3', 'Applied Date')
    ->setCellValue('C3', 'Student Name As Per Aadhaar')
    ->setCellValue('D3', 'Application Number')
    ->setCellValue('E3', 'Hostel Type')
    ->setCellValue('F3', 'Application Type')
    ->setCellValue('G3', 'Submit Status')
    ->setCellValue('H3', 'Student EMIS/UMIS ID')
    ->setCellValue('I3', 'Student Name As Per UMIS/EMIS')
    ->setCellValue('J3', 'Class/Course')
    ->setCellValue('K3', 'Group')
    ->setCellValue('L3', 'Medium')
    ->setCellValue('M3', 'Year Studying')
    ->setCellValue('N3', 'Gender')
    ->setCellValue('O3', 'Home Address')
    ->setCellValue('P3', 'Hostel District')
    ->setCellValue('Q3', 'Hostel Taluk')
    ->setCellValue('R3', 'Hostel Name')
    ->setCellValue('S3', 'Warden Approval Status')
    ->setCellValue('T3', 'Warden Approval Date')
    ->setCellValue('U3', 'DADWO Approval Status')
    ->setCellValue('V3', 'DADWO Approval Date')
    ->setCellValue('W3', 'Hostel ID')
    ->setCellValue('X3', 'School/College')
    ->setCellValue('Y3', 'Dropout Status')
    ->setCellValue('Z3', 'Dropout Request Date')
    ->setCellValue('AA3', 'DADWO Action Date')
    ->setCellValue('AB3', 'Reject Reason')
    ->setCellValue('AC3', 'Distance from Home to Institution')
    ->setCellValue('AD3', 'Distance from Home to Hostel')
    ->setCellValue('AE3', 'Name Difference');


$academic_year = $_GET['academic_year'];
if($academic_year){
    $where = " and academic_year = '".$academic_year."'";
}else{
    $where = "";

}

$i = 0;
$row_val = 4;

 $sql = ("SELECT 
    std_app_no,
    std_name,
    entry_date,
    application_type,
    student_type,
    gender,
    std_address,
    hostel_1,
    hostel_district_1, 
    hostel_taluk_1,
    submit_status,
    status,
    status_upd_date,
    emis_no,
    emis_name,
    umis_no,
    umis_name,
    no_umis_name,
    emis_class,
    umis_std_course,
    no_umis_stream,
    year_studying,
    hostel_id,
    school_name,
    umis_clg_name,
    no_umis_college,
    group_name,
    medium,
    batch_no,
    batch_cr_date,
    dropout_status,
    dropout_date,
    dropout_status_upd_date,
    dropout_reject_reason,
    std_to_inst_distance,
    std_to_hostel_distance
FROM std_app WHERE is_delete = 0 $where");

$users = $conn->query($sql);
foreach ($users as $row) {

    if($row['batch_no']){
        $warden_approval_status = 'Accepted';
    }else{
        $warden_approval_status = 'Pending';
    }

    // Determine Application Type
    $application_type = ($row['application_type'] == 1) ? 'New' : 'Renewal';

    // Determine Hostel Type
    switch ($row['student_type']) {
        case '65f00a259436412348': $hostel_type = 'School'; break;
        case '65f00a327c08582160': $hostel_type = 'ITI'; break;
        case '65f00a3e3c9a337012': $hostel_type = 'Diploma'; break;
        case '65f00a495599589293': $hostel_type = 'College-UG'; break;
        case '65f00a53eef3015995': $hostel_type = 'College-PG'; break;
        default: $hostel_type = '-'; // If no match, set to '-'
    }

    // Submit Status
    $submit_status = ($row['submit_status'] == '0') ? 'Partially Submitted' : (($row['submit_status'] == '1') ? 'Submitted' : '-');

    // EMIS/UMIS ID
    $emis_umis_no = !empty($row['emis_no']) ? $row['emis_no'] : (!empty($row['umis_no']) ? $row['umis_no'] : '-');

    // Student Name
    $emis_umis_name = !empty($row['emis_name']) ? $row['emis_name'] : (!empty($row['umis_name']) ? $row['umis_name'] : $row['no_umis_name']);

    // School/College 
    $emis_umis_clg = !empty($row['school_name']) ? $row['school_name'] : (!empty($row['umis_clg_name']) ? $row['umis_clg_name'] : $row['no_umis_college']);

if($row['no_umis_stream']){
if($row['no_umis_stream'] == '1'){
    $no_umis_stream = 'ITI';
}elseif($row['no_umis_stream'] == '2'){
    $no_umis_stream = 'Diploma';
}elseif($row['no_umis_stream'] == '3'){
    $no_umis_stream = 'UG';
}elseif($row['no_umis_stream'] == '4'){
    $no_umis_stream = 'PG';
}elseif($row['no_umis_stream'] == '5'){
    $no_umis_stream = 'PHD';
}
}
    // Class/Course
    $std_course = !empty($row['emis_class']) ? $row['emis_class'] : (!empty($row['umis_std_course']) ? $row['umis_std_course'] : $no_umis_stream);

    // DADWO Approval Status
    switch ($row['status']) {
        case 0: $status = 'Pending'; break;
        case 1: $status = 'Approved'; break;
        case 2: $status = 'Rejected'; break;
        default: $status = '-';
    }

    if($row['dropout_status'] != '' || $row['dropout_status'] != NULL){

    if($row['dropout_status'] == '0'){
        $dropout_status = 'Pending'; $row['dropout_reject_reason'] = '-';
    }elseif($row['dropout_status'] == '1'){
        $dropout_status = 'Approved'; $row['dropout_reject_reason'] = '-';
    }elseif($row['dropout_status'] == '2'){ 
        $dropout_status = 'Rejected';
    }else{
        $dropout_status = '-'; $row['dropout_date'] = '-'; $row['dropout_status_upd_date'] = '-'; $row['dropout_reject_reason'] = '-';
    }


    // switch ($row['dropout_status']) {
    //     case 0: $dropout_status = 'Pending'; $row['dropout_reject_reason'] = '-'; break;
    //     case 1: $dropout_status = 'Approved'; $row['dropout_reject_reason'] = '-'; break;
    //     case 2: $dropout_status = 'Rejected'; break;
    //     case null: $dropout_status = '-'; $row['dropout_date'] = '-'; $row['dropout_status_upd_date'] = '-'; $row['dropout_reject_reason'] = '-'; break;
    //     default: $dropout_status = '-'; $row['dropout_date'] = '-'; $row['dropout_status_upd_date'] = '-'; $row['dropout_reject_reason'] = '-';
    // }
}else{
    $dropout_status = '-'; $row['dropout_date'] = '-'; $row['dropout_status_upd_date'] = '-'; $row['dropout_reject_reason'] = '-';
}

 if ($row["std_name"] != '' && $emis_umis_name != '') {

        $jac_similarity = jaccardSimilarity($row['std_name'], $emis_umis_name);
        $score = floatval($jac_similarity);

        if ($score == 1) {
            $name_diff = 'Matched';
        } elseif ($score >= 0.6) {
            $name_diff = 'Partially Matched';
        } else {
            $name_diff = 'Mismatched';
        }
      

    }


    // Fill Excel cells
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, $row["entry_date"] ?: '-')
        ->setCellValue('C' . $row_val, $row["std_name"] ?: '-')
        ->setCellValue('D' . $row_val, $row["std_app_no"] ?: '-')
        ->setCellValue('E' . $row_val, $hostel_type ?: '-')
        ->setCellValue('F' . $row_val, $application_type)
        ->setCellValue('G' . $row_val, $submit_status)
        ->setCellValue('H' . $row_val, $emis_umis_no) 
        ->setCellValue('I' . $row_val, $emis_umis_name ?: '-')
        ->setCellValue('J' . $row_val, $std_course ?: '-')
        ->setCellValue('K' . $row_val, $row['group_name'] ?: '-')
        ->setCellValue('L' . $row_val, $row['medium'] ?: '-')
        ->setCellValue('M' . $row_val, $row['year_studying'] ?: '-')
        ->setCellValue('N' . $row_val, $row['gender'] ?: '-')
        ->setCellValue('O' . $row_val, $row['std_address'] ?: '-')
        ->setCellValue('P' . $row_val, $row['hostel_district_1'] ?: '-')
        ->setCellValue('Q' . $row_val, $row['hostel_taluk_1'] ?: '-')
        ->setCellValue('R' . $row_val, $row['hostel_1'] ?: '-')
        ->setCellValue('S' . $row_val, $warden_approval_status ?: '-')
        ->setCellValue('T' . $row_val, $row['batch_cr_date'] ?: '-')
        ->setCellValue('U' . $row_val, $status)
        ->setCellValue('V' . $row_val, $row['status_upd_date'] ?: '-')
        ->setCellValue('W' . $row_val, $row['hostel_id'] ?: '-')
        ->setCellValue('X' . $row_val, $emis_umis_clg ?: '-')
        ->setCellValue('Y' . $row_val, $dropout_status ?: '-')
        ->setCellValue('Z' . $row_val, $row['dropout_date'] ?: '-')
        ->setCellValue('AA' . $row_val, $row['dropout_status_upd_date'] ?: '-')
        ->setCellValue('AB' . $row_val, $row['dropout_reject_reason'] ?: '-')
        ->setCellValue('AC' . $row_val, isset($row['std_to_inst_distance']) && $row['std_to_inst_distance'] !== '' ? floor($row['std_to_inst_distance']) : '-')
        ->setCellValue('AD' . $row_val, isset($row['std_to_hostel_distance']) && $row['std_to_hostel_distance'] !== ''  ? floor($row['std_to_hostel_distance']) : '-')
        ->setCellValue('AE' . $row_val, $name_diff ?: '-');

    $row_val++;
}

// Styling
$styleArray = array(
    'font' => array(
        'bold' => true
    )
);
$excel->getActiveSheet()->getStyle('A1:AE1')->applyFromArray($styleArray);
$excel->getActiveSheet()->getStyle('A3:AE3')->applyFromArray($styleArray);

$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
foreach (range('A', 'AE') as $columnID) {
    $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Output the Excel file
//header('Content-Type: application/vnd.ms-excel');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Student_Application_Report.xlsx"');
header('Cache-Control: max-age=0');

$fileDownload = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
ob_clean();
$fileDownload->save('php://output');





function jaccardSimilarity($str1, $str2)
{
    $set1 = array_unique(str_split(mb_strtolower($str1)));
    $set2 = array_unique(str_split(mb_strtolower($str2)));

    $intersection = array_intersect($set1, $set2);
    $union = array_unique(array_merge($set1, $set2));

    $similarity = count($union) > 0 ? count($intersection) / count($union) : 0;

    return round($similarity, 1); // Round to 1 decimal place
}

?>