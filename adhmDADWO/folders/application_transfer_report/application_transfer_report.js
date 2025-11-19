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

var form_name = 'Application Transfer Report';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'application_transfer_report_datatable';
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
			title: 'Application Transfer Report'
		},
		{
			extend: 'csvHtml5',
			title: 'Application Transfer Report',
			filename: 'application_transfer_report'
		},
		{
			extend: 'excelHtml5',
			title: 'Application Transfer Report',
			filename: 'application_transfer_report'
		},
		{
			extend: 'pdfHtml5',
			title: 'Application Transfer Report',
			filename: 'application_transfer_report'
		},
		{
			extend: 'print',
			title: 'Application Transfer Report'
		}
		]
	});
}