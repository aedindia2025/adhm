$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);
	get_value();

});

var company_name 		= sessionStorage.getItem("company_name");
var company_address 	= sessionStorage.getItem("company_name");
var company_phone 		= sessionStorage.getItem("company_name");
var company_email 		= sessionStorage.getItem("company_name");
var company_logo 		= sessionStorage.getItem("company_name");

var form_name 			= 'Leave Approvel';
var form_header 		= '';
var form_footer 		= '';
var table_name 			= '';
var table_id 			= 'staff_leave_approvel_datatable';
var action 				= "datatable";

function statusFilters(filter_action = 0) {
	
	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}

	var approval_status = $('#approval_status').val();
	var academic_year	= $('#academic_year').val();

	// if (approval_status) {

		// sessionStorage.setItem("approval_status", approval_status);
		sessionStorage.setItem("status_action", filter_action);

		// Delete Below Line After Testing Complete
		sessionStorage.setItem("follow_up_call_action", 0);

		var filter_data = {

			"approval_status"	: approval_status,
			"academic_year"		: academic_year,
			"filter_action"		: filter_action
		};

		//console.log(filter_data);

		init_datatable(table_id, form_name, action, filter_data);

	// } else {
		//sweetalert("form_alert", "");
	// }
}


function leave_print(unique_id="") {
	// alert(unique_id);
	
	var external_window = window.open('folders/staff_leave_approval/print.php?unique_id='+unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// external_window.print();
// external_window.print();
}

function init_datatable(table_id = '', form_name = '', action = '', filter_data = '') {

	var table = $("#" + table_id);

	var data = {

		"action": action,
	};

	data = {
		...data,
		...filter_data
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		ordering: true,
		searching: false,
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		dom: 'Bfrtip',
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Staff Leave Approval'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Staff Leave Approval',
			filename: 'staff_leave_approval'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Staff Leave Approval',
			filename: 'staff_leave_approval'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Staff Leave Approval',
			filename: 'staff_leave_approval'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Staff Leave Approval'
		}
		]
	});
}

function staff_leave_approval_cu(unique_id = "") {
	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}

	var is_form = form_validity_check("was-validated");

	if (is_form) {

		var data 		= $(".was-validated").serialize();
		data 			+= "&unique_id=" + unique_id + "&action=createupdate";

		var ajax_url 	= sessionStorage.getItem("folder_crud_link");
		var url 		= sessionStorage.getItem("list_link");

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


function get_values() {

	var approval_status = $("#approval_status").val();

	if (approval_status == 1) {

		$('#reject_reason_div').css('display', 'none');
		$('#reject_reason').prop('required', false);
	}
	else
		if (approval_status == 2) {

			$('#reject_reason_div').css('display', 'block');
			$('#reject_reason').prop('required', true);
		}
}
