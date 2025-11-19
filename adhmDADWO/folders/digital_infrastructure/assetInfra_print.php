<link href='app-saas.min.css' rel='stylesheet' type='text/css'>
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

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

// Form variables
// $hostel_name            = "";
// $hostel_id              = "";
// $district_name          = "";
// $taluk_name             = "";
// $special_tahsildar      = "";
// $assembly_const         = "";
// $parliment_const        = "";
// $address                = "";
// $hostel_location        = "";
// $urban_type             = "";
// $corporation            = "";
// $municipality           = "";
// $town_panchayat         = "";
// $block                  = "";
// $village_name           = "";
// $hostel_type            = "";
// $gender_type            = "";
// $yob                    = "";
// $sanctioned_strength    = "";
// $distance_btw_phc       = "";
// $phc_name               = "";
// $distance_btw_ps        = "";
// $ps_name                = "";
// $staff_count            = "";
// $latitude               = "";
// $longitude              = "";
// $unique_id              = "";
// $is_active              = 1;


// echo $_GET['unique_id'];

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {

        $unique_id  = $_GET['unique_id'];
        // $where      = [
        //     "unique_id" => $unique_id
        // ];
		$where = "unique_id='$unique_id'";
        $table      =  "digital_infra_creation";

        $columns    = [
            // "hostel_name",
			"(select amc_year from academic_year_creation where  academic_year_creation.unique_id = digital_infra_creation.academic_year) as academic_year",
            "hostel_id",
            "(select district_name from district_name where  district_name.unique_id = digital_infra_creation.district) as district",
			// "district",
			"(select hostel_name from hostel_name where  unique_id = digital_infra_creation.hostel_name) as hostel_name",
            // "hostel_name",
            "(select taluk_name from taluk_creation where taluk_creation.unique_id = digital_infra_creation.taluk) as taluk_name",
            // "special_tahsildar",
            // "assembly_const",
            // "parliment_const",
            "land_type",
            "owner_of_land",
            "reg_of_land", 	
            "area_of_land",
            "con_area_land",
            "existing_demolished",
            "no_floors",
            "toilet_each_floor",
            "(select hostel_type from hostel_type where hostel_type.unique_id = digital_infra_creation.hostel_id) as hostel_type",
            // "(select gender_type from hostel_gender_type where hostel_gender_type.unique_id = hostel_name.gender_type) as gender_type",
            "compound_wall",
            "water_facilities",
            "living_area",
            "living_area_size",
            "no_of_rooms",
            "room_size",
            "no_of_kitchen",
            "kitchen_size",
            "demolished",
            "land_doc_name",
			"land_doc_name",
			"land_doc_name",
			"land_doc_name",
            "unique_id"
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select($table_details, $where);
// print_r($result_values);
        if ($result_values->status) {

            $result_values                      = $result_values->data;

            $hostel_name                        = $result_values[0]["hostel_name"];
            $hostel_id                          = $result_values[0]["hostel_id"];
            $district_name                      = $result_values[0]["district"];
            $taluk_name                         = $result_values[0]["taluk_name"];
            $land_type                  = $result_values[0]["land_type"];
            $owner_of_land                     = $result_values[0]["owner_of_land"];
            $reg_of_land               = $result_values[0]["reg_of_land"];
            $area_of_land                            = $result_values[0]["area_of_land"];
            $con_area_land                    = $result_values[0]["con_area_land"];
            $existing_demolished                         = $result_values[0]["existing_demolished"];
            $no_floors                        = $result_values[0]["no_floors"];
            $toilet_each_floor                       = $result_values[0]["toilet_each_floor"];
            $compound_wall                     = $result_values[0]["compound_wall"];
            $water_facilities                              = $result_values[0]["water_facilities"];
            $living_area                       = $result_values[0]["living_area"];
            $living_area_size                        = $result_values[0]["living_area_size"];
            $no_of_rooms                        = $result_values[0]["no_of_rooms"];
            $room_size                                = $result_values[0]["room_size"];
            $no_of_kitchen                = $result_values[0]["no_of_kitchen"];
            $kitchen_size                   = $result_values[0]["kitchen_size"];
            $demolished                           = $result_values[0]["demolished"];
            $land_doc_name                    = $result_values[0]["land_doc_name"];
            $acc_year                            = $result_values[0]["academic_year"];
            // $staff_count                        = $result_values[0]["staff_count"];
            // $latitude                           = $result_values[0]["latitude"];
            // $longitude                          = $result_values[0]["longitude"];
            // $is_active                          = $result_values[0]["is_active"];
            $unique_id                          = $result_values[0]["unique_id"];
			 $land_doc_name = image_view("digital_infrastructure", $result_values[0]['unique_id'],  $result_values[0]['land_doc_name']);			
    	}
	}
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

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

.mt-2.vendorListHeading p {
    margin-bottom: 0px;
    text-align: center;
    padding: 5px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 50px auto;
}


th {
    background: #ececec;
    color: black;
    font-weight: bold;
}

td,
th {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
    font-size: 18px;
}

@media only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px) {

    table {
        width: 100%;
    }

    /* Force table to not be like tables anymore */
    table,
    thead,
    tbody,
    th,
    td,
    tr {
        display: block;
    }

}
</style>
<div class="card-body">
    <div class="clearfix">
        <div class=" mb-3 mt-2 text-center vendorListHeading2">
            <img src="../../assets/images/ad-logo.png" alt="dark logo" height="50">
        </div>

    </div>
    <div class="row">

        <div class="col-sm-12 mb-2">
            <div class=" mt-2 vendorListHeading">
                <p><b>Hostel Details</b></p>
            </div>
        </div><!-- end col -->
        <div class="col-sm-12 ">
            <!-- <div class="mt-0 float-sm-left"> -->
            <div class="row">
                <div class="col-sm-6">
                    <p class="font-12">Hostel Name: <strong><?= $hostel_name; ?></strong></p>
                </div>
                <div class="col-sm-6">
                    <p class="font-12">Academic Year <strong><?= $acc_year; ?></strong></p>
                </div>
                <div class="col-sm-6">
                    <p class="font-12">Hostel District: <strong><?= $district_name; ?></strong></p>
                </div>
                <div class="col-sm-6">
                    <p class="font-12">Hostel Taluk: <strong><?= $taluk_name; ?></strong></p>
                </div>
            </div>
            <!-- </div> -->
        </div>

        <hr>
        <div class="col-sm-12 mb-2">
            <div class=" mt-2 vendorListHeading">
                <p><b>Facilities Details</b></p>
            </div>
        </div><!-- end col -->
        <div class="col-sm-12 ">
            <div class="mt-0 float-sm-left">
                <div class="row">

                    <div class="col-sm-6">
                        <p class="font-12">Land Detail: <strong><?= disname($land_type); ?></strong></p>
                    </div>
                  <?php if($land_type == 'own_land'){?>
                    <div class="col-sm-6">
                        <p class="font-12">Land Owner Detail: <strong><?= disname($owner_of_land); ?></strong></p>
                    </div>

                    <div class="col-sm-6">
                        <p class="font-12">A-Register of the land: <strong><?= disname($reg_of_land); ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Land Document: <strong><?= $land_doc_name; ?></strong></p>
                    </div>

                    <div class="col-sm-6">
                        <p class="font-12">Total Area of land:<strong><?= $area_of_land; ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Constructed area of land: <strong><?= $con_area_land; ?></strong></p>
                    </div>
                    <?php ;}?>
                    
                    <div class="col-sm-6">
                        <p class="font-12">Existing or Demolished:
                            <strong><?= disname($existing_demolished); ?></strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Compound wall :
                            <strong><?= $compound_wall; ?></strong>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-12">Water facilities : <strong><?= $water_facilities; ?></strong>
                        </p>
                    </div>
                    <hr>
