<?php

// Form variables
$btn_text   =   "Save";
$btn_action =   "create";

$unique_id  =    "";
$date       =    "";
$holiday    =    "";
$is_active  =    1;


if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "holiday_creation";

        $columns = [
            "date",
            "holiday",
            "description",
            "is_active",
            
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details,$where);
        // print_r($result_values);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $date = $result_values[0]["date"];
            $holiday = $result_values[0]["holiday"];
            $description = $result_values[0]["description"];
            $is_active = $result_values[0]["is_active"];



            $btn_text = "Update";
            $btn_action = "update";

        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$academic_year = academic_year();
$academic_year = select_option_acc($academic_year, "Select Academic Year", $academic_year);

$active_status_options = active_status($is_active); 

?>
<style>
    .disabled-select {
        pointer-events: none;
        background-color: #f5f5f5;
        color: #999;
        border: 1px dark gray !important;
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
                        <h4 class="page-title">Holiday Creation</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form class="was-validated" id="myForm" autocomplete="off">
                                <div class="row mb-3">
                                    <div class="col-md-3 fm">
                                        <label for="academic_year"  class="form-label">Academic year</label>
                                        <select id="academic_year" name="academic_year" class="form-control disabled-select" required>
                                            <?php echo $academic_year;?>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date" id="date" value="<?php echo $date;?>">
                                        <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                    </div>

                                    <div class="col-md-3">
                                        <label for="holiday" class="form-label">Holiday</label>
                                        <input type="text" class="form-control" oninput="validateCharInput(this)"  name="holiday" id="holiday" value="<?php echo $holiday;?>">
                                    </div>
                                    

                                    <div class="col-md-3 fm">
                                        <label for="is_active" class="form-label">Status</label>
                                        <select name="is_active" id="is_active" class="select2 form-control" required>
                                            <?php echo $active_status_options; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 fm mt-2">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" oninput="validateCharInput(this)"  class="form-control"><?php echo $description; ?></textarea>
                                    </div>
                                    
                                </div>
                                
                                <div class="btns">
                                    <?php echo btn_cancel($btn_cancel); ?>
                                    <?php echo btn_createupdate($folder_name_org,$unique_id,$btn_text); ?>
                                </div>
                            </form>

                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
        </div>
    </div>
</div>