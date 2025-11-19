$(document).ready(function () {

	auto_datatable("auto_datatable", form_name, "auto_datatable");
	m_datatable("m_datatable", form_name, "m_datatable");

});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Renewal';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'auto_datatable';
var action = "datatable";

function m_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);
	var data = {
		"action": action,
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		searching: false,
		responsive: false,
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		dom: 'Blfrtip',
		buttons: [
			{
				extend: 'pdfHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Renewal',
				filename: 'renewal'
			}

		],
		lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		pageLength: 10,
		paging: true,
		searching: false,

	});

}


function auto_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);
	var data = {
		"action": action,
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		searching: false,
		responsive: false,
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		dom: 'Blfrtip',
		buttons: [
			{
				extend: 'pdfHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Renewal',
				filename: 'renewal'
			}
		],

		lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]], // Entries per page
		pageLength: 10, // Default entries per page
		paging: true,
		searching: false
	});
}


function showLoader() {

	$("#loader").css("display", "inline-block");
}

function hideLoader() {
	$("#loader").css("display", "none");
}

function showLoader_1() {

	$("#loader_1").css("display", "inline-block");
}

function hideLoader_1() {
	$("#loader_1").css("display", "none");
}


function m_showLoader() {

	$("#loader").css("display", "inline-block");
}

function m_hideLoader() {
	$("#loader").css("display", "none");
}

function m_showLoader_1() {

	$("#loader_1").css("display", "inline-block");
}

function m_hideLoader_1() {
	$("#loader_1").css("display", "none");
}



function get_taluk_id() {

	var district_id = $('#district_id').val();

	var data = "district_id=" + district_id + "&action=get_taluk_id";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		url: ajax_url,
		data: data,
		type: "POST",

		success: function (data) {
			if (data) {
				$('#taluk_id').html(data);
			}
		}
	});
}

function get_hostel_id() {
	var taluk_id = $('#taluk_id').val();

	var gender_id = $('#gender_id').val();

	var hostel_type_id = $('#hostel_type_id').val();

	var data = "taluk_id=" + taluk_id + "&gender_id=" + gender_id + "&hostel_type_id=" + hostel_type_id + "&action=get_hostel_id";

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#hostelId").html(data);
			}
		}
	});
}



function m_get_taluk_id() {

	var m_district_id = $('#m_district_id').val();

	var data = "m_district_id=" + m_district_id + "&action=m_get_taluk_id";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		url: ajax_url,
		data: data,
		type: "POST",

		success: function (data) {
			if (data) {
				$('#m_taluk_id').html(data);
			}
		}
	});
}

function m_get_hostel_id() {
	var m_taluk_id = $('#m_taluk_id').val();

	var m_gender_id = $('#m_gender_id').val();

	var m_hostel_type_id = $('#m_hostel_type_id').val();

	var data = "m_taluk_id=" + m_taluk_id + "&m_gender_id=" + m_gender_id + "&m_hostel_type_id=" + m_hostel_type_id + "&action=m_get_hostel_id";

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#m_hostelId").html(data);
			}
		}
	});
}

function transferStudent(button) {
	const regNo = button.getAttribute('data-reg_no');
	const s1UniqueId = button.getAttribute('data-s1_unique_id');
	const studentName = button.getAttribute('data-std_name');

	document.getElementById('regNo').value = regNo;
	document.getElementById('s1UniqueId').value = s1UniqueId;
	document.getElementById('studentName').value = studentName;

	$('#transferModal').modal({
		backdrop: 'static',
		keyboard: false
	});
	$('#transferModal').modal('show');

}


function transferStd() {

	const std_id = document.getElementById("s1UniqueId").value;
	const std_reg_no = document.getElementById("regNo").value;
	const std_name = document.getElementById("studentName").value;
	const from_district = document.getElementById("fromDistrict").value;
	const from_taluk = document.getElementById("fromTaluk").value;
	const from_hostel = document.getElementById("fromHostel").value;
	const to_district = document.getElementById("district_id").value;
	const to_taluk = document.getElementById("taluk_id").value;
	const to_hostel = document.getElementById("hostelId").value;

	if (to_district != '' && to_taluk != '' && to_hostel != '') {
		// Validation: transfer hostel must not be same as current hostel
		if (from_hostel === to_hostel) {
			r_sweetalert("transfer_hostel_same");
			return;
		}

		const data = {
			action: "transfer_student",
			std_id: std_id,
			std_reg_no: std_reg_no,
			std_name: std_name,
			from_district: from_district,
			from_taluk: from_taluk,
			from_hostel: from_hostel,
			to_district: to_district,
			to_taluk: to_taluk,
			to_hostel: to_hostel
		};


		var ajax_url = sessionStorage.getItem("folder_crud_link");


		$.ajax({
			url: ajax_url,
			type: "POST",
			data: data,
			dataType: "json",
			success: function (response) {
				if (response.status === "success") {
					r_sweetalert("transfer_success");
					$('#transferModal').modal('hide');

					const transferBtn = document.querySelector('.transferbtn[data-reg_no="' + std_reg_no + '"]');

					if (transferBtn) {
						const span = document.createElement("span");
						span.textContent = "Transferred";
						span.style.color = "orange";
						span.style.fontWeight = "bold";
						transferBtn.parentNode.replaceChild(span, transferBtn);

						const exitBtn = document.querySelector('.exitBtn[data-reg_no="' + std_reg_no + '"]');
						if (exitBtn) {
							exitBtn.remove();
						}

						const renewBtn = document.querySelector('.renewBtn[data-reg_no="' + std_reg_no + '"]');
						if (renewBtn) {
							renewBtn.remove();
						}

					}

					$("#district_id").val(null).trigger('change');
					$("#taluk_id").val(null).trigger('change');
					$("#hostel_id").val(null).trigger('change');

				}
				else if (response.status === "already") {

					r_sweetalert("transfer_already");
					$('#transferModal').modal('hide');

					$("#district_id").val(null).trigger('change');
					$("#taluk_id").val(null).trigger('change');
					$("#hostel_id").val(null).trigger('change');

				} else {
					$('#transferModal').modal('hide');
					alert("Error: " + response.message);
				}
			},
			error: function () {
				alert("AJAX request failed.");
			}
		});
	} else {
		r_sweetalert("form_alert");
	}
}


