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


function filter(){
	init_datatable(table_id, form_name, action);
	
}

function category_print(unique_id = "") {


	var external_window = window.open('folders/monthly_bill_submission/category_view.php?unique_id=' + unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	// external_window.print();
	// external_window.print();
}


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
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
"columnDefs": [
           
            { "className": "dt-right", "targets": [0, 2, 4] } // Align the fourth, fifth, and sixth columns to the right
        ],
	});
}

$(document).ready(function () {

    

$(document).on('click', '.accept-btn', function (event) {
    event.preventDefault();
   
	var batchNo = $(this).data('batch-no');
	var uniqueId = $(this).data('unique-id');
	
	var acceptButton = $(this); // Store reference to accept button

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	if (batchNo) {
		var data = {
			"batchNo": batchNo,
			"uniqueId": uniqueId,
			
			"action": "at_accept"
		}

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function (data) {

				var obj = JSON.parse(data);
				var msg = obj.msg;

				// if (msg != "sanc_cnt_exceed") {
					acceptButton.hide(); // Hide accept button
					acceptButton.closest('td').html('Accepted'); // Show status as "Accepted"
					
					// log_sweetalert_approval("saved");
                    log_sweetalert_approval('saved');
				// }else if(msg == "sanc_cnt_exceed"){
					// sweetalert("sanc_cnt_exceed");
				// }
			}
		});
	}
});



$(document).on('click', '.reject-btn', function (event) {
   
    event.preventDefault();

var batchNo = $(this).data('batch-no');
var uniqueId = $(this).data('unique-id');



var reasonTextBox = '<br><br><textarea class="reason-textbox" placeholder="Enter reason for rejection"></textarea>';
$(this).parent().append(reasonTextBox);

var rejectButton = $(this);
rejectButton.replaceWith('<button class="confirm-reject-btn"  data-batch-no="' + batchNo + '" data-unique-id="' + uniqueId + '">Confirm Reject</button>');
});

// Event listener for confirm reject button
$(document).on('click', '.confirm-reject-btn', function (event) {
   
    event.preventDefault();
var batchNo = $(this).data('batch-no');
var uniqueId = $(this).data('unique-id');
var reason = $(this).siblings('.reason-textbox').val();

var rejectButton = $(this); // Store reference to confirm reject button

var ajax_url = sessionStorage.getItem("folder_crud_link");
if (batchNo && reason) {
    var data = {
        "batchNo": batchNo,
        "uniqueId": uniqueId,
       
        "reason": reason,
        "action": "at_reject"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            if (data) {
                rejectButton.hide(); // Hide confirm reject button
                rejectButton.closest('td').html('Rejected'); // Show status as "Rejected"
                log_sweetalert_approval("rejected", "");
            }
        }
    });
}else{
	log_sweetalert_approval("no_reason");
}
});
});

function register(url){
    var url = "index.php?file="+url;
    sweetalert('create',url);
}


function log_sweetalert_approval(msg = '', url = '') {
	switch (msg) {
		case "saved":
			Swal.fire({
				icon: 'success',
				title: 'Approved Successfully',
				showConfirmButton: true,
				timer: 2000,
				willClose: () => {
					if (url !== '') {
						window.location = url;
					}
				}
			});
			break;

		case "rejected":
			Swal.fire({
				icon: 'warning',
				title: 'Rejected !!',
				showConfirmButton: true,
				timer: 2000,
				willClose: () => {
					if (url !== '') {
						window.location = url;
					}
				}
			});
			break;

			case "sanc_cnt_exceed":
			Swal.fire({
				icon: 'warning',
				title: 'Hostel Vacancy Count Exceeded',
				showConfirmButton: true,
				timer: 2000,
				willClose: () => {
					if (url !== '') {
						window.location = url;
					}
				}
			});
			break;

			case "no_reason":
			Swal.fire({
				icon: 'warning',
				title: 'Enter Valid Reason',
				showConfirmButton: true,
				timer: 2000,
				willClose: () => {
					if (url !== '') {
						window.location = url;
					}
				}
			});
			break;
	}
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
	var district_name = $("#district_name").val();
	var taluk_name = $("#taluk_name").val();
	var hostel_name = $("#hostel_name").val();
	
	var table = $("#" + table_id);
	var data = {
		"action": action,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
	};	var ajax_url = sessionStorage.getItem("folder_crud_link");

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
           
            { "className": "dt-right", "targets": [0, 4, 5, 6, 7, 8, 9] } // Align the fourth, fifth, and sixth columns to the right
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


	var external_window = window.open('folders/monthly_bill_submission/batch_print1.php?unique_id=' + unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	// external_window.print();
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


function rec_status(batchNo) {
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		'batchNo': batchNo,
		'action': 'rec_status'
	};

	// Make an AJAX call to update the status
	$.ajax({
		type: 'POST',
		url: ajax_url,
		data: data,
		success: function (response) {
			// Handle success response
			log_sweetalert_register("rec_success", "");
		},
		error: function (xhr, status, error) {
			// Handle error response
			console.error(error);
		}
	});
}

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
        success: function(response) {
            // Handle success response
            console.log(response);
			sweetalert("status_saved");
			if(print_status == '2'){
				$('#print_id').prop('disabled', true);
			}

        },
        error: function(xhr, status, error) {
            // Handle error response
            console.error(error);
        }
    });
}

function log_sweetalert_register(msg = '', url = '') {
	switch (msg) {
		case "registered":
			Swal.fire({
				icon: 'success',
				title: 'Successfully Registered',
				showConfirmButton: true,
				timer: 2000,
				willClose: () => {
					// if (url !== '') {

					window.location = "index.php?file="+url;
					// }
				}
			});
			break;

		case "rec_success":
			Swal.fire({
				icon: 'success',
				title: 'Received !!',
				showConfirmButton: true,
				timer: 2000,
				willClose: () => {
					window.location.reload();
				}
			});
			break;

		case "rejected":
			Swal.fire({
				icon: 'warning',
				title: 'Rejected !!',
				showConfirmButton: true,
				timer: 2000,
				willClose: () => {
					if (url !== '') {
						window.location = url;
					}
				}
			});
			break;
	}
}


function get_taluk() {


	var district_name = $('#district_name').val();

	var data = "district_name=" + district_name + "&action=district_name";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
	if (data) {
				$("#taluk_name").html(data);
			}
		}
	});

}

function get_hostel() {



	var taluk_name = $('#taluk_name').val();

	var data = "taluk_name=" + taluk_name + "&action=get_hostel_by_taluk_name";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {


			if (data) {
				$("#hostel_name").html(data);
			}
		}
	});

}




