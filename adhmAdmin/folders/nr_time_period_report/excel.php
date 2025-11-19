<?php

require_once("../../assets/PHPExcel-1.8/Classes/PHPExcel.php");
require("../../config/common_fun.php");

$driver = "mysql";
$host = "localhost";
$username = "root";
$password = "H_Cw3O4CM*fXcGtz";
$databasename = "adi_dravidar";


try {

    $conn = new PDO($driver . ":host=" . $host . ";dbname=" . $databasename, $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}
$excel = new PHPExcel();

$excel->setActiveSheetIndex(0);


$excel->getActiveSheet()
    ->mergeCells('A1:F1');

$excel->getActiveSheet()
    ->setCellValue('A1', 'Student Application Report');



$excel->getActiveSheet()
    ->setCellValue('A3', 'S.No')
    ->setCellValue('B3', 'Applied Date')
    ->setCellValue('C3', 'Application Number')
    ->setCellValue('D3', 'Hostel Type')
    ->setCellValue('E3', 'Submit Status')
    ->setCellValue('F3', 'Student EMIS/UMIS ID')
    ->setCellValue('G3', 'Student Name As Per UMIS/EMIS')
    ->setCellValue('H3', 'Gender')
    ->setCellValue('I3', 'Home Address')
    ->setCellValue('J3', 'Hostel District')
    ->setCellValue('K3', 'Hostel Taluk')
    ->setCellValue('L3', 'Hostel Name')
    ->setCellValue('M3', 'Warden Status')
    ->setCellValue('N3', 'Physically Submission Status')
    ->setCellValue('O3', 'DADWO Received Status')
    ->setCellValue('P3', 'DADWO Approval Status')
    ->setCellValue('Q3', 'DADWO Approval Date');

$i = 0;
$row_val = 4;

$sql = ("SELECT *  from view_application_report");

$users = $conn->query($sql);
foreach ($users as $row) {

    $studentName = '-';
    if (!empty($row["std_name"])) {
        $studentName = $row["std_name"];
        $studentNo = $row["emis_no"];
    } elseif (!empty($row["umis_name"])) {
        $studentName = $row["umis_name"];
        $studentNo = $row["umis_no"];
    } elseif (!empty($row["no_umis_name"])) {
        $studentName = $row["no_umis_name"];
        $studentNo = 'No UMIS';
    }

    $student_type_un = $conn->prepare("SELECT hostel_type FROM hostel_type WHERE unique_id = '" . $row['student_type'] . "'");
    $student_type_un->execute();
    $student_type = $student_type_un->fetch();

    $gender_type_un = $conn->prepare("SELECT gender_type FROM hostel_gender_type WHERE unique_id = '" . $row['gender'] . "'");
    $gender_type_un->execute();
    $gender_type = $gender_type_un->fetch();

    switch ($row['status']) {
        case 0:
            $row['status'] = 'Pending';
            break;
        case 1:
            $row['status'] = 'Approved';
            break;
        case 2:
            $row['status'] = 'Rejected';
            break;
    }

    switch ($row['submit_status']) {
        case 0:
            $row['submit_status'] = 'Partially Submitted';
            break;
        case 1:
            $row['submit_status'] = 'Submitted';
            break;
       
    }
    $row['status_upd_date'] = $row['status_upd_date'] ?: '-';

    $warden_status = empty($row['batch_no']) ? 'Not Dispatched' : 'Dispatched';

    switch ($row['print_status']) {
        case 0:
            $row['print_status'] = 'Pending';
            break;
        case 1:
            $row['print_status'] = 'Printed';
            break;
        case 2:
            $row['print_status'] = 'Submitted';
            break;
    }

    $row['rec_status'] = $row['rec_status'] == '1' ? 'Received' : 'Pending';
  
    $excel->getActiveSheet()
        ->setCellValue('A' . $row_val, ++$i)
        ->setCellValue('B' . $row_val, ($row["entry_date"]))
        ->setCellValue('C' . $row_val, ($row["std_app_no"]))
        ->setCellValue('D' . $row_val, ($student_type['hostel_type']))
        ->setCellValue('E' . $row_val, ($row['submit_status']))
        ->setCellValue('F' . $row_val, ($studentNo))
        ->setCellValue('G' . $row_val, ($studentName))
        ->setCellValue('H' . $row_val, ($row["gender"]))
        ->setCellValue('I' . $row_val, ($row["address"]))
        ->setCellValue('J' . $row_val, ($row["district_name"]))
        ->setCellValue('K' . $row_val, ($row["taluk_name"]))
        ->setCellValue('L' . $row_val, ($row["hostel_name"]))
        ->setCellValue('M' . $row_val, ($warden_status))
        ->setCellValue('N' . $row_val, ($row['print_status']))
        ->setCellValue('O' . $row_val, ($row["rec_status"]))
        ->setCellValue('P' . $row_val, ($row["status"]))
        ->setCellValue('Q' . $row_val, ($row["status_upd_date"]));
    

    $row_val++;
}

//for Styling

$styleArray = array(

    'font' => array(

        'bold' => true
    )
);
// $excel->mergeCells('A1:E1');
$excel->getActiveSheet()->getStyle('A1:Z1')->applyFromArray($styleArray);
$excel->getActiveSheet()->getStyle('A3:Z3')->applyFromArray($styleArray);


$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
$excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);




header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Student Application Report.xls"');
header('Cache-Control: max-age=0');

$fileDownload = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
ob_clean();
$fileDownload->save('php://output');