function m_transferStudent(button) {
	const m_regNo = button.getAttribute('data-reg_no');
	const m_s1UniqueId = button.getAttribute('data-s1_unique_id');
	const m_studentName = button.getAttribute('data-std_name');

	document.getElementById('m_regNo').value = m_regNo;
	document.getElementById('m_s1UniqueId').value = m_s1UniqueId;
	document.getElementById('m_studentName').value = m_studentName;

	$('#m_transferModal').modal({
		backdrop: 'static',
		keyboard: false
	});
	$('#m_transferModal').modal('show');

}


function m_transferStd() {

	const m_std_id = document.getElementById("m_s1UniqueId").value;
	const m_std_reg_no = document.getElementById("m_regNo").value;
	const m_std_name = document.getElementById("m_studentName").value;
	const m_from_district = document.getElementById("m_fromDistrict").value;
	const m_from_taluk = document.getElementById("m_fromTaluk").value;
	const m_from_hostel = document.getElementById("m_fromHostel").value;
	const m_to_district = document.getElementById("m_district_id").value;
	const m_to_taluk = document.getElementById("m_taluk_id").value;
	const m_to_hostel = document.getElementById("m_hostelId").value;

	if (m_to_district != '' && m_to_taluk != '' && m_to_hostel != '') {
		// Validation: transfer hostel must not be same as current hostel
		if (m_from_hostel === m_to_hostel) {
			r_sweetalert("transfer_hostel_same");
			return;
		}

		const data = {
			action: "m_transfer_student",
			m_std_id: m_std_id,
			m_std_reg_no: m_std_reg_no,
			m_std_name: m_std_name,
			m_from_district: m_from_district,
			m_from_taluk: m_from_taluk,
			m_from_hostel: m_from_hostel,
			m_to_district: m_to_district,
			m_to_taluk: m_to_taluk,
			m_to_hostel: m_to_hostel
		};


		var ajax_url = sessionStorage.getItem("folder_crud_link");


		$.ajax({
			url: ajax_url,
			type: "POST",
			data: data,
			dataType: "json",
			success: function (response) {
				if (response.status === "success") {
					r_sweetalert("transfer_success");
					$('#m_transferModal').modal('hide');

					const m_transferBtn = document.querySelector('.m_transferbtn[data-reg_no="' + m_std_reg_no + '"]');

					if (m_transferBtn) {
						const span = document.createElement("span");
						span.textContent = "Transferred";
						span.style.color = "orange";
						span.style.fontWeight = "bold";
						m_transferBtn.parentNode.replaceChild(span, m_transferBtn);

						const m_exitBtn = document.querySelector('.m_exitBtn[data-reg_no="' + m_std_reg_no + '"]');
						if (m_exitBtn) {
							m_exitBtn.remove();
						}

						const m_renewBtn = document.querySelector('.m_renewBtn[data-reg_no="' + m_std_reg_no + '"]');
						if (m_renewBtn) {
							m_renewBtn.remove();
						}

					}

					$("#m_district_id").val(null).trigger('change');
					$("#m_taluk_id").val(null).trigger('change');
					$("#m_hostel_id").val(null).trigger('change');
				}
				else if (response.status === "already") {

					r_sweetalert("transfer_already");
					$('#m_transferModal').modal('hide');

					$("#m_district_id").val(null).trigger('change');
					$("#m_taluk_id").val(null).trigger('change');
					$("#m_hostel_id").val(null).trigger('change');

				} else {
					$('#m_transferModal').modal('hide');
					alert("Error: " + response.message);
				}
			},
			error: function () {
				alert("AJAX request failed.");
			}
		});
	} else {
		r_sweetalert("form_alert");
	}
}



function exitStudent(button) {
	const stdRegNo = button.getAttribute('data-reg_no');
	var reason = $(button).siblings('.reason-selectbox').val();

	if(reason != ''){

	Swal.fire({
		title: 'Are you sure to Exit this student?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, Exit!'
	}).then((result) => {
		if (result.isConfirmed) {
		const data = {
				action: "exit_student",
				stdRegNo: stdRegNo,
				reason: reason,
			};

			var ajax_url = sessionStorage.getItem("folder_crud_link");

			$.ajax({
				url: ajax_url,
				type: "POST",
				data: data,
				dataType: "json",
				success: function (response) {
					if (response.status === "success") {
						r_sweetalert("exit_success");
						const transferBtn = document.querySelector('.transferbtn[data-reg_no="' + stdRegNo + '"]');
						if (transferBtn) {
							transferBtn.remove();
						}

						const exitBtn = document.querySelector('.conExitBtn[data-reg_no="' + stdRegNo + '"]');
						if (exitBtn && exitBtn.parentNode) {
							const span = document.createElement("span");
							span.textContent = "Exited";
							span.style.color = "red";
							span.style.fontWeight = "bold";
							exitBtn.parentNode.replaceChild(span, exitBtn);
						}

						
						$('.reason-selectbox').hide();

						const renewBtn = document.querySelector('.renewBtn[data-reg_no="' + stdRegNo + '"]');
						if (renewBtn) {
							renewBtn.remove();
						}
					} else {
						alert("Error", response.message, "error");
					}
				},
				error: function () {
					alert("Error", "AJAX request failed.", "error");
				}
			});
		}
	});
}else{
	r_sweetalert("fill_reason");
}
}


function m_exitStudent(button) {

	const m_stdRegNo = button.getAttribute('data-reg_no');
	var reason = $(button).siblings('.m_reason-selectbox').val();

	if(reason != ''){

	Swal.fire({
		title: 'Are you sure to Exit this student?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, Exit!'
	}).then((result) => {
		if (result.isConfirmed) {

			const data = {
				action: "m_exit_student",
				m_stdRegNo: m_stdRegNo,
				reason: reason,
			};

			var ajax_url = sessionStorage.getItem("folder_crud_link");

			$.ajax({
				url: ajax_url,
				type: "POST",
				data: data,
				dataType: "json",
				success: function (response) {
					if (response.status === "success") {
						r_sweetalert("exit_success");

						const m_transferBtn = document.querySelector('.m_transferbtn[data-reg_no="' + m_stdRegNo + '"]');

						if (m_transferBtn) {
							
							m_transferBtn.remove();

							const m_exitBtn = document.querySelector('.m_conExitBtn[data-reg_no="' + m_stdRegNo + '"]');
							if (m_exitBtn) {

								const span = document.createElement("span");
								span.textContent = "Exited";
								span.style.color = "red";
								span.style.fontWeight = "bold";
								m_exitBtn.parentNode.replaceChild(span, m_exitBtn);
							}
							$('.m_reason-selectbox').hide();

							const m_renewBtn = document.querySelector('.m_renewBtn[data-reg_no="' + m_stdRegNo + '"]');
							if (m_renewBtn) {
								m_renewBtn.remove();
							}
						}
					} else {
						alert("Error: " + response.message);
					}
				},
				error: function () {
					alert("AJAX request failed.");
				}
			});

		}
	});
}else{
	r_sweetalert("fill_reason");
}

}

