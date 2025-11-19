<style>

</style>

<!-- This file Only PHP Functions -->
<?php include 'function.php';?>

<?php 
// Form variables
$btn_text           = "Save";
$btn_action         = "create";
$is_btn_disable     = "";

$unique_id          = "";

// $permission_check   = user_permission_ui();

// $permission_check   = "";

if(isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        
        if ($unique_id) {

            $result_val = get_permissions($unique_id);

            $btn_text           = "Update";
            $btn_action         = "update";
            $is_btn_disable     = " disabled ";

        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = " disabled ";
        }
    }
}

$user_type_options  = user_type();
$user_type_options  = select_option($user_type_options,"Select User Type",$unique_id);

$main_screen_options= main_screen();
$main_screen_options= select_option($main_screen_options,"Select Main Screen","");

// user_sub_screen_main();





?>
<input type="hidden" name="update_user_type" id="update_user_type" value="<?php echo $unique_id; ?>">

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">User Type Permission</h4>
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
                                    <div class="col-md-6">
                                        <div class="mb-6">
                                            <label class="col-md-6 col-form-label" for="user_type"> User Type</label>
                                            <select <?php echo $is_btn_disable; ?> name="user_type"
                                                onchange="perm_ui_val()" id="user_type" class="select2 form-control"
                                                required>
                                                <?php echo $user_type_options;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-6">
                                            <label class="col-md-10 col-form-label" for="main_screen"> Main Screen</label>
                                            <select name="main_screen" onchange="perm_ui_val()" id="main_screen"
                                                class="select2 form-control" required>
                                                <?php echo $main_screen_options;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12" id="perm_ui">
                                        <!-- <div class="card-box"> -->
                                        <input type="hidden" id="perm_ui" value="">
                                        <!-- </div> -->
                                    </div>
                                </div>
                                 <div class="btns mt-3">
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