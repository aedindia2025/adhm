$(document).ready(function () {

	auto_datatable("auto_datatable", form_name, "auto_datatable");
	manual_datatable("manual_datatable", form_name, "manual_datatable");

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




function manual_datatable(table_id = '', form_name = '', action = '') {
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






function print_for_dispatch_cu(unique_id = "") {
	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}

	var is_form = form_validity_check("was-validated");

	if (is_form) {

		var data = $(".was-validated").serialize();
		data += "&unique_id=" + unique_id + "&action=createupdate";

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = sessionStorage.getItem("list_link");

		// console.log(data);
		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			beforeSend: function () {
				$(".createupdate_btn").attr("disabled", "disabled");
				$(".createupdate_btn").text("Loading...");
			},
			success: function (data) {

				var obj = JSON.parse(data);
				var msg = obj.msg;
				var status = obj.status;
				var error = obj.error;

				if (!status) {
					url = '';
					$(".createupdate_btn").text("Error");
					console.log(error);
				} else {
					if (msg == "already") {
						// Button Change Attribute
						url = '';

						$(".createupdate_btn").removeAttr("disabled", "disabled");
						if (unique_id) {
							$(".createupdate_btn").text("Update");
						} else {
							$(".createupdate_btn").text("Save");
						}
					}
				}

				sweetalert(msg, url);
			},
			error: function (data) {
				alert("Network Error");
			}
		});


	} else {
		sweetalert("form_alert");
	}
}

function batch_print(unique_id = "") {


	var external_window = window.open('folders/print_for_dispatch/batch_print1.php?unique_id=' + unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	external_window.print();
	// external_window.print();
}

function print_for_dispatch(unique_id = "") {


	var external_window = window.open('folders/print_for_dispatch/print.php?unique_id=' + unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	external_window.print();
	// external_window.print();
}

function openModal(unique_id = "") {

	$('#myModal').modal('show');
	// var external_window = window.open('folders/print_for_dispatch/batch_print.php?unique_id='+unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	// external_window.print();
	// external_window.print();
}
function close() {

	$('#myModal').modal('hide');

}


function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../adhmHostel/uploads/maintanance' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
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
	var pdfUrl = "../adhmHostel/uploads/maintanance/" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "../adhmHostel/uploads/maintanance/" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

// function print_pdf(file_name) {

// 	onmouseover = window.open('../adhmHostel/uploads/maintanance/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// }

// function print_view(file_name) {
// 	alert();
// 	onmouseover = window.open('../adhmHostel/uploads/maintanance/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// }


function updateStatus(unique_id, print_status) {

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		'unique_id': unique_id,
		'print_status': print_status,
		'action': 'update_status'
	};

	// Make an AJAX call to update the status
	$.ajax({
		type: 'POST',
		url: ajax_url,
		data: data,
		success: function (response) {
			// Handle success response
			console.log(response);
			sweetalert("status_saved");
			window.location.reload();
			// if(print_status == '2'){
			//	$('#print_id').prop('disabled', true);
			//	      }

		},
		error: function (xhr, status, error) {
			// Handle error response
			console.error(error);
		}
	});
}


function check_umis(button) {

	const s1_unique_id = button.getAttribute('data-s1_unique_id');
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		url: ajax_url,
		type: 'POST',
		data: {
			action: 'check_umis_no',
			s1_unique_id: s1_unique_id
		},
		dataType: 'json',
		success: function (response) {
			const umis = response.umis_no ? response.umis_no.trim() : '';
			const renewal = response.renewal_umis_no ? response.renewal_umis_no.trim() : '';

			if (response.status === 1 && umis === '' && renewal === '') {
				$('#umisModal').modal({
					backdrop: 'static',
					keyboard: false
				});
				$('#umisModal').modal('show');
				$('#umisModal').data('s1_unique_id', s1_unique_id);
			} else {
				console.log("UMIS already exists.\nUMIS No: " + (umis || 'N/A') + "\nRenewal UMIS No: " + (renewal || 'N/A'));
			}
		},
		error: function () {
			alert("Error checking UMIS number.");
		}
	});
}


function submitUMIS() {

	const umis_number = document.getElementById("umis_number").value.trim();
	var s1_unique_id = $('#umisModal').data('s1_unique_id');

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
					alert("already exist");
					log_sweetalert("umis_already");
				} else {
					insertUMIS(s1_unique_id, umis_number);
				}
			}
		});
	}
}

