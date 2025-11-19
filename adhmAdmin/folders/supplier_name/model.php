<?php
// Form variables
$btn_text = 'Save';
$btn_action = 'create';

$unique_id = '';
$supplier_name = '';
$is_active = 1;

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        $uni_dec = str_replace(' ', '+', $_GET['unique_id']);

        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            'unique_id' => $unique_id,
        ];

        $table = 'supplier_name_creation';

        $columns = [
            'supplier_name',
            'mobile_number',
            'email_id',
            'city',
            'gst_no',
            'pan_number',
            'building_no',
            'street',
            'area',
            'pincode',
            'bank_name',
            'account_num',
            'ifsc_code',
            'bank_address',
            'is_active',
        ];

        $table_details = [
            $table,
            $columns,
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {
            $result_values = $result_values->data;

            $supplier_name = $result_values[0]['supplier_name'];
            $mobile_number = $result_values[0]['mobile_number'];
            $email_id = $result_values[0]['email_id'];
            $city = $result_values[0]['city'];
            $gst_no = $result_values[0]['gst_no'];
            $pan_number = $result_values[0]['pan_number'];
            $building_no = $result_values[0]['building_no'];
            $street = $result_values[0]['street'];
            $area = $result_values[0]['area'];
            $pincode = $result_values[0]['pincode'];
            $bank_name = $result_values[0]['bank_name'];
            $account_num = $result_values[0]['account_num'];
            $ifsc_code = $result_values[0]['ifsc_code'];
            $bank_address = $result_values[0]['bank_address'];

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

$active_status_options = active_status($is_active);
?>
<!-- Modal with form -->

<style>
    .col-md-4 {
        margin-bottom: 20px;
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

                        <h4 class="page-title">Supplier Form</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <form class="was-validated" autocomplete="off">
                                    <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <div id="basicwizard">
                                            <ul class="nav nav-pills nav-justified form-wizard-header mb-4">
                                                <li class="nav-item ">
                                                    <a href="#basictab1" data-bs-toggle="tab" data-toggle="tab"
                                                        class="nav-link rounded-0 py-2">
                                                        <i class="mdi mdi-account-circle font-18 align-middle me-1"></i>
                                                        <span class="d-none d-sm-inline">Personal Details</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#basictab2" data-bs-toggle="tab" data-toggle="tab"
                                                        class="nav-link rounded-0 py-2">
                                                        <i class=" uil-lock-access font-18 align-middle me-1"></i>
                                                        <span class="d-none d-sm-inline">Address Details</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#basictab3" data-bs-toggle="tab" data-toggle="tab"
                                                        class="nav-link rounded-0 py-2">
                                                        <i class="  uil-store-alt font-18 align-middle me-1"></i>
                                                        <span class="d-none d-sm-inline">Bank Details</span>
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content b-0 mb-0">
                                                <div class="tab-pane" id="basictab1">
                                                    <div class="">
                                                        <div class="">
                                                            <div class="row mb-3">
                                                                <div class="col-md-4 fm">
                                                                    <label>Supplier Name</label>
                                                                    <input type="text" class="form-control"
                                                                        id="supplier_name" name="supplier_name"
                                                                        oninput="valid_user_name(this)"          value="<?php echo $supplier_name; ?>" required>
                                                                </div>
                                                                <div class="col-md-4 fm">
                                                                    <label>Mobile No</label>
                                                                    <input type="text" class="form-control"
                                                                        id="mobile_number" name="mobile_number" oninput="valid_mobile_number(this)" 
                                                                        minlength="10" maxlength="10"
                                                                        value="<?php echo $mobile_number; ?>" required>
                                                                </div>
                                                                <div class="col-md-4 fm">
                                                                    <label>GST No</label>
                                                                    <input type="text" class="form-control" id="gst_no" oninput="validateCharInput(this)" 
                                                                        name="gst_no" value="<?php echo $gst_no; ?>" required>
                                                                </div>
                                                                <div class="col-md-4 fm">
                                                                    <label>Email id</label>
                                                                    <input type="email" class="form-control"
                                                                        id="email_id" name="email_id" oninput="validateCharInput(this)" 
                                                                        value="<?php echo $email_id; ?>" required>
                                                                </div>

                                                                <div class="col-md-4 fm">
                                                                    <label>PAN Number</label>
                                                                    <input type="text" class="form-control"
                                                                        id="pan_number" name="pan_number" oninput="validateCharInput(this)" 
                                                                        value="<?php echo $pan_number; ?>" required>
                                                                </div>

                                                            </div>



                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->

                                                    <ul class="list-inline wizard mb-0">
                                                        <li class="next list-inline-item float-end">
                                                            <a href="javascript:void(0);" class="btn btn-info">Next<i
                                                                    class="mdi mdi-arrow-right ms-1"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="tab-pane" id="basictab2">
                                                    <div class="row mb-3">
                                                        <div class="col-md-4 fm">
                                                            <div class="">
                                                                <label>Building No.</label>
                                                                <input type="text" class="form-control" id="building_no" oninput="validateCharInput(this)"
                                                                    name="building_no" value="<?php echo $building_no; ?>"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 fm">
                                                            <label>Street</label>
                                                            <input type="text" class="form-control" id="street"
                                                            oninput="validateCharInput(this)"     name="street" value="<?php echo $street; ?>" required>
                                                        </div>
                                                        <div class="col-md-4 fm">
                                                            <label>Area</label>
                                                            <input type="text" class="form-control" id="area"
                                                            oninput="validateCharInput(this)"           name="area" value="<?php echo $area; ?>" required>
                                                        </div>
                                                        <div class="col-md-4 fm">
                                                            <label>City</label>
                                                            <input type="text" class="form-control" id="city"
                                                            oninput="validateCharInput(this)"    name="city" value="<?php echo $city; ?>" required>
                                                        </div>
                                                        <div class="col-md-4 fm">
                                                            <label>Pincode</label>
                                                            <input type="text" class="form-control" id="pincode"
                                                                minlength="6" maxlength="6" name="pincode"
                                                                oninput="number_only(this)"      value="<?php echo $pincode; ?>" required>
                                                        </div>


                                                    </div>

                                                    <ul class="pager wizard mb-0 list-inline">
                                                        <li class="previous list-inline-item">
                                                            <button type="button" class="btn btn-light"><i
                                                                    class="mdi mdi-arrow-left me-1"></i> Back</button>
                                                        </li>
                                                        <li class="next list-inline-item float-end">
                                                            <button type="button" class="btn btn-info">Next <i
                                                                    class="mdi mdi-arrow-right ms-1"></i></button>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="tab-pane" id="basictab3">
                                                    <div class="row mb-3">
                                                        <div class="col-md-4 fm">
                                                            <div class="mb-3">
                                                                <label>Bank Name</label>
                                                                <input type="text" class="form-control" id="bank_name"
                                                                oninput="validateCharInput(this)"           name="bank_name" value="<?php echo $bank_name; ?>"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 fm">
                                                            <div class="mb-3">
                                                                <label>A/C No.</label>
                                                                <input type="text" class="form-control" id="account_num"
                                                                oninput="validateCharInput(this)"      name="account_num" value="<?php echo $account_num; ?>"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 fm">
                                                            <div class="mb-3">
                                                                <label>IFSC Code</label>
                                                                <input type="text" class="form-control" id="ifsc_code"
                                                                oninput="validateCharInput(this)"       name="ifsc_code" value="<?php echo $ifsc_code; ?>"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 fm">
                                                            <div class="mb-3">
                                                                <label>Bank Address</label>
                                                                <input type="text" class="form-control"
                                                                oninput="validateCharInput(this)"     id="bank_address" name="bank_address"
                                                                    value="<?php echo $bank_address; ?>" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 fm">
                                                            <div class="mb-3">
                                                                <label>Status</label>
                                                                <select name="is_active" id="is_active"
                                                                    class="select2 form-control" required>
                                                                    <?php echo $active_status_options; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <ul class="pager wizard mb-0 list-inline mt-1">
                                                        <li class="previous list-inline-item">
                                                            <button type="button" class="btn btn-light"><i
                                                                    class="mdi mdi-arrow-left me-1"></i> Back</button>
                                                        </li>
                                                        <!-- <li class="next list-inline-item float-end"> -->
                                                        <!-- <button type="button" class="btn btn-info"> -->
                                                        <div class="btns">
                                                            <?php echo btn_cancel($btn_cancel); ?>
                                                            <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                                        </div>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div> <!-- tab-content -->
                                        </div> <!-- end #basicwizard-->
                                    </form>

                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->

                    </div>




                </div>
            </div>



        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Initialize the wizard with the first tab shown
        $('#basicwizard').find('.nav-link').first().addClass('active show');
        $('#basicwizard').find('.tab-pane').first().addClass('active show');

        // Next button click event handling
        $('.next').click(function () {
            var $activeTab = $('.nav-link.active');
            var $nextTab = $activeTab.parent().next().find('.nav-link');

            $activeTab.removeClass('active show');
            $nextTab.addClass('active show');

            var target = $nextTab.attr('href');
            $('.tab-content').children('.tab-pane').removeClass('active show');
            $(target).addClass('active show');
        });

        // Previous button click event handling (if needed)
        $('.previous').click(function () {
            var $activeTab = $('.nav-link.active');
            var $prevTab = $activeTab.parent().prev().find('.nav-link');

            $activeTab.removeClass('active show');
            $prevTab.addClass('active show');

            var target = $prevTab.attr('href');
            $('.tab-content').children('.tab-pane').removeClass('active show');
            $(target).addClass('active show');
        });
    });
</script>