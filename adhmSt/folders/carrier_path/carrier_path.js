$(document).ready(function () {

	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);
	// student_details();
	get_job_div();
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Feedback';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'carrier_path_datatable';
var action = "datatable";




function init_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);
	var data = {
		"action": action,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		dom: 'Bfrtip',
		searching: false,
	buttons: [{
		extend: 'copyHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Career Path'
	},
	{
		extend: 'csvHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Career Path',
		filename: 'career_path'
	},
	{
		extend: 'excelHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Career Path',
		filename: 'career_path'
	},
	{
		extend: 'pdfHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Career Path',
		filename: 'career_path'
	},
	{
		extend: 'print',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Career Path'
	}
]
	});
}

function carrier_path_cu(unique_id = "", job = "", course = "") {

	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}

	// var job = $('#job').val();
	// var course = $('#course').val();
	var is_form = form_validity_check("was-validated");

	if (is_form) {

		var data = $(".was-validated").serialize();
		data += "&unique_id=" + unique_id + "&action=createupdate";

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

				if (!status) {
					url = '';
					$(".createupdate_btn").text("Error");
					console.log(error);
				} else {
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

function carrier_path_delete(unique_id = "") {

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



function student_details(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");

	var student_name = $('#student_name').val();


	var data = {
		"unique_id": unique_id,
		// "student_reg_no": student_reg_no,
		// "std_name"	: 	student_name,

		"action": "students_details"
	}

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {

			var obj = JSON.parse(data);
			//  var student_details = obj.student_details;
			var student_name = obj.student_name;
			//  var student_id = obj.std_reg_no;



			$('#student_name').html(student_name);
			//  $('#student_id').html(student_id);

		}
	});
}


function student_id() {
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");

	var student_name = $('#student_name').val();

	var data = {
		"student_name": student_name,
		"action": "students_details",
	}


	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,

		success: function (data) {

			var obj = JSON.parse(data);

			var reg_no = obj.std_reg_no;
			alert(reg_no);
			if (reg_no) {

				$('#std_reg_no').val(reg_no);
			}


		}

	});

}		// } else {
// 	// alert("cancel");
// }


function get_std_name() {
	// alert();
	var student_id = $("#std_reg_no").val();

	// alert(asset_category);
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	if (student_id) {
		var data = {
			"student_id": student_id,
			"action": "get_std_name",
		}

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function (data) {
				var obj = JSON.parse(data);
				var student_name = obj.student_name;
				// if (data) {
				$("#student_name").val(student_name);
				// }
			}
		});
	}
}

function get_job_div() {

	var employment_course = $('#employment_course').val();
	var textBoxContainer = document.getElementById("textBoxContainer");
	var course = document.getElementById("course_div");

	// Check if the selected value requires showing the text box
	if (employment_course === "employment") {
		textBoxContainer.style.display = "block";
		$("#course").val('');
		course.style.display = "none"; // Show the text box container
	}


	else if (employment_course === "course") {
		course.style.display = "block";
		$("#job").val('');
		textBoxContainer.style.display = "none";

	}
	else {
		textBoxContainer.style.display = "none";
		course.style.display = "none";
		$("#course").val('');
		$("#job").val('');
	}
}

function carrier_print(unique_id = "") {
	// alert(unique_id);

	var external_window = window.open('folders/carrier_path/print.php?unique_id=' + unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	external_window.print();
	// external_window.print();
}