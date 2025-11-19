
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/> -->


<?php

// Form variables
$btn_text   =   "Save";
$btn_action =   "create";

$unique_id  =    "";
$from_year  =    "";
$to_year    =    "";
$is_active  =    1;






if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
        $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "academic_year_creation";

        $columns = [
            "from_year",
            "to_year",
            "amc_year",
            "is_active"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details,$where);
        // print_r($result_values);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $from_year = $result_values[0]["from_year"];
            $to_year = $result_values[0]["to_year"];
            $amc_year = $result_values[0]["amc_year"];
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


$active_status_options = active_status($is_active); ?>







<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Academic Year</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <form class="was-validated" autocomplete="off">
                    <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">



                        <div class="row">

                            <div class="">
                                <div class="card">
                                    <div class="card-body">


                                        <div class="row mb-3">
                                            <div class="col-md-3 fm">
                                                <label for="product_category" class="form-label">From Date</label>
                                              

                                            <input type="date" class="form-control" name="from_year" id="from_year" value="<?php echo $from_year;?>">
                                               

                                               
                     
                                            </div>

                                            <div class="col-md-3 fm">
                                            <label for="product_category" class="form-label">To Date</label>
                                              

                                              <input type="date" class="form-control" name="to_year" id="to_year" onchange=get_amc_year() value="<?php echo $to_year;?>">
                                            </div>
                                            <div class="col-md-3 fm">
                                            <label for="product_category" class="form-label">AMC year</label>
                                              

                                              <input type="text" class="form-control" name="amc_year" id="amc_year" oninput="validateCharInput(this)" value="<?php echo $amc_year;?>">
                                            </div>
                                            

                                            <div class="col-md-3 fm">
                                                <label class="form-label">Status</label>
                                                <select name="is_active" id="is_active" class="select2 form-control" required>
                                                    <?php echo $active_status_options; ?>
                                                </select>



                                            </div>
                                        </div>
                                        <div class="btns">

                                            <?php echo btn_cancel($btn_cancel); ?>
                                            <?php echo btn_createupdate($folder_name_org,$unique_id,$btn_text); ?>

                                        </div>

                    </form>


                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col -->
    
    <script>

function get_amc_year(){

	
 var amc_value = $('#amc_year').val();

var from_year = $('#from_year').val();

var to_year	  = $('#to_year').val();

var array_1 = from_year.split("-");
var split_from_year = array_1[0];

var array_1 = to_year.split("-");
var split_to_year = array_1[0];

var amc_year = split_from_year + '-' + split_to_year ;

// alert(amc_year);


$('#amc_year').val(amc_year);






}


    </script>

