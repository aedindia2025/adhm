    <!-- <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" /> -->

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
    <style>
    	body {
    		background-color: #fff;
    	}

    	.zone_recom {
    		border: 1px solid #ccc;
    		padding: 14px;
    		margin-bottom: 30px;
    	}

    	.box1 h3 {
    		background-color: #f0f0f0;
    		padding: 4px;
    		text-align: center;
    		font-weight: 700;
    		color: #333;
    		font-size: 14px;
    	}

    	.bd-highlight {
    		font-size: 14px;
    		color: #444;
    	}

    	.contn_info.d-flex h6 {
    		text-align: right;
    		font-size: 11.5px;
    		margin-bottom: 4px;
    	}

    	.contn_info.d-flex h5 {
    		color: #000;
    		font-size: 11.5px;
    		margin-bottom: 4px;
    	}

    	.contn_info.d-flex p {
    		margin-bottom: 4px;
    	}

    	.zone_boxbor {

    		margin-bottom: 20px;

    	}

    	.zone_recom1,
    	.zone_recom3 {
    		/* border: 1px solid #ccc; */
    		padding: 4px;
    	}

    	.zone_recom2 {
    		/* border: 1px solid #ccc; */
    		padding: 4px;
    	}

    	.col-md-4 {
    		width: 33.33333333%;
    		padding-left: 5px;
    		padding-right: 5px;
    	}

    	.col-md-8 {
    		flex: 0 0 auto;
    		width: 66.66666667%;
    	}

    	.col-md-4.wid1 {
    		width: 45%;
    	}

    	.col-md-8.wid2 {
    		width: 55%;
    	}

    	table,
    	th,
    	td {
    		border: 1px solid #ccc;
    		border-collapse: collapse;
    	}

    	th,
    	td {
    		padding: 5px;
    		text-align: left;
    	}

    	.print_icon {
    		text-align: right;
    		font-size: 33px;
    	}
    </style>
    <?php

	include '../../config/dbconfig.php';
	// $p1_unique_id = $_GET['unique_id'];

	if ($_GET["unique_id"]) {

        // echo $_GET["unique_id"];
		$unique_id = $_GET["unique_id"];
        

        $where1 = [
            "unique_id" => $unique_id
        ];

        $table1 = "maintanance_creation";

        $columns1 = [
            "(select staff_name from staff_registration where staff_registration.unique_id  = maintanance_creation.warden_name) as warden_name",
            "(select hostel_name from hostel_name where hostel_name.unique_id = maintanance_creation.hostel_name) as hostel_name",
            "(select taluk_name from taluk_creation where taluk_creation.unique_id = maintanance_creation.hostel_taluk) as hostel_taluk",
            "(select district_name from district_name where  district_name.unique_id = maintanance_creation.hostel_district) as hostel_district",
            "(select amc_year from academic_year_creation where academic_year_creation.unique_id = maintanance_creation.academic_year) as academic_year",
            "maintanance_no",
            "(select facility_type from facility_type_creation where facility_type_creation.unique_id = maintanance_creation.asset_category) as asset_category",
            "(select facility_name from facility_creation where facility_creation.unique_id = maintanance_creation.asset_name) as asset_name",
            "description",
            "spend_amount",
            "file_name",
            "entry_date",
            
            // "count(id) as count"
            //  
        ];

        $table_details1 = [
            $table1,
            $columns1
        ];

        $result_values1 = $pdo->select($table_details1, $where1);
        // print_r($result_values1);

        if ($result_values1->status) {

            $result_values1 = $result_values1->data;

            $warden_name = $result_values1[0]["warden_name"];
            $hostel_name = $result_values1[0]["hostel_name"];
            $hostel_taluk = $result_values1[0]["hostel_taluk"];
            $hostel_district = $result_values1[0]["hostel_district"];
            $academic_year = $result_values1[0]["academic_year"];
            $maintanance_no = $result_values1[0]["maintanance_no"];
            $asset_category = $result_values1[0]["asset_category"];
            $asset_name = $result_values1[0]["asset_name"];
            $description = $result_values1[0]["description"];
            $spend_amount = $result_values1[0]["spend_amount"];
            $file_name = $result_values1[0]["file_name"];
            $invoice = image_view($file_name);
            $entry_date = $result_values1[0]["entry_date"];
            
           
        }

       

}
    
	?>