function insertUMIS(s1_unique_id, umis_number) {
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

						}
					});

				} else {
					// Handle error
					hideLoader();
					console.log(response.data.message);
					log_sweetalert('valid_umis');
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

		log_sweetalert('valid_umis');
	}
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

	var data = "taluk_id=" + taluk_id + "&action=get_hostel_id";

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

	// Validation: transfer hostel must not be same as current hostel
	if (from_hostel === to_hostel) {
		alert("Transfer hostel must not be the same as the current hostel.");
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
				alert("Student transferred successfully.");
				$('#transferModal').modal('hide');

				const transferBtn = document.querySelector('.transferbtn[data-reg_no="' + std_reg_no + '"]');

				if (transferBtn) {
					const span = document.createElement("span");
					span.textContent = "Transferred";
					span.style.color = "green";
					span.style.fontWeight = "bold";
					transferBtn.parentNode.replaceChild(span, transferBtn);

					const parentRow = span.closest('tr');
					if (parentRow) {

						const checkbox = parentRow.querySelector('input.myCheck[type="checkbox"]');
						if (checkbox) {
							checkbox.disabled = true;
							checkbox.style.cursor = "not-allowed";
							checkbox.checked = false;

						}
					}

					const exitBtn = document.querySelector('.exitBtn[data-reg_no="' + std_reg_no + '"]');
					if (exitBtn) {
						exitBtn.disabled = true;
						exitBtn.style.opacity = "0.6";
						exitBtn.style.cursor = "not-allowed";
					}

				}

				['district_id', 'taluk_id', 'hostelId'].forEach(id => {
					let select = document.getElementById(id);
					if (select) {

						// Reset selected index to 0 (default)
						select.selectedIndex = 0;
					}
				});
			}
			else if (response.status === "already") {

				alert("Student already processed for transfer.");
				$('#transferModal').modal('hide');

				['district_id', 'taluk_id', 'hostelId'].forEach(id => {
					let select = document.getElementById(id);
					if (select) {

						// Reset selected index to 0 (default)
						select.selectedIndex = 0;
					}
				});

			} else {
				$('#transferModal').modal('hide');
				alert("Error: " + response.message);
			}
		},
		error: function () {
			alert("AJAX request failed.");
		}
	});
}


function exitStudent(button) {

	const stdRegNo = button.getAttribute('data-reg_no');

	const data = {
		action: "exit_student",
		stdRegNo: stdRegNo,
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		url: ajax_url,
		type: "POST",
		data: data,
		dataType: "json",
		success: function (response) {
			if (response.status === "success") {
				alert("Student exited successfully.");

				const transferBtn = document.querySelector('.transferbtn[data-reg_no="' + stdRegNo + '"]');

				if (transferBtn) {

					transferBtn.disabled = true;
					transferBtn.style.opacity = "0.6";
					transferBtn.style.cursor = "not-allowed";

					const parentRow = transferBtn.closest('tr');
					if (parentRow) {

						const checkbox = parentRow.querySelector('input.myCheck[type="checkbox"]');
						if (checkbox) {
							checkbox.disabled = true;
							checkbox.style.cursor = "not-allowed";
							checkbox.checked = false;

						}
					}

					const exitBtn = document.querySelector('.exitBtn[data-reg_no="' + stdRegNo + '"]');
					if (exitBtn) {

						const span = document.createElement("span");
						span.textContent = "Exited";
						span.style.color = "red";
						span.style.fontWeight = "bold";
						exitBtn.parentNode.replaceChild(span, exitBtn);
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

function autoRenewal() {
	showLoader_1();

	const checked = document.querySelectorAll('.myCheck:checked');
	const ajax_url = sessionStorage.getItem("folder_crud_link");

	let total = checked.length;
	let completed = 0;
	let successCount = 0;

	if (total === 0) {
		hideLoader_1();
		alert("No records selected.");
		document.getElementById('autoRenewal').disabled = false;
		return;
	}

		const s1_unique_id = checkbox.parentElement.querySelector('#s1_unique_id').value;

		const data = {
			action: "autoRenewal",
			s1_unique_id: s1_unique_id
		};

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function (response) {
				if (response.status === true) {
					successCount++;
				}
			},
			error: function () {
				console.log("Error with: " + s1_unique_id);
				document.getElementById('autoRenewal').disabled = false;
				hideLoader_1();
			},

			complete: function () {
				completed++;
				if (completed === total) {
					hideLoader_1();
					alert("Auto-renewal process completed for all selected students.");
					document.getElementById('autoRenewal').disabled = false;
					auto_datatable("auto_datatable", form_name, "auto_datatable");
				}
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


function log_sweetalert_approval(msg = '', url = '') {
	switch (msg) {
		case "saved":
			Swal.fire({
				icon: 'success',
				title: 'Approved Successfully',
				showConfirmButton: true,
				timer: 2000,
				willClose: () => {
					if (url !== '') {
						window.location = url;
					}
				}
			});
			break;

		case "rejected":
			Swal.fire({
				icon: 'warning',
				title: 'Rejected !!',
				showConfirmButton: true,
				timer: 2000,
				willClose: () => {
					if (url !== '') {
						window.location = url;
					}
				}
			});
			break;

			case "sanc_cnt_exceed":
			Swal.fire({
				icon: 'warning',
				title: 'Hostel Vacancy Count Exceeded',
				showConfirmButton: true,
				timer: 2000,
				willClose: () => {
					if (url !== '') {
						window.location = url;
					}
				}
			});
			break;
	}
}




