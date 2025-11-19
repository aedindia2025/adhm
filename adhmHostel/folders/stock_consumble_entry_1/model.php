<!-- Modal with form -->
<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<style>
    #stock_id {
        border: none;
        font-weight: bold;
    }

    .dt-right {
        text-align: right !important;
    }

    .dt-center {
        text-align: center !important;
    }
</style>


<meta http-equiv="refresh" content="<?php echo $sec ?>;URL='<?php echo $page ?>'">
<?php
// Form variables
$btn_text = "Save";
$btn_action = "create";
$prefix = "";
$entry_date = date('Y-m-d');

// $unique_id          = "";
$district_name = "";
$is_active = 1;
$screen_unique_id = unique_id($preifx);

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
        $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id; 

        $where = "unique_id = '$unique_id'";
        $table_main = "stock_consumble_entry";

        $columns = [

            "' ' as sno",
            "entry_date",
            "stock_id",
            "hostel_name",
            "district",
            "taluk",
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
            $hostel_name = $result_values[0]["hostel_name"];
            $district = $result_values[0]["district"];
            $taluk = $result_values[0]["taluk"];
            $unique_ids = $result_values[0]["unique_id"];
            $screen_unique_id = $result_values[0]["screen_unique_id"];
            
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

$district_name_options = district_name();
$district_name_options = select_option($district_name_options, "Select District Name", $district_unique_id);
$taluk_options = taluk_name_get();
$taluk_name_options = select_option($taluk_options, "Select Zone", $taluk_unique_id);
$hostel_options = hostel_name();
$hostel_name_options = select_option($hostel_options, "Select Hostel", $hostel_unique_id);
$supplier_name_options = supplier_name_creation();
$supplier_name_options = select_option($supplier_name_options, "Select supplier Name", $supplier_name);
$product_type_options = product_type_name();
$product_type_option = select_option($product_type_options, "Select Item Name", $item_name);

$unit_options = unit_measurement();
$unit_options = select_option($unit_optionss, "Select Item Name", $unit);
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

    $stmt = $conn->query("SELECT * FROM stock_consumble_entry where stock_id LIKE 'SC%' order by id desc");

    if ($res1 = $stmt->fetch()) {
        $pur_array = explode('-', $res1['stock_id']);



        $year1 = $pur_array[0];
        $year2 = substr($year1, 0, 2);
        $year = '20' . $year2;

        $booking_no = substr($pur_array[1], 6, 4);
    }

    if ($booking_no == '') {
        $booking_nos = 'SC-' . $splt_acc_yr . '/' . $month . '/' . '0001';
    } else {

        $booking_no += 1;
        $booking_nos = 'SC-' . $splt_acc_yr . '/' . $month . '/' . str_pad($booking_no, 4, '0', STR_PAD_LEFT);
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

    .btns {
        margin-bottom: 27px;
        margin-right: 20px;
    }

    table#document_upload_sub_datatable td,
    th {
        border: 1px solid #efe9e9;
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
                        <input type="hidden" id="screen_unique_id" name="screen_unique_id"
                            value="<?= $screen_unique_id; ?>">

                        <h4 class="page-title">Stock Consumble Entry</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">


                    <div class="row">

                        <div class="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-2">

                                        <div class="col-md-3 ">
                                        <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                            <label for="simpleinput" class="form-label">Stock Consume No</label>
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
                                        <div class="col-md-3">
                                            <label for="simpleinput" class="form-label">Date:</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" id="entry_date" name="entry_date" class="form-control"
                                                value="<?php echo $entry_date; ?>">
                                            <input type="hidden" class="form-control" name="unique_id" id="unique_id"
                                                value="<?php echo $unique_id; ?>">
                                        </div>

                                    </div>
                                    <div class="row mb-2">

                                        <div class="col-md-3 ">

                                            <label for="simpleinput" class="form-label">District</label>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <select id="district" name="district" class="form-control disabled-select">
                                                <?php echo $district_name_options; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 ">

                                            <label for="simpleinput" class="form-label">Taluk</label>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <select id="taluk" name="taluk" class="form-control disabled-select">
                                                <?php echo $taluk_name_options; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">


                                        <div class="col-md-3 ">

                                            <label for="simpleinput" class="form-label">Hostel Name</label>
                                        </div>
                                        <div class="col-md-3">
                                            <select id="hostel_name" name="hostel_name"
                                                class="form-control disabled-select">
                                                <?php echo $hostel_name_options; ?>
                                            </select>
                                        </div>
                                        <input type="hidden" class="form-control" name="hostel_id" id="hostel_id"
                                            value="<?= $hostel_unique_id ?>" readonly>

                                    </div><br>
                                    <hr><br>

                                    <form class="was-validated" autocomplete="off">
                                        <div class="row">
                                            <div class="col-md-3 fm">

                                                <label for="simpleinput" class="form-label">Product Type</label>
                                                <select class="form-control" name="item_name" id="item_name"
                                                    onchange="get_unit_name(this.value)">
                                                    <?php echo $product_type_option; ?>
                                                </select>
                                            </div>


                                            <div class="col-md-3 fm">

                                                <label for="simpleinput" class="form-label">Stock</label>
                                                <input type="text" class="form-control" name="act_qty" id="act_qty"
                                                    dir="rtl" readonly>
                                            </div>

                                            <div class="col-md-3 fm">

                                                <label for="simpleinput" class="form-label">Outward Qty</label>
                                                <input type="text" class="form-control" name="qty" id="qty" dir="rtl" oninput="dec_number(this)"
                                                    onkeypress="number_only(event)">
                                            </div>
                                            <input type="hidden" id="stock_id" name="stock_id" class="form-control"
                                                value='<?php if ($stock_ids == '') {
                                                    echo stock_id();
                                                } else {
                                                    echo $stock_ids;
                                                }
                                                ; ?>'>

                                            <div class="col-md-1 fm mt-3 ">

                                                <button type="button" class="btn btn-primary"
                                                    onclick="stock_out_sub_add()" id="btn">Add</button>
                                            </div>
                                        </div>


                                        <!-- <div class="col-md-3 fm">

                                            <label for="simpleinput" class="form-label">Item Name</label>
                                        </div>
                                        <div class="col-md-3">
                                        <select class="form-control" name="item_name" id="item_name" onchange="get_unit_name(this.value)">
                                         <?php echo $product_type_option; ?>
                                        </select>
                                        </div>

                                        <div class="col-md-3 fm">

                                            <label for="simpleinput" class="form-label">Actual Quantity</label>
                                        </div>
                                        <div class="col-md-3">
                                        <input type="text" class="form-control" name="act_qty" id="act_qty" dir="rtl" readonly >
                                        </div>

                                        <div class="col-md-3 fm">

                                            <label for="simpleinput" class="form-label">Quantity</label>
                                        </div>
                                        <div class="col-md-3">
                                        <input type="text" class="form-control" name="qty" id="qty" dir="rtl" onkeypress="number_only(event)">
                                        </div>

                                        <input type="hidden" id="stock_id" name="stock_id" class="form-control" value='<?php if ($stock_ids == '') {
                                            echo stock_id();
                                        } else {
                                            echo $stock_ids;
                                        }
                                        ; ?>'>

                                       
                                        <div class="col-md-3">
                                        <button type="button" class="btn btn-primary" onclick="stock_out_sub_add()" id="btn">Add</button>
                                        </div> -->
                                    </form>






                                    <!-- <div class="table-responsive mb-4 mt-2"> -->
                                    <div id="product_details_datatable_wrapper"
                                        class="dataTables_wrapper dt-bootstrap5 no-footer">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6"></div>
                                            <div class="col-sm-12 col-md-6"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <form class="was-validated" autocomplete="off">

                                                    <table id="stock_out_sub_datatable" class="table table-hover"
                                                        style=" width: 100%;">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="sorting_disabled text-center" rowspan="1"
                                                                    colspan="1" style="width: 5%;">
                                                                    S.NO </th>
                                                                <th scope="col" class="sorting_disabled" rowspan="1"
                                                                    colspan="1" style="width: 20%;">
                                                                    Item Name</th>
                                                                <th scope="col" class="sorting_disabled" rowspan="1"
                                                                    colspan="1" style="width: 20%;">
                                                                    Actual Quantity</th>
                                                                <th scope="col" class="sorting_disabled" rowspan="1"
                                                                    colspan="1" style="width: 20%;">
                                                                    Quantity</th>

                                                                <!-- <th scope="col" class="sorting_disabled" rowspan="1"
                                                                        colspan="1" style="width: 0px;">
                                                                        Unit</th> -->
                                                                <!-- <th scope="col" class="sorting_disabled" rowspan="1"
                                                                        colspan="1" style="width: 0px;">
                                                                        Rate </th>
                                                                    <th scope="col" class="sorting_disabled" rowspan="1"
                                                                        colspan="1" style="width: 0px;">
                                                                        Amount </th> -->
                                                                <th scope="col" class="sorting_disabled" rowspan="1"
                                                                    colspan="1" style="width: 0px;">
                                                                    Action </th>
                                                            </tr>
                                                        </thead>


                                                        <?php
                                                        // $tot_qty = $qty;
                                                        // $floatValue = floatval($tot_qty);
                                                        
                                                        // $float_tot_qty = number_format($floatValue, 2, '.', '');
                                                        // $tot_amount = $amount;
                                                        ?>
                                                        <?php

                                                        ?>


                                                        <!-- <td id="totalqty">Quantity :
                                                                    <?php echo $float_tot_qty; ?>
                                                                </td> -->




                                                    </table>
                                                </form>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-5"></div>
                                            <div class="col-sm-12 col-md-7"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="btns">
                            <?php echo btn_cancel($btn_cancel); ?>
                                <?php if ($unique_ids == '') { ?>
                                    <button type="button" class="btn btn-primary m-t-15 waves-effect createupdate_btn"
                                        onclick="stock_consumble_entry_cu('')">Save</button>
                                <?php }
                                if ($unique_ids != '') { ?>
                                    <button type="button" class="btn btn-primary m-t-15 waves-effect createupdate_btn"
                                        onclick="stock_consumble_entry_cu('')">Update</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
    </div>
</div>
</div>


<script>
    $(document).ready(function () {
        var table_id = 'document_upload_sub_datatable';
        // sub_list_datatable(table_id);

    });

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
        $("#act_qty").value = '';
        var item_name = document.getElementById('item_name').value;
        var hostel_id = document.getElementById('hostel_id').value;

        // alert(item_name);
        var ajax_url = sessionStorage.getItem("folder_crud_link");
        if (item_name) {
            var data = {
                "item_name": item_name,
                "hostel_id": hostel_id,
                "action": "get_unit_name"
            }

            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function (data) {
                    // alert(data);
                    if (data == "") {
                        $("#act_qty").val('');
                    } else {
                        $("#act_qty").val(data);
                    }

                    // get_price();
                }
            });
        }
    }



    function save_overall() { // au = add,update
        $('#qty').empty();
        $('#act_qty').empty();
        var internet_status = is_online();
        var unique_id = $("#unique_id").val();

        var actual_qty = $("#act_qty").val();
        var entry_qty = $("#qty").val();

        var is_form = form_validity_check("was-validated");

        if (entry_qty != '') {
            if (actual_qty >= entry_qty) {

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
            } else {
                alert("Actual Qty Limit Exist!.  " + actual_qty);
            }
        } else {
            sweetalert("form_alert");
        }
    }

    function save_data() { // au = add,update
        $('#totalqty').empty();
        $('#act_qty').empty();
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
                        location.reload();

                        document.getElementById('item_name').value = '';
                        document.getElementById('qty').value = '';

                        document.getElementById('unit').value = '';

                        document.getElementById('rate').value = '';

                        document.getElementById('amount').value = '';

                        document.getElementById('unique_id').value = '';
                        sweetalert(msg);

                    }
                    if (msg == "update") {
                        location.reload();

                        document.getElementById('item_name').value = '';
                        document.getElementById('qty').value = '';

                        document.getElementById('unit').value = '';

                        document.getElementById('rate').value = '';

                        document.getElementById('amount').value = '';

                        document.getElementById('unique_id').value = '';
                        $('#btn').empty();
                        $('#btn').append('Add');
                        sweetalert(msg);

                    }
                }
            });
        } else {

            sweetalert("custom", '', '', 'Create Sub Details');

            if (status_option == '') {
                document.getElementById('status_option').focus();
            } else if (status_description == '') {
                document.getElementById('status_description').focus();
            }
        }
    }

    function sub_list_datatable(table_id = "", form_name = "", action = "") {

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



    function getdiscountamount() {

        var actual_price = document.getElementById('total_amount').value;
        // alert(total_amount);
        var discount = document.getElementById('discount').value;
        selling_price = actual_price - (actual_price * (discount / 100));
        document.getElementById('discount_amount').value = selling_price;
    }

    function getgstamount() {
        $('#net_amt').empty();
        var gst_total = '';
        var discount_amount = document.getElementById('discount_amount').value;

        var gst = document.getElementById('gst').value;
        // alert(gst);
        const tax = discount_amount * (gst / 100);
        // alert(tax);
        gst_total = parseInt(discount_amount) + parseInt(tax);
        // alert(gst_total);
        var gst_val = discount_amount * (gst / 100);
        document.getElementById('gst_val').value = gst_val;

        document.getElementById('gst_amount').value = gst_total;
        document.getElementById('net_total_amount').value = gst_total;
        $('#net_amt').append(gst_total);
    }

    function getotherexpamount() {
        $('#net_amt').empty();
        var expense = document.getElementById('expense').value;

        var gst_amount = document.getElementById('gst_amount').value;
        // alert(expense);
        var other_expense = parseInt(gst_amount) + parseInt(expense);
        //  alert(other_expense);
        document.getElementById('net_total_amount').value = other_expense;
        $('#net_amt').append(other_expense);
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
        document.getElementById('amount').value = amount;
    }

    // save_data()

    function stock_consumble_entry_cu($unique_id = "") {
        var internet_status = is_online();

        if (!internet_status) {
            sweetalert("no_internet");
            return false;
        }




        var entry_date = document.getElementById('entry_date').value;

        var hostel_name = document.getElementById('hostel_name').value;

        var stock_id = document.getElementById('stock_id').value;
        var csrf_token = document.getElementById('csrf_token').value;
        
        
        var district = document.getElementById('district').value;
        var taluk = document.getElementById('taluk').value;



        var unique_id_val = document.getElementById('unique_id').value;
        var screen_unique_id = document.getElementById('screen_unique_id').value;

        var data = new FormData();




        var actions = "main_createupdate";

        data.append("entry_date", entry_date);

        data.append("hostel_name", hostel_name);

        data.append("stock_id", stock_id);
        data.append("csrf_token", csrf_token);

        data.append("district", district);
        data.append("taluk", taluk);
        data.append("screen_unique_id", screen_unique_id);
        data.append("unique_id", unique_id_val);

        data.append("action", actions);


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
                if (msg == "create" || msg == "update") {
                    overall_consumble_entry_cu();
                }
            },
            error: function (data) {
                alert("Network Error");
            }
        });
    }

    function overall_consumble_entry_cu($unique_id = "") {
        var internet_status = is_online();

        if (!internet_status) {
            sweetalert("no_internet");
            return false;
        }



        var entry_date = document.getElementById('entry_date').value;
        // var bill_no = document.getElementById('bill_no').value;
        var hostel_name = document.getElementById('hostel_name').value;

        var stock_id = document.getElementById('stock_id').value;
        var district = document.getElementById('district').value;
        var taluk = document.getElementById('taluk').value;


        var data = new FormData();

        var actions = "overall_createupdate";

        data.append("entry_date", entry_date);

        // data.append("bill_no", bill_no);
        data.append("hostel_name", hostel_name);

        data.append("stock_id", stock_id);
        data.append("district", district);
        data.append("taluk", taluk);

        data.append("action", actions);


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

                sweetalert(msg, url);
            },
            error: function (data) {
                alert("Network Error");
            }
        });
    }

    //    }
</script>