<?php
// Form variables
$btn_text           = "Save";
$btn_action         = "create";

// $unique_id          = "";
$expenses_type      = "";
$is_active          = 1;

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $unique_id  = $_GET["unique_id"];
        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "disbursement_creation";

        $columns    = [
            "hostel_name",
            "taluk_name",
            "applied_date",
            '(SELECT disbursement_type FROM disbursement_type AS dis WHERE dis.unique_id = ' . $table . '.disbursement_type ) AS disbursement_type',
            '(SELECT amc_year FROM academic_year_creation AS acc_year WHERE acc_year.unique_id = ' . $table . '.academic_year ) AS academic_year',
            "month",
            "connection_no",
            "letter_no",
            "letter_date",
            "disbursement_file",
            '(SELECT staff_name FROM user WHERE user.unique_id = ' . $table . '.warden_name ) AS warden_name',
            "tah_letter_no",
            "tah_letter_date",
            "tah_rec_file",
            '(SELECT staff_name FROM user WHERE user.unique_id = ' . $table . '.tahsildar_name ) AS tahsildar_name',
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
            $dadwo_rec_file          = $result_values[0]["dadwo_rec_file"];
            $dadwo_name         = $result_values[0]["tahsildar_name"];

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

                        <h4 class="page-title">Disbursement Recommended</h4>
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
                                        <div class="head">
                                            <h4>Hostel Disbursement Details</h4>
                                        </div>
                                    <div class="col-md-4 fm">
                                            <p>Hostel Name: <h4><?= $hostel_name ?></h4></p><br>
                                            <label for="simpleinput" class="form-label"</label>
                                            <!-- <input type="text" class="form-control" id="carrier_options" name="carrier_options" value="<?= $carrier_options; ?>" required> -->
                                        
                                        </div>
                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Taluk</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?= $taluk_name?></h4></label>
                                            <!-- <input type="text" class="form-control" id="carrier_options" name="carrier_options" value="<?= $carrier_options; ?>" required> -->
                                        
                                        </div>
                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Applied Date</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?= $applied_date;?></h4></label>
                                            <!-- <input type="text" class="form-control" id="carrier_options" name="carrier_options" value="<?= $carrier_options; ?>" required> -->
                                        
                                        </div>
                                        </div>
                                        <div class="row mb-3">

                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Disbursement Type</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?= $disbursement_type ?></h4></label>

                                        </div>
                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Academic Year</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?=  $academic_year ?></h4></label>
                                        
                                        </div>
                                        

                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Month</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?= $month ?></h4></label>
                                        
                                        </div>

                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Connection No.</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?=$connection_no;?></h4></label>
                                        
                                        </div>

                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Letter No.</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?=$letter_no?></h4></label>
                                        
                                        </div>

                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Letter Date</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?=$letter_date?></h4></label>
                                        
                                        </div>
                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Warden Name</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?=$warden_name?></h4></label>
                                        
                                        </div>
                                        <div class="col-md-6">
                                        
                                            <label for="main_screen" class="form-label">Warden Attached Document</label>

                                            <?php echo $dis_file; ?>
                                        </div>
                                                    
                                    </div>
                                    <hr>
                                    <div class="row">
                                    <div class="head">
                                            <h4>SP. Tahsildar Disbursement Details</h4>
                                        </div>
                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Letter No.</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?=$tah_letter_no?></h4></label>
                                        
                                        </div>

                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Letter Date</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?=$tah_letter_date?></h4></label>
                                        
                                        </div>
                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">SP. Tahsildar Name</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?=$tahsildar_name;?></h4></label>
                                        
                                        </div>
                                        <div class="col-md-6">
                                        
                                            <label for="main_screen" class="form-label">Attached Documents</label>
                                            <img src="assets/images/compare-icon.png" height="30px" width="30px" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" type="button">

                                            <!-- <?php echo $tah_dis_file; ?> -->
                                        </div>

                                        <!-- modal start for po dc comparision -->
                                <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">PO & DC Comparison Attachment </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mt-2">
                                                    <div class="col-md-6">
                                                        <h4 class="text-center">Warden Attachment</h4>
                                                        <object data="../adhmHostel/uploads/disbursement/<?php echo $disbursement_file ?>" type="application/pdf" width="100%" height="400">
                                                            <iframe src="../adhmHostel/uploads/disbursement/<?php echo $disbursement_file ?>" width="100%" height="400">
                                                            </iframe>
                                                        </object>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h4 class="text-center">SP.Tahsildar Attachment</h4>
                                                        <object data="../adhmSt/uploads/disbursement/<?php echo  $tah_rec_file ?>" type="application/pdf" width="100%" height="400">
                                                            <iframe src="../adhmSt/uploads/disbursement/<?php echo  $tah_rec_file ?>" width="100%" height="400">
                                                            </iframe>
                                                        </object>
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->
                                <!-- modal end -->
                                    </div>
                                    <hr>

                                    <div class="row">
                                    <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Letter No.</label>
                                            <input type="text" class="form-control" id="dadwo_letter_no" name="dadwo_letter_no" value="<?= $dadwo_letter_no ?>" required>
                                        
                                        </div>

                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Letter Date</label>
                                            <input type="date" class="form-control" id="dadwo_letter_date" name="dadwo_letter_date" value="<?= $dadwo_letter_date ?>" required>
                                            
                                            <input type="hidden" class="form-control" id="dadwo_login_user_id" name="dadwo_login_user_id" value="<?= $login_user_id ?>" required>
                                            <input type="hidden" class="form-control" id="unique_id" name="unique_id" value="<?= $unique_id ?>" required>

                                        </div>
                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Document Upload</label>
                                            <input type="file" class="form-control" id="test_file" name="test_file">
                                        </div>
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
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $disbursement_file . '\')"><img src="uploads/pdf.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print_excel(\'/' . $disbursement_file . '\')"><img src="uploads/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $disbursement_file . '\')"><img src="uploads/word.png"  height="30px" width="30px" ></a>';
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
                    $image_view .= '<a href="javascript:print_pdf_1(\'/' . $tah_rec_file . '\')"><img src="uploads/pdf.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print_excel(\'/' . $tah_rec_file . '\')"><img src="uploads/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $tah_rec_file . '\')"><img src="uploads/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }
    // print_r($image_view);
    return $image_view;
}
        ?>