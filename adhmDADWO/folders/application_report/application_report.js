$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Feedback';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'application_report_datatable';
var action 			= "datatable";
function filter(){
	init_datatable(table_id,form_name,action);
}

function init_datatable(table_id='',form_name='',action='') {
	var table = $("#"+table_id);
	var district_name = $("#district_name").val();
	var amc_name = $("#amc_name").val();
	var taluk_name = $("#taluk_name").val();
	var hostel_name = $("#hostel_name").val();
	var app_status = $("#app_status").val();
	var data 	  = {
		"district_name"	: district_name, 
		"amc_name"	: amc_name, 
		"taluk_name"	: taluk_name, 
		"hostel_name"	: hostel_name, 
		"app_status"	: app_status, 
		"action"	: action, 
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		searching: false,
	
	"ajax"		: {
		url 	: ajax_url,
		type 	: "POST",
		data 	: data
	},
		dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		]
	});
}

function carrier_guidance_cu(unique_id = "") {
	
    var internet_status  = is_online();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }

	var cur_date = document.getElementById('cur_date').value;
    var carrier_title = document.getElementById('carrier_title').value;
	var soc_media_link = document.getElementById('soc_media_link').value;
    var remarks = document.getElementById('remarks').value;
	var is_active = document.getElementById('is_active').value;
	var unique_id = document.getElementById('unique_id').value;


    var image_upload = document.getElementById('image_upload');
	var doc_upload = document.getElementById('doc_upload');
    var video_upload = document.getElementById('video_upload');

	
   var data = new FormData();
	if (image_upload != '') {
		for (var i = 0; i < image_upload.files.length; i++) {
			data.append("image_file", document.getElementById('image_upload').files[i]);
		}
	} 
	else {
		data.append("image_file", '');
	}

	if (doc_upload != '') {
		for (var i = 0; i < doc_upload.files.length; i++) {
			data.append("doc_file", document.getElementById('doc_upload').files[i]);
		}
	} 
	else {
		data.append("doc_file", '');
	}

	if (video_upload != '') {
		for (var i = 0; i < video_upload.files.length; i++) {
			data.append("video_file", document.getElementById('video_upload').files[i]);
		}
	} 
	else {
		data.append("video_file", '');
	}


    var actions = "createupdate";


	data.append("cur_date", cur_date);
	data.append("carrier_title", carrier_title);
	data.append("soc_media_link", soc_media_link);
	data.append("remarks", remarks);
	data.append("is_active", is_active);
	data.append("action", actions);

    // var is_form = form_validity_check("was-validated");
    // if (is_form) {

    //     var data 	 = $(".was-validated").serialize();
    //     data 		+= "&unique_id="+unique_id+"&action=createupdate";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url      = sessionStorage.getItem("list_link");

        $.ajax({
            type: "POST",
			url: ajax_url,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			// beforeSend 	: function() {
			// 	$(".createupdate_btn").attr("disabled","disabled");
			// 	$(".createupdate_btn").text("Loading...");
			// },
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
}

function carrier_guidance_delete(unique_id = "") {

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

function get_hostel(){
		
	
	
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

	function view_app(unique_id="") {
	
	
		var external_window = window.open('folders/application_report/view_app.php?unique_id='+unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	// external_window.print();
	// external_window.print();
	}

function print_view()
    {
       onmouseover= window.open('adhm/adhmAdmin/uploads/document_upload','onmouseover','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    }  

	function print_pdf(file_name)
	{
		
		 onmouseover=window.open('../adhmAdmin/uploads/carrier_guidance/documents' + file_name,'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	}