function autoRenewal(button) {
	showLoader_1();
	button.disabled = true;
	const s1_unique_id = button.getAttribute('data-s1_unique_id');
	const ajax_url = sessionStorage.getItem("folder_crud_link");

	const instDist = parseFloat(button.getAttribute("data-inst_dist"));
    const hostDist = parseFloat(button.getAttribute("data-host_dist"));
    const regNo = button.getAttribute("data-reg_no");

    // Get select box elements using the regNo (adjust selector as per your actual structure)
    const instSelect = document.querySelector(`.reason-select[data-type="inst"][data-reg_no="${regNo}"]`);
    const hostSelect = document.querySelector(`.reason-select[data-type="hostel"][data-reg_no="${regNo}"]`);

    let selectedValue;

    if (instDist <= 5 && hostDist <= 5) {
        selectedValue = instSelect?.value || '';
    } else if (instDist <= 5 && hostDist > 5) {
        selectedValue = instSelect?.value || '';
    } else if (hostDist <= 5 && instDist > 5) {
        selectedValue = hostSelect?.value || '';
    } else {
        selectedValue = ''; // or handle case where neither distance is < 5km
    }

	if(instDist <= 5 || hostDist <= 5){
		if(selectedValue == ''){
			button.disabled = false;
			hideLoader_1();
			r_sweetalert('fill_reason');
			return;
		}
	}


	const marksheet_data = {
		action: "check_marksheet",
		s1_unique_id: s1_unique_id
	};

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: marksheet_data,
		success: function (marksheetResponse) {
			let marksheetRes = typeof marksheetResponse === "string" ? JSON.parse(marksheetResponse) : marksheetResponse;

			// Now get the student type AFTER marksheet check
			$.ajax({
				type: "POST",
				url: ajax_url,
				data: {
					action: "get_student_type",
					s1_unique_id: s1_unique_id
				},
				dataType: "json",
				success: function (typeResponse) {
					const student_type = typeResponse.student_type;

					if (student_type === "65f00a259436412348") {
						// School student
						const data_r = {
							action: "autoRenewal",
							s1_unique_id: s1_unique_id,
							reasons: selectedValue

						};

						$.ajax({
							type: "POST",
							url: ajax_url,
							data: data_r,
							success: function (response) {
								if (response.status === true) {
									successCount++;
								}
							},
							error: function () {
								console.log("Error with: " + s1_unique_id);
								button.disabled = false;
								hideLoader_1();
							},
							complete: function () {
								hideLoader_1();
								button.disabled = false;

								const transferBtn = document.querySelector('.transferbtn[data-s1_unique_id="' + s1_unique_id + '"]');
								if (transferBtn) transferBtn.remove();

								const exitBtn = document.querySelector('.exitBtn[data-s1_unique_id="' + s1_unique_id + '"]');
								if (exitBtn) exitBtn.remove();

								const renewBtn = document.querySelector('.renewBtn[data-s1_unique_id="' + s1_unique_id + '"]');
								if (renewBtn) {
									const span = document.createElement("span");
									span.textContent = "Renewed";
									span.style.color = "green";
									span.style.fontWeight = "bold";
									renewBtn.parentNode.replaceChild(span, renewBtn);
								}
								if (instSelect) instSelect.style.display = 'none';
           						if (hostSelect) hostSelect.style.display = 'none';
								r_sweetalert("auto_renew_success");
							}
						});

					} else {
						// Not a school student 
						$.ajax({
							url: ajax_url,
							type: 'POST',
							data: {
								action: 'check_umis_no',
								s1_unique_id: s1_unique_id
							},
							dataType: 'json',
							success: function (umisResponse) {
								const umis = umisResponse.umis_no ? umisResponse.umis_no.trim() : '';
								const renewal = umisResponse.renewal_umis_no ? umisResponse.renewal_umis_no.trim() : '';

								if (
									(marksheetRes.msg === "no_exist") &&
									(umis === '' && renewal === '')
								) {
									button.disabled = false;
									hideLoader_1();
									r_sweetalert("no_marksheet_umis");
									return;
								}

								if (
									(marksheetRes.msg === "not_valid") &&
									(umis === '' && renewal === '')
								) {
									button.disabled = false;
									hideLoader_1();
									r_sweetalert("fail_five_subject_noumis");
									return;
								}

								if (marksheetRes.msg === "no_exist") {
									button.disabled = false;
									r_sweetalert("no_marksheet_entry");
									hideLoader_1();
									return;
								}

								if (marksheetRes.msg === "not_valid") {
									button.disabled = false;
									r_sweetalert("fail_five_subject");
									hideLoader_1();
									return;
								}

								if (marksheetRes.msg === "valid") {
									if (umis === '' && renewal === '') {
										$('#umisModal').modal({
											backdrop: 'static',
											keyboard: false
										});
										$('#umisModal').modal('show');
										$('#umisModal').data('s1_unique_id', s1_unique_id);
										$('#umisModal').data('selected_reasons', selectedValue);
									} else {
										const data_r = {
											action: "autoRenewal",
											s1_unique_id: s1_unique_id,
											reasons: selectedValue
										};

										$.ajax({
											type: "POST",
											url: ajax_url,
											data: data_r,
											success: function (response) {
												if (response.status === true) {
													successCount++;
												}
											},
											error: function () {
												button.disabled = false;
												console.log("Error with: " + s1_unique_id);
												hideLoader_1();
											},
											complete: function () {
												hideLoader_1();
												const transferBtn = document.querySelector('.transferbtn[data-s1_unique_id="' + s1_unique_id + '"]');
												if (transferBtn) transferBtn.remove();

												const exitBtn = document.querySelector('.exitBtn[data-s1_unique_id="' + s1_unique_id + '"]');
												if (exitBtn) exitBtn.remove();

												const renewBtn = document.querySelector('.renewBtn[data-s1_unique_id="' + s1_unique_id + '"]');
												if (renewBtn) {
													const span = document.createElement("span");
													span.textContent = "Renewed";
													span.style.color = "green";
													span.style.fontWeight = "bold";
													renewBtn.parentNode.replaceChild(span, renewBtn);
												}
												if (instSelect) instSelect.style.display = 'none';
           										if (hostSelect) hostSelect.style.display = 'none';
												r_sweetalert("auto_renew_success");
											}
										});
									}
								} else {
									button.disabled = false;
									hideLoader_1();
									alert("Error: " + marksheetRes.msg);
								}
							},
							error: function () {
								button.disabled = false;
								hideLoader_1();
								alert("Error checking UMIS number.");
							}
						});
					}
				},
				error: function () {
					button.disabled = false;
					hideLoader_1();
					alert("Error fetching student type.");
				}
			});

		},
		error: function () {
			button.disabled = false;
			hideLoader_1();
			alert("Error checking marksheet.");
		}
	});
}

