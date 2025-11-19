<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/> -->


<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Form variables
$btn_text = 'Save';
$btn_action = 'create';

$current_date = today();

$unique_id = '';
$from_year = '';
$to_year = '';
$is_active = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('Invalid CSRF token');
    }
}

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        // $unique_id = $_GET["unique_id"];
        $uni_dec = str_replace(' ', '+', $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        $where = [
            'unique_id' => $unique_id,
        ];

        $table = 'application_download';

        $columns = [
            'cur_date',
            'validate_date',
            'application_name',
            'file_name',
            'description',
            'unique_id',
            'is_active',
        ];

        $table_details = [
            $table,
            $columns,
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);

        if ($result_values->status) {
            $result_values = $result_values->data;

            $current_date = $result_values[0]['cur_date'];
            $validate_date = $result_values[0]['validate_date'];
            $application_name = $result_values[0]['application_name'];
            $file_name = $result_values[0]['file_name'];
            $unique_id = $result_values[0]['unique_id'];
            $description = $result_values[0]['description'];
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

                        <h4 class="page-title">Application Download</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <form class="was-validated" autocomplete="off">


                        <div class="row">

                            <div class="">
                                <div class="card">
                                    <div class="card-body">


                                        <div class="row mb-3">
                                            <div class="col-md-3 fm">
                                                <label for="product_category" class="form-label">Entry Date</label>

                                                <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                                <input type="date" class="form-control" name="current_date" id="current_date" required value=<?php echo $current_date; ?>>

                                                <input type="hidden" name="unique_id" id="unique_id" value=<?php echo $unique_id; ?>>


                                            </div>

                                            <div class="col-md-3 fm">
                                                <label for="product_category" class="form-label">Valid Date</label>


                                                <input type="date" class="form-control" name="validate_date" id="validate_date" onchange="valid_date()" required value="<?php echo $validate_date; ?>">
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="product_category" class="form-label">Application Name</label>


                                                <input type="text" class="form-control" name="application_name" id="application_name"  oninput="validateCharInput(this)"  required value="<?php echo $application_name; ?>">
                                            </div>

                                            <div class="col-md-3 fm">
                                                <label for="product_category" class="form-label">File Upload</label>


                                                <input type="file" id="test_file" name="test_file" class="form-control" accept=".pdf, .doc, .docx, image/*" required>
                                                <input type="hidden" class="form-control" id="file_name" name="file_name" value="<?php echo $file_name; ?>">
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-3 fm">
                                                <label for="product_category" class="form-label">Description</label>
                                                <textarea name="description" id="description" class="form-control"  oninput="validateCharInput(this)"  required rows="2" cols="50"><?php echo $description; ?></textarea>

                                            </div>

                    </form>




                    <div class="col-md-3 fm">
                        <label class="form-label">Status</label>
                        <select name="is_active" id="is_active" class="select2 form-control" required>
                            <?php echo $active_status_options; ?>
                        </select>

                    </div>

                </div>

            </div>
            <div class="btns">

                <?php echo btn_cancel($btn_cancel); ?>
                <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>

            </div>


        </div>

    </div>
</div> <!-- end card-body -->
</div> <!-- end card-->
</div> <!-- end col -->