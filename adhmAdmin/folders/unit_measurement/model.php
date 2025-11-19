<?php
// Form variables
$btn_text = 'Save';
$btn_action = 'create';

$unique_id = '';
$unit_measurement = '';
$is_active = 1;

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        $uni_dec = str_replace(' ', '+', $_GET['unique_id']);

        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            'unique_id' => $unique_id,
        ];

        $table = 'unit_measurement';

        $columns = [
            'unit_measurement',
            // "description",
            'is_active',
        ];

        $table_details = [
            $table,
            $columns,
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {
            $result_values = $result_values->data;

            $unit_measurement = $result_values[0]['unit_measurement'];
            // $description        = $result_values[0]["description"];
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

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Unit Measurement Form</h4>
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
                                <form class="was-validated" autocomplete="off" >
                                <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label>Unit Name</label>
                                                    <input type="text"  oninput="validateCharInput(this)"  class="form-control" id="unit_measurement"
                                                        name="unit_measurement" value="<?php echo $unit_measurement; ?>" required>
                                                </div>
                                            </div>

                                            <!-- <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label>Description</label>
                                                    <input type="text" class="form-control" id="description"
                                                        name="description" value="<?php echo $description; ?>" required>
                                                </div>
                                            </div> -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <select name="is_active" id="is_active" class="select2 form-control"
                                                        required>
                                                        <?php echo $active_status_options; ?>
                                                    </select>
                                                </div>
                                            </div>
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
            </div>
        </div>
    </div>
</div>