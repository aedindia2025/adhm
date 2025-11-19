$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	transfer_datatable("transfer_datatable", form_name, "transfer_datatable");
	approval_datatable("approval_datatable", form_name, "approval_datatable");
	
});
var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");
var form_name = 'Student Transfer';
var form_header = '';
var form_footer = '';
var table_name = '';





function transfer_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);

	
	var acc_year = $('#acc_year').val();


	var data = {
	
		"acc_year" : acc_year,
		"action": action,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	//  table.DataTable().destroy();

	var datatable = table.DataTable({
		searching: false,
		responsive: false,
		
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		dom: 'Bfrtip',
		buttons: [
		
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Transfer',
			filename: 'Student Transfer'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Transfer',
			filename: 'Student Transfer'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Transfer'
		}
		]
	});
}


function go_filter(){

	var acc_year = $('#acc_year').val();

	var active_tab = $('.tab-pane.active').attr('id'); // Will return 'basictab1' or 'basictab2'

    if (active_tab === 'basictab1') {
        transfer_datatable('transfer_datatable', '', 'transfer_datatable');
    } else if (active_tab === 'basictab2') {
        approval_datatable('approval_datatable', '', 'approval_datatable');
    }

}




function approval_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);

	
	if ($.fn.DataTable.isDataTable(table)) {
    table.DataTable().clear().destroy();
}

	var acc_year = $('#acc_year').val();


	var data = {
	
		"acc_year" : acc_year,
		"action": action,
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	//  table.DataTable().destroy();
	var datatable = table.DataTable({
		searching: false,
		responsive: false,

		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		dom: 'Bfrtip',
		buttons: [
		
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Transfer',
			filename: 'Student Transfer'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Transfer',
			filename: 'Student Transfer'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Transfer'
		}
		]
	});
}




function student_transfer_cu(unique_id = "") {
	

	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}


	var from_district = $('#from_district').val();
	var from_taluk = $('#from_taluk').val();
	var from_hostel = $('#from_hostel').val();

	var to_district = $('#to_district').val();

	var to_taluk = $('#to_taluk').val();

	var to_hostel  = $('#to_hostel').val();
	


	
	if(from_hostel === to_hostel){

		Swal.fire({
			icon:'warning',
			 title: 'Invalid Selection',
			text:'From Hostel and To Hostel cannot be same',
			 showConfirmButton: true,
            timer: 2000,
		});
		return;
	}

	 
	

	var is_form = form_validity_check("was-validated");

	if (is_form) {

		var data = $(".was-validated").serialize();
		data += "&unique_id=" + unique_id + "&action=createupdate&from_district=" + from_district + "&from_taluk=" + from_taluk + "&from_hostel=" + from_hostel;

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = sessionStorage.getItem("list_link");

		// console.log(data);
		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			beforeSend: function () {
				$(".createupdate_btn").attr("disabled", "disabled");
				$(".createupdate_btn").text("Loading...");
			},
			success: function (data) {

				var obj = JSON.parse(data);
				var msg = obj.msg;
				var status = obj.status;
				var error = obj.error;

				
				if (msg == "form_alert") {
					sweetalert("form_alert");
					$(".createupdate_btn").removeAttr("disabled", "disabled");
							if (unique_id) {
								$(".createupdate_btn").text("Update");
							} else {
								$(".createupdate_btn").text("Save");
							}
				} else {
					if (!status) {
						url = '';
						$(".createupdate_btn").text("Error");
						console.log(error);
					} 
						if (msg == "already") {
							// Button Change Attribute
							url = '';

							$(".createupdate_btn").removeAttr("disabled", "disabled");
							if (unique_id) {
								$(".createupdate_btn").text("Update");
							} else {
								$(".createupdate_btn").text("Save");
							}
						}
					
				}
				sweetalert(msg, url);
			},
			error: function (data) {
				alert("Network Error");
			}
		});


	} else {
		sweetalert("form_alert");
	}
}

function student_transfer_delete(unique_id = "",std_reg_no = "") {
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");
	var csrf_token = $("#csrf_token").val();

	confirm_delete('delete')
		.then((result) => {
			if (result.isConfirmed) {
				var data = {
					"unique_id"		: unique_id,
					"std_reg_no" 	: std_reg_no,
					"csrf_token" 	: csrf_token,
					"action" 		: "delete"
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
	               			transfer_datatable("transfer_datatable", form_name, "transfer_datatable");
						}
						sweetalert(msg, url);
					}
				});
			} else {
				// alert("cancel");
			}
		});
}




function get_taluk(){

	var to_district = $('#to_district').val();

	var data = "to_district=" + to_district + "&action=get_taluk";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		url:ajax_url,
		data:data,
		type:"POST",

		success:function(data){
			if(data){
				$('#to_taluk').html(data);
			}
		}
	});
}

function get_hostel(){
	var to_taluk = $('#to_taluk').val();

	var gender_type = $('#gender_type').val();

	var hostel_type  = $('#hostel_type').val();


var data = "to_taluk=" + to_taluk + "&gender_type=" + gender_type + "&hostel_type=" + hostel_type +"&action=get_hostel";
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#to_hostel").html(data);
			}
		}
	});
}




function get_std_name() {

	var std_id = $("#std_id").val();

	// alert(asset_category);
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	if (std_id) {
		var data = {
			"std_id": std_id,
			"action": "get_std_name",
		}

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function (data) {
				var obj = JSON.parse(data);
				var student_name = obj.student_name;
				var std_reg_no = obj.std_reg_no;
				// if (data) {
				$("#std_name").val(student_name);
				$("#std_reg_no").val(std_reg_no);
				// }
			}
		});
	}
}


