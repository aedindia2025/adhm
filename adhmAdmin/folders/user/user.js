$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
	// get_warehouse_name();
	team_users_div($("#is_team_head").prop("checked"));
});



var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'User Creation';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'user_creation_datatable';
var action 			= "datatable";

function hashPassword(password) {
	return CryptoJS.SHA256(password).toString(CryptoJS.enc.Hex);
  }

function init_datatable(table_id='',form_name='',action='') {

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
			title: 'User'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'User',
			filename: 'user'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'User',
			filename: 'user'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'User',
			filename: 'user'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'User'
		}
	]
	});
}

function user_cu(unique_id = "") {

    var internet_status  = is_online();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }

    var password 		= $("#password").val();
	var con_password 	= $("#confirm_password").val();
	var hashedPassword = hashPassword(password);

	if (password !== con_password) {
		sweetalert("custom","","","Password Dosen't Match");
		return false;
	}

    var is_form = form_validity_check("was-validated");

    if (is_form) {

        var data 	 = $(".was-validated").serialize();
		data 		+= "&hashedPassword="+hashedPassword+"&unique_id="+unique_id+"&action=createupdate";


        var ajax_url = sessionStorage.getItem("folder_crud_link");
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

function user_delete(unique_id = "") {

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

function get_warehouse_name() {
	var branch   = $('#branch').val();
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    if (branch) {
        var data = {
            "branch": branch,
            "action": "warehouse"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function(data) {

                if (data) {
                    $("#warehouse").html(data);

                    var warehouse = $("#edit_warehouse").val();

                    if (warehouse) {

                        $("#warehouse").val(warehouse).trigger('change');

                        $("#edit_warehouse").val('');
                    }
                }
            }
        });
    }
}


function get_under_users (under_user = "") {

$("#under_user_name").html('');
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	if (under_user) {
		var data = {
			"under_user" 	: under_user,
			"action"	: "user_options"
		}

		$.ajax({
			type 	: "POST",
			url 	: ajax_url,
			data 	: data,
			success : function(data) {

				if (data) { 
					$("#under_user_name").html(data);
				}

			}
		});
	}
}


  
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function get_under_user_ids()
{
	var under_user= $('#under_user_name').val();
	$('#under_user').val(under_user);
}

function get_team_users_ids()
{
	var under_user= $('#team_users_name').val();
	$('#team_users').val(under_user);
}

// $(document).ready(function () {
// 	$("#confirm_password").change(function() { 
//    var password = $("#password").val();
//    var confirmPassword = $("#confirm_password").val();

// 	    if (password !== confirmPassword)
// 	    {
// 	       alert("Confirm Password Doesn't match with Password");
// 	       $("#confirm_password").focus();
// 	    }
//     });
// });



// Get Group Names Based On Category Selection
function get_mobile_no(staff_id = "") {


	var ajax_url = sessionStorage.getItem("folder_crud_link");


	if (staff_id) {
		var data = {
			"staff_id": staff_id,
			"action": "mobile"
		}

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function (data) {

				if (data) {
					// $("#phone_no").html(data);
				document.getElementById('phone_no').value = data;

				}

				
			}
		});
	}
}

function team_users_div(this_val = '') {
	if (this_val) {
		$(".team_users_class").removeClass("d-none");
	} else {
		$(".team_users_class").addClass("d-none");
		$("#team_users_name").val(null);
	}
}