<?php if($existing_demolished == 'existing'){?>

                    <div class="col-sm-12 mb-2">
                        <div class=" mt-2 vendorListHeading">
                            <p><b>Building Details</b></p>
                        </div>
                    </div><!-- end col -->
                    <table id="buildings_sub_datatable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Floor Name</th>
                                <th>No Of Toilet</th>
                                <th>No Of Living Area</th>
                                <th>Living Area Size</th>
                                <th>No Of Room</th>
                                <th>Room Size</th>
                                <th>No Of Kitchen</th>
                                <th>Kitchen Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$table_sub_building = "buildings_sub";
							$columns = [
								"no_floors",
								"toilet_each_floor",
								"living_area",
								"living_area_size",
								"no_of_rooms",
								"room_size",
								"no_of_kitchen",
								"kitchen_size",
								"unique_id"
							];
							$table_details = [
								$table_sub_building,
								$columns
							];
							$where = "is_active= 1 AND is_delete = 0 and form_main_unique_id = '" . $unique_id . "'";
							$result = $pdo->select($table_details, $where);
							// print_r($result);die();
							$total_records = total_records();

							if ($result->status) {

								$res_array = $result->data;
								$s_no = 1;
								foreach ($res_array as $key => $value) { ?>
                            <tr>
                                <td><?= $s_no; ?></td>
                                <td><?= $value['no_floors'] ?></td>
                                <td><?= $value['toilet_each_floor'] ?></td>
                                <td><?= $value['living_area'] ?></td>
                                <td><?= $value['living_area_size'] ?></td>
                                <td><?= $value['no_of_rooms'] ?></td>
                                <td><?= $value['room_size'] ?></td>
                                <td><?= $value['no_of_kitchen'] ?></td>
                                <td><?= $value['kitchen_size'] ?></td>
                            </tr>

                            <?php $s_no++; }
							}
							?>
                        </tbody>
                    </table>
                    <hr>
<?php }?>
                    <div class="col-sm-12 mb-2">
                        <div class=" mt-2 vendorListHeading">
                            <p><b>Facilities Details</b></p>
                        </div>
                    </div><!-- end col -->
                    <table id="buildings_sub_datatable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Facility Type</th>
                                <th>Facility Name</th>
                                <th>Quantity</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$table_facility_details = "digital_infra_facility_sub";
							$columns = [
                                '(SELECT facility_type FROM facility_type_creation WHERE unique_id = ' . $table_facility_details . '.facilities_type ) AS facilities_type',
								'(SELECT facility_name FROM facility_creation WHERE facility_creation.unique_id = ' . $table_facility_details . '.facilities ) AS disbursement_type',
								'quantity',
								'description'
							];
					
							$table_details = [
								$table_facility_details,
								$columns
							];
							$where = "is_active= 1 AND is_delete = 0 and form_main_unique_id = '" . $unique_id . "'";
							$result = $pdo->select($table_details, $where);
							// print_r(	$result);die();
							$total_records = total_records();

							if ($result->status) {

								$res_array = $result->data;
								$s_no = 1;
								foreach ($res_array as $key => $value) { ?>
                            <tr>
                                <td><?= $s_no; ?></td>
                                <td><?= $value['facilities_type'] ?></td>
                                <td><?= $value['disbursement_type'] ?></td>
                                <td><?= $value['quantity'] ?></td>
                                <td><?= $value['description'] ?></td>
                            </tr>

                            <?php $s_no++; }
							}
							?>
                        </tbody>
                    </table>


                </div>
            </div><!-- end col -->
        </div>
    </div>
