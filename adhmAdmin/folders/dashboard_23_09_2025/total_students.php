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





?>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->

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


                                    <div class="col-sm-12">

                                    </div>


                                </div>
                            </div><!-- end col -->
                        </div>
                        <div class="zone_recom3">
                            <div class="box1">
                                <table cellspacing="0" cellpadding="0" class="" width="100%" style="font-family: monospace;">
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
                                    // $table_main = "std_app_p1";
                                    $table = "std_reg_s";
                                    // $table1= "taluk_creation";
                                    // $district_name = $_SESSION["district_id"]; 

                                    // $today  =  date('Y-m-d');


                                    $columns_list    = [
                                        "@a:=@a+1 s_no",
                                        "(select std_name from std_app_s2 where std_app_s2.s1_unique_id = $table.unique_id) as std_name",
                                        "std_reg_no",
                                        "(select hostel_name from hostel_name where unique_id = $table.hostel_1)as hostel_name",

                                        "(select district_name from district_name where unique_id = $table.hostel_district_1)as district_name",

                                        "(select taluk_name from taluk_creation where unique_id = $table.hostel_taluk_1)as taluk_name"
                                    ];

                                    $where_list = "is_delete = 0 AND dropout_status = '1' ";

                                    $table_details_list  = [
                                        $table . ", (SELECT @a:= " . $start . ") AS a ",
                                        $columns_list
                                    ];



                                    $result    = $pdo->select($table_details_list, $where_list);

                                    // print_r($result);
                                    if ($result->status) {

                                        $res_array      = $result->data;

                                        $table_data     = "";
                                        if (count($res_array) == 0) {
                                            $table_data .= "<tr>";

                                            $table_data .= "<td colspan=9; style='text-align:center'>NO DATA FOUND</td>";
                                            $table_data .= "</tr>";
                                        } else {
                                            foreach ($res_array as $key => $value) {

                                                $student_name = $value['std_name'];

                                                $student_id  = $value['std_reg_no'];

                                                $hostel_name  = $value['hostel_name'];

                                                $district_name  = $value['district_name'];

                                                $taluk_name  = $value['taluk_name'];



                                                // $value['entry_date']        = disdate($value['entry_date']);

                                                $table_data .= "<tr>";

                                                $table_data .= "<td>" . $value['s_no'] . "</td>";
                                                // $table_data .= "<td style = 'text-align : left'>" . $value['entry_date'] . "</td>";
                                                $table_data .= "<td style = 'text-align : left'>" . $value['std_name'] . "</td>";
                                                $table_data .= "<td style = 'text-align : left'>" . $value['std_reg_no'] . "</td>";
                                                $table_data .= "<td style = 'text-align : left'>" . $value['hostel_name'] . "</td>";
                                                $table_data .= "<td style = 'text-align : left'>" . $district_name .    "</td>";
                                                $table_data .= "<td style = 'text-align : left'>" . $value['taluk_name'] . "</td>";
                                                $table_data .= "</tr>";
                                            }
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