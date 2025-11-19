<?php include 'header.php' ?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

.load {
		text-align: center;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		display: none;

	}

	i.mdi.mdi-loading.mdi-spin {
		font-size: 75px;
		color: #17a8df;
	}
	.home-bt h3 i {
		background: #fff;
		color: #00aff0;
		border-radius: 50px;
		padding: 4px 7px;
		margin-left: 9px;
	}

	.home-bt h3 {
		color: #fff;
		text-align: end;
		display: inline-block;
		padding: 9px 5px 9px 14px;
		border-radius: 50px;
		font-size: 16px;
		background: linear-gradient(to right, #25bff9, #0890c3);
	}

	h3.form-hed {
		color: #0a93c7;
		font-size: 22px;
		border: 0px solid #14a4d97d;
		border-bottom: 0px solid #14a4d97d;
		padding: 20px;
		border-radius: 0.3125rem;
		background-color: #ffffff;
		font-weight: 800;

		margin: 0px;
	}

	.form-img img {
		width: 100%;
	}

	.form-control:focus {
		box-shadow: none;
		border-color: #13a2d7;
	}

	.normal-imput input {
		color: #000;
		margin-bottom: 0px;
		border: 0px;
		border-bottom: 1px solid #dee2e6;
		border-radius: 0px;
		font-weight: 700;
		padding: 5px;

	}

	.normal-imput label {
		color: #0a93c7;
		margin-bottom: 25px;
		font-size: 13px;
	}

	.normal-imput select {
		color: #000;
		margin-bottom: 15px;
		border: 0px;
		border-bottom: 1px solid #dee2e6;
		border-radius: 0px;
		font-weight: 700;
		padding: 5px;

	}

	.comm-btn {
		background: linear-gradient(to right, #25bff9, #0890c3);
		border: 0px;
		padding: 10px;
		color: #fff;
		border-radius: 5px;
		font-weight: 600;
		font-size: 15px;
		outline: 0;
	}

	.comm-btn:hover {
		background: linear-gradient(to right, #0890c3, #25bff9);
	}

	table.app-table {
		width: 100%;
	}

	.sep-mar {
		border: 1px solid #14a4d97d;
		margin: 0px 10px 10px;

	}

	.model-design img {
		width: 51%;
	}

	div#inputs input {
		width: 32px;
		height: 32px;
		text-align: center;
		border: none;
		border-bottom: 1.5px solid #d2d2d2;
		margin: 0px 10px;
		outline: none;
		font-size: 21px;
		font-weight: 700;
	}

	div#inputs input:focus {
		border-bottom: 1.5px solid #13a2d7;
		outline: none;
	}

	.out-mar {
		border: 1px dashed #0f9bd0;
		margin: 10px;
		background: aliceblue;
	}

	ul.new-tabdesig li a {
		font-size: 12px;
	}

	ul.new-tabdesig {
		background: aliceblue;
		padding: 0px 5px 8px;
		width: 100%;
		text-align: center;
		margin: 0px auto;
		border: 1px solid #cccccc7a;
		border-radius: 6px;
	}

	.nav-pills .nav-link.active,
	.nav-pills .show>.nav-link {
		border: 0px;
	}

	ul.new-tabdesig li {
		margin: 7px 6px 0px;
	}

	ul.new-tabdesig li a {
		background: white;
		color: #3e3c3c;
		font-weight: 700;
		border: 1px solid #e5e5e5 !important;
		outline: 0;
	}

	.col1 small {
		color: black;
	}

	.hr-text {
		line-height: 1em;
		position: relative;
		outline: 0;
		border: 0;
		color: black;
		text-align: center;
		height: 1.5em;
		opacity: 0.5;
	}

	.hr-text:before {
		content: "";
		background: linear-gradient(to right, transparent, #818078, transparent);
		position: absolute;
		left: 0;
		top: 50%;
		width: 100%;
		height: 1px;
	}

	.hr-text:after {
		content: attr(data-content);
		position: relative;
		display: inline-block;
		color: black;
		padding: 0 0.5em;
		line-height: 1.5em;
		color: #818078;
		background-color: #fcfcfa;
	}

	.custom-accordion-title {
		position: relative;
		color: rgb(0 0 0 / 75%);
	}

	.umis-header {
		background: #efefef;
		padding: 11px 14px;
		margin-bottom: 25px;
	}

	tr.spel-tr td {
		color: #000;
		font-size: 14px;
		font-weight: 600;
	}

	.note-box h5 {
		font-size: 15px;
		color: red;
	}

	.note-box {
		border: 1px solid #ccc;
		margin-top: 26px;
	}

	div#audit-trail ul {
		margin-bottom: 0px;
	}

	.note-box p {
		font-size: 16px;
		z-index: 99999999;
		background: white;
		padding: 0px 8px;
		font-weight: 700;
		margin-top: -14px;
		width: 5%;
		margin-bottom: 0px;
	}

	#info-block section {
		border: 1px solid #ccc;
		padding-left: 18px;
	}

	.file-marker>div {
		padding: 0 3px;

		margin-top: -0.8em;

	}

	.box-title {
		background: white none repeat scroll 0 0;
		display: inline-block;
		padding: 0 10px;
		margin-left: 0em;
	}

	div#audit-trail p {
		margin-bottom: 0px;
		color: red;
		font-size: 13px;
		font-weight: 700;
	}

	button.comm-btn.revert {
		background: #fa5c7c;
	}

	.avatar-lg {
		height: 10rem;
		width: 10rem;
	}

	.appli h4 {
		font-size: 13px;
	}

	.boc-app h4 {
		line-height: 23px;
	}

	.boc-app {
		text-align: center;
	}

	th.pro-width {
		width: 14% !important;
	}
</style>
<?php include "config/dbconfig.php"; ?>


<?php