</div>

<?php
function image_view($folder_name = "", $unique_id = "", $doc_file_name = "")
{
    // echo $dc_file_name;
    $file_names = explode(',', $doc_file_name);
    $image_view = '';

    if ($doc_file_name) {
        foreach ($file_names as $file_key => $doc_file_name) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }

            $cfile_name = explode('.', $doc_file_name);

            if ($doc_file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../../uploads/' . $folder_name . '/' . $doc_file_name . '"  width="20%" ></a>';
                    // $image_view .= '<img src="../../uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="../../assets/images/pdf.png"   width="20%" style="margin-left: 10px;
					width: 29px;
					margin-top: -10px;" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="../../assets/images/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="assets/images/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }

    return $image_view;
}
?>
<script>



function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../../../adhmHostel/uploads/digital_infrastructure' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
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
	var pdfUrl = "../../../adhmHostel/uploads/digital_infrastructure" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "../../../adhmHostel/uploads/digital_infrastructure" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

// function print_pdf(file_name) {
//     // alert("hii");
//     onmouseover = window.open('../../../adhmHostel/uploads/digital_infrastructure' + file_name, 'onmouseover',
//         'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no'
//     );
// }
// function print_view(file_name)
//     {
//        onmouseover= window.open('../../../adhmHostel/uploads/digital_infrastructure'+file_name,'_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     }
</script>