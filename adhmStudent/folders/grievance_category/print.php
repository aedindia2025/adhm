<link href='../../assets/css/app-saas.min.css' rel='stylesheet' type='text/css'>

<?php 
include '../../config/dbconfig.php';
if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $unique_id  = $_GET["unique_id"];
        $where      = [
            "unique_id" => $unique_id
        ];

        $table_1    =  "grievance_category";
        // $table2  ="std_reg_p1";

        $columns_1    = [
            "acc_year",
            "student_name",
            "std_reg_no",
            "hostel_name",
            "district",
            "taluk",
            "grievance_no",
            "grievance_description",
            "(select grievance_name from grievance_creation where grievance_creation.unique_id = $table_1.grievance_cate)as grievance_cate",
            "file_name",
            // "(select staff_name from staff_registration where staff_registration.hostel_name =$table_1.hostel_main_id and staff_registration.user_type='65cb092facaf836335') as warden_name",
            "unique_id",
            "date_format(created,'%d-%m-%Y') as created",
            "is_active"
        ];

        $table_details   = [
            $table_1,
            $columns_1
        ];

       
        $result_values  = $pdo->select($table_details, $where);

        // print_r($result_values);die();


        if ($result_values->status) {

            $result_values            =   $result_values->data;
          
            $acc_year             =  $result_values[0]["acc_year"];
            $std_reg_no             =  $result_values[0]["std_reg_no"];
            $student_name               =   $result_values[0]["student_name"];
            $hostel_name    =   $result_values[0]["hostel_name"];
            $warden_name    =   $result_values[0]["warden_name"];
           

            $taluk = $result_values[0]["taluk"];  
            $district =  $result_values[0]["district"];
            $grievance_no =  $result_values[0]["grievance_no"];
            $created =  $result_values[0]["created"];
            // acc_yr
            $grievance_description =  $result_values[0]["grievance_description"];
            $grievance_category =  $result_values[0]["grievance_cate"];
            $file_name = $result_values[0]["file_name"];

            $pdf = image_view( $file_name);


            

            

            // // $district    =   $result_values[0]["district"];
            // // $hostel        =   $result_values[0]["hostel"];
            // $job                      =   $result_values[0]["job"];

            // $course                   =    $result_values[0]["course"];
            $is_active                =    $result_values["is_active"];

            $btn_text           = "Update";
            $btn_action         = "update";
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}

// $active_status_options   = active_status($is_active);
?> 

<style>
   
   .card-body {
    margin: 20px;
    border: 1px solid #ccc;
	padding: 20px;
}
.vendorListHeading {
    background-color: #f3f3f3;
    color: black;
    -webkit-print-color-adjust: exact;
	 border: 1px solid #ccc;
}

.vendorListHeading p {
    margin-bottom: 0px;
    text-align: center;
    padding: 5px;
}
p {
    margin-bottom: 5px;
}
	</style>
<div class="card-body">
    <div class="clearfix">
        <div class=" mb-3 text-center">
            <img src="../../assets/images/ad-logo.png" alt="dark logo" height="50">
        </div>

    </div>
    <div class="row">
        <div class="col-sm-12 mb-2">
            <div class=" mt-2 vendorListHeading">
                <p><b>Students / Hostel Details</b></p>

            </div>
        </div><!-- end col -->
        <div class="col-sm-12 ">
            <div class="mt-0 float-sm-left">
                <div class="row">
                    <div class="col-sm-6">
                        <p class="font-12">Entry Date: <strong><?= $created; ?></strong></p>
                    </div>
                    <!-- <div class="col-sm-6">
                        <p class="font-12">Accademic Year: <strong><?= $acc_year;?></strong></p>
                    </div> -->
                    <div class="col-sm-6">
                        <p class="font-12">Student ID: <strong><?= $std_reg_no ; ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Student Name: <strong><?= $student_name; ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Taluk Name: <strong><?=$taluk;?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">District Name: <strong><?=$district;?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Hostel Name: <strong><?=$hostel_name;?></strong></p>
                    </div>
                    <!-- <div class="col-sm-6">
                        <p class="font-12">Warden Name: <strong><?= $warden_name;?></strong></p>
                    </div> -->
                </div></div></div>
    </div>



<div class="col-sm-12 mb-2">
            <div class=" mt-2 vendorListHeading">
                <p><b>Grievance Details</b></p>

            </div>
        </div><!-- end col -->

               
        <div class="col-sm-12 mb-2">
             <div class="mt-0 float-sm-left">
                    <div class="col-sm-4">
                        <p class="font-12">Grievance Category: <strong><?=$grievance_category;?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12">Course: <strong><?=$grievance_description;?></strong></p>
                    </div> 
                </div>
            </div><!-- end col -->
       
    <!-- </div> -->
<!--        
                </div>
            </div> -->
            
            <div class="row">
            <!-- <div class="mt-0 float-sm-left"> -->
        <div class="col-sm">
            <div class="mt-2 vendorListHeading">
            <p><b>Upload Details </b></p>
            </div>
                    <div class="col-sm-2 mt-3">
                        <p class="font-12">Upload Details: <strong><?=$pdf;?></strong></p>
                    </div>
                    
                </div>
            </div>
        </div>
		 
      
<?php
function image_view($doc_file_name = "")
{
    // echo $doc_file_name;
    // $file_names = explode(',', $doc_file_name);
    // $image_view = '';

    // if ($doc_file_name) {
    //     foreach ($file_names as $file_key => $doc_file_name) {
    //         if ($file_key != 0) {
    //             if ($file_key % 4 != 0) {
    //                 $image_view .= "&nbsp";
    //             } else {
    //                 $image_view .= "<br><br>";
    //             }
    //         }

            $cfile_name = explode('.', $doc_file_name);

            if ($doc_file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    // echo "dd";
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../../uploads/grievance_category/' . $doc_file_name . '"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="../../assets/images/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                } 
                // else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="assets/images/excel.png"  height="30px" width="30px" ></a>';
                // } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="assets/images/word.png"  height="30px" width="30px" ></a>';
                // }
            }
            return $image_view;
        }

        ?>

<script>

    
function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../../uploads/grievance_category/' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
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
	var pdfUrl = "../../uploads/grievance_category/" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "../../uploads/grievance_category/" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

    // function print_pdf(file_name)
	// {
		
	// 	 onmouseover=window.open('../../uploads/grievance_category/' + file_name, '_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	// }
    
    // function print_view(file_name)
    // {
    //    onmouseover= window.open('../../uploads/grievance_category/'+file_name,'_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    // } 
    </script>
