
<?php 
// Form variables
$btn_text           = "Save";
$btn_action         = "create";
$is_btn_disable     = "";

$unique_id          = "";

$screen_type_id     = "";
$screen_name        = "";
$order_no           = "";
$icon_name          = "";
$is_active          = 1;
$description        = "";

if(isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "user_screen_main";

        $columns    = [
            "screen_type_unique_id",
            "screen_main_name",
            "icon_name",
            "order_no",
            "is_active",
            "description"
        ];

        $table_details   = [
            $table,
            $columns
        ]; 

        $result_values  = $pdo->select($table_details,$where);

        if ($result_values->status) {

            $result_values     = $result_values->data;

            $screen_type_id    = $result_values[0]["screen_type_unique_id"];
            $screen_name       = $result_values[0]["screen_main_name"];
            $icon_name         = $result_values[0]["icon_name"];
            $order_no          = $result_values[0]["order_no"];
            $is_active         = $result_values[0]["is_active"];
            $description       = $result_values[0]["description"];

            $btn_text           = "Update";
            $btn_action         = "update";
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}

$screen_type_options  = screen_type();

// print_r($screen_type_options);

$screen_type_options  = select_option($screen_type_options,"Select the Screen Type",$screen_type_id);

$active_status_options= active_status($is_active);

?>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Main User Screen</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        <form class="was-validated" autocomplete="off" >
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                    <label  for="screen_type">Screen Type </label>
                                        <select name="screen_type" id="screen_type" class="select2 form-control" required>
                                        <?php echo $screen_type_options;?>
                                    </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                    <label  for="screen_name">Screen Name </label>
                                        <input type="text" class="form-control" oninput="validateCharInput(this)" id='screen_name' name='screen_name' value='<?php echo $screen_name; ?>' required>
                                    </div> 
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                    <label  for="order_no">Order No</label>
                                         <input type="number" id="order_no" name="order_no" class="form-control" oninput="validateCharInput(this)" min="1" value="<?php echo $order_no; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                    <label  for="is_active">Active Status </label>
                                        <select name="active_status" id="active_status" class="select2 form-control" required>
                                        <?php echo $active_status_options;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                    <label  for="icon_name">Icon</label>
                                        <input type="text" class="form-control" id='icon_name' name='icon_name' oninput="validateCharInput(this)" value='<?php echo $icon_name; ?>' required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                    <label  for="description">Description</label>
                                        <textarea name="description" id="description"  oninput="validateCharInput(this)" rows="2" class="form-control"><?php echo $description; ?></textarea>
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