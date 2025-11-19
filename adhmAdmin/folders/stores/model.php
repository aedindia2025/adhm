<?php 

// Form variables
$btn_text           = "Save";
$btn_action         = "create";


$is_active          = 1;
$warehouse_options  = "";
if(isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "taluk_creation";

        $columns    = [
            
            "district_name",
            "taluk_name",
            "is_active",
            "unique_id"
        ];

        $table_details   = [
            $table,
            $columns
        ]; 

        $result_values  = $pdo->select($table_details,$where);
// print_r($result_values);
        if ($result_values->status) {

            $result_values     = $result_values->data;

        
            $district_name        = $result_values[0]["district_name"];
            $taluk_name        = $result_values[0]["taluk_name"];
            $is_active        = $result_values[0]["is_active"];

            
            
          
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


// $staff_options      = staff_name();
// $staff_options      = select_option($staff_options,"Select Staff",$staff_name); 

$district_name_option      = district_name();
$district_name_options      = select_option($district_name_option,"Select district",$district_name); 




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

                        <h4 class="page-title">Taluk Form</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">


                    <div class="row">

                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <form class="was-validated" autocomplete="off">
                                    
                                        <div class="row mb-3">
                                            <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Select District</label>
                                                <select name="district_name" id="district_name"
                                                    class="select2 form-control" required>

                                                    <?php echo $district_name_options; ?>

                                                </select>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="simpleinput" class="form-label">Taluk Name</label>
                                                <input type="text" class="form-control"   oninput="validateCharInput(this)" id="taluk_name" name="taluk_name"
                                                    value="<?=$taluk_name;?>" required>
                                                    <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                                </div>
                                            <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Status</label>
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
</div>