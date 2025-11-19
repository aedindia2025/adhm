$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Additional Strength';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'additional_strength_datatable';
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') {
	var table = $("#"+table_id);

	
	// var student_id = $('#student_id').val();
	var district_name = $('#district_name').val();
	// var current_date = $('#current_date').val();

	var from_taluk_name = $('#from_taluk_name').val();
    var to_taluk_name = $('#to_taluk_name').val();
	var from_hostel_name = $('#from_hostel_name').val()
	// var from_year =$('#from_year').val();

	
	var data 	  = {

		// "student_id"	: student_id,
		"district_name": district_name,
		"from_taluk_name": from_taluk_name,
        "to_taluk_name" : to_taluk_name,
		"from_hostel_name": from_hostel_name,
		// "current_date":current_date,
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
	buttons: [{
		extend: 'copyHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Additional Strength'
	},
	{
		extend: 'csvHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Additional Strength',
		filename: 'additional_strength'
	},
	{
		extend: 'excelHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Additional Strength',
		filename: 'additional_strength'
	},
	{
		extend: 'pdfHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Additional Strength',
		filename: 'additional_strength'
	},
	{
		extend: 'print',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Additional Strength'
	}
	]
	});
}


function additional_strength_cu(unique_id = "") {

    var internet_status  = is_online();

	var district_id = $('#district_id').val();
	var from_taluk_name = $('#from_taluk_name').val();
	var from_hostel_name = $('#from_hostel_name').val();
	var from_hostel_strength = $('#from_hostel_strength').val();
	var to_taluk_name = $('#to_taluk_name').val();
	var to_hostel_name = $('#to_hostel_name').val();
	var doc_pic = $('#doc_pic').val();
	var remarks =$('#remarks').val();
	var csrf_token = $('#csrf_token').val();
	var to_hostel_strength = $('#to_hostel_strength').val();
	var transfer_count = $('#transfer_count').val();
	var user_name = $('#user_name').val();
	var user_type = $('#user_type').val();
	var unique_id = $('#unique_id').val();
	var is_active = $('#is_active').val();

	
// alert("hi");
    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }
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
			// if (!allowedExtensions.test(files[i].name)) {
			// 	// sweetalert('doc_error');
			// 	// alert('Please select only image files (JPEG, JPG, PNG, GIF) or PDF and Excel files.');
			// 	var errorMessageSpan = document.getElementById("error_message");
			// 	errorMessageSpan.textContent = "Error: Only PDF, images (JPG, JPEG, PNG, GIF), and Excel files (XLSX, XLS) are allowed.";
			// } else {
				data.append("test_file", document.getElementById('test_file').files[i]);
			// }
		}
	} else {
		data.append("test_file", '');
	}
	
    var is_form = form_validity_check("was-validated");
	var action =  "createupdate";

	if((image_s != ''|| file_name !='')&& to_hostel_strength!=''&& transfer_count !='' && district_id !='' && from_taluk_name !='' && from_hostel_name !=''&& from_hostel_strength!=''&& to_taluk_name!=''&& to_hostel_name!=''){
		
	data.append("csrf_token",csrf_token);
	data.append("district_id",district_id);
	data.append("from_taluk_name",from_taluk_name);
	data.append("from_hostel_name",from_hostel_name);
	data.append("from_hostel_strength",from_hostel_strength);
	data.append("to_taluk_name",to_taluk_name);
	data.append("to_hostel_name",to_hostel_name);
	data.append("to_hostel_strength",to_hostel_strength);
	data.append("transfer_count",transfer_count);
	data.append("doc_pic",doc_pic);
	data.append("remarks",remarks);
	data.append("user_name",user_name);
	data.append("user_type",user_type);
	data.append("unique_id",unique_id);
	data.append("is_active",is_active);
	data.append("action","createupdate");


        // var data 	 = $(".was-validated").serialize();
        // data 		+= "&unique_id="+unique_id+"&action=createupdate";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url      = sessionStorage.getItem("list_link");

        // console.log(data);
        $.ajax({
			type 	: "POST",
			url 	: ajax_url,
			data 	: data,
			cache	: false,
			contentType : false,
			processData : false,
			method: 'POST',

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

function additional_strength_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url      = sessionStorage.getItem("list_link");
	var csrf_token = $("#csrf_token").val();

	
	confirm_delete('delete')
	.then((result) => {
		if (result.isConfirmed) {

			var data = {
				"unique_id" 	: unique_id,
				"action"		: "delete",
				"csrf_token" : csrf_token
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

function get_taluk(){
    

	var district_name = $('#district_name').val();
	
	var data = "district_name=" + district_name + "&action=district_name";
	
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#from_taluk_name").html(data);
                $("#to_taluk_name").html(data);
			}
		}
	});
	
	}
	 
	
	function get_hostel(){
		

	var from_taluk_name = $('#from_taluk_name').val();
	
	var data = "from_taluk_name=" + from_taluk_name + "&action=get_hostel_by_taluk_name";
	
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
	
	
			if (data) {
				$("#from_hostel_name").html(data);
                
			}
		}
	});
	


	}


	function get_to_hostel(){
		
	
	
		var to_taluk_name = $('#to_taluk_name').val();
		
		
		var data = "to_taluk_name=" + to_taluk_name + "&action=get_hostel_by_taluk_name_1";
		
		var ajax_url = sessionStorage.getItem("folder_crud_link");
		
		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function (data) {
				if (data) {
					$("#to_hostel_name").html(data);
					
				}
			}
		});
		
		}

	function get_hostel_strength(){

	
		var from_hostel_name = $('#from_hostel_name').val();

		// alert(from_hostel_name);
		
		// console.log(from_hostel_name);
		var data = "from_hostel_name=" + from_hostel_name + "&action=get_hostel_strength";

		// alert(data);

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		
		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function(data){

				if (data!='') {
					$("#from_hostel_strength").val(data);	
				}
				else{
					$("#from_hostel_strength").val(0);
				}
			}
		});
		
		}

		

		function get_to_hostel_strength(){

			var to_hostel_name = $('#to_hostel_name').val();
			
			
			var data = "from_hostel_name=" + to_hostel_name + "&action=get_hostel_strength";
	
			
			var ajax_url = sessionStorage.getItem("folder_crud_link");
			
			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				success: function(data){
					if (data!='') {
						$("#to_hostel_strength").val(data);	
					}
					else{
						$("#to_hostel_strength").val(0);
					}
				}
			});
			
			}
	


	// function get_hostel_1(){
	
    //     var to_taluk_name = $('#to_taluk_name').val();
        
        
    //     var data = "to_taluk_name=" + to_taluk_name + "&action=get_hostel_by_taluk_name_1";
        
    //     var ajax_url = sessionStorage.getItem("folder_crud_link");
        
    //     $.ajax({
    //         type: "POST",
    //         url: ajax_url,
    //         data: data,
    //         success: function (data) {
        
        
    //             if (data) {
                    
    //                 $("#to_hostel_name").html(data);
    //             }
    //         }
    //     });
        
    //     }