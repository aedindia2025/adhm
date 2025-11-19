<?php

session_start();



if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('Invalid CSRF token');
    }
}


function inspection_id()
{
    //    print_r("jii");
    $date = date('Y');
    $st_date = substr($date, 2);
    $month = date('m');
    $datee = $st_date.$month;

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
    $acc_year = $date;
    $a = str_split($acc_year);
    $splt_acc_yr = $a[2].$a[3];

    $stmt = $conn->query("SELECT * FROM inspection where inspection_id LIKE 'INS%' order by inspection_id desc limit 1");

    
    if ($res1 = $stmt->fetch()) {
        $pur_array = explode('-', $res1['inspection_id']);

        $year1 = $pur_array[0];
        $year2 = substr($year1, 0, 2);
        $year = '20'.$year2;
        $booking_no = substr($pur_array[1], 6, 4);
    }

    if ($booking_no == '') {
        $booking_nos = 'INS-'.$splt_acc_yr.'/'.$month.'/0001';
    } else {
        ++$booking_no;
        $booking_nos = 'INS-'.$splt_acc_yr.'/'.$month.'/'.str_pad($booking_no, 4, '0', STR_PAD_LEFT);
    }

    // echo $booking_nos;
    return $booking_nos;
}

$inspection_id = inspection_id(); 


// Form variables
$btn_text = 'Save';
$btn_action = 'create';

$unique_id = '';
$district_name = '';
$taluk_name = '';
$inspection_date = date('Y-m-d');
$hostel_name = '';
$desc_text = '';

$user_name = $_SESSION['user_id'];
$sess_user_type = $_SESSION['sess_user_type'];

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        $uni_dec = str_replace(' ', '+', $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            'unique_id' => $unique_id,
        ];

        $table = 'inspection';

        $columns = [
            'district_name',
            'taluk_name',
            'inspection_date',
		'inspection_id',
            'description',
            'hostel_name',
            'file_name',
        ];

        $table_details = [
            $table,
            $columns,
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {
            $result_values = $result_values->data;
            $district_name = $result_values[0]['district_name'];
            $taluk_name = $result_values[0]['taluk_name'];
            $inspection_date = $result_values[0]['inspection_date'];
$inspection_id = $result_values[0]['inspection_id'];

            $file_name = $result_values[0]['file_name'];
            $description = $result_values[0]['description'];
            $hostel_name = $result_values[0]['hostel_name'];

            $btn_text = 'Update';
            $btn_action = 'update';
        } else {
            $btn_text = 'Error';
            $btn_action = 'error';
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$active_status_options = active_status($is_active);
// $taluk_id = $_SESSION['taluk_id'];
$ses_taluk_id = $_SESSION['taluk_id'];
$ses_district_id = $_SESSION['district_id'];

// $hostel_name_options = hostel_name("","", $ses_district_id);
$hostel_name_list = hostel_name();

$hostel_name_options = select_option_host($hostel_name_list, 'Select Hostel Name', $hostel_name);

$hostel_ids = $_SESSION['hostel_id'];

$taluk_name_options = taluk_name('', $ses_district_id);

$taluk_name_options = select_option($taluk_name_options, 'Select Taluk Name', $taluk_name);

// $hostel_name_options   = dadwo_hostel_list($hostel_ids);

// $hostel_name_options = hostel_name();
// $hostel_name_options = select_option($hostel_name_options, "Select Hostel Name");


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
                        <h4 class="page-title">Inspection</h4>
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
                                            <div class="col-md-6 fm">
                                                <div class="col-md-6 fm">
                                                    <label for="inspection_date" class="form-label">Inspection Date</label>
                                                    <input type="date" class="form-control" id="inspection_date" name="inspection_date" value="<?php echo $inspection_date; ?>" required>
                                                    <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                </div>
                                                <br>
                                                <div class="col-md-6 fm">
                                                    <label for="hostel_name" class="form-label">Taluk Name</label>
                                                 
                                                    <select class="form-control" id="taluk_name" name="taluk_name"  onchange="get_hostel()"  required>
                                                    <?php echo $taluk_name_options; ?>
                                                    </select>
                                                    </div>
<br>
                                                <div class="col-md-6 fm">
                                                    <label for="hostel_name" class="form-label">Hostel Name</label>
                                                 
                                                    <select class="form-control" id="hostel_name" name="hostel_name"  required>
                                                    <?php echo $hostel_name_options; ?>
                                                    </select>
                                                    <input type="hidden" class="form-control" id="pic" name="pic" value="<?php echo $file_name; ?>" required>
                                                    <input type="hidden" class="form-control" id="user_name" name="user_name" value="<?php echo $user_name; ?>" required>
                                                    <input type="hidden" class="form-control" id="user_type" name="user_type" value="<?php echo $sess_user_type; ?>" required>
                                                    <input type="hidden" class="form-control" id="district_name" name="district_name" value="<?php echo $ses_district_id; ?>" required>
                                                    <!-- <input type="text" class="form-control" id="taluk_name" name="taluk_name" value="<?php echo $_SESSION['taluk_id']; ?>" required> -->
                                                    <input type="hidden" class="form-control" id="inspection_id" name="inspection_id" value="<?php echo $inspection_id; ?>" required>
                                                </div>

                                                <div class="col-md-6 fm mt-3">
                                                    <label for="product_category" class="form-label">File Upload</label>
                                                    <input type="file" id="test_file" name="test_file" class="form-control" accept=".doc, .docx, .pdf, .txt, image/*">
                                                </div>
                                                <span id="error_message"></span>

                                                <div class="col-md-9 fm mt-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea id='description' name='description' class='form-control' oninput="description_val(this)"
                                                    value="<?php echo $description; ?>"><?php echo $description; ?></textarea>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="btns">
                                            <?php echo btn_cancel($btn_cancel); ?>
                                            <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                        </div>
                                </div> <!-- end card-body -->
                                </form>
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>


    function hostel_to_taluk(hostel_name = "") {
        
        var ajax_url = sessionStorage.getItem("folder_crud_link");

        if (hostel_name) {
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: {
                    hostel_name: hostel_name,
                    action: "hostel_to_taluk"
                },
                success: function(res) {
                    console.log(res);

                    var details = JSON.parse(res);
                    // alert(details);

                    $("#district_name").val(details['district_name']);
                    $("#taluk_name").val(details['taluk_name']);

                }
            });
        }
    }
</script>