$(document).ready(function () {
	// var table_id 	= "hostel_type_datatable";
	init_datatable(table_id, form_name, action);
	approval_datatable("approval_datatable", "approval_datatable");
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Hostel Type';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'registration_datatable';
var table_id_approval = 'approval_datatable';
var action = "datatable";

function filter(){
	init_datatable(table_id, form_name, action);
}

function init_datatable(table_id = '', form_name = '', action = '') {
	var academic_year = $("#academic_year").val();
	var hostel_name = $("#hostel_name").val();
	var batch_no = $("#batch_no").val();
	var table = $("#" + table_id);
	var data = {
		"action": action,
		"academic_year": academic_year,
		"hostel_name": hostel_name,
		"batch_no": batch_no,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		searching: false,
		responsive: false,
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
			title: 'Student Approval'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Approval',
			filename: 'student_approval'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Approval',
			filename: 'student_approval'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Approval',
			filename: 'student_approval'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Approval'
		}
		]
	});

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

case "no_reason":
			Swal.fire({
				icon: 'warning',
				title: 'Need to Fill Reject Reason',
				showConfirmButton: true,
				timer: 2000,
				willClose: () => {
					if (url !== '') {
						window.location = url;
					}
				}
			});
			break;

			case "no_acc_reason":
			Swal.fire({
				icon: 'warning',
				title: 'Need to Fill Accept Reason',
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
	}
}


function approval_datatable(table_id_approval = "", action = "") {
// alert();

var batch_no = $("#batch_no").val();
var sanc_cnt = $("#sanc_cnt").val();

var table = $("#" + table_id_approval);

var data = {
	"batch_no": batch_no,
	"sanc_cnt": sanc_cnt,
	"action": table_id_approval,
};
var ajax_url = sessionStorage.getItem("folder_crud_link");
var datatable = new DataTable(table, {
	destroy: true,
	"searching": false,
	"paging": false,
	"ordering": false,
	"info": false,
	responsive:false,
	"ajax": {
		url: ajax_url,
		type: "POST",
		data: data
	}

});
}

function hostel_type_cu(unique_id = "") {
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

function hostel_type_delete(unique_id = "") {

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


function register() {

	var batch_no = $("#batch_no").val();
var sanc_cnt = $("#sanc_cnt").val();
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		"sanc_cnt": sanc_cnt,
		"batch_no": batch_no,
		"action": "register"
	}
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			
			if (data) {
				// alert("Sucessfully inserted");
				var obj = JSON.parse(data);
				var url= obj.url;
				log_sweetalert_register("registered");
			}
		}
	});
}

function update_date() {

	var batch_no = $("#batch_no").val();
	var total_cnt = $("#total_cnt").val();

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		"batch_no": batch_no,
		"total_cnt": total_cnt,
		"action": "update_date"
	}
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {

				location.reload(); // Reload the page

			}
		}
	});
}

function print(batch_no) {
	url = 'folders/registration/approval.php?batch_no=' + batch_no;
	onmouseover = window.open(url, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
}



// function print_new(batch_no) {
//     alert('hi');
//     var url = 'folders/registration/approval_view.php?batch_no=' + batch_no;
//     var newWindow = window.open(url, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// }


function print_new(unique_id, status) {
   
	var url = 'folders/registration/approval_view.php?unique_id=' + unique_id;
    if(status == 0) {
        url = 'folders/registration/approval_view.php?unique_id=' + unique_id;
    } else if(status == 1) {
        url = 'folders/registration/accepted_view.php?unique_id=' + unique_id;
    } else if(status == 2) {
        url = 'folders/registration/rejected_view.php?unique_id=' + unique_id;
    }
    var newWindow = window.open(url, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
}


// function print_new(batch_no) {
// 	alert('hi');
// 	url = 'folders/registration/approval_view.php?batch_no=' + batch_no;
// 	onmouseover = window.open(url, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// }


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


function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../uploads' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
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
	var pdfUrl = "../uploads" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "../uploads" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

// function print_pdf(file_name) {

// 	onmouseover = window.open('../uploads'+ file_name, '_blank', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// }

// function print_view(file_name) {
	
// 	onmouseover = window.open('../uploads/' + file_name, '_blank', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// }


function get_batch_no(){
	var hostel_name = $('#hostel_name').val();
	var academic_year = $('#academic_year').val();
	var data = "hostel_name=" + hostel_name + "&action=get_batch_no&academic_year="+academic_year;
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#batch_no").html(data);
			}
		}
	});
}
