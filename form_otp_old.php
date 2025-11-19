<?php include 'header.php'; ?>

<?php include 'config/dbconfig.php'; ?>
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
		color: #505050;
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
</style>
<?php
$academic_year_options = academic_year();
$academic_year_options = select_option_acc($academic_year_options);

$hostel_type_options = hostel_type();
$hostel_type_options = select_option($hostel_type_options);

?>

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
		<div class="card-body sep-mar ">
			<div class="row ">
				<div class="col-md-12">
					<div class="form-img">
						<img src="img/new-form.png">
					</div>
					<ul class="nav nav-pills bg-nav-pills nav-justified mb-3 mt-3">
						<li class="nav-item">
							<a href="#home1" data-bs-toggle="tab" aria-expanded="false"
								class="nav-link rounded-0 active">
								<i class="mdi mdi-home-variant d-md-none d-block"></i>
								<span class="d-none d-md-block">விடுதி விண்ணப்பம் / Hostel Application</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#profile1" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0 ">
								<i class="mdi mdi-account-circle d-md-none d-block"></i>
								<span class="d-none d-md-block">விண்ணப்ப திருத்தம் / Application Edit</span>
							</a>
						</li>

					</ul>

					<div class="tab-content">
						<div class="tab-pane  show active" id="home1">

							<div class="row spel-app normal-imput">
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-6">
											<label class="form-label" for="userName2">கல்வி ஆண்டு / Academic
												Year</label>
										</div>
										<div class="col-md-6">
											<select class="form-select" id="academic_year" disabled>
												<?php echo $academic_year_options; ?>
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label" for="userName2">எங்கே படிக்கிறாய்? / Where are you
												studying?</label>
										</div>
										<div class="col-md-6">
											<select class="form-select" onchange="checkOption(this)" id="hostel_type">
												<?php echo $hostel_type_options; ?>
											</select>
										</div>
										<div class="col-md-12">
											<span id="admissionMessage"
												style="color: red; display: none;font-weight: 700;font-size: 13px;">You
												can proceed with
												the application only after you've received admission to the
												college.</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-6">
											<label class="form-label" for="userName2">விண்ணப்ப வகை /
												Application Type</label>
										</div>
										<div class="col-md-6">
											<select class="form-select" id="app_type">
												<option selected value=''>Select</option>
												<option value="1">New</option>
												<option value="2">Renewal</option>
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label" for="userName2">ஆதார் எண் / Aadhaar No</label>

											<input type="hidden" class="form-control" id="unique_id" name="unique_id">
										</div>
										<div class="col-md-6">
											<!-- oninput="checkInputLength(this)" -->
											<input type="text" id="aadhar_no" class="form-control" maxlength="12"
												oninput="valid_aadhar_number(this)" onkeyup="onAadharKeyPress()">
											<span id="aadhaarError" class="error" style="color:red"></span>

										</div>
										<input type="hidden" id="invalid_aadhaar">
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col-md-12 mt-3 align-self-center text-center">
									<input type="button" class="comm-btn" id="gen_otp" onclick="gen_otp();"
										value="Generate OTP">
								</div>
							</div>
						</div>

						<div class="tab-pane" id="profile1">
							<div class="row spel-app normal-imput">
								<div class="col-md-3">
									<label class="form-label" for="userName2">ஆதார் எண் / Aadhaar No</label>

									<input type="hidden" class="form-control" id="unique_id" name="unique_id">
								</div>
								<div class="col-md-3">
									<input type="text" id="edit_aadhar_no" class="form-control" maxlength="12"
										oninput="valid_aadhar_number(this)" onkeyup="onAadharEditKeyPress()">
									<span id="aadhaarEditError" class="error" style="color:red"></span>
								</div>
								<input type="hidden" id="invalid_edit_aadhaar">
							</div>
							<div class="row">
								<div class="col-md-12 mt-3 align-self-center text-center">
									<input type="button" class="comm-btn" id="edit_gen_otp" onclick="edit_gen_otp();"
										value="Generate OTP">
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="txn">
	<input type="hidden" id="edit_txn">

	<input type="hidden" id="uuid">
	<input type="hidden" id="adob">
	<input type="hidden" id="agender">
	<input type="hidden" id="aname">
	<input type="hidden" id="aaddress">
	<input type="hidden" id="apincode">
	<input type="hidden" id="afatherName">
	<input type="hidden" id="pro_image">



	<!-- Warning Alert Modal -->

	<div id="login-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
		data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-body p-4 out-mar">
					<div class="text-center model-design">

						<img src="img/otp.webp">
						<input type="hidden" class="form-control" id="otp_no" name="otp_no">
						<input type="hidden" class="form-control" id="verify_otps" name="verify_otps">

						<h4 class="mt-2">Verification Code</h4>
						<p class="mt-0">We have sent a verification code to your Aadhaar Linked Mobile Number</p>
						<div id="inputs">
							<input id="otp1" type="text" maxlength="1">
							<input id="otp2" type="text" maxlength="1">
							<input id="otp3" type="text" maxlength="1">
							<input id="otp4" type="text" maxlength="1">
							<input id="otp5" type="text" maxlength="1">
							<input id="otp6" type="text" maxlength="1">
						</div>
						<!-- <a href="form_new2.php"> -->
						<button type="button" class="comm-btn mt-4" id="aad_otp_verify"
							onclick="aad_otp_verify()">Verify OTP</button></a>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="edit-login-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
		data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-body p-4 out-mar">
					<div class="text-center model-design">

						<img src="img/otp.webp">
						<input type="hidden" class="form-control" id="edit_otp_no" name="otp_no">
						<input type="hidden" class="form-control" id="edit_verify_otps" name="verify_otps">

						<h4 class="mt-2">Verification Code</h4>
						<p class="mt-0">We have sent a verification code to your Aadhaar Linked Mobile Number</p>
						<div id="inputs">
							<input id="edit_otp1" type="text" maxlength="1">
							<input id="edit_otp2" type="text" maxlength="1">
							<input id="edit_otp3" type="text" maxlength="1">
							<input id="edit_otp4" type="text" maxlength="1">
							<input id="edit_otp5" type="text" maxlength="1">
							<input id="edit_otp6" type="text" maxlength="1">
						</div>
						<!-- <a href="form_new2.php"> -->
						<button type="button" class="comm-btn mt-4" id="edit_aad_otp_verify"
							onclick="edit_aad_otp_verify()">Verify
							OTP</button></a>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->



