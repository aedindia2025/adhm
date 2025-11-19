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
var table_id 		= 'additional_strength_district_datatable';
var action 			= "datatable";


function init_datatable(table_id='',form_name='',action='') {

	var table = $("#"+table_id);
	var district_name =          $('#district_name').val();
	// alert(district_name);
	var from_district_name   = $('#from_district_name').val();
	var to_district_name     = $('#to_district_name').val();
	var from_taluk_name      = $('#from_taluk_name').val();
    var to_taluk_name        = $('#to_taluk_name').val();
	var from_hostel_name     = $('#from_hostel_name').val();
	var to_hostel_name     = $('#to_hostel_name').val();
	var data 	  = {
		"district_name": district_name,
		"from_district_name"    : from_district_name,
		"to_district_name"      : to_district_name,
		"from_taluk_name"       : from_taluk_name,
        "to_taluk_name"         : to_taluk_name,
		"from_hostel_name"      : from_hostel_name,
		"to_hostel_name"        : to_hostel_name,
		"action"	            : action, 
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

	var from_district_name = $('#from_district_name').val();
	var from_taluk_name = $('#from_taluk_name').val();
	var from_hostel_name = $('#from_hostel_name').val();
	var from_hostel_strength = parseInt($('#from_hostel_strength').val(),10);
	var to_district_name = $('#to_district_name').val();
	var to_taluk_name = $('#to_taluk_name').val();
	var to_hostel_name = $('#to_hostel_name').val();
	var to_hostel_strength = $('#to_hostel_strength').val();
	var transfer_count = parseInt($('#transfer_count').val(),10);
	
	var csrf_token = $('#csrf_token').val();
	var remarks = $('#remarks').val();
	var file_name = $('#file_name').val();
	var unique_id = $('#unique_id').val();
	// var user_name = $('#user_name').val();
	// var user_type = $('#user_type').val();
	var is_active = $('#is_active').val();

	function showTabAndFocus(tab_id, input_id, error_id, message) {
		$('a[href="#' + tab_id + '"]').tab('show'); // Show the tab
		$('#' + error_id).text(message); // Display the error message
		setTimeout(function () {
			$('#' + input_id).focus(); // Focus the input field after the tab is shown
		}, 200);
	}

	if (transfer_count > from_hostel_strength) {
		showTabAndFocus('arrow-overview', 'transfer_count', 'error-transfer-count', "Transfer Count is Greater than From hostel strength.");
		document.getElementById('transfer_count').style.borderColor = 'red';
		return false;
	}

	
	// alert(remarks);	
    
	if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }
	var data = new FormData();

	


const allowedFileTypes = [
'image/jpeg', 'image/png', 'image/gif', // Images
'application/pdf',                     // PDF
'application/msword',                  // DOC
'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
'application/vnd.ms-excel',            // XLS
'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // XLSX
];


	
	if(to_hostel_strength!=''&& transfer_count !='' && from_district_name !='' && from_taluk_name !='' && from_hostel_name !=''&& from_hostel_strength!=''&& to_taluk_name!=''&& to_hostel_name!=''){

    // var is_form = form_validity_check("was-validated");
	var action =  "createupdate";
	data.append("from_district_name",from_district_name);
	data.append("from_taluk_name",from_taluk_name);
	data.append("from_hostel_name",from_hostel_name);
	data.append("from_hostel_strength",from_hostel_strength);
	data.append("to_district_name",to_district_name);
	data.append("to_taluk_name",to_taluk_name);
	data.append("to_hostel_name",to_hostel_name);
	data.append("to_hostel_strength",to_hostel_strength);
	data.append("transfer_count",transfer_count);
	// data.append("doc_pic",doc_pic);
	data.append("csrf_token",csrf_token);
	data.append("remarks",remarks);
	
	// data.append("user_name",user_name);
	// data.append("user_type",user_type);
	data.append("unique_id",unique_id);
	data.append("is_active",is_active);
	data.append("action",action);


        // var data 	 = $(".was-validated").serialize();
        // data 		+= "&unique_id="+unique_id+"&action=createupdate";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url      = sessionStorage.getItem("list_link");

		// if((image_s !='')||(file_name !='')){

		// alert('hi');
        // console.log(data);
        $.ajax({
			type 	: "POST",
			url 	: ajax_url,
			data 	: data,
			cache	: false,
			contentType : false,
			processData : false,
			// method: 'POST',
			beforeSend: function () {
				$(".createupdate_btn").attr("disabled", "disabled");
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


    }else {
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

function get_taluk_options() {

	// alert('hi');
	var from_district_name = $('#from_district_name').val();

	// alert(from_district_name);
	var data = "from_district_name=" + from_district_name + "&action=from_district_name";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#from_taluk_name").html(data);
			}
		}
	});
}

function get_taluk_district_wise() {

	var to_district_name = $('#to_district_name').val();

	var data = "to_district_name=" + to_district_name + "&action=to_district_name";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#to_taluk_name").html(data);
			}
		}
	});
}
	 
	
function get_hostel_name() {
	// alert("from_taluk_name");
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

function get_hostel_1() {

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
	
	
	var data = "from_hostel_name=" + from_hostel_name + "&action=get_hostel_strength";

	
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
	
	
	var data = "to_hostel_name=" + to_hostel_name + "&action=get_to_hostel_strength";

	
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

function check_strength() {
	var transfer_count = parseInt(document.getElementById("transfer_count").value,10);
	var from_hostel_strength = parseInt(document.getElementById("from_hostel_strength").value,10);

	var transfererror = document.getElementById("error-transfer-count");

	// Validate using Verhoeff algorithm
	if (transfer_count > from_hostel_strength) {

		transfererror.textContent = "Transfer Count is Greater than From hostel strength.";
		document.getElementById('transfer_count').style.borderColor = 'red';
		
		
		
		// $("#aadhaar_no").val('');
	} else {
		transfererror.textContent = ""; // Clear error message if Aadhaar number is valid
		document.getElementById('transfer_count').style.borderColor = '';
		
		
	}
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
				$("#taluk_name").html(data);
			}
		}
	});
	
	}
	 
	
	function get_hostel(){
	var taluk_name = $('#taluk_name').val();
	
	var data = "taluk_name=" + taluk_name + "&action=hostel_by_taluk_name";
	
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

	function go_filter() {

		// var student_id = $('#student_id').val();
		var district_name = $('#district_name').val();
	
	
	// alert(district_name);
	
		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var data = {
			
			"district_name": district_name,
			
			"action": 'datatable',
	
		};
	// alert(data);
	
		init_datatable(table_id,form_name,action,data);
	
	}
