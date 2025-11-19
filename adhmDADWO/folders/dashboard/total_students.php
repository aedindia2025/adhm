<?php
session_start();

// Step 1: Check Authentication Status
if (!isset($_SESSION['user_id'])) {
    // Redirect unauthorized users to the login page
    header("Location: login.php");
    exit;
}
?>

<style>
    body {

        font-family: 'Poppins', sans-serif;
    }

    body {
        background-color: #fff;
    }

    .zone_recom {
        border: 1px solid #ccc;
        padding: 14px;
        margin-bottom: 30px;
    }

    .box1 h3 {
        background-color: #f0f0f0;
        padding: 4px;
        text-align: center;
        font-weight: 700;
        color: #333;
        font-size: 14px;
    }

    .bd-highlight {
        font-size: 14px;
        color: #444;
    }

    .contn_info.d-flex h6 {
        text-align: right;
        font-size: 11.5px;
        margin-bottom: 4px;
    }

    .contn_info.d-flex h5 {
        color: #000;
        font-size: 11.5px;
        margin-bottom: 4px;
    }

    .contn_info.d-flex p {
        margin-bottom: 4px;
    }

    .zone_boxbor {

        margin-bottom: 20px;

    }

    .zone_recom1,
    .zone_recom3 {
        /* border: 1px solid #ccc; */
        padding: 4px;
    }

    .zone_recom2 {
        /* border: 1px solid #ccc; */
        padding: 4px;
    }

    .col-md-4 {
        width: 33.33333333%;
        padding-left: 5px;
        padding-right: 5px;
    }

    .col-md-8 {
        flex: 0 0 auto;
        width: 66.66666667%;
    }

    .col-md-4.wid1 {
        width: 45%;
    }

    .col-md-8.wid2 {
        width: 55%;
    }

    table,
    th,
    td {
        border: 1px solid #ccc;
        border-collapse: collapse;

    }

    th,
    td {
        padding: 5px;
        text-align: left;

    }

    .print_icon {
        text-align: right;
        font-size: 33px;
    }
</style>

<?php

include '../../config/dbconfig.php';

$district_name = $_SESSION['district_id'];
?>

<div class="container-fluid" style="background-color:#fff;">
    <div class="compl_print pt-2">
        <div class="zone_boxbor">
            <div class="row">
                <div class="col-md-12">
                    <div class="clearfix">
                        <center>
                            <div class=" mb-3 mt-1 text-center vendorListHeading2">
                                <img src="../../../assets/images/ad-logo.png" alt="dark logo" height="50">
                            </div>
                        </center>

                        <div class="col-sm-12 ">
                            <div class="mt-0 float-sm-left">
                                <div class="row">
                                    <center>
                                        <h3>REGISTERED STUDENTS</h3>
                                    </center>
                                </div>
                            </div><!-- end col -->
                        </div>
                        <div class="zone_recom3">
                            <div class="box1">
                                <table cellspacing="0" cellpadding="0" class="" width="100%"
                                    style="font-family: monospace;">
                                    <thead class="colspanHead">
                                        <tr>
                                            <th width="5%" colspan="1" class="blankCell">S.No</th>
                                            <th width="10%" colspan="1" class="blankCell">Student Name</th>
                                            <th width="10%" colspan="1" class="blankCell">Student Reg No</th>
                                            <th width="10%" colspan="1" class="blankCell">Hostel Name</th>
                                            <th width="10%" colspan="1" class="blankCell">District Name</th>
                                            <th width="10%" colspan="1" class="blankCell">Taluk Name</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $start = 0;
                                    $table = "std_reg_s a";

                                    $taluk_name = $_GET['taluk_name'] ?? '';
                                    $hostel_name = $_GET['hostel_name'] ?? '';
                                    $gender_type = $_GET['gender_type'] ?? '';
                                    $hostel_type = $_GET['hostel_type'] ?? '';

                                    // echo "Hi ".$taluk_name;

                                    $columns = [
                                        "@a:=@a+1 AS s_no",
                                        "a.std_name",
                                        "a.std_reg_no",
                                        "(SELECT hostel_name FROM hostel_name WHERE unique_id = a.hostel_1) AS hostel_1",
                                        "(SELECT district_name FROM district_name WHERE unique_id = a.hostel_district_1) AS hostel_district_1",
                                        "(SELECT taluk_name FROM taluk_creation WHERE unique_id = a.hostel_taluk_1) AS hostel_taluk_1"
                                    ];

                                    $table_details = [$table . ", (SELECT @a:=$start) AS a", $columns];

                                    $where = "a.is_delete = 0 AND a.dropout_status = '1' AND a.hostel_district_1 = '$district_name'";

                                    if ($taluk_name != '')
                                        $where .= " AND a.hostel_taluk_1 = '$taluk_name'";
                                    if ($hostel_name != '')
                                        $where .= " AND a.hostel_1 = '$hostel_name'";

                                    // Build EXISTS subquery for hostel_name filtering
                                    $exists_conditions = "b.unique_id = a.hostel_1 AND b.is_delete = 0";

                                    if (!empty($gender_type)) {
                                        if (is_array($gender_type)) {
                                            $gender_type = array_filter($gender_type, fn($val) => $val !== '');
                                            if (!empty($gender_type)) {
                                                $gender_str = "'" . implode("','", $gender_type) . "'";
                                                $exists_conditions .= " AND b.gender_type IN ($gender_str)";
                                            }
                                        } else {
                                            $exists_conditions .= " AND b.gender_type = '$gender_type'";
                                        }
                                    }

                                    if (!empty($hostel_type) && is_array($hostel_type)) {
                                        $hostel_type = array_filter($hostel_type, fn($val) => $val !== '');
                                        if (!empty($hostel_type)) {
                                            $hostel_type_str = "'" . implode("','", $hostel_type) . "'";
                                            $exists_conditions .= " AND b.hostel_type IN ($hostel_type_str)";
                                        }
                                    }

                                    $where .= " AND EXISTS (
                                        SELECT 1
                                        FROM hostel_name b
                                        WHERE $exists_conditions
                                    )";

                                    $result = $pdo->select($table_details, $where);
                                    $res_array = $result->data;

                                    $table_data = "";
                                    if (count($res_array) == 0) {
                                        $table_data .= "<tr><td colspan='6' style='text-align:center'>NO DATA FOUND</td></tr>";
                                    } else {
                                        foreach ($res_array as $value) {
                                            $table_data .= "<tr>";
                                            $table_data .= "<td>" . $value['s_no'] . "</td>";
                                            $table_data .= "<td style='text-align:left'>" . $value['std_name'] . "</td>";
                                            $table_data .= "<td style='text-align:left'>" . $value['std_reg_no'] . "</td>";
                                            $table_data .= "<td style='text-align:left'>" . $value['hostel_1'] . "</td>";
                                            $table_data .= "<td style='text-align:left'>" . $value['hostel_district_1'] . "</td>";
                                            $table_data .= "<td style='text-align:left'>" . $value['hostel_taluk_1'] . "</td>";
                                            $table_data .= "</tr>";
                                        }
                                    }
                                    ?>

                                    <tbody>
                                        <?php echo $table_data; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>