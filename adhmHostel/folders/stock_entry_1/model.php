<!-- Modal with form -->
<?php

?>
<style>
    #stock_id {
        border: none;
        font-weight: bold;
    }
</style>


<!-- <meta http-equiv="refresh" content="<?php echo $sec ?>;URL='<?php echo $page ?>'"> -->
<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}
// Form variables
$btn_text = "Save";
$btn_action = "create";
$prefix = "";
$entry_date = date('Y-m-d');

$unique_id = "";
$district_name = "";
$is_active = 1;
$screen_unique_id = unique_id($prefix);
if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        // $main_unique_id = $_GET["unique_id"];

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        // $where      = [
        //     "unique_id" => $unique_id
        // ];
        $where = "unique_id = '$unique_id'";
        $table_main = "stock_entry";

        $columns = [
            // "entry_date",
            // "grievance_id",
            // "grievance_cate",
            // "grievance_description",
            // "student_name",
            // "reg_no",
            // "hostel_name as hostel_name_id",
            // "(select hostel_name from hostel_name where unique_id = $table.hostel_name) as hostel_name",
            // // "(select hostel_id from hostel_name where unique_id = $table.hostel_id) as hostel_id",
            // "hostel_id as hostel_id",
            // "hostel_id as hostel_id_val",
            // "grievance_no",
            // "district as district_id_val",
            // "(select district_name from district_name where unique_id = $table.district) as district",
            // "taluk as taluk_id_val",
            // "(select taluk_name from taluk_creation where unique_id = $table.taluk) as taluk",
            // "tahsildar",
            // "file_name",
            // "is_active",
            // "unique_id"
            "' ' as sno",
            "entry_date",
            "stock_id",
            // "(select supplier_name from supplier_name_creation where unique_id = $table.supplier_name) as supplier_name",
            "supplier_name",
            "address",
            // "(select hostel_name from hostel_name where unique_id = $table.hostel_name) as hostel_name",
            // "(select user_name from user where unique_id = $table.user_id) as user_id", 
            "bill_no",
            "hostel_name",
            "district",
            "discount",
            "expense",
            "gst",
            "net_total_amount",
            "taluk",
            "file_name",
            // "file_org_name",
            // "unit",
            "unique_id",
            "screen_unique_id",


        ];

        $table_details = [
            $table_main,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);
        if ($result_values->status) {

            $result_values = $result_values->data;


            $entry_date = $result_values[0]["entry_date"];
            $stock_ids = $result_values[0]["stock_id"];
            $supplier_name = $result_values[0]["supplier_name"];
            $address = $result_values[0]["address"];
            $bill_no = $result_values[0]["bill_no"];
            $reg_no = $result_values[0]["reg_no"];
            $discount = $result_values[0]["discount"];
            $expense = $result_values[0]["expense"];
            $gst = $result_values[0]["gst"];
            $net_total_amount = $result_values[0]["net_total_amount"];
            // $hostel_name_id      = $result_values[0]["hostel_name_id"];
            $hostel_name = $result_values[0]["hostel_name"];
            // $hostel_id_val      = $result_values[0]["hostel_id_val"];
            // $hostel_id      = $result_values[0]["hostel_id"];
            //  $grievance_no      = $result_values[0]["grievance_no"];
            // $district_id_val      = $result_values[0]["district_id_val"];
            $district = $result_values[0]["district"];

            $file_names = $result_values[0]["file_name"];
            // $taluk_id_val      = $result_values[0]["taluk_id_val"];
            $taluk = $result_values[0]["taluk"];
            // $tahsildar      = $result_values[0]["tahsildar"];
            // $file_name      = $result_values[0]["file_name"];
            // $is_active          = $result_values[0]["is_active"];
            $main_unique_id = $result_values[0]["unique_id"];
            $screen_unique_id = $result_values[0]["screen_unique_id"];

            // if($result_values[0]["stock_id"] == ''){
            //    $stock_ids= stock_id();
            // }else{
            //    $stock_ids= $result_values[0]["stock_id"];
            // }

            if ($result_values[0]["entry_date"] == '') {
                $entry_date = date('Y-m-d');
            } else {
                $entry_date = $result_values[0]["entry_date"];
            }

            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$district_unique_id = $_SESSION["district_id"];
$taluk_unique_id = $_SESSION['taluk_id'];
$hostel_unique_id = $_SESSION['hostel_id'];

$academic_year_options = academic_year();
$academic_year_options = select_option_acc($academic_year_options, "Select Academic Year");
$district_name_options = district_name();
$district_name_options = select_option($district_name_options, "Select District Name", $district_unique_id);
$taluk_options = taluk_name_get();
$taluk_name_options = select_option($taluk_options, "Select Zone", $taluk_unique_id);
$hostel_options = hostel_name_get();
$hostel_name_options = select_option($hostel_options, "Select Hostel", $hostel_unique_id);
$supplier_name_options = supplier_name_creation();
$supplier_name_options = select_option($supplier_name_options, "Select supplier Name", $supplier_name);
$product_type_options = product_type_name();
$product_type_option = select_option($product_type_options, "Select Item Name", $item_name);

$unit_options = unit_measurement();
$unit_options = select_option($unit_options, "Select Item Name", $unit);
// print_r($taluk_name_option);

function stock_id()
{
    $date = date("Y");
    $st_date = substr($date, 2);
    $month = date("m");
    $datee = $st_date . $month;

    $servername = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $database_name = "adi_dravidar";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
    } catch (PDOException $e) {
        // echo "Connection failed: " . $e->getMessage();
    }
    $acc_year = $date;
    $a = str_split($acc_year);
    $splt_acc_yr = $a[2] . $a[3];

    $stmt = $conn->query("SELECT * FROM stock_entry where stock_id LIKE 'STK%' order by id desc");

    if ($res1 = $stmt->fetch()) {
        $pur_array = explode('-', $res1['stock_id']);



        $year1 = $pur_array[0];
        $year2 = substr($year1, 0, 2);
        $year = '20' . $year2;
        // echo $pur_array[0];
        // echo "0<br>";
        // echo $pur_array[1];
        // echo "<br>";
        // echo $pur_array[2];
        // echo "<br>";
        // echo $pur_array[3];
        // echo "<br>";
        // echo $pur_array[4];
        // echo "<br>";
        $booking_no = substr($pur_array[1], 6, 4);
    }

    if ($booking_no == '') {
        $booking_nos = 'STK-' . $splt_acc_yr . '/' . $month . '/' . '0001';
    } else {

        $booking_no += 1;
        $booking_nos = 'STK-' . $splt_acc_yr . '/' . $month . '/' . str_pad($booking_no, 4, '0', STR_PAD_LEFT);
    }

    // echo $booking_nos;
    return $booking_nos;
}