function submitUMIS() {

	const umis_number = document.getElementById("umis_number").value.trim();
	var s1_unique_id = $('#umisModal').data('s1_unique_id');
	const selectedReasons = $('#umisModal').data('selected_reasons');


	if (umis_number != '' && (umis_number.length == '10' || umis_number.length == '12')) {

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var data = {
			"umis_number": umis_number,
			"action": "umis_already"
		}

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function (data) {
				var obj = JSON.parse(data);
				var msg = obj.msg;

				if (msg == 'already') {
					r_sweetalert("umis_already");
				} else if (msg == 'not_found') {
					insertUMIS(s1_unique_id, umis_number, selectedReasons);
				}
			}
		});
	} else {
		r_sweetalert("incorrect_umis");
	}
}


function insertUMIS(s1_unique_id, umis_number, selectedReasons) {
	showLoader();
	document.getElementById('umisInsertBtn').disabled = true;

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	if (umis_number != '' && (umis_number.length == '10' || umis_number.length == '12')) {

		var data = {
			"umis_number": umis_number,
			"action": "insert_umis"
		};

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			dataType: 'json', // Parse response as JSON
			success: function (response) {
				hideLoader();

				if (response.status) {

					var ajax_url = sessionStorage.getItem("folder_crud_link");

					var data_umis = {
						"s1_unique_id": s1_unique_id,
						"umis_number": umis_number,
						"name": response.data.name,
						"emsid": response.data.emsid,
						"dateOfBirth": response.data.dateOfBirth,
						"nationalityId": response.data.nationalityId,
						"religionId": response.data.religionId,
						"communityId": response.data.communityId,
						"casteId": response.data.casteId,
						"isFirstGraduate": response.data.isFirstGraduate,
						"isSpecialCategory": response.data.isSpecialCategory,
						"isDifferentlyAbled": response.data.isDifferentlyAbled,
						"udid": response.data.udid,
						"disabilityId": response.data.disabilityId,
						"extentOfDisability": response.data.extentOfDisability,
						"bloodGroupId": response.data.bloodGroupId,
						"genderId": response.data.genderId,
						"salutationId": response.data.salutationId,
						"instituteId": response.data.instituteId,
						"umisId": response.data.umisId,
						"nameAsOnCertificate": response.data.nameAsOnCertificate,
						"isFirstGraduateVerifiedbyUniversity": response.data.isFirstGraduateVerifiedbyUniversity,
						"isFirstGraduateVerifiedbyHod": response.data.isFirstGraduateVerifiedbyHod,
						"mobileNumber": response.data.mobileNumber,
						"emailId": response.data.emailId,
						"permAddress": response.data.permAddress,
						"countryId": response.data.countryId,
						"stateId": response.data.stateId,
						"districtId": response.data.districtId,
						"zoneId": response.data.zoneId,
						"blockId": response.data.blockId,
						"caCountryId": response.data.caCountryId,
						"caStateId": response.data.caStateId,
						"caDistrictId": response.data.caDistrictId,
						"caAddress": response.data.caAddress,
						"caZoneId": response.data.caZoneId,
						"caCorporationId": response.data.caCorporationId,
						"caBlockId": response.data.caBlockId,
						"caVillagePanchayatId": response.data.caVillagePanchayatId,
						"caWardId": response.data.caWardId,
						"caTalukId": response.data.caTalukId,
						"caVillageId": response.data.caVillageId,
						"talukId": response.data.talukId,
						"villageId": response.data.villageId,
						"wardId": response.data.wardId,
						"corporationId": response.data.corporationId,
						"villagePanchayatId": response.data.villagePanchayatId,
						"courseId": response.data.courseId,
						"courseSpecializationId": response.data.courseSpecializationId,
						"dateOfAdmission": response.data.dateOfAdmission,
						"academicYearId": response.data.academicYearId,
						"streamInfoId": response.data.streamInfoId,
						"courseType": response.data.courseType,
						"mediumOfInstructionType": response.data.mediumOfInstructionType,
						"academicStatusType": response.data.academicStatusType,
						"yearOfStudy": response.data.yearOfStudy,
						"isLateralEntry": response.data.isLateralEntry,
						"isHosteler": response.data.isHosteler,
						"hostelAdmissionDate": response.data.hostelAdmissionDate,
						"leavingFromHostelDate": response.data.leavingFromHostelDate,
						"studentId": response.data.studentId,
						"parentMobileNo": response.data.parentMobileNo,
						"fatherOccupationId": response.data.fatherOccupationId,
						"motherOccupationId": response.data.motherOccupationId,
						"guardianOccupationId": response.data.guardianOccupationId,
						"aisheId": response.data.aisheId,
						"instituteName": response.data.instituteName,
						"instituteTypeId": response.data.instituteTypeId,
						"instituteOwnershipId": response.data.instituteOwnershipId,
						"instituteCategoryId": response.data.instituteCategoryId,
						"instituteStatusType": response.data.instituteStatusType,
						"universityName": response.data.universityName,
						"universityTypeId": response.data.universityTypeId,
						"hodName": response.data.hodName,
						"departmentName": response.data.departmentName,

						"action": "umis_insert"
					}

					const data_dis = {
						action: "updateDistance",
						s1_unique_id: s1_unique_id,
						instituteId: response.data.instituteId
					};

					$.ajax({
						type: "POST",
						url: ajax_url,
						data: data_dis,
						dataType: "json",
						success: function (response) {
							if (response.status === true) {
								console.log("Distance updated:", response.inst_distance, "km (Inst),", response.hostel_distance, "km (Hostel)");
							} else {
								console.error("Distance update failed:", response.message);
							}
						},
						error: function (xhr) {
							console.error("AJAX Error (updateDistance):", xhr.responseText);
						}
					});

					$.ajax({
						type: "POST",
						url: ajax_url,
						data: data_umis,
						success: function (data) {

							$('#umisModal').modal('hide');
							hideLoader();

							$('#umis_number').val('');
							$('#umisModal').removeData('s1_unique_id');
							document.getElementById('umisInsertBtn').disabled = false;

							const data_r = {
								action: "autoRenewal",
								s1_unique_id: s1_unique_id, 
								reasons: selectedReasons
							};

							$.ajax({
								type: "POST",
								url: ajax_url,
								data: data_r,
								success: function (response) {
									if (response.status === true) {
										successCount++;
									}
								},
								error: function () {
									console.log("Error with: " + s1_unique_id);
									hideLoader_1();
								},

								complete: function () {

									hideLoader_1();
									const transferBtn = document.querySelector('.transferbtn[data-s1_unique_id="' + s1_unique_id + '"]');

									if (transferBtn) {
										transferBtn.remove();
									}
										const exitBtn = document.querySelector('.exitBtn[data-s1_unique_id="' + s1_unique_id + '"]');
										if (exitBtn) {
											exitBtn.remove();
										}

										const renewBtn = document.querySelector('.renewBtn[data-s1_unique_id="' + s1_unique_id + '"]');
										const regNo = renewBtn.getAttribute('data-reg_no');
										if (renewBtn) {

											const span = document.createElement("span");
											span.textContent = "Renewed";
											span.style.color = "green";
											span.style.fontWeight = "bold";
											renewBtn.parentNode.replaceChild(span, renewBtn);
										}

										const instSelect = document.querySelector(`.reason-select[data-type="inst"][data-reg_no="${regNo}"]`);
									const hostSelect = document.querySelector(`.reason-select[data-type="hostel"][data-reg_no="${regNo}"]`);

									if (instSelect) instSelect.style.display = 'none';
           							if (hostSelect) hostSelect.style.display = 'none';
									
									r_sweetalert("auto_renew_success");
									//auto_datatable("auto_datatable", form_name, "auto_datatable");

								}
							});


						}
					});

				} else {
					// Handle error
					hideLoader();
					console.log(response.data.message);
					r_sweetalert('incorrect_umis');
					document.getElementById('umisInsertBtn').disabled = false;

				}

			},

			error: function (xhr, status, error) {
				// Handle AJAX error
				console.error(xhr.responseText);
			}
		});

	} else {
		hideLoader();

		r_sweetalert('incorrect_umis');
	}
}

