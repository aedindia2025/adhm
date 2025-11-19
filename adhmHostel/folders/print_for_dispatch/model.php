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

$unique_id = $_SESSION['sess_user_id'];

$batch_no = batch_no();


?>
<style>
    .card-body.brd {
        border: 1px solid #ccc;
    }

    .common h4 {
        color: #000;
        margin-top: 0px;
    }

    .common label {
        font-size: 13px;
        font-weight: 500;
    }

    table#dispatch_datatable thead {
        background: #f1f1f1;
    }

    table#dispatch_datatable {
        width: 100%;
        display: block;
        overflow: scroll;
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
                        <h4 class="page-title">Print For Dispatch</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body brd">
                            <form class="was-validated">
                                <div class="row  common">
                                    <input type="hidden" id="hostel_district" value="<?php echo $_SESSION['district_id']; ?>">
                                    <input type="hidden" id="hostel_taluk" value="<?php echo $_SESSION['taluk_id']; ?>">
                                    <input type="hidden" id="hostel_name" value="<?php echo $_SESSION['hostel_id']; ?>">
                                    <!-- <input type="text" id="hostel_name" value="<?php echo $_SESSION['hostel_main_id']; ?>"> -->
                                    

                                    <div class="col-md-4 fontsize-14">

                                        <label for="academic_year">HOSTEL DISTRICT</label>
                                        <h4><?php echo $_SESSION['district_name']; ?></h4>

                                        <label for="academic_year">ENTRY DATE</label>
                                        <h4><?php echo date('Y-m-d'); ?></h4>



                                    </div>


                                    <div class="col-md-4 fontsize-14">

                                        <label for="academic_year">HOSTEL TALUK</label>
                                        <h4><?= $_SESSION['taluk_name']; ?></h4>


                                        <label for="academic_year">BATCH NO</label>
                                        <h4 id="batch"></h4>
                                        <input type="hidden" id="batch_no" value="<?= $batch_no; ?>">



                                    </div>
                                    <div class="col-md-4 fontsize-14">
                                        <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <label for="academic_year">HOSTEL NAME</label>
                                        <h4><?= $_SESSION['hostel_name']; ?></h4>



                                    </div>

                                    <div class="col-md-3">
                                        <label for="academic_year" class="form-label">Batch Type</label>
                                        <select name="apptype" id="apptype" class="select2 form-control" onchange="appilicationtype()" required>
                                            <option value="1">NEW</option>
                                            <option value="2">RENEWAL</option>
                                        </select>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <table id="dispatch_datatable" class="table nowrap w-100">
                            <thead>

                                <tr>
                                    <th>Selection</th>
                                    <th>S.no</th>
                                    <!-- <th>Batch No</th> -->
                                    <th>Name As Aadhaar</th>
                                    <th>Name As EMIS/UMIS</th>
                                    <th>Name Difference Check</th>
                                    <th>Distance From Home to School/College</th>
                                    <th>Distance From Home to Hostel</th>
                                    <th>Application No</th>
                                    <th>Applied Date</th>
                                    <!-- <th>Hostel Name</th> -->

                                </tr>
                            </thead>
                        </table><br>
                        <!-- <div class="col-12 mt-3">
                        <div class="form-group row "> -->
                        <div class="col-md-12" align="right">
                            <!-- Cancel,save and update Buttons -->
                            <?php echo btn_cancel($btn_cancel); ?>
                            <button type="button" id="createbatch" class="btn btn-primary waves-effect waves-light" onclick="batch_create()">Create Batch</button>

                        </div>
                        <!-- </div>
                    </div> -->
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<?php