?>

<style>
    .disabled-select {
        pointer-events: none;
        background-color: #f5f5f5;
        /* or any other color to indicate it's disabled */
        color: #999;
        /* or any other color to indicate it's disabled */
    }

    #error_message {
        color: red;
    }

    input#discount {
        border: 1px solid #ccc;
    }

    input#gst_val {
        border: 1px solid #ccc;
    }

    input#gst {
        border: 1px solid #cccc;
    }

    input#expense {
        border: 1px solid #ccc;
    }

    .percent {
        width: 100%;
    }

    .table-bo input {
        border: 1px solid #ccc;
        padding: 5px;
        border-radius: 2px;
        outline: 0;
        width: 100%;
    }

    .center-percent h3 {
        font-size: 14px;
    }

    .dt-right {
        text-align: right !important;
    }

    .dt-center {
        text-align: center !important;
    }
</style>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Stock Inward Entry</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">

                    <input type="hidden" id="screen_unique_id" name="screen_unique_id"
                        value="<?= $screen_unique_id; ?>">
                    <div class="row">

                        <div class="">
                            <div class="card">
                                <div class="card-body">
                                    <!-- <form class="was-validated" autocomplete="off"> -->
                                    <div class="row mb-2">


                                        <div class="col-md-3">
                                            <label for="simpleinput" class="form-label">Supplier Name</label>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <select id="supplier_name" name="supplier_name" class="form-control"
                                                placeholder="" onchange="get_sup_address()">
                                                <?php echo $supplier_name_options; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-3 ">
                                            <input type="hidden" id="csrf_token" name="csrf_token"
                                                value="<?php echo $_SESSION['csrf_token']; ?>">

                                            <label for="simpleinput" class="form-label">Stock Inward Entry No</label>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                <input type="text" id="stock_id" name="stock_id" class="form-control"
                                                    value='<?php if ($stock_ids == '') {
                                                        echo stock_id();
                                                    } else {
                                                        echo $stock_ids;
                                                    }
                                                    ; ?>'>
                                            </form>
                                        </div>

                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3 ">

                                            <label for="simpleinput" class="form-label">Address</label>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <textarea class="form-control" id="address" name="address"
                                                value="<?php echo $address; ?>"
                                                readonly><?php echo $address; ?></textarea>
                                            <!-- <input type="text" id="address" name="address" value="Erode" class="form-control" placeholder=" "> -->
                                        </div>
                                        <div class="col-md-3 ">
                                            <label for="simpleinput" class="form-label">Date:</label>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <input type="date" id="entry_date" name="entry_date" class="form-control"
                                                value="<?php echo $entry_date; ?>">

                                        </div>



                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3 ">
                                            <label for="simpleinput" class="form-label">Supplier Bill No</label>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <input type="text" id="bill_no" name="bill_no" class="form-control"
                                                oninput="off_id(this)" placeholder=" " value='<?php echo $bill_no; ?>'>
                                            <input type="hidden" id="main_unique_id" name="main_unique_id"
                                                class="form-control" placeholder=" "
                                                value='<?php echo $main_unique_id; ?>'>
                                        </div>
                                        <div class="col-md-3 ">

                                            <label for="simpleinput" class="form-label">District</label>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <select id="district" name="district" class="form-control disabled-select">
                                                <?php echo $district_name_options; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">

                                        <div class="col-md-3 ">

                                            <label for="simpleinput" class="form-label">Taluk</label>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <select id="taluk" name="taluk"
                                                class="select2 form-control disabled-select">
                                                <?php echo $taluk_name_options; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 ">

                                            <label for="simpleinput" class="form-label">Hostel Name</label>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <select id="hostel_name" name="hostel_name"
                                                class="form-control disabled-select">
                                                <?php echo $hostel_name_options; ?>
                                            </select>
                                        </div>

                                    </div><br>
                                    <hr><br>


                                    <form class="was-validated" autocomplete="off">
                                        <div class="row">
                                            <div class="col-md-3 fm">

                                                <label for="simpleinput" class="form-label">Product Type</label>
                                                <select class="form-control" name="item_name" id="item_name"
                                                    onchange="get_unit_name(this.value)" required>
                                                    <?php echo $product_type_option; ?>
                                                </select>
                                            </div>


                                            <div class="col-md-2 fm">

                                                <label for="simpleinput" class="form-label">Quantity</label>
                                                <input type="text" class="form-control" name="qty" id="qty" dir="rtl"
                                                    oninput="dec_number(this)" onkeyup="get_total()" required>
                                            </div>




                                            <div class="col-md-2 fm">

                                                <label for="simpleinput" class="form-label">Unit</label>
                                                <select name="unit" id="unit" class="form-control" dir="rtl" disabled>

                                                </select>
                                            </div>



                                            <div class="col-md-2 fm">

                                                <label for="simpleinput" class="form-label">Rate</label>
                                                <input type="text" class="form-control" name="rate" id="rate" dir="rtl"
                                                    onkeyup="get_total()" oninput="dec_number(this)" required>
                                            </div>




                                            <div class="col-md-2 fm">

                                                <label for="simpleinput" class="form-label">Amount</label>
                                                <input type="text" class="form-control" name="amount" id="amount"
                                                    dir="rtl" required disabled>
                                            </div>



                                            <input type="hidden" class="form-control" name="unique_id" id="unique_id">


                                            <input type="hidden" id="stock_id" name="stock_id" class="form-control"
                                                value='<?php if ($stock_ids == '') {
                                                    echo stock_id();
                                                } else {
                                                    echo $stock_ids;
                                                }
                                                ; ?>'>
                                            <div class="col-md-1 fm mt-3 ">

                                                <input type="button" class="btn btn-primary"
                                                    onclick="stock_sub_add_update()" id="btn" value="Add"
                                                    style="float: right;">
                                            </div>
                                        </div>

                                    </form>








                                    <div class=" mb-4">
                                        <div id="product_details_datatable_wrapper"
                                            class="dataTables_wrapper dt-bootstrap5 no-footer">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6"></div>
                                                <div class="col-sm-12 col-md-6"></div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-12">
                                                    <form class="was-validated" autocomplete="off">

                                                        <table id="stock_sub_datatable"
                                                            class="table table-hover table-bordered align-middle mb-0 dataTable no-footer"
                                                            width="100%" style="width: 100%;">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th class="sorting_disabled text-center" rowspan="1"
                                                                        colspan="1" style="width: 0px;">
                                                                        S.NO </th>
                                                                    <th scope="col" class="sorting_disabled" rowspan="1"
                                                                        colspan="1" style="width: 0px;">
                                                                        Item Name</th>
                                                                    <th scope="col" class="sorting_disabled" rowspan="1"
                                                                        colspan="1" style="width: 0px;">
                                                                        Qty</th>

                                                                    <th scope="col" class="sorting_disabled" rowspan="1"
                                                                        colspan="1" style="width: 0px;">
                                                                        Unit</th>
                                                                    <th scope="col" class="sorting_disabled" rowspan="1"
                                                                        colspan="1" style="width: 0px;">
                                                                        Rate </th>
                                                                    <th scope="col" class="sorting_disabled" rowspan="1"
                                                                        colspan="1" style="width: 0px;">
                                                                        Amount </th>
                                                                    <th scope="col" class="sorting_disabled" rowspan="1"
                                                                        colspan="1" style="width: 0px;">
                                                                        Action </th>





                                                                </tr>
                                                            </thead>




                                                            <?php
                                                            // if ($stock_ids == '') {
                                                            //     $stock_id = stock_id();
                                                            // } else {
                                                            //     $stock_id = $stock_ids;
                                                            // }
                                                            ?>



                                                        </table>
                                                    </form>


                                                    <div class="row mt-3 table-bo" >
                                                        <!-- <div class="col-6">
                                                           
                                                            <label>Total Quantity</label>
                                                        </div>-->
                                                        <!-- <div class="col-6"> -->
                                                        <input type="hidden" name="tot_qty" id="tot_qty"
                                                            value='<?php echo $tot_qty; ?>' readonly>


                                                        <!-- </div><br><br> -->
                                                        <div class="col-6">

                                                            <label>Total Amount</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <!-- <input type="hidden" name="tot_amount" id="tot_amount" value='<?php echo $tot_amount; ?>' readonly> -->
                                                            <h3 id="tot_amount"><?php echo $tot_amount; ?></h3>


                                                        </div><br><br>
                                                        <div class="col-4">
                                                            <input type="hidden" name="total_amount" id="total_amount"
                                                                value='<?php echo $tot_amount; ?>'>

                                                            <label>Discount %</label>
                                                        </div>
                                                        <div class="col-2">
                                                            <input type="text" name="discount" id="discount"
                                                                oninput="dec_number(this)"
                                                                value='<?php echo $discount; ?>'
                                                                onkeyup="getdiscountamount()" class="percent"
                                                                placeholder="0" dir="rtl">
                                                        </div>
                                                        <div class="col-2 center-percent">
                                                            <h3>%</h3>
                                                        </div>

                                                        <div class="col-4 ">
                                                            <input type="text" name="aft_discount" id="aft_discount"
                                                                readonly dir="rtl">

                                                            <input type="hidden" name="discount_amount"
                                                                id="discount_amount">

                                                        </div><br><br>

                                                        <div class="col-4">
                                                            <label>GST %</label>
                                                        </div>
                                                        <div class="col-2">
                                                            <input type="text" name="gst" id="gst"
                                                                oninput="dec_number(this)" value='<?php echo $gst; ?>'
                                                                onkeyup="getgstamount()" class="percent" placeholder="0"
                                                                dir="rtl">
                                                        </div>
                                                        <div class="col-2 center-percent">
                                                            <h3>%</h3>
                                                        </div>

                                                        <div class="col-4">
                                                            <input type="hidden" name="gst_val" id="gst_val" value=""
                                                                readonly>
                                                            <input type="text" name="aft_gst" id="aft_gst" value=""
                                                                dir="rtl" readonly>
                                                            <input type="hidden" name="gst_amount" id="gst_amount">

                                                        </div>

                                                        <br><br>

                                                        <div class="col-4">
                                                            <label>Other Expense</label>
                                                        </div>
                                                        <div class="col-2">
                                                            <input type="text" name="expense" id="expense"
                                                                oninput="number_only(this)"
                                                                value='<?php echo $expense; ?>'
                                                                onkeyup="getotherexpamount()" class="percent"
                                                                placeholder="0" dir="rtl">
                                                        </div>
                                                        <div class="col-2 center-percent">
                                                            <h3></h3>
                                                        </div>
                                                        <div class="col-4">
                                                            <input type="text" name="aft_expense" id="aft_expense"
                                                                dir="rtl" readonly>
                                                        </div>
                                                        <br><br>
                                                        <hr>

                                                        <div class="col-6">
                                                            <label>Net Amount</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <h3 id="net_amt">
                                                                
                                                                    <?php if ($net_total_amount) {
                                                                        echo $net_total_amount;
                                                                    } else {
                                                                        echo $tot_amount;
                                                                    } ?>
                                                               
                                                            </h3>
                                                            <input type="hidden" name="net_total_amount"
                                                                id="net_total_amount" value='<?php if ($net_total_amount) {
                                                                    echo $net_total_amount;
                                                                } else {
                                                                    echo $tot_amount;
                                                                } ?>'>
                                                        </div>



                                                    </div>
                                                    <div class="row file-up">
                                                        <div class="col-3 attach">
                                                            <label style="margin-right: 12px;">Bill Attachment
                                                                Upload</label>

                                                            <input type="file" class='form-control' name="test_file" id="test_file"
                                                            accept=".pdf,.doc,.docx,.xls,.xlsx,image/*">
                                                            <input type="hidden" name="file_name" id="file_name"
                                                                value="<?php echo $file_names; ?>">
                                                        </div>

                                                    </div>
                                                    <div id="product_details_datatable_processing"
                                                        class="dataTables_processing card" style="display: none;">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-5"></div>
                                                <div class="col-sm-12 col-md-7"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btns">
                                        <?php echo btn_cancel($btn_cancel); ?>

                                        <?php if ($main_unique_id == '') { ?>
                                            <button type="button"
                                                class="btn btn-primary m-t-15 waves-effect createupdate_btn"
                                                onclick="stock_entry_cu('')">Save</button>
                                        <?php }
                                        if ($main_unique_id != '') { ?>
                                            <button type="button"
                                                class="btn btn-primary m-t-15 waves-effect createupdate_btn"
                                                onclick="stock_entry_cu('')">Update</button>
                                        <?php } ?>
                                    </div>

                                </div>

                            </div>



                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
        </div>
    </div>
