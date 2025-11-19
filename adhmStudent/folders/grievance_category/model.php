<?php
// Form variables
// Form variables
$btn_text = 'Save';
$btn_action = 'create';

// $unique_id = "";
$grievance_category = '';
$description = '';

// $district_name = "";
$is_active = 1;

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        // $unique_id = $_GET["unique_id"];

        $uni_dec = str_replace(' ', '+', $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        $where = [
            'unique_id' => $unique_id,
        ];

        $table = 'grievance_category';

        $columns = [
            'grievance_cate',
            'grievance_description',
            'file_name',
            'file_org_name',
        ];

        $table_details = [
            $table,
            $columns,
        ];

        $result_values = $pdo->select($table_details, $where);

        // print_r($result_values);die();

        if ($result_values->status) {
            $result_values = $result_values->data;

            $grievance_category = $result_values[0]['grievance_cate'];

            $description = $result_values[0]['grievance_description'];
            $file_names = $result_values[0]['file_org_name'];
            // $is_active = $result_values[0]["is_active"];
            $unique_ids = $result_values[0]['unique_id'];

            $btn_text = 'Update';
            $btn_action = 'update';
        } else {
            $btn_text = 'Error';
            $btn_action = 'error';
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$grievance_name_list = grievance_name();
$grievance_name_list = select_option($grievance_name_list, 'Select grievance', $grievance_category);

$active_status_options = active_status($is_active);

$gr_no = batch_no();

$district = $_SESSION['hostel_district'];
$taluk = $_SESSION['hostel_taluk'];
$hostel = $_SESSION['hostel_name'];

?>
<!-- Modal with form -->

<style>
    hr {
        height: 5px;
        border-width: 0;
        background-color: #00A4BD;
    }
    #error_message{
        color:red;
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

                        <h4 class="page-title">Grievance</h4>

                        

                    </div>
                </div>
            </div>


                            <!-- <div class="col-md-3 fm">
<label for="example-select" class="form-label">StudentID</label>
<input type="text" id="simpleinput" class="form-control">
</div> -->
                            <form class="was-validated" autocomplete="off">
							<div class="row">
                                <div class="col-4">
                                    <!-- <label for="example-select" class="form-label"> Student Name</label>&nbsp;&nbsp;
                                    &nbsp;&nbsp; -->
                                    <input type="hidden" id="student_name" name="student_name" class="form-control" readonly
                                        value="<?php echo $_SESSION['std_name']; ?>">

                                        <input type="hidden" id="district_id" name="district_id" class="form-control" readonly
                                        value="<?php echo $_SESSION['hostel_district']; ?>">

                                        <input type="hidden" id="taluk_id" name="taluk_id" class="form-control" readonly
                                        value="<?php echo $_SESSION['hostel_taluk']; ?>">

                                        <input type="hidden" id="hostel_main_id" name="hostel_main_id" class="form-control" readonly
                                        value="<?php echo $_SESSION['hostel_name']; ?>">

                                </div>
                                <div class="col-4">
                                    <!-- <label for="example-select" class="form-label"> Grievance no</label>&nbsp;&nbsp;:
                                    &nbsp;&nbsp; -->
                                    <input type="hidden" id="gr_no" name="gr_no" class="form-control" readonly
                                        value="<?php echo $gr_no; ?>">
                                        
                                        <input  id="grievance_id" name="grievance_id" type="hidden" class="form-control" readonly
                                        value="<?php echo $grievance_id; ?>">

                                    <!-- <input type="text" id="simpleinput" class="form-control"> -->

                                </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <!-- <label for="example-select" class="form-label"> Register -->
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                <input type="hidden" class="form-control" name="std_reg_no" id="std_reg_no" readonly
                                    value="<?php echo $_SESSION['std_reg_no']; ?>">


                            </div>
                            <div class="col-4">
                                <!-- <label for="example-select" class="form-label"> District -->
                                </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;<input type="hidden" name="district_name" class="form-control"
                                    id="district_name" readonly value="<?php echo $_SESSION['district_name']; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <!-- <label for="example-select" class="form-label"> Hostel Name</label> -->
                                <input type="hidden" name="hostel_name" class="form-control" id="hostel_name" readonly
                                    value="<?php echo $_SESSION['hostel_names']; ?>">


                            </div>
                            <div class="col-4">
                                <!-- <label for="example-select" class="form-label">Taluk</label> -->
                                <input type="hidden" name="taluk_name" class="form-control" id="taluk_name" readonly
                                    value="<?php echo $_SESSION['taluk_name']; ?>">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <!-- <label for="example-select" class="form-label">Hostel
                                    Id</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp; -->
                                <input type="hidden" name="hostel_id" class="form-control" id="hostel_id" readonly
                                    value="<?php echo $_SESSION['hostel_main_id']; ?>">


                            </div>
                            <div class="col-4">
                                <!-- <label for="example-select"
                                    class="form-label">Tahsildar</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp; -->
                                <input type="hidden" name="tahsildar_name" class="form-control" id="tahsildar_name"
                                    readonly value="<?php echo $_SESSION['tahsildar_name']; ?>">
                            </div>
                        </div>




            
            <!-- end page title -->

            
           

                <div class="row">

                    <div class="">
                        <div class="card">
                            <div class="card-body">


                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                <div class="row mb-3">
                                    <div class="col-md-4 fm">
                                        <label for="example-select" class="form-label">Grievance Category</label>
                                        <select name="grievance_category" id="grievance_category" class="form-control"
                                            required>
                                            <?php echo $grievance_name_list; ?>
                                        </select>

                                    </div>
                               
                                <!-- <input name="hostel_id" id="hostel_id" type="hidden" value="HS123CD"> -->
                                <!-- <input name="student_name" id="student_name" type="hidden" value="Test"> -->
                                <!-- <input name="reg_no" id="reg_no" type="hidden" value="Test"> -->
                                <!-- <input name="hostel_name" id="hostel_name" type="hidden" value="65584660e85as2403322"> -->
                                <!-- <input name="grievance_no" id="grievance_no" type="hidden" value="03"> -->
                                <!-- <input name="district" id="district" type="hidden" value="65584660e85f131401">
                                <input name="taluk" id="taluk" type="hidden" value="65584660e85d24200559">
                                <input name="tahsildar" id="tahsildar" type="hidden" value="Kumar">-->
                                <input type="hidden" class="form-control" name="unique_id" id="unique_id"  value="<?php echo $unique_id; ?>"> 
                                <div class="col-4">
                                    <label for="example-select" class="form-label">Grievance Details</label>
                                    <textarea name="description" id="description" class="form-control" rows="2" col="20" oninput="description_val(this)"
                                        required><?php echo $description; ?></textarea>
                                </div>
                           

                            
                                <!-- <div class="col-5">
                                    <label for="example-select" class="form-label">File Upload: </label>
                                    <input type="file" id="test_file" name="test_file" style="color:green;"
                                        accept="application/pdf">
                                </div> -->

                                <div class="col-md-4 fm">
                                    <label for="product_category" class="form-label">File Upload</label>
                                    <input type="file" id="test_file" name="test_file" class="form-control" value="<?php echo $file_names; ?>" accept=".doc, .docx, .pdf, .txt, image/*">
                                    
                                    
                                </div>
                                

                                <!-- <p id="error" style="color:red"></p> -->
                             </div>

                            <div class="btns">
                                <?php echo btn_cancel($btn_cancel); ?>
                                <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                            </div>
                            </form>
                            
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
       
    </div>

</div>
 </div>
<script>
    //      function grievance_category_cu() {
    //         // alert("jiii");
    //     var internet_status = is_online();
    //     var data = new FormData();
    
    //     if (!internet_status) {
    //         sweetalert("no_internet");
    //         return false;
    //     }
    //     var unique_id = document.getElementById('unique_id').value;
    //     var student_name = document.getElementById('student_name').value;
    //     var grievance_no = document.getElementById('gr_no').value;
    //     // var grievance_id = document.getElementById('grievance_id').value;
    //     var std_reg_no = document.getElementById('std_reg_no').value;
    //     var district_name = document.getElementById('district_name').value;
    //     var taluk_name = document.getElementById('taluk_name').value;
    //     var hostel_name = document.getElementById('hostel_name').value;   
    //     var tahsildar_name = document.getElementById('tahsildar_name').value;
    //     var hostel_id = document.getElementById('hostel_id').value;   
    //     var grievance_category = document.getElementById('grievance_category').value;   
    //      var description = document.getElementById('description').value;
    //     //  var taluk_name = document.getElementById('taluk_name').value;
    //     //  var tahsildar_name = document.getElementById('tahsildar_name').value;
    
    //     var data = new FormData();
    
    //     var image_s = $("#test_file");
    
    //     if (image_s != '') {
    //             for (var i = 0; i < image_s.length; i++) {
    //                 data.append("test_file", document.getElementById('test_file').files[i]);
    
    //             }
    //         } else {
    //             data.append("test_file", '');
    //         }
        
    
       
    //         var action = "createupdate";
    
           
    //         data.append("student_name", student_name);
    //         data.append("gr_no", grievance_no);
    //         // data.append("grievance_id",grievance_id);
    //         data.append("std_reg_no", std_reg_no);
    //         data.append("district_name", district_name);
    //         data.append("taluk_name", taluk_name);
    //         data.append("hostel_name", hostel_name);
    //         data.append("tahsildar_name", tahsildar_name);
    //         data.append("hostel_id", hostel_id);
    //         data.append("grievance_category", grievance_category);
    //         data.append("description", description);
    //         data.append("unique_id", unique_id);
    //         data.append("action", "createupdate");
    
    //         var ajax_url = sessionStorage.getItem("folder_crud_link");
    //         var url = sessionStorage.getItem("list_link");
    
            
    //         $.ajax({
    //             type: "POST",
    //             url: ajax_url,
    //             data: data,
    //             cache: false,
    //             contentType: false,
    //             processData: false,
    //             method: 'POST',
    //             // beforeSend 	: function() {
    //             // 	$(".createupdate_btn").attr("disabled","disabled");
    //             // 	$(".createupdate_btn").text("Loading...");
    //             // },
    //             success		: function(data) {
    //                 var obj     = JSON.parse(data);
    //                 var msg     = obj.msg;
    //                 var status  = obj.status;
    //                 var error   = obj.error;
    
    //                 if (!status) {
    //                     url 	= '';
    //                     $(".createupdate_btn").text("Error");
    //                     console.log(error);
    //                 } else {
    //                     if (msg=="already") {
    //                         // Button Change Attribute
    //                         url 		= '';
    
    //                         $(".createupdate_btn").removeAttr("disabled","disabled");
    //                         if (unique_id) {
    //                             $(".createupdate_btn").text("Update");
    //                         } else {
    //                             $(".createupdate_btn").text("Save");
    //                         }
    //                     }
    //                 }
    
    //                 sweetalert(msg,url);
    //             },
    //             error 		: function(data) {
    //                 alert("Network Error");
    //             }
    //         });
    
    
    //     // } else {
    //     //     sweetalert("form_alert");
    //     // }
    // }
    
</script>
<?php

function batch_no($academic_year = '')
{
    // $date = date("Y");
    // $st_date = substr($date, 4);

    $servername = 'localhost';
    $username = 'root';
    $password = '4/rb5sO2s3TpL4gu';
    $database_name = 'adi_dravidar';

    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully";
    } catch (PDOException $e) {
        // echo "Connection failed: " . $e->getMessage();
    }

    $stmt_acc = $conn->query("SELECT amc_year FROM academic_year_creation where is_delete = '0' order by s_no desc Limit 1");

    $value = $stmt_acc->fetch();

    //  $acmc_year = academic_year($academic_year)[0]['acc_year'];
    $a = str_split($value['amc_year']);
    $splt_acc_yr = $a[0].$a[1].$a[2].$a[3];

    $std_reg_no = $_SESSION['std_reg_no'];
    //  $host_id = hostel_name($hostel_id)[0]['hostel_id'];
    $splt_reg_id = substr($std_reg_no, -3);

    // echo "SELECT * FROM apply_application_form where application_no LIKE 'APN%' order by id desc";echo "<br>";
    $stmt = $conn->query("SELECT * FROM grievance_category where is_delete = '0' and std_reg_no = '".$_SESSION['std_reg_no']."' order by id desc limit 1");

    // echo"SELECT max(grievance_no) as grievance_no FROM grievance_category where is_delete = '0' and std_reg_no = '" . $_SESSION['std_reg_no'] . "' order by id desc limit 1";
    // if($res1=$stmt->fetch($stmt))
    if ($res1 = $stmt->fetch()) {
        // if ($res1['grievance_no'] != '') {

        // echo $res1['grievance_no'];
        $pur_array = explode('-', $res1['grievance_no']);

        //  echo $pur_array[1];

        $booking_no = $pur_array[1];
        // }
        // else{
        //     $booking_no  = '';
        // }
    }
    //  $booking_nos = 'APN-' . $splt_acc_yr .'-' . ''. $splt_dis .'-' .'' .$splt_zone.'-' .'' .$splt_host.'-' .'-0001';
    if ($booking_no == '') {
        // echo "ff";
        $booking_nos = $splt_acc_yr.$splt_reg_id.'GR-0001';
    }
    // else if ($year != date("Y")){
    //     $booking_nos = 'APN-'.date('Y'). $splt_acc_yr . ''.$splt_dis.'' .$splt_zone.'' .$splt_host.'-0001';
    // }
    else {
        ++$booking_no;

        $booking_nos = $splt_acc_yr.$splt_reg_id.'GR-'.str_pad($booking_no, 4, '0', STR_PAD_LEFT);
    }

    return $booking_nos;
}

// function get_grievance_id()
// {
//     $date = date("Y");
//     $st_date = substr($date, 2);
//     $month = date("m");
//     $datee = $st_date . $month;
//     $servername = "localhost";
//     $username = "root";
//     $password = "";
//     $database_name = "adi_dravidar";
//     $hostel_id = $_POST['hostel_id'];

//     try {
//         $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
//         // set the PDO error mode to exception
//         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         //echo "Connected successfully";
//     } catch (PDOException $e) {
//         // echo "Connection failed: " . $e->getMessage();
//     }

//     $acc_year = $date;
//     $a = str_split($acc_year);
//     $splt_acc_yr = $a[2] . $a[3];

//     $stmt = $conn->query("SELECT * FROM grievance_category where is_delete='0' and grievance_id  LIKE 'GRV%' order by id desc");
//     // $stmt = $conn->query("SELECT * FROM grievance_category where is_delete = '0' and std_reg_no = '" . $_SESSION['std_reg_no'] . "' order by id desc limit 1");

// echo "SELECT * FROM grievance_category where is_delete = '0' and grievance_id  LIKE 'GRV%' order by id desc";

//     if ($res1 = $stmt->fetch()) {
//         $pur_array = explode('-', $res1['grievance_id']);

//         $year1 = $pur_array[0];
//         $year2 = substr($year1, 0, 2);
//         $year = '20' . $year2;
//         //  echo $booking_no = $pur_array[1];die();
//         $booking_no = substr($pur_array[1], 16, 4);

//     }
//     if ($booking_no == '') {
//         $booking_nos = 'GRV-' . $splt_acc_yr . '/' . $month . '/' . $hostel_id . '/' . '0001';

//     } else {
//         $booking_no += 1;
//         $booking_nos = 'GRV-' . $splt_acc_yr . '/' . $month . '/' . $hostel_id . '/' . str_pad($booking_no, 4, '0', STR_PAD_LEFT);

//     }

//     return $booking_nos;
// }

// $grievance_id = get_grievance_id();

?>