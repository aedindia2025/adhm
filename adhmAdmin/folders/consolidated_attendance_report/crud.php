<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "hostel_name";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$user_type = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose

switch ($action) {


    case 'datatable':



        // Get month-year from POST request
        $month_year = explode('-', $_POST['month_year']);
        $year = $month_year[0]; // Year (YYYY)
        $month = $month_year[1]; // Month (MM)
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Get pagination parameters from DataTable request
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10; // Default: 10 per page
        $district_name = $_POST['district_name'];
        $taluk_name = $_POST['taluk_name'];
        if ($district_name) {
            $where_fil = " and district_name = '" . $district_name . "'";
        }

        if ($taluk_name) {
            $where_fil .= " and taluk_name = '" . $taluk_name . "'";
        }

        // 1. Get Total Count of Hostels for Pagination
        $countQuery = "SELECT COUNT(hostel_id) AS total FROM hostel_name WHERE is_delete = 0 AND dev_reg = 1 $where_fil";
        $result = $mysqli->query($countQuery);
        $row = $result->fetch_assoc();
        $totalRecords = $row['total'];
        $result->free();

        $limit_query = $length == -1 ? "" : "LIMIT $start, $length";

        // 2. Fetch Paginated Hostel Data
        $hostelQuery = "SELECT SQL_CALC_FOUND_ROWS
        (SELECT district_name FROM district_name WHERE district_name.unique_id = hostel_name.district_name) AS district_name,
        (SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = hostel_name.taluk_name) AS taluk_name,
        hostel_id, 
        hostel_name,
        (SELECT COUNT(id) FROM std_reg_s WHERE std_reg_s.hostel_1 = hostel_name.unique_id) AS dadwo_app_cnt,
        (SELECT COUNT(id) FROM std_reg_s WHERE bio_reg_status = 1 AND hostel_name.unique_id = std_reg_s.hostel_1) AS bio_reg_cnt,
        (SELECT COUNT(id) FROM std_reg_s WHERE face_id_status = 1 AND hostel_name.unique_id = std_reg_s.hostel_1) AS face_id_status,
        (SELECT COUNT(id) FROM std_reg_s WHERE fingerprint_status = 1 AND hostel_name.unique_id = std_reg_s.hostel_1) AS fingerprint_status 
    FROM hostel_name 
    WHERE is_delete = 0 AND dev_reg = 1 $where_fil
    ORDER BY hostel_id $limit_query";

        $result = $mysqli->query($hostelQuery);
        $hostels = [];

        while ($row = $result->fetch_assoc()) {
            $hostels[] = [
                "hostel_id" => $row['hostel_id'],
                "hostel_name" => $row['hostel_name'],
                "district_name" => $row['district_name'],
                "taluk_name" => $row['taluk_name'],
                "dadwo_app_cnt" => $row['dadwo_app_cnt'],
                "bio_reg_cnt" => $row['bio_reg_cnt'],
                "face_id_status" => $row['face_id_status'],
                "fingerprint_status" => $row['fingerprint_status']
            ];
        }
        $result->free();

        // Convert hostel IDs into a comma-separated string for query
        $hostelIdsString = implode(',', array_map(function ($hostel) {
            return '"' . $hostel['hostel_id'] . '"'; // Wrap each ID in double quotes
        }, $hostels));

        // 3. Fetch Attendance Data Only for These Hostels
        $attendanceQuery = "
        SELECT 
            hostel_id,
            DAY(report_date) AS day,
            morning_punch_count AS morning_count,
            eve_punch_count AS evening_count
        FROM attendance_report
        WHERE report_date BETWEEN '$year-$month-01' AND '$year-$month-$daysInMonth'
        AND hostel_id IN ($hostelIdsString)
        GROUP BY hostel_id, DAY(report_date)
    ";

        $result = $mysqli->query($attendanceQuery);
        $attendanceData = [];

        while ($row = $result->fetch_assoc()) {
            $hostelID = $row['hostel_id'];
            $day = $row['day'];
            $attendanceData[$hostelID][$day] = [
                'morning' => $row['morning_count'],
                'evening' => $row['evening_count']
            ];
        }
        $result->free();

        // 4. Construct Data for DataTable
        $data = [];
        $sno = $start + 1;

        foreach ($hostels as $hostel) {
            $row = [];
            $row[] = $sno++; // Serial No
            $row[] = $hostel["district_name"]; // District Name
            $row[] = $hostel["taluk_name"]; // Taluk Name
            $row[] = $hostel["hostel_id"] . "<br><span style='font-size:12px; color:gray;'>" . $hostel["hostel_name"] . "</span>";
            $row[] = $hostel["dadwo_app_cnt"]; // Dadwo Approved Count
            $row[] = $hostel["bio_reg_cnt"]; // Biometric Registered Count
            $row[] = $hostel["face_id_status"]; // Face Enrolled Count
            $row[] = $hostel["fingerprint_status"]; // Fingerprint Enrolled Count

            $totalMorning = 0;
            $totalEvening = 0;

            // Loop through each day in the month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                if ($day < 10) {
                    $con_day = '0' . $day;
                } else {
                    $con_day = $day;
                }
                $formatted_date = $year . '-' . $month . '-' . $con_day;

                $mrg_count = isset($attendanceData[$hostel["hostel_id"]][$day]) ? $attendanceData[$hostel["hostel_id"]][$day]['morning'] : 0;
                $eve_count = isset($attendanceData[$hostel["hostel_id"]][$day]) ? $attendanceData[$hostel["hostel_id"]][$day]['evening'] : 0;

                $morning = isset($attendanceData[$hostel["hostel_id"]][$day]) ?
                    '<span class="print-trigger" onclick="openPrintWindow(\'' . $hostel["hostel_id"] . '\', \'' . $formatted_date . '\', \'morning\',' . $mrg_count . ')">' .
                    $attendanceData[$hostel["hostel_id"]][$day]['morning'] . '</span>' : '<span class="print-trigger" onclick="openPrintWindow(\'' . $hostel["hostel_id"] . '\', \'' . $formatted_date . '\', \'morning\',' . $mrg_count . ')">0</span>';

                $evening = isset($attendanceData[$hostel["hostel_id"]][$day]) ?
                    '<span class="print-trigger" onclick="openPrintWindow(\'' . $hostel["hostel_id"] . '\', \'' . $formatted_date . '\', \'evening\',' . $eve_count . ')">' .
                    $attendanceData[$hostel["hostel_id"]][$day]['evening'] . '</span>' : '<span class="print-trigger" onclick="openPrintWindow(\'' . $hostel["hostel_id"] . '\', \'' . $formatted_date . '\', \'evening\',' . $eve_count . ')">0</span>';



                $totalMorning += $mrg_count;
                $totalEvening += $eve_count;
                // Store morning and evening counts as separate columns per day
                $row[] = $morning;
                $row[] = $evening;
            }

            // Append Total Column
            $row[] = $totalMorning;
            $row[] = $totalEvening;

            $data[] = $row;
        }

        // 5. Return JSON Response with Pagination
        $response = [
            "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data
        ];

        echo json_encode($response);
        break;

    case 'get_hostel_by_taluk_name':

        $taluk_name = $_POST['taluk_name'];


        $hostel_name_options = hostel_name('', $taluk_name);

        $hostel_name_options = select_option_host($hostel_name_options, "Select Hostel");

        echo $hostel_name_options;

        break;

    case 'district_name':

        $district_name = $_POST['district_name'];


        $district_name_options = taluk_name('', $district_name);

        $taluk_name_options = select_option($district_name_options, "Select Taluk");

        echo $taluk_name_options;

        break;

    case 'get_hostel_details':


        $district_name = $_POST['district_name'];

        $taluk_name = $_POST['taluk_name'];


        if ($district_name) {
            $sub_where .= " AND hostel_district_1 = '$district_name' ";
        }

        if ($_POST['taluk_name']) {
            $sub_where .= " AND hostel_taluk_1 = '$taluk_name' ";
        }


        // Query Variables
        $json_array = "";
        $columns = [
            "count(id) as tot_hostel",
            "(select count(id) from std_reg_s where bio_reg_status = 1 $sub_where) as bio_reg_cnt",


        ];
        $table_details = [
            $table,
            $columns
        ];

        $where = "is_delete = 0 and dev_reg = 1";

        if ($district_name) {
            $where .= " AND district_name = '$district_name' ";
        }

        if ($_POST['taluk_name']) {
            $where .= " AND taluk_name = '$taluk_name' ";
        }


        // if($status != ''){

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);

        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {


                $tot_hostel = $value['tot_hostel'];
                $bio_reg_cnt = $value['bio_reg_cnt'];
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "tot_hostel" => $tot_hostel,
                "bio_reg_cnt" => $bio_reg_cnt,

                "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;

    case 'get_att_details':


        $district_name = $_POST['district_name'];

        $taluk_name = $_POST['taluk_name'];
        $month_year = $_POST['month_year'];

        // Query Variables
        $json_array = "";
        $columns = [
            "sum(morning_punch_count) as mrg_cnt",
            "sum(eve_punch_count) as eve_cnt",



        ];
        $table_details = [
            "attendance_report",
            $columns
        ];
        $where = "date_format(report_date,'%Y-%m') = '" . $month_year . "'";

        if ($district_name) {
            $where .= " AND district_id = '$district_name' ";
        }

        if ($_POST['taluk_name']) {
            $where .= " AND taluk_name = '$taluk_name' ";
        }

        // if($status != ''){

        $sql_function = "SQL_CALC_FOUND_ROWS";

        $result = $pdo->select($table_details, $where, $limit, $start, $order_by, $sql_function);

        $total_records = total_records();

        if ($result->status) {

            $res_array = $result->data;

            foreach ($res_array as $key => $value) {


                $mrg_cnt = $value['mrg_cnt'];
                $eve_cnt = $value['eve_cnt'];
                $tot_punch_cnt = $mrg_cnt + $eve_cnt;
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "mrg_cnt" => $mrg_cnt,
                "eve_cnt" => $eve_cnt,
                "tot_punch_cnt" => $tot_punch_cnt,

                "testing" => $result->sql
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);
        break;
}
