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
var table_id 		= 'hostel_type_datatable';
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') 
{
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
                    title: 'Hostel Type'
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    },
                    title: 'Hostel Type',
                    filename: 'hostel_type'
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    },
                    title: 'Hostel Type',
                    filename: 'hostel_type'
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    },
                    title: 'Hostel Type',
                    filename: 'hostel_type'
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    },
                    title: 'Hostel Type'
                }
            ]
        });
    
}

function hostel_type_cu(unique_id = "") {
    var internet_status  = is_online();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }

    var is_form = form_validity_check("was-validated");

    if (is_form) {

        var data 	 = $(".was-validated").serialize();
        data 		+= "&unique_id="+unique_id+"&action=createupdate";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
		var media_url = "https://nallosaims.tn.gov.in/adw_biometric/folders/hostel_type/crud.php";
        var url      = sessionStorage.getItem("list_link");

        // console.log(data);
        $.ajax({
			type 	: "POST",
			url 	: ajax_url,
			data 	: data,
			beforeSend 	: function() {
				$(".createupdate_btn").attr("disabled","disabled");
				$(".createupdate_btn").text("Loading...");
			},
			success		: function(response) {

				var obj     = JSON.parse(response);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;
				var app_unique_id = obj.data?.unique_id; // Get unique_id from application server

                if (status) {
                    if (msg !== "already") {
                        // Forward to media server if successful
                        var mediaData = data + "&unique_id=" + app_unique_id; // Append app unique_id
                        $.ajax({
                            type: "POST",
                            url: media_url,
                            data: mediaData,
                            success: function () {
                                console.log("Operation synced with media server.");
                            },
                            error: function () {
                                console.error("Failed to sync with media server.");
                            }
                        });
                    }

                    sweetalert(msg, url);
                } else {
                    $(".createupdate_btn").text("Error");
                    console.error(error);
                }

                $(".createupdate_btn").removeAttr("disabled");
                $(".createupdate_btn").text(unique_id ? "Update" : "Save");
            },
            error: function () {
                alert("Network Error");
            }
        });
    } else {
        sweetalert("form_alert");
    }
}


function hostel_type_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
    var media_url = "https://nallosaims.tn.gov.in/adw_biometric/folders/hostel_type/crud.php";
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
				success : function(response) {

					var obj     = JSON.parse(response);
					var msg     = obj.msg;
					var status  = obj.status;
					var error   = obj.error;

					if (status) {
                        // Forward to media server if successful
                        $.ajax({
                            type: "POST",
                            url: media_url,
                            data: data,
                            success: function () {
                                console.log("Operation synced with media server.");
                            },
                            error: function () {
                                console.error("Failed to sync with media server.");
                            }
                        });

                        init_datatable(table_id, form_name, action);
                    } else {
                        console.error(error);
                    }

                    sweetalert(msg, url);
                },
                error: function () {
                    alert("Network Error");
                }
            });
        }
    });
}

