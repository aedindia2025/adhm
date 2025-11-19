$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);
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
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Disbursement'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Disbursement',
			filename: 'disbursement'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Disbursement',
			filename: 'disbursement'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Disbursement',
			filename: 'disbursement'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Disbursement'
		}
		]
	});
}


function disbursement_cu(unique_id = "") {
	// alert('hi');
	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}

	var district_name = document.getElementById('district_name').value;
	var csrf_token = document.getElementById('csrf_token').value;
	var hostel_name = document.getElementById('hostel_name').value;
	var taluk_name = document.getElementById('taluk_name').value;
	var applied_date = document.getElementById('applied_date').value;
	var disbursement_type = document.getElementById('disbursement_type').value;
	var academic_year = document.getElementById('academic_year').value;
	var cur_month = document.getElementById('cur_month').value;
	var connection_no = document.getElementById('connection_no').value;
	var letter_no = document.getElementById('letter_no').value;
	var letter_date = document.getElementById('letter_date').value;
	var unique_id = document.getElementById('unique_id').value;
	var login_user_id = document.getElementById('login_user_id').value;

	// var image_s = document.getElementById("test_file");
	var data = new FormData();

	var image_s = $("#test_file").val();
	var hid_pic = $("#hid_pic").val();

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

	// if ((image_s != '' || hid_pic != '') && disbursement_type != '' && connection_no != '' && letter_no != '' && letter_date != '') {

	if (files.length > 0) { // Check if files were selected
		var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf|\.xlsx|\.xls)$/i;  // Regular expression for allowed extensions
		for (var i = 0; i < files.length; i++) {
			// if () {
				data.append("test_file", files[i]);


				var actions = "createupdate";
				data.append("csrf_token", csrf_token);
				data.append("hostel_name", hostel_name);
				data.append("taluk_name", taluk_name);
				data.append("district_name", district_name);
				data.append("applied_date", applied_date);
				data.append("disbursement_type", disbursement_type);
				data.append("academic_year", academic_year);
				data.append("cur_month", cur_month);
				data.append("connection_no", connection_no);
				data.append("letter_no", letter_no);
				data.append("letter_date", letter_date);
				data.append("login_user_id", login_user_id);
				data.append("unique_id", unique_id);
				data.append("action", "createupdate");

				var ajax_url = sessionStorage.getItem("folder_crud_link");
				var url = sessionStorage.getItem("list_link");

				$.ajax({
					type: "POST",
					url: 'folders/disbursement/crud.php',
					data: data,
					cache: false,
					contentType: false,
					processData: false,
					method: 'POST',

					success: function (data) {
						var obj = JSON.parse(data);
						var msg = obj.msg;
						var status = obj.status;
						var error = obj.error;

						if (msg == "form_alert") {
							sweetalert("form_alert");
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
			// } else {
			// 	var errorMessageSpan = document.getElementById("error_message");
			// 	errorMessageSpan.textContent = "Error: Only PDF, images (JPG, JPEG, PNG, GIF), and Excel files (XLSX, XLS) are allowed.";
			// 	// Additional error handling if needed
			// }
		}

	} else {
		data.append("test_file", '');

		var actions = "createupdate";

		data.append("hostel_name", hostel_name);
		data.append("csrf_token", csrf_token);
		data.append("taluk_name", taluk_name);
		data.append("district_name", district_name);
		data.append("applied_date", applied_date);
		data.append("disbursement_type", disbursement_type);
		data.append("academic_year", academic_year);
		data.append("cur_month", cur_month);
		data.append("connection_no", connection_no);
		data.append("letter_no", letter_no);
		data.append("letter_date", letter_date);
		data.append("login_user_id", login_user_id);
		data.append("unique_id", unique_id);
		data.append("action", "createupdate");

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = sessionStorage.getItem("list_link");

		$.ajax({
			type: "POST",
			url: 'folders/disbursement/crud.php',
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',

			success: function (data) {
				var obj = JSON.parse(data);
				var msg = obj.msg;
				var status = obj.status;
				var error = obj.error;
				if (msg == "form_alert") {
					sweetalert("form_alert");
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

	}
	// } else {
	// 	sweetalert("form_alert");
	// }

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

// function print_pdf(file_name) {

// 	onmouseover = window.open('uploads/disbursement/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// }

// function print_view(file_name) {
// 	onmouseover = window.open('uploads/disbursement/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// }



function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="uploads/disbursement' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
		'</body></html>';


	var win = window.open("", "", "width=600,height=480,toolbar=no,menubar=no,resizable=yes");

	if (win) {

		win.document.open();

		win.document.write(iframeContent);

		win.document.close();

		var iframe = win.document.getElementById('myIframe');
		iframe.onload = function () {
			var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

			// Prevent right-click context menu inside the iframe
			iframeDoc.addEventListener('contextmenu', function (e) {
				e.preventDefault();
			});

			iframeDoc.addEventListener('keydown', function (e) {
				// Check for specific key combinations
				if ((e.ctrlKey || e.metaKey) && (e.keyCode == 83 || e.keyCode == 67 || e.keyCode == 74 || e.keyCode == 73)) {
					// Prevent default action (e.g., save, copy, downloads, inspect)
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				// Check for F12 key
				if (e.keyCode == 123) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
			});

		};


	} else {
		alert('Please allow popups for this website');
	}
}

function print_pdf(file_name) {
	var pdfUrl = "uploads/disbursement/" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "uploads/disbursement/" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}