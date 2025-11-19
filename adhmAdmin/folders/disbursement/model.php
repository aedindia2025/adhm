<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Form variables
$btn_text           = "Save";
$btn_action         = "create";

// $unique_id          = "";
$expenses_type      = "";
$is_active          = 1;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}
if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
        $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id; 
        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "disbursement_creation";

        $columns    = [
            '(SELECT district_name FROM district_name WHERE unique_id = ' . $table . '.district_unique_id ) AS district_name',
            '(SELECT taluk_name FROM taluk_creation WHERE unique_id = ' . $table . '.taluk_name ) AS taluk_name',
            '(SELECT hostel_name FROM hostel_name WHERE unique_id = ' . $table . '.hostel_name ) AS hostel_name',
            "applied_date",
            '(SELECT disbursement_type FROM disbursement_type AS dis WHERE dis.unique_id = ' . $table . '.disbursement_type ) AS disbursement_type',
            '(SELECT amc_year FROM academic_year_creation AS acc_year WHERE acc_year.unique_id = ' . $table . '.academic_year ) AS academic_year',
            "month",
            "connection_no",
            "letter_no",
            "letter_date",
            "disbursement_file",
            '(SELECT staff_name FROM staff_registration WHERE unique_id = ' . $table . '.warden_name ) AS warden_name',
            "tah_letter_no",
            "tah_letter_date",
            "tah_rec_file",
            '(SELECT staff_name FROM staff_registration WHERE unique_id = ' . $table . '.tahsildar_name ) AS tahsildar_name',
            "dadwo_letter_no",
            "dadwo_letter_date",
            "dadwo_attach_file",
            '(SELECT staff_name FROM staff_registration WHERE unique_id = ' . $table . '.dadwo_off_name ) AS dadwo_off_name',
            "unique_id"
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select($table_details, $where);

        // print_r($result_values);die();
        if ($result_values->status) {

            $result_values      = $result_values->data;

            $hostel_name          = $result_values[0]["hostel_name"];
            $taluk_name        = $result_values[0]["taluk_name"];
            $applied_date          = $result_values[0]["applied_date"];
            $disbursement_type          = $result_values[0]["disbursement_type"];
            $academic_year        = $result_values[0]["academic_year"];
            $month          = $result_values[0]["month"];
            $connection_no          = $result_values[0]["connection_no"];
            $letter_no        = $result_values[0]["letter_no"];
            $letter_date          = $result_values[0]["letter_date"];
            $disbursement_file          = $result_values[0]["disbursement_file"];
            $warden_name          = $result_values[0]["warden_name"];
            $tah_letter_no          = $result_values[0]["tah_letter_no"];
            $tah_letter_date          = $result_values[0]["tah_letter_date"];
            $tah_rec_file          = $result_values[0]["tah_rec_file"];
            $tahsildar_name         = $result_values[0]["tahsildar_name"];
            $dadwo_letter_no          = $result_values[0]["dadwo_letter_no"];
            $dadwo_letter_date          = $result_values[0]["dadwo_letter_date"];
            $dadwo_rec_file          = $result_values[0]["dadwo_attach_file"];
            $dadwo_name         = $result_values[0]["dadwo_off_name"];

            $dis_file = image_view("disbursement", $result_values[0]['unique_id'],  $result_values[0]['disbursement_file']);
            $tah_dis_file = image_view1("disbursement", $result_values[0]['unique_id'],  $result_values[0]['tah_rec_file']);

            $btn_text           = "Update";
            $btn_action         = "update";
        } 
        else {
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

// $disbursement_type_options = disbursement_type($disbursement_type);
// $disbursement_type_options = select_option($disbursement_type_options, "Select Disbursement",$disbursement_type);

$academic_year_options = academic_year($academic_year);
$academic_year_options = select_option_acc($academic_year_options,$academic_year);

$month = date('F');

$login_user_id = $_SESSION["user_id"];


?>

<style>
.head{
    text-align: center;
}
.head h4 {
    padding: 10px;
    color: #141414;
    font-size: 15px;
    border: 1px solid #ababab;
    margin: 0px;
    background: #e5dfdf;
}
.table td, th {
    border: 0px solid #f4f3f3;
    text-align: left;
    padding: 5px;
    color: #000;
}
.table-over {
    border: 1px solid #ccc;
	padding:10px;
}
td.bold {
    font-weight: 600;
}
table.table {
    margin-bottom: 0px;
}
.page-title-box .page-title {
    font-size: 17px;
  
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
									<div class="col-md-6 table-over">
                                        <div class="head">
                                            <h4>Hostel Disbursement Details</h4>
                                        </div>
												<table class="table">
												<tr>
												<td>Hostel Name</td>
												<td class="bold"><?= $hostel_name ?></td>
												</tr>
												<tr>
												<td>Taluk</td>
												<td class="bold gren"><?= $taluk_name?></td>
												</tr>
												<tr>
												<td>Applied Date</td>
												<td class="bold"><?= $applied_date;?></td>
												</tr>
												<tr>
												<td>Disbursement Type</td>
												<td class="bold"><?= $disbursement_type ?></td>
												</tr>
												<tr>
												<td>Academic Year</td>
												<td class="bold"><?=  $academic_year ?></td>
												</tr>
												<tr>
												<td>Month</td>
												<td class="bold"><?= $month ?></td>
												</tr>
												<tr>
												<td>Connection No.</td>
												<td class="bold"><?=$connection_no;?></td>
												</tr>
												<tr>
												<td>Letter No.</td>
												<td class="bold"><?=$letter_date?></td>
												</tr>
												<tr>
												<td>Warden Name</td>
												<td class="bold"><?=$warden_name?></td>
												</tr>
												</table>
                                      </div>
									  
									  
									  <div class="col-md-6 table-over">
									   <div class="head">
                                            <h4>SP. Tahsildar Disbursement Details</h4>
                                        </div>
									  <table class="table">
												<tr>
												<td>Letter No.</td>
												<td class="bold"><?=$tah_letter_no?></td>
												</tr>
												<tr>
												<td>Letter Date</td>
												<td class="bold"><?=$tah_letter_date?></td>
												</tr>
												<tr>
												<td>SP. Tahsildar Name</td>
												<td class="bold"><?=$tahsildar_name;?></td>
												</tr>
												</table>
												
												<div class="head">
                                            <h4>DADWO Officer Disbursement Details</h4>
											<table class="table">
											<tr>
												<td>DADWO Officer Letter No.</td>
												<td class="bold"><?=$dadwo_letter_no?></td>
												</tr>
												<tr>
												<td>DADWO Officer Letter Date</td>
												<td class="bold"><?=$dadwo_letter_date?></td>
												</tr>
												<tr>
												<td>DADWO Officer Name</td>
												<td class="bold"><?=$dadwo_name;?></td>
												</tr>
												<tr>
												<td>Attached Documents</td>
												<td class="bold"> <img src="assets/images/compare-icon.png" height="50px" width="50px" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" type="button">
</td>
												</tr>
												</table>
												
                                        </div>
									  </div>
                                        
                                        
                                    </div>
                                  
                                   

    
                                        <!-- modal start for po dc comparision -->
                                <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Warden & SP.Tahsildar & DADWO Officer Comparison Attachment </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mt-2">
                                                    <div class="col-md-4">
                                                        <h4 class="text-center">Warden Attachment</h4>
                                                        <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                                        <object data="../adhmHostel/uploads/disbursement/<?php echo $disbursement_file ?>" type="application/pdf" width="100%" height="400">
                                                            <iframe src="../adhmHostel/uploads/disbursement/<?php echo $disbursement_file ?>" width="100%" height="400">
                                                            </iframe>
                                                        </object>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h4 class="text-center">SP.Tahsildar Attachment</h4>
                                                        <object data="../adhmSt/uploads/disbursement/<?php echo  $tah_rec_file ?>" type="application/pdf" width="100%" height="400">
                                                            <iframe src="../adhmSt/uploads/disbursement/<?php echo  $tah_rec_file ?>" width="100%" height="400">
                                                            </iframe>
                                                        </object>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h4 class="text-center">DADWO Officer Attachment</h4>
<?php if(!$dadwo_rec_file){
?>
<center><p>Files Not Uploaded</p></center>
<img src=''>

<?php } else { ?>
                                                        <object data="../adhmDADWO/uploads/disbursement/<?php echo  $dadwo_rec_file ?>" type="application/pdf" width="100%" height="400">
                                                            <iframe src="../adhmDADWO/uploads/disbursement/<?php echo  $dadwo_rec_file ?>" width="100%" height="400">
                                                            </iframe>
                                                        </object>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer mb-5">
                                                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->
                                <!-- modal end -->
                                    </div>
                                    <center><div class="btns mb-3" style="text-align: center;">
                                        <?php echo btn_cancel($btn_cancel); ?>
                                        <!-- <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?> -->
                                    </div> </center>
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
<script>function print_pdf(file_name) {
        onmouseover = window.open('../adhmHostel/uploads/disbursement' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    }

    function print_excel(file_name) {
        // alert('hi');
        window.location = 'uploads/installation/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no';
    }
    // function print(file_name) {
    //     onmouseover = window.open('uploads/kra_kpi_form/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    // }
    function print_view(file_name) {
        onmouseover = window.open('uploads/installation/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    }

    function print_1(file_name) {
        window.location = 'uploads/po_form/po_attach/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no';
    }

    function print_pdf_1(file_name) {
        onmouseover = window.open('../adhmSt/uploads/disbursement' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    }
    // function print(file_name) {
    //     onmouseover = window.open('uploads/kra_kpi_form/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    // }
    function print_view_1(file_name) {
        window.open('uploads/po_form/po_attach/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    }
        </script>

        <?php
function image_view($folder_name = "", $unique_id = "", $disbursement_file = "")
{
    // echo $dc_file_name;
    $file_names = explode(',', $disbursement_file);
    $image_view = '';
    if ($disbursement_file) {
        foreach ($file_names as $file_key => $disbursement_file) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }
            $cfile_name = explode('.', $disbursement_file);
            if ($disbursement_file) {
                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' . $disbursement_file . '\')"><img src="adhmSt/uploads/' . $folder_name . '/' . $disbursement_file . '"  height="30px" width="30px" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $disbursement_file . '\')"><img src="assets/images/pdf.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print_excel(\'/' . $disbursement_file . '\')"><img src="assets/images/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $disbursement_file . '\')"><img src="assets/images/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }
    // print_r($image_view);
    return $image_view;
}

function image_view1($folder_name = "", $unique_id = "", $tah_rec_file = "")
{
    // echo $dc_file_name;
    $file_names = explode(',', $tah_rec_file);
    $image_view = '';
    if ($tah_rec_file) {
        foreach ($file_names as $file_key => $tah_rec_file) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }
            $cfile_name = explode('.', $tah_rec_file);
            if ($tah_rec_file) {
                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' . $tah_rec_file . '\')"><img src="adhmHostel/uploads/' . $folder_name . '/' . $tah_rec_file . '"  height="30px" width="30px" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf_1(\'/' . $tah_rec_file . '\')"><img src="assets/images/pdf.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print_excel(\'/' . $tah_rec_file . '\')"><img src="assets/images/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $tah_rec_file . '\')"><img src="assets/images/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }
    // print_r($image_view);
    return $image_view;
}
        ?>