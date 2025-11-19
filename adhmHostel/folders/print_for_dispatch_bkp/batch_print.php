<?php
$academic_year_options = academic_year();
$academic_year_options = select_option($academic_year_options, "Select Academic Year");
$batch_no = $_GET['unique_id'];
if ($_GET["unique_id"]) {
    // echo $_GET["unique_id"];
    // $unique_id = $_GET["unique_id"];

    $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
    $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

    $unique_id  = $get_uni_id; 

    $batch_no = $get_uni_id;
    
    $where = [
        "batch_no" => $unique_id
    ];

    $table = "batch_creation";

    $columns = [
        "(select hostel_name from hostel_name where hostel_name.unique_id = batch_creation.hostel_name) as hostel_name",
        "(select taluk_name from taluk_creation where taluk_creation.unique_id = batch_creation.hostel_taluk) as hostel_taluk",
        "(select district_name from district_name where  district_name.unique_id = batch_creation.hostel_district) as hostel_district",
        "(select amc_year from academic_year_creation where academic_year_creation.unique_id = batch_creation.academic_year) as academic_year",
        // "acc_year as academic_year",
        "batch_cr_date",
        "count(id) as count"
        //  
    ];

    $table_details = [
        $table,
        $columns
    ];

    $result_values = $pdo->select($table_details, $where);
    // print_r($result_values);

    if ($result_values->status) {

        $result_values = $result_values->data;

        $hostel_name = $result_values[0]["hostel_name"];
        $hostel_taluk = $result_values[0]["hostel_taluk"];
        $hostel_district = $result_values[0]["hostel_district"];
        $academic_year = $result_values[0]["academic_year"];
        $batch_cr_date = $result_values[0]["batch_cr_date"];
        $count = $result_values[0]["count"];
        // $is_active = $result_values[0]["is_active"];




    }

}

?>
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Print For Dispatch</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="was-validated">
                                <div class="row mb-3">
                                    <div class="container-fluid" style="background-color:#fff;">
                                        <div class="compl_print pt-2">
                                            <div class="zone_boxbor">
                                                <div class="row">

                                                    <div class="col-md-12">


                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                Academic Year : &nbsp<b><?php echo $academic_year; ?></b>
                                                            </div><br>
                                                            <div class="col-md-4">
                                                                Hostel District :
                                                                &nbsp<b><?php echo strtoupper($hostel_district); ?></b>
                                                            </div><br>
                                                            <div class="col-md-4">
                                                                Hostel Taluk :
                                                                &nbsp<b><?php echo strtoupper($hostel_taluk); ?></b>
                                                            </div><br><br>
                                                            <div class="col-md-4">
                                                                Hostel Name :
                                                                &nbsp<b><?php echo strtoupper($hostel_name); ?></b>
                                                            </div><br>
                                                            <div class="col-md-4">
                                                                Batch Created Date :
                                                                &nbsp<b><?php echo $batch_cr_date; ?></b>
                                                            </div><br>
                                                            <div class="col-md-4">
                                                                Total Count Of Application :
                                                                &nbsp<b><?php echo $count; ?></b>
                                                            </div><br>


                                                        </div>
                                                        <!-- <div class="col-md-3">
                                        <label for="academic_year" class="form-label">Academic Year</label>
                                        <select name="academic_year" id="academic_year" class="select2 form-control" required>
                                            <?php echo $academic_year_options; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary mt-3 btn-rounded mr-2" onclick="filter_records();">Filter</button>
                                    </div>
                                    <div class="col-md-3">
                                       <a href="index.php?file=print_for_dispatch/print"><button type="button" class="btn btn-primary mt-3 btn-rounded mr-2" style="float: right;">Print For Dispatch</button></a>
                                    </div> -->
                                                        <input type="hidden" id="batch_no" value="<?= $batch_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="">
                                            <div class="">
                                                <table id="batch_detail_datatable"
                                                    class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>S.no</th>

                                                            <th>Batch No</th>
                                                            <th>Application No</th>
                                                            <th>Applicant Name</th>
							    <th>EMIS/UMIS Name</th>
                                                            <th>Status</th>
                                                            <!-- <th>Rejected Count</th> -->
                                                            <th>Print</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="folders/print_for_dispatch/print_for_dispatch.js"></script> -->