if (isset($_GET["unique_id"])) {

	if (!empty($_GET["unique_id"])) {

		$unique_id = $_GET["unique_id"];

		$where_1 = [
			"unique_id" => $unique_id
		];

		$table_1 = "std_app_s";

		$columns_1 = [

			"unique_id",
			"entry_date",
			"academic_year",
			"application_type",
			"student_type",

			"std_app_no",
			"batch_no",
			"batch_cr_date",
			"hostel_1",
			"hostel_2",
			"hostel_3",
			"status",
			"is_active"
		];

		$table_details_1 = [
			$table_1,
			$columns_1
		];

		$result_values_1 = $pdo->select($table_details_1, $where_1);

		if ($result_values_1->status) {

			$result_values = $result_values_1->data;

			$s1_unique_id = $result_values[0]["unique_id"];

			$entry_date = $result_values[0]["entry_date"];

			$academic_year = $result_values[0]["academic_year"];
			$application_type = $result_values[0]["application_type"];

			$student_type = $result_values[0]["student_type"];

			$aadhar_no = $result_values[0]["aadhar_no"];
			$std_app_no = $result_values[0]["std_app_no"];

			$batch_no = $result_values[0]["batch_no"];
			$batch_cr_date = $result_values[0]["batch_cr_date"];

			$hostel_1 = $result_values[0]["hostel_1"];
			$hostel_2 = $result_values[0]["hostel_2"];
			$hostel_3 = $result_values[0]["hostel_3"];
			$status = $result_values[0]["status"];



			$is_active = $result_values[0]["is_active"];




		}

		$where_aadhaar = [
			"s1_unique_id" => $_GET['unique_id']
		];

		$table_aadhar = "aadhar";

		$columns_aadhar = [

			"adob",
			"agender",
			"aname",
			"aaddress",
			"apincode",
			"afatherName",
			"pro_image",

		];

		$table_details_aadhar = [
			$table_aadhar,
			$columns_aadhar
		];

		$result_values_aadhar = $pdo->select($table_details_aadhar, $where_aadhaar);
		// print_r();

		if ($result_values_aadhar->status) {

			$result_values = $result_values_aadhar->data;

			$adob = $result_values[0]["adob"];

			$agender = $result_values[0]["agender"];

			$aname = $result_values[0]["aname"];
			$aaddress = $result_values[0]["aaddress"];

			$apincode = $result_values[0]["apincode"];

			$afatherName = $result_values[0]["afatherName"];

			$pro_image = $result_values[0]["pro_image"];
			//$image_data = base64_encode($pro_image);
			$image_src = 'data:image/jpeg;base64,' . $pro_image;

			if ($agender == 'M') {
				$agender = 'Male';
				$gender_id = '65584660e85afd2400';
			} else if ($agender == 'F') {
				$agender = 'Female';
				$gender_id = '65584660e85afd2401';
			}

		}

		$where2 = [

			"s1_unique_id" => $s1_unique_id
		];

		$table2 = "std_app_s2";

		$columns2 = [

			"unique_id",
			"entry_date",
			"s1_unique_id",
			"std_app_no",
			"std_name",
			"father_name",
			"age",
			"dob",
			"gender",
			"mobile_no",
			"address",
			"is_active"

		];

		$table_details2 = [
			$table2,
			$columns2
		];

		$result_values2 = $pdo->select($table_details2, $where2);

		if ($result_values2->status) {

			$result_values = $result_values2->data;



			// $std_app_no = $result_values[0]["std_app_no"];

			$std_name = $result_values[0]["std_name"];

			$father_name = $result_values[0]["father_name"];

			$std_age = $result_values[0]["age"];

			$std_dob = $result_values[0]["dob"];

			$gender = $result_values[0]["gender"];

			$std_mobile_no = $result_values[0]["mobile_no"];

			$address = $result_values[0]["address"];


			$is_active = $result_values[0]["is_active"];



			$btn_text = "Update";
			$btn_action = "update";
		}

		//3

		$where3 = [

			"s1_unique_id" => $s1_unique_id
		];

		$table3 = "std_app_emis_s3";

		$columns3 = [

			"unique_id",
			"entry_date",
			"s1_unique_id",
			"emis_no",
			"std_name",
			"group_name",
			"dob",
			"class",
			"school_name",
			"school_district",
			"medium",
			"school_block",
			"is_active"

		];

		$table_details3 = [
			$table3,
			$columns3
		];

		$result_values3 = $pdo->select($table_details3, $where3);

		if ($result_values3->status) {

			$result_values = $result_values3->data;


			$emis_no = $result_values[0]["emis_no"];

			$emis_std_name = $result_values[0]["std_name"];

			$group_name = $result_values[0]["group_name"];
			$medium = $result_values[0]["medium"];

			$emis_std_dob = $result_values[0]["dob"];

			$class = $result_values[0]["class"];

			$school_name = $result_values[0]["school_name"];

			$school_district = $result_values[0]["school_district"];


			$class = $result_values[0]["class"];

			$school_block = $result_values[0]["school_block"];

			$is_active = $result_values[0]["is_active"];



			$btn_text = "Update";
			$btn_action = "update";
		}

		//4

		$where4 = [

			"s1_unique_id" => $s1_unique_id
		];

		$table4 = "std_app_umis_s4";

		$columns4 = [

			"got_addmission",
			"year_studying",
			"lateral_entry",
			"umis_no",
			"umis_name",
			"umis_dob",
			"umis_yoa",
			"caDistrictId",
			"umis_yos",
			"umis_clg_name",
			"umis_clg_add",
			"umis_std_degree",
			"umis_std_course",
			"no_umis_college",
			"no_umis_name",
			"no_umis_course",
			"no_umis_stream",
			"no_umis_clg_district",
			"no_umis_pincode",
			"no_umis_yoa",
			"no_umis_yos",
			"is_active"

		];

		$table_details4 = [
			$table4,
			$columns4
		];

		$result_values4 = $pdo->select($table_details4, $where4);

		if ($result_values4->status) {

			$result_values = $result_values4->data;


			$got_addmission = $result_values[0]["got_addmission"];
			$year_studying = $result_values[0]["year_studying"];
			$lateral_entry = $result_values[0]["lateral_entry"];

			$umis_no = $result_values[0]["umis_no"];

			$umis_name = $result_values[0]["umis_name"];
			$umis_dob = $result_values[0]["umis_dob"];

			$umis_yoa = $result_values[0]["umis_yoa"];
			$umis_yos = $result_values[0]["umis_yos"];
			$caDistrictId = $result_values[0]["caDistrictId"];

			$umis_clg_name = $result_values[0]["umis_clg_name"];

			$umis_clg_add = $result_values[0]["umis_clg_add"];

			$umis_std_degree = $result_values[0]["umis_std_degree"];

			$umis_std_course = $result_values[0]["umis_std_course"];


			$no_umis_name = $result_values[0]["no_umis_name"];
			$no_umis_stream = $result_values[0]["no_umis_stream"];

			$no_umis_clg_district = $result_values[0]["no_umis_clg_district"];


			$no_umis_college = $result_values[0]["no_umis_college"];

			$no_umis_course = $result_values[0]["no_umis_course"];


			$no_umis_pincode = $result_values[0]["no_umis_pincode"];

			$no_umis_yoa = $result_values[0]["no_umis_yoa"];

			$no_umis_yos = $result_values[0]["no_umis_yos"];

			$is_active = $result_values[0]["is_active"];

			$course_name_options = courseName_options("", $no_umis_stream);
			$course_name_options = select_option($course_name_options, "Select", $no_umis_course);



			$btn_text = "Update";
			$btn_action = "update";
		}
		//5

		$where5 = [

			"s1_unique_id" => $s1_unique_id
		];

		$table5 = "std_app_s5";

		$columns5 = [

			"c_no",
			"c_name",
			"c_father_name",
			"c_mother_name",
			"caste_name",
			"sub_caste_name",
			"c_file_name",
			"c_file_org_name",
			"community_pdf",
			"i_no",
			"i_name",
			"income_level",
			"i_father_name",
			"i_mother_name",
			"f_income_source",
			"m_income_source",
			"i_file_name",
			"i_file_org_name",
			"income_pdf",
			"diffabled",
			"category",
			"idnumber",
			"disability_percent",
			"p_file_name",
			"p_file_org_name",
			"is_active",
			"com_name",
			"cert_detail",

		];

		$table_details5 = [
			$table5,
			$columns5
		];

		$result_values5 = $pdo->select($table_details5, $where5);

		if ($result_values5->status) {

			$result_values = $result_values5->data;


			$c_no = $result_values[0]["c_no"];
			$com_name = $result_values[0]["com_name"];
			$cert_detail = $result_values[0]["cert_detail"];

			$c_name = $result_values[0]["c_name"];

			$c_father_name = $result_values[0]["c_father_name"];
			$c_mother_name = $result_values[0]["c_mother_name"];

			$caste_name = $result_values[0]["caste_name"];

			$sub_caste_name = $result_values[0]["sub_caste_name"];

			$c_file_name = $result_values[0]["c_file_name"];

			$c_file_org_name = $result_values[0]["c_file_org_name"];
			$community_pdf = $result_values[0]["community_pdf"];

			$i_no = $result_values[0]["i_no"];


			$i_name = $result_values[0]["i_name"];

			$income_level = $result_values[0]["income_level"];


			$i_father_name = $result_values[0]["i_father_name"];

			$i_mother_name = $result_values[0]["i_mother_name"];

			$f_income_source = $result_values[0]["f_income_source"];


			$m_income_source = $result_values[0]["m_income_source"];

			$i_file_name = $result_values[0]["i_file_name"];
			$income_pdf = $result_values[0]["income_pdf"];



			$i_file_org_name = $result_values[0]["i_file_org_name"];

			$diffabled = $result_values[0]["diffabled"];


			$category = $result_values[0]["category"];

			$idnumber = $result_values[0]["idnumber"];


			$disability_percent = $result_values[0]["disability_percent"];


			$p_file_name = $result_values[0]["p_file_name"];

			$p_file_org_name = $result_values[0]["p_file_org_name"];


			$is_active = $result_values[0]["is_active"];



			$btn_text = "Update";
			$btn_action = "update";
		}

		//6
		$where6 = [

			"s1_unique_id" => $s1_unique_id
		];

		$table6 = "std_app_s6";

		$columns6 = [

			"dob",
			"age",
			"blood_group",
			"email_id",
			"religion",
			"mother_tongue",
			"contact_no",
			"father_name",
			"mother_name",
			"father_qual",
			"mother_qual",
			"father_occu",
			"mother_occu",
			"father_no",
			"guardian_no",
			"umis_no",
			"refugee",
			"orphan",
			"single_parent",
			"first_graduate",
			"door_no",
			"block",
			"district",
			"pincode",
			"street_name",
			"area_name",
			"is_active"

		];

		$table_details6 = [
			$table6,
			$columns6
		];

		$result_values6 = $pdo->select($table_details6, $where6);
		//  print_r($result_values6);

		if ($result_values6->status) {

			$result_values = $result_values6->data;


			$per_dob = $result_values[0]["dob"];

			if ($per_dob != '') {
				$newDateString = date('d-m-Y', strtotime($per_dob));
				$per_dob = $newDateString;
			}

			// 	$date = DateTime::createFromFormat('Y-m-d', $p_dob);
			// $formatted_date = $date->format('d-m-Y');

			// $per_dob = $formatted_date;

			$per_age = $result_values[0]["age"];

			$blood_group = $result_values[0]["blood_group"];

			$email_id = $result_values[0]["email_id"];

			$religion = $result_values[0]["religion"];

			$mother_tongue = $result_values[0]["mother_tongue"];

			$contact_no = $result_values[0]["contact_no"];

			$per_father_name = $result_values[0]["father_name"];


			$per_mother_name = $result_values[0]["mother_name"];

			$father_qual = $result_values[0]["father_qual"];


			$mother_qual = $result_values[0]["mother_qual"];

			$father_occu = $result_values[0]["father_occu"];

			$mother_occu = $result_values[0]["mother_occu"];


			$father_no = $result_values[0]["father_no"];
			// print_r($father_no);
			$guardian_no = $result_values[0]["guardian_no"];



			$refugee = $result_values[0]["refugee"];


			$orphan = $result_values[0]["orphan"];

			$single_parent = $result_values[0]["single_parent"];


			$first_graduate = $result_values[0]["first_graduate"];


			$door_no = $result_values[0]["door_no"];

			$block = $result_values[0]["block"];

			$district = $result_values[0]["district"];

			$pincode = $result_values[0]["pincode"];
			$street_name = $result_values[0]["street_name"];
			$area_name = $result_values[0]["area_name"];

			$is_active = $result_values[0]["is_active"];



			$btn_text = "Update";
			$btn_action = "update";
		}

		$where7 = [

			"s1_unique_id" => $s1_unique_id,
			"is_delete" => '0',
		];

		$table7 = "std_app_s7";

		$columns7 = [

			"GROUP_CONCAT(priority SEPARATOR ', ') AS priority",

		];

		$table_details7 = [
			$table7,
			$columns7
		];

		$result_values7 = $pdo->select($table_details7, $where7);
		// print_r($result_values7);
		if ($result_values7->status) {

			$result_values = $result_values7->data;


			$priority = $result_values[0]["priority"];




			$btn_text = "Update";
			$btn_action = "update";
		}

	}


}

$district_name_options = district_name();
$district_name_options = select_option($district_name_options, "Select Hostel District");

$gender_options = gender_type();
$gender_options = select_option($gender_options, "Select", $gender);



$blood_group_options = blood_group();
$blood_group_options = select_option($blood_group_options, "Select", $blood_group);

$college_name_options = college_name();
$college_name_options = select_option($college_name_options, "Select", $no_umis_college);

