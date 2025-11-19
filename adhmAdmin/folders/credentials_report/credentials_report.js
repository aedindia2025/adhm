$(document).ready(function () {
	init_datatable(table_id, form_name, action);
	dadwo_datatable('dadwo_datatable', form_name = '', 'dadwo_datatable')
})

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Credentials Report';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'credentials_report_datatable';
var action = "datatable";

function init_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);

	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var designation = $('#designation').val();

	var data = {
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"designation": designation,
		"action": action
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},

		columns: [
			{ data: 0 },                           // S.no
			{ data: 1 },                           // District
			{ data: 2 },                           // Taluk
			{ data: 3,},						   // Hostel (Set to 300px)
			{ data: 4, width: '300px', className: 'dt-body-wrap' },   // Hostel (Set to 300px)
			{ data: 5 },                           // User ID
			{ data: 6, orderable: false },         // Password
			{ data: 7, orderable: false }          // View Password
		],

		dom: 'Bflrtip',
		searching: true,
		buttons: [
			{
				extend: 'copyHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Credentials Report'
			},
			{
				extend: 'csvHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Credentials Report',
				filename: 'credentials_report'
			},
			{
				extend: 'excelHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Credentials Report',
				filename: 'credentials_report'
			},
			{
				extend: 'pdfHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Credentials Report',
				filename: 'credentials_report'
			},
			{
				extend: 'print',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Credentials Report'
			}
		]
	});
}

function dadwo_datatable(table_id = '', form_name = '', action = '') {

	var table = $("#dadwo_datatable");

	var academic_year = $('#academic_year').val();
	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var designation = $('#designation').val();

	var data = {
		// "academic_year": academic_year,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"designation": designation,
		"action": 'dadwo_datatable'
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},

		columns: [
			{ data: 0 },                           // S.no
			{ data: 1 },                           // District
			{ data: 2 },                           // User ID
			{ data: 3, orderable: false },         // Password
			{ data: 4, orderable: false }          // View Password
		],

		dom: 'Bflrtip',
		searching: false,
		buttons: [
			{
				extend: 'copyHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Credentials Report'
			},
			{
				extend: 'csvHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Credentials Report',
				filename: 'credentials_report'
			},
			{
				extend: 'excelHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Credentials Report',
				filename: 'credentials_report'
			},
			{
				extend: 'pdfHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Credentials Report',
				filename: 'credentials_report'
			},
			{
				extend: 'print',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Credentials Report'
			}
		]
	});
}

$(document).on('click', '.password-toggle-btn', function (e) {
	e.preventDefault();

	// 1. Find the parent <tr> (row) of the clicked button
	var $row = $(this).closest('tr');

	// 2. The password column is the 6th column (index 5)
	// Find the <span> inside the 6th <td> element (0-indexed)
	var $passwordDisplay = $row.find('td:eq(6) .password-value-display');

	if ($passwordDisplay.length === 0) {
		console.error("Password display element not found.");
		return;
	}

	var $icon = $(this).find('i');

	// Get the stored values from the span's data attributes
	var originalPassword = $passwordDisplay.data('original-value');
	var maskedPassword = $passwordDisplay.data('masked-value');

	if ($passwordDisplay.hasClass('is-masked')) {
		// Case 1: Currently masked -> switch to original
		$passwordDisplay.text(originalPassword);
		$passwordDisplay.removeClass('is-masked').addClass('is-unmasked');

		// Change icon to 'eye-slash' (hidden)
		$icon.removeClass('fa-eye').addClass('fa-eye-slash');
	} else {
		// Case 2: Currently unmasked -> switch back to masked
		$passwordDisplay.text(maskedPassword);
		$passwordDisplay.removeClass('is-unmasked').addClass('is-masked');

		// Change icon back to 'eye' (visible)
		$icon.removeClass('fa-eye-slash').addClass('fa-eye');
	}
});

$(document).on('click', '.password-toggle-btn-dadwo', function (e) {
	e.preventDefault();

	// 1. Find the parent <tr> (row) of the clicked button
	var $row = $(this).closest('tr');

	// 2. The password column is the 6th column (index 3)
	// Find the <span> inside the 6th <td> element (0-indexed)
	var $passwordDisplay = $row.find('td:eq(3) .password-value-display');

	if ($passwordDisplay.length === 0) {
		console.error("Password display element not found.");
		return;
	}

	var $icon = $(this).find('i');

	// Get the stored values from the span's data attributes
	var originalPassword = $passwordDisplay.data('original-value');
	var maskedPassword = $passwordDisplay.data('masked-value');

	if ($passwordDisplay.hasClass('is-masked')) {
		// Case 1: Currently masked -> switch to original
		$passwordDisplay.text(originalPassword);
		$passwordDisplay.removeClass('is-masked').addClass('is-unmasked');

		// Change icon to 'eye-slash' (hidden)
		$icon.removeClass('fa-eye').addClass('fa-eye-slash');
	} else {
		// Case 2: Currently unmasked -> switch back to masked
		$passwordDisplay.text(maskedPassword);
		$passwordDisplay.removeClass('is-unmasked').addClass('is-masked');

		// Change icon back to 'eye' (visible)
		$icon.removeClass('fa-eye-slash').addClass('fa-eye');
	}
});


function stock_report_filter() {
	// var academic_year = $('#academic_year').val();
	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var designation = $('#designation').val();

	// var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		// "academic_year": academic_year,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"designation": designation,
		"action": 'datatable',

	};

	init_datatable(table_id, form_name, action, data);

}

function get_taluk() {


	var district_name = $('#district').val();

	var data = "district_name=" + district_name + "&action=get_taluk";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#taluk").html(data);
			}
		}
	});

}

function get_hostel() {

	var taluk_name = $('#taluk').val();

	var data = "taluk_name=" + taluk_name + "&action=get_hostel";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {

			if (data) {
				$("#hostel").html(data);
			}
		}
	});
}

function showLoader() {
	$("#loader").css("display", "inline-block");
}

function hideLoader() {
	$("#loader").css("display", "none");
}

function warden_export() {
	var district_name = $('#district').val();
	taluk_name = $('#taluk').val();
	hostel_name = $('#hostel').val();
	designation = "65f3191aa725518258";

	var url = `folders/credentials_report/warden_excel.php?district_name=${district_name}&taluk_name=${taluk_name}&hostel_name=${hostel_name}&designation=${designation}`;
	window.location.href = url; // ✅ triggers direct download
}

function dadwo_export() {
	var district_name = $('#district').val();
	designation = "65f31975f0ce678724";

	var url = `folders/credentials_report/dadwo_excel.php?district_name=${district_name}&designation=${designation}`;
	window.location.href = url; // ✅ triggers direct download
}

$("#consolidatedExport").click(function () {
	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();

	var url = `folders/credentials_report/consolidated_excel.php?district_name=${district_name}&taluk_name=${taluk_name}&hostel_name=${hostel_name}`;
	window.location.href = url; // ✅ triggers direct download
});