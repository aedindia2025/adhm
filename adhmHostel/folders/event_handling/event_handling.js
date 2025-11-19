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
var table_id 		= "event_datatable";
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') {
	// alert("hii");
	var table = $("#"+table_id);
	var data 	  = {
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
			columns: ':not(:last-child):not(:nth-last-child(-n+3))'
		},
		title: 'Event Handling'
	},
	{
		extend: 'csvHtml5',
		exportOptions: {
			columns: ':not(:last-child):not(:nth-last-child(-n+3))'
		},
		title: 'Event Handling',
		filename: 'event_handling'
	},
	{
		extend: 'excelHtml5',
		exportOptions: {
			columns: ':not(:last-child):not(:nth-last-child(-n+3))'
		},
		title: 'Event Handling',
		filename: 'event_handling'
	},
	{
		extend: 'pdfHtml5',
		exportOptions: {
			columns: ':not(:last-child):not(:nth-last-child(-n+3))'
		},
		title: 'Event Handling',
		filename: 'event_handling'
	},
	{
		extend: 'print',
		exportOptions: {
			columns: ':not(:last-child):not(:nth-last-child(-n+3))'
		},
		title: 'Event Handling'
	}
	]
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

					if (status) {
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



function print(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="uploads/event_handling/images/' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
		'</body></html>';


	var win = window.open("", "", "width=600,height=480,toolbar=no,menubar=no,resizable=yes");

	if (win) {

		win.document.open();

		win.document.write(iframeContent);

		win.document.close();

		var iframe = win.document.getElementById('myIframe');
		iframe.onload = function () {
			var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

			// Prevent right-click context menu inside the iframe
			iframeDoc.addEventListener('contextmenu', function (e) {
				e.preventDefault();
			});

			iframeDoc.addEventListener('keydown', function (e) {
				// Check for specific key combinations
				if ((e.ctrlKey || e.metaKey) && (e.keyCode == 83 || e.keyCode == 67 || e.keyCode == 74 || e.keyCode == 73)) {
					// Prevent default action (e.g., save, copy, downloads, inspect)
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				// Check for F12 key
				if (e.keyCode == 123) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
			});

		};


	} else {
		alert('Please allow popups for this website');
	}
}


