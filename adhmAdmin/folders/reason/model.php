<!-- This file Only PHP Functions -->
<?php include 'function.php'; ?>

<?php
// Form variables
$btn_text = "Save";
$btn_action = "create";
$is_btn_disable = "";

$unique_id = "";

$main_screen_id = "";
$screen_section_id = "";
$screen_name = "";
$screen_folder_name = "";
$order_no = "";
$icon_name = "";
$is_active = 1;
$description = "";

$user_action_options = "";
$user_action_selected = "";

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "user_screen";

        $columns = [
            "main_screen_unique_id",
            "sub_screen_unique_id",
            "sub_screen_icon",
            "screen_name",
            "folder_name",
            "icon_name",
            "order_no",
            "login",
            "process",
            "is_active",
            "description",
            "actions"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $main_screen_id = $result_values[0]["main_screen_unique_id"];
            $sub_screen_id = $result_values[0]["sub_screen_unique_id"];
            $sub_screen_icon_name = $result_values[0]["sub_screen_icon"];
            $screen_name = $result_values[0]["screen_name"];
            $screen_folder_name = $result_values[0]["folder_name"];
            $icon_name = $result_values[0]["icon_name"];
            $order_no = $result_values[0]["order_no"];
            $login = $result_values[0]["login"];
            $process = $result_values[0]["process"];
            $is_active = $result_values[0]["is_active"];
            $description = $result_values[0]["description"];
            $user_action_selected = $result_values[0]["actions"];


            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$process_type_options = [
    "1" => [
        "unique_id" => "accepted",
        "value" => "accepted",
    ],
    "2" => [
        "unique_id" => "rejected",
        "value" => "rejected",
    ],
    "3" => [
        "unique_id" => "pending",
        "value" => "pending",
    ]
];
$process_type_options = select_option($process_type_options, "Select Process Type", $process);

$login_options = user_type();
$login_options = select_option($login_options, "Select Process Type", $login);



$main_screen_options = main_screen();

$main_screen_options = select_option($main_screen_options, "Select the Main Screen", $main_screen_id);

$section_name_options = "<option value='' disabled='disabled' selected>Select the Screen Section</option>";


$active_status_options = active_status($is_active);

$user_action_options = user_actions();

$user_action_options = user_action_list($user_action_options, $user_action_selected);


// $sub_screen_options = user_sub_screen();
// $sub_screen_options = select_option($sub_screen_options, "Select the Sub Screen", $sub_screen_id);


$sub_screen_options_1 = sub_screen_main();
$sub_screen_options_1 = select_option($sub_screen_options_1, "Select Forms","",$screen_name); 

?>


<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Reason</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="was-validated" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="main_screen" class="mb-1"> Login </label>
                                            <select name="login" id="login" class="select2 form-control"
                                                required>
                                                <?php echo $login_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="main_screen" class="mb-1"> Main Name </label>
                                            <select name="main_screen" id="main_screen" onchange="sub_menu()" class="select2 form-control"
                                                required>
                                                <?php echo $main_screen_options; ?>
                                            </select>
                                            <!-- onchange="get_sections(this.value)" -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="main_screen" class="mb-1"> Sub Menu </label>
                                            <select name="sub_screen" id="sub_screen" class="select2 form-control"
                                                required>
                                                <?php echo $sub_screen_options_1;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="mb-1">Action</label>
                                            <select name="process" id="process" class="select2 form-control" required>
                                                <!-- <option value="">Select</option>
                                                <option value="1">Reject</option>
                                                <option value="2">Accepted</option>
                                                <option value="3">Pending</option> -->
                                                <?php echo $process_type_options; ?>
                                            </select>
                                            <!-- <input type="text" id="unique_id" name="unique_id" value="<?php echo $unique_id;?>"> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="description" class="mb-1">Description</label>
                                            <textarea name="description" id="description" class="form-control" oninput="validateCharInput(this)"
                                                required><?php echo $description; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="mb-1">Status</label>
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