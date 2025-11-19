$(document).ready(function () {
	// var table_id 	= "print_for_dispatch_datatable";
	init_datatable(table_id, form_name, action);
	sub_list_datatable("bill_dispatch_datatable", form_name, "bill_dispatch_datatable");
	sub_list_datatable1("batch_detail_datatable", form_name, "batch_detail_datatable");
	// get_list_div("batch_print_datatable","batch_print_datatable");
});



var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Print For Dispatch';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'batch_datatable';
var action = "datatable";


function sub_list_datatable1(table_id = "", form_name = "", action = "") {
	// alert('hii');

	var batch_no = $("#batch_no").val();

	var table = $("#" + table_id);
	var data = {
		"action": action,

		"batch_no": batch_no,

	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var datatable = table.DataTable({
		ordering: false,
		searching: false,
		destroy: true,
		searching: false,
		"paging": true,
		"ordering": true,
		"info": false,
"columnDefs": [
           
            { "className": "dt-right", "targets": [0, 2, 4] } // Align the fourth, fifth, and sixth columns to the right
        ],
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		}

	});
}


function filter_records(filter_action = 0) {
	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}

	var is_form = form_validity_check("was-validated");

	if (is_form) {
		var academic_year = $("#academic_year").val();

		var is_vaild = fromToDateValidity(academic_year);

		if (is_vaild) {

			sessionStorage.setItem("follow_up_call_action", 0);

			var filter_data = {
				"academic_year": academic_year,
				"filter_action": filter_action
			};

			console.log(filter_data);

			init_datatable(table_id, form_name, action, filter_data);

		}

	} else {
		sweetalert("form_alert", "");
	}
}

function init_datatable(table_id = '', form_name = '', action = '', filter_data = '') {
	// alert("jiji");
	// alert(action);
	var table = $("#" + table_id);
	var data = {
		"action": action,
	};
	data = {
		...data,
		...filter_data
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
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Monthly Bill Submission'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Monthly Bill Submission',
			filename: 'monthly_bill_submission'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Monthly Bill Submission',
			filename: 'monthly_bill_submission'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Monthly Bill Submission',
			filename: 'monthly_bill_submission'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Monthly Bill Submission'
		}
		],
"columnDefs": [
           
            { "className": "dt-right", "targets": [0, 3, 4, 5, 6, 7, 8] } // Align the fourth, fifth, and sixth columns to the right
        ],
	});

}

function sub_list_datatable(table_id = "", form_name = "", action = "") {
	// alert('hii');
	var bill_no = $("#bill_no").val();
	$("#bill").text(bill_no);
	var hostel_name = $("#hostel_name").val();
	var hostel_taluk = $("#hostel_taluk").val();
	var hostel_district = $("#hostel_district").val();
	var academic_year = $("#academic_year").val();
	// var batch_no = $("#batch_no").val();
	// var appilicationtype = $("#apptype").val();

	// alert(appilicationtype);

	var table = $("#" + table_id);
	var data = {
		"action": action,
		"hostel_name": hostel_name,
		"hostel_taluk": hostel_taluk,
		"hostel_district": hostel_district,
		"bill_no": bill_no,
		// "appilicationtype": appilicationtype,
		"academic_year": academic_year,
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var datatable = table.DataTable({
		ordering: false,
		searching: false,
"columnDefs": [
           
            { "className": "dt-right", "targets": [1, 4] } // Align the fourth, fifth, and sixth columns to the right
        ],
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		}
	});
}

function appilicationtype() {

	sub_list_datatable("dispatch_datatable", form_name, "dispatch_datatable");


}


function print_for_dispatch_cu(unique_id = "") {
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

function batch_print(unique_id = "") {


	var external_window = window.open('folders/print_for_dispatch/batch_print1.php?unique_id=' + unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	external_window.print();
	// external_window.print();
}

function openModal(unique_id = "") {

	$('#myModal').modal('show');
	// var external_window = window.open('folders/print_for_dispatch/batch_print.php?unique_id='+unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	// external_window.print();
	// external_window.print();
}
function close() {

	$('#myModal').modal('hide');

}


function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../adhmHostel/uploads/stock_entry' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
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
	var pdfUrl = "../adhmHostel/uploads/stock_entry/" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "../adhmHostel/uploads/stock_entry/" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

// function print_pdf(file_name) {

// 	onmouseover = window.open('../adhmHostel/uploads/stock_entry/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// }

// function print_view(file_name) {
// 	// alert();
// 	onmouseover = window.open('../adhmHostel/uploads/stock_entry/' + file_name, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// }


function updateStatus(unique_id, print_status) {

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		'unique_id': unique_id,
		'print_status': print_status,
		'action': 'update_status'
	};

	// Make an AJAX call to update the status
	$.ajax({
		type: 'POST',
		url: ajax_url,
		data: data,
		success: function (response) {
			// Handle success response
			console.log(response);
			sweetalert("status_saved");
			
			
			if(print_status == '2'){
				window.location.reload();
				      }

		},
		error: function (xhr, status, error) {
			// Handle error response
			console.error(error);
		}
	});
}