function m_submitUMIS() {

	const m_umis_number = document.getElementById("m_umis_number").value.trim();
	var m_s1_unique_id = $('#m_umisModal').data('m_s1_unique_id');
	var reasons = $('#m_umisModal').data('reasons');


	if (m_umis_number != '' && (m_umis_number.length == '10' || m_umis_number.length == '12')) {

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var data = {
			"m_umis_number": m_umis_number,
			"action": "m_umis_already"
		}

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function (data) {
				var obj = JSON.parse(data);
				var msg = obj.msg;

				if (msg == 'already') {
					r_sweetalert("umis_already");
				} else if (msg == 'not_found') {
					m_insertUMIS(m_s1_unique_id, m_umis_number, reasons);
				}
			}
		});
	}
	else {
		r_sweetalert("incorrect_umis");
	}
}


function m_insertUMIS(m_s1_unique_id, m_umis_number, reasons) {
	m_showLoader();
	document.getElementById('m_umisInsertBtn').disabled = true;

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	if (m_umis_number != '' && (m_umis_number.length == '10' || m_umis_number.length == '12')) {

		var data = {
			"m_umis_number": m_umis_number,
			"action": "m_insert_umis"
		};

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			dataType: 'json', // Parse response as JSON
			success: function (response) {
				hideLoader();

				if (response.status) {

					var ajax_url = sessionStorage.getItem("folder_crud_link");

					var data_umis = {
						"s1_unique_id": m_s1_unique_id,
						"umis_number": m_umis_number,
						"name": response.data.name,
						"emsid": response.data.emsid,
						"dateOfBirth": response.data.dateOfBirth,
						"nationalityId": response.data.nationalityId,
						"religionId": response.data.religionId,
						"communityId": response.data.communityId,
						"casteId": response.data.casteId,
						"isFirstGraduate": response.data.isFirstGraduate,
						"isSpecialCategory": response.data.isSpecialCategory,
						"isDifferentlyAbled": response.data.isDifferentlyAbled,
						"udid": response.data.udid,
						"disabilityId": response.data.disabilityId,
						"extentOfDisability": response.data.extentOfDisability,
						"bloodGroupId": response.data.bloodGroupId,
						"genderId": response.data.genderId,
						"salutationId": response.data.salutationId,
						"instituteId": response.data.instituteId,
						"umisId": response.data.umisId,
						"nameAsOnCertificate": response.data.nameAsOnCertificate,
						"isFirstGraduateVerifiedbyUniversity": response.data.isFirstGraduateVerifiedbyUniversity,
						"isFirstGraduateVerifiedbyHod": response.data.isFirstGraduateVerifiedbyHod,
						"mobileNumber": response.data.mobileNumber,
						"emailId": response.data.emailId,
						"permAddress": response.data.permAddress,
						"countryId": response.data.countryId,
						"stateId": response.data.stateId,
						"districtId": response.data.districtId,
						"zoneId": response.data.zoneId,
						"blockId": response.data.blockId,
						"caCountryId": response.data.caCountryId,
						"caStateId": response.data.caStateId,
						"caDistrictId": response.data.caDistrictId,
						"caAddress": response.data.caAddress,
						"caZoneId": response.data.caZoneId,
						"caCorporationId": response.data.caCorporationId,
						"caBlockId": response.data.caBlockId,
						"caVillagePanchayatId": response.data.caVillagePanchayatId,
						"caWardId": response.data.caWardId,
						"caTalukId": response.data.caTalukId,
						"caVillageId": response.data.caVillageId,
						"talukId": response.data.talukId,
						"villageId": response.data.villageId,
						"wardId": response.data.wardId,
						"corporationId": response.data.corporationId,
						"villagePanchayatId": response.data.villagePanchayatId,
						"courseId": response.data.courseId,
						"courseSpecializationId": response.data.courseSpecializationId,
						"dateOfAdmission": response.data.dateOfAdmission,
						"academicYearId": response.data.academicYearId,
						"streamInfoId": response.data.streamInfoId,
						"courseType": response.data.courseType,
						"mediumOfInstructionType": response.data.mediumOfInstructionType,
						"academicStatusType": response.data.academicStatusType,
						"yearOfStudy": response.data.yearOfStudy,
						"isLateralEntry": response.data.isLateralEntry,
						"isHosteler": response.data.isHosteler,
						"hostelAdmissionDate": response.data.hostelAdmissionDate,
						"leavingFromHostelDate": response.data.leavingFromHostelDate,
						"studentId": response.data.studentId,
						"parentMobileNo": response.data.parentMobileNo,
						"fatherOccupationId": response.data.fatherOccupationId,
						"motherOccupationId": response.data.motherOccupationId,
						"guardianOccupationId": response.data.guardianOccupationId,
						"aisheId": response.data.aisheId,
						"instituteName": response.data.instituteName,
						"instituteTypeId": response.data.instituteTypeId,
						"instituteOwnershipId": response.data.instituteOwnershipId,
						"instituteCategoryId": response.data.instituteCategoryId,
						"instituteStatusType": response.data.instituteStatusType,
						"universityName": response.data.universityName,
						"universityTypeId": response.data.universityTypeId,
						"hodName": response.data.hodName,
						"departmentName": response.data.departmentName,

						"action": "m_umis_insert"
					}

					const m_data_dis = {
						action: "m_updateDistance",
						m_s1_unique_id: m_s1_unique_id,
						instituteId: response.data.instituteId
					};

					$.ajax({
						type: "POST",
						url: ajax_url,
						data: m_data_dis,
						dataType: "json",
						success: function (response) {
							if (response.status === true) {
								console.log("Distance updated:", response.inst_distance, "km (Inst),", response.hostel_distance, "km (Hostel)");
							} else {
								console.error("Distance update failed:", response.message);
							}
						},
						error: function (xhr) {
							console.error("AJAX Error (updateDistance):", xhr.responseText);
						}
					});

					$.ajax({
						type: "POST",
						url: ajax_url,
						data: data_umis,
						success: function (data) {

							$('#m_umisModal').modal('hide');
							hideLoader();

							$('#m_umis_number').val('');
							$('#m_umisModal').removeData('m_s1_unique_id');
							document.getElementById('m_umisInsertBtn').disabled = false;

							const data_r = {
								action: "manualRenewal",
								m_s1_unique_id: m_s1_unique_id,
								reasons: reasons
							};

							$.ajax({
								type: "POST",
								url: ajax_url,
								data: data_r,
								success: function (response) {
									if (response.status === true) {
										successCount++;
									}
								},
								error: function () {
									console.log("Error with: " + m_s1_unique_id);
									hideLoader_1();
								},

								complete: function () {
									hideLoader_1();
									const m_transferBtn = document.querySelector('.m_transferbtn[data-s1_unique_id="' + m_s1_unique_id + '"]');

									if (m_transferBtn) {
										m_transferBtn.remove();
									}

										const m_exitBtn = document.querySelector('.m_exitBtn[data-s1_unique_id="' + m_s1_unique_id + '"]');
										if (m_exitBtn) {
											m_exitBtn.remove();
										}

										const m_renewBtn = document.querySelector('.m_renewBtn[data-s1_unique_id="' + m_s1_unique_id + '"]');
										const regNo = m_renewBtn.getAttribute('data-reg_no');
										if (m_renewBtn) {
											

											const span = document.createElement("span");
											span.textContent = "Requested";
											span.style.color = "green";
											span.style.fontWeight = "bold";
											m_renewBtn.parentNode.replaceChild(span, m_renewBtn);
										}

										const instSelect = document.querySelector(`.m_reason-select[data-type="m_inst"][data-reg_no="${regNo}"]`);
									const hostSelect = document.querySelector(`.m_reason-select[data-type="m_hostel"][data-reg_no="${regNo}"]`);

									if (instSelect) instSelect.style.display = 'none';
           							if (hostSelect) hostSelect.style.display = 'none';
									
									r_sweetalert("manual_renew_success")
									//auto_datatable("auto_datatable", form_name, "auto_datatable");

								}
							});

						}
					});

				} else {
					// Handle error
					m_hideLoader();
					console.log(response.data.message);
					r_sweetalert('incorrect_umis');
					document.getElementById('m_umisInsertBtn').disabled = false;

				}

			},

			error: function (xhr, status, error) {
				// Handle AJAX error
				console.error(xhr.responseText);
			}
		});

	} else {
		hideLoader();

		r_sweetalert('incorrect_umis');
	}
}