function batch_no_1($academic_year)
{
    // $date = date("Y");
    // $st_date = substr($date, 4);

    $servername = "localhost";
    $username = "root";
    $password = "H_Cw3O4CM*fXcGtz";
    $database_name = "adi_dravidar";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    }

    $sql = $conn->query("SELECT amc_year FROM academic_year_creation where is_delete = '0' order by s_no desc Limit 1");
    $row = $sql->fetch();

    $acc_year = $row['amc_year'];
    $a = str_split($acc_year);
    $splt_acc_yr = $a[0] . $a[1] . $a[2] . $a[3];

    $hostel_id = $_SESSION['hostel_id'];

    $hostel_main_id = $_SESSION['hostel_main_id'];
    $splt_hos_id = substr($hostel_main_id, -3);

    $stmt = $conn->query("SELECT max(batch_no) as batch_no FROM batch_creation where is_delete = '0' and hostel_name = '" . $hostel_id . "' order by id desc limit 1");
    $last_reg_no = $stmt->fetchColumn();

    if ($last_reg_no == '') {
        $new_seq_no = 1;
    } else {
        // Extract year and sequence number from the last registration number
        $last_seq_no = intval(substr($last_reg_no, -4)); // Extract last 4 digits

        // Increment the sequence number
        $new_seq_no = $last_seq_no + 1;
    }

    // Format the new registration number
    $registration_no = $splt_acc_yr . $splt_hos_id . 'BAT' . str_pad($new_seq_no, 4, '0', STR_PAD_LEFT);

    return $registration_no;
}



