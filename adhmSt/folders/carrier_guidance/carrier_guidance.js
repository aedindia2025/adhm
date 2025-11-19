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
var table_id 		= 'carrier_guidance_datatable';
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
	dom: 'Bfrtip',
	searching: false,
	buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Career Guidance'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Career Guidance',
			filename: 'career_guidance'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Career Guidance',
			filename: 'career_guidance'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Career Guidance',
			filename: 'career_guidance'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+3))'
			},
			title: 'Career Guidance'
		}
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

function print_view()
    {
       onmouseover= window.open('adhm/adhmAdmin/uploads/document_upload','onmouseover','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    }  

	function print_pdf(file_name)
	{
		
		 onmouseover=window.open('../adhmAdmin/uploads/carrier_guidance/documents' + file_name,'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	}

	function downloadFile(doc_file_name) {
		var link = document.createElement('a');
		link.href = '../adhmAdmin/uploads/carrier_guidance/documents' + doc_file_name;
		link.download = doc_file_name;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}