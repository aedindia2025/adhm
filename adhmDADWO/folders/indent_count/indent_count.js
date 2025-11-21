$(document).ready(function () {
	init_datatable(table_id, form_name, action);
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Feedback';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'indent_count_datatable';
var action = "datatable";

function filter_records() {
	init_datatable(table_id, form_name, action);
}

function init_datatable(table_id = '', form_name = '', action = '') {
	var district_name = $("#district_name").val();
	var taluk_name = $("#taluk_name").val();
	var hostel_name = $("#hostel_name").val();
	var table = $("#" + table_id);
	var data = {
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"action": action
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({

		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		dom: 'Bflrtip', // Buttons + Filter + Table + Pagination
		responsive: false,
		searching: true,      // ✅ enable search box
		paging: true,         // ✅ enable pagination
		lengthChange: true,   // ✅ enable "Show X entries"
		pageLength: 10,       // default 10 rows
		lengthMenu: [[10, 20, 50, 100], [10, 20, 50, 100]], // ✅ dropdown options
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Consume'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Consume',
			filename: 'stock_consume'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Consume',
			filename: 'stock_consume'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Consume',
			filename: 'stock_consume'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Consume'
		}
		]
	});
}

$(document).on('click', '.openPopup', function (e) {
	e.preventDefault();

	let hostelId = $(this).data('hostel-id');
	let requestedCount = $(this).data('requested-count');
	let baseCount = $(this).data('base-count');
	let monthYear = $(this).data('month-year');

	$('#label_hostel_id').html(hostelId);
	$('#hostel_id').val(hostelId);
	$('#request_count').val(requestedCount);
	$('#base_count').val(baseCount);
	$('#month_year').val(monthYear);

	// Show modal
	$('#approveModal').modal('show');
});

function approve() {

	const hostel_id = $('#hostel_id').val();
	const request_count = $('#request_count').val();
	const approved_count = $('#approved_count').val();
	const base_count = $('#base_count').val();
	const month_year = $('#month_year').val();



if (!approved_count || approved_count.trim() === "") {
        Swal.fire({
            icon: 'warning',
           title: 'Warning',
    text: 'Please provide Approved Count before submitting.',
            showConfirmButton: true,
            timer: 6000,
            timerProgressBar: true,
        });
        return; // stop execution
    }




	$.ajax({
		url: sessionStorage.getItem('folder_crud_link'),
		method: 'POST',
		data: {
			action: 'approve_count',
			hostel_id: hostel_id,
			request_count: request_count,
			base_count: base_count,
			approved_count: approved_count,
			month_year: month_year,
			csrf_token: $('#csrf_token').val()
		},
		success: function (res) {
			try {
				const json = JSON.parse(res);
				console.log(json);
				if (json.status) {
					$('#approveModal').modal('hide');
					sweetalert(json.msg);
					loadNotificationCount();
					init_datatable(table_id, form_name, action);
				} else if (json.status == false && json.msg == "already") {
					sweetalert(json.msg);
					$('#approveModal').modal('hide');
				} else {
					sweetalert(json.msg || 'Copy failed.');
				}
			} catch (e) {
				alert("Invalid server response.");
			}
		},
		error: function () {
			alert("Request failed.");
		}
	});
}

$(document).on('click', '.openReject', function (e) {
	e.preventDefault();

	let hostelId = $(this).data('hostel-id');
	let requestedCount = $(this).data('requested-count');
	let month_year = $(this).data('month-year');

	$('#reject_hostel_id').html(hostelId);
	$('#hostel_id_rej_val').val(hostelId);
	$('#reject_request_count').val(requestedCount);
	$('#month_year_rej_val').val(month_year);

	// Show modal
	$('#rejectModal').modal('show');
});

function reject() {

	const hostel_id_rej_val = $('#hostel_id_rej_val').val();
	const reject_reason = $('#reject_reason').val();
	const month_year = $('#month_year_rej_val').val();

if (!reject_reason || reject_reason.trim() === "") {
        Swal.fire({
            icon: 'warning',
           title: 'Warning',
    text: 'Please provide remarks before submitting.',
            showConfirmButton: true,
            timer: 6000,
            timerProgressBar: true,
        });
        return; // stop execution
    }
	
	$.ajax({
		url: sessionStorage.getItem('folder_crud_link'),
		method: 'POST',
		data: {
			action: 'reject',
			hostel_id_rej_val: hostel_id_rej_val,
			month_year: month_year,
			reject_reason: reject_reason,
			csrf_token: $('#csrf_token').val()
		},
		success: function (res) {
			try {
				const json = JSON.parse(res);
				console.log(json);
				if (json.status) {
					$('#rejectModal').modal('hide');
					sweetalert(json.msg);
					init_datatable(table_id, form_name, action);
					loadNotificationCount();
				} else if (json.status == false && json.msg == "already") {
					sweetalert(json.msg);
					$('#rejectModal').modal('hide');
				} else {
					sweetalert(json.msg || 'Copy failed.');
				}
			} catch (e) {
				alert("Invalid server response.");
			}
		},
		error: function () {
			alert("Request failed.");
		}
	});
}

function get_taluk() {
	var district_name = $('#district_name').val();

	var data = "district_name=" + district_name + "&action=district_name";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#taluk_name").html(data);
			}
		}
	});
}

function get_hostel() {
	var taluk_name = $('#taluk_name').val();

	var data = "taluk_name=" + taluk_name + "&action=get_hostel_by_taluk_name";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {


			if (data) {
				$("#hostel_name").html(data);
			}
		}
	});

}