function batch_no()
{
    // $date = date("Y");
    // $st_date = substr($date, 4);

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

    $stmt_acc = $conn->query("SELECT amc_year FROM academic_year_creation where is_delete = '0' order by s_no desc Limit 1");

    $value = $stmt_acc->fetch();

    //  $acmc_year = academic_year($academic_year)[0]['acc_year'];
    $a = str_split($value['amc_year']);
    $splt_acc_yr = $a[0] . $a[1] . $a[2] . $a[3];
    //  $hostel_id = $_SESSION['hostel_id']; 
    //  $host_id = hostel_name($hostel_id)[0]['hostel_id'];
    //  $splt_hos_id = substr($host_id,-3);

    $hostel_id = $_SESSION['hostel_id'];
    // $host_main_id = hostel_name($hostel_id)[0]['hostel_id'];
    $hostel_main_id = $_SESSION['hostel_main_id'];
    $splt_hos_id = $hostel_main_id;
    // $splt_hos_id = substr($hostel_main_id, 0, 4)  . substr($hostel_main_id, -2);





    // echo "SELECT * FROM apply_application_form where application_no LIKE 'APN%' order by id desc";echo "<br>";
    $stmt = $conn->query("SELECT batch_no  FROM batch_main where hostel_name = '" . $hostel_id . "' order by id desc limit 1");
    // echo "SELECT max(batch_no) as batch_no FROM batch_creation where is_delete = '0' and hostel_name = '".$hostel_id."' order by id desc";


    if ($res1 = $stmt->fetch()) {
        if ($res1['batch_no'] != '') {

            print_r($res1['batch_no']);
            $pur_array = explode("-", $res1['batch_no']);


            //  echo $pur_array[1];

            $booking_no  = $pur_array[1];
            print_r($booking_no);
        }
        // else{
        //     $booking_no  = '';
        // }

    }

    if ($booking_no == '') {
        // echo "ff";
        $booking_nos = $splt_acc_yr . $splt_hos_id . 'B-' . '0001';
    } else {
        $booking_no += 1;

        $booking_nos = $splt_acc_yr . $splt_hos_id . 'B-' . str_pad($booking_no, 4, '0', STR_PAD_LEFT);
    }

    return $booking_nos;
}
?>
<script>
    function batch_create() {

        document.getElementById('createbatch').disabled = true;

        // Get total and checked count for each type
        const totalNameChecks = document.querySelectorAll('input[id^="name_check_"]').length;
        const checkedNameChecks = document.querySelectorAll('input[id^="name_check_"]:checked').length;

        const totalInstChecks = document.querySelectorAll('input[id^="inst_check_"]').length;
        const checkedInstChecks = document.querySelectorAll('input[id^="inst_check_"]:checked').length;

        const totalHostelChecks = document.querySelectorAll('input[id^="hostel_check_"]').length;
        const checkedHostelChecks = document.querySelectorAll('input[id^="hostel_check_"]:checked').length;

        // Compare counts
        if (
            totalNameChecks !== checkedNameChecks ||
            totalInstChecks !== checkedInstChecks ||
            totalHostelChecks !== checkedHostelChecks
        ) {
            sweetalert("empty_checkbox");
            document.getElementById('createbatch').disabled = false;
            return;
        }


         const visibleSelects = Array.from(document.querySelectorAll('select.reason-select'))
            .filter(select => select.style.display !== 'none');

        // Step 2: Get unique s1_unique_ids from those selects (to count per-row)
        const uniqueS1Ids = new Set();
        visibleSelects.forEach(select => {
            const s1Id = select.getAttribute('data-s1_unique_id');
            if (s1Id) uniqueS1Ids.add(s1Id);
        });
        const visibleRowCount = uniqueS1Ids.size;
        
        // Step 3: Count how many visible select boxes have a selected reason
        const selectedReasonCount = visibleSelects.filter(select => select.value !== '').length;
        

        // ✅ Step 4: Compare
        if (parseInt(selectedReasonCount) !== parseInt(visibleRowCount)) {
           
            sweetalert("fill_all_reason");
            document.getElementById('createbatch').disabled = false;
            return;
        }

        const checked = document.querySelectorAll('.myCheck:checked');

        for (var i = 0; i < checked.length; i++) {
            var checkbox = checked[i];
            var s1_unique_id = checkbox.parentElement.querySelector('#s1_unique_id').value;
            // alert(form_unique_id);
            var applied_date = checkbox.parentElement.querySelector('#applied_date').value;
            // alert(po_num);
            var hostel_name = checkbox.parentElement.querySelector('#hostel_name').value;
            // alert(po_date);
            var hostel_taluk = checkbox.parentElement.querySelector('#hostel_taluk').value;
            // alert(po_product_name);
            var hostel_district = checkbox.parentElement.querySelector('#hostel_district').value;
            // alert(bg_month);
            var std_name = checkbox.parentElement.querySelector('#std_name').value;
            var academic_year = checkbox.parentElement.querySelector('#academic_year').value;
            var batch_no = checkbox.parentElement.querySelector('#batch_no').value;
            // alert(bg_per);
            var std_app_no = checkbox.parentElement.querySelector('#std_app_no').value;
            // var std_app_no = checkbox.parentElement.querySelector('#std_app_no').value;

            var reason = ''; // default empty
    var selects = document.querySelectorAll('select.reason-select[data-s1_unique_id="' + s1_unique_id + '"]');

    selects.forEach(function(select) {
        if (select.style.display !== 'none' && select.value !== '') {
            reason = select.value;
        }
    });

            var data = "s1_unique_id=" + s1_unique_id + "&applied_date=" + applied_date + "&hostel_name=" + hostel_name + "&hostel_taluk=" + hostel_taluk + "&hostel_district=" + hostel_district + "&std_name=" + std_name + "&std_app_no=" + std_app_no + "&academic_year=" + academic_year + "&batch_no=" + batch_no + "&reason=" + reason;
            data += "&action=batch_add";
            var ajax_url = sessionStorage.getItem("folder_crud_link");
            var url = sessionStorage.getItem("list_link");
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                beforeSend: function() {
                    $(".bg_add_update_btn").attr("disabled", "disabled");
                    $(".bg_add_update_btn").text("Loading...");
                },
                success: function(data) {
                    // document.getElementById('createbatch').disabled = false;

                    var obj = JSON.parse(data);
                    var msg = obj.msg;
                    var status = obj.status;
                    var error = obj.error;

                    if (msg == "create") {

                        // document.getElementById('createbatch').disabled = false;
                        sweetalert("batch_created", url);

                        // window.location.href = "index.php?file=print_for_dispatch/list";
                    }
                },
                error: function(data) {
                    alert("Network Error");
                }
            });


            // }else{
            //     alert("hii");
            // }
        }
        var main_data = "s1_unique_id=" + s1_unique_id + "&applied_date=" + applied_date + "&hostel_name=" + hostel_name + "&hostel_taluk=" + hostel_taluk + "&hostel_district=" + hostel_district + "&std_name=" + std_name + "&std_app_no=" + std_app_no + "&academic_year=" + academic_year + "&batch_no=" + batch_no;
        main_data += "&action=main_batch_add";

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: main_data

        });
    }
</script>