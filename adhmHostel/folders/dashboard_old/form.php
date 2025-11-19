<?php

header("Permissions-Policy: geolocation=(self),camera=()");

$hostel_taluk_options = "<option value=''>Select Taluk</option>";
$hostel_special_tahsildar_options = "<option value=''>Select Special Tahsildar</option>";
$parliment_const_name_options = "<option value=''>Select Parliament</option>";
$block_options = "<option value=''>Select Block</option>";
$village_options = "<option value=''>Select Village</option>";
$corporation_options = "<option value=''>Select Corporation</option>";
$municipality_options = "<option value=''>Select Municipality</option>";
$town_panchayat_options = "<option value=''>Select Town Panchayat</option>";

if (isset($_SESSION['hostel_id'])) {
	if (!empty($_SESSION['hostel_id'])) {


		$unique_id = $_SESSION['hostel_id'];
		$where = [
			"unique_id" => $unique_id
		];

		$table = "hostel_name";

		$columns = [
			"hostel_name",
			"hostel_id",
			"district_name",
			"hostel_name",
			"taluk_name",
			"special_tahsildar",
			"assembly_const",
			"parliment_const",
			"hostel_location",
			"urban_type",
			"corporation",
			"municipality",
			"town_panchayat",
			"block_name",
			"village_name",
			"hostel_type",
			"yob",
			"go_attach_file",
			"distance_btw_phc",
			"phc_name",
			"distance_btw_ps",
			"ps_name",
			"(SELECT COUNT(*) FROM establishment_registration WHERE hostel_name = '$_SESSION[hostel_id]' and status = 1) AS staff_count",
			"sanc_staff_count",
			"unique_id",
			"entrance_image",
			"dining_image",
			"building_image",
			"latitude",
			"longitude",
			"address"
		];

		$table_details = [
			$table,
			$columns
		];
		$result_values = $pdo->select($table_details, $where);

		if ($result_values->status) {

			$result_values = $result_values->data;

			$hostel_name = $result_values[0]["hostel_name"];
			$hostel_id = $result_values[0]["hostel_id"];
			$district_name = $result_values[0]["district_name"];
			$taluk_name = $result_values[0]["taluk_name"];
			$special_tahsildar = $result_values[0]["special_tahsildar"];
			$assembly_const = $result_values[0]["assembly_const"];
			$parliment_const_name = $result_values[0]["parliment_const"];
			$hostel_location = $result_values[0]["hostel_location"];
			$urban_type = $result_values[0]["urban_type"];
			$corporation = $result_values[0]["corporation"];
			$municipality = $result_values[0]["municipality"];
			$town_panchayat = $result_values[0]["town_panchayat"];
			$block_name = $result_values[0]["block_name"];
			$village_name = $result_values[0]["village_name"];
			$hostel_type = $result_values[0]["hostel_type"];
			$yob = $result_values[0]["yob"];
			$distance_btw_phc = $result_values[0]["distance_btw_phc"];
			$phc_name = $result_values[0]["phc_name"];
			$distance_btw_ps = $result_values[0]["distance_btw_ps"];
			$ps_name = $result_values[0]["ps_name"];
			$staff_count = $result_values[0]["staff_count"];
			$sanc_staff_count = $result_values[0]["sanc_staff_count"];
			$entrance_image = $result_values[0]["entrance_image"];
			$dining_image = $result_values[0]["dining_image"];
			$building_image = $result_values[0]["building_image"];
			$unique_id = $result_values[0]["unique_id"];
			$file_names = $result_values[0]["go_attach_file"];
			$latitude = $result_values[0]["latitude"];
			$longitude = $result_values[0]["longitude"];
			$address = $result_values[0]["address"];



			$district_options = taluk_name("", $district_name);
			$hostel_taluk_options = select_option($district_options, "Select Taluk", $taluk_name);

			$district_options = special_tahsildar("", $district_name);
			$hostel_special_tahsildar_options = select_option($district_options, "Select Special Tahsildar", $special_tahsildar);

			$district_options = block("", $district_name);
			$block_options = select_option($district_options, "Select Block Name", $block_name);

			$village_option = village_name("", $block_name);
			$village_options = select_option($village_option, "Select Village Name", $village_name);
		} else {
			$btn_text = "Error";
			$btn_action = "error";
			$is_btn_disable = "disabled='disabled'";
		}
	}
}

