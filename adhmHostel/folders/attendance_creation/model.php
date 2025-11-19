<?php
// include "function.php";
$amc_name_list = academic_year();
$amc_name_list = select_option_acc($amc_name_list);
$unique_id = $_SESSION['sess_user_id'];
// if (isset($_GET["unique_id"])) {
    if ($unique_id) {

       // $unique_id = $unique_id

        // $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
        // $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        // $unique_id  = $get_uni_id; 

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
    }

    // $batch_no = batch_no($academic_year);


?>
<style>
.card-body.brd {
    border: 1px solid #ccc;
}
.common h4 {
    color: #000;
    margin-top: 0px;
    font-size: 14px;
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
                        <h4 class="page-title">Attendance Creation</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body brd">
                            <form class="was-validated">
                                <div class="row  common">
                                <input type="hidden" id="hostel_district" value="<?php echo $district_office;?>">
<input type="hidden" id="hostel_taluk" value="<?php echo $taluk_office;?>">
<input type="hidden" id="hostel_name" value="<?php echo $hostel_name;?>">


                                                  <input type="hidden" id="academic_year">

                                                  

                                    <div class="col-md-4">

                                        <label for="academic_year" >HOSTEL DISTRICT</label>
                                        <h4 ><?php echo district_name($district_office)[0]['district_name'];?></h4>

                                        <label for="academic_year" >ENTRY DATE</label>
                                        <h4><?php echo date('Y-m-d');?></h4>

                                        

                                    </div>
                                    <div class="col-md-5">
                                    <label for="academic_year" >HOSTEL TALUK</label>
                                        <h4><?=taluk_name($taluk_office)[0]['taluk_name'];?></h4>

                                        <label for="academic_year">HOSTEL NAME</label>
                                        <h4><?=hostel_name($hostel_name)[0]['hostel_name'];?></h4>
                                        <!-- <label for="academic_year" >BATCH NO</label>
                                        <h4 id="batch"></h4>
                                        <input type="hidden" id="batch_no" value="<?=$batch_no;?>"> -->
                                       
                                        

                                    </div>
                                    <div class="col-md-3">
                                    

                                        <select type="hidden" name ="acc_year" id="acc_year" class="form-control" >  
<?=$amc_name_list;?>
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
                        <table id="att_cr_datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Selection</th>
                                    <th>S.no</th>
                                    
                                    <!-- <th>Batch No</th> -->
                                    <th>Student Name</th>
                                    <th>Student ID</th>
                                    <!-- <th>Applied Date</th>
                                    <th>Hostel Name</th> -->
                                    
                                </tr>
                            </thead>
                        </table><br>
                        <!-- <div class="col-12 mt-3">
                        <div class="form-group row "> -->
                        <div class="col-md-12" align="right">
                                <!-- Cancel,save and update Buttons -->
                                <?php echo btn_cancel($btn_cancel); ?>
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="att_create()">Submit Attendance</button>
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
         $hostel_id = $_SESSION['hostel_id']; 
         $host_id = hostel_name($hostel_id)[0]['hostel_id'];
         $splt_hos_id = substr($host_id,-3);
        
        
    
    
        // echo "SELECT * FROM apply_application_form where application_no LIKE 'APN%' order by id desc";echo "<br>";
        $stmt = $conn->query("SELECT max(batch_no) as batch_no FROM batch_creation where is_delete = '0' and hostel_name = '".$hostel_id."' order by id desc");
    
    
        // if($res1=$stmt->fetch($stmt))
        if ($res1 = $stmt->fetch()) {
            if($res1['batch_no'] != ''){

            
            $pur_array = explode("-",$res1['batch_no']);
           

            //  echo $pur_array[1];
          
                $booking_no  = $pur_array[1];
            }
            // else{
            //     $booking_no  = '';
            // }
           
        }
        //  $booking_nos = 'APN-' . $splt_acc_yr .'-' . ''. $splt_dis .'-' .'' .$splt_zone.'-' .'' .$splt_host.'-' .'-0001';
        if ($booking_no == ''){
            // echo "ff";
            $booking_nos = $splt_acc_yr.$splt_hos_id.'BAT-'.'0001';
        }
        // else if ($year != date("Y")){
        //     $booking_nos = 'APN-'.date('Y'). $splt_acc_yr . ''.$splt_dis.'' .$splt_zone.'' .$splt_host.'-0001';
        // }
        else {
            $booking_no += 1;
            
        $booking_nos = $splt_acc_yr.$splt_hos_id.'BAT-'.str_pad($booking_no, 4, '0', STR_PAD_LEFT);
        }
    
        return $booking_nos;
    }
?>
<script>
get_academic_year();

function get_academic_year(){
	var acc_year = $("#acc_year").val();
    $("#academic_year").val(acc_year);
}

function att_create() {
   
    const checked = document.querySelectorAll('.myCheck:checked');
    
    for (var i = 0; i < checked.length; i++) {
        var checkbox = checked[i];
        var s1_unique_id = checkbox.parentElement.querySelector('#s1_unique_id').value;
        // alert(form_unique_id);
        
        // alert(po_num);
        var hostel_name = checkbox.parentElement.querySelector('#hostel_name').value;
        // alert(po_date);
        var hostel_taluk = checkbox.parentElement.querySelector('#hostel_taluk').value;
        // alert(po_product_name);
        var hostel_district = checkbox.parentElement.querySelector('#hostel_district').value;
        // alert(bg_month);
        var std_name = checkbox.parentElement.querySelector('#std_name').value;
        var academic_year = checkbox.parentElement.querySelector('#academic_year').value;
        
        var std_reg_no = checkbox.parentElement.querySelector('#std_reg_no').value;
        // var std_app_no = checkbox.parentElement.querySelector('#std_app_no').value;
        
       
        
    
    var data = "s1_unique_id=" + s1_unique_id + "&hostel_name=" + hostel_name + "&hostel_taluk=" + hostel_taluk + "&hostel_district=" + hostel_district + "&std_name=" + std_name + "&std_reg_no=" + std_reg_no+ "&academic_year=" + academic_year;
    data += "&action=att_create";
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
            var obj = JSON.parse(data);
				var msg = obj.msg;
				var status = obj.status;
				var error = obj.error;
            // sweetalert(msg, url);
            if(msg == 'create'){
                sweetalert('create',url);
            }
        },
        error: function(data) {
            alert("Network Error");
        }
    });
    

}
}

</script>
