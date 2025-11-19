 <?php
 session_start();

 if (empty($_SESSION['csrf_token'])) {
     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
 }
 
 
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
         die('Invalid CSRF token');
     }
 }
// Form variables
$btn_text           = "Save";
$btn_action         = "create";

// $unique_id          = "";
$expenses_type      = "";
$is_active          = 1;

$letter_date = date("Y-m-d");

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        // $unique_id  = $_GET["unique_id"];

        $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
        $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id; 

        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "disbursement_creation";

        $columns    = [
            "hostel_name",
            "taluk_name",
            "applied_date",
            "disbursement_type",
            "academic_year",
            "month",
            "connection_no",
            "letter_no",
            "letter_date",
            "disbursement_file",
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values          = $result_values->data;

            $hostel_name            = $result_values[0]["hostel_name"];
            $taluk_name             = $result_values[0]["taluk_name"];
            $applied_date          = $result_values[0]["applied_date"];
            $disbursement_type          = $result_values[0]["disbursement_type"];
            $academic_year        = $result_values[0]["academic_year"];
            $month          = $result_values[0]["month"];
            $connection_no          = $result_values[0]["connection_no"];
            $letter_no        = $result_values[0]["letter_no"];
            $letter_date          = $result_values[0]["letter_date"];
            $disbursement_file          = $result_values[0]["disbursement_file"];

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

// $district_name_list = district_name();
// $district_name_list = select_option($district_name_list, "Select District",$district_name);

// $taluk_name_list = taluk_name();
// $taluk_name_list = select_option($taluk_name_list, "Select Taluk",$taluk_name);

// $hostel_name_list = hostel_name();
// $hostel_name_list = select_option($hostel_name_list, "Select Hostel",$hostel_name);

$disbursement_type_options = disbursement_type($disbursement_type);
$disbursement_type_options = select_option($disbursement_type_options, "Select Disbursement",$disbursement_type);

$academic_year_options = academic_year($academic_year);
$academic_year_options = select_option_acc($academic_year_options,$academic_year);

$month = date('F');

$login_user_id = $_SESSION["user_id"];
$ses_hostel_id = $_SESSION['hostel_id'];
$ses_hostel_name = $_SESSION['hostel_name'];
$ses_taluk_name   =$_SESSION['taluk_name'];
$ses_taluk_id   = $_SESSION['taluk_id']; 

$ses_district_name   =$_SESSION['district_name'];
$ses_district_id   = $_SESSION['district_id']; 
?>
<style>
    #error_message{
        color:red;
    }
    </style>
 <!-- Modal with form -->

 <div class="content-page">
     <div class="content">

         <!-- Start Content-->
         <div class="container-fluid">

             <!-- start page title -->
             <div class="row">
                 <div class="col-12">
                     <div class="page-title-box">

                         <h4 class="page-title">Disbursement</h4>
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
                                                 <label for="simpleinput" class="form-label">District</label> 
                                                 <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                                 <!-- <input type="text" class="form-control" id="taluk_name" name="taluk_name" value="<?=$taluk_name ?>" required > -->
                                                 <input type="text" id="" name="" required class="form-control"
                                                     value="<?= $ses_district_name; ?>" readonly>
                                                 <input type="hidden" id="district_name" name="district_name"
                                                     required class="form-control" value="<?= $ses_district_id; ?>"
                                                     readonly>

                                             </div>
                                             <div class="col-md-3 fm">
                                                 <label for="simpleinput" class="form-label">Taluk</label>
                                                 <!-- <input type="text" class="form-control" id="taluk_name" name="taluk_name" value="<?=$taluk_name ?>" required > -->
                                                 <input type="text" id="" name="" required class="form-control"
                                                     value="<?= $ses_taluk_name; ?>" readonly>
                                                 <input type="hidden" id="taluk_name" name="taluk_name" required
                                                     class="form-control" value="<?= $ses_taluk_id; ?>" readonly>

                                             </div>
                                             <div class="col-md-3 fm">
                                                 <label for="simpleinput" class="form-label">Hostel</label>
                                                 <!-- <input type="text" class="form-control" id="hostel_name" name="hostel_name" value="<?=$hostel_name?>" required > -->
                                                 <input type="text" id="" name="" required class="form-control"
                                                     value="<?= $ses_hostel_name; ?>" readonly>
                                                 <input type="hidden" id="hostel_name" name="hostel_name" required
                                                     class="form-control" value="<?= $ses_hostel_id; ?>" readonly>
                                             </div>

                                             <div class="col-md-3 fm">
                                                 <label for="simpleinput" class="form-label">Applied Date</label><br>
                                                 <label for="simpleinput" class="form-label">
                                                     <h4><?php if($applied_date){ echo $applied_date; }else{echo date('Y-m-d');}?>
                                                     </h4>
                                                 </label>
                                                 <input type="hidden" class="form-control" id="applied_date"
                                                     name="applied_date"
                                                     value="<?php if($applied_date){ echo $applied_date; }else{echo date('Y-m-d');}?>">

                                             </div>
                                         </div>
                                         <hr>

                                         <div class="row mb-3">
                                             <div class="col-md-4 fm">
                                                 <label for="simpleinput" class="form-label">Disbursement Type</label>
                                                 <select class="form-control" id="disbursement_type"
                                                     name="disbursement_type" required>
                                                     <?php echo $disbursement_type_options ?>
                                                 </select>
                                             </div>
                                             <div class="col-md-4 fm">
                                                 <label for="simpleinput" class="form-label">Academic Year</label>
                                                 <select class="form-control" id="academic_year" name="academic_year"
                                                     disabled required>
                                                     <?php echo $academic_year_options ?>
                                                 </select>

                                             </div>

                                             <div class="col-md-4 fm">
                                                 <label for="simpleinput" class="form-label">Month</label>
                                                 <input type="text" class="form-control" id="cur_month" name="cur_month"
                                                     value="<?=$month?>" readonly required>
                                                 <input type="hidden" class="form-control" id="unique_id"
                                                     name="unique_id" value="<?=$unique_id?>" readonly required>

                                             </div>

                                             <div class="col-md-4 fm mt-3">
                                                 <label for="simpleinput" class="form-label">Connection No</label>
                                                 <input type="text" class="form-control" id="connection_no" oninput="off_id(this)"
                                                     name="connection_no" value="<?= $connection_no ?>" required>

                                             </div>

                                             <div class="col-md-4 fm mt-3">
                                                 <label for="simpleinput" class="form-label">Letter No</label>
                                                 <input type="text" class="form-control" id="letter_no" name="letter_no" oninput="off_id(this)"
                                                     value="<?= $letter_no ?>" required>

                                             </div>

                                             <div class="col-md-4 fm mt-3">
                                                 <label for="simpleinput" class="form-label">Letter Date</label>
                                                 <input type="date" class="form-control" id="letter_date"
                                                     name="letter_date" value="<?= $letter_date ?>" required>

                                                 <input type="hidden" class="form-control" id="login_user_id"
                                                     name="login_user_id" value="<?= $login_user_id ?>" required>

                                             </div>

                                         </div>
                                         <div class="row">
                                             <div class="col-md-4 fm">
                                                 <label for="simpleinput" class="form-label">Document Upload</label>
                                                 <input type="file" class="form-control" id="test_file"
                                                     name="test_file" accept=".pdf,.doc,.docx,.xls,.xlsx,image/*">
                                                     <input type="hidden" id="hid_pic" name="hid_pic" value="<?= $disbursement_file ?>">
                                             </div>
                                         </div>
                                         <span id="error_message"></span>

                                            
                                         



                                         <div class="btns">
                                             <?php echo btn_cancel($btn_cancel); ?>
                                             <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                             <!-- <button type="button" onclick="disbursement_cu();">Save</button> -->
                                         </div>
                                     </form>
                                 </div> <!-- end card-body -->
                             </div> <!-- end card-->
                         </div> <!-- end col -->

                     </div>




                 </div>
             </div>



         </div>
     </div>
 </div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

 <script>

 </script>