$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
	get_std_name();
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'User Type';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'student_marksheet_datatable';
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') {
	var table = $("#"+table_id);

	var acc_year = $('#acc_year').val();
	var data 	  = {
		"acc_year" : acc_year,
		"action"	: action, 
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
	
	"ajax"		: {
		url 	: ajax_url,
		type 	: "POST",
		data 	: data
	},
		dom: 'Bfrtip',
		searching: false,
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Marksheet'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Marksheet',
			filename: 'student_marksheet'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Marksheet',
			filename: 'student_marksheet'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Marksheet',
			filename: 'student_marksheet'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Marksheet'
		}
	],
	});
}

function student_marksheet_cu(unique_id = "") {
    var internet_status  = is_online();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }

    var is_form = form_validity_check("was-validated");

	var reg_no = $("#reg_no").val();
	var sem_type = $("#sem_type").val();
	var sem_status = $("#sem_status").val();
	var academic_year = $("#academic_year").val();
	var cgpa = $("#cgpa").val();
	var upd_file = $("#upd_file").val();
	var unique_id = $("#unique_id").val();

	var data = new FormData();

	var image_s = $("#file_name");
	var file_name = $("#file_name").val();

	var files = document.getElementById('file_name').files;

	const fileInput = document.getElementById('file_name');
		const file = fileInput.files[0];


const allowedFileTypes = [
	'image/jpeg', 'image/png','image', // Images
	'application/pdf',                     // PDF
	'application/msword',                  // DOC
	'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
	
];
const maxFileSize = 5 * 1024 * 1024; // 5MB
if(file){
	if (!allowedFileTypes.includes(file.type)) {
		sweetalert('invalid_ext');
		return false;
	}
}


	if (image_s != '') {
		
		for (var i = 0; i < image_s.length; i++) {
			{
				data.append("file_name", document.getElementById('file_name').files[i]);
			}
		}
	} else {
		data.append("file_name", '');
	}


	data.append("reg_no", reg_no);
	data.append("sem_type", sem_type);
	data.append("sem_status", sem_status);
	data.append("academic_year", academic_year);
	data.append("cgpa", cgpa);
	data.append("unique_id", unique_id);
	data.append("action", "createupdate");





    if (reg_no != '' && cgpa != '' && sem_type != '' && academic_year != '' && sem_status != '' && (upd_file || file_name)) {

       

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url      = sessionStorage.getItem("list_link");

        // console.log(data);
        $.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			beforeSend 	: function() {
				$(".createupdate_btn").attr("disabled","disabled");
				$(".createupdate_btn").text("Loading...");
			},
			success		: function(data) {

				var obj     = JSON.parse(data);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;

				if (!status) {
					url 	= '';
                    $(".createupdate_btn").text("Error");
                    console.log(error);
				} else {
					if (msg=="already") {
						// Button Change Attribute
						url 		= '';

						$(".createupdate_btn").removeAttr("disabled","disabled");
						if (unique_id) {
							$(".createupdate_btn").text("Update");
						} else {
							$(".createupdate_btn").text("Save");
						}
					}
				}

				sweetalert(msg,url);
			},
			error 		: function(data) {
				alert("Network Error");
			}
		});


    } else {
        sweetalert("form_alert");
    }
}

function student_marksheet_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url      = sessionStorage.getItem("list_link");
	
	confirm_delete('delete')
	.then((result) => {
		if (result.isConfirmed) {

			var data = {
				"unique_id" 	: unique_id,
				"action"		: "delete"
			}

			$.ajax({
				type 	: "POST",
				url 	: ajax_url,
				data 	: data,
				success : function(data) {

					var obj     = JSON.parse(data);
					var msg     = obj.msg;
					var status  = obj.status;
					var error   = obj.error;

					if (!status) {
						url 	= '';
						
					} else {
						init_datatable(table_id,form_name,action);
					}
					sweetalert(msg,url);
				}
			});

		} else {
			// alert("cancel");
		}
	});
}




function go_filter(){

	var acc_year = $('#acc_year').val();
	var ajax_url  = sessionStorage.getItem("folder_crud_link");
	var data = {

		"acc_year":acc_year,
		"action" : "datatable"
	};

	init_datatable(table_id,form_name,action,data);
}



function print_pdf(file_name) {
	var pdfUrl = "uploads/student_marksheet/" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="uploads/student_marksheet/' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
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

function get_std_name() {
	// alert();
	var reg_no = $("#reg_no").val();

	// alert(asset_category);
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	if (reg_no) {
		var data = {
			"reg_no": reg_no,
			"action": "get_std_name",
		}

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function (data) {
				var obj = JSON.parse(data);
				var student_name = obj.student_name;
				// if (data) {
				$("#student_name").val(student_name);
				// }
			}
		});
	}
}