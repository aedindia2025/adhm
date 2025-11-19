$(document).ready(function () {
	init_datatable(table_id, form_name, action);
})

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Indent Raise';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'indent_raise_datatable';
var action = "datatable";

function init_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);

	var academic_year = $('#academic_year').val();
	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var month_fill = $('#month_fill').val();

	var data = {
		// "academic_year": academic_year,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"month_fill": month_fill,
		"action": action
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
		buttons: [
			{
				extend: 'copyHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise'
			},
			{
				extend: 'csvHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise',
				filename: 'indent_raise'
			},
			{
				extend: 'excelHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise',
				filename: 'indent_raise'
			},
			{
				extend: 'pdfHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise',
				filename: 'indent_raise'
			},
			{
				extend: 'print',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise'
			}
		]
	});
}

$(document).on('click', '.openPopup', function (e) {
	e.preventDefault();

	let uniqueId = $(this).data('screen_unique_id');
	let hostelId = $(this).data('hostel-id');
	let hostelName = $(this).data('hostel-name');
	let month = $(this).data('month');
	let hostelUniqueId = $(this).data('hostel-unique-id');

	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var month_fill = $('#month_fill').val();

	$('#lab_hostel_id').text(hostelId);
	$('#lab_hostel_name').text(hostelName);
	$('#lab_month').text(month);

	var item_table = $('#item_deatils_table');
	var data = {
		// "academic_year": academic_year,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"hostel_id": uniqueId,
		"month_fill": month_fill,
		"hostel_unique_id": hostelUniqueId,
		"action": 'item_details'
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = item_table.DataTable({
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},

		// dom: 'Bfrtip',
		searching: false,
		buttons: [
			{
				extend: 'copyHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise'
			},
			{
				extend: 'csvHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise',
				filename: 'indent_raise'
			},
			{
				extend: 'excelHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise',
				filename: 'indent_raise'
			},
			{
				extend: 'pdfHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise',
				filename: 'indent_raise'
			},
			{
				extend: 'print',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise'
			}
		]
	});

	// Show modal
	$('#itemDetails').modal('show');
});


function stock_report_filter() {
	// var academic_year = $('#academic_year').val();
	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var month_fill = $('#month_fill').val();

	// var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		// "academic_year": academic_year,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"month_fill": month_fill,
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

$("#export").click(function() {
  var district_name = $('#district').val();
  var taluk_name = $('#taluk').val();
  var hostel_name = $('#hostel').val();
  var month_fill = $('#month_fill').val();

  var url = `folders/current_stock_report/excel.php?district_name=${district_name}&taluk_name=${taluk_name}&hostel_name=${hostel_name}&month_fill=${month_fill}`;
  window.location.href = url; // ✅ triggers direct download
});

$("#consolidatedExport").click(function() {
  var district_name = $('#district').val();
  var taluk_name = $('#taluk').val();
  var hostel_name = $('#hostel').val();
  var month_fill = $('#month_fill').val();

  var url = `folders/current_stock_report/consolidated_excel.php?district_name=${district_name}&taluk_name=${taluk_name}&hostel_name=${hostel_name}&month_fill=${month_fill}`;
  window.location.href = url; // ✅ triggers direct download
});