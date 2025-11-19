<?php include 'function.php';?>
<?php 

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('Invalid CSRF token');
    }
}

// Form variables
$btn_text           = "Save";
$btn_action         = "create";

$unique_id          = "";
$is_active          = 1;
$date = date("Y-m-d");
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

        $table      =  "notification";

        $columns    = [
            "date",
            "expire_date",
            "title",
            "content",
            "is_active",
            "actions"
        ];

        $table_details   = [
            $table,
            $columns
        ]; 

        $result_values  = $pdo->select($table_details,$where);

        if ($result_values->status) {

            $result_values      = $result_values->data;

            $date             = $result_values[0]["date"];
            $expire_date      = $result_values[0]["expire_date"];
            $title            = $result_values[0]["title"];
            $content          = $result_values[0]["content"];
            $is_active        = $result_values[0]["is_active"];
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

 $active_status_options   = active_status($is_active);

 $user_action_options    = notification_actions();

$user_action_options    = user_action_list($user_action_options, $user_action_selected);

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

                        <h4 class="page-title">Notification</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">


                    <div class="row">

                        <div class="">
                            <div class="card">
                                <div class="card-body">
                                    <form class="was-validated" autocomplete="off">
                                        <div class="row mb-3">
                                        <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Date</label>
                                                <input type="date" name="date" id="date"
                                                    class="select2 form-control" value="<?php echo $date;?>" required>
                                                    
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Expire Date</label>
                                                <input type="date" name="expire_date" id="expire_date"
                                                    class="select2 form-control" value="<?php echo $expire_date;?>" required>
                                                <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                              
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="simpleinput" class="form-label">Title</label>
                                                <input type="text" class="form-control" id="title" name="title"  oninput="validateCharInput(this)"  value="<?php echo $title;?>" required>
                                                
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="simpleinput" class="form-label">Content</label>
                                                <input type="text-area"   oninput="validateCharInput(this)"  class="form-control" id="content" name="content" value="<?php echo $content;?>" required>
                                                
                                            </div>
                                            <div class="col-md-3 fm mt-1">
                                                <label>Status</label>
                                                <select name="is_active" id="is_active" class="select2 form-control"
                                                    required>
                                                    <?php echo $active_status_options;?>
                                                </select>
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
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    