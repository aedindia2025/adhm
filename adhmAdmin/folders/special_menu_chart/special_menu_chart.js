$(document).ready(function () {
	init_datatable(table_id, form_name, action);
	special_menu_sub_datatable("special_menu_sub_datatable", '', "special_menu_sub_datatable");
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
var table_id = 'special_menu_chart_datatable';
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

function special_menu_sub_datatable(table_id = "", form_name = "", action = "") {

	var screen_unique_id = $("#screen_unique_id").val();

	var table = $("#" + table_id);
	var data = {
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
			{ "targets": [0, 3, 4], "className": "dt-right" },
			{ "targets": [5], "className": "dt-center" }
		],
		initComplete: function (settings, json) {
			handleFreezeFields(json);
		}

	});
}

function special_menu_chart_cu() {

	var unique_id = $("#unique_id").val();
	var screen_unique_id = $("#screen_unique_id").val();
	var date = $("#date").val();
	var festival = $("#festival").val();
	var csrf_token = $("#csrf_token").val();

	if (!date || !screen_unique_id) {
		sweetalert("form_alert");
		return;
	}

	var data = {
		unique_id: unique_id,
		screen_unique_id: screen_unique_id,
		date: date,
		festival: festival,
		csrf_token: csrf_token,
		action: "createupdate"
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		method: 'POST',
		success: function (data) {
			try {
				var obj = JSON.parse(data);
				var msg = obj.msg;
				var status = obj.status;
				var error = obj.error;

				if (!status) {
					sweetalert(msg, url);
					$(".createupdate_btn").text("Error");
					console.log("Error:", error);
					truncate_sublist(screen_unique_id);
				} else {
					if (msg === "already") {
						$(".createupdate_btn").removeAttr("disabled");

						if (unique_id) {
							$(".createupdate_btn").text("Update");
						} else {
							$(".createupdate_btn").text("Save");
						}
					}
					sweetalert(msg, url);
				}
			} catch (e) {
				console.error("Invalid JSON response:", data);
				alert("Server Error: Invalid response");
			}
		},
		error: function () {
			alert("Network Error");
		}
	});
}

function truncate_sublist(screen_unique_id) {
	const ajax_url = sessionStorage.getItem("folder_crud_link");
	const csrf_token = $("#csrf_token").val();

	$.post(ajax_url, {
		action: 'silent_delete',
		csrf_token: csrf_token,
		screen_unique_id: screen_unique_id
	});
}


function special_menu_chart_delete(unique_id = "") {

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
			}
		});
}

function sublist_cu() {
	const is_form = form_validity_check("was-validated");
	const screen_unique_id = $("#screen_unique_id").val();
	const sublist_unique_id = $("#sublist_unique_id").val();

	if (is_form) {
		let date = $("#date").val();
		let item_category = $("#item_category").val();
		let item = $("#item").val();
		let unit = $("#unit").val();
		let quantity = $("#quantity").val();
		let veg_type = $("#veg_type").val() || "";

		if (item_category === "cff03fed698590fd71") {
			if (veg_type === "") {
				sweetalert("Please select Distribution Type");
				return;
			}

			if (veg_type === "common") {
				// Save as common veg
				item = "C-V";
			} else if (veg_type === "individual") {
				// Must select an item in this case
				if (!item) {
					sweetalert("Please select an item for individual distribution");
					return;
				}
			}
		}

		let data =
			"&screen_unique_id=" + screen_unique_id +
			"&sublist_unique_id=" + sublist_unique_id +
			"&item_category=" + item_category +
			"&date=" + date +
			"&item=" + item +
			"&unit=" + unit +
			"&quantity=" + quantity +
			"&veg_type=" + veg_type +
			"&action=sublist_cu";

		const ajax_url = sessionStorage.getItem("folder_crud_link");

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function (data) {
				try {
					const obj = JSON.parse(data);
					const msg = obj.msg;

					if (msg === "create" || msg === "update") {
						// Refresh sublist table
						special_menu_sub_datatable("special_menu_sub_datatable", '', "special_menu_sub_datatable");

						// Clear fields
						$('#item_category').val('').trigger('change');
						$('#veg_type').val('').trigger('change'); // reset new dropdown
						$('#item').val('').trigger('change').show(); // show back if hidden
						$('#unit').val('');
						$('#quantity').val('');
						$('#sublist_unique_id').val('');
						$('.add_update_btn').text('Save');
						$('#veg_type_container').hide(); // hide container by default
					}

					sweetalert(msg);
				} catch (err) {
					console.error("Invalid JSON:", data);
					alert("Server Error: Check console");
				}
			},
			error: function () {
				alert("Network Error");
			}
		});
	} else {
		sweetalert("form_alert");
	}
}

