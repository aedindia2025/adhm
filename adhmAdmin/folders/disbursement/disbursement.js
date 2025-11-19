$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Disbursement';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'disbursement_datatable';
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') {
	var table = $("#"+table_id);
	var district_name = $('#district_name').val();
    var taluk_name = $('#taluk_name').val();
    var hostel_name = $('#hostel_name').val();
	var data 	  = {
		"district_name": district_name,
		"taluk_name" : taluk_name,
		"hostel_name" : hostel_name,
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
		title: 'Disbursement'
	},
	{
		extend: 'csvHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Disbursement',
		filename: 'disbursement'
	},
	{
		extend: 'excelHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Disbursement',
		filename: 'disbursement'
	},
	{
		extend: 'pdfHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Disbursement',
		filename: 'disbursement'
	},
	{
		extend: 'print',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Disbursement'
	}
	]
	});
}

function disbursement_cu(unique_id = "") {
	
    var internet_status  = is_online();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }


	var dadwo_letter_no = document.getElementById('dadwo_letter_no').value;
    var dadwo_letter_date = document.getElementById('dadwo_letter_date').value;
	var unique_id = document.getElementById('unique_id').value;
    var dadwo_login_user_id = document.getElementById('dadwo_login_user_id').value;
	var csrf_token = document.getElementById('csrf_token').value;

	var image_s = document.getElementById("test_file");

	
   var data = new FormData();
	if (image_s != '') {
		for (var i = 0; i < image_s.files.length; i++) {
			data.append("test_file", document.getElementById('test_file').files[i]);

		}
	} 
	else {
		data.append("test_file", '');
	}
    var actions = "createupdate";


	data.append("csrf_token", csrf_token);
	data.append("dadwo_letter_no", dadwo_letter_no);
	data.append("dadwo_letter_date", dadwo_letter_date);
	data.append("dadwo_login_user_id", dadwo_login_user_id);
	data.append("unique_id", unique_id);
	data.append("action", actions);

    // var is_form = form_validity_check("was-validated");
    // if (is_form) {

    //     var data 	 = $(".was-validated").serialize();
    //     data 		+= "&unique_id="+unique_id+"&action=createupdate";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url      = sessionStorage.getItem("list_link");

        $.ajax({
            type: "POST",
			url: 'folders/disbursement/crud.php',
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

function disbursement_delete(unique_id = "") {

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

	function go_filter() {

		// var student_id = $('#student_id').val();
		var district_name = $('#district_name').val();
		// var current_date = $('#current_date').val();
	
		var taluk_name = $('#taluk_name').val();
		var hostel_name = $('#hostel_name').val();
	
	
	
		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var data = {
			// "student_id"	: student_id,
			"district_name": district_name,
			"taluk_name": taluk_name,
			"hostel_name": hostel_name,
			// "current_date":current_date,
			"action": 'datatable',
	
		};
	// alert(data);
	
		init_datatable(table_id,form_name,action,data);
	
	}

