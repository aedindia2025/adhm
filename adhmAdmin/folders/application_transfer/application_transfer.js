$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);
	onLoad();
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Application Transfer';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'application_transfer_datatable';
var action = "datatable";

function init_datatable(table_id = '', form_name = '', action = '') {
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
		dom: 'Bfrtip',
		searching: false,
		responsive: false,
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Application Transfer'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Application Transfer',
			filename: 'application_transfer'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Application Transfer',
			filename: 'application_transfer'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Application Transfer',
			filename: 'application_transfer'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Application Transfer'
		}
		]
	});
}


function transfer_datatable(table_id = '', form_name = '', action = '') {
	var transfer_table = $('#transfer_datatable');
	var hostel_district_final = $('#hostel_district_final').val();
	var s1_unique_id = $('#s1_unique_id').val();
	var gender = $('#gender').val();

	var data = {
		"action": "transfer_datatable",
		"hostel_district_final": hostel_district_final,
		"s1_unique_id": s1_unique_id,
		"gender": gender,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = transfer_table.DataTable({

		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		dom: 'Bfrtip',
		searching: false,
		responsive: false,
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Application Transfer'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Application Transfer',
			filename: 'application_transfer'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Application Transfer',
			filename: 'application_transfer'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Application Transfer',
			filename: 'application_transfer'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Application Transfer'
		}
		]
	});
}


function onLoad() {
	const ajax_url = sessionStorage.getItem("folder_crud_link");

	const studentType = $("#student_type").val();
	const sDistrict = $("#s_district").val();
	const caDistrict = $("#ca_district").val();
	const caDistrictNo = $("#ca_district_no").val();

	let usedDistrict = "";

	if (studentType === "65f00a259436412348") {
		// Use s_district directly
		usedDistrict = sDistrict;
		getGroupDistrict(usedDistrict);
	} else {
		if (caDistrictNo) {
			// Lookup by unique_id in district_name_test
			const postData = {
				action: "get_district_name_by_unique_id",
				unique_id: caDistrictNo
			};

			$.ajax({
				url: ajax_url,
				method: "POST",
				data: postData,
				success: function (response) {
					try {
						const res = JSON.parse(response);
						if (res.status && res.data) {
							$("#nearby_district").text(res.data.group_district);
							$("#nearby_district_id").val(res.data.group_district_unique_id);
							$("#act_district").val(res.data.unique_id);
							const combinedIds = combineDistrictIds();

							$("#hostel_district_final").val(combinedIds);

							transfer_datatable(table_id, form_name, action);
						} else {
							$("#nearby_district").text("District not found");
						}
					} catch (e) {
						console.error("Invalid JSON:", response);
						$("#nearby_district").text("Error parsing district");
					}
				},
				error: function () {
					$("#nearby_district").text("Network error");
				}
			});
		} else if (caDistrict) {
			// Lookup by DistrictCode in umis_district
			const postData = {
				action: "get_district_name_by_code",
				district_code: caDistrict
			};

			$.ajax({
				url: ajax_url,
				method: "POST",
				data: postData,
				success: function (response) {
					try {
						const res = JSON.parse(response);
						if (res.status && res.data) {
							usedDistrict = res.data.district_name;
							getGroupDistrict(usedDistrict);
						} else {
							$("#nearby_district").text("District not found");
						}
					} catch (e) {
						console.error("Invalid JSON:", response);
						$("#nearby_district").text("Error parsing district");
					}
				},
				error: function () {
					$("#nearby_district").text("Network error");
				}
			});
		} else {
			$("#nearby_district").text("District code missing");
		}
	}
}


function getGroupDistrict(districtName) {
	const ajax_url = sessionStorage.getItem("folder_crud_link");

	const postData = {
		action: "get_group_district",
		district_name: districtName
	};

	$.ajax({
		url: ajax_url,
		method: "POST",
		data: postData,
		success: function (response) {
			try {
				const res = JSON.parse(response);
				if (res.status && res.data) {
					$("#nearby_district").text(res.data.group_district);
					$("#nearby_district_id").val(res.data.group_district_unique_id);
					$("#act_district").val(res.data.unique_id);
					const combinedIds = combineDistrictIds();
					$("#hostel_district_final").val(combinedIds);

					transfer_datatable(table_id, form_name, action);
				} else {
					$("#nearby_district").text("Group district not found");
				}
			} catch (e) {
				console.error("Invalid JSON:", response);
				$("#nearby_district").text("Error parsing group district");
			}
		},
		error: function () {
			$("#nearby_district").text("Network error");
		}
	});
}

function combineDistrictIds() {
	const nearbyId = $("#nearby_district_id").val()?.trim() || '';
	const actId = $("#act_district").val()?.trim() || '';
	const idSet = new Set();

	if (nearbyId) nearbyId.split(',').forEach(id => idSet.add(id));
	if (actId) actId.split(',').forEach(id => idSet.add(id));

	return Array.from(idSet).join(',');
}

function transferStudent(btn) {
	const s1Id = btn.getAttribute('data-s1_unique_id');
	const hostelUnId = btn.getAttribute('data-hostel_un_id');
	const hostelId = btn.getAttribute('data-hostel_id');
	const ajax_url = sessionStorage.getItem("folder_crud_link");
	const url = $('#url').val();
	const loader = document.getElementById('modal-new');
	const renewBtn = document.getElementById('renewbtnn');

	Swal.fire({
		title: 'Are you sure?',
		text: `Do you want to transfer this student to Hostel ID: ${hostelId}?`,
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, Transfer'
	}).then((result) => {
		if (result.isConfirmed) {
			Swal.showLoading();
			loader.style.display = 'inline-flex'; // Show loader
			if (renewBtn) renewBtn.disabled = true; // Disable button

			const postData = {
				action: "application_transfer",
				s1_unique_id: s1Id,
				hostel_unique_id: hostelUnId
			};

			$.ajax({
				url: ajax_url,
				method: "POST",
				data: postData,
				success: function (response) {
					loader.style.display = 'none'; // Hide loader
					if (renewBtn) renewBtn.disabled = false; // Re-enable button

					try {
						const res = JSON.parse(response);

						if (res.status) {
							Swal.fire({
								icon: 'success',
								title: 'Transfer Successful!',
								text: `Student has been transferred. New ID: ${res.new_unique_id}`,
								timer: 1000,
							});
							setTimeout(() => {
								window.location.href = url;
							}, 1000);

						} else {
							Swal.fire({
								icon: 'error',
								title: 'Transfer Failed!',
								text: res.msg || 'Something went wrong.'
							});
						}
					} catch (e) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Invalid server response'
						});
						console.error("Parsing error:", response);
					}
				},
				error: function (xhr, status, error) {
					loader.style.display = 'none'; // Hide loader
					if (renewBtn) renewBtn.disabled = false; // Re-enable button

					Swal.fire({
						icon: 'error',
						title: 'AJAX Error',
						text: 'Transfer request failed. Check network or server.'
					});
					console.error("AJAX error:", error);
				}
			});
		}
	});
}
