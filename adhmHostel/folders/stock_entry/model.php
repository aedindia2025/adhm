<!-- Modal with form -->
<?php

?>
<style>
    #stock_id {
        border: none;
        font-weight: bold;
    }
</style>

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


        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        // $where      = [
        //     "unique_id" => $unique_id
        // ];
        $where = "unique_id = '$unique_id'";
        $table_main = "stock_entry";

        $columns = [
            "' ' as sno",
            "entry_date",
            "stock_id",
            "supplier_name",
            "address",
            "bill_no",
            "hostel_name",
            "district",
            "discount",
            "expense",
            "gst",
            "purchase_item",
            "fssai_no",
            "veg_item",
            "net_total_amount",
            "taluk",
            "file_name",
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
            $purchase_item = $result_values[0]["purchase_item"];
            $fssai_no = $result_values[0]["fssai_no"];
            $veg_item = $result_values[0]["veg_item"];
            $expense = $result_values[0]["expense"];
            $gst = $result_values[0]["gst"];
            $net_total_amount = $result_values[0]["net_total_amount"];
            $hostel_name = $result_values[0]["hostel_name"];
            $district = $result_values[0]["district"];

            $file_names = $result_values[0]["file_name"];
            $taluk = $result_values[0]["taluk"];
            $main_unique_id = $result_values[0]["unique_id"];
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
$staff_id = $_SESSION['staff_id'];

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

// $item_category_inward = item_category();
$item_category_inward = item_categoryindent();
$item_category_inward = select_option($item_category_inward, "Select Purchase Category", $purchase_item);

$item_stock_inward = item_stock_inward();
$item_stock_inward = select_option($item_stock_inward, "Select Item Name", $item_name);

$unit_options = unit_measurement();
$unit_options = select_option($unit_options, "Select Item Name", $unit);

function stock_id()
{
    // Get the current year and month
    $year = date("Y"); // Full year, e.g., 2024
    $month = date("m"); // Current month in two digits, e.g., 09
    $day = date("d");


    $staff_id = $_SESSION['hostel_main_id'];
    $hostel_unique_id = $_SESSION['hostel_id'];

    $hostel_name = $staff_id; // Make sure session is started and hostel_id is set

    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $database_name = "adi_dravidar";

    try {
        // Create a new PDO instance
        $conn = new PDO("mysql:host=$servername;dbname=$database_name", $username, $password);
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Handle connection error
        echo "Connection failed: " . $e->getMessage();
        return;
    }

    // Prepare the format of the stock ID prefix
    $stock_id_prefix = $hostel_name . '/' . $day . '/' . $month . '/' . $year . '-';

    // Query to get the last stock ID that matches the current prefix
    $stmt = $conn->query("SELECT stock_id FROM stock_entry WHERE hostel_name = '$hostel_unique_id' ORDER BY id DESC LIMIT 1");

    // Initialize the sequence number
    $sequence_no = 0;

    if ($res1 = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Extract the last stock ID
        $last_stock_id = $res1['stock_id'];

        // Extract the number after the '-' and increment it
        $sequence_no = (int) substr($last_stock_id, strrpos($last_stock_id, '-') + 1);
    }

    // Increment the sequence number and pad with leading zeros
    $sequence_no += 1;
    $new_stock_id = $stock_id_prefix . str_pad($sequence_no, 4, '0', STR_PAD_LEFT);

    return $new_stock_id;
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
                                    <div class="row">
                                        <div class="col-md-4 fm">
                                        <label for="district_id" class="form-label">District Name:  <span class="xy-lab"><?php echo district_name($_SESSION["district_id"])[0]['district_name']; ?></span></label>
                                        </div>
                                        <div class="col-md-4 fm">
                                        <label for="taluk_id" class="form-label">Taluk Name:  <span class="xy-lab"><?php echo taluk_name($_SESSION["taluk_id"])[0]['taluk_name']; ?></span></label>
                                        </div>
                                        <div class="col-md-4 fm">

                                        <label for="hostel_id" class="form-label">Hostel Id:  <span class="xy-lab"><?php echo hostel_name($_SESSION["hostel_id"])[0]['hostel_id']; ?></span></label>
                                        </div>
                                        <div class="col-md-4 fm">

                                        <label for="hostel_id" class="form-label">Hostel Name:  <span class="xy-lab"><?php echo hostel_name($_SESSION["hostel_id"])[0]['hostel_name']; ?></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form>
                                    <div class="row mb-2">

                                        <div class="col-md-3">
                                            <label id="supplier_label" for="supplier_name" class="form-label">Supplier
                                                Name</label><span style="color:red">*</span>
                                        </div>
                                        <div class="col-md-3 fm" id="supplier_div" style="display:none;">
                                            <select id="supplier_name" name="supplier_name" class="form-control">
                                                <?php echo $supplier_name_options; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 fm" id="supplier_div" style="display:none;">
                                            <select id="academic_year" name="academic_year" class="form-control">
                                                <?php echo $academic_year_options; ?>
                                            </select>
                                        </div>

                                        <!-- Hidden Textbox for vegetables -->
                                        <div class="col-md-3 fm" id="textbox_div" style="display:none;">
                                            <input type="text" id="veg_item" name="veg_item" oninput="description_val(this)"
                                                value="<?php echo $veg_item; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 fm">
                                            <input type="hidden" id="csrf_token" name="csrf_token"
                                                value="<?php echo $_SESSION['csrf_token']; ?>">
                                        </div>
                                        <div class="col-md-3 fm">
                                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                                                <input type="hidden" id="stock_id" name="stock_id" class="form-control"
                                                    value='<?php echo $stock_ids ?: stock_id(); ?>' readonly>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-3 ">
                                            <label for="simpleinput" class="form-label">Address</label><span style="color:red">*</span>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <textarea class="form-control" id="address" name="address" oninput="valid_address(this)"
                                                value="<?php echo $address; ?>"
                                                readonly><?php echo $address; ?></textarea>
                                            <textarea class="form-control" id="text_address" name="text_address" oninput="valid_address(this)"
                                                value="<?php echo $address; ?>"
                                                style="display:none"><?php echo $address; ?></textarea>
                                        </div>
                                        <div class="col-md-3 ">
                                            <label for="simpleinput" class="form-label">Date:</label><span style="color:red">*</span>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <input type="date" id="entry_date" name="entry_date" class="form-control"
                                                value="<?php echo $entry_date; ?>">
                                        </div>

                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label for="simpleinput" class="form-label">FSSAI Number:</label><span style="color:red">*</span>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <input type="text" id="fssai_no" name="fssai_no" class="form-control"
                                                oninput="number_only(this)" maxlength="14" value="<?php echo $fssai_no; ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="simpleinput" class="form-label">Supplier Bill No</label><span style="color:red">*</span>
                                        </div>
                                        <div class="col-md-3 fm">
                                            <input type="text" id="bill_no" name="bill_no" class="form-control" maxlength="25"
                                                oninput="off_id(this)" placeholder=" " value='<?php echo $bill_no; ?>'>
                                            <input type="hidden" id="main_unique_id" name="main_unique_id"
                                                class="form-control" placeholder=" "
                                                value='<?php echo $main_unique_id; ?>'>
                                        </div>
                                        <div class="col-md-3 fm" style="display:none;">
                                            <select id="district" name="district" class="form-control disabled-select">
                                                <?php echo $district_name_options; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-3 fm" style="display:none;">
                                            <select id="taluk" name="taluk" class="form-control disabled-select">
                                                <?php echo $taluk_name_options; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 fm" style="display:none;">
                                            <select id="hostel_name" name="hostel_name"
                                                class="form-control disabled-select">
                                                <?php echo $hostel_name_options; ?>
                                            </select>
                                        </div>

                                    </div><br>
                                    <hr><br>
                                    </form>

                                    <form class="was-validated" autocomplete="off">
                                        <div class="row">
                                            
                                            <div class="col-md-2 fm">
                                                <label for="simpleinput" class="form-label">Category<span style="color:red">*</span></label>
                                                <select class="form-control select2" name="purchase_item" id="purchase_item"
                                                    onchange="category_change()" required>
                                                    <?php echo $item_category_inward; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-3 fm">
                                                <label for="simpleinput" class="form-label">Item<span style="color:red">*</span></label>
                                                <select class="form-control select2" name="item_name" id="item_name"
                                                    onchange="get_unit_name(this.value)" required>
                                                    <?php echo $item_stock_inward; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2 fm">
                                                <label for="simpleinput" class="form-label">Remaining Monthly Indent<span style="color:red">*</span></label>
                                                <br>
                                                <label id="month_indentlabel"></label>
                                                <input type="hidden" class="form-control" name="month_indent" id="month_indent">
                                            </div>
                                            <div class="col-md-2 fm">
                                                <label for="simpleinput" class="form-label">Quantity<span style="color:red">*</span></label>
                                                <input type="text" class="form-control" name="qty" id="qty" 
                                                    oninput="dec_number(this)" onkeyup="get_total()" required>
                                            </div>

                                            <div class="col-md-1 fm">
                                                <label for="simpleinput" class="form-label">Unit</label>
                                                
                                                <input type="text" class="form-control" name="unit" id="unit"  readonly>
                                            </div>

                                            <div class="col-md-2 fm">
                                                <label for="simpleinput" class="form-label">Rate<span style="color:red">*</span></label>
                                                <input type="text" class="form-control" name="rate" id="rate" 
                                                    onkeyup="get_total()" oninput="dec_number(this)" required>
                                            </div>

                                            <div class="col-md-2 fm">
                                                <label for="simpleinput" class="form-label">Amount</label>
                                                <input type="text" class="form-control" name="amount" id="amount"
                                                     required disabled>
                                            </div>

                                            <input type="hidden" class="form-control" name="unique_id" id="unique_id">

                                            <input type="hidden" id="stock_id" name="stock_id" class="form-control"
                                                value='<?php if ($stock_ids == '') {
                                                    echo stock_id();
                                                } else {
                                                    echo $stock_ids;
                                                }
                                                ; ?>'>
                                               
                                        </div>
                                         <div class="row">
                                            <center>
                                            <div class="col-md-1 fm mt-3 ">
                                                <input type="button" class="btn btn-primary"
                                                    onclick="stock_sub_add_update()" id="btn" value="Add"
                                                    style="float: right;">
                                            </div>
                                            </center>
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
                                                                        S.No </th>
                                                                    <th scope="col" class="sorting_disabled" rowspan="1"
                                                                        colspan="1" style="width: 0px;">
                                                                        Category</th>
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

                                                        </table>
                                                    </form>

                                                    <div class="row mt-3 table-bo justify-content-end text-end">
                                                        <input type="hidden" name="tot_qty" id="tot_qty" value='<?php echo $tot_qty; ?>' readonly>
                                                        <input type="hidden" name="total_amount" id="total_amount" value='<?php echo $tot_amount; ?>'>
                                                        <input type="hidden" name="net_total_amount" id="net_total_amount" value='<?php echo $net_total_amount ? $net_total_amount : $tot_amount; ?>'>

                                                        <div class="col-6 col-md-3 mb-2">
                                                            <label style="margin-top:5px">Total Amount</label>
                                                        </div>
                                                        <div class="col-6 col-md-3 mb-2">
                                                            <label for="" id="tot_amount" style="font-weight: 700; font-size: 20px;">
                                                                <?php echo $tot_amount; ?>
                                                            </label>
                                                        </div>

                                                        <div class="col-4 col-md-2 mb-2" style="display:none;">
                                                            <label>Discount %</label>
                                                        </div>
                                                        <div class="col-2 col-md-2 mb-2" style="display:none;">
                                                            <input type="text" name="discount" id="discount" oninput="dec_number(this)" value='<?php echo $discount; ?>' onkeyup="getdiscountamount()" class="percent" placeholder="0" dir="rtl">
                                                        </div>
                                                        <div class="col-1 col-md-1 mb-2" style="display:none;">
                                                            <h3>%</h3>
                                                        </div>
                                                        <div class="col-5 col-md-4 mb-2" style="display:none;">
                                                            <input type="text" name="aft_discount" id="aft_discount" readonly dir="rtl">
                                                            <input type="hidden" name="discount_amount" id="discount_amount">
                                                        </div>

                                                        <div class="col-4 col-md-2 mb-2" style="display:none;">
                                                            <label>GST %</label>
                                                        </div>
                                                        <div class="col-2 col-md-2 mb-2" style="display:none;">
                                                            <input type="text" name="gst" id="gst" oninput="dec_number(this)" value='<?php echo $gst; ?>' onkeyup="getgstamount()" class="percent" placeholder="0" dir="rtl">
                                                        </div>
                                                        <div class="col-1 col-md-1 mb-2" style="display:none;">
                                                            <h3>%</h3>
                                                        </div>
                                                        <div class="col-5 col-md-4 mb-2" style="display:none;">
                                                            <input type="hidden" name="gst_val" id="gst_val" value="" readonly>
                                                            <input type="text" name="aft_gst" id="aft_gst" value="" dir="rtl" readonly>
                                                            <input type="hidden" name="gst_amount" id="gst_amount">
                                                        </div>

                                                        <div class="col-4 col-md-2 mb-2" style="display:none;">
                                                            <label>Other Expense</label>
                                                        </div>
                                                        <div class="col-2 col-md-2 mb-2" style="display:none;">
                                                            <input type="text" name="expense" id="expense" oninput="number_only(this)" value='<?php echo $expense; ?>' onkeyup="getotherexpamount()" class="percent" placeholder="0" dir="rtl">
                                                        </div>
                                                        <div class="col-1 col-md-1 mb-2" style="display:none;"></div>
                                                        <div class="col-5 col-md-4 mb-2" style="display:none;">
                                                            <input type="text" name="aft_expense" id="aft_expense" dir="rtl" readonly>
                                                        </div>


                                                        <div class="col-6 col-md-3 mb-2" style="display:none;">
                                                            <label>Net Amount:</label>
                                                        </div>
                                                        <div class="col-6 col-md-3 mb-2" style="display:none;">
                                                            <h3>
                                                                <p id="net_amt"><?php echo $net_total_amount ? $net_total_amount : $tot_amount; ?></p>
                                                            </h3>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <form>
                                                            <div class="col-3 attach">
                                                                <label style="margin-right: 12px;">Invoice
                                                                    Upload<span style="color:red">*</span></label>

                                                                <input type="file" class="form-control" name="test_file"
                                                                    id="test_file"
                                                                    accept=".pdf,.doc,.docx,.xls,.xlsx,image/*"
                                                                    required>
                                                                <input type="hidden" name="file_name" id="file_name"
                                                                    value="<?php echo $file_names; ?>">
                                                            </div>
                                                        </form>

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
    $(document).ready(function () {
        category_change();
    });

    function get_zone_name() {
        var district = document.getElementById('district').value;

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
        var taluk = document.getElementById('taluk').value;
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
    var item_name = document.getElementById('item_name').value;
var screen_unique_id=$('#screen_unique_id').val();
    if (!item_name) return; // Exit if empty

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var data = {
        "item_name": item_name,
        "screen_unique_id": screen_unique_id,
        "action": "get_unit_name"
    };

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        dataType: "json", // <-- Tell jQuery to expect JSON
        success: function (response) {
            // Populate the readonly input with the unit
            $("#unit").val(response.unit ?? '');

            // Update label
            $("#month_indentlabel").text(response.available_qty ?? 0);

            // Update hidden input
            $("#month_indent").val(response.available_qty ?? 0);
        },
        error: function () {
            console.error("Failed to fetch unit");
        }
    });
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
        var stock_id = $("#stock_id").val();

        var table = $("#" + table_id);
        var data = {
            "stock_id": stock_id,
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
                
                document.getElementById('item_name').value = data.item_name;
                document.getElementById('qty').value = data.qty;

                document.getElementById('unit').value = data.unit;

                document.getElementById('rate').value = data.rate;

                document.getElementById('amount').value = data.amount;

                document.getElementById('unique_id').value = data.unique_id;

                $('#btn').empty();
                $('#btn').append('update');

                var item_name = document.getElementById('item_name').value;
                if (item_name) {
                    get_unit_name();
                }
            }
        });
    }

    function get_delete(val) {

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
        $('#gst').val('');
        $('#gst_amount').val('0');
        $('#aft_gst').val('0');
        $('#expense').val('');
        $('#aft_expense').val('0');

        var actual_price = document.getElementById('total_amount').value;
        if (discount == '') {
            discount = '0';
        }

        selling_price = actual_price - (actual_price * (discount / 100));
        let rounded_selling_price = Math.round(selling_price);
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
        $('#expense').val('');
        $('#aft_expense').val('0');
        var gst_total = '';
        var discount_amount = document.getElementById('discount_amount').value;
        var discount = document.getElementById('discount').value;
        var net_total_amount = document.getElementById('net_total_amount').value;
        var total_amount = document.getElementById('total_amount').value;

        var gst = document.getElementById('gst').value;
        if (gst == '') {
            gst = '0';
        }

        if (discount == '') {
            const tax = total_amount * (gst / 100);
            let rounded_tax = Math.round(tax);
            gst_total = parseInt(total_amount) + parseInt(rounded_tax);
        } else if (discount != '') {
            const tax = discount_amount * (gst / 100);

            let rounded_tax = Math.round(tax);
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

        var other_expense = parseInt(net_total_amount) + parseInt(expense);

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
        generateFSSAINumber();
    });


    function get_total() {
        var qty = document.getElementById('qty').value;
        var rate = document.getElementById('rate').value;
        var amount = qty * rate;
        var amt = Math.round(amount);
        document.getElementById('amount').value = amt;
    }

    function stock_entry_cu($unique_id = "") {
        var internet_status = is_online();

        if (!internet_status) {
            sweetalert("no_internet");
            return false;
        }

        var entry_date = document.getElementById('entry_date').value;
        var supplier_name = document.getElementById('supplier_name').value;
        var address = document.getElementById('address').value;
        var text_address = document.getElementById('text_address').value;
        var bill_no = document.getElementById('bill_no').value;
        var hostel_name = document.getElementById('hostel_name').value;
        var csrf_token = document.getElementById('csrf_token').value;
        var stock_id = document.getElementById('stock_id').value;
        var discount = document.getElementById('discount').value;
        var expense = document.getElementById('expense').value;
        var gst = document.getElementById('gst').value;
        var fssai_no = document.getElementById('fssai_no').value;
        var purchase_item = document.getElementById('purchase_item').value;
        var veg_item = document.getElementById('veg_item').value;

        var net_total_amount = document.getElementById('net_total_amount').value;
        var tot_qty = document.getElementById('tot_qty').value;
        var tot_amount = document.getElementById('total_amount').value;
        var fssai_no = document.getElementById('fssai_no').value;
        var purchase_item = document.getElementById('purchase_item').value;
        var veg_item = document.getElementById('veg_item').value;

        var district = document.getElementById('district').value;
        var taluk = document.getElementById('taluk').value;

        var unique_ids = document.getElementById('unique_id').value;

        var file_name = document.getElementById('file_name').value;
        var test_file = document.getElementById('test_file').value;
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
        if (file) {
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
        data.append("text_address", text_address);
        data.append("bill_no", bill_no);
        data.append("hostel_name", hostel_name);
        data.append("csrf_token", csrf_token);
        data.append("stock_id", stock_id);
        data.append("discount", discount);
        data.append("expense", expense);
        data.append("gst", gst);
        data.append("fssai_no", fssai_no);
        data.append("purchase_item", purchase_item);
        data.append("veg_item", veg_item);

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

        if ((test_file != '' || file_name != '') && entry_date != '' && stock_id != '') {
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
        var academic_year = document.getElementById('academic_year').value;

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
        data.append("academic_year", academic_year);

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
<script>

    function category_change_ready() {
        // Get the value of the selected purchase item
        var purchaseItem = document.getElementById('purchase_item').value;

        // Get references to the elements
        var supplierDiv = document.getElementById('supplier_div');
        var textboxDiv = document.getElementById('textbox_div');
        var addressField = document.getElementById('address');

        supplierDiv.style.display = 'none';
        textboxDiv.style.display = 'block';
        addressField.readOnly = false;

        // Get the AJAX URL from session storage
        var ajax_url = sessionStorage.getItem("folder_crud_link");

        // Check if purchase item has a value
        if (purchaseItem) {
            var data = {
                "purchase_item": purchaseItem,
                "action": "product_supply"
            };

            // Perform AJAX request
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function (response) {
                    // Handle the AJAX response
           

                    if (response) {
                        $("#item_name").html(response);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX request failed:", status, error);
                }
            });
        }
    }
    function category_change() {

        // Get the value of the selected purchase item
        var purchaseItem = document.getElementById('purchase_item').value;

        // Get references to the elements
        var supplierDiv = document.getElementById('supplier_div');
        var textboxDiv = document.getElementById('textbox_div');
        var addressField = document.getElementById('address');
        var text_addressField = document.getElementById('text_address');
        var supplier_name = document.getElementById('supplier_name');
        var veg_item = document.getElementById('veg_item');

        supplierDiv.style.display = 'none';
            textboxDiv.style.display = 'block';
            addressField.style.display = 'none';
            text_addressField.style.display = 'block';

            addressField.value = '';
            supplier_name.value = '';

        // Get the AJAX URL from session storage
        var ajax_url = sessionStorage.getItem("folder_crud_link");

        // Check if purchase item has a value
        if (purchaseItem) {
            var data = {
                "purchase_item": purchaseItem,
                "action": "product_supply"
            };

            // Perform AJAX request
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function (response) {
                    // Handle the AJAX response
                     $("#month_indentlabel").text('');
            $("#month_indent").val('');
                    if (response) {
                        $("#item_name").html(response);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX request failed:", status, error);
                }
            });
        }
    }
</script>