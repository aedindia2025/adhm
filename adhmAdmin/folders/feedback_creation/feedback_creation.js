$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Product Category';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'feedback_creation_datatable';
var action 			= "datatable";

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
 "searching": false,
        dom: 'Bfrtip',
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Feedback Creation'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Feedback Creation',
			filename: 'feedback_creation'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Feedback Creation',
			filename: 'feedback_creation'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Feedback Creation',
			filename: 'feedback_creation'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Feedback Creation'
		}
		],
          "autoWidth": false,
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



function feedback_creation_delete(unique_id = "") {

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



