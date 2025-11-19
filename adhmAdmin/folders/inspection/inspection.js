$(document).ready(function () {
	// var table_id 	= "assembly_constituency_datatable";
	init_datatable(table_id, form_name, action);
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Inspection';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'inspection_datatable';
var action = "datatable";

function init_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);
	var district_name = $('#district_id').val();
    var taluk_name = $('#taluk_id').val();
    var hostel_name = $('#hostel_id').val();

	var data = {
		"district_name": district_name,
		"taluk_name" : taluk_name,
		"hostel_name" : hostel_name,
		"action": action,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({

		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
"searching" : false,
		dom: 'Bfrtip',
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Inspection'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Inspection',
			filename: 'inspection'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Inspection',
			filename: 'inspection'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Inspection',
			filename: 'inspection'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child):not(:nth-last-child(-n+2))'
			},
			title: 'Inspection'
		}
		]
	});

}

function leave_print(unique_id="") {
	// alert(unique_id);
	
	var external_window = window.open('folders/inspection/print.php?unique_id='+unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// external_window.print();
// external_window.print();
}

function inspection_cu(unique_id = "") {
	
	var internet_status = is_online();
    var data = new FormData();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }
	var user_name = document.getElementById('user_name').value;
    var user_type = document.getElementById('user_type').value;
    var inspection_date = document.getElementById('inspection_date').value;
	var inspection_id = document.getElementById('inspection_id').value;
    var hostel_name = document.getElementById('hostel_name').value;
    var district_name = document.getElementById('district_name').value;
    var description = document.getElementById('description').value; 
    var taluk_name = document.getElementById('taluk_name').value;
    var is_active = document.getElementById('is_active').value;

// alert(inspection_id);
// alert(district_name);
// alert(taluk_name);

    var data = new FormData();

	var image_s = $("#test_file");


    if (image_s != '') {
			for (var i = 0; i < image_s.length; i++) {
				data.append("test_file", document.getElementById('test_file').files[i]);

			}
		} else {
			data.append("test_file", '');
		}
	

   
        var action = "createupdate";

		data.append("user_name", user_name);
        data.append("user_type", user_type);
        data.append("inspection_date", inspection_date);
		data.append("inspection_id", inspection_id);
        data.append("hostel_name", hostel_name);
        data.append("district_name", district_name);
		data.append("taluk_name", taluk_name);
        data.append("description", description);
        data.append("unique_id", unique_id);
        data.append("is_active", is_active);
        data.append("action", "createupdate");

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = sessionStorage.getItem("list_link");

        
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

function get_taluk(){
    

	var district_name = $('#district_id').val();

	
	
	var data = "district_id=" + district_name + "&action=district_name";
	
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#taluk_id").html(data);
			}
		}
	});
	
	}
	 
	
function get_hostel(){


	var taluk_name = $('#taluk_id').val();
	
	var data = "taluk_id=" + taluk_name + "&action=get_hostel_by_taluk_name";
	
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
	
	
			if (data) {
				$("#hostel_id").html(data);
			}
		}
	});
	
	}

	function go_filter() {

		
		var district_name = $('#district_id').val();
		var taluk_name = $('#taluk_id').val();
		var hostel_name = $('#hostel_id').val();
	
	
	
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
	



// function print_pdf(file_name)
// 	{
		
// 		 onmouseover=window.open('../uploads/inspection/' + file_name, '_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// 	}
    
//     function print_view(file_name)
//     {
//        onmouseover= window.open('../uploads/inspection/'+file_name,'_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     } 


	function print_view(file_name) {
		var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
			'<iframe id="myIframe" src="../uploads/inspection/' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
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
	
	function print_pdf(file_name) {
		var pdfUrl = "../uploads/inspection/" + file_name;
		var link = document.createElement("a");
		link.href = pdfUrl;
		link.download = file_name;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}



function inspection_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");

	confirm_delete('delete')
		.then((result) => {
			if (result.isConfirmed) {

				var data = {
					"unique_id": unique_id,
					"action": "delete"
				}

				$.ajax({
					type: "POST",
					url: ajax_url,
					data: data,
					success: function (data) {

						var obj = JSON.parse(data);
						var msg = obj.msg;
						var status = obj.status;
						var error = obj.error;

						if (!status) {
							url = '';

						} else {
							init_datatable(table_id, form_name, action);
						}
						sweetalert(msg, url);
					}
				});

			} else {
				// alert("cancel");
			}
		});
}
//hostel_uniqueid






//hostel to taluk




function get_taluk_name() {

	var district_name = $("#district_name").val();


	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");
	
	if (district_name) {
		var data = {
			"district_name": district_name,
			"action": "get_taluk_name"
		}

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
}


function get_hostel_name() {

	var taluk_name = $("#taluk_name").val();

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");
	
	if (district_name) {
		var data = {
			"taluk_name": taluk_name,
			"action": "get_hostel_name"
		}

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
}