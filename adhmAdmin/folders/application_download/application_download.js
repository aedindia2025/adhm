$(document).ready(function () {
	// var table_id 	= "district_name_datatable";
	init_datatable(table_id,form_name,action);
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Application Download';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'application_download_datatable';
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') {
	var table = $("#"+table_id);
	var data 	  = {
		"action"	: action, 
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
	
	"ajax"		: {
		url 	: ajax_url,
		type 	: "POST",
		data 	: data
	},
"searching" : false,
		dom: 'Bfrtip',
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Application Download'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Application Download',
			filename: 'application_download'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Application Download',
			filename: 'application_download'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Application Download',
			filename: 'application_download'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Application Download'
		}
		],
	});
}

function application_download_cu(unique_id = "") {
    var internet_status = is_online();
    var data = new FormData();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }
    var current_date = document.getElementById('current_date').value;
    var validate_date = document.getElementById('validate_date').value;
    var application_name = document.getElementById('application_name').value;
    // var applied_date = document.getElementById('applied_date').value; 
    var description = document.getElementById('description').value;
	var file_name = document.getElementById('file_name').value;
	var csrf_token = document.getElementById('csrf_token').value;
	var unique_id = document.getElementById('unique_id').value;
    var is_active = document.getElementById('is_active').value;

    var data = new FormData();
	var image_s = $("#test_file");

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
if(file){
if (!allowedFileTypes.includes(file.type)) {
sweetalert('invalid_ext');
return false;
}
}


    if (image_s != '') {

			for (var i = 0; i < image_s.length; i++) {
				data.append("test_file", document.getElementById('test_file').files[i]);

			}
		} else {
			data.append("test_file", '');
		}
	

   
        var action = "createupdate";
        data.append("current_date", current_date);
        data.append("validate_date", validate_date);
		data.append("csrf_token", csrf_token);
        data.append("application_name", application_name);
        data.append("description", description);
        data.append("unique_id", unique_id);
        data.append("is_active", is_active);
        data.append("action", action);
		
        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = sessionStorage.getItem("list_link");

		if(((image_s != '') || (file_name !='')) && application_name != '' ){

        
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

				if(msg == "form_alert"){
                    sweetalert("form_alert");
				}else{

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





function application_download_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url      = sessionStorage.getItem("list_link");
	var csrf_token = $("#csrf_token").val();
	
	confirm_delete('delete')
	.then((result) => {
		if (result.isConfirmed) {

			var data = {
				"unique_id" 	: unique_id,
"csrf_token" : csrf_token,

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

function valid_date(){
	
	var entry_date = $('#current_date').val();
	var valid_date = $('#validate_date').val();

	if(valid_date >=entry_date){

	//   (valid_date).val(validate_date);
	$("#validate_date").val(valid_date);

	}
	else{
		sweetalert('valid_date');
		$("#validate_date").val('');
	}

}