function edit_sublist(unique_id) {
	let ajax_url = sessionStorage.getItem("folder_crud_link");

	$.post(ajax_url, {
		action: "get_sublist_item",
		unique_id: unique_id
	}, function (response) {
		let obj = JSON.parse(response);
		if (obj.status) {
			$('#item_category').val(obj.data.category).trigger('change');

			// ✅ If item = "C-V"
			if (obj.data.item === 'C-V') {
				$('#item_container').hide(); // hide item container
				$('#veg_type_container').show(); // show veg type container if needed
				$('#veg_type').val('common').trigger('change'); // set default to Common Veg
			} else {
				$('#item_container').show();
				$('#veg_type').val('individual').trigger('change');
			}

			setTimeout(() => {
				$('#item').val(obj.data.item).trigger('change');
			}, 200); // Delay if item is dynamically loaded

			$("#quantity").val(obj.data.quantity);
			$("#sublist_unique_id").val(obj.data.unique_id);
			$(".add_update_btn").text("Update");
		}
	});
}

function delete_sublist(unique_id) {
	const ajax_url = sessionStorage.getItem("folder_crud_link");
	const csrf_token = $("#csrf_token").val();

	confirm_delete('delete').then((result) => {
		if (result.isConfirmed) {
			$.post(ajax_url, {
				action: 'sublist_delete',
				csrf_token: csrf_token,
				unique_id: unique_id
			}, function (data) {
				try {
					const obj = JSON.parse(data);
					sweetalert(obj.msg);
					special_menu_sub_datatable("special_menu_sub_datatable", '', "special_menu_sub_datatable");
				} catch (err) {
					console.error("Invalid JSON:", data);
				}
			});
		}
	});
}


function get_items() {
	var item_category = $('#item_category').val();

	// Show veg type dropdown if category is Vegetables
	if (item_category === 'cff03fed698590fd71') {
		$('#veg_type_container').show();
	} else {
		$('#veg_type_container').hide();
		$('#item_container').show();
		$('#veg_type').val(''); // Clear the selection
	}

	// By default, load all items (will re-filter when type changes)
	load_items();
}

function load_items() {
	var item_category = $('#item_category').val();
	var veg_type = $('#veg_type').val();

	$.ajax({
		type: "POST",
		url: sessionStorage.getItem("folder_crud_link"),
		data: {
			action: "item_category",
			item_category: item_category,
			veg_type: veg_type
		},
		success: function (data) {
			$("#item").html(data);
		}
	});
}

function toggleVegMode() {
	var veg_type = $('#veg_type').val();

	if (veg_type === 'common') {
		console.log("inside the common");
		$('#item').val('').trigger('change'); // clear item selection
		$('#item_container').hide(); // ✅ hide the entire div
	} else {
		$('#item_container').show(); // ✅ show the entire div again
		load_items();
	}
}

$(document).on('click', '.openPopup', function (e) {
	e.preventDefault();

	let uniqueId = $(this).data('id');
	let screenId = $(this).data('screen-id');

	$('#copy_unique_id').val(uniqueId);
	$('#copy_screen_id').val(screenId);

	// Show modal
	$('#copyModal').modal('show');
});

function get_unit() {
	$("#unit").val('');
	var veg_type = document.getElementById('veg_type').value;
	if (veg_type == "common") {
		$("#unit").val('Kg');
		return;
	}
	var item_name = document.getElementById('item').value;

	if (!item_name) {
		$("#unit").val('');
		return; // Exit if empty
	}

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var data = {
		"item_name": item_name,
		"action": "get_unit"
	};

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (response) {
			// Populate the readonly input with the unit
			$("#unit").val(response);
		},
		error: function () {
			console.error("Failed to fetch unit");
		}
	});
}

function null_unit() {
	$("#unit").val('');
}

function validate_quantity(input) {
	// Remove any non-digit characters
	input.value = input.value.replace(/\D/g, '');

	// Remove leading 0
	if (input.value.startsWith('0')) {
		input.value = input.value.replace(/^0+/, '');
	}

	// Limit to max 5 digits
	if (input.value.length > 5) {
		input.value = input.value.slice(0, 5);
	}
}

function makeInputReadOnly(selector) {
	$(selector).addClass('input-disabled-look');
}

function removeInputReadOnly(selector) {
	$(selector).removeClass('input-disabled-look');
}

function handleFreezeFields(json) {
	if (json && json.data && json.data.length > 0) {
		// Freeze if sublist has entries
		makeInputReadOnly('#date');
	} else {
		// Unfreeze if all deleted
		removeInputReadOnly('#date');
	}
}