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
// Form variables
$btn_text = 'Save';
$btn_action = 'create';

// $unique_id          = "";
$expenses_type = '';
$is_active = 1;

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        $uni_dec = str_replace(' ', '+', $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            'unique_id' => $unique_id,
        ];

        $table = 'disbursement_creation';

        $columns = [
            'hostel_name',
            'taluk_name',
            'applied_date',
            '(SELECT disbursement_type FROM disbursement_type AS dis WHERE dis.unique_id = '.$table.'.disbursement_type ) AS disbursement_type',
            '(SELECT amc_year FROM academic_year_creation AS acc_year WHERE acc_year.unique_id = '.$table.'.academic_year ) AS academic_year',
            'month',
            'connection_no',
            'letter_no',
            'letter_date',
            'disbursement_file',
            '(SELECT staff_name FROM staff_registration WHERE unique_id = '.$table.'.warden_name ) AS warden_name',

            // '(SELECT staff_name FROM user WHERE user.unique_id = ' . $table . '.warden_name ) AS warden_name',
            'tah_letter_no',
            'tah_letter_date',
            'tah_rec_file',
            '(SELECT staff_name FROM staff_registration WHERE unique_id = '.$table.'.tahsildar_name ) AS tahsildar_name',
            'dadwo_letter_no',
            'unique_id',
        ];

        $table_details = [
            $table,
            $columns,
        ];

        $result_values = $pdo->select($table_details, $where);

        // print_r($result_values);die();
        if ($result_values->status) {
            $result_values = $result_values->data;

            $dadwo_letter_date = $result_values[0]['dadwo_letter_date'];

            if ($dadwo_letter_date == '') {
                $dadwo_letter_date = date('Y-m-d');
            } else {
                $dadwo_letter_date = $result_values[0]['dadwo_letter_date'];
            }

            $hostel_name = hostel_name_un($result_values[0]['hostel_name']);
            $taluk_name = taluk_name_un($result_values[0]['taluk_name']);
            $applied_date = $result_values[0]['applied_date'];
            $disbursement_type = $result_values[0]['disbursement_type'];
            $academic_year = $result_values[0]['academic_year'];
            $month = $result_values[0]['month'];
            $connection_no = $result_values[0]['connection_no'];
            $letter_no = $result_values[0]['letter_no'];
            $letter_date = $result_values[0]['letter_date'];
            $disbursement_file = $result_values[0]['disbursement_file'];
            $warden_name = $result_values[0]['warden_name'];
            $tah_letter_no = $result_values[0]['tah_letter_no'];
            $tah_letter_date = $result_values[0]['tah_letter_date'];
            $tah_rec_file = $result_values[0]['tah_rec_file'];
            $tahsildar_name = $result_values[0]['tahsildar_name'];
            $dadwo_letter_no = $result_values[0]['dadwo_letter_no'];

            $dadwo_rec_file = $result_values[0]['dadwo_rec_file'];
            $dadwo_name = $result_values[0]['tahsildar_name'];

            $dis_file = image_view('disbursement', $result_values[0]['unique_id'], $result_values[0]['disbursement_file']);
            $tah_dis_file = image_view1('disbursement', $result_values[0]['unique_id'], $result_values[0]['tah_rec_file']);

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

// $hostel_names = hostel_name($hostel_name);

// $district_name_list = district_name();
// $district_name_list = select_option($district_name_list, "Select District",$district_name);

// $taluk_name_list = taluk_name();
// $taluk_name_list = select_option($taluk_name_list, "Select Taluk",$taluk_name);

// $hostel_name_list = hostel_name();
// $hostel_name_list = select_option($hostel_name_list, "Select Hostel",$hostel_name);

// $disbursement_type_options = disbursement_type($disbursement_type);
// $disbursement_type_options = select_option($disbursement_type_options, "Select Disbursement",$disbursement_type);

$academic_year_options = academic_year($academic_year);
$academic_year_options = select_option_acc($academic_year_options, $academic_year);

$month = date('F');

$login_user_id = $_SESSION['user_id'];

?>

<style>
.head{
    text-align: center;
}
.head {
    background-color: #f3f3f3;
    color: black;
    -webkit-print-color-adjust: exact;
    border: 1px solid #ccc;
    margin-bottom: 20px;
}
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
                                    <div class="row mb-0">  
                                        <div class="head">
                                            <h4>Hostel Disbursement Details</h4>
                                        </div>
                                    <div class="col-md-4 fm">
                                            <p>Hostel Name: <strong><?php echo $hostel_name; ?></strong></p>
                                            
                                            
                                        
                                        </div>
                                        <div class="col-md-4 fm">
										<p>Taluk: <strong><?php echo $taluk_name; ?></strong></p>
                                            
                                        </div>
                                        <div class="col-md-4 fm">
										<p>Applied Date: <strong><?php echo $applied_date; ?></strong></p>
                                          
                                        </div>
                                       

                                        <div class="col-md-4 fm">
										<p>Disbursement Type: <strong><?php echo $disbursement_type; ?></strong></p>
                                           
                                        </div>
                                        <div class="col-md-4 fm">
										<p>Academic Year: <strong><?php echo $academic_year; ?></strong></p>
                                            
                                        </div>
                                        

                                        <div class="col-md-4 fm">
										<p>Month: <strong><?php echo $month; ?></strong></p>
                                            
                                        </div>

                                        <div class="col-md-4 fm">
										<p>Connection No: <strong><?php echo $connection_no; ?></strong></p>
                                           
                                        </div>

                                        <div class="col-md-4 fm">
										<p>Letter N: <strong><?php echo $letter_no; ?></strong></p>
                                           
                                        
                                        </div>

                                        <div class="col-md-4 fm">
										<p>Letter Date: <strong><?php echo $letter_date; ?></strong></p>
                                            
                                        </div>
                                        <div class="col-md-4 fm">
										<p>Warden Name: <strong><?php echo $warden_name; ?></strong></p>
                                            
                                        
                                        </div>
                                        <div class="col-md-6">
                                        <p>Warden Attached Document:  <?php echo $dis_file; ?></p>
                                            
                                        </div>
                                                    
                                    </div>
                                   
                                    <div class="row">
                                    <div class="head">
                                            <h4>SP. Tahsildar Disbursement Details</h4>
                                        </div>
                                        <div class="col-md-4 fm">
										<p>Letter No: <strong><?php echo $tah_letter_no; ?></strong></p>
                                           
                                        
                                        </div>

                                        <div class="col-md-4 fm">
										<p>Letter Date: <strong><?php echo $tah_letter_date; ?></strong></p>
                                          
                                        
                                        </div>
                                        <div class="col-md-4 fm">
										<p>SP. Tahsildar Name: <strong><?php echo $tahsildar_name; ?></strong> </p>
                                           
                                        
                                        </div>
                                        <div class="col-md-6">
                                            <p>Attached Documents:    <img src="assets/images/compare-icon.png" height="25px" width="25px" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" type="button"></p>
                                        </div>
                                        


                                <!-- modal start for po dc comparision -->
                                <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Warden & SP.Tahsildar Comparison Attachment </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mt-2">
                                                    <div class="col-md-6">
                                                        <h4 class="text-center">Warden Attachment</h4>
                                                        <object data="../adhmHostel/uploads/disbursement/<?php echo $disbursement_file; ?>" type="application/pdf" width="100%" height="400">
                                                            <iframe src="../adhmHostel/uploads/disbursement/<?php echo $disbursement_file; ?>" width="100%" height="400">
                                                            </iframe>
                                                        </object>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h4 class="text-center">SP.Tahsildar Attachment</h4>
                                                        <object data="../adhmSt/uploads/disbursement/<?php echo $tah_rec_file; ?>" type="application/pdf" width="100%" height="400">
                                                            <iframe src="../adhmSt/uploads/disbursement/<?php echo $tah_rec_file; ?>" width="100%" height="400">
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
                                            <input type="text" class="form-control" id="dadwo_letter_no" oninput="off_id(this)" name="dadwo_letter_no" value="<?php echo $dadwo_letter_no; ?>" required>
                                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        </div>

                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Letter Date</label>
                                            <input type="date" class="form-control" id="dadwo_letter_date" name="dadwo_letter_date" value="<?php echo $dadwo_letter_date; ?>" required>
                                            
                                            <input type="hidden" class="form-control" id="dadwo_login_user_id" name="dadwo_login_user_id" value="<?php echo $login_user_id; ?>" required>
                                            <input type="hidden" class="form-control" id="unique_id" name="unique_id" value="<?php echo $unique_id; ?>" required>

                                        </div>
                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Document Upload</label>
                                            <input type="file" class="form-control" id="test_file" name="test_file" accept=".doc, .docx, .pdf, .txt, image/*">
                                           
                                        </div>
                                    </div>
                                    <div class="btns mt-3">
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
<script>



    
function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../adhmHostel/uploads/disbursement' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
		'</body></html>';


	var win = window.open("", "", "width=600,height=480,toolbar=no,menubar=no,resizable=yes");

	if (win) {

		win.document.open();

		win.document.write(iframeContent);

		win.document.close();

		var iframe = win.document.getElementById('myIframe');
		iframe.onload = function () {
			var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

			// Prevent right-click context menu inside the iframe
			iframeDoc.addEventListener('contextmenu', function (e) {
				e.preventDefault();
			});

			iframeDoc.addEventListener('keydown', function (e) {
				// Check for specific key combinations
				if ((e.ctrlKey || e.metaKey) && (e.keyCode == 83 || e.keyCode == 67 || e.keyCode == 74 || e.keyCode == 73)) {
					// Prevent default action (e.g., save, copy, downloads, inspect)
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				// Check for F12 key
				if (e.keyCode == 123) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
			});

		};


	} else {
		alert('Please allow popups for this website');
	}
}

function print_pdf(file_name) {
	var pdfUrl = "../adhmHostel/uploads/disbursement" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print_pdf_1(file_name) {
	var pdfUrl = "../adhmSt/uploads/disbursement" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "uploads/disbursement/" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print_excel(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "uploads/installation/" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}


// function print_pdf(file_name) {
//         onmouseover = window.open('../adhmHostel/uploads/disbursement' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     }

//     function print_excel(file_name) {
//         // alert('hi');
//         window.location = 'uploads/installation/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no';
//     }
//     // function print(file_name) {
//     //     onmouseover = window.open('uploads/kra_kpi_form/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     // }
//     function print_view(file_name) {
//         onmouseover = window.open('../adhmHostel/uploads/disbursement' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     }

//     function print_1(file_name) {
//         window.location = 'uploads/po_form/po_attach/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no';
//     }

//     function print_pdf_1(file_name) {
//         onmouseover = window.open('../adhmSt/uploads/disbursement' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     }
//     // function print(file_name) {
//     //     onmouseover = window.open('uploads/kra_kpi_form/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     // }
//     function print_view_1(file_name) {
//         window.open('uploads/po_form/po_attach/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     }
        </script>

        <?php
function image_view($folder_name = '', $unique_id = '', $disbursement_file = '')
{
    // echo $dc_file_name;
    $file_names = explode(',', $disbursement_file);
    $image_view = '';
    if ($disbursement_file) {
        foreach ($file_names as $file_key => $disbursement_file) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= '&nbsp';
                } else {
                    $image_view .= '<br><br>';
                }
            }

            // . $folder_name . '/' . $disbursement_file . '
            $cfile_name = explode('.', $disbursement_file);
            if ($disbursement_file) {
                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/'.$disbursement_file.'\')"><img src="assets/images/images.png"  height="30px" width="30px" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } elseif ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/'.$disbursement_file.'\')"><img src="assets/images/pdf.png"  height="20px" width="20px" ></a>';
                } elseif (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print_excel(\'/'.$disbursement_file.'\')"><img src="assets/images/excel.png"  height="30px" width="30px" ></a>';
                } elseif (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/'.$disbursement_file.'\')"><img src="assets/images/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }

    // print_r($image_view);
    return $image_view;
}

function image_view1($folder_name = '', $unique_id = '', $tah_rec_file = '')
{
    // echo $dc_file_name;
    $file_names = explode(',', $tah_rec_file);
    $image_view = '';
    if ($tah_rec_file) {
        foreach ($file_names as $file_key => $tah_rec_file) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= '&nbsp';
                } else {
                    $image_view .= '<br><br>';
                }
            }
            $cfile_name = explode('.', $tah_rec_file);
            if ($tah_rec_file) {
                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/'.$tah_rec_file.'\')"><img src="adhmHostel/uploads/'.$folder_name.'/'.$tah_rec_file.'"  height="25px" width="25px" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } elseif ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf_1(\'/'.$tah_rec_file.'\')"><img src="assets/images/pdf.png"  height="25px" width="25px" ></a>';
                } elseif (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print_excel(\'/'.$tah_rec_file.'\')"><img src="assets/images/excel.png"  height="25px" width="25px" ></a>';
                } elseif (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/'.$tah_rec_file.'\')"><img src="assets/images/word.png"  height="25px" width="25px" ></a>';
                }
            }
        }
    }

    // print_r($image_view);
    return $image_view;
}
?>