<?php

$maintanence_no = ma_no();
// Form variables
$btn_text           = "Save";
$btn_action         = "create";

$unique_id          = "";
$district_name      = "";
$is_active          = 1;

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {
        
        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;

        $where      = [

                 "unique_id" => $unique_id
        ];

        $table      =  "maintanance_creation";

        $columns    = [
            "asset_name",
            "asset_category",
            "spend_amount",
            "description",
            "maintanance_no",

            "(select staff_name from staff_registration where staff_registration.unique_id  = maintanance_creation.warden_name) as warden_name",
            "warden_name as warden_name_unique_id",


            "warden_id",
            "(select hostel_name from hostel_name where hostel_name.unique_id = maintanance_creation.hostel_name) as hostel_name",
            "hostel_name as hostel_unique_id",

            "hostel_id",

            "(select district_name from district_name where  district_name.unique_id = maintanance_creation.hostel_district) as hostel_district",
            "hostel_district as district_name_unique_id",

            "(select taluk_name from taluk_creation where taluk_creation.unique_id = maintanance_creation.hostel_taluk) as hostel_taluk",
            "hostel_taluk as hostel_taluk_unique_id",

            "file_name",
            "unique_id",
            "is_active"
            // "file_org_name"
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select($table_details,$where);
        // print_r($result_values);die();

        if ($result_values->status) {

            $result_values      = $result_values->data;

            $asset_name      = $result_values[0]["asset_name"];
            $asset_category      = $result_values[0]["asset_category"];
            $spend_amount      = $result_values[0]["spend_amount"];
            $description      = $result_values[0]["description"];
            $maintanance_no      = $result_values[0]["maintanance_no"];
            $warden_name      = $result_values[0]["warden_name"];
            $warden_id      = $result_values[0]["warden_id"];
            $hostel_name      = $result_values[0]["hostel_name"];
            $hostel_id      = $result_values[0]["hostel_id"];
            $hostel_district      = $result_values[0]["hostel_district"];
            $hostel_taluk      = $result_values[0]["hostel_taluk"];
            $file_names      = $result_values[0]["file_name"];
            $file_org_names     = $result_values[0]["file_org_name"];

            $unique_id                      = $result_values[0]["unique_id"];

            $warden_name_unique_id           = $result_values[0]["warden_name_unique_id"];

            $hostel_unique_id                = $result_values[0]["hostel_unique_id"];

            $district_name_unique_id           = $result_values[0]["district_name_unique_id"];

            $hostel_taluk_unique_id           = $result_values[0]["hostel_taluk_unique_id"];
            
        
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

$facility_type_options = facility_type();
$facility_type_options = select_option($facility_type_options, 'Select',$asset_category);

$facility_name_options = facility_name();
$facility_name_options = select_option($facility_name_options, 'Select',$asset_name);


$district_name_options = district_name();
$district_name_options = select_option($district_name_options, 'Select District', $district_name);

$active_status_options   = active_status($is_active);

?>
<!-- Modal with form -->

<style>
   
    hr{
  height:5px;
  border-width:0;
  background-color:#00A4BD;
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

                        <h4 class="page-title">Maintenance</h4>
                        
                        <div class="row">


<input type="hidden" id="hostel_id" value="<?php echo $hostel_id;?>">

<input type="hidden" id="hostel_name" name="hostel_name" value="<?php echo $hostel_unique_id;?>">

<input type="hidden" id="district_id" name="district_id" value="<?=$district_name_unique_id;?>">

<input type="hidden" id="taluk_id" name="taluk_id" value="<?=$hostel_taluk_unique_id;?>">

<input type="hidden" id="warden_name" value="<?=$warden_name_unique_id;?>">
<input type="hidden" id="warden_id" value="<?=$warden_id;?>">
<input type="hidden" id="maintanance_no" value="<?=$maintanence_no;?>">
<div class="col-4">
    <label for="example-select" class="form-label"> Warden Name</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp; <?php echo $warden_name;?>
  

</div>
<div class="col-4">
    <label for="example-select" class="form-label"> Maintenance no</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp; <?=$maintanence_no;?>
   

</div>
                        </div>
<div class="row">
<div class="col-4">
    <label for="example-select" class="form-label"> Warden ID</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;<?php echo $warden_id;?>
  

</div>
<div class="col-4">
    <label for="example-select" class="form-label"> District </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp; <?php echo $hostel_district;?>
</div>
</div>

<div class="row">
<div class="col-4">
    <label for="example-select" class="form-label"> Hostel Name</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;<?=$hostel_name;?>
  

</div>
<div class="col-4">
    <label for="example-select" class="form-label">Taluk</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $hostel_taluk;?>
</div>
</div>

<div class="row">
<div class="col-4 md-3 mt-2">
    <label for="example-select" class="form-label">Hostel Id</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;&nbsp; <?php echo $hostel_id;?>
  

</div>
<!-- <div class="col-4">
    <label for="example-select" class="form-label">Tahsildar</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;&nbsp; Kumar 
</div> -->
</div>




                    </div>
                </div>
            </div>
            <hr>
            <!-- end page title -->

            <div class="row"></div>
                <div class="col-12">


                    <div class="row">

                        <div class="">
                            <div class="card">
                                <div class="card-body">
                                    <form class="was-validated" autocomplete="off">

                                            <br>
                                            <br>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Asset Category</label>
                                                  <select name ="asset_category" id="asset_category" class="form-control" required onchange="get_asset_name()">  
                                                    <?php echo $facility_type_options;?>
                                                  </select>
                                                </div>

                                                <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Asset Name</label>
                                                  <select name ="asset_name" id="asset_name" class="form-control" required >  
                                                    <?php echo $facility_name_options;?>
                                                  </select>
                                                </div>

                                                <!-- <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Existing Count</label>
                                                <input type="text" class="form-control" id="existing_count" name="existing_count"  value="<?php echo $existing_count; ?>" readonly>

                                                </div>

                                                <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Defect Count</label>
                                                <input type="text" class="form-control" id="defect_count" name="defect_count"  onkeyup="check_count()" value="<?php echo $defect_count; ?>">

                                                </div>
</div>
<div class="row mb-3"> -->
                                                <div class="col-3">
                                                    <label for="example-select" class="form-label">Description </label>
                                                    <textarea name="description" oninput="description_val(this)" id="description" class="form-control" rows="2"col="20"  required><?=$description;?></textarea>
                                                </div>

                                                <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Spend Amount</label>
                                                <input type="text"  oninput="year_only(this)" class="form-control" id="spend_amount" name="spend_amount"   value="<?php echo $spend_amount; ?>">

                                                <input type="text" hidden id="unique_id" name="unique_id" value=<?php echo $unique_id;?>>

                                                </div>
                                             

                                               
                                                <div class="col-3">
                                                    <label for="example-select" class="form-label">File Upload: </label>
                                                    <input type="file"  style="color:green;" accept="application/pdf" id="test_file" name="test_file" accept=".pdf, .doc, .docx, image/*">

                                                    <input type="hidden" class="form-control" id="file_name" name="file_name" value="<?php echo $file_names;?>">
                                                </div>
                                                <span id="error_message"></span>

                                                <!-- <div class="col-md-3 fm">
                                                    <?php if ($file_names == '') { ?>
                                                        <img class="imagePreview" id="cm_image_preview" src='uploads/download.png'></img>
                                                    <?php } else if ($file_names != '') { ?>
                                                        <img class="imagePreview" id="cm_image_preview" src='../adhmHostel/uploads/maintanace/<?php echo $file_names;?>'></img>
                                                    <?php } ?> -->
                                                    
                                                </div>
                                                <!-- \\192.168.0.113\d\xampp\htdocs\adhm\adhmHostel\uploads\maintanance\DFA3B0CYfqiy6RaNVpnLxzutX.pdf -->
                                            </div>
                                            <div class="btns">
                                                <?php echo btn_cancel($btn_cancel); ?>
                                                <?php echo btn_createupdate($folder_name_org,$unique_id,$btn_text); ?>
                                            </div>
                                    </form>
</hr>
                                        </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <?php 
        
        function ma_no($academic_year="")
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
         $splt_acc_yr = $a[2].$a[3];
         $hostel_id = $_SESSION['hostel_id']; 
         $host_id = hostel_name($hostel_id)[0]['hostel_id'];
         $splt_hos_id = substr($host_id,-3);
        
        
    
    
        // echo "SELECT * FROM apply_application_form where application_no LIKE 'APN%' order by id desc";echo "<br>";
        $stmt = $conn->query("SELECT max(maintanance_no) as maintanance_no FROM maintanance_creation where is_delete = '0' and hostel_name = '".$hostel_id."' order by id desc");
    
    
        // if($res1=$stmt->fetch($stmt))
        if ($res1 = $stmt->fetch()) {
            if($res1['maintanance_no'] != ''){

            
            $pur_array = explode("-",$res1['maintanance_no']);
           

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
            $booking_nos = 'ME'.$splt_acc_yr.$splt_hos_id.'-'.'0001';
        }
        // else if ($year != date("Y")){
        //     $booking_nos = 'APN-'.date('Y'). $splt_acc_yr . ''.$splt_dis.'' .$splt_zone.'' .$splt_host.'-0001';
        // }
        else {
            $booking_no += 1;
            
        $booking_nos = 'ME'.$splt_acc_yr.$splt_hos_id.'-'.str_pad($booking_no, 4, '0', STR_PAD_LEFT);
        }
    
        return $booking_nos;
    }


 
        
        ?>
        <script>
               test_file.onchange = evt => {
        const [file] = test_file.files;
        if (file) {
            cm_image_preview.src = URL.createObjectURL(file);
        } else {
            cm_image_preview.src = 'uploads/download.png';
        }
    };
    </script>
        