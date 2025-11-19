$(document).ready(function () {
	// var table_id 	= "zone_name_datatable";
	init_datatable(table_id, form_name, action);
	var screen_unique_id = $("#screen_unique_id").val();
	diet_sublist_datatable("diet_chart_sub_datatable", screen_unique_id);
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Master Diet Chart';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'diet_chart_datatable';
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
			title: 'Master Diet Chart'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Master Diet Chart',
			filename: 'master_diet_chart'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Master Diet Chart',
			filename: 'master_diet_chart'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Master Diet Chart',
			filename: 'master_diet_chart'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Master Diet Chart'
		}
		],
	});
}

function master_diet_chart_cu(unique_id = "") {
	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}

	// Collect only specific field values
	var screen_unique_id = $("#screen_unique_id").val();
	var main_unique_id = $("#main_unique_id").val();
	var hostel_type = $("#hostel_type").val();
	var description = $("#description").val();

	if (!hostel_type) {
		sweetalert("form_alert");
		return;
	}

	// Prepare data manually
	var data = {
		"screen_unique_id": screen_unique_id,
		"main_unique_id": main_unique_id,
		"hostel_type": hostel_type,
		"description": description,
		"unique_id": unique_id,
		"action": "createupdate"
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");

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
					url = '';
					$(".createupdate_btn").removeAttr("disabled");
					if (unique_id) {
						$(".createupdate_btn").text("Update");
					} else {
						$(".createupdate_btn").text("Save");
					}
				}
			}

			sweetalert(msg, url);
		},
		error: function () {
			alert("Network Error");
		}
	});

}

function master_diet_chart_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");

	confirm_delete('delete')
		.then((result) => {
			if (result.isConfirmed) {

				var data = {
					"unique_id": unique_id,
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
						location.reload();
					}
				});

			} else {
				// alert("cancel");
			}
		});
}

function get_unit() {
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");

	var item = $('#item').val();

	var data = {
		"item": item,
		"action": "get_unit"
	}

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			var obj = JSON.parse(data);
			console.log(obj);
			var unit = obj.unit;
			alert(unit);

			$('#unit').value(unit);
		}
	});

}

function get_items() {

	var item_category = $('#item_category').val();

	var data = "item_category=" + item_category + "&action=item_category";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#item").html(data);
			}
		}
	});

}

function save_entry() {
	var item_category = document.getElementById('item_category').value;
	var item = document.getElementById('item').value;
	var quantity = document.getElementById('quantity').value;
	var csrf_token = document.getElementById('csrf_token').value;
	var screen_unique_id = document.getElementById('screen_unique_id').value;
	var unique_id = document.getElementById('unique_id') ? document.getElementById('unique_id').value : '';

	if (item_category === '' || item === '' || quantity === '') {
		Swal.fire({
			icon: 'info',
			title: 'Fill out mandatory fields',
			text: 'Please make sure all fields are filled.',
			confirmButtonColor: '#3085d6',
		});
		return false; // stop further execution
	}

	var data = "item_category=" + item_category + "&item=" + item + "&quantity=" + quantity;
	data += "&csrf_token=" + csrf_token + "&screen_unique_id=" + screen_unique_id + "&unique_id=" + unique_id;
	data += "&action=add_diet_entry";

	$.ajax({
		type: "POST",
		url: "folders/master_diet_chart/crud.php",
		data: data,
		beforeSend: function () {
			$(".add_update_btn").attr("disabled", "disabled").text("Loading...");
		},
		success: function (response) {
			var obj = JSON.parse(response);
			var msg = obj.msg;
			var status = obj.status;
			var sub_list = obj.data;
			var error = obj.error;

			if (!status) {
				$(".add_update_btn").text("Error");
				// Optionally log error
				console.log(error);
			} else {
				$(".add_update_btn").removeAttr("disabled");
				if (unique_id && msg == "update") {
					$(".add_update_btn").text("Update");
				} else {
					$(".add_update_btn").text("Add");
					$(".add_update_btn").attr("onclick", "save_entry('')");
				}

				// Init sublist datatable
				diet_sublist_datatable("diet_chart_sub_datatable", screen_unique_id);
			}

			// Reset fields
			$("#item_category").val(null).trigger('change');
			$("#item").val(null).trigger('change');
			$("#quantity").val("");
			$("#sub_unique_id").val("");

			sweetalert(msg, "");
		},
		error: function () {
			alert("Network Error");
		}
	});
}

function diet_sublist_datatable(table_id = "", screen_unique_id = "") {
	var table = $("#" + table_id);
	var data = {
		"screen_unique_id": screen_unique_id,
		"action": table_id
	};

	var datatable = table.DataTable({
		ordering: true,
		searching: false,
		paging: false,
		info: false,
		ajax: {
			url: "folders/master_diet_chart/crud.php",
			type: "POST",
			data: data
		}
	});
}

function diet_chart_sub_delete(unique_id = "") {
	if (unique_id) {

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = sessionStorage.getItem("list_link");
		var csrf_token = $("#csrf_token").val();
		var screen_unique_id = $("#screen_unique_id").val();

		confirm_delete('delete')
			.then((result) => {
				if (result.isConfirmed) {

					var data = {
						"unique_id": unique_id,
						"csrf_token": csrf_token,
						"screen_unique_id": screen_unique_id,
						"action": "diet_chart_sub_delete"
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
								diet_sublist_datatable("diet_chart_sub_datatable", screen_unique_id);
							}
							sweetalert(msg, url);
						}
					});

				}

			});
	}
}

function diet_chart_sub_edit(unique_id = "") {
	if (unique_id) {

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var csrf_token = $("#csrf_token").val();
		var screen_unique_id = $("#screen_unique_id").val();

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: {
				"sub_unique_id": sub_unique_id,
				"csrf_token": csrf_token,
				"screen_unique_id": screen_unique_id,
				"action": "diet_chart_sub_edit"
			},
			dataType: "json",
			success: function (response) {

				if (response.status && response.data) {
					// Extract data from response
					var category = response.data.category;
					var item = response.data.item;
					var quantity = response.data.quantity;
					var unique_id = response.data.unique_id;

					// Set values to form fields
					$("#item_category").val(category).trigger('change');
					$("#item").val(item).trigger('change');
					$("#quantity").val(quantity);
					$("#sub_unique_id").val(unique_id);

				} else {
					Swal.fire({
						icon: "info",
						title: "No Record Found",
						text: "Unable to fetch diet chart details.",
					});
				}
			},
			error: function (xhr, status, error) {
				Swal.fire({
					icon: "error",
					title: "Request Failed",
					text: "Error: " + error
				});
			}
		});
	}
}

$(document).ready(function () {
	// When copy icon is clicked
	$(document).on('click', '.btn-copy', function () {
		$('#copyModalPopup').modal('show');
		const unique_id = $(this).data('id');
		$('#copy_unique_id').val(unique_id);
	});

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	// Confirm Copy button
	$('#confirmCopy').on('click', function () {
		const unique_id = $('#copy_unique_id').val();
		const copy_to = $('#copy_select').val();

		if (!copy_to) {
			alert('Please select an option');
			return;
		}

		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: {
				action: 'copy_diet_chart_sub',
				unique_id: unique_id,
				copy_to: copy_to,
				csrf_token: $('#csrf_token').val()
			},
			success: function (response) {
				const res = JSON.parse(response);
				if (res.status) {
					alert('Copied successfully!');
					$('#copyModal').modal('hide');
				} else {
					alert('Copy failed: ' + res.error);
				}
			}
		});
	});
});