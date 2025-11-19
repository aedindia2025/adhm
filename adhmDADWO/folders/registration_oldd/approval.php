<?php

include '../../config/dbconfig.php';
// $reject_reason_list = reject_reason();
// $reject_reason_options = select_option($reject_reason_list, 'Select Reason', $reject_reason);

$renewal_reject_reason_options = renewal_reject_reason();
$renewal_reject_reason_options = select_option($renewal_reject_reason_options, "Select Reason");

$renewal_accept_reason_options = renewal_accept_reason();
$renewal_accept_reason_options = select_option($renewal_accept_reason_options, "Select Reason");



if (isset($_GET["batch_no"])) {
	if (!empty($_GET["batch_no"])) {

		// $batch_no = $_GET["batch_no"];
		$uni_dec = str_replace(" ", "+", $_GET['batch_no']);
		$get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

	}

	$where = [
		"batch_no" => $get_uni_id
	];

	$table = "batch_creation";

	$columns = [
		"batch_no",
		"(select hostel_name from hostel_name where hostel_name.unique_id = batch_creation.hostel_name) as hostel_name",
		"(select taluk_name from taluk_creation where taluk_creation.unique_id = batch_creation.hostel_taluk) as hostel_taluk",
		"(select district_name from district_name where  district_name.unique_id = batch_creation.hostel_district) as hostel_district",
		"(select amc_year from academic_year_creation where academic_year_creation.unique_id = batch_creation.academic_year) as academic_year",
		"batch_cr_date",
		"count(id) as count",
		"hostel_name as hostel_id",
		"batch_status",
		"batch_sub_date"
	];

	$table_details = [
		$table,
		$columns
	];

	$result_values = $pdo->select($table_details, $where);

	if ($result_values->status) {

		$result_values = $result_values->data;

		$batch_no = $result_values[0]["batch_no"];
		$hostel_name = $result_values[0]["hostel_name"];
		$hostel_taluk = $result_values[0]["hostel_taluk"];
		$hostel_district = $result_values[0]["hostel_district"];
		$academic_year = $result_values[0]["academic_year"];
		$batch_cr_date = $result_values[0]["batch_cr_date"];
		$count = $result_values[0]["count"];
		$hostel_id = $result_values[0]["hostel_id"];
		$batch_status = $result_values[0]["batch_status"];
		$batch_sub_date = $result_values[0]["batch_sub_date"];

	}

}

$sanc_cnt = hostel_name($hostel_id)[0]['sanc_strength'];
// $academic_year = last_academic_year();


?>
<style>
	table#approval_datatable {
		width: 100%;
		display: block;
		overflow: scroll;
	}
</style>
<div class="content-page">
	<div class="content">
		<!-- Start Content-->
		<div class="container-fluid">
			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box">
						<div class="page-title-right">
						</div>

						<h4 class="page-title">Approval / Rejection</h4>
					</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="container-fluid" style="background-color:#fff;">
					<div class="compl_print pt-2">
						<div class="zone_boxbor">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											Batch No : &nbsp<b><?php echo $batch_no; ?></b>
										</div><br><br>
										<div class="col-md-4">
											Academic Year : &nbsp<b><?php echo $academic_year; ?></b>
										</div><br><br>
										<div class="col-md-4">
											Hostel District :
											&nbsp<b><?php echo strtoupper($hostel_district); ?></b>
										</div><br><br>
										<div class="col-md-4">
											Hostel Taluk :
											&nbsp<b><?php echo strtoupper($hostel_taluk); ?></b>
										</div><br><br><br>
										<div class="col-md-4">
											Hostel Name :
											&nbsp<b><?php echo strtoupper($hostel_name); ?></b>
										</div><br><br>
										<div class="col-md-4">
											Batch Created Date :
											&nbsp<b><?php echo $batch_cr_date; ?></b>
										</div><br><br>
										<div class="col-md-4">
											Total Count Of Application :
											&nbsp<b><?php echo $count; ?></b>
										</div><br>
									</div>
									<input type="hidden" id="batch_no" value="<?= $batch_no; ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="batch_no" name="batch_no" value="<?= $batch_no; ?>">
			<input type="hidden" id="sanc_cnt" name="sanc_cnt" value="<?= $sanc_cnt; ?>">
			<input type="hidden" id="total_cnt" name="total_cnt" value="<?= $count; ?>">
			<div class="row">

				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<table id="approval_datatable" class="table dt-responsive nowrap w-100">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Application No</th>
										<th>Name As Aadhaar</th>
										<th>Name As EMIS/UMIS</th>
										<th>Name Difference Check</th>
										<th>Distance From Home to Hostel</th>
										<th>Distance From Home to School/College</th>
										<th>Community Certificate</th>
										<th>Income certificate</th>
										<th>Action</th>
										<th>View</th>

									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="compl_print">
						<div class="zone_boxbor">
							<div class="col-md-12">
								<div class="row user-f">
									<h4>Approved By</h4><br><br>
									<hr>
									<div class="col-md-4 mt-3">
										User ID : &nbsp<b><?php echo $_SESSION["user_name"]; ?></b>
									</div>
									<div class="col-md-4 mt-3">
										User Name :
										&nbsp<b><?php echo $_SESSION["staff_name"] ? $_SESSION["staff_name"] : '-'; ?></b>

									</div>
									<div class="col-md-4 mt-3">
										Submitted Date :
										&nbsp<b><?php echo $batch_sub_date ? (new DateTime($batch_sub_date))->format('d-m-Y') : '-'; ?></b>

									</div>
									<div class="col-md-4 mt-3">
										Status : &nbsp<b>
											<?php
											echo $batch_status == 0
												? '<span style="color:red;">Pending</span>'
												: ($batch_status == 1
													? '<span style="color:orange;">Partially Completed</span>'
													: '<span style="color:green;">Completed</span>');
											?>
										</b>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="btns" style="text-align: center; margin-left: 0px;">
					<button type="button" class="btn btn-primary m-t-15 waves-effect createupdate_btn"
						onclick="update_date();">Submit</button>
					<!-- <a href="index.php?file=registration/list"> -->
					<!-- <button type="button" class="btn btn-danger  m-t-15 btn-rounded waves-effect waves-light float-right ml-2">Cancel</button></a> -->

					<?php echo btn_cancel($btn_cancel); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<template id="accept-reason-template">
	<select name="accept_reason[]" class="accept-reason-selectbox select2 form-control mt-2" multiple>
		<?php echo $renewal_accept_reason_options; ?>
	</select>
