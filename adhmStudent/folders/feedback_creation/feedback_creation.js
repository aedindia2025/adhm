$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Product Category';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'feedback_creation_datatable';
var action = "datatable";

function init_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);
	// var from_year =$('#from_year').val();


	var data = {
		"action": action,
	};



	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		"searching": false,
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
			title: 'Feedback Creation'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Feedback Creation',
			filename: 'feedback_creation'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Feedback Creation',
			filename: 'feedback_creation'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Feedback Creation',
			filename: 'feedback_creation'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Feedback Creation'
		}
		]
	});
}




function feedback_creation_cu(unique_id = "") {
	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}

	var is_form = form_validity_check("was-validated");

	if (is_form) {

		var data = $(".was-validated").serialize();
		data += "&unique_id=" + unique_id + "&action=createupdate";

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = sessionStorage.getItem("list_link");

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


				if (msg == "form_alert") {
					sweetalert("form_alert");
					$(".createupdate_btn").removeAttr("disabled", "disabled");
							if (unique_id) {
								$(".createupdate_btn").text("Update");
							} else {
								$(".createupdate_btn").text("Save");
							}
				} else {

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


function feedback_creation_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");
	var csrf_token = $("#csrf_token").val();

	confirm_delete('delete')
		.then((result) => {
			if (result.isConfirmed) {

				var data = {
					"unique_id": unique_id,
					"action": "delete",
					"csrf_token" : csrf_token
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

// var foo = "09/22/2011";
// var arr = foo.split("/");
// alert(arr[0])
// alert(arr[1])
// alert(arr[2])



