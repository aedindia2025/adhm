$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Disbursement_Recommended';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'disbursement_datatable';
var action = "datatable";

function init_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);
	var data = {
		"action": action,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
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
			title: 'Disbursement'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Disbursement',
			filename: 'disbursement'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Disbursement',
			filename: 'disbursement'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Disbursement',
			filename: 'disbursement'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Disbursement'
		}
		]
	});
}

function disbursement_cu(unique_id = "") {

	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}


	var tah_letter_no = document.getElementById('tah_letter_no').value;
	var tah_letter_date = document.getElementById('tah_letter_date').value;
	var unique_id = document.getElementById('unique_id').value;
	var tah_login_user_id = document.getElementById('tah_login_user_id').value;

	var image_s = document.getElementById("test_file");
	var file_name = document.getElementById("file_name");
	var csrf_token = document.getElementById("csrf_token").value;
	// alert(csrf_token);


	var data = new FormData();


	var image_s = $("#test_file");

	var files = document.getElementById('test_file').files;

	const fileInput = document.getElementById('test_file');
	const file = fileInput.files[0];


	const allowedFileTypes = [
		'image/jpeg', 'image/png', 'image/gif', // Images
		'application/pdf',                     // PDF
		'application/msword',                  // DOC
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
		'application/vnd.ms-excel',            // XLS
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // XLSX
	];
	const maxFileSize = 5 * 1024 * 1024; // 5MB

	if (file) {
		if (!allowedFileTypes.includes(file.type)) {
			sweetalert('invalid_ext');
			return false;
		}
	}

	if (image_s != '') {
		var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf|\.xlsx|\.xls)$/i;  // Regular expression for allowed extensions
		for (var i = 0; i < image_s.length; i++) {

			data.append("test_file", document.getElementById('test_file').files[i]);
		}

	} else {
		data.append("test_file", '');
	}


	var actions = "createupdate";


	data.append("tah_letter_no", tah_letter_no);
	data.append("tah_letter_date", tah_letter_date);
	data.append("tah_login_user_id", tah_login_user_id);
	data.append("csrf_token", csrf_token);

	data.append("unique_id", unique_id);
	data.append("action", actions);


	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");


	if (image_s != '' || file_name != '') {

		$.ajax({
			type: "POST",
			url: 'folders/disbursement/crud.php',
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			// beforeSend 	: function() {
			// 	$(".createupdate_btn").attr("disabled","disabled");
			// 	$(".createupdate_btn").text("Loading...");
			// },
			success: function (data) {
				var obj = JSON.parse(data);
				var msg = obj.msg;
				var status = obj.status;
				var error = obj.error;

				if (msg == "form_alert") {
					sweetalert("form_alert");
				} else {

					if (status) {
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

function disbursement_delete(unique_id = "") {

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

						if (status != 'success') {
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