$clg_district_options = district_name();
$clg_district_options = select_option($clg_district_options, "Select District", $no_umis_clg_district);

$physically_challenged_options = physically_challenged();
$physically_challenged_options = select_option($physically_challenged_options, "Select", $category);

$priority_type_options = priority('', $priority);
$priority_type_options = select_option($priority_type_options, "Select Priority");






?>
<input type="hidden" id="student_type" value="<?php echo $student_type; ?>">
<input type="hidden" id="application_type" value="<?php echo $application_type; ?>">


<input type="hidden" id="gender_id" value="<?php echo $gender_id; ?>">
<input type="hidden" id="priority_count" value="<?php echo $priority; ?>">


<div class="container">
	<div class="row mt-4">
		<div class="col-md-6">
			<div class="ad-logo">
				<a href="index.php"><img src="img/ad-logo.png"></a>
			</div>
		</div>
		<div class="col-md-6 home-bt text-end">
			<a href="index.php">
				<h3>Home <i class="mdi mdi-home"></i></h3>
			</a>
		</div>
	</div>

	<div class="card mt-4">
		<div class="row ">
			<div class="col-md-12">
				<div class="form-img">
					<img src="img/new-form.png">
				</div>
				<h3 class="form-hed">விடுதி விண்ணப்பம் / Hostel Application</h3>
			</div>
		</div>
		<div class="card-body sep-mar">
			<div id="btnwizard">
				<ul class="nav nav-tabs  mb-3 new-tabdesig" role="tablist">
					<li class="nav-item ds" role="presentation">
						<a href="#tab12" data-bs-toggle="tab" data-toggle="tab" class="nav-link  active" id="tab12-tab"
							aria-controls="tab12" aria-selected="true" role="tab">

							<span class="d-none d-sm-block">ஆதார் உறுதிப்படுத்தல்<br> Aadhaar Confirmation</span>
						</a>
					</li>
					<li class="nav-item ds" role="presentation">
						<a href="#tab22" data-bs-toggle="tab" class="nav-link" id="tab22-tab" aria-controls="tab22"
							aria-selected="false" tabindex="-1" role="tab">

							<span class="d-none d-sm-block">கல்வி விவரங்கள்<br> Educational Details</span>
						</a>
					</li>
					<!--<li class="nav-item" role="presentation">
													<a href="#tab32" data-bs-toggle="tab" data-toggle="tab" class="nav-link " aria-selected="false" tabindex="-1" role="tab">
														
														<span class="d-none d-sm-block"> Institution Details</span>
													</a>
												</li>-->
					<li class="nav-item ds" role="presentation">
						<a href="#tab33" data-bs-toggle="tab" data-toggle="tab" class="nav-link" id="tab33-tab"
							aria-controls="tab33" aria-selected="false" tabindex="-1" role="tab">

							<span class="d-none d-sm-block">சாதி & வருமானச் சான்றிதழ் <br> Community & Income
								Certificate</span>
						</a>
					</li>
					<li class="nav-item ds" role="presentation">
						<a href="#tab34" data-bs-toggle="tab" data-toggle="tab" class="nav-link" aria-selected="false"
							tabindex="-1" role="tab">

							<span class="d-none d-sm-block"> குடும்பம் மற்றும் சுயவிவரம் <br> Family & Personal info
							</span>
						</a>
					</li>
					<li class="nav-item ds" role="presentation">
						<a href="#tab35" data-bs-toggle="tab" data-toggle="tab" class="nav-link " aria-selected="false"
							tabindex="-1" role="tab">

							<span class="d-none d-sm-block">விடுதி தேர்வு <br> Hostel Choice</span>
						</a>
					</li>
				</ul>

				<div class="tab-content mb-0 b-0">

					<div class="tab-pane fade active show" id="tab12" role="tabpanel">
						<!--<div class="row spel-app normal-imput">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-12 ">
										<label class="form-label" for="userName2">விண்ணப்ப எண் / Application No : <span
												style="font-size: 16px;font-weight: 700;padding-left: 6px;">2024ADTWC0001 <span></label>
									</div>

								</div>
							</div>
						</div>-->

						<div class="row spel-app">

							<div class="col-md-6  normal-imput">
								<div class="row">

									<div class="col-md-6">
										<label class="form-label" for="userName2">முழு பெயர் /
											Full Name</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="std_name" oninput="valid_user_name(this)"
											value="<?= $aname; ?>" readonly class="form-control">
									</div>
									<div class="col-md-6">
										<label class="form-label" for="userName2">பிறந்த தேதி /
											DOB</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="std_dob" value="<?= $adob; ?>" class="form-control"
											readonly min="1980-01-01">
									</div>
									<div class="col-md-6">
										<label class="form-label" for="userName2">வயது / Age
										</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="std_age" class="form-control" readonly
											oninput="number_only(this)" value="<?= $std_age; ?>">
									</div>
									<div class="col-md-6">

										<label class="form-label" for="userName2">பாலினம் / Gender
										</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="std_gender" class="form-control" readonly
											value="<?php echo $agender; ?>">
										<!-- <select class="form-select" id="std_gender" onchange="get_gender()">
											<?php echo $gender_options; ?>
										</select> -->
									</div>
									<div class="col-md-6">


										<label class="form-label" for="userName2">ஆதார் இணைக்கப்பட்ட எண் / Aadhar Linked
											No</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="std_mobile_no" class="form-control" maxlength="10"
											oninput="valid_mobile_number(this)" value="<?= $std_mobile_no; ?>">
										<span class="error-message text-danger" id="error-std-mob-no"></span>
									</div>
									<div class="col-md-6">
										<label class="form-label" for="userName2">தந்தையின்
											பெயர் / Father's
											Name</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="father_name" class="form-control"
											oninput="valid_user_name(this)" readonly value="<?= $afatherName; ?>">
									</div>

									<div class="col-md-6">

										<label class="form-label" for="userName2">முகவரி /
											Address </label>
									</div>
									<div class="col-md-6">
										<input type="hidden" id="std_address" class="form-control"
											value="<?= $aaddress; ?>">
										<h5><?= $aaddress; ?></h5>
									</div>






								</div>

							</div>



							<div class="col-md-6  normal-imput">
								<div class="row ">
									<div class="col-md-12 " style="text-align: end;">
										<label class="form-label mb-align" for="userName2">விண்ணப்ப எண் / Application No
											: <span
												style="font-size: 17px;font-weight: 800;padding-left: 6px;border: 0px;color: #00aff0;"><?php echo $std_app_no; ?>
												<span></label>
										<input type="hidden" id="std_app_no" name="std_app_no"
											value="<?php echo $std_app_no; ?>">
										<input type="hidden" id="s1_unique_id" name="s1_unique_id"
											value="<?php echo $s1_unique_id; ?>">
									</div>
									<!--<div class="col-md-6">
										<label class="form-label" for="userName2">விண்ணப்ப எண் / Application No </label>
									</div>
									<div class="col-md-6">
										<input type="text" id="example-" readonly class="form-control" style="font-size: 17px;font-weight: 800;padding-left: 6px;border: 0px;color: #00aff0;" value="2024ADTWC00001">
									</div>-->
									<div class="col-md-8"></div>
									<div class="col-md-4 mt-2 mb-2 appli ">
										<div class="boc-app">
											<div class="">
												<img src="<?php echo $image_src; ?>" class=" avatar-lg img-thumbnail"
													alt="profile-image">
												<h4 class="mb-0 mt-2">விண்ணப்பதாரர் புகைப்படம்</h4>
												<p class="text-muted font-14">Applicant Photo</p>
											</div>
											<!--<img src="img/dummy-image.jpg" >
								<h4> விண்ணப்பதாரர் புகைப்படம் <br>Applicant Photo</h5>
								</div>-->
										</div>

									</div>
								</div>


							</div>

							<div class="col-md-12 mt-3 mb-3 red-top ">
								<aside id="info-block">
									<section class="file-marker">
										<div>
											<div class="box-title">
												Note
											</div>
											<div class="box-contents">
												<div id="audit-trail">
													<ul>
														<li>
															<p>Please note that all communication will be sent to your
																Aadhar
																Linked phone number</p>
														</li>
														<li>
															<p style="margin-bottom: 6px;">Please ensure your Bank
																account is
																linked to the Aadhar Number as well</p>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</section>
								</aside>
							</div>

							<div class="col-md-12 mt-0">
								<div class="form-check form-check-inline">
									<label class="form-check-label" for="customCheck3">I confirm that the above details
										are mine and correct</label>
									<input type="checkbox" class="form-check-input" id="first_check" name="first_check"
										onclick="get_first_check(this.value)" value="YES">
								</div>
							</div>



						</div>
						<div class="float-end mt-3">
							<input type="button" class="btn btn-info" name="next" id="aadhar_btn"
								value="Save & Continue" onclick="aadhar_confirmation_add()" disabled>
							<!--<input type="button" class="btn btn-info button-last" name="last" value="Last">-->
						</div>
						<div class="float-start mt-3">
							<!--<input type="button" class="btn btn-info button-first" name="first" value="First">-->
							<input type="button" class="btn btn-info button-previous disabled" name="previous"
								value="Previous">
						</div>

						<div class="clearfix"></div>
					</div>

					<div class="tab-pane fade" id="tab22" role="tabpanel">


						<div class="" id="emis_div" style="display:none">
							<div class=" ">
								<div class="umis-header">
									<h5 class="m-0">
										EMIS

									</h5>
								</div>

								<div>
									<div class="">
										<div class="row spel-app">
											<div class="col-md-6 normal-imput">
												<div class="row">
													<div class="col-md-6 ">
														<label class="form-label" for="userName2">EMIS Id </label>
													</div>
													<!-- <form class="was-validated" id=""> -->
													<div class="col-md-6">
														<input type="text" id="emis_no" class="form-control"
															maxlength="10" minlength="10" oninput="number_only(this)"
															onkeyup="empty_emis()" value="<?= $emis_no; ?>">
													</div>
													<div class="col-md-12">
														<small class="mt-3">Please see EMIS Id in school id card and
															enter full 10 digit number</small>
													</div>
												</div>
											</div>

											<div class="col-md-2">
												<input type="button" data-bs-toggle="modal"
													data-bs-target="#warning-alert-modal" class="comm-btn"
													onclick="toggleDiv_emis()" value="Go">
											</div>
											<div class="col-md-4">
												<input type="button" class="comm-btn new-emis" onclick="cancel_app()" value="Cancel">
											</div>



										</div>





									</div>

									<div class="row spel-app mt-2" id="hiddenDiv" style="display: none;">
										<div class="col-md-6 normal-imput">
											<div class="row">
												<input type="hidden" id="emis_father_name" class="form-control">
												<input type="hidden" id="emis_mother_name" class="form-control">
												<input type="hidden" id="emis_father_occupation" class="form-control">
												<input type="hidden" id="emis_mother_occupation" class="form-control">
												<input type="hidden" id="group_code_id" class="form-control">
												<input type="hidden" id="community_name" class="form-control">
												<input type="hidden" id="class_section" class="form-control">
												<input type="hidden" id="udise_code" class="form-control">
												<div class="col-md-6">
													<label class="form-label" for="userName2"> முழு பெயர் / Full Name
													</label>
												</div>
												<div class="col-md-6">
													<input type="text" id="emis_name" class="form-control"
														oninput="valid_user_name(this)" value="<?= $emis_std_name; ?>">
												</div>

												<div class="col-md-6">
													<label class="form-label" for="userName2">பிறந்த தேதி / Date of
														Birth</label>
												</div>
												<div class="col-md-6">
													<input type="date" id="emis_dob" class="form-control"
														value="<?= $emis_std_dob; ?>">
												</div>
												<div class="col-md-6">

													<label class="form-label" for="userName2">வகுப்பு / Class </label>
												</div>
												<div class="col-md-6">
													<input type="text" id="emis_class" class="form-control"
														value="<?= $class; ?>">
												</div>
												<div class="col-md-6">

													<label class="form-label" for="userName2" id="group_lbl">குழு பெயர்
														/ Group
														name</label>
												</div>
												<div class="col-md-6">
													<input type="text" id="emis_group" class="form-control"
														value="<?= $group_name; ?>">

												</div>



											</div>
										</div>
										<div class="col-md-6  normal-imput">
											<div class="row ">
												<div class="col-md-6">
													<label class="form-label" for="userName2">வகை / Medium </label>
												</div>
												<div class="col-md-6">
													<input type="text" id="emis_medium" class="form-control"
														value="<?= $medium; ?>">
												</div>

												<div class="col-md-6">
													<label class="form-label" for="userName2">பள்ளி பெயர் / School
														Name</label>
												</div>
												<div class="col-md-6">
													<input type="text" id="emis_school_name" class="form-control"
														value="<?= $school_name; ?>">
												</div>

												<div class="col-md-6">
													<label class="form-label" for="userName2">பள்ளித் தொகுதி / School
														Block </label>
												</div>
												<div class="col-md-6">
													<input type="text" id="emis_school_block" class="form-control"
														value="<?= $school_block; ?>">

												</div>
												<div class="col-md-6">
													<label class="form-label" for="userName2">பள்ளி மாவட்டம் / School
														District</label>
												</div>
												<div class="col-md-6">
													<input type="text" id="emis_school_district" class="form-control"
														value="<?= $school_district; ?>">
												</div>


											</div>
										</div>
										<hr>
										<div class="col-md-12 mt-0">
											<div class="form-check form-check-inline">
												<label class="form-check-label" for="customCheck3">I confirm that the
													above details
													are mine and correct</label>
												<input type="checkbox" class="form-check-input" id="emis_check"
													name="emis_check" onclick="get_emis_check()" value="YES">
											</div><br><br>

										</div>
										<div class="buttn">
											<div class="float-end mt-3">
												<input type="button" class="btn btn-info" name="next"
													value="Save & Continue" id="emis_btn" onclick="emis_details_add()"
													disabled>

											</div>
											<div class="float-start mt-3">
												<!--<input type="button" class="btn btn-info button-first" name="first" value="First">-->
												<button type="button" class="btn btn-info" name="previous"
													onclick="back(this.value)" value="2">Previous</button>
											</div>

											<div class="clearfix"></div>
										</div>

									</div>

								</div>
							</div>

						</div>


						<div class=" mb-0 mt-3" id="umis_div" style="display: block">
							<div class="umis-header">
								<h5 class="m-0">UMIS
								</h5>
							</div>
							<div>
								<div class="">
									<div class="row spel-app mt-3 ">
										<div class="col-md-12 normal-imput">

											<div class="row" id="yr_std_div">
												<div class="col-md-6">
													<label class="form-label" for="userName2">நீங்கள் எந்த வருடம்
														படிக்கிறீர்கள்? / Which Year are you Studying? </label>
												</div>
												<div class="col-md-4">
													<select class="form-select" id="yr_stdy" onchange="get_lat_div()">
														<option selected="">Select</option>
														<option value="1" <?php if ($year_studying == "1") {
															echo "selected";
														} ?>>Year 1</option>
														<option value="2" <?php if ($year_studying == "2") {
															echo "selected";
														} ?>>Year 2</option>
														<option value="3" <?php if ($year_studying == "3") {
															echo "selected";
														} ?>>Year 3</option>
														<option value="4" <?php if ($year_studying == "4") {
															echo "selected";
														} ?>>Year 4</option>
														<option value="5" <?php if ($year_studying == "5") {
															echo "selected";
														} ?>>Year 5</option>
													</select>
												</div>
												<div class="col-md-2">


												<input type="button" class="comm-btn new-emis"
													onclick="cancel_app()" value="Cancel">




											</div>
											</div>

											<div class="row" id="lat_div" style="display: none">
												<div class="col-md-6">
													<label class="form-label" for="userName2">நீங்கள் லேட்டரல் என்ட்ரி
														மாணவரா? / Are You a Lateral Entry? </label>
												</div>
												<div class="col-md-4">
													<select class="form-select" id="lat_entry"
														onchange="get_have_umis()">
														<option selected value="">select</option>
														<option value="Yes" <?php if ($lateral_entry == 'Yes') {
															echo 'selected';
														} ?>>Yes</option>
														<option value="No" <?php if ($lateral_entry == 'No') {
															echo 'selected';
														} ?>>No</option>
													</select>
												</div>
											</div>

											<div class="row" id="have_umis_div" style="display:none">
												<div class="col-md-6">
													<label class="form-label" for="userName2">உங்களுடைய UMIS எண்
														கிடைத்ததா? / Have
														you got yours UMIS Id? </label>
												</div>
												<div class="col-md-4">
													<select class="form-select" id="umisSelect" onchange="get_values()">
														<option selected value="">select</option>
														<option value="Yes" <?php if ($got_addmission == 'Yes') {
															echo 'selected';
														} ?>>Yes</option>
														<option value="No" <?php if ($got_addmission == 'No') {
															echo 'selected';
														} ?>>No</option>
													</select>
												</div>
											</div>

										</div>
									</div>
									<div class="" id="havingUmis" style="display: none;">
										<div class="row spel-app mt-2">
											<div class="col-md-6 normal-imput ">
												<div class="row">
													<div class="col-md-6">
														<label class="form-label" for="userName2">UMIS Id <br>
															<small>Please enter UMIS Id of college
																admission</small></label>
													</div>
													<div class="col-md-6">
														<input type="text" id="umis_no" class="form-control"
															oninput="number_only(this)" onkeyup="empty_umis()"
															value="<?= $umis_no; ?>" minlength="12" maxlength="12">

													</div>

												</div>
											</div>
											<div class="col-md-5">
												<div class="row">
													<div class="col-md-12">
														<input type="button" data-bs-toggle="modal"
															data-bs-target="#warning-alert-modal" class="comm-btn mb-bt"
															onclick="toggleDiv_umis()" value="Go">
													</div>
												</div>
											</div>
										</div>




										<div class="row spel-app mt-3" id="toggleUmis" style="display: none;">
											<div class="col-md-6 normal-imput">
												<div class="row">
													<div class="col-md-6">
														<label class="form-label" for="userName2">முழு பெயர் / Full
															Name</label>
													</div>
													<div class="col-md-6">
														<input type="text" id="umis_std_name" class="form-control"
															value="<?= $umis_name; ?>">
													</div>
													<div class="col-md-6">

														<label class="form-label" for="userName2">பிறந்த தேதி / Date of
															Birth</label>
													</div>
													<div class="col-md-6">
														<input type="text" id="umis_dob" class="form-control"
															value="<?= $umis_dob; ?>">
													</div>
													<div class="col-md-6">
														<label class="form-label" for="userName2">சேர்க்கை ஆண்டு / Year
															of
															Admission</label>
													</div>
													<div class="col-md-6">
														<input type="text" id="umis_yoa" class="form-control"
															value="<?= $umis_yoa; ?>">

													</div>
													<div class="col-md-6">
														<label class="form-label" for="userName2">படிப்பு ஆண்டு / Year
															of
															Study</label>
													</div>
													<div class="col-md-6">
														<input type="text" id="umis_yos" class="form-control"
															value="<?= $umis_yos; ?>">
													</div>

												</div>
											</div>
											<div class="col-md-6 normal-imput">
												<div class="row ">
													<div class="col-md-6">
														<label class="form-label" for="userName2">பட்டம் /
															Degree</label>
													</div>
													<div class="col-md-6">
														<input type="text" id="umis_std_degree" class="form-control"
															value="<?= $umis_std_degree; ?>">
													</div>
													<div class="col-md-6">

														<label class="form-label" for="userName2">படிப்பு / Course
														</label>
													</div>
													<div class="col-md-6">
														<input type="text" id="umis_std_course" class="form-control"
															value="<?= $umis_std_course; ?>">
													</div>

													<div class="col-md-6">
														<label class="form-label" for="userName2">கல்லூரி பெயர் /
															College
															Name</label>
													</div>
													<div class="col-md-6">
														<input type="text" id="umis_clg_name" class="form-control"
															value="<?= $umis_clg_name; ?>">
													</div>
													<div class="col-md-6">

														<label class="form-label" for="userName2">கல்லூரி முகவரி /
															College
															Address </label>
													</div>
													<div class="col-md-6">
														<input type="text" id="umis_clg_add" class="form-control"
															value="<?= $umis_clg_add; ?>">
													</div>

												</div>
												<input type="hidden" id="caDistrictId"
													value="<?php echo $caDistrictId; ?>">
											</div><br>
											<hr>
											<div class="form-check form-check-inline">
												<label class="form-check-label" for="customCheck3">I confirm that the
													above details
													are mine and correct</label>
												<input type="checkbox" class="form-check-input" id="umis_check"
													name="umis_check" onclick="get_umis_check()" value="YES">
											</div><br>

										</div>

									</div>


									<div class="row spel-app mt-2" id="Noumis" style="display: none;">
										<div class="col-md-6 normal-imput">
											<div class="row">
												<div class="col-md-6">
													<label class="form-label" for="userName2">முழு பெயர் / Full Name
														<br>
														<small>(As per SSLC
															Certificate)</small></label>
												</div>
												<div class="col-md-6">
													<input type="text" id="no_umis_name" class="form-control"
														value="<?= $no_umis_name ?>" oninput="valid_user_name(this)">
													<span class="error-message text-danger"
														id="error_no_umis_name"></span>
												</div>
												<div class="col-md-6">
													<label class="form-label" for="userName2">சேர்க்கை ஆண்டு / Year of
														Admission</label>
												</div>
												<div class="col-md-6">

													<select class="form-select mb-0" id="no_umis_yoa">
														<option selected value="">Select</option>
														<option value="2024" <?php if ($no_umis_yoa == "2024") {
															echo "selected";
														} ?>>2024</option>
														<option value="2023" <?php if ($no_umis_yoa == "2023") {
															echo "selected";
														} ?>>2023</option>

													</select>
													<span class="error-message text-danger"
														id="error_no_umis_yoa"></span>
												</div>

												<div class="col-md-6">
													<label class="form-label" for="userName2">படிப்பு / Course</label>
												</div>
												<div class="col-md-6">
													<!-- <input type="text" id="no_umis_stream" class="form-control"
														value="<?= $no_umis_stream; ?>"> -->
													<select class="form-select mb-0 " id="no_umis_stream"
														onchange="get_degree()">
														<option value="" selected>Select Course</option>
														<option value="1" <?php if ($no_umis_stream == "1") {
															echo "selected";
														} ?>>ITI</option>
														<option value="2" <?php if ($no_umis_stream == "2") {
															echo "selected";
														} ?>>Diploma</option>
														<option value="3" <?php if ($no_umis_stream == "3") {
															echo "selected";
														} ?>>UG</option>
														<option value="4" <?php if ($no_umis_stream == "4") {
															echo "selected";
														} ?>>PG</option>
														<option value="5" <?php if ($no_umis_stream == "5") {
															echo "selected";
														} ?>>PHD</option>
													</select>
													<span class="error-message text-danger"
														id="error_no_umis_stream"></span>

												</div>
												<div class="col-md-6">

													<label class="form-label" for="userName2">பட்டம் /
														Degree</label>
												</div>
												<div class="col-md-6">
													<select class="form-select mb-0" id="no_umis_course">
														<?php echo $course_name_options; ?>
													</select>
													<span class="error-message text-danger"
														id="error_no_umis_course"></span>

												</div>

											</div>
										</div>
										<div class="col-md-6">
											<div class="row normal-imput">

												<div class="col-md-6">
													<label class="form-label" for="userName2">கல்லூரி பெயர் / College
														Name
													</label>
												</div>
												<div class="col-md-6">

													<!-- <select class="form-select mb-0" id="no_umis_college">
														<?php echo $college_name_options; ?>

													</select> -->

													<input type="text" name="no_umis_college" id="no_umis_college"
														class="form-control" oninput="description_val(this)"
														value="<?= $no_umis_college; ?>">
													<span class="error-message text-danger"
														id="error_no_umis_college"></span>

												</div>
												<div class="col-md-6">
													<label class="form-label" for="userName2">கல்லூரி மாவட்டம் / College
														District </label>
												</div>
												<div class="col-md-6">
													<select class="form-select mb-0 " id="no_umis_clg_district"
														onchange="host_district()">
														<?php echo $clg_district_options; ?>
													</select>
													<span class="error-message text-danger"
														id="error_no_umis_clg_district"></span>

												</div>

												<div class="col-md-6">
													<label class="form-label" for="userName2">அஞ்சல் குறியீடு / Pincode
													</label>
												</div>
												<div class="col-md-6">

													<input type="text" name="no_umis_pincode" id="no_umis_pincode"
														class="form-control" onkeyup="validateNumber()" maxlength="6"
														oninput="pincode(this)" value="<?= $no_umis_pincode; ?>">
													<span id="invalid_no" style="color:red"></span>
													<span class="error-message text-danger"
														id="error_no_umis_pincode"></span>


												</div>



											</div>
										</div>
										<br>
										<hr>
										<div class="form-check form-check-inline">
											<label class="form-check-label" for="customCheck3">I confirm that the
												above details
												are mine and correct</label>
											<input type="checkbox" class="form-check-input" id="no_umis_check"
												name="no_umis_check" onclick="get_no_umis_check()" value="YES">
										</div><br><br>

										<!-- <div>
											<div class="float-end mt-3">
												<input type="button" class="btn btn-info" name="next"
													value="Save & Continue" onclick="umis_details_add()" id="no_umis_btn"  disabled>
											
											</div>
											<div class="float-start mt-3">
								
								<input type="button" class="btn btn-info button-previous" name="previous"
									>
							</div>
										</div>

										<div class="clearfix"></div> -->
									</div>

									<div class="buttn">

										<div class="float-end mt-3">
											<input type="button" class="btn btn-info" name="next"
												value="Save & Continue" onclick="umis_details_add()" id="umis_btn"
												disabled>

										</div>
										<div class="float-start mt-3">

											<button type="button" class="btn btn-info" name="previous"
												onclick="back(this.value)" value="2">Previous</button>
										</div>


										<div class="clearfix"></div>
									</div>

								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane" id="tab33" role="tabpanel">

						<div class="row spel-app  ">
							<div class="col-md-7">
								<div class="row normal-imput">
									<div class="col-md-7">
										<label class="form-label" for="userName2">நீங்கள் எந்த சமூகத்தை சேர்ந்தவர்?
											/Which community do you belong to?</label>
									</div>
									<div class="col-md-5">

										<select class="form-select" id="com_name" onchange="get_cert_detail()">
											<option value="" selected>select</option>
											<option value="BC" <?php if ($com_name == 'BC') {
												echo 'selected';
											} ?>>BC
											</option>
											<option value="MBC" <?php if ($com_name == 'MBC') {
												echo 'selected';
											} ?>>MBC
											</option>
											<option value="SC" <?php if ($com_name == 'SC') {
												echo 'selected';
											} ?>>SC
											</option>
											<option value="SCA" <?php if ($com_name == 'SCA') {
												echo 'selected';
											} ?>>SCA
											</option>

											<option value="ST" <?php if ($com_name == 'ST') {
												echo 'selected';
											} ?>>ST
											</option>
											<option value="OC" <?php if ($com_name == 'OC') {
												echo 'selected';
											} ?>>OC
											</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5"></div>


							<div class="row mb-3">
								<div class="col-md-7">
									<div class="row normal-imput">
										<div class="col-md-7" id="cert_detail_lbl" style="display:none">
											<label class="form-label" for="userName2">உங்களிடம் ஆன்லைன் சான்றிதழ்
												உள்ளதா? / Do You Have Online Certificate?</label>
										</div>
										<div class="col-md-5" id="cert_detail_div" style="display:none">

											<select class="form-select" onchange="get_com_no()" id="cert_detail">
												<option selected value="">select</option>
												<option value="Yes" <?php if ($cert_detail == 'Yes') {
													echo 'selected';
												} ?>>Yes</option>
												<option value="No" <?php if ($cert_detail == 'No') {
													echo 'selected';
												} ?>>No</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-5"></div>
							</div>

							<div class="row" id="communityno_div" style="display:none">
								<div class="col-md-8">
									<div class="row normal-imput">

										<div class="col-md-6">
											<label class="form-label" for="userName2">சாதி சான்றிதழ் / Community
												Certificate</label>
										</div>
										<div class="col-md-6">
											<input type="text" id="communityno" name="communityno" class="form-control"
												onkeyup="empty_com()" value="<?= $c_no; ?>" maxlength="16"
												oninput="validateCharInput(this)">
										</div>
									</div>
								</div>
								<div class="col-md-4" id="com_btn">
									<div class="row">
										<div class="col-md-12">
											<table class="app-table normal-imput">

												<input type="button" data-bs-toggle="modal"
													data-bs-target="#warning-alert-modal" class="comm-btn mb-bt"
													onclick="togglecommunity()" value="Go">

											</table>
										</div>
									</div>
								</div>
							</div>


							<div class="row spel-app mt-3" id="togglecom" style="display: none;">

								<div class="row spel-app ">
									<div class="col-md-6 ">
										<div class="row normal-imput">


											<div class="col-md-6 ">
												<label class="form-label" for="userName2">முழு பெயர் / Full Name</label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="fullname1" name="fullname1" class="form-control"
													value="<?= $c_name; ?>" oninput="valid_user_name(this)">
											</div>
											<div class="col-md-6 ">


												<label class="form-label" for="userName2">சாதி பெயர் / Caste
													Name</label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="castename" name="castename" class="form-control"
													value="<?= $caste_name; ?>" oninput="valid_user_name(this)">
											</div>
											<div class="col-md-6 ">


												<label class="form-label" for="userName2">துணை சாதி பெயர் / Sub-caste
													Name</label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="subcastename" name="subcastename"
													class="form-control" value="<?= $sub_caste_name; ?>"
													oninput="valid_user_name(this)">
											</div>



										</div>
									</div>
									<div class="col-md-6 ">
										<div class="row normal-imput">

											<div class="col-md-6 ">
												<label class="form-label" for="userName2">தந்தையின் பெயர் / Father
													Name</label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="fathername3" name="fathername3"
													class="form-control" value="<?= $c_father_name; ?>"
													oninput="valid_user_name(this)">
											</div>


											<!-- <div class="col-md-6 ">
											<label class="form-label" for="userName2">தாய் பெயர் / Mother Name
											</label>
										</div> -->
											<!-- <div class="col-md-6 ">
											<input type="text" id="mothername3" name="mothername3" class="form-control"
												value="<?= $c_mother_name; ?>">
										</div> -->
											<div class="col-md-6 " id="upload_lbl_div">

												<label class="form-label" for="userName2">சான்றிதழ் பதிவேற்றம் /
													Certificate
													Upload
												</label>
											</div>
											<div class="col-md-6 " id="upload_file_div">
												<input type="file" id="communitycer" name="communitycer"
													class="form-control mt-2" accept=".pdf,.doc,.docx,image/*">
											</div>

											<div class="col-md-6 " id="com_file_lbl">
												<label class="form-label" for="userName2">சான்றிதழ் /
													Certificate</label>
											</div>
											<div class="col-md-6 " id="com_file_div">
												<input type="hidden" id="community_pdf" name="community_pdf"
													class="form-control mt-2" value="<?php echo $community_pdf;?>">
												<a id="download_link" href="<?php echo $community_pdf;?>" class=""><img src="pdf.png" height="45px"
														width="45px"></a>

											</div>

										</div>
									</div>
									<div class="col-md-12 mt-2 mb-3">
										<div class=" gap-2" style="float: right;">
											<button type="button" class="comm-btn revert"
												onclick="com_revert()">Revert</button>
											<button type="button" class="comm-btn"
												onclick="com_confirm()">Confirm</button>
										</div>
									</div>
								</div>
							</div>

							<hr>


							<div class="row spel-app  ">
								<div class="col-md-7">
									<div class="row normal-imput">
										<div class="col-md-6">


											<label class="form-label" for="userName2">வருமானம் சான்றிதழ் எண் / Income
												Certificate No</label>
										</div>
										<div class="col-md-6">

											<input type="text" id="incomecerno" name="incomecerno" class="form-control"
												onkeyup="empty_inc()" value="<?= $i_no; ?>"
												oninput="validateCharInput(this)">




										</div>
									</div>
								</div>
								<div class="col-md-5">
									<div class="row ">
										<div class="col-md-12">
											<table class="app-table normal-imput">

												<input type="button" data-bs-toggle="modal"
													data-bs-target="#warning-alert-modal" class="comm-btn mb-bt"
													onclick="toggleincome()" value="Go">

											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="row spel-app mt-3" id="toggleinc" style="display: none;">
								<div class="row spel-app ">
									<div class="col-md-6 ">
										<div class="row normal-imput">

											<div class="col-md-6 ">
												<label class="form-label" for="userName2">முழு பெயர் / Full
													Name</label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="fullname4" name="fullname4" class="form-control"
													oninput="valid_user_name(this)" readonly value="<?= $i_name; ?>">
											</div>
											<div class="col-md-6 ">

												<label class="form-label" for="userName2">வருமான நிலை / Income
													Level</label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="incomelevel" name="incomelevel"
													class="form-control" oninput="number_only(this)"
													value="<?= $income_level; ?>">
											</div>
											<div class="col-md-6 ">

												<label class="form-label" for="userName2">தந்தையின் பெயர் / Father
													Name</label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="fathername4" name="fathername4"
													class="form-control" oninput="valid_user_name(this)" readonly
													value="<?= $i_father_name; ?>">
											</div>
											<div class="col-md-6 ">

												<label class="form-label" for="userName2">தாய் பெயர் / Mother Name
												</label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="mothername4" name="mothername4"
													class="form-control" readonly value="<?= $i_mother_name; ?>"
													oninput="valid_user_name(this)">

											</div>




										</div>
									</div>


									<div class="col-md-6 ">
										<div class="row normal-imput">
											<div class="col-md-6 ">
												<label class="form-label" for="userName2">தந்தை வருமானம்
													ஆதாரம் / Father income
													source </label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="fatherincomesource" name="fatherincomesource"
													readonly class="form-control" value="<?= $f_income_source; ?>">
											</div>

											<div class="col-md-6 ">
												<label class="form-label" for="userName2">தாய் வருமானம்
													ஆதாரம் / Mother income source</label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="motherincomesource" name="motherincomesource"
													class="form-control" value="<?= $m_income_source; ?>">
											</div>


											<!-- <div class="col-md-6 ">
											<label class="form-label" for="userName2">சான்றிதழ் பதிவேற்றம் / Certificate
												Upload
											</label>
										</div>
										<div class="col-md-6 ">
											<input type="file" id="incomecer" name="incomecer"
												class="form-control mt-0">
										</div> -->

											<div class="col-md-6 ">
												<label class="form-label" for="userName2">சான்றிதழ் /
													Certificate</label>
											</div>
											<div class="col-md-6 ">
												<input type="hidden" id="income_pdf" name="income_pdf"
													class="form-control mt-2" value="<?php echo $income_pdf;?>">
												<a id="download_link_income" href="<?php echo $income_pdf;?>" class=""><img src="pdf.png"
														height="45px" width="45px"></a>
											</div>

										</div>

									</div>
									<div class="col-md-12 mt-4 mb-3">
										<div class=" gap-2" style="float: right;">
											<button type="button" class="comm-btn revert"
												onclick="inc_revert()">Revert</button>
											<button type="button" class="comm-btn mb-bt"
												onclick="inc_confirm()">Confirm</button>
										</div>
									</div>
								</div>
							</div>



							<hr>


							<div class="row spel-app ">

								<div class="col-md-8 mb-4">
									<div class="row normal-imput">
										<div class="col-md-8 ">
											<label class="form-label" for="userName2">நீங்கள் மாற்றுத் திறனாளியா? / Are
												you differently abled?</label>
										</div>
										<div class="col-md-4 ">
											<select class="form-select  mb-0 " id="diffabled" name="diffabled"
												onchange="get_phy_div(this.value)">
												<option selected value="">Select</option>
												<option value="Yes" <?php if ($diffabled == 'Yes') {
													echo 'selected';
												} ?>>Yes
												</option>
												<option value="No" <?php if ($diffabled == 'No') {
													echo 'selected';
												} ?>>
													No
												</option>
											</select>
											<input type="text" id="diff_abled" name="diff_abled" class="form-control"
												value="<?= $diffabled; ?>" style="display:none">
										</div>
									</div>
								</div>
								<div class="col-md-4 ">
								</div>

								<div id="phy_div" class="row" style="display:none">
									<div class="col-md-6 ">
										<div class="row normal-imput">

											<div class="col-md-6 ">
												<label class="form-label" for="userName2">வகை / Category</label>
											</div>
											<div class="col-md-6 ">
												<select class="form-select mb-0" id="category" name="category">
													<?php echo $physically_challenged_options; ?>


												</select>

											</div>
											<div class="col-md-6 ">
												<label class="form-label" for="userName2"> அடையாள எண் / ID Number
												</label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="idnumber" name="idnumber" class="form-control"
													value="<?= $idnumber; ?>" oninput="off_id(this)">
											</div>
										</div>
									</div>
									<div class="col-md-6 ">
										<div class="row normal-imput">
											<div class="col-md-6 ">
												<label class="form-label" for="userName2">இயலாமை
													சதவிதம் / Disability
													percentage</label>
											</div>
											<div class="col-md-6 ">
												<input type="text" id="disabilitypercentage" name="disabilitypercentage"
													class="form-control" value="<?= $disability_percent; ?>"
													oninput="dec_number(this)" maxlength="2">
											</div>

											<div class="col-md-6 ">
												<label class="form-label" for="userName2">சான்றிதழ் பதிவேற்றம் /
													Certificate
													Upload </label>
											</div>
											<div class="col-md-6 ">
												<input type="file" id="disabilitycertificate"
													name="disabilitycertificate" class="form-control mt-2" accept=".pdf,.doc,.docx,image/*">
											</div>

											<!-- <input type="button"  class="btn btn-info" value="Save & Continue" onclick="certificateinfo()"> -->

										</div>
									</div>
								</div>
								<hr>
								<div class="form-check form-check-inline">
									<label class="form-check-label" for="customCheck3">I confirm that the
										above details
										are mine and correct</label>
									<input type="checkbox" class="form-check-input" id="cert_check" name="cert_check"
										onclick="get_certificate_check()" value="YES">
								</div><br><br>
							</div>
							<div>
								<div class="float-end mt-3">
									<input type="button" class="btn btn-info" name="next" value="Save & Continue"
										onclick="certificateinfo()" id="cert_btn" disabled>

								</div>
								<div class="float-start mt-3">

									<button type="button" class="btn btn-info" name="previous"
										onclick="back(this.value)" value="3">Previous</button>
								</div>
							</div>

							<div class="clearfix"></div>



						</div>





					</div>

					<div class="tab-pane" id="tab34" role="tabpanel">
						<div class="row spel-app mt-3">
							<div class="col-md-6 normal-imput ">
								<div class="row">


									<div class="col-md-6">
										<label class="form-label" for="userName2">பிறந்த தேதி / Date of
											Birth</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="dob" name="dob" class="form-control"
											value="<?= $per_dob; ?>" style="display:none">
										<input type="date" id="t_dob" name="t_dob" class="form-control"
											value="<?= date('Y-m-d', strtotime($per_dob)); ?>">
										<span class="error-message text-danger" id="error_dob"></span>
									</div>

									<div class="col-md-6">
										<label class="form-label" for="userName2">வயது / Age</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="age" name="age" class="form-control"
											value="<?= $per_age; ?>">
									</div>
									<div class="col-md-6">

										<label class="form-label" for="userName2">இரத்த வகை / Blood
											Group</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="bloodgroup" name="bloodgroup" class="form-control"
											value="<?= $blood_group; ?>" style="display:none">

										<select class="form-select" id="bloodgroup_opt">
											<?php echo $blood_group_options; ?>
										</select>
										<span class="error-message text-danger" id="error_bloodgroup"></span>


									</div>

									<div class="col-md-6">
										<label class="form-label" for="userName2"> மின்னஞ்சல் முகவரி / Email Id
										</label>
									</div>
									<div class="col-md-6">
										<input type="email" id="mailid" name="mailid" class="form-control"
											value="<?= $email_id ?>" oninput="mail_valid(this)">
										<span class="error-message text-danger" id="error_mailid"></span>

									</div>
									<div class="col-md-6">
										<label class="form-label" for="userName2">மதம் / Religion
										</label>
									</div>
									<div class="col-md-6">
										<select class="form-select" id="religion">
											<option value="" selected>Select Religion</option>
											<option value="Hindu" <?php if ($religion == "Hindu") {
												echo "selected";
											} ?>>
												Hindu</option>
											<option value="Christian" <?php if ($religion == "Christian") {
												echo "selected";
											} ?>>Christian</option>
											<option value="Muslim" <?php if ($religion == "Muslim") {
												echo "selected";
											} ?>>
												Muslim</option>
											<option value="Buddhism" <?php if ($religion == "Buddhism") {
												echo "selected";
											} ?>>
												Buddhism</option>
											<option value="Sikh" <?php if ($religion == "Sikh") {
												echo "selected";
											} ?>>
												Sikh</option>
											<option value="Jainism" <?php if ($religion == "Jainism") {
												echo "selected";
											} ?>>
												Jainism</option>
											<option value="others" <?php if ($religion == "others") {
												echo "selected";
											} ?>>
												others</option>
											<option value="Religion not disclosed" <?php if ($religion == "Religion not disclosed") {
												echo "selected";
											} ?>>
												Religion not disclosed</option>
											

										</select>
										<!-- <input type="text" id="religion" name="religion" class="form-control" value="<?= $religion; ?>"> -->
										<span class="error-message text-danger" id="error_religion"></span>

									</div>

									<div class="col-md-6">
										<label class="form-label" for="userName2">தாய் மொழி / Mother
											Tongue</label>
									</div>
									<div class="col-md-6">
										<select class="form-select" id="mothertongue">
											<option value="">Select Mother Tongue</option>
											<option value="Tamil" <?php if ($mother_tongue == "Tamil") {
												echo "selected";
											} ?>>
												Tamil</option>
											<option value="Hindi" <?php if ($mother_tongue == "Hindi") {
												echo "selected";
											} ?>>
												Hindi</option>
											<option value="Malayalam" <?php if ($mother_tongue == "Malayalam") {
												echo "selected";
											} ?>>Malayalam</option>
											<option value="Kannadam" <?php if ($mother_tongue == "Kannadam") {
												echo "selected";
											} ?>>Kannadam</option>
											<option value="Telugu" <?php if ($mother_tongue == "Telugu") {
												echo "selected";
											} ?>>Telugu</option>

										</select>
										<span class="error-message text-danger" id="error_mothertongue"></span>

										<!-- <input type="text" id="mothertongue" name="mothertongue" class="form-control" value="<?= $mother_tongue; ?>"> -->
									</div>
									<!-- <div class="col-md-6">
										<label class="form-label" for="userName2">ஆதார் இணைக்கப்பட்ட எண் / Aadhar Linked
											No</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="aadharno" name="aadharno" class="form-control"
											value="<?= $contact_no; ?>" oninput="number_only(this)" maxlength="10">
									</div> -->

									<div class="col-md-6">
										<label class="form-label" for="userName2">இலங்கை அகதி / Srilankan
											Refugee</label>
									</div>
									<div class="col-md-6">

										<select class="form-select mb-0 " id="refugee" name="refugee">
											<option selected value="">Select</option>
											<option value="YES" <?php if ($refugee == "YES") {
												echo "selected";
											} ?>>Yes
											</option>
											<option value="NO" <?php if ($refugee == "NO") {
												echo "selected";
											} ?>>No
											</option>

										</select>
										<span class="error-message text-danger" id="error_refugee"></span>

									</div>


									<div class="col-md-6" id="orphan_label">
										<label class="form-label" for="userName2">ஆதரவற்ற குழந்தை / Orphan</label>
									</div>
									<div class="col-md-6" id="orphan_input">
										<select class="form-select  mb-0" id="orphan" name="orphan">
											<option selected value="">Select</option>
											<option value="YES" <?php if ($orphan == "YES") {
												echo "selected";
											} ?>>Yes
											</option>
											<option value="NO" <?php if ($orphan == "NO") {
												echo "selected";
											} ?>>No
											</option>
										</select>
										<span class="error-message text-danger" id="error_orphan"></span>

									</div>


									<div class="col-md-6">
										<label class="form-label" for="userName2">ஒற்றை பெற்றோர் குழந்தை / Single
											Parent Child</label>
									</div>
									<div class="col-md-6">

										<select class="form-select mb-0" id="singleparent" name="singleparent">
											<option selected value="">Select</option>
											<option value="YES" <?php if ($single_parent == "YES") {
												echo "selected";
											} ?>>Yes
											</option>
											<option value="NO" <?php if ($single_parent == "NO") {
												echo "selected";
											} ?>>
												No
											</option>
										</select>
										<span class="error-message text-danger" id="error_singleparent"></span>

									</div>


									<div class="col-md-6" id="fg_lbl">
										<label class="form-label" for="userName2">முதல் பட்டதாரி / First
											Graduate</label>
									</div>
									<div class="col-md-6" id="fg_div">

										<select class="form-select mb-0" id="firstgraduate_opt"
											name="firstgraduate_opt">
											<option selected value="">Select</option>
											<option value="YES" <?php if ($first_graduate == "YES") {
												echo "selected";
											} ?>>Yes
											</option>
											<option value="NO" <?php if ($first_graduate == "NO") {
												echo "selected";
											} ?>>
												No
											</option>
										</select>

										<input type="text" id="firstgraduate" name="firstgraduate" class="form-control"
											value="<?= $first_graduate ?>" style="display:none"
											oninput="validateCharInput(this)">
										<span class="error-message text-danger" id="error_firstgraduate"></span>

									</div>

								</div>
							</div>
							<div class="col-md-6">
								<div class="row normal-imput">

									<div class="col-md-6">
										<label class="form-label" for="userName2">தந்தை பெயர் / Father
											Name</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="dadname" name="dadname" class="form-control"
											value="<?= $per_father_name ?>" oninput="valid_user_name(this)">
										<span class="error-message text-danger" id="error_dadname"></span>

									</div>
									<div class="col-md-6">
										<label class="form-label" for="userName2">தாய்
											பெயர் / Mother
											Name </label>
									</div>
									<div class="col-md-6">
										<input type="text" id="momname" name="momname" class="form-control"
											value="<?= $per_mother_name; ?>" oninput="valid_user_name(this)">
										<span class="error-message text-danger" id="error_momname"></span>

									</div>
									<div class="col-md-6">

										<label class="form-label" for="userName2">தந்தை
											தகுதி / Father
											Qualification</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="dadqualification" name="dadqualification"
											class="form-control" value="<?= $father_qual; ?>"
											oninput="valid_address(this)">
										<span class="error-message text-danger" id="error_dadqualification"></span>


									</div>
									<div class="col-md-6">
										<label class="form-label" for="userName2">தாய் தகுதி / Mother
											Qualification</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="momqualification" name="momqualification"
											class="form-control" value="<?= $mother_qual; ?>"
											oninput="valid_address(this)">
										<span class="error-message text-danger" id="error_momqualification"></span>

									</div>
									<div class="col-md-6">

										<label class="form-label" for="userName2">தந்தை தொழில் / Father
											Occupation</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="dadOccupation" name="dadOccupation" class="form-control"
											value="<?= $father_occu; ?>" oninput="valid_user_name(this)">
										<span class="error-message text-danger" id="error_dadOccupation"></span>

									</div>


									<div class="col-md-6">
										<label class="form-label" for="userName2">தாய் தொழில் / Mother
											Occupation</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="momOccupation" name="momOccupation" class="form-control"
											value="<?= $mother_occu; ?>" oninput="valid_user_name(this)">
										<span class="error-message text-danger" id="error_momOccupation"></span>

									</div>
									<div class="col-md-6">

										<label class="form-label" for="userName2">தந்தை
											தொலைபேசி எண் / Father
											Telephone Number</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="dadmobno" name="dadmobno" class="form-control"
											value="<?= $father_no; ?>" oninput="valid_mobile_number(this)"
											maxlength="10">
										<span class="error-message text-danger" id="error_dadmobno"></span>

									</div>
									<div class="col-md-6">

										<label class="form-label" for="userName2">பெற்றோர் அல்லது
											பாதுகாவலர் எண் / Parent or Guardian No</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="guardianno" name="guardianno" class="form-control"
											value="<?= $guardian_no; ?>" oninput="valid_mobile_number(this)"
											maxlength="10">
										<span class="error-message text-danger" id="error_guardianno"></span>

									</div>





									<div class="col-md-6">
										<label class="form-label" for="userName2">தற்போது முகவரி / Present
											Address :-</label>
									</div>
									<div class="col-md-6">

									</div>

									<div class="col-md-6">
										<label class="form-label" for="userName2">கதவு எண் / Door No.</label>
									</div>

									<div class="col-md-6">
										<input type="text" id="door_no" name="door_no" class="form-control"
											value="<?= $door_no; ?>" oninput="valid_address(this)">
										<span class="error-message text-danger" id="error_door_no"></span>

									</div>

									<div class="col-md-6">
										<label class="form-label" for="userName2">தெரு / Street </label>
									</div>
									<div class="col-md-6">
										<input type="text" id="street_name" name="street_name" class="form-control"
											value="<?= $street_name; ?>" oninput="valid_address(this)">
										<span class="error-message text-danger" id="error_street_name"></span>

									</div>

									<div class="col-md-6">
										<label class="form-label" for="userName2">பகுதியின் பெயர் / Area Name</label>
									</div>
									<div class="col-md-6">
										<input type="text" id="area_name" name="area_name" class="form-control"
											value="<?= $area_name; ?>" oninput="description_val(this)">
										<span class="error-message text-danger" id="error_area_name"></span>

									</div>

									<div class="col-md-6">
										<label class="form-label" for="userName2">தாலுகா / Taluk </label>
									</div>
									<div class="col-md-6">
										<input type="text" id="taluk" name="taluk" class="form-control"
											value="<?= $block; ?>" oninput="description_val(this)">
										<span class="error-message text-danger" id="error_taluk"></span>

									</div>

									<div class="col-md-6">
										<label class="form-label" for="userName2">மாவட்டம் / District </label>
									</div>
									<div class="col-md-6">
										<input type="text" id="District" name="District" class="form-control"
											value="<?= $district; ?>" oninput="description_val(this)">
										<span class="error-message text-danger" id="error_District"></span>

									</div>

									<div class="col-md-6">
										<label class="form-label" for="userName2">அஞ்சல் குறியீடு / Pincode </label>
									</div>
									<div class="col-md-6">
										<input type="text" id="Pincode" name="Pincode" class="form-control"
											maxlength="6" onkeyup="validatepincode()" oninput="pincode(this)"
											value="<?= $pincode; ?>">
										<span id="invalid_pincode" style="color:red"></span>
										<span class="error-message text-danger" id="error_Pincode"></span>

									</div>

									<!-- </div> -->

									<!-- <input type="button" class="btn btn-info button-next" name="next" value="Next" onclick="familyinfo()"> -->

									<!--
												<h5>Family Photo</h5>
												<hr>
												<img src="img/dummy.jpg" style="width:50%;">
												--->

								</div>
							</div>

							<hr><br>
							<!-- <div class="row"> -->
							<div class="form-check form-check-inline">
								<label class="form-check-label" for="customCheck3">I certify that all the above details
									are correct</label>
								<input type="checkbox" class="form-check-input" id="fam_check" name="fam_check"
									onclick="get_familyinfo_check()" value="YES">
							</div><br>
						</div>
						<div>
							<div class="float-end mt-3">
								<input type="button" class="btn btn-info" name="next" value="Save & Continue"
									onclick="familyinfo()" id="fam_btn" disabled>

							</div>
							<div class="float-start mt-3">

								<button type="button" class="btn btn-info" name="previous" onclick="back(this.value)"
									value="4">Previous</button>
							</div>
						</div>

						<div class="clearfix"></div>



					</div>





					<div class="tab-pane" id="tab35" role="tabpanel">
						<div class="row spel-app normal-imput ">
							<div class="col-md-6 ">
								<div class="row">

									<!-- <div class="col-md-6 ">
										<label class="form-label" for="userName2">முன்னுரிமை / Priority</label>
									</div>
									<div class="col-md-6 ">
									<select class="form-select" id="priority">
											<?php echo $priority_type_options; ?>
										</select>
									</div> -->

									<div class="col-md-6 ">
										<label class="form-label" for="userName2">Gender</label>
									</div>
									<div class="col-md-6 ">
										<input type="hidden" id="host_gender" value="<?= $gender; ?>">
										<h5><?= $agender; ?></h5>
									</div>

									<div class="col-md-6 ">
										<label class="form-label" for="userName2">Hostel Type</label>
									</div>
									<div class="col-md-6">
										<input type="hidden" id="host_student"
											value="<?php echo hostel_type($student_type)[0]['hostel_type']; ?>">
										<h5><?= hostel_type($student_type)[0]['hostel_type']; ?></h5>
									</div>




									<div class="col-md-6 ">
										<label class="form-label" for="userName2" id="scl_dis" style="display:none">உன்
											பள்ளி மாவட்டம் / Your School
											District</label>
										<label class="form-label" for="userName2" id="clg_dis">உங்கள் கல்லூரி மாவட்டம் /
											Your College
											District</label>
									</div>
									<div class="col-md-6 ">



										<input type="hidden" id="hostel_district" style="display:none" readonly>
										<input type="hidden" id="emis_hostel_district" style="display:none" readonly>
										<input type="hidden" id="umis_district" readonly>
										<!-- <h5 id="emis_dis"></h5> -->
										<!-- <h5 id="umis_dist"></h5> -->
										<h5 id="host_name"></h5>


										<input type="hidden" id="adjacent_district">
									</div>
									<div class="col-md-6 ">
										<label class="form-label" for="userName2" id="adj_dis_lbl">Nearby
											District's</label>
									</div>
									<div class="col-md-6 ">
										<!-- <input type="hidden" id="adjacent_district_name"> -->
										<h5 id="adj_dis"></h5>
									</div>







								</div>
							</div>
							<div class="col-md-6">
								<div class="row normal-imput">


									<!-- <div class="col-md-6 ">
										<label class="form-label" for="userName2">விடுதி தாலுகா / Hostel
											Taluk</label>
									</div>
									<div class="col-md-6 ">

										<select class="form-select" id="hostel_taluk" onchange="get_hostel_name()">
											
										</select>
									</div>
									<div class="col-md-6">

										<label class="form-label" for="userName2">விடுதி பெயர் / Hostel
											Name</label>
									</div>
									<div class="col-md-6">

										<select class="form-select" id="hostel_name">
											
										</select>
									</div> -->
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3 mb-3 text-center">
								<!-- <input type="button" class="comm-btn" onclick="hostel_sub_add_update()" value="Add"> -->
								<!-- <input type="button" class="comm-btn" onclick="get_hostel_list()" value="Go"> -->
							</div>
						</div>
						<div class="row">
							<!-- <div class="col-md-12">
								<table id="hostel_sub_datatable" class="table table-sm table-centered mb-0 font-14"
									style="width: 100%;">
									<thead class="table-light">
										<th>S.no</th>
										<th>Hostel District</th>
										<th>Hostel Taluk</th>
										<th>Hostel Name</th>
										<th>Hostel Address</th>
										<th class="pro-width">Priority</th>
									</thead>
								</table>
							</div> -->

							<table id="hostel_sub_datatable" class="mb-0 table table-striped">

								<thead>
									<th>S.no</th>
									<th>Hostel District</th>
									<th>Hostel Taluk</th>
									<th>Hostel Name</th>
									<th>Hostel Address</th>
									<th class="pro-width">Priority</th>
								</thead>
							</table>
							<!-- <div class="col-md-12">
								<table id="hostel_sub_datatable" class="table table-sm table-centered mb-0 font-14" style="width: 100%;">
									<thead class="table-light">
										<th>S.no</th>
										<th>Hostel District</th>
										<th>Hostel Taluk</th>
										<th>Hostel Name</th>
										<th>Hostel Address</th>
										<th class="pro-width">Priority</th>
									</thead>
								</table>
							</div> -->
							<div class="col-md-12 mt-4 mb-3 ">
								<aside id="info-block">
									<section class="file-marker">
										<div>
											<div class="box-title">
												Note
											</div>
											<div class="box-contents">
												<div id="audit-trail">
													<ul>

														<li>
															<p style="margin-bottom: 6px;padding: 5px 0px;"> Kindly note
																that you can only add up to three priorities</p>
														</li>
													</ul>
												</div>
											</div>
										</div>

									</section>
								</aside>
								<div class="float-end mt-3">
									<input type="button" class="btn btn-info" name="next" value="Submit"
										onclick="overall_submit()">
									<!--<input type="button" class="btn btn-info button-last" name="last" value="Last">-->
								</div>
								<div class="float-start mt-3">
									<!--<input type="button" class="btn btn-info button-first" name="first" value="First">-->
									<button type="button" class="btn btn-info" name="previous"
										onclick="back(this.value)" value="5">Previous</button>
								</div>

								<div class="clearfix"></div>
							</div>
						</div>
					</div>



				</div> <!-- tab-content -->
			</div> <!-- end #btnwizard-->
		</div>



	</div>
<div class="row">
	<div class="col-md-12 load" id="loader">
		<i class="mdi mdi-loading mdi-spin"></i>
	</div>
</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
	<script src="application.js"></script>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>


	<?php include 'footer.php' ?>

	<script>

	
		

	</script>