$hostel_location_type_options = [
	"1" => [
		"unique_id" => "1",
		"value" => "Rural",
	],
	"2" => [
		"unique_id" => "2",
		"value" => "Urban",
	]
];
$hostel_location_type_options = select_option($hostel_location_type_options, "Select Hostel Location", $hostel_location);

$urban_type_options = [
	"1" => [
		"unique_id" => "1",
		"value" => "Corporation",
	],
	"2" => [
		"unique_id" => "2",
		"value" => "Municipality",
	],
	"3" => [
		"unique_id" => "3",
		"value" => "Town Panchayat",
	]
];
$urban_type_options = select_option($urban_type_options, "Select Urban Type", $urban_type);

$district_name_options = district_name();
$district_name_options = select_option($district_name_options, "Select Hostel District", $district_name);

$assembly_const_name_options = assembly_constituency('', $_SESSION['district_id']);
$assembly_const_name_options = select_option($assembly_const_name_options, "Select Assembly Constituency", $assembly_const);

$parliment_const_name_options = parliment_constituency('', $_SESSION['district_id']);
$parliment_const_name_options = select_option($parliment_const_name_options, "Select Parliment Constituency", $parliment_const_name);

$corporation_options = corporation('', $_SESSION['district_id']);
$corporation_options = select_option($corporation_options, "Select Corporation Name", $corporation);

$municipality_options = municipality('', $_SESSION['district_id']);
$municipality_options = select_option($municipality_options, "Select Municipality Name", $municipality);

$town_panchayat_options = town_panchayat('', $_SESSION['district_id']);
$town_panchayat_options = select_option($town_panchayat_options, "Select Town Panchayat Name", $town_panchayat);

$hostel_type_options = hostel_type_name();
$hostel_type_options = select_option($hostel_type_options, "Select Hostel Type", $hostel_type);

$old_hostel_type_options = hostel_type_name();
$old_hostel_type_options = select_option($old_hostel_type_options, "Select Hostel Type");

$old_gender_type_options = hostel_gender_name();
$old_gender_type_options = select_option($old_gender_type_options, "Select Hostel Gender Type");

?>
<style>
	.page-title-box .page-title {

		line-height: 54px !important;
	}

	.modal-content {
		width: 100%;
		margin-left: unset !important;
	}

	.modal-header h5 {
		font-size: 17px;
	}

	.modal-header.border-0 {
		background: #2993bc;
		color: #ffffff;
	}

	.modal-body {
		padding: 24px;
	}