</div>
</div>

<script>
    // var discount = $("")
    // $(document).ready(function() {
    //     var discount = $("#discount").val();
    //     if(discount){
    //         getdiscountamount();
    //     }
    //     var gst = $("#gst").val();
    //     if(gst){
    //         getgstamount();
    //     }
    //     var expense = $("#expense").val();
    //     if(expense){
    //         getotherexpamount();
    //     }

    // });

    function get_sup_address() {
        var supplier_name = document.getElementById('supplier_name').value;
        var ajax_url = sessionStorage.getItem("folder_crud_link");
        if (supplier_name) {
            var data = {
                "supplier_name": supplier_name,
                "action": "get_sup_address"
            }

            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function (data) {
                    // alert(data);
                    if (data) {
                        $("#address").html(data);
                    }
                }
            });
        }
    }

    function get_zone_name() {
        // alert("hii");
        var district = document.getElementById('district').value;
        // alert(district);
        var ajax_url = sessionStorage.getItem("folder_crud_link");
        if (district) {
            var data = {
                "district": district,
                "action": "get_zone_name"
            }

            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function (data) {
                    // alert(data);
                    if (data) {
                        $("#taluk").html(data);
                    }
                }
            });
        }
    }


    function get_hostel_name() {
        //  alert("hii");
        var taluk = document.getElementById('taluk').value;
        // alert(taluk);
        var ajax_url = sessionStorage.getItem("folder_crud_link");
        if (taluk) {
            var data = {
                "taluk": taluk,
                "action": "get_hostel_name"
            }

            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function (data) {
                    //  alert(data);
                    if (data) {
                        $("#hostel_name").html(data);
                    }
                }
            });
        }
    }

    function get_unit_name() {
        // alert("hii");
        var item_name = document.getElementById('item_name').value;

        // alert(item_name);
        var ajax_url = sessionStorage.getItem("folder_crud_link");
        if (item_name) {
            var data = {
                "item_name": item_name,
                "action": "get_unit_name"
            }

            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function (data) {
                    // alert(data);
                    if (data) {
                        $("#unit").html(data);
                    }
                }
            });
        }
    }

    function save_overall() { // au = add,update
        $('#totalqty').empty();
        $('#totalamount').empty();
        var internet_status = is_online();
        var unique_id = $("#unique_id").val();

        var is_form = form_validity_check("was-validated");

        if (is_form) {

            var data = $(".was-validated").serialize();
            data += "&unique_id=" + unique_id + "&action=createupdate_overall";

            var ajax_url = sessionStorage.getItem("folder_crud_link");
            var url = '';

            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                // cache: false,
                // contentType: false,
                // processData: false,
                method: 'POST',


                success: function (data) {
                    save_data();

                }

            });


        }
    }

    function save_data() { // au = add,update
        $('#totalqty').empty();
        $('#totalamount').empty();
        var internet_status = is_online();
        var unique_id = $("#unique_id").val();

        var is_form = form_validity_check("was-validated");

        if (is_form) {

            var data = $(".was-validated").serialize();
            data += "&unique_id=" + unique_id + "&action=createupdate";

            var ajax_url = sessionStorage.getItem("folder_crud_link");
            var url = '';



            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                // cache: false,
                // contentType: false,
                // processData: false,
                method: 'POST',


                success: function (data) {

                    var obj = JSON.parse(data);
                    var msg = obj.msg;
                    var status = obj.status;
                    var error = obj.error;

                    if (msg == "already") {

                        sweetalert(msg);

                    }
                    if (msg == "create") {

                        document.getElementById('item_name').value = '';
                        document.getElementById('qty').value = '';

                        document.getElementById('unit').value = '';

                        document.getElementById('rate').value = '';

                        document.getElementById('amount').value = '';

                        document.getElementById('unique_id').value = '';
                        sweetalert(msg);
                        location.reload();


                    }
                    if (msg == "update") {

                        document.getElementById('item_name').value = '';
                        document.getElementById('qty').value = '';

                        document.getElementById('unit').value = '';

                        document.getElementById('rate').value = '';

                        document.getElementById('amount').value = '';

                        document.getElementById('unique_id').value = '';
                        $('#btn').empty();
                        $('#btn').append('Add');
                        sweetalert(msg);
                        location.reload();



                    }

                }
            });


        } else {

            sweetalert("custom", '', '', 'Create Sub Details');


        }
    }

    function sub_list_datatable(table_id = "", form_name = "", action = "") {
        // alert("hii");

        var stock_id = $("#stock_id").val();

        var table = $("#" + table_id);
        var data = {
            "stock_id": stock_id,
            // "screen_unique_id": screen_unique_id,
            "action": table_id,
        };
        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var datatable = new DataTable(table, {
            destroy: true,
            "searching": false,
            "paging": false,
            "ordering": false,
            "info": false,
            "ajax": {
                url: ajax_url,
                type: "POST",
                data: data,

            }



        });
    }

    function get_records(val) {

        //  var data 	 = val;
        var data = "id=" + val + " &action=updatevalues";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = '';



        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,

            method: 'POST',


            success: function (data) {

                var obj = JSON.parse(data);
                var msg = obj.msg;
                var data = obj.data;
                var status = obj.status;
                var error = obj.error;
                // alert(data.item_name);
                document.getElementById('item_name').value = data.item_name;
                document.getElementById('qty').value = data.qty;

                document.getElementById('unit').value = data.unit;

                document.getElementById('rate').value = data.rate;

                document.getElementById('amount').value = data.amount;

                document.getElementById('unique_id').value = data.unique_id;

                $('#btn').empty();
                $('#btn').append('update');

                var item_name = document.getElementById('item_name').value;
                // alert(item_name);
                if (item_name) {
                    get_unit_name();
                }



            }
        });


    }

    function get_delete(val) {

        //  var data 	 = val;
        var data = "id=" + val + " &action=sub_delete";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = '';



        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,

            method: 'POST',


            success: function (data) {

                var obj = JSON.parse(data);
                var msg = obj.msg;
                var data = obj.data;
                var status = obj.status;
                var error = obj.error;

                if (msg == "success_delete") {
                    sweetalert(msg);
                    location.reload();
                    // sub_list_datatable("document_upload_sub_datatable");
                }
            }
        });


    }

    function getdiscountamount() {
        $('#net_amt').empty();
        var discount = document.getElementById('discount').value;

       // if (discount == '') {
            $('#gst').val('');
            $('#gst_amount').val('0');
            $('#aft_gst').val('0');
            $('#expense').val('');
            $('#aft_expense').val('0');
            // $("#discount").val('0');
       // }


        var actual_price = document.getElementById('total_amount').value;

        // var discount = document.getElementById('discount').value;
        if (discount == '') {
            discount = '0';
        }

        selling_price = actual_price - (actual_price * (discount / 100));
        let rounded_selling_price = Math.round(selling_price);
        // let rounded_selling_price = selling_price;




        if (discount != '' && discount != '0') {
            document.getElementById('discount_amount').value = rounded_selling_price;
            document.getElementById('aft_discount').value = rounded_selling_price;
            $('#net_amt').append(rounded_selling_price);
            $('#net_total_amount').val(rounded_selling_price);
        } else {
            document.getElementById('discount_amount').value = '0';
            document.getElementById('aft_discount').value = '0';
            $('#net_amt').text(actual_price);
            $('#net_total_amount').val(actual_price);
        }



    }

    function getgstamount() {

        var gst = document.getElementById('gst').value;


        $('#net_amt').empty();
      //  if (gst == '') {
            $('#expense').val('');
       // }
        $('#aft_expense').val('0');
        var gst_total = '';



        var discount_amount = document.getElementById('discount_amount').value;

        var discount = document.getElementById('discount').value;
        var net_total_amount = document.getElementById('net_total_amount').value;
        // alert(net_total_amount);

        var total_amount = document.getElementById('total_amount').value;

        // if (total_amount == '') {
        //     var discount_amount = total_amount;
        //     alert(discount_amount);

        // }

        var gst = document.getElementById('gst').value;
        if (gst == '') {
            gst = '0';
        }



        if (discount == '') {

            const tax = total_amount * (gst / 100);

            let rounded_tax = Math.round(tax);
            // let rounded_tax = tax;
            gst_total = parseInt(total_amount) + parseInt(rounded_tax);
        } else if (discount != '') {
            const tax = discount_amount * (gst / 100);

            let rounded_tax = Math.round(tax);
            // let rounded_tax = tax;
            gst_total = parseInt(discount_amount) + parseInt(rounded_tax);


        }



        if (gst != '' && gst != '0') {
            ;
            document.getElementById('aft_gst').value = gst_total;
            document.getElementById('gst_amount').value = gst_total;
            document.getElementById('net_total_amount').value = gst_total;
            $('#net_amt').text(gst_total);
        } else {
            document.getElementById('aft_gst').value = "0";
            document.getElementById('gst_amount').value = "0";
            document.getElementById('net_total_amount').value = gst_total;
            $('#net_amt').text(gst_total);
        }
        //   alert(net_total_amount);
    }

    function getotherexpamount() {
        var expense = document.getElementById('expense').value;
        var actual_price = document.getElementById('total_amount').value;
        var gst_amt = document.getElementById('aft_gst').value;
        var discount_amt = document.getElementById('aft_discount').value;
        var discount = document.getElementById('discount').value;
        var gst = document.getElementById('gst').value;
        var net_total_amount = document.getElementById('net_total_amount').value;


        if (expense == '') {
            expense = '0';
        }

        if (gst != '') {
            net_total_amount = gst_amt;
        } else if (gst == '' && discount != '') {
            net_total_amount = discount_amt;
        } else {
            net_total_amount = actual_price;
        }

        // var gst_amount = document.getElementById('gst_amount').value;
        // if(gst != ''){
        var other_expense = parseInt(net_total_amount) + parseInt(expense);
        // }else{
        //     var other_expense = parseInt(tot_amount) +parseInt(expense);
        // }

        if (expense != '' && expense != '0') {
            document.getElementById('aft_expense').value = other_expense;
            document.getElementById('net_total_amount').value = other_expense;
            $('#net_amt').text(other_expense);
        } else {
            document.getElementById('net_total_amount').value = net_total_amount;
            $('#net_amt').text(net_total_amount);
            document.getElementById('aft_expense').value = "0";
        }


    }

    $(document).ready(function () {
        var table_id = "document_upload_sub_datatable";
        // go();
        // sub_list_datatable(table_id,form_name,action);
    });

    function get_total() {
        var qty = document.getElementById('qty').value;
        var rate = document.getElementById('rate').value;
        var amount = qty * rate;
        var amt = Math.round(amount);
        document.getElementById('amount').value = amt;
        // getdiscountamount();
        // getgstamount();
        // getotherexpamount();
    }

    // save_data()

    function stock_entry_cu($unique_id = "") {
        var internet_status = is_online();

        if (!internet_status) {
            sweetalert("no_internet");
            return false;
        }

        var entry_date = document.getElementById('entry_date').value;
        var supplier_name = document.getElementById('supplier_name').value;
        var address = document.getElementById('address').value;
        var bill_no = document.getElementById('bill_no').value;
        var hostel_name = document.getElementById('hostel_name').value;
        var csrf_token = document.getElementById('csrf_token').value;
        var stock_id = document.getElementById('stock_id').value;
        var discount = document.getElementById('discount').value;
        var expense = document.getElementById('expense').value;
        var gst = document.getElementById('gst').value;

        var net_total_amount = document.getElementById('net_total_amount').value;
        var tot_qty = document.getElementById('tot_qty').value;
        var tot_amount = document.getElementById('total_amount').value;

        var district = document.getElementById('district').value;
        var taluk = document.getElementById('taluk').value;

        var unique_ids = document.getElementById('unique_id').value;

        var file_name = document.getElementById('file_name').value;
        var main_unique_id = document.getElementById('main_unique_id').value;
        var screen_unique_id = document.getElementById('screen_unique_id').value;

        if (main_unique_id == '') {
            unique_id = unique_ids;
        } else {
            unique_id = main_unique_id;
        }


        var data = new FormData();

        var image_s = $("#test_file");

        var files = document.getElementById('test_file').files;

        const fileInput = document.getElementById('test_file');
            const file = fileInput.files[0];


    const allowedFileTypes = [
        'image/jpeg', 'image/png', 'image/gif', // Images
        'application/pdf',                     // PDF
        'application/msword',                  // DOC
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
        'application/vnd.ms-excel',            // XLS
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // XLSX
    ];
    const maxFileSize = 5 * 1024 * 1024; // 5MB
    if(file){
        if (!allowedFileTypes.includes(file.type)) {
            sweetalert('invalid_ext');
            return false;
        }
    }


        if (image_s != '') {
            var allowedExtensions =
                /(\.jpg|\.jpeg|\.png|\.pdf|\.xlsx|\.xls)$/i; // Regular expression for allowed extensions
            for (var i = 0; i < image_s.length; i++) {
                {
                    data.append("test_file", document.getElementById('test_file').files[i]);
                }
            }
        } else {
            data.append("test_file", '');
        }

        var actions = "main_createupdate";

        data.append("entry_date", entry_date);
        data.append("supplier_name", supplier_name);
        data.append("address", address);
        data.append("bill_no", bill_no);
        data.append("hostel_name", hostel_name);
        data.append("csrf_token", csrf_token);
        data.append("stock_id", stock_id);
        data.append("discount", discount);
        data.append("expense", expense);
        data.append("gst", gst);

        data.append("net_total_amount", net_total_amount);
        data.append("tot_qty", tot_qty);
        data.append("tot_amount", tot_amount);
        data.append("district", district);
        data.append("taluk", taluk);
        data.append("unique_id", unique_id);
        data.append("screen_unique_id", screen_unique_id);

        data.append("action", actions);


        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = sessionStorage.getItem("list_link");

        if ((image_s != '' || file_name != '') && entry_date != '' && supplier_name != '' && bill_no != '' && stock_id != '') {
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',

                success: function (data) {
                    // alert(data);
                    overall_entry_cu();
                    var obj = JSON.parse(data);
                    var msg = obj.msg;
                    var status = obj.status;
                    var error = obj.error;

                },
                error: function (data) {
                    alert("Network Error");
                }
            });
        } else {
            sweetalert("form_alert");
        }
    }



    function overall_entry_cu() {

        var entry_date = document.getElementById('entry_date').value;
        var hostel_name = document.getElementById('hostel_name').value;
        var bill_no = document.getElementById('bill_no').value;
        var csrf_token = document.getElementById('csrf_token').value;
        var stock_id = document.getElementById('stock_id').value;
        var district = document.getElementById('district').value;
        var taluk = document.getElementById('taluk').value;

        var unique_ids = document.getElementById('unique_id').value;
        var main_unique_id = document.getElementById('main_unique_id').value;

        if (main_unique_id == '') {
            unique_id = unique_ids;
        } else {
            unique_id = main_unique_id;
        }

        var data = new FormData();

        data.append("entry_date", entry_date);
        data.append("bill_no", bill_no);
        data.append("hostel_name", hostel_name);
        data.append("csrf_token", csrf_token);
        data.append("stock_id", stock_id);
        data.append("district", district);
        data.append("taluk", taluk);

        data.append("action", "overall_createupdate");


        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = sessionStorage.getItem("list_link");

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',

            success: function (data) {
                var obj = JSON.parse(data);
                var msg = obj.msg;
                var status = obj.status;
                var error = obj.error;

                // if (msg == "form_alert") {
                //     sweetalert("form_alert");
                // } else {
                    if (!status) {
                        url = '';
                        $(".createupdate_btn").text("Error");
                        console.log(error);
                    } else {
                        if (msg == "already") {
                            // Button Change Attribute
                            url = '';

                            $(".createupdate_btn").removeAttr("disabled", "disabled");
                            if (unique_id) {
                                $(".createupdate_btn").text("Update");
                            } else {
                                $(".createupdate_btn").text("Save");
                            }
                        }
                    }
                // }
                sweetalert(msg, url);
            },
            error: function (data) {
                alert("Network Error");
            }
        });

    }
</script>