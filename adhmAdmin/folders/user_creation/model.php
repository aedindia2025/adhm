<?php

// Form variables
$btn_text = 'Save';
$btn_action = 'create';

$unique_id = '';
$user_type = '';
$user_name = '';
$branch_name = '';
$warehouse_name = '';
$staff_name = '';
$password = '';
$conform_password = '';
$is_active = 1;
$warehouse_options = '';
if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        $uni_dec = str_replace(' ', '+', $_GET['unique_id']);

        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            'unique_id' => $unique_id,
        ];

        $table = 'user_creation';

        $columns = [
            'user_type',
            'user_name',
            'staff_name',
            'password',
            'conform_password',
            'branch',
            'warehouse',
            'is_active',
        ];

        $table_details = [
            $table,
            $columns,
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {
            $result_values = $result_values->data;

            $user_type = $result_values[0]['user_type'];
            $user_name = $result_values[0]['user_name'];
            $staff_name = $result_values[0]['staff_name'];
            $password = $result_values[0]['password'];
            $conform_password = $result_values[0]['conform_password'];
            $branch_name = $result_values[0]['branch'];
            $warehouse_name = $result_values[0]['warehouse'];
            $is_active = $result_values[0]['is_active'];

            $warehouse_options = state('', $branch_name);
            $warehouse_options = select_option($warehouse_options, 'Select Warehouse', $warehouse_name);

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
$user_type_options = user_type();
$user_type_options = select_option($user_type_options, 'Select User Type', $user_type);

$staff_options = staff_name();
$staff_options = select_option($staff_options, 'Select Staff', $staff_name);

$branch_options = branch();
$branch_options = select_option($branch_options, 'Select Branch', $branch_name);

?>            
<!-- Modal with form -->
<section class="section">
    <div class="section-header">
        <h1>User Creation</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="#">Admin</a></div>
            <div class="breadcrumb-item">User Creation</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="was-validated"  autocomplete="off" >
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>User Type </label>
                                <select class="form-control select2" id = "user_type" name = "user_type" required>
                                  <?php echo $user_type_options; ?>
                                </select>
                              </div> 
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>User Name </label>
                                <input type="text" class="form-control" id = "user_name" name = "user_name" oninput="validateCharInput(this)" value = "<?php echo $user_name; ?>" required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Staff Name  </label>
                                <select name="staff_name" id="staff_name"  class="select2 form-control" required>
                                    <?php echo $staff_options; ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Password  </label>
                                <input type="text" class="form-control" id = "password" name = "password"  value = "<?php echo $password; ?>" required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Branch Name  </label>
                                <select name="branch" id="branch" onchange = "get_warehouse_name()" class="select2 form-control" required>
                                    <?php echo $branch_options; ?>
                                </select>

                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Warehouse Name  </label>
                                <select name="warehouse" id="warehouse"  class="select2 form-control" required>
                                    <?php echo $warehouse_options; ?>
                                </select>
                                <input type="hidden" name="edit_warehouse" id="edit_warehouse" value="" >
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Status</label>
                                <select name="is_active" id="is_active" class="select2 form-control" required>
                                  <?php echo $active_status_options; ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Conform Password </label>
                                <input type="text" class="form-control" id = "conform_password" name = "conform_password" oninput="validateCharInput(this)" value = "<?php echo $conform_password; ?>" required>
                              </div>
                            </div>
                          </div>
                          <?php echo btn_cancel($btn_cancel); ?>
                          <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>