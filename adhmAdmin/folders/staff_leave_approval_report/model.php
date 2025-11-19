<head>
    <style>
        text-align1.row.mt-2 {
            .text-align: left;
        }
    </style>
</head>

<?php


// Form variables
$btn_text = "Save";
$btn_action = "create";

$unique_id = "";
$infrastructure_types = "";
$description = "";
$is_active = 1;
// $_SESSION['user_id'] ="";

$ses_staff_id = $_SESSION['staff_id'];
$user_name = $_SESSION["user_name"];
$ses_hostel_id = $_SESSION['hostel_id'];
$ses_hostel_name = $_SESSION['hostel_name'];
$hostel_district = $_SESSION["district_name"];
$ses_district_id = $_SESSION["district_id"];
$ses_academic_year = $_SESSION['academic_year'];
$ses_hostel_taluk = $_SESSION["hostel_taluk"];
$ses_taluk_id = $_SESSION['taluk_id'];

// print_R($userid);

if (isset ($_GET["unique_id"])) {
    if (!empty ($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "staff_leave_application";

        $columns = [
            "reason",
            "staff_id",
            "staff_name",
            "(select district_name from district_name where district_name.unique_id=staff_leave_application.district_name) as district_name",
            "(select taluk_name from taluk_creation where taluk_creation.unique_id = staff_leave_application.taluk_name)as taluk_name",
            "(select hostel_name from hostel_name where hostel_name.unique_id = staff_leave_application.hostel_name)as hostel_name",
            "(select amc_year from academic_year_creation where academic_year_creation.unique_id = staff_leave_application.academic_year)as academic_year",
            "from_date",
            "to_date",
            "no_of_days",
            "is_active"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $from_date = $result_values[0]["from_date"];
            $staff_id = $result_values[0]["staff_id"];
            $staff_name = $result_values[0]["staff_name"];
            $district_name = $result_values[0]["district_name"];
            $taluk_name = $result_values[0]["taluk_name"];
            $hostel_name = $result_values[0]["hostel_name"];
            $academic_year = $result_values[0]["academic_year"];
            $to_date = $result_values[0]["to_date"];
            $reason = $result_values[0]["reason"];
            $is_active = $result_values[0]["is_active"];



            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$active_status_options = active_status($is_active);

?>




<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Staff Leave Application</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <form class="was-validated" autocomplete="off">
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 fm">
                                            <label for="staff_id" class="form-label">Staff Id: <?= $staff_id; ?></label>
                                            <input type="hidden" id="staff_id" name="staff_id" value="<?php echo $staff_id; ?>"></input>
                                        </div>
                                        <div class="col-md-4 fm">
                                        <label for="staff_name" class="form-label">Staff Name: <?php echo $staff_name; ?></label>
                                            <input type="hidden" id="staff_name" name="staff_name" value="<?php echo $staff_name; ?>"></input>
                                        </div>
                                        <div class="col-md-4 fm">
                                            <label for="academic_year" class="form-label">Academic Year: <?= $academic_year; ?></label>
                                            <input type="hidden" id="academic_year" name="academic_year" value="<?php echo $academic_year; ?>"></input>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-md-4 fm">
                                        <label for="district_id" class="form-label">District Name:  <?php echo $district_name; ?></label>
                                            <input type="hidden" id="district_id" name="district_id" value="<?php echo $district_name; ?>"></input>
                                        </div>
                                        <div class="col-md-4 fm">
                                        <label for="taluk_id" class="form-label">Taluk Name:  <?php echo $taluk_name; ?></label>
                                            <input type="hidden" id="taluk_id" name="taluk_id" value="<?php echo $taluk_name; ?>"></input>
                                        </div>
                                        <div class="col-md-4 fm">
                                        <label for="hostel_id" class="form-label">Hostel Name:  <?php echo $hostel_name; ?></label>
                                            <input type="hidden" id="hostel_id" name="hostel_id" value="<?php echo $hostel_name; ?>"></input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <label for="example-select" class="form-label">From Date</label>
                                            <input type="date" id="from_date" name="from_date" class="form-control"
                                                value="<?= $from_date;?>" required></input>
                                        </div>
                                        <div class="col-4">
                                            <label for="example-select" class="form-label">To Date</label>
                                            <input type="date" id="to_date" name="to_date" class="form-control"
                                                value="<?= $to_date;?>" required></input>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <label for="example-select" class="form-label">No Of Days</label>
                                        <input type="text" id="no_of_days" name="no_of_days" class="form-control"
                                            value="" required></input>
                                    </div>
                                    <div class="col-9 mt-3">
                                        <label for="example-select" class="form-label">Reason</label>
                                        <textarea type="text" id="reason" name="reason" class="form-control"
                                            value="<?php echo $reason ?>" rows="4" cols="360"
                                            required><?php echo $reason ?></textarea>
                                        <input type="hidden" id="status" name="status" class="form-control"
                                            value="1"></input>
                                    </div>
                                    <div class="col-3">
                                        <input type="hidden" id="district_name" value="">
                                        <input type="hidden" id="taluk_name" value="">
                                    </div>
                                    <div class="btns">
                                        <?php echo btn_cancel($btn_cancel); ?>
                                        <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<script>

    function staff_leave_application_cu(unique_id = "") {

        var internet_status = is_online();

        if (!internet_status) {
            sweetalert("no_internet");
            return false;
        }

        var is_form = form_validity_check("was-validated");

        if (is_form) {

            var data = $(".was-validated").serialize();
            data += "&unique_id=" + unique_id + "&action=createupdate";

            var ajax_url = sessionStorage.getItem("folder_crud_link");
            var url = sessionStorage.getItem("list_link");

            // console.log(data);
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                beforeSend: function () {
                    $(".createupdate_btn").attr("disabled", "disabled");
                    $(".createupdate_btn").text("Loading...");
                },
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


        } else {
            sweetalert("form_alert");
        }
    }





    function calculateDays() {
        var fromDate = new Date(document.getElementById('from_date').value);
        var toDate = new Date(document.getElementById('to_date').value);
        var diffTime = toDate.getTime() - fromDate.getTime();
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        // If from_date and to_date are same, set no_of_days to 1, otherwise calculate the difference
        document.getElementById('no_of_days').value = (diffDays >= 0) ? diffDays + 1 : diffDays;
    }

    // Add event listeners to both date inputs to trigger the calculation when they change
    document.getElementById('from_date').addEventListener('change', calculateDays);
    document.getElementById('to_date').addEventListener('change', calculateDays);

    // Initially calculate days when the page loads
    calculateDays();

    // Add event listeners to the date inputs to trigger calculation on change
    document.getElementById("from_date").addEventListener("change", calculateDays);
    document.getElementById("to_date").addEventListener("change", calculateDays);





</script>