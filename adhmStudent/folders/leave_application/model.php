<head>
    <style>
        text-align1.row.mt-2 {
    .text-align: left;
}
label.form-label {
    font-weight: 400;
}
    </style>
</head>

<?php
// Form variables
$btn_text = 'Save';
$btn_action = 'create';

$unique_id = '';
$infrastructure_types = '';
$description = '';
$is_active = 1;
// $_SESSION['user_id'] ="";

// $std_reg_no = $_SESSION['std_reg_no'];
$ses_user_name = $_SESSION['std_name'];

$ses_district = $_SESSION['hostel_district_id'];
$ses_hostel = $_SESSION['hostel_name_id'];
$ses_acc_year = $_SESSION['acc_year'];
$ses_hostel_taluk = $_SESSION['hostel_taluk_id'];
$ses_std_reg_no = $_SESSION['std_reg_no'];

$ses_academic_year = $_SESSION['academic_year'];

$hostel_main_id = $_SESSION['hostel_name'];
$from_date = date('Y-m-d');
$to_date = date('Y-m-d');

// print_r($ses_std_reg_no);

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        // $unique_id = $_GET["unique_id"];

        $uni_dec = str_replace(' ', '+', $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            'unique_id' => $unique_id,
        ];

        $table = 'leave_application';

        $columns = [
            'reason',
            'from_date',
            'to_date',
            'no_of_days',
            'is_active',
        ];

        $table_details = [
            $table,
            $columns,
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result);

        if ($result_values->status) {
            $result_values = $result_values->data;

            $from_date = $result_values[0]['from_date'];
            $to_date = $result_values[0]['to_date'];
            $reason = $result_values[0]['reason'];
            $is_active = $result_values[0]['is_active'];

            $btn_text = 'Update';
            $btn_action = 'update';
        } else {
            $btn_text = 'Error';
            $btn_action = 'error';
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$active_status_options = active_status($is_active); ?>



<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Student Leave Application</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <form class="was-validated" autocomplete="off">
                    <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">



                        <div class="row">



                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 fm">
                                            <div class="col-md-6 fm">
                                                <label for="student_id" class="form-label">Student Id: </label>
                                                <input type="hidden" id="student_id" name="student_id" value="<?php echo $ses_std_reg_no; ?>"></input>
                                                <label for="" value=""><?php echo $ses_std_reg_no; ?></label>
                                            </div>
                                            <div class="col-md-6 fm">
                                                <label for="student_id" class="form-label">District Name: </label>
                                                <input type="hidden" id="district_id" name="district_id" value="<?php echo $ses_district; ?>"></input>
                                                <label for="" value=""><?php echo district_name($ses_district)[0]['district_name']; ?></label>
                                            </div>
                                            <div class="col-md-12 fm">
                                                <label for="hostel_id" class="form-label">Hostel Name: </label>
                                                <input type="hidden" id="hostel_id" name="hostel_id" value="<?php echo $ses_hostel; ?>"> </input>
                                                <label for="" value=""><?php echo hostel_name($_SESSION['hostel_name'])[0]['hostel_name']; ?></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 fm">
                                            <div class="col-md-6 fm">
                                            <label for="student_name" class="form-label">Student Name: </label>

                                                <input type="hidden" id="student_name" name="student_name" value="<?php echo $ses_user_name; ?>"></input>
                                                <label for="" id="student_name" name="student_name" value=""><?php echo $ses_user_name; ?> </label>
                                            </div>
                                            <div class="col-md-6 fm">
                                                <label for="student_id" class="form-label">Taluk Name: </label>
                                                <input type="hidden" id="taluk_id" name="taluk_id" value="<?php echo $ses_hostel_taluk; ?>"></input>
                                                <label for="" value=""><?php echo taluk_name($ses_hostel_taluk)[0]['taluk_name']; ?></label>
                                            </div>
                                            <div class="col-md-6 fm">
                                                <label for="student_id" class="form-label">Academic Year: </label>
                                                <input type="hidden" id="academic_year" name="academic_year" value="<?php echo $ses_academic_year; ?>"></input>
                                                <label for="" value=""><?php echo academic_year($_SESSION['academic_year'])[0]['amc_year']; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
    <div class="col-4 mb-2">
        <label for="from_date" class="form-label">From Date</label>
        <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo $from_date; ?>" required>
    </div>
    <div class="col-4 mb-2">
        <label for="to_date" class="form-label">To Date</label>
        <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo $to_date; ?>" required>
    </div>
</div>

                                       
                                        <div class="col-4 mb-2">
                                            <label for="example-select" class="form-label">No Of Days</label>
                                            <input type="text" id="no_of_days" readonly name="no_of_days" class="form-control" value="" required></input>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <label for="example-select" class="form-label">Reason</label>
                                            <textarea type="text" id="reason" name="reason" class="form-control" oninput="description_val(this)" value="<?php echo $reason; ?>" rows="4" cols="360" required ><?php echo $reason; ?></textarea>
                                        
                                            <input type="hidden" id="status" name="status" class="form-control" value="1"></input>
                                        
                                        </div>
                                        </div>
                                        
                                        <!-- <div  class="col-md-3 fm">
                                            <label type="hidden" class="form-label">Status</label>
                                            <select type="hidden" name="is_active" id="is_active" class="select2 form-control" required>
                                                <?php echo $active_status_options; ?>
                                            </select>
                                        </div> -->
                                        <div class="col-3">
                                            <input type="hidden" id="district_name" value="">
                                            <input type="hidden" id="taluk_name" value="">
                                        </div>

                                    </div>
                                    <div class="btns  mb-3">

                                        <?php echo btn_cancel($btn_cancel); ?>
                                        <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>

                                    </div>

                    </form>


                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col -->
    <script>
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
        
        //less than from date alert

        document.getElementById('to_date').addEventListener('change', function() {
        var fromDate = new Date(document.getElementById('from_date').value);
        var toDate = new Date(this.value);

        if (toDate < fromDate) {
            alert('To Date must be greater than or equal to From Date');
            this.value = ''; // Clear the invalid date
        }
    });

    </script>