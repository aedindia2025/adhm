$(document).ready(function () {
	// var table_id 	= "hostel_type_datatable";
	init_datatable(table_id,form_name,action);
	
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Hostel Type';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'maintanance_datatable';
var action 			= "datatable";
function filter_records(){
    
    init_datatable(table_id,form_name,action);
}

function init_datatable(table_id='',form_name='',action='') 
{
    
    var district_name = $("#district_name").val();
    var taluk_name = $("#taluk_name").val();
    var hostel_name = $("#hostel_name").val();
    var academic_year = $("#academic_year").val();


	var table = $("#"+table_id);
    
	var data 	  = {
        "district_name"    : district_name,
        "taluk_name"    : taluk_name,
        "hostel_name"    : hostel_name,
        "academic_year"    : academic_year,
		"action"	: action, 
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
	
		"ajax"		: {
            destroy : true,
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
		title: 'Maintenance'
	},
	{
		extend: 'csvHtml5',
		exportOptions: {
			columns: ':not(:last-child):not(:nth-last-child(-n+3))'
		},
		title: 'Maintenance',
		filename: 'maintenance'
	},
	{
		extend: 'excelHtml5',
		exportOptions: {
			columns: ':not(:last-child):not(:nth-last-child(-n+3))'
		},
		title: 'Maintenance',
		filename: 'maintenance'
	},
	{
		extend: 'pdfHtml5',
		exportOptions: {
			columns: ':not(:last-child):not(:nth-last-child(-n+3))'
		},
		title: 'Maintenance',
		filename: 'maintenance'
	},
	{
		extend: 'print',
		exportOptions: {
			columns: ':not(:last-child):not(:nth-last-child(-n+3))'
		},
		title: 'Maintenance'
	}
	]
        });
    
}

function maintanance_cu(unique_id ="") {
    var internet_status  = is_online();
    var data = new FormData();
    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }
    var hostel_id = $("#hostel_id").val();
    var hostel_name = $("#hostel_name").val();
    var district_id = $("#district_id").val();
	
    var taluk_id = $("#taluk_id").val();
    var warden_name = $("#warden_name").val();
    var warden_id = $("#warden_id").val();
    var maintanance_no = $("#maintanance_no").val();
    var asset_category = $("#asset_category").val();
    var asset_name = $("#asset_name").val();
    
    var description = $("#description").val();
    var spend_amount = parseInt($("#spend_amount").val());

	var file_name = $("#file_name").val();

    var unique_id = $("#unique_id").val();



    // var is_form = form_validity_check("was-validated");
    var image_s = $("#test_file");
    
    
            if (image_s != '') {
                for (var i = 0; i < image_s.length; i++) {
                    data.append("test_file", document.getElementById('test_file').files[i]);
    
                }
            } else {
                data.append("test_file", '');
            }


        		

        data.append("hostel_id", hostel_id);
        data.append("hostel_name", hostel_name);
        data.append("district_id", district_id);
        data.append("taluk_id", taluk_id);
        data.append("warden_name", warden_name);
        data.append("warden_id", warden_id);
        data.append("maintanance_no", maintanance_no);
        data.append("asset_category", asset_category);
        data.append("asset_name", asset_name);
       
        data.append("description", description);
        data.append("spend_amount", spend_amount);
		data.append("unique_id", unique_id);
        
        data.append("action", "createupdate");

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url      = sessionStorage.getItem("list_link");

		if((image_s != '') || (file_name !='')){

        // console.log(data);
        $.ajax({
			type 	: "POST",
			url 	: ajax_url,
			data 	: data,
            cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			beforeSend 	: function() {
				$(".createupdate_btn").attr("disabled","disabled");
				$(".createupdate_btn").text("Loading...");
			},
			success		: function(data) {

				var obj     = JSON.parse(data);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;

				if (!status) {
					url 	= '';
                    $(".createupdate_btn").text("Error");
                    // console.log(error);
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

function maintanance_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url      = sessionStorage.getItem("list_link");
	var csrf_token = $('#csrf_token').val();
	
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

function get_asset_name() {

    var asset_category = $("#asset_category").val();


var ajax_url = sessionStorage.getItem("folder_crud_link");

    if (asset_category) {
        var data = {
            "asset_category": asset_category,
            "action": "get_asset_name",
        }

        $.ajax({
            type: "POST",
            url: "folders/maintanance/crud.php",
            data: data,
            success: function (data) {

                if (data) {
                    $("#asset_name").html(data);
                }
            }
        });
    }
}

function get_asset_count() {

    var asset_name = $("#asset_name").val();
// alert(asset_name);

var ajax_url = sessionStorage.getItem("folder_crud_link");

    if (asset_name) {
        var data = {
            "asset_name": asset_name,
            "action": "get_asset_count"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {
// alert(data);
var obj     = JSON.parse(data);
					var quantity     = obj.quantity;

                if (quantity) {

                    $("#existing_count").val(quantity);
                }
            }
        });
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

function check_count(){

    var existing_count = parseInt($("#existing_count").val());
    var defect_count = parseInt($("#defect_count").val());
    
    if(defect_count <= existing_count){
        
        $("#defect_count").val(defect_count);
    }else{
        alert("Defect Count Must Be lower Than Existing Count");
        $("#defect_count").val('');
    }
}

function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../adhmHostel/uploads/maintanance/' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
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
	var pdfUrl = "../adhmHostel/uploads/maintanance/" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "../adhmHostel/uploads/maintanance/" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

// function print_pdf(file_name)
// 	{
		
// 		 onmouseover=window.open('../adhmHostel/uploads/maintanance/' + file_name,'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// 	}
    
//     function print_view(file_name)
//     {
//        onmouseover= window.open('../adhmHostel/uploads/maintanance/'+file_name,'onmouseover','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     } 

    function view_app(unique_id="") {
	
	
		var external_window = window.open('folders/maintanance/view_app.php?unique_id='+unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	// external_window.print();
	// external_window.print();
	}

