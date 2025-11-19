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
// include "function.php";
// $academic_year_options = academic_year();
// $academic_year_options = select_option_acc($academic_year_options, "Select Academic Year");
$unique_id = $_SESSION['sess_user_id'];
// if (isset($_GET["unique_id"])) {
    if ($unique_id) {

        $unique_id = $_GET["unique_id"];

        $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
        $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id; 
        
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "staff_registration";

        $columns = [
            "hostel_name",
            "taluk_office",
            "district_office",
            // "academic_year"
            //  
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $hostel_name = $result_values[0]["hostel_name"];
            $taluk_office = $result_values[0]["taluk_office"];
            $district_office = $result_values[0]["district_office"];
            // $academic_year = $result_values[0]["academic_year"];
            // $is_active = $result_values[0]["is_active"];



            
        }


        $where = [
            "unique_id" => $unique_id
        ];

        $table = "std_app_s2";

        $columns = [
            "s1_unique_id"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);

        if ($result_values->status) {

            $result_values = $result_values->data;
 
            $s1_unique_id=$result_values[0]["s1_unique_id"];
           



            
        }


    }

    $batch_no = batch_no($academic_year);


?>
<style>
.card-body.brd {
    border: 1px solid #ccc;
}
.common h4 {
    color: #000;
    margin-top: 0px;
}
.common label {
    font-size: 13px;
    font-weight: 500;
}
table#dispatch_datatable thead {
    background: #f1f1f1;
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
                        <h4 class="page-title">Print For Dispatch</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body brd">
                            <form class="was-validated">
                                <div class="row  common">
                                <input type="hidden" id="hostel_district" value="<?php echo $_SESSION['district_id'];?>">
<input type="hidden" id="hostel_taluk" value="<?php echo $_SESSION['taluk_id'];?>">
<input type="hidden" id="hostel_name" value="<?php echo $_SESSION['hostel_id'];?>">
<!-- <input type="text" id="hostel_name" value="<?php echo $_SESSION['hostel_main_id'];?>"> -->
<input type="hidden" id="academic_year" value="<?php echo $academic_year;?>">

                                    <div class="col-md-4 fontsize-14">

                                        <label for="academic_year" >HOSTEL DISTRICT</label>
                                        <h4 ><?php echo $_SESSION['district_name'];?></h4>

                                        <label for="academic_year" >ENTRY DATE</label>
                                        <h4><?php echo date('Y-m-d');?></h4>

                                        

                                    </div>


                                    <div class="col-md-4 fontsize-14">
                                    
                                    <label for="academic_year" >HOSTEL TALUK</label>
                                        <h4><?=$_SESSION['taluk_name'];?></h4>

                                        
                                        <label for="academic_year" >BATCH NO</label>
                                        <h4 id="batch"></h4>
                                        <input type="hidden" id="batch_no" value="<?=$batch_no;?>">
                                       
                                        

                                    </div>
                                    <div class="col-md-4 fontsize-14">
                                    <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                    <label for="academic_year">HOSTEL NAME</label>
                                        <h4><?=$_SESSION['hostel_name'];?></h4>

                                    

                                    </div>
                                   
                                    <div class="col-md-3">
                                        <label for="academic_year" class="form-label">Batch Type</label>
                                        <select name="apptype" id="apptype" class="select2 form-control" onchange="appilicationtype()" required>
                                            <option value="1">FRESH</option>
                                            <option value="2">RENEWAL</option>
                                        </select>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    
                        <table id="dispatch_datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                
                                <tr>
                                    <th>Selection</th>
                                    <th>S.no</th>
                                    
                                    <!-- <th>Batch No</th> -->
                                    <th>Applicant Name</th>
                                    <th>EMIS/UMIS Name</th>
                                    <th>Application No</th>
                                    <th>Applied Date</th>
                                    <!-- <th>Hostel Name</th> -->
                                    
                                </tr>
                            </thead>
                        </table><br>
                        <!-- <div class="col-12 mt-3">
                        <div class="form-group row "> -->
                        <div class="col-md-12" align="right">
                                <!-- Cancel,save and update Buttons -->
                                <?php echo btn_cancel($btn_cancel); ?>
                                <button type="button" id="createbatch" class="btn btn-primary waves-effect waves-light" onclick="batch_create()">Create Batch</button>
                               
                            </div>
                        <!-- </div>
                    </div> -->
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
</div>
<?php 

function batch_no_1($academic_year)
{
    // $date = date("Y");
    // $st_date = substr($date, 4);

    $servername = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $database_name = "adi_dravidar";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    }

    $sql = $conn->query("SELECT amc_year FROM academic_year_creation where is_delete = '0' order by s_no desc Limit 1");
    $row = $sql->fetch();

    $acc_year = $row['amc_year'];
    $a = str_split($acc_year);
    $splt_acc_yr = $a[0] . $a[1] . $a[2] . $a[3];

    $hostel_id = $_SESSION['hostel_id'];
    
    $hostel_main_id = $_SESSION['hostel_main_id'];
    $splt_hos_id = substr($hostel_main_id, -3);

    $stmt = $conn->query("SELECT max(batch_no) as batch_no FROM batch_creation where is_delete = '0' and hostel_name = '".$hostel_id."' order by id desc limit 1");
    $last_reg_no = $stmt->fetchColumn();
   
    if ($last_reg_no == '') {
        $new_seq_no = 1;
    } else {
        // Extract year and sequence number from the last registration number
        $last_seq_no = intval(substr($last_reg_no, -4)); // Extract last 4 digits

        // Increment the sequence number
        $new_seq_no = $last_seq_no + 1;
    }

    // Format the new registration number
    $registration_no = $splt_acc_yr . $splt_hos_id. 'BAT' . str_pad($new_seq_no, 4, '0', STR_PAD_LEFT);

    return $registration_no;
}