<?php 
// include 'header.php' 
?>
<style>

body {
    
    font-family: 'Poppins', sans-serif;
}
.card-body {
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
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<div class="card-body">
<div class="clearfix">
                                            <div class=" mb-3 mt-1 text-center vendorListHeading2" >
                                                <img src="../../../assets/images/ad-logo.png" alt="dark logo" height="50">
                                            </div>
                                           
                                        </div>
<div class="row">
                                            <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                   <center> <p><b>Hostel Details</b></p></center>
													
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-sm-12">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												
												<div class="col-sm-9">
                                                <div class="row">
													<div class="col-sm-12">
													<p class="font-12">Academic Year:  <strong><?=$academic_year;?></strong></p>
													</div>
</div>
												<div class="row">
												<div class="col-sm-12">
                                                    <p class="font-12">Warden Name:  <strong><?=$warden_name;?></strong></p>
													</div>
                                                    
<div class="row">
													<div class="col-sm-12">
													<p class="font-12">Hostel District:  <strong><?=$hostel_district;?></strong></p>
													</div>
</div>
<div class="row">
													<div class="col-sm-12">
													<p class="font-12">Hostel Taluk:  <strong><?=$hostel_taluk;?></strong></p>
													</div>
</div>
<div class="row">
													<div class="col-sm-12">
													<p class="font-12">Hostel Name:  <strong><?=$hostel_name;?></strong></p>
													</div>
</div>

													
													
													</div>
													</div>
													
													
                                                </div>
                                            </div><!-- end col -->
											</div>

                                            <div class="row">
                                            <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                   <center> <p><b>Asset Details</b></p></center>
													
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-sm-12">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												
												<div class="col-sm-9">
                                                <div class="row">
													<div class="col-sm-12">
													<p class="font-12">Asset Created Date :  <strong><?=$entry_date;?></strong></p>
													</div>
</div>
											<div class="row">
													<div class="col-sm-12">
													<p class="font-12">Maintanance NO :  <strong><?=$maintanance_no;?></strong></p>
													</div>
													</div>
                                                <div class="row">
													<div class="col-sm-12">
													<p class="font-12">Asset Category :  <strong><?=$asset_category;?></strong></p>
													</div>
</div>
												<div class="row">
												<div class="col-sm-12">
                                                    <p class="font-12">Asset Name :  <strong><?=$asset_name;?></strong></p>
													</div>
                                                    
<div class="row">
													<div class="col-sm-12">
													<p class="font-12">Description :  <strong><?=$description;?></strong></p>
													</div>
</div>
<div class="row">
													<div class="col-sm-12">
													<p class="font-12">Spend Amount :  <strong><?=$spend_amount;?></strong></p>
													</div>
</div>
<div class="row">
													<div class="col-sm-12">
													<p class="font-12">Hostel Name : <strong><?=$hostel_name;?></strong></p>
													</div>
</div>

													
													
													</div>
													</div>
													
													
                                                </div>
                                            </div><!-- end col -->
											</div>

											

                                            <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b>Invoice Details</b></p>
													
                                                </div>
                                            </div><!-- end col -->
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12"> Invoice :  <?php echo $invoice;?></p>
													</div>
													
													
																									
                                                </div>
                                            </div><!-- end col -->
											</div>
											
											
											
</div>
</div>
											
											
											
</div>
</div>
										
<?php include 'footer.php' ?>
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
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../../../adhmHostel/uploads/maintanance/' . $doc_file_name . '"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="../../../pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
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
		'<iframe id="myIframe" src="../../../adhmHostel/uploads/maintanance/' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
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
	var pdfUrl = "../../../adhmHostel/uploads/maintanance/" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "../../../adhmHostel/uploads/maintanance/" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}
    // function print_pdf(file_name)
	// {
		
	// 	 onmouseover=window.open('../../../adhmHostel/uploads/maintanance/' + file_name, '_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	// }
    
    // function print_view(file_name)
    // {
    //    onmouseover= window.open('../../../adhmHostel/uploads/maintanance/'+file_name,'_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    // } 
    </script>

