<?php
session_start();

// Step 1: Check Authentication Status
if (!isset($_SESSION['user_id'])) {
    // Redirect unauthorized users to the login page
    header("Location: login.php");
    exit;
}

// Step 2: Secure File Access (optional)
// Implement authorization checks here if necessary

// Step 3: Fetch Unique ID
if(isset($_GET['unique_id'])) {
    $unique_id = $_GET['unique_id'];
    // Process the unique ID as needed
    // For example, retrieve the corresponding PDF file and display it
    // Make sure to implement appropriate security checks here
} else {
    // Handle case where unique ID is not provided
    echo "Error: Unique ID is missing.";
}
?>




<?php




include '../../config/dbconfig.php';
// include '../header.php';
if (isset ($_GET["unique_id"])) {
    if (!empty ($_GET["unique_id"])) {

        $unique_id = $_GET["unique_id"];
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "inspection";

        $columns = [
            "inspection_id",
            "inspection_date",
            "(select staff_name from staff_registration where staff_registration.unique_id=$table.user_name)as user_type",
            "unique_id",
            "(select district_name from district_name where district_name.unique_id= $table.district_name)as district_name",
            "(select taluk_name from taluk_creation where taluk_creation.unique_id=$table.taluk_name)as taluk_name",
            "(select hostel_name from hostel_name where hostel_name.unique_id=$table.hostel_name)as hostel_name",
            "(select DATE_FORMAT(created, '%Y-%m-%d')) as created",
            // "(select staff_name from staff_registration where  staff_registration.hostel_name = $table.hostel_name and staff_registration.user_type='65cb092facaf836335') as warden_name",
            "description",
            "file_name"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $inspection_id = $result_values[0]["inspection_id"];
            $inspection_date = $result_values[0]["inspection_date"];
            $user_type = $result_values[0]["user_type"];
            $district_name = $result_values[0]["district_name"];
            $taluk_name = $result_values[0]["taluk_name"];
            $hostel_name = $result_values[0]["hostel_name"];
            $created = $result_values[0]["created"];
            $description = $result_values[0]["description"];
            $warden_name = $result_values[0]["warden_name"];
            $file_name = $result_values[0]["file_name"];

            $file_name = image_view($file_name);

            if ($approval_status == '1') {
                $approval_status = 'pending';
            }
            if ($approval_status == '2') {
                $approval_status = 'Approved';
            }
            if ($approval_status == '3') {
                $approval_status = 'Rejected';
            }


            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<style>
    body {

        font-family: 'Poppins', sans-serif;
    }

    .card-body {
        margin: 20px;
        border: 1px solid #ccc;
        padding: 20px;
    }

    .vendorListHeading {
        background-color: #f3f3f3;
        color: black;
        -webkit-print-color-adjust: exact;
    }

    .mt-2.vendorListHeading p {
        margin-bottom: 0px;
        text-align: center;
        padding: 5px;
    }

    .print-1 p {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 13px;
    }
</style>
<div class="card-body print-1">
    <div class="clearfix">
        <div class=" mb-3 text-center">
            <img src="../../assets/images/ad-logo.png" alt="dark logo" height="50">
        </div>

    </div>
    <div class="row">
        <div class="col-sm-12 mb-2">
            <div class=" mt-2 vendorListHeading">
                <p><b>Inspection</b></p>

            </div>
        </div><!-- end col -->
        <div class="col-sm-12 ">
            <div class="mt-0 float-sm-left">
                <div class="row">
                    <div class="col-sm-6">
                        <p class="font-12">Inspection ID: <strong>
                                <?= $inspection_id; ?>
                            </strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Date: <strong>
                                <?= $created; ?>
                            </strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Inspection Date: <strong>
                                <?= $inspection_date; ?>
                            </strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">District Name: <strong>
                                <?= $district_name; ?>
                            </strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Inspect person: <strong>
                                <?= $user_type; ?>
                            </strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Taluk Name: <strong>
                                <?= $taluk_name; ?>
                            </strong></p>
                    </div>

                    <div></div>

                    <div class="col-sm-6">
                        <p class="font-12">Hostel Name: <strong>
                        <?= $hostel_name; ?>
                            </strong></p>
                    </div>

                    <div></div>
<!-- 
                    <div class="col-sm-6">
                        <p class="font-12">Hostel ID: <strong>
                                <?= $warden_name; ?>
                            </strong></p>
                    </div> -->

                    <div></div>

                    <!-- <div class="col-sm-6">
                        <p class="font-12">Warden Name: <strong>
                                <?= $warden_name; ?>
                            </strong></p>
                    </div> -->

                </div>
            </div>
        </div><!-- end col -->
    </div>
    <div class="col-sm-12 mb-2">
        <div class=" mt-2 vendorListHeading">
            <p><b>Description Details</b></p>

        </div>
        <p></p>
        <div class="col-sm-6">
            <p class="font-12">Comment: <strong>
                    <?= $description; ?>
                </strong></p>
        </div>
    </div>


    <div class="col-sm-12 mb-2">
        <div class=" mt-2 vendorListHeading">
            <p><b>File Display:</b></p>

        </div>
        <p></p>
        <div class="row">

            <div class="col-sm-6">
                <p class="font-12">File: <strong>
                    <?= $file_name; ?>
                </strong></p>
            </div>

        </div>



    </div>







</div><!-- end col -->

</div><?php
function image_view($doc_file_name = "")
{
   
            $cfile_name = explode('.', $doc_file_name);

            if ($doc_file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    // echo "dd";
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../../../assets/images/images.png"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="../../../assets/images/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                } 
                // else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/excel.png"  height="30px" width="30px" ></a>';
                // } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/word.png"  height="30px" width="30px" ></a>';
                // }
            }
            return $image_view;
        }
    // }

   
// }
?>
<script>



function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../../../uploads/inspection/' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
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
	var pdfUrl = "../../../uploads/inspection/" + file_name;
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

// function print_pdf(file_name)
// 	{
		
// 		 onmouseover=window.open('../../../uploads/inspection/' + file_name, '_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// 	}
    
//     function print_view(file_name)
//     {
//        onmouseover= window.open('../../../uploads/inspection/'+file_name,'_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     } 
</script>