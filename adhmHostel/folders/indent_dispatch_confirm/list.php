<?php

$district_unique_id = $_SESSION["district_id"];
$taluk_unique_id = $_SESSION['taluk_id'];
$hostel_unique_id = $_SESSION['hostel_id'];
$academic_year = $_SESSION['academic_year'];

$academic_year_options = academic_year();
$academic_year_options = select_option_acc($academic_year_options);

$district_name_list = district_name($district_unique_id);
$district_name_list = select_option($district_name_list, "Select District", $district_unique_id);

$taluk_name_list = taluk_name($taluk_unique_id);
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_unique_id);

$hostel_name_list = hostel_name($hostel_unique_id);
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_unique_id);

$current_month = date('%M,%Y');

// print_r($current_month);die();
?>

<style>
    .disabled-select {
        pointer-events: none;
        background-color: #f5f5f5;
        /* or any other color to indicate it's disabled */
        color: #999;
        /* or any other color to indicate it's disabled */
    }

    .load {
        text-align: center;
        position: absolute;
        top: 17%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;

    }

    i.mdi.mdi-loading.mdi-spin {
        font-size: 75px;
        color: #17a8df;
    }

    .dt-buttons.btn-group.flex-wrap {
        display: none;
    }

    .modal-content {
        width: 100% !important;
        margin: 0px !important;
    }

    /* status text colours (no full-row coloring) */
    .status-text-approved {
        color: #198754;
        font-weight: 600;
    }

    /* green */
    .status-text-returned {
        color: #d39e00;
        font-weight: 600;
    }

    /* orange */
    .status-text-notreceived {
        color: #dc3545;
        font-weight: 600;
    }

    /* red */

    /* inline remarks area styling */
    .inline-action-area {
        margin-top: 6px;
    }

    .inline-action-area textarea.inline-remarks {
        width: 100%;
        min-height: 60px;
        resize: vertical;
        margin-bottom: 6px;
    }

    /* small buttons inside action area */
    .small-btn {
        padding: 4px 8px;
        font-size: 12px;
        margin-right: 6px;
    }
</style>
<?php
$screen_unique_id = unique_id($prefix);

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

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Indent Dispatch Confirm</h4>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form class="was-validated" autocomplete="off">

                        <div class="row">
                            <div class="col-md-3 mb-3" style="display:none">
                                <label for="example-select" class="form-label">Academic Year</label>
                                <select class="form-select disabled-select" name="academic_year" id="academic_year">
                                    <?php echo $academic_year_options; ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3" style="display:none">
                                <label for="example-select" class="form-label">District</label>
                                <select class="form-select select2" name="district" id="district"
                                    onchange="get_taluk()">
                                    <?php echo $district_name_list; ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3" style="display:none">
                                <label for="example-select" class="form-label">Taluk</label>
                                <select class="form-select select2" name="taluk" id="taluk" onchange="get_hostel()">
                                    <?php echo $taluk_name_list; ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3" style="display:none">
                                <label for="example-select" class="form-label">Hostel</label>
                                <select class="form-select select2" name="hostel" id="hostel">
                                    <?php echo $hostel_name_list; ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="example-select" class="form-label">Month</label>
                                <input type="hidden" id="screen_unique_id" value="<?php echo $screen_unique_id; ?>">
                                <input type="month" id="month_fill" name="month_fill" class="form-control"
                                    value="<?php echo date('Y-m'); ?>">
                            </div>

                            <div class="col-md-2 mb-3">
                                <form class="d-flex">
                                    <buttont type="button" class="btn btn-primary" style="margin-top: 28px;"
                                        onclick="stock_report_filter()">Go</button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12 load" id="loader">
                                    <i class="mdi mdi-loading mdi-spin"></i>
                                </div>
                            </div>
                            <table id="indent_confirm_datatable" class="table dt-responsive nowrap w-100 mt-3">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Date</th>
                                        <th>Hostel ID</th>
                                        <th>Hostel Name</th>
                                        <th>Month</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Item details -->
<div class="modal fade" id="itemDetails" tabindex="-1" aria-labelledby="itemDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content width">
            <div class="modal-header">
                <h5 class="modal-title">Indent Dispatch Confirm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hostel ID</label><br>
                        <label class="form-label" id="lab_hostel_id"></label>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Month</label><br>
                        <label class="form-label" id="lab_month"></label>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Hostel Name</label><br>
                    <label class="form-label" id="lab_hostel_name"></label>
                </div>

                <input type="hidden" id="stock_id" name="stock_id" class="form-control" value='<?php echo $stock_ids ?: stock_id(); ?>'>

                <div class="col-md-12">
                    <div class="alert alert-info mb-3">
                        <strong>Instructions:</strong>
                        <div class="mt-1">
                            <span class="badge bg-success">Accepted</span> — Item received &amp; approved. No
                            remarks required.<br>
                            <span class="badge bg-warning text-dark">Returned</span> — Item received but
                            returned. <strong>Remarks required</strong>.<br>
                            <span class="badge bg-danger">Not Received</span> — Item not received.
                            <strong>Remarks required</strong>.
                        </div>
                    </div>
                    <table id="item_deatils_table" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Received Qty</th>
                                <th style="min-width: 110px;">Status</th>
                                <!-- <th style="min-width: 120px;">Remarks</th> -->
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-danger" id="generatePDF">Generate PDF</button> -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>