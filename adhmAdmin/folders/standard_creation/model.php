<<?php 
// Form variables
$btn_text           = "Save";
$btn_action         = "create";

$unique_id          = "";
$district_name      = "";
$is_active          = 1;

if(isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "standard_creation";

        $columns    = [
            "school_unique_id",
            "standard",
            
            "is_active"
        ];

        $table_details   = [
            $table,
            $columns
        ]; 

        $result_values  = $pdo->select($table_details,$where);

        if ($result_values->status) {

            $result_values      = $result_values->data;

            $school_unique_id      = $result_values[0]["school_unique_id"];
            $standard         = $result_values[0]["standard"];
            
            $is_active          = $result_values[0]["is_active"];

           
          
            $btn_text           = "Update";
            $btn_action         = "update";
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}


$school_name_option      = school_name();
$school_name_option      = select_option($school_name_option,"Select",$school_unique_id);
// $taluk_name_option = taluk_name('');
// $taluk_name_option = select_option($taluk_name_option, 'Select taluk', $taluk_name);
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

                        <h4 class="page-title">Standard Creation Form</h4>
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
                                                <label for="example-select" class="form-label">Select School Name</label>
                                                <select name="school_unique_id" id="school_unique_id"
                                                    class="select2 form-control" required>
                                                    <?php echo $school_name_option;?>"

                                                </select>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="simpleinput" class="form-label">Standard</label>
                                                <input type="text" class="form-control" id="standard" name="standard"  oninput="validateCharInput(this)"  value="<?php echo $standard;?>" required>
                                                

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
    