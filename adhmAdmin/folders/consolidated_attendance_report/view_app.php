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
if ($_GET["hostel"]) {

	// echo $_GET["unique_id"];
	$unique_id = $_GET["hostel"];


	$where1 = [
		"hostel_id" => $unique_id
	];

	$table1 = "hostel_name";

	$columns1 = [

		"hostel_name",
		"hostel_id",
		"(select taluk_name from taluk_creation where taluk_creation.unique_id = hostel_name.taluk_name) as taluk_name",
		"(select district_name from district_name where  district_name.unique_id = hostel_name.district_name) as district_name",
		"unique_id",
		"(select count(id) from std_reg_s where bio_reg_status = 1 and hostel_name.unique_id = std_reg_s.hostel_1) as bio_reg_cnt",
		"warden_name",
		"warden_no"
 
	];

	$table_details1 = [
		$table1,
		$columns1
	];

	$result_values1 = $pdo->select($table_details1, $where1);
	// print_r($result_values1);

	if ($result_values1->status) {

		$result_values1 = $result_values1->data;


		$hostel_name = $result_values1[0]["hostel_name"];
		$hostel_id = $result_values1[0]["hostel_id"];
		$taluk_name = $result_values1[0]["taluk_name"];
		$district_name = $result_values1[0]["district_name"];
		$unique_id = $result_values1[0]["unique_id"];
		$bio_reg_cnt = $result_values1[0]["bio_reg_cnt"];
		$warden_name = $result_values1[0]["warden_name"];
		$warden_no = $result_values1[0]["warden_no"];



	}



}

?>

