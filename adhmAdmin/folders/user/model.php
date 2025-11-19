<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Form variables
$btn_text           = "Save";
$btn_action         = "create";
$is_btn_disable     = "";

$unique_id          = "";

$name               = "";
$user_name          = "";
$password           = "";
$user_type          = "";
$is_active          = 1;
$phone_no           = "";
$address            = "";
$under_users        = "";
$exp_under_user     = "";

$is_team_head       = "";
$team_id            = "";
$exp_team_users     = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}

if (isset($_GET["unique_id"])) {
  if (!empty($_GET["unique_id"])) {

    $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
    $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

    $unique_id  = $get_uni_id;
    $where      = [
      "unique_id" => $unique_id
    ];

    $table      =  "user";

    $columns    = [
      "user_type_unique_id",
      "phone_no",
      "staff_name",
      "user_name",
      "address",
      "is_active",
      "password",
    ];

    $table_details   = [
      $table,
      $columns
    ];

    $user_values  = $pdo->select($table_details, $where);

    if ($user_values->status) {

      $user_values     = $user_values->data;

      $user_type              = $user_values[0]["user_type_unique_id"];
      $phone_no               = $user_values[0]["phone_no"];
      $user_name              = $user_values[0]["user_name"];
      $staff_name                = $user_values[0]["staff_name"];
      $password               = $user_values[0]["password"];
    //   $under_users            = $user_values[0]["under_user"];
      $is_active              = $user_values[0]["is_active"];
    //   $is_team_head           = $user_values[0]["is_team_head"];
    //   $team_id                = $user_values[0]["team_id"];
    //   $team_users             = $user_values[0]["team_members"];


    //   if ($is_team_head) {
    //     $is_team_head       = " checked ";
    //   }

    //   $exp_under_user     = explode(",", $under_users);
    //   $exp_team_users     = explode(",", $team_users);


      $btn_text           = "Update";
      $btn_action         = "update";
    } else {
      $btn_text           = "Error";
      $btn_action         = "error";
      $is_btn_disable     = "disabled='disabled'";
    }
  }
}

$user_type_options  = user_type();
$user_type_options  = select_option($user_type_options, "Select User Type", $user_type);

// $staff_options      = staff_name();
// $staff_options      = select_option($staff_options, "Select Staff", $name);

// $under_user_options = under_user($user_name);
// $under_user_options = select_option($under_user_options, "Select Users ", $exp_under_user);

// $team_user_options  = team_user($user_name);
// $team_users_options = select_option($team_user_options, "Select Team Members", $exp_team_users);

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

                        <h4 class="page-title">User Creation</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="was-validated" autocomplete="off" method="POST" action="">
                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                <div class="row">
                                <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Staff Name </label>
                                            <input type="text" oninput="validateCharInput(this)" id="staff_name" name="staff_name"
                                                 class="form-control"
                                                value="<?php echo $staff_name; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>User Name </label>
                                            <input type="text"  oninput="validateCharInput(this)" id="user_name" name="user_name"
                                                 class="form-control"
                                                value="<?php echo $user_name; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Password </label>
                                            <input type="text"  oninput="validateCharInput(this)" class="form-control" id="password" name="password"
                                                value="<?= $password; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Confirm Password </label>
                                            <input type="text" oninput="validateCharInput(this)" class="form-control" id="confirm_password"
                                                name="confirm_password" value="<?= $password; ?>" required>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>User Type </label>
                                            <select class="form-control select2" id="user_type" name="user_type"
                                                required>
                                                <?php echo $user_type_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Mobile No</label>
                                            <input type="text" id="phone_no" name="phone_no" class="form-control"
                                            oninput="number_only(this)" minlength="10" maxlength="10"
                                                value="<?php echo $phone_no; ?>"  required>
                                        </div>
                                    </div>
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
                            <?php echo btn_cancel($btn_cancel);?>
                            <?php echo btn_createupdate($folder_name_org,$unique_id,$btn_text);?>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
<script>
    document.getElementById('password').addEventListener('input', function() {
        // Get the input value
        var inputValue = this.value;

        // Remove any HTML tags
        var sanitizedValue = inputValue.replace(/[<>]>?/g, '');

        // Update the input value with the sanitized text
        this.value = sanitizedValue;
    });

    document.getElementById('confirm_password').addEventListener('input', function() {
        // Get the input value
        var inputValue = this.value;

        // Remove any HTML tags
        var sanitizedValue = inputValue.replace(/[<>]>?/g, '');

        // Update the input value with the sanitized text
        this.value = sanitizedValue;
    });
</script>