</style>
<div class="content-page">
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="page-title-box">
						<div class="page-title-right">

						</div>
						<h4 class="page-title">Application - Academic Year 2024</h4>
					</div>
				</div>
			</div>

			<div class="row">
				<input type="hidden" id="hostel_id" value="<?php echo $_SESSION['hostel_id']; ?>">
				<div class="col-md-4 col-lg-3 mb-mb">
					<div class="card mb-0">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0 me-3">
									<div class="avatar-sm">
										<span class="avatar-title bg-primary-lighten1 text-primary rounded ap-1">
											<i class="mdi mdi-file-check "></i>
										</span>
									</div>
								</div>
								<div class="flex-grow-1 c-v">
									<h5 class="mt-0 mb-1">Applied Application</h5>
									<p class="mb-0" id="appl_cnt"
										onclick="new_external_window_print(event,'folders/dashboard/print.php','applied');">
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-lg-3 mb-mb">
					<div class="card mb-0">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0 me-3">
									<div class="avatar-sm">
										<span class="avatar-title bg-primary-lighten1 text-primary rounded ap-2">
											<i class="mdi mdi-thumb-up "></i>
										</span>
									</div>
								</div>
								<div class="flex-grow-1 c-v">
									<h5 class="mt-0 mb-1">Acceptance</h5>
									<p class="mb-0" id="accp_cnt"
										onclick="new_external_window_print(event,'folders/dashboard/print.php','acceptance');">
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-lg-3 mb-mb">
					<div class="card mb-0">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0 me-3">
									<div class="avatar-sm">
										<span class="avatar-title bg-primary-lighten1 text-primary rounded ap-3">
											<i class="mdi mdi-checkbox-marked-circle-outline "></i>
										</span>
									</div>
								</div>
								<div class="flex-grow-1 c-v">
									<h5 class="mt-0 mb-1">Approval</h5>
									<p class="mb-0" id="appr_cnt"
										onclick="new_external_window_print(event,'folders/dashboard/print.php','approved');">
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-lg-3 mb-mb">
					<div class="card mb-0">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0 me-3">
									<div class="avatar-sm">
										<span class="avatar-title bg-primary-lighten1 text-primary rounded ap-4">
											<i class="mdi mdi-file-sign "></i>
										</span>
									</div>
								</div>
								<div class="flex-grow-1 c-v">
									<h5 class="mt-0 mb-1">Rejected</h5>
									<p class="mb-0" id="rej_cnt"
										onclick="new_external_window_print(event,'folders/dashboard/print.php','rejected');">
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="page-title-box">
						<div class="page-title-right">
							<form class="d-flex">

							</form>
						</div>
						<h4 class="page-title">Registration</h4>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-lg-3 mb-mb">
					<div class="card mb-0">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0 me-3">
									<div class="avatar-sm">
										<span class="avatar-title bg-primary-lighten1 text-primary rounded ap-3">
											<i class="mdi mdi-timeline-check-outline "></i>
										</span>
									</div>
								</div>
								<div class="flex-grow-1 c-v">
									<h5 class="mt-0 mb-1"> Total Capacity </h5>
									<p class="mb-0" id="tot_cap"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-lg-3 mb-mb">
					<div class="card mb-0">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0 me-3">
									<div class="avatar-sm">
										<span class="avatar-title bg-primary-lighten1 text-primary rounded ap-5">
											<i class="mdi mdi-account-multiple-outline "></i>
										</span>
									</div>
								</div>
								<div class="flex-grow-1 c-v">
									<h5 class="mt-0 mb-1">Old Students</h5>
									<p class="mb-0" id="old_std"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-lg-3 mb-mb">
					<div class="card mb-0">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0 me-3">
									<div class="avatar-sm">
										<span class="avatar-title bg-primary-lighten1 text-primary rounded ap-6">
											<i class="mdi mdi-account-plus-outline "></i>
										</span>
									</div>
								</div>
								<div class="flex-grow-1 c-v">
									<h5 class="mt-0 mb-1">New Students</h5>
									<p class="mb-0" id="new_std"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-lg-3 mb-mb">
					<div class="card mb-0">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0 me-3">
									<div class="avatar-sm">
										<span class="avatar-title bg-primary-lighten1 text-primary rounded ap-7">
											<i class="mdi mdi-hospital-building "></i>
										</span>
									</div>
								</div>
								<div class="flex-grow-1 c-v">
									<h5 class="mt-0 mb-1">Hostel Vacancy</h5>
									<p class="mb-0" id="hos_vac"></p>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="row">
				<div class="col-12">
					<div class="page-title-box">
						<div class="page-title-right">
							<form class="d-flex">

							</form>
						</div>
						<h4 class="page-title">Biometric Registration</h4>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-lg-3 mb-mb">
					<div class="card mb-0">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0 me-3">
									<div class="avatar-sm">
										<span class="avatar-title bg-primary-lighten1 text-primary rounded ap-3">
											<i class="mdi mdi-timeline-check-outline "></i>
										</span>
									</div>
								</div>
								<div class="flex-grow-1 c-v">
									<h5 class="mt-0 mb-1"> Registered Count </h5>
									<p class="mb-0" id="bio_reg_cnt"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="page-title-box">
						<div class="page-title-right">
							<form class="d-flex">
								<div class="input-group">
									<input class="form-control" id="example-month" type="month" name="month"
										value="2024-03">
									<span class="input-group-text thme-colo  border-primary text-white">
										<i class="mdi mdi-calendar-range font-13"></i>
									</span>
								</div>

							</form>
						</div>
						<h4 class="page-title">Attendance</h4>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 mt-3">
					<div class="row">
						<div class="col-sm-3">
							<div class="card widget-flat">
								<div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
									<div class="cd i1">
										<i class="ri-checkbox-circle-line widget-icon"></i>
									</div>
									<h5 class="text-muted  mt-0 vf" title="Number of Customers">Present</h5>
									<h3 class="mt-2 mb-0 v-1 count">25</h3>

								</div> <!-- end card-body-->
							</div> <!-- end card-->
						</div>
						<div class="col-sm-3">
							<div class="card widget-flat">
								<div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
									<div class="cd i2">
										<i class="ri-close-circle-line widget-icon"></i>
									</div>
									<h5 class="text-muted  mt-0 vf" title="Number of Customers">Absent</h5>
									<h3 class="mt-2 mb-0 v-1 count">02</h3>

								</div> <!-- end card-body-->
							</div> <!-- end card-->
						</div>
						<div class="col-sm-3">
							<div class="card widget-flat">
								<div class="card-body text-center" style="background: #fff;border: 1px solid #c8bebe;">
									<div class="cd i3">
										<i class="ri-shield-user-line widget-icon"></i>
									</div>
									<h5 class="text-muted  mt-0 vf" title="Number of Customers">Leave</h5>
									<h3 class="mt-2 mb-0 v-1 count">03</h3>

								</div> <!-- end card-body-->
							</div> <!-- end card-->
						</div>
						<div class="col-sm-3">
							<div class="card cta-box text-bg-primary">
								<div class="card-body red-pad">
									<div class="text-center">
										<h3 class="m-0  cta-box-title text-reset">Applied Leave</h3>
										<div class="ff">

										</div>
										<a href="#" data-bs-toggle="modal" data-bs-target=".applied-leave-modal"
											type="button">
											<h4 class="mt-2 mb-0 v-1 count"
												style="color: #fff;margin-top:10px !important;" id="no_of_student"
												onclick="new_external_window_print_new(event,'folders/dashboard/student_leave_count.php');">
											</h4>
										</a>
									</div>
								</div>
								<!-- end card-body -->
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="sct">
						<div class="card border-1">

							<div class="alert alert-danger new-bgg  rounded-0 mb-1" role="alert">
								<i class="uil-folder-heart me-1 h4 align-middle"></i> <b>Holiday</b>
							</div>

							<div class="card-body pt-1 notify" id="holiday_list">

							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="sct">
						<div class="card border-1">

							<div class="alert alert-warning bg-22 rounded-0 mb-1" role="alert">
								<i class="uil-folder-heart me-1 h4 align-middle"></i> <b>Notifications</b>
							</div>

							<div class="card-body pt-1 notify" id="notification_list">

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="completionModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
		aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title w-100 text-center">Hostel Creation</h5>
				</div>
				<div class="modal-body">
					<form class="was-validated" autocomplete="off">
						<!-- <input type="hidden" id="latitude" name="latitude" value="<?php echo $latitude; ?>">
						<input type="hidden" id="longitude" name="longitude" value="<?php echo $longitude; ?>"> -->
						<input type="hidden" id="go_attach_file" name="go_attach_file"
							value="<?php echo $go_attach_file; ?>">
						<!-- <input type="hidden" id="address" name="address" value="<?php echo $address; ?>"> -->
						<div class="row">
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="hostel_name">Hostel Name</label>
									<input type="text" class="form-control" oninput="valid_user_name(this)"
										id="hostel_name" name="hostel_name" value="<?= $hostel_name; ?>" required
										readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="hostel_id">Hostel Id</label>
									<input type="text" oninput="off_id(this)" class="form-control" id="hostel_id"
										name="hostel_id" value="<?= $hostel_id; ?>" required readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label for="district_name" class="form-label">District Name</label>
									<select class="select form-control" id="district_name" name="district_name"
										onchange="get_taluk_name(); get_block(); get_assembly(); get_parliament(); get_corporation(); get_municipality(); get_town_panchayat();"
										required disabled>
										<?php echo $district_name_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label for="taluk_name" class="form-label">Taluk Name</label>
									<select class="select form-control" id="taluk_name" name="taluk_name" required
										disabled>
										<?php echo $hostel_taluk_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="latitude">Latitude</label>
									<input type="text" class="form-control" oninput="valid_user_name(this)"
										id="latitude" name="latitude" value="<?= $latitude; ?>" required readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="longitude">Longitude</label>
									<input type="text" class="form-control" oninput="valid_user_name(this)"
										id="longitude" name="longitude" value="<?= $longitude; ?>" required readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="hostel_id">Address</label>
									<input type="text" class="form-control" id="address" name="address"
										value="<?= $address; ?>" required readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="special_tahsildar">Special Thasildhar</label>
									<select class="select form-control" id="special_tahsildar" name="special_tahsildar"
										required>
										<?php echo $hostel_special_tahsildar_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label for="assembly_const" class="form-label">Assembly
										Constituency </label>
									<select name="assembly_const" id="assembly_const" class="select form-control"
										required>
										<?php echo $assembly_const_name_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label for="parliment_const" class="form-label">Parliament
										Constituency</label>
									<select name="parliment_const" id="parliment_const" class="select form-control"
										required>
										<?php echo $parliment_const_name_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="hostel_location">Hostel Location</label>
									<select name="hostel_location" id="hostel_location" class="select form-control"
										required onchange="get_hostel_location_type()">
										<?php echo $hostel_location_type_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4" id="urbanFieldsType" style="display: none;">
								<div class="mb-3">
									<label for="urban_type" class="form-label">Urban Type</label>
									<select name="urban_type" id="urban_type" class="select form-control"
										onchange="get_urban_type()" required>
										<?php echo $urban_type_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4" id="corporationField" style="display: none;">
								<div class="mb-3">
									<label for="corporation" class="form-label">Corporation</label>
									<select name="corporation" id="corporation" class="select form-control" required>
										<?php echo $corporation_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4" id="municipalityField" style="display: none;">
								<div class="mb-3">
									<label for="municipality" class="form-label">Municipality</label>
									<select name="municipality" id="municipality" class="select form-control" required>
										<?php echo $municipality_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4" id="town_panchayatField" style="display: none;">
								<div class="mb-3">
									<label for="town_panchayat" class="form-label">Town Panchayat</label>
									<select name="town_panchayat" id="town_panchayat" class="select form-control" required>
										<?php echo $town_panchayat_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4" id="ruralFieldsTypeBlock" style="display: none;">
								<div class="mb-3">
									<label for="block_name" class="form-label">Block</label>
									<select name="block_name" id="block_name" class="select form-control"
										onchange="get_village()" required>
										<?php echo $block_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4" id="ruralFieldsTypeVillage" style="display: none;">
								<div class="mb-3">
									<label for="village_name" class="form-label">Village</label>
									<select name="village_name" id="village_name" class="select form-control" required>
										<?php echo $village_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label for="hostel_type" class="form-label">Hostel Type</label>
									<select name="hostel_type" id="hostel_type" class="select form-control" required
										disabled>
										<?php echo $hostel_type_options; ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label">Established Year</label>
									<input type="text" class="form-control" id="yob" name="yob" value="<?= $yob; ?>"
										oninput="validateYear(this)" maxlength="4" minlength="4" required>
									<small id="year-error" style="color:red; display:none;">Enter a valid year</small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<?php
									$borderClass = !empty($file_names) ? 'border-success' : 'border-danger';
									?>
									<label class="form-label">Government order for Hostel</label>
									<input type="file" class="form-control" id="test_file" name="test_file"
										accept=".pdf, .doc, .docx">
									<input type="hidden" class="form-control" id="file_name" name="file_name"
										value="<?php echo $file_names; ?>">
								</div>
								<span id="error_message"></span>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="distance_btw_phc">Distance From
										PHC(in Km)</label>
									<input type="text" class="form-control" id="distance_btw_phc"
										name="distance_btw_phc" value="<?= $distance_btw_phc; ?>"
										oninput="valid_aadhar_number(this)" maxlength="2" minlength="1" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="phc_name">Primary Health Center Name</label>
									<input type="text" oninput="valid_user_name(this)" class="form-control"
										id="phc_name" name="phc_name" value="<?= $phc_name; ?>" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="distance_btw_ps">Distance From Police
										Station(in Km)</label>
									<input type="text" class="form-control" oninput="valid_aadhar_number(this)"
										id="distance_btw_ps" name="distance_btw_ps" value="<?= $distance_btw_ps; ?>"
										maxlength="2" minlength="1" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="ps_name">Police Station Name</label>
									<input type="text" oninput="off_id(this)" class="form-control" id="ps_name"
										name="ps_name" value="<?= $ps_name; ?>" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="sanc_staff_count">Sanctioned Staff Count</label>
									<input type="text" oninput="valid_aadhar_number(this)" class="form-control"
										id="sanc_staff_count" name="sanc_staff_count" value="<?= $sanc_staff_count; ?>"
										maxlength="2" minlength="1" required readonly>

								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label" for="staff_count">Currently Working Staff Count</label>
									<input type="text" class="form-control" id="staff_count" name="staff_count"
										value="<?= $staff_count; ?>" maxlength="2" minlength="1" required readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<center><label class="form-label" for="staff_count">Entrance with hostel Name
										Board</label>
									<div class="mb-2">
										<?php if ($entrance_image) { ?>
											<img src="uploads/hostel_creation/<?php echo $entrance_image; ?>"
												id="entrance_preview" style="width: 72%;">
										<?php } else { ?>
											<img src="assets/images/No_Image_Available.jpg" id="entrance_preview"
												style="width: 72%;">
										<?php } ?>
									</div>
									<button type="button" id="capBtn1" class="btn btn-secondary mb-3"
										onclick="openCameraWindow1()">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
											viewBox="0 0 24 24" style="vertical-align: middle; margin-right: 8px;">
											<path
												d="M20 5h-3.2l-1.2-1.6A2 2 0 0 0 14 3H10a2 2 0 0 0-1.6.8L7.2 5H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm-8 13a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
										</svg>
										Capture</button>
								</center>
							</div>
							<div class="col-md-4">
								<center><label class="form-label" for="staff_count">Dining Area with resident students
										of the day</label>
									<div class="mb-2">
										<?php if ($dining_image) { ?>
											<img src="uploads/hostel_creation/<?php echo $dining_image; ?>"
												id="dining_preview" style="width: 72%;">
										<?php } else { ?>
											<img src="assets/images/No_Image_Available.jpg" id="dining_preview"
												style="width: 72%;">
										<?php } ?>
									</div>
									<button type="button" id="capBtn2" class="btn btn-secondary mb-3"
										onclick="openCameraWindow2()">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
											viewBox="0 0 24 24" style="vertical-align: middle; margin-right: 8px;">
											<path
												d="M20 5h-3.2l-1.2-1.6A2 2 0 0 0 14 3H10a2 2 0 0 0-1.6.8L7.2 5H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm-8 13a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
										</svg>
										Capture</button>
								</center>
							</div>
							<div class="col-md-4">
								<center><label class="form-label" for="staff_count">Full building view of the
										hostel</label>
									<div class="mb-2">
										<?php if ($building_image) { ?>
											<img src="uploads/hostel_creation/<?php echo $building_image; ?>"
												id="building_preview" style="width: 72%;">
										<?php } else { ?>
											<img src="assets/images/No_Image_Available.jpg" id="building_preview"
												style="width: 72%;">
										<?php } ?>
									</div>
									<button type="button" id="capBtn3" class="btn btn-secondary mb-3"
										onclick="openCameraWindow3()">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
											viewBox="0 0 24 24" style="vertical-align: middle; margin-right: 8px;">
											<path
												d="M20 5h-3.2l-1.2-1.6A2 2 0 0 0 14 3H10a2 2 0 0 0-1.6.8L7.2 5H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm-8 13a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
										</svg>
										Capture</button>
								</center>
							</div>

							<input type="hidden" class="form-control" id="entrance_image" name="entrance_image"
								value="<?= $entrance_image; ?>">
							<input type="hidden" class="form-control" id="dining_image" name="dining_image"
								value="<?= $dining_image; ?>">
							<input type="hidden" class="form-control" id="building_image" name="building_image"
								value="<?= $building_image; ?>">

							<input type="hidden" class="form-control" id="unique_id" name="unique_id"
								value="<?= $unique_id; ?>">
						</div>
					</form>
				</div>
				<div class="modal-footer justify-content-center border-0">
					<button type="button" id="submitBtn" class="btn btn-primary"
						onclick="hostel_update()">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.btn-secondary {
		--ct-btn-color: #fff;
		--ct-btn-bg: #4fd3ad;
		--ct-btn-border-color: #4fd3ad;
		--ct-btn-hover-color: #fff;
		--ct-btn-hover-bg: darkcyan;
		--ct-btn-hover-border-color: darkcyan;
	}
</style>