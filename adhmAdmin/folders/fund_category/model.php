<?php
// Form variables
$btn_text = 'Save';
$btn_action = 'create';

$unique_id = '';
$company_name = '';
$company_address = '';
$mobile_num = '';
$email_id = '';
$fund_category = '';
$cost_category = '';
$is_active = 1;

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        $uni_dec = str_replace(' ', '+', $_GET['unique_id']);

        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            'unique_id' => $unique_id,
        ];

        $table = 'fund_category_creation';

        $columns = [
            'company_name',
            'company_address',
            'mobile_num',
            'email_id',
            'fund_category',
            'cost_category',
            'is_active',
        ];

        $table_details = [
            $table,
            $columns,
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {
            $result_values = $result_values->data;

            $company_name = $result_values[0]['company_name'];
            $company_address = $result_values[0]['company_address'];
            $mobile_num = $result_values[0]['mobile_num'];
            $email_id = $result_values[0]['email_id'];
            $fund_category = $result_values[0]['fund_category'];
            $cost_category = $result_values[0]['cost_category'];
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
$fund_category_options = fund_category();
$fund_category_options = select_option($fund_category_options, 'Select Fund Name', $fund_category);

$active_status_options = active_status($is_active);
?>
<!-- Modal with form -->

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Fund Category</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="was-validated" autocomplete="off">
                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                <div class="row">
                                    <div class="col-md-3">

                                        <label for="company_name" class="form-label">Company Name</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name"
                                        oninput="validateCharInput(this)"       value="<?php echo $company_name; ?>" required>
                                    </div>
                                    <div class="col-md-3">

                                        <label for="company_address" class="form-label">Company Address</label>
                                        <input type="text" class="form-control" id="company_address"
                                        oninput="validateCharInput(this)"           name="company_address" value="<?php echo $company_address; ?>" required>
                                    </div>
                                    <div class="col-md-3">

                                        <label for="mobile_num" class="form-label">Phone no</label>
                                        <input type="text" class="form-control" id="mobile_num" name="mobile_num"
                                        oninput="valid_mobile_number(this)"        minlength="10" maxlength="10" oninput="number_only(this)" value="<?php echo $mobile_num; ?>" required>
                                    </div>

                                    <div class="col-md-3">

                                        <label for="email_id" class="form-label">Email id</label>
                                        <input type="email" class="form-control" id="email_id" name="email_id" oninput="validateCharInput(this)" 
                                            value="<?php echo $email_id; ?>" required>
                                    </div>


                                    <div class="col-md-3 fm mt-2">
                                        <label for="fund_category" class="form-label">Fund category</label>
                                        <select name="fund_cat" id="fund_cat" class="select2 form-control" required>
                                            <?php echo $fund_category_options; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mt-2">

                                        <label for="cost_category" class="form-label">Cost category</label>
                                        <input type="text" class="form-control" id="cost_category" name="cost_category"
                                        oninput="validateCharInput(this)"       value="<?php echo $cost_category; ?>" required>
                                    </div>




                                    <div class="col-md-3 mt-3">
                                       
                                            <label>Status</label>
                                            <select name="is_active" id="is_active" class="select2 form-control"
                                                required>
                                                <?php echo $active_status_options; ?>
                                            </select>
                                        
                                    </div>

                                    <div class="btns">
                                        <?php echo btn_cancel($btn_cancel); ?>
                                        <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>

                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>