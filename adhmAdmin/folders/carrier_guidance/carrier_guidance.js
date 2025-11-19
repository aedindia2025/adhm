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

function showLoader() {
		$("#loader").css("display", "inline-block"); // or "block" depending on your preference
	}

	function hideLoader() {
		$("#loader").css("display", "none");
	}


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
				columns: ':not(:last-child)'
			},
			title: 'Career Guidance'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Career Guidance',
			filename: 'career_guidance'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Career Guidance',
			filename: 'career_guidance'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Career Guidance',
			filename: 'career_guidance'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
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
	var data = new FormData();
	var cur_date = $("#cur_date").val();
	
	

	// var cur_date = $('#cur_date').val();

	
    var carrier_title = $('#carrier_title').val();
	var soc_media_link = $('#soc_media_link').val();
    var remarks = $('#remarks').val();
	var is_active = $('#is_active').val();

	var unique_id = $('#unique_id').val();

	var image_upload = $('#image_upload').val();
	var doc_upload = $('#doc_upload').val();
    var video_upload = $('#video_upload').val();
	
    var image = $('#image').val();
	var document = $('#document').val();
    var video = $('#video').val();
	
	var img_org = $('#img_org').val();
	var doc_org = $('#doc_org').val();
    var video_org = $('#video_org').val();

	var image_upload_1 = $('#image_upload');
	var doc_upload_1 = $('#doc_upload');
    var video_upload_1 = $('#video_upload');

	const img_fileInput = $('#image_upload')[0];
	const img_file = img_fileInput.files[0];


const img_allowedFileTypes = [
'image/jpeg', 'image/png', 'image/gif', // Images
];

if(img_file){
if (!img_allowedFileTypes.includes(img_file.type)) {
sweetalert('invalid_ext');
return false;
}
}

const doc_fileInput =$('#doc_upload')[0];
	const doc_file = doc_fileInput.files[0];


const doc_allowedFileTypes = [

'application/pdf',                     // PDF
'application/msword',                  // DOC
'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
'application/vnd.ms-excel',            // XLS
'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // XLSX
];

if(doc_file){
if (!doc_allowedFileTypes.includes(doc_file.type)) {
sweetalert('invalid_ext');
return false;
}
}

const vid_fileInput = $('#video_upload')[0];
	const vid_file = vid_fileInput.files[0];


const vid_allowedFileTypes = [
'video/mp4',                            // MP4
    'video/x-msvideo',                      // AVI
    'video/x-ms-wmv',                       // WMV
    'video/mpeg',                           // MPEG
    'video/webm'       
];

if(vid_file){
if (!vid_allowedFileTypes.includes(vid_file.type)) {
sweetalert('invalid_ext');
return false;
}
}
	
	
	
   var data = new FormData();
	
	if (image_upload_1) { 
		if (image_upload_1.length) { 
			for (var i = 0; i < image_upload_1.length; i++) {
				data.append("image_upload", image_upload_1[i].files[0]);
			}
		} else {
			data.append("image_upload", image_upload_1.files[0]);
		}
	} else { 
		data.append("image_upload", ''); 
	}

	if (doc_upload_1) { 
		if (doc_upload_1.length) { 
			for (var i = 0; i < doc_upload_1.length; i++) {
				data.append("doc_upload", doc_upload_1[i].files[0]);
			}
		} else {
			data.append("doc_upload", doc_upload_1.files[0]);
		}
	} else { 
		data.append("doc_upload", ''); 
	}

	if (video_upload_1) { 
		if (video_upload_1.length) { 
			for (var i = 0; i < video_upload_1.length; i++) {
				data.append("video_upload", video_upload_1[i].files[0]);
			}
		} else {
			data.append("video_upload", video_upload_1.files[0]);
		}
	} else { 
		data.append("video_upload", ''); 
	}

    var csrf_token = $('#csrf_token').val();
	
   
	
//    var files = document.getElementById('image_upload').files;

	// if (image_upload != '') {
	// 	for (var i = 0; i < image_upload.length; i++) {
	// 		alert("d");
	// 		data.append("image_upload", document.getElementById('image_upload').files[i]);
	// 	}
	// } else{
    //     data.append("image_upload", '');
    // }

	

	// if (doc_upload != '') {
	// 	for (var i = 0; i < doc_upload.length; i++) {
	// 		alert("f");
	// 		data.append("doc_upload", doc_upload.files[i]);
	// 	}
	// } else{
    //     data.append("doc_upload", '');
    // }

	// if (video_upload != '') {
	// 	for (var i = 0; i < video_upload.length; i++) {
	// 		alert("g");
	// 		data.append("video_upload", video_upload.files[i]);
	// 	}
	// } else{
    //     data.append("video_upload", '');
    // }

	
    var actions = "createupdate";
	
	
	data.append("cur_date", cur_date);
	data.append("carrier_title", carrier_title);
	data.append("soc_media_link", soc_media_link);
	data.append("img_org", img_org);
	data.append("video_org", video_org);
	data.append("doc_org", doc_org);
	data.append("image", image);
	data.append("document", document);
	data.append("video", video);
	data.append("csrf_token", csrf_token);
	data.append("remarks", remarks);
	data.append("unique_id", unique_id);
	data.append("is_active", is_active);
	data.append("action", actions);

    // var is_form = form_validity_check("was-validated");
    // if (is_form) {

    //     var data 	 = $(".was-validated").serialize();
    //     data 		+= "&unique_id="+unique_id+"&action=createupdate";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url      = sessionStorage.getItem("list_link");
		
	if((image_upload !='' || image!='') && (doc_upload!='' || document!='') && (video_upload!='' || video!='')){
showLoader();

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
hideLoader();
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

function carrier_guidance_delete(unique_id = "") {

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

function print_view()
    {
       onmouseover= window.open('uploads/sample-pdf.pdf','onmouseover','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    }  

function downloadFile(doc_file_name) {
		var link = document.createElement('a');
		link.href = '../adhmAdmin/uploads/carrier_guidance/documents' + doc_file_name;
		link.download = doc_file_name;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}

