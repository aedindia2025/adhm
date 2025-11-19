$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Leave Approval';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'leave_approval_datatable';
var action 			= "datatable";

function leave_print(unique_id="") {
	// alert(unique_id);
	
	var external_window = window.open('folders/leave_approval_report/print.php?unique_id='+unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// external_window.print();
// external_window.print();
}

function init_datatable(table_id='',form_name='',action='') {

	var table = $("#"+table_id);

	
	// var student_id = $('#student_id').val();
	var district_name = $('#district_name').val();
	// var current_date = $('#current_date').val();

	var taluk_name = $('#taluk_name').val();
	var hostel_name = $('#hostel_name').val()
	// var from_year =$('#from_year').val();

	
	var data 	  = {

		// "student_id"	: student_id,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		// "current_date":current_date,
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
		title: 'Leave Approval Report'
	},
	{
		extend: 'csvHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Leave Approval Report',
		filename: 'leave_approval_report'
	},
	{
		extend: 'excelHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Leave Approval Report',
		filename: 'leave_approval_report'
	},
	{
		extend: 'pdfHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Leave Approval Report',
		filename: 'leave_approval_report'
	},
	{
		extend: 'print',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Leave Approval Report'
	}
	]
	});
}



function leave_filter() {

	var district_name = $('#district_name').val();

	var taluk_name = $('#taluk_name').val();
	var hostel_name = $('#hostel_name').val();



	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var data = {
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"action": 'datatable',
	};
// alert(data);

	init_datatable(table_id,form_name,action,data);

}



// function feedback_creation_cu(unique_id = "") {
//     var internet_status  = is_online();

//     if (!internet_status) {
//         sweetalert("no_internet");
//         return false;
//     }

//     var is_form = form_validity_check("was-validated");

//     if (is_form) {

//         var data 	 = $(".was-validated").serialize();
//         data 		+= "&unique_id="+unique_id+"&action=createupdate";

//         var ajax_url = sessionStorage.getItem("folder_crud_link");
//         var url      = sessionStorage.getItem("list_link");

//         // console.log(data);
//         $.ajax({
// 			type 	: "POST",
// 			url 	: ajax_url,
// 			data 	: data,
// 			beforeSend 	: function() {
// 				$(".createupdate_btn").attr("disabled","disabled");
// 				$(".createupdate_btn").text("Loading...");
// 			},
// 			success		: function(data) {

// 				var obj     = JSON.parse(data);
// 				var msg     = obj.msg;
// 				var status  = obj.status;
// 				var error   = obj.error;

// 				if (!status) {
// 					url 	= '';
//                     $(".createupdate_btn").text("Error");
//                     console.log(error);
// 				} else {
// 					if (msg=="already") {
// 						// Button Change Attribute
// 						url 		= '';

// 						$(".createupdate_btn").removeAttr("disabled","disabled");
// 						if (unique_id) {
// 							$(".createupdate_btn").text("Update");
// 						} else {
// 							$(".createupdate_btn").text("Save");
// 						}
// 					}
// 				}

// 				sweetalert(msg,url);
// 			},
// 			error 		: function(data) {
// 				alert("Network Error");
// 			}
// 		});


//     } else {
//         sweetalert("form_alert");
//     }
// }




// function feedback_creation_delete(unique_id = "") {

// 	var ajax_url = sessionStorage.getItem("folder_crud_link");
// 	var url      = sessionStorage.getItem("list_link");
	
// 	confirm_delete('delete')
// 	.then((result) => {
// 		if (result.isConfirmed) {

// 			var data = {
// 				"unique_id" 	: unique_id,
// 				"action"		: "delete"
// 			}

// 			$.ajax({
// 				type 	: "POST",
// 				url 	: ajax_url,
// 				data 	: data,
// 				success : function(data) {

// 					var obj     = JSON.parse(data);
// 					var msg     = obj.msg;
// 					var status  = obj.status;
// 					var error   = obj.error;

// 					if (!status) {
// 						url 	= '';
						
// 					} else {
// 						init_datatable(table_id,form_name,action);
// 					}
// 					sweetalert(msg,url);
// 				}
// 			});

// 		} else {
// 			// alert("cancel");
// 		}
// 	});
// }


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

// var foo = "09/22/2011";
// var arr = foo.split("/");
// alert(arr[0])
// alert(arr[1])
// alert(arr[2])



