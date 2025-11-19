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
		responsive: false,
		searching: false,
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

$(document).on('click', '.openRequest', function (e) {
	e.preventDefault();

	let hostelId = $(this).data('hostel-id');

	$('#req_hostel_id').html(hostelId);
	$('#req_hostel_id_val').val(hostelId);

	// Show modal
	$('#requestModal').modal('show');
});

function request() {

	const req_hostel_id = $('#req_hostel_id_val').val();
	const request_count = $('#request_count').val();

	$.ajax({
		url: sessionStorage.getItem('folder_crud_link'),
		method: 'POST',
		data: {
			action: 'request',
			req_hostel_id: req_hostel_id,
			request_count: request_count,
			csrf_token: $('#csrf_token').val()
		},
		success: function (res) {
			try {
				const json = JSON.parse(res);
				console.log(json);
				if (json.status && json.msg === 'add') {
					$('#requestModal').modal('hide');
					sweetalert(json.msg);
					$('#request_count').val('');
					init_datatable(table_id, form_name, action);

				} else if (json.status && json.msg === 'not_online') {
					Swal.fire({
						title: 'Hostel Device is Offline',
						icon: 'warning',
						confirmButtonText: 'OK'
					});
				}
				else if (json.status == false && json.msg == "already") {
					sweetalert(json.msg);
					$('#requestModal').modal('hide');
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