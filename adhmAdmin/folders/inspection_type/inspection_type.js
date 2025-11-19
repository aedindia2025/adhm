$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Inspections Type';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'inspection_type_datatable';
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') {
	var table = $("#"+table_id);
	var data 	  = {
		"action"	: action, 
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
	
	"ajax"		: {
		url 	: ajax_url,
		type 	: "POST",
		data 	: data
	},
"searching" : false,
		dom: 'Bfrtip',
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Inspection Type'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Inspection Type',
			filename: 'inspection_type'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Inspection Type',
			filename: 'inspection_type'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Inspection Type',
			filename: 'inspection_type'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Inspection Type'
		}
		]
	});
}
function inspection_type_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url      = sessionStorage.getItem("list_link");
	var csrf_token = $("#csrf_token").val();
	
	confirm_delete('delete')
	.then((result) => {
		if (result.isConfirmed) {

			var data = {
				"unique_id" 	: unique_id,
				"csrf_token" : csrf_token,
				"action"		: "delete"
			}

			$.ajax({
				type 	: "POST",
				url 	: ajax_url,
				data 	: data,
				success : function(data) {

					var obj     = JSON.parse(data);
					var msg     = obj.msg;
					var status  = obj.status;
					var error   = obj.error;

					if (!status) {
						url 	= '';
						
					} else {
						init_datatable(table_id,form_name,action);
					}
					sweetalert(msg,url);
				}
			});

		} else {
			// alert("cancel");
		}
	});
}