function manualRenewal(button) {
	m_showLoader_1();
	const m_s1_unique_id = button.getAttribute('data-s1_unique_id');
	const ajax_url = sessionStorage.getItem("folder_crud_link");

	 const instDist = parseFloat(button.getAttribute("data-m_inst_dist"));
    const hostDist = parseFloat(button.getAttribute("data-m_host_dist"));
    const regNo = button.getAttribute("data-reg_no");

    // Get select box elements using the regNo (adjust selector as per your actual structure)
    const instSelect = document.querySelector(`.m_reason-select[data-type="m_inst"][data-reg_no="${regNo}"]`);
    const hostSelect = document.querySelector(`.m_reason-select[data-type="m_hostel"][data-reg_no="${regNo}"]`);

    let selectedValue;

    if (instDist <= 5 && hostDist <= 5) {
        selectedValue = instSelect?.value || '';
    } else if (instDist <= 5 && hostDist > 5) {
        selectedValue = instSelect?.value || '';
    } else if (hostDist <= 5 && instDist > 5) {
        selectedValue = hostSelect?.value || '';
    } else {
        selectedValue = ''; // or handle case where neither distance is < 5km
    }

	if(instDist <= 5 || hostDist <= 5){
		if(selectedValue == ''){
			hideLoader_1();
			r_sweetalert('fill_reason');
			return;
		}
	}

	let isMarksheetValid = false;
	let isUMISPresent = false;

	// First check marksheet
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: {
			action: "m_check_marksheet",
			m_s1_unique_id: m_s1_unique_id
		},
		success: function (response) {
			let res = typeof response === "string" ? JSON.parse(response) : response;

			if (res.msg === "no_exist" || res.msg === "not_valid") {
				isMarksheetValid = false;
			} else if (res.msg === "valid") {
				isMarksheetValid = true;
			} else {
				alert("Error: " + res.msg);
				m_hideLoader_1();
				return;
			}

			// Now get the student type
			$.ajax({
				type: "POST",
				url: ajax_url,
				data: {
					action: "m_get_student_type",
					m_s1_unique_id: m_s1_unique_id
				},
				dataType: "json",
				success: function (typeResponse) {
					const m_student_type = typeResponse.m_student_type;

					// If school student
					if (m_student_type == "65f00a259436412348") {
						const data_r = {
							action: "manualRenewal",
							m_s1_unique_id: m_s1_unique_id,
							reasons: selectedValue
						};

						$.ajax({
							type: "POST",
							url: ajax_url,
							data: data_r,
							success: function (response) {
								if (response.status === true) {
									successCount++;
								}
							},
							error: function () {
								console.log("Error with: " + m_s1_unique_id);
							},
							complete: function () {
								m_hideLoader_1();

								const m_transferBtn = document.querySelector('.m_transferbtn[data-s1_unique_id="' + m_s1_unique_id + '"]');
								if (m_transferBtn) m_transferBtn.remove();

								const m_exitBtn = document.querySelector('.m_exitBtn[data-s1_unique_id="' + m_s1_unique_id + '"]');
								if (m_exitBtn) m_exitBtn.remove();

								const m_renewBtn = document.querySelector('.m_renewBtn[data-s1_unique_id="' + m_s1_unique_id + '"]');
								if (m_renewBtn) {
									const span = document.createElement("span");
									span.textContent = "Requested";
									span.style.color = "green";
									span.style.fontWeight = "bold";
									m_renewBtn.parentNode.replaceChild(span, m_renewBtn);
								}
								if (instSelect) instSelect.style.display = 'none';
            					if (hostSelect) hostSelect.style.display = 'none';

								r_sweetalert("manual_renew_success");
							}
						});

					} else {
						// Not a school student
						$.ajax({
							url: ajax_url,
							type: 'POST',
							data: {
								action: 'm_check_umis_no',
								m_s1_unique_id: m_s1_unique_id
							},
							dataType: 'json',
							success: function (umisResponse) {
								const umis = umisResponse.umis_no ? umisResponse.umis_no.trim() : '';
								const renewal = umisResponse.renewal_umis_no ? umisResponse.renewal_umis_no.trim() : '';

								isUMISPresent = !(umis === '' && renewal === '');

								// Alert if both marksheet & UMIS invalid
								if (!isMarksheetValid && !isUMISPresent) {
									if (res.msg === "not_valid") {
										r_sweetalert("fail_five_subject_noumis");
										return;
									} else {
										r_sweetalert("no_marksheet_umis");
										m_hideLoader_1();
										return;
									}
								}

								// Handle invalid marksheet messages
								if (!isMarksheetValid) {
									if (res.msg === "no_exist") {
										r_sweetalert("no_marksheet_entry");
									} else if (res.msg === "not_valid") {
										r_sweetalert("fail_five_subject");
									} else {
										alert("Error: " + res.msg);
									}
									m_hideLoader_1();
									return;
								}

								// If UMIS missing, show modal
								if (isMarksheetValid && !isUMISPresent) {
									$('#m_umisModal').modal({
										backdrop: 'static',
										keyboard: false
									});
									$('#m_umisModal').modal('show');
									$('#m_umisModal').data('m_s1_unique_id', m_s1_unique_id);
									$('#m_umisModal').data('reasons', selectedValue);
									m_hideLoader_1();
									return;
								}

								// Proceed with manual renewal
								if (isMarksheetValid && isUMISPresent) {
									const data_r = {
										action: "manualRenewal",
										m_s1_unique_id: m_s1_unique_id,
										reasons: selectedValue
									
									};

									$.ajax({
										type: "POST",
										url: ajax_url,
										data: data_r,
										success: function (response) {
											if (response.status === true) {
												successCount++;
											}
										},
										error: function () {
											console.log("Error with: " + m_s1_unique_id);
										},
										complete: function () {
											m_hideLoader_1();

											const m_transferBtn = document.querySelector('.m_transferbtn[data-s1_unique_id="' + m_s1_unique_id + '"]');
											if (m_transferBtn) m_transferBtn.remove();

											const m_exitBtn = document.querySelector('.m_exitBtn[data-s1_unique_id="' + m_s1_unique_id + '"]');
											if (m_exitBtn) m_exitBtn.remove();

											const m_renewBtn = document.querySelector('.m_renewBtn[data-s1_unique_id="' + m_s1_unique_id + '"]');
											if (m_renewBtn) {
												const span = document.createElement("span");
												span.textContent = "Requested";
												span.style.color = "green";
												span.style.fontWeight = "bold";
												m_renewBtn.parentNode.replaceChild(span, m_renewBtn);
											}
											if (instSelect) instSelect.style.display = 'none';
            								if (hostSelect) hostSelect.style.display = 'none';

											r_sweetalert("manual_renew_success");
										}
									});
								}
							},
							error: function () {
								alert("Error checking UMIS number.");
								m_hideLoader_1();
							}
						});
					}
				},
				error: function () {
					alert("Error fetching student type.");
					m_hideLoader_1();
				}
			});
		},
		error: function () {
			alert("Error checking marksheet.");
			m_hideLoader_1();
		}
	});
}