</template>

<template id="reject-reason-template">
	<select name="reject_reason[]" class="reject-reason-selectbox select2 form-control mt-2" multiple>
		<?php echo $renewal_reject_reason_options; ?>
	</select>
</template>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="folders/registration/registration.js"></script>
<script src="assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
<script src="assets/libs/jquery_multiselect/jquery.multiselect.js<?php echo $js_css_file_comment; ?>"></script>
<script src="assets/libs/select2/js/select2.min.js<?php echo $js_css_file_comment; ?>"></script>

<script>



	$(document).ready(function () {



		$(document).on('click', '.accept-btn', function () {

			var batchNo = $(this).data('batch-no');
			var uniqueId = $(this).data('unique-id');
			var hostelId = $(this).data('hostel-name');
			var sanc_cnt = $(this).data('sanc-cnt');
			const name_diff = $(this).data("name_diff");
			const inst_distance_check = $(this).data("inst_distance_check");


			if (name_diff == 'mismatched' || name_diff == 'partially_matched' || inst_distance_check <= 5) {

				const $cell = $(this).closest('td');
				const selectHTML = $('#accept-reason-template').html();

				$cell.html(`
		   <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 10px;">
		<div style="display: flex; gap: 10px;">
				<button class="confirm-accept-btn" style="background-color: green; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;"
						data-batch-no="${batchNo}"
						data-unique-id="${uniqueId}"
						data-hostel-name="${hostelId}"
						data-sanc-cnt="${sanc_cnt}"
						>
					Confirm Accept
				</button>

				<button class="reject-btn" style="background-color: red; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;"
						data-batch-no="${batchNo}"
						data-unique-id="${uniqueId}"
						data-hostel-name="${hostelId}"
						data-name_diff="${name_diff}"
						data-inst_distance_check="${inst_distance_check}"
						data-sanc-cnt="${sanc_cnt}"
						>
					Reject
				</button>
			</div>
			<div style="width: 100%;">
			${selectHTML}
		</div>
	</div>                          
		`);

				/* ---------- activate Select2 on the new box ----- */
				$cell.find('.accept-reason-selectbox').select2({
					placeholder: 'Select reason',
					width: '100%',
					dropdownParent: $cell        // keeps dropdown inside this <td>
				});



			} else {


				var acceptButton = $(this); // Store reference to accept button
				acceptButton.prop('disabled', true);

				var ajax_url = sessionStorage.getItem("folder_crud_link");

				var data = {
					"batchNo": batchNo,
					"uniqueId": uniqueId,
					"hostelId": hostelId,
					"sanc_cnt": sanc_cnt,
					"action": "at_accept"
				}

				$.ajax({
					type: "POST",
					url: ajax_url,
					data: data,
					success: function (data) {

						var obj = JSON.parse(data);
						var msg = obj.msg;

						if (msg != "sanc_cnt_exceed") {
							acceptButton.hide(); // Hide accept button
							acceptButton.closest('td').html('Accepted'); // Show status as "Accepted"
							// log_sweetalert_approval("saved", "");
							register();
						} else if (msg == "sanc_cnt_exceed") {
							log_sweetalert_approval("sanc_cnt_exceed");
							acceptButton.prop('disabled', false);

						}
					}
				});

			}
		});

		$(document).on('click', '.confirm-accept-btn', function () {
			var batchNo = $(this).data('batch-no');
			var uniqueId = $(this).data('unique-id');
			var sanc_cnt = $(this).data('sanc-cnt');
			var reason = $(this).closest('td').find('.accept-reason-selectbox').val();
			var hostelId = $(this).data('hostel-name');

			var acceptButton = $(this);
			acceptButton.prop('disabled', true);

			var ajax_url = sessionStorage.getItem("folder_crud_link");
			if (reason.some(r => r.trim() !== '')) {
				var data = {
					"batchNo": batchNo,
					"uniqueId": uniqueId,
					"hostelId": hostelId,
					"sanc_cnt": sanc_cnt,
					"reason": reason,
					"action": "at_accept"
				}

				$.ajax({
					type: "POST",
					url: ajax_url,
					data: data,
					success: function (data) {

						var obj = JSON.parse(data);
						var msg = obj.msg;

						if (msg != "sanc_cnt_exceed") {
							acceptButton.hide(); // Hide accept button
							acceptButton.closest('td').html('Accepted'); // Show status as "Accepted"
							// log_sweetalert_approval("saved", "");
							register();
						} else if (msg == "sanc_cnt_exceed") {
							log_sweetalert_approval("sanc_cnt_exceed");
							acceptButton.prop('disabled', false);

						}
					}
				});
			} else {
				log_sweetalert_approval("no_acc_reason");
				acceptButton.prop('disabled', false);

			}
		});






		$(document).on('click', '.reject-btn', function () {

			var batchNo = $(this).data('batch-no');
			var uniqueId = $(this).data('unique-id');
			var hostelId = $(this).data('hostel-name');
			const name_diff = $(this).data("name_diff");
			var sanc_cnt = $(this).data('sanc-cnt');
			const inst_distance_check = $(this).data("inst_distance_check");

			const container = $(this).closest('td');
			const selectHTML = $('#reject-reason-template').html();

			container.html(`
		
		<div style="display: flex; flex-direction: column; align-items: flex-start; gap: 10px;">
		<div style="display: flex; gap: 10px;">
			<button class="accept-btn" style="background-color: green; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;"
			   data-batch-no="${batchNo}"
						data-unique-id="${uniqueId}"
						data-hostel-name="${hostelId}"
						data-name_diff="${name_diff}"
						data-inst_distance_check="${inst_distance_check}"
						data-sanc-cnt="${sanc_cnt}"
						>
				Accept
			</button>
			<button class="confirm-reject-btn" style="background-color: red; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;"
			   data-batch-no="${batchNo}"
						data-unique-id="${uniqueId}"
						data-hostel-name="${hostelId}">
				Confirm Reject
			</button>
		</div>
			<div style="width: 100%;">
			${selectHTML}
		</div>
	</div>        
	`);
			container.find('.reject-reason-selectbox').select2({
				placeholder: 'Select reason',
				width: '100%',
				dropdownParent: container        // keeps dropdown inside this <td>
			});
		});

		// Event listener for confirm reject button
		$(document).on('click', '.confirm-reject-btn', function () {

			var batchNo = $(this).data('batch-no');
			var uniqueId = $(this).data('unique-id');
			var reason = $(this).closest('td').find('.reject-reason-selectbox').val();


			var hostelId = $(this).data('hostel-name');
			var rejectButton = $(this); // Store reference to confirm reject button
			rejectButton.prop('disabled', true);


			var ajax_url = sessionStorage.getItem("folder_crud_link");
			if (reason.some(r => r.trim() !== '')) {

				var data = {
					"batchNo": batchNo,
					"uniqueId": uniqueId,
					"hostelId": hostelId,
					"reason": reason,
					"action": "at_reject"
				}

				$.ajax({
					type: "POST",
					url: ajax_url,
					data: data,
					success: function (data) {
						if (data) {
							rejectButton.hide(); // Hide confirm reject button
							rejectButton.closest('td').html('Rejected'); // Show status as "Rejected"
							log_sweetalert_approval("rejected", "");
						}
					}
				});
			} else {

				log_sweetalert_approval("no_reason");
				rejectButton.prop('disabled', false);

			}
		});
	});

	document.addEventListener("DOMContentLoaded", function () {
		var cancelBtn = document.querySelector(".btn-danger");
		if (cancelBtn) {
			cancelBtn.classList.remove("float-right");
		}
	});
</script>