function batch_no($academic_year="")
    {
        // $date = date("Y");
        // $st_date = substr($date, 4);
        
        $servername = "localhost";
        $username = "root";
        $password = "4/rb5sO2s3TpL4gu";
        $database_name = "adi_dravidar";
        
        try {
            $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
        } catch (PDOException $e) {
            // echo "Connection failed: " . $e->getMessage();
        }

        $stmt_acc = $conn->query("SELECT amc_year FROM academic_year_creation where is_delete = '0' order by s_no desc Limit 1");
        
        $value = $stmt_acc->fetch();

        //  $acmc_year = academic_year($academic_year)[0]['acc_year'];
        $a = str_split($value['amc_year']);
         $splt_acc_yr = $a[0].$a[1].$a[2].$a[3];
        //  $hostel_id = $_SESSION['hostel_id']; 
        //  $host_id = hostel_name($hostel_id)[0]['hostel_id'];
        //  $splt_hos_id = substr($host_id,-3);

         $hostel_id = $_SESSION['hostel_id'];
    // $host_main_id = hostel_name($hostel_id)[0]['hostel_id'];
    $hostel_main_id = $_SESSION['hostel_main_id'];
    $splt_hos_id = $hostel_main_id;

        
        
    
    
        // echo "SELECT * FROM apply_application_form where application_no LIKE 'APN%' order by id desc";echo "<br>";
        $stmt = $conn->query("SELECT batch_no  FROM batch_creation where is_delete = '0' and hostel_name = '".$hostel_id."' order by id desc limit 1");
    // echo "SELECT max(batch_no) as batch_no FROM batch_creation where is_delete = '0' and hostel_name = '".$hostel_id."' order by id desc";

   
        if ($res1 = $stmt->fetch()) {
            if($res1['batch_no'] != ''){

            print_r($res1['batch_no']);
            $pur_array = explode("-",$res1['batch_no']);
           

            //  echo $pur_array[1];
          
                $booking_no  = $pur_array[1];
                print_r($booking_no);
            }
            // else{
            //     $booking_no  = '';
            // }
           
        }
       
        if ($booking_no == ''){
            // echo "ff";
            $booking_nos = $splt_acc_yr.$splt_hos_id.'B-'.'0001';
        }
        
        else {
            $booking_no += 1;
            
        $booking_nos = $splt_acc_yr.$splt_hos_id.'B-'.str_pad($booking_no, 4, '0', STR_PAD_LEFT);
        }
    
        return $booking_nos;
    }
?>
<script>
   
   
function batch_create() {
    
    document.getElementById('createbatch').disabled = true;
   
    const checked = document.querySelectorAll('.myCheck:checked');
    // if(checked.length > '0'){
        
        
    
    for (var i = 0; i < checked.length; i++) {
        var checkbox = checked[i];
        var s1_unique_id = checkbox.parentElement.querySelector('#s1_unique_id').value;
        // alert(form_unique_id);
        var applied_date = checkbox.parentElement.querySelector('#applied_date').value;
        // alert(po_num);
        var hostel_name = checkbox.parentElement.querySelector('#hostel_name').value;
        // alert(po_date);
        var hostel_taluk = checkbox.parentElement.querySelector('#hostel_taluk').value;
        // alert(po_product_name);
        var hostel_district = checkbox.parentElement.querySelector('#hostel_district').value;
        // alert(bg_month);
        var std_name = checkbox.parentElement.querySelector('#std_name').value;
        var academic_year = checkbox.parentElement.querySelector('#academic_year').value;
        var batch_no = checkbox.parentElement.querySelector('#batch_no').value;
        // alert(bg_per);
        var std_app_no = checkbox.parentElement.querySelector('#std_app_no').value;
        // var std_app_no = checkbox.parentElement.querySelector('#std_app_no').value;
        
       
        // alert();
    
    var data = "s1_unique_id=" + s1_unique_id + "&applied_date=" + applied_date + "&hostel_name=" + hostel_name + "&hostel_taluk=" + hostel_taluk + "&hostel_district=" + hostel_district + "&std_name=" + std_name + "&std_app_no=" + std_app_no+ "&academic_year=" + academic_year+ "&batch_no=" + batch_no;
    data += "&action=batch_add";
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");
    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        beforeSend: function() {
            $(".bg_add_update_btn").attr("disabled", "disabled");
            $(".bg_add_update_btn").text("Loading...");
        },
        success: function(data) {
          // document.getElementById('createbatch').disabled = false;

            var obj = JSON.parse(data);
				var msg = obj.msg;
				var status = obj.status;
				var error = obj.error;
             
            if(msg == "create"){
                     sweetalert("batch_created",url);
            document.getElementById('createbatch').disabled = false;
           
                 
            // window.location.href = "index.php?file=print_for_dispatch/list";
            }
        },
        error: function(data) {
            alert("Network Error");
        }
    });
    

// }else{
//     alert("hii");
// }
    }
var main_data = "s1_unique_id=" + s1_unique_id + "&applied_date=" + applied_date + "&hostel_name=" + hostel_name + "&hostel_taluk=" + hostel_taluk + "&hostel_district=" + hostel_district + "&std_name=" + std_name + "&std_app_no=" + std_app_no+ "&academic_year=" + academic_year+ "&batch_no=" + batch_no;
                main_data += "&action=main_batch_add";
                
                $.ajax({
        type: "POST",
        url: ajax_url,
        data: main_data,
       
    });
}





</script>
