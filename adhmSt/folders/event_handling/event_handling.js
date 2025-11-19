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
var table_id 		= 'event_handling_datatable';
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') {
	// alert("hii");
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
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Event Handling'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Event Handling',
			filename: 'event_handling'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Event Handling',
			filename: 'event_handling'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Event Handling',
			filename: 'event_handling'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Event Handling'
		}
	]
	});
}

function create(id){
	
	
  	var status = document.getElementById('status').value;
	
	var data = "id=" + id + "&status=" + status ;
	data += "&action=approval_create";
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		
		success: function(data) {
			var obj     = JSON.parse(data);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;
				sweetalert(msg,url);

			},
		error: function(data) {
			alert("Network Error");
		}
	});
	
}
function approval_create(sno){
	
	

var s_no = $('#s_no').val();
// alert(s_no);
	var internet_status = is_online();
	var unique_id = document.getElementById('unique_id').value;
	
	var status = document.getElementById('status').value;
	var session_user_id = document.getElementById('session_user_id').value;
	
	var data = "unique_id=" + unique_id + "&status=" + status +"&session_user_id=" + session_user_id ;
	data += "&action=approval_create";
	// print_r(data);
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		
		success: function(data) {
			var obj     = JSON.parse(data);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;
				sweetalert(msg,url);

			},
		error: function(data) {
			alert("Network Error");
		}
	});
}
function event_handling_delete(unique_id = "") {

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
       onmouseover= window.open('uploads/sample-pdf.pdf','onmouseover','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    }  


