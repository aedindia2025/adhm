<?php 
// Form variables
$btn_text           = "Save";
$btn_action         = "create";

$unique_id          = "";
// $district_name      = "";
$is_active          = 1;

if(isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "school_name";

        $columns    = [
            // "(select district_name from district_name where district_name.unique_id=school_name.district_unique_id) as district_unique_id",
            // "(select taluk_name from taluk_creation where taluk_creation.unique_id = school_name.taluk_unique_id) as taluk_unique_id",
            "school_name",
            "is_active",
            "district_unique_id",
            "taluk_unique_id"
        ];

        $table_details   = [
            $table,
            $columns
        ]; 

        $result_values  = $pdo->select($table_details,$where);

        if ($result_values->status) {

            $result_values      = $result_values->data;

            $district_name      = $result_values[0]["district_unique_id"];
            $taluk_name         = $result_values[0]["taluk_unique_id"];
            $school_name        = $result_values[0]["school_name"];
            $is_active          = $result_values[0]["is_active"];
            
            $taluk_name_option = taluk_name("",$district_name);
            $taluk_name_options = select_option($taluk_name_option, 'Select taluk', $taluk_name);

           
          
            $btn_text           = "Update";
            $btn_action         = "update";
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}

$district_name_option      = district_name();
$district_name_options      = select_option($district_name_option, "Select district", $district_name);

$active_status_options   = active_status($is_active);
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

                        <h4 class="page-title">School Name Creation Form</h4>
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
                                    <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <div class="row mb-3">
                                        <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Select District</label>
                                                <select name="district_name" id="district_name"
                                                    class="select2 form-control" onchange="get_taluk_name()" required>

                                                    <?php echo $district_name_options; ?>

                                                </select>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Select Taluk</label>
                                                <select name="taluk_name" id="taluk_name"
                                                    class="select2 form-control" required>
                                                    <?= $taluk_name_options; ?>

                                                </select>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="simpleinput" class="form-label">School Name</label>
                                                <input type="text"  oninput="validateCharInput(this)"  class="form-control" id="school_name" name="school_name" value="<?= $school_name;?>" required>
                                                
                                            </div>
                                            <div class="col-md-3 fm mt-1">
                                                <label>Status</label>
                                                <select name="is_active" id="is_active" class="select2 form-control"
                                                    required>
                                                    <?php echo $active_status_options;?>
                                                </select>
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
    