// Disable Bootstrap modal focus trap (to allow Select2 dropdown)
$.fn.modal.Constructor.prototype._enforceFocus = function () { };

// Initialize Select2 after modal opens
$('#transferModal').on('shown.bs.modal', function () {
	$('#district_id, #taluk_id, #hostel_id').select2({
		width: '100%'
	});
});

// Disable Bootstrap modal focus trap (to allow Select2 dropdown)
$.fn.modal.Constructor.prototype._enforceFocus = function () { };

// Initialize Select2 after modal opens
$('#m_transferModal').on('shown.bs.modal', function () {
	$('#m_district_id, #m_taluk_id, #m_hostel_id').select2({
		width: '100%'
	});
});



function r_sweetalert(msg = '', url = '', callback = '', title = '') {

	switch (msg) {
		case "create":

			Swal.fire({
				icon: 'success',
				title: 'Successfully Saved',
				imageAlt: 'Custom image',
				showConfirmButton: true,
				timer: 3000,
				timerProgressBar: true,
				willClose: () => {

					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "delete":
			return Swal.fire({
				title: 'Are you sure to Delete?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!',
				preConfirm: () => {
					return true;
				}
			});
			break;

		case "status_saved":
			Swal.fire({
				icon: 'success',
				title: 'Status Updated Successfully',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "exit_success":
			Swal.fire({
				icon: 'success',
				title: 'Exited Successfully',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "transfer_success":
			Swal.fire({
				icon: 'success',
				title: 'Student transferred successfully',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "auto_renew_success":
			Swal.fire({
				icon: 'success',
				title: 'Student has been successfully auto-renewed',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;


		case "manual_renew_success":
			Swal.fire({
				icon: 'success',
				title: 'Manual renewal of the student was completed successfully',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "transfer_already":
			Swal.fire({
				icon: 'warning',
				title: 'Student already processed for transfer',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

			case "fill_reason":
			Swal.fire({
				icon: 'info',
				title: 'Please Fill Reason',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "fail_five_subject_noumis":
			Swal.fire({
				icon: 'warning',
				title: 'Renewal cannot be processed: the student has failed more than four subjects, and a UMIS is required to proceed.',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "umis_already":
			Swal.fire({
				icon: 'warning',
				title: 'UMIS Number has already been assigned to another student',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "incorrect_umis":
			Swal.fire({
				icon: 'warning',
				title: 'UMIS Number cannot be empty or incorrect. Please enter a valid UMIS number',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "no_marksheet_umis":
			Swal.fire({
				icon: 'warning',
				title: 'UMIS and Student marksheet is mandatory to renew the student application',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;
		case "no_marksheet_entry":
			Swal.fire({
				icon: 'warning',
				title: "2024-25 ஆம் கல்வி ஆண்டில் முதல் பருவத் தேர்விற்கான மதிப்பெண் பட்டியலை கட்டாயம் பதிவேற்றி புதுப்பித்தல் செயல்முறையை முடித்திடுமாறு அறிவுறுத்தப்படுகிறது. |  Please ensure that the Academic performance for the odd semesters is compulsorily updated in the Student Marksheet Menu to complete renewal processing",
				showConfirmButton: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "fail_five_subject":
			Swal.fire({
				icon: 'warning',
				title: "Unable to proceed with renewal: the student has failed more than four subjects",
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "transfer_hostel_same":
			Swal.fire({
				icon: 'warning',
				title: 'Transfer hostel must not be the same as the current hostel',
				showConfirmButton: true,
				timer: 4000,
				timerProgressBar: true,
				willClose: () => {
					if (url) {
						window.location = url;
					}
				}
			});
			break;

		case "form_alert":
			Swal.fire({
				icon: 'info',
				title: 'Fill Out All Mandatory Fields',
				//imageUrl:'img/emoji/form_fill.webp',
				showConfirmButton: true,
				timer: 2000,
				timerProgressBar: true
			})
			break;

		default:
			break;
	}
}

function empty_fields() {
	$("#district_id").val(null).trigger('change');
	$("#taluk_id").val(null).trigger('change');
	$("#hostel_id").val(null).trigger('change');
}

function m_empty_fields() {
	$("#m_district_id").val(null).trigger('change');
	$("#m_taluk_id").val(null).trigger('change');
	$("#m_hostel_id").val(null).trigger('change');
}

function handleCheckboxChange(checkbox,regNo) {
    // const regNo = checkbox.getAttribute('data-reg_no');
    const type = checkbox.getAttribute('data-type'); // 'inst' or 'hostel'

    // Target the specific select box
    const selectBox = document.querySelector(`select.reason-select[data-reg_no="${regNo}"][data-type="${type}"]`);

    if (selectBox) {
        if (checkbox.checked) {
            selectBox.style.display = "block";
        } else {
            selectBox.style.display = "none";
            selectBox.value = "";
        }
    }

    // Optionally enable action buttons if all checkboxes are checked
    const checkboxes = document.querySelectorAll(`input[type="checkbox"][data-reg_no="${regNo}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    const buttons = document.querySelectorAll(`.action-buttons[data-reg_no="${regNo}"] button`);
    buttons.forEach(button => {
        button.disabled = !allChecked;
    });
}



function m_handleCheckboxChange(checkbox,regNo) {

	 const type = checkbox.getAttribute('data-type'); // 'inst' or 'hostel'

    // Target the specific select box
    const selectBox = document.querySelector(`select.m_reason-select[data-reg_no="${regNo}"][data-type="${type}"]`);

    if (selectBox) {
        if (checkbox.checked) {
            selectBox.style.display = "block";
        } else {
            selectBox.style.display = "none";
            selectBox.value = "";
        }
    }



	// Get all checkboxes for this student
	const checkboxes = document.querySelectorAll(`input[type="checkbox"][data-reg_no="${regNo}"]`);

	// Check if all checkboxes are checked
	const allChecked = Array.from(checkboxes).every(cb => cb.checked);

	// Enable or disable buttons based on the allChecked value
	const buttons = document.querySelectorAll(`.action-buttons[data-reg_no="${regNo}"] button`);
	buttons.forEach(button => {
		button.disabled = !allChecked; // enable only if all are checked
	});
}
