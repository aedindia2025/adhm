$(document).ready(function () {
	init_datatable(table_id,form_name,action);

	// stock_report_filter();
})

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Stock Report';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'stock_report_datatable';
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') {
	var table = $("#"+table_id);

	var academic_year = $('#academic_year').val();
	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var month_fill = $('#month_fill').val();

	var data = {
		"academic_year"	: academic_year,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"month_fill":month_fill,
		"action": action
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({

		searching: false,
	
	"ajax"		: {
		url 	: ajax_url,
		type 	: "POST",
		data 	: data
	},
	
		dom: 'Bfrtip',
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Report'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Report',
			filename: 'stock_report'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Report',
			filename: 'stock_report'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Report',
			filename: 'stock_report'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Report'
		}
	]
		// "columns": [
        //     { "data": "item_name" },
        //     { "data": "opening_stock" },
        //     { "data": "in_qty" },
        //     { "data": "out_qty" },
        //     { "data": "closing_stock" }
        // ]
	});
}


function stock_report_filter(){
	var academic_year = $('#academic_year').val();
	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var month_fill = $('#month_fill').val();

	// var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		"academic_year"	: academic_year,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"month_fill":month_fill,
		"action": 'datatable',

	};

	init_datatable(table_id,form_name,action,data);

}


