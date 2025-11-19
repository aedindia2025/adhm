$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);
	stock_out_sub_datatable("stock_out_sub_datatable", '', "stock_out_sub_datatable");
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
var table_id = 'stock_consumble_entry_datatable';
var action = "datatable";

function filter() {
	init_datatable(table_id, form_name, action);
}

function init_datatable(table_id = '', form_name = '', action = '') {
	// alert("hii");
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var table = $("#" + table_id);
	var data = {
		"action": action,
		"from_date": from_date,
		"to_date": to_date,
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





function stock_consumble_entry_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");
	var csrf_token = $("#csrf_token").val();

	confirm_delete('delete')
		.then((result) => {
			if (result.isConfirmed) {

				var data = {
					"unique_id": unique_id,
					"csrf_token": csrf_token,
					"action": "delete"
				}

				$.ajax({
					type: "POST",
					url: ajax_url,
					data: data,
					success: function (data) {

						var obj = JSON.parse(data);
						var msg = obj.msg;
						var status = obj.status;
						var error = obj.error;

						if (!status) {
							url = '';

						} else {
							init_datatable(table_id, form_name, action);
						}
						sweetalert(msg, url);
					}
				});

			} else {
				// alert("cancel");
			}
		});
}

function stock_out_sub_delete(unique_id = "", item_name = "") {
	// alert();


	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");
	var csrf_token = $("#csrf_token").val();
	// alert(csrf_token);


	confirm_delete('delete')
		.then((result) => {
			if (result.isConfirmed) {

				var data = {
					"unique_id": unique_id,
					"item_name": item_name,
					"csrf_token": csrf_token,
					"action": "sub_delete"
				}

				$.ajax({
					type: "POST",
					url: ajax_url,
					data: data,
					success: function (data) {

						var obj = JSON.parse(data);
						var msg = obj.msg;
						var status = obj.status;
						var error = obj.error;

						if (msg == "success_delete") {
							sweetalert(msg);
							// location.reload();
							stock_out_sub_datatable("stock_out_sub_datatable", '', "stock_out_sub_datatable");

						}

					}
				});

			} else {
				// alert("cancel");
			}
		});
}


function stock_out_sub_add() {
	// au = add,update
	$('#qty').empty();
	$('#act_qty').empty();

	var internet_status = is_online();
	var unique_id = $("#unique_id").val();
	var screen_unique_id = $("#screen_unique_id").val();
	var csrf_token = $("#csrf_token").val();

	var actual_qty_str = $("#act_qty").val();
	var purchase_item = $("#purchase_item").val();

	var actual_qty = parseFloat(actual_qty_str.replace(/,/g, '')).toFixed(3);

	var entry_qty = $("#qty").val();
	var parsed_qty = parseFloat($("#qty").val());

	if (parsed_qty > actual_qty_str) {
		Swal.fire({
			icon: 'warning',
			title: 'Quantity Exceeded!',
			text: 'Entered quantity exceeds the available quantity.',
			confirmButtonText: 'OK',
			confirmButtonColor: '#3085d6'
		});
		return;
	}

	var is_form = form_validity_check("was-validated");

	if (entry_qty != '' && entry_qty != '0') {
		// if (actual_qty >= entry_qty) {
			var data = $(".was-validated").serialize();
			data += "&unique_id=" + unique_id + "&screen_unique_id=" + screen_unique_id + "&csrf_token=" + csrf_token + "&action=createupdate_overall&parsed_qty=" + parsed_qty.toFixed(3);

			var ajax_url = sessionStorage.getItem("folder_crud_link");
			var url = '';

			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				// cache: false,
				// contentType: false,
				// processData: false,

				method: 'POST',


				success: function (data) {

					stock_out_add();

				}

			});
		// } else {
		// 	msg = 'over';
		// 	sweetalert(msg);
			// alert("Actual Qty Limit Exist!.  "+actual_qty);
		// }
	} else {
		sweetalert("form_alert");
	}
}

function stock_out_add() { // au = add,update

	$('#qty').empty();
	$('#act_qty').empty();
	var internet_status = is_online();
	var unique_id = $("#unique_id").val();
	var screen_unique_id = $("#screen_unique_id").val();
	var parsed_qty = parseFloat($("#qty").val()).toFixed(3);
	var actual_qty_str = $("#act_qty").val();
	var actual_qty = parseFloat(actual_qty_str.replace(/,/g, '')).toFixed(3);

	var is_form = form_validity_check("was-validated");

	if (is_form) {

		var data = $(".was-validated").serialize();
		data += "&unique_id=" + unique_id + "&screen_unique_id=" + screen_unique_id + "&action=createupdate&parsed_qty=" + parsed_qty;

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = '';

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			// cache: false,
			// contentType: false,
			// processData: false,
			method: 'POST',


			success: function (data) {


				var obj = JSON.parse(data);
				var msg = obj.msg;
				var status = obj.status;
				var error = obj.error;

				if (msg == "already") {
					sweetalert(msg);

				}

				stock_out_sub_datatable("stock_out_sub_datatable", '', "stock_out_sub_datatable");
				// $("#item_name").val(null).trigger('change');
				$("#item_name").empty().trigger('change');

				$("#purchase_item").val(null).trigger('change');
				$("#qty").val("");
				$("#act_qty").val("");
				$("#mg").val("");
				document.getElementById('mg').readOnly = false;
				document.getElementById('qty').readOnly = false;





			}
		});
	} else {

		sweetalert("custom", '', '', 'Create Sub Details');


	}
}

function get_stock_val() {
	//  alert("hii");
	var screen_unique_id = document.getElementById('screen_unique_id').value;
	// alert(taluk);
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	// if (taluk) {
	var data = {
		"screen_unique_id": screen_unique_id,
		"action": "get_stock_val"
	}

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			//  alert(data);
			if (data) {
				$("#item_name").html(data);
			}
		}
	});
	// }
}

function stock_out_sub_datatable(table_id = "", form_name = "", action = "") {
	// get_stock_val();

	var screen_unique_id = $("#screen_unique_id").val();

	var table = $("#" + table_id);
	var data = {
		// "stock_id": stock_id,
		"screen_unique_id": screen_unique_id,
		"action": table_id,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var datatable = new DataTable(table, {
		destroy: true,
		"searching": false,
		"paging": false,
		"ordering": false,
		"info": false,
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data,

		},
		"columnDefs": [
			// Example: aligning first and third columns to the left
			{ "targets": [0, 3, 4], "className": "dt-right" },
			{ "targets": [5], "className": "dt-center" }
			// Adjust column indices as needed
		]

	});
}

function sub_delete(val) {

	//  var data 	 = val;
	var csrf_token = $("#csrf_token").val();

	var data = "id=" + val + "&csrf_token=" + csrf_token + "&action=sub_delete";

	// var data = "id=" + val + csrf_token + " &action=sub_delete";

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = '';

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		method: 'POST',

		success: function (data) {

			var obj = JSON.parse(data);
			var msg = obj.msg;
			var data = obj.data;
			var status = obj.status;
			var error = obj.error;

			if (msg == "success_delete") {
				sweetalert(msg);

				// sub_list_datatable("document_upload_sub_datatable");
			}




		}
	});


}


