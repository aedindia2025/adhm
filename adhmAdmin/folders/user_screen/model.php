<!-- This file Only PHP Functions -->
<?php include 'function.php';?>

<?php 
// Form variables
$btn_text           = "Save";
$btn_action         = "create";
$is_btn_disable     = "";

$unique_id          = "";

$main_screen_id     = "";
$screen_section_id  = "";
$screen_name        = "";
$screen_folder_name = "";
$order_no           = "";
$icon_name          = "";
$is_active          = 1;
$description        = "";

$user_action_options    = "";
$user_action_selected   = "";

if(isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "user_screen";

        $columns    = [
            "main_screen_unique_id",
            "sub_screen_unique_id",
            "sub_screen_icon",
            "screen_name",
            "folder_name",
            "icon_name",
            "order_no",
            "is_active",
            "description",
            "actions"
        ];

        $table_details   = [
            $table,
            $columns
        ]; 

        $result_values  = $pdo->select($table_details,$where);

        if ($result_values->status) {

            $result_values     = $result_values->data;

            $main_screen_id    = $result_values[0]["main_screen_unique_id"];
            $sub_screen_id       = $result_values[0]["sub_screen_unique_id"];
            $sub_screen_icon_name = $result_values[0]["sub_screen_icon"];
            $screen_name       = $result_values[0]["screen_name"];
            $screen_folder_name= $result_values[0]["folder_name"];
            $icon_name         = $result_values[0]["icon_name"];
            $order_no          = $result_values[0]["order_no"];
            $is_active         = $result_values[0]["is_active"];
            $description       = $result_values[0]["description"];
            $user_action_selected   = $result_values[0]["actions"];


            $btn_text           = "Update";
            $btn_action         = "update";
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}

$main_screen_options  = main_screen();

$main_screen_options  = select_option($main_screen_options,"Select the Main Screen",$main_screen_id);

$section_name_options = "<option value='' disabled='disabled' selected>Select the Screen Section</option>";


$active_status_options = active_status($is_active);

$user_action_options    = user_actions();

$user_action_options    = user_action_list($user_action_options, $user_action_selected);


$sub_screen_options    = user_sub_screen();
$sub_screen_options  = select_option($sub_screen_options,"Select the Sub Screen",$sub_screen_id);
?>


<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">User Screen</h4>
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
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="main_screen"> Main Screen </label>
                                            <select name="main_screen" id="main_screen" class="select2 form-control"
                                                onchange="get_sections(this.value)" required>
                                                <?php echo $main_screen_options;?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="main_screen"> Sub Screen </label>
                                            <select name="sub_screen" id="sub_screen" class="select2 form-control" >
                                                <?php echo $sub_screen_options;?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="icon_name">Sub Screen Icon</label>
                                            <input type="text" class="form-control" id='sub_screen_icon_name' name='sub_screen_icon_name'
                                            oninput="validateCharInput(this)"    value='<?php echo $sub_screen_icon_name; ?>' >
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="screen_name">Screen Name </label>
                                            <input type="text" class="form-control" id='screen_name' name='screen_name'
                                            oninput="validateCharInput(this)"    value='<?php echo $screen_name; ?>' required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="screen_folder_name">Folder Name</label>
                                            <input type="text" id="screen_folder_name" name="screen_folder_name"
                                            oninput="validateCharInput(this)"     class="form-control" value="<?php echo $screen_folder_name; ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="order_no">Order No</label>
                                            <input type="number" id="order_no" name="order_no" class="form-control"
                                            oninput="validateCharInput(this)"      value="<?php echo $order_no; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="icon_name">Icon</label>
                                            <input type="text" class="form-control" id='icon_name' name='icon_name'
                                            oninput="validateCharInput(this)"     value='<?php echo $icon_name; ?>' required>
                                        </div>
                                    </div>
                                    
                                   
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Status</label>
                                            <select name="is_active" id="is_active" class="select2 form-control"
                                                required>
                                                <?php echo $active_status_options;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description"
                                            oninput="validateCharInput(this)"    class="form-control"><?php echo $description; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="header-title" style="color: #343a40;">Actions </h6>

                                    </div>

                                    <div class="form-group">
                                        <ul class="ks-cboxtags">
                                            <?php echo $user_action_options; ?>
                                        </ul>
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