</div>

<div class="row">
	<div class="col-md-12 load" id="loader">
		<i class="mdi mdi-loading mdi-spin"></i>
	</div>
</div>





<script>

	function showLoader() {
		$("#loader").css("display", "inline-block"); // or "block" depending on your preference
	}

	function hideLoader() {
		$("#loader").css("display", "none");
	}

	function base64Encode(str) {
		return btoa(str);
	}

	function base256Encode(str) {
		var result = '';
		for (var i = 0; i < str.length; i++) {
			var charCode = str.charCodeAt(i);
			result += pad(charCode, 3);
		}
		return result;
	}

	function pad(num, size) {
		var s = num + "";
		while (s.length < size) s = "0" + s;
		return s;
	}

	function valid_aadhar_number(input) {

		const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
		];

		// Filter out characters that are not in the allowedChars array
		input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
	}


	const inputs = ["otp1", "otp2", "otp3", "otp4", "otp5", "otp6"];

	inputs.map((id) => {
		const input = document.getElementById(id);
		addListener(input);
	});

	function addListener(input) {
		input.addEventListener("keyup", () => {
			const code = parseInt(input.value);
			if (code >= 0 && code <= 9) {
				const n = input.nextElementSibling;
				if (n) n.focus();
			} else {
				input.value = "";
			}

			const key = event.key; // const {key} = event; ES6+
			if (key === "Backspace" || key === "Delete") {
				const prev = input.previousElementSibling;
				if (prev) prev.focus();
			}
		});
	}


	const edit_inputs = ["edit_otp1", "edit_otp2", "edit_otp3", "edit_otp4", "edit_otp5", "edit_otp6"];

	edit_inputs.map((id) => {
		const edit_input = document.getElementById(id);
		addListener(edit_input);
	});

	function addListener(edit_input) {
		edit_input.addEventListener("keyup", () => {
			const code = parseInt(edit_input.value);
			if (code >= 0 && code <= 9) {
				const n = edit_input.nextElementSibling;
				if (n) n.focus();
			} else {
				edit_input.value = "";
			}

			const key = event.key; // const {key} = event; ES6+
			if (key === "Backspace" || key === "Delete") {
				const prev = edit_input.previousElementSibling;
				if (prev) prev.focus();
			}
		});
	}


	function formatAadharInput(input) {

		// Remove all non-digit characters
		let cleaned = input.value.replace(/\D/g, '');

		// Format the cleaned input as groups of 4 digits separated by hyphens
		let formatted = '';
		for (let i = 0; i < cleaned.length; i++) {
			if (i > 0 && i % 4 === 0) {
				formatted += '-';
			}
			formatted += cleaned[i];
		}

		// Update the input value with the formatted Aadhar number
		input.value = formatted;
	}

	function checkInputLength(input) {
		if (input.value.length === parseInt(input.getAttribute('maxlength'))) {
			triggerSweetAlert();
		}
	}

	function triggerSweetAlert() {
		Swal.fire({
			icon: 'warning',
			title: 'Please ensure you have a Aadhar-linked phone is with you for the OTP generation',
			showConfirmButton: true,
			timer: 9000,
		});
	}

	function checkOption(select) {

		var admissionMessage = document.getElementById("admissionMessage");
		if (select.value === "65f00a3e3c9a337012" || select.value === "65f00a495599589293" || select.value === "65f00a53eef3015995") {
			admissionMessage.style.display = "inline";
		} else {
			admissionMessage.style.display = "none";
		}
	}

	function onAadharKeyPress() {
		var aadhaarNo = document.getElementById("aadhar_no").value;

		var aadhaarError = document.getElementById("aadhaarError");

		// Validate using Verhoeff algorithm
		if (!verhoeffCheck(aadhaarNo)) {
			aadhaarError.textContent = "Invalid Aadhaar number.";
			$("#invalid_aadhaar").val('Invalid');
		} else {
			aadhaarError.textContent = ""; // Clear error message if Aadhaar number is valid
			$("#invalid_aadhaar").val('');
			if (aadhaarNo.length == '12') {
				triggerSweetAlert();
			}
		}
	}


	function onAadharEditKeyPress() {
		var aadhaar_edit_No = document.getElementById("edit_aadhar_no").value;

		var aadhaarEditError = document.getElementById("aadhaarEditError");

		// Validate using Verhoeff algorithm
		if (!verhoeffCheck(aadhaar_edit_No)) {
			aadhaarEditError.textContent = "Invalid Aadhaar number.";
			$("#invalid_edit_aadhaar").val('Invalid');
		} else {
			aadhaarEditError.textContent = ""; // Clear error message if Aadhaar number is valid
			$("#invalid_edit_aadhaar").val('');
			if (aadhaar_edit_No.length == '12') {
				triggerSweetAlert();
			}
		}
	}

	function verhoeffCheck(num) {
		var verhoeffTable = [
			[0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
			[1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
			[2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
			[3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
			[4, 0, 1, 2, 3, 9, 5, 6, 7, 8],
			[5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
			[6, 5, 9, 8, 7, 1, 0, 4, 3, 2],
			[7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
			[8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
			[9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
		];

		var verhoeffPermutation = [
			[0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
			[1, 5, 7, 6, 2, 8, 3, 0, 9, 4],
			[5, 8, 0, 3, 7, 9, 6, 1, 4, 2],
			[8, 9, 1, 6, 0, 4, 3, 5, 2, 7],
			[9, 4, 5, 3, 1, 2, 6, 8, 7, 0],
			[4, 2, 8, 6, 5, 7, 3, 9, 0, 1],
			[2, 7, 9, 3, 8, 0, 6, 4, 1, 5],
			[7, 0, 4, 6, 9, 1, 3, 2, 5, 8]
		];

		var c = 0;
		var numArray = num.split('').map(Number).reverse();

		for (var i = 0; i < numArray.length; i++) {
			c = verhoeffTable[c][verhoeffPermutation[(i % 8)][numArray[i]]];
		}

		return (c === 0);
	}


	function encodeAadhar(aadhar_no) {
		// Encode Aadhar number using Base64 encoding
		var encodedAadhar = btoa(aadhar_no);
		return encodedAadhar;
	}

	function encodeEditAadhar(edit_aadhar_no) {
		// Encode Aadhar number using Base64 encoding
		var encodeEditAadhar = btoa(edit_aadhar_no);
		return encodeEditAadhar;
	}


	function gen_otp() {

		var aadhar_no = $("#aadhar_no").val();
		var encodedAadhar = base256Encode(aadhar_no);

		$("#gen_otp").prop("disabled", true);
		var hostel_type = $("#hostel_type").val();
		var app_type = $("#app_type").val();

		if (aadhar_no != '' && hostel_type != '' && app_type != '') {




			var ajax_url = "crud_v1.php";
			var data = {
				"aadhar_no": encodedAadhar,
				"action": "check_aadhar"
			};

			showLoader();

			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				dataType: 'json', // Parse response as JSON

				success: function (response) {

					if (response.data) {
						// Handle success
						//console.log(response.data.RESPONSE);

						var status = response.data.RESPONSE.STATUS;
						var uuid = response.data.RESPONSE.UUID;

						var data_check = {
							"uuid": base256Encode(uuid),
							"action": "already_uuid"
						};

						$.ajax({
							type: "POST",
							url: ajax_url,
							data: data_check,
							dataType: 'json',
							success: function (status) {

								var uuid_msg = status.msg;

								if (uuid_msg == "not_found") {

									var ajax_url = "crud_v1.php";
									var data = {
										"aadhar_no": encodedAadhar,
										"action": "gen_otp"
									};

									$.ajax({
										type: "POST",
										url: ajax_url,
										data: data,
										dataType: 'json', // Parse response as JSON
										success: function (response) {

											hideLoader();

											if (response.status) {
												// Handle success
												// console.log(response.data.result);

												var errdesc	= response.data.errdesc;
												var err	= response.data.err;
												var txnValue = response.data.txn;

												if(err != "000") {
													sweetalert("aadhar_maintanance",'','',errdesc);
													$("#gen_otp").prop("disabled", false);
												} 												else{

													document.getElementById('txn').value = txnValue;
													$('#login-modal').modal('show');

												}


											} else {
												// Handle error
												console.log(response.data.message);
											}
										},
										error: function (xhr, status, error) {
											hideLoader();
											// Handle AJAX error
											console.error(xhr.responseText);
										}
									});

								} else {
									hideLoader();
									sweetalert("aadhar_already");
									$("#gen_otp").prop("disabled", false);
								}

							},
							error: function (xhr, status, error) {
								hideLoader();
								console.error("Error checking UUID:", error);
							}
						});

					} else {
						hideLoader();
						// Handle error
						console.log(response.data.message);
					}
				},
				error: function (xhr, status, error) {
					hideLoader();
					sweetalert("failed_conn_auth");
					$("#gen_otp").prop("disabled", false);
					// Handle AJAX error
					console.error(xhr.responseText);
				}
			});

		} else {
			sweetalert("form_alert");
			$("#gen_otp").prop("disabled", false);
		}

	}

	function aad_otp_verify() {

		$("#aad_otp_verify").prop("disabled", true);

		showLoader();
		var aadhar_no = $("#aadhar_no").val();
		var encodedAadhar = base256Encode(aadhar_no);

		var txn = document.getElementById('txn').value;
		var a = document.getElementById('otp1').value;
		var b = document.getElementById('otp2').value;
		var c = document.getElementById('otp3').value;
		var d = document.getElementById('otp4').value;
		var e = document.getElementById('otp5').value;
		var f = document.getElementById('otp6').value;

		var academic_year = $("#academic_year").val();
		var hostel_type = $("#hostel_type").val();
		var app_type = $("#app_type").val();

		//var data = "&unique_id=" + unique_id + "&hostel_type=" + hostel_type + "&academic_year=" + academic_year + "&app_type=" + app_type + "&aadhar_no=" + aadhar_no + "&action=createupdate";

		var otp = a + b + c + d + e + f;

		if (aadhar_no != '' && otp.length === 6) {
			var encode_otp = base256Encode(otp);
			var ajax_url = "crud_v1.php";
			var data = {
				"aadhar_no": encodedAadhar,
				"otp": encode_otp,
				"txn": txn,
				"action": "aad_otp_verify"
			};



			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				dataType: 'json', // Parse response as JSON
				success: function (response) {

					if(response.data.err != "000"){
						
						sweetalert("aadhar_maintanance",'','',response.data.errdesc);
						$("#aad_otp_verify").prop("disabled", false);

					}

					else{

						if (response.data.errdesc != "OTP validation failed") {

							var decodedData = base64DecodeUnicode(response.data.responseXML);
							//console.log(decodedData);

							// Parse XML
							var parser = new DOMParser();
							var xmlDoc = parser.parseFromString(decodedData, "text/xml");

							var pro_image = xmlDoc.querySelector("Pht").textContent;

							// Extract fields from the XML
							var KycResNode = xmlDoc.querySelector("KycRes");
							var uuid = KycResNode.getAttribute("uuid");

							//alert(uuid);

							var poiNode = xmlDoc.querySelector("Poi");
							var dob = poiNode.getAttribute("dob");
							var gender = poiNode.getAttribute("gender");
							var name = poiNode.getAttribute("name");

							var poaNode = xmlDoc.querySelector("Poa");
							var co = poaNode.getAttribute("co");
							var country = poaNode.getAttribute("country");
							var dist = poaNode.getAttribute("dist");
							var house = poaNode.getAttribute("house");
							var lm = poaNode.getAttribute("lm");
							var pc = poaNode.getAttribute("pc");
							var po = poaNode.getAttribute("po");
							var state = poaNode.getAttribute("state");
							var street = poaNode.getAttribute("street");
							var subdist = poaNode.getAttribute("subdist");
							var vtc = poaNode.getAttribute("vtc");

							// Concatenate address fields excluding null or empty values
							var addressParts = [co, house, street, po, vtc, dist, state, pc].filter(function (value) {
								return value !== null && value !== "";
							});
							var address = addressParts.join(", ");

							var fatherName = co.replace(/^(S\/O|D\/O) ?:? ?/i, "");


							// Append field values to individual text boxes
							$("#uuid").val(uuid);
							$("#adob").val(dob);
							$("#agender").val(gender);
							$("#aname").val(name);
							$("#apincode").val(pc);
							$("#afatherName").val(fatherName);
							$("#aaddress").val(address);
							$('#pro_image').val(pro_image)


							// alert(fatherName);
							// alert(address);

							var data1 = "&unique_id=" + unique_id + "&hostel_type=" + base64Encode(hostel_type) + "&academic_year=" + base64Encode(academic_year) + "&app_type=" + base64Encode(app_type) + "&uuid=" + base64Encode(uuid) + "&std_name=" + base64Encode(name) + "&action=createupdate";

							$.ajax({
								type: "POST",
								url: "crud.php",
								data: data1,

								success: function (data) {

									var obj = JSON.parse(data);
									var msg = obj.msg;
									var unique_id = obj.unique_id;
									var status = obj.status;
									var error = obj.error;

									if (msg == 'otp') {
										//alert();
										hideLoader();
										$("#login-modal").modal('hide');

										var a_data = {
											"s1_unique_id": base256Encode(unique_id),
											"uuid": base256Encode(uuid),
											"dob": base256Encode(dob),
											"gender": base256Encode(gender),
											"name": base256Encode(name),
											"pc": base256Encode(pc),
											"fatherName": base256Encode(fatherName),
											"address": base256Encode(address),
											"pro_image": pro_image,
											"action": "a_create"
										};

										$.ajax({
											type: "POST",
											url: "crud.php",
											data: a_data,
											dataType: 'json', // Parse response as JSON
											success: function (response) {
												//alert(response);

												if (response.status) {
													// Handle success

												} else {
													// Handle error
													console.log(response.data);
												}
											},
											error: function (xhr, status, error) {
												// Handle AJAX error
												console.error(xhr.responseText);
											}
										});

										sweetalert("verify", "form_application.php?unique_id=" + unique_id);


									} //else {
									// 	sweetalert("aadhar_already");
									// }

								},
								error: function (data) {
									alert("Network Error");
									$("#aad_otp_verify").prop("disabled", false);
								}
							});

						} else {
							// Handle error
							console.log(response.data.message);
							sweetalert("wrong_otp");
							$("#aad_otp_verify").prop("disabled", false);

						}

					}
				},
				error: function (xhr, status, error) {
					// Handle AJAX error
					hideLoader();
					sweetalert("failed_conn_auth");
					$("#aad_otp_verify").prop("disabled", false);
					console.error(xhr.responseText);
				}
			});
		} else {
			sweetalert("wrong_otp");
			$("#aad_otp_verify").prop("disabled", false);
		}

	}


	function base64DecodeUnicode(str) {
		// Decode string from base64
		var base64 = str.replace(/-/g, '+').replace(/_/g, '/');
		var binaryString = window.atob(base64);

		// Convert binary string to character-number array
		var bytes = new Uint8Array(binaryString.length);
		for (var i = 0; i < binaryString.length; i++) {
			bytes[i] = binaryString.charCodeAt(i);
		}

		// Decode array of characters into Unicode string
		var decodedString = new TextDecoder('utf-8').decode(bytes);

		return decodedString;
	}


	function edit_gen_otp() {

		var edit_aadhar_no = $("#edit_aadhar_no").val();
		var encodedEditAadharNo = base256Encode(edit_aadhar_no);
		$("#edit_gen_otp").prop("disabled", true);

		if (aadhar_no != '') {

			var ajax_url = "crud_v1.php";
			var data = {
				"edit_aadhar_no": encodedEditAadharNo,
				"action": "edit_check_aadhar"
			};

			showLoader();

			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				dataType: 'json', // Parse response as JSON

				success: function (response) {

					if (response.data) {
						// Handle success
						//console.log(response.data.RESPONSE);

						var status = response.data.RESPONSE.STATUS;
						var uuid = response.data.RESPONSE.UUID;

						var data_check = {
							"uuid": base256Encode(uuid),
							"action": "edit_already_uuid"
						};

						$.ajax({
							type: "POST",
							url: ajax_url,
							data: data_check,
							dataType: 'json',
							success: function (status) {

								var uuid_msg = status.msg;

								if (uuid_msg == "already") {

									var ajax_url = "crud_v1.php";
									var data = {
										"edit_aadhar_no": encodedEditAadharNo,
										"action": "edit_gen_otp"
									};

									$.ajax({
										type: "POST",
										url: ajax_url,
										data: data,
										dataType: 'json', // Parse response as JSON
										success: function (response) {

											hideLoader();

											if (response.status) {
												// Handle success
												// console.log(response.data.result);

												var errdesc	= response.data.errdesc;
												var err	= response.data.err;
												var txnValue = response.data.txn;

												if(err != "000") {
													sweetalert("aadhar_maintanance",'','',errdesc);
													$("#edit_gen_otp").prop("disabled", false);
												} 
												else{

												document.getElementById('edit_txn').value = txnValue;
												$('#edit-login-modal').modal('show');
												}

											} else {
												// Handle error
												console.log(response.data.message);
											}
										},
										error: function (xhr, status, error) {
											hideLoader();
											// Handle AJAX error
											console.error(xhr.responseText);
										}
									});

								} else {
									hideLoader();
									sweetalert("no_aadhar");
									$("#edit_gen_otp").prop("disabled", false);
								}

							},
							error: function (xhr, status, error) {
								hideLoader();
								console.error("Error checking UUID:", error);
							}
						});

					} else {
						hideLoader();
						// Handle error
						console.log(response.data.message);
					}
				},
				error: function (xhr, status, error) {
					hideLoader();
					sweetalert("failed_conn_auth");
					$("#edit_gen_otp").prop("disabled", false);
					// Handle AJAX error
					console.error(xhr.responseText);
				}
			});

		} else {
			alert("Enter Aadhar Number");
			$("#edit_gen_otp").prop("disabled", false);
		}

	}


	function edit_aad_otp_verify() {

		$("#edit_aad_otp_verify").prop("disabled", true);

		var edit_aadhar_no = $("#edit_aadhar_no").val();
		var encodedEditAadharNo = base256Encode(edit_aadhar_no);

		var edit_txn = document.getElementById('edit_txn').value;
		var a = document.getElementById('edit_otp1').value;
		var b = document.getElementById('edit_otp2').value;
		var c = document.getElementById('edit_otp3').value;
		var d = document.getElementById('edit_otp4').value;
		var e = document.getElementById('edit_otp5').value;
		var f = document.getElementById('edit_otp6').value;


		var otp = a + b + c + d + e + f;

		if (edit_aadhar_no != '' && otp.length === 6) {
			var encode_otp = base256Encode(otp);
			var ajax_url = "crud_v1.php";
			var data = {
				"edit_aadhar_no": encodedEditAadharNo,
				"otp": encode_otp,
				"edit_txn": edit_txn,
				"action": "edit_aad_otp_verify"
			};

			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				dataType: 'json', // Parse response as JSON
				success: function (response) {

					if(response.data.err != "000"){
						
						sweetalert("aadhar_maintanance",'','',response.data.errdesc);
						$("#edit_aad_otp_verify").prop("disabled", false);

					}

					else{

						if (response.data.errdesc != "OTP validation failed") {

							var decodedData = base64DecodeUnicode(response.data.responseXML);
							//console.log(decodedData);

							// Parse XML
							var parser = new DOMParser();
							var xmlDoc = parser.parseFromString(decodedData, "text/xml");

							var pro_image = xmlDoc.querySelector("Pht").textContent;

							// Extract fields from the XML
							var KycResNode = xmlDoc.querySelector("KycRes");
							var uuid = KycResNode.getAttribute("uuid");

							//alert(uuid);

							var poiNode = xmlDoc.querySelector("Poi");
							var dob = poiNode.getAttribute("dob");
							var gender = poiNode.getAttribute("gender");
							var name = poiNode.getAttribute("name");

							var poaNode = xmlDoc.querySelector("Poa");
							var co = poaNode.getAttribute("co");
							var country = poaNode.getAttribute("country");
							var dist = poaNode.getAttribute("dist");
							var house = poaNode.getAttribute("house");
							var lm = poaNode.getAttribute("lm");
							var pc = poaNode.getAttribute("pc");
							var po = poaNode.getAttribute("po");
							var state = poaNode.getAttribute("state");
							var street = poaNode.getAttribute("street");
							var subdist = poaNode.getAttribute("subdist");
							var vtc = poaNode.getAttribute("vtc");

							// Concatenate address fields excluding null or empty values
							var addressParts = [co, house, street, po, vtc, dist, state, pc].filter(function (value) {
								return value !== null && value !== "";
							});
							var address = addressParts.join(", ");

							var fatherName = co.replace(/^(S\/O|D\/O) ?:? ?/i, "");

							//alert();
							var data_there = {
								"uuid": uuid,
								"action": "get_unique_id"
							};
							var ajax_url = "crud_v1.php";

							$.ajax({
								type: "POST",
								url: ajax_url,
								data: data_there,
								dataType: 'json',
								success: function (status) {
									if (status.msg === "uid") {
										var unique_id = status.unique_id;

										hideLoader();
										$("#edit-login-modal").modal('hide');

										sweetalert("verify", "form_application.php?unique_id=" + unique_id);
									} else {
										alert("no_uid");
										sweetalert("no_uid");
									}
								},
							});
						} else {
							// Handle error
							console.log(response.data.message);
							sweetalert("wrong_otp");
							$("#edit_aad_otp_verify").prop("disabled", false);

						}
					}
				},
				error: function (xhr, status, error) {
					// Handle AJAX error
					hideLoader();
					sweetalert("failed_conn_auth");
					$("#edit_aad_otp_verify").prop("disabled", false);
					console.error(xhr.responseText);
				}
			});
		} else {
			sweetalert("wrong_otp");
			$("#edit_aad_otp_verify").prop("disabled", false);
		}

	}
	document.addEventListener('contextmenu', function (event) {
		event.preventDefault();
	});

	document.onkeydown = function (e) {
		if (event.keyCode == 123) {
			return false;
		}
		if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
			return false;
		}
		if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
			return false;
		}
		if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
			return false;
		}
		if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
			return false;
		}
	}


	function sweetalert(msg = '', url = '', callback = '', title = '') {

		switch (msg) {

			case "verify":
				Swal.fire({
					icon: 'success',
					title: 'OTP Verified Successfully',

					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "cc_verify":
				Swal.fire({
					icon: 'success',
					title: 'Verified',
					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "inc_verify":
				Swal.fire({
					icon: 'success',
					title: 'Verified',
					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "priority_added":
				Swal.fire({
					icon: 'success',
					title: 'Priority For This Hostel Has Created',
					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "wrong_otp":
				Swal.fire({
					icon: 'warning',
					title: 'Please Enter Valid OTP',
					// text: 'Modal with a custom image.',  
					//imageUrl:'img/emoji/success.webp',
					// imageWidth: 250,
					// imageHeight: 200,
					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

	case "no_aadhar":
				Swal.fire({
					icon: 'warning',
					title: 'Aadhar Number Not Registered',
					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							// window.location = url;
						}
					}
				});
				break;



			case "income_expiry":
				Swal.fire({
					icon: 'warning',
					title: 'Your Income Certificate is Expired',
					// text: 'Modal with a custom image.',  
					//imageUrl:'img/emoji/success.webp',
					// imageWidth: 250,
					// imageHeight: 200,
					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

				

			case "income_level_exceed":
				Swal.fire({
					icon: 'warning',
					title: 'Your Income Level is above the Limit, You Cannot Proceed the Application',
					// text: 'Modal with a custom image.',  
					//imageUrl:'img/emoji/success.webp',
					// imageWidth: 250,
					// imageHeight: 200,
					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "invalid_aadhaar":
				Swal.fire({
					icon: 'warning',
					title: 'Please Enter Valid Aadhaar Number',
					// text: 'Modal with a custom image.',  
					//imageUrl:'img/emoji/success.webp',
					// imageWidth: 250,
					// imageHeight: 200,
					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							// window.location = url;
						}
					}
				});
				break;

			case "no_income_record":
				Swal.fire({
					icon: 'warning',
					title: 'No Data found for given Certificate No.',
					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							// window.location = url;
						}
					}
				});
				break;

			case "no_community_record":
				Swal.fire({
					icon: 'warning',
					title: 'No Data found for given Certificate No.',
					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							// window.location = url;
						}
					}
				});
				break;

			case "mobile_already":
				Swal.fire({
					icon: 'warning',
					title: 'Mobile Number Already Registered',

					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "priority_exceed":
				Swal.fire({
					icon: 'warning',
					title: 'Priority For This Hostel Is Removed',

					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "valid_emis":
				Swal.fire({
					icon: 'warning',
					title: 'Please enter Valid EMIS ID',

					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "valid_umis":
				Swal.fire({
					icon: 'warning',
					title: 'Please enter Valid UMIS ID',

					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "valid_com":
				Swal.fire({
					icon: 'warning',
					title: 'Please enter Valid Community Certificate Number',

					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "valid_inc":
				Swal.fire({
					icon: 'warning',
					title: 'Please enter Valid Income Certificate Number',

					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

				case "aadhar_server_down":
				Swal.fire({
					icon: 'warning',
					title: 'Unable to connect to the Aadhar Server',

					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

case "aadhar_maintanance":
				Swal.fire({
					icon: 'warning',
					title: title,

					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

				

			case "aadhar_already":
				Swal.fire({
					icon: 'warning',
					title: 'Aadhar Number has Already Registered',

					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "not_reg_mob":
				Swal.fire({
					icon: 'warning',
					title: 'Please Enter Registered Mobile Number',
					// text: 'Modal with a custom image.',  
					//imageUrl:'img/emoji/success.webp',
					// imageWidth: 250,
					// imageHeight: 200,
					imageAlt: 'Custom image',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;


			case "update":
				Swal.fire({
					icon: 'success',
					title: 'Successfully Updated',
					//imageUrl:'img/emoji/clapping.webp',
					showConfirmButton: true,
					timer: 2000,
					timerProgressBar: true,
					willClose: () => {
						if (url) {
							window.location = url;
						}
					}
				});
				break;

			case "error":
				Swal.fire({
					icon: 'error',
					title: 'Error Occured',
					showConfirmButton: true,
					timer: 2000,
					timerProgressBar: true,
					willClose: () => {
						// alert("Hi");
					}
				});
				break;

			case "network_err":
				Swal.fire({
					icon: 'error',
					title: 'Network Error Occured',
					showConfirmButton: true,
					timer: 2000,
					timerProgressBar: true,
					willClose: () => {
						// alert("Hi");
					}
				});
				break;

			case "otp":
				Swal.fire({
					icon: 'success',
					title: 'OTP verified!',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						// alert("Hi");
					}
				});
			case "otp_verify":
				Swal.fire({
					icon: 'warning',
					title: 'Please Enter valid OTP!',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						// alert("Hi");
					}
				});
				break;
			// end
			case "not_exist":
				Swal.fire({
					icon: 'warning',
					title: 'Mobile Number Not Registered',
					//imageUrl:'img/emoji/already.webp',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						// alert("Hi");
					}
				});
				break;

			case "mob_already_exist":
				Swal.fire({
					icon: 'warning',
					title: 'Mobile Number Already Registered',
					//imageUrl:'img/emoji/already.webp',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						// alert("Hi");
					}
				});
				break;



			case "already":
				Swal.fire({
					icon: 'warning',
					title: 'Mobile Number Already',
					//imageUrl:'img/emoji/already.webp',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true,
					willClose: () => {
						// alert("Hi");
					}
				});
				break;

			case "no_internet":
				Swal.fire({
					icon: 'warning',
					title: 'Please Check Your Internet Connection!',
					showConfirmButton: true,
					timer: 2000,
					timerProgressBar: true,

					willClose: () => {
						// alert("Hi");
					}
				});
				break;

		
			case "delete":
				return Swal.fire({
					title: 'Are you sure to Delete?',
					// text: "You won't be able to revert this!",
					icon: 'warning',
					//imageUrl:'img/emoji/delete.webp',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!',
					preConfirm: () => {
						return true;
					}
				});
				break;

			case "success_delete":
				Swal.fire({
					icon: 'success',
					title: 'Deleted!',
					//imageUrl:'img/emoji/success_delete.webp',
					showConfirmButton: true,
					timer: 1500,
					timerProgressBar: true
				});
				break;

			case "form_alert":
				Swal.fire({
					icon: 'info',
					title: 'Fill Out All Mandatory Fields',
					// imageUrl:'img/emoji/form_fill.webp',
					showConfirmButton: true,
					timer: 6000,
					timerProgressBar: true
				})
				break;


			case "approve":
				Swal.fire({
					icon: 'success',
					title: 'Successfully Approved',
					showConfirmButton: true,
					timer: 2000,
					willClose: () => {
						window.location = url;
					}
				});
				break;

			case "convert":
				Swal.fire({
					icon: 'success',
					title: 'Successfully Converted',
					showConfirmButton: true,
					timer: 2000,
					willClose: () => {
						window.location = url;
					}
				});
				break;

			case "add":
				Swal.fire({
					icon: 'success',
					title: 'Successfully Added',
					//imageUrl:'img/emoji/success_delete.webp',
					showConfirmButton: true,
					timer: 2000,
					willClose: () => {
						//   window.location = url;
					}
				});
				break;

			case "custom":
				Swal.fire({
					icon: 'info',
					title: title,
					willClose: () => {

						if (url != "") {
							window.location = url;
						}
					}
				});
				break;

			case "password_alert":
				Swal.fire({
					icon: 'info',
					title: 'Please Update either Password Or Profile Image',
					//imageUrl:'img/emoji/form_fill.webp',
					showConfirmButton: true,
					timer: 2000,
					timerProgressBar: true
				})
				break;
		}
	}

</script>


<?php include 'footer.php'; ?>