<?php
// include 'header.php' 
?>
<style>
	body {

		font-family: 'Poppins', sans-serif;
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

	h4.mid-hed {
		background: #ffffff;

		font-size: 17px;

		font-weight: 700;
	}

	h4.mid-hed2 {
		background: #deedde;
		padding: 10px;
		font-size: 16px;
	}

	h4.mid-hed3 {
		background: #ffe4e2;
		padding: 10px;
		font-size: 16px;
	}

	.com-h5 h5 {
		font-size: 14px;
		margin-bottom: 11px;
		color: #555;
	}

	table.com-table tr th {
		border-bottom: 1px solid #ccc;
		border-top: 1px solid #ccc;
	}

	table.com-table {
		font-family: arial, sans-serif;
		border-collapse: collapse;
		width: 100%;
		border: 0px;
		margin-bottom: 13px;
	}

	td,
	th {

		text-align: left;
		padding: 8px;
	}

	.iner-spena span {
		font-size: 16px;
		font-weight: 700;
	}

	.iner-spena h5 {
		font-weight: 400;
	}

	h4.mid-hed.GREN {
		color: green;
	}

	h4.mid-hed.RED {
		color: red;
	}

	tr:nth-child(odd) {
		background-color: #f0f0f0;
	}
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<div class="card-body">
	<div class="clearfix">
		<div class=" mb-3 mt-1 text-center vendorListHeading2">
			<img src="../../../assets/images/newlogo.svg" alt="dark logo" height="80">
		</div>

	</div>

	<div class="row com-h5">
		<div class="col-sm-12 mb-2">
			<h4 class="mid-hed" style="border-bottom: 1px solid #ccc;
	padding-bottom: 8px;">Hostel Details</h4>
		</div><!-- end col -->
		<div class="row iner-spena">
			<div class="col-md-7">
				<div class="col-md-12">
					<h5>Hostel Id: <b><?= $hostel_id ?></b></h5>
				</div>
				<div class="col-md-12">
					<h5>Hostel Name: <b><?= $hostel_name ?></b></h5>
				</div>
				<div class="col-md-12">
					<h5>Warden Name: <b><?= $warden_name ?></b></h5>
				</div>
				<div class="col-md-12">
					<h5>Warden No: <b><?= $warden_no ?></b></h5>
				</div>
			</div>
			<div class="col-md-5">
				<div class="col-md-12">
					<h5>Date: <b><?php echo disdate($_GET['date']); ?></b></h5>
				</div>
				<div class="col-md-12">
					<h5>District: <b><?= $district_name; ?></b></h5>
				</div>
				<div class="col-md-12">
					<h5>Taluk: <b><?= $taluk_name; ?></b></h5>
				</div>
				<div class="col-md-12">
					<h5> Registed Count: <span><b><?= $bio_reg_cnt; ?></b></span></h5>
				</div>
			</div>
		</div>
		<div class="col-sm-12 mb-2">
			<h4 class="mid-hed GREN">Present - <?= $_GET['count'] ?></h4>
		</div><!-- end col -->
		<div class="col-md-12">
			<table class="com-table ">
				<tr>
					<th>S.no</th>
					<th>Biometric id</th>
					<th>Name</th>
					<th>Punch time</th>
				</tr>

				<?php

				if ($_GET['type'] == 'morning') {
					$punch_where = " and punch_mrg IS NOT NULL";
				} elseif ($_GET['type'] == 'evening') {
					$punch_where = " and punch_eve IS NOT NULL";
				}

				$columns_list = [
					"'' as s_no",
					"userId",
					"userName",
					"punch_mrg",
					"punch_eve"

				];
				$table_details_list = [
					"dayattreport",
					$columns_list
				];
				$where_list = "hostel_id = '" . $_GET['hostel'] . "' and currentDate = '" . $_GET['date'] . "' $punch_where";

				$result = $pdo->select($table_details_list, $where_list);

				$s_no = 1;
				if ($result->status) {

					$res_array = $result->data;

					$table_data = "";
					if (count($res_array) == 0) {
						$table_data .= "<tr>";

						$table_data .= "<td colspan=9; style='text-align:center'>NO DATA FOUND</td>";
						$table_data .= "</tr>";
					} else {

						foreach ($res_array as $key => $value) {

							if ($_GET['type'] == 'morning') {
								$punch_time = $value['punch_mrg'];
								$punch = date("h:i A", strtotime($punch_time));
							} elseif ($_GET['type'] == 'evening') {
								$punch_time = $value['punch_eve'];
								$punch = date("h:i A", strtotime($punch_time));
							}

							$table_data .= "<tr>";

							$table_data .= "<td>" . $s_no++ . "</td>";
							$table_data .= "<td style = 'text-align : left'>" . $value['userId'] . "</td>";
							$table_data .= "<td style = 'text-align : left'>" . $value['userName'] . "</td>";
							$table_data .= "<td style = 'text-align : left'>" . $punch . "</td>";

							$table_data .= "</tr>";

						}
					}
				}

				?>

				<tbody>
					<?php echo $table_data; ?>
				</tbody>

			</table>
		</div>

<?php
if ($_GET['type'] == 'morning') {
	$absent_punch_where = " and punch_mrg IS NOT NULL";
} elseif ($_GET['type'] == 'evening') {
	$absent_punch_where = " and punch_eve IS NOT NULL";
}

$count_columns = [
"count(id) as cnt"
];

$count_table_details_list = [
	"std_reg_s",
	$count_columns
];
$count_where_list = "hostel_1 = '" . $unique_id . "' AND NOT EXISTS (SELECT 1 FROM dayattreport WHERE std_reg_s.std_reg_no = dayattreport.std_reg_no and currentDate = '" . $_GET['date'] . "' $absent_punch_where)";

$count_values = $pdo->select($count_table_details_list, $count_where_list);
	//  print_r($count_values);

	if ($count_values->status) {

		$count_values = $count_values->data;
		
		$absent_count = $count_values[0]['cnt'];
	

	}


?>

		<div class="col-sm-12 mb-2">
			<h4 class="mid-hed RED">Absent - <?php echo $absent_count;?></h4>
		</div><!-- end col -->
		<div class="col-md-12">
			<table class="com-table ">
				<tr>
					<th>S.no</th>
					<th>Biometric id</th>
					<th>Name</th>

				</tr>

				<?php

				if ($_GET['type'] == 'morning') {
					$absent_punch_where = " and punch_mrg IS NOT NULL";
				} elseif ($_GET['type'] == 'evening') {
					$absent_punch_where = " and punch_eve IS NOT NULL";
				}

				$absent_columns_list = [
					"'' as s_no",
					"std_reg_no",
					"std_name",


				];
				$absent_table_details_list = [
					"std_reg_s",
					$absent_columns_list
				];
				$absent_where_list = "hostel_1 = '" . $unique_id . "' AND NOT EXISTS (SELECT 1 FROM dayattreport WHERE std_reg_s.std_reg_no = dayattreport.std_reg_no and currentDate = '" . $_GET['date'] . "' $absent_punch_where)";

				$absent_result = $pdo->select($absent_table_details_list, $absent_where_list);
				// print_r($absent_result);
				$s_no = 1;
				if ($absent_result->status) {

					$absent_res_array = $absent_result->data;

					$absent_table_data = "";
					if (count($absent_res_array) == 0) {
						$absent_table_data .= "<tr>";

						$absent_table_data .= "<td colspan=9; style='text-align:center'>NO DATA FOUND</td>";
						$absent_table_data .= "</tr>";
					} else {

						foreach ($absent_res_array as $key => $value) {



							$biometric_id = preg_replace("/[^0-9]/", "", $value['std_reg_no']);

							$absent_table_data .= "<tr>";

							$absent_table_data .= "<td>" . $s_no++ . "</td>";
							$absent_table_data .= "<td style = 'text-align : left'>" . $biometric_id . "</td>";
							$absent_table_data .= "<td style = 'text-align : left'>" . $value['std_name'] . "</td>";


							$absent_table_data .= "</tr>";

						}
					}
				}

				?>

				<tbody>
					<?php echo $absent_table_data; ?>
				</tbody>




			</table>
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
	$image_view = '';

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
			$image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../../../adhmDADWO/uploads/maintenance/' . $doc_file_name . '"  width="10%" ></a>';
			// $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
		} else if ($cfile_name[1] == 'pdf') {
			$image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="../../../pdf.png"   width="10%" style="margin-left: 15px;" ></a>';
		} else if (($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
			$image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="../../assets/images/excel.png"  width="10%" style="margin-left: 15px;" ></a>';
		} else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
			$image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="../../assets/images/word.png"  width="10%" style="margin-left: 15px;" ></a>';
		}
	}
	return $image_view;
}
// }


// }
?>
<script>
	function print_view(file_name) {
		var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
			'<iframe id="myIframe" src="../../../adhmDADWO/uploads/maintenance' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
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
		var pdfUrl = "../../../adhmDADWO/uploads/maintenance/" + file_name;
		var link = document.createElement("a");
		link.href = pdfUrl;
		link.download = file_name;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}

	function print(file_name) {
		// Construct the full URL to your Excel file
		var excelUrl = "../../../adhmDADWO/uploads/maintenance/" + file_name;
		var link = document.createElement("a");
		link.href = excelUrl;
		link.download = file_